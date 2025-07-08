<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Model\Config\Source\Category;

class Description implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * TO Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'category_description', 'label' => __('Category Description')],
            ['value' => 'meta_description', 'label' => __('Meta Description')]
        ];
    }
}
