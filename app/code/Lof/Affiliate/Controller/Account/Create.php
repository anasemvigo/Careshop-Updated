<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Lof\Affiliate\Controller\Account;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Lof\Affiliate\Model\AccountAffiliate;

class Create extends \Magento\Customer\Controller\AbstractAccount
{
    /** @var Registration */
    protected $registration;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     * @param Registration $registration
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory,
        Registration $registration,
        \Lof\Affiliate\Helper\Data $helper
    )
    {
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->registration = $registration;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Customer register form page
     *
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->session->isLoggedIn() || !$this->registration->isAllowed()) {
            $data = [];
            $customerData = $this->session->getCustomer();
            $this->helper->createAffiliateAccount($data, $customerData);

            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*');
            return $resultRedirect;
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
