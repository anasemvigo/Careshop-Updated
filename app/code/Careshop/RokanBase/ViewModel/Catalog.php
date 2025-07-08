<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Careshop\RokanBase\ViewModel;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;

class Catalog implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
	protected $storeManager; 
	protected $objectManager; 
	protected $registry; 

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        Registry $registry
    ) {
        $this->storeManager = $storeManager;
        $this->objectManager = $objectmanager;
        $this->registry = $registry;
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
}
