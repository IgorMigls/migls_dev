/** @var o _ */
/** @var o Vue */
"use strict";

const url = (action) => {
	return '/rest2/public/personal' + action;
};

const api = {
	getUser: {method: 'GET', url: url('/getUserAction')},
	saveUserFields: {method: 'POST', url: url('/saveUserFields')},
	changeEmail:  {method: 'POST', url: url('/changeEmail')},
	changePassword:  {method: 'POST', url: url('/changePassword')},
};

const Rest = Vue.resource('', {sessid: BX.bitrix_sessid()}, api);
export {Rest};


export default {
	async fetchUser({ commit }) {
		let res = await Rest.getUser();
		if(res.data.DATA !== null){
			commit('user', res.data.DATA);
		}

		return res;
	},

	async saveUserFields({ commit }, payload){
		let res = await Rest.saveUserFields(payload);
		if(res.data.DATA !== null){
			commit('userUpdate', payload);
		}
	},

	async changeEmail({commit, dispatch}, payload){
		return await Rest.changeEmail(payload);
	},

	async changePassword({commit}, payload){
		return await Rest.changePassword(payload);
	},

};