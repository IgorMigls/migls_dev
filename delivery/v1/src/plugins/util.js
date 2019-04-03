/**
 * Created by dremin_s on 22.02.2018.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

export default {
	priceFormat(number, decimals = 0) {
		let dec_point = '.', thousands_sep = ' ';
		let i, j, kw, kd, km, sign = '';

		number = (+number || 0).toFixed(decimals);
		if (number < 0) {
			sign = '-';
			number = -number;
		}

		i = parseInt(number, 10) + '';
		j = (i.length > 3 ? i.length % 3 : 0);

		km = (j ? i.substr(0, j) + thousands_sep : '');
		kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
		kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, '0').slice(2) : '');

		return sign + km + kw + kd;
	},

	dd(){
		console.info(arguments);
		throw new Error('exit');
	}
};
