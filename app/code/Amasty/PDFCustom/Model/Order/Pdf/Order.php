<?php

namespace Amasty\PDFCustom\Model\Order\Pdf;

class Order extends \Magento\Framework\DataObject
{
    /**
     * @var \Amasty\PDFCustom\Model\PdfFactory
     */
    private $pdfFactory;

    /**
     * @var \Amasty\PDFCustom\Model\Order\Html\Order
     */
    private $orderHtml;

    public function __construct(
        \Amasty\PDFCustom\Model\PdfFactory $pdfFactory,
        \Amasty\PDFCustom\Model\Order\Html\Order $orderHtml,
        array $data = []
    ) {
        $this->pdfFactory = $pdfFactory;
        $this->orderHtml = $orderHtml;
        parent::__construct($data);
    }

    /**
     * Return PDF document
     *
     * @param array|\Magento\Sales\Model\ResourceModel\Order\Collection $orders
     * @return \Amasty\PDFCustom\Model\Pdf
     */
    public function getPdf($orders = [])
    {
        /** @var \Amasty\PDFCustom\Model\Pdf $pdf */
        $pdf = $this->pdfFactory->create();
        $html = '';
        /** @var \Magento\Sales\Model\Order $order */
        foreach ($orders as $order) {
            $html .= $this->orderHtml->getHtml($order);
        }

        $pdf->setHtml($html);

        return $pdf;
    }
}
