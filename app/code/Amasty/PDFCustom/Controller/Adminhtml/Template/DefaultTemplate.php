<?php

namespace Amasty\PDFCustom\Controller\Adminhtml\Template;

class DefaultTemplate extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_PDFCustom::template';

    /**
     * @var \Magento\Email\Model\Template\Config
     */
    private $emailConfig;

    /**
     * @var \Amasty\PDFCustom\Model\TemplateFactory
     */
    private $templateFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Email\Model\Template\Config $emailConfig,
        \Amasty\PDFCustom\Model\TemplateFactory $templateFactory
    ) {
        $this->emailConfig = $emailConfig;
        $this->templateFactory = $templateFactory;
        parent::__construct($context);
    }

    /**
     * Set template data to retrieve it in template info form
     *
     * @return void
     * @throws \RuntimeException
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
        $templateId = $this->getRequest()->getParam('code');
        try {
            $parts = $this->emailConfig->parseTemplateIdParts($templateId);
            $templateId = $parts['templateId'];
            $theme = $parts['theme'];

            if ($theme) {
                $template->setForcedTheme($templateId, $theme);
            }
            $template->setForcedArea($templateId);

            $template->loadDefault($templateId);
            $template->setData('orig_template_code', $templateId);
            $template->setData(
                'template_variables',
                \Zend_Json::encode($template->getVariablesOptionArray(true))
            );

            $templateBlock = $this->_view->getLayout()->createBlock(
                \Amasty\PDFCustom\Block\Adminhtml\Template\Edit::class,
                'template_edit',
                [
                    'data' => [
                        'email_template' => $template
                    ]
                ]
            );
            $template->setData('orig_template_currently_used_for', $templateBlock->getCurrentlyUsedForPaths(false));

            $this->getResponse()->representJson(
                \Zend_Json::encode($template->getData())
            );
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }
    }
}
