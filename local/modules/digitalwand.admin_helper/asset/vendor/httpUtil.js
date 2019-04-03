/**
 * Created by dremin_s on 26.10.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

export default {
	install (Vue, options = {}) {

		if(Vue.hasOwnProperty('http')){
			Vue.http.interceptors.push(function (request, next) {
				next(function (response) {
					if (typeof response.body !== 'object') {
						setTimeout(() => {
							swal('Ошибка', 'Системная ошибка', 'error')
						});
					} else {

						if (response.body.hasOwnProperty('ERRORS') && response.body.ERRORS !== null) {

							if (response.body.ERRORS instanceof Array) {
								let txt = [];
								response.body.ERRORS.forEach((el) => {
									if(typeof el === 'object'){
										txt.push(el.msg);
									} else {
										txt.push(el);
									}
								});
								setTimeout(() => {
									swal('Ошибка', txt.join(', '), 'error');
								});

							} else {
								setTimeout(() => {
									swal('Ошибка', 'Системная ошибка', 'error');
								});

							}
						}
					}
				});
			});
		}

	}
}