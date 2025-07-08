define([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/modal'
], function ($, $t, modal) { 
    'use strict';
    $.widget('mage.customLayout', {
        _create: function () {
            var $this = this;
            if($('#coming-soon-pupup-container-fixed').length){
                var options_coming_soon = {
                    type: 'popup',
                    responsive: true,
                    modalClass: 'see-detail-modal-coming-soon-fixed',
                    innerScroll: true,
                    title: $.mage.__('COMING SOON'),
                    buttons: [{
                        text: $.mage.__('Close'),
                        class: '',
                        click: function () {
                            this.closeModal();
                        }
                    }]
                };
    
                var popup_coming_soon = modal(options_coming_soon, $('#coming-soon-pupup-container-fixed'));
                $(".coming-soon-click a").on('click',function(){ 
                    $("#coming-soon-pupup-container-fixed").modal("openModal");
                    return false;
                });
            }
    
            if($('#popup-modal-confirmation-email-my-account').length){
                $('.choice .checkbox').click(function(event){
                    if($(this).is(":checked")){
                        if(event.target.id == 'change-email'){
                            $('#change-password').prop('checked', false);
                            $('.field.password.current.required').hide();
                        }else{
                            $('#change-email').prop('checked', false);
                            $('.field.password.current.required').show();
                        }
                    }
                });
    
                $(document).on("click" , '.popup-confirm-email', function(event){
                    $("#form-validate").submit();
                });
    
                var options_confirmation_email_my_account = {
                    type: 'popup',
                    modalClass: 'modal-popup-confirm-email',
                    responsive: true,
                    innerScroll: true,
                    title: $t('Are you sure you want to change email?'),
                    buttons: [{
                        text: $.mage.__('Cancel'),
                        class: 'popup-cancel-email',
                        click: function () {
                            this.closeModal();
                        }
                    },
                    {
                        text: $t('Confirm'),
                        class: 'popup-confirm-email',
                        click: function () {
                            this.closeModal();
                        }
                    }
                    ]
                };
    
                var popup_confirmation_email_my_account = modal(options_confirmation_email_my_account, $('#popup-modal-confirmation-email-my-account'));
    
                $(".secondary .action.save").click(function(event) {
                    if($('#change-email').prop("checked")){
                        $('#popup-modal-confirmation-email-my-account').modal('openModal');
                    }else{
                        $("#form-validate").submit();
                    }
                });
            }
    
            if($('#popup-modal-newsletter-my-account').length){
                var options_newsletter_my_account = {
                    type: 'popup',
                    modalClass: 'modal-popup-confirm-unsubscribe',
                    responsive: true,
                    innerScroll: true,
                    title: $.mage.__('Are you sure you want to unsubscribe?'),
                    buttons: [{
                        text: $.mage.__('Cancel'),
                        class: 'popup-cancel-unsubscribe',
                        click: function () {
                            this.closeModal();
                        }
                    },
                    {
                        text: $.mage.__('Unsubscribe'),
                        class: 'popup-confirm-unsubscribe',
                        click: function () {
                            this.closeModal();
                        }
                    }
                    ]
                };
                var popup_newsletter_my_account = modal(options_newsletter_my_account, $('#popup-modal-newsletter-my-account'));
                var form, form_active = 1;
                var subscription;
                $(document).on("click" , '#subscription', function(event){
                    if(form_active){
                        if($( this ).prop('checked'))
                        {
                            subscription = 1;
                        }else{
                            subscription = 0;
                        }
                        form = $(document.createElement('form'));
                        $(form).attr("action", BASE_URL+"newsletter/manage/save");
                        $(form).attr("method", "POST");
    
                        var input = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "is_subscribed")
                            .val(subscription);
    
                        $(form).append($(input));
    
                        var formkeys = $("[name='form_key']");
                        var formkey = '';
    
                        formkeys.each(function( index ) {
                            formkey = $( this ).val();
                        });
    
                        var input = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "form_key")
                            .val(formkey);
    
                        $(form).append($(input));
                    }
    
                    if(subscription == 0){
                        if(form_active == 1){
                            form.appendTo(document.body);
                            form_active = 0;
                        }
                        $('#popup-modal-newsletter-my-account').modal('openModal');
                    }else{
                        form.appendTo(document.body);
    
                        $(form).submit();
                    }
                });
    
                $(document).on("click" , '.popup-confirm-unsubscribe', function(event){
                    $(form).submit();
                });
    
                $(document).on("click" , '.popup-cancel-unsubscribe', function(event){
                    $('#subscription').prop('checked', true);
                });
            }
        }
    });
    return $.mage.customLayout;
});