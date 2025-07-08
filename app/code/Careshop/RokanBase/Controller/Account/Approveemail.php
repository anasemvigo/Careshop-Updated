<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Careshop\RokanBase\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class Approveemail
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Approveemail extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resource;
    protected $customerRepository;
    protected $emailSender;
    protected $scopeConfig;

	public function __construct(
		Context $context,
		PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Careshop\RokanBase\Helper\Email $emailSender,
        CustomerRepositoryInterface $customerRepository
	)
	{
		$this->resultPageFactory = $resultPageFactory;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resource;
        $this->customerRepository = $customerRepository;
        $this->emailSender = $emailSender;
		parent::__construct($context);
	}

	public function execute()
	{
        $resultRedirect = $this->resultRedirectFactory->create();
        $connection = $this->resourceConnection->getConnection();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $themeoption_email_confirm_email_template = $this->scopeConfig->getValue('themeoption/email/confirm_email_template', $storeScope);
        if (!$themeoption_email_confirm_email_template) {
            $themeoption_email_confirm_email_template = 'themeoption_email_confirm_email_template';
        }
        $code = $this->getRequest()->getParam('code');
        if ($code) {
            $customer_email_change = $connection->getTableName('customer_email_change');
            $query_email_change = 'Select * FROM ' . $customer_email_change . ' WHERE `code` = "'.$code.'"';
            $result_email_change = $connection->fetchRow($query_email_change);
            if ($result_email_change) {
                $customer = $this->getCustomerDataObject($result_email_change['customer_id']);

                $customer = $customer->setEmail($result_email_change['email_change']);
                $this->customerRepository->save($customer);
                $emailTemplateData = [
                    'customer_name' => $customer->getFirstname().' '.$customer->getLastname(),
                    'email_change' => $result_email_change['email_change']
                ];

                $query_update_email_change = 'DELETE FROM '. $customer_email_change .' WHERE `entity_id` = '.$result_email_change['entity_id'].'';
        		$connection->query($query_update_email_change);

                $this->emailSender->sendEmail($result_email_change['email'], $emailTemplateData, $themeoption_email_confirm_email_template);
                return $resultRedirect->setPath('customer/account/edit');
            }
        }

		$resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/');
        return $resultRedirect;
	}

    private function getCustomerDataObject($customerId)
    {
        return $this->customerRepository->getById($customerId);
    }
}
