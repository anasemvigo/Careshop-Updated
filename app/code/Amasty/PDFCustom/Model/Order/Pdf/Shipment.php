<?php

namespace Amasty\PDFCustom\Model\Order\Pdf;

class Shipment extends \Magento\Framework\DataObject
{
    /**
     * @var \Amasty\PDFCustom\Model\PdfFactory
     */
    private $pdfFactory;

    /**
     * @var \Amasty\PDFCustom\Model\Order\Html\Shipment
     */
    private $shipmentHtml;

    public function __construct(
        \Amasty\PDFCustom\Model\PdfFactory $pdfFactory,
        \Amasty\PDFCustom\Model\Order\Html\Shipment $shipmentHtml,
        array $data = []
    ) {
        $this->pdfFactory = $pdfFactory;
        $this->shipmentHtml = $shipmentHtml;
        parent::__construct($data);
    }

    /**
     * Return PDF document
     *
     * @param array|\Magento\Sales\Model\ResourceModel\Order\Shipment\Collection $shipments
     * @return \Amasty\PDFCustom\Model\Pdf
     */
    public function getPdf($shipments = [])
    {
        /** @var \Amasty\PDFCustom\Model\Pdf $pdf */
        $pdf = $this->pdfFactory->create();
        $html = '';
        /** @var \Magento\Sales\Model\Order\Shipment $shipment */
        foreach ($shipments as $shipment) {
            $html .= $this->shipmentHtml->getHtml($shipment);
        }

        $pdf->setHtml($html);

        return $pdf;
    }
}
