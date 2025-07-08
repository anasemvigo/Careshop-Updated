<?php

namespace Amasty\PDFCustom\Model\Order\Html;

use Magento\Framework\DataObject;

class Shipment extends AbstractTemplate
{
    /**
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     *
     * @return string
     */
    public function getHtml($shipment)
    {
        $order = $this->orderRepository->get($shipment->getOrderId());
        $templateId = $this->templateRepository->getShipmentTemplateId(
            $order->getStoreId(),
            $order->getCustomerGroupId()
        );
        if (!$templateId) {
            return '';
        }
        $vars = [
            'order' => $order,
            'shipment' => $shipment,
            'comment' => $shipment->getCustomerNoteNotify() ? $shipment->getCustomerNote() : '',
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order)
        ];
        $transportObject = new DataObject($vars);
        $this->eventManager->dispatch(
            'email_shipment_set_template_vars_before',
            ['sender' => $this, 'transport' => $transportObject->getData(), 'transportObject' => $transportObject]
        );

        $template = $this->templateFactory->get($templateId)
            ->setVars($transportObject->getData())
            ->setOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $shipment->getStoreId()
                ]
            );

        return $template->processTemplate();
    }
}
