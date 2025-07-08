<?php

namespace Lof\AffiliateSaveCart\Block\Cart;
use Magento\Customer\Model\Session;
class Save extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'cart/save.phtml';

    /**
     * @var Session
     */
    protected $session;

    protected $_accountAffiliateFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Lof\AffiliateSaveCart\Helper\Data $saveCartHelper,
        \Lof\Affiliate\Model\AccountAffiliateFactory $accountAffiliateFactory,
        Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
        $this->saveCartHelper = $saveCartHelper;
        $this->session = $customerSession;
        $this->_accountAffiliateFactory = $accountAffiliateFactory;
    }
    /**
     * Return the save action Url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('affiliatesavecart/cart/save');
    }

    public function getAffiliateSaveCartLinkConfig()
    {
        return \Zend_Json::encode([
            'saveCartLinkUrl' => $this->getAction()
        ]);
    }
    public function _toHtml()
    {
        $enabled = $this->saveCartHelper->getConfig('affiliatesavecart/enable');
        $enabled_cart_button = $this->saveCartHelper->getConfig('affiliatesavecart/enable_save_cart_button');
        if($enabled && $enabled_cart_button){
            if($this->checkExistAccount()){
                return parent::_toHtml();
            }
        }
        return ;
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
            $customerId = $this->session->getCustomerId();
            $model = $this->_accountAffiliateFactory->create();
            $this->_affiliate_account = $model->load($customerId, 'customer_id');
        }
        return $this->_affiliate_account;
    }
}
