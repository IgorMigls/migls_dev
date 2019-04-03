<template>
	<div class="step-1" v-loading="preloader.show" :element-loading-text="preloader.text">
		<div class="check__title">Введите ваш адрес</div>
		<div class="order1__radio" v-if="addressList">
			<label class="filter__label filter__label_radio">
				<input type="radio" v-model="statusAddress" class="filter__checkbox filter__checkbox_radio" value="new" /><i></i>
				<div class="check__wrapper b-ib">
					<span class="history__order2">Новый адрес</span>
				</div>
			</label>
			<label class="filter__label filter__label_radio">
				<input type="radio" v-model="statusAddress" class="filter__checkbox filter__checkbox_radio" value="old" /><i></i>
				<div class="check__wrapper b-ib">
					<span class="history__order2">Выбрать</span>
				</div>
			</label>
			
			<div class="step-1__row" style="margin-top: 15px" v-if="statusAddress == 'old'">
				<el-select v-model="selectedProfile" placeholder="Выбрать адрес" clearable class="grey_select"
						@clear="clearAddress" @change="selectAddress">
					<el-option v-for="item in addressList" :key="item.ID"
							:label="item.NAME" :value="item.ID"
							:disabled="item.inArea === 0 || item.inArea === undefined">
						<div v-if="item.inArea == 1" class="color_select_green">{{item.NAME}}&nbsp;</div>
						<el-tooltip class="item" effect="dark" v-else=""
								content="Адрес не доступен в текущей зоне доставки" placement="left">
							<span>{{item.NAME}}&nbsp;<i class="el-icon-info"></i></span>
						</el-tooltip>
					</el-option>
				</el-select>
			</div>
			
		</div>
		
		<div class="step-1__info_block"><i class="star">*</i><span> &ndash; обязательны для заполнения</span></div>
		
		<div class="step-1__row">
			<label class="form__label">
				<i class="star">*</i>
					<el-autocomplete
							:class="['field_form_wrap', {'error_input': errors.has('CITY')}]"
							v-model="form.CITY" valueKey="value" placeholder="Город"
							:fetch-suggestions="loadCity" :trigger-on-focus="false"
							v-validate="'required'" data-vv-value-path="innerValue" data-vv-name="CITY"
					></el-autocomplete>
					<error :show="errors.has('CITY')">Укажите город</error>
			</label>
		</div>
		
		<div class="step-1__row">
			
			<label class="form__label">
				<i class="star">*</i>
				<el-autocomplete
						:disabled="form.CITY == ''"
						:class="['field_form_wrap', {'error_input': errors.has('STREET')}]"
						v-model="form.STREET" valueKey="value" placeholder="Улица"
						:fetch-suggestions="loadStreet" :trigger-on-focus="false"
						v-validate="'required'" data-vv-value-path="innerValue" data-vv-name="STREET"
				></el-autocomplete>
				<error :show="errors.has('STREET')">Укажите улицу</error>
			</label>
		
		</div>
		
		<div class="step-1__row cells">
			<div class="cell">
				<label class="form__label">
					<i class="star">*</i>
					<span :class="['field_form_wrap', {'error_input': errors.has('HOUSE')}]">
						<input type="text" name="HOUSE" v-model="form.HOUSE" class="form__input"
								placeholder="Дом" autocomplete="off" v-validate="'required'"  />
						<error :show="errors.has('HOUSE')">Укажите номер дома</error>
					</span>
				</label>
			</div>
			<div class="cell">
				<label class="form__label">
					<span class="field_form_wrap">
						<input type="text" name="FLOOR" v-model="form.FLOOR" class="form__input" placeholder="Этаж" autocomplete="off">
					</span>
				</label>
			</div>
		</div>
		
		<div class="step-1__row cells">
			<div class="cell">
				<label class="form__label">
					<i class="star">*</i>
					<span :class="['field_form_wrap', {'error_input': errors.has('APARTMENT')}]">
						<input type="text" name="APARTMENT" v-model="form.APARTMENT" class="form__input"
								placeholder="Квартира" autocomplete="off" v-validate="'required'" />
						<error :show="errors.has('APARTMENT')">Укажите квартиру</error>
					</span>
				</label>
			</div>
			<div class="cell">
				<label class="form__label">
					<span class="field_form_wrap">
						<input type="text" name="ZIP" v-model="form.ZIP" class="form__input" placeholder="Подъезд" autocomplete="off" />
					</span>
				</label>
			</div>
		</div>
		
		<div class="step-1__row">
			<label class="form__label">
				<i class="star">*</i>
				<span :class="['field_form_wrap', {'error_input': errors.has('NAME')}]">
					<input type="text" name="NAME" @input="compileNameProfile" class="form__input"
							placeholder="Название" autocomplete="off" v-validate="'required'" :value="compileAddress" />
					<error :show="errors.has('NAME')">Придумайте название</error>
					<span class="tip_hover"><slot name="name_tip"></slot></span>
				</span>
			</label>
		</div>
		
		<div class="step-1__row">
			<label class="form__label">
				<i class="star">*</i>
				<span class="field_form_wrap" :class="['field_form_wrap', {'error_input': errors.has('PHONE')}]">
					<masked-input v-model="form.PHONE" name="PHONE" mask="\+\7 (111) 111-1111" v-validate="'required'"
							placeholder-char="_" placeholder="Номер тел." type="tel" class="form__input"/>
					<error :show="errors.has('PHONE')">Введите номер телефона</error>
				</span>
			</label>
		</div>
		
		<div slot="order_actions">
			<button type="button" @click="nextStep" class="b-button b-button_green b-button_check b-button_big b-button_width">Дальше</button>
		</div>
		
		<modal :show="showModal" class-wrap="order_modals">
			<div class="order_modal_inner">
				<div class="error_modal">
					<div class="error_modal__head">
						<img src="/local/dist/images/x_win.png" />
					</div>
				</div>
				<div class="order_modal_inner__content">
					<h2></h2>
					<p>Адрес не попадает в зону доставки.</p>
					<p>Перейдите на <a href="/">главную страницу</a> и поменяйте зону доставки или введите другой адрес.</p>
				</div>
				<div class="order_modal_inner__buttons">
					<button class="b-button b-button_green b-button_check" @click="showModal = false">Ввести другой адрес</button>
				</div>
			</div>
		</modal>
	</div>
