<?php

namespace Amasty\PDFCustom\Model\Order\Pdf;

class Creditmemo extends \Magento\Framework\DataObject
{
    /**
     * @var \Amasty\PDFCustom\Model\PdfFactory
     */
    private $pdfFactory;

    /**
     * @var \Amasty\PDFCustom\Model\Order\Html\Creditmemo
     */
    private $creditmemoHtml;

    public function __construct(
        \Amasty\PDFCustom\Model\PdfFactory $pdfFactory,
        \Amasty\PDFCustom\Model\Order\Html\Creditmemo $creditmemoHtml,
        array $data = []
    ) {
        $this->pdfFactory = $pdfFactory;
        $this->creditmemoHtml = $creditmemoHtml;
        parent::__construct($data);
    }

    /**
     * Return PDF document
     *
     * @param array|\Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection $creditmemos
     * @return \Amasty\PDFCustom\Model\Pdf
     */
    public function getPdf($creditmemos = [])
    {
        /** @var \Amasty\PDFCustom\Model\Pdf $pdf */
        $pdf = $this->pdfFactory->create();
        $html = '';
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        foreach ($creditmemos as $creditmemo) {
            $html .= $this->creditmemoHtml->getHtml($creditmemo);
        }

        $pdf->setHtml($html);

        return $pdf;
    }
}
