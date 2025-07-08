define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'ko',
    'Magento_Customer/js/model/customer',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/create-shipping-address',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry',
    'mage/translate',
    'Magento_Checkout/js/model/shipping-rate-service'
], function (
    $,
    _,
    Component,
    ko,
    customer,
    addressList,
    addressConverter,
    quote,
    createShippingAddress,
    selectShippingAddress,
    shippingRatesValidator,
    formPopUpState,
    shippingService,
    selectShippingMethodAction,
    rateRegistry,
    setShippingInformationAction,
    stepNavigator,
    modal,
    checkoutDataResolver,
    checkoutData,
    registry,
    $t
) {
    'use strict';

    var popUp = null;

    return function (Component) {
        return Component.extend({

            defaults: {
                shippingFormTemplate: 'EmvigoTech_Checkout/shipping-address/form',
                setAsDefault: 1



            },

            initialize: function () {
                this._super();
                // Your custom logic here
                return this;
            },

            saveNewAddress: function () {
                var addressData,
                    newShippingAddress;
                this.source.set('params.invalid', false);
                this.triggerShippingDataValidateEvent();
    
                if (!this.source.get('params.invalid')) {
                    addressData = this.source.get('shippingAddress');
                    // if user clicked the checkbox, its value is true or false. Need to convert.
                    addressData['save_in_address_book'] = this.saveInAddressBook ? 1 : 0;
    
                    // New address must be selected as a shipping address
                    newShippingAddress = createShippingAddress(addressData);
                    selectShippingAddress(newShippingAddress);
                    checkoutData.setSelectedShippingAddress(newShippingAddress.getKey());
                    checkoutData.setNewCustomerShippingAddress($.extend(true, {}, addressData));
                    this.getPopUp().closeModal();
                    this.isNewAddressAdded(true);

                    var customerId = customer.customerData.id

                    if (this.setAsDefault) {
                        console.log('checked')

                        $.ajax({
                            url: '/emvigocheckout/customer/setasdefaultshipping/', // Adjust the URL according to your Magento routing
                            type: 'POST',
                            data: {
                                customer_id: customerId, // Pass the customer ID
                                address_data: JSON.stringify(addressData)
                                 // Ensure this matches what your controller expects
                            },
                            success: function (response) {
                                // Handle success
                                console.log(response.message);
                            },
                            error: function (xhr, status, error) {
                                // Handle error
                                console.error(error);
                            }
                        });
                    }

                    else{

                        console.log('unchecked')
                        
                    }

                    
                }
            },


          
        });
    };
});
