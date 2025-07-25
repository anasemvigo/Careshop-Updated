<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Careshop\RokanBase\Controller\Account;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AddressRegistry;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\Customer\Mapper;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\Phrase;

/**
 * Class EditPost
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EditPost extends AbstractAccount implements CsrfAwareActionInterface, HttpPostActionInterface
{
    /**
     * Form code for data extractor
     */
    const FORM_DATA_EXTRACTOR_CODE = 'customer_account_edit';

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var CustomerExtractor
     */
    protected $customerExtractor;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Magento\Customer\Model\EmailNotificationInterface
     */
    private $emailNotification;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var Mapper
     */
    private $customerMapper;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var AddressRegistry
     */
    private $addressRegistry;

	protected $resourceConnection;

	protected $emailSender;

	protected $_storeManager;

    protected $scopeConfig;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param Validator $formKeyValidator
     * @param CustomerExtractor $customerExtractor
     * @param Escaper|null $escaper
     * @param AddressRegistry|null $addressRegistry
     */
    public function __construct(
        Context $context,
		\Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerRepositoryInterface $customerRepository,
        Validator $formKeyValidator,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Careshop\RokanBase\Helper\Email $emailSender,
        CustomerExtractor $customerExtractor,
        ?Escaper $escaper = null,
        AddressRegistry $addressRegistry = null
    ) {
        parent::__construct($context);
        $this->session = $customerSession;
		$this->resourceConnection = $resource;
		$this->emailSender = $emailSender;
		$this->_storeManager = $storeManager;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerRepository = $customerRepository;
        $this->scopeConfig = $scopeConfig;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerExtractor = $customerExtractor;
        $this->escaper = $escaper ?: ObjectManager::getInstance()->get(Escaper::class);
        $this->addressRegistry = $addressRegistry ?: ObjectManager::getInstance()->get(AddressRegistry::class);
    }

    /**
     * Get authentication
     *
     * @return AuthenticationInterface
     */
    private function getAuthentication()
    {
        if (!($this->authentication instanceof AuthenticationInterface)) {
            return ObjectManager::getInstance()->get(
                \Magento\Customer\Model\AuthenticationInterface::class
            );
        } else {
            return $this->authentication;
        }
    }

    /**
     * Get email notification
     *
     * @return EmailNotificationInterface
     * @deprecated 100.1.0
     */
    private function getEmailNotification()
    {
        if (!($this->emailNotification instanceof EmailNotificationInterface)) {
            return ObjectManager::getInstance()->get(
                EmailNotificationInterface::class
            );
        } else {
            return $this->emailNotification;
        }
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/edit');

        return new InvalidRequestException(
            $resultRedirect,
            [new Phrase('Invalid Form Key. Please refresh the page.')]
        );
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return null;
    }

    /**
     * Change customer email or password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $validFormKey = $this->formKeyValidator->validate($this->getRequest());

		$connection = $this->resourceConnection->getConnection();

        if ($validFormKey && $this->getRequest()->isPost()) {
            $currentCustomerDataObject = $this->getCustomerDataObject($this->session->getCustomerId());
            $customerCandidateDataObject = $this->populateNewCustomerDataObject(
                $this->_request,
                $currentCustomerDataObject
            );

            try {
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $themeoption_email_change_email_template = $this->scopeConfig->getValue('themeoption/email/change_email_template', $storeScope);
                if (!$themeoption_email_change_email_template) {
                    $themeoption_email_change_email_template = 'themeoption_email_change_email_template';
                }

				$current_email = $currentCustomerDataObject->getEmail();
				if ($this->getRequest()->getParam('change_email')) {
					$change_email = $this->getRequest()->getParam('email');
					if ($change_email && $current_email !== $change_email) {
        				$customer_entity = $connection->getTableName('customer_entity');
						$query_customer = 'Select * FROM ' . $customer_entity . ' WHERE `email` = "'.$change_email.'"';
        				$result = $connection->fetchRow($query_customer);
						if (!$result) {
							$customer_email_change = $connection->getTableName('customer_email_change');
							$query_email_change = 'Select * FROM ' . $customer_email_change . ' WHERE `customer_id` = "'.$currentCustomerDataObject->getId().'"';
        					$result_email_change = $connection->fetchRow($query_email_change);
							$code = $this->generateRandomString(12);
							if (!$result_email_change) {
								$query_insert_email_change = "INSERT INTO `" . $customer_email_change . "`(`customer_id`, `email`, `email_change`, `code`) VALUES (".$currentCustomerDataObject->getId().",'".$current_email."','".$change_email."','".$code."')";
        						$connection->query($query_insert_email_change);
							} else {
								$query_update_email_change = "UPDATE `" . $customer_email_change . "` SET `email_change`= '".$change_email."', `code` = '".$code."' WHERE customer_id = ".$currentCustomerDataObject->getId()." ";
        						$connection->query($query_update_email_change);
							}
							$emailTemplateData = [
								'customer_name' => $currentCustomerDataObject->getFirstname().' '.$currentCustomerDataObject->getLastname(),
								'url' => $this->_storeManager->getStore()->getUrl('rokanbase/account/approveemail').'?code='.$code.''
							];
							$this->emailSender->sendEmail($current_email, $emailTemplateData, $themeoption_email_change_email_template);
						}
					}
				}

                // whether a customer enabled change password option
                $isPasswordChanged = $this->changeCustomerPassword($currentCustomerDataObject->getEmail());

                // No need to validate customer address while editing customer profile
                $this->disableAddressValidation($customerCandidateDataObject);

                $this->customerRepository->save($customerCandidateDataObject);
                $this->getEmailNotification()->credentialsChanged(
                    $customerCandidateDataObject,
                    $currentCustomerDataObject->getEmail(),
                    $isPasswordChanged
                );
                $this->dispatchSuccessEvent($customerCandidateDataObject);
                $this->messageManager->addSuccessMessage(__('You saved the account information.'));
                return $resultRedirect->setPath('customer/account/index');
            } catch (InvalidEmailOrPasswordException $e) {
                $this->messageManager->addErrorMessage($this->escaper->escapeHtml($e->getMessage()));
            } catch (UserLockedException $e) {
                $message = __(
                    'The account sign-in was incorrect or your account is disabled temporarily. '
                    . 'Please wait and try again later.'
                );
                $this->session->logout();
                $this->session->start();
                $this->messageManager->addErrorMessage($message);
                return $resultRedirect->setPath('customer/account/login');
            } catch (InputException $e) {
                $this->messageManager->addErrorMessage($this->escaper->escapeHtml($e->getMessage()));
                foreach ($e->getErrors() as $error) {
                    $this->messageManager->addErrorMessage($this->escaper->escapeHtml($error->getMessage()));
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t save the customer.'));
            }

            $this->session->setCustomerFormData($this->getRequest()->getPostValue());
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/edit');
        return $resultRedirect;
    }

	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}

    /**
     * Account editing action completed successfully event
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customerCandidateDataObject
     * @return void
     */
    private function dispatchSuccessEvent(\Magento\Customer\Api\Data\CustomerInterface $customerCandidateDataObject)
    {
        $this->_eventManager->dispatch(
            'customer_account_edited',
            ['email' => $customerCandidateDataObject->getEmail()]
        );
    }

    /**
     * Get customer data object
     *
     * @param int $customerId
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function getCustomerDataObject($customerId)
    {
        return $this->customerRepository->getById($customerId);
    }

    /**
     * Create Data Transfer Object of customer candidate
     *
     * @param \Magento\Framework\App\RequestInterface $inputData
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function populateNewCustomerDataObject(
        \Magento\Framework\App\RequestInterface $inputData,
        \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
    ) {
        $attributeValues = $this->getCustomerMapper()->toFlatArray($currentCustomerData);
        $customerDto = $this->customerExtractor->extract(
            self::FORM_DATA_EXTRACTOR_CODE,
            $inputData,
            $attributeValues
        );
        $customerDto->setId($currentCustomerData->getId());
        if (!$customerDto->getAddresses()) {
            $customerDto->setAddresses($currentCustomerData->getAddresses());
        }
        $customerDto->setEmail($currentCustomerData->getEmail());

        return $customerDto;
    }

    /**
     * Change customer password
     *
     * @param string $email
     * @return boolean
     * @throws InvalidEmailOrPasswordException|InputException
     */
    protected function changeCustomerPassword($email)
    {
        $isPasswordChanged = false;
        if ($this->getRequest()->getParam('change_password')) {
            $currPass = $this->getRequest()->getPost('current_password');
            $newPass = $this->getRequest()->getPost('password');
            $confPass = $this->getRequest()->getPost('password_confirmation');
            if ($newPass != $confPass) {
                throw new InputException(__('Password confirmation doesn\'t match entered password.'));
            }

            $isPasswordChanged = $this->customerAccountManagement->changePassword($email, $currPass, $newPass);
        }

        return $isPasswordChanged;
    }

    /**
     * Process change email request
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerDataObject
     * @return void
     * @throws InvalidEmailOrPasswordException
     * @throws UserLockedException
     */
    private function processChangeEmailRequest(\Magento\Customer\Api\Data\CustomerInterface $currentCustomerDataObject)
    {
        if ($this->getRequest()->getParam('change_email')) {
            // authenticate user for changing email
            try {
                $this->getAuthentication()->authenticate(
                    $currentCustomerDataObject->getId(),
                    $this->getRequest()->getPost('current_password')
                );
            } catch (InvalidEmailOrPasswordException $e) {
                throw new InvalidEmailOrPasswordException(
                    __("The password doesn't match this account. Verify the password and try again.")
                );
            }
        }
    }

    /**
     * Get Customer Mapper instance
     *
     * @return Mapper
     *
     * @deprecated 100.1.3
     */
    private function getCustomerMapper()
    {
        if ($this->customerMapper === null) {
            $this->customerMapper = ObjectManager::getInstance()->get(\Magento\Customer\Model\Customer\Mapper::class);
        }
        return $this->customerMapper;
    }

    /**
     * Disable Customer Address Validation
     *
     * @param CustomerInterface $customer
     * @throws NoSuchEntityException
     */
    private function disableAddressValidation($customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
            $addressModel->setShouldIgnoreValidation(true);
        }
    }
}
