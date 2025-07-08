<?php
/**
 * @package EmvigoTech_CustomShipping
 */
declare(strict_types=1);

namespace EmvigoTech\CustomShipping\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Shipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'customshipping';

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * Shipping constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface          $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory
     * @param \Psr\Log\LoggerInterface                                    $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array                                                       $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @return float
     */
    private function getShippingPrice()
    {
        $configPrice = $this->getConfigData('price');

        $shippingPrice = $this->getFinalPriceWithHandlingFee($configPrice);

        return $shippingPrice;
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $amount = $this->calculateCustomShippingAmount($request);

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);

        return $result;
    }

    /**
     * @param RateRequest $request
     * @return float
     */

    private function calculateCustomShippingAmount(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $fixedShipping = 0;
        $boxerShipping = 0;
        $packableWeight = 0;
        $hasHighWeightItem = false;

        foreach ($request->getAllItems() as $item) {
            if ($item->getProduct()->getTypeId() === 'virtual' || $item->getParentItem()) {
                continue;
            }

            $product = $item->getProduct()->load($item->getProduct()->getId());
            $qty = $item->getQty();
            $weight = $product->getWeight() * $qty;

            // Fixed shipping items
            if ($product->getData('is_chair')) {
                $fixedShipping += 25.00 * $qty;
                continue;
            }

            if ($product->getData('is_bike')) {
                $fixedShipping += 38.00 * $qty;
                continue;
            }

            // Boxer-specific pricing
            if (strpos(strtolower($product->getName()), 'boxer') !== false) {
                $boxerShipping += ($qty == 1) ? 9.50 : 12.50;
                continue;
            }

            // Packable items
            $packableWeight += $weight;

            // Check if any item is NOT marked as low-weight
            if (!$product->getData('is_low_weight')) {
                $hasHighWeightItem = true;
            }
        }

        // Determine per-pack rate
        $packs = ceil($packableWeight / 30);
        $perPackRate = $hasHighWeightItem ? 12.50 : 9.50;
        $packShipping = $packs * $perPackRate;

        // Total shipping
        $totalShipping = $fixedShipping + $boxerShipping + $packShipping;

        return $totalShipping;
    }




}
