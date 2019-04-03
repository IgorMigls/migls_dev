<template>
	<modal :show="openWindow" @close-modal="closeModal" class-content="wrap_address" :close-overlay="false">
		<transition-group tag="div" name="custom-classes-transition" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
			<div class="b-popup b-popup-hello" v-if="openWindowSearch" key="openWindowSearch">
				<div class="b-popup-hello__wrapper b-ib-wrapper" v-loading="loading">
					<div class="b-popup-hello__left b-ib b-popup-hello__left_reset">
						<div class="b-popup-hello__ymap" id="ya_maps_hello"></div>
					</div>
					<div class="b-popup-hello__right b-ib">
						<div class="b-popup-hello__title">Сменить адрес</div>
						<div class="b-popup-hello-form">
							<div class="b-popup-hello-form__note" style="font-size: 18px">Найти адрес</div>
							<div class="b-popup-hello-form__item b-popup-hello-form__item_reset">
								<el-autocomplete autocomplete="off"
										class="b-form-control" placeholder="Например: г. Самара, ул. Дыбенко, д. 30"
										v-model="search" :fetch-suggestions="querySearch" :trigger-on-focus="false" :debounce="300">
									<template slot="append">
										<button type="button" class="b-button b-button_green" @click="chooseAddress">Найти</button>
									</template>
								</el-autocomplete>
							</div>
							
							<address-list></address-list>
						
						</div>
					</div>
				</div>
				
				<button type="button" @click="closeModal" class="b-button b-button__close-popup close_change_address"></button>
			</div>
			
			<div class="b-popup b-popup-card b-popu-card_add-card" v-if="openEditAddress" key="openEditAddress">
				<div class="b-popup-recovery address_edit" v-loading="loading">
					<div class="b-popup-cart__head">
						<div class="b-products-block-top b-ib bg_reverse">
							<div class="cart__img-wrapper">
								<div class="cart__prod-title">
									<div class="icon-g"></div>
								</div>
								<div class="recovery__title">Сохранить адрес</div>
							</div>
						</div>
						<button type="button" @click="closeModal" class="b-button b-button__close-popup mfp-close"></button>
					</div>
					<div class="accepted__content">
						<div class="lk__add-address">
							<form method="post" novalidate="" autocomplete="off" :name="formIdRand">
								<span class="lk__form-descr"><i class="star">*</i> - обязательны для заполнения</span>
								<label class="form__label"><span class="span__label"><i class="star">*</i>Город</span>
									
									<el-autocomplete autocomplete="off" class="form__input form__input_middle" placeholder="Город"
											v-model="addressForm.CITY" :fetch-suggestions="searchCity" :trigger-on-focus="false" :debounce="300">
									</el-autocomplete>
									
								</label>
								<label class="form__label"><span class="span__label"><i class="star">*</i>Улица</span>
									
									<el-autocomplete autocomplete="off" class="form__input form__input_middle" placeholder="Улица"
											v-model="addressForm.STREET" :fetch-suggestions="searchStreet" :trigger-on-focus="false" :debounce="300">
									</el-autocomplete>
									
								</label>
								<div class="form__col1">
									<label class="form__label"><span class="span__label span__label_short"><i class="star">*</i>Дом</span>
										<input type="text" placeholder="Дом" class="form__input form__input_short"
												name="HOUSE" value="22A" v-model="addressForm.HOUSE"/>
									</label>
									<label class="form__label"><span class="span__label span__label_short"><i class="star">*</i>Квартира</span>
										<input type="text" placeholder="Квартира" class="form__input form__input_short"
												name="APARTMENT" value="12" v-model="addressForm.APARTMENT"/>
									</label>
								</div>
								<div class="form__col1">
									<label class="form__label"><span class="span__label span__label_short">Этаж</span>
										<input type="text" placeholder="Этаж" class="form__input form__input_short"
												name="FLOOR" value="22" v-model="addressForm.FLOOR"/>
									</label>
									<label class="form__label"><span class="span__label span__label_short">Подъезд</span>
										<input type="text" placeholder="Подъезд" class="form__input form__input_short"
												name="ZIP" value="12312312" v-model="addressForm.ZIP"/>
									</label>
								</div>
								<label class="form__label">
								<span class="span__label"><i class="star">*</i>Название
									<div class="lable__tooltip">
										<span class="tooltip__content animated zoomIn">Название адреса, под которым он будет сохранен. Например, "мой дом" или "моя работа".</span>
									</div>
								</span>
									<input type="text" placeholder="Название" name="PROFILE_NAME"
											class="form__input form__input_middle form__input_tooltip"
											v-model="addressForm.PROFILE_NAME"/>
								</label>
								<button type="button" class="b-button b-button_check b-button_green b-button_big" @click="save">Сохранить адрес</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<div class="b-popup b-popup-hello" v-if="isModalPopup" key="isModalPopup">
				<div class="b-popup-hello__wrapper b-ib-wrapper">
					<div class="b-popup-hello__left b-ib">
						<div class="b-popup-hello__items b-ib-wrapper">
							<div class="b-popup-hello__item b-ib" style="height: 121px"><img src="/local/dist/images/hello-1.png" /><span>Магазин</span></div>
							<div class="b-popup-hello__item b-ib" style="padding-top: 7px"><img src="/local/dist/images/hello-2.png" /><span>Наши<br> курьеры</span></div>
							<div class="b-popup-hello__item b-ib"><img src="/local/dist/images/hello-3.png" /><span>Заказ у<br> вас дома</span></div>
						</div>
						
						<div class="maps_hello_modal">
							<div id="ya_maps_hello_modal"></div>
						</div>
					</div>
					
					<div class="b-popup-hello__right b-ib">
						<div class="b-popup-hello__title">Получите Ваши продукты<br>из местных магазинов</div>
						<div class="b-popup-hello-form">
							<div class="b-popup-hello-form__title">Введите Ваш адрес или выберите зону доставки</div>
							<!--<span id="errors_address">Адрес не найден</span>-->
							
							<div class="b-popup-hello-form__item ui-widget">
								<el-autocomplete autocomplete="off"
										class="b-form-control" placeholder="Например: г. Самара, ул. Дыбенко, д. 30"
										v-model="search" :fetch-suggestions="querySearch" :trigger-on-focus="false" :debounce="300">
									<template slot="append">
										<button type="button" class="b-button b-button_green" @click="chooseAddress">Найти</button>
									</template>
								</el-autocomplete>
							</div>
							<div class="b-popup-hello-form__note">Информация необходима для отображения доступных магазинов</div>
						</div>
					</div>
				</div>
			</div>
			
			<error-address v-show="noValidAddress" key="error-address">
				<div slot="description">
					<h4 style="text-align: center">В данный момент мы не доставляем по Вашему адресу :(</h4>
					<p>Но Вы можете ввести свой e-mail, чтобы мы знали, где нас больше всего ждут, а также мы
						отправим Вам письмо, как только доставка станет возможна!</p>
				</div>
				<form @submit.prevent="saveAddress" novalidate="" autocomplete="off" method="post" class="" id="email_no_address" name="EMAILS">
					<div>
						<div class="field_form_wrap">
							<p style="color: red" v-if="errorEmail">{{errorEmail}}</p>
							<p style="color: #4bce5b;" v-else-if="successEmail">{{successEmail}}</p>
							<input type="text" v-model="emailForAddress" placeholder="example@migls.ru" name="EMAIL_NO_ADDRESS" class="form__input form__input_accepted" />
						</div>
						<div class="field_submit_wrap">
							<button type="submit" class="b-button b-button_green b-button_check">Отправить</button>
							<a class="return_home_win" href="javascript:" @click="setOtherAddress">Выбрать другой адрес</a>
						</div>
					</div>
				</form>
			</error-address>
			
		</transition-group>
		
	</modal>
</template>

<script>
	import Modal from 'Utilities/Modal.vue';
	import ErrorAddress from 'Utilities/maps/ErrorAddress.vue';
	import {mapActions, mapGetters} from 'vuex';
	import AddressList from "./addressList";

	const defaultAddressFields = {
		APARTMENT: '',
		CITY: '',
		FLOOR: '',
		HOUSE: '',
		PHONE: '',
		STREET: '',
		ZIP: '',
		PROFILE_NAME: '',
		PROFILE_ID: null
	};
	
	export default {
		name: "address-window",
		props: {
			deny: {type: Boolean|String}
		},
		data() {
			return {
				addressForm: Object.assign({}, defaultAddressFields),
				// search: 'Самара, ул Победы, д 18',
				// search: 'Самара, ул Победы, д 122',
				search: '',
				isModalPopup: false,
				noValidAddress: false,
				emailForAddress: '',
				errorEmail: false,
				successEmail: false,
				formIdRand: BX.util.getRandomString(6)
			}
		},
		methods: {
			...mapActions([
				'showWindow', 'saveAddress', 'searchAddress', 'loadAddressSearch', 'searchAddressInArea',
				'saveAddressEmail', 'loadCity', 'loadStreet',
			]),
			closeModal(){
				this.$store.commit('openWindowSearch', false);
				this.$store.commit('openWindow', false);
			},
			
			save(){
				this.searchAddress(this.addressForm).then(res => {
					if(res === 0){
						this.$store.commit('loading', false);
						this.$store.commit('openEditAddress', false);
						this.noValidAddress = true;
					} else {
						this.$store.dispatch('saveAddress', this.addressForm);
					}

				}).catch(err => {
					this.$store.commit('loading', false);
					this.$store.commit('openEditAddress', false);
					this.noValidAddress = true;
				});
			},

			querySearch(queryString, cb) {
				this.loadAddressSearch(queryString).then(res => {
					if(res.data.DATA !== null){
						cb(res.data.DATA);
					}
				})
			},
			
			searchCity(queryString, cb) {
				this.loadCity(queryString).then(res => {
					if(res.data.DATA !== null){
						cb(res.data.DATA);
					}
				})
			},
			
			searchStreet(queryString, cb) {
				this.loadStreet({query: queryString, city: this.addressForm.CITY}).then(res => {
					if(res.data.DATA !== null){
						cb(res.data.DATA);
					}
				})
			},

			chooseAddress(){
				this.searchAddressInArea(this.search).then(result => {
					if (result !== false && result.data.DATA != null) {
						let backUrl = result.data.DATA.BACK_URL || '/';
						window.location.assign(backUrl);
					}
				}).catch(err => {
					this.isModalPopup = false;
					this.$store.commit('openWindowSearch', false);
					this.noValidAddress = true;
				});
			},

			saveAddress(){
				this.errorEmail = false;
				this.successEmail = false;
				this.saveAddressEmail(this.emailForAddress).then(res => {
					if(res.data.DATA.error !== null){
						this.errorEmail = res.data.DATA.error;
					} else {
						this.emailForAddress = '';
						this.successEmail = res.data.DATA.msg;
					}
				})
			},

			setOtherAddress(){
				if(this.deny == 1){
					this.noValidAddress = false;
					this.isModalPopup = true;
					this.$store.dispatch('showWindow', true);
				} else {
					this.noValidAddress = false;
					this.$store.commit('openWindowSearch', true);
					this.$store.dispatch('showWindow', true);
				}
			},

		},
		watch: {
			currentAddress(item){
				if(item !== false && item !== undefined && item.VALUES !== undefined){
					let form = Object.assign({}, this.addressForm);
					_.forEach(form, (el, code) => {
						if(item.VALUES.hasOwnProperty(code)){
							form[code] = item.VALUES[code]['VALUE']
						}
					});
					form.PROFILE_NAME = item.NAME;
					form.PROFILE_ID = item.ID;
					
					this.addressForm = Object.assign({}, form);
				} else {
					this.addressForm = Object.assign({}, defaultAddressFields);
				}
			},

			addressSaved(val){
				if(val === true){
					
					this.$message({
						message: 'Адрес сохранен',
						type: 'success'
					});
				}
			}
		},
		created() {
			if(this.deny == 1){
				this.$store.commit('setMapContainer', {
					mapId: 'map_hello_modal',
					mainComponent: 'ya_maps_hello_modal'
				});
				
				this.$store.commit('openWindow', true);
				this.isModalPopup = true;
				this.$store.dispatch('showWindow', true);
			}
		},
		beforeUpdate() {},
		components: {
			AddressList,
			Modal,
			ErrorAddress
		},
		computed: {
			...mapGetters([
				'openWindow', 'openEditAddress', 'openWindowSearch', 'currentAddress',
				'loading', 'addressSaved', 'loadAddress',
			]),
			
		},
		mounted() {

		}
	}
</script>

<style lang="scss">
	.modal_content.wrap_address {
		top: 0;
		display: flex;
		justify-content: center;
		position: fixed;
		left: 2%;
	}
	
	.wrap_address {
		
		.b-popup-hello__wrapper {
			display: flex;
			height: 470px;
			padding: 0;
		}
		
		.b-popup-hello__right {
			text-align: left;
		}
		
		.b-popup-hello {
			position: relative;
			top: 20%;
		}
		
		.b-popup-hello__ymap,
		#map_hello {
			height: 470px !important;
		}
		
		.b-popup-hello__left {
			padding: 0;
		}
		
		.el-input__inner {
			font-size: 14px;
			-webkit-border-radius: 20px;
			border-radius: 20px;
			background-color: #fff;
			border: 1px solid #dedede;
			padding: 8px 10px;
			width: 100%;
			height: 33px;
		}
		
		.el-input-group__append {
			border: none;
			color: inherit;
			background-color: transparent;
		}
		.b-popup-hello-form__item .b-form-control {
			padding-right: 0;
		}
		
		.el-autocomplete {
			display: block;
		}
	}
	
	.address_edit {
		background: #fff;
		margin-top: 10%;
		
		.lk__add-address {
			text-align: left !important;
			
			.b-button {
				margin-left: 97px;
				width: auto;
				padding: 0 25px;
			}
		}
		
		.form__label {
			.span__label {
				width: 90px;
			}
		}
		
		.form__input_middle {
			width: 440px;
		}
		
		.form__label {
			display: flex;
			align-items: center;
			
			.el-input__inner {
				background: none;
				border: none;
				font-size: 18px;
				color: #808080 !important;
			}
			
			.el-autocomplete {
				padding: 10px;
			}
		}
	}
	
	#ya_maps_hello_modal, .maps_hello_modal, #map_hello_modal {
		height: 314px;
	}
	.maps_hello_modal {
		margin-top: 13px;
	}
	
	.close_change_address {
		top: 16px;
	}
	
	.b-popup-hello__items.b-ib-wrapper {
		padding-top: 15px;
	}
	
</style>
