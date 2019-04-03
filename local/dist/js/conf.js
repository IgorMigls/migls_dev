require.config({
	baseUrl: '/local/dist/js/',
	jsx: {
		fileExtension: '.jsx',
		harmony: true,
		stripTypes: true
	},
	paths: {
		// "react": "../../../libs/react/react-with-addons.min",
		"react": "/local/dist/libs/react/react.min",
		"dom": "/local/dist/libs/react/react-dom.min",
		"babel": "/local/dist/libs/react/babel.min",
		"jsx": "/local/dist/js/jsx",
		"text": "/local/dist/js/text",
		"sweetalert": "/local/dist/libs/js/sweetalert",
		"AjaxService": "/local/dist/js/ajaxService"
	},

	shim: {
		"react": {
			"exports": "React"
		},
	},

	config: {
		babel: {
			sourceMaps: "inline", // One of [false, 'inline', 'both']. See https://babeljs.io/docs/usage/options/
			fileExtension: ".jsx" // Can be set to anything, like .es6 or .js. Defaults to .jsx
		}
	},
	waitSeconds: 0
});