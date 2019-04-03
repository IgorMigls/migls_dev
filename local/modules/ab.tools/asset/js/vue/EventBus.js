/**
 * Created by dremin_s on 23.06.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

const VueBus = new Vue();

const EventBus = {
	Vue: VueBus,

	getBus(){
		return EventBus.Vue;
	},

	getEventList(){
		return EventBus.Vue._events;
	},

	$on(name, callback){
		EventBus.Vue.$on(name, callback);
	},
	$emit(name, data){
		EventBus.Vue.$on(name, data);
	}
};

if(window.EventBus === undefined){
	window.EventBus = EventBus;
}

export default window.EventBus;