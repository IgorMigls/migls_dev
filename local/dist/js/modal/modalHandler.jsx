define(function (require) {
	'use strict';

	var React = require('react');
	var ReactDOM = require('dom');
	var Modal = require('jsx!modal/Modal');

	var modalHandler = function () {
		this.app = React.createClass({
			openWin: function () {
				Modal.render();
			},

		    render: function () {
		    	var link = this.props.link ? this.props.link : "javascript:";

		        return(
		        	<a href={link} onClick={this.openWin}>КЛИК</a>
				);
		    }
		});

		this.render = function () {

			return ReactDOM.render(<this.app />, BX('click1'));
		};
	};

	return new modalHandler();
});