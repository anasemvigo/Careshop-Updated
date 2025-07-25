<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Lof\Affiliate\Block\Account\Form;
/**
 * Customer edit form block
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */

use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Link extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Lof\Affiliate\Model\ResourceModel\TransactionAffiliate\Collection
     */
    protected $_accountCollectionFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Session
     */
    protected $_helper;
    protected $_helperImage;
    protected $objectManager;

    /**
     * @var Lof\Affiliate\Model\ResourceModel\BannerAffiliate\Collection
     */
    protected $_bannerCollectionFactory;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    protected $_accountAffiliateFactory;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Lof\Affiliate\Model\ResourceModel\AccountAffiliate\CollectionFactory $accountCollectionFactory,
        Session $customerSession,
        \Lof\Affiliate\Helper\Data $helper,
        PriceCurrencyInterface $priceCurrency,
        \Lof\Affiliate\Helper\Image $helperImage,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Lof\Affiliate\Model\AccountAffiliateFactory $accountAffiliateFactory,
        array $data = []
    )
    {
        $this->_accountCollectionFactory = $accountCollectionFactory;
        $this->session = $customerSession;
        $this->_helper = $helper;
        $this->priceCurrency = $priceCurrency;
        $this->objectManager = $objectManager;
        $this->_helperImage = $helperImage;
        $this->_accountAffiliateFactory = $accountAffiliateFactory;

        $template = 'Lof_Affiliate::form/link.phtml';
        if ($this->hasData("template") && $this->getData("template")) {
            $template = $this->getData("template");
        }
        if (isset($data['template']) && $data['template']) {
            $template = $data['template'];
        }
        $this->setTemplate($template);

        parent::__construct($context, $data);
    }

    public function _toHtml()
    {
        $customer_id = $this->session->getCustomerId();
        $email = $this->session->getCustomer()->getEmail();
        $this->assign("listProductLink", $this->listProductLink());
        $this->assign("trackingCode", $this->getTrackingCode());

        $accountCollection = $this->_accountCollectionFactory->create()
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('email', $email);
        $this->setAccountCollection($accountCollection);
        return parent::_toHtml();
    }

    public function getAffiliateAccount(){
        if(!isset($this->_affiliate_account)){
            $customerId = $this->session->getCustomerId();
            $model = $this->_accountAffiliateFactory->create();
            $this->_affiliate_account = $model->load($customerId, 'customer_id');
        }
        return $this->_affiliate_account;
    }

    public function checkExistAccount()
    {
        if(!isset($this->_is_exist_affiliate_account)){
            $accountAffiliate = $this->getAffiliateAccount();
            if (!$accountAffiliate->getId()) {
                $this->_is_exist_affiliate_account = false;
            } else {
                if($accountAffiliate->getIsActive()) {
                    $this->_is_exist_affiliate_account = true;
                }else{
                    $this->_is_exist_affiliate_account = false;
                }
            }
        }
        return $this->_is_exist_affiliate_account;
    }

    public function setAccountCollection($collection)
    {
        $this->_collection = $collection;
        return $this;
    }

    public function getAccountCollection()
    {
        return $this->_collection;

    }

    /**
     * Get TrackingCode
     *
     * @param
     * @return customer_id or trackingCode
     */
    public function getTrackingCode()
    {
        $customer_id = $this->session->getCustomerId();

        $url_param_value = $this->_helper->getUrlParamValue();
        if ($url_param_value == 2) {
            return $customer_id;
        } else {
            $accountAffiliate = $this->getAffiliateAccount();
            if($accountAffiliate->getId() && $accountAffiliate->getIsActive()){
                return $accountAffiliate->getTrackingCode();
            }
        }
        return $customer_id;
    }

    public function listProductLink()
    {
        $isSecure = $this->_storeManager->getStore()->isCurrentlySecure();
        if ($isSecure) {
            return $this->getUrl('affiliate/account/transaction', array('_secure' => true));
        } else {
            return $this->getUrl('affiliate/account/transaction');
        }
    }

    public function getSiteUrl()
    {
        $isSecure = $this->_storeManager->getStore()->isCurrentlySecure();
        if ($isSecure) {
            return $this->getUrl('', array('_secure' => true));
        } else {
            return $this->getUrl('');
        }
    }

    /**
     * Get Default Helper
     *
     * @param
     * @return helper
     */
    public function getAffiliateHelper()
    {
        return $this->_helper;
    }

    public function formatCurrency(
        $amount,
        $includeContainer = true,
        $precision = 4
    )
    {
        return $this->priceCurrency->format($amount, $includeContainer, $precision);
    }

    /**
     * Get Default Helper
     *
     * @param
     * @return helper
     */
    public function getAffiliateHelperImage()
    {
        return $this->_helperImage;
    }

    /**
     * Get Link Default
     *
     * @param
     * @return string
     */
    public function getLinkAffDefault()
    {
        $base_url = $this->_storeManager->getStore()->getBaseUrl();

        $param_code = $this->_helper->getParamCode();
        $param_value = $this->getTrackingCode();

        $link_aff_default = $base_url . "?" . $param_code . "=" . $param_value;
        return $link_aff_default;
    }

    public function getLinkAffRegister()
    {
        $base_url = $this->_storeManager->getStore()->getBaseUrl();

        $param_code = $this->_helper->getParamCode();
        $param_value = $this->getTrackingCode();

        $link_aff_register = $base_url . 'customer/account/create/' . "?" . $param_code . "=" . $param_value;
        return $link_aff_register;
    }

    /**
     * Get Code Default
     *
     * @param
     * @return string
     */
    public function getUrlCodeAffDefault()
    {
        $code = $this->getTrackingCode();
        return $code;
    }


    /**
     * Prepare Layout
     *
     * @param no param
     * @return void
     */
    public function _prepareLayout()
    {

        $page_title = __('Banners & Links');
        $meta_description = __('Banners & Links');
        $meta_keywords = __('Banners & Links');

        $this->_addBreadcrumbs();

        if ($page_title) {
            $this->pageConfig->getTitle()->set($page_title);
        }
        if ($meta_keywords) {
            $this->pageConfig->setKeywords($meta_keywords);
        }
        if ($meta_description) {
            $this->pageConfig->setDescription($meta_description);
        }

        return parent::_prepareLayout();
    }

    /**
     * Prepare breadcrumbs
     *
     * @param \Magento\Cms\Model\Page $brand
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addBreadcrumbs()
    {

        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $page_title = __('Banners & Links');
        $show_breadcrumbs = true;

        if ($show_breadcrumbs && $breadcrumbsBlock) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $baseUrl
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'list',
                [
                    'label' => __('Affiliate'),
                    'title' => __('Return to Affiliate'),
                    'link' => $baseUrl . 'affiliate'
                ]
            );

            $breadcrumbsBlock->addCrumb(
                'view',
                [
                    'label' => $page_title,
                    'title' => $page_title,
                    'link' => ''
                ]
            );
        }
    }

}
