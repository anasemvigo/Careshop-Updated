<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Adminhtml\Request;

use Amasty\Rma\Model\OptionSource\Manager;
use Amasty\Rma\Model\OptionSource\State;
use Amasty\Rma\Model\Request\ResourceModel\Request;
use Amasty\Rma\Model\Request\ResourceModel\RequestItem;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;

class Reports extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_Rma::manage';

    /**
     * @var Request
     */
    private $requestResource;

    /**
     * @var State
     */
    private $state;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var RequestItem
     */
    private $requestItemResource;

    public function __construct(
        Request $requestResource,
        RequestItem $requestItemResource,
        State $state,
        Manager $manager,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->requestResource = $requestResource;
        $this->state = $state;
        $this->manager = $manager;
        $this->requestItemResource = $requestItemResource;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $totalsByState = [];
        $states = $this->state->toArray();
        foreach ($this->requestResource->getTotalByState() as $key => $value) {
            $totalsByState[] = [
                'name' => (string)(isset($states[$key]) ? $states[$key] : ''),
                'total' => (int)$value
            ];
        }

        $managersTotal = [];
        $managers = $this->manager->toArray();
        foreach ($this->requestResource->getManagerRequestsCount() as $key => $value) {
            $managersTotal[] = [
                'name' => (string)(isset($managers[$key]) ? $managers[$key] : ''),
                'total' => (int)$value
            ];
        }

        $data = [
            'totalByState' => $totalsByState,
            'managersTotal' => $managersTotal,
            'topReasons' => $this->requestItemResource->getTop5Reasons(),
            'itemsBasePrice' => $this->requestItemResource->getReturnItemsBasePrice()
        ];

        return $result->setData($data);
    }
}
