!function(t){function e(r){if(o[r])return o[r].exports;var s=o[r]={i:r,l:!1,exports:{}};return t[r].call(s.exports,s,s.exports,e),s.l=!0,s.exports}var o={};e.m=t,e.c=o,e.d=function(t,o,r){e.o(t,o)||Object.defineProperty(t,o,{configurable:!1,enumerable:!0,get:r})},e.n=function(t){var o=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(o,"a",o),o},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="/home/bitrix/ext_www/ul.profitweb.biz/",e(e.s="8fe4")}({"8fe4":function(t,e,o){"use strict";var r=o("zeCC"),s=function(t){return t&&t.__esModule?t:{default:t}}(r);window.hasOwnProperty("MigBus")||(window.MigBus=new Vue);var n={data:{tabActive:"tab-0"},methods:{openShop:function(t){var e=t.replace("#",""),o=$(this.$el);o.find(".b-tabs-content").hide(0),o.find(t).show(0),this.tabActive=e}},components:{productItem:s.default},mounted:function(){var t=$(".js-products-slider"),e={dots:!1,arrows:!0,slide:".js-products-slider-item",slidesToShow:5,slidesToScroll:1};t.length&&t.slick(e);var o=$(".js-products-slider-search");o.length>0&&o.slick({dots:!1,arrows:!0,slide:".js-products-slider-item",slidesToShow:4,slidesToScroll:4})}};$(function(){$("#popular_products_app").length>0?new Vue(n).$mount("#popular_products_app"):$(".popular_products_app").length>0&&$(".popular_products_app").each(function(){new Vue(n).$mount(this)})})},CJfO:function(t,e,o){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:{product:{type:Object,required:!0}},data:function(){return{quantity:1}},beforeUpdate:function(){},created:function(){this.product.BASKET_QUANTITY&&(this.quantity=_.toInteger(this.product.BASKET_QUANTITY))},methods:{plus:function(){this.quantity++},minus:function(){this.quantity>1&&this.quantity--},addToBasket:function(){window.MigBus.$emit("addToBasket",{quantity:this.quantity,product:this.product,sku:this.product.ID})},watchQuantity:function(t){var e=_.toInteger(t.target.value);0===t.target.value.length?e="":0==e&&(e=1),e>0&&(this.quantity=e)}},components:{},computed:{}}},"VU/8":function(t,e){t.exports=function(t,e,o,r){var s,n=t=t||{},i=typeof t.default;"object"!==i&&"function"!==i||(s=t,n=t.default);var u="function"==typeof n?n.options:n;if(e&&(u.render=e.render,u.staticRenderFns=e.staticRenderFns),o&&(u._scopeId=o),r){var c=Object.create(u.computed||null);Object.keys(r).forEach(function(t){var e=r[t];c[t]=function(){return e}}),u.computed=c}return{esModule:s,exports:n,options:u}}},zeCC:function(t,e,o){var r=o("VU/8")(o("CJfO"),o("zsQ/"),null,null);r.options.__file="/home/bitrix/ext_www/ul.profitweb.biz/local/resource/js/product/productItem.vue",r.esModule&&Object.keys(r.esModule).some(function(t){return"default"!==t&&"__esModule"!==t})&&console.error("named exports are not supported in *.vue files."),r.options.functional&&console.error("[vue-loader] productItem.vue: functional components are not supported with templates, they should use render functions."),t.exports=r.exports},"zsQ/":function(t,e,o){t.exports={render:function(){var t=this,e=t.$createElement,o=t._self._c||e;return t.product?o("div",{staticClass:"b-products-slider__item js-products-slider-item"},[o("div",{class:["b-product-preview b-ib-wrapper",{"b-product-preview_actvie":t.product.BASKET_QUANTITY}]},[o("div",{staticClass:"b-product__count b-ib"},[o("span",{staticClass:"b-count__in"},[t._v(t._s(t.product.BASKET_QUANTITY)+" шт. в корзине")])]),t._v(" "),t.product.PRODUCT_PICTURE?o("div",{staticClass:"b-product-preview__pic b-ib"},[o("a",{attrs:{href:"#/catalog/"+t.product.PRODUCT_ID}},[o("img",{attrs:{src:t.product.PRODUCT_PICTURE.src}})])]):t._e(),t._v(" "),o("div",{staticClass:"b-product-preview__name b-ib"},[o("a",{attrs:{href:"#/catalog/"+t.product.PRODUCT_ID}},[t._v("\n\t\t\t\t"+t._s(t.product.PRODUCT_NAME)+"\n\t\t\t")])]),t._v(" "),o("div",{staticClass:"b-product-preview__price b-ib"},[t._v(t._s(t.product.PRICE_FORMAT)+" "),o("span",{staticClass:"b-rouble"},[t._v("₽")])]),t._v(" "),o("div",{staticClass:"b-product-preview__buy b-ib"},[o("form",{staticClass:"index_products_basket",on:{submit:function(t){t.preventDefault()}}},[o("div",{staticClass:"b-product-preview__incart b-ib"},[o("button",{staticClass:"b-button b-button_green add_basket_btn",attrs:{type:"button"},on:{click:t.addToBasket}},[t._v("В корзину")])])])])])]):t._e()},staticRenderFns:[]},t.exports.render._withStripped=!0}});
//# sourceMappingURL=globalApp.js.map