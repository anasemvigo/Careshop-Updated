<?php
namespace Careshop\RokanBase\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\Event\ObserverInterface;

class AddClassToBody implements ObserverInterface
{
    /** @var PageConfig */
    protected $pageConfig;
      /** @var CustomerSession */
    protected $customerSession;
    protected $request;
    protected $scopeConfig;


    public function __construct(
        PageConfig $pageConfig,
        CustomerSession $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->pageConfig = $pageConfig;
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        $this->_request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $color_type = $this->scopeConfig->getValue('catalog/frontend/color_type', $storeScope);
        $this->pageConfig->addBodyClass('common-color-type-square');
    }
}
