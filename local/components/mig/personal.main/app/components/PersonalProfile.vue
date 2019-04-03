<template>
	<div class="profile_user" v-if="user" v-loading="userSaver">
		<div class="profile_user-title" v-loading="avaLoader">
			<div class="avatar">
				<img :src="user.AVA.src" v-if="user.AVA" />
				<i class="fa fa-user-secret" v-else></i>
			</div>
			<div class="username">
				<div>{{user.NAME}}</div>
				<div>{{user.LAST_NAME}}</div>
				<el-upload class="upload-ava" action="/local/ajax/uploadAvatar.php"
						:on-success="avatarSuccess" :on-progress="onProgressAva"
						name="ava" :show-file-list="showFileAva" accept="image/*">
					<el-button type="text">Загрузить аватарку</el-button>
				</el-upload>
			</div>
		</div>
		
		<div class="profile_user-password">
			<p class="title_block">Основные настройки</p>
			<div class="form_item">
				<label>Эл. почта</label>
				<div class="form_field fake_field">
					<input type="text" class="form__input" disabled :value="user.EMAIL" />
					<button class="b-button b-button_check b-button_green b-button_big b-button_reset" @click="showEmailForm" type="button">
						Изменить
					</button>
				</div>
			</div>
			<div class="form_item fake_field">
				<label>Пароль</label>
				<div class="form_field">
					<input type="password" class="form__input" disabled value="111122222" />
					<button class="b-button b-button_check b-button_green b-button_big b-button_reset" @click="showPassForm" type="button">
						Изменить
					</button>
				</div>
			</div>
		</div>
		
		<div class="profile_user-main">
			<p class="title_block">Личная информация</p>
			<div class="form_item">
				<label>Имя</label>
				<div class="form_field">
					<input type="text" v-model="form.NAME" maxlength="30" name="NAME"
							placeholder="имя" class="form__input form__input_middle" />
				</div>
			</div>
			<div class="form_item">
				<label>Фамилия</label>
				<div class="form_field">
					<input type="text" v-model="form.LAST_NAME" maxlength="30" name="LAST_NAME"
							placeholder="Фамилия" class="form__input form__input_middle" />
				</div>
			</div>
			<div class="form_item">
				<label>Телефон</label>
				<div class="form_field">
					<vue-mask-input mask="\+\7 (111) 111-11-11" v-model="form.PERSONAL_MOBILE" name="PERSONAL_MOBILE"
							placeholder="Телефон" class="form__input form__input_middle"></vue-mask-input>
				</div>
			</div>
			<div class="form_item">
				<label>Дата рождения</label>
				<div class="form_field date_field">
					<input type="text" placeholder="д." maxlength="2" class="form__input form__input_xs" v-model="form.PERSONAL_BIRTHDAY.day" />
					<el-select v-model="form.PERSONAL_BIRTHDAY.month" placeholder="Месяц" class="grey-select">
						<el-option v-for="(item, index) in month" :key="index" :label="item" :value="(index + 1)"></el-option>
					</el-select>
					<input type="text" placeholder="год" maxlength="4" class="form__input form__input_xs2" v-model="form.PERSONAL_BIRTHDAY.year" />
				</div>
			</div>
			<div class="form_item">
				<label></label>
				<div class="form_field">
					<button class="b-button b-button_check b-button_green b-button_big b-button_reset save_btn"
							type="button" @click="saveUser">Сохранить
					</button>
				</div>
			</div>
		</div>
		
		<modal-profile :show="showPopup" classWrap="profile_popup_wrap">
			<transition-group tag="div" name="custom-classes-transition" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
				
				<div class="b-popup b-popup-card b-popu-card_add-rec profile_forms_popup" id="change_email_profile" key="change_email" v-if="showChangeEmail">
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
								<form method="post" @submit.prevent="changeEmailSubmit" name="EmailFormPersonal" novalidate="" autocomplete="off">
									<label class="form__label"> <span class="span__label">Ваш текущий e-mail</span>
										<span>
											<input type="text" class="form__input form__input_middle" placeholder="Ваш текущий e-mail"
													name="CURRENT_EMAIL" v-validate="'required|email'" v-model="emailForm.CURRENT_EMAIL" />
											<error :show="errors.has('CURRENT_EMAIL')" :leftMargin="25">Введите ваш текущий e-mail правильно</error>
										</span>
									</label>
									<label class="form__label"> <span class="span__label">Новый e-mail</span>
										<input type="text" placeholder="Новый e-mail" autocomplete="new-email" class="form__input form__input_middle"
												name="NEW_EMAIL" v-validate="'required|email'" v-model="emailForm.NEW_EMAIL" />
										<error :show="errors.has('NEW_EMAIL')" :leftMargin="25">Введите новый e-mail правильно</error>
									</label>
									<label class="form__label"> <span class="span__label">Ваш пароль</span>
										<input type="password" placeholder="Ваш пароль" autocomplete="new-password" class="form__input form__input_middle"
												name="PASSWORD" v-validate="'required'" v-model="emailForm.PASSWORD" />
										<error :show="errors.has('PASSWORD')" :leftMargin="25">Введите новый пароль</error>
									</label>
									<button type="submit" class="b-button b-button_check b-button_green b-button_big">
										Сохранить изменения
									</button>
								</form>
							</div>
						</div>
					</div>
					<button type="button" class="mfp-close" @click="closeEmailForm">×</button>
				</div>
				
				<div class="b-popup b-popup-card b-popu-card_add-rec profile_forms_popup" id="change_pass_profile" key="change_pass" v-if="showChangePass">
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
								<form method="post" @submit.prevent="changePassSubmit" name="EmailFormPass" novalidate="" autocomplete="off">
									<label class="form__label"> <span class="span__label">Текущий пароль</span>
										<span>
											<input type="password" autocomplete="new-password" class="form__input form__input_middle"
													name="CURRENT_PASS" v-validate="'required'" v-model="passForm.CURRENT_PASS" />
											<error :show="errors.has('CURRENT_PASS')" :leftMargin="25">Введите ваш текущий пароль</error>
										</span>
									</label>
									<label class="form__label"> <span class="span__label">Новый пароль</span>
										<input type="password" autocomplete="new-password" class="form__input form__input_middle"
												name="NEW_PASS" v-validate="'required'" v-model="passForm.NEW_PASS" />
										<error :show="errors.has('NEW_PASS')" :leftMargin="25">Введите новый пароль</error>
									</label>
									<label class="form__label"> <span class="span__label">Повторите новый пароль</span>
										<input type="password" autocomplete="new-password" class="form__input form__input_middle"
												name="NEW_CONFIRM_PASS" v-validate="'required'" v-model="passForm.NEW_CONFIRM_PASS" />
										<error :show="errors.has('NEW_CONFIRM_PASS')" :leftMargin="25">Повторите новый пароль</error>
									</label>
									<button type="submit" class="b-button b-button_check b-button_green b-button_big">
										Сохранить изменения
									</button>
								</form>
							</div>
						</div>
					</div>
					<button type="button" class="mfp-close" @click="closePassForm">×</button>
				</div>
				
			</transition-group>
		</modal-profile>
	</div>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	import ModalProfile from 'Utilities/Modal.vue';
	import Error from 'Utilities/validator/Error.vue';
	import VueMaskInput from 'vue-masked-input';
	
	export default {
		name: "personal-profile",
		props: {},
		data() {
			return {
				avaLoader: false,
				showFileAva: false,
				userSaver: false,
				form: {
					NAME: '',
					LAST_NAME: '',
					PERSONAL_MOBILE: '',
					PERSONAL_BIRTHDAY: {
						day: '',
						month: '',
						year: ''
					}
				},
				month: [
					'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
					'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'
				],
				showPopup: false,
				showChangeEmail: false,
				showChangePass: false,
				emailForm: {
					CURRENT_EMAIL: '',
					NEW_EMAIL: '',
					PASSWORD: ''
				},
				passForm: {
					CURRENT_PASS: '',
					NEW_PASS: '',
					NEW_CONFIRM_PASS: '',
				}
			}
		},
		methods: {
			...mapActions('profile', [
				'fetchUser', 'saveUserFields', 'changeEmail', 'changePassword',
			]),
			avatarSuccess(response) {
				this.$store.commit('profile/userUpdate', { AVA: response.data });
				this.avaLoader = false;
				this.showFileAva = false;
			},

			onProgressAva() {
				// this.showFileAva = true;
				this.avaLoader = true;
			},

			saveUser() {
				this.userSaver = true;
				this.saveUserFields(this.form).then(r => {
					this.userSaver = false;
					this.$notify({
						title: 'Профиль сохранен',
						type: 'success'
					});
				});
			},

			showEmailForm() {
				this.showPopup = true;
				this.showChangeEmail = true;
				// this.showChangePass = false;
			},
			
			showPassForm(){
				this.showPopup = true;
				// this.showChangeEmail = false;
				this.showChangePass = true;
			},

			closeEmailForm(){
				this.showPopup = false;
				this.showChangeEmail = false;
				this.showChangePass = false;
				this.emailForm = {
					CURRENT_EMAIL: this.user.EMAIL,
					NEW_EMAIL: '',
					PASSWORD: ''
				};
			},

			closePassForm(){
				this.showPopup = false;
				this.showChangeEmail = false;
				this.showChangePass = false;
				this.passForm = {
					CURRENT_PASS: '',
					NEW_PASS: '',
					NEW_CONFIRM_PASS: '',
				};
			},
			
			changeEmailSubmit() {
				this.$validator.validateAll().then(result => {
					if (result) {
						this.changeEmail(this.emailForm).then(res => {
							if(res.data.ERRORS === null){
								this.fetchUser();
								this.$swal(res.data.DATA.success, '', 'success');
								this.closeEmailForm();
							}
						})
					}
				});
			},
			changePassSubmit() {
				this.$validator.validateAll().then(result => {
					if (result) {
						this.changePassword(this.passForm).then(res => {
							if(res.data.ERRORS === null){
								this.fetchUser();
								this.$swal(res.data.DATA.success, '', 'success');
								this.closeEmailForm();
							}
						})
					}
				});
			}
		},
		watch: {},
		created() {
			this.fetchUser().then(data => {
				if (data.data.DATA !== null) {
					_.forEach(data.data.DATA, (el, code) => {
						if (this.form.hasOwnProperty(code)) {
							if (code === 'PERSONAL_BIRTHDAY' && el instanceof Object) {
								el.month = _.toInteger(el.month)
							}
							this.form[code] = el;
						}
					});

					this.emailForm.CURRENT_EMAIL = data.data.DATA.EMAIL;
				}
				
			});
		},
		beforeUpdate() {
		},
		components: {
			ModalProfile, Error, VueMaskInput
		},
		computed: {
			...mapGetters('profile', [
				'user'
			])
		},
		mounted() {
		}
	}
