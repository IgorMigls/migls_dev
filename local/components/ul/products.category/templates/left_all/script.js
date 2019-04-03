BX(function () {
	$(".catalog__acordion").navgoco({accordion: true});
	$(".acord-main").navgoco({accordion: true});
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