<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Api;

/**
 * Interface RequestRepositoryInterface
 */
interface RequestRepositoryInterface
{
    /**
     * @param int $requestId
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($requestId);

    /**
     * @param int $hash
     *
     * @return \Amasty\Rma\Api\Data\RequestInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByHash($hash);

    /**
     * @param \Amasty\Rma\Api\Data\RequestInterface $request
     * @param bool $notify
     *
     * @return \Amasty\Rma\Api\Data\ResolutionInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Amasty\Rma\Api\Data\RequestInterface $request);

    /**
     * @param \Amasty\Rma\Api\Data\TrackingInterface $tracking
     *
     * @return \Amasty\Rma\Api\Data\TrackingInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveTracking(\Amasty\Rma\Api\Data\TrackingInterface $tracking);

    /**
     * @param int $trackingId
     *
     * @return \Amasty\Rma\Api\Data\TrackingInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getTrackingById($trackingId);

    /**
     * @param int $trackingId
     *
     * @return \Amasty\Rma\Api\Data\TrackingInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteTrackingById($trackingId);

    /**
     * @return \Amasty\Rma\Api\Data\RequestInterface
     */
    public function getEmptyRequestModel();

    /**
     * @return \Amasty\Rma\Api\Data\RequestItemInterface
     */
    public function getEmptyRequestItemModel();

    /**
     * @return \Amasty\Rma\Api\Data\TrackingInterface
     */
    public function getEmptyTrackingModel();
}
