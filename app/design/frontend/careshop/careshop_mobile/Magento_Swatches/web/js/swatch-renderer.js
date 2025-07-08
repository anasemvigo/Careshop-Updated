define([
    'jquery',
    'underscore',
    'mage/template',
    'mage/smart-keyboard-handler',
    'mage/translate',
    'priceUtils',
    'Magento_Customer/js/customer-data',
    'jquery-ui-modules/widget',
    'jquery/jquery.parsequery',
    'mage/validation/validation'
], function ($, _, mageTemplate, keyboardHandler, $t, priceUtils, customerData) {
    'use strict';


    /**
     * Extend form validation to support swatch accessibility
     */
    $.widget('mage.validation', $.mage.validation, {
        /**
         * Handle form with swatches validation. Focus on first invalid swatch block.
         *
         * @param {jQuery.Event} event
         * @param {Object} validation
         */
        listenFormValidateHandler: function (event, validation) {
            var swatchWrapper, firstActive, swatches, swatch, successList, errorList, firstSwatch;

            this._superApply(arguments);

            swatchWrapper = '.swatch-attribute-options';
            swatches = $(event.target).find(swatchWrapper);

            if (!swatches.length) {
                return;
            }

            swatch = '.swatch-attribute';
            firstActive = $(validation.errorList[0].element || []);
            successList = validation.successList;
            errorList = validation.errorList;
            firstSwatch = $(firstActive).parent(swatch).find(swatchWrapper);

            keyboardHandler.focus(swatches);

            $.each(successList, function (index, item) {
                $(item).parent(swatch).find(swatchWrapper).attr('aria-invalid', false);
            });

            $.each(errorList, function (index, item) {
                $(item.element).parent(swatch).find(swatchWrapper).attr('aria-invalid', true);
            });

            if (firstSwatch.length) {
                $(firstSwatch).focus();
            }
        }
    });
    
    $.widget('mage.SwatchRenderer', {
        options: {
            classes: {
                attributeClass: 'swatch-attribute',
                attributeLabelClass: 'swatch-attribute-label',
                attributeSelectedOptionLabelClass: 'swatch-attribute-selected-option',
                attributeOptionsWrapper: 'swatch-attribute-options',
                attributeInput: 'swatch-input',
                optionClass: 'swatch-option',
                selectClass: 'swatch-select',
                moreButton: 'swatch-more',
                loader: 'swatch-option-loading'
            },
            // option's json config
            jsonConfig: {},

            // swatch's json config
            jsonSwatchConfig: {},

            // selector of parental block of prices and swatches (need to know where to seek for price block)
            selectorProduct: '.product-info-main',

            // selector of price wrapper (need to know where set price)
            selectorProductPrice: '[data-role=priceBox]',

            //selector of product images gallery wrapper
            mediaGallerySelector: '[data-gallery-role=gallery-placeholder]',

            // selector of category product tile wrapper
            selectorProductTile: '.product-item',

            // number of controls to show (false or zero = show all)
            numberToShow: false,

            // show only swatch controls
            onlySwatches: false,

            // enable label for control
            enableControlLabel: true,

            // control label id
            controlLabelId: '',

            // text for more button
            moreButtonText: $t('More'),

            // Callback url for media
            mediaCallback: '',

            // Local media cache
            mediaCache: {},

            // Cache for BaseProduct images. Needed when option unset
            mediaGalleryInitial: [{}],

            // Use ajax to get image data
            useAjax: false,

            /**
             * Defines the mechanism of how images of a gallery should be
             * updated when user switches between configurations of a product.
             *
             * As for now value of this option can be either 'replace' or 'prepend'.
             *
             * @type {String}
             */
            gallerySwitchStrategy: 'replace',

            // whether swatches are rendered in product list or on product page
            inProductList: false,

            // sly-old-price block selector
            slyOldPriceSelector: '.sly-old-price',

            // tier prise selectors start
            tierPriceTemplateSelector: '#tier-prices-template',
            tierPriceBlockSelector: '[data-role="tier-price-block"]',
            tierPriceTemplate: '',
            // tier prise selectors end

            // A price label selector
            normalPriceLabelSelector: '.normal-price .price-label'
        },

        /**
         * Get chosen product
         *
         * @returns int|null
         */
        getProduct: function () {
            var products = this._CalcProducts();

            return _.isArray(products) ? products[0] : null;
        },

        /**
       * Get chosen products
       *
       * @returns Array|null
       */
        getProducts: function () {

            var $widget = this,
                products = [],
                productsoptionarray = {};

            // Generate intersection of products

            if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {

                // console.log("working here inside ynchro-pro-fabric");

                $('.bundle-selection-data .swatch-opt').each(function () {




                    let swatchoptclosetid = $(this).attr('data-sub-id');

                    if (swatchoptclosetid == 59 || swatchoptclosetid == 62) {
                        return;
                    }
                    productsoptionarray = {};


                    $(this).find('.swatch-attribute').each(function () {


                        var id = $(this).attr('attribute-id'),
                            option = $(this).attr('option-selected');


                        // console.log("working here inside swatchoptclosetid " + option);

                        productsoptionarray[id] = option;


                    });

                    let product = _.findKey(training_system_product_object[swatchoptclosetid].index, productsoptionarray);
                    if (product === undefined) {
                        //  product = _.findKey($widget.options.jsonConfig.index, productsoptionarray);
                    }
                    products.push(product);


                });
            }
            else if ($('body').hasClass('boxers-5478-5479-5480-5481-performance-mid')) {
                $('.parent-container-common-show-hide-fixed.swatch-attribute.color').each(function () {





                    var id = $(this).attr('attribute-id'),
                        option = $(this).attr('option-selected');


                    let swatchoptclosetid = $(this).closest('.swatch-opt').attr('data-sub-id');

                    if (training_system_product_object !== undefined) {

                        productsoptionarray[id] = option;

                        //  productsoptionarray.push(element);

                        let product = _.findKey(training_system_product_object[swatchoptclosetid].index, productsoptionarray);
                        products.push(product);

                    }

                });


            } else {

                $('.visible-selection-show .swatch-opt').each(function (i, ele) {
                    $(this).find('.' + $widget.options.classes.attributeClass).each(function () {





                        var id = $(this).attr('attribute-id'),
                            option = $(this).attr('option-selected');
                        productsoptionarray[id] = option;

                    });

                    let product = _.findKey($widget.options.jsonConfig.index, productsoptionarray);
                    products.push(product);

                });

            }

            return products;

            return _.isArray(products) ? products : null;
        },

        /**
         * @private
         */
        _init: function () {
            // Don't render the same set of swatches twice
            if ($(this.element).attr('data-rendered')) {
                return;
            }
            $(this.element).attr('data-rendered', true);

            if (_.isEmpty(this.options.jsonConfig.images)) {
                this.options.useAjax = true;
                // creates debounced variant of _LoadProductMedia()
                // to use it in events handlers instead of _LoadProductMedia()
                this._debouncedLoadProductMedia = _.debounce(this._LoadProductMedia.bind(this), 500);
            }

            if (this.options.jsonConfig !== '' && this.options.jsonSwatchConfig !== '') {
                // store unsorted attributes
                this.options.jsonConfig.mappedAttributes = _.clone(this.options.jsonConfig.attributes);
                this._sortAttributes();
                this._RenderControls();
                this._setPreSelectedGallery();
                $(this.element).trigger('swatch.initialized');
            } else {
                // console.log('SwatchRenderer: No input data received');
            }
            this.options.tierPriceTemplate = $(this.options.tierPriceTemplateSelector).html();
        },

        /**
         * @private
         */
        _sortAttributes: function () {
            this.options.jsonConfig.attributes = _.sortBy(this.options.jsonConfig.attributes, function (attribute) {
                return parseInt(attribute.position, 10);
            });
        },

        /**
         * @private
         */
        _create: function () {
            var options = this.options,
                gallery = $('[data-gallery-role=gallery-placeholder]', '.column.main'),
                productData = this._determineProductData(),
                $main = productData.isInProductView ?
                    this.element.parents('.column.main') :
                    this.element.parents('.product-item-info');

            if (productData.isInProductView) {
                gallery.data('gallery') ?
                    this._onGalleryLoaded(gallery) :
                    gallery.on('gallery:loaded', this._onGalleryLoaded.bind(this, gallery));
            } else {
                options.mediaGalleryInitial = [{
                    'img': $main.find('.product-image-photo').attr('src')
                }];
            }

            this.productForm = this.element.parents(this.options.selectorProductTile).find('form:first');
            this.inProductList = this.productForm.length > 0;
        },

        /**
         * Determine product id and related data
         *
         * @returns {{productId: *, isInProductView: bool}}
         * @private
         */
        _determineProductData: function () {
            // Check if product is in a list of products.
            var productId,
                isInProductView = false;

            productId = this.element.parents('.product-item-details')
                .find('.price-box.price-final_price').attr('data-product-id');

            if (!productId) {
                // Check individual product.
                productId = $('[name=product]').val();
                isInProductView = productId > 0;
            }

            return {
                productId: productId,
                isInProductView: isInProductView
            };
        },

        /**
         * Render controls
         *
         * @private
         */
        _RenderControls: function () {
            var $widget = this,
                container = this.element,
                classes = this.options.classes,
                chooseText = this.options.jsonConfig.chooseText,
                showTooltip = this.options.showTooltip,
                productData = this._determineProductData();

            $widget.optionsMap = {};
            var check_is_chair = false;
            if ($('.check_custom_type_chair').length > 0) {
                check_is_chair = true;
            }
            var true_looking_for_fixed = false;
            if ($('.true_looking_for_fixed').length > 0) {
                true_looking_for_fixed = true;
            }

            if (check_is_chair && productData.isInProductView) {
                var custom_color_chair = '<div class="custom-swatch-attribute-custom-chair custom-swatch-attribute-custom-chair-color"><div class="box-select-common-chair"><a href="javascript:void(0);"><span class="swatch-attribute-label">' + $t("Color") + '</span><strong class="swatch-attribute-selected-option-chair"></strong></a></div></div>';
                container.append(custom_color_chair);
                container.append('<div style="display: none;" class="container-custom-swatch-attribute-grouped-common-attribute container-custom-swatch-attribute-grouped-common-attribute-color"></div>');

                var custom_size_chair = '<div class="custom-swatch-attribute-custom-chair custom-swatch-attribute-custom-chair-size"><div class="box-select-common-chair"><a href="javascript:void(0);"><span class="swatch-attribute-label">' + $t("Size") + '</span><strong class="swatch-attribute-selected-option-chair"></strong></a></div></div>';
                container.append(custom_size_chair);
                container.append('<div style="display: none;" class="container-custom-swatch-attribute-grouped-common-attribute container-custom-swatch-attribute-grouped-common-attribute-size"></div>');
            }

            $.each(this.options.jsonConfig.attributes, function () {
                var item = this,
                    controlLabelId = 'option-label-' + item.code + '-' + item.id,
                    options = $widget._RenderSwatchOptions(item, controlLabelId),
                    select = $widget._RenderSwatchSelect(item, chooseText),
                    input = $widget._RenderFormInput(item),
                    listLabel = '',
                    label = '';

                var getcustomer = customerData.get('customer');
                var check_customer_login = false;
                if (getcustomer().fullname && getcustomer().firstname) {
                    check_customer_login = true;
                }

                // Show only swatch controls
                if ($widget.options.onlySwatches && !$widget.options.jsonSwatchConfig.hasOwnProperty(item.id)) {
                    return;
                }
                
               

                if ($widget.options.enableControlLabel) {
                    
                    
                     
                    var attributeLabelTextFixed = item.label;
                    if (check_is_chair && item.code == 'color' && productData.isInProductView) {
                        attributeLabelTextFixed = $t("Seat") + ' ' + attributeLabelTextFixed;
                    }
                    
                 
                    
                    
                    label +=
                        '<span id="' + controlLabelId + '" class="' + classes.attributeLabelClass + '">' +
                        $('<i></i>').text(attributeLabelTextFixed).html() +
                        '</span>' +
                        '<strong class="' + classes.attributeSelectedOptionLabelClass + '"></strong>';
                }

                if ($widget.inProductList) {
                    $widget.productForm.append(input);
                    input = '';
                    listLabel = 'aria-label="' + $('<i></i>').text(item.label).html() + '"';
                } else {
                    listLabel = 'aria-labelledby="' + controlLabelId + '"';
                }

                if (productData.isInProductView) {
                    var id_code_fixed = 'suggest_' + item.code + '_' + item.id;
                    var other_idea = '<div class="container-submit-your-idea-this-product" style="display: none;">';
                    other_idea += '<div class="show-hide-container-suggest-on-following-product">';
                    other_idea += '<div class="left-container-suggest-on-following">';
                    other_idea += '<p class="title-suggest-on-following">' + $t("Suggest on the following") + '</p>';
                    other_idea += '<div class="container-suggest-on-following-options">';
                    other_idea += '<div class="suggest-on-following-option-left">';
                    other_idea += '<div class="cutom-fixed-checkbox-group"><input type="checkbox" name="color_' + id_code_fixed + '" id="color_' + id_code_fixed + '"> <label for="color_' + id_code_fixed + '">' + $t("Colors") + '</label></div>';
                    other_idea += '<div class="cutom-fixed-checkbox-group"><input type="checkbox" name="function_' + id_code_fixed + '" id="function_' + id_code_fixed + '"> <label for="function_' + id_code_fixed + '">' + $t("Functions") + '</label></div>';
                    other_idea += '<div class="cutom-fixed-checkbox-group"><input type="checkbox" name="other_' + id_code_fixed + '" id="other_' + id_code_fixed + '"> <label for="other_' + id_code_fixed + '">' + $t("Others") + '</label></div>';
                    other_idea += '</div>';
                    other_idea += '<div class="suggest-on-following-option-right">';
                    other_idea += '<div class="cutom-fixed-checkbox-group"><input type="checkbox" name="size_' + id_code_fixed + '" id="size_' + id_code_fixed + '"> <label for="size_' + id_code_fixed + '">' + $t("Sizes") + '</label></div>';
                    other_idea += '<div class="cutom-fixed-checkbox-group"><input type="checkbox" name="feature_' + id_code_fixed + '" id="feature_' + id_code_fixed + '"> <label for="feature_' + id_code_fixed + '">' + $t("Features") + '</label></div>';
                    other_idea += '</div>';
                    other_idea += '</div>';
                    other_idea += '</div>';
                    other_idea += '<div class="submit-your-idea-option-right">';
                    if (!check_customer_login) {
                        other_idea += '<div class="container-content-your-idea-input" style=" padding-bottom: 10px;">';
                        other_idea += '<label for="email_input_submit_' + id_code_fixed + '">' + $t("Your email") + ':</label><input style="height: 4.4rem; border: 1px solid #cdcdcd; border-radius: 0; padding: 0 1rem; width: 100%" class="input-text" id="email_input_submit_' + id_code_fixed + '" name="email_input_submit_' + id_code_fixed + '">';
                        other_idea += '</div>';
                    }
                    other_idea += '<div class="container-content-your-idea-input">';
                    other_idea += '<label for="textarea_submit_' + id_code_fixed + '">' + $t("Submit your idea on this product") + ':</label><textarea id="textarea_submit_' + id_code_fixed + '" name="textarea_submit_' + id_code_fixed + '" rows="4" cols="50"></textarea>';
                    other_idea += '</div>';
                    other_idea += '<div class="button-fixed-container-join-us-win"><button type="button" value="' + $t("Submit your idea(s)") + '">' + $t("Submit your idea(s)") + '</button></div>';
                    other_idea += '</div>';
                    other_idea += '</div>';
                    other_idea += '</div>';
                    var looking_for = '<div class="you-are-looking-for-fixed"><a class="btn" href="javascript:void(0);">' + $t("Haven't found what you are looking for ? ") + '</a></div>' + other_idea;
                    if (item.code == 'color' || item.code == 'base_color_material' || item.code == 'frame_color' || item.code == 'colors_glasses' || item.code == 'color_boardgame' || item.code == 'flower_bags') {
                        if (true_looking_for_fixed && item.code == 'color') {
                            looking_for = '';
                        }
                        var append_c_a =
                            '<div class="parent-container-common-show-hide-fixed ' + classes.attributeClass + ' ' + item.code + '" ' +
                            'attribute-code="' + item.code + '" ' +
                            'attribute-id="' + item.id + '"><div class="fixed-select-' + item.code + '-box box-select-common qoekhwew"><a href="javascript:void(0);">' +
                            label +
                            '</a></div><div class="container-fixed-color-product-view-only container-show-hide-attribute-common fixed-style-type-image-swatch"><div aria-activedescendant="" ' +
                            'tabindex="0" ' +
                            'aria-invalid="false" ' +
                            'aria-required="true" ' +
                            'role="listbox" ' + listLabel +
                            'class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                            options + select +
                            '</div>' + input + looking_for +
                            '</div></div>';

                        if (check_is_chair && productData.isInProductView) {
                            container.find('.container-custom-swatch-attribute-grouped-common-attribute-color').append(append_c_a);
                        }
                        else {
                            container.append(append_c_a);
                        }
                    }
                    else {
                        // Create new control
                        if ((!true_looking_for_fixed && item.code != 'seat_color' && item.code != 'gaslift_size') || (item.code != 'size_boxer' && item.code != 'gaslift_size' && item.code != 'seat_color')) {
                            looking_for = '';
                        }
                        var what_fits_size = '';
                        var append_s_a =
                            '<div class="parent-container-common-show-hide-fixed ' + classes.attributeClass + ' ' + item.code + '" ' +
                            'attribute-code="' + item.code + '" ' +
                            'attribute-id="' + item.id + '"><div class="fixed-select-' + item.code + '-box box-select-common "><a href="javascript:void(0);">' +
                            label +
                            '</a></div><div class="container-fixed-' + item.code + '-product-view-only container-show-hide-attribute-common fixed-style-type-text-swatch"><div aria-activedescendant="" ' +
                            'tabindex="0" ' +
                            'aria-invalid="false" ' +
                            'aria-required="true" ' +
                            'role="listbox" ' + listLabel +
                            'class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                            options + select +
                            '</div>' + input + looking_for + what_fits_size +
                            '</div></div>';

                        if (check_is_chair && productData.isInProductView) {
                            container.find('.container-custom-swatch-attribute-grouped-common-attribute-size').append(append_s_a);
                        }
                        else {
                            container.append(append_s_a);
                        }
                    }
                }
                else {
                    // Create new control
                    container.append(
                        '<div class="' + classes.attributeClass + ' ' + item.code + '" ' +
                        'attribute-code="' + item.code + '" ' +
                        'attribute-id="' + item.id + '">' +
                        label +
                        '<div aria-activedescendant="" ' +
                        'tabindex="0" ' +
                        'aria-invalid="false" ' +
                        'aria-required="true" ' +
                        'role="listbox" ' + listLabel +
                        'class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                        options + select +
                        '</div>' + input +
                        '</div>'
                    );

                    var item_list = container.closest('.item.product.product-item');

                    if (item_list.length && item_list.attr('data-default-color')) {
                        var default_color = item_list.attr('data-default-color');
                        item_list.find('.swatch-option[aria-label="' + default_color + '"]').addClass('selected');
                        var find_code_selected = item_list.find('.swatch-option[aria-label="' + default_color + '"]').closest('div.swatch-attribute').attr('attribute-code');
                        if (find_code_selected == 'colors_glasses' || find_code_selected == 'color') {
                            var opt_id_de = item_list.find('.swatch-option[aria-label="' + default_color + '"]').attr('option-id');;
                            var link_f_de = item_list.attr('data-link');
                            link_f_de = link_f_de + '?' + find_code_selected + '=' + opt_id_de;
                            item_list.find('a').attr('href', link_f_de);
                        }
                    }
                }

                $widget.optionsMap[item.id] = {};

                // Aggregate options array to hash (key => value)
                $.each(item.options, function () {
                    if (this.products.length > 0) {
                        $widget.optionsMap[item.id][this.id] = {
                            price: parseInt(
                                $widget.options.jsonConfig.optionPrices[this.products[0]].finalPrice.amount,
                                10
                            ),
                            products: this.products
                        };
                    }
                });
            });

            if (showTooltip === 1) {
                // Connect Tooltip
                // container
                //     .find('[option-type="1"], [option-type="2"], [option-type="3"]')
                //     .SwatchRendererTooltip();
            }

            // Hide all elements below more button
            $('.' + classes.moreButton).nextAll().hide();

            // Handle events like click or change
            $widget._EventListener();

            // Rewind options
            $widget._Rewind(container);

            //Emulate click on all swatches from Request
            $widget._EmulateSelected($.parseQuery());
            $widget._EmulateSelected($widget._getSelectedAttributes());
        },

        /**
         * Render swatch options by part of config
         *
         * @param {Object} config
         * @param {String} controlId
         * @returns {String}
         * @private
         */
        _RenderSwatchOptions: function (config, controlId) {
            var optionConfig = this.options.jsonSwatchConfig[config.id],
                optionClass = this.options.classes.optionClass,
                sizeConfig = this.options.jsonSwatchImageSizeConfig,
                moreLimit = parseInt(this.options.numberToShow, 10),
                moreClass = this.options.classes.moreButton,
                moreText = this.options.moreButtonText,
                countAttributes = 0,
                productData = this._determineProductData(),
                optionPricesFixed = this.options.jsonConfig.optionPrices,
                getfixattributes = this.options.jsonConfig.attributes,
                html = '';

            if (!this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }
            var options_count = config.options.length;
            $.each(config.options, function (index) {
                var id,
                    type,
                    value,
                    thumb,
                    label,
                    width,
                    height,
                    attr,
                    swatchImageWidth,
                    swatchImageHeight;

                if (!optionConfig.hasOwnProperty(this.id)) {
                    return '';
                }

                // Add more button
                if (moreLimit === countAttributes++) {
                    html += '<a href="javascript:void(0);" class="' + moreClass + '"><span>' + moreText + '</span></a>';
                }

                id = this.id;
                type = parseInt(optionConfig[id].type, 10);
                value = optionConfig[id].hasOwnProperty('value') ?
                    $('<i></i>').text(optionConfig[id].value).html() : '';
                thumb = optionConfig[id].hasOwnProperty('thumb') ? optionConfig[id].thumb : '';
                width = _.has(sizeConfig, 'swatchThumb') ? sizeConfig.swatchThumb.width : 110;
                height = _.has(sizeConfig, 'swatchThumb') ? sizeConfig.swatchThumb.height : 90;
                label = this.label ? $('<i></i>').text(this.label).html() : '';
                attr =
                    ' id="' + controlId + '-item-' + id + '"' +
                    ' index="' + index + '"' +
                    ' aria-checked="false"' +
                    ' aria-describedby="' + controlId + '"' +
                    ' tabindex="0"' +
                    ' option-type="' + type + '"' +
                    ' option-id="' + id + '"' +
                    ' option-label="' + label + '"' +
                    ' aria-label="' + label + '"' +
                    ' option-tooltip-thumb="' + thumb + '"' +
                    ' option-tooltip-value="' + value + '"' +
                    ' role="option"' +
                    ' thumb-width="' + width + '"' +
                    ' thumb-height="' + height + '"';

                swatchImageWidth = _.has(sizeConfig, 'swatchImage') ? sizeConfig.swatchImage.width : 30;
                swatchImageHeight = _.has(sizeConfig, 'swatchImage') ? sizeConfig.swatchImage.height : 20;

                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }
                if (index > 20) {
                    attr += ' hide-swacth-test';
                }
                if (productData.isInProductView) {
                    var myObjColorCode = [
                        { "name": "Pearl White Glossy", "code": "MAPW" },
                        { "name": "BPM Blue", "code": "BLU" },
                        { "name": "Metal Gray Matte", "code": "MMGR" },
                        { "name": "Alu Orange Glossy", "code": "SHAO" },
                        { "name": "Light Green", "code": "LTGR" },
                        { "name": "Aluminium", "code": "ALU" },
                        { "name": "Leather Black", "code": "BLK" },
                        { "name": "Off White", "code": "OWHT" },
                        { "name": "Orange", "code": "ORG" },
                        { "name": "Sky Blue", "code": "SKYB" },
                        { "name": "Ice Blue", "code": "ICEB" },
                        { "name": "Navy Blue", "code": "NVYB" },
                        { "name": "Ice Green", "code": "ICGR" },
                        { "name": "Bamboo", "code": "FBO" },
                        { "name": "Matte Black", "code": "MBLK" },
                        { "name": "Matte Blue", "code": "MTBL" },
                        { "name": "Matte Green", "code": "MGRE" },
                        { "name": "Blue Curacao", "code": "BLCU" },
                        { "name": "Steel Blue", "code": "STBL" },
                        { "name": "Monserat", "code": "MTSR" },
                        { "name": "White Glossy", "code": "SWHT" },
                        { "name": "Yellow Glossy", "code": "SYLW" },
                        { "name": "Red Glossy", "code": "SRED" },
                        { "name": "Alu Matt", "code": "ALMA" },
                        { "name": "Alu Polished", "code": "ALPO" },
                        { "name": "Alu Black Matt", "code": "ALBM" },
                        { "name": "Alu Black Polished", "code": "ALBP" },
                        { "name": "Bamboo (Iron Not Alu)", "code": "BAFE" },
                        { "name": "Black", "code": "BLK" },
                        { "name": "Grey", "code": "GRY" },
                        { "name": "Iceblue", "code": "IBE" },
                        { "name": "Olive", "code": "OLE" },
                        { "name": "Orage", "code": "ORE" },
                        { "name": "Red", "code": "RED" },
                        { "name": "Green", "code": "GRE" },
                        { "name": "Skyblue", "code": "SBE" },
                        { "name": "Springgreen", "code": "SRN" },
                        { "name": "Yellow", "code": "YLW" },
                        { "name": "Black Matt", "code": "BLM" },
                        { "name": "Blue Matt", "code": "BMT" },
                        { "name": "Green Matt", "code": "GMT" },
                        { "name": "Red Shiny", "code": "RSY" },
                        { "name": "White Shiny", "code": "WYS" },
                        { "name": "Yellow Shiny", "code": "YLS" },
                        { "name": "White", "code": "WHT" },
                        { "name": "Anthracite", "code": "ATC" },
                        { "name": "Blue", "code": "BLU" },
                        { "name": "Yellow Green", "code": "YLG" },
                        { "name": "Silver", "code": "SLR" },
                        { "name": "Costa", "code": "CTA" },
                        { "name": "Curacao", "code": "CRO" },
                        { "name": "Demerera", "code": "DMA" },
                        { "name": "Domingo", "code": "DMO" },
                        { "name": "Guava", "code": "GAA" },
                        { "name": "Havana", "code": "HNA" },
                        { "name": "Montserat", "code": "MNT" },
                        { "name": "Paradise", "code": "PDE" },
                        { "name": "Sombrero", "code": "STL" },
                        { "name": "Steel", "code": "SEL" },
                        { "name": "Jet Black", "code": "JBL" },
                        { "name": "Lily White", "code": "LWH" },
                        { "name": "Pearl Gray", "code": "PGR" },
                        { "name": "Alu black polished", "code": "ABP" },
                        { "name": "Alu matt", "code": "AMT" },
                        { "name": "Alu matt black", "code": "AMB" },
                        { "name": "Alu polished", "code": "APD" },
                        { "name": "Wood bamboo", "code": "WBO" },
                        { "name": "Blue Matt Alu", "code": "BMA" },
                        { "name": "DEMI Dark Brown Matt", "code": "DDM" },
                        { "name": "Matt Black Metal", "code": "MBM" },
                        { "name": "Matt Gun Metal Grey", "code": "MGG" },
                        { "name": "Matt Pearl White", "code": "MPW" },
                        { "name": "Shiny Alu Orange", "code": "SAO" },
                        { "name": "Shiny Red", "code": "SRD" },
                        { "name": "Shiny Yellow", "code": "SYW" },
                        { "name": "Fresh Bamboo", "code": "FBO" },
                        { "name": "Dark Brown Matt", "code": "DBRM" },
                        { "name": "Metal Black Matte", "code": "MMBK" }

                    ];

                    var true_looking_for_fixed = false;
                    if ($('.true_looking_for_fixed').length > 0) {
                        true_looking_for_fixed = true;
                    }

                    var color_code_string = '';
                    var color_code_attr = '';
                    for (var i_t_f in myObjColorCode) {
                        if (myObjColorCode[i_t_f].name == label) {
                            color_code_string = '<span class="fixed-color-core-careshop-pro-detail">' + myObjColorCode[i_t_f].code + '</span>';
                            color_code_attr = ' data-color-code="' + myObjColorCode[i_t_f].code + '"';
                        }
                    }

                    if (type === 0) {

                        // Text
                        if (true_looking_for_fixed) {

                            if (controlId == 'option-label-gender_boxer-171') {
                                html += '<div class="container-border-fixed-common-all"><div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                                    '</div><span class="label-fixed-swatch-gender">' + label + '</span><span class="border-fixed-common-all"></span></div>';
                            }
                            else {


                                if ($('body').hasClass('boxers-5478-5479-5480-5481-performance-mid') && (controlId == 'option-label-seat_sizes-161' || controlId === 'option-label-frame_color-163' || controlId === 'option-label-seat_color-192' || controlId === 'option-label-base_color_material-164' || controlId === 'option-label-gaslift_size-200')) {

                                    attr =
                                        ' id="' + controlId + '-item-' + id + '"' +
                                        ' index="' + index + '"' +
                                        ' aria-checked="false"' +
                                        ' aria-describedby="' + controlId + '"' +
                                        ' tabindex="0"' +
                                        ' option-type="' + type + '"' +
                                        ' option-id="' + id + '"' +
                                        ' option-label="' + value + '"' +
                                        ' aria-label="' + label + '"' +
                                        ' option-tooltip-thumb="' + thumb + '"' +
                                        ' option-tooltip-value="' + value + '"' +
                                        ' role="option"' +
                                        ' thumb-width="' + width + '"' +
                                        ' thumb-height="' + height + '"';


                                    html += '<div class="' + optionClass + ' text " ' + attr + '>' + (value ? value : label);

                                    if (controlId == 'option-label-gaslift_size-200') {
                                        var fixed_price_shows = (optionPricesFixed[this.products[0]].finalPrice.amount) ? optionPricesFixed[this.products[0]].finalPrice.amount : 0;
                                        html += '</div><div class="container-name-price-fixed-swatch-detail afsdfr"><span class="label-fixed-swatch-detail">' + label + '</span><span class="price-fixed-swatch-detail fkggfh" data-option-id="' + id + '"> +' + priceUtils.formatPrice(fixed_price_shows) + '</span></div>';

                                    } else {
                                        html += '</div><div class="container-name-price-fixed-swatch-detail hfhdg"><span class="label-fixed-swatch-detail">' + label + '</span></div>';
                                    }




                                    attr =
                                        ' id="' + controlId + '-item-' + id + '"' +
                                        ' index="' + index + '"' +
                                        ' aria-checked="false"' +
                                        ' aria-describedby="' + controlId + '"' +
                                        ' tabindex="0"' +
                                        ' option-type="' + type + '"' +
                                        ' option-id="' + id + '"' +
                                        ' option-label="' + label + '"' +
                                        ' aria-label="' + label + '"' +
                                        ' option-tooltip-thumb="' + thumb + '"' +
                                        ' option-tooltip-value="' + value + '"' +
                                        ' role="option"' +
                                        ' thumb-width="' + width + '"' +
                                        ' thumb-height="' + height + '"';

                                } else {
                                    html += '<div class="container-border-fixed-common-all"><div class="' + optionClass + ' text fixed-size-group-foreach" ' + attr + '>' + (value ? value : label);

                                    html += '</div><span class="border-fixed-common-all"></span>';
                                    html += '</div>';
                                }


                            }
                        }
                        else {

                            if (controlId == 'option-label-seat_sizes-161' || controlId == 'option-label-seat_height-162') {

                                var des_fixed = '';
                                var strArray = label.split(" ");
                                for (var isf = 0; isf < strArray.length; isf++) {
                                    des_fixed = des_fixed + "<span>" + strArray[isf] + " </span>";
                                }

                                html += '<div class="container-seat-sizes-height-fixed-common-all"><div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                                    '</div><span class="container-label-price-fixed-seat-sizes-height"><span class="label-fixed-swatch-seat-sizes-height">' + des_fixed + '</span><span class="price-fixed-swatch-seat-sizes-height">+ ' + priceUtils.formatPrice(0) + '</span></span><span class="border-fixed-common-seat-sizes-height"></span></div>';
                            }
                            else {

                                if ($('body').hasClass('page-product-configurable') || $('body').hasClass('page-product-jacket-fixed') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-women_1') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men')  || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men-women')) {

                                    html += '<div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) + '</div>';
                                    var fixed_price_show = (optionPricesFixed[this.products[0]].finalPrice.amount) ? optionPricesFixed[this.products[0]].finalPrice.amount : 0;
                                    html += '<div class="container-name-price-fixed-swatch-detail  rhdrhdrh"><span class="price-fixed-swatch-detail  ghdhgf" data-product-id="'+ this.products[0] +'" data-option-id="' + id + '">' + priceUtils.formatPrice(fixed_price_show) + color_code_string + '</span></div>';
                                }
                                else {

                                    html += '<div class="' + optionClass + ' size-dynamic-price text" ' + attr + '>' + (value ? value : label) + '</div>';
                                    
                                         var fixed_price_shows = (optionPricesFixed[this.products[0]].finalPrice.amount) ? optionPricesFixed[this.products[0]].finalPrice.amount : 0;
                                     
                                        
                                        
                                    html += '<div class="container-name-price-fixed-swatch-detail cvvbc"><span class="price-fixed-swatch-detail  hfgh" data-option-id="' + id + '">' + priceUtils.formatPrice(fixed_price_shows) +'</span></div>';
                                }
                            }
                        }
                    } else if (type === 1) {

                        // Color
                        var fixed_price_show = (optionPricesFixed[this.products[0]].finalPrice.amount) ? optionPricesFixed[this.products[0]].finalPrice.amount : 0;
                        if (controlId == 'option-label-frame_color-163' || controlId == 'option-label-base_color_material-164') {

                            fixed_price_show = 0;
                        }
                        var format_c = priceUtils.formatPrice(fixed_price_show);
                        var color_dynamic = '';
                        if ($('body').hasClass('page-product-configurable') || $('body').hasClass('page-product-jacket-fixed') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-women_1') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men')  || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men-women')) {
                            var pr_by_opt = [];
                            $.each(getfixattributes, function () {
                                var item_a = this;
                                if (item_a.code == "color") {
                                    var opt_a = item_a.options;
                                    $.each(opt_a, function () {
                                        var item_pr = this;
                                        if (item_pr.id == id) {
                                            pr_by_opt = item_pr.products;
                                            return;
                                        }
                                    });
                                }
                            });
                            if (pr_by_opt.length > 0) {
                                var list_price = [];
                                for (var isfdd = 0; isfdd < pr_by_opt.length; isfdd++) {
                                    var pr_id_f = pr_by_opt[isfdd];
                                    var price_pr_id_f = optionPricesFixed[pr_id_f].finalPrice.amount;
                                    list_price.push(price_pr_id_f);
                                }
                                let minValue = Math.min(...list_price);
                                let maxValue = Math.max(...list_price);
                                if (minValue == maxValue) {
                                    format_c = priceUtils.formatPrice(minValue);
                                }
                                else {
                                    format_c = priceUtils.formatPrice(maxValue) + '-' + minValue + '.00';
                                    
                                    $('#product_addtocart_form').prepend('<input type="hidden" class="rudrapriceCurrency" value = "'+format_c+'" />');
                               
                                    color_dynamic = ' color-dynamic-price';
                                }
                            }
                        }



                        html += '<div class="' + optionClass + color_dynamic + ' color" ' + attr + color_code_attr +
                            ' style="background: ' + value +
                            ' no-repeat center; background-size: initial;">' + '' +
                            '</div><div class="container-name-price-fixed-swatch-detail vhgh"><span class="label-fixed-swatch-detail">' + label + '</span><span class="price-fixed-swatch-detail hrtrt" option-id="'+id+'">' + format_c + color_code_string + '</span></div>';
                    } else if (type === 2) {
                        // Image

                        var fixed_price_show = (optionPricesFixed[this.products[0]].finalPrice.amount) ? optionPricesFixed[this.products[0]].finalPrice.amount : 0;
                        if (controlId == 'option-label-frame_color-163' || controlId == 'option-label-base_color_material-164') {
                            fixed_price_show = 0;
                        }

                        if ($('body').hasClass('page-product-officeswivelchair-fixed boxers-5478-5479-5480-5481-performance-mid') && (controlId == 'option-label-frame_color-163' || controlId == 'option-label-base_color_material-164')) {

                            html += '<div class="' + optionClass + ' image" ' + attr + color_code_attr +
                                ' style="background: url(' + value + ') no-repeat center; background-size: initial;width:' +
                                swatchImageWidth + 'px; height:' + swatchImageHeight + 'px">' + '' +
                                '</div><div class="container-name-price-fixed-swatch-detail fdhh"><span class="label-fixed-swatch-detail">' + label + '</span><span class="price-fixed-swatch-detail">_' + color_code_string + '</span></div>';

                        } else {

                            html += '<div class="' + optionClass + ' image" ' + attr + color_code_attr +
                                ' style="background: url(' + value + ') no-repeat center; background-size: initial;width:' +
                                swatchImageWidth + 'px; height:' + swatchImageHeight + 'px">' + '' +
                                '</div><div class="container-name-price-fixed-swatch-detail dfghfd"><span class="label-fixed-swatch-detail">' + label + '</span><span class="price-fixed-swatch-detail syty">' + priceUtils.formatPrice(fixed_price_show) + color_code_string + '</span></div>';
                        }

                    } else if (type === 3) {
                        // console.log("option-label-frame_color 14" + controlId);
                        // Clear
                        html += '<div class="' + optionClass + '" ' + attr + '></div>';
                    } else {
                        // console.log("option-label-frame_color 15" + controlId);
                        // Default
                        html += '<div class="' + optionClass + '" ' + attr + '>' + label + '</div>';
                    }
                }
                else {
                    // console.log("option-label-frame_color 16" + controlId);
                    if (type === 0) {
                        // Text
                        html += '<div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                            '</div>';
                    } else if (type === 1) {
                        // Color
                        html += '<div class="' + optionClass + ' color" ' + attr +
                            ' style="background: ' + value +
                            ' no-repeat center; background-size: initial;">' + '' +
                            '</div>';
                    } else if (type === 2) {
                        // Image
                        html += '<div class="' + optionClass + ' image" ' + attr +
                            ' style="background: url(' + value + ') no-repeat center; background-size: initial;width:' +
                            swatchImageWidth + 'px; height:' + swatchImageHeight + 'px">' + '' +
                            '</div>';
                    } else if (type === 3) {
                        // Clear
                        html += '<div class="' + optionClass + '" ' + attr + '></div>';
                    } else {
                        // Default
                        html += '<div class="' + optionClass + '" ' + attr + '>' + label + '</div>';
                    }
                }
                if (index == 20) {
                    html += '<div class="show-mode-swatch">+' + (options_count - 20) + '</div>';
                }
            });

            return html;
        },

        /**
         * Render select by part of config
         *
         * @param {Object} config
         * @param {String} chooseText
         * @returns {String}
         * @private
         */
        _RenderSwatchSelect: function (config, chooseText) {
            var html;

            if (this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }

            html =
                '<select class="' + this.options.classes.selectClass + ' ' + config.code + '">' +
                '<option value="0" option-id="0">' + chooseText + '</option>';

            $.each(config.options, function () {
                var label = this.label,
                    attr = ' value="' + this.id + '" option-id="' + this.id + '"';

                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }

                html += '<option ' + attr + '>' + label + '</option>';
            });

            html += '</select>';

            return html;
        },

        /**
         * Input for submit form.
         * This control shouldn't have "type=hidden", "display: none" for validation work :(
         *
         * @param {Object} config
         * @private
         */
        _RenderFormInput: function (config) {
            return '<input class="' + this.options.classes.attributeInput + ' super-attribute-select" ' +
                'name="super_attribute[' + config.id + ']" ' +
                'type="text" ' +
                'value="" ' +
                'data-selector="super_attribute[' + config.id + ']" ' +
                'data-validate="{required: true}" ' +
                'aria-required="true" ' +
                'aria-invalid="false">';
        },

        /**
         * Event listener
         *
         * @private
         */
        _EventListener: function () {
            var $widget = this,
                options = this.options.classes,
                target;

            // console.log("_EventListener is calling")


            $widget.element.on('click', '.' + options.optionClass, function () {
                return $widget._OnClick($(this), $widget);
            });

            $widget.element.on('change', '.' + options.selectClass, function () {
                return $widget._OnChange($(this), $widget);
            });

            $widget.element.on('click', '.' + options.moreButton, function (e) {
                e.preventDefault();

                return $widget._OnMoreClick($(this));
            });

            $widget.element.on('keydown', function (e) {
                if (e.which === 13) {
                    target = $(e.target);

                    if (target.is('.' + options.optionClass)) {
                        return $widget._OnClick(target, $widget);
                    } else if (target.is('.' + options.selectClass)) {
                        return $widget._OnChange(target, $widget);
                    } else if (target.is('.' + options.moreButton)) {
                        e.preventDefault();

                        return $widget._OnMoreClick(target);
                    }
                }
            });
        },


        /**
         * Load media gallery using ajax or json config.
         *
         * @private
         */
        _loadMedia: function () {
            
            // console.log("_loadMedia is trigger");
            
            var $main = this.inProductList ?
                this.element.parents('.product-item-info') :
                this.element.parents('.column.main'),
                images;

            if (this.options.useAjax) {
                this._debouncedLoadProductMedia();
            } else {
                images = this.options.jsonConfig.images[this.getProduct()];

                if (!images) {
                    images = this.options.mediaGalleryInitial;
                }
                this.updateBaseImage(this._sortImages(images), $main, !this.inProductList);






            }
        },

        /**
         * Sorting images array
         *
         * @private
         */
        _sortImages: function (images) {


            return _.sortBy(images, function (image) {
                return parseInt(image.position, 20);
            });
        },

        /**
         * Event for swatch options
         *
         * @param {Object} $this
         * @param {Object} $widget
         * @private
         */
        _OnClick: function ($this, $widget) {





           if ($this.hasClass('selected')) {
                return false;
            }
            
            if ($this.hasClass('selected') && $this.closest('.is_training_system_fixed_uncheck2').length) {
                $('#bundle-option-20').val('54');
            } 
            
            if ($('body').hasClass('catalog-category-view')) {
                var get_code_attr = $this.closest('.swatch-attribute').attr('attribute-code');
                if (get_code_attr == 'colors_glasses' || get_code_attr == 'color') {
                    var opt_id = $this.attr('option-id');
                    var link_f = $this.closest('li.product-item').attr('data-link');
                    link_f = link_f + '?' + get_code_attr + '=' + opt_id;
                    $this.closest('li.product-item').find('a').attr('href', link_f);
                }
            }

            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                $wrapper = $this.parents('.' + $widget.options.classes.attributeOptionsWrapper),
                $label = $parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                attributeId = $parent.attr('attribute-id'),
                $input = $parent.find('.' + $widget.options.classes.attributeInput),
                optionPricesFixedOnclick = this.options.jsonConfig.optionPrices,
                getfixattributesOnclick = this.options.jsonConfig.index,
                checkAdditionalData = JSON.parse(this.options.jsonSwatchConfig[attributeId]['additional_data']);

            if ($('.container-swatch-opt-configurable-chair').length > 0) {

                // console.log("alu matt configurable");


                var count_item_attribute = $this.closest('.container-custom-swatch-attribute-grouped-common-attribute').find('.parent-container-common-show-hide-fixed').length;
                var selected_item_attribute = $this.closest('.container-custom-swatch-attribute-grouped-common-attribute').find('.parent-container-common-show-hide-fixed .swatch-option.selected').length;
                if ($this.hasClass('selected')) {
                    selected_item_attribute = selected_item_attribute - 1;
                } else {
                    selected_item_attribute = selected_item_attribute + 1;
                }
                if (count_item_attribute <= selected_item_attribute) {
                    $('.custom-swatch-attribute-custom-chair').removeClass('actived');
                    $('.container-custom-swatch-attribute-grouped-common-attribute').removeClass('actived');
                    $('.container-custom-swatch-attribute-grouped-common-attribute').hide();
                }
                var option_id_chair = $this.attr('option-id'), data_image;
                if ($this.closest('.swatch-attribute').hasClass('color')) {
                    if (!$this.hasClass('selected')) {
                        $(".chair-product.seat").closest('li').removeClass('active');
                        $(".chair-product.seat[data-option='" + option_id_chair + "']").closest('li').addClass('active');
                    } else {
                        $(".chair-product.seat").closest('li').removeClass('active');
                    }
                    //$(".color-swatch-options-container-left").find('li').removeClass('active');
                    $(".color-swatch-options-container-left").find("a[data-option='" + option_id_chair + "']").closest("li").addClass('active');
                    data_image = $(".chair-product.frame[data-option='" + option_id_chair + "']").data('image');
                } else if ($this.closest('.swatch-attribute').hasClass('frame_color')) {
                    if (!$this.hasClass('selected')) {
                        $(".chair-product.frame").closest('li').removeClass('active');
                        $(".chair-product.frame[data-option='" + option_id_chair + "']").closest('li').addClass('active');
                    } else {
                        $(".chair-product.frame").closest('li').removeClass('active');
                    }
                    data_image = $(".chair-product.frame[data-option='" + option_id_chair + "']").data('image');
                } else if ($this.closest('.swatch-attribute').hasClass('base_color_material')) {
                    if (!$this.hasClass('selected')) {
                        $(".chair-product.material").closest('li').removeClass('active');
                        $(".chair-product.material[data-option='" + option_id_chair + "']").closest('li').addClass('active');
                    } else {
                        $(".chair-product.material").closest('li').removeClass('active');
                    }
                    data_image = $(".chair-product.material[data-option='" + option_id_chair + "']").data('image');
                }


                // console.log("tony code is working here");
                
                var api_product = $('[data-gallery-role=gallery-placeholder]').data('gallery');
                if (api_product) {
                    var arr_image = [];
                    $(".container-fixed-gallery-images-thumb ul li").each(function (index) {
                        var url_image = $(this).find('a').data('image');
                        var thumb = $(this).find('a').data('thumb');
                        var caption = $(this).find('a').data('caption');
                        if ($('.chair-product.seat').closest('li').hasClass('active') && $('.chair-product.frame').closest('li').hasClass('active') && $('.chair-product.material').closest('li').hasClass('active')) {
                            if ($(this).hasClass('active')) {
                                if ($(this).find('a').hasClass('seat')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: true
                                    });
                                } else {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else if ($('.chair-product.seat').closest('li').hasClass('active') && $('.chair-product.frame').closest('li').hasClass('active')) {
                            if (url_image == data_image) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                if ($(this).hasClass('active') || $(this).find('a').hasClass('material')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else if ($('.chair-product.seat').closest('li').hasClass('active') && $('.chair-product.material').closest('li').hasClass('active')) {
                            if (url_image == data_image) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                if ($(this).hasClass('active') || $(this).find('a').hasClass('frame')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else if ($('.chair-product.frame').closest('li').hasClass('active') && $('.chair-product.material').closest('li').hasClass('active')) {
                            if (url_image == data_image) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                if ($(this).hasClass('active') || $(this).find('a').hasClass('seat')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else if ($('.chair-product.seat').closest('li').hasClass('active')) {
                            if (url_image == data_image) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                if ($(this).hasClass('active') || $(this).find('a').hasClass('frame') || $(this).find('a').hasClass('material')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else if ($('.chair-product.frame').closest('li').hasClass('active')) {
                            if (url_image == data_image) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                if ($(this).hasClass('active') || $(this).find('a').hasClass('seat') || $(this).find('a').hasClass('material')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else if ($('.chair-product.material').closest('li').hasClass('active')) {
                            if (url_image == data_image) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                if ($(this).hasClass('active') || $(this).find('a').hasClass('frame') || $(this).find('a').hasClass('seat')) {
                                    arr_image.push({
                                        img: url_image,
                                        thumb: thumb,
                                        caption: caption,
                                        isMain: false
                                    });
                                }
                            }
                        } else {
                            if ($(this).hasClass('main_image')) {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: true
                                });
                            } else {
                                arr_image.push({
                                    img: url_image,
                                    thumb: thumb,
                                    caption: caption,
                                    isMain: false
                                });
                            }
                        }
                    });
                    
                    //  console.log("trigger api_product")
                     
                    api_product.updateData(arr_image);
                    api_product.first();
                }
            }
            else {
                var option_id_other = $this.attr('option-id');
                if (!$this.hasClass('selected')) {



                    if (!$("body").hasClass("boxers-5479-5481-performance-mid") && !$('body').hasClass('boxers-5479-performance-mid') && !$("body").hasClass("boxers-5481-performance-mid") && !$('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') && !$('body').hasClass('page-product-configurable')) {
                        $(".color-swatch-options-container-left").find('li').removeClass('active');
                    }
                    // for jacket product
                    if ($(this).hasClass('color')) {
                        $(".color-swatch-options-container-left").find("a[data-option='" + option_id_other + "']").closest("li").addClass('active');
                    }

                } else {
                    $(".chair-product.material").closest('li').removeClass('active');
                }

                //  $('.container-show-hide-attribute-common').removeClass('active');
                // $('.box-select-common').removeClass('active');
            }

            if ($widget.inProductList) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }

            if ($this.hasClass('disabled')) {
                return;
            }
            
         

            if ($this.hasClass('selected')) {
                 
                $parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                $input.val('');
                $label.text('');
                $this.attr('aria-checked', false);
                if ($this.attr('option-id') == '5524' || $this.attr('option-id') == '5525') {
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach').removeClass('added-disabled-size').attr('aria-checked', false);
                    $this.closest('.container-border-fixed-common-all').removeClass('atc');
                }
            } else {
                
             
                
                $parent.attr('option-selected', $this.attr('option-id')).find('.selected').removeClass('selected');
                if ($this.attr('data-color-code')) {
                    $label.text($this.attr('option-label') + ' ' + '(' + $this.attr('data-color-code') + ')');
                    $label.closest('a').removeClass('not-option');
                }
                else {
                    $label.text($this.attr('option-label'));
                    $label.closest('a').removeClass('not-option');
                }
                $input.val($this.attr('option-id'));
                $input.attr('data-attr-name', this._getAttributeCodeById(attributeId));
                $this.addClass('selected');
                $widget._toggleCheckedAttributes($this, $wrapper);

                if ($this.attr('option-id') == '5524') {
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach').removeClass('added-disabled-size').attr('aria-checked', false);;

                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach[option-id="5526"]').removeClass('selected');
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach[option-id="5527"]').removeClass('selected');
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach[option-id="5528"]').removeClass('selected');

                    $('.container-border-fixed-common-all').removeClass('atc');
                    $this.closest('.container-border-fixed-common-all').addClass('atc');
                    $this.closest('.fixed-size-group-foreach').each(function (index) {
                        if ($(this).attr('option-id') == '5527' || $(this).attr('option-id') == '5526' || $(this).attr('option-id') == '5528') {

                            //  if (!$("body").hasClass("boxers-5479-5481-performance-mid")) {
                            $(this).addClass('added-disabled-size').attr('disabled', true);

                            //    }
                        }
                    });
                }

                if ($this.attr('option-id') == '5525') {
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach').removeClass('added-disabled-size');

                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach[option-id="5529"]').removeClass('selected');
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach[option-id="5530"]').removeClass('selected');
                    $this.closest('.visible-selection-show').find('.fixed-size-group-foreach[option-id="5531"]').removeClass('selected');



                    $('.container-border-fixed-common-all').removeClass('atc');
                    $this.closest('.container-border-fixed-common-all').addClass('atc');
                    $this.closest('.fixed-size-group-foreach').each(function (index) {
                        if ($(this).attr('option-id') == '5529' || $(this).attr('option-id') == '5530' || $(this).attr('option-id') == '5531') {
                            //  if (!$("body").hasClass("box4ers-579-5481-performance-mid")) {
                            $(this).addClass('added-disabled-size').attr('disabled', true);
                            // }
                        }
                    });
                }

                if ($("body").hasClass("boxers-5479-5481-performance-mid") || $('body').hasClass('boxers-5479-performance-mid') || $("body").hasClass("boxers-5481-performance-mid")) {
                    // console.log("alu matt workign here also ");
                    $widget._loadMedia();
                }
            }


            // console.log("alu matt ");

            if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {


                if ($('.swatch-attribute[attribute-code]').length && $('.swatch-attribute[option-selected]').length) {


                    // console.log("alu matt base material is working do your stuff here ");

                    $widget._loadMedia();
                }
            }


            $widget._Rebuild();





            if ($widget.element.parents($widget.options.selectorProduct)
                .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
            ) {
                $widget._UpdatePrice();
            }

            $(document).trigger('updateMsrpPriceBlock',
                [
                    _.findKey($widget.options.jsonConfig.index, $widget.options.jsonConfig.defaultValues),
                    $widget.options.jsonConfig.optionPrices
                ]);
            if (!$('.container-swatch-opt-configurable-chair').length > 0 && !$('body').hasClass('page-product-configurable')) {
                if (parseInt(checkAdditionalData['update_product_preview_image'], 10) === 1) {
                    $widget._loadMedia();
                }
            }

            $input.trigger('change');
            if ($('.swatch-attribute[attribute-code]').length && $('.swatch-attribute[option-selected]').length) {

                if ($('.swatch-attribute[attribute-code]').length === $('.swatch-attribute[option-selected]').length && $('#product-addtocart-button').length && (($('.swatch-attribute.color').find('.swatch-option.selected').length >= 1 && ($('.swatch-attribute.size').find('.swatch-option.selected').length >= 1 )) || ($('.swatch-attribute.colors_glasses').find('.swatch-option.selected').length >= 1 && $('.swatch-attribute.size_lens_radiation').find('.swatch-option.selected').length >= 1))) {
                    // console.log("enable-add-to-cart-product-button disabled from 1");
                    $('#product-addtocart-button').removeClass('disable-add-to-cart-product-button');
                    $('#product-addtocart-button').addClass('enable-add-to-cart-product-button');
                }
                else {
                    if ($('#product-addtocart-button').length) {


                        if ($('body').hasClass('page-product-trainingsystem-fixed')) {
                            
                              if($('.swatch-attribute[option-selected]').length > 0){

                            // console.log("enable-add-to-cart-product-button disabled from 2");

                            $('#product-addtocart-button').removeClass('disable-add-to-cart-product-button');
                            $('#product-addtocart-button').addClass('enable-add-to-cart-product-button');
                              }else {
                                  
                                  $('#product-addtocart-button').removeClass('enable-add-to-cart-product-button');
                                  
                                   $('#product-addtocart-button').addClass('disable-add-to-cart-product-button');
                                  
                              }

                        } else {
                            if ($('body').hasClass('page-product-configurable')) {

                            } else {
                                $('#product-addtocart-button').addClass('disable-add-to-cart-product-button');
                                $('#product-addtocart-button').removeClass('enable-add-to-cart-product-button');
                            }

                            // console.log("enable-add-to-cart-product-button disabled from 3");


                        }

                    }
                }
            }

            if ($('.container-swatch-opt-configurable-chair').length > 0) {
                var selected_item_arr = [];
                if ($this.closest('.container-custom-swatch-attribute-grouped-common-attribute').find('.parent-container-common-show-hide-fixed .swatch-option.selected').length > 0) {
                    var selected_item_attribute_get_label = $this.closest('.container-custom-swatch-attribute-grouped-common-attribute').find('.parent-container-common-show-hide-fixed .swatch-option.selected');
                    selected_item_attribute_get_label.each(function (index) {
                        if ($(this).attr('data-color-code')) {
                            selected_item_arr[index] = $(this).attr('option-label') + '(' + $(this).attr('data-color-code') + ')';
                        }
                        else {
                            selected_item_arr[index] = $(this).attr('option-label');
                        }
                    });
                }
                if (selected_item_arr.length > 0) {
                    var result_join_text = selected_item_arr.join(', ');
                    if ($this.closest('.parent-container-common-show-hide-fixed').attr('attribute-code') == 'color' || $this.closest('.parent-container-common-show-hide-fixed').attr('attribute-code') == 'frame_color' || $this.closest('.parent-container-common-show-hide-fixed').attr('attribute-code') == 'base_color_material') {
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-color .swatch-attribute-selected-option-chair').text(result_join_text);
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-color .box-select-common-chair').addClass('selected');
                    }
                    else {
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-size .swatch-attribute-selected-option-chair').text(result_join_text);
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-size .box-select-common-chair').addClass('selected');
                    }
                }
                else {
                    if ($this.closest('.parent-container-common-show-hide-fixed').attr('attribute-code') == 'color' || $this.closest('.parent-container-common-show-hide-fixed').attr('attribute-code') == 'frame_color' || $this.closest('.parent-container-common-show-hide-fixed').attr('attribute-code') == 'base_color_material') {
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-color .swatch-attribute-selected-option-chair').text('');
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-color .box-select-common-chair').removeClass('selected');
                    }
                    else {
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-size .swatch-attribute-selected-option-chair').text('');
                        $this.closest('.container-swatch-opt-configurable-chair').find('.custom-swatch-attribute-custom-chair-size .box-select-common-chair').removeClass('selected');
                    }
                }
            }
            if (($('body').hasClass('page-product-configurable') || $('body').hasClass('page-product-jacket-fixed') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-women_1') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men')  || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men-women') ) && $this.hasClass('color')) {
                var g_attribute_code = $this.closest('div.swatch-attribute').attr('attribute-code');
                var g_attribute_id_size = $('.parent-container-common-show-hide-fixed.swatch-attribute.size').attr('attribute-id');
                var g_attribute_id = $this.closest('div.swatch-attribute').attr('attribute-id');
                var g_option_selected = parseInt($this.closest('div.swatch-attribute').attr('option-selected'));
                if (g_attribute_code == 'color') {
                    var data_max_price_fixed = parseInt($('.data-max-price-fixed').attr('data-max-price'));
                    $('.container-fixed-size-product-view-only .container-name-price-fixed-swatch-detail').each(function () {
                        var data_option_id_size_option = parseInt($(this).find('.price-fixed-swatch-detail').attr('data-option-id'));
                        var get_this_c = $(this);

                        $.each(getfixattributesOnclick, function (key_index, val_index) {
                            g_attribute_id = parseInt(g_attribute_id);
                            g_attribute_id_size = parseInt(g_attribute_id_size);
                            if (val_index[g_attribute_id] == g_option_selected && val_index[g_attribute_id_size] == data_option_id_size_option) {
                                var fixed_price_show_size = (optionPricesFixedOnclick[key_index].finalPrice.amount) ? optionPricesFixedOnclick[key_index].finalPrice.amount : 0;
                                if (fixed_price_show_size < data_max_price_fixed) {
                                    get_this_c.find('.price-fixed-swatch-detail').css('color', '#303030');
                                }
                                else {
                                    get_this_c.find('.price-fixed-swatch-detail').css('color', '#303030');
                                }
                                get_this_c.find('.price-fixed-swatch-detail').text(priceUtils.formatPrice(fixed_price_show_size));
                                return;
                            }
                        });
                    });
                }
            }
        },

        /**
         * Get human readable attribute code (eg. size, color) by it ID from configuration
         *
         * @param {Number} attributeId
         * @returns {*}
         * @private
         */
        _getAttributeCodeById: function (attributeId) {
            var attribute = this.options.jsonConfig.mappedAttributes[attributeId];

            return attribute ? attribute.code : attributeId;
        },

        /**
         * Toggle accessibility attributes
         *
         * @param {Object} $this
         * @param {Object} $wrapper
         * @private
         */
        _toggleCheckedAttributes: function ($this, $wrapper) {
            $wrapper.attr('aria-activedescendant', $this.attr('id'))
                .find('.' + this.options.classes.optionClass).attr('aria-checked', false);
            $this.attr('aria-checked', true);
        },

        /**
         * Event for select
         *
         * @param {Object} $this
         * @param {Object} $widget
         * @private
         */
        _OnChange: function ($this, $widget) {
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                attributeId = $parent.attr('attribute-id'),
                $input = $parent.find('.' + $widget.options.classes.attributeInput);

            if ($widget.productForm.length > 0) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }

            if ($this.val() > 0) {
                $parent.attr('option-selected', $this.val());
                $input.val($this.val());
            } else {
                $parent.removeAttr('option-selected');
                $input.val('');
            }

            $widget._Rebuild();
            $widget._UpdatePrice();
            $widget._loadMedia();
            $input.trigger('change');
        },

        /**
         * Event for more switcher
         *
         * @param {Object} $this
         * @private
         */
        _OnMoreClick: function ($this) {
            $this.nextAll().show();
            $this.blur().remove();
        },

        /**
         * Rewind options for controls
         *
         * @private
         */
        _Rewind: function (controls) {

            controls.find('div[option-id], option[option-id]').removeClass('disabled').removeAttr('disabled');
            controls.find('div[option-empty], option[option-empty]')
                .attr('disabled', true)
                .addClass('disabled')
                .attr('tabindex', '-1');
        },

        /**
         * Rebuild container
         *
         * @private
         */
        _Rebuild: function () {
            var $widget = this,
                controls = $widget.element.find('.' + $widget.options.classes.attributeClass + '[attribute-id]'),
                selected = controls.filter('[option-selected]');

            // Enable all options
            $widget._Rewind(controls);

            // done if nothing selected
            if (selected.length <= 0) {
                return;
            }

            // Disable not available options
            controls.each(function () {
                var $this = $(this),
                    id = $this.attr('attribute-id'),
                    products = $widget._CalcProducts(id);

                if (selected.length === 1 && selected.first().attr('attribute-id') === id) {
                    return;
                }

                $this.find('[option-id]').each(function () {
                    var $element = $(this),
                        option = $element.attr('option-id');

                    if (!$widget.optionsMap.hasOwnProperty(id) || !$widget.optionsMap[id].hasOwnProperty(option) ||
                        $element.hasClass('selected') ||
                        $element.is(':selected')) {
                        return;
                    }

                    if ($("body").hasClass("boxers-5479-5481-performance-mid") || $('body').hasClass('boxers-5479-performance-mid') || $("body").hasClass("boxers-5481-performance-mid")) {

                        if (option == 5521 || option == 5522 || option == 5523 || option == 5524 || option == 5525) {
                            return;
                        }

                    }


                    if ($('body').hasClass('page-product-configurable')) {
                        return;

                    }

                    if (_.intersection(products, $widget.optionsMap[id][option].products).length <= 0) {

                        // console.log("intersection disabled is working here");

                        $element.attr('disabled', true).addClass('disabled').attr('aria-checked', false);
                    }
                });
            });
        },

        /**
         * Get selected product list
         *
         * @returns {Array}
         * @private
         */
        _CalcProducts: function ($skipAttributeId) {
            var $widget = this,
                products = [];

            // Generate intersection of products
            $widget.element.find('.' + $widget.options.classes.attributeClass + '[option-selected]').each(function () {
                var id = $(this).attr('attribute-id'),
                    option = $(this).attr('option-selected');

                if ($skipAttributeId !== undefined && $skipAttributeId === id) {
                    return;
                }

                if (!$widget.optionsMap.hasOwnProperty(id) || !$widget.optionsMap[id].hasOwnProperty(option)) {
                    return;
                }

                if (products.length === 0) {
                    products = $widget.optionsMap[id][option].products;
                } else {
                    products = _.intersection(products, $widget.optionsMap[id][option].products);
                }
            });

            return products;
        },

        /**
         * Update total price
         *
         * @private
         */
        _UpdatePrice: function () {


            var $widget = this,
                $product = $widget.element.parents($widget.options.selectorProduct),
                $productPrice = $product.find(this.options.selectorProductPrice),
                result = $widget._getNewPrices(),
                tierPriceHtml,
                isShow;


            if ($.isFunction($productPrice.priceBox.option)) {
                $productPrice.trigger(
                    'updatePrice',
                    {
                        'prices': $widget._getPrices(result, $productPrice.priceBox('option').prices)
                    }
                );
            } else {
                $product.closest('.fixed-size-group-foreach').removeClass('selected');
                $product.find('.fixed-size-group-foreach').removeClass('selected');

                // console.log($product);
            }

            isShow = typeof result != 'undefined' && result.oldPrice.amount !== result.finalPrice.amount;

            $product.find(this.options.slyOldPriceSelector)[isShow ? 'show' : 'hide']();

            if (typeof result != 'undefined' && result.tierPrices && result.tierPrices.length) {
                if (this.options.tierPriceTemplate) {
                    tierPriceHtml = mageTemplate(
                        this.options.tierPriceTemplate,
                        {
                            'tierPrices': result.tierPrices,
                            '$t': $t,
                            'currencyFormat': this.options.jsonConfig.currencyFormat,
                            'priceUtils': priceUtils
                        }
                    );
                    $(this.options.tierPriceBlockSelector).html(tierPriceHtml).show();
                }
            } else {
                $(this.options.tierPriceBlockSelector).hide();
            }

            $(this.options.normalPriceLabelSelector).hide();

            _.each($('.' + this.options.classes.attributeOptionsWrapper), function (attribute) {
                if ($(attribute).find('.' + this.options.classes.optionClass + '.selected').length === 0) {
                    if ($(attribute).find('.' + this.options.classes.selectClass).length > 0) {
                        _.each($(attribute).find('.' + this.options.classes.selectClass), function (dropdown) {
                            if ($(dropdown).val() === '0') {
                                $(this.options.normalPriceLabelSelector).show();
                            }
                        }.bind(this));
                    } else {
                        $(this.options.normalPriceLabelSelector).show();
                    }
                }
            }.bind(this));
        },

        /**
         * Get new prices for selected options
         *
         * @returns {*}
         * @private
         */
        _getNewPrices: function () {
            var $widget = this,
                optionPriceDiff = 0,
                allowedProduct = this._getAllowedProductWithMinPrice(this._CalcProducts()),
                optionPrices = this.options.jsonConfig.optionPrices,
                basePrice = parseFloat(this.options.jsonConfig.prices.basePrice.amount),
                optionFinalPrice,
                newPrices;

            if (!_.isEmpty(allowedProduct)) {
                optionFinalPrice = parseFloat(optionPrices[allowedProduct].finalPrice.amount);
                optionPriceDiff = optionFinalPrice - basePrice;
            }

            if (optionPriceDiff !== 0) {
                newPrices = this.options.jsonConfig.optionPrices[allowedProduct];
            } else {
                newPrices = $widget.options.jsonConfig.prices;
            }

            return newPrices;
        },

        /**
         * Get prices
         *
         * @param {Object} newPrices
         * @param {Object} displayPrices
         * @returns {*}
         * @private
         */
        _getPrices: function (newPrices, displayPrices) {
            var $widget = this;

            if (_.isEmpty(newPrices)) {
                newPrices = $widget._getNewPrices();
            }
            _.each(displayPrices, function (price, code) {

                if (newPrices[code]) {
                    displayPrices[code].amount = newPrices[code].amount - displayPrices[code].amount;
                }
            });

            return displayPrices;
        },

        /**
         * Get product with minimum price from selected options.
         *
         * @param {Array} allowedProducts
         * @returns {String}
         * @private
         */
        _getAllowedProductWithMinPrice: function (allowedProducts) {
            var optionPrices = this.options.jsonConfig.optionPrices,
                product = {},
                optionFinalPrice, optionMinPrice;

            _.each(allowedProducts, function (allowedProduct) {
                optionFinalPrice = parseFloat(optionPrices[allowedProduct].finalPrice.amount);

                if (_.isEmpty(product) || optionFinalPrice < optionMinPrice) {
                    optionMinPrice = optionFinalPrice;
                    product = allowedProduct;
                }
            }, this);

            return product;
        },

        /**
         * Gets all product media and change current to the needed one
         *
         * @private
         */
        _LoadProductMedia: function () {
            var $widget = this,
                $this = $widget.element,
                productData = this._determineProductData(),
                mediaCallData,
                mediaCacheKey,

                /**
                 * Processes product media data
                 *
                 * @param {Object} data
                 * @returns void
                 */
                mediaSuccessCallback = function (data) {
                    if (!(mediaCacheKey in $widget.options.mediaCache)) {
                        $widget.options.mediaCache[mediaCacheKey] = data;
                    }
                    $widget._ProductMediaCallback($this, data, productData.isInProductView);



                    setTimeout(function () {
                        $widget._DisableProductMediaLoader($this);
                    }, 600);



                };

            if (!$widget.options.mediaCallback) {
                return;
            }

            let trainingsystemflag = false;
            let officeswivelchair = false;


            if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {
                officeswivelchair = true;
            }
            if ($('body').hasClass('product-training-system-x1-outdoor-fitness-watch-body-fitness-analysis-balance-3-in-1-optional-gps-pod-x-tension')) {

                if ($('.swatch-attribute[attribute-code]').length == $('.swatch-attribute[option-selected]').length && $('#product-addtocart-button').length) {
                    trainingsystemflag = true
                }
            }



// console.log("_LoadProductMedia is trigger");


            if ($("body").hasClass("boxers-5479-5481-performance-mid") || $('body').hasClass('boxers-5479-performance-mid') || $("body").hasClass("boxers-5481-performance-mid") || trainingsystemflag || officeswivelchair) {
                let neededproductid = this.getProducts();

                var mediaCallData, mediaCacheKey;

                // console.log("workin here inside officeswivelchair");




                mediaCallData = {
                    'product_id': neededproductid,
                    'current_bundle_product_id': $widget.options.current_bundle_product_id
                };


                mediaCacheKey = JSON.stringify(mediaCallData);



               /* if (mediaCacheKey in $widget.options.mediaCache) {
                    $widget._XhrKiller();
                    $widget._EnableProductMediaLoader($this);
                    mediaSuccessCallback($widget.options.mediaCache[mediaCacheKey]);


                } else {*/
                    if (neededproductid.indexOf(undefined) <= -1) {

                        // console.log('tocart disable-add-to-cart-product-button ');




                        $('#product-addtocart-button').attr('class', 'action primary tocart enable-add-to-cart-product-button');



                        mediaCallData.isAjax = true;
                        $widget._XhrKiller();
                        $widget._EnableProductMediaLoader($this);
                        $widget.xhr = $.ajax({
                            url: $widget.options.mediaCallback,
                            cache: false,
                            type: 'GET',
                            async: false,
                            dataType: 'json',
                            data: mediaCallData,
                            success: mediaSuccessCallback,
                            complete: function () {

                                setTimeout(function () {

                                    var $fotoramaDiv = $('.fotorama').fotorama();

                                    var fotorama = $fotoramaDiv.data('fotorama');

                                    if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {



                                        String.prototype.filename = function (extension) {
                                            var s = this.replace(/\\/g, '/');
                                            s = s.substring(s.lastIndexOf('/') + 1);
                                            return extension ? s.replace(/[?#].+$/, '') : s.split('.')[0];
                                        }








                                        let firstimageele = $('*[data-dependshowhidebyid="2"]').find('.swatch-option.image.selected');
                                        let secondimageele = $('*[data-dependshowhidebyid="3"]').find('.swatch-option.image.selected');

                                        let firstimageelename = firstimageele.attr('option-tooltip-thumb').filename();
                                        let secondimageelename = secondimageele.attr('option-tooltip-thumb').filename();
                                        // console.log("hereisworkingfirstimageelesrc" + firstimageelename);
                                        //  let secondimageelesrc = secondimageele.attr('option-tooltip-thumb');

                                        // console.log("hereisworkingsecondimageelesrc" + secondimageelename);

                                        let imageelename = $(".fotorama__img[src$='" + firstimageelename + '_' + secondimageelename + ".png']");

                                        // console.log("imageelenameworking" + imageelename.attr('src'));



                                        let imageelenameindexof = $(".fotorama__nav__shaft > .fotorama__nav__frame--thumb").index(imageelename.closest('.fotorama__nav__frame--thumb'));


                                        // console.log("imageelenameindexof" + imageelenameindexof);


                                        fotorama.show(imageelenameindexof);
                                    } else {
                                        fotorama.show(6);
                                    }

                                    $('.custom-swatch-ul').addClass('disableClick');

                                }, 100);

                                $(".fotorama__nav-wrap--horizontal").css('top', $(".product-info-main").outerHeight(true) + 110);
                                $(".container-fixed-gallery-images-thumb").css('height', $(".fotorama__nav-wrap--horizontal").outerHeight(true));


                            }
                        }).done(function () {
                            $widget._XhrKiller();
                        });

                   /* }*/
                }




            } else {


                if ($('body').hasClass('product-training-system-x1-outdoor-fitness-watch-body-fitness-analysis-balance-3-in-1-optional-gps-pod-x-tension') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {


                    trainingsystemflag = true;

                }


                if (trainingsystemflag) {
                    mediaCallData = {
                        'product_id': this.getProduct(),
                        'current_bundle_product_id': $widget.options.current_bundle_product_id
                    };
                } else {

                    mediaCallData = {
                        'product_id': this.getProduct()
                    };

                }


                // console.log("---------------------------------------mediaCallDatahgafsdf----------------------------------");

                // console.log(mediaCallData);



                mediaCacheKey = JSON.stringify(mediaCallData);

                if (mediaCacheKey in $widget.options.mediaCache) {
                    $widget._XhrKiller();
                    $widget._EnableProductMediaLoader($this);
                    mediaSuccessCallback($widget.options.mediaCache[mediaCacheKey]);
                } else {
                    mediaCallData.isAjax = true;
                    $widget._XhrKiller();
                    $widget._EnableProductMediaLoader($this);
                    $widget.xhr = $.ajax({
                        url: $widget.options.mediaCallback.replace(/\/+$/, ''),
                        cache: true,
                        type: 'GET',
                        async: true,
                        dataType: 'json',
                        data: mediaCallData,
                        success: mediaSuccessCallback,
                        complete: function () {


                            if ($('body').hasClass('page-product-trainingsystem-fixed') && productData.isInProductView) {
                                setTimeout(function () {


                                    var $fotoramaDiv = $('.fotorama').fotorama();

                                    var fotorama = $fotoramaDiv.data('fotorama');



                                    fotorama.show(6);
                                }, 300);
                            }

                            $('.custom-swatch-ul').addClass('disableClick');

                            $(".fotorama__nav-wrap--horizontal").css('top', $(".product-info-main").outerHeight(true) + 110);
                            $(".container-fixed-gallery-images-thumb").css('height', $(".fotorama__nav-wrap--horizontal").outerHeight(true));
                        }
                    }).done(function () {
                        $widget._XhrKiller();
                    });
                }
            }
        },

        /**
         * Enable loader
         *
         * @param {Object} $this
         * @private
         */
        _EnableProductMediaLoader: function ($this) {
            var $widget = this;

            if ($('body.catalog-product-view').length > 0) {
                $this.parents('.column.main').find('.photo.image')
                    .addClass($widget.options.classes.loader);
            } else {
                //Category View
                $this.parents('.product-item-info').find('.product-image-photo')
                    .addClass($widget.options.classes.loader);
            }
        },

        /**
         * Disable loader
         *
         * @param {Object} $this
         * @private
         */
        _DisableProductMediaLoader: function ($this) {
            var $widget = this;

            if ($('body.catalog-product-view').length > 0) {
                $this.parents('.column.main').find('.photo.image')
                    .removeClass($widget.options.classes.loader);
            } else {
                //Category View
                $this.parents('.product-item-info').find('.product-image-photo')
                    .removeClass($widget.options.classes.loader);
            }
        },

        /**
         * Callback for product media
         *
         * @param {Object} $this
         * @param {String} response
         * @param {Boolean} isInProductView
         * @private
         */
        _ProductMediaCallback: function ($this, response, isInProductView) {
            var $main = isInProductView ? $this.parents('.column.main') : $this.parents('.product-item-info'),
                $widget = this,
                images = [],

                /**
                 * Check whether object supported or not
                 *
                 * @param {Object} e
                 * @returns {*|Boolean}
                 */
                support = function (e) {
                    return e.hasOwnProperty('large') && e.hasOwnProperty('medium') && e.hasOwnProperty('small');
                };

            if (_.size($widget) < 1 || !support(response)) {
                this.updateBaseImage(this.options.mediaGalleryInitial, $main, isInProductView);

                return;
            }

            images.push({
                full: response.large,

            });

            if (response.hasOwnProperty('gallery')) {
                $.each(response.gallery, function () {
                    if (!support(this) || response.large === this.large) {
                        // return;
                    }
                    images.push({
                        full: this.large,
                        img: this.medium,
                        thumb: this.small


                    });
                });
            }

            if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric') || $('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {

                let seat_color = $('.parent-container-common-show-hide-fixed.swatch-attribute.seat_color').attr('option-selected');
                let base_color_material = $('.parent-container-common-show-hide-fixed.swatch-attribute.base_color_material').attr('option-selected');

                if (seat_color && base_color_material) {

                    let chairtype;

                    if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric')) {
                        chairtype = 'fabric_chair';
                    } else {
                        chairtype = 'leather_chair';

                    }
//window.location.protocol + '//' + window.location.hostname +
                    let imageURL =  'https://staging1.careshop.ch/media/' + chairtype + '/' + seat_color + '_' + base_color_material + '.png';


                    // console.log(imageURL);

                    images.push({
                        full: imageURL,
                        img: imageURL,
                        thumb: imageURL

                    });

                }




            }


            this.updateBaseImage(images, $main, isInProductView);
        },

        /**
         * Check if images to update are initial and set their type
         * @param {Array} images
         */
        _setImageType: function (images) {
            var initial = this.options.mediaGalleryInitial[0].img;

            if (images[0].img === initial) {
                images = $.extend(true, [], this.options.mediaGalleryInitial);
            } else {
                images.map(function (img) {
                    if (!img.type) {
                        img.type = 'image';
                    }
                });
            }



            return images;
        },

        /**
         * Update [gallery-placeholder] or [product-image-photo]
         * @param {Array} images
         * @param {jQuery} context
         * @param {Boolean} isInProductView
         */
        updateBaseImage: function (images, context, isInProductView) {
            var justAnImage = images[0],
                initialImages = this.options.mediaGalleryInitial,
                imagesToUpdate,
                gallery = context.find(this.options.mediaGallerySelector).data('gallery'),
                isInitial;

            if (isInProductView) {


                imagesToUpdate = images.length ? this._setImageType($.extend(true, [], images)) : [];
                isInitial = _.isEqual(imagesToUpdate, initialImages);

                if (this.options.gallerySwitchStrategy === 'prepend' && !isInitial) {
                    imagesToUpdate = imagesToUpdate.concat(initialImages);
                }

                /*        if ($("body").hasClass("boxers-5479-5481-performance-mid") || $("body").hasClass("boxers-5479-performance-mid") || $("body").hasClass("boxers-5481-performance-mid") || $('body').hasClass('boxers-5478-5479-5480-5481-performance-mid')) {
                            imagesToUpdate = initialImages.concat(imagesToUpdate);
                        }
        
        
                        imagesToUpdate = initialImages.concat(imagesToUpdate);*/

                imagesToUpdate = this._setImageIndex(imagesToUpdate);



                if (!_.isUndefined(gallery)) {
                    
                    // console.log("trigger isUndefined");
             
            

                    gallery.updateData(imagesToUpdate);




                } else {
                    context.find(this.options.mediaGallerySelector).on('gallery:loaded', function (loadedGallery) {
                        loadedGallery = context.find(this.options.mediaGallerySelector).data('gallery');
                        
                        //  console.log("trigger context")
                         
                        loadedGallery.updateData(imagesToUpdate);
                    }.bind(this));
                }


                if (gallery) {
                    gallery.first();
                }










            } else {


                context.find('.product-image-photo').attr('src', justAnImage.full);
            }



        },

        /**
         * Set correct indexes for image set.
         *
         * @param {Array} images
         * @private
         */
        _setImageIndex: function (images) {
            var length = images.length,
                i;

            for (i = 0; length > i; i++) {
                images[i].i = i + 1;
            }

            return images;
        },

        /**
         * Kill doubled AJAX requests
         *
         * @private
         */
        _XhrKiller: function () {
            var $widget = this;

            if ($widget.xhr !== undefined && $widget.xhr !== null) {
                $widget.xhr.abort();
                $widget.xhr = null;
            }
        },

        /**
         * Emulate mouse click on all swatches that should be selected
         * @param {Object} [selectedAttributes]
         * @private
         */
        _EmulateSelected: function (selectedAttributes) {
            $.each(selectedAttributes, $.proxy(function (attributeCode, optionId) {
                var elem = this.element.find('.' + this.options.classes.attributeClass +
                    '[attribute-code="' + attributeCode + '"] [option-id="' + optionId + '"]'),
                    parentInput = elem.parent();

                if (elem.hasClass('selected')) {
                    return;
                }

                if (parentInput.hasClass(this.options.classes.selectClass)) {
                    parentInput.val(optionId);
                    parentInput.trigger('change');
                } else {

                    if (!$("body").hasClass("boxers-5479-5481-performance-mid") && !$('body').hasClass('boxers-5479-performance-mid') && !$("body").hasClass("boxers-5481-performance-mid")) {
                        // elem.trigger('click');
                    }
                }
            }, this));
        },

        /**
         * Emulate mouse click or selection change on all swatches that should be selected
         * @param {Object} [selectedAttributes]
         * @private
         */
        _EmulateSelectedByAttributeId: function (selectedAttributes) {
            $.each(selectedAttributes, $.proxy(function (attributeId, optionId) {
                var elem = this.element.find('.' + this.options.classes.attributeClass +
                    '[attribute-id="' + attributeId + '"] [option-id="' + optionId + '"]'),
                    parentInput = elem.parent();

                if (elem.hasClass('selected')) {
                    return;
                }

                if (parentInput.hasClass(this.options.classes.selectClass)) {
                    parentInput.val(optionId);
                    parentInput.trigger('change');
                } else {


                    //  elem.trigger('click');

                }
            }, this));
        },

        /**
         * Get default options values settings with either URL query parameters
         * @private
         */
        _getSelectedAttributes: function () {
            var hashIndex = window.location.href.indexOf('#'),
                selectedAttributes = {},
                params;

            if (hashIndex !== -1) {
                params = $.parseQuery(window.location.href.substr(hashIndex + 1));

                selectedAttributes = _.invert(_.mapObject(_.invert(params), function (attributeId) {
                    var attribute = this.options.jsonConfig.mappedAttributes[attributeId];

                    return attribute ? attribute.code : attributeId;
                }.bind(this)));
            }

            return selectedAttributes;
        },

        /**
         * Callback which fired after gallery gets initialized.
         *
         * @param {HTMLElement} element - DOM element associated with a gallery.
         */
        _onGalleryLoaded: function (element) {
            var galleryObject = element.data('gallery');

            this.options.mediaGalleryInitial = galleryObject.returnCurrentImages();
        },

        /**
         * Sets mediaCache for cases when jsonConfig contains preSelectedGallery on layered navigation result pages
         *
         * @private
         */
        _setPreSelectedGallery: function () {
            var mediaCallData;

            if (this.options.jsonConfig.preSelectedGallery) {
                mediaCallData = {
                    'product_id': this.getProduct()
                };

                this.options.mediaCache[JSON.stringify(mediaCallData)] = this.options.jsonConfig.preSelectedGallery;
            }
        }
    });


    return $.mage.SwatchRenderer;
});