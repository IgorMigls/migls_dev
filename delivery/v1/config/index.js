var path = require('path')
var Env = require('../env');
module.exports = {
	// Webpack aliases
	aliases: {
		quasar: path.resolve(__dirname, '../node_modules/quasar-framework/'),
		src: path.resolve(__dirname, '../src'),
		assets: path.resolve(__dirname, '../src/assets'),
		'@': path.resolve(__dirname, '../src/components'),
		variables: path.resolve(__dirname, '../src/themes/quasar.variables.styl'),
		vue: path.resolve(__dirname, '../node_modules/vue/dist/vue.esm.js'),
		VueResource: path.resolve(__dirname, '../node_modules/vue-resource/dist/vue-resource.esm.js'),
		Lo: path.resolve(__dirname, '../node_modules/lodash/core.min.js'),
	},

	// Progress Bar Webpack plugin format
	// https://github.com/clessg/progress-bar-webpack-plugin#options
	progressFormat: ' [:bar] ' + ':percent'.bold + ' (:msg)',

	// Default theme to build with ('ios' or 'mat')
	defaultTheme: 'mat',

	build: {
		env: require('./prod.env'),
		publicPath: '/delivery/admin/',
		productionSourceMap: false,

		// Remove unused CSS
		// Disable it if it has side-effects for your specific app
		purifyCSS: true
	},
	dev: {
		env: require('./dev.env'),
		cssSourceMap: true,
		// auto open browser or not
		openBrowser: true,
		publicPath: '/',
		port: 8080,

		// If for example you are using Quasar Play
		// to generate a QR code then on each dev (re)compilation
		// you need to avoid clearing out the console, so set this
		// to "false", otherwise you can set it to "true" to always
		// have only the messages regarding your last (re)compilation.
		clearConsoleOnRebuild: false,

		// Proxy your API if using any.
		// Also see /build/script.dev.js and search for "proxy api requests"
		// https://github.com/chimurai/http-proxy-middleware
		proxyTable: {
			'/rest': {
				// target: 'http://migls.io/rest2/delivery/v1',
				target: Env.proxyHost + '/rest2/delivery/v1',
				changeOrigin: true,
				pathRewrite: {
					'^/rest': ''
				}
			},
			'/upload': {
				// target: 'http://migls.io/upload',
				target: Env.proxyHost + '/upload',
				changeOrigin: true,
				pathRewrite: {
					'^/upload': ''
				}
			}
		}
	}
}

/*
 * proxyTable example:
 *
   proxyTable: {
      // proxy all requests starting with /api
      '/api': {
        target: 'https://some.address.com/api',
        changeOrigin: true,
        pathRewrite: {
          '^/api': ''
        }
      }
    }
 */
