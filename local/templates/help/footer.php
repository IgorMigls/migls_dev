<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div> <!-- ./ b-help-wrapper -->

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
					<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki"></div>
				</div>
			</div>
		</div>
	</div>

</footer>

<include></include>
</div><!-- ==================== /.b-wrapper ============== -->
</div><!-- ==================== /#b-layout =============== -->

<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>

</body>
</html>