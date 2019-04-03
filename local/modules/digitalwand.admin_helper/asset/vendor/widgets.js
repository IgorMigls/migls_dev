/**
 * Created by dremin_s on 27.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

$(function () {
	var $inputTranslit = $('input.link_translit'), translitTo = $inputTranslit.data('translitto');

	$inputTranslit.each(function () {
		var $input = $(this);
		var $translitToInput = $('[name*=' + translitTo +']');
		$input.next('img').clone().attr('id', 'translite_in_'+ translitTo).appendTo($translitToInput.parent());

		$input.next('img').on('click', function () {
			if($(this).hasClass('yes')){
				$(this).removeClass('yes').addClass('no').attr('src', '/bitrix/themes/.default/icons/iblock/unlink.gif');
				$translitToInput = $('[name*=' + translitTo +']');
				$translitToInput.next('img').remove();
				$input.next('img').clone().attr('id', 'translite_in_'+ translitTo).appendTo($translitToInput.parent());
			} else {
				$(this).removeClass('no').addClass('yes').attr('src', '/bitrix/themes/.default/icons/iblock/link.gif');
				var value = BX.CYandexTranslator($input.val());
				if(value === undefined || value === ''){
					value = BX.translit($input.val());
				}
				$translitToInput = $('[name*=' + translitTo +']');
				$translitToInput.next('img').remove();
				$input.next('img').clone().attr('id', 'translite_in_'+ translitTo).appendTo($translitToInput.parent());

				$translitToInput.val(value);
			}
			if($translitToInput.length > 0 && $input.next('img').hasClass('yes')){
				$('input.link_translit').on('keypress', function (ev) {
					if($(this).next('img').hasClass('yes')){
						var value = BX.CYandexTranslator(ev.target.value);
						if(value === undefined || value === ''){
							value = BX.translit(ev.target.value);
						}

						$translitToInput.val(value);
					}
				});
			}
		});

		if($translitToInput.length > 0){
			$('input.link_translit').on('keypress', function (ev) {
				if($(this).next('img').hasClass('yes')){
					var value = BX.CYandexTranslator(ev.target.value);
					if(value === undefined || value === ''){
						value = BX.translit(ev.target.value);
					}

					$translitToInput.val(value);
				}
			});

			$('input.link_translit').on('change', function (ev) {
				if($(this).next('img').hasClass('yes')){
					var value = BX.CYandexTranslator(ev.target.value);
					if(value === undefined || value === ''){
						value = BX.translit(ev.target.value);
					}

					$translitToInput.val(value);
				}
			});
		}
	});

});