/**
 * Created by dremin_s on 14.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import Ajax from 'preloader/RestService';
import MapTools from './MapTools';


const Rest = new Ajax({
	baseURL: '/rest/order'
});

const mapStateToProps = (state) => {
	return state;
};

const mapDispatchToProps = (dispatch) => {
	return {

		getRestAjax(){
			return Rest;
		},

		startLoad(text = false){
			dispatch({type: 'SWITCH_LOADER', show: true, text: text});
		},

		stopLoad(){
			dispatch({type: 'SWITCH_LOADER', show: false, text: false});
		},

		getProfiles(){
			Rest.get('/getProfiles').then(res => {
				if(res.data.STATUS === 1){
					let address = [{id:null, label: 'Выбрать адрес'}];
					$.each(res.data.DATA.profiles, (code, el) => {
						address.push({id: el.ID, label: el.NAME});
					});
					dispatch({type:'GET_ADDRESS_LIST', items: res.data.DATA.profiles, address, props: res.data.DATA.props});
				}
			});
		},

		setAddressType(value){
			dispatch({type: 'SET_ADDRESS_TYPE', value, clearFields: value === 'new'});
		},

		searchAddress(data = {}){
			return Rest.post('/searchAddress', data);
		},

		setAddressValue(data = {}){
			dispatch({type: 'SET_ADDR_VALUE', name: data.name, value: data.value});
		},

		setValidateStep1 (form = {}){
			dispatch({type: 'SET_VALID_FROM', isValid: form.valid, form});
		},

		setAddressSelect(address, profiles){
			let currentAddress = {};
			if(profiles instanceof Array){
				profiles.forEach((el) => {
					if(el.ID === address.id){
						currentAddress = el;
					}
				});

				let addressItems = {};
				if(is.empty(currentAddress) || currentAddress === undefined){
					this.setAddressType('new');
					dispatch({type: 'SET_PROFILE_ID', id: null});
					return;
				}

				$.each(currentAddress.VALUES, (code, field) => {
					if(field.VALUE === null || field.VALUE === undefined)
						field.VALUE = '';
					addressItems[code] = field.VALUE;
				});
				addressItems['PROFILE_NAME'] = currentAddress.NAME;
				addressItems['PROFILE_ID'] = currentAddress.ID;

				dispatch({type: 'SET_PROFILE_VALUES', addressItems});
				dispatch({type: 'SET_PROFILE_ID', id: currentAddress.ID});

				const Map = new MapTools({
					city: addressItems.CITY,
					street: addressItems.STREET,
					house: addressItems.HOUSE,
					RestManager: Rest
				});
				Map.initMap();
			}
		},

		deleteAddress(id = null){
			Rest.post('/delAddress', {ID: id}).then(res => {
				if(res.data.STATUS === 1){
					this.getProfiles();
				}
			});
		},

		setNextStep(step, data){

			let oldStep = step - 1;
			if(oldStep <= 0)
				oldStep = 1;

			switch (oldStep){
				case 1:
					dispatch({type: 'SET_DATA_STEP1', step, data});
					dispatch({type: 'ACTIVE_STEP2', active: true});
					dispatch({type: 'ACTIVE_STEP3', active: false});
					break;
				case 2:
					dispatch({type: 'ACTIVE_STEP2', active: false});
					dispatch({type: 'SET_NEXT_STEP', activeStep: step});
					dispatch({type: 'ACTIVE_STEP3', active: true});
					break;
			}

			dispatch({type: 'SET_NEXT_STEP', step});
		},

		saveAddressOrder(data = {}){
			let post = {};

			$.each(data, (code, field) => {
				post[code] = field.value;
			});

			Rest.post('/saveAddress', post).then(res => {
				if(res.data.STATUS === 1){
					if(!data.hasOwnProperty('ADDRESS_SELECT')){
						swal({
							title: '',
							text: 'Адрес сохранен',
							imageUrl: "/local/dist/images/successicon1.png",
							imageSize: "112x112",
							customClass: 'error_window_custom success',
							confirmButtonText: 'Закрыть',
						});
					}
					dispatch({type: 'SET_PROFILE_ID', id: res.data.DATA});
					this.getProfiles();
				}
			});
		},

		prevStep(step){

			switch (step){
				case 1:
					dispatch({type: 'SET_NEXT_STEP', step});
					dispatch({type: 'SET_DATA_STEP1', active: true});
					dispatch({type: 'ACTIVE_STEP2', active: false});
					dispatch({type: 'ACTIVE_STEP3', active: false});
					break;
				case 2:
					dispatch({type: 'SET_NEXT_STEP', step});
					dispatch({type: 'SET_DATA_STEP1', active: false});
					dispatch({type: 'ACTIVE_STEP2', active: true});
					dispatch({type: 'ACTIVE_STEP3', active: false});
					break;
			}
		},

		basketLoad(){
			Rest.get('/basketLoad').then(res => {
				if(res.data.STATUS === 1){
					dispatch({type: 'ORDER_DATA', orderData: res.data.DATA});
				}
			})
		},

		setCurrentShop(code = ''){
			dispatch({type: 'SET_CURRENT_SHOP', code});
		},

		setTimeShop(arTime ={}){
			dispatch({type: 'SET_TIME', arTime});
		},

		setValidateStep(val){
			dispatch({type: 'SET_VALID_STEP_2', val});
		},

		testDataInsert(){
			$.get('/local/lg/set.json', (res) => {
				dispatch({type: 'SET_NEXT_STEP', activeStep: 2});
				dispatch({type: 'SET_TEST_STEP_1', data: res.step1});
				dispatch({type: 'SET_TEST_STEP_2', data: res.step2});
			}, 'json');
		},

		saveOrder(data = {}){

			let post = {
				profileId: data.step1.profileId,
				DELIVERY: {},
				PROPERTIES: {}
			};
			$.each(data.step2.SHOPS, (code, arShop) => {
				post.DELIVERY[code] = Object.assign(arShop.DELIVERY, {NAME: arShop.NAME, CODE: arShop.SHOP_ID});
			});

			post.PROPERTIES = data.step1.form.values;

			post.comment = data.mainComment;
			Rest.post('/saveOrder', post).then(res => {
				if(res.data.STATUS === 1){
					dispatch({type: 'ORDER_INFO', order: res.data.DATA});
				}
			});
		}
	}
};

export {mapStateToProps, mapDispatchToProps};