<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Model\History;

class EventType
{
    const RMA_CREATED = 0;
    const TRACKING_NUMBER_ADDED = 1;
    const TRACKING_NUMBER_DELETED = 2;
    const NEW_MESSAGE = 3;
    const DELETED_MESSAGE = 4;
    const CUSTOMER_CLOSED_RMA = 5;
    const STATUS_AUTOMATICALLY_CHANGED = 6;
    const MANAGER_SAVED_RMA = 7;
    const MANAGER_ADDED_SHIPPING_LABEL = 8;
    const MANAGER_DELETED_SHIPPING_LABEL = 9;
    const SYSTEM_CHANGED_STATUS = 10;
    const SYSTEM_CHANGED_MANAGER = 11;
}
