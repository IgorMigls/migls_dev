BX(function () {
	BX.appUL.service('$orderService', ['$aService','$q','$rootScope', function ($aService, $q, $rootScope) {

		var self = this;

		this.url = '/rest/UL/Main';

		this.data = {
			Properties: {},
			Profiles: {},
			Basket: {},
			Delivery: {},
			deliveryRaw: {},
			CurrentProfile: {},
			formPropProfile: {}
		};

		this.get = function (name) {
			if(!is.empty(name) && is.defined(this.data[name])){
				return this.data[name];
			}

			return null;
		};

		this.set = function (name, value) {
			this.data[name] = value;
		};

		this.getAllData = function () {
			return this.data;
		};

		this.loadData = function () {
			this.Deferred = $q.defer();

			$aService.setAction(this.url + '/Personal/Address/getData').get().then(function (result) {
				self.data.Properties = result.data.DATA.props;
				// if (self.data.Profiles.length == 0) {
				// 	self.data.bNewOrder = true;
				// }

				var profiles = [];
				profiles.push({
					ID: 0,
					NAME: 'Выбрать',
					PERSON_TYPE_ID: 1,
					USER_ID: 0,
					VALUES: {},
					VALUE_FORMAT: 'Выбрать'
				});

				angular.forEach(result.data.DATA.profiles, function (val, k) {
					profiles.push(val);
				});

				// if (angular.isDefined(self.data.formAddress) && !is.empty(self.data.formAddress)) {
				// 	self.data.formAddress.$setPristine();
				// }

				self.data.Profiles = profiles;
				self.Deferred.resolve(self);

			});

			return this.Deferred.promise;
		};

		this.clearPropValue = function () {
			angular.forEach(self.data.Properties, function (value, k) {
				self.data.Properties[k]['VALUE'] = '';
			});

			self.data.Properties['PROFILE_ID'] = 0;
			self.data.Properties['PROFILE_NAME'] = '';
		};

		this.saveAddress = function (profileName) {

			var defAddress = $q.defer();
			var post = this.get('Properties');
			if(!BX.is.empty(this.get('CurrentProfile')) && this.get('CurrentProfile') != null){
				// post.PROFILE_NAME = this.get('CurrentProfile').NAME;
				post.PROFILE_ID = this.get('CurrentProfile').ID;
			}

			post.PROFILE_NAME = profileName;
			$aService.setAction(this.url + '/Personal/Address/saveAddress').post(post).then(function (res) {
				defAddress.resolve(res);
			});

			return defAddress.promise;
		};

		this.delAddress = function (index) {
			if (angular.isDefined(self.data.Profiles[index])) {
				return $aService.setAction(self.url + '/Personal/Address/delete').post({ID: self.data.Profiles[index]['ID']});
			}
		};

		this.loadDelivery = function () {
			this.Deferred = $q.defer();
			if (!is.empty(this.get('Delivery'))) {
				var shopIds = [];
				angular.forEach(this.get('Delivery').shop, function (val, shopId) {
					shopIds.push(val.ID);
				});
				$aService.setAction('/rest/UL/Main/Order/getDeliveryItems').post(shopIds).then(function (result) {
					if (result.data.DATA != null) {
						self.set('deliveryPrice', result.data.DATA.SUM);
						self.set('deliveryPriceFormat', result.data.DATA.SUM_FORMAT);
						self.set('deliveryRaw', result.data.DATA);
						self.Deferred.resolve(self);
					}
				});
			}

			return self.Deferred.promise;
		};
	}]);
});
