/**
 * Created by dremin_s on 21.04.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

import { Field, Form } from 'UIForm';
import Ajax from 'preloader/RestService';

const Rest = new Ajax({
	baseURL: '/rest/UL/Main/Personal/Address/'
})

class NoAddressWin extends React.Component {

	constructor(props) {
		super(props);

		this.state = {
			success: false
		}

		this.saveEmailNoAddress = this.saveEmailNoAddress.bind(this);
		this.prevWin = this.prevWin.bind(this);
	}

	saveEmailNoAddress(form) {
		console.info(form);
		if(form.valid === true){
			Rest.post('/saveEmailNoAddress', {EMAIL: form.values.EMAIL_NO_ADDRESS, address: this.props.address}).then(result => {
				if (result.data.DATA != null && result.data.STATUS == 1) {
					this.setState({success: true});
				}
			});
		}
	}

	prevWin() {
		$.magnificPopup.close();
		$('#render_address .b-header-location__change').click();
	}

	componentDidMount () {
		$.magnificPopup.open({
			items: {
				src: '#render_no_address',
				type: 'inline'
			},
			midClick: false,
			closeOnBgClick: false,
			closeBtnInside: false,
			showCloseBtn: false,
			modal: true,
			key: 'noaddress'
		});
	}

	render() {
		return (
			<div className="no_address">
				<div className="header_win">
					<div className="no_address_icon"></div>
				</div>
				<div className="content accepted__content">
					<div className="lk__add-address">
						<h2>Ваш адрес не попадает <br />ни в одну из зон доставки :(</h2>
						<p>Оставьте e-mail, чтобы бы могли сообщить, <br />
							когда сервис станет доступен по этому адресу</p>

						<Form name="EMAILS" id="email_no_address" onSubmit={this.saveEmailNoAddress}>
							<p>

								{this.state.success === true && <div className="success_txt">Адрес сохранен</div>}

								<Field.String name="EMAIL_NO_ADDRESS" valid={['isEmail']}
									className="form__input form__input_accepted" placeholder="sss@sss.ru" errorMsg="Введите e-mail правильно" />

								<button type="submit"
									className="b-button b-button_green b-button_check" onClick={this.saveEmail}>
									Отправить
								</button>

								<a className="return_home_win" onClick={(ev) => {ev.preventDefault(); window.location.reload()}} href="javascript:">
									Выбрать другой адрес
								</a>
							</p>
						</Form>
					</div>
				</div>
			</div>
		);
	}
}

if(window.UL === undefined){
	window.UL = {
		noAddressWin: (node, address) => {
			$(function () {
				ReactDOM.render(<NoAddressWin  address={address} />, node);
			});
		}
	};
}
