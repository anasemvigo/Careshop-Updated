<?php

namespace EmvigoTech\CustomShipping\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigProvider implements ConfigProviderInterface
{
    protected $storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    public function getConfig()
    {
        return [
            'customShipping' => [
                'storeCode' => $this->storeManager->getStore()->getCode()
            ]
        ];
    }
}
