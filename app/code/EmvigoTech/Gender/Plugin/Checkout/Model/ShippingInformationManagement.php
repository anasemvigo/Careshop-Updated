<?php

namespace EmvigoTech\Gender\Plugin\Checkout\Model;

class ShippingInformationManagement
{
    const MALE = 'MALE';
    const FEMALE = 'FEMALE';

    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        if (!$extensionAttributes = $addressInformation->getExtensionAttributes()) {
            return;
        }

        $genderField = $extensionAttributes->getGenderField();

        if ($genderField !== null) {
            $quote = $this->quoteRepository->getActive($cartId);

            if ($genderField === 1) {
                $customerGender = self::MALE;
            } elseif ($genderField === 2) {
                $customerGender = self::FEMALE;
            } else {
                $customerGender = self::FEMALE;
            }
            $quote->setCustomerGender($customerGender);
            $this->quoteRepository->save($quote);
        }
    }
}
