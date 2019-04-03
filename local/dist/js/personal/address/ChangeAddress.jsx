define(function (require) {
	'use strict';
	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});
	var React = require('react');
	var ReactDOM = require('dom');
	var ContentWin = require('jsx!personal/address/ContentWin');

	var ChangeAddress = function () {
		this.ClassAddress = React.createClass({
			getInitialState: function () {
			    return {
			        auth: false
			    }
			},
			showWindow: function () {
				var popup = $.magnificPopup.instance;
				popup.open({
						items: {
							src: '#change_address_win',
							type: 'inline',
						},
						enableEscapeKey: true,
						showCloseBtn: false,
						closeOnBgClick: true,
						mainClass: 'show_address_win'
					}
				)
			},

			render: function () {
				return (
					<div>
						<button onClick={this.showWindow} className="b-button b-header-location__change">Сменить адрес</button>
						<div className="hide_content">
						<div className="b-popup b-popup-hello" id="change_address_win">
							<ContentWin auth={this.state.auth}/>
						</div>
					</div>
					</div>
				);
			},

			componentDidMount:function () {
				this.setState({auth: $('#render_address').data('user')});
			},
		});

		this.render = function () {
			return ReactDOM.render(<this.ClassAddress />, BX('render_address'));
		}
	};

	return new ChangeAddress();
});