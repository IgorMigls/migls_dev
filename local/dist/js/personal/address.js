BX(function () {
	if(BX.UL == undefined){
		BX.UL = {};
	}

	var Service = new BX.AjaxService({
		mainUrl: '/rest/UL/Main/Personal/Address/'
	});

	BX.UL.FormAddress = React.createClass({

		getInitialState: function () {
			return {
				visible: false,
				validForm: false,
				Fields: {},
				Result: {},
				profileId: null
			}
		},

		componentWillUpdate: function (prop, state) {
			if (state.visible === true) {
				$.magnificPopup.open({
					items: {
						src: '#change_address_popup',
						type: 'inline'
					},
					enableEscapeKey: true,
					showCloseBtn: false,
					closeOnBgClick: true,
					mainClass: 'show_address_change',
				});
			}
			if(state.profileId > 0){
				setTimeout(function () {
					$.magnificPopup.close();
				}, 2000);
			}
		},

		componentDidMount:function () {
			Service.action('getData').get().then(function (result) {
				if(result.DATA.props != null){
					this.setState({Fields: result.DATA.props});
				}
			}.bind(this));
		},

		changeField: function (ev) {
			var Fields = this.state.Fields;
			if(ev.target.name == 'PROFILE_NAME'){
				Fields['PROFILE_NAME'] = ev.target.value;
			}
			if(!is.empty(Fields[ev.target.name]) || !is.undefined(Fields[ev.target.name])){
				Fields[ev.target.name]['VALUE'] = ev.target.value;
				this.setState({Fields: Fields});
			}

		},

		saveAddress: function () {
			var noValid = 0;
			$.each(this.state.Fields, function (code, arField) {
				if(is.undefined(arField.VALUE) || arField.VALUE == ''){
					noValid++;
				}
			});
			if(noValid > 0){
				this.setState({validForm: false});
			} else {
				this.setState({validForm: true});
			}

			Service.action('saveAddress').post(this.state.Fields).then(function (result) {
				if(result.DATA != null){
					this.setState({profileId: result.DATA});
				}
				this.setState({Result: result});
			}.bind(this));
		},

		render: function () {
			var goodSave;
			if(this.state.profileId != null){
				goodSave = (<span className="goodMsg">Адрес сохранен</span>);
			}

			if(!is.empty(this.state.Fields)){
				return (
					<div className="b-popup-recovery">
						<div className="b-popup-cart__head">
							<div className="b-products-block-top b-ib bg_reverse">
								<div className="cart__img-wrapper">
									<div className="cart__prod-title">
										<div className="icon-g"></div>
									</div>
									<div className="recovery__title">Сохранить адрес</div>
								</div>
							</div>
						</div>
						<div className="accepted__content">
							<div className="lk__add-address">
								<form method="post" noValidate="novalidate">
									{goodSave}
									<span className="lk__form-descr">Все поля обязательны для заполнения</span>
									<label className="form__label">
										<span className="span__label" >{this.state.Fields['CITY']['NAME']}</span>
										<input type="text" placeholder={this.state.Fields['CITY']['NAME']}
											   className="form__input form__input_middle" name="CITY" onChange={this.changeField} />
									</label>
									<label className="form__label">
										<span className="span__label" >{this.state.Fields['STREET']['NAME']}</span>
										<input type="text" placeholder={this.state.Fields['STREET']['NAME']}
											   className="form__input form__input_middle" name="STREET" onChange={this.changeField} />
									</label>
									<label className="form__label">
										<span className="span__label" >{this.state.Fields['HOUSE']['NAME']}</span>
										<input type="text" placeholder={this.state.Fields['HOUSE']['NAME']}
											   className="form__input form__input_middle" name="HOUSE" onChange={this.changeField} />
									</label>
									<label className="form__label">
										<span className="span__label" >{this.state.Fields['APARTMENT']['NAME']}</span>
										<input type="text" placeholder={this.state.Fields['APARTMENT']['NAME']}
											   className="form__input form__input_middle" name="APARTMENT" onChange={this.changeField} />
									</label>
										<label className="form__label">
											<span className="span__label">{this.state.Fields['FLOOR']['NAME']}</span>
											<input type="text" placeholder={this.state.Fields['FLOOR']['NAME']}
												   className="form__input form__input_middle" name="FLOOR"onChange={this.changeField} />
										</label>
									<label className="form__label"> <span className="span__label">Название</span>
										<input type="text" placeholder="Название"
											   name="PROFILE_NAME"
											   className="form__input form__input_middle form__input_tooltip" onChange={this.changeField} />
									</label>
									<button onClick={this.saveAddress} type="button"
											className="b-button b-button_check b-button_green b-button_big">
										Сохранить адрес
									</button>
								</form>
							</div>
						</div>
					</div>
				);
			} else {
				return (<div className="b-popup-recovery">Загрузка...</div>);
			}
		}
	});
});