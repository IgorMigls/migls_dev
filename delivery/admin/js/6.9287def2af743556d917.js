webpackJsonp([6],{140:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=i(152),a=i(157),r=i(59),c=r(n.a,a.a,!1,null,null,null);e.default=c.exports},152:function(t,e,i){"use strict";var n=i(10);e.a={name:"product-list",props:{product:{type:Object,require:!0},deleteAction:{type:Boolean,default:function(){return!0}},showBackBtn:{type:Boolean}},data:function(){return{}},methods:{openDetail:function(){this.$emit("open-detail-product",this.item)},deleteProduct:function(){this.$emit("delete-product",this.item)},returnToBasketList:function(){this.$emit("return-to-basket",this.item)}},watch:{},computed:{item:function(){return this.product}},created:function(){},mounted:function(){},components:{QCard:n.e,QCardTitle:n.j,QCardSeparator:n.i,QCardMain:n.g,QCardActions:n.f,QBtn:n.d,QIcon:n.l,QCardMedia:n.h,QModal:n.u}}},157:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.item?i("q-card",[i("div",{staticClass:"row"},[i("div",{staticClass:"item_product col-4"},[t.item.RESIZE?i("q-card-media",{staticClass:"item_product_img",style:{"background-image":"url("+t.item.RESIZE.src+")"}}):t._e()],1),t._v(" "),i("div",{staticClass:"item_product_body col-8"},[i("q-card-title",[t._v(t._s(t.item.NAME))]),t._v(" "),i("q-card-main",[i("p",[t._v("Цена: "+t._s(t.item.BASKET_DATA.PRICE_FORMAT)+"р")]),t._v(" "),i("p",[t._v("Количество: "+t._s(t.item.BASKET_DATA.QUANTITY)+" "+t._s(t.item.MEASURE_SHORT_NAME))]),t._v(" "),i("p",[t._v("Сумма: "+t._s(t.item.BASKET_DATA.SUM_FORMAT)+"р")])])],1)]),t._v(" "),i("q-card-separator"),t._v(" "),i("q-card-actions",[i("q-btn",{attrs:{flat:""},on:{click:function(e){t.openDetail()}}},[t._v("Подробнее")]),t._v(" "),t.deleteAction?i("q-btn",{attrs:{flat:""},on:{click:function(e){t.deleteProduct()}}},[t._v("Удалить")]):t._e(),t._v(" "),t.showBackBtn?i("q-btn",{attrs:{flat:"",icon:"replay",color:"primary"},on:{click:t.returnToBasketList}},[t._v("Вернуть в список покупок")]):t._e()],1)],1):t._e()},a=[],r={render:n,staticRenderFns:a};e.a=r}});