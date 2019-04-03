<div class="profile_wrap ng-cloak">
	<div class="lk__avatar">
		<div class="avator__col1 b-ib">
			<img ng-if="User.PERSONAL_PHOTO.src" ng-src="{{User.PERSONAL_PHOTO.src}}" alt="" class="avator__img">
			<div ng-if="!User.PERSONAL_PHOTO.src" class="avator__img">
				<i class="fa fa-user-secret" aria-hidden="true"></i>
			</div>
		</div>
		<div class="avator__col2 b-ib">
			<div class="avator__title"><span>{{User.NAME}} </span><span>{{User.LAST_NAME}}</span></div>

			<div class="file_upload">
				<a href="javascript:" class="change__avator">Загрузить аватарку</a>
				<input type="file" nv-file-select="" name="avatar" uploader="uploader" />
			</div>

		</div>
	</div>
	<div class="lk__add-address">

		<div ng-if="User.note.length > 0" class="alert alert-success" role="alert">
			<p ng-repeat="msg in User.note">{{msg}}</p>
		</div>

		<div ng-if="User.errors.length > 0" class="alert alert-danger" role="alert">
			<p ng-repeat="msg in User.errors">{{msg}}</p>
		</div>

		<form action="" autocomplete="off"><span class="lk__form-descr lk__m-left-none">Основные настройки</span>
			<label class="form__label"> <span class="span__label">Эл. почта</span>
				<?/*<input type="text" ng-model="User.EMAIL" disabled placeholder="e-mail" class="form__input form__input_middle">*/?>
				<button class="b-button b-button_check b-button_green b-button_big b-button_reset"
					type="button" ng-click="showChangeMail()">Изменить e-mail
				</button>
			</label>
			<label class="form__label">
				<span class="span__label">Пароль</span>
				<button class="b-button b-button_check b-button_green b-button_big b-button_reset"
				        type="button" ng-click="showForm()">Изменить пароль
				</button>
			</label>

			<span class="lk__form-descr lk__m-left-none">Личная информация</span>
			<label class="form__label"> <span class="span__label">Имя</span>
				<input type="text" ng-model="User.NAME" maxlength="30" placeholder="имя" class="form__input form__input_middle">
			</label>
			<label class="form__label"> <span class="span__label">Фамилия</span>
				<input type="text" ng-model="User.LAST_NAME" maxlength="30" placeholder="фамилия"
				class="form__input form__input_middle">
			</label>
			<label class="form__label"> <span class="span__label">Телефон</span>
				<input type="text" ng-model="User.PERSONAL_MOBILE" mask="+7(999)999-99-99" placeholder="+7(___)___-__-__"
					class="form__input form__input_middle">
			</label>


			<div class="lk__date-rapper">
				<label class="form__label form__label_ib"> <span class="span__label">Дата рождения</span>
					<input type="text"
						ng-model="User.BIRTHDAY.CURRENT.d" placeholder="12"
						maxlength="2" class="form__input form__input_xs" mask="" typeMask="day">
				</label>
				<div class="b-ib lk__profile">
					<div class="b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-ib">
						<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
							<select ng-model="User.BIRTHDAY.CURRENT.m"
								class="b-custom-select js-custom-select2 month_select"
								selectizeEx="User.BIRTHDAY.MONTHS"
								ng-options="item as item for item in User.BIRTHDAY.MONTHS">
							</select>
						</div>
					</div>
				</div>
				<input type="text" mask="9999" ng-model="User.BIRTHDAY.CURRENT.y" placeholder="год" class="form__input form__input_xs2">
			</div>

			<button type="button" class="b-button b-button_check b-button_green b-button_big" ng-click="saveUser(User)">Сохранить</button>
		</form>
	</div>
	<div class="hide_content">
		<div class="b-popup b-popup-card b-popu-card_add-rec profile_forms_popup" id="change_pass_profile">
			<div class="b-popup-recovery">
				<button class="b-button b-button__close-popup"></button>
				<div class="b-popup-cart__head">
					<div class="b-products-block-top b-ib bg_reverse">
						<div class="cart__img-wrapper">
							<div class="cart__prod-title">
								<div class="icon-h"></div>
							</div>
							<div class="recovery__title">Изменить пароль</div>
						</div>
					</div>
				</div>
				<div class="accepted__content">
					<div class="lk__add-address">
						<form method="post" ng-submit="savePasswordForm()" name="PasswordForm" novalidate autocomplete="off">

							<div ng-if="User.errors.length > 0" class="alert alert-danger" role="alert">
								<p ng-repeat="msg in User.errors">{{msg}}</p>
							</div>

							<label class="form__label"> <span class="span__label">Текущий пароль</span>
								<input type="password" ng-model="Pass.OLD_PASSWORD"
										placeholder="Старый пароль"
										autocomplete="off"
										class="form__input form__input_middle"
										ng-required="true" name="OLD_PASSWORD" />
							</label>

							<label class="form__label"> <span class="span__label">Новый пароль</span>
								<input type="password" ng-model="Pass.PASSWORD"
								       placeholder="Ваш пароль"
										autocomplete="off"
								       class="form__input form__input_middle"
								       ng-required="true" name="PASSWORD" />
							</label>
							<label class="form__label"> <span class="span__label">Повторите новый пароль</span>
								<input type="password" ng-model="Pass.CONFIRM_PASSWORD"
								       placeholder="Повторите пароль"
										autocomplete="off"
								       class="form__input form__input_middle"
								       ng-required="true" name="CONFIRM_PASSWORD" />
							</label>

							<button type="submit" class="b-button b-button_check b-button_green b-button_big">Сохранить изменения</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="b-popup b-popup-card b-popu-card_add-rec profile_forms_popup" id="change_email_profile">
			<div class="b-popup-recovery">
				<button class="b-button b-button__close-popup"></button>
				<div class="b-popup-cart__head">
					<div class="b-products-block-top b-ib bg_reverse">
						<div class="cart__img-wrapper">
							<div class="cart__prod-title">
								<i class="fa fa-envelope-o" aria-hidden="true"></i>
							</div>
							<div class="recovery__title">Изменить e-mail</div>
						</div>
					</div>
				</div>
				<div class="accepted__content">
					<div class="lk__add-address">
						<form method="post" ng-submit="saveEmailProfile()" name="EmailForm" novalidate autocomplete="off">

							<div ng-if="ChangeError.length > 0" class="alert alert-danger" role="alert">
								<p ng-repeat="msg in ChangeError">{{msg}}</p>
							</div>

							<label class="form__label"> <span class="span__label">Ваш текущий e-mail</span>
								<input type="text" ng-model="Change.EMAIL"
									class="form__input form__input_middle" placeholder="Ваш текущий e-mail"
									ng-required="true" name="EMAIL" />
							</label>
							<label class="form__label"> <span class="span__label">Новый e-mail</span>
								<input type="text" ng-model="Change.NEW_EMAIL"
									placeholder="Новый e-mail"
									autocomplete="off"
									class="form__input form__input_middle"
									ng-required="true" name="NEW_EMAIL" />
							</label>
							<label class="form__label"> <span class="span__label">Ваш пароль</span>
								<input type="password" ng-model="Change.PASSWORD"
									placeholder="Ваш пароль"
										autocomplete="off"
									class="form__input form__input_middle"
									ng-required="true" name="PASSWORD" />
							</label>
							<button type="submit" class="b-button b-button_check b-button_green b-button_big">Сохранить изменения</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>