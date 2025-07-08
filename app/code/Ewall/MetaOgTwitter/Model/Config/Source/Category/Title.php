<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Model\Config\Source\Category;

class Title implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'category_name', 'label' => __('Category Name')],
            ['value' => 'meta_title', 'label' => __('Meta Title')]
        ];
    }
}
