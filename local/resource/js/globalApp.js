/**
 * Created by dremin_s on 28.10.2017.
 */
/** @var o is */
/** @var o $ */
"use strict";
if(!window.hasOwnProperty('MigBus')){
	window.MigBus = new Vue();
}

import productItem from './product/productItem.vue';

const basketProductApp = {
	data: {
		tabActive: 'tab-0'
	},
	methods: {
		openShop(id){
			let active = id.replace('#','');
			const $container = $(this.$el);
			$container.find('.b-tabs-content').hide(0);
			$container.find(id).show(0);
			this.tabActive = active;
		}
	},
	components: {
		productItem
	},
	mounted(){
		let productSlider = $('.js-products-slider'),
			productSliderOptions = {
				dots: false,
				arrows: true,
				slide: '.js-products-slider-item',
				slidesToShow: 5,
				slidesToScroll: 1
			};

		if (productSlider.length) {
			productSlider.slick(productSliderOptions);
		}


		let searchSlider = $('.js-products-slider-search');
		if (searchSlider.length > 0) {
			searchSlider.slick({
				dots: false,
				arrows: true,
				slide: '.js-products-slider-item',
				slidesToShow: 4,
				slidesToScroll: 4
			});
		}
	}
};

$(function () {
	if($('#popular_products_app').length > 0){
		new Vue(basketProductApp).$mount('#popular_products_app');
	} else if($('.popular_products_app').length > 0){
		$('.popular_products_app').each(function () {
			new Vue(basketProductApp).$mount(this);
		});
	}

});