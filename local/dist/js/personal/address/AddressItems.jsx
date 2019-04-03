define(function (require) {
	'use strict';
	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});
	var React = require('react');
	var ReactDOM = require('dom');

	return React.createClass({

		getInitialState: function () {
			return {
				profileList: []
			}
		},

		componentDidMount: function () {
			Service.action('getData').get().then(function (result) {
				if (result.DATA.profiles.length > 0) {
					this.setState({profileList: result.DATA.profiles});

					$('#js-address-scroll').jScrollPane({
						autoReinitialise: true
					});
				}
			}.bind(this));
		},

		render: function () {
			var temple = [];
			var compare = this.props.changeAddress;
			var edit = this.props.editAddress;
			if (this.state.profileList.length > 0) {
				temple = this.state.profileList.map(function (profile) {
					if(!is.undefined(profile.VALUES.CITY)){
						var strSearch = profile.VALUES.CITY.VALUE;
						strSearch += ', ул.' + profile.VALUES.STREET.VALUE;
						strSearch += ', д.' + profile.VALUES.HOUSE.VALUE;

						return (
							<div key={profile.ID} className="b-button check__back address_item_popup">
								<div className="edit__icon address_item_popup" onClick={edit.bind(null, profile)} />
								<div className="arrow_left_address"/>
								<div className="address_item_txt"
									 onClick={compare.bind(null, strSearch)}>{profile.VALUE_FORMAT}</div>
							</div>
						)
					}

				}.bind(this));
			}
			return (
				<div className="b-popup-hello-form-adr-wrapper b-custom-scroll js-custom-scroll" id="js-address-scroll">
					{temple}
				</div>
			);
		}
	});
});