<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>
<br />
<br />
<br />

	<div class="soc_links">
		<a href="javascript:" class="vk_link">ВКОНТАКТЕ</a>
		<br/>
		<a href="javascript:" class="vk_out">ВЫХОД</a>
	</div>

	<div id="soc_auth_vk"></div>
	<script src="https://vk.com/js/api/openapi.js?150" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			VK.init({
				apiId: 6291063
			});

			// VK.Widgets.Auth("soc_auth_vk", {
			// 	"onAuth": function (data) {
			// 		console.info(data);
			// 	}
			// });

			$('.soc_links .vk_link').click(function () {


				VK.Auth.login(function(response) {


					if(response.hasOwnProperty('user')){
						var dataUser = {
							name: response.user.first_name,
							lastName: response.user.last_name,
							xmlId: response.user.id,
						};

						
					}

					// if (response.session) {
					// 	/* Пользователь успешно авторизовался */
					// 	if (response.settings) {
					// 		/* Выбранные настройки доступа пользователя, если они были запрошены */
					// 	}
					// } else {
					// 	/* Пользователь нажал кнопку Отмена в окне авторизации */
					// }
				});

			});

			$('.soc_links .vk_out').click(function () {
				VK.Auth.logout(function (data) {
					console.info(data);
				});
			})
		});
	</script>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>