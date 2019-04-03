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

class TimeTabs extends React.Component {
	constructor(props) {
		super(props);

		this.setTime = this.setTime.bind(this);
	}

	static defaultProps = {
		times: []
	};

	componentDidMount() {
		// $('.set_times').removeClass('choose');
		let $tabs = $('.jsTab');
		$tabs.on('click', function () {
			let index = $(this).attr('rel');
			$('.jsTab').removeClass('active');
			$('.col__700__cont .jsCont').hide(0);
			$('.col__700__cont .jsCont.content_' + index).show(0);
			$(this).addClass('active');
		});
		$tabs.eq(0).click();
	}

	setTime(data){

		if(data.item.DISABLED === true)
			return;

		let arTime = {
			tab: data.index,
			item: data.item
		};
		this.props.chooseTime(arTime);
		$('.set_times').removeClass('active_time');
		$('.jsCont.content_' + data.index + ' .set_times.time_item_'+ data.row).addClass('active_time');
	}

	render() {

		const {times} = this.props;

		if (is.empty(times) || times === undefined) {
			return null;
		}

		return (
			<div className="col__700__bottom">
				<div className="col__700__tabs">
					{times.map((el, iTab) => {
						return (
							<div className="jsTab" rel={iTab} key={'tab_link_' + iTab}>
								<span>{el.NAME}</span>
							</div>
						)
					})}
				</div>

				<div className="col__700__cont">
					{times.map((el, iTab) => {

						return (
							<div className={'jsCont content_' + iTab}>
								<table className="day__table">
									{/*className="not-ev"*/}

									{el.ITEMS.map((item, k) => {
										let classNotEv = cn('set_times', 'time_item_'+ k, {'not-ev': item.DISABLED === true});

										return (
											<tr className={classNotEv} onClick={this.setTime.bind(this, {index: iTab, item, row: k})}>
												<td>{item.PROPERTY_TIME_FROM_VALUE} - {item.PROPERTY_TIME_TO_VALUE}</td>
												<td>{item.PROPERTY_PRICE_VALUE == 0 ? item.PRICE_FORMAT : item.PRICE_FORMAT + ' ₽'}</td>
												<td>
													<span className="green-t">{item.DISABLED === true ? 'недоступно' : 'выбрать'}</span>
												</td>
											</tr>
										)
									})}
								</table>
							</div>
						)
					})}
				</div>
			</div>
		);
	}
}

class TimeShop extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			styleTime: {display: 'none'}
		};

		this.setTime = this.setTime.bind(this);
	}

	static defaultProps = {
		currentShop: {
			data: {},
			times: [],
		},
		show: false
	};

	setTime(data){
		this.setState({styleTime: {display: 'none'}});
		this.props.chooseTime(data);
	}

	componentWillReceiveProps(nextProps){
		if(nextProps.show === true && nextProps.show !== this.props.show){
			this.setState({styleTime: {display: 'block'}});
		} else {
			this.setState({styleTime: {display: 'none'}});
		}
	}

	render() {

		const {data, times} = this.props.currentShop;

		if (is.empty(data) || data === undefined) {
			return null;
		}

		return (
			<div className="col__700 animated fadeInLeft" style={this.state.styleTime}>
				<div className="col__700__top">
					<div className="order2__form">
						<div className="order__time-wrapper">
							<div className="interval__img b-ib">
								<a href="javascript:"><img src={data.PICTURE.src} /></a>
							</div>
							<div className="descr__img b-ib"><span>{data.NAME}</span><span /></div>
						</div>
					</div>
				</div>
				{times.length > 0 && <TimeTabs times={times} chooseTime={this.setTime} />}

				{/*<button className="b-button b-button_back b-button_big"> Показать еще</button>*/}
			</div>
		)
	}
}

class StepTwo extends React.Component {

	constructor(props) {
		super(props);

		this.state = {
			checkPersonalData: false,
			showTimeTab: false
		};

		this.setTimeShop = this.setTimeShop.bind(this);
		this.usePersonal = this.usePersonal.bind(this);
	}

	componentDidMount() {
		if (is.empty(this.props.step2.SHOPS) && this.props.step2.active) {
			this.props.basketLoad();
		}
	}

	setValidate(state = {}){
		let noValid = 0;
		$.each(this.props.step2.SHOPS, (code, shop) => {
			if(!shop.hasOwnProperty('DELIVERY')){
				noValid++;
			}
		});

		if(state.hasOwnProperty('checkPersonalData')){
			if(state.checkPersonalData !== true)
				noValid++;
		}

		// console.info(noValid);

		let valid = noValid === 0;
		this.props.setValidateStep(valid);
	}

	setCurrentShop(code){
		this.props.setCurrentShop(code);
		this.setState({showTimeTab: !this.state.showTimeTab});
	}

	compileShops(shops = {}) {
		if (is.empty(shops))
			return null;

		let temple = [];
		$.each(shops, (code, arShop) => {
			let setterDate = '', setterTime = '';
			if(arShop.DELIVERY !== undefined){
				setterDate = arShop.DELIVERY.name;
				setterTime = arShop.DELIVERY.from + " - "+ arShop.DELIVERY.to;
			}

			temple.push(
				<div>
				<div className="order__time-wrapper">
					<div className="interval__img b-ib" onClick={this.setCurrentShop.bind(this, code)}>
						<a href="javascript:">
							<img src={arShop.PICTURE.src} height="auto" width="90" />
						</a>
					</div>
					<div className="descr__img b-ib">
						<span>{arShop.NAME}</span>
						<span>{setterDate}<br />{setterTime}</span>
						<button type="button" className="b-button" onClick={this.setCurrentShop.bind(this, code)}>
							выбрать время
						</button>
					</div>
				</div>
					{arShop.INFO != '' && <span className="history__order5">{arShop.INFO}</span>}
				</div>
			);
		});

		return temple;
	}

	setTimeShop(data = {}){
		this.props.setTimeShop(Object.assign({shop: this.props.step2.currentShop}, data));
		this.setValidate();
		this.setState({showTimeTab: false});
	}

	usePersonal(){
		let val = !this.state.checkPersonalData, newState = Object.assign({}, this.state, {checkPersonalData: val});
		// this.setState(newState);

		this.setValidate(newState);
	}

	nextStep(){

	}

	render() {

		const {step2} = this.props;
		const Shops = step2.SHOPS;

		let active = cn('tab_order', {'active_tab': step2.active});

		return (
			<div className={active}>
				<TimeShop show={this.state.showTimeTab} currentShop={step2.currentShop} chooseTime={this.setTimeShop} />

				<div className="order1__form order2__form">
					<div className="check__title check__title_m">Выберите время доставки</div>
					{this.compileShops(Shops)}
				</div>

				<div className="order1__radio" />
				<label className="filter__label filter__label_ok" onClick={this.usePersonal.bind(this)}>
					<input type="checkbox" value="Y" className="filter__checkbox filter__checkbox_radio" /><i />
					<div className="check__wrapper b-ib">
							<span className="history__order2 history__order3">
								Разрешаю использовать мои контактные данные для отправки электронных писем
							</span>
					</div>
				</label>
				<button type="button" className="b-button b-button_back b-button_big" onClick={this.props.prevStep.bind(this, 1)}>Назад</button>
				{step2.isValid === true &&
				<button type="button"
					className="b-button b-button_green b-button_check b-button_big b-button_width"
					onClick={this.props.setNextStep.bind(this, 3)}>
					Дальше
				</button>
				}

			</div>
		);
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(StepTwo);