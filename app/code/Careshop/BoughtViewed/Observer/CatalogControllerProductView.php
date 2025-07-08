<?php
namespace Careshop\BoughtViewed\Observer;

use Magento\Framework\Event\ObserverInterface;
use Careshop\BoughtViewed\Model\Viewed;
use Magento\Framework\Stdlib\DateTime\DateTime;

class CatalogControllerProductView implements ObserverInterface
{
    
    protected $_viewed;
    protected $messageManager;
    public $date;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        DateTime $date,
        Viewed $viewed
    ) {
        $this->_viewed = $viewed;
        $this->date  = $date;
        $this->messageManager  = $messageManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        $product_id = $product->getId();
        $ip = $this->_viewed->getIpAddress();
        $viewed = $this->_viewed->loadByIpAddress();
        if (!$viewed) {
            $viewed = $this->_viewed->create();
        }
        $viewed->setProductId($product_id);
        $viewed->setIpAddress($ip);
        $viewed->setCreatedAt($this->date->date());
        $viewed->save();
    }
}
