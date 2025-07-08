<?php

namespace Amasty\PDFCustom\Model\Order\Html;

use Magento\Framework\DataObject;

class Creditmemo extends AbstractTemplate
{
    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     *
     * @return string
     */
    public function getHtml($creditmemo)
    {
        $order = $this->orderRepository->get($creditmemo->getOrderId());
        $storeId = $order->getStoreId();
        $customerGroupId = $order->getCustomerGroupId();
        $templateId = $this->templateRepository->getCreditmemoTemplateId($storeId, $customerGroupId);
        if (!$templateId) {
            return '';
        }
        $vars = [
            'order' => $order,
            'creditmemo' => $creditmemo,
            'comment' => $creditmemo->getCustomerNoteNotify() ? $creditmemo->getCustomerNote() : '',
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
        ];

        $transportObject = new DataObject($vars);
        $this->eventManager->dispatch(
            'email_creditmemo_set_template_vars_before',
            ['sender' => $this, 'transport' => $transportObject->getData(), 'transportObject' => $transportObject]
        );

        $template = $this->templateFactory->get($templateId)
            ->setVars($transportObject->getData())
            ->setOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $creditmemo->getStoreId()
                ]
            );

        return $template->processTemplate();
    }
}
