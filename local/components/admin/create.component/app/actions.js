/**
 * Created by dremin_s on 23.10.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
const url = (action) => {
	return '/rest2/component_creator' + action;
};
export default {
	getNamespace: {method: 'GET', url: url('/getNameSpaces{data}')},
	createComponent: {method: 'POST', url: url('/createComponent')}
};