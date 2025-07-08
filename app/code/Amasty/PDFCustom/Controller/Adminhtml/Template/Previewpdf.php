<?php

namespace Amasty\PDFCustom\Controller\Adminhtml\Template;

class Previewpdf extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_PDFCustom::template';

    /**
     * @var \Amasty\PDFCustom\Model\PdfFactory
     */
    private $pdfFactory;

    /**
     * @var \Amasty\PDFCustom\Model\ComponentChecker
     */
    private $componentChecker;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\PDFCustom\Model\PdfFactory $pdfFactory,
        \Amasty\PDFCustom\Model\ComponentChecker $componentChecker
    ) {
        $this->pdfFactory = $pdfFactory;
        parent::__construct($context);
        $this->componentChecker = $componentChecker;
    }

    /**
     * Preview transactional email action
     *
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        if (!$this->componentChecker->isComponentsExist()) {
            $this->messageManager->addErrorMessage($this->componentChecker->getComponentsErrorMessage());

            return $this->_redirect('*/*/');
        }

        try {
            $html = $this->_view->getLayout()
                ->createBlock(\Amasty\PDFCustom\Block\Adminhtml\Template\Preview::class, 'preview.page.content')
                ->toHtml();
		echo $html;		
die("Sorry, Under development");
            /** @var \Amasty\PDFCustom\Model\Pdf $pdf */
            $pdf = $this->pdfFactory->create();
            $pdf->setHtml($html);
            $rawPdf = $pdf->render();

            /** @var \Magento\Framework\Controller\Result\Raw $raw */
            $raw = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
            $raw->setHeader('Content-type', "application/x-pdf");
            $raw->setHeader('Content-Security-Policy', "script-src 'none'");
            $raw->setHeader('Content-Disposition', "inline; filename=preview.pdf");
            $raw->setContents($rawPdf);

            return $raw;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred. The PDF template can not be opened for preview.')
            );
            $this->_redirect('*/*/');
        }
    }
}
