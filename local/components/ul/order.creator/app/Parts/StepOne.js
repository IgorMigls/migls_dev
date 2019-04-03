/**
 * Created by dremin_s on 30.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import { connect } from "react-redux";
import { mapDispatchToProps, mapStateToProps } from "../Controller";
import cn from 'classnames';
import { Field, Form } from 'UIForm';
import Suggestion from '../Suggestion';
import MapTools from '../MapTools';

class StepOne extends React.Component {

	constructor(props) {
		super(props);

		this.selectAddress = this.selectAddress.bind(this);
		this.checkNewAddress = this.checkNewAddress.bind(this);
		this.setAddress = this.setAddress.bind(this);
		this.processAddress = this.processAddress.bind(this);
		this.nextStep = this.nextStep.bind(this);
		this.watcherForm = this.watcherForm.bind(this);
		this.saveAddressItems = this.saveAddressItems.bind(this);
	}

	getCheckAddress(value = 'new'){
		let checkedNew = false, checkedOld = false;
		if(value === 'new')
			checkedNew = true;
		else
			checkedOld = true;

		return (
			<div className="order1__radio">
				<label className="filter__label filter__label_radio">
					<input checked={checkedNew} type="radio" name="adr_check" onChange={this.checkNewAddress}
						className="filter__checkbox filter__checkbox_radio" value="new" /><i />
					<div className="check__wrapper b-ib"><span className="history__order2">
						Новый адрес
					</span></div>
				</label>
				<label className="filter__label filter__label_radio">
					<input checked={checkedOld} type="radio" name="adr_check" onChange={this.checkNewAddress}
						className="filter__checkbox filter__checkbox_radio" value="old" /><i />
					<div className="check__wrapper b-ib"><span className="history__order2">
						Выбрать
					</span></div>
				</label>
			</div>
		)
	}

	checkNewAddress(ev) {
		this.props.setAddressType(ev.target.value);
	}

	selectAddress(address = {}) {
		this.props.setAddressSelect(address.value, this.props.step1.profiles);
	}

	setAddress(data, event){
		this.props.setAddressValue({name: event.target.name, value: data.item.value});
	}

	processAddress(request, response, $node){
		this.props.searchAddress({
			name: $node.attr('name'),
			value: request.term,
			addressItems: this.props.step1.addressItems
		}).then(res => {
			if(res.data.STATUS === 1){
				let sResult = res.data.DATA;

				if(!is.empty(sResult) && sResult !== null){
					response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);
				}
			}
		});
	}

	nextStep(form){

		const {fields} = form;
		let address = 'г.'+ fields.CITY.value + ', ул.' + fields.STREET.value + ' д.' + fields.HOUSE.value;
		const Map = new MapTools({
			city: fields.CITY.value,
			street: fields.STREET.value,
			house: fields.HOUSE.value,
			RestManager: this.props.getRestAjax()
		});
		Map.initMap().then(res => {
			$(document).on('map_valid_address', (ev, data) => {
				if(data === true){
					this.saveAddressItems(form);
					this.props.setNextStep(2, form);
				}
			});
		});
	}

	watcherForm(form){
		this.props.setValidateStep1(form);
	}

	componentDidMount() {
		const step = this.props.step1;

		if (step.profiles.length === 0) {
			this.props.getProfiles();
		}
	}

	saveAddressItems(form) {
		this.props.saveAddressOrder(form.fields);
	}

	deleteAddress(profileId) {
		this.props.deleteAddress(profileId);
	}

	render() {
		const step = this.props.step1;

		let active = cn('tab_order', {'active_tab': step.active});
		const oField = step.props;


		if(is.empty(oField))
			return null;

		// console.info(step.addressItems);


		return (
			<div className={active}>
				<div className="check__title check__title_m">Введите ваш адрес</div>

			{this.getCheckAddress(step.checkTypeAddress)}

				<Form noValidate="noValidate" name="formPropProfile" autoComplete="off"
					onSubmit={this.saveAddressItems} onChange={this.watcherForm} >
					{step.checkTypeAddress !== 'new' &&
					<div className="b-ib lk__profile">
						<div className="b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-ib custom_profile">
							<Field.Select id="address_select" name="ADDRESS_SELECT" onChange={this.selectAddress} items={step.addressList} />
						</div>
					</div>
					}

					<span className="lk__form-descr"><i>*</i> - обязательны для заполнения</span>

					<label className="form__label">
						<i>*</i>
						<Suggestion placeholder={oField.CITY.NAME}
							className="form__input form__input_middle"
							name="CITY" id="CITY_FIELD"
							onSelected={this.setAddress}
							onProcess={this.processAddress}
							valid={['isRequired']} errorMsg="Заполните город" defaultValue={step.addressItems['CITY']}/>
					</label>

					<label className="form__label">
						<i>*</i>
						<Suggestion placeholder={oField.STREET.NAME}
							className="form__input form__input_middle"
							name="STREET" id="STREET_FIELD"
							onSelected={this.setAddress}
							onProcess={this.processAddress} valid={['isRequired']}
							errorMsg="Заполните улицу" defaultValue={step.addressItems['STREET']}/>

					</label>

					<div className="form__col1">
						<label className="form__label">
							<i>*</i>
							<Field.String placeholder={oField.HOUSE.NAME} className="form__input form__input_short"
								name="HOUSE" maxlength={10} valid={['isRequired']}
								errorMsg="Заполните номер дома" defaultValue={step.addressItems['HOUSE']}/>
						</label>
						<label className="form__label">
							<i>*</i>
							<Field.String placeholder={oField.APARTMENT.NAME} className="form__input form__input_short"
								name="APARTMENT" maxlength={5} valid={['isRequired']}
								errorMsg="Заполните номер квартиры" defaultValue={step.addressItems['APARTMENT']}/>
						</label>
					</div>

					<div className="form__col1">
						<label className="form__label">
							<Field.String placeholder={oField.FLOOR.NAME} className="form__input form__input_short"
								name="FLOOR" maxlength={4}
								errorMsg={'Заполните '+ oField.FLOOR.NAME} defaultValue={step.addressItems['FLOOR']}/>
						</label>
						<label className="form__label">
							<Field.String placeholder={oField.ZIP.NAME} className="form__input form__input_short"
								name="ZIP" transform="toNumber"
								errorMsg={'Заполните '+ oField.ZIP.NAME} defaultValue={step.addressItems['ZIP']}/>
						</label>
					</div>
					<label className="form__label">
						{step.isValid === true &&
						<button type="submit" style={{width: '165px'}}
							className="b-button b-button_check b-button_green b-button_big b-button_width btn_address">
							Сохранить адрес
						</button>
						}
						{step.checkTypeAddress !== 'new' && step.profileId !== null && step.profileId !== undefined &&
						<button type="button" style={{width: '115px'}}
							className="b-button b-button_check b-button_green b-button_big b-button_del b-button_width btn_address"
							onClick={this.deleteAddress.bind(this, step.profileId)}>
							Удалить
						</button>
						}
					</label>
					<label className="form__label">
						<i>*</i>
						<Field.String  placeholder="Название"
							className="form__input form__input_middle form__input_tooltip"
							name="PROFILE_NAME"  valid={['isRequired']}
							errorMsg="Заполните название профиля" defaultValue={step.addressItems['PROFILE_NAME']}/>
							<div className="lable__tooltip">
								<span className="tooltip__content animated zoomIn">
									Название адреса, под которым он будет сохранен. Например, "мой дом" или "моя работа".
								</span>
							</div>
					</label>

					<label className="form__label">
						<Field.Mask mask="+7(111)111-11-11" placeholder="Номер тел."
							className="form__input form__input_middle form__input_tooltip"
							name="PHONE" errorMsg="Заполните правильно номер тел."
							clear={step.checkTypeAddress === 'new'} onBlur={this.props.setAddressValue}/>
					</label>
					{step.isValid === true &&
					<button type="button" className="b-button b-button_green b-button_check b-button_big b-button_width"
						onClick={this.nextStep.bind(this, step.form)}>
						Дальше
					</button>
					}
				</Form>
			</div>
		);
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(StepOne);