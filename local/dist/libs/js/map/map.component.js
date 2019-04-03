$(function () {
	ymaps.ready(UL.Maps.init);

	$('#change_start_address').magnificPopup({
		items: {
			src: '#popupHello',
			type: 'inline'
		},
		midClick: false,
		showCloseBtn: true,
	});


	$("#search_address_start").autocomplete({
		source: function (request, response) {
			var post = {query: request.term, count: 10};
			$.post('/service/UL/Suggestions/getAddress', JSON.stringify(post), function (result) {
				var sResult = [];
				if (result.STATUS = 1 && is.array(result.DATA.suggestions)) {
					$.each(result.DATA.suggestions, function (k, arItem) {
						sResult.push(arItem.value);
					});
					response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);

					$('.ui-autocomplete').css({'display':'block','z-index':'1080'});
				}
			}, 'json');
		},
		minLength: 3,
		select: function (event, ui) {
			UL.Maps.searchAddress(false, ui.item.label);
		}
	});
});

	/*$('#search_address_start').blur(function () {
	 if ($(this).val() == '') {
	 $('.suggestions_address').hide(0);
	 $('.suggestions_address li').remove();
	 }
	 });

	 $(document).on('keydown', '.b-popup-hello-form__item', function (ev) {
	 if (ev.keyCode != 38 && ev.keyCode != 40 && ev.keyCode != 13 && ev.keyCode != 8) {
	 UL.Maps.suggestions(ev);
	 }
	 });

	 $('body').on('click', function () {
	 $('.suggestions_address').hide(0);
	 $('.suggestions_address li').remove();
	 })*/