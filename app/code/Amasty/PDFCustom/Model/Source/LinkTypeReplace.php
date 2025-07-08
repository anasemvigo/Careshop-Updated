<?php

namespace Amasty\PDFCustom\Model\Source;

class LinkTypeReplace extends \Amasty\PDFCustom\Model\Source\LinkType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = parent::toOptionArray();

        unset($options[self::TYPE_ADD]);

        return $options;
    }
}
