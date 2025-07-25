<?php

namespace Lof\Affiliate\Observer;

use Magento\Framework\Event\ObserverInterface;
// use Magento\Sales\Model\Order;
use Magento\Framework\Stdlib\DateTime\Timezone;

class PlaceAfter implements ObserverInterface
{
    CONST EMAILIDENTIFIER = 'sent_mail_after_order_complete';

    CONST EMAIL_IDENTIFIER_CANCEL = 'sent_mail_after_order_cancel';

    protected $_resource;
    protected $_request;
    protected $logger;
    protected $_objectManager;

    // @param Lof\Affiliate\Helper\Data
    protected $_helper;
    // @var \Magento\Framework\Stdlib\DateTime\Timezone
    protected $_stdTimezone;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Lof\Affiliate\Helper\Data $helper,
        Timezone $stdTimezone
    )
    {
        $this->_request = $context->getRequest();
        $this->logger = $logger;
        $this->_resource = $resource;
        $this->_objectManager = $context->getObjectManager();
        $this->_helper = $helper;
        $this->_stdTimezone = $stdTimezone;
    }

    /**
     * Add New Layout handle
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		
		
        if (!$this->_helper->getConfig('general_settings/enable'))
            return;
        $order = $observer->getEvent()->getOrder();
        $priceOrder = $order->getBaseSubtotal();
        $orderStt = $order->getStatus();
        $orderId = $order->getEntityId();
        $complete_order_status = $this->_helper->getConfig('general_settings/order_status');
        $complete_order_status = $complete_order_status ? $complete_order_status : 'complete';

        if ($orderStt == $complete_order_status) {
            $dataTransaction = [
                'order_id' => $orderId,
                'increment_id' => $order->getIncrementId(),
                'transaction_stt' => $complete_order_status,
                'order_status' => $complete_order_status,
                'is_active' => 1
            ];
            # get orderData in transaction table
            $ordersData = $this->_helper->getDataOrderAffiliate($order->getIncrementId());
            if (!empty($ordersData)) {
                foreach ($ordersData as $orderData) {
                    $checkSpam = $this->_helper->checkSpam($orderData);
                    if ($checkSpam) {
                        $dataTransaction['transaction_stt'] = 'pending';
                        $dataTransaction['reason'] = __("Suspected spam transaction");
                        $this->_helper->updateTransactionOrder($dataTransaction);
                        break;
                    }
                    $affiliate_code = $orderData['affiliate_code'];
                    $commission_total = $orderData['commission_total'];

                    $this->_helper->updateTransactionOrder($dataTransaction);

                    $this->_helper->saveDataCommissionComplete($affiliate_code, $priceOrder, $commission_total);
                }
            }
        }
        // end
    }
}