</script>

<style lang="scss">
	.profile_user {
		padding-top: 60px;
		padding-bottom: 30px;
		
		&-title {
			display: flex;
			margin-bottom: 15px;
			
			.username {
				font-size: 36px;
				font-family: 'HelveticaNeueCyr-Thin';
				display: flex;
				flex-direction: column;
				justify-content: center;
			}
			
		}
		
		.avatar {
			padding-right: 20px;
			
			.fa {
				font-size: 130px;
			}
			
			img {
				border: 1px solid #fff;
				border-radius: 50%;
			}
		}
		
		&-password {
		
		}
		
		&-main {
		
		}
		
		.title_block {
			color: #898989;
			font-size: 13px;
			display: inline-block;
			margin-bottom: 15px;
		}
		
		.form_item {
			margin: 10px 0;
			display: flex;
			align-items: center;
			
			label {
				width: 125px;
			}
			
			.form__input_middle {
				width: 335px;
			}
			
			.grey-select {
				width: 160px;
				background: #f0efec;
				border: none;
				height: 50px;
				border-radius: 30px;
				
				.el-input, .el-input__inner {
					background: transparent;
					border: none;
					border-radius: 30px;
					font-size: 18px;
					padding: 5px 10px;
					font-family: 'HelveticaNeueCyr-Thin';
				}
			}
			
			.form__input_xs {
				margin-right: 0;
			}
			
			.save_btn {
				width: 335px;
				text-align: left;
			}
		}
		
		.date_field {
			display: flex;
			justify-content: space-between;
			width: 335px;
		}
		
		.profile_popup_wrap {
			.modal_content {
				justify-content: center;
				align-items: center;
				position: fixed;
			}
			
			.cart__prod-title .fa {
				font-size: 7rem;
				color: #fff;
			}
		}
		
		.fake_field {
			.form__input {
				width: auto;
				background: #f0efec;
				border: none;
				padding: 15px 20px;
				-webkit-border-radius: 30px;
				border-radius: 30px;
				font-size: 18px;
				color: #808080;
				font-family: 'HelveticaNeueCyr-Thin';
			}
			
		}
	}
</style>