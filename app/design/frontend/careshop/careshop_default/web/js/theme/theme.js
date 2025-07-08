require([
	'jquery',
	'moment',
	'mage/url',
	'mage/gallery/gallery',
	'Magento_Ui/js/modal/confirm',
	'Magento_Ui/js/modal/modal',
	'mage/translate',
	'Careshop_RokanBase/js/owl_carousel',
	'jquery/jquery.cookie',
	'jquery/jquery.inputmask.min',
	'Magento_Catalog/js/catalog-add-to-cart',
	'mage/calendar',
	'jquery/validate'
], function($, moment, url, gallery, confirmation, modal, $t) {
	'use strict';
	$(document).ready(function() {
	    


		const observer = new MutationObserver((mutations) => {
			const date_of_birth = $('.checkout-date');
			if (date_of_birth.length) {
				Inputmask('99/99/9999', {
					mask: '99/99/9999',
					alias: 'date',
					placeholder: $.mage.__('DD/MM/YYYY'),
					insertMode: false,
					clearIncomplete: true,
					clearMaskOnLostFocus: false
				}).mask(date_of_birth[0]);
				observer.disconnect();
			}
		});

		observer.observe(document.body, {
			childList: true,
			subtree: true
		});

		function onReady() {
			var lazyVideos = [].slice.call(document.querySelectorAll("video.lazy"));
			if ("IntersectionObserver" in window) {
				var lazyVideoObserver = new IntersectionObserver(function(entries, observer) {
					entries.forEach(function(video) {
						if (video.isIntersecting) {
							for (var source in video.target.children) {
								var videoSource = video.target.children[source];
								if (typeof videoSource.tagName === "string" && videoSource.tagName === "SOURCE") {
									videoSource.src = videoSource.dataset.src;
								}
							}
							video.target.load();
							video.target.classList.remove("lazy");
							lazyVideoObserver.unobserve(video.target);
						}
					});
				});
				lazyVideos.forEach(function(lazyVideo) {
					lazyVideoObserver.observe(lazyVideo);
				});
			}
		}
		if (document.readyState !== "loading") {
			onReady();
		} else {
			document.addEventListener("DOMContentLoaded", onReady);
		}
		var timer = false;
		$('.swatch-option-custom-hover-fixed').hover(function() {
			var cur_this = $(this);
			timer = setTimeout(
				function() {
					var leftOpt = null,
						leftCorner = 0,
						left,
						$window;
					// Color
					$('.swatch-option-tooltip').find('.image').css({
						background: cur_this.attr('option-tooltip-value')
					});
					$('.swatch-option-tooltip').find('.image').show();
					$('.swatch-option-tooltip').find('.title').text(cur_this.attr('option-label'));
					leftOpt = cur_this.offset().left;
					left = leftOpt + cur_this.width() / 2 - $('.swatch-option-tooltip').width() / 2;
					$window = $(window);
					if (left < 0) {
						left = 5;
					} else if (left + $('.swatch-option-tooltip').width() > $window.width()) {
						left = $window.width() - $('.swatch-option-tooltip').width() - 5;
					}
					// the numbers (6,  3 and 18) is magick constants for offset tooltip
					leftCorner = 0;
					if ($('.swatch-option-tooltip').width() < cur_this.width()) {
						leftCorner = $('.swatch-option-tooltip').width() / 2 - 3;
					} else {
						leftCorner = (leftOpt > left ? leftOpt - left : left - leftOpt) + cur_this.width() / 2 - 6;
					}
					$('.swatch-option-tooltip').find('.corner').css({
						left: leftCorner
					});
					$('.swatch-option-tooltip').css({
						left: left,
						top: cur_this.offset().top - $('.swatch-option-tooltip').height() - $('.swatch-option-tooltip').find('.corner').height() - 18
					}).show();
				},
				200
			);
		}, function() {
			$('.swatch-option-tooltip').hide();
			clearTimeout(timer);
		});
		$(document).on('click', '.click-swatch-custom-shop-page', function(e) {
			var link_img = $(this).data('url');
			var fixed_option_id = $(this).attr('option-fixed-id');
			if (fixed_option_id == '5689') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5522');
			} else if (fixed_option_id == '5691') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5523');
			} else if (fixed_option_id == '5690') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5521');
			}
			// training system product starts here on list page
			else if (fixed_option_id == '5693') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5483');
			} else if (fixed_option_id == '5694') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5484');
			} else if (fixed_option_id == '5695') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5485');
			} else if (fixed_option_id == '5696') {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?color=5486');
			}
			if ($(this).closest('.swatch-opt-officeswivelchair').length) {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?seat_color=' + fixed_option_id);
			}
			if ($(this).closest('.swatch-opt-glasses').length) {
				var get_fixed_url = $(this).closest('li.product-item').attr('data-link');
				$(this).closest('li.product-item').find('a').attr('href', get_fixed_url + '?colors_glasses=' + fixed_option_id);
			}
			$(this).closest('div.swatch-attribute-options').find('.click-swatch-custom-shop-page').removeClass('selected');
			$(this).addClass('selected');
			$(this).closest('div.product-item-info').find('span.product-image-wrapper').html('<img class="product-image-photo" src="' + link_img + '" max-width="500" max-height="500" alt="">');
		});
		if ($('.fixed-seat-color-trigger').length > 0 && $('.catalog-product-view').length > 0) {
			setTimeout(function(ep) {
				var fi_o_id = $('.fixed-seat-color-trigger').attr('data-opt-id');
				$('.trigger-click-product-page-opt-id' + fi_o_id).trigger('click');
				$('.trigger-click-product-page-opt-id' + fi_o_id).trigger('click');

			}, 1000);
		}

		var triggerclickproductpageoptid = true;

		if ($('.fixed-color-trigger').length > 0 && $('.catalog-product-view').length > 0 && triggerclickproductpageoptid === true) {

			$('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function() {



				triggerclickproductpageoptid = false;


				var fi_o_id = $('.fixed-color-trigger').attr('data-opt-id');
				if (fi_o_id == '5522') {
					$('.trigger-click-product-page-opt-id5689').trigger('click');
					//   $('.trigger-click-product-page-opt-id5689').trigger('click');
				}
				if (fi_o_id == '5523') {
					$('.trigger-click-product-page-opt-id5691').trigger('click');
					$('.trigger-click-product-page-opt-id5691').trigger('click');
				}
				if (fi_o_id == '5521') {
					$('.trigger-click-product-page-opt-id5690').trigger('click');
					$('.trigger-click-product-page-opt-id5690').trigger('click');
				}

				if (fi_o_id == '5483') {
					$('.trigger-click-product-page-opt-id5693').trigger('click');
					$('.trigger-click-product-page-opt-id5693').trigger('click');
				}

				if (fi_o_id == '5484') {
					$('.trigger-click-product-page-opt-id5694').trigger('click');
					$('.trigger-click-product-page-opt-id5694').trigger('click');
				}

				if (fi_o_id == '5485') {
					$('.trigger-click-product-page-opt-id5695').trigger('click');
					$('.trigger-click-product-page-opt-id5695').trigger('click');
				}

				if (fi_o_id == '5486') {
					$('.trigger-click-product-page-opt-id5696').trigger('click');
					$('.trigger-click-product-page-opt-id5696').trigger('click');
				}


			});
		}









		if ($('.swatch-opt-boxer').length > 0 && $('.catalog-category-view').length > 0) {
			$(".swatch-opt-boxer").each(function() {
				var sel_ed = $(this).find('.click-swatch-custom-shop-page.selected');
				var fixed_option_id_1 = sel_ed.attr('option-fixed-id');
				if (fixed_option_id_1 == '5689') {
					var get_fixed_url_1 = $(this).closest('li.product-item').attr('data-link');
					sel_ed.closest('li.product-item').find('a').attr('href', get_fixed_url_1 + '?color=5522');
				} else if (fixed_option_id_1 == '5691') {
					var get_fixed_url_1 = $(this).closest('li.product-item').attr('data-link');
					sel_ed.closest('li.product-item').find('a').attr('href', get_fixed_url_1 + '?color=5523');
				} else if (fixed_option_id_1 == '5690') {
					var get_fixed_url_1 = $(this).closest('li.product-item').attr('data-link');
					sel_ed.closest('li.product-item').find('a').attr('href', get_fixed_url_1 + '?color=5521');
				}
			});
		}
		if ($('.swatch-opt-officeswivelchair').length > 0 && $('.catalog-category-view').length > 0) {
			$(".swatch-opt-officeswivelchair").each(function() {
				var sel_ed1 = $(this).find('.click-swatch-custom-shop-page.selected');
				var fixed_option_id_2 = sel_ed1.attr('option-fixed-id');
				var get_fixed_url3 = $(this).closest('li.product-item').attr('data-link');
				sel_ed1.closest('li.product-item').find('a').attr('href', get_fixed_url3 + '?seat_color=' + fixed_option_id_2);
			});
		}
		var video = ".watch_out_moving .product_images";
		$("body").on("mouseenter", video, function() {
			var video_source = $(this).find('video')[0];
			video_source.muted = true;
			video_source.loop = true;
			video_source.currentTime = 1;
			video_source.playbackRate = 0.5;
			const videoPromise = video_source.play();
			if (videoPromise != undefined) {
				videoPromise.then(_ => {
					video_source.play();
				});
			}

		});
		$("body").on("mouseleave", video, function() {
			var video_source = $(this).find('video')[0];
			video_source.currentTime = 0;
			video_source.playbackRate = 1;

			const videoPromisePause = video_source.pause();
			if (videoPromisePause != undefined) {
				videoPromisePause.then(_ => {
					video_source.pause();
				});
			}


		});
		$(document).on('click', video, function(e) {

		});
		$(document).on('click', '.language.switcher-language .switcher-dropdown li a', function(e) {
			var text = $(this).text();
			$(".language.switcher-language .switcher-trigger.active strong span").html(text);
		});
		if ($('.qtyid').length) {
			var selectedSubject = $(this).val();
			var idAttribute = $(this).data('productid');
			setTimeout(function(ep) {
				$('#cart-' + idAttribute + '-qty').val(selectedSubject);

			}, 100);
			setTimeout(function(ep) {
				$('.action.update').trigger('click');

			}, 200);
		}
		if ($('.shopping_cart_title').length) {
			$('.shopping_cart_title').insertAfter($('.page-title-wrapper'));
			$('.qty-up-fixed-onclick-page-cart').on('click', function(event) {
				event.preventDefault();
				var input_ = $(this).closest('.control.qty').find('.qty');
				var qtyval = parseInt(input_.val(), 10);
				qtyval = qtyval + 1;
				input_.val(qtyval);
			});
			$('.qty-down-fixed-onclick-page-cart').on('click', function(event) {
				event.preventDefault();
				var input_ = $(this).closest('.control.qty').find('.qty');
				var qtyval = parseInt(input_.val(), 10);
				qtyval = qtyval - 1;
				if (qtyval > 1) {
					input_.val(qtyval);
				} else {
					qtyval = 1;
					input_.val(qtyval);
				}
			});
		}
		if ($('.fixed-box-functional-boxer-short-pc').length) {
			var fixed_pc_get = $('.product-info-main .product-info-price .product-info-top-left .price-box .price-wrapper').width();
			$('.fixed-box-functional-boxer-short-pc').css('left', fixed_pc_get + 'px');
		}
		if ($('#id-product-included-product-sets').length) {
			var options_productset = {
				type: 'popup',
				responsive: true,
				innerScroll: true,
				modalClass: 'see-detail-modal-included-product-sets',
				title: '',
				buttons: [{
					text: $.mage.__('Close'),
					class: '',
					click: function() {
						this.closeModal();
					}
				}]
			};
			var popup_product_set = modal(options_productset, $('#id-product-included-product-sets'));
			$(".button-included-product-set button").on('click', function() {
				$("#id-product-included-product-sets").modal("openModal");

			});
		}
		if ($('#id-product-pupup-size-chart').length) {
			var pupup_size_chart_options = {
				type: 'popup',
				responsive: true,
				innerScroll: true,
				modalClass: 'see-detail-modal-pupup-size-chart',
				title: '',
				buttons: [{
					text: $.mage.__('Close'),
					class: '',
					click: function() {
						this.closeModal();
					}
				}]
			};
			var pupup_size_chart = modal(pupup_size_chart_options, $('#id-product-pupup-size-chart'));
			$(".product-size-chart-container-fixed a").on('click', function() {
				$("#id-product-pupup-size-chart").modal("openModal");

			});
		}
		$(document).on('click', '.fixed-container-out-of-stock-product-list a', function(e) {
			confirmation({
				title: $.mage.__('Notify!'),
				content: $.mage.__('Product is Out Of Stock'),
				buttons: [{
					text: $.mage.__('Close'),
					class: 'action-primary action-accept',
					click: function(event) {
						this.closeModal(event, true);
					}
				}]
			});

		});
		$(document).on('click', '.fixed-click-size-bundle-customization-functional-boxer', function(e) {
			if ($('#product-addtocart-button').length) {
				$('#product-addtocart-button').removeClass('disable-add-to-cart-product-button');
				$('#product-addtocart-button').addClass('enable-add-to-cart-product-button');
			}
			$('.size-custom-functional-boxer .fixed-select-size-box').addClass('selected');
			$('.swatch-attribute-selected-option-custom-functional-boxer-size').text($(this).attr('option-label'));
			$('.container-show-hide-attribute-common').removeClass('active');
			$(this).addClass('selected');
			$('.box-select-common').removeClass('active');

		});
		$(document).on('click', '.disable-add-to-cart-product-button', function(e) {
			$(".box-select-common .swatch-attribute-selected-option").each(function() {
				var text = $(this).text();
				if (!text) {
					$(this).closest('a').addClass('not-option');
				}
			});
		});
		$(document).ready(function() {
			$('body').on("click", function(event) {
				var $trigger = $('.parent-container-common-show-hide-fixed');
				var $trigger_boxer = $('.container-configurable-color-functional-boxer');
				if ($trigger !== event.target && !$trigger.has(event.target).length) {
					$('.container-show-hide-attribute-common').removeClass('active');
					$('.box-select-common').removeClass('active');
				}
				if ($trigger_boxer !== event.target && !$trigger_boxer.has(event.target).length) {
					$('.container-tab-color-functional-boxer').removeClass('actived');
					$('.box-select-common-functional-boxer').removeClass('actived');
					$('.container-tab-color-functional-boxer').hide();
					if ($("body").hasClass("boxers-5479-5481-performance-mid") || $("body").hasClass("boxers-5479-performance-mid") || $("body").hasClass("boxers-5481-performance-mid")) {
						/* added by rudracomputech */
						if ($('.container-tab-color-functional-boxer').hasClass('actived')) {
							if ($('.visible-selection-show .bundle-option-label').length !== $('.visible-selection-show .boxer-bg-active-check').length) {
								console.log("event fired mycode");
								console.log("working here inside rudracomputech 2");
								$('body').find('.swatch-attribute-selected-option-custom-functional-boxer-color').html("");
								$('.custom-swatch-ul').removeClass('disableClick');
								//		$('.multi-select.select-link').removeClass('active custom-active');
								$('.custom-swatch-ul li:first a')[0].click();
								$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '0px');
								$('.product_row').show();
							} else {
								$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '50%');
								$('.product_row').hide();
								$('body').find('.swatch-attribute-selected-option-custom-functional-boxer-color').html($('a.multi-select.select-link.custom-active.active').text());
							}
						}
					}
				}
			});
		});
		$(document).on('click', '.page-layout-landing-page .hide-langding-page', function(e) {

		});
		$(document).on('click', '.show-mode-swatch', function(e) {
			$(this).hide();
			$(this).closest('.swatch-attribute-options').find("[hide-swacth]").removeAttr("hide-swacth");

		});
		if ($('.catalog-category-view').length) {
			$('.shop-menu').addClass('active');
		} else {
			$('.shop-menu').removeClass('active');
		}
		$(document).on('click', '.page-layout-landing-page .currency-EUR a', function(e) {

		});
		$(document).on('click', '.page-layout-landing-page .currency-USD a', function(e) {

		});
		$(document).on('click', '.box-select-common-functional-boxer a', function(e) {
			if (!$('.box-select-common-functional-boxer').hasClass('has-clicked') && $('body.page-product-bundle').length) {
				$('.box-select-common-functional-boxer').addClass('has-clicked');
				$('.multi-select.custom-active.active').trigger('click');
				$('.warrning-error-click-next-step').hide();
			}
			$('.container-show-hide-attribute-common').removeClass('active');
			$('.box-select-common').removeClass('active');
			if (!$('.box-select-common-functional-boxer').hasClass('actived')) {
				$('.box-select-common-functional-boxer').addClass('actived');
				$('.container-tab-color-functional-boxer').addClass('actived');
				$('.container-tab-color-functional-boxer').show();
				if ($('.container-tab-color-functional-boxer .select-images a.select-link.active').length == 0 && $('body').hasClass('page-product-boxer-fixed')) {
					$(".price-fixed-swatch-detail").each(function() {
						var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
						$(this).html('CHF 42.25 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
					});
				}
			} else {
				$('.box-select-common-functional-boxer').removeClass('actived');
				$('.container-tab-color-functional-boxer').removeClass('actived');
				$('.container-tab-color-functional-boxer').hide();
				if ($("body").hasClass("boxers-5479-5481-performance-mid") || $("body").hasClass("boxers-5479-performance-mid") || $("body").hasClass("boxers-5481-performance-mid")) {
					/* added by rudracomputech */
					console.log("working here inside rudracomputech 1");
					if ($('.visible-selection-show .bundle-option-label').length !== $('.visible-selection-show .boxer-bg-active-check').length) {
						//console.log("event fired mycode");
						$('body').find('.swatch-attribute-selected-option-custom-functional-boxer-color').html("");
						$('.custom-swatch-ul').removeClass('disableClick');
						//	$('.multi-select.select-link').removeClass('active custom-active');
						$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '0px');
						$('.product_row').show();
						$('.custom-swatch-ul li:first a')[0].click();
					} else {
						$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '50%');

						$('.product_row').hide();
						$('body').find('.swatch-attribute-selected-option-custom-functional-boxer-color').html($('a.multi-select.select-link.custom-active.active').text());
					}
				}
			}
			if ($('.detech_is_4_functional_boxer').length > 0) {
				$(".price-fixed-swatch-detail").each(function() {
					var g_f_s = $(this).find('.fixed-color-core-careshop-pro-detail').text();
					$(this).html('CHF 17.25 <span class="fixed-color-core-careshop-pro-detail">' + g_f_s + '</span>');
				});
			}
		});
		$(document).on('click', '.custom-swatch-attribute-custom-chair-color .box-select-common-chair a', function(e) {
			$('.custom-swatch-attribute-custom-chair-size').removeClass('actived');
			$('.container-custom-swatch-attribute-grouped-common-attribute-size').removeClass('actived');
			$('.container-custom-swatch-attribute-grouped-common-attribute-size').hide();
			$('.container-show-hide-attribute-common').removeClass('active');
			$('.box-select-common').removeClass('active');
			if (!$('.custom-swatch-attribute-custom-chair-color').hasClass('actived')) {
				$('.custom-swatch-attribute-custom-chair-color').addClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-color').addClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-color').show();
				$('.container-custom-swatch-attribute-grouped-common-attribute-color .parent-container-common-show-hide-fixed:first').find('.box-select-common').addClass("active");
				$('.container-custom-swatch-attribute-grouped-common-attribute-color .parent-container-common-show-hide-fixed:first').find('.container-show-hide-attribute-common').addClass("active");
			} else {
				$('.custom-swatch-attribute-custom-chair-color').removeClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-color').removeClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-color').hide();
				$('.container-custom-swatch-attribute-grouped-common-attribute-color').find('.box-select-common').removeClass("active");
				$('.container-custom-swatch-attribute-grouped-common-attribute-color').find('.container-show-hide-attribute-common').removeClass("active");
			}

		});
		$(document).on('click', '.custom-swatch-attribute-custom-chair-size .box-select-common-chair a', function(e) {
			$('.custom-swatch-attribute-custom-chair-color').removeClass('actived');
			$('.container-custom-swatch-attribute-grouped-common-attribute-color').removeClass('actived');
			$('.container-custom-swatch-attribute-grouped-common-attribute-color').hide();
			$('.container-show-hide-attribute-common').removeClass('active');
			$('.box-select-common').removeClass('active');
			if (!$('.custom-swatch-attribute-custom-chair-size').hasClass('actived')) {
				$('.custom-swatch-attribute-custom-chair-size').addClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-size').addClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-size').show();
				$('.container-custom-swatch-attribute-grouped-common-attribute-size .parent-container-common-show-hide-fixed:first').find('.box-select-common').addClass("active");
				$('.container-custom-swatch-attribute-grouped-common-attribute-size .parent-container-common-show-hide-fixed:first').find('.container-show-hide-attribute-common').addClass("active");
			} else {
				$('.custom-swatch-attribute-custom-chair-size').removeClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-size').removeClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute-size').hide();
				$('.container-custom-swatch-attribute-grouped-common-attribute-size').find('.box-select-common').removeClass("active");
				$('.container-custom-swatch-attribute-grouped-common-attribute-size').find('.container-show-hide-attribute-common').removeClass("active");
			}

		});
		$(document).on('click', '#tab-men', function(e) {
			$("#women").hide();
			$("#men").show();
			$("#tab-men").addClass("active");
			$("#tab-women").removeClass("active");

		});
		$(document).on('click', '.filter-options .filter-options-title', function(e) {
			if (!$(this).closest('.filter-options-item').hasClass('active')) {
				$(this).closest('.filter-options-item').find('.filter-options-content').show();
				$(this).closest('.filter-options-item').addClass('active');
			} else {
				$(this).closest('.filter-options-item').find('.filter-options-content').hide();
				$(this).closest('.filter-options-item').removeClass('active');
			}

		});
		$(document).on('click', '#tab-women', function(e) {
			$("#men").hide();
			$("#women").show();
			$("#tab-women").addClass("active");
			$("#tab-men").removeClass("active");

		});
		$(document).on('click', '.catalog-product-view .product.info.detailed .data.item.title a', function(e) {
			if ($('.catalog-product-view .container-product-tab-videos iframe').length > 0) {
				$('.catalog-product-view .container-product-tab-videos iframe')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
			}

		});
		$(document).on('click', '.button-fixed-container-join-us-win button', function(e) {
			var conten_idea = $(this).closest('.parent-container-common-show-hide-fixed').find('.container-content-your-idea-input textarea').val();
			if (conten_idea != '') {
				confirmation({
					title: $.mage.__('Success!'),
					content: $.mage.__('Thanks for Submit your idea on this product.'),
					actions: {
						confirm: function() {},
						cancel: function() {}
					}
				});
			} else {
				confirmation({
					title: $.mage.__('Warning!'),
					content: $.mage.__('The content can not be empty.'),
					actions: {
						confirm: function() {},
						cancel: function() {}
					}
				});
			}

		});
		$(document).on('click', '.you-are-looking-for-fixed a', function(e) {
			if (!$(this).closest('.parent-container-common-show-hide-fixed').find('.you-are-looking-for-fixed').hasClass('active-show-hide-content-submit-idea')) {
				$(this).closest('.parent-container-common-show-hide-fixed').find('.you-are-looking-for-fixed').addClass('active-show-hide-content-submit-idea');
			} else {
				$(this).closest('.parent-container-common-show-hide-fixed').find('.you-are-looking-for-fixed').removeClass('active-show-hide-content-submit-idea')
			}
			$(this).closest('.parent-container-common-show-hide-fixed').find('.container-submit-your-idea-this-product').toggle();

		});
		$(document).on('click', '.minus-system-qty-fixed', function(e) {
			var qty_fixed = parseInt($('.up-and-down-qty-fixed').html());
			if (qty_fixed > 10) {
				qty_fixed--;
				$('.up-and-down-qty-fixed').html(qty_fixed);
			}

		});
		$(document).on('click', '.plus-system-qty-fixed', function(e) {
			var qty_fixed = parseInt($('.up-and-down-qty-fixed').html());
			qty_fixed++;
			$('.up-and-down-qty-fixed').html(qty_fixed);

		});
		$(document).on('click', '.select-qty-final-fixed-button li a.button-qty', function(e) {
			var qty_fixed = parseInt($(this).html());
			$(this).closest('.box-tocart').find('div.control #qty').val(qty_fixed);
			$('.container-show-hide-attribute-common').removeClass('active');
			$('.box-select-common').removeClass('active');
			$('.fixed-select-qty-box a strong').html(qty_fixed);
			$('.select-qty-final-fixed-button li a').removeClass('active');
			$(this).addClass('active');

		});
		$(document).on('click', '.box-select-common a', function(e) {
			if ($(this).closest('.parent-container-common-show-hide-fixed.price').length > 0) {
				$('.custom-swatch-attribute-custom-chair').removeClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute').removeClass('actived');
				$('.container-custom-swatch-attribute-grouped-common-attribute').hide();
			}
			if ($('.container-configurable-color-functional-boxer').length > 0) {
				$('.box-select-common-functional-boxer').removeClass('actived');
				$('.container-tab-color-functional-boxer').removeClass('actived');
				$('.container-tab-color-functional-boxer').hide();
			}
			if (!$(this).closest('.parent-container-common-show-hide-fixed').find('.container-show-hide-attribute-common').hasClass('active')) {
				$('.container-show-hide-attribute-common').removeClass('active');
				$('.box-select-common').removeClass('active');
				$(this).closest('.parent-container-common-show-hide-fixed').find('.container-show-hide-attribute-common').addClass('active');
				$(this).closest('.parent-container-common-show-hide-fixed').find('.box-select-common').addClass('active');
			} else {
				$('.container-show-hide-attribute-common').removeClass('active');
				$('.box-select-common').removeClass('active');
				if ($(this).closest('.container-custom-swatch-attribute-grouped-common-attribute').length > 0) {
					$(this).closest('.parent-container-common-show-hide-fixed').find('.container-show-hide-attribute-common').addClass('active');
					$(this).closest('.parent-container-common-show-hide-fixed').find('.box-select-common').addClass('active');
				}
			}

		});
		$(".button-more-improve-product-page a").click(function() {
			if ($(this).closest('.button-more-improve-product-page').hasClass('open')) {
				$(this).closest('.button-more-improve-product-page').removeClass('open');
				$(".show-hide-less-more-improve-product-page").slideUp();
			} else {
				$(this).closest('.button-more-improve-product-page').addClass('open');
				$(".show-hide-less-more-improve-product-page").slideDown();
			}

		});
		$(".button-more-description-product-page a").click(function() {
		   
			$(".show-hide-less-more-description-product-page").slideDown();
			$(".button-more-description-product-page").hide();
			$(".button-less-description-product-page").show();

		});
		$(".button-less-description-product-page a").click(function() {
			$(".show-hide-less-more-description-product-page").slideUp();
			$(".button-less-description-product-page").hide();
			$(".button-more-description-product-page").show();

		});
		$(document).on('click', '.product-reviews-summary .reviews-actions .view', function(e) {
			if (!$(this).hasClass('active')) {
				$(this).addClass('active');
				$(this).closest('.reviews-actions').find('.reviews-pupup').show();
			} else {
				$(this).removeClass('active');
				$(this).closest('.reviews-actions').find('.reviews-pupup').hide();
			}

		});
		$(document).on('click', '.color-swatch-options-container-left ul.custom-swatch-ul li a', function(e) {
			var id_p_o_d = $(this).attr('product-option-id');
			$('.color-swatch-options-container-left ul.custom-swatch-ul li').removeClass('active');
			$(this).closest('li').addClass('active');
			$(".container-fixed-gallery-images-thumb ul li").each(function(index) {
				var get_url_image = $(this).find('a').data('image');
				if (get_url_image.indexOf(id_p_o_d) != -1) {
					$(this).find('a').trigger("click");
				}
			});

		});
		$(document).on('click', '.color-swatch-options-container-left ul.ul-swatch li a', function(e) {
			var id = $(this).data('option');
			if (!$(this).closest('li').hasClass('active')) {
				if (!$(".swatch-option[option-id='" + id + "']").hasClass('selected')) {
					$(".swatch-option[option-id='" + id + "']").trigger("click");
				}
				if (!$(".chair-product.seat[data-option='" + id + "']").hasClass('selected')) {
					$(".chair-product.seat[data-option='" + id + "']").trigger("click");
				}
				$('.color-swatch-options-container-left ul li').removeClass('active');
				$(this).closest('li').addClass('active');
			} else {
				if ($(".swatch-option[option-id='" + id + "']").hasClass('selected')) {
					$(".swatch-option[option-id='" + id + "']").trigger("click");
				}
				if ($(".chair-product.seat[data-option='" + id + "']").hasClass('selected')) {
					$(".chair-product.seat[data-option='" + id + "']").trigger("click");
				}
				$(this).closest('li').removeClass('active');
			}

		});
		$(document).on('click', '.container-product-tab-knowhow ul li a', function(e) {
			var tab = $(this).attr('rel');
			if (!$(this).hasClass('active')) {
				$('.container-product-tab-knowhow ul li a').removeClass('active');
				$(this).addClass('active');
				$('.container-product-tab-knowhow .tab-content').removeClass('active');
				$('.' + tab + '').addClass('active');
			}

		});



		$(document).on('click', '.container-fixed-gallery-images-thumb ul li a', function(e) {

			var data_image = $(this).data('image');
			var this_ = $(this);
			$('.container-fixed-gallery-images-thumb ul li').removeClass('active');
			$(this).closest('li').addClass('active');
			var api_product = $('[data-gallery-role=gallery-placeholder]').data('gallery');
			if (!api_product) {
				$(this).triggerHandler("click");
				e.preventDefault();
			} else {
				var arr_image = [],
					first;
				$(".container-fixed-gallery-images-thumb ul li").each(function(index) {
					var url_image = $(this).find('a').data('image');
					var thumb = $(this).find('a').data('thumb');
					var caption = $(this).find('a').data('caption');
					if (url_image == data_image) {
						if (index == 0) {
							first = true;
						}
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
				});
				api_product.updateData(arr_image);
				if (first) {
					api_product.first();
				}
			}

		});



		$('.footer-careshop .title-footer').click(function() {
			if (!$(this).hasClass('active')) {
				$(this).addClass('active');
				$(this).closest('.footer-careshop').find('.footer-content').show(300);
			} else {
				$(this).removeClass('active');
				$(this).closest('.footer-careshop').find('.footer-content').hide(300);
			}

		});
		$('.menu-mobile-custom').click(function() {
			var data = $(this).data('view');
			if (!$(this).hasClass('active')) {
				$('.menu-mobile-custom').removeClass('active');
				$(this).addClass('active');
				if (data == 'myaccount-popup-view-mobi') {
					$('.popup-lang-container').removeClass('active');
					$('.myaccount-popup-view-mobi').addClass('active');
				} else {
					$('.myaccount-popup-view-mobi').removeClass('active');
					$('.popup-lang-container').addClass('active');
				}
			} else {
				$(this).removeClass('active');
				if (data == 'myaccount-popup-view-mobi') {
					$('.myaccount-popup-view-mobi').removeClass('active');
				} else {
					$('.popup-lang-container').removeClass('active');
				}
			}

		});
		$('.menu-mobile-custom-search').click(function() {
			if (!$(this).hasClass('active')) {
				$(this).addClass('active');
				$(this).closest('li').find('.custom-content-menu-mobi').addClass('active');
			} else {
				$(this).removeClass('active');
				$(this).closest('li').find('.custom-content-menu-mobi').removeClass('active');
			}

		});
		$("#switcher-ship-to-country ul.switcher-dropdown li.switcher-option a").click(function() {
			var code_country = $(this).attr('data-country');
			var title_country = $(this).attr('data-title');
			var date = new Date();
			var minutes = 600000;
			date.setTime(date.getTime() + (minutes * 60 * 1000));
			$.cookie('ship_to_country_code', code_country, {
				expires: date
			});
			$.cookie('ship_to_country_name', title_country, {
				expires: date
			});
			location.reload(true);

		});
		$(".button-closed-topbar-navi-advertisement").click(function() {
			$(".web-one-topbar-navi-advertisement-container").slideUp();
			$("body").removeClass("has-class-topbar-navi-advertisement");

		});
		$(".special-offers-click-open").click(function() {
			$(".lang-currency-click-open-pc").show();
			$(".lang-currency-click-close").hide();
			$(".lang-currency-container").removeClass("active");
			$(".special-offers-click-close").show();
			$(".special-offers-click-open").hide();
			$(".special-offers-container").addClass("active");

		});
		$(".special-offers-click-close").click(function() {
			$(".special-offers-click-close").hide();
			$(".special-offers-click-open").show();
			$(".special-offers-container").removeClass("active");

		});
		$(".lang-currency-click-open-pc").click(function() {
			$(".special-offers-click-close").hide();
			$(".special-offers-click-open").show();
			$(".special-offers-container").removeClass("active");
			$(".lang-currency-click-close").show();
			$(".lang-currency-click-open-pc").hide();
			$(".lang-currency-container").addClass("active");

		});
		$(".lang-currency-click-close").click(function() {
			$(".lang-currency-click-open-pc").show();
			$(".lang-currency-click-close").hide();
			$(".lang-currency-container").removeClass("active");

		});
		$(".button-click-view-popup-static-link").click(function() {
			$('.myaccount-li-content-show-hide').removeClass('active');
			$('.search-li-content-show-hide').removeClass('active');
			if (!$('.static-link-li-content-show-hide').hasClass('active')) {
				$('.static-link-li-content-show-hide').addClass('active');
			} else {
				$('.static-link-li-content-show-hide').removeClass('active');
			}

		});
		$(".button-click-view-pupup-search").click(function() {
			$('.myaccount-li-content-show-hide').removeClass('active');
			$('.static-link-li-content-show-hide').removeClass('active');
			if (!$('.search-li-content-show-hide').hasClass('active')) {
				$('.search-li-content-show-hide').addClass('active');
			} else {
				$('.search-li-content-show-hide').removeClass('active');
			}

		});
		$(".panel-heading a").click(function() {
			if (!$(this).hasClass('active')) {
				$(this).closest('.panel-defaut').find(".panel-collapse").show();
				$(this).addClass('active');
			} else {
				$(this).closest('.panel-defaut').find(".panel-collapse").hide();
				$(this).removeClass('active');
			}

		});
		$(".action-start-repair").click(function() {
			$(this).closest('.repair-content-form').addClass('active');
			$(this).closest('.repair-content-form').find(".start-order").addClass('active');

		});
		$(".action-cancel-repair").click(function() {
			$(this).closest('.repair-content-form').removeClass('active');
			$(this).closest('.repair-content-form').find(".start-order").removeClass('active');

		});
		$(".action-return-available").click(function() {
			if (!$(this).closest('.return-available').hasClass('active')) {
				$(this).closest('.return-available').addClass('active');
			} else {
				$(this).closest('.return-available').removeClass('active');
			}

		});
		$(document).on('click', '.action-warranty', function(e) {
			var val = $('#search-warranty').val();
			if (val) {
				$.ajax({
					url: url.build("returnswarranty/registerwarranty/search"),
					type: "POST",
					dataType: 'json',
					data: {
						val: val
					}
				}).success(function(data) {
					var response = data.orders;
					if (response.length > 0) {
						var _html = '';
						$.each(data.orders, function(i, item) {
							_html += '<div class="order">';
							_html += '<div class="order-info">';
							_html += '<p>' + $.mage.__('Order') + ': #' + item.increment_id + '</p>';
							_html += '<p>' + $.mage.__('Order Date') + ': ' + item.created_at + '</p>';
							_html += '<p>' + $.mage.__('Status') + ': ' + item.status + '</p>';
							_html += '</div>';
							_html += '<div class="actions">';
							_html += '<a class="action view" href="' + BASE_URL + 'returnswarranty/order/view/order_id/' + item.entity_id + '">' + $.mage.__('Register Warranty') + '</a>';
							_html += '</div>';
							_html += '</div>';
						});
						$('.list-order-warranty').html(_html);
					} else {
						$('.list-order-warranty').html('<p>' + $.mage.__('No results were found.') + '</p>');
					}
				});
			} else {
				alert($.mage.__('You have not entered Order Number'));
			}

		});
		$(document).on('click', '.product-infor-right button', function(e) {
			var product_id = $(this).data('product-id');
			var qty = $(this).closest('.product-infor-right').find(".input-text-optional-qty").val();
			var form_key = $('.product-add-form input[name=form_key]').val();
			var _this = $(this);
			if (product_id) {
				$('[data-block="minicart"]').trigger('contentLoading');
				$(this).addClass('pending');
				$(this).find('span').text('Adding...');
				$.ajax({
					url: url.build("rokanbase/cart/add"),
					type: "POST",
					dataType: 'json',
					data: {
						product: product_id,
						item: product_id,
						form_key: form_key,
						qty: qty
					}
				}).success(function(data) {
					$('[data-block="minicart"]').trigger('contentUpdated');
					_this.find('span').text('Added');
					setTimeout(function(ep) {
						_this.removeClass('pending');
						_this.find('span').text('Add to Cart');

					}, 1000);
					$('.container-show-hide-attribute-common').removeClass('active');
					$('.box-select-common').removeClass('active');
				});
			}

		});
		$(document).on('click', '.minus-custom-bundel-qty-fixed', function(e) {
			var get_val_m_qty = parseInt($(this).closest('.content-fixed-custom-qty-bundel-product').find('.input-custom-bundel-qty-fixed').val());
			if (get_val_m_qty <= 1) {

			}
			get_val_m_qty = get_val_m_qty - 1;
			$(this).closest('.content-fixed-custom-qty-bundel-product').find('.input-custom-bundel-qty-fixed').val(get_val_m_qty);

		});
		$(document).on('click', '.plus-custom-bundel-qty-fixed', function(e) {
			var get_val_m_qty = parseInt($(this).closest('.content-fixed-custom-qty-bundel-product').find('.input-custom-bundel-qty-fixed').val());
			get_val_m_qty = get_val_m_qty + 1;
			$(this).closest('.content-fixed-custom-qty-bundel-product').find('.input-custom-bundel-qty-fixed').val(get_val_m_qty);

		});
		$('.qty-plus .plus').on('click', function(event) {
			event.preventDefault();
			var input_ = $(this).closest('.qty-item-optional-list').find('.qty-custom');
			var qtyval = parseInt(input_.val(), 10);
			qtyval = qtyval + 1;
			input_.val(qtyval);

		});
		$('.qty-minus .minus').on('click', function(event) {
			event.preventDefault();
			var input_ = $(this).closest('.qty-item-optional-list').find('.qty-custom');
			var qtyval = parseInt(input_.val(), 10);
			qtyval = qtyval - 1;
			if (qtyval > 1) {
				input_.val(qtyval);
			} else {
				qtyval = 1;
				input_.val(qtyval);
			}

		});
		$(document).on('click', '.action-returnsorder', function(e) {
			var orders = $('#search-orders').val();
			var startdate = $('#search-startdate').val();
			var endorders = $('#search-endorders').val();
			if (orders && startdate && endorders) {
				$.ajax({
					url: url.build("returnswarranty/returnorders/search"),
					type: "POST",
					dataType: 'json',
					data: {
						orders: orders,
						startdate: startdate,
						endorders: endorders
					}
				}).success(function(data) {
					var response = data.orders;
					if (response.length > 0) {
						var _html = '';
						$.each(data.orders, function(i, item) {
							_html += '<div class="order">';
							_html += '<div class="order-info">';
							_html += '<p>' + $.mage.__('Order') + ': #' + item.increment_id + '</p>';
							_html += '<p>' + $.mage.__('Order Date') + ': ' + item.created_at + '</p>';
							_html += '<p>' + $.mage.__('Status') + ': ' + item.status + '</p>';
							_html += '</div>';
							_html += '<div class="actions">';
							_html += '<a class="action view" href="' + BASE_URL + 'rma/account/newreturn/order/' + item.entity_id + '">' + $.mage.__('Return Orders') + '</a>';
							_html += '</div>';
							_html += '</div>';
						});
						$('.list-order-returnsorder').html(_html);
					} else {
						$('.list-order-returnsorder').html('<p>' + $.mage.__('No results were found.') + '</p>');
					}
				});
			} else {
				alert('You have not entered Order Number');
			}

		});
		$(document).on('click', '.action-checkorder-now', function(e) {
			var orders = $('#traking-order-number').val();
			var email = $('#traking-order-email').val();
			if (orders && email) {
				$.ajax({
					url: url.build("rokanbase/account/orderPost"),
					type: "POST",
					dataType: 'json',
					data: {
						orders: orders,
						email: email
					}
				}).success(function(data) {
					$('.traking-list').html(data.html);
				});
			} else {
				alert('You have not entered Order Number');
			}

		});
		$(document).on('click', '.action-repair', function(e) {
			var orders = $('#search-orders').val();
			var lastname = $('#search-lastname').val();
			if (orders && lastname) {
				if (orders.length > 6) {
					$.ajax({
						url: url.build("repair/order/search"),
						type: "POST",
						dataType: 'json',
						data: {
							orders: orders,
							lastname: lastname
						}
					}).success(function(data) {
						var response = data.orders;
						if (response.length > 0) {
							var _html = '';
							$.each(data.orders, function(i, item) {
								_html += '<div class="order">';
								_html += '<div class="order-info">';
								_html += '<p>' + $.mage.__('Order') + ': #' + item.increment_id + '</p>';
								_html += '<p>' + $.mage.__('Order Date') + ': ' + item.created_at + '</p>';
								_html += '<p>' + $.mage.__('Status') + ': ' + item.status + '</p>';
								_html += '</div>';
								_html += '<div class="actions">';
								_html += '<a class="action view" href="' + BASE_URL + 'rma/account/newreturn/order/' + item.entity_id + '">' + $.mage.__('Repair Order') + '</a>';
								_html += '</div>';
								_html += '</div>';
							});
							$('.list-order-repair').html(_html);
						} else {
							$('.list-order-repair').html('<p>' + $.mage.__('No results were found.') + '</p>');
						}
					});
				} else {
					alert('Order numbers are 6 or more numeric digits.');
				}
			} else {
				alert('You have not entered Order Number');
			}

		});
		$(document).on('click', '.toolbar-button .square', function(e) {
			var minutes = 600000;
			var date = new Date();
			date.setTime(date.getTime() + (minutes * 60 * 1000));
			$('body').removeClass('common-color-type-circle');
			$('body').addClass('common-color-type-square');
			$.cookie('color_type', 'square', {
				expires: date
			});
		});
		$(document).on('click', '.toolbar-button .circle', function(e) {
			var minutes = 600000;
			var date = new Date();
			date.setTime(date.getTime() + (minutes * 60 * 1000));
			$('body').removeClass('common-color-type-square');
			$('body').addClass('common-color-type-circle');
			$.cookie('color_type', 'circle', {
				expires: date
			});
		});
		$(document).on('click', '.buttons-play-pause a', function(e) {
			var owl = $('.wrapper-the-blue-sky-slider');
			if ($(this).hasClass('play')) {
				owl.trigger('play.owl.autoplay');
				$(this).removeClass('play');
				$(this).addClass('stop');
				$(this).find('i').removeClass('fa-play');
				$(this).find('i').addClass('fa-pause');
			} else {
				owl.trigger('stop.owl.autoplay');
				$(this).removeClass('stop');
				$(this).addClass('play');
				$(this).find('i').removeClass('fa-pause');
				$(this).find('i').addClass('fa-play');
			}

		});

		function Popup(btnSelector, containerSelector) {
			const btn = document.querySelector(btnSelector);
			const container = document.querySelector(containerSelector);
			const cartIcon = document.querySelector('.minicart-li-content-show-hide a');
			if (btn) {
				const missClickEventHandler = function(event) {
					if (btn.contains(event.target)) {
						return;
					}
					const isClickInsideElement = container.querySelector('.popup-body').contains(event.target);
					if (!isClickInsideElement && container.classList.contains('active')) {
						container.classList.remove('active');
					}
				};
				if (cartIcon) {
					cartIcon.addEventListener('click', missClickEventHandler);
				}
				document.addEventListener('click', missClickEventHandler);
				btn.addEventListener('click', function() {
					container.classList.toggle('active');
				});
			}
		}
		new Popup('.button-click-view-popup-information', '.information-li-content-show-hide');
		new Popup('.button-click-view-popup-myaccount', '.myaccount-li-content-show-hide');
		$.validator.addMethod(
			'validate-dob-eu',
			function(value) {
				if (value === '') {
					return true;
				}
				return moment(value, 'DD/MM/YYYY').isBefore(moment());
			},
			$.mage.__('The Date of Birth should not be greater than today.')
		);
		const dobInput = $('#dob');

		if (dobInput.length) {

			Inputmask('99/99/9999', {
				mask: '99/99/9999',
				alias: 'date',
				placeholder: $.mage.__('DD/MM/YYYY'),
				insertMode: false,
				clearIncomplete: true,
				clearMaskOnLostFocus: false
			}).mask(dobInput[0]);

		}


		$(document).on("keypress", ".checkout-date", function(event) {

			var inputLength = event.target.value.length;

			if (inputLength > 9) {
				return false
			}

			if (inputLength === 2 || inputLength === 5) {
				var thisVal = event.target.value;
				thisVal += '/';
				$(event.target).val(thisVal);
			}


		});



		$('.show-hide-password-btn').on('click', function() {
			const input = $(this).parent().find('input');
			const icon = $(this).find('i');
			if (input.attr('type') === 'password') {
				input.attr('type', 'text');
				icon.removeClass('fa-eye');
				icon.addClass('fa-eye-slash');
			} else {
				input.attr('type', 'password');
				icon.removeClass('fa-eye-slash');
				icon.addClass('fa-eye');
			}
		});
		//init for owl carousel
		var owl = $('.owl-carousel');
		$(function() {
			owl.each(function(index, el) {
				var $_this = $(this);
				var $center = $_this.data('center');
				var $mousedrag = $_this.data('mousedrag');
				var $stagepadding = $_this.data('stagepadding');
				var $item = $_this.data('items');
				var $touchdrag = $_this.data('touchdrag');
				var $rtl = $_this.data('rtl');
				var $dots = $_this.data('dots');
				var $rewind = $_this.data('rewind');
				var $lazyLoad = $_this.data('lazyload');
				var $autoplayhoverpause = $_this.data('autoplayhoverpause');
				var $nav = $_this.data('nav');
				var $margin = $_this.data('margin') ? $_this.data('margin') : 0;
				var $bigdesk_items = $_this.data('bigdesktop') ? $_this.data('bigdesktop') : 4;
				var $desksmall_items = $_this.data('smalldesktop') ? $_this.data('smalldesktop') : 3;
				var $bigtablet_items = $_this.data('bigtablet') ? $_this.data('bigtablet') : 3;
				var $tablet_items = $_this.data('tablet') ? $_this.data('tablet') : 3;
				var $tabletsmall_items = $_this.data('smalltablet') ? $_this.data('smalltablet') : 2;
				var $mobile_items = $_this.data('mobile') ? $_this.data('mobile') : 1;
				var $tablet_margin = Math.floor($margin / 1.5);
				var $mobile_margin = Math.floor($margin / 3);
				var $default_items = $item ? $item : 4;
				var $autoplay = $_this.data('autoplay');
				var $autoplayTimeout = $_this.data('autoplaytimeout') ? $_this.data('autoplaytimeout') : 1000;
				var $smartSpeed = $_this.data('speed') ? $_this.data('speed') : 250;
				var $loop = $_this.data('loop');
				var $next_text = $_this.data('navnext') ? $_this.data('navnext') : 'Next';
				var $prev_text = $_this.data('navprev') ? $_this.data('navprev') : 'Prev';
				var $buttonsplaypause = $_this.data('buttonsplaypause') ? $_this.data('buttonsplaypause') : false;
				var $autoHeight = $_this.data('autoheight') ? $_this.data('autoheight') : false;
				var obj = {
					autoHeight: $autoHeight,
					autoplayHoverPause: $autoplayhoverpause,
					center: $center,
					rewind: $rewind,
					touchDrag: $touchdrag,
					mouseDrag: $mousedrag,
					stagePadding: $stagepadding,
					loop: $loop,
					nav: $nav,
					dots: $dots,
					buttonsplaypause: $buttonsplaypause,
					margin: $margin,
					rtl: $rtl,
					items: $default_items,
					autoplay: $autoplay,
					autoplayTimeout: $autoplayTimeout,
					smartSpeed: $smartSpeed,
					lazyLoad: $lazyLoad,
					navText: [$next_text, $prev_text],
					responsive: {
						0: {
							items: $mobile_items,
							margin: $mobile_margin
						},
						480: {
							items: $tabletsmall_items,
							margin: $mobile_margin
						},
						640: {
							items: $tablet_items,
							margin: $tablet_margin
						},
						768: {
							items: $bigtablet_items,
							margin: $tablet_margin
						},
						992: {
							items: $desksmall_items,
							margin: $margin
						},
						1200: {
							items: $default_items,
							margin: $margin
						},
						1500: {
							items: $bigdesk_items,
							margin: $margin
						},
					},
				}
				$_this.owlCarousel(obj);

				function setFirstAndLastItemOwl() {
					var total_owl_document_ready = $_this.find('.owl-item.active').length;
					$_this.find('.owl-item').removeClass('first-active-item');
					$_this.find('.owl-item').removeClass('last-active-item');
					$_this.find('.owl-item.active').each(function(index) {
						if (index === 0) {
							$(this).addClass('first-active-item');
						}
						var setActiveItems = (screenWidth) => {
							if (total_owl_document_ready - obj.responsive[screenWidth].items >= 1) {
								if (index === total_owl_document_ready - 1 && total_owl_document_ready > 1) {
									$(this).removeClass('active')
								}
								if (index === total_owl_document_ready - 2 && total_owl_document_ready > 1) {
									$(this).addClass('last-active-item')
								}
							} else {
								if (index === total_owl_document_ready - 1 && total_owl_document_ready > 1) {
									$(this).addClass('last-active-item')
								}
							}
						}
						if (innerWidth >= 1500) {
							setActiveItems(1500);
						} else if (innerWidth < 1500 && innerWidth >= 1200) {
							setActiveItems(1200);
						} else if (innerWidth < 1200 && innerWidth >= 992) {
							setActiveItems(992);
						} else if (innerWidth < 992 && innerWidth >= 768) {
							setActiveItems(768);
						} else if (innerWidth < 768 && innerWidth >= 640) {
							setActiveItems(640);
						} else if (innerWidth < 640 && innerWidth >= 480) {
							setActiveItems(480);
						} else {
							setActiveItems(0);
						}
					});
				}
				$_this.on('translated.owl.carousel', function(event) {
					setFirstAndLastItemOwl();
					onReady();
				});
			});
		});
	});
	// only for boxer product
	if ($("body").hasClass("boxers-5479-5481-performance-mid") || $("body").hasClass("boxers-5479-performance-mid") || $("body").hasClass("boxers-5481-performance-mid")) {
		$(document).on('click', '.swatch-option', function(e) {



			let showhideid = $(this).closest('.visible-selection-show').attr('data-dependshowhidebyid');
			checkswatchclicktick(showhideid);
		});
		let showhideidflagtest = true;
		// $('*[data-showhideid').addClass('disableClick');
		var showhideid = false;
		var showhideidflag = false;
		var nextnumberattribute;
		var showhideArray = [];
		$(document).on('click', '.select-images a.select-link', function(e) {
			$(this).addClass('custom-active');
			var clickelement = $(this);
			showhideid = $(clickelement).attr("data-showhideid");
			if ($("body").hasClass("boxers-5479-5481-performance-mid")) {
				$('.gender_boxer').each(function() {
					$(this).attr('option-selected', 0);
				});
			}
			$('.container-tab-color-functional-boxer').find('.boxer-bg-active-check').removeClass('boxer-bg-active-check');
			setTimeout(function() {
				$('#product-addtocart-button').attr('class', 'action primary tocart disable-add-to-cart-product-button');
			}, 200);
			if ($('body').hasClass('boxers-5479-performance-mid')) {
				$('.swatch-option:not([option-id="5524"])').attr('aria-checked', false);
				$('*[data-dependshowhidebyid="1"] .swatch-option:not([option-id="5524"])').removeClass('selected');
				$('*[data-dependshowhidebyid="2"] .swatch-option:not([option-id="5524"])').removeClass('selected');
				$('*[data-dependshowhidebyid="3"] .swatch-option:not([option-id="5524"])').removeClass('selected');
				$('*[data-dependshowhidebyid="4"] .swatch-option:not([option-id="5524"])').removeClass('selected');
			} else if ($("body").hasClass("boxers-5481-performance-mid")) {
				$('.swatch-option:not([option-id="5525"])').attr('aria-checked', false);
				$('*[data-dependshowhidebyid="1"] .swatch-option:not([option-id="5525"])').removeClass('selected');
				$('*[data-dependshowhidebyid="2"] .swatch-option:not([option-id="5525"])').removeClass('selected');
				$('*[data-dependshowhidebyid="3"] .swatch-option:not([option-id="5525"])').removeClass('selected');
				$('*[data-dependshowhidebyid="4"] .swatch-option:not([option-id="5525"])').removeClass('selected');
			} else {
				$('.fixed-size-group-foreach').addClass('disabled');
				$('.fixed-size-group-foreach').attr('disabled', true);
				$('.swatch-option').attr('aria-checked', false);
				$('*[data-dependshowhidebyid="1"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="2"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="3"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="4"] .swatch-option').removeClass('selected');
			}
			$('*[data-dependshowhidebyid="1"] .container-border-fixed-common-all').removeClass('atc');
			$('*[data-dependshowhidebyid="2"] .container-border-fixed-common-all').removeClass('atc');
			$('*[data-dependshowhidebyid="3"] .container-border-fixed-common-all').removeClass('atc');
			$('*[data-dependshowhidebyid="4"] .container-border-fixed-common-all').removeClass('atc');
			nextnumberattribute = parseInt(showhideid) + 1;
			if (showhideid < 4) {
				//console.warn("This is number needed" + nextnumberattribute);
				//  $('*[data-showhideid="' + showhideid + '"]').removeClass('disableClick'); // default 1 active
			}
			if (showhideid < nextnumberattribute && $.inArray(showhideid, showhideArray) !== -1) {
				//console.log("code not working here");
				showhideArray.push(showhideid);
				$('*[data-dependshowhidebyid="' + showhideid + '"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="' + showhideid + '"] .swatch-option').removeClass('atc');
			}

			// make multiselect working
			let showhidearray = [];
			$('.super-attribute-select').attr('disabled', true);
			for (let i = 1; i <= showhideid; i++) {

				console.log(i);

				let dataselectionpushdata = $('.multi-select.select-link[data-showhideid="' + i + '"]').attr('data-selection-id');


				$('.bundle-selection-data[data-dependshowhidebyid="' + i + '"]').find('.super-attribute-select').attr('disabled', false);
				showhidearray.push(dataselectionpushdata);
			}

			console.log("showhidearray");
			console.log(showhidearray);

			$("#bundle-option-1 option").prop("selected", false);
			$("#bundle-option-16 option").prop("selected", false);
			$("#bundle-option-17 option").prop("selected", false);

			$.each(showhidearray, function(i, e) {

				if ($('body').hasClass('boxers-5479-performance-mid')) {
					$("#bundle-option-16 option[value='" + e + "']").prop("selected", true);
					$('input[name="super_attribute[16][' + e + '][171]"]').val(5524);


					$('.swatch-option[option-id="5524"]').each(function(i, elem) {
						console.log("running times" + i);
						$(elem).addClass('selected').attr('aria-checked', true).attr('disabled', true);
						$(elem).closest('.gender_boxer').attr('option-selected', '5524');
					});


				}

				if ($('body').hasClass('boxers-5481-performance-mid')) {
					$("#bundle-option-17 option[value='" + e + "']").prop("selected", true);
					$('input[name="super_attribute[17][' + e + '][171]"]').val(5525);

					$('.swatch-option[option-id="5525"]').each(function(i, elem) {
						console.log("else running times" + i);
						$(elem).addClass('selected').attr('aria-checked', true).attr('disabled', true);
						$(elem).closest('.gender_boxer').attr('option-selected', '5525');
					});

				}


				$("#bundle-option-1 option[value='" + e + "']").prop("selected", true);
			});

			if ($('body').hasClass('boxers-5479-performance-mid')) {
				$('.fixed-select-gender_boxer-box .swatch-attribute-selected-option').text('Man');
			}

			if ($('body').hasClass('boxers-5481-performance-mid')) {
				$('.fixed-select-gender_boxer-box .swatch-attribute-selected-option').text('Woman');
			}


		});
		document.getElementById("product-addtocart-button").disabled = true;
		$(document).on('click', '.swatch_image', function(e) {
			$('.custom-swatch-ul li').removeClass('active');
			let imagesrcpath = $(this).attr("src");
			let swatch_image_color_code_ext = imagesrcpath.split('/').pop();
			let swatch_image_color_code = swatch_image_color_code_ext.split('.').shift();
			let swatch_image_color_codes = swatch_image_color_code.split('_').slice(0, 2).join('_');
			//console.log('swatch_image_color_code_ext' + swatch_image_color_code_ext);
			//console.log('swatch_image_color_code' + swatch_image_color_code);
			//console.log('swatch_image_color_codes' + swatch_image_color_codes);
			$('.custom-swatch-ul *[product-option-id="' + swatch_image_color_codes + '"]').trigger("click");
		});
		jQuery(document).ready(function() {
			$(document).on('click', '.swatch-option.color', function(e) {
				console.log("working is here sfadfasd");
				$('.custom-swatch-ul li').removeClass('active');
				$('.product_row li').removeClass('active');
				let hexatval = false;
				if ($(this).hasClass("color")) {
					hexatval = $(this).attr('option-tooltip-value');
				} else {
					hexatval = $(this).closest('.swatch-option.color.selected').attr('option-tooltip-value');
				}
				console.log("This is hexvlaue needed" + hexatval);
				var colorswatchpusharray = [];
				var testcolorswatchpusharray = [];
				var foundproducts = [];
				setTimeout(function() {
					$('.product.media .fotorama__nav-wrap .fotorama__nav.fotorama__nav--thumbs').show();
					$('.product_row').hide();
					$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '50%');

					let productoptionidvararray = [];
					$('.visible-selection-show .swatch-option.color.selected').each(function(i, ele) {
						let letrgbyohex = $(this).attr('option-tooltip-value');
						//console.log("this is letrgbyohex" + letrgbyohex);
						let hexcolorcodeanchor = $('*[data-hexcolorcode="' + letrgbyohex + '"]');
						let hexcolorcodeanchorhexaval = $('*[data-hexcolorcode="' + hexatval + '"]');
						hexcolorcodeanchor.addClass('active');
						hexcolorcodeanchorhexaval.addClass('active');
						let productoptionid = hexcolorcodeanchor.find('a');
						let productoptionidhexaval = hexcolorcodeanchorhexaval.find('a');
						let hexcolorcodeanchorpod = productoptionid.attr('product-option-id');
						let productoptionidhexavalpod = productoptionidhexaval.attr('product-option-id');
						$('.product_row .swatch_image').each(function(i, e) {

							console.log("product_row .swatch_image triggered")

							if ($(this).attr('src').indexOf(hexcolorcodeanchorpod) != -1) {
								$(this).closest('li').addClass('active');
								productoptionidvararray.push(hexcolorcodeanchorpod);
							}
							if ($(this).attr('src').indexOf(productoptionidhexavalpod) != -1) {
								$(this).closest('li').addClass('active');
								productoptionidvararray.push(productoptionidhexavalpod);
							}
						});
					});
					let letrgbyohex = $(this).attr('option-tooltip-value');
					//console.log('background is clicked' + letrgbyohex);
					$('.container-fixed-gallery-images-thumb .swatch_image').each(function(i, ele) {
						$('*[data-hexcolorcode="' + letrgbyohex + '"]').addClass('active');
						$('*[data-hexcolorcode="' + hexatval + '"]').addClass('active');
					});
				}, 100);
			});
		});
		$(document).on('click', '.box-select-common-functional-boxer span', function(e) {
			//console.log("click is working");
			$('.bundle-selection-data').find('.boxer-bg-active-check').removeClass('boxer-bg-active-check');
			$('.bundle-selection-data').removeClass('visible-selection-show');
			$(this).find('.a.multi-select.select-link').removeClass('active');
			$('.fixed-size-group-foreach').addClass('disabled');
			$('.fixed-size-group-foreach').attr('disabled', true);
			setTimeout(function(ep) {
				$('#product-addtocart-button').attr('class', 'action primary tocart disable-add-to-cart-product-button');

			}, 200);
			if ($('body').hasClass('boxers-5479-performance-mid') || $("body").hasClass("boxers-5481-performance-mid")) {
				$('.swatch-option').attr('aria-checked', false);
				$('*[data-dependshowhidebyid="1"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="1"] .container-border-fixed-common-all').removeClass('atc');
				$('*[data-dependshowhidebyid="2"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="2"] .container-border-fixed-common-all').removeClass('atc');
				$('*[data-dependshowhidebyid="3"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="3"] .container-border-fixed-common-all').removeClass('atc');
				$('*[data-dependshowhidebyid="4"] .swatch-option').removeClass('selected');
				$('*[data-dependshowhidebyid="4"] .container-border-fixed-common-all').removeClass('atc');
			}
		});
		$(document).on('click', 'a.multi-select.select-link.active', function(e) {
			//console.log("its workign hurray");
			$('.a.multi-select.select-link').removeClass('custom-active');
			setTimeout(function(ep) {
				$('.bundle-selection-data').removeClass('custom-active');
				$(this).removeClass('custom-active');

			}, 100);
		});
		$(window).load(function() {
			//   $(document).find('.fixed-click-size-bundle-customization-functional-boxer')[0].trigger('click');
			$('.size-custom-functional-boxer .fixed-select-size-box').addClass('selected');
		//	$('.swatch-attribute-selected-option-custom-functional-boxer-size').text('All');
		//	$('.container-show-hide-attribute-common').removeClass('active');
		//	$('.fixed-click-size-bundle-customization-functional-boxer').addClass('selected');
		//	$('.box-select-common').removeClass('active');
		});
	}
	$(document).ready(function() {
		setTimeout(function() {
			$('.customrudra').first().remove();

		}, 10000);
	});
	$(window).load(function() {
		if ($('body').hasClass('boxers-5479-performance-mid')) {
			$('.swatch-option[option-id="5524"]').each(function(i, elem) {
				console.log("running times" + i);
				$(elem).addClass('selected').attr('aria-checked', true).attr('disabled', true);
				$(elem).closest('.gender_boxer').attr('option-selected', '5524');
			});
			$(document).on('click', '.swatch-option', function(e) {
				console.log("Maybe disturb here 1");
				let showhideid = $(this).closest('.visible-selection-show').attr('data-dependshowhidebyid');
				checkswatchclicktick(showhideid);
			});
		}
		if ($('body').hasClass('boxers-5481-performance-mid')) {
			$('.swatch-option[option-id="5525"]').each(function(i, elem) {
				console.log("running times" + i);
				$(elem).addClass('selected').attr('aria-checked', true).attr('disabled', true);
				$(elem).closest('.gender_boxer').attr('option-selected', '5525');
			});
			$(document).on('click', '.swatch-option', function(e) {
				console.log("Maybe disturb here ");
				let showhideid = $(this).closest('.visible-selection-show').attr('data-dependshowhidebyid');
				checkswatchclicktick(showhideid);
			});
		}
		if ($('body').hasClass('product-4-functional-boxer-shorts-skinetic-performance-mid-men-women----')) {
			/*	$('[data-showhideid="1"]').remove();
				$('[data-showhideid="2"]').remove();
				$('[data-showhideid="3"]').remove();


				$('[data-showhideid="4"]').addClass('active custom-active');
				$('[data-showhideid="4"]').html('<span>Set of 4</span>');
				$('[data-showhideid="4"]').trigger('click');


				$('[data-dependshowhidebyid="1"]').addClass('custom-visible-selection visible-selection-show');
				$('[data-dependshowhidebyid="2"]').addClass('custom-visible-selection visible-selection-show');
				$('[data-dependshowhidebyid="3"]').addClass('custom-visible-selection visible-selection-show');
				$('[data-dependshowhidebyid="4"]').addClass('custom-visible-selection visible-selection-show');


				$('.box-select-common-functional-boxer').addClass('has-clicked');*/
		}
	});
	if ($('body').hasClass('page-product-trainingsystem-fixed')) {
		$(window).load(function() {
			//   $(document).find('.fixed-click-size-bundle-customization-functional-boxer')[0].trigger('click');
		//	$('.size-custom-functional-boxer .fixed-select-size-box').addClass('selected');
		//	$('.swatch-attribute-selected-option-custom-functional-boxer-size').text($('.fixed-click-size-bundle-customization-functional-boxer').text());
		//	$('.container-show-hide-attribute-common').removeClass('active');
		//	$('.fixed-click-size-bundle-customization-functional-boxer').addClass('selected');
		//	$('.box-select-common').removeClass('active');


			$('#bundle-option-20:selected').removeAttr("selected");

		});
		$(document).ready(function() {

		//	$('.fixed-select-size-box').addClass('selected');

		//	$('.swatch-attribute-selected-option-custom-functional-boxer-size').text('One Size');



			$('#bundle-option-20 option:selected').removeAttr("selected");

			setTimeout(function() {
				let loadcolorselected = $('.fixed-color-trigger').attr('data-opt-id');
				if (loadcolorselected == '5483') {
					$('li[data-hexcolorcode="#ffffff"] a').trigger('click');
				} else if (loadcolorselected == '5484') {
					$('li[data-hexcolorcode="#fec34d"] a').trigger('click');
				} else if (loadcolorselected == '5485') {
					$('li[data-hexcolorcode="#4177d5"] a').trigger('click');
				} else if (loadcolorselected == '5486') {
					$('li[data-hexcolorcode="#000000"] a').trigger('click');
				}

				$('#bundle-option-20 option:selected').removeAttr("selected");

			}, 1000);
			$(document).on('click', '.swatch-option.color', function(e) {
				$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '50%');

				$('.product.media .fotorama__nav-wrap .fotorama__nav.fotorama__nav--thumbs').show();
				$('.product_row').hide();
				//	$('li[data-hexcolorcode="' + letrgbyohex + '"] a').trigger('click');
				$('.custom-swatch-ul li').removeClass('active');
				$('.visible-selection .swatch-option.color.selected').each(function(i, ele) {
					let letrgbyohex = $(this).attr('option-tooltip-value');
					$('li[data-hexcolorcode="' + letrgbyohex + '"]').addClass('active');
				});



			});
			$(document).on('click', '.swatch_image', function(e) {
				$('.custom-swatch-ul li').removeClass('active');
				let imagesrcpath = $(this).attr("src");
				let swatch_image_color_code_ext = imagesrcpath.split('/').pop();
				let swatch_image_color_code = swatch_image_color_code_ext.split('.').shift();
				let swatch_image_color_codes = swatch_image_color_code.split('_').slice(0, 2).join('_');
				//console.log('swatch_image_color_code_ext' + swatch_image_color_code_ext);
				//console.log('swatch_image_color_code' + swatch_image_color_code);
				//console.log('swatch_image_color_codes' + swatch_image_color_codes);
				$('.custom-swatch-ul *[product-option-id="' + swatch_image_color_codes + '"]').trigger("click");

				$('#bundle-option-20:selected').removeAttr("selected");

			});
		});
	}
	if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-fabric')) {
		$(document).ready(function() {
			$('[data-showhideid="1"]').addClass('custom-active active');
			$('[data-dependshowhidebyid="1"]').addClass(' visible-selection-show visible-selection');
			$('[data-dependshowhidebyid="4"]').addClass(' visible-selection');
			$('input[name="super_attribute[21][59][200]"]').val("5629");
			$('#bundle-option-21 option[value="59"]').prop("selected", true);
			$('input[name="super_attribute[21][59][200]"]').prop('disabled', false);

		//	$('.size-custom-functional-boxer .fixed-select-size-box').addClass('selected');
		//	$('.swatch-attribute-selected-option-custom-functional-boxer-size').text($('.fixed-click-size-bundle-customization-functional-boxer').text());
			$('.container-show-hide-attribute-common').removeClass('active');
		//	$('.fixed-click-size-bundle-customization-functional-boxer').addClass('selected');
			$('.box-select-common').removeClass('active');
		});
		$('.box-select-common-functional-boxer').on('click', 'a', function(e) {
			console.log("event fired");
			$('input[name="super_attribute[21][59][200]"]').val("5629");
			$('input[name="super_attribute[21][59][200]"]').prop('disabled', false);
			$('#bundle-option-21 option[value="59"]').prop("selected", true);

			$('[data-dependshowhidebyid="4"]').addClass(' visible-selection');
			$('.container-fixed-gaslift_size-product-view-only').find('.super-attribute-select').val("5629");
			$('.container-fixed-gaslift_size-product-view-only .super-attribute-select').val("5629");
			$('.container-fixed-gaslift_size-product-view-only .swatch-option:first-child').addClass(' selected');
			$('[data-option-id="5629"]').text('Included');
			//	$('.fixed-select-gaslift_size-box box-select-common .swatch-attribute-selected-option').text($('#option-label-gaslift_size-200-item-5629').text());
			$('#option-label-gaslift_size-200-item-5629').attr('aria-checked', true);
			$('.fixed-select-gaslift_size-box.box-select-common .swatch-attribute-selected-option').text("M/L");
			$('#option-label-gaslift_size-200-item-5629').closest('.swatch-attribute-options').attr('aria-activedescendant', 'option-label-gaslift_size-200-item-5629');
		//	$('#option-label-gaslift_size-200-item-5630').addClass('disabled disableClick');
		//	$('#option-label-gaslift_size-200-item-5631').addClass('disabled disableClick');
			$('.parent-container-common-show-hide-fixed.swatch-attribute.gaslift_size').attr('option-selected', '5629');
		});
		$(document).on('click', '.swatch-option.image', function(e) {
			setTimeout(function() {
				$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '50%');
				$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('top', '725px');
				$('.container-fixed-gallery-images-thumb').css('height', '365px');

				$('.product.media .fotorama__nav-wrap .fotorama__nav.fotorama__nav--thumbs').show();
				$('.product_row').hide();
			}, 1000);
			//	$('li[data-hexcolorcode="' + letrgbyohex + '"] a').trigger('click');
			if ($(this).closest('.container-show-hide-attribute-common').hasClass('container-fixed-seat_color-product-view-only')) {
				console.log("workinginsidehexcode");
				$('.custom-swatch-ul li').removeClass('active');
				$('.background-type-image[option-label="' + $(this).attr('option-id') + '"]').closest('li').addClass('active');
			}
		});
		$(document).on('click', '.background-type-image', function(e) {
			console.log("background-type-image click is working new");
			let $prodoptid = $(this).attr('product-option-id');
			let $swatch_image = $('.swatch_image[alt="' + $prodoptid + '"]');
			$('.product_row li').removeClass('active');
			$swatch_image.closest('li').addClass('active');
			$swatch_image.trigger('click');
		});
		$(document).on('click', 'img.swatch_image', function(e) {
			console.log("swatch_image click is working new");
			let $prodoptid = $(this).attr('alt');
			let $swatch_image = $('.background-type-image[product-option-id="' + $prodoptid + '"]');
			$('.custom-swatch-ul li').removeClass('active');
			$swatch_image.closest('li').addClass('active');
		});
	}

	if ($('body').hasClass('product-office-swivel-chair-ergotec-synchro-pro-leather')) {

		$(document).ready(function() {
			$('[data-showhideid="1"]').addClass('custom-active active');
			$('[data-dependshowhidebyid="1"]').addClass(' visible-selection-show visible-selection');

		//	$('.size-custom-functional-boxer .fixed-select-size-box').addClass('selected');
		//	$('.swatch-attribute-selected-option-custom-functional-boxer-size').text($('.fixed-click-size-bundle-customization-functional-boxer').text());
		//	$('.container-show-hide-attribute-common').removeClass('active');

		});


		$('.box-select-common-functional-boxer').on('click', 'a', function(e) {
			console.log("event fired");
			$('input[name="super_attribute[21][59][200]"]').val("5629");
			$('.container-fixed-gaslift_size-product-view-only').find('.super-attribute-select').val("5629");
			$('.container-fixed-gaslift_size-product-view-only .super-attribute-select').val("5630");
			$('.container-fixed-gaslift_size-product-view-only .swatch-option:first-child').addClass(' selected');
			$('[data-option-id="5629"]').text('Included');
			//	$('.fixed-select-gaslift_size-box box-select-common .swatch-attribute-selected-option').text($('#option-label-gaslift_size-200-item-5629').text());
			$('#option-label-gaslift_size-200-item-5629').attr('aria-checked', true);
			$('.fixed-select-gaslift_size-box.box-select-common .swatch-attribute-selected-option').text("M/L");
			$('#option-label-gaslift_size-200-item-5629').closest('.swatch-attribute-options').attr('aria-activedescendant', 'option-label-gaslift_size-200-item-5629');
		//	$('#option-label-gaslift_size-200-item-5630').addClass('disabled disableClick');
		//	$('#option-label-gaslift_size-200-item-5631').addClass('disabled disableClick');
			$('.parent-container-common-show-hide-fixed.swatch-attribute.gaslift_size').attr('option-selected', '5629');



			$('input[name="super_attribute[22][63][192]"]').val("5625");
			$('.parent-container-common-show-hide-fixed.swatch-attribute.seat_color').attr("option-selected", "5625");
			$('.fixed-click-size-bundle-customization-functional-boxer').addClass('selected');
			$('#option-label-seat_color-192-item-5625').addClass('selected');


		});



		$(document).on('click', '.swatch-option.image', function(e) {
			setTimeout(function() {
				$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('width', '50%');
				$('.fotorama__nav-wrap.fotorama__nav-wrap--horizontal').css('top', '725px');
				$('.container-fixed-gallery-images-thumb').css('height', '365px');

				$('.product.media .fotorama__nav-wrap .fotorama__nav.fotorama__nav--thumbs').show();
				$('.product_row').hide();
			}, 1000);
			//	$('li[data-hexcolorcode="' + letrgbyohex + '"] a').trigger('click');
			console.log("workinginsidehexcode");

		});

	}




	// configurable products

	if ($('body').hasClass('page-product-configurable')) {

		//  let params = getURLParams(window.location.href);
		$.urlParam = function(name) {
			var results = new RegExp('[\?&]' + name + '=([^&#]*)')
				.exec(window.location.search);

			return (results !== null) ? results[1] || 0 : false;
		}



		$(document).ready(function() {

			if ($.urlParam('color')) {
				//  let swatchobj = $('.swatch_colors[data-option="' + params['color'] + '"]').attr('count');

				let swatchobj = $('.swatch_colors[data-option="' + $.urlParam("color") + ']"').attr('count');

				$('.swatch_colors[data-option="' + $.urlParam('color') + '"]').closest('li').addClass('active');
				//  $('.swatch_image[count="' + swatchobj + '"]').closest('a').trigger('click');




				var swatchclicktriggered = true;


				
					$('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function() {
					    
					  

						//   $('.swatch-option[data-option="' + $.urlParam('color') + '"]').click();
	$('.swatch_colors[data-option="' + $.urlParam('color') + '"]').trigger("click");
					

						setTimeout(function() {
					
					    
							$('.swatch-option[option-id="' + $.urlParam('color') + '"]').trigger("click");
							
						

						}, 500);



						//  swatchclicktriggered = false;

						console.log("swatchclicktriggered");

			
					});
				




			} else if ($.urlParam('colors_glasses')) {
				let swatchobj = $('.swatch_colors[data-option="' + $.urlParam('colors_glasses') + '"]').attr('count');

				$('.swatch_colors[data-option="' + $.urlParam('colors_glasses') + '"]').closest('li').addClass('active');
				$('.swatch_image[count="' + swatchobj + '"]').closest('a').trigger('click');

				setTimeout(function() {

					$('.swatch_colors[data-option="' + $.urlParam('colors_glasses') + '"]').trigger('click');
					//	$('.swatch_image[count="' + swatchobj + '"]').closest('a').trigger('click');
				}, 500);



			} else {
				console.log("working on document else");

				setTimeout(function() {

					$('li.main_image').find('.swatch_image').trigger('click');
				}, 1000);

			}


			setTimeout(function() {

				let containerfixedsizeproductviewonly = $('.container-fixed-size-product-view-only').find('.swatch-option');

				if (containerfixedsizeproductviewonly.length <= 1) {




					$('.container-fixed-size-product-view-only').find('.swatch-option').trigger('click');


					$('.container-fixed-size-product-view-only').addClass('.swatch-option').trigger('click');

					//	$('.fixed-select-size-box').addClass('disableClick ');

					console.log("working inside swatch-option size" + containerfixedsizeproductviewonly.length);




				}

			}, 500);



			$(document).on('click', '.swatch_colors', function(e) {
				let $prodoptid = $(this).attr('data-option');

				console.log("swatch_colors click is working" + $prodoptid);

				let $swatch_image = $('.swatch_image[alt="' + $prodoptid + '"]');


				$('.ul-swatch li').removeClass('active');
				$('.product_row li').removeClass('active');


				$('.swatch-option.color').removeClass('selected');
				$('.swatch-option.image').removeClass('selected');
				$('.swatch-option').removeClass('disabled');
		//		$('.swatch-option[option-id="' + $prodoptid + '"]').addClass('selected');

				$('.swatch-option.color').attr('aria-checked', false);
				$('.swatch-option.image').attr('aria-checked', false);

				$('.swatch-option.color').prop('disabled', false);
				$('.swatch-option.image').prop('disabled', false);

			//	$('.swatch-option[option-id="' + $prodoptid + '"]').attr('aria-checked', true);



				if ($swatch_image.length > 0) {

					console.log("working inside swatch_image if" + $swatch_image.length);


					$swatch_image.closest('li').addClass('active');

					$swatch_image.trigger('click');

				} else {

					console.log("working inside swatch_image else" + $swatch_image.length);

					$('.swatch_image[count="' + $(this).attr('count') + '"]').closest('li').addClass('active');

					$('.swatch_image[count="' + $(this).attr('count') + '"]').trigger('click');

				}





				setTimeout(function() {

					console.log("working before visibilitychange");

	$('.swatch-option[option-id="' + $prodoptid + '"]').click();
					document.addEventListener('visibilitychange', function() {
						if (document.hidden) {
							console.log("hidded click is working for inactive tab");
							$('.swatch-option[option-id="' + $prodoptid + '"]').click();
						} else {
							$('.swatch-option[option-id="' + $prodoptid + '"]').click();
						}

					});

					$('.fixed-select-color-box box-select-common .swatch-attribute-selected-option').text($('.swatch-option[option-id="' + $prodoptid + '"]').attr('aria-label'));
					$('.fixed-select-colors_glasses-box box-select-common .swatch-attribute-selected-option').text($('.swatch-option[option-id="' + $prodoptid + '"]').attr('aria-label'));

				}, 300);

				console.log("trigger click is working");



				$('.parent-container-common-show-hide-fixed.swatch-attribute').attr('option-selected', $prodoptid);

				$('.fixed-select-color-box strong').text($('.swatch-option[option-id="' + $prodoptid + '"]').attr('aria-label'));
				$('.fixed-select-colors_glasses-box strong').text($('.swatch-option[option-id="' + $prodoptid + '"]').attr('aria-label'));


				$(this).closest('li').addClass('active');

			});







			$(document).on('click', '.swatch-option.image', function(e) {


				console.log("swatch-option.image triggered")

				let $prodoptid = $(this).attr('option-id');
				let $swatch_colors = $('.swatch_colors[data-option="' + $prodoptid + '"]');
				$('.ul-swatch li').removeClass('active');
				$('.product_row li').removeClass('active');
				$swatch_colors.closest('li').addClass('active');



				/* */


				let $swatch_colorsattr = $swatch_colors.attr('alt');
				let $swatch_colorscount = $swatch_colors.attr('count');

				let $swatch_image = $('.swatch_image[alt="' + $swatch_colorsattr + '"]');


				if (typeof $swatch_image !== 'undefined' && $swatch_image !== false && $.isNumeric($swatch_image.attr('alt'))) {


					$swatch_image = $('.swatch_image[alt="' + $swatch_colorsattr + '"]');


				} else {
					$swatch_image = $('.swatch_image[count="' + $swatch_colorscount + '"');


				}

				$swatch_image.closest('li').addClass('active');

				setTimeout(function() {

					///	$swatch_colors.trigger('click');
				}, 500);

			});


			$(document).on('click', '.swatch-option.color', function(e) {

				console.log("swatch-option.color triggered")

				let $prodoptid = $(this).attr('option-id');
				let $swatch_colors = $('.swatch_colors[data-option="' + $prodoptid + '"]');
				$('.ul-swatch li').removeClass('active');
				$('.product_row li').removeClass('active');
				$swatch_colors.closest('li').addClass('active');



				/* */


				let $swatch_colorsattr = $swatch_colors.attr('alt');
				let $swatch_colorscount = $swatch_colors.attr('count');

				let $swatch_image = $('.swatch_image[alt="' + $swatch_colorsattr + '"]');


				if (typeof $swatch_image !== 'undefined' && $swatch_image !== false && $.isNumeric($swatch_image.attr('alt'))) {


					$swatch_image = $('.swatch_image[alt="' + $swatch_colorsattr + '"]');


				} else {
					$swatch_image = $('.swatch_image[count="' + $swatch_colorscount + '"');


				}

				$swatch_image.closest('li').addClass('active');

				setTimeout(function() {

					console.log(" $swatch_image.trigger");
					  $swatch_image.trigger('click');
				}, 500);


			});


		});







	}

	// configurable jacket products

