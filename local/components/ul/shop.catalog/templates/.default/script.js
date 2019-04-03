/**
 * Created by Grandmaster.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

$(function () {
	var popupDates = $.magnificPopup.instance;
	$('#show_all_interval').click(function () {
		popupDates.open({
			items: {
				src: '#shop_times1',
				type: 'inline',
				enableEscapeKey: true,
				showCloseBtn: false,
				closeOnBgClick: true,
			}
		});
	});

	$('#all_shop_link_time').magnificPopup({
		items: {
			src: '#all_shop_times',
			type: 'inline',
			enableEscapeKey: true,
			showCloseBtn: false,
			closeOnBgClick: true,
		}
	});


	$('.b-button.interval__date').on('click', function (ev) {
		ev.preventDefault();
		$('.time_items.active').hide(0).removeClass('active');
		$('.b-button.interval__date').removeClass('timer_shop_hover');
		$(this).addClass('timer_shop_hover');

		$('#' + $(this).data('timeId')).fadeIn(400, function () {
			$(this).addClass('active');
		});
	});

	$('.catalog__item__sub a.catalog__item').on('click', function (ev) {
		if($(this).parent('li').find('.subcategory').length > 0){
			// ev.preventDefault();

			$('li .subcategory').slideUp(300);

			if($(this).parent('li').find('.subcategory').is(':visible') === true){
				$(this).parent('li').find('.subcategory').slideUp(350);
			} else {
				$(this).parent('li').find('.subcategory').slideDown(350);
			}

		}
	});

});