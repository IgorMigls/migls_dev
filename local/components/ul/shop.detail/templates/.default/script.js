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

		$('#' + $(this).data('timeId')).fadeIn(400, function () {
			$(this).addClass('active');
		});
	})
});