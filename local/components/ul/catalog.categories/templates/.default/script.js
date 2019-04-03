/**
 * Created by Grandmaster.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
$(function () {
	$('.catalog__sub').hide(0);
	$('.catalog__acordion .catalog__list .result__item').on('click', function () {
		if($(this).parent().find('.catalog__sub').length > 0){
			var _self = $(this);
			$('.catalog__sub').slideUp(350);
			if(_self.parent().find('.catalog__sub').is(':visible')){
				_self.parent().find('.catalog__sub').slideUp(350);
			} else {
				_self.parent().find('.catalog__sub').slideDown(350);
			}
		}
	});

	// $(".catalog__acordion").navgoco({accordion: true});
	// $(".acord-main").navgoco({accordion: true});
	$(".acord__item").click(function (event) {
		event.preventDefault();
		$('.acord__item').addClass('open');
		$(this).addClass('open')
	});
	$('.acord__link').click(function (event) {
		event.preventDefault();
		$('.acord__item-2').removeClass('jsAcordActive');
		if ($('.acord__item').hasClass('open')) {
			$('.b-mac').addClass('fadeOut')
		}
		else {
			$('.b-mac').removeClass('fadeOut')
		}
	});
	$('.acord__item-2').click(function (event) {
		$('.acord__item-2').removeClass('jsAcordActive');
		$(this).addClass('jsAcordActive')
	});

	$('.open_item_menu > ul').css('display','block');
	$('.catalog__list').eq(3).css('marginTop', 0);


});