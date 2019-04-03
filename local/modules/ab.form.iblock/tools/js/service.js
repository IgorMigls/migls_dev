BX(function () {
	/*BX.FormIblock = function (params) {
		this.params = {
			url: '/rest/forms/',
			action: '',
			type: 'json'
		};

		var _this = this;

		$.each(params, function (key, val) {
			_this.params[key] = val;
		});

		this.params['obForm'] = $(this.params['formId']);
	};

	this.getValues = function () {
		var values = this.params.obForm.serializeArray();

		console.info(values);
	};

	this.send = function () {
		this.getValues();
	};*/

	BX.FormIblock = {
		params: {
			action: '',
			ajax: {
				url: '/rest/forms/',
				dataType: 'json',
				type: 'post',
				success: this.successForm
			}
		},

		init: function (params) {
			$.each(params, function (key, val) {
				BX.FormIblock.params[key] = val;
			});

			BX.FormIblock.params['obForm'] = $('#' + BX.FormIblock.params['formId']);
		},

		getValues: function () {
			var values = this.params.obForm.serializeArray();

			console.info(values);
		},

		send: function () {

			this.params.obForm.ajaxSubmit(this.params.ajax);
		},

		successForm: function (result) {
			console.info(result);
		}
	};
});