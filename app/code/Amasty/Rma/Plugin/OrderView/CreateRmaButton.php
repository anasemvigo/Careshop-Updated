<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */

namespace Amasty\Rma\Plugin\OrderView;

use Magento\Sales\Block\Adminhtml\Order\View as OrderView;

class CreateRmaButton
{
    /**
     * @var \Amasty\Rma\Model\ConfigProvider
     */
    private $configProvider;

    public function __construct(\Amasty\Rma\Model\ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    /**
     * @param OrderView $subject
     */
    public function beforeSetLayout(OrderView $subject)
    {
        if ($this->configProvider->isEnabled()) {
            if ($statuses = $this->configProvider->getAllowedOrderStatuses($subject->getOrder()->getStoreId())) {
                if (!in_array($subject->getOrder()->getStatus(), $statuses)) {
                    return;
                }
            }
            $subject->addButton(
                'amrma_create',
                [
                    'label' => __('Create Return'),
                    'class' => 'amrma-create-return-button',
                    'id' => 'amrma-create-return-button',
                    'onclick' => "setLocation('" . $subject->getUrl('amrma/request/create') . "')"
                ]
            );
        }
    }
}
