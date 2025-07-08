define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction) {

            var shippingAddress = quote.shippingAddress();

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            if (shippingAddress.customAttributes != undefined) {
                $.each(shippingAddress.customAttributes , function( key, value ) {
					
					

                    if($.isPlainObject(value)){
						key = value['attribute_code'];
                        value = value['value'];
                        
                    }
					
					console.log("working 1");
					console.log(key);
					console.log("working first");
					console.log(value);

                   
                    shippingAddress['extension_attributes'][key] = value;

                });
				
				 shippingAddress['extension_attributes']['gender_field'] = 'MALE';
            }

            return originalAction();
        });
    };
});