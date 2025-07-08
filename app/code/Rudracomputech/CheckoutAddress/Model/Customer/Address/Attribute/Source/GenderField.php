<?php
/**
 * Copyright © Copyright All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Rudracomputech\CheckoutAddress\Model\Customer\Address\Attribute\Source;

class GenderField extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '1', 'label' => __('Mr')],
                ['value' => '2', 'label' => __('Ms')]
            ];
        }
        return $this->_options;
    }
}

