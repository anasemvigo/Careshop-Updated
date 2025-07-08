<?php
declare(strict_types=1);

namespace Careshop\RokanthemesBrand\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * View model Brand
 */
class Brand implements ArgumentInterface
{
    protected $swatchesHelper;
    protected $swatchesMedia;

    public function __construct(
        \Magento\Swatches\Helper\Data $swatchesHelper,
        \Magento\Swatches\Helper\Media $swatchesMedia
    ) {
        $this->swatchesHelper = $swatchesHelper;
        $this->swatchesMedia = $swatchesMedia;
    }

    public function getBrandCollection($_product)
    {
        $brandValue = $_product->getData('brand');
        $swatchData = $this->swatchesHelper->getSwatchesByOptionsId([$brandValue]);
        $thumbImage = '';
        $arr_brand = [];
        if ($brandValue && isset($swatchData[$brandValue]['value'])) {
            $thumbImage = $this->swatchesMedia->getSwatchAttributeImage('swatch_thumb', $swatchData[$brandValue]['value']);
            $arr_brand['image_url'] = $thumbImage;
            $value = $_product->getResource()->getAttribute('brand')->getFrontend()->getValue($_product);
            $arr_brand['value'] = $value;
        }
        return $arr_brand;
    }
}
