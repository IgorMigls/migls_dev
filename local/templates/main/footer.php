<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<footer class="b-footer b-ib-wrapper">
	<div class="b-footer-steps b-ib-wrapper">
		<div style="background-image: url('/local/dist/images/footer-step_1.jpg')" class="b-footer-steps__step b-ib">
			<? $APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/inc/baner_1.php"
				)
			); ?>
		</div>
		<!--///////-->
		<div style="background-image: url('/local/dist/images/footer-step_2.jpg')" class="b-footer-steps__step b-ib">
			<? $APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/inc/baner_2.php"
				)
			); ?>
		</div>
		<!--////////-->
		<div style="background-image: url('/local/dist/images/footer-step_3.jpg')" class="b-footer-steps__step b-ib">
			<? $APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/inc/baner_3.php"
				)
			); ?>
		</div>
	</div>
	<div class="b-footer-wave"></div>



	<div class="b-footer-bottom b-ib-wrapper">
		<div class="bottom_item">
			<h4 class="b-footer-bottom__title">Есть вопросы?</h4>
			<div class="b-footer-bottom__info b-ib">
				<? $APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/inc/bottom_1.php"
					)
				); ?>
			</div>
		</div>
		<div class="bottom_item">
			<h4 class="b-footer-bottom__title">Телефон горячей линии</h4>
			<div class="b-footer-bottom__info b-ib">
				<? $APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/inc/contact_f_1.php"
					)
				); ?>
			</div>
		</div>

		<div class="bottom_item">
			<h4 class="b-footer-bottom__title">
				Свяжитесь с нами любым<br>
				удобным для вас способом
			</h4>
			<div class="b-footer-bottom__info b-ib">
				<div class="soc_bottom">
					<a href="https://www.instagram.com/migls_ru/" target="_blank">
						<i class="fab fa-instagram"></i>
					</a>
					<a href="https://vk.com/migls_ru" target="_blank">
						<i class="fab fa-vk"></i>
					</a>

<!--					<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki"></div>-->
				</div>
			</div>
		</div>
	</div>
</footer>

<?//$APPLICATION->IncludeComponent('ul:address.set','', array(), false);?>

<!--<div a-service-loader="{overlay: {background: '#fff', opacity: '0.7'}, loader:{class:'fa fa-circle-o-notch fa-spin fa-2x fa-fw'}}"></div>-->
<div class="hide_product" ng-controller="ProductCtrl">
	<section ui-view="product"></section>
</div>
<include></include>

<?
global $USER;
if($USER->IsAuthorized()){
	$CUser = new CUser();
	$arUser = $CUser->GetList($b, $o, ['ID' => $USER->GetID()], ['SELECT' =>['UF_*']])->Fetch();

	/** @var \Bitrix\Main\HttpRequest $request */
	$request = \Bitrix\Main\Context::getCurrent()->getRequest();
	$deliveryFreeCookie = $request->getCookie('FREE_DELIVERY');
	if($arUser['UF_FREE_DELIVERY'] == 1 && $deliveryFreeCookie != 'Y' && strlen($_SESSION['REGIONS']['ADDRESS']) > 0){
		?>
		<div class="hide_content">
			<div id="free_delivery">
				<div class="popup_replace_2">
					<div class="replace_2_header">
						<div class="container"><div class="icon_head_free_delivery"></div></div>
					</div>
					<div class="replace_2_body">
						<div class="container">
							<h2 style="text-align: center">Ура! Вы получили одну<br>бесплатную доставку!</h2>
							<p style="text-align: center">Закажите продукты в ближайшие 7 дней, чтобы наш подарок не пропал даром!</p>
							<div class="btn_group"  style="text-align: center">
								<a href="javascript:" id="confirm_free" class="b-button replace_btn_ confirm_orders">Спасибо</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(function () {
				var deliveryPopup = $.magnificPopup.instance;
				deliveryPopup.open({
					items: {src: '#free_delivery' },
					type: 'inline',
					closeOnBgClick: false,
					showCloseBtn: false,
					callbacks: {
						beforeClose: function () {
							$.post('/local/ajax/free_delivery.php', {free: 'Y'}, function (res) {

							});
						}
					}
				});

				$('#confirm_free').on('click', function (ev) {
					ev.preventDefault();
					$.magnificPopup.close();
				})
			});
		</script>
		<?
	}
}

if($request->get('auth') == 'Y'){?>
	<script>
		$(function () {
			$('#btn_auth_form_top').click();
		});
	</script>
<?} ?>
</div><!-- ==================== /.b-wrapper ============== -->
</div><!-- ==================== /#b-layout =============== -->
<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
</body>
</html>
