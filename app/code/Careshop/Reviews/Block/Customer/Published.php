<?php

namespace Careshop\Reviews\Block\Customer;

/**
 * Class AbstractAccount
 * @package Magento\Customer\Controller
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Published extends \Magento\Framework\View\Element\Template
{
    protected $_review;
    protected $storeManager;
    protected $_productloader;
    protected $itemFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Careshop\Reviews\Model\Review $review,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Model\Order\ItemFactory $itemFactory,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->_review = $review;
        $this->_productloader = $_productloader;
        $this->itemFactory = $itemFactory;
        parent::__construct($context, $data);
    }

    /**
     * Returns the Magento Customer Model for this block
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */

    public function getReviewsPublished()
    {
        $data_query = $this->_review->dataReviewsPublished();
        return $data_query;
    }

    public function getDataProduct($product_id)
    {
        $product = $this->_productloader->create()->load($product_id);
        if ($product) {
            return $product;
        }
        return false;
    }

    public function getProductOptions($id)
    {
        $orderItem = $this->itemFactory->create()->getCollection()->addFieldToFilter('item_id', ['eq'=>$id]);
        $data_query = $orderItem->getData();
        if (!empty($data_query)) {
            if (isset($data_query['product_type']) && $data_query['product_type'] == 'bundle') {
                $orderItem = $this->itemFactory->create()->getCollection()->addFieldToFilter('parent_item_id', ['eq'=>$id]);
                return $orderItem->getData();
            }
        }
        return $data_query;
    }

    public function mediaUrl()
    {
        $store = $this->storeManager->getStore();
        $mediaUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
    
    public function getReviewsRating($id)
    {
        $percent = $this->_review->dataReviewsRating($id);
        return $percent;
    }
}
