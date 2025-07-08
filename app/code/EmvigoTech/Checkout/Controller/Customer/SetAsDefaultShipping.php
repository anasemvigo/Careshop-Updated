<?php
namespace EmvigoTech\Checkout\Controller\Customer;

class SetAsDefaultShipping extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;
    protected $customer;
    protected $addressFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\AddressFactory $addressFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->customer = $customer;
        $this->addressFactory = $addressFactory; // Assign the injected factory to your property
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/mycustom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('start');
        $resultRedirect = $this->resultRedirectFactory->create();

        $allPostData = $this->_request->getPostValue();
        $addressDataJson = json_decode($allPostData['address_data'], true);
        $customerId = $this->getRequest()->getParam('customer_id');

        $customer = $this->customer->load($customerId);

        if ($customer->getId()) {
            $address = $this->addressFactory->create();
            $address->setCustomerId($customerId)
                    ->setFirstname($addressDataJson['firstname'])
                    ->setLastname($addressDataJson['lastname'])
                    ->setCountryId($addressDataJson['country_id'])
                    ->setPostcode($addressDataJson['postcode'])
                    ->setCity($addressDataJson['city'])
                    ->setTelephone($addressDataJson['telephone'])
                    ->setCompany($addressDataJson['company'])
                    ->setStreet([
                        '0' => $addressDataJson['street'][0],
                    ])
                    ->setIsDefaultBilling('1')
                    ->setIsDefaultShipping('1');

            try {
                $address->save();
            } catch (Exception $e) {
                $this->messageManager->addSuccessMessage__(('We can\'t save the customer address.'));
            }
                  
        }

        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}
