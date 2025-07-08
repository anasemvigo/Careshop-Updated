<?php
declare(strict_types=1);

namespace Careshop\RokanthemesBrand\ViewModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * View model Brand
 */
class Brand implements ArgumentInterface
{
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->_objectManager = $objectmanager;
    }

    public function getBrandCollection($_product){
        $brandValue = $_product->getData('brand');
        $swatchHelper = $this->_objectManager->get("Magento\Swatches\Helper\Data");
        $swatchData = $swatchHelper->getSwatchesByOptionsId([$brandValue]);
        $swatchHelperMedia = $this->_objectManager->get("Magento\Swatches\Helper\Media");
        $thumbImage = '';
        $arr_brand = [];
        if($brandValue && isset($swatchData[$brandValue]['value'])) {
            $thumbImage = $swatchHelperMedia->getSwatchAttributeImage('swatch_thumb', $swatchData[$brandValue]['value']);
            $arr_brand['image_url'] = $thumbImage;
            $value = $_product->getResource()->getAttribute('brand')->getFrontend()->getValue($_product);
            $arr_brand['value'] = $value;
        }
        return $arr_brand;
    }
}
