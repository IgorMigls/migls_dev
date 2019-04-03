/**
 * Created by GrandMaster on 26.10.17.
 */
"use strict";
import { store } from './store';
import mainForm from './components/mainForm.vue';
import stepOne from './components/stepOne.vue';
import stepTwo from './components/stepTwo.vue';
import stepThree from './components/stepThree.vue';
import {mapGetters, mapActions} from 'vuex';
import Map from 'Utilities/maps/Map';

Vue.use(VeeValidate);

$(function () {
	new Vue({
		store,
		el: "#main_order_form",
		data: {},
		created(){
			this.$store.commit('orderFormLocalStorage');
		},
		methods: {
			// ...mapActions([])
		},
		components: {
			mainForm, stepOne, stepTwo, stepThree
		},
		computed: {
			...mapGetters([
				'activeComponent'
			])
		},
		mounted(){
			const mapData = new Map({
				mainComponent: this.$el
			});
			this.$store.dispatch('loadMap', mapData);

		}
	});
});