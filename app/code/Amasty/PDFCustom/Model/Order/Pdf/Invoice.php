<?php

namespace Amasty\PDFCustom\Model\Order\Pdf;

class Invoice extends \Magento\Framework\DataObject
{
    /**
     * @var \Amasty\PDFCustom\Model\PdfFactory
     */
    private $pdfFactory;

    /**
     * @var \Amasty\PDFCustom\Model\Order\Html\Invoice
     */
    private $invoiceHtml;

    public function __construct(
        \Amasty\PDFCustom\Model\PdfFactory $pdfFactory,
        \Amasty\PDFCustom\Model\Order\Html\Invoice $invoiceHtml,
        array $data = []
    ) {
        $this->pdfFactory = $pdfFactory;
        $this->invoiceHtml = $invoiceHtml;
        parent::__construct($data);
    }

    /**
     * Return PDF document
     *
     * @param array|\Magento\Sales\Model\ResourceModel\Order\Invoice\Collection $invoices
     * @return \Amasty\PDFCustom\Model\Pdf
     */
    public function getPdf($invoices = [])
    {
        /** @var \Amasty\PDFCustom\Model\Pdf $pdf */
        $pdf = $this->pdfFactory->create();
        $html = '';
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        foreach ($invoices as $invoice) {
            $html .= $this->invoiceHtml->getHtml($invoice);
        }

        $pdf->setHtml($html);

        return $pdf;
    }
}
