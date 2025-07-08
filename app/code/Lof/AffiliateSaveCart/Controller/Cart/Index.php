<?php

namespace Lof\AffiliateSaveCart\Controller\Cart;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Lof\AffiliateSaveCart\Controller\AbstractCart;

class Index extends AbstractCart
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    protected $_accountAffiliateFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        PageFactory $resultPageFactory,
        \Lof\Affiliate\Model\AccountAffiliateFactory $accountAffiliateFactory
    ) {
        parent::__construct($context, $customerSession);
        $this->resultPageFactory = $resultPageFactory;
        $this->_accountAffiliateFactory = $accountAffiliateFactory;
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('affiliate/account/login');
            return $resultRedirect;
        }
        if (!$this->checkExistAccount()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account');
            return $resultRedirect;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Saved Carts'));
        return $resultPage;
    }

    public function checkExistAccount()
    {
        if (!isset($this->_is_exist_affiliate_account)) {
            $accountAffiliate = $this->getAffiliateAccount();
            if (!$accountAffiliate->getId()) {
                $this->_is_exist_affiliate_account = false;
            } else {
                if ($accountAffiliate->getIsActive()) {
                    $this->_is_exist_affiliate_account = true;
                } else {
                    $this->_is_exist_affiliate_account = false;
                }
            }
        }
        return $this->_is_exist_affiliate_account;
    }

    public function getAffiliateAccount()
    {
        if (!isset($this->_affiliate_account)) {
            $customerId = $this->customerSession->getCustomerId();
            $model = $this->_accountAffiliateFactory->create();
            $this->_affiliate_account = $model->load($customerId, 'customer_id');
        }
        return $this->_affiliate_account;
    }
}
