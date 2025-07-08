<?php

namespace EmvigoTech\ShippingLabel\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Api\OrderStatusHistoryRepositoryInterface;
use Magento\Shipping\Model\ShipmentNotifier;
use Magento\Sales\Model\Order\ShipmentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Model\Order\Pdf\Shipment;
use Magento\Framework\Mail\Template\TransportBuilder;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Translate\Inline\StateInterface;

class ShippingLabelObserver implements ObserverInterface
{
    protected $objectManager;
    protected $shipmentRepository;
    protected $orderStatusHistoryRepository;
    protected $shipmentNotifier;
    protected $shipmentFactory;
    protected $transportBuilder;
    protected $storeManager;
    protected $pdfShipment;
    protected $scopeConfig;
    protected $appState;
    protected $inlineTranslation;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        ShipmentRepositoryInterface $shipmentRepository,
        OrderStatusHistoryRepositoryInterface $orderStatusHistoryRepository,
        ShipmentNotifier $shipmentNotifier,
        ShipmentFactory $shipmentFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        Shipment $pdfShipment,
        ScopeConfigInterface $scopeConfig,
        State $appState,
        StateInterface $inlineTranslation
    ) {
        $this->objectManager = $objectmanager;
        $this->shipmentRepository = $shipmentRepository;
        $this->orderStatusHistoryRepository = $orderStatusHistoryRepository;
        $this->shipmentNotifier = $shipmentNotifier;
        $this->shipmentFactory = $shipmentFactory;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->pdfShipment = $pdfShipment;
        $this->scopeConfig = $scopeConfig;
        $this->appState = $appState;
        $this->inlineTranslation = $inlineTranslation;
    }

    public function execute(EventObserver $observer)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/shipping_label.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
       
        $shipment = $this->_prepareShipment($invoice);
        $shipment->register();
        if ($shipment) {
            $shipment->getOrder()->setIsInProcess(true);
            $this->objectManager->create(
                \Magento\Framework\DB\Transaction::class
            )->addObject(
                $shipment
            )->addObject(
                $shipment->getOrder()
            )->save();

            $this->shipmentRepository->save($shipment);

            $history = $order->addCommentToStatusHistory(
                "shipment created successfully.",
                'complete',
                true // no email is triggered for this
            );
            $this->orderStatusHistoryRepository->save($history);

            // Send packing slip to custom email
            $this->sendPackingSlipEmail($shipment);
        }
    }

    protected function sendPackingSlipEmail($shipment)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/shipping_label.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        try {
            $order = $shipment->getOrder();
            $store = $this->storeManager->getStore($order->getStoreId());
            
            // Check if area code is already set before setting it
            if (!$this->appState->getAreaCode()) {
                $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
            }
            $this->inlineTranslation->suspend();
            
            // Custom email address where you want to send the packing slip
            $customEmail = $this->scopeConfig->getValue('shipping_label/general/shipping_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId()); 

            $billingAddress = $order->getBillingAddress();
            $storeId = $order->getStoreId();
            
            switch ($storeId) {
                case 1:
                    $content = $this->scopeConfig->getValue('shipping_label/general/shipping_content_english', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
                    break;
                case 3:
                    $content = $this->scopeConfig->getValue('shipping_label/general/shipping_content_french', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
                    break;
                case 4:
                    $content = $this->scopeConfig->getValue('shipping_label/general/shipping_content_german', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
                    break;
                case 5:
                    $content = $this->scopeConfig->getValue('shipping_label/general/shipping_content_italian', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
                    break;
                default:
                    $content = $this->scopeConfig->getValue('shipping_label/general/shipping_content_english', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
                    break;
            }
            $packingSlip = $this->generatePackingSlip($shipment);
            $pdfContent = file_get_contents($packingSlip);
            $fileName = basename($packingSlip);
            $logger->info('Packing slip generated successfully');
            $logger->info('Packing slip path: ' . $packingSlip);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('emvigo_shipping_label_template')
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $store->getId(),
                ])
                ->setTemplateVars([
                    'order' => $order,
                    'order_id' => $order->getIncrementId(),
                    'shipment' => $shipment,
                    'shipment_id' => $shipment->getIncrementId(),
                    'comment' => '',
                    'payment_html' => '',
                    'store' => $store,
                    'packing_slip' => $packingSlip,
                    'content' => $content
                ])
                ->setFrom([
                    'name' => $store->getConfig('trans_email/ident_general/name'),
                    'email' => $store->getConfig('trans_email/ident_general/email')
                ])
                ->addTo($customEmail)
                ->setReplyTo($store->getConfig('trans_email/ident_general/email'))
                ->getTransport();

            $message = $transport->getMessage();

            // Create HTML part from body
            $htmlPart = new MimePart($message->getBodyText());
            $htmlPart->type = Mime::TYPE_HTML;
            

            // Create PDF attachment
            $attachment = new MimePart($pdfContent);
            $attachment->type = 'application/pdf';
            $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
            $attachment->encoding = Mime::ENCODING_BASE64;
            $attachment->filename = $fileName;

            // Combine parts
            $body = new MimeMessage();
            $body->setParts([$htmlPart, $attachment]);

            $message->setBody($body);

            $transport->sendMessage();
            
            // Resume inline translation
            $this->inlineTranslation->resume();
            
        } catch (\Exception $e) {
            $logger->err('Failed to send packing slip email: ' . $e->getMessage());
            // Resume inline translation in case of error
            $this->inlineTranslation->resume();
        }
    }

    protected function _prepareShipment($invoice)
    {
        $invoiceData = [];
        $itemArr = [];
        if (!isset($invoiceData['items']) || empty($invoiceData['items'])) {
            $orderItems = $invoice->getOrder()->getItems();
            foreach ($orderItems as $item) {
                $itemArr[$item->getId()] = (int)$item->getQtyOrdered();
            }
        }
        $shipment = $this->shipmentFactory->create(
            $invoice->getOrder(),
            isset($invoiceData['items']) ? $invoiceData['items'] : $itemArr,
            null
        );
        if (!$shipment->getTotalQty()) {
            return false;
        }

        return $shipment;
    }

    public function generatePackingSlip($shipment)
    {
        $pdf = $this->pdfShipment->getPdf([$shipment]);
        $pdfContent = $pdf->render();
        
        // Generate unique filename with timestamp
        $timestamp = date('Y-m-d_H-i-s');
        $orderId = $shipment->getOrder()->getRealOrderId();
        $filename = "packing_slip_{$orderId}_{$timestamp}.pdf";
        
        // Save PDF to var directory
        $pdfPath = BP . '/var/shipping_labels/' . $filename;
        
        // Create directory if it doesn't exist
        $directory = dirname($pdfPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Write PDF content to file
        file_put_contents($pdfPath, $pdfContent);
        
        return $pdfPath;
    }
}