//	if ($('body').hasClass('page-product-jacket-fixed') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men-women') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-men') || $('body').hasClass('product-outdoor-functional-pants-titanium-3-in-1-women_1')) {


	    console.log("- found workinghere");

	

		jQuery(document).ready(function() {
		    
		    	if (!$('body').hasClass('page-product-trainingsystem-fixed')){
		    
		    	$('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function() {
		    
		    	    
		setTimeout(function() {
		    
		    
		
console.log("found here"+$('.rudrapriceCurrency:first').val());
		
		     $('.product-container-left').find('span.price').text($('.rudrapriceCurrency').val());
		     
		    //  $('.product-container-left').find('span.data-max-price-fixed').text($('.rudrapriceCurrency').val());
		     
		     
		     
		  
		    


				}, 500);
		
		    	});
				
				
			$(document).on('click', '.swatch-option.text', function(e) {
				console.log("working is here jacket");
				let optionid = $(this).attr('option-id');

				console.log("THis is optionid" + optionid)

				let swatchprice = $('.price-fixed-swatch-detail[data-option-id=' + optionid + ']').text();

				console.log("THis is price" + swatchprice);
				
				if(swatchprice){
				    

				 $('.product-container-left').find('span.price').text(swatchprice);
				 
				  $('.product-container-left').find('span.data-max-price-fixed').text(swatchprice);
				  
				}

				setTimeout(function() {

				}, 500)



			});
			
			
			
				$(document).on('click', '.swatch-option.color', function(e) {
				console.log("working is here jacket");
				let optionid = $(this).attr('option-id');

				console.log("THis is optionid" + optionid)

				let swatchprice = $('.price-fixed-swatch-detail[data-option-id=' + optionid + ']').text();
				
				console.log("swatchprice "+swatchprice);
				
				if(!swatchprice || swatchprice ==''){
				    	console.log("inside swatchprice "+swatchprice);
				     swatchprice = $('.price-fixed-swatch-detail[option-id=' + optionid + ']:visible:first').clone().children().remove().end().text();
				}
				
				

				console.log("THis is price here" + swatchprice);
				
				if(swatchprice){
				    

				 $('.product-container-left').find('span.price').text(swatchprice);
				 
				  $('.product-container-left').find('span.data-max-price-fixed').text(swatchprice);
				  
				}

				setTimeout(function() {

				}, 500)



			});
		    	}
		
			
			
			// backpack, bycicle, upperarm
			
			
			if ($('body').hasClass('catalog-product-view')  || $('body').hasClass('catalog-product-view product-folding-bike-op20-shimano-alvio-3x8') || $('body').hasClass('product-upper-arm-blood-pressure-monitor-alarm-travel-alarm-clock-bpm-med8') || $('body').hasClass('product-upper-arm-blood-pressure-monitor-alarm-travel-alarm-clock-bpm-med8')) {
                
                
                
		    	$('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function() {
		    
		    	    
		setTimeout(function() {
		    
		    
		
console.log("found here"+$('.rudrapriceCurrencybcpck:first').val());
		
		     $('.product-container-left').find('span.price').text($('.rudrapriceCurrency').val());
		     
		     $('.product-container-left').find('span.data-max-price-fixed').text($('.rudrapriceCurrency').val());
		     
		     
		     
		  
		    


				}, 500);
		
		    	});
		    	
		    	
		    	
		    	$(document).on('click', '.swatch-option.color', function(e) {
				console.log("backpack is here");
				let optionid = $(this).attr('option-id');

				console.log("THis is backpack" + optionid)

				let swatchprice = $('.price-fixed-swatch-detail[data-option-id=' + optionid + ']').text();

				console.log("THis is price backpack" + swatchprice);
				
				if(swatchprice){
				    

				 $('.product-container-left').find('span.price').text(swatchprice);
				 
				  $('.product-container-left').find('span.data-max-price-fixed').text(swatchprice);
				  
				}

			



			});
		    	

            }
			
		});


