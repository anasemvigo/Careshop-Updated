define([
    'Magento_Customer/js/model/customer'
], function (customer) {
    'use strict';

    return {
        isLoggedIn: function () {
            return customer.isLoggedIn();
        }
    };
});
