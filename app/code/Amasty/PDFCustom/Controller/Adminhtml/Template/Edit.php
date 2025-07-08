<?php

namespace Amasty\PDFCustom\Controller\Adminhtml\Template;

class Edit extends \Magento\Backend\App\Action
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
     * Edit PDF template action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $id = (int)$this->getRequest()->getParam('id');
        /** @var \Amasty\PDFCustom\Model\Template $template */
        $template = $this->templateFactory->create();
        if ($id) {
            $template->load($id);
        }
        $this->_setActiveMenu('Amasty_PDFCustom::template');

        if ($this->getRequest()->getParam('id')) {
            $this->_addBreadcrumb(__('Edit Template'), __('Edit System Template'));
        } else {
            $this->_addBreadcrumb(__('New Template'), __('New System Template'));
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('PDF Templates'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $template->getId() ? $template->getTemplateCode() : __('New Template')
        );

        $this->_addContent(
            $this->_view->getLayout()->createBlock(
                \Amasty\PDFCustom\Block\Adminhtml\Template\Edit::class,
                'template_edit',
                [
                    'data' => [
                        'email_template' => $template
                    ]
                ]
            )->setEditMode(
                (bool)$this->getRequest()->getParam('id')
            )
        );
        $this->_view->renderLayout();
    }
}
