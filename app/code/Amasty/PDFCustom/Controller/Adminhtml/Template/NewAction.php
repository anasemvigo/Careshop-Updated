<?php

namespace Amasty\PDFCustom\Controller\Adminhtml\Template;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_PDFCustom::template';

    /**
     * New transactional email action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
