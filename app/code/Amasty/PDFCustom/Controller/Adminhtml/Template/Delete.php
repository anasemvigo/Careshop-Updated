<?php

namespace Amasty\PDFCustom\Controller\Adminhtml\Template;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_PDFCustom::template';

    /**
     * @var \Amasty\PDFCustom\Model\TemplateFactory
     */
    private $templateFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\PDFCustom\Model\TemplateFactory $templateFactory
    ) {
        parent::__construct($context);
        $this->templateFactory = $templateFactory;
    }

    /**
     * Delete transactional email action
     *
     * @return void
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        /** @var \Amasty\PDFCustom\Model\Template $template */
        $template = $this->templateFactory->create();
        if ($id) {
            $template->load($id);
        }
        if ($template->getId()) {
            try {
                $template->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the PDF template.'));
                $this->_objectManager->get(\Magento\Framework\App\ReinitableConfig::class)->reinit();
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('We can\'t delete PDF template data right now. Please review log and try again.')
                );
                // redirect to edit form
                $this->_redirect('*/*/edit', ['id' => $template->getId()]);
                return;
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find an PDF template to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
}
