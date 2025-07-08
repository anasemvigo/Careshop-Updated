define([], function () {
    'use strict';

    return function (payload) {
        console.log('payload')

        payload.addressInformation['extension_attributes'] = {
            gender_field : document.querySelector('[name="gender_field"]').value
        };
        console.log(payload)
        return payload;
    };
});
