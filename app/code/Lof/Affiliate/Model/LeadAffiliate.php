<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the venustheme.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_Affiliate
 * @copyright  Copyright (c) 2016 Landofcoder (https://landofcoder.com)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\Affiliate\Model;

use Magento\Customer\Model\Session;

class LeadAffiliate extends \Magento\Framework\Model\AbstractModel
{

    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_leadlHelper;

    protected $_resource;

    protected $_leadFactory;

    protected $_resourceModel;

    /**
     * @var Session
     */
    protected $session;
    /**
     * Page cache tag
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Lof\Affiliate\Model\ResourceModel\LeadAffiliate $resource = null,
        \Lof\Affiliate\Model\ResourceModel\LeadAffiliate\Collection $resourceCollection = null,
        \Lof\Affiliate\Model\ResourceModel\LeadAffiliate\CollectionFactory $leadFactory,
        \Magento\Framework\UrlInterface $url,
        \Lof\Affiliate\Helper\Data $leadlHelper,
        Session $customerSession,
        \Magento\Framework\App\ResourceConnection $resourceModel,
        array $data = []
    )
    {
        $this->_url = $url;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_resource = $resource;
        $this->_leadlHelper = $leadlHelper;
        $this->_leadFactory = $leadFactory;
        $this->_resourceModel = $resourceModel;
        $this->session = $customerSession;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Lof\Affiliate\Model\ResourceModel\LeadAffiliate');
    }

    /**
     * Prevent blocks recursion
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadByAttribute($attribute, $value)
    {
        $this->load($value, $attribute);

        return $this;
    }
}
