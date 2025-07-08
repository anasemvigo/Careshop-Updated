require(['jquery'], function ($) {
  if ($("body.page-layout-1column.checkout-index-index").length) {
    function validateCheckbox() {
      var allow = true;
      $('.payment-method._active .payment-method-content .primary.checkout').addClass('btn_active');
      $(".payment-method._active .payment-method-content .cus_term_condition input[type='checkbox']").each(function () {
        if (!$(this).is(":checked"))
          allow = false;
      });
      if (!allow)
        $(".payment-method._active .payment-method-content .primary.checkout").removeClass("btn_active");
    }
    $(document).on('click', ".payment-method._active .payment-method-content .cus_term_condition input[type='checkbox']", function () {
      $(this).parent().addClass("checked");
      $(this).parent().removeClass("notchecked");
      if (!$(this).is(":checked"))
        $(this).parent().addClass("notchecked");
      validateCheckbox();
    });

    setInterval(function () {
      $(".payment-method._active .payment-method-content .primary.checkout").prop("disabled", !$(".payment-method._active .payment-method-content .primary.checkout").hasClass("btn_active"));
    }, 1000);
  }
  if ($("body.page-layout-1column.checkout-index-index").length) {
    $('body').addClass('loading-cjs');
    function validateSAForm(s) {
      // ..field required
      valid = true;
      $(".checkout-shipping-method button").addClass('btn-active');
      if ($(".shipping-address-items").length) { } else {
        $(".checkout-shipping-address .field._required .control input,.checkout-shipping-address .field._required .control select,.checkout-shipping-address .field.required .control input,.checkout-shipping-address .field.required .control select").each(function () {
          var _fdname = $(this).attr('name');
          if (($(this).closest('.hidden-fields').length && ($(this).closest('.hidden-fields').attr('style') || '').includes("none")) || _fdname == 'street[1]' || _fdname == 'region_id') { } else {
            if ($(this).val().trim() == '')
              valid = false;
          }
        });
      }
    $(".checkout-shipping-method button").css({
        display: "inline",
        backgroundColor: valid ? "" : "grey",
        color: valid ? "" : "white",
        cursor: valid ? "" : "not-allowed",
        opacity: valid ? "" : "0.6",
        borderColor: valid ? "" : "grey" // Optional: Match the border color to the background
      }).prop("disabled", !valid);
      
      if (!valid)
        $(".checkout-shipping-method button").removeClass('btn-active');
    }
    $(document).on("change input paste", ".checkout-shipping-address .field._required .control input,.checkout-shipping-address .field._required .control select,.checkout-shipping-address .field.required .control input,.checkout-shipping-address .field.required .control select", function () {
      validateSAForm(2);
    });
    /* $(document).on("change input paste", "input#confirm_email,input#customer-email", function() {
       if ($("input#confirm_email").length) {
         var style = $("input#confirm_email").attr('style') || '';
         if (!style.includes("none")) {
           setTimeout(() => {
             var keyEvent = jQuery.Event("keypress");
             keyEvent.keyCode = 13;
             $("input#confirm_email").focus().trigger(keyEvent);
           }, 100);
         }
       }
     });*/
    setInterval(function () {
      if ($(".authentication-wrapper.custom_sign_btns.defult_old_sign:not('chkd')").length) {
        var style = $(".authentication-wrapper.custom_sign_btns.defult_old_sign").attr('style') || '';
        if (style.includes("none")) {
          $(".custom_sign_btn").remove();
        }
        $(".authentication-wrapper.custom_sign_btns.defult_old_sign").addClass('chkd');
      }
    }, 400);

    $(document).on("change", ".box-ship-cust-title input", function () {
      _val = $(this).val();
      $(this).closest('.field').find('select').val(_val).trigger('change');
    });
    var loadingCJS = 0;
    setInterval(function () {
    /*  if ($('div[name="shippingAddress.gender_field"]:not(.chnd)').length) {
        var _fld = $('div[name="shippingAddress.gender_field"] select');
        _html = `
                    <div class="box-ship-cust-title"> 
                        <label for="scust_title_1"> <input type="radio" id="scust_title_1" name="cust-title" value="1" checked="checked"> Mr</label>
                        <label for="scust_title_2"> <input type="radio" id="scust_title_2" name="cust-title" value="2"> Mrs</label>
                    </div>
                `;
        $('div[name="shippingAddress.gender_field"]').addClass('chnd').append(_html);
        _fld.hide();
      }*/
      if (!loadingCJS && $(".loading-cjs .checkout-shipping-address .field._required,.loading-cjs .checkout-shipping-address .field.required").length && $("#customer-email").length) {
        $('body').removeClass('loading-cjs');
        loadingCJS = 1;
        setTimeout(() => validateSAForm(1), 1500);
      }

    }, 1000);
  }
});
