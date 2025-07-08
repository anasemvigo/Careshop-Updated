<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the venustheme.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_Affiliate
 * @copyright  Copyright (c) 2016 Landofcoder (https://landofcoder.com)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\Affiliate\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Customer\Model\Registration;
use Lof\Affiliate\Model\AccountAffiliate;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /** @var Registration */
    protected $registration;

    /**
     * @var Session
     */
    protected $session;

    protected $_accountAffiliate;

    /**
     * [__construct description]
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Session $customerSession,
        Registration $registration,
        AccountAffiliate $accountAffiliate
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $customerSession;
        $this->registration = $registration;
        $this->_accountAffiliate = $accountAffiliate;
        parent::__construct($context);
    }

    /**
     * Affiliate Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $emailCustomer = $this->session->getCustomer()->getEmail();
        $checkAccountExist = $this->_accountAffiliate->checkAccountExist($emailCustomer);
        if ($this->session->isLoggedIn()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/affiliate/home');
            return $resultRedirect;
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Affiliate Home '));
        return $resultPage;
    }
}