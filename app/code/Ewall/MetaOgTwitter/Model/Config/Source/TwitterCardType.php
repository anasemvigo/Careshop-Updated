<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class TwitterCardType extends AbstractSource implements SourceInterface, OptionSourceInterface
{

   /**
    * Summary
    */
    protected const TYPE_ONE = 'summary';

   /**
    * Summary Card with Large Image
    */
    protected const TYPE_TWO = 'summary_large_image';

    /**
     * App Card
     */
    protected const TYPE_THREE = 'app';

    /**
     * Player Card
     */
    protected const TYPE_FOUR = 'player';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public function getOptionArray()
    {
        return [self::TYPE_ONE => __('Summary'), self::TYPE_TWO => __('Summary Card with Large Image'), self::TYPE_THREE => __('App Card'), self::TYPE_FOUR => __('Player Card')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
