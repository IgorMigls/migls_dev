/**
 * Created by dremin_s on 16.02.2018.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
import { Loading, Dialog } from 'quasar';


const restModule = function (state, Vue) {
	state.registerModule('rest', {
		namespaced: true,
		state: {
			preloader: { show: false, text: 'Загрузка...' },
			status: 0,
			error: false
		},
		mutations: {
			preloader(state, payload = { show: false, text: 'Загрузка...' }) {
				state.preloader = Object.assign({}, state.preloader, payload);

				if (state.preloader.show) {
					Loading.show({
						message: state.preloader.text,
						customClass: 'bg-white',
						spinnerColor: 'primary',
						messageColor: 'blue'
					});
				} else {
					Loading.hide();
				}

			},
			error(state, data = false) {
				state.error = data;
				if(data !== false){
					Dialog.create({
						title: data.title,
						message: data.message,
						buttons: [{ label: 'Закрыть'}]
					})
				}
			},
		},
		getters: {
			preloader(state) {
				return state.preloader;
			},
			error(state) {
				return state.error;
			}
		}
	});

	if (Vue.hasOwnProperty('http')) {
		Vue.http.interceptors.push(function (request, next) {
			state.commit('rest/preloader', { show: true });

			next(function (response) {

				if (typeof response.body !== 'object') {
					setTimeout(() => {
						state.commit('rest/error', {
							title: 'Ошибка',
							message: 'Системная ошибка'
						});
					});
				} else {

					if (response.body.hasOwnProperty('ERRORS') && response.body.ERRORS !== null) {

						if (response.body.ERRORS instanceof Array) {
							let txt = [];
							response.body.ERRORS.forEach((el) => {
								if (typeof el === 'object') {
									txt.push(el.msg);
								} else {
									txt.push(el);
								}
							});
							setTimeout(() => {
								state.commit('rest/error', {
									title: 'Ошибка',
									message: txt.join(', ')
								});
							});

						} else {
							setTimeout(() => {
								state.commit('rest/error', {
									title: 'Ошибка',
									message: 'Системная ошибка'
								});
							});
						}
					}
				}

				state.commit('rest/preloader', { show: false });
			});
		});
	}
};

export { restModule };
