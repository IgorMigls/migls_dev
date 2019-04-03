define(function(require){
	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});

	var Store = function () {

		this.profiles = {};

		Service.action('/getProfilesByUser').get().then(function (result) {
			this.profiles = result.DATA;
		}.bind(this));



	};

	return Store;
});
