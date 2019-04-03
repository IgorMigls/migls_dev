<?
/** @global CMain $APPLICATION */
/** @global CUser $USER */
$Asset = \Bitrix\Main\Page\Asset::getInstance();

$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/bootstrap.min.css');
$APPLICATION->SetAdditionalCSS('/bitrix/css/main/font-awesome.min.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/admin/bootstrap_admin_fix.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/admin/profile_import.css');

$Asset->addJs('http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');
$Asset->addJs('/local/dist/libs/js/is.min.js');

$Asset->addJs('/local/dist/libs/js/angular/angular.min.js');
$Asset->addJs('/local/dist/libs/js/angular/angular-resource.min.js');
$Asset->addJs('/local/dist/libs/js/angular/angular-sanitize.min.js');
//$Asset->addJs('/local/dist/libs/js/angular/angular-file-upload.min.js');
$Asset->addJs('/local/dist/libs/js/angular/aService.js');
$Asset->addJs('/local/dist/js/admin/importProfiles.js');

CUtil::InitJSCore(['ajax']);
?>
<div ng-app="ProfileApp">
	<import-profile>
		<ul id="folder_items">
			<li ng-repeat="first in Folder.Items">
				<a ng-click="showSubItems($event)" href="javascript:" class="handler_subitems">
					<span>{{first.NAME}}</span>
					<i ng-if="first.ITEMS" class="fa fa-plus" aria-hidden="true"></i>
					<i ng-if="first.ITEMS" class="fa fa-minus" aria-hidden="true"></i>
				</a>
				<a href="javascript:" class="profile_change"><span>Добавить в профиль</span></a>
				<ul ng-if="first.ITEMS">
					<li ng-repeat="second in first.ITEMS">
						<a ng-click="showSubItems($event)" href="javascript:" class="handler_subitems">
							<span>{{second.NAME}}</span>
							<i ng-if="second.ITEMS" class="fa fa-plus" aria-hidden="true"></i>
							<i ng-if="second.ITEMS" class="fa fa-minus" aria-hidden="true"></i>
						</a>
						<a href="javascript:" class="profile_change"><span>Добавить в профиль</span></a>

						<ul ng-if="second.ITEMS">
							<li ng-repeat="third in second.ITEMS">
								<a ng-click="showSubItems($event)" href="javascript:" class="handler_subitems">
									<span>{{third.NAME}}</span>
									<i ng-if="third.ITEMS" class="fa fa-plus" aria-hidden="true"></i>
									<i ng-if="third.ITEMS" class="fa fa-minus" aria-hidden="true"></i>
								</a>
								<a href="javascript:" class="profile_change"><span>Добавить в профиль</span></a>

								<ul ng-if="third.ITEMS">
									<li ng-repeat="fourth in third.ITEMS">
										<a href="javascript:"><span>{{fourth.NAME}}</span></a>
										<a href="javascript:" class="profile_change"><span>Добавить в профиль</span></a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>

<!--		<pre>{{Folder.Items | json}}</pre>-->

	</import-profile>
</div>
