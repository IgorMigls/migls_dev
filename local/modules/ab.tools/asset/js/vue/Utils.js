/**
 * Created by dremin_s on 23.06.2017.
 /** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

export default {
	install (Vue, options) {

		Vue.prototype.$foreach = function (items, callback) {
			for (let key in items) {
				if (items.hasOwnProperty(key)) {
					callback(items[key], key);
				}
			}
		};

		Vue.prototype.$keyExist = function (key, items) {
			return items.hasOwnProperty(key);
		};

		Vue.prototype.$inData = function (search, items) {
			let res = false;
			if (search !== undefined) {
				for (let key in items) {
					if (items[key] == search) {
						res = true;
						break;
					}
				}
			}
			return res;
		};

		Vue.prototype.$indexOf = function (search, items) {
			let res = false;
			if (search !== undefined) {
				for (let key in items) {
					if (items[key] == search) {
						res = key;
						break;
					}
				}
			}
			return res;
		};

		Vue.prototype.$objToArray = function (items, callback = null) {
			let result = [];
			this.$foreach(items, (el, code) => {
				if(callback instanceof Function){
					result.push(callback(el, code))
				} else {
					result.push(el);
				}
			});

			return result;
		};

		Vue.prototype.$forMap = function (items, callback) {
			let result, bType = Array;

			if (items instanceof Array) {
				result = [];
			}
			if (items instanceof Object) {
				result = {};
				bType = Object;
			}

			this.$foreach(items, (el, code) => {
				if (items instanceof Object) {
					result[code] = callback(el, code);
				} else if (items instanceof Array) {
					result.push(callback(el, code));
				}
			});

			return result;
		};

		Vue.prototype.$intVal = (val) => {
			let result = null;
			val = val.trim(val);

			switch (typeof val) {
				case 'string':
					result = parseInt(val);
					if (!/^[0-9]+$/gi.test(result)) {
						result = null;
					}
					break;
				case 'number':
					result = val;
					if (!/^[0-9]+$/gi.test(result)) {
						result = null;
					}
					break;
				default:
					result = null;
					break;
			}
			return result;
		};

		Vue.prototype.$count = (data) => {
			let result = 0;
			if(data instanceof Object){
				result = Object.keys(data).length;
			} else if(data instanceof Array) {
				result = data.length;
			}

			return result;
		};
	}
};
