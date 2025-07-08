<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Model\Request\DataProvider\Data;

use Amasty\Rma\Api\Data\ConditionInterface;
use Amasty\Rma\Api\Data\ReasonInterface;
use Amasty\Rma\Api\Data\ResolutionInterface;
use Amasty\Rma\Model\Condition\ResourceModel\CollectionFactory as ConditionCollectionFactory;
use Amasty\Rma\Model\OptionSource\Status;
use Amasty\Rma\Model\Reason\ResourceModel\CollectionFactory as ReasonCollectionFactory;
use Amasty\Rma\Model\Resolution\ResourceModel\CollectionFactory as ResolutionCollectionFactory;
use Magento\Framework\Data\Collection;

class FormInformation
{
    /**
     * @var ConditionCollectionFactory
     */
    private $conditionCollectionFactory;

    /**
     * @var ReasonCollectionFactory
     */
    private $reasonCollectionFactory;

    /**
     * @var ResolutionCollectionFactory
     */
    private $resolutionCollectionFactory;

    public function __construct(
        ConditionCollectionFactory $conditionCollectionFactory,
        ReasonCollectionFactory $reasonCollectionFactory,
        ResolutionCollectionFactory $resolutionCollectionFactory
    ) {
        $this->conditionCollectionFactory = $conditionCollectionFactory;
        $this->reasonCollectionFactory = $reasonCollectionFactory;
        $this->resolutionCollectionFactory = $resolutionCollectionFactory;
    }

    public function getResolutionList()
    {
        $result = [];
        $resolutions = $this->resolutionCollectionFactory->create()
            ->addFieldToSelect(
                [ResolutionInterface::RESOLUTION_ID, ResolutionInterface::TITLE]
            )->setOrder(ResolutionInterface::POSITION, Collection::SORT_ORDER_ASC)
            ->addNotDeletedFilter()
            ->addFieldToFilter(ResolutionInterface::STATUS, Status::ENABLED)
            ->getData();

        if (!empty($resolutions)) {
            $result[] = ['value' => '', 'label' => __('Please choose')];
            foreach ($resolutions as $resolution) {
                $result[] = [
                    'value' => $resolution[ResolutionInterface::RESOLUTION_ID],
                    'label' => $resolution[ResolutionInterface::TITLE]
                ];
            }
        }

        return $result;
    }

    public function getReasonList()
    {
        $result = [];
        $reasons = $this->reasonCollectionFactory->create()
            ->addFieldToSelect(
                [ReasonInterface::REASON_ID, ReasonInterface::TITLE, ReasonInterface::PAYER]
            )->setOrder(ReasonInterface::POSITION, Collection::SORT_ORDER_ASC)
            ->addNotDeletedFilter()
            ->addFieldToFilter(ReasonInterface::STATUS, Status::ENABLED)
            ->getData();

        if (!empty($reasons)) {
            $result[] = ['value' => '', 'label' => __('Please choose')];
            foreach ($reasons as $reason) {
                $result[] = [
                    'value' => $reason[ReasonInterface::REASON_ID],
                    'label' => $reason[ReasonInterface::TITLE],
                    'payer' => $reason[ReasonInterface::PAYER]
                ];
            }
        }

        return $result;
    }

    public function getConditionList()
    {
        $result = [];
        $conditions = $this->conditionCollectionFactory->create()
            ->addFieldToSelect(
                [ConditionInterface::CONDITION_ID, ConditionInterface::TITLE]
            )->setOrder(ConditionInterface::POSITION, Collection::SORT_ORDER_ASC)
            ->addNotDeletedFilter()
            ->addFieldToFilter(ConditionInterface::STATUS, Status::ENABLED);

        $conditions = $conditions->getData();
        if (!empty($conditions)) {
            $result[] = ['value' => '', 'label' => __('Please choose')];
            foreach ($conditions as $condition) {
                $result[] = [
                    'value' => $condition[ConditionInterface::CONDITION_ID],
                    'label' => $condition[ConditionInterface::TITLE]
                ];
            }
        }

        return $result;
    }
}
