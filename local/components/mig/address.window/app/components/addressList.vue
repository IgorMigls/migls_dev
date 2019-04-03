<template>
	<div v-if="addressList !== null" class="user_address_wrap">
		<div class="b-popup-hello-form__note" style="font-size: 18px">Ваши адреса</div>
		<scroll-bar class="user_address_list" :settings="scrollSettings">
			
			<div class="user_address_list-item" v-for="item in addressList" :key="item.ID">
				<div class="b-button">
					<div class="edit__icon address_item_popup" @click="editAddress(item)"></div>
					<div class="arrow_left_address"></div>
					<div class="address_item_txt" @click="checkAddress(item)">{{item.ADDRESS_FORMAT}}</div>
				</div>
			</div>
		
		</scroll-bar>
		<div class="b-button b-button_green" @click="addAddress">Добавить новый адрес</div>
	
	</div>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	import ScrollBar from 'ScrollBar';

	export default {
		name: "address-list",
		props: {},
		data() {
			return {
				scrollSettings: {},
			}
		},
		methods: {
			...mapActions([
				'fetchAddressList', 'searchAddressInArea'
			]),

			checkAddress(val) {
				const values = val.VALUES;
				let queryCheck = 'г. ' + values.CITY.VALUE;
				queryCheck += ' ' + values.STREET.VALUE;
				queryCheck += ' ' + values.HOUSE.VALUE;

				this.searchAddressInArea(queryCheck).then(result => {
					if (result !== false && result.data.DATA != null) {
						let backUrl = result.data.DATA.BACK_URL || '/';
						window.location.assign(backUrl);
					}
				}).catch(err => {
					swal("Данный адрес не попадает ни в одну из зон доставки", "Выберите другой адрес", "error")
				});
			},

			editAddress(item) {
				this.$store.commit('currentAddress', item);
				this.$store.commit('openWindowSearch', false);
				this.$store.commit('openEditAddress', true);
			},

			addAddress() {
				this.editAddress(false);
			}
		},
		watch: {},
		created() {
			this.fetchAddressList();

		},
		beforeUpdate() {
		},
		components: {
			ScrollBar,
		},
		computed: {
			...mapGetters([
				'addressList'
			])
		},
		mounted() {
		}
	}
</script>

<style lang="scss">
	.user_address_list {
		height: 200px;
		width: 400px;
		box-shadow: inset 0 -3px 3px rgba(226, 223, 192, 0.5);
		
		&-item {
			margin: 0 0 20px 0;
			width: auto;
			text-decoration: none;
			padding-left: 5px;
		}
		
		.address_item_txt {
			line-height: 19px;
			font-size: 13px;
		}
		.b-button {
			width: 100%;
			display: flex;
		}
		
		.arrow_left_address {
			background: url(/local/dist/images/left_arr.png) 0 2px no-repeat;
			width: 19px;
			height: 25px;
			margin-right: 5px;
			cursor: pointer;
		}
		
		.edit__icon {
			background: url(/local/dist/images/sprite3.png) -595px -618px no-repeat;
			width: 19px;
			height: 19px;
			margin-right: 3px;
		}
	}
	
	.user_address_list__title {
		margin: 10px 0;
		font-size: 18px;
	}
	
	.user_address_wrap {
		.b-button_green {
			margin-top: 15px;
		}
	}

	.b-popup-hello-form__note {
		margin-bottom: 10px;
	}
	
	.wrap_address {
		.b-popup-hello-form__item {
			margin-top: 10px;
			margin-bottom: 15px;
		}
	}
	
</style>