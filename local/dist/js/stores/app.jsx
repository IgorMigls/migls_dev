define(function (require) {
	'use strict';

	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});
	var React = require('react');
	var ReactDOM = require('dom');

	return {
		component: React.createClass({
			render: function () {
				return (
					<div><h2>test</h2></div>
				);
			}
		}),

		render: function () {
			return ReactDOM.render(<this.component />, BX('main_store'));
		}
	}
});