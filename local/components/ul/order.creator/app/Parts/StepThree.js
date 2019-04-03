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

class StepThree extends React.Component {

	constructor(props) {
		super(props);

		this.state = {
			processOrderSave: false,
			mainComment: ''
		};
	}

	saveOrder(data){
		this.setState({processOrderSave: true});
		this.props.saveOrder({step1: data.step1, step2: data.step2, mainComment: this.state.mainComment});
	}

	setMainComment(ev){
		this.setState({mainComment: ev.target.value});
	}

	render() {
		const {step3, step2, step1} = this.props;

		// console.info(step2, step1);

		if(step3.hasOwnProperty('order')){
			return (
				<div>
					<div className="check__title check__title_r">Спасибо за заказ!</div>
					<div className="check__cont">
						<span className="check__sm">Номер вашего заказа</span><span className="check__big">{step3.order}</span>
						<span className="check__sm">Наши менеджеры свяжутся <br/> с вами а течение 5 минут </span>
					</div>
				</div>
			);
		}

		let sumDelivery = 0;
		if (!is.empty(step2.SHOPS)) {
			$.each(step2.SHOPS, (code, shop) => {
				if (shop.hasOwnProperty('DELIVERY'))
					sumDelivery += parseInt(shop.DELIVERY.price);
			});
		}

		let active = cn('tab_order', {'active_tab': step3.active});
		let btnSaveClass = cn('b-button b-button_green b-button_check b-button_big', {'process': this.state.processOrderSave});

		console.info(step2);

		let totalSum = BX.util.number_format(Number(step2.SUM_RAW) + parseInt(sumDelivery), 2, ',', ' ');


		return (
			<div className={active}>
				<div className="order_step_1 order_step_2 order_step_3">
					<div className="check__title check__title_m">Завершение</div>
					<div className="check__total-items">
						<div className="check__del">
							<span>Адрес доставки: </span>
							<span>г.{step1.addressItems.CITY}, {step1.addressItems.STREET} д.{step1.addressItems.HOUSE}</span>
						</div>
						<div className="check__friends">
							<div className="check__total">Товары:
								<span /><span className="check-rub"> {step2.SUM} ₽</span>
							</div>
							<div className="check__total">Доставка:
								<span /><span className="check-rub"> {sumDelivery} ₽</span>
							</div>
							<div className="check__total check__total_m">Итого: {totalSum} ₽</div>
						</div>
					</div>
					<div className="order1__form">

						<label className="form__label">
							<input type="text" placeholder="Промокод" className="form__input form__input_middle" />
							<div className="lable__tooltip">
								<span className="tooltip__content animated zoomIn">
									При наличии купона Вы можете получить скидку или бесплатную доставку.
								</span>
							</div>
						</label>
						<label className="form__label">
							<input type="text" onChange={this.setMainComment.bind(this)}
								placeholder="Комментарии к заказу" className="form__input form__input_middle" />
						</label>
					</div>
					<button type="button" className="b-button b-button_back b-button_big" onClick={this.props.prevStep.bind(this, 2)}>
						Назад
					</button>
					{this.state.processOrderSave === false ?
						<button type="button" className={btnSaveClass}
							onClick={this.saveOrder.bind(this, {step1, step2})}>
							Завершить
						</button>
					:
						<button type="button" className={btnSaveClass}>
							Оформление <i className="fa fa-spinner fa-spin fa-fw" />
						</button>
					}

				</div>
			</div>
		);
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(StepThree);