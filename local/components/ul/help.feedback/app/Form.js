import {FormCtrl as Ctrl} from './controller';
import {connect} from 'react-redux';

class Form extends React.Component{
 	constructor(props){
 		super(props);

	}

    render () {
		return (
			<formHelp>
				<div className="ph-me">
					<span>Перезвоните мне</span>
					<a href="javascript:">Задать вопрос</a>
				</div>
				<div className="order1__form">
					<form>
						<div className="b-ib lk__profile"><span className="span_help">Город</span>
							<div className="b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-header-popup__filter_lk_280 b-ib">
								<div className="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
									<select className="b-custom-select js-custom-select2">
										<option selected="selected" value="Самара">Самара</option>
										<option value="Деревня кабачки">Деревня кабачки</option>
										<option value="Черпеповец">Черпеповец</option>
									</select>
								</div>
							</div>
						</div>
						<label className="form__label">
							<input type="text" placeholder="Ваш номер" className="form__input form__input_middle form__input_help" /><span className="span_help">Телефон</span>
						</label>
						<label className="form__label">
							<input type="text" placeholder="Введите имя" className="form__input form__input_middle form__input_help" /><span className="span_help">Имя</span>
						</label>
						<div className="help-wrapper">
							<div className="help__left b-ib"><span>Причина</span></div>
							<div className="help__right b-ib">
								<div className="order1__radio">
									<label className="filter__label filter__label_radio filter__label_radio-yellow">
										<input checked type="radio" name="adr" className="filter__checkbox filter__checkbox_radio" /><i />
											<div className="check__wrapper b-ib"><span className="history__order2">Хочу сделать заказ</span></div>
									</label>
									<label className="filter__label filter__label_radio filter__label_radio-yellow">
										<input checked type="radio" name="adr" className="filter__checkbox filter__checkbox_radio" /><i />
											<div className="check__wrapper b-ib"><span className="history__order2">Не пришел заказ</span></div>
									</label>
									<label className="filter__label filter__label_radio filter__label_radio-yellow">
										<input type="radio" name="adr" className="filter__checkbox filter__checkbox_radio" /><i />
											<div className="check__wrapper b-ib"><span className="history__order2">Жалоба</span></div>
									</label>
								</div>
							</div>
						</div>
					</form>
					<button className="b-button b-button_green b-button_check b-button_big b-button_width"> Отправить заявку</button>
					<span className="help__small">Оператор перезванивает в рабочее время в течение 2 часов</span>
				</div>
			</formHelp>
		);
    }
}

export default connect(Ctrl.mapStateToProps, Ctrl.mapDispatchToProps)(Form);