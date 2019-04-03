<template>
	<div class="profile_address">
		<div class="profile_address-title">Ваши адреса:</div>
		<div class="profile_address-body lk__address-wrapper" v-loading="loaderItems">
			<div class="lk__address" v-for="(item, index) in addressList" :key="'address_' + index">
				<button class="b-button b-button__edit" type="button" @click="openAddressWindow(item)"></button>
				<button class="b-button b-button__remove" type="button" @click="deleteAddress(item)"></button>
				<div class="lk__input1">{{item.NAME}}</div>
				<div class="lk__input2">{{item.ADDRESS_FORMAT}}</div>
			</div>
		</div>
		
		<div class="profile_address-title">Добавить адрес:</div>
		
		<address-edit @validate="validateAddress" key="static_address"></address-edit>
		
		<modal-address :show="openEditAddress" v-show="openEditAddress" classWrap="address_edit_wrap">
			<transition-group tag="div" name="custom-classes-transition" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
				<div class="b-popup b-popup-card b-popu-card_add-card openEditAddress" v-if="showAddressEdit" key="openEditAddress">
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
						
						<address-edit @validate="validateAddress" key="popup_address"></address-edit>
					</div>
				</div>
				
				<error-address v-if="noValidAddress" key="error-address" :showClose="true" @close-error="closeModal">
					<div slot="description">
					<h4 style="text-align: center">В данный момент мы не доставляем по Вашему адресу :(</h4>
						<p>Но Вы можете ввести свой e-mail, чтобы мы знали, где нас больше всего ждут, а также мы отправим Вам письмо, как только доставка станет возможна!</p>
					</div>
					<form @submit.prevent="saveAddress" novalidate="" autocomplete="off" method="post" class="" id="email_no_address" name="EMAILS">
						<div>
							<div class="field_form_wrap">
								<p style="color: red" v-if="errorEmail">{{errorEmail}}</p>
								<p style="color: #4bce5b;" v-else-if="successEmail">
									Спасибо за интерес к нашему сервису! Мы обязательно уведомим Вас, когда доставка по Вашему адресу станет возможна!
								</p>
								<input type="text" v-model="emailForAddress" placeholder="example@migls.ru" name="EMAIL_NO_ADDRESS" class="form__input form__input_accepted" />
							</div>
							<div class="field_submit_wrap">
								<button type="submit" class="b-button b-button_green b-button_check">Отправить</button>
							</div>
						</div>
					</form>
				</error-address>
			</transition-group>
		</modal-address>
	</div>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	import AddressEdit from './AddressEdit';
	import ModalAddress from 'Utilities/Modal.vue';
	import ErrorAddress from 'Utilities/maps/ErrorAddress.vue';
	
	export default {
		name: "personal-address",
		props: {},
		data() {
			return {
				openEditAddress: false,
				noValidAddress: false,
				emailForAddress: '',
				errorEmail: false,
				successEmail: false,
				showAddressEdit: false
			}
		},
		methods: {
			...mapActions('address', [
				'loadAddressList', 'saveAddressEmail', 'deleteAddress'
			]),
			
			closeModal() {
				this.openEditAddress = false;
				this.showAddressEdit = false;
				this.$store.commit('address/updateCurrentAddress', false);
				this.noValidAddress = false;
				this.successEmail = false;
			},

			openAddressWindow(item = false) {
				this.showAddressEdit = true;
				this.$store.commit('address/updateCurrentAddress', item);
				this.openEditAddress = true;
				this.noValidAddress = false;
			},

			validateAddress(val){
				this.noValidAddress = !val;
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
		},
		watch: {
			noValidAddress(val){
				if(val === true){
					this.openEditAddress = true;
					this.showAddressEdit = false;
				} else {
					this.openEditAddress = false;
				}
			}
		},
		created() {
			this.loadAddressList();
		},
		beforeUpdate() {
		},
		components: { AddressEdit, ModalAddress, ErrorAddress },
		computed: {
			...mapGetters('address', [
				'PersonalMap', 'addressList', 'loading', 'loaderItems'
			])
		},
		mounted() {
		}
	}
</script>

<style scoped lang="scss">
	.profile_address {
		padding-top: 42px;
		padding-bottom: 30px;
		
		&-title {
			font-size: 36px;
			font-family: 'HelveticaNeueCyr-Thin';
			padding-bottom: 30px;
		}
		
		&-body {
			margin-bottom: 35px;
			
			.lk__input1 {
				width: 50%;
			}
		}
		
		.address_edit {
			margin-top: 0;
		}
		
		.openEditAddress {
			margin-top: 5%;
			height: 710px;
		}
		.accepted__content {
			padding: 0 50px 30px 50px;
		}
		
		
	}
	
	.openEditAddress {
		position: fixed;
		left: calc(50% - 20%);
	}
	
</style>