//	}




	// universal functions
	function checkswatchclicktick(showhideid) {
		//console.log("This is showhideid" + showhideid)
		$('*[data-dependshowhidebyid="' + showhideid + '"]').each(function(i, ele) {
			if ($(ele).find('[aria-checked="true"]').length >= $(ele).find('.swatch-attribute-options').length) {
				//console.log("next working selectedlength" + $(ele).find('.swatch-attribute-options').find('.selected').length);
				//console.log("next working length" + $(ele).length);
				//	$('#product-addtocart-button').removeClass('boxer-bg-active-check');
				$('*[data-dependshowhidebyid="' + showhideid + '"] .bundle-option-label').addClass('boxer-bg-active-check');
				//  $('*[data-showhideid="' + nextnumberattribute + '"]').removeClass('disableClick');
			} else {
				//  console.log("next not working here");
				// $('*[data-showhideid="' + nextnumberattribute + '"]').removeClass('disableClick');
				$('*[data-dependshowhidebyid="' + showhideid + '"] .bundle-option-label').removeClass('boxer-bg-active-check');
			}
		});
	}

	function getURLParams(url) {
		return Object.fromEntries(new URL(url).searchParams.entries());
	}



	$('body').on('click', 'a', function(e) {
		/*  console.log("working ahref");
		  console.log($(this).attr('href'));*/

		console.log("classNametrigger" + $(this).attr('class'));


		if ($(this).attr('href') == "#") {
		    console.log("hrefis "+$(this).attr('href'))
			e.preventDefault();

		}

	});



	jQuery(document).ready(function() {


		resizeswatchcontainerleft();
	});

	jQuery(window).resize(function() {
		resizeswatchcontainerleft();
	});


	function resizeswatchcontainerleft() {
		if ($("body").hasClass("catalog-product-view") && $("body").hasClass("page-product-bundle-configuare") && $(".fotorama__nav--thumbs").is(":visible") /*if shown*/ ) {


			$(".fotorama__nav-wrap--horizontal").css('top', $(".product-info-main").outerHeight(true) + $(".color-swatch-options-container-left").outerHeight(true) + 50);
			$(".container-fixed-gallery-images-thumb").css('height', $(".fotorama__nav__shaft").height());


		}
	}




});