<template>
	<div class="address_edit">
		<div class="accepted__content">
			<div class="lk__add-address">
				<form method="post" novalidate="" autocomplete="off" name="formIdAddress">
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
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
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
		name: "address-edit",
		props: {},
		data() {
			return {
				addressForm: Object.assign({}, defaultAddressFields),
			}
		},
		methods: {
			...mapActions('address', [
				'loadCity', 'loadStreet', 'searchAddress'
			]),
			
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
			save() {
				this.searchAddress(this.addressForm).then(res => {
					if(res > 0){
						this.$store.dispatch('address/saveAddress', this.addressForm).then(res => {
							if (res.data.DATA !== null) {
								this.addressForm = Object.assign({}, defaultAddressFields);
							}
						});
						this.$emit('validate', true);
					} else {
						this.$emit('validate', false);
						this.$store.commit('address/loading', false);
					}
				});
				
				/*this.searchAddress(this.addressForm).then(res => {
					this.$store.dispatch('address/saveAddress', this.addressForm).then(res => {
						if (res.data.DATA !== null) {
							this.addressForm = Object.assign({}, defaultAddressFields);
						}
					})
				}).catch(err => {
					this.$store.commit('address/loading', false);
					this.$emit('validate', false);
				});*/
			},
			
			_setCurrent(item){
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
			}
		},
		watch: {
			currentAddress(item){
				this._setCurrent(item);
			},
		},
		created() {
			if(this.currentAddress !== false){
				this._setCurrent(this.currentAddress);
			}
		},
		beforeUpdate() {
		},
		components: {},
		computed: {
			...mapGetters('address', [
				'currentAddress', 'loading'
			])
		},
		mounted() {
		}
	}
</script>

<style scoped lang="scss">
	.address_edit {
		margin-top: 0;
	}
	.accepted__content {
		margin: 0;
		padding: 0;
	}
</style>