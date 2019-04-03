/**
 * Created by dremin_s on 16.02.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import {connect} from 'react-redux';
import Control from '../Controller';
import option from '../const';
import cn from 'classnames';
import Loader from 'preloader/Preloader';
import {TextField, MaskField} from 'form/Fields'

class StepOne extends React.Component {

	constructor(props) {
		super(props);

		this.state = {
			inst_profile_name: ''
		};

		this.setAddressType = this.setAddressType.bind(this);
		this.saveAddress = this.saveAddress.bind(this);
	}

	setAddressType(ev){
		this.props.setAddressType(ev.target.value)
	}

	componentWillReceiveProps(nextProps){
		const {Order} = this.props;

		if(!is.empty(Order.profiles)){
			setTimeout(() => {
				let $selectAddress = $('#SelectAddress');
				if ($selectAddress.length > 0) {
					$selectAddress.selectize();
					$selectAddress.on('change', (ev) => {
						this.props.changeAddress(ev.target.value);
					})
				}
			}, 150);
		}
	}

	getOptionAddress(items = []){
		let options = [];
		if(!is.empty(items)){
			options = items.map((el, k) => {
				return <option value={k} key={'address_' + k}>{el.NAME}</option>
			});
		}

		return options;
	}

	getAddressField(fieldName, className = '', mask = '', value = ''){
		if(is.propertyDefined(this.props.Order.currentAddress['VALUES'], fieldName)){
			let field = this.props.Order.currentAddress['VALUES'][fieldName];
			if(field.VALUE === null || field.VALUE === undefined){
				field.VALUE = '';
			}
			if(value.length > 0){
				field.VALUE = value;
			}

			let classInput = cn('form__input', className);

			return (
				<label className="form__label">
					{mask.length > 0 ?
						<MaskField value={field.VALUE} className={classInput}
							name={field.CODE} placeholder={field.NAME}
							onChange={this.props.changeValueField} mask={mask}/>
					:
						<TextField value={field.VALUE} className={classInput}
							name={field.CODE} placeholder={field.NAME}
							onChange={this.props.changeValueField}/>
					}

				</label>
			)
		}
	}

	saveAddress() {
		let postData = this.props.Order.currentAddress;
		postData.PHONE = this.props.Order.phone;
		postData.PROFILE_NAME = this.props.Order.profileName;

		this.props.saveAddress(postData);
	}


	componentDidMount () {
		const {Order} = this.props;
		this.props.getDataProps();
		$.get(window.location.pathname + 'inst_PROFILE_NAME.php').then(res => {
			this.setState({inst_profile_name: res});
		});
	}

	render() {
		const {Order} = this.props;

		let checkedAddressNew = Order.addressType === option.ADDRESS_NEW;
		let checkedAddressOld = Order.addressType === option.ADDRESS_OLD;

		return (
			<div className="order_step_1">
				<Loader {...this.props.Loader} />
				<div className="check__title check__title_m">Введите ваш адрес</div>
				<div className="order1__radio">
					<label className="filter__label filter__label_radio">
						<input type="radio" value={option.ADDRESS_NEW} onChange={this.setAddressType} checked={checkedAddressNew}
							className="filter__checkbox filter__checkbox_radio" name="addressType" /><i />
							<div className="check__wrapper b-ib"><span className="history__order2">Новый адрес</span></div>
					</label>
					<label className="filter__label filter__label_radio">
						<input type="radio" value={option.ADDRESS_OLD} onChange={this.setAddressType} checked={checkedAddressOld}
							className="filter__checkbox filter__checkbox_radio" name="addressType" /><i />
							<div className="check__wrapper b-ib"><span className="history__order2">Выбрать</span></div>
					</label>
				</div>
				<div className="order1__form">
					<form method="post" noValidate name="formPropProfile" autoComplete="off">
						<div className="b-ib lk__profile">
							{checkedAddressOld !== false &&
								<div className="b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-ib custom_profile">
									<div className="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
										<select className="b-custom-select js-custom-select2" id="SelectAddress">
											{this.getOptionAddress(Order.profiles)}
										</select>
									</div>
								</div>
							}
							<span className="lk__form-descr">Все поля обязательны для заполнения</span>

							{this.getAddressField('CITY', 'form__input_middle')}
							{this.getAddressField('STREET', 'form__input_middle')}
							<div className="form__col1" style={{width: '51%'}}>
								{this.getAddressField('HOUSE', 'form__input_short')}
								{this.getAddressField('APARTMENT', 'form__input_short')}
							</div>
							<div className="form__col1">
								{this.getAddressField('FLOOR', 'form__input_short', '111')}
								{this.getAddressField('ZIP', 'form__input_short', '111111')}
							</div>

							<label className="form__label">
								<TextField placeholder="Название" name="PROFILE_NAME"
									className="form__input form__input_middle form__input_tooltip"
									onChange={this.props.changeValueField} value={Order.profileName}/>
									<div className="lable__tooltip">
										<span className="tooltip__content animated zoomIn">
											{this.state.inst_profile_name}
										</span>
									</div>
							</label>
							<label className="form__label">
								<MaskField
									mask="+7(111)111-11-11" placeholder="+7(___)___-__-__"
									className="form__input form__input_middle form__input_tooltip"
									name="PHONE" onChange={this.props.changePhone} value={Order.phone} />
							</label>
							<label className="form__label">
								{Order.validAddress === true &&
									<button type="button" onClick={this.saveAddress}
										className="b-button b-button_check b-button_green b-button_big b-button_width btn_address">
										Сохранить адрес
									</button>
								}
								{checkedAddressOld != false && Order.currentAddress.ID > 0 &&
									<button type="button"
										className="b-button b-button_check b-button_green b-button_big b-button_del b-button_width btn_address">
										Удалить адрес
									</button>
								}
							</label>
							{(Order.validAddress === true || Order.currentAddress.ID > 0) &&
							<button type="button"
								className="b-button b-button_green b-button_check b-button_big b-button_width">
								Дальше
							</button>
							}
						</div>
					</form>
				</div>
			</div>
		)
	}
}

export default connect(Control.mapStateToProps, Control.mapDispatchToProps)(StepOne);