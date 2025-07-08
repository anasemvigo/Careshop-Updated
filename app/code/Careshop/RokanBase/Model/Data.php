<?php
 
namespace Careshop\RokanBase\Model;
 
class Data extends \Magento\Framework\Model\AbstractModel
{
    protected $_orderCollectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory 
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
    }

    public function getOrders($orders, $email)
    {
        $collection = $this->_orderCollectionFactory->create()
         ->addAttributeToSelect('*')
         ->addFieldToFilter('increment_id', ['eq'=>$orders])
         ->addFieldToFilter('customer_email', ['eq'=>$email]);
        
        return $collection->getData();
    }
}
