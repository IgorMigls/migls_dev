define(function (require) {
	'use strict';
	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});
	var React = require('react');

	return React.createClass({

		emailInput: null,
		Service: Service,

		getInitialState: function () {
			return {
				visible: false,
				email: '',
				validForm: false,
				addressStr: ''
			}
		},

		componentDidMount: function () {
			this.Service.action('getAddressUser').get().then(function (res) {
				if (res.DATA != null) {
					this.setState({email: res.DATA, validForm: true});
				}
			}.bind(this));
		},

		componentWillUpdate: function (prop, state) {
			if (state.visible === true) {
				$.magnificPopup.open({
					items: {
						src: '#render_no_address',
						type: 'inline'
					},
					enableEscapeKey: true,
					showCloseBtn: false,
					closeOnBgClick: true,
					mainClass: 'show_address_win'
				});
			}
			$('.success_txt').remove();
			if (this.emailInput === null) {
				this.emailInput = $('input[name=EMAIL_NO_ADDRESS]');
			}
		},

		changeEmail: function (ev) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if (re.test(ev.target.value)) {
				this.emailInput.css({'background-color': '#EBFFF2'});
				this.setState({validForm: true});
			} else {
				this.setErrorEmail();
			}

			this.setState({email: ev.target.value});
		},

		saveEmail: function (ev) {
			if (this.state.email == '') {
				this.setErrorEmail();
			}
			if (this.state.validForm === true) {
				this.Service.action('saveEmailNoAddress').post({EMAIL: this.state.email, address: this.state.addressStr}).then(function (result) {
					if (result.DATA != null && result.STATUS == 1) {
						this.emailInput.parent().prepend('<div class="success_txt">Адрес сохранен</div>');
					}
				}.bind(this));
			}
		},

		setErrorEmail: function (msg) {
			if (!msg || msg == '' || msg === false || msg === null) {
				msg = '';
			}
			if (this.emailInput.length > 0) {
				this.emailInput.css({'background-color': '#FFEFF1'});
			}
			this.setState({validForm: false});
		},

		prevWin: function () {
			$.magnificPopup.close();
			$('#render_address .b-header-location__change').click();
		},

		render: function () {
			return (
				<div className="no_address">
					<div className="mfp-close"/>
					<div className="header_win">
						<div className="no_address_icon"></div>
					</div>
					<div className="content accepted__content">
						<div className="lk__add-address">
							<h2>Ваш адрес не попадает <br />ни в одну из зон доставки :(</h2>
							<p>Оставьте e-mail, чтобы бы могли сообщить, <br />
								когда сервис станет доступен по этому адресу</p>
							<p>
								<input type="text" placeholder="sss@sss.ru" name="EMAIL_NO_ADDRESS"
									   className="form__input form__input_accepted" onChange={this.changeEmail}
									   value={this.state.email}/>
								<button type="submit" name="Login"
										className="b-button b-button_green b-button_check" onClick={this.saveEmail}>
									Отправить
								</button>
							</p>
							<p>Есть еще один адрес? <a href="javascript:" onClick={this.prevWin}>Попробуйте другой</a></p>
						</div>
					</div>
				</div>
			);
		}

	});
});