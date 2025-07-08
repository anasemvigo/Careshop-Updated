<?php

namespace Amasty\PDFCustom\Controller\Adminhtml\Template;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_PDFCustom::template';

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->_view->loadLayout();
        $this->_setActiveMenu('Amasty_PDFCustom::template');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('PDF Templates'));
        $this->_addBreadcrumb(__('PDF Templates'), __('PDF Templates'));
        $this->_view->renderLayout();
    }
}
