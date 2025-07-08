define([
    'jquery',
    'underscore',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Customer/js/model/customer'
], function ($, _, ko, Component, stepNavigator, customer) {
    'use strict';

    var steps = stepNavigator.steps;

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/progress-bar',
            visible: true
        },
        steps: steps,

        /** @inheritdoc */
        initialize: function () {
            var stepsValue;

            this._super();
            window.addEventListener('hashchange', _.bind(stepNavigator.handleHash, stepNavigator));

            if (!window.location.hash) {
                stepsValue = stepNavigator.steps();

                if (stepsValue.length) {
                    stepNavigator.setHash(stepsValue.sort(stepNavigator.sortItems)[0].code);
                }
            }

            stepNavigator.handleHash();
        },

        /**
         * @param {*} itemOne
         * @param {*} itemTwo
         * @return {*|Number}
         */
        sortItems: function (itemOne, itemTwo) {
            return stepNavigator.sortItems(itemOne, itemTwo);
        },

        /**
         * @param {Object} step
         */
        navigateTo: function (step) {
            stepNavigator.navigateTo(step.code);
        },

        /**
         * @param {Object} item
         * @return {*|Boolean}
         */
        isProcessed: function (item) {
            return stepNavigator.isProcessed(item.code);
        },

        /**
         * Custom method to check if the customer is logged in
         * @return {Boolean}
         */
        isCustomerLoggedIn: function () {

            if (typeof customer.isLoggedIn === 'function') {

                return customer.isLoggedIn();
            }
            return false;
        }
    });
});
