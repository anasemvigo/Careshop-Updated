<?php
namespace Careshop\BoughtViewed\Block\Viewed;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * @method Text getProductViewed()
 */
class ProductViewed extends \Magento\Catalog\Block\Product\AbstractProduct
{
    protected $_defaultToolbarBlock = 'Magento\Catalog\Block\Product\ProductList\Toolbar';

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    protected $productCollectionFactory;
    protected $storeManager;
    protected $catalogConfig;
    protected $productVisibility;
    protected $scopeConfig;
    protected $date;
    protected $registry;

    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        DateTime $date,
        \Magento\Framework\Registry $registry,
        \Careshop\BoughtViewed\Model\Viewed $viewed,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->date  = $date;
        $this->viewed = $viewed;
        $this->registry = $registry;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $context->getStoreManager();
        $this->catalogConfig = $context->getCatalogConfig();
        $this->productVisibility = $productVisibility;
        parent::__construct(
            $context,
            $data
        );
    }
    public function getProducts()
    {
        if ($this->registry->registry('current_product')) {
            $product_id = $this->getProduct()->getId();
        } else {
            $product_id = null;
        }
        $ip = $this->viewed->getIpAddress();
        $data_time = $this->date->date();
        $data_query_product_id = $this->viewed->dataQueryProductId($data_time, $ip, $product_id);
        $id_product = [];
        foreach ($data_query_product_id as $data_id) {
            $id_product[] = $data_id['product_id'];
        }
        $storeId    = $this->storeManager->getStore()->getId();
        $products = $this->productCollectionFactory->create()->setStoreId($storeId);
        $products
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds())
            ->addFieldToFilter('entity_id', ['in' => $id_product]);
        $products->setPageSize(8)->setCurPage(1);
        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $products]
        );
        return $products;
    }
    
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
