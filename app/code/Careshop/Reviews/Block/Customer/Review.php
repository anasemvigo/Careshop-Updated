<?php

namespace Careshop\Reviews\Block\Customer;

use Magento\Framework\Registry;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class AbstractAccount
 * @package Magento\Customer\Controller
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Review extends \Magento\Framework\View\Element\Template
{

	protected $objectManager;
	protected $customerSession;
	protected $_resource;
	protected $_order;
	protected $_review;
	protected $registry; 
	protected $_productRepositoryFactory;
	protected $request;
	protected $productImage;
	protected $_productloader;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
		Registry $registry,
		\Magento\Catalog\Model\ProductFactory $_productloader,
		\Magento\Sales\Model\Order $order,
		\Careshop\Reviews\Model\Review $review,
		\Magento\Framework\App\Request\Http $request,
		\Magento\Catalog\Helper\Image $productImage,
		\Magento\Framework\App\ResourceConnection $_resource,
		ObjectManagerInterface $objectManager,
		array $data = []
	)
	{
		$this->objectManager    = $objectManager;
		$this->productImage = $productImage;
		$this->customerSession  = $customerSession;
		$this->_resource = $_resource;
		$this->_productloader = $_productloader;
		$this->_order = $order;
		$this->_review = $review;
		$this->_productRepositoryFactory = $productRepositoryFactory;
		$this->registry         = $registry;
		$this->request = $request;

		parent::__construct($context, $data);
	}

	/**
	 * Returns the Magento Customer Model for this block
	 *
	 * @return \Magento\Customer\Api\Data\CustomerInterface|null
	 */
	
	public function getDataProduct($product_id)  
	{
		$product = $this->_productloader->create()->load($product_id);
		return $product;
	}

	public function getProductOptions($id)  
	{
		$adapter  = $this->_resource->getConnection();
		$tableName = $this->_resource->getTableName('sales_order_item');
		$sql = 'SELECT * FROM '.$tableName.' WHERE item_id="'.$id.'"';
		$data_query = $adapter->fetchRow($sql);
		return $data_query;
	}

	public function getNameCustomer()  
	{
		$name = '';
		if ($this->customerSession->isLoggedIn()) {
			$firstname = $this->customerSession->getCustomer()->getFirstname();
            $lastname = substr($this->customerSession->getCustomer()->getLastname(), 0, 1);
			$name = $firstname.' '.$lastname.'.' ;
		}
		return $name;
	}
}
