/**
 * Created by dremin_s on 11.04.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
if (window.AB === undefined){
	window.AB = {components: {}, tools: {}};
} else {
	if (!window.AB.hasOwnProperty('components')){
		window.AB.components = {};
	}
	if (!window.AB.hasOwnProperty('tools')){
		window.AB.tools = {};
	}
}