</template>
<script>
	
	import { mapGetters, mapActions } from 'vuex';
	import Error from 'Utilities/validator/Error.vue';
	import {Rest} from '../store/actions';
	import MaskedInput from 'vue-masked-input';
	import Modal from 'Utilities/Modal.vue';
	
	const ADDRESS_NEW = 'new';
	const ADDRESS_CHOICE = 'choice';
	
	const initFields = {
		PROFILE_ID: null,
		CITY: '',
		STREET: '',
		HOUSE: '',
		FLOOR: '',
		APARTMENT: '',
		ZIP: '',
		NAME: '',
		PHONE: '',
	};
	
	export default {
		data() {
			return {
				statusAddress: ADDRESS_NEW,
				form: Object.assign({}, initFields),
				selectedProfile: '',
				showModal: false
			}
		},
		methods: {
//			...mapActions([]),
			async nextStep(){
				let validateAll = await this.$validator.validateAll();
				if(validateAll){
					try{
						let validAddress = await this.validateAddress();

						if(validAddress === 0){
							this.showModal = true;
						} else {
							if(_.size(this.form.NAME) === 0){
								this.form.NAME = this.compileAddress;
							}
							this.$store.commit('addOrderData', {address: this.form});
							this.$store.dispatch('saveProfile').then(res => {
								this.$notify({
									message: 'Адрес сохранен',
									type: 'success'
								});
								this.$store.commit('setStep', 2);
							})
						}
					} catch (Error){
						this.showModal = true;
					}
					
				}
			},
			clearAddress(){
				this.selectedProfile = '';
				this.clearFields();
			},
			selectAddress(id){
				let profile = _.find(this.addressList, {'ID': id});
				if(profile !== undefined){
					_.forEach(profile.VALUES, (el, code) => {
						this.form[code] = el.VALUE;
					});
					this.form.PROFILE_ID = profile.ID;
					this.form.NAME = profile.NAME;
				}
			},
			
			clearFields(){
				this.form = Object.assign({}, initFields);
				setTimeout(() => {
					this.errors.clear();
				});
				this.selectedProfile = '';
			},

			loadCity(q, cb){
				if(_.size(q) > 2){
					Rest.searchCity({q}).then(res => {
						if(res.data.DATA !== null){
							cb(res.data.DATA);
						}
					})
				}
			},

			loadStreet(q, cb){
				if(_.size(q) > 2){
					Rest.searchStreet({q, city: this.form.CITY}).then(res => {
						if(res.data.DATA !== null){
							cb(res.data.DATA);
						}
					})
				}
			},

			compileNameProfile(ev){
				this.form.NAME = ev.target.value;
				this.$store.commit('addOrderData', {address: this.form});
			},
			
			validateAddress() {
				let addressStr = `${this.form.CITY} ${this.form.STREET} д.${this.form.HOUSE}`;
				return this.$store.state.Map.search(addressStr)
			}
		},
		watch: {
			statusAddress(val){
				if(val === ADDRESS_NEW && _.isEmpty(this.orderAddress)){
					this.clearFields();
				}
			}
		},
		created() {
			if(!_.isEmpty(this.orderAddress)){
				this.form = this.orderAddress;
			}
		},
		components: {
			Error,
			MaskedInput,
			Modal
		},
		computed: {
			...mapGetters(['map', 'addressList', 'preloader', 'orderAddress']),

			compileAddress(){
				if(_.size(this.form.CITY) === 0 )
					return 'Название';
				
				if(_.size(this.form.NAME) > 0)
					return this.form.NAME;
				
				let address = this.form.CITY;
				if(_.size(this.form.STREET) > 0)
					address += ' ул.'+ this.form.STREET;
				if(_.size(this.form.HOUSE) > 0)
					address += ' д.'+ this.form.HOUSE;
				
				return address;
			}
		},
	}
