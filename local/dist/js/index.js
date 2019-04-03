/**
    © Mihail Firsov
    mihailfirsov.ru
    dev.firsov@gmail.com
*/


function mainSliderAnimation (mainSliderOptions, time) {
    setTimeout(function() {
        $(mainSliderOptions.slide).removeClass('loaded');
        $(mainSliderOptions.slide).filter('.slick-current').addClass('loaded');
    }, time);
}

$(function () {

    /*
     * Toggle class
     */
    $(document).on('click', '.js-toggle-class', function() {
        var el = $(this).data('toggleElement');
        if (el == 'this') {
            $(this).toggleClass($(this).data('toggleClass'));
        } else {
            $($(this).data('toggleElement')).toggleClass($(this).data('toggleClass'));

            var $containerToggle = $('.cart__comments').closest($(this).data('toggleElement'));

            if($containerToggle.hasClass($(this).data('toggleClass'))){
                $containerToggle.find('.cart__comments').hide(0);
            } else {
                $containerToggle.find('.cart__comments').show(0);
            }
        }
        return false;
    });

    /*
     * Main slider
     */
    var mainSlider = $('.js-main-slider'),
        mainSliderOptions = {
            dots: true,
            arrows: false,
            slide: '.js-main-slider-item',
            autoplay: true,
            fade: true,
            autoplaySpeed: 5000,
            speed: 600
        };

    if (mainSlider.length) {
        mainSlider
            .on('init', function() {mainSliderAnimation(mainSliderOptions, 500)})
            .on('afterChange', function() {mainSliderAnimation(mainSliderOptions, 50)})
            .slick(mainSliderOptions);
    }

    $(document).on('click', '.js-tab', function() {
        var slider = $($(this).data('tab')).find('.js-products-slider');
        if (slider.length) {
            slider.slick('unslick');
            slider.slick({
                dots: false,
                arrows: true,
                slide: '.js-products-slider-item',
                slidesToShow: 5,
                slidesToScroll: 1
            });

        }
    });


}); /////////////////////////////////////// END READY