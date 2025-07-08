<?php

namespace Amasty\PDFCustom\Model\Source;

class LinkType extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_DEFAULT = 0;
    const TYPE_REPLACE = 1;
    const TYPE_ADD = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TYPE_DEFAULT, 'label' => __('Do not replace the default "Print" link')],
            ['value' => self::TYPE_REPLACE, 'label' => __('Replace the default "Print" link')],
            ['value' => self::TYPE_ADD, 'label' => __('Add new link for custom PDF')],
        ];
    }
}
