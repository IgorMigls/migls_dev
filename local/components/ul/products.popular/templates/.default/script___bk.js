$(function () {
	$('.index_products_basket').each(function () {
		var $plus = $(this).find('.b-button_plus');
		var $minus = $(this).find('.b-button_minus');
		var $basketBtn = $(this).find('.add_basket_btn');
		var $quantity = $(this).find('.b-product-preview__input');

		$plus.on('click', function () {
			var quantity = $quantity.val();
			quantity++;
			$quantity.val(quantity);
		});
		$minus.on('click', function () {
			var quantity = $quantity.val();
			quantity--;
			if(quantity <= 0){
				quantity = 1;
			}
			$quantity.val(quantity);
		});

		$basketBtn.on('click', function () {
			var id = $(this).data('id');
			var url = '/local/ajax/basket.php?ID='+ id +'&quantity='+ $quantity.val()+ '&sessid='+ BX.bitrix_sessid();
			$.get(url, function (result) {
				if(result.status == 1){
					sweetAlert("Товар добавлен в корзину", "", "success");

					$.get('/local/ajax/basket.php?getBasket=Y', function (res) {
						$('#basket_update').html(res);
					});
				} else {
					sweetAlert("Ошибка добавления в корзину", "", "error");
				}
			}, 'json')
		});
	});
});