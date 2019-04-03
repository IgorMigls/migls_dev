webpackJsonp([12],{112:function(e,t){},113:function(e,t,r){"use strict";var n=r(114),a=r.n(n),o=r(25),i=r.n(o),s=r(38),c=r.n(s),u=r(61),d=r(10);t.a={titlePage:function(e,t){e.titlePage=t},setOrders:function(e,t){e.orderList=i()([],t),c.a.forEach(e.orderList,function(t,r){e.maps.orderList[t.ID]=r}),d.D.set("orderList",e.orderList)},updateOrders:function(e,t){e.orderList=i()([],e.orderList,t)},updateDetailOrder:function(e,t){e.detail=i()({},e.detail,t),null!==t.COMPLECTATION&&t.hasOwnProperty("COMPLECTATION")&&t.COMPLECTATION.PRODUCT_DATA?(e.products=t.COMPLECTATION.PRODUCT_DATA.products||{},e.replaces=t.COMPLECTATION.PRODUCT_DATA.replaces||{},e.founded=t.COMPLECTATION.PRODUCT_DATA.founded||{}):(e.products=i()({},t.BASKET),e.replaces={},e.founded={})},setAuth:function(e,t){e.userId=t},pickingUpdate:function(e,t){var r=e.orderList;(c.a.isEmpty(r)||!1===r)&&(r=d.D.get.item("orderList"),this.commit("setOrders",r));var n=e.maps.orderList[t];void 0!==n&&(e.picking[t]=r[n],r.splice(n,1)),this.commit("setOrders",r)},setPicking:function(e,t){e.picking=t},setForDelivery:function(e,t){e.delivery=t},setForMyDelivery:function(e,t){e.myDelivery=t},updateFounded:function(e,t){var r=t.product.BASKET_DATA.ID,n=i()({},t.product.BASKET_DATA),a=i()({},t.product),o=c.a.toNumber(t.count);if(n.SUM=t.sum,n.SUM_FORMAT=u.a.priceFormat(t.sum),n.QUANTITY=o,a.QUANTITY=c.a.toNumber(a.QUANTITY),a.QUANTITY!==o)if(e.founded.hasOwnProperty(r)){var s=t.product.MEASURE_RATIO>0?t.product.MEASURE_RATIO:1;a=e.replaces[r];var d=i()({},a.BASKET_DATA);d.QUANTITY=a.BASKET_DATA.QUANTITY-s,d.SUM=d.QUANTITY*c.a.toNumber(a.BASKET_DATA.PRICE),d.SUM_FORMAT=u.a.priceFormat(d.SUM),d.QUANTITY>0?e.replaces[r]=i()({},a,{BASKET_DATA:d}):delete e.replaces[r],e.founded[r].BASKET_DATA.QUANTITY+=s}else{e.founded[r]=i()({},a,{BASKET_DATA:n});var l=i()({},a.BASKET_DATA);l.QUANTITY=a.QUANTITY-n.QUANTITY,l.SUM=l.QUANTITY*c.a.toNumber(a.BASKET_DATA.PRICE),l.SUM_FORMAT=u.a.priceFormat(l.SUM),e.products[r]=i()({},a,{BASKET_DATA:l})}else delete e.replaces[r],delete e.products[r],e.founded[r]=t.product},updateReplaces:function(e,t){var r=i()({},e.detail);r.BASKET.hasOwnProperty(t.PRODUCT_ID)&&(r.BASKET[t.PRODUCT_ID]=t,e.detail=i()({},r));var n=i()({},e.products[t.PRODUCT_ID]);e.replaces=i()({},e.replaces,a()({},t.PRODUCT_ID,n)),delete e.products[t.PRODUCT_ID]},searchReplaceItems:function(e,t){c.a.isEmpty(t)?e.searchReplaceItems=[]:e.searchReplaceItems=t},deleteProduct:function(e,t){var r=i()({},e.detail);delete r.BASKET[t],e.detail=i()({},r);var n=i()({},e.products);delete n[t],e.products=i()({},n)},updateCurrentProduct:function(e,t){var r=i()({},e.detail),n=i()({},e.replaces),a=t.basketItem.BASKET_DATA.ID,o=e.products;r.PRICE=t.price,r.PRICE_FORMAT=t.priceFormat,r.TOTAL_PRICE_FORMAT=t.priceFormat,e.detail=i()({},r),o.hasOwnProperty(a)&&(n[a]=t.basketItem,n[a].DELETED=o[a],delete o[a],e.products=i()({},o),e.replaces=i()({},n))}}},118:function(e,t,r){"use strict";t.a={orderList:function(e){return e.orderList},products:function(e){return e.products},founded:function(e){return e.founded},replaces:function(e){return e.replaces},detailOrder:function(e){return e.detail},userId:function(e){return e.userId},titlePage:function(e){return e.titlePage},picking:function(e){return e.picking},searchReplaceItems:function(e){return e.searchReplaceItems},deliverOrders:function(e){return e.delivery},myDeliveryOrders:function(e){return e.myDelivery}}},119:function(e,t,r){"use strict";r.d(t,"a",function(){return c});var n=r(120),a=r.n(n),o=r(25),i=r.n(o),s=r(10),c=function(e,t){e.registerModule("rest",{namespaced:!0,state:{preloader:{show:!1,text:"Загрузка..."},status:0,error:!1},mutations:{preloader:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{show:!1,text:"Загрузка..."};e.preloader=i()({},e.preloader,t),e.preloader.show?s.c.show({message:e.preloader.text,customClass:"bg-white",spinnerColor:"primary",messageColor:"blue"}):s.c.hide()},error:function(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];e.error=t,!1!==t&&s.b.create({title:t.title,message:t.message,buttons:[{label:"Закрыть"}]})}},getters:{preloader:function(e){return e.preloader},error:function(e){return e.error}}}),t.hasOwnProperty("http")&&t.http.interceptors.push(function(t,r){e.commit("rest/preloader",{show:!0}),r(function(t){if("object"!==a()(t.body))setTimeout(function(){e.commit("rest/error",{title:"Ошибка",message:"Системная ошибка"})});else if(t.body.hasOwnProperty("ERRORS")&&null!==t.body.ERRORS)if(t.body.ERRORS instanceof Array){var r=[];t.body.ERRORS.forEach(function(e){"object"===(void 0===e?"undefined":a()(e))?r.push(e.msg):r.push(e)}),setTimeout(function(){e.commit("rest/error",{title:"Ошибка",message:r.join(", ")})})}else setTimeout(function(){e.commit("rest/error",{title:"Ошибка",message:"Системная ошибка"})});e.commit("rest/preloader",{show:!1})})})}},133:function(e,t,r){"use strict";function n(e){r(134)}Object.defineProperty(t,"__esModule",{value:!0});var a=r(58),o=r(135),i=r(59),s=n,c=i(a.a,o.a,!1,s,null,null);t.default=c.exports},134:function(e,t){},135:function(e,t,r){"use strict";var n=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{attrs:{id:"q-app"}},[r("q-layout",{ref:"layout",attrs:{view:"lhh LpR fFf","left-class":{"bg-grey-2":!0}}},[r("q-toolbar",{attrs:{slot:"header",color:"primary"},slot:"header"},[r("q-toolbar-title",[e._v(e._s(e.titlePage))])],1),e._v(" "),null!==e.userId?r("div",[r("router-view")],1):e._e(),e._v(" "),r("q-toolbar",{attrs:{slot:"footer"},slot:"footer"},[r("q-tabs",{attrs:{position:"bottom",align:"justify"}},[r("q-route-tab",{attrs:{slot:"title",icon:"home",to:"/",exact:""},slot:"title"}),e._v(" "),r("q-route-tab",{attrs:{slot:"title",icon:"add_shopping_cart",to:"/complect",exact:""},slot:"title"}),e._v(" "),r("q-route-tab",{attrs:{slot:"title",icon:"alarm_on",to:"/delivery",exact:""},slot:"title"}),e._v(" "),r("q-route-tab",{attrs:{slot:"title",icon:"favorite",to:"/myDelivery",exact:""},slot:"title"})],1)],1)],1)],1)},a=[],o={render:n,staticRenderFns:a};t.a=o},40:function(e,t,r){"use strict";e.exports={baseRouterPath:"/",api:"/rest",proxyHost:"http://migls.io"}},58:function(e,t,r){"use strict";var n=r(60),a=r.n(n),o=r(10),i=r(37);t.a={components:{QLayout:o.r,QToolbar:o.B,QToolbarTitle:o.C,QIcon:o.l,QTabs:o.A,QRouteTab:o.w},methods:a()({},Object(i.b)(["isAuth"])),created:function(){this.isAuth()},computed:a()({},Object(i.c)(["userId","titlePage"]))}},61:function(e,t,r){"use strict";t.a={priceFormat:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,r=void 0,n=void 0,a=void 0,o=void 0,i=void 0,s="";return e=(+e||0).toFixed(t),e<0&&(s="-",e=-e),r=parseInt(e,10)+"",n=r.length>3?r.length%3:0,i=n?r.substr(0,n)+" ":"",a=r.substr(n).replace(/(\d{3})(?=\d)/g,"$1 "),o=t?"."+Math.abs(e-r).toFixed(t).replace(/-/,"0").slice(2):"",s+i+a+o},dd:function(){throw console.info(arguments),new Error("exit")}}},62:function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=r(16),a=r(10),o=r(68),i=r(79),s=(r.n(i),r(81)),c=(r.n(s),r(83)),u=r(119);r(63),n.a.config.productionTip=!1,n.a.use(a.E),r(77),Object(u.a)(c.a,n.a),a.E.start(function(){new n.a({el:"#q-app",router:o.a,store:c.a,render:function(e){return e(r(133).default)}})})},63:function(e,t){},68:function(e,t,r){"use strict";function n(e){return function(){return r(70)("./"+e+".vue")}}var a=r(16),o=r(69),i=r(40),s=r.n(i);a.a.use(o.a),s.a.baseRouterPath="/delivery/admin/",t.a=new o.a({base:s.a.baseRouterPath,mode:"history",scrollBehavior:function(){return{y:0}},routes:[{path:"/",component:n("NewOrders")},{path:"/order/:id",component:n("OrderView")},{path:"/complect",component:n("Complectation"),name:"ComplectationList"},{path:"/complect/:id",component:n("OrderDetail"),name:"ComplectationDetail"},{path:"/delivery",component:n("ForDelivery")},{path:"/delivery/:id",component:n("OrderView"),name:"deliveryDetail"},{path:"/myDelivery",component:n("myDelivery")},{path:"/myDelivery/:id",component:n("OrderView"),name:"myDeliveryDetail"},{path:"*",component:n("Error404")}]})},70:function(e,t,r){function n(e){var t=a[e];return t?r.e(t[1]).then(function(){return r(t[0])}):Promise.reject(new Error("Cannot find module '"+e+"'."))}var a={"./Complectation.vue":[141,10],"./Error404.vue":[142,5],"./ForDelivery.vue":[143,9],"./Hello.vue":[144,1],"./NewOrders.vue":[145,8],"./OrderDetail.vue":[146,0],"./OrderInfo.vue":[139,7],"./OrderView.vue":[147,4],"./ProductDetail.vue":[138,3],"./ProductList.vue":[140,6],"./myDelivery.vue":[148,2]};n.keys=function(){return Object.keys(a)},n.id=70,e.exports=n},78:function(e,t){},80:function(e,t){},82:function(e,t){},83:function(e,t,r){"use strict";r.d(t,"a",function(){return c});var n=r(16),a=r(37),o=r(84),i=r(113),s=r(118);n.a.use(a.a);var c=new a.a.Store({state:{titlePage:"",orderList:[],picking:[],delivery:[],products:{},founded:{},replaces:{},detail:!1,userId:null,maps:{orderList:{},picking:{},delivery:{}},searchReplaceItems:[],myDelivery:[]},getters:s.a,mutations:i.a,actions:o.a})},84:function(e,t,r){"use strict";var n=r(85),a=r.n(n),o=r(88),i=r.n(o),s=r(16),c=r(111),u=r(38),d=r.n(u),l=r(10),p=r(40),m=r.n(p);s.a.use(c.a),m.a.api="/rest2/delivery/v1";var f=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";return m.a.api+e},v={isAuth:{method:"POST",url:f("/isAuth")},login:{method:"POST",url:f("/login")},getNewOrders:{method:"GET",url:f("/getNewOrders")},getDetailOrder:{method:"GET",url:f("/getDetail")},lockOrder:{method:"POST",url:f("/lockOrder")},searchReplaceItems:{method:"POST",url:f("/searchReplaceItems")},cancelOrder:{method:"POST",url:f("/cancelOrder")},addReplaceToBasket:{method:"POST",url:f("/addReplaceToBasket")},deleteProduct:{method:"POST",url:f("/deleteProduct")},addToDelivery:{method:"POST",url:f("/sendForDelivery")},saveComplectionOrder:{method:"POST",url:f("/saveComplectationOrder")},copyProduct:{method:"POST",url:f("/copyProduct")},setMyDelivery:{method:"POST",url:f("/addToMyOrders")},abortMyDelivery:{method:"POST",url:f("/abortDelivery")},returnToBasketList:{method:"POST",url:f("/returnToBasketList")},updateQuantityFinal:{method:"POST",url:f("/updateQuantityFinal")},deleteProductFinal:{method:"POST",url:f("/deleteProductFinal")}},T=s.a.resource("",{},v);t.a={getNewOrders:function(e){var t=this,r=e.commit,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return i()(a.a.mark(function e(){var o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.getNewOrders(n);case 2:o=e.sent,null===o.data.ERRORS&&r("setOrders",o.data.DATA);case 4:case"end":return e.stop()}},e,t)}))()},getDetailOrder:function(e,t){var r=this,n=e.commit;return i()(a.a.mark(function e(){var o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.getDetailOrder(t);case 2:o=e.sent,null===o.data.ERRORS&&n("updateDetailOrder",o.data.DATA);case 4:case"end":return e.stop()}},e,r)}))()},login:function(e,t){var r=this,n=(e.commit,e.dispatch);return i()(a.a.mark(function e(){var o,i;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.login(t);case 2:o=e.sent,i=!1,null===o.data.DATA&&(i="Доступ запрещен. Возможно неверный логин или пароль"),n("isAuth",i);case 6:case"end":return e.stop()}},e,r)}))()},isAuth:function(e){var t=this,r=e.commit,n=e.dispatch,o=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return i()(a.a.mark(function e(){var i;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.isAuth();case 2:i=e.sent,null!==i.data.DATA?r("setAuth",i.data.DATA):l.b.create({title:"Авторизация",message:o||"",form:{email:{type:"email",label:"E-mail",model:""},pass:{type:"password",label:"Пароль",model:"",min:6}},buttons:[{label:"Ok",handler:function(e){n("login",e)}}]});case 4:case"end":return e.stop()}},e,t)}))()},lockOrder:function(e,t){var r=this,n=e.commit;e.dispatch;return i()(a.a.mark(function e(){var o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.lockOrder({id:t});case 2:return o=e.sent,null===o.data.ERRORS&&n("pickingUpdate",t),e.abrupt("return",o);case 5:case"end":return e.stop()}},e,r)}))()},getPicking:function(e){var t=this,r=e.commit;return i()(a.a.mark(function e(){var n;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.getNewOrders({type:"complect"});case 2:n=e.sent,null===n.data.ERRORS&&r("setPicking",n.data.DATA);case 4:case"end":return e.stop()}},e,t)}))()},getForDelivery:function(e){var t=this,r=e.commit;return i()(a.a.mark(function e(){var n;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.getNewOrders({type:"delivery"});case 2:n=e.sent,null===n.data.ERRORS&&r("setForDelivery",n.data.DATA);case 4:case"end":return e.stop()}},e,t)}))()},updateFounded:function(e,t){var r=(e.commit,e.state,e.dispatch),n=d.a.toNumber(t.product.BASKET_DATA.QUANTITY),a={orderId:t.product.BASKET_DATA.ORDER_ID,count:t.count,storeQuantity:n,basketId:t.product.BASKET_DATA.ID,basketCustomId:t.product.ID};r("saveComplectionOrder",a).then(function(e){null===e.data.ERRORS&&r("getDetailOrder",{id:a.orderId})})},updateReplaces:function(e,t){(0,e.commit)("updateReplaces",t);t.product.BASKET_DATA.ORDER_ID,state.products,state.founded,state.replaces},fetchReplaces:function(e,t){var r=this,n=e.commit,o=e.dispatch;return i()(a.a.mark(function e(){var i;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.searchReplaceItems(t);case 2:i=e.sent,null===i.data.ERRORS&&(n("searchReplaceItems",i.data.DATA),o("getDetailOrder",{id:t.orderId}));case 4:case"end":return e.stop()}},e,r)}))()},cancelOrder:function(e,t){var r=this,n=(e.commit,e.dispatch);return i()(a.a.mark(function e(){var o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.cancelOrder({id:t});case 2:o=e.sent,null===o.data.ERRORS&&n("getPicking");case 4:case"end":return e.stop()}},e,r)}))()},addToDelivery:function(e,t){var r=this;e.dispatch;return i()(a.a.mark(function e(){var n;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.addToDelivery({id:t});case 2:return n=e.sent,n.ERRORS,e.abrupt("return",n);case 5:case"end":return e.stop()}},e,r)}))()},addReplaceToBasket:function(e,t){var r=this,n=e.commit,o=e.dispatch;return i()(a.a.mark(function e(){var i,s;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return i={replace:t.replace,basketItem:t.basketItem},e.next=3,T.addReplaceToBasket(i);case 3:return s=e.sent,null===s.data.ERRORS&&(n("updateCurrentProduct",s.data.DATA),o("getDetailOrder",{id:t.basketItem.BASKET_DATA.ORDER_ID})),e.abrupt("return",s);case 6:case"end":return e.stop()}},e,r)}))()},deleteProduct:function(e,t){var r=this,n=(e.commit,e.dispatch);e.state;return i()(a.a.mark(function e(){var o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.deleteProduct({item:t.item,orderId:t.orderId});case 2:o=e.sent,null===o.data.ERRORS&&n("getDetailOrder",{id:t.orderId,name:"ComplectationDetail"});case 4:case"end":return e.stop()}},e,r)}))()},saveComplectionOrder:function(e,t){var r=this;e.commit,e.state;return i()(a.a.mark(function e(){var n;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.saveComplectionOrder(t);case 2:return n=e.sent,n.data.ERRORS,e.abrupt("return",n);case 5:case"end":return e.stop()}},e,r)}))()},copyProduct:function(e,t){var r=this;e.commit,e.state;return i()(a.a.mark(function e(){var n,o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return n={count:t.count,basketId:t.basketItem.BASKET_ID,orderId:t.basketItem.BASKET_DATA.ORDER_ID},e.next=3,T.copyProduct(n);case 3:o=e.sent;case 4:case"end":return e.stop()}},e,r)}))()},getMyDelivery:function(e,t){var r=this,n=e.commit;e.state;return i()(a.a.mark(function e(){var t;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.getNewOrders({type:"myDelivery"});case 2:return t=e.sent,null===t.data.ERRORS&&n("setForMyDelivery",t.data.DATA),e.abrupt("return",t);case 5:case"end":return e.stop()}},e,r)}))()},setMyDelivery:function(e,t){var r=this;e.commit,e.dispatch;return i()(a.a.mark(function e(){var n;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.setMyDelivery({id:t});case 2:return n=e.sent,n.ERRORS,e.abrupt("return",n);case 5:case"end":return e.stop()}},e,r)}))()},abortMyDelivery:function(e,t){var r=this;e.commit,e.dispatch;return i()(a.a.mark(function e(){var n;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.abortMyDelivery({id:t});case 2:return n=e.sent,n.ERRORS,e.abrupt("return",n);case 5:case"end":return e.stop()}},e,r)}))()},returnToBasket:function(e,t){var r=this,n=e.dispatch;return i()(a.a.mark(function e(){var o;return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.returnToBasketList({basketId:t.BASKET_ID,orderId:t.BASKET_DATA.ORDER_ID});case 2:return o=e.sent,null===o.data.ERRORS&&n("getDetailOrder",{id:t.BASKET_DATA.ORDER_ID}),e.abrupt("return",o);case 5:case"end":return e.stop()}},e,r)}))()},updateQuantityFinal:function(e,t){var r=this;e.commit,e.dispatch;return i()(a.a.mark(function e(){return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.updateQuantityFinal({orderId:t.BASKET_DATA.ORDER_ID,basketId:t.BASKET_DATA.ID,quantity:t.BASKET_DATA.QUANTITY});case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}},e,r)}))()},deleteProductFinal:function(e,t){var r=this;e.dispatch;return i()(a.a.mark(function e(){return a.a.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,T.deleteProductFinal({basketId:t.BASKET_DATA.ID,orderId:t.BASKET_DATA.ORDER_ID});case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}},e,r)}))()}}}},[62]);