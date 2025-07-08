<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Careshop\Customaddress\Model\Attribute\Source;

class Gender extends \Magento\Eav\Model\Entity\Attribute\Source\Boolean
{
    /**
     * Value of 'Use Config' option
     */
    const VALUE_USE_CONFIG = 2;

    /**
     * Retrieve all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Mr'), 'value' => '1'],
                ['label' => __('Mrs'), 'value' => '2'],
                ['label' => __('Not Specified'), 'value' => '3'],
            ];
        }
        return $this->_options;
    }
}
