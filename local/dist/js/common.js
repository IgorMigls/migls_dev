/***********************
    © Mihail Firsov
    mihailfirsov.ru
    dev.firsov@gmail.com
***********************/

$(function () {

    /*
     * Product slider
     */

    var shopCategorySlider = $('.js-products-slider-4'),
        shopCategorySliderOption = {
            dots: true,
            arrows: false,
            slide: '.js-products-slider-item',
            slidesToShow: 3,
            slidesToScroll: 3
        };

    if (shopCategorySlider.length) {
        shopCategorySlider.slick(shopCategorySliderOption);
    }

	var shopDiscountSlider = $('.js-products-slider-3');

	if (shopDiscountSlider.length) {
		shopDiscountSlider.slick({
			dots: true,
			arrows: true,
			slide: '.js-products-slider-item',
			slidesToShow: 1,
			slidesToScroll: 1
		});
	}
	// var searchSlider = $('.js-products-slider-search');
	//
	// if (searchSlider.length) {
	// 	searchSlider.slick({
	// 		dots: false,
	// 		arrows: true,
	// 		slide: '.js-products-slider-item',
	// 		slidesToShow: 4,
	// 		slidesToScroll: 4
	// 	});
	// }

	var shopPopular = $('.js-products-slider-detail_shop');
	shopPopular.slick({
		dots: false,
		arrows: true,
		slide: '.js-products-slider-item',
		slidesToShow: 4,
		slidesToScroll: 4
	});


    /*
     * Custom scroll
     */

    $('.js-custom-scroll').jScrollPane({autoReinitialise: true, contentWidth: 150});


    /*
    Custom select
     */
    if ($('.js-custom-select').length > 0) {
        $('.js-custom-select').selectize();
    }

    if ($('.js-custom-select2').length > 0) {
        $('.js-custom-select2').selectize({
            readOnly: true,
            onDelete: function() { return false }
        });
    }


    /*
     * TABS
     */
    $('.b-main-products').on('click', '.js-tab', function() {
        var $this = $(this),
            tabs = $(this).parents('.js-tabs'),
            tabsWrapper = $(tabs.data('tabsWrapper'));
        tabs.find('.js-tab').removeClass('active');
        tabsWrapper.find('.js-tab-content').removeClass('active');
        $this.addClass('active');
        $($this.data('tab')).addClass('active');
    });


    $('.js-close-popup').on('click', function() {
        $.magnificPopup.close();
    });



}); /////////////////////////////////////// END READY

