<?php

namespace Magento\Layout\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Customer\Model\Session as CustomerSession;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * LayoutProcessor constructor.
     *
     * @param CustomerSession $customerSession
     */
    public function __construct(CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'])) {
            $fields =$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

            if( isset($fields['company']) )
                $fields['company']['sortOrder'] = 16;

            if( isset($fields['gender_field']) )
                $fields['gender_field']['sortOrder'] = 18;

            if( isset($fields['postcode']) ){
                $fields['postcode']['validation']['required-entry'] = 1;
                $fields['postcode']['sortOrder'] = 98;
            }
            if( isset($fields['city']) )
                $fields['city']['validation']['required-entry'] = 1;

            $isLoggedIn = $this->customerSession->isLoggedIn();

            $fields['date_of_birth'] = [
                'component' => 'Magento_Ui/js/form/element/date',
                'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/date',
                    'options' => [
                               'changeYear'=> true,
                               'changeMonth'=> true,
                               'yearRange' => '-120y:c+nn',
                               'dateFormat' => 'dd/mm/yy',
                               'showOn' => 'both',
                               'mask'=> true,
                               'maxDate' => '-1d',
                               ]
                ],
                'validation' => ['required-entry'=> !$isLoggedIn],
                'dataScope' => 'shippingAddress.custom_attributes.date_of_birth',
                'label' => __('Date of Birth'),
                'provider' => 'checkoutProvider',
                'placeholder' => __('DD/MM/YYYY'),
				 'filterBy' => null,
                'visible' => !$isLoggedIn,
                'sortOrder' => 42,

                'id' => 'date_of_birth'
            ];

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] =  $fields;


        }
        return $jsLayout;
    }
}
