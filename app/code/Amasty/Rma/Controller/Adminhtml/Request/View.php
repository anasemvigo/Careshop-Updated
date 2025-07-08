<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Rma
 */


namespace Amasty\Rma\Controller\Adminhtml\Request;

use Amasty\Rma\Api\RequestRepositoryInterface;
use Amasty\Rma\Controller\Adminhtml\RegistryConstants;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_Rma::request_view';

    /**
     * @var RequestRepositoryInterface
     */
    private $requestRepository;

    public function __construct(
        RequestRepositoryInterface $requestRepository,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->requestRepository = $requestRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($requestId = (int) $this->getRequest()->getParam(RegistryConstants::REQUEST_ID)) {
            try {
                $this->requestRepository->getById($requestId);
                $resultPage->getConfig()->getTitle()->prepend(__('View Return Request'));
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This request is no longer exists.'));

                return $this->_redirect('*/*');
            }
        } else {
            $this->_redirect('*/*');
        }

        return $resultPage;
    }
}
