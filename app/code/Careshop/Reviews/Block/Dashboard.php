<?php

namespace Careshop\Reviews\Block;

use Magento\Framework\Registry;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class AbstractAccount
 * @package Magento\Customer\Controller
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Dashboard extends \Magento\Framework\View\Element\Template
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
	protected $storeManager;
	protected $resultLayout;
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
		\Magento\Framework\View\Result\Layout $resultLayout,
		\Magento\Framework\App\ResourceConnection $_resource,
		ObjectManagerInterface $objectManager,
		
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		array $data = []
	)
	{
		$this->objectManager    = $objectManager;
		$this->productImage = $productImage;
		$this->storeManager = $storeManager;
		$this->customerSession  = $customerSession;
		$this->_resource = $_resource;
		$this->_order = $order;
		$this->_productloader = $_productloader;
		$this->_review = $review;
		$this->_productRepositoryFactory = $productRepositoryFactory;
		$this->registry = $registry;
		$this->resultLayout = $resultLayout;
		$this->request = $request;

		parent::__construct($context, $data);
	}

	/**
	 * Returns the Magento Customer Model for this block
	 *
	 * @return \Magento\Customer\Api\Data\CustomerInterface|null
	 */
	public function getOrderCollection()  
	{
		$adapter  = $this->_resource->getConnection();
		$customer_id = $this->customerSession->getId();
		$order_collection = $this->_order->getCollection()->addAttributeToFilter('customer_id', $customer_id);
		return $order_collection;
	}


	public function getDataProduct($product_id)  
	{
		$product = $this->_productloader->create()->load($product_id);
		if ($product) {
			return $product;
		}
		return false;
	}
	
	public function getImage($product)  
	{
		$image = $product->getData('image');
		$image_url = $this->productImage->init($product, 'product_thumbnail_image')->setImageFile($product->getFile())->resize(150, 150)->getUrl();
		return $image_url;
	}

	public function getTitelHeader($product_id)  
	{
		$data_query = $this->Review($product_id);
		$data_query_improve = $this->ImproveReview($product_id);
		$html = '';
		if(!$data_query && !$data_query_improve){
			$html = ''.__('Read To Review / Improve').'';
		}elseif(!$data_query && $data_query_improve){
			$html = ''.__('Read To Review').'';
		}elseif($data_query && !$data_query_improve){
			$html = ''.__('Read To Improve').'';
		}
		return $html;
	}

	public function getProductPriceHtml(\Magento\Catalog\Model\Product $product)
{
		/** @var \Magento\Framework\Pricing\Render $priceRender */
		$priceRender = $this->resultLayout->getLayout()->getBlock(\Magento\Framework\Pricing\Render::class);
		if (!$priceRender) {
			$priceRender = $this->resultLayout->getLayout()->createBlock(
				\Magento\Framework\Pricing\Render::class,
				\Magento\Framework\Pricing\Render::class,
				['data' => ['price_render_handle' => 'catalog_product_prices']]
			);
		}
		$price = '';
		if ($priceRender) {
			$price = $priceRender->render(
				\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
				$product,
				[
					'display_minimal_price' => true,
					'use_link_for_as_low_as' => true,
					'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
				]
			);
		}

		return $price;
	}

	public function getAction($product_id, $item_id)  
	{
		$html = '<a href="'.$this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB).'reviews/customer/review/id/'.$product_id.'/item/'.$item_id.'" class="btn">'.__('Review Product').'</a>';
		return $html;
	}
	
	public function Review($product_id){
		$data = $this->_review->dataReviewsByProductId($product_id);
		return $data;
	}

	public function ImproveReview($product_id){ 
		$data = $this->_review->dataImproveReviewByProductId($product_id);
		return $data;
	}
}
