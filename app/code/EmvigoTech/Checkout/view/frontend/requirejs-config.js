var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'EmvigoTech_Checkout/js/view/shipping': true
            }
        }
    },
    map: {
        '*': {
            'Magento_Checkout/js/view/progress-bar': 'EmvigoTech_Checkout/js/view/progress-bar'
        }
    }
};

