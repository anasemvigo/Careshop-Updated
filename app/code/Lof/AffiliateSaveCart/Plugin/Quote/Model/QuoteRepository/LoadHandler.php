<?php

namespace Lof\AffiliateSaveCart\Plugin\Quote\Model\QuoteRepository;

use Magento\Quote\Api\Data\CartExtensionFactory;
use Psr\Log\LoggerInterface as Logger;
use Lof\AffiliateSaveCart\Api\AffiliateSaveCartRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Lof\AffiliateSaveCart\Model\AffiliateSaveCartFactory as AffiliateSaveCartModelFactory;

class LoadHandler
{
    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;

    private $saveCartRepository;

    /**
     * @var AffiliateSaveCartModelFactory
     */
    private $saveCartModelFactory;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        CartExtensionFactory $cartExtensionFactory,
        AffiliateSaveCartRepositoryInterface $saveCartRepository,
        AffiliateSaveCartModelFactory $saveCartModelFactory,
        Logger $logger
    ) {
        $this->saveCartRepository = $saveCartRepository;
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->saveCartModelFactory = $saveCartModelFactory;
        $this->logger = $logger;
    }

    public function afterLoad($subject, $entity)
    {
        try {
            $saveCartData = $this->saveCartRepository->get($entity->getId());
        } catch (NoSuchEntityException $e) {
            $saveCartData = $this->saveCartModelFactory->create();
            $saveCartData->setQuoteId($entity->getId());
        }

        try {
            $cartExtension = $entity->getExtensionAttributes();
            // if ($cartExtension === null) {
            //     $cartExtension = $this->cartExtensionFactory->create();
            // }
            if( $cartExtension && method_exists($cartExtension, "setSaveCartData")) {
                $cartExtension->setSaveCartData($saveCartData);
                $entity->setExtensionAttributes($cartExtension);
            }
        } catch (\Exception $e) {
            //$this->logger->error($e->getMessage(), $e->getTrace());
        }

        return $entity;
    }
}
