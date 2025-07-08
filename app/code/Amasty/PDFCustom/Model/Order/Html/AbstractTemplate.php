<?php

namespace Amasty\PDFCustom\Model\Order\Html;

abstract class AbstractTemplate
{
    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    /**
     * @var \Amasty\PDFCustom\Model\Template\Factory
     */
    protected $templateFactory;

    /**
     * @var \Magento\Sales\Model\Order\Address\Renderer
     */
    protected $addressRenderer;

    /**
     * @var \Amasty\PDFCustom\Model\ConfigProvider
     */
    protected $configProvider;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Amasty\PDFCustom\Model\ResourceModel\TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    public function __construct(
        \Magento\Payment\Helper\Data $paymentHelper,
        \Amasty\PDFCustom\Model\Template\Factory $templateFactory,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Amasty\PDFCustom\Model\ConfigProvider $configProvider,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Amasty\PDFCustom\Model\ResourceModel\TemplateRepository $templateRepository,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->paymentHelper = $paymentHelper;
        $this->templateFactory = $templateFactory;
        $this->addressRenderer = $addressRenderer;
        $this->configProvider = $configProvider;
        $this->orderRepository = $orderRepository;
        $this->templateRepository = $templateRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * @param \Magento\Sales\Model\AbstractModel $saleObject
     *
     * @return string
     */
    abstract public function getHtml($saleObject);

    /**
     * Return payment info block as html
     *
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    protected function getPaymentHtml(\Magento\Sales\Model\Order $order)
    {
        return $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $order->getStoreId()
        );
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string|null
     */
    protected function getFormattedShippingAddress($order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }
}
