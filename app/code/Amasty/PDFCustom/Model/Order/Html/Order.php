<?php

namespace Amasty\PDFCustom\Model\Order\Html;

use Magento\Framework\DataObject;

class Order extends AbstractTemplate
{
    /**
     * @param \Magento\Sales\Model\Order $order
     *
     * @return string
     */
    public function getHtml($order)
    {
        $order = $this->orderRepository->get($order->getId());
        $templateId = $this->templateRepository->getOrderTemplateId($order->getStoreId(), $order->getCustomerGroupId());
        if (!$templateId) {
            return '';
        }
        $vars = [
            'order' => $order,
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
        ];
        $transportObject = new DataObject($vars);
        $this->eventManager->dispatch(
            'email_order_set_template_vars_before',
            ['sender' => $this, 'transport' => $transportObject, 'transportObject' => $transportObject]
        );

        $template = $this->templateFactory->get($templateId)
            ->setVars($transportObject->getData())
            ->setOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $order->getStoreId()
                ]
            );

        return $template->processTemplate();
    }
}
