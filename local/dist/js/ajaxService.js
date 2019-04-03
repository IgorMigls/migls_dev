define(function(require){
	var sweetAlert = require('sweetalert');

	return function AjaxService(params) {
		this.params = {
			mainUrl: '/service/ajax',
			loader: {}
		};

		if(is.array(params) || is.object(params)){
			$.each(params, function (k, val) {
				this.params[k] = val;
			}.bind(this));
		}

		var _self = this;

		this.showLoader = function () {
			this.$loader.removeClass('zoomOut').addClass('fadeIn');
		};

		this.hideLoader = function () {
			this.$loader.removeClass('fadeIn').addClass('zoomOut');
		};

		this.preLoader = function () {
			var html = '' +
				'<div id="preLoader_service" class="animated"> ' +
					'<div class="loader_wrap"> ' +
						'<div class="icon item_content"><i class="fa fa-spinner fa-pulse fa-2x fa-fw" /></div> ' +
						'<div class="message item_content">Загрузка...</div>' +
					'</div> ' +
				'</div>';

			if($('#preLoader_service').length == 0){
				$('body').append(html);
			}

			this.$loader = $('#preLoader_service');
		};

		this.preLoader();

		if(!is.empty(this.params.loader)){
			if(this.params.loader.show === true) {
				this.showLoader();
			} else {
				this.hideLoader();
			}
		}

		this.ajaxParam = {
			url: this.params.mainUrl,
			dataType: 'json',
			processData: false,
			headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
			dataFilter: function (data, type) {
				if (type == 'json') {
					var result = JSON.parse(data);
					if (result.ERRORS != null && is.array(result.ERRORS)) {
						var error = result.ERRORS.join("\n");
						_self.errorView(false, error);
					}
					data = JSON.stringify(result);
				}

				return data;
			},
			beforeSend: function () {
				_self.showLoader();
			},
			complete: function () {
				_self.hideLoader();
			}
		};

		this.action = function (action) {
			this.ajaxParam.url = this.params.mainUrl + action;

			return this;
		};

		this.post = function (data) {
			if(!data || !is.object(data)){
				data = {};
			}

			this.ajaxParam.data = JSON.stringify(data);
			this.ajaxParam.type = 'post';

			return this.send();
		};

		this.get = function (data) {
			if (data) {
				this.ajaxParam.url = BX.util.add_url_param(this.ajaxParam.url, data);
			}
			this.ajaxParam.type = 'get';

			return this.send();
		};

		this.send = function () {
			return $.ajax(this.ajaxParam);
		};

		this.errorView = function (title, text) {
			if (!title) {
				title = 'Ошибка';
			}
			sweetAlert(title, text, "error");
		};

		this.setImport = function (arImport) {
			this.imports = arImport;
		};
	}
});