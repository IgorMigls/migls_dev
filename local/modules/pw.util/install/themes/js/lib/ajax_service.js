angular.module('ajax.service', ['ngResource'])
		.provider('EAjax', [function(){

			var options = {
				alertTime: false,
				preLoader: true,
				trace: false
			};

			this.setUrl = function(val){
				var regexp = /[?]+/;
				var testUrl = regexp.test(val);
				if(testUrl){
					options.url = val + '&sessid='+ BX.bitrix_sessid() + '&';
				} else {
					options.url = val + '?sessid='+ BX.bitrix_sessid() + '&';
				}
			};

			this.setPreLoader = function(isLoader){
				options.preLoader = isLoader;
			};

			this.setTrace = function (val) {
				options.trace = val;
			};

			var _this = this;

			BX.PreLoader = function (options) {

				this.html = '';
				this.message = 'Загрузка...';
				this.options = {
					time: 30,
					srcImg: false,
					icon: 'fa fa-refresh fa-spin fa-lg'
				};

				var PreLoader = this;

				if(angular.isDefined(options)){
					angular.forEach(options, function (value, k) {
						PreLoader.options[k] = value;
					});
				}

				this.createLoader = function () {
					var loaderWrap = $('#preloader_wrap');
					if(loaderWrap.length == 0){
						this.html = '<div id="preloader_wrap" preloader="" style="display:none">';
						this.html += 	'<div class="preloader_inner">';
						if(this.options.srcImg){
							this.html +=	'<span class="preloader_icon">'+ this.message +'</span>';
						} else {
							this.html +=	'<i class="'+ this.options.icon +'"></i>&nbsp;&nbsp;';
						}
						this.html +=		'<span class="preloader_msg">'+ this.message +'</span>';

						this.html += 	'</div>';
						this.html += '</div>';

						$('body').append(this.html);
						this.wrap = $('#preloader_wrap');
						this.wrap.css({
							'top':'50%',
							'z-index':10000,
							'position':'fixed',
							'background': '#fff',
							'padding':'15px',
							'font-size':'100%',
							'border-radius':'5px',
							'box-shadow':'0 0 6px #999',
							'left': '45%'
						});
					} else {
						this.wrap = loaderWrap;
					}
				};

				this.show = function () {
					this.wrap.fadeIn(300);
				};

				this.close = function () {
					this.wrap.remove();
				};

				this.getHtml = function () {
					return this.html;
				};

				this.createLoader();
			};

			this.$get = [
				'$http','$q','$rootScope',
				function($http, $q, $rootScope) {

					var CAjax = function(){
						this.alerts = [];
						this.errors = {};

						var AjaxNow = this;

						this.getServiceUrl = function() {
							return options.url;
						};

						this.setServiceUrl = function (url) {
							_this.setUrl(url);
						};

						this.save = function(data){

							$rootScope.$broadcast('OnAjaxStart', true);

							var defer = $q.defer();

							if(options.preLoader)
								$rootScope.$broadcast('showWait','show');

							AjaxNow.alerts = [];
							AjaxNow.errors = {};

							if(!data['CLASS']){
								data['CLASS'] = this.getParam('CLASS');
							}

							$http.post(this.getParam('url'), data, {cache: false}).success(function (result) {
								//console.info(result);

								AjaxNow.setResult(result);

								if(options.preLoader){
									$rootScope.$broadcast('showWait', false);
								}
								defer.resolve(AjaxNow.getResult());
							});

							return defer.promise;
						};

						this.post = function (data) {
							return AjaxNow.save(data);
						};

						this.get = function(data){
							$rootScope.$broadcast('OnAjaxStart', true);
							var defer = $q.defer();

							if(!data['CLASS']){
								data['CLASS'] = this.getParam('CLASS');
							}

							if(options.preLoader)
								$rootScope.$broadcast('showWait','show');

							AjaxNow.alerts = [];
							AjaxNow.errors = {};

							var url = AjaxNow.decodeUrl(data);

							$http.get(this.getParam('url') + url).success(function (result) {
								//console.info(result);
								AjaxNow.setResult(result);
								if(options.preLoader){
									$rootScope.$broadcast('showWait', false);
								}

								defer.resolve(AjaxNow.getResult());
							});

							return defer.promise;
						};

						this.setResult = function (result) {
							AjaxNow.result = {};
							if (result.DATA) {
								AjaxNow.result.DATA = result.DATA;
							} else {
								AjaxNow.result.DATA = null;
							}

							if(result.INFO){
								AjaxNow.result.INFO = result.INFO;
								$rootScope.$broadcast('ajaxInfo', AjaxNow.result.INFO);
							} else {
								AjaxNow.result.INFO = null;
							}

							if(result.ERRORS){
								AjaxNow.result.ERRORS = result.ERRORS;
								$rootScope.$broadcast('ajaxErrors', AjaxNow.result.ERRORS);
							} else{
								AjaxNow.result.ERRORS = null;
							}
						};

						this.getResult = function () {
							return AjaxNow.result;
						};

						this.getParam = function(key){
							if(options[key])
								return options[key];
							else
								return null;
						};

						this.parseUrlQuery = function(path) {
							var data = {};
							if (path) {
								var pair = (path.substr(0)).split('&');
								for (var i = 0; i < pair.length; i++) {
									var param = pair[i].split('=');
									data[param[0]] = param[1];
								}
							}
							return data;
						};

						this.addParaToUrl = function(arParam) {
							var url = this.getParam('url'), newUrl = '';
							var obPath = this.parseUrlQuery(url);
							angular.forEach(arParam, function(val, key){
								obPath[key] = val;
							});
							newUrl = this.decodeUrl(obPath);
							options['url'] = newUrl;
							return newUrl;
						};

						this.decodeUrl = function(data, isObj){
							var url = '';
							angular.forEach(data, function(val, key){
								if(angular.isString(val)){
									if(key != '' && val != undefined){
										if(isObj){
											url += isObj + '[' + key + ']' + '=' + val + '&';
										} else {
											url += key + '=' + val + '&';
										}
									}
								} else if(angular.isObject(val) || angular.isArray(val)) {
									url += AjaxNow.decodeUrl(val, 'DATA');
								}
							});
							return url;
						};

						this.addOption = function(key, val){
							options[key] = val;
						};

						this.getAjaxErrors = function () {
							if(AjaxNow.result.ERRORS != null){
								var alerts = [];
								angular.forEach(AjaxNow.result.ERRORS, function (value, k) {
									var msg = '';
									if(options.trace){
										msg = value.msg;
									} else if(value.code > 100){
										msg = value.msg;
									} else {
										msg = 'Ошибка выполнения запроса.';
									}
									alerts.push({type: 'danger', msg: msg});
								});
								return alerts;
							}
							return null;
						};

						this.getAlerts = function(){
							return this.alerts;
						};

						this.closeAlert = function(index){
							this.alerts.splice(index, 1);
							$rootScope.$broadcast('setAlerts', this.alerts);
						};

						this.getErrorField = function(){
							return this.errors;
						};

						this.setClassName = function (className) {
							this.addOption('CLASS', className);
						};

						this.getClassName = function () {
							return options['CLASS'];
						};

						this.showGrowl = function (msg, type, title) {
							if(!type)
								type = 'info';

							return $.notify({
								message: msg,
								title: title,
								icon: 'glyphicon glyphicon-warning-sign'
							}, {
								type: type,
								element: 'body',
								placement: {
									from: "top"
									//align: 'top'
								},
								delay: 4000,
								animate: {
									enter: 'animated fadeInDown',
									exit: 'animated fadeOutUp'
								},
								icon_type: 'class',
								offset: 100
							});
						}
					};

					return new CAjax($http, options);
				}];
		}]);