</script>
<style lang="scss">
	$colorField: #808080;
	$bgInput: #f0efec;
	
	.error_input input {
		border:1px solid #d1404a !important;
	}
	
	.step-1 {
		padding-left: 15px;
		
		&__info_block {
			text-align: center;
			font-size: 0.8em;
			margin: 15px auto;
		}
		
		.star {
			color: red;
			font-weight: bold;
			font-style: normal;
			font-size: 1.4em;
		}
		
		.order1__radio label {
			margin-bottom: 5px;
		}
		
		&__row {
			.star {
				position: absolute;
				top: 20px;
				left: -7px;
				font-size: 1em;
			}
			
			.el-autocomplete{
				width: 100%;
			}
			
			.el-input__inner {
				width: 100%;
				background: $bgInput;
				padding: 15px 20px;
				-webkit-border-radius: 30px;
				border-radius: 30px;
				font-size: 18px;
				color: $colorField;
				height: 50px;
				border-color: $bgInput;
				
				&::placeholder{
					color: $colorField
				}
			}
		}
		
		&__row.cells {
			display: flex;
			justify-content: space-between;
			
			.cell {
				width: 48%;
			}
		}
		
		
	}
	
	.grey_select {
		width: 100%;
		background: $bgInput;
		color: $colorField;
		border-radius: 30px;
		
		&:hover .el-input__inner{
			border-radius: 30px;
			border-color: #e5e4e1;
		}
		
		.el-input__inner {
			border-color: transparent;
			background: transparent;
			height: 50px;
			color: $colorField;
			font-size: 18px;
		}
		
		.el-input__suffix-inner > .el-input__icon,
		.el-input__suffix-inner > .el-icon-circle-close {
			font-size: 18px !important;
			margin-right: 4px;
		}
		
		.el-input__inner[plaseholder]{
			color: $colorField;
		}
		.el-input.is-focus .el-input__inner {
			border-color: #e5e4e1;
			border-radius: 30px;
		}
	}
	.el-select-dropdown__item.selected .color_select_green {
		color: #71c54a
	}
	
	.tip_hover {
		background-image: url(/local/dist/images/sprite2.png);
		background-position: -182px -529px;
		width: 16px;
		height: 16px;
		display: inline-block;
		position: absolute;
		top: 17px;
		right: 10px;
		cursor: pointer;
		
		.tip_field {
			display: none;
			position: absolute;
			width: 300px;
			right: -25px;
			top: 33px;
			line-height: 20px;
			box-shadow: 0 0 8px rgba(0, 0, 0, 0.18);
			font-size: 13px;
			z-index: 1;
			background: #fff;
			padding: 20px 30px;
		}
		
		&:hover .tip_field {
			display: block;
			
			&:before {
				content: '';
				border-bottom: 15px solid #fff;
				border-left: 15px solid transparent;
				border-right: 15px solid transparent;
				top: -15px;
				position: absolute;
				right: 23px;
			}
		}
	}
	
	.order_modals .modal_content {
		justify-content: center;
		align-items: center;
		position: fixed;
		
		.order_modal_inner {
			width: 430px;
			min-height: 510px;
			background: #fff;
			
			.error_modal {
				
				&__head {
					height: 245px;
					background: url(/local/dist/images/r_bottom_border.png) left bottom #ff0000 repeat-x;
					display: flex;
					justify-content: center;
					align-items: center;
				}
			}
			
			&__content {
				min-height: 150px;
				padding: 25px;
				margin: 0;
				text-align: left;
				font-size: 95%;
				line-height: 20px;
				float: none;
				font-weight: 300;
				color: $colorField;
			}
			
			&__buttons {
				display: flex;
				justify-content: center;
				padding: 25px;
			}
			
			h2 {
				font-size: 36px;
				font-family: 'HelveticaNeueCyr-Thin', "Open Sans", Arial, "Helvetica Neue", Helvetica, sans-serif;
				margin: 10px 0 20px 0;
				padding: 10px 0 17px 0;
				border-bottom: 1px solid #e5e5e5;
				text-align: left;
			}
		}
		
	}
	
</style>