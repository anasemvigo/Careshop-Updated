<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Careshop\Reviews\Block\Product\View;

/**
 * Detailed Product Reviews
 *
 * @api
 * @since 100.0.2
 */
class ListView extends \Careshop\Reviews\Block\Product\View
{
    /**
     * Unused class property
     * @var false
     */
    protected $_forceHasOptions = false;

    /**
     * Get product id
     *
     * @return int|null
     */
    public function getProductId()
    {
        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * Prepare product review list toolbar
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $toolbar = $this->getLayout()->getBlock('product_reviews_list.toolbar');
        if ($toolbar) {
            $toolbar->setCollection($this->getReviewsCollection());
            $this->setChild('toolbar', $toolbar);
        }

        return $this;
    }

    /**
     * Add rate votes
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->getReviewsCollection()->load();  
        return parent::_beforeToHtml();
    }

    /**
     * Return review url
     *
     * @param int $id
     * @return string
     */
    public function getReviewUrl($id)
    {
        return $this->getUrl('*/*/view', ['id' => $id]);
    }
}
