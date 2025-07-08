<?php

namespace Amasty\PDFCustom\Model\Order\Html;

use Magento\Framework\DataObject;

class Invoice extends AbstractTemplate
{
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     *
     * @return string
     */
    public function getHtml($invoice)
    {
        $order = $this->orderRepository->get($invoice->getOrderId());
        $templateId = $this->templateRepository->getInvoiceTemplateId(
            $invoice->getStoreId(),
            $order->getCustomerGroupId()
        );
        if (!$templateId) {
            return '';
        }
        $vars = [
            'order' => $order,
            'invoice' => $invoice,
            'comment' => $invoice->getCustomerNoteNotify() ? $invoice->getCustomerNote() : '',
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order)
        ];
        $transportObject = new DataObject($vars);
        $this->eventManager->dispatch(
            'email_invoice_set_template_vars_before',
            ['sender' => $this, 'transport' => $transportObject->getData(), 'transportObject' => $transportObject]
        );

        $template = $this->templateFactory->get($templateId)
            ->setVars($transportObject->getData())
            ->setOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $invoice->getStoreId()
                ]
            );

        return $template->processTemplate();
    }
}
