<?php

namespace Careshop\RokanBase\Block\Order;

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

class History extends \Magento\Sales\Block\Order\History
{
    protected $_orderCollectionFactory;
    protected $_customerSession;
    protected $_orderConfig;
    protected $orders;
    private $orderCollectionFactory;
    protected $amastyRmaRequest;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Amasty\Rma\Model\Request\ResourceModel\Collection $amastyRmaRequest,
        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->amastyRmaRequest = $amastyRmaRequest;
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data);
    }

    private function getOrderCollectionFactory()
    {
        if ($this->orderCollectionFactory === null) {
            $this->orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactoryInterface::class);
        }
        return $this->orderCollectionFactory;
    }

    public function getOrders()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }

        if (!$this->orders) {
            $params_url = $this->getRequest()->getParams();

            if (isset($params_url['status'])) {
                if ($params_url['status'] == 'returned') {
                    $amastyRmaRequest = $this->amastyRmaRequest->addFieldToFilter('customer_id', ['eq'=>$customerId]);
                    $data_query = $amastyRmaRequest->getData();
                    $id_order = [];
                    if ($data_query) {
                        foreach ($data_query as $data) {
                            $id_order[] = $data['order_id'];
                        }
                        $id_order = array_unique($id_order);
                    }

                    $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                        '*'
                    )->addFieldToFilter(
                        'status',
                        ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
                    )->addFieldToFilter(
                        'entity_id',
                        ['in' => $id_order]
                    )->setOrder(
                        'created_at',
                        'desc'
                    );
                } elseif ($params_url['status'] == 'complete' || $params_url['status'] == 'processing') {
                    $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                        '*'
                    )->addFieldToFilter(
                        'status',
                        ['in' => [$params_url['status']]]
                    )->setOrder(
                        'created_at',
                        'desc'
                    );
                } else {
                    $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                        '*'
                    )->addFieldToFilter(
                        'status',
                        ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
                    )->setOrder(
                        'created_at',
                        'desc'
                    );
                }
            } else {
                $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                    '*'
                )->addFieldToFilter(
                    'status',
                    ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
                )->setOrder(
                    'created_at',
                    'desc'
                );
            }
        }

        return $this->orders;
    }
}
