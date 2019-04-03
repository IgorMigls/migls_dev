BX(function () {
	require.config({
		baseUrl: '/local/components/esd/react.form/js/jsx',
		jsx: {
			fileExtension: '.jsx',
			harmony: true,
			stripTypes: true
		},
		paths: {
			"react": "../react-with-addons.min",
			"reactDom": "../react-dom.min",
			"babel": "../babel.min",
			"sweetAlert": '../sweet_alert/sweetalert.min',
			"jsx": "../jsx",
			"text": "../text",
			'AjaxService' : '../AjaxService',
		},

		shim : {
			"react": {
				"exports": "React"
			},
			// "sweetAlert" : {
			// 	'exports': 'sweetAlert'
			// }
		},

		config: {
			babel: {
				sourceMaps: "inline", // One of [false, 'inline', 'both']. See https://babeljs.io/docs/usage/options/
				fileExtension: ".jsx" // Can be set to anything, like .es6 or .js. Defaults to .jsx
			}
		},
		waitSeconds: 0
	});

	// require(['jsx!Form'], function(Form){
	//
	// 	var app = new Form();
	// 	app.init();
	// });

	require(['jsx!ProfileList'], function (ProfileList) {
		var Profile = new ProfileList();
		Profile.init();
	})
});