
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'underscore',
    'jquery/ui',
    'jquery/jquery.parsequery'
], function ($, _) {
    'use strict';

    $.widget('mage.bundleOptionSelector', {
        options: {
            optionId: 0
        },

        /**
         * @private
         */
        _create: function createBundleOptionSelector() {
            var $widget = this;

            $widget.setOnImageClickEvents();
            $widget.setOnInputChangeEvents();

            $("body").on('DOMSubtreeModified', "span.price-configured_price", function () {
                $('span.price-final_price').html($('span.price-configured_price').html());
            });

            // Trigger a first click to ensure everything is properly handled
            $('.select-images:not(.multi-select) a.select-link.active:visible', '[data-role=select-option-' + $widget.options.optionId + ']').trigger('click');

            $widget.resetOptionEvents();
        },

        setOnImageClickEvents: function () {
            var $widget = this,
                inputSelector,
                optionSelector;

            $('[data-role=select-option-' + $widget.options.optionId + ']').on('click', ".select-link", function (event) {
                if ($(this).hasClass('multi-select') && $('.container-configurable-color-functional-boxer').length > 0) {

                    console.log("bundle working inside 1");
                    var data_s_id = $(this).attr('data-selection-id');
                    var data_showhideid = $(this).attr('data-showhideid');
                    $('.warrning-error-click-next-step').hide();
                    if ($('body.page-product-boxer-fixed').length) {
                        $('.warrning-error-click-next-step').show();
                    }
                    if (data_showhideid != 1 && ($('body.page-product-officeswivelchair-fixed').length || $('body.page-product-4functionalboxer').length)) {
                        console.log("bundle working inside 2");
                        if (data_showhideid == 2) {
                            console.log("bundle working inside 3");
                            var check_se = $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="1"]');
                            if (check_se.find('.swatch-option.selected').length == 0) {
                                $('.warrning-error-click-next-step').show();
                                return;
                            }
                        }
                        else if (data_showhideid == 3) {
                            console.log("bundle working inside 4");
                            var check_se = $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="2"]');
                            if (check_se.find('.swatch-option.selected').length <= 1) {
                                $('.warrning-error-click-next-step').show();
                                return;
                            }
                        }
                        else if (data_showhideid == 4) {

                            console.log("bundle working inside 5");

                            var check_se = $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="3"]');
                            if (check_se.find('.swatch-option.selected').length == 0) {
                                $('.warrning-error-click-next-step').show();
                                return;
                            }
                        }
                    }

                    $('.container-tab-color-functional-boxer .select-images a.select-link').removeClass('custom-active');
                    $('.container-tab-color-functional-boxer .bundle-selection-data').removeClass('custom-visible-selection');
                    $('.container-tab-color-functional-boxer .bundle-selection-data').removeClass('visible-selection-show');
                    $('.you-are-looking-for-fixed').hide();
                    $(this).addClass('custom-active');
                    $('[data-role=data-option-' + $widget.options.optionId + '][data-selection-id="' + $(this).attr('data-selection-id') + '"]').addClass('custom-visible-selection');

                    if ($('.detech_bundle_together_products').length > 0) {

                        console.log("bundle working inside 6");

                        $('.bundle-option-you-save span').hide();
                        $('.bundle-option-you-save .fixed-discount-price-box-' + data_showhideid).show();
                        $('.swatch-attribute-selected-option-custom-functional-boxer-color').show();
                        if ($('body.page-product-trainingsystem-fixed').length) {
                            if (data_showhideid == 1) {
                                $('.swatch-attribute-selected-option-custom-functional-boxer-color').text($(this).find('span').text());
                            }
                        }
                        else {
                            $('.swatch-attribute-selected-option-custom-functional-boxer-color').text($(this).find('span').text());
                        }

                        var _this = $(this);
                        $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="' + data_showhideid + '"]').addClass('visible-selection-show');
                        $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="' + data_showhideid + '"]').find('.you-are-looking-for-fixed').show();
                        if ($('.detech_is_4_functional_boxer').length > 0) {
                            $(".price-fixed-swatch-detail").each(function () {
                                var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
                                $(this).html('CHF 17.25 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
                            });
                        }
                    }
                    else {
                        console.log("bundle working inside 7");

                        $('.bundle-option-you-save span').hide();
                        $('.bundle-option-you-save .fixed-discount-price-box-' + data_showhideid).show();
                        $('.swatch-attribute-selected-option-custom-functional-boxer-color').show();
                        if ($('body.page-product-trainingsystem-fixed').length) {
                            if (data_showhideid == 1) {
                                $('.swatch-attribute-selected-option-custom-functional-boxer-color').text($(this).find('span').text());
                            }
                        }
                        else {
                            $('.swatch-attribute-selected-option-custom-functional-boxer-color').text($(this).find('span').text());
                        }
                        $('.boxer-price-calculator-fixed').hide();
                        $('.boxer-price-calculator-fixed-' + data_showhideid).show();
                        var _this = $(this);
                        var title_product_bun = $('meta[name="title"]').attr("content");;
                        $('.page-title-wrapper .page-title span.base').text(data_showhideid + ' ' + title_product_bun);
                        if (data_showhideid == 1) {
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="1"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="1"]').find('.you-are-looking-for-fixed').show();
                        }
                        else if (data_showhideid == 2) {
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="1"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="2"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="2"]').find('.you-are-looking-for-fixed').show();
                        }
                        else if (data_showhideid == 3) {
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="1"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="2"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="3"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="3"]').find('.you-are-looking-for-fixed').show();
                        }
                        else if (data_showhideid == 4) {
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="1"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="2"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="3"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="4"]').addClass('visible-selection-show');
                            $('[data-role=data-option-' + $widget.options.optionId + '][data-dependshowhidebyid="4"]').find('.you-are-looking-for-fixed').show();
                        }
                        if ($('body').hasClass('page-product-boxer-fixed')) {
                            if (data_showhideid == 1) {
                                $(".price-fixed-swatch-detail").each(function () {
                                    var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
                                    $(this).html('CHF 42.25 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
                                });
                            }
                            else if (data_showhideid == 2) {
                                $(".price-fixed-swatch-detail").each(function () {
                                    var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
                                    $(this).html('CHF 27.00 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
                                });
                            }
                            else if (data_showhideid == 3) {
                                $(".price-fixed-swatch-detail").each(function () {
                                    var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
                                    $(this).html('CHF 21.00 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
                                });
                            }
                            else if (data_showhideid == 4) {
                                $(".price-fixed-swatch-detail").each(function () {
                                    var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
                                    $(this).html('CHF 17.25 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
                                });
                            }
                        }
                    }

                    console.log("bundle working inside 8");

                    if ($('body').hasClass('product-4-functional-boxer-shorts-skinetic-performance-mid-men-women')) {

                        $('.you-are-looking-for-fixed').hide();


                        $('[data-dependshowhidebyid="1"]').addClass(' custom-visible-selection visible-selection-show');
                        $('[data-dependshowhidebyid="2"]').addClass(' custom-visible-selection visible-selection-show');
                        $('[data-dependshowhidebyid="3"]').addClass(' custom-visible-selection visible-selection-show');
                        $('[data-dependshowhidebyid="4"]').addClass(' custom-visible-selection visible-selection-show');

                        $('.you-are-looking-for-fixed:last-of-type').show();
                    }


                    if (_this.hasClass('active')) {
                        return;
                    }
                }
                // If its a click on an already active element but there's no multi select,  ignore it
                if (_this.hasClass('multi-select') === false && _this.hasClass('active')) {
                    return;
                }

                if (_this.hasClass('multi-select') === false) {
                    $('[data-role=select-option-' + $widget.options.optionId + '] .select-link').removeClass('active');
                }

                inputSelector = $('#bundle-option-' + $widget.options.optionId + '-' + _this.attr('data-selection-id'));

                if (inputSelector.length) {
                    inputSelector.filter('[value=' + _this.attr('data-selection-id') + ']').prop('checked', _this.hasClass('active') === false);
                    $(inputSelector).trigger('change');
                } else {
                    inputSelector = $('#bundle-option-' + $widget.options.optionId);

                    if ($(inputSelector).prop('multiple')) {
                        optionSelector = $('#bundle-option-' + $widget.options.optionId + ' option[value=' + _this.attr('data-selection-id') + ']');
                        optionSelector.prop('selected', _this.hasClass('active') === false);
                    } else {
                        $(inputSelector).val(_this.attr('data-selection-id'));
                    }
                }

                _this.toggleClass('active');
                $(inputSelector).trigger('change');
            });
        },

        setOnInputChangeEvents: function () {
            var $widget = this;

            $('[data-role=select-option-' + $widget.options.optionId + ']').on('change', "input,select", function (event) {
                $('[data-role=data-option-' + $widget.options.optionId + ']').removeClass('visible-selection');

                $('.checkbox:checked, .bundle-option-radio:checked, .bundle-option-select option:selected', '[data-role=select-option-' + $widget.options.optionId + ']').each(function () {
                    $('[data-role=data-option-' + $widget.options.optionId + '][data-selection-id="' + $(this).val() + '"]').addClass('visible-selection');
                });

                $widget.resetOptionEvents();
            });
        },

        resetOptionEvents: function () {
            // Disable everything
            $('.bundle-selection-data:not(.visible-selection) .super-attribute-select').prop('disabled', 'disabled');
            $('.bundle-selection-data:not(.visible-selection) .product-custom-option').prop('disabled', 'disabled');

            // Reenable visible selections
            $('.visible-selection .super-attribute-select').prop('disabled', false);
            $('.visible-selection .product-custom-option').prop('disabled', false);
        }
    });

    return $.mage.bundleOptionSelector;
});
