/**
 * Created by Grandmaster.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
$(function () {
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
	})
})