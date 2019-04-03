/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _jsxFileName = '/home/bitrix/ext_www/ul.profitweb.biz/local/components/ul/help.feedback/app/app.js';

	__webpack_require__(1);

	var _redux = __webpack_require__(3);

	var _reactRedux = __webpack_require__(18);

	var _controller = __webpack_require__(57);

	var _Form = __webpack_require__(133);

	var _Form2 = _interopRequireDefault(_Form);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	var store = (0, _redux.createStore)(_controller.rootReducer);

	$(function () {
		ReactDOM.render(React.createElement(
			_reactRedux.Provider,
			{ store: store, __source: {
					fileName: _jsxFileName,
					lineNumber: 11
				},
				__self: this
			},
			React.createElement(_Form2.default, {
				__source: {
					fileName: _jsxFileName,
					lineNumber: 12
				},
				__self: this
			})
		), BX('help_form_hover'));
	});

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/* WEBPACK VAR INJECTION */(function(global, process) { /*!
	  * https://github.com/paulmillr/es6-shim
	  * @license es6-shim Copyright 2013-2016 by Paul Miller (http://paulmillr.com)
	  *   and contributors,  MIT License
	  * es6-shim: v0.35.1
	  * see https://github.com/paulmillr/es6-shim/blob/0.35.1/LICENSE
	  * Details and documentation:
	  * https://github.com/paulmillr/es6-shim/
	  */

	// UMD (Universal Module Definition)
	// see https://github.com/umdjs/umd/blob/master/returnExports.js
	(function (root, factory) {
	  /*global define, module, exports */
	  if (true) {
	    // AMD. Register as an anonymous module.
	    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory), __WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ? (__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) : __WEBPACK_AMD_DEFINE_FACTORY__), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	  } else if (typeof exports === 'object') {
	    // Node. Does not work with strict CommonJS, but
	    // only CommonJS-like environments that support module.exports,
	    // like Node.
	    module.exports = factory();
	  } else {
	    // Browser globals (root is window)
	    root.returnExports = factory();
	  }
	}(this, function () {
	  'use strict';

	  var _apply = Function.call.bind(Function.apply);
	  var _call = Function.call.bind(Function.call);
	  var isArray = Array.isArray;
	  var keys = Object.keys;

	  var not = function notThunker(func) {
	    return function notThunk() {
	      return !_apply(func, this, arguments);
	    };
	  };
	  var throwsError = function (func) {
	    try {
	      func();
	      return false;
	    } catch (e) {
	      return true;
	    }
	  };
	  var valueOrFalseIfThrows = function valueOrFalseIfThrows(func) {
	    try {
	      return func();
	    } catch (e) {
	      return false;
	    }
	  };

	  var isCallableWithoutNew = not(throwsError);
	  var arePropertyDescriptorsSupported = function () {
	    // if Object.defineProperty exists but throws, it's IE 8
	    return !throwsError(function () {
	      Object.defineProperty({}, 'x', { get: function () {} });
	    });
	  };
	  var supportsDescriptors = !!Object.defineProperty && arePropertyDescriptorsSupported();
	  var functionsHaveNames = (function foo() {}).name === 'foo'; // eslint-disable-line no-extra-parens

	  var _forEach = Function.call.bind(Array.prototype.forEach);
	  var _reduce = Function.call.bind(Array.prototype.reduce);
	  var _filter = Function.call.bind(Array.prototype.filter);
	  var _some = Function.call.bind(Array.prototype.some);

	  var defineProperty = function (object, name, value, force) {
	    if (!force && name in object) { return; }
	    if (supportsDescriptors) {
	      Object.defineProperty(object, name, {
	        configurable: true,
	        enumerable: false,
	        writable: true,
	        value: value
	      });
	    } else {
	      object[name] = value;
	    }
	  };

	  // Define configurable, writable and non-enumerable props
	  // if they don’t exist.
	  var defineProperties = function (object, map, forceOverride) {
	    _forEach(keys(map), function (name) {
	      var method = map[name];
	      defineProperty(object, name, method, !!forceOverride);
	    });
	  };

	  var _toString = Function.call.bind(Object.prototype.toString);
	  var isCallable =  false ? function IsCallableSlow(x) {
	    // Some old browsers (IE, FF) say that typeof /abc/ === 'function'
	    return typeof x === 'function' && _toString(x) === '[object Function]';
	  } : function IsCallableFast(x) { return typeof x === 'function'; };

	  var Value = {
	    getter: function (object, name, getter) {
	      if (!supportsDescriptors) {
	        throw new TypeError('getters require true ES5 support');
	      }
	      Object.defineProperty(object, name, {
	        configurable: true,
	        enumerable: false,
	        get: getter
	      });
	    },
	    proxy: function (originalObject, key, targetObject) {
	      if (!supportsDescriptors) {
	        throw new TypeError('getters require true ES5 support');
	      }
	      var originalDescriptor = Object.getOwnPropertyDescriptor(originalObject, key);
	      Object.defineProperty(targetObject, key, {
	        configurable: originalDescriptor.configurable,
	        enumerable: originalDescriptor.enumerable,
	        get: function getKey() { return originalObject[key]; },
	        set: function setKey(value) { originalObject[key] = value; }
	      });
	    },
	    redefine: function (object, property, newValue) {
	      if (supportsDescriptors) {
	        var descriptor = Object.getOwnPropertyDescriptor(object, property);
	        descriptor.value = newValue;
	        Object.defineProperty(object, property, descriptor);
	      } else {
	        object[property] = newValue;
	      }
	    },
	    defineByDescriptor: function (object, property, descriptor) {
	      if (supportsDescriptors) {
	        Object.defineProperty(object, property, descriptor);
	      } else if ('value' in descriptor) {
	        object[property] = descriptor.value;
	      }
	    },
	    preserveToString: function (target, source) {
	      if (source && isCallable(source.toString)) {
	        defineProperty(target, 'toString', source.toString.bind(source), true);
	      }
	    }
	  };

	  // Simple shim for Object.create on ES3 browsers
	  // (unlike real shim, no attempt to support `prototype === null`)
	  var create = Object.create || function (prototype, properties) {
	    var Prototype = function Prototype() {};
	    Prototype.prototype = prototype;
	    var object = new Prototype();
	    if (typeof properties !== 'undefined') {
	      keys(properties).forEach(function (key) {
	        Value.defineByDescriptor(object, key, properties[key]);
	      });
	    }
	    return object;
	  };

	  var supportsSubclassing = function (C, f) {
	    if (!Object.setPrototypeOf) { return false; /* skip test on IE < 11 */ }
	    return valueOrFalseIfThrows(function () {
	      var Sub = function Subclass(arg) {
	        var o = new C(arg);
	        Object.setPrototypeOf(o, Subclass.prototype);
	        return o;
	      };
	      Object.setPrototypeOf(Sub, C);
	      Sub.prototype = create(C.prototype, {
	        constructor: { value: Sub }
	      });
	      return f(Sub);
	    });
	  };

	  var getGlobal = function () {
	    /* global self, window, global */
	    // the only reliable means to get the global object is
	    // `Function('return this')()`
	    // However, this causes CSP violations in Chrome apps.
	    if (typeof self !== 'undefined') { return self; }
	    if (typeof window !== 'undefined') { return window; }
	    if (typeof global !== 'undefined') { return global; }
	    throw new Error('unable to locate global object');
	  };

	  var globals = getGlobal();
	  var globalIsFinite = globals.isFinite;
	  var _indexOf = Function.call.bind(String.prototype.indexOf);
	  var _arrayIndexOfApply = Function.apply.bind(Array.prototype.indexOf);
	  var _concat = Function.call.bind(Array.prototype.concat);
	  // var _sort = Function.call.bind(Array.prototype.sort);
	  var _strSlice = Function.call.bind(String.prototype.slice);
	  var _push = Function.call.bind(Array.prototype.push);
	  var _pushApply = Function.apply.bind(Array.prototype.push);
	  var _shift = Function.call.bind(Array.prototype.shift);
	  var _max = Math.max;
	  var _min = Math.min;
	  var _floor = Math.floor;
	  var _abs = Math.abs;
	  var _exp = Math.exp;
	  var _log = Math.log;
	  var _sqrt = Math.sqrt;
	  var _hasOwnProperty = Function.call.bind(Object.prototype.hasOwnProperty);
	  var ArrayIterator; // make our implementation private
	  var noop = function () {};

	  var OrigMap = globals.Map;
	  var origMapDelete = OrigMap && OrigMap.prototype['delete'];
	  var origMapGet = OrigMap && OrigMap.prototype.get;
	  var origMapHas = OrigMap && OrigMap.prototype.has;
	  var origMapSet = OrigMap && OrigMap.prototype.set;

	  var Symbol = globals.Symbol || {};
	  var symbolSpecies = Symbol.species || '@@species';

	  var numberIsNaN = Number.isNaN || function isNaN(value) {
	    // NaN !== NaN, but they are identical.
	    // NaNs are the only non-reflexive value, i.e., if x !== x,
	    // then x is NaN.
	    // isNaN is broken: it converts its argument to number, so
	    // isNaN('foo') => true
	    return value !== value;
	  };
	  var numberIsFinite = Number.isFinite || function isFinite(value) {
	    return typeof value === 'number' && globalIsFinite(value);
	  };
	  var _sign = isCallable(Math.sign) ? Math.sign : function sign(value) {
	    var number = Number(value);
	    if (number === 0) { return number; }
	    if (numberIsNaN(number)) { return number; }
	    return number < 0 ? -1 : 1;
	  };

	  // taken directly from https://github.com/ljharb/is-arguments/blob/master/index.js
	  // can be replaced with require('is-arguments') if we ever use a build process instead
	  var isStandardArguments = function isArguments(value) {
	    return _toString(value) === '[object Arguments]';
	  };
	  var isLegacyArguments = function isArguments(value) {
	    return value !== null &&
	      typeof value === 'object' &&
	      typeof value.length === 'number' &&
	      value.length >= 0 &&
	      _toString(value) !== '[object Array]' &&
	      _toString(value.callee) === '[object Function]';
	  };
	  var isArguments = isStandardArguments(arguments) ? isStandardArguments : isLegacyArguments;

	  var Type = {
	    primitive: function (x) { return x === null || (typeof x !== 'function' && typeof x !== 'object'); },
	    string: function (x) { return _toString(x) === '[object String]'; },
	    regex: function (x) { return _toString(x) === '[object RegExp]'; },
	    symbol: function (x) {
	      return typeof globals.Symbol === 'function' && typeof x === 'symbol';
	    }
	  };

	  var overrideNative = function overrideNative(object, property, replacement) {
	    var original = object[property];
	    defineProperty(object, property, replacement, true);
	    Value.preserveToString(object[property], original);
	  };

	  // eslint-disable-next-line no-restricted-properties
	  var hasSymbols = typeof Symbol === 'function' && typeof Symbol['for'] === 'function' && Type.symbol(Symbol());

	  // This is a private name in the es6 spec, equal to '[Symbol.iterator]'
	  // we're going to use an arbitrary _-prefixed name to make our shims
	  // work properly with each other, even though we don't have full Iterator
	  // support.  That is, `Array.from(map.keys())` will work, but we don't
	  // pretend to export a "real" Iterator interface.
	  var $iterator$ = Type.symbol(Symbol.iterator) ? Symbol.iterator : '_es6-shim iterator_';
	  // Firefox ships a partial implementation using the name @@iterator.
	  // https://bugzilla.mozilla.org/show_bug.cgi?id=907077#c14
	  // So use that name if we detect it.
	  if (globals.Set && typeof new globals.Set()['@@iterator'] === 'function') {
	    $iterator$ = '@@iterator';
	  }

	  // Reflect
	  if (!globals.Reflect) {
	    defineProperty(globals, 'Reflect', {}, true);
	  }
	  var Reflect = globals.Reflect;

	  var $String = String;

	  /* global document */
	  var domAll = (typeof document === 'undefined' || !document) ? null : document.all;
	  /* jshint eqnull:true */
	  var isNullOrUndefined = domAll == null ? function isNullOrUndefined(x) {
	    /* jshint eqnull:true */
	    return x == null;
	  } : function isNullOrUndefinedAndNotDocumentAll(x) {
	    /* jshint eqnull:true */
	    return x == null && x !== domAll;
	  };

	  var ES = {
	    // http://www.ecma-international.org/ecma-262/6.0/#sec-call
	    Call: function Call(F, V) {
	      var args = arguments.length > 2 ? arguments[2] : [];
	      if (!ES.IsCallable(F)) {
	        throw new TypeError(F + ' is not a function');
	      }
	      return _apply(F, V, args);
	    },

	    RequireObjectCoercible: function (x, optMessage) {
	      if (isNullOrUndefined(x)) {
	        throw new TypeError(optMessage || 'Cannot call method on ' + x);
	      }
	      return x;
	    },

	    // This might miss the "(non-standard exotic and does not implement
	    // [[Call]])" case from
	    // http://www.ecma-international.org/ecma-262/6.0/#sec-typeof-operator-runtime-semantics-evaluation
	    // but we can't find any evidence these objects exist in practice.
	    // If we find some in the future, you could test `Object(x) === x`,
	    // which is reliable according to
	    // http://www.ecma-international.org/ecma-262/6.0/#sec-toobject
	    // but is not well optimized by runtimes and creates an object
	    // whenever it returns false, and thus is very slow.
	    TypeIsObject: function (x) {
	      if (x === void 0 || x === null || x === true || x === false) {
	        return false;
	      }
	      return typeof x === 'function' || typeof x === 'object' || x === domAll;
	    },

	    ToObject: function (o, optMessage) {
	      return Object(ES.RequireObjectCoercible(o, optMessage));
	    },

	    IsCallable: isCallable,

	    IsConstructor: function (x) {
	      // We can't tell callables from constructors in ES5
	      return ES.IsCallable(x);
	    },

	    ToInt32: function (x) {
	      return ES.ToNumber(x) >> 0;
	    },

	    ToUint32: function (x) {
	      return ES.ToNumber(x) >>> 0;
	    },

	    ToNumber: function (value) {
	      if (_toString(value) === '[object Symbol]') {
	        throw new TypeError('Cannot convert a Symbol value to a number');
	      }
	      return +value;
	    },

	    ToInteger: function (value) {
	      var number = ES.ToNumber(value);
	      if (numberIsNaN(number)) { return 0; }
	      if (number === 0 || !numberIsFinite(number)) { return number; }
	      return (number > 0 ? 1 : -1) * _floor(_abs(number));
	    },

	    ToLength: function (value) {
	      var len = ES.ToInteger(value);
	      if (len <= 0) { return 0; } // includes converting -0 to +0
	      if (len > Number.MAX_SAFE_INTEGER) { return Number.MAX_SAFE_INTEGER; }
	      return len;
	    },

	    SameValue: function (a, b) {
	      if (a === b) {
	        // 0 === -0, but they are not identical.
	        if (a === 0) { return 1 / a === 1 / b; }
	        return true;
	      }
	      return numberIsNaN(a) && numberIsNaN(b);
	    },

	    SameValueZero: function (a, b) {
	      // same as SameValue except for SameValueZero(+0, -0) == true
	      return (a === b) || (numberIsNaN(a) && numberIsNaN(b));
	    },

	    IsIterable: function (o) {
	      return ES.TypeIsObject(o) && (typeof o[$iterator$] !== 'undefined' || isArguments(o));
	    },

	    GetIterator: function (o) {
	      if (isArguments(o)) {
	        // special case support for `arguments`
	        return new ArrayIterator(o, 'value');
	      }
	      var itFn = ES.GetMethod(o, $iterator$);
	      if (!ES.IsCallable(itFn)) {
	        // Better diagnostics if itFn is null or undefined
	        throw new TypeError('value is not an iterable');
	      }
	      var it = ES.Call(itFn, o);
	      if (!ES.TypeIsObject(it)) {
	        throw new TypeError('bad iterator');
	      }
	      return it;
	    },

	    GetMethod: function (o, p) {
	      var func = ES.ToObject(o)[p];
	      if (isNullOrUndefined(func)) {
	        return void 0;
	      }
	      if (!ES.IsCallable(func)) {
	        throw new TypeError('Method not callable: ' + p);
	      }
	      return func;
	    },

	    IteratorComplete: function (iterResult) {
	      return !!iterResult.done;
	    },

	    IteratorClose: function (iterator, completionIsThrow) {
	      var returnMethod = ES.GetMethod(iterator, 'return');
	      if (returnMethod === void 0) {
	        return;
	      }
	      var innerResult, innerException;
	      try {
	        innerResult = ES.Call(returnMethod, iterator);
	      } catch (e) {
	        innerException = e;
	      }
	      if (completionIsThrow) {
	        return;
	      }
	      if (innerException) {
	        throw innerException;
	      }
	      if (!ES.TypeIsObject(innerResult)) {
	        throw new TypeError("Iterator's return method returned a non-object.");
	      }
	    },

	    IteratorNext: function (it) {
	      var result = arguments.length > 1 ? it.next(arguments[1]) : it.next();
	      if (!ES.TypeIsObject(result)) {
	        throw new TypeError('bad iterator');
	      }
	      return result;
	    },

	    IteratorStep: function (it) {
	      var result = ES.IteratorNext(it);
	      var done = ES.IteratorComplete(result);
	      return done ? false : result;
	    },

	    Construct: function (C, args, newTarget, isES6internal) {
	      var target = typeof newTarget === 'undefined' ? C : newTarget;

	      if (!isES6internal && Reflect.construct) {
	        // Try to use Reflect.construct if available
	        return Reflect.construct(C, args, target);
	      }
	      // OK, we have to fake it.  This will only work if the
	      // C.[[ConstructorKind]] == "base" -- but that's the only
	      // kind we can make in ES5 code anyway.

	      // OrdinaryCreateFromConstructor(target, "%ObjectPrototype%")
	      var proto = target.prototype;
	      if (!ES.TypeIsObject(proto)) {
	        proto = Object.prototype;
	      }
	      var obj = create(proto);
	      // Call the constructor.
	      var result = ES.Call(C, obj, args);
	      return ES.TypeIsObject(result) ? result : obj;
	    },

	    SpeciesConstructor: function (O, defaultConstructor) {
	      var C = O.constructor;
	      if (C === void 0) {
	        return defaultConstructor;
	      }
	      if (!ES.TypeIsObject(C)) {
	        throw new TypeError('Bad constructor');
	      }
	      var S = C[symbolSpecies];
	      if (isNullOrUndefined(S)) {
	        return defaultConstructor;
	      }
	      if (!ES.IsConstructor(S)) {
	        throw new TypeError('Bad @@species');
	      }
	      return S;
	    },

	    CreateHTML: function (string, tag, attribute, value) {
	      var S = ES.ToString(string);
	      var p1 = '<' + tag;
	      if (attribute !== '') {
	        var V = ES.ToString(value);
	        var escapedV = V.replace(/"/g, '&quot;');
	        p1 += ' ' + attribute + '="' + escapedV + '"';
	      }
	      var p2 = p1 + '>';
	      var p3 = p2 + S;
	      return p3 + '</' + tag + '>';
	    },

	    IsRegExp: function IsRegExp(argument) {
	      if (!ES.TypeIsObject(argument)) {
	        return false;
	      }
	      var isRegExp = argument[Symbol.match];
	      if (typeof isRegExp !== 'undefined') {
	        return !!isRegExp;
	      }
	      return Type.regex(argument);
	    },

	    ToString: function ToString(string) {
	      return $String(string);
	    }
	  };

	  // Well-known Symbol shims
	  if (supportsDescriptors && hasSymbols) {
	    var defineWellKnownSymbol = function defineWellKnownSymbol(name) {
	      if (Type.symbol(Symbol[name])) {
	        return Symbol[name];
	      }
	      // eslint-disable-next-line no-restricted-properties
	      var sym = Symbol['for']('Symbol.' + name);
	      Object.defineProperty(Symbol, name, {
	        configurable: false,
	        enumerable: false,
	        writable: false,
	        value: sym
	      });
	      return sym;
	    };
	    if (!Type.symbol(Symbol.search)) {
	      var symbolSearch = defineWellKnownSymbol('search');
	      var originalSearch = String.prototype.search;
	      defineProperty(RegExp.prototype, symbolSearch, function search(string) {
	        return ES.Call(originalSearch, string, [this]);
	      });
	      var searchShim = function search(regexp) {
	        var O = ES.RequireObjectCoercible(this);
	        if (!isNullOrUndefined(regexp)) {
	          var searcher = ES.GetMethod(regexp, symbolSearch);
	          if (typeof searcher !== 'undefined') {
	            return ES.Call(searcher, regexp, [O]);
	          }
	        }
	        return ES.Call(originalSearch, O, [ES.ToString(regexp)]);
	      };
	      overrideNative(String.prototype, 'search', searchShim);
	    }
	    if (!Type.symbol(Symbol.replace)) {
	      var symbolReplace = defineWellKnownSymbol('replace');
	      var originalReplace = String.prototype.replace;
	      defineProperty(RegExp.prototype, symbolReplace, function replace(string, replaceValue) {
	        return ES.Call(originalReplace, string, [this, replaceValue]);
	      });
	      var replaceShim = function replace(searchValue, replaceValue) {
	        var O = ES.RequireObjectCoercible(this);
	        if (!isNullOrUndefined(searchValue)) {
	          var replacer = ES.GetMethod(searchValue, symbolReplace);
	          if (typeof replacer !== 'undefined') {
	            return ES.Call(replacer, searchValue, [O, replaceValue]);
	          }
	        }
	        return ES.Call(originalReplace, O, [ES.ToString(searchValue), replaceValue]);
	      };
	      overrideNative(String.prototype, 'replace', replaceShim);
	    }
	    if (!Type.symbol(Symbol.split)) {
	      var symbolSplit = defineWellKnownSymbol('split');
	      var originalSplit = String.prototype.split;
	      defineProperty(RegExp.prototype, symbolSplit, function split(string, limit) {
	        return ES.Call(originalSplit, string, [this, limit]);
	      });
	      var splitShim = function split(separator, limit) {
	        var O = ES.RequireObjectCoercible(this);
	        if (!isNullOrUndefined(separator)) {
	          var splitter = ES.GetMethod(separator, symbolSplit);
	          if (typeof splitter !== 'undefined') {
	            return ES.Call(splitter, separator, [O, limit]);
	          }
	        }
	        return ES.Call(originalSplit, O, [ES.ToString(separator), limit]);
	      };
	      overrideNative(String.prototype, 'split', splitShim);
	    }
	    var symbolMatchExists = Type.symbol(Symbol.match);
	    var stringMatchIgnoresSymbolMatch = symbolMatchExists && (function () {
	      // Firefox 41, through Nightly 45 has Symbol.match, but String#match ignores it.
	      // Firefox 40 and below have Symbol.match but String#match works fine.
	      var o = {};
	      o[Symbol.match] = function () { return 42; };
	      return 'a'.match(o) !== 42;
	    }());
	    if (!symbolMatchExists || stringMatchIgnoresSymbolMatch) {
	      var symbolMatch = defineWellKnownSymbol('match');

	      var originalMatch = String.prototype.match;
	      defineProperty(RegExp.prototype, symbolMatch, function match(string) {
	        return ES.Call(originalMatch, string, [this]);
	      });

	      var matchShim = function match(regexp) {
	        var O = ES.RequireObjectCoercible(this);
	        if (!isNullOrUndefined(regexp)) {
	          var matcher = ES.GetMethod(regexp, symbolMatch);
	          if (typeof matcher !== 'undefined') {
	            return ES.Call(matcher, regexp, [O]);
	          }
	        }
	        return ES.Call(originalMatch, O, [ES.ToString(regexp)]);
	      };
	      overrideNative(String.prototype, 'match', matchShim);
	    }
	  }

	  var wrapConstructor = function wrapConstructor(original, replacement, keysToSkip) {
	    Value.preserveToString(replacement, original);
	    if (Object.setPrototypeOf) {
	      // sets up proper prototype chain where possible
	      Object.setPrototypeOf(original, replacement);
	    }
	    if (supportsDescriptors) {
	      _forEach(Object.getOwnPropertyNames(original), function (key) {
	        if (key in noop || keysToSkip[key]) { return; }
	        Value.proxy(original, key, replacement);
	      });
	    } else {
	      _forEach(Object.keys(original), function (key) {
	        if (key in noop || keysToSkip[key]) { return; }
	        replacement[key] = original[key];
	      });
	    }
	    replacement.prototype = original.prototype;
	    Value.redefine(original.prototype, 'constructor', replacement);
	  };

	  var defaultSpeciesGetter = function () { return this; };
	  var addDefaultSpecies = function (C) {
	    if (supportsDescriptors && !_hasOwnProperty(C, symbolSpecies)) {
	      Value.getter(C, symbolSpecies, defaultSpeciesGetter);
	    }
	  };

	  var addIterator = function (prototype, impl) {
	    var implementation = impl || function iterator() { return this; };
	    defineProperty(prototype, $iterator$, implementation);
	    if (!prototype[$iterator$] && Type.symbol($iterator$)) {
	      // implementations are buggy when $iterator$ is a Symbol
	      prototype[$iterator$] = implementation;
	    }
	  };

	  var createDataProperty = function createDataProperty(object, name, value) {
	    if (supportsDescriptors) {
	      Object.defineProperty(object, name, {
	        configurable: true,
	        enumerable: true,
	        writable: true,
	        value: value
	      });
	    } else {
	      object[name] = value;
	    }
	  };
	  var createDataPropertyOrThrow = function createDataPropertyOrThrow(object, name, value) {
	    createDataProperty(object, name, value);
	    if (!ES.SameValue(object[name], value)) {
	      throw new TypeError('property is nonconfigurable');
	    }
	  };

	  var emulateES6construct = function (o, defaultNewTarget, defaultProto, slots) {
	    // This is an es5 approximation to es6 construct semantics.  in es6,
	    // 'new Foo' invokes Foo.[[Construct]] which (for almost all objects)
	    // just sets the internal variable NewTarget (in es6 syntax `new.target`)
	    // to Foo and then returns Foo().

	    // Many ES6 object then have constructors of the form:
	    // 1. If NewTarget is undefined, throw a TypeError exception
	    // 2. Let xxx by OrdinaryCreateFromConstructor(NewTarget, yyy, zzz)

	    // So we're going to emulate those first two steps.
	    if (!ES.TypeIsObject(o)) {
	      throw new TypeError('Constructor requires `new`: ' + defaultNewTarget.name);
	    }
	    var proto = defaultNewTarget.prototype;
	    if (!ES.TypeIsObject(proto)) {
	      proto = defaultProto;
	    }
	    var obj = create(proto);
	    for (var name in slots) {
	      if (_hasOwnProperty(slots, name)) {
	        var value = slots[name];
	        defineProperty(obj, name, value, true);
	      }
	    }
	    return obj;
	  };

	  // Firefox 31 reports this function's length as 0
	  // https://bugzilla.mozilla.org/show_bug.cgi?id=1062484
	  if (String.fromCodePoint && String.fromCodePoint.length !== 1) {
	    var originalFromCodePoint = String.fromCodePoint;
	    overrideNative(String, 'fromCodePoint', function fromCodePoint(codePoints) {
	      return ES.Call(originalFromCodePoint, this, arguments);
	    });
	  }

	  var StringShims = {
	    fromCodePoint: function fromCodePoint(codePoints) {
	      var result = [];
	      var next;
	      for (var i = 0, length = arguments.length; i < length; i++) {
	        next = Number(arguments[i]);
	        if (!ES.SameValue(next, ES.ToInteger(next)) || next < 0 || next > 0x10FFFF) {
	          throw new RangeError('Invalid code point ' + next);
	        }

	        if (next < 0x10000) {
	          _push(result, String.fromCharCode(next));
	        } else {
	          next -= 0x10000;
	          _push(result, String.fromCharCode((next >> 10) + 0xD800));
	          _push(result, String.fromCharCode((next % 0x400) + 0xDC00));
	        }
	      }
	      return result.join('');
	    },

	    raw: function raw(callSite) {
	      var cooked = ES.ToObject(callSite, 'bad callSite');
	      var rawString = ES.ToObject(cooked.raw, 'bad raw value');
	      var len = rawString.length;
	      var literalsegments = ES.ToLength(len);
	      if (literalsegments <= 0) {
	        return '';
	      }

	      var stringElements = [];
	      var nextIndex = 0;
	      var nextKey, next, nextSeg, nextSub;
	      while (nextIndex < literalsegments) {
	        nextKey = ES.ToString(nextIndex);
	        nextSeg = ES.ToString(rawString[nextKey]);
	        _push(stringElements, nextSeg);
	        if (nextIndex + 1 >= literalsegments) {
	          break;
	        }
	        next = nextIndex + 1 < arguments.length ? arguments[nextIndex + 1] : '';
	        nextSub = ES.ToString(next);
	        _push(stringElements, nextSub);
	        nextIndex += 1;
	      }
	      return stringElements.join('');
	    }
	  };
	  if (String.raw && String.raw({ raw: { 0: 'x', 1: 'y', length: 2 } }) !== 'xy') {
	    // IE 11 TP has a broken String.raw implementation
	    overrideNative(String, 'raw', StringShims.raw);
	  }
	  defineProperties(String, StringShims);

	  // Fast repeat, uses the `Exponentiation by squaring` algorithm.
	  // Perf: http://jsperf.com/string-repeat2/2
	  var stringRepeat = function repeat(s, times) {
	    if (times < 1) { return ''; }
	    if (times % 2) { return repeat(s, times - 1) + s; }
	    var half = repeat(s, times / 2);
	    return half + half;
	  };
	  var stringMaxLength = Infinity;

	  var StringPrototypeShims = {
	    repeat: function repeat(times) {
	      var thisStr = ES.ToString(ES.RequireObjectCoercible(this));
	      var numTimes = ES.ToInteger(times);
	      if (numTimes < 0 || numTimes >= stringMaxLength) {
	        throw new RangeError('repeat count must be less than infinity and not overflow maximum string size');
	      }
	      return stringRepeat(thisStr, numTimes);
	    },

	    startsWith: function startsWith(searchString) {
	      var S = ES.ToString(ES.RequireObjectCoercible(this));
	      if (ES.IsRegExp(searchString)) {
	        throw new TypeError('Cannot call method "startsWith" with a regex');
	      }
	      var searchStr = ES.ToString(searchString);
	      var position;
	      if (arguments.length > 1) {
	        position = arguments[1];
	      }
	      var start = _max(ES.ToInteger(position), 0);
	      return _strSlice(S, start, start + searchStr.length) === searchStr;
	    },

	    endsWith: function endsWith(searchString) {
	      var S = ES.ToString(ES.RequireObjectCoercible(this));
	      if (ES.IsRegExp(searchString)) {
	        throw new TypeError('Cannot call method "endsWith" with a regex');
	      }
	      var searchStr = ES.ToString(searchString);
	      var len = S.length;
	      var endPosition;
	      if (arguments.length > 1) {
	        endPosition = arguments[1];
	      }
	      var pos = typeof endPosition === 'undefined' ? len : ES.ToInteger(endPosition);
	      var end = _min(_max(pos, 0), len);
	      return _strSlice(S, end - searchStr.length, end) === searchStr;
	    },

	    includes: function includes(searchString) {
	      if (ES.IsRegExp(searchString)) {
	        throw new TypeError('"includes" does not accept a RegExp');
	      }
	      var searchStr = ES.ToString(searchString);
	      var position;
	      if (arguments.length > 1) {
	        position = arguments[1];
	      }
	      // Somehow this trick makes method 100% compat with the spec.
	      return _indexOf(this, searchStr, position) !== -1;
	    },

	    codePointAt: function codePointAt(pos) {
	      var thisStr = ES.ToString(ES.RequireObjectCoercible(this));
	      var position = ES.ToInteger(pos);
	      var length = thisStr.length;
	      if (position >= 0 && position < length) {
	        var first = thisStr.charCodeAt(position);
	        var isEnd = position + 1 === length;
	        if (first < 0xD800 || first > 0xDBFF || isEnd) { return first; }
	        var second = thisStr.charCodeAt(position + 1);
	        if (second < 0xDC00 || second > 0xDFFF) { return first; }
	        return ((first - 0xD800) * 1024) + (second - 0xDC00) + 0x10000;
	      }
	    }
	  };
	  if (String.prototype.includes && 'a'.includes('a', Infinity) !== false) {
	    overrideNative(String.prototype, 'includes', StringPrototypeShims.includes);
	  }

	  if (String.prototype.startsWith && String.prototype.endsWith) {
	    var startsWithRejectsRegex = throwsError(function () {
	      /* throws if spec-compliant */
	      '/a/'.startsWith(/a/);
	    });
	    var startsWithHandlesInfinity = valueOrFalseIfThrows(function () {
	      return 'abc'.startsWith('a', Infinity) === false;
	    });
	    if (!startsWithRejectsRegex || !startsWithHandlesInfinity) {
	      // Firefox (< 37?) and IE 11 TP have a noncompliant startsWith implementation
	      overrideNative(String.prototype, 'startsWith', StringPrototypeShims.startsWith);
	      overrideNative(String.prototype, 'endsWith', StringPrototypeShims.endsWith);
	    }
	  }
	  if (hasSymbols) {
	    var startsWithSupportsSymbolMatch = valueOrFalseIfThrows(function () {
	      var re = /a/;
	      re[Symbol.match] = false;
	      return '/a/'.startsWith(re);
	    });
	    if (!startsWithSupportsSymbolMatch) {
	      overrideNative(String.prototype, 'startsWith', StringPrototypeShims.startsWith);
	    }
	    var endsWithSupportsSymbolMatch = valueOrFalseIfThrows(function () {
	      var re = /a/;
	      re[Symbol.match] = false;
	      return '/a/'.endsWith(re);
	    });
	    if (!endsWithSupportsSymbolMatch) {
	      overrideNative(String.prototype, 'endsWith', StringPrototypeShims.endsWith);
	    }
	    var includesSupportsSymbolMatch = valueOrFalseIfThrows(function () {
	      var re = /a/;
	      re[Symbol.match] = false;
	      return '/a/'.includes(re);
	    });
	    if (!includesSupportsSymbolMatch) {
	      overrideNative(String.prototype, 'includes', StringPrototypeShims.includes);
	    }
	  }

	  defineProperties(String.prototype, StringPrototypeShims);

	  // whitespace from: http://es5.github.io/#x15.5.4.20
	  // implementation from https://github.com/es-shims/es5-shim/blob/v3.4.0/es5-shim.js#L1304-L1324
	  var ws = [
	    '\x09\x0A\x0B\x0C\x0D\x20\xA0\u1680\u180E\u2000\u2001\u2002\u2003',
	    '\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u202F\u205F\u3000\u2028',
	    '\u2029\uFEFF'
	  ].join('');
	  var trimRegexp = new RegExp('(^[' + ws + ']+)|([' + ws + ']+$)', 'g');
	  var trimShim = function trim() {
	    return ES.ToString(ES.RequireObjectCoercible(this)).replace(trimRegexp, '');
	  };
	  var nonWS = ['\u0085', '\u200b', '\ufffe'].join('');
	  var nonWSregex = new RegExp('[' + nonWS + ']', 'g');
	  var isBadHexRegex = /^[-+]0x[0-9a-f]+$/i;
	  var hasStringTrimBug = nonWS.trim().length !== nonWS.length;
	  defineProperty(String.prototype, 'trim', trimShim, hasStringTrimBug);

	  // Given an argument x, it will return an IteratorResult object,
	  // with value set to x and done to false.
	  // Given no arguments, it will return an iterator completion object.
	  var iteratorResult = function (x) {
	    return { value: x, done: arguments.length === 0 };
	  };

	  // see http://www.ecma-international.org/ecma-262/6.0/#sec-string.prototype-@@iterator
	  var StringIterator = function (s) {
	    ES.RequireObjectCoercible(s);
	    this._s = ES.ToString(s);
	    this._i = 0;
	  };
	  StringIterator.prototype.next = function () {
	    var s = this._s;
	    var i = this._i;
	    if (typeof s === 'undefined' || i >= s.length) {
	      this._s = void 0;
	      return iteratorResult();
	    }
	    var first = s.charCodeAt(i);
	    var second, len;
	    if (first < 0xD800 || first > 0xDBFF || (i + 1) === s.length) {
	      len = 1;
	    } else {
	      second = s.charCodeAt(i + 1);
	      len = (second < 0xDC00 || second > 0xDFFF) ? 1 : 2;
	    }
	    this._i = i + len;
	    return iteratorResult(s.substr(i, len));
	  };
	  addIterator(StringIterator.prototype);
	  addIterator(String.prototype, function () {
	    return new StringIterator(this);
	  });

	  var ArrayShims = {
	    from: function from(items) {
	      var C = this;
	      var mapFn;
	      if (arguments.length > 1) {
	        mapFn = arguments[1];
	      }
	      var mapping, T;
	      if (typeof mapFn === 'undefined') {
	        mapping = false;
	      } else {
	        if (!ES.IsCallable(mapFn)) {
	          throw new TypeError('Array.from: when provided, the second argument must be a function');
	        }
	        if (arguments.length > 2) {
	          T = arguments[2];
	        }
	        mapping = true;
	      }

	      // Note that that Arrays will use ArrayIterator:
	      // https://bugs.ecmascript.org/show_bug.cgi?id=2416
	      var usingIterator = typeof (isArguments(items) || ES.GetMethod(items, $iterator$)) !== 'undefined';

	      var length, result, i;
	      if (usingIterator) {
	        result = ES.IsConstructor(C) ? Object(new C()) : [];
	        var iterator = ES.GetIterator(items);
	        var next, nextValue;

	        i = 0;
	        while (true) {
	          next = ES.IteratorStep(iterator);
	          if (next === false) {
	            break;
	          }
	          nextValue = next.value;
	          try {
	            if (mapping) {
	              nextValue = typeof T === 'undefined' ? mapFn(nextValue, i) : _call(mapFn, T, nextValue, i);
	            }
	            result[i] = nextValue;
	          } catch (e) {
	            ES.IteratorClose(iterator, true);
	            throw e;
	          }
	          i += 1;
	        }
	        length = i;
	      } else {
	        var arrayLike = ES.ToObject(items);
	        length = ES.ToLength(arrayLike.length);
	        result = ES.IsConstructor(C) ? Object(new C(length)) : new Array(length);
	        var value;
	        for (i = 0; i < length; ++i) {
	          value = arrayLike[i];
	          if (mapping) {
	            value = typeof T === 'undefined' ? mapFn(value, i) : _call(mapFn, T, value, i);
	          }
	          createDataPropertyOrThrow(result, i, value);
	        }
	      }

	      result.length = length;
	      return result;
	    },

	    of: function of() {
	      var len = arguments.length;
	      var C = this;
	      var A = isArray(C) || !ES.IsCallable(C) ? new Array(len) : ES.Construct(C, [len]);
	      for (var k = 0; k < len; ++k) {
	        createDataPropertyOrThrow(A, k, arguments[k]);
	      }
	      A.length = len;
	      return A;
	    }
	  };
	  defineProperties(Array, ArrayShims);
	  addDefaultSpecies(Array);

	  // Our ArrayIterator is private; see
	  // https://github.com/paulmillr/es6-shim/issues/252
	  ArrayIterator = function (array, kind) {
	    this.i = 0;
	    this.array = array;
	    this.kind = kind;
	  };

	  defineProperties(ArrayIterator.prototype, {
	    next: function () {
	      var i = this.i;
	      var array = this.array;
	      if (!(this instanceof ArrayIterator)) {
	        throw new TypeError('Not an ArrayIterator');
	      }
	      if (typeof array !== 'undefined') {
	        var len = ES.ToLength(array.length);
	        for (; i < len; i++) {
	          var kind = this.kind;
	          var retval;
	          if (kind === 'key') {
	            retval = i;
	          } else if (kind === 'value') {
	            retval = array[i];
	          } else if (kind === 'entry') {
	            retval = [i, array[i]];
	          }
	          this.i = i + 1;
	          return iteratorResult(retval);
	        }
	      }
	      this.array = void 0;
	      return iteratorResult();
	    }
	  });
	  addIterator(ArrayIterator.prototype);

	/*
	  var orderKeys = function orderKeys(a, b) {
	    var aNumeric = String(ES.ToInteger(a)) === a;
	    var bNumeric = String(ES.ToInteger(b)) === b;
	    if (aNumeric && bNumeric) {
	      return b - a;
	    } else if (aNumeric && !bNumeric) {
	      return -1;
	    } else if (!aNumeric && bNumeric) {
	      return 1;
	    } else {
	      return a.localeCompare(b);
	    }
	  };

	  var getAllKeys = function getAllKeys(object) {
	    var ownKeys = [];
	    var keys = [];

	    for (var key in object) {
	      _push(_hasOwnProperty(object, key) ? ownKeys : keys, key);
	    }
	    _sort(ownKeys, orderKeys);
	    _sort(keys, orderKeys);

	    return _concat(ownKeys, keys);
	  };
	  */

	  // note: this is positioned here because it depends on ArrayIterator
	  var arrayOfSupportsSubclassing = Array.of === ArrayShims.of || (function () {
	    // Detects a bug in Webkit nightly r181886
	    var Foo = function Foo(len) { this.length = len; };
	    Foo.prototype = [];
	    var fooArr = Array.of.apply(Foo, [1, 2]);
	    return fooArr instanceof Foo && fooArr.length === 2;
	  }());
	  if (!arrayOfSupportsSubclassing) {
	    overrideNative(Array, 'of', ArrayShims.of);
	  }

	  var ArrayPrototypeShims = {
	    copyWithin: function copyWithin(target, start) {
	      var o = ES.ToObject(this);
	      var len = ES.ToLength(o.length);
	      var relativeTarget = ES.ToInteger(target);
	      var relativeStart = ES.ToInteger(start);
	      var to = relativeTarget < 0 ? _max(len + relativeTarget, 0) : _min(relativeTarget, len);
	      var from = relativeStart < 0 ? _max(len + relativeStart, 0) : _min(relativeStart, len);
	      var end;
	      if (arguments.length > 2) {
	        end = arguments[2];
	      }
	      var relativeEnd = typeof end === 'undefined' ? len : ES.ToInteger(end);
	      var finalItem = relativeEnd < 0 ? _max(len + relativeEnd, 0) : _min(relativeEnd, len);
	      var count = _min(finalItem - from, len - to);
	      var direction = 1;
	      if (from < to && to < (from + count)) {
	        direction = -1;
	        from += count - 1;
	        to += count - 1;
	      }
	      while (count > 0) {
	        if (from in o) {
	          o[to] = o[from];
	        } else {
	          delete o[to];
	        }
	        from += direction;
	        to += direction;
	        count -= 1;
	      }
	      return o;
	    },

	    fill: function fill(value) {
	      var start;
	      if (arguments.length > 1) {
	        start = arguments[1];
	      }
	      var end;
	      if (arguments.length > 2) {
	        end = arguments[2];
	      }
	      var O = ES.ToObject(this);
	      var len = ES.ToLength(O.length);
	      start = ES.ToInteger(typeof start === 'undefined' ? 0 : start);
	      end = ES.ToInteger(typeof end === 'undefined' ? len : end);

	      var relativeStart = start < 0 ? _max(len + start, 0) : _min(start, len);
	      var relativeEnd = end < 0 ? len + end : end;

	      for (var i = relativeStart; i < len && i < relativeEnd; ++i) {
	        O[i] = value;
	      }
	      return O;
	    },

	    find: function find(predicate) {
	      var list = ES.ToObject(this);
	      var length = ES.ToLength(list.length);
	      if (!ES.IsCallable(predicate)) {
	        throw new TypeError('Array#find: predicate must be a function');
	      }
	      var thisArg = arguments.length > 1 ? arguments[1] : null;
	      for (var i = 0, value; i < length; i++) {
	        value = list[i];
	        if (thisArg) {
	          if (_call(predicate, thisArg, value, i, list)) {
	            return value;
	          }
	        } else if (predicate(value, i, list)) {
	          return value;
	        }
	      }
	    },

	    findIndex: function findIndex(predicate) {
	      var list = ES.ToObject(this);
	      var length = ES.ToLength(list.length);
	      if (!ES.IsCallable(predicate)) {
	        throw new TypeError('Array#findIndex: predicate must be a function');
	      }
	      var thisArg = arguments.length > 1 ? arguments[1] : null;
	      for (var i = 0; i < length; i++) {
	        if (thisArg) {
	          if (_call(predicate, thisArg, list[i], i, list)) {
	            return i;
	          }
	        } else if (predicate(list[i], i, list)) {
	          return i;
	        }
	      }
	      return -1;
	    },

	    keys: function keys() {
	      return new ArrayIterator(this, 'key');
	    },

	    values: function values() {
	      return new ArrayIterator(this, 'value');
	    },

	    entries: function entries() {
	      return new ArrayIterator(this, 'entry');
	    }
	  };
	  // Safari 7.1 defines Array#keys and Array#entries natively,
	  // but the resulting ArrayIterator objects don't have a "next" method.
	  if (Array.prototype.keys && !ES.IsCallable([1].keys().next)) {
	    delete Array.prototype.keys;
	  }
	  if (Array.prototype.entries && !ES.IsCallable([1].entries().next)) {
	    delete Array.prototype.entries;
	  }

	  // Chrome 38 defines Array#keys and Array#entries, and Array#@@iterator, but not Array#values
	  if (Array.prototype.keys && Array.prototype.entries && !Array.prototype.values && Array.prototype[$iterator$]) {
	    defineProperties(Array.prototype, {
	      values: Array.prototype[$iterator$]
	    });
	    if (Type.symbol(Symbol.unscopables)) {
	      Array.prototype[Symbol.unscopables].values = true;
	    }
	  }
	  // Chrome 40 defines Array#values with the incorrect name, although Array#{keys,entries} have the correct name
	  if (functionsHaveNames && Array.prototype.values && Array.prototype.values.name !== 'values') {
	    var originalArrayPrototypeValues = Array.prototype.values;
	    overrideNative(Array.prototype, 'values', function values() { return ES.Call(originalArrayPrototypeValues, this, arguments); });
	    defineProperty(Array.prototype, $iterator$, Array.prototype.values, true);
	  }
	  defineProperties(Array.prototype, ArrayPrototypeShims);

	  if (1 / [true].indexOf(true, -0) < 0) {
	    // indexOf when given a position arg of -0 should return +0.
	    // https://github.com/tc39/ecma262/pull/316
	    defineProperty(Array.prototype, 'indexOf', function indexOf(searchElement) {
	      var value = _arrayIndexOfApply(this, arguments);
	      if (value === 0 && (1 / value) < 0) {
	        return 0;
	      }
	      return value;
	    }, true);
	  }

	  addIterator(Array.prototype, function () { return this.values(); });
	  // Chrome defines keys/values/entries on Array, but doesn't give us
	  // any way to identify its iterator.  So add our own shimmed field.
	  if (Object.getPrototypeOf) {
	    addIterator(Object.getPrototypeOf([].values()));
	  }

	  // note: this is positioned here because it relies on Array#entries
	  var arrayFromSwallowsNegativeLengths = (function () {
	    // Detects a Firefox bug in v32
	    // https://bugzilla.mozilla.org/show_bug.cgi?id=1063993
	    return valueOrFalseIfThrows(function () {
	      return Array.from({ length: -1 }).length === 0;
	    });
	  }());
	  var arrayFromHandlesIterables = (function () {
	    // Detects a bug in Webkit nightly r181886
	    var arr = Array.from([0].entries());
	    return arr.length === 1 && isArray(arr[0]) && arr[0][0] === 0 && arr[0][1] === 0;
	  }());
	  if (!arrayFromSwallowsNegativeLengths || !arrayFromHandlesIterables) {
	    overrideNative(Array, 'from', ArrayShims.from);
	  }
	  var arrayFromHandlesUndefinedMapFunction = (function () {
	    // Microsoft Edge v0.11 throws if the mapFn argument is *provided* but undefined,
	    // but the spec doesn't care if it's provided or not - undefined doesn't throw.
	    return valueOrFalseIfThrows(function () {
	      return Array.from([0], void 0);
	    });
	  }());
	  if (!arrayFromHandlesUndefinedMapFunction) {
	    var origArrayFrom = Array.from;
	    overrideNative(Array, 'from', function from(items) {
	      if (arguments.length > 1 && typeof arguments[1] !== 'undefined') {
	        return ES.Call(origArrayFrom, this, arguments);
	      } else {
	        return _call(origArrayFrom, this, items);
	      }
	    });
	  }

	  var int32sAsOne = -(Math.pow(2, 32) - 1);
	  var toLengthsCorrectly = function (method, reversed) {
	    var obj = { length: int32sAsOne };
	    obj[reversed ? (obj.length >>> 0) - 1 : 0] = true;
	    return valueOrFalseIfThrows(function () {
	      _call(method, obj, function () {
	        // note: in nonconforming browsers, this will be called
	        // -1 >>> 0 times, which is 4294967295, so the throw matters.
	        throw new RangeError('should not reach here');
	      }, []);
	      return true;
	    });
	  };
	  if (!toLengthsCorrectly(Array.prototype.forEach)) {
	    var originalForEach = Array.prototype.forEach;
	    overrideNative(Array.prototype, 'forEach', function forEach(callbackFn) {
	      return ES.Call(originalForEach, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }
	  if (!toLengthsCorrectly(Array.prototype.map)) {
	    var originalMap = Array.prototype.map;
	    overrideNative(Array.prototype, 'map', function map(callbackFn) {
	      return ES.Call(originalMap, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }
	  if (!toLengthsCorrectly(Array.prototype.filter)) {
	    var originalFilter = Array.prototype.filter;
	    overrideNative(Array.prototype, 'filter', function filter(callbackFn) {
	      return ES.Call(originalFilter, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }
	  if (!toLengthsCorrectly(Array.prototype.some)) {
	    var originalSome = Array.prototype.some;
	    overrideNative(Array.prototype, 'some', function some(callbackFn) {
	      return ES.Call(originalSome, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }
	  if (!toLengthsCorrectly(Array.prototype.every)) {
	    var originalEvery = Array.prototype.every;
	    overrideNative(Array.prototype, 'every', function every(callbackFn) {
	      return ES.Call(originalEvery, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }
	  if (!toLengthsCorrectly(Array.prototype.reduce)) {
	    var originalReduce = Array.prototype.reduce;
	    overrideNative(Array.prototype, 'reduce', function reduce(callbackFn) {
	      return ES.Call(originalReduce, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }
	  if (!toLengthsCorrectly(Array.prototype.reduceRight, true)) {
	    var originalReduceRight = Array.prototype.reduceRight;
	    overrideNative(Array.prototype, 'reduceRight', function reduceRight(callbackFn) {
	      return ES.Call(originalReduceRight, this.length >= 0 ? this : [], arguments);
	    }, true);
	  }

	  var lacksOctalSupport = Number('0o10') !== 8;
	  var lacksBinarySupport = Number('0b10') !== 2;
	  var trimsNonWhitespace = _some(nonWS, function (c) {
	    return Number(c + 0 + c) === 0;
	  });
	  if (lacksOctalSupport || lacksBinarySupport || trimsNonWhitespace) {
	    var OrigNumber = Number;
	    var binaryRegex = /^0b[01]+$/i;
	    var octalRegex = /^0o[0-7]+$/i;
	    // Note that in IE 8, RegExp.prototype.test doesn't seem to exist: ie, "test" is an own property of regexes. wtf.
	    var isBinary = binaryRegex.test.bind(binaryRegex);
	    var isOctal = octalRegex.test.bind(octalRegex);
	    var toPrimitive = function (O) { // need to replace this with `es-to-primitive/es6`
	      var result;
	      if (typeof O.valueOf === 'function') {
	        result = O.valueOf();
	        if (Type.primitive(result)) {
	          return result;
	        }
	      }
	      if (typeof O.toString === 'function') {
	        result = O.toString();
	        if (Type.primitive(result)) {
	          return result;
	        }
	      }
	      throw new TypeError('No default value');
	    };
	    var hasNonWS = nonWSregex.test.bind(nonWSregex);
	    var isBadHex = isBadHexRegex.test.bind(isBadHexRegex);
	    var NumberShim = (function () {
	      // this is wrapped in an IIFE because of IE 6-8's wacky scoping issues with named function expressions.
	      var NumberShim = function Number(value) {
	        var primValue;
	        if (arguments.length > 0) {
	          primValue = Type.primitive(value) ? value : toPrimitive(value, 'number');
	        } else {
	          primValue = 0;
	        }
	        if (typeof primValue === 'string') {
	          primValue = ES.Call(trimShim, primValue);
	          if (isBinary(primValue)) {
	            primValue = parseInt(_strSlice(primValue, 2), 2);
	          } else if (isOctal(primValue)) {
	            primValue = parseInt(_strSlice(primValue, 2), 8);
	          } else if (hasNonWS(primValue) || isBadHex(primValue)) {
	            primValue = NaN;
	          }
	        }
	        var receiver = this;
	        var valueOfSucceeds = valueOrFalseIfThrows(function () {
	          OrigNumber.prototype.valueOf.call(receiver);
	          return true;
	        });
	        if (receiver instanceof NumberShim && !valueOfSucceeds) {
	          return new OrigNumber(primValue);
	        }
	        /* jshint newcap: false */
	        return OrigNumber(primValue);
	        /* jshint newcap: true */
	      };
	      return NumberShim;
	    }());
	    wrapConstructor(OrigNumber, NumberShim, {});
	    // this is necessary for ES3 browsers, where these properties are non-enumerable.
	    defineProperties(NumberShim, {
	      NaN: OrigNumber.NaN,
	      MAX_VALUE: OrigNumber.MAX_VALUE,
	      MIN_VALUE: OrigNumber.MIN_VALUE,
	      NEGATIVE_INFINITY: OrigNumber.NEGATIVE_INFINITY,
	      POSITIVE_INFINITY: OrigNumber.POSITIVE_INFINITY
	    });
	    /* globals Number: true */
	    /* eslint-disable no-undef, no-global-assign */
	    /* jshint -W020 */
	    Number = NumberShim;
	    Value.redefine(globals, 'Number', NumberShim);
	    /* jshint +W020 */
	    /* eslint-enable no-undef, no-global-assign */
	    /* globals Number: false */
	  }

	  var maxSafeInteger = Math.pow(2, 53) - 1;
	  defineProperties(Number, {
	    MAX_SAFE_INTEGER: maxSafeInteger,
	    MIN_SAFE_INTEGER: -maxSafeInteger,
	    EPSILON: 2.220446049250313e-16,

	    parseInt: globals.parseInt,
	    parseFloat: globals.parseFloat,

	    isFinite: numberIsFinite,

	    isInteger: function isInteger(value) {
	      return numberIsFinite(value) && ES.ToInteger(value) === value;
	    },

	    isSafeInteger: function isSafeInteger(value) {
	      return Number.isInteger(value) && _abs(value) <= Number.MAX_SAFE_INTEGER;
	    },

	    isNaN: numberIsNaN
	  });
	  // Firefox 37 has a conforming Number.parseInt, but it's not === to the global parseInt (fixed in v40)
	  defineProperty(Number, 'parseInt', globals.parseInt, Number.parseInt !== globals.parseInt);

	  // Work around bugs in Array#find and Array#findIndex -- early
	  // implementations skipped holes in sparse arrays. (Note that the
	  // implementations of find/findIndex indirectly use shimmed
	  // methods of Number, so this test has to happen down here.)
	  /*jshint elision: true */
	  /* eslint-disable no-sparse-arrays */
	  if (![, 1].find(function (item, idx) { return idx === 0; })) {
	    overrideNative(Array.prototype, 'find', ArrayPrototypeShims.find);
	  }
	  if ([, 1].findIndex(function (item, idx) { return idx === 0; }) !== 0) {
	    overrideNative(Array.prototype, 'findIndex', ArrayPrototypeShims.findIndex);
	  }
	  /* eslint-enable no-sparse-arrays */
	  /*jshint elision: false */

	  var isEnumerableOn = Function.bind.call(Function.bind, Object.prototype.propertyIsEnumerable);
	  var ensureEnumerable = function ensureEnumerable(obj, prop) {
	    if (supportsDescriptors && isEnumerableOn(obj, prop)) {
	      Object.defineProperty(obj, prop, { enumerable: false });
	    }
	  };
	  var sliceArgs = function sliceArgs() {
	    // per https://github.com/petkaantonov/bluebird/wiki/Optimization-killers#32-leaking-arguments
	    // and https://gist.github.com/WebReflection/4327762cb87a8c634a29
	    var initial = Number(this);
	    var len = arguments.length;
	    var desiredArgCount = len - initial;
	    var args = new Array(desiredArgCount < 0 ? 0 : desiredArgCount);
	    for (var i = initial; i < len; ++i) {
	      args[i - initial] = arguments[i];
	    }
	    return args;
	  };
	  var assignTo = function assignTo(source) {
	    return function assignToSource(target, key) {
	      target[key] = source[key];
	      return target;
	    };
	  };
	  var assignReducer = function (target, source) {
	    var sourceKeys = keys(Object(source));
	    var symbols;
	    if (ES.IsCallable(Object.getOwnPropertySymbols)) {
	      symbols = _filter(Object.getOwnPropertySymbols(Object(source)), isEnumerableOn(source));
	    }
	    return _reduce(_concat(sourceKeys, symbols || []), assignTo(source), target);
	  };

	  var ObjectShims = {
	    // 19.1.3.1
	    assign: function (target, source) {
	      var to = ES.ToObject(target, 'Cannot convert undefined or null to object');
	      return _reduce(ES.Call(sliceArgs, 1, arguments), assignReducer, to);
	    },

	    // Added in WebKit in https://bugs.webkit.org/show_bug.cgi?id=143865
	    is: function is(a, b) {
	      return ES.SameValue(a, b);
	    }
	  };
	  var assignHasPendingExceptions = Object.assign && Object.preventExtensions && (function () {
	    // Firefox 37 still has "pending exception" logic in its Object.assign implementation,
	    // which is 72% slower than our shim, and Firefox 40's native implementation.
	    var thrower = Object.preventExtensions({ 1: 2 });
	    try {
	      Object.assign(thrower, 'xy');
	    } catch (e) {
	      return thrower[1] === 'y';
	    }
	  }());
	  if (assignHasPendingExceptions) {
	    overrideNative(Object, 'assign', ObjectShims.assign);
	  }
	  defineProperties(Object, ObjectShims);

	  if (supportsDescriptors) {
	    var ES5ObjectShims = {
	      // 19.1.3.9
	      // shim from https://gist.github.com/WebReflection/5593554
	      setPrototypeOf: (function (Object, magic) {
	        var set;

	        var checkArgs = function (O, proto) {
	          if (!ES.TypeIsObject(O)) {
	            throw new TypeError('cannot set prototype on a non-object');
	          }
	          if (!(proto === null || ES.TypeIsObject(proto))) {
	            throw new TypeError('can only set prototype to an object or null' + proto);
	          }
	        };

	        var setPrototypeOf = function (O, proto) {
	          checkArgs(O, proto);
	          _call(set, O, proto);
	          return O;
	        };

	        try {
	          // this works already in Firefox and Safari
	          set = Object.getOwnPropertyDescriptor(Object.prototype, magic).set;
	          _call(set, {}, null);
	        } catch (e) {
	          if (Object.prototype !== {}[magic]) {
	            // IE < 11 cannot be shimmed
	            return;
	          }
	          // probably Chrome or some old Mobile stock browser
	          set = function (proto) {
	            this[magic] = proto;
	          };
	          // please note that this will **not** work
	          // in those browsers that do not inherit
	          // __proto__ by mistake from Object.prototype
	          // in these cases we should probably throw an error
	          // or at least be informed about the issue
	          setPrototypeOf.polyfill = setPrototypeOf(
	            setPrototypeOf({}, null),
	            Object.prototype
	          ) instanceof Object;
	          // setPrototypeOf.polyfill === true means it works as meant
	          // setPrototypeOf.polyfill === false means it's not 100% reliable
	          // setPrototypeOf.polyfill === undefined
	          // or
	          // setPrototypeOf.polyfill ==  null means it's not a polyfill
	          // which means it works as expected
	          // we can even delete Object.prototype.__proto__;
	        }
	        return setPrototypeOf;
	      }(Object, '__proto__'))
	    };

	    defineProperties(Object, ES5ObjectShims);
	  }

	  // Workaround bug in Opera 12 where setPrototypeOf(x, null) doesn't work,
	  // but Object.create(null) does.
	  if (Object.setPrototypeOf && Object.getPrototypeOf &&
	      Object.getPrototypeOf(Object.setPrototypeOf({}, null)) !== null &&
	      Object.getPrototypeOf(Object.create(null)) === null) {
	    (function () {
	      var FAKENULL = Object.create(null);
	      var gpo = Object.getPrototypeOf;
	      var spo = Object.setPrototypeOf;
	      Object.getPrototypeOf = function (o) {
	        var result = gpo(o);
	        return result === FAKENULL ? null : result;
	      };
	      Object.setPrototypeOf = function (o, p) {
	        var proto = p === null ? FAKENULL : p;
	        return spo(o, proto);
	      };
	      Object.setPrototypeOf.polyfill = false;
	    }());
	  }

	  var objectKeysAcceptsPrimitives = !throwsError(function () { Object.keys('foo'); });
	  if (!objectKeysAcceptsPrimitives) {
	    var originalObjectKeys = Object.keys;
	    overrideNative(Object, 'keys', function keys(value) {
	      return originalObjectKeys(ES.ToObject(value));
	    });
	    keys = Object.keys;
	  }
	  var objectKeysRejectsRegex = throwsError(function () { Object.keys(/a/g); });
	  if (objectKeysRejectsRegex) {
	    var regexRejectingObjectKeys = Object.keys;
	    overrideNative(Object, 'keys', function keys(value) {
	      if (Type.regex(value)) {
	        var regexKeys = [];
	        for (var k in value) {
	          if (_hasOwnProperty(value, k)) {
	            _push(regexKeys, k);
	          }
	        }
	        return regexKeys;
	      }
	      return regexRejectingObjectKeys(value);
	    });
	    keys = Object.keys;
	  }

	  if (Object.getOwnPropertyNames) {
	    var objectGOPNAcceptsPrimitives = !throwsError(function () { Object.getOwnPropertyNames('foo'); });
	    if (!objectGOPNAcceptsPrimitives) {
	      var cachedWindowNames = typeof window === 'object' ? Object.getOwnPropertyNames(window) : [];
	      var originalObjectGetOwnPropertyNames = Object.getOwnPropertyNames;
	      overrideNative(Object, 'getOwnPropertyNames', function getOwnPropertyNames(value) {
	        var val = ES.ToObject(value);
	        if (_toString(val) === '[object Window]') {
	          try {
	            return originalObjectGetOwnPropertyNames(val);
	          } catch (e) {
	            // IE bug where layout engine calls userland gOPN for cross-domain `window` objects
	            return _concat([], cachedWindowNames);
	          }
	        }
	        return originalObjectGetOwnPropertyNames(val);
	      });
	    }
	  }
	  if (Object.getOwnPropertyDescriptor) {
	    var objectGOPDAcceptsPrimitives = !throwsError(function () { Object.getOwnPropertyDescriptor('foo', 'bar'); });
	    if (!objectGOPDAcceptsPrimitives) {
	      var originalObjectGetOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;
	      overrideNative(Object, 'getOwnPropertyDescriptor', function getOwnPropertyDescriptor(value, property) {
	        return originalObjectGetOwnPropertyDescriptor(ES.ToObject(value), property);
	      });
	    }
	  }
	  if (Object.seal) {
	    var objectSealAcceptsPrimitives = !throwsError(function () { Object.seal('foo'); });
	    if (!objectSealAcceptsPrimitives) {
	      var originalObjectSeal = Object.seal;
	      overrideNative(Object, 'seal', function seal(value) {
	        if (!ES.TypeIsObject(value)) { return value; }
	        return originalObjectSeal(value);
	      });
	    }
	  }
	  if (Object.isSealed) {
	    var objectIsSealedAcceptsPrimitives = !throwsError(function () { Object.isSealed('foo'); });
	    if (!objectIsSealedAcceptsPrimitives) {
	      var originalObjectIsSealed = Object.isSealed;
	      overrideNative(Object, 'isSealed', function isSealed(value) {
	        if (!ES.TypeIsObject(value)) { return true; }
	        return originalObjectIsSealed(value);
	      });
	    }
	  }
	  if (Object.freeze) {
	    var objectFreezeAcceptsPrimitives = !throwsError(function () { Object.freeze('foo'); });
	    if (!objectFreezeAcceptsPrimitives) {
	      var originalObjectFreeze = Object.freeze;
	      overrideNative(Object, 'freeze', function freeze(value) {
	        if (!ES.TypeIsObject(value)) { return value; }
	        return originalObjectFreeze(value);
	      });
	    }
	  }
	  if (Object.isFrozen) {
	    var objectIsFrozenAcceptsPrimitives = !throwsError(function () { Object.isFrozen('foo'); });
	    if (!objectIsFrozenAcceptsPrimitives) {
	      var originalObjectIsFrozen = Object.isFrozen;
	      overrideNative(Object, 'isFrozen', function isFrozen(value) {
	        if (!ES.TypeIsObject(value)) { return true; }
	        return originalObjectIsFrozen(value);
	      });
	    }
	  }
	  if (Object.preventExtensions) {
	    var objectPreventExtensionsAcceptsPrimitives = !throwsError(function () { Object.preventExtensions('foo'); });
	    if (!objectPreventExtensionsAcceptsPrimitives) {
	      var originalObjectPreventExtensions = Object.preventExtensions;
	      overrideNative(Object, 'preventExtensions', function preventExtensions(value) {
	        if (!ES.TypeIsObject(value)) { return value; }
	        return originalObjectPreventExtensions(value);
	      });
	    }
	  }
	  if (Object.isExtensible) {
	    var objectIsExtensibleAcceptsPrimitives = !throwsError(function () { Object.isExtensible('foo'); });
	    if (!objectIsExtensibleAcceptsPrimitives) {
	      var originalObjectIsExtensible = Object.isExtensible;
	      overrideNative(Object, 'isExtensible', function isExtensible(value) {
	        if (!ES.TypeIsObject(value)) { return false; }
	        return originalObjectIsExtensible(value);
	      });
	    }
	  }
	  if (Object.getPrototypeOf) {
	    var objectGetProtoAcceptsPrimitives = !throwsError(function () { Object.getPrototypeOf('foo'); });
	    if (!objectGetProtoAcceptsPrimitives) {
	      var originalGetProto = Object.getPrototypeOf;
	      overrideNative(Object, 'getPrototypeOf', function getPrototypeOf(value) {
	        return originalGetProto(ES.ToObject(value));
	      });
	    }
	  }

	  var hasFlags = supportsDescriptors && (function () {
	    var desc = Object.getOwnPropertyDescriptor(RegExp.prototype, 'flags');
	    return desc && ES.IsCallable(desc.get);
	  }());
	  if (supportsDescriptors && !hasFlags) {
	    var regExpFlagsGetter = function flags() {
	      if (!ES.TypeIsObject(this)) {
	        throw new TypeError('Method called on incompatible type: must be an object.');
	      }
	      var result = '';
	      if (this.global) {
	        result += 'g';
	      }
	      if (this.ignoreCase) {
	        result += 'i';
	      }
	      if (this.multiline) {
	        result += 'm';
	      }
	      if (this.unicode) {
	        result += 'u';
	      }
	      if (this.sticky) {
	        result += 'y';
	      }
	      return result;
	    };

	    Value.getter(RegExp.prototype, 'flags', regExpFlagsGetter);
	  }

	  var regExpSupportsFlagsWithRegex = supportsDescriptors && valueOrFalseIfThrows(function () {
	    return String(new RegExp(/a/g, 'i')) === '/a/i';
	  });
	  var regExpNeedsToSupportSymbolMatch = hasSymbols && supportsDescriptors && (function () {
	    // Edge 0.12 supports flags fully, but does not support Symbol.match
	    var regex = /./;
	    regex[Symbol.match] = false;
	    return RegExp(regex) === regex;
	  }());

	  var regexToStringIsGeneric = valueOrFalseIfThrows(function () {
	    return RegExp.prototype.toString.call({ source: 'abc' }) === '/abc/';
	  });
	  var regexToStringSupportsGenericFlags = regexToStringIsGeneric && valueOrFalseIfThrows(function () {
	    return RegExp.prototype.toString.call({ source: 'a', flags: 'b' }) === '/a/b';
	  });
	  if (!regexToStringIsGeneric || !regexToStringSupportsGenericFlags) {
	    var origRegExpToString = RegExp.prototype.toString;
	    defineProperty(RegExp.prototype, 'toString', function toString() {
	      var R = ES.RequireObjectCoercible(this);
	      if (Type.regex(R)) {
	        return _call(origRegExpToString, R);
	      }
	      var pattern = $String(R.source);
	      var flags = $String(R.flags);
	      return '/' + pattern + '/' + flags;
	    }, true);
	    Value.preserveToString(RegExp.prototype.toString, origRegExpToString);
	  }

	  if (supportsDescriptors && (!regExpSupportsFlagsWithRegex || regExpNeedsToSupportSymbolMatch)) {
	    var flagsGetter = Object.getOwnPropertyDescriptor(RegExp.prototype, 'flags').get;
	    var sourceDesc = Object.getOwnPropertyDescriptor(RegExp.prototype, 'source') || {};
	    var legacySourceGetter = function () {
	      // prior to it being a getter, it's own + nonconfigurable
	      return this.source;
	    };
	    var sourceGetter = ES.IsCallable(sourceDesc.get) ? sourceDesc.get : legacySourceGetter;

	    var OrigRegExp = RegExp;
	    var RegExpShim = (function () {
	      return function RegExp(pattern, flags) {
	        var patternIsRegExp = ES.IsRegExp(pattern);
	        var calledWithNew = this instanceof RegExp;
	        if (!calledWithNew && patternIsRegExp && typeof flags === 'undefined' && pattern.constructor === RegExp) {
	          return pattern;
	        }

	        var P = pattern;
	        var F = flags;
	        if (Type.regex(pattern)) {
	          P = ES.Call(sourceGetter, pattern);
	          F = typeof flags === 'undefined' ? ES.Call(flagsGetter, pattern) : flags;
	          return new RegExp(P, F);
	        } else if (patternIsRegExp) {
	          P = pattern.source;
	          F = typeof flags === 'undefined' ? pattern.flags : flags;
	        }
	        return new OrigRegExp(pattern, flags);
	      };
	    }());
	    wrapConstructor(OrigRegExp, RegExpShim, {
	      $input: true // Chrome < v39 & Opera < 26 have a nonstandard "$input" property
	    });
	    /* globals RegExp: true */
	    /* eslint-disable no-undef, no-global-assign */
	    /* jshint -W020 */
	    RegExp = RegExpShim;
	    Value.redefine(globals, 'RegExp', RegExpShim);
	    /* jshint +W020 */
	    /* eslint-enable no-undef, no-global-assign */
	    /* globals RegExp: false */
	  }

	  if (supportsDescriptors) {
	    var regexGlobals = {
	      input: '$_',
	      lastMatch: '$&',
	      lastParen: '$+',
	      leftContext: '$`',
	      rightContext: '$\''
	    };
	    _forEach(keys(regexGlobals), function (prop) {
	      if (prop in RegExp && !(regexGlobals[prop] in RegExp)) {
	        Value.getter(RegExp, regexGlobals[prop], function get() {
	          return RegExp[prop];
	        });
	      }
	    });
	  }
	  addDefaultSpecies(RegExp);

	  var inverseEpsilon = 1 / Number.EPSILON;
	  var roundTiesToEven = function roundTiesToEven(n) {
	    // Even though this reduces down to `return n`, it takes advantage of built-in rounding.
	    return (n + inverseEpsilon) - inverseEpsilon;
	  };
	  var BINARY_32_EPSILON = Math.pow(2, -23);
	  var BINARY_32_MAX_VALUE = Math.pow(2, 127) * (2 - BINARY_32_EPSILON);
	  var BINARY_32_MIN_VALUE = Math.pow(2, -126);
	  var E = Math.E;
	  var LOG2E = Math.LOG2E;
	  var LOG10E = Math.LOG10E;
	  var numberCLZ = Number.prototype.clz;
	  delete Number.prototype.clz; // Safari 8 has Number#clz

	  var MathShims = {
	    acosh: function acosh(value) {
	      var x = Number(value);
	      if (numberIsNaN(x) || value < 1) { return NaN; }
	      if (x === 1) { return 0; }
	      if (x === Infinity) { return x; }
	      return _log((x / E) + (_sqrt(x + 1) * _sqrt(x - 1) / E)) + 1;
	    },

	    asinh: function asinh(value) {
	      var x = Number(value);
	      if (x === 0 || !globalIsFinite(x)) {
	        return x;
	      }
	      return x < 0 ? -asinh(-x) : _log(x + _sqrt((x * x) + 1));
	    },

	    atanh: function atanh(value) {
	      var x = Number(value);
	      if (numberIsNaN(x) || x < -1 || x > 1) {
	        return NaN;
	      }
	      if (x === -1) { return -Infinity; }
	      if (x === 1) { return Infinity; }
	      if (x === 0) { return x; }
	      return 0.5 * _log((1 + x) / (1 - x));
	    },

	    cbrt: function cbrt(value) {
	      var x = Number(value);
	      if (x === 0) { return x; }
	      var negate = x < 0;
	      var result;
	      if (negate) { x = -x; }
	      if (x === Infinity) {
	        result = Infinity;
	      } else {
	        result = _exp(_log(x) / 3);
	        // from http://en.wikipedia.org/wiki/Cube_root#Numerical_methods
	        result = ((x / (result * result)) + (2 * result)) / 3;
	      }
	      return negate ? -result : result;
	    },

	    clz32: function clz32(value) {
	      // See https://bugs.ecmascript.org/show_bug.cgi?id=2465
	      var x = Number(value);
	      var number = ES.ToUint32(x);
	      if (number === 0) {
	        return 32;
	      }
	      return numberCLZ ? ES.Call(numberCLZ, number) : 31 - _floor(_log(number + 0.5) * LOG2E);
	    },

	    cosh: function cosh(value) {
	      var x = Number(value);
	      if (x === 0) { return 1; } // +0 or -0
	      if (numberIsNaN(x)) { return NaN; }
	      if (!globalIsFinite(x)) { return Infinity; }
	      if (x < 0) { x = -x; }
	      if (x > 21) { return _exp(x) / 2; }
	      return (_exp(x) + _exp(-x)) / 2;
	    },

	    expm1: function expm1(value) {
	      var x = Number(value);
	      if (x === -Infinity) { return -1; }
	      if (!globalIsFinite(x) || x === 0) { return x; }
	      if (_abs(x) > 0.5) {
	        return _exp(x) - 1;
	      }
	      // A more precise approximation using Taylor series expansion
	      // from https://github.com/paulmillr/es6-shim/issues/314#issuecomment-70293986
	      var t = x;
	      var sum = 0;
	      var n = 1;
	      while (sum + t !== sum) {
	        sum += t;
	        n += 1;
	        t *= x / n;
	      }
	      return sum;
	    },

	    hypot: function hypot(x, y) {
	      var result = 0;
	      var largest = 0;
	      for (var i = 0; i < arguments.length; ++i) {
	        var value = _abs(Number(arguments[i]));
	        if (largest < value) {
	          result *= (largest / value) * (largest / value);
	          result += 1;
	          largest = value;
	        } else {
	          result += value > 0 ? (value / largest) * (value / largest) : value;
	        }
	      }
	      return largest === Infinity ? Infinity : largest * _sqrt(result);
	    },

	    log2: function log2(value) {
	      return _log(value) * LOG2E;
	    },

	    log10: function log10(value) {
	      return _log(value) * LOG10E;
	    },

	    log1p: function log1p(value) {
	      var x = Number(value);
	      if (x < -1 || numberIsNaN(x)) { return NaN; }
	      if (x === 0 || x === Infinity) { return x; }
	      if (x === -1) { return -Infinity; }

	      return (1 + x) - 1 === 0 ? x : x * (_log(1 + x) / ((1 + x) - 1));
	    },

	    sign: _sign,

	    sinh: function sinh(value) {
	      var x = Number(value);
	      if (!globalIsFinite(x) || x === 0) { return x; }

	      if (_abs(x) < 1) {
	        return (Math.expm1(x) - Math.expm1(-x)) / 2;
	      }
	      return (_exp(x - 1) - _exp(-x - 1)) * E / 2;
	    },

	    tanh: function tanh(value) {
	      var x = Number(value);
	      if (numberIsNaN(x) || x === 0) { return x; }
	      // can exit early at +-20 as JS loses precision for true value at this integer
	      if (x >= 20) { return 1; }
	      if (x <= -20) { return -1; }

	      return (Math.expm1(x) - Math.expm1(-x)) / (_exp(x) + _exp(-x));
	    },

	    trunc: function trunc(value) {
	      var x = Number(value);
	      return x < 0 ? -_floor(-x) : _floor(x);
	    },

	    imul: function imul(x, y) {
	      // taken from https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/imul
	      var a = ES.ToUint32(x);
	      var b = ES.ToUint32(y);
	      var ah = (a >>> 16) & 0xffff;
	      var al = a & 0xffff;
	      var bh = (b >>> 16) & 0xffff;
	      var bl = b & 0xffff;
	      // the shift by 0 fixes the sign on the high part
	      // the final |0 converts the unsigned value into a signed value
	      return (al * bl) + ((((ah * bl) + (al * bh)) << 16) >>> 0) | 0;
	    },

	    fround: function fround(x) {
	      var v = Number(x);
	      if (v === 0 || v === Infinity || v === -Infinity || numberIsNaN(v)) {
	        return v;
	      }
	      var sign = _sign(v);
	      var abs = _abs(v);
	      if (abs < BINARY_32_MIN_VALUE) {
	        return sign * roundTiesToEven(
	          abs / BINARY_32_MIN_VALUE / BINARY_32_EPSILON
	        ) * BINARY_32_MIN_VALUE * BINARY_32_EPSILON;
	      }
	      // Veltkamp's splitting (?)
	      var a = (1 + (BINARY_32_EPSILON / Number.EPSILON)) * abs;
	      var result = a - (a - abs);
	      if (result > BINARY_32_MAX_VALUE || numberIsNaN(result)) {
	        return sign * Infinity;
	      }
	      return sign * result;
	    }
	  };
	  defineProperties(Math, MathShims);
	  // IE 11 TP has an imprecise log1p: reports Math.log1p(-1e-17) as 0
	  defineProperty(Math, 'log1p', MathShims.log1p, Math.log1p(-1e-17) !== -1e-17);
	  // IE 11 TP has an imprecise asinh: reports Math.asinh(-1e7) as not exactly equal to -Math.asinh(1e7)
	  defineProperty(Math, 'asinh', MathShims.asinh, Math.asinh(-1e7) !== -Math.asinh(1e7));
	  // Chrome 40 has an imprecise Math.tanh with very small numbers
	  defineProperty(Math, 'tanh', MathShims.tanh, Math.tanh(-2e-17) !== -2e-17);
	  // Chrome 40 loses Math.acosh precision with high numbers
	  defineProperty(Math, 'acosh', MathShims.acosh, Math.acosh(Number.MAX_VALUE) === Infinity);
	  // Firefox 38 on Windows
	  defineProperty(Math, 'cbrt', MathShims.cbrt, Math.abs(1 - (Math.cbrt(1e-300) / 1e-100)) / Number.EPSILON > 8);
	  // node 0.11 has an imprecise Math.sinh with very small numbers
	  defineProperty(Math, 'sinh', MathShims.sinh, Math.sinh(-2e-17) !== -2e-17);
	  // FF 35 on Linux reports 22025.465794806725 for Math.expm1(10)
	  var expm1OfTen = Math.expm1(10);
	  defineProperty(Math, 'expm1', MathShims.expm1, expm1OfTen > 22025.465794806719 || expm1OfTen < 22025.4657948067165168);

	  var origMathRound = Math.round;
	  // breaks in e.g. Safari 8, Internet Explorer 11, Opera 12
	  var roundHandlesBoundaryConditions = Math.round(0.5 - (Number.EPSILON / 4)) === 0 &&
	    Math.round(-0.5 + (Number.EPSILON / 3.99)) === 1;

	  // When engines use Math.floor(x + 0.5) internally, Math.round can be buggy for large integers.
	  // This behavior should be governed by "round to nearest, ties to even mode"
	  // see http://www.ecma-international.org/ecma-262/6.0/#sec-terms-and-definitions-number-type
	  // These are the boundary cases where it breaks.
	  var smallestPositiveNumberWhereRoundBreaks = inverseEpsilon + 1;
	  var largestPositiveNumberWhereRoundBreaks = (2 * inverseEpsilon) - 1;
	  var roundDoesNotIncreaseIntegers = [
	    smallestPositiveNumberWhereRoundBreaks,
	    largestPositiveNumberWhereRoundBreaks
	  ].every(function (num) {
	    return Math.round(num) === num;
	  });
	  defineProperty(Math, 'round', function round(x) {
	    var floor = _floor(x);
	    var ceil = floor === -1 ? -0 : floor + 1;
	    return x - floor < 0.5 ? floor : ceil;
	  }, !roundHandlesBoundaryConditions || !roundDoesNotIncreaseIntegers);
	  Value.preserveToString(Math.round, origMathRound);

	  var origImul = Math.imul;
	  if (Math.imul(0xffffffff, 5) !== -5) {
	    // Safari 6.1, at least, reports "0" for this value
	    Math.imul = MathShims.imul;
	    Value.preserveToString(Math.imul, origImul);
	  }
	  if (Math.imul.length !== 2) {
	    // Safari 8.0.4 has a length of 1
	    // fixed in https://bugs.webkit.org/show_bug.cgi?id=143658
	    overrideNative(Math, 'imul', function imul(x, y) {
	      return ES.Call(origImul, Math, arguments);
	    });
	  }

	  // Promises
	  // Simplest possible implementation; use a 3rd-party library if you
	  // want the best possible speed and/or long stack traces.
	  var PromiseShim = (function () {
	    var setTimeout = globals.setTimeout;
	    // some environments don't have setTimeout - no way to shim here.
	    if (typeof setTimeout !== 'function' && typeof setTimeout !== 'object') { return; }

	    ES.IsPromise = function (promise) {
	      if (!ES.TypeIsObject(promise)) {
	        return false;
	      }
	      if (typeof promise._promise === 'undefined') {
	        return false; // uninitialized, or missing our hidden field.
	      }
	      return true;
	    };

	    // "PromiseCapability" in the spec is what most promise implementations
	    // call a "deferred".
	    var PromiseCapability = function (C) {
	      if (!ES.IsConstructor(C)) {
	        throw new TypeError('Bad promise constructor');
	      }
	      var capability = this;
	      var resolver = function (resolve, reject) {
	        if (capability.resolve !== void 0 || capability.reject !== void 0) {
	          throw new TypeError('Bad Promise implementation!');
	        }
	        capability.resolve = resolve;
	        capability.reject = reject;
	      };
	      // Initialize fields to inform optimizers about the object shape.
	      capability.resolve = void 0;
	      capability.reject = void 0;
	      capability.promise = new C(resolver);
	      if (!(ES.IsCallable(capability.resolve) && ES.IsCallable(capability.reject))) {
	        throw new TypeError('Bad promise constructor');
	      }
	    };

	    // find an appropriate setImmediate-alike
	    var makeZeroTimeout;
	    /*global window */
	    if (typeof window !== 'undefined' && ES.IsCallable(window.postMessage)) {
	      makeZeroTimeout = function () {
	        // from http://dbaron.org/log/20100309-faster-timeouts
	        var timeouts = [];
	        var messageName = 'zero-timeout-message';
	        var setZeroTimeout = function (fn) {
	          _push(timeouts, fn);
	          window.postMessage(messageName, '*');
	        };
	        var handleMessage = function (event) {
	          if (event.source === window && event.data === messageName) {
	            event.stopPropagation();
	            if (timeouts.length === 0) { return; }
	            var fn = _shift(timeouts);
	            fn();
	          }
	        };
	        window.addEventListener('message', handleMessage, true);
	        return setZeroTimeout;
	      };
	    }
	    var makePromiseAsap = function () {
	      // An efficient task-scheduler based on a pre-existing Promise
	      // implementation, which we can use even if we override the
	      // global Promise below (in order to workaround bugs)
	      // https://github.com/Raynos/observ-hash/issues/2#issuecomment-35857671
	      var P = globals.Promise;
	      var pr = P && P.resolve && P.resolve();
	      return pr && function (task) {
	        return pr.then(task);
	      };
	    };
	    /*global process */
	    /* jscs:disable disallowMultiLineTernary */
	    var enqueue = ES.IsCallable(globals.setImmediate) ?
	      globals.setImmediate :
	      typeof process === 'object' && process.nextTick ? process.nextTick :
	      makePromiseAsap() ||
	      (ES.IsCallable(makeZeroTimeout) ? makeZeroTimeout() :
	      function (task) { setTimeout(task, 0); }); // fallback
	    /* jscs:enable disallowMultiLineTernary */

	    // Constants for Promise implementation
	    var PROMISE_IDENTITY = function (x) { return x; };
	    var PROMISE_THROWER = function (e) { throw e; };
	    var PROMISE_PENDING = 0;
	    var PROMISE_FULFILLED = 1;
	    var PROMISE_REJECTED = 2;
	    // We store fulfill/reject handlers and capabilities in a single array.
	    var PROMISE_FULFILL_OFFSET = 0;
	    var PROMISE_REJECT_OFFSET = 1;
	    var PROMISE_CAPABILITY_OFFSET = 2;
	    // This is used in an optimization for chaining promises via then.
	    var PROMISE_FAKE_CAPABILITY = {};

	    var enqueuePromiseReactionJob = function (handler, capability, argument) {
	      enqueue(function () {
	        promiseReactionJob(handler, capability, argument);
	      });
	    };

	    var promiseReactionJob = function (handler, promiseCapability, argument) {
	      var handlerResult, f;
	      if (promiseCapability === PROMISE_FAKE_CAPABILITY) {
	        // Fast case, when we don't actually need to chain through to a
	        // (real) promiseCapability.
	        return handler(argument);
	      }
	      try {
	        handlerResult = handler(argument);
	        f = promiseCapability.resolve;
	      } catch (e) {
	        handlerResult = e;
	        f = promiseCapability.reject;
	      }
	      f(handlerResult);
	    };

	    var fulfillPromise = function (promise, value) {
	      var _promise = promise._promise;
	      var length = _promise.reactionLength;
	      if (length > 0) {
	        enqueuePromiseReactionJob(
	          _promise.fulfillReactionHandler0,
	          _promise.reactionCapability0,
	          value
	        );
	        _promise.fulfillReactionHandler0 = void 0;
	        _promise.rejectReactions0 = void 0;
	        _promise.reactionCapability0 = void 0;
	        if (length > 1) {
	          for (var i = 1, idx = 0; i < length; i++, idx += 3) {
	            enqueuePromiseReactionJob(
	              _promise[idx + PROMISE_FULFILL_OFFSET],
	              _promise[idx + PROMISE_CAPABILITY_OFFSET],
	              value
	            );
	            promise[idx + PROMISE_FULFILL_OFFSET] = void 0;
	            promise[idx + PROMISE_REJECT_OFFSET] = void 0;
	            promise[idx + PROMISE_CAPABILITY_OFFSET] = void 0;
	          }
	        }
	      }
	      _promise.result = value;
	      _promise.state = PROMISE_FULFILLED;
	      _promise.reactionLength = 0;
	    };

	    var rejectPromise = function (promise, reason) {
	      var _promise = promise._promise;
	      var length = _promise.reactionLength;
	      if (length > 0) {
	        enqueuePromiseReactionJob(
	          _promise.rejectReactionHandler0,
	          _promise.reactionCapability0,
	          reason
	        );
	        _promise.fulfillReactionHandler0 = void 0;
	        _promise.rejectReactions0 = void 0;
	        _promise.reactionCapability0 = void 0;
	        if (length > 1) {
	          for (var i = 1, idx = 0; i < length; i++, idx += 3) {
	            enqueuePromiseReactionJob(
	              _promise[idx + PROMISE_REJECT_OFFSET],
	              _promise[idx + PROMISE_CAPABILITY_OFFSET],
	              reason
	            );
	            promise[idx + PROMISE_FULFILL_OFFSET] = void 0;
	            promise[idx + PROMISE_REJECT_OFFSET] = void 0;
	            promise[idx + PROMISE_CAPABILITY_OFFSET] = void 0;
	          }
	        }
	      }
	      _promise.result = reason;
	      _promise.state = PROMISE_REJECTED;
	      _promise.reactionLength = 0;
	    };

	    var createResolvingFunctions = function (promise) {
	      var alreadyResolved = false;
	      var resolve = function (resolution) {
	        var then;
	        if (alreadyResolved) { return; }
	        alreadyResolved = true;
	        if (resolution === promise) {
	          return rejectPromise(promise, new TypeError('Self resolution'));
	        }
	        if (!ES.TypeIsObject(resolution)) {
	          return fulfillPromise(promise, resolution);
	        }
	        try {
	          then = resolution.then;
	        } catch (e) {
	          return rejectPromise(promise, e);
	        }
	        if (!ES.IsCallable(then)) {
	          return fulfillPromise(promise, resolution);
	        }
	        enqueue(function () {
	          promiseResolveThenableJob(promise, resolution, then);
	        });
	      };
	      var reject = function (reason) {
	        if (alreadyResolved) { return; }
	        alreadyResolved = true;
	        return rejectPromise(promise, reason);
	      };
	      return { resolve: resolve, reject: reject };
	    };

	    var optimizedThen = function (then, thenable, resolve, reject) {
	      // Optimization: since we discard the result, we can pass our
	      // own then implementation a special hint to let it know it
	      // doesn't have to create it.  (The PROMISE_FAKE_CAPABILITY
	      // object is local to this implementation and unforgeable outside.)
	      if (then === Promise$prototype$then) {
	        _call(then, thenable, resolve, reject, PROMISE_FAKE_CAPABILITY);
	      } else {
	        _call(then, thenable, resolve, reject);
	      }
	    };
	    var promiseResolveThenableJob = function (promise, thenable, then) {
	      var resolvingFunctions = createResolvingFunctions(promise);
	      var resolve = resolvingFunctions.resolve;
	      var reject = resolvingFunctions.reject;
	      try {
	        optimizedThen(then, thenable, resolve, reject);
	      } catch (e) {
	        reject(e);
	      }
	    };

	    var Promise$prototype, Promise$prototype$then;
	    var Promise = (function () {
	      var PromiseShim = function Promise(resolver) {
	        if (!(this instanceof PromiseShim)) {
	          throw new TypeError('Constructor Promise requires "new"');
	        }
	        if (this && this._promise) {
	          throw new TypeError('Bad construction');
	        }
	        // see https://bugs.ecmascript.org/show_bug.cgi?id=2482
	        if (!ES.IsCallable(resolver)) {
	          throw new TypeError('not a valid resolver');
	        }
	        var promise = emulateES6construct(this, PromiseShim, Promise$prototype, {
	          _promise: {
	            result: void 0,
	            state: PROMISE_PENDING,
	            // The first member of the "reactions" array is inlined here,
	            // since most promises only have one reaction.
	            // We've also exploded the 'reaction' object to inline the
	            // "handler" and "capability" fields, since both fulfill and
	            // reject reactions share the same capability.
	            reactionLength: 0,
	            fulfillReactionHandler0: void 0,
	            rejectReactionHandler0: void 0,
	            reactionCapability0: void 0
	          }
	        });
	        var resolvingFunctions = createResolvingFunctions(promise);
	        var reject = resolvingFunctions.reject;
	        try {
	          resolver(resolvingFunctions.resolve, reject);
	        } catch (e) {
	          reject(e);
	        }
	        return promise;
	      };
	      return PromiseShim;
	    }());
	    Promise$prototype = Promise.prototype;

	    var _promiseAllResolver = function (index, values, capability, remaining) {
	      var alreadyCalled = false;
	      return function (x) {
	        if (alreadyCalled) { return; }
	        alreadyCalled = true;
	        values[index] = x;
	        if ((--remaining.count) === 0) {
	          var resolve = capability.resolve;
	          resolve(values); // call w/ this===undefined
	        }
	      };
	    };

	    var performPromiseAll = function (iteratorRecord, C, resultCapability) {
	      var it = iteratorRecord.iterator;
	      var values = [];
	      var remaining = { count: 1 };
	      var next, nextValue;
	      var index = 0;
	      while (true) {
	        try {
	          next = ES.IteratorStep(it);
	          if (next === false) {
	            iteratorRecord.done = true;
	            break;
	          }
	          nextValue = next.value;
	        } catch (e) {
	          iteratorRecord.done = true;
	          throw e;
	        }
	        values[index] = void 0;
	        var nextPromise = C.resolve(nextValue);
	        var resolveElement = _promiseAllResolver(
	          index, values, resultCapability, remaining
	        );
	        remaining.count += 1;
	        optimizedThen(nextPromise.then, nextPromise, resolveElement, resultCapability.reject);
	        index += 1;
	      }
	      if ((--remaining.count) === 0) {
	        var resolve = resultCapability.resolve;
	        resolve(values); // call w/ this===undefined
	      }
	      return resultCapability.promise;
	    };

	    var performPromiseRace = function (iteratorRecord, C, resultCapability) {
	      var it = iteratorRecord.iterator;
	      var next, nextValue, nextPromise;
	      while (true) {
	        try {
	          next = ES.IteratorStep(it);
	          if (next === false) {
	            // NOTE: If iterable has no items, resulting promise will never
	            // resolve; see:
	            // https://github.com/domenic/promises-unwrapping/issues/75
	            // https://bugs.ecmascript.org/show_bug.cgi?id=2515
	            iteratorRecord.done = true;
	            break;
	          }
	          nextValue = next.value;
	        } catch (e) {
	          iteratorRecord.done = true;
	          throw e;
	        }
	        nextPromise = C.resolve(nextValue);
	        optimizedThen(nextPromise.then, nextPromise, resultCapability.resolve, resultCapability.reject);
	      }
	      return resultCapability.promise;
	    };

	    defineProperties(Promise, {
	      all: function all(iterable) {
	        var C = this;
	        if (!ES.TypeIsObject(C)) {
	          throw new TypeError('Promise is not object');
	        }
	        var capability = new PromiseCapability(C);
	        var iterator, iteratorRecord;
	        try {
	          iterator = ES.GetIterator(iterable);
	          iteratorRecord = { iterator: iterator, done: false };
	          return performPromiseAll(iteratorRecord, C, capability);
	        } catch (e) {
	          var exception = e;
	          if (iteratorRecord && !iteratorRecord.done) {
	            try {
	              ES.IteratorClose(iterator, true);
	            } catch (ee) {
	              exception = ee;
	            }
	          }
	          var reject = capability.reject;
	          reject(exception);
	          return capability.promise;
	        }
	      },

	      race: function race(iterable) {
	        var C = this;
	        if (!ES.TypeIsObject(C)) {
	          throw new TypeError('Promise is not object');
	        }
	        var capability = new PromiseCapability(C);
	        var iterator, iteratorRecord;
	        try {
	          iterator = ES.GetIterator(iterable);
	          iteratorRecord = { iterator: iterator, done: false };
	          return performPromiseRace(iteratorRecord, C, capability);
	        } catch (e) {
	          var exception = e;
	          if (iteratorRecord && !iteratorRecord.done) {
	            try {
	              ES.IteratorClose(iterator, true);
	            } catch (ee) {
	              exception = ee;
	            }
	          }
	          var reject = capability.reject;
	          reject(exception);
	          return capability.promise;
	        }
	      },

	      reject: function reject(reason) {
	        var C = this;
	        if (!ES.TypeIsObject(C)) {
	          throw new TypeError('Bad promise constructor');
	        }
	        var capability = new PromiseCapability(C);
	        var rejectFunc = capability.reject;
	        rejectFunc(reason); // call with this===undefined
	        return capability.promise;
	      },

	      resolve: function resolve(v) {
	        // See https://esdiscuss.org/topic/fixing-promise-resolve for spec
	        var C = this;
	        if (!ES.TypeIsObject(C)) {
	          throw new TypeError('Bad promise constructor');
	        }
	        if (ES.IsPromise(v)) {
	          var constructor = v.constructor;
	          if (constructor === C) {
	            return v;
	          }
	        }
	        var capability = new PromiseCapability(C);
	        var resolveFunc = capability.resolve;
	        resolveFunc(v); // call with this===undefined
	        return capability.promise;
	      }
	    });

	    defineProperties(Promise$prototype, {
	      'catch': function (onRejected) {
	        return this.then(null, onRejected);
	      },

	      then: function then(onFulfilled, onRejected) {
	        var promise = this;
	        if (!ES.IsPromise(promise)) { throw new TypeError('not a promise'); }
	        var C = ES.SpeciesConstructor(promise, Promise);
	        var resultCapability;
	        var returnValueIsIgnored = arguments.length > 2 && arguments[2] === PROMISE_FAKE_CAPABILITY;
	        if (returnValueIsIgnored && C === Promise) {
	          resultCapability = PROMISE_FAKE_CAPABILITY;
	        } else {
	          resultCapability = new PromiseCapability(C);
	        }
	        // PerformPromiseThen(promise, onFulfilled, onRejected, resultCapability)
	        // Note that we've split the 'reaction' object into its two
	        // components, "capabilities" and "handler"
	        // "capabilities" is always equal to `resultCapability`
	        var fulfillReactionHandler = ES.IsCallable(onFulfilled) ? onFulfilled : PROMISE_IDENTITY;
	        var rejectReactionHandler = ES.IsCallable(onRejected) ? onRejected : PROMISE_THROWER;
	        var _promise = promise._promise;
	        var value;
	        if (_promise.state === PROMISE_PENDING) {
	          if (_promise.reactionLength === 0) {
	            _promise.fulfillReactionHandler0 = fulfillReactionHandler;
	            _promise.rejectReactionHandler0 = rejectReactionHandler;
	            _promise.reactionCapability0 = resultCapability;
	          } else {
	            var idx = 3 * (_promise.reactionLength - 1);
	            _promise[idx + PROMISE_FULFILL_OFFSET] = fulfillReactionHandler;
	            _promise[idx + PROMISE_REJECT_OFFSET] = rejectReactionHandler;
	            _promise[idx + PROMISE_CAPABILITY_OFFSET] = resultCapability;
	          }
	          _promise.reactionLength += 1;
	        } else if (_promise.state === PROMISE_FULFILLED) {
	          value = _promise.result;
	          enqueuePromiseReactionJob(
	            fulfillReactionHandler, resultCapability, value
	          );
	        } else if (_promise.state === PROMISE_REJECTED) {
	          value = _promise.result;
	          enqueuePromiseReactionJob(
	            rejectReactionHandler, resultCapability, value
	          );
	        } else {
	          throw new TypeError('unexpected Promise state');
	        }
	        return resultCapability.promise;
	      }
	    });
	    // This helps the optimizer by ensuring that methods which take
	    // capabilities aren't polymorphic.
	    PROMISE_FAKE_CAPABILITY = new PromiseCapability(Promise);
	    Promise$prototype$then = Promise$prototype.then;

	    return Promise;
	  }());

	  // Chrome's native Promise has extra methods that it shouldn't have. Let's remove them.
	  if (globals.Promise) {
	    delete globals.Promise.accept;
	    delete globals.Promise.defer;
	    delete globals.Promise.prototype.chain;
	  }

	  if (typeof PromiseShim === 'function') {
	    // export the Promise constructor.
	    defineProperties(globals, { Promise: PromiseShim });
	    // In Chrome 33 (and thereabouts) Promise is defined, but the
	    // implementation is buggy in a number of ways.  Let's check subclassing
	    // support to see if we have a buggy implementation.
	    var promiseSupportsSubclassing = supportsSubclassing(globals.Promise, function (S) {
	      return S.resolve(42).then(function () {}) instanceof S;
	    });
	    var promiseIgnoresNonFunctionThenCallbacks = !throwsError(function () {
	      globals.Promise.reject(42).then(null, 5).then(null, noop);
	    });
	    var promiseRequiresObjectContext = throwsError(function () { globals.Promise.call(3, noop); });
	    // Promise.resolve() was errata'ed late in the ES6 process.
	    // See: https://bugzilla.mozilla.org/show_bug.cgi?id=1170742
	    //      https://code.google.com/p/v8/issues/detail?id=4161
	    // It serves as a proxy for a number of other bugs in early Promise
	    // implementations.
	    var promiseResolveBroken = (function (Promise) {
	      var p = Promise.resolve(5);
	      p.constructor = {};
	      var p2 = Promise.resolve(p);
	      try {
	        p2.then(null, noop).then(null, noop); // avoid "uncaught rejection" warnings in console
	      } catch (e) {
	        return true; // v8 native Promises break here https://code.google.com/p/chromium/issues/detail?id=575314
	      }
	      return p === p2; // This *should* be false!
	    }(globals.Promise));

	    // Chrome 46 (probably older too) does not retrieve a thenable's .then synchronously
	    var getsThenSynchronously = supportsDescriptors && (function () {
	      var count = 0;
	      var thenable = Object.defineProperty({}, 'then', { get: function () { count += 1; } });
	      Promise.resolve(thenable);
	      return count === 1;
	    }());

	    var BadResolverPromise = function BadResolverPromise(executor) {
	      var p = new Promise(executor);
	      executor(3, function () {});
	      this.then = p.then;
	      this.constructor = BadResolverPromise;
	    };
	    BadResolverPromise.prototype = Promise.prototype;
	    BadResolverPromise.all = Promise.all;
	    // Chrome Canary 49 (probably older too) has some implementation bugs
	    var hasBadResolverPromise = valueOrFalseIfThrows(function () {
	      return !!BadResolverPromise.all([1, 2]);
	    });

	    if (!promiseSupportsSubclassing || !promiseIgnoresNonFunctionThenCallbacks ||
	        !promiseRequiresObjectContext || promiseResolveBroken ||
	        !getsThenSynchronously || hasBadResolverPromise) {
	      /* globals Promise: true */
	      /* eslint-disable no-undef, no-global-assign */
	      /* jshint -W020 */
	      Promise = PromiseShim;
	      /* jshint +W020 */
	      /* eslint-enable no-undef, no-global-assign */
	      /* globals Promise: false */
	      overrideNative(globals, 'Promise', PromiseShim);
	    }
	    if (Promise.all.length !== 1) {
	      var origAll = Promise.all;
	      overrideNative(Promise, 'all', function all(iterable) {
	        return ES.Call(origAll, this, arguments);
	      });
	    }
	    if (Promise.race.length !== 1) {
	      var origRace = Promise.race;
	      overrideNative(Promise, 'race', function race(iterable) {
	        return ES.Call(origRace, this, arguments);
	      });
	    }
	    if (Promise.resolve.length !== 1) {
	      var origResolve = Promise.resolve;
	      overrideNative(Promise, 'resolve', function resolve(x) {
	        return ES.Call(origResolve, this, arguments);
	      });
	    }
	    if (Promise.reject.length !== 1) {
	      var origReject = Promise.reject;
	      overrideNative(Promise, 'reject', function reject(r) {
	        return ES.Call(origReject, this, arguments);
	      });
	    }
	    ensureEnumerable(Promise, 'all');
	    ensureEnumerable(Promise, 'race');
	    ensureEnumerable(Promise, 'resolve');
	    ensureEnumerable(Promise, 'reject');
	    addDefaultSpecies(Promise);
	  }

	  // Map and Set require a true ES5 environment
	  // Their fast path also requires that the environment preserve
	  // property insertion order, which is not guaranteed by the spec.
	  var testOrder = function (a) {
	    var b = keys(_reduce(a, function (o, k) {
	      o[k] = true;
	      return o;
	    }, {}));
	    return a.join(':') === b.join(':');
	  };
	  var preservesInsertionOrder = testOrder(['z', 'a', 'bb']);
	  // some engines (eg, Chrome) only preserve insertion order for string keys
	  var preservesNumericInsertionOrder = testOrder(['z', 1, 'a', '3', 2]);

	  if (supportsDescriptors) {

	    var fastkey = function fastkey(key, skipInsertionOrderCheck) {
	      if (!skipInsertionOrderCheck && !preservesInsertionOrder) {
	        return null;
	      }
	      if (isNullOrUndefined(key)) {
	        return '^' + ES.ToString(key);
	      } else if (typeof key === 'string') {
	        return '$' + key;
	      } else if (typeof key === 'number') {
	        // note that -0 will get coerced to "0" when used as a property key
	        if (!preservesNumericInsertionOrder) {
	          return 'n' + key;
	        }
	        return key;
	      } else if (typeof key === 'boolean') {
	        return 'b' + key;
	      }
	      return null;
	    };

	    var emptyObject = function emptyObject() {
	      // accomodate some older not-quite-ES5 browsers
	      return Object.create ? Object.create(null) : {};
	    };

	    var addIterableToMap = function addIterableToMap(MapConstructor, map, iterable) {
	      if (isArray(iterable) || Type.string(iterable)) {
	        _forEach(iterable, function (entry) {
	          if (!ES.TypeIsObject(entry)) {
	            throw new TypeError('Iterator value ' + entry + ' is not an entry object');
	          }
	          map.set(entry[0], entry[1]);
	        });
	      } else if (iterable instanceof MapConstructor) {
	        _call(MapConstructor.prototype.forEach, iterable, function (value, key) {
	          map.set(key, value);
	        });
	      } else {
	        var iter, adder;
	        if (!isNullOrUndefined(iterable)) {
	          adder = map.set;
	          if (!ES.IsCallable(adder)) { throw new TypeError('bad map'); }
	          iter = ES.GetIterator(iterable);
	        }
	        if (typeof iter !== 'undefined') {
	          while (true) {
	            var next = ES.IteratorStep(iter);
	            if (next === false) { break; }
	            var nextItem = next.value;
	            try {
	              if (!ES.TypeIsObject(nextItem)) {
	                throw new TypeError('Iterator value ' + nextItem + ' is not an entry object');
	              }
	              _call(adder, map, nextItem[0], nextItem[1]);
	            } catch (e) {
	              ES.IteratorClose(iter, true);
	              throw e;
	            }
	          }
	        }
	      }
	    };
	    var addIterableToSet = function addIterableToSet(SetConstructor, set, iterable) {
	      if (isArray(iterable) || Type.string(iterable)) {
	        _forEach(iterable, function (value) {
	          set.add(value);
	        });
	      } else if (iterable instanceof SetConstructor) {
	        _call(SetConstructor.prototype.forEach, iterable, function (value) {
	          set.add(value);
	        });
	      } else {
	        var iter, adder;
	        if (!isNullOrUndefined(iterable)) {
	          adder = set.add;
	          if (!ES.IsCallable(adder)) { throw new TypeError('bad set'); }
	          iter = ES.GetIterator(iterable);
	        }
	        if (typeof iter !== 'undefined') {
	          while (true) {
	            var next = ES.IteratorStep(iter);
	            if (next === false) { break; }
	            var nextValue = next.value;
	            try {
	              _call(adder, set, nextValue);
	            } catch (e) {
	              ES.IteratorClose(iter, true);
	              throw e;
	            }
	          }
	        }
	      }
	    };

	    var collectionShims = {
	      Map: (function () {

	        var empty = {};

	        var MapEntry = function MapEntry(key, value) {
	          this.key = key;
	          this.value = value;
	          this.next = null;
	          this.prev = null;
	        };

	        MapEntry.prototype.isRemoved = function isRemoved() {
	          return this.key === empty;
	        };

	        var isMap = function isMap(map) {
	          return !!map._es6map;
	        };

	        var requireMapSlot = function requireMapSlot(map, method) {
	          if (!ES.TypeIsObject(map) || !isMap(map)) {
	            throw new TypeError('Method Map.prototype.' + method + ' called on incompatible receiver ' + ES.ToString(map));
	          }
	        };

	        var MapIterator = function MapIterator(map, kind) {
	          requireMapSlot(map, '[[MapIterator]]');
	          this.head = map._head;
	          this.i = this.head;
	          this.kind = kind;
	        };

	        MapIterator.prototype = {
	          next: function next() {
	            var i = this.i;
	            var kind = this.kind;
	            var head = this.head;
	            if (typeof this.i === 'undefined') {
	              return iteratorResult();
	            }
	            while (i.isRemoved() && i !== head) {
	              // back up off of removed entries
	              i = i.prev;
	            }
	            // advance to next unreturned element.
	            var result;
	            while (i.next !== head) {
	              i = i.next;
	              if (!i.isRemoved()) {
	                if (kind === 'key') {
	                  result = i.key;
	                } else if (kind === 'value') {
	                  result = i.value;
	                } else {
	                  result = [i.key, i.value];
	                }
	                this.i = i;
	                return iteratorResult(result);
	              }
	            }
	            // once the iterator is done, it is done forever.
	            this.i = void 0;
	            return iteratorResult();
	          }
	        };
	        addIterator(MapIterator.prototype);

	        var Map$prototype;
	        var MapShim = function Map() {
	          if (!(this instanceof Map)) {
	            throw new TypeError('Constructor Map requires "new"');
	          }
	          if (this && this._es6map) {
	            throw new TypeError('Bad construction');
	          }
	          var map = emulateES6construct(this, Map, Map$prototype, {
	            _es6map: true,
	            _head: null,
	            _map: OrigMap ? new OrigMap() : null,
	            _size: 0,
	            _storage: emptyObject()
	          });

	          var head = new MapEntry(null, null);
	          // circular doubly-linked list.
	          head.next = head.prev = head;
	          map._head = head;

	          // Optionally initialize map from iterable
	          if (arguments.length > 0) {
	            addIterableToMap(Map, map, arguments[0]);
	          }
	          return map;
	        };
	        Map$prototype = MapShim.prototype;

	        Value.getter(Map$prototype, 'size', function () {
	          if (typeof this._size === 'undefined') {
	            throw new TypeError('size method called on incompatible Map');
	          }
	          return this._size;
	        });

	        defineProperties(Map$prototype, {
	          get: function get(key) {
	            requireMapSlot(this, 'get');
	            var entry;
	            var fkey = fastkey(key, true);
	            if (fkey !== null) {
	              // fast O(1) path
	              entry = this._storage[fkey];
	              if (entry) {
	                return entry.value;
	              } else {
	                return;
	              }
	            }
	            if (this._map) {
	              // fast object key path
	              entry = origMapGet.call(this._map, key);
	              if (entry) {
	                return entry.value;
	              } else {
	                return;
	              }
	            }
	            var head = this._head;
	            var i = head;
	            while ((i = i.next) !== head) {
	              if (ES.SameValueZero(i.key, key)) {
	                return i.value;
	              }
	            }
	          },

	          has: function has(key) {
	            requireMapSlot(this, 'has');
	            var fkey = fastkey(key, true);
	            if (fkey !== null) {
	              // fast O(1) path
	              return typeof this._storage[fkey] !== 'undefined';
	            }
	            if (this._map) {
	              // fast object key path
	              return origMapHas.call(this._map, key);
	            }
	            var head = this._head;
	            var i = head;
	            while ((i = i.next) !== head) {
	              if (ES.SameValueZero(i.key, key)) {
	                return true;
	              }
	            }
	            return false;
	          },

	          set: function set(key, value) {
	            requireMapSlot(this, 'set');
	            var head = this._head;
	            var i = head;
	            var entry;
	            var fkey = fastkey(key, true);
	            if (fkey !== null) {
	              // fast O(1) path
	              if (typeof this._storage[fkey] !== 'undefined') {
	                this._storage[fkey].value = value;
	                return this;
	              } else {
	                entry = this._storage[fkey] = new MapEntry(key, value);
	                i = head.prev;
	                // fall through
	              }
	            } else if (this._map) {
	              // fast object key path
	              if (origMapHas.call(this._map, key)) {
	                origMapGet.call(this._map, key).value = value;
	              } else {
	                entry = new MapEntry(key, value);
	                origMapSet.call(this._map, key, entry);
	                i = head.prev;
	                // fall through
	              }
	            }
	            while ((i = i.next) !== head) {
	              if (ES.SameValueZero(i.key, key)) {
	                i.value = value;
	                return this;
	              }
	            }
	            entry = entry || new MapEntry(key, value);
	            if (ES.SameValue(-0, key)) {
	              entry.key = +0; // coerce -0 to +0 in entry
	            }
	            entry.next = this._head;
	            entry.prev = this._head.prev;
	            entry.prev.next = entry;
	            entry.next.prev = entry;
	            this._size += 1;
	            return this;
	          },

	          'delete': function (key) {
	            requireMapSlot(this, 'delete');
	            var head = this._head;
	            var i = head;
	            var fkey = fastkey(key, true);
	            if (fkey !== null) {
	              // fast O(1) path
	              if (typeof this._storage[fkey] === 'undefined') {
	                return false;
	              }
	              i = this._storage[fkey].prev;
	              delete this._storage[fkey];
	              // fall through
	            } else if (this._map) {
	              // fast object key path
	              if (!origMapHas.call(this._map, key)) {
	                return false;
	              }
	              i = origMapGet.call(this._map, key).prev;
	              origMapDelete.call(this._map, key);
	              // fall through
	            }
	            while ((i = i.next) !== head) {
	              if (ES.SameValueZero(i.key, key)) {
	                i.key = i.value = empty;
	                i.prev.next = i.next;
	                i.next.prev = i.prev;
	                this._size -= 1;
	                return true;
	              }
	            }
	            return false;
	          },

	          clear: function clear() {
	            requireMapSlot(this, 'clear');
	            this._map = OrigMap ? new OrigMap() : null;
	            this._size = 0;
	            this._storage = emptyObject();
	            var head = this._head;
	            var i = head;
	            var p = i.next;
	            while ((i = p) !== head) {
	              i.key = i.value = empty;
	              p = i.next;
	              i.next = i.prev = head;
	            }
	            head.next = head.prev = head;
	          },

	          keys: function keys() {
	            requireMapSlot(this, 'keys');
	            return new MapIterator(this, 'key');
	          },

	          values: function values() {
	            requireMapSlot(this, 'values');
	            return new MapIterator(this, 'value');
	          },

	          entries: function entries() {
	            requireMapSlot(this, 'entries');
	            return new MapIterator(this, 'key+value');
	          },

	          forEach: function forEach(callback) {
	            requireMapSlot(this, 'forEach');
	            var context = arguments.length > 1 ? arguments[1] : null;
	            var it = this.entries();
	            for (var entry = it.next(); !entry.done; entry = it.next()) {
	              if (context) {
	                _call(callback, context, entry.value[1], entry.value[0], this);
	              } else {
	                callback(entry.value[1], entry.value[0], this);
	              }
	            }
	          }
	        });
	        addIterator(Map$prototype, Map$prototype.entries);

	        return MapShim;
	      }()),

	      Set: (function () {
	        var isSet = function isSet(set) {
	          return set._es6set && typeof set._storage !== 'undefined';
	        };
	        var requireSetSlot = function requireSetSlot(set, method) {
	          if (!ES.TypeIsObject(set) || !isSet(set)) {
	            // https://github.com/paulmillr/es6-shim/issues/176
	            throw new TypeError('Set.prototype.' + method + ' called on incompatible receiver ' + ES.ToString(set));
	          }
	        };

	        // Creating a Map is expensive.  To speed up the common case of
	        // Sets containing only string or numeric keys, we use an object
	        // as backing storage and lazily create a full Map only when
	        // required.
	        var Set$prototype;
	        var SetShim = function Set() {
	          if (!(this instanceof Set)) {
	            throw new TypeError('Constructor Set requires "new"');
	          }
	          if (this && this._es6set) {
	            throw new TypeError('Bad construction');
	          }
	          var set = emulateES6construct(this, Set, Set$prototype, {
	            _es6set: true,
	            '[[SetData]]': null,
	            _storage: emptyObject()
	          });
	          if (!set._es6set) {
	            throw new TypeError('bad set');
	          }

	          // Optionally initialize Set from iterable
	          if (arguments.length > 0) {
	            addIterableToSet(Set, set, arguments[0]);
	          }
	          return set;
	        };
	        Set$prototype = SetShim.prototype;

	        var decodeKey = function (key) {
	          var k = key;
	          if (k === '^null') {
	            return null;
	          } else if (k === '^undefined') {
	            return void 0;
	          } else {
	            var first = k.charAt(0);
	            if (first === '$') {
	              return _strSlice(k, 1);
	            } else if (first === 'n') {
	              return +_strSlice(k, 1);
	            } else if (first === 'b') {
	              return k === 'btrue';
	            }
	          }
	          return +k;
	        };
	        // Switch from the object backing storage to a full Map.
	        var ensureMap = function ensureMap(set) {
	          if (!set['[[SetData]]']) {
	            var m = set['[[SetData]]'] = new collectionShims.Map();
	            _forEach(keys(set._storage), function (key) {
	              var k = decodeKey(key);
	              m.set(k, k);
	            });
	            set['[[SetData]]'] = m;
	          }
	          set._storage = null; // free old backing storage
	        };

	        Value.getter(SetShim.prototype, 'size', function () {
	          requireSetSlot(this, 'size');
	          if (this._storage) {
	            return keys(this._storage).length;
	          }
	          ensureMap(this);
	          return this['[[SetData]]'].size;
	        });

	        defineProperties(SetShim.prototype, {
	          has: function has(key) {
	            requireSetSlot(this, 'has');
	            var fkey;
	            if (this._storage && (fkey = fastkey(key)) !== null) {
	              return !!this._storage[fkey];
	            }
	            ensureMap(this);
	            return this['[[SetData]]'].has(key);
	          },

	          add: function add(key) {
	            requireSetSlot(this, 'add');
	            var fkey;
	            if (this._storage && (fkey = fastkey(key)) !== null) {
	              this._storage[fkey] = true;
	              return this;
	            }
	            ensureMap(this);
	            this['[[SetData]]'].set(key, key);
	            return this;
	          },

	          'delete': function (key) {
	            requireSetSlot(this, 'delete');
	            var fkey;
	            if (this._storage && (fkey = fastkey(key)) !== null) {
	              var hasFKey = _hasOwnProperty(this._storage, fkey);
	              return (delete this._storage[fkey]) && hasFKey;
	            }
	            ensureMap(this);
	            return this['[[SetData]]']['delete'](key);
	          },

	          clear: function clear() {
	            requireSetSlot(this, 'clear');
	            if (this._storage) {
	              this._storage = emptyObject();
	            }
	            if (this['[[SetData]]']) {
	              this['[[SetData]]'].clear();
	            }
	          },

	          values: function values() {
	            requireSetSlot(this, 'values');
	            ensureMap(this);
	            return this['[[SetData]]'].values();
	          },

	          entries: function entries() {
	            requireSetSlot(this, 'entries');
	            ensureMap(this);
	            return this['[[SetData]]'].entries();
	          },

	          forEach: function forEach(callback) {
	            requireSetSlot(this, 'forEach');
	            var context = arguments.length > 1 ? arguments[1] : null;
	            var entireSet = this;
	            ensureMap(entireSet);
	            this['[[SetData]]'].forEach(function (value, key) {
	              if (context) {
	                _call(callback, context, key, key, entireSet);
	              } else {
	                callback(key, key, entireSet);
	              }
	            });
	          }
	        });
	        defineProperty(SetShim.prototype, 'keys', SetShim.prototype.values, true);
	        addIterator(SetShim.prototype, SetShim.prototype.values);

	        return SetShim;
	      }())
	    };

	    if (globals.Map || globals.Set) {
	      // Safari 8, for example, doesn't accept an iterable.
	      var mapAcceptsArguments = valueOrFalseIfThrows(function () { return new Map([[1, 2]]).get(1) === 2; });
	      if (!mapAcceptsArguments) {
	        globals.Map = function Map() {
	          if (!(this instanceof Map)) {
	            throw new TypeError('Constructor Map requires "new"');
	          }
	          var m = new OrigMap();
	          if (arguments.length > 0) {
	            addIterableToMap(Map, m, arguments[0]);
	          }
	          delete m.constructor;
	          Object.setPrototypeOf(m, globals.Map.prototype);
	          return m;
	        };
	        globals.Map.prototype = create(OrigMap.prototype);
	        defineProperty(globals.Map.prototype, 'constructor', globals.Map, true);
	        Value.preserveToString(globals.Map, OrigMap);
	      }
	      var testMap = new Map();
	      var mapUsesSameValueZero = (function () {
	        // Chrome 38-42, node 0.11/0.12, iojs 1/2 also have a bug when the Map has a size > 4
	        var m = new Map([[1, 0], [2, 0], [3, 0], [4, 0]]);
	        m.set(-0, m);
	        return m.get(0) === m && m.get(-0) === m && m.has(0) && m.has(-0);
	      }());
	      var mapSupportsChaining = testMap.set(1, 2) === testMap;
	      if (!mapUsesSameValueZero || !mapSupportsChaining) {
	        overrideNative(Map.prototype, 'set', function set(k, v) {
	          _call(origMapSet, this, k === 0 ? 0 : k, v);
	          return this;
	        });
	      }
	      if (!mapUsesSameValueZero) {
	        defineProperties(Map.prototype, {
	          get: function get(k) {
	            return _call(origMapGet, this, k === 0 ? 0 : k);
	          },
	          has: function has(k) {
	            return _call(origMapHas, this, k === 0 ? 0 : k);
	          }
	        }, true);
	        Value.preserveToString(Map.prototype.get, origMapGet);
	        Value.preserveToString(Map.prototype.has, origMapHas);
	      }
	      var testSet = new Set();
	      var setUsesSameValueZero = (function (s) {
	        s['delete'](0);
	        s.add(-0);
	        return !s.has(0);
	      }(testSet));
	      var setSupportsChaining = testSet.add(1) === testSet;
	      if (!setUsesSameValueZero || !setSupportsChaining) {
	        var origSetAdd = Set.prototype.add;
	        Set.prototype.add = function add(v) {
	          _call(origSetAdd, this, v === 0 ? 0 : v);
	          return this;
	        };
	        Value.preserveToString(Set.prototype.add, origSetAdd);
	      }
	      if (!setUsesSameValueZero) {
	        var origSetHas = Set.prototype.has;
	        Set.prototype.has = function has(v) {
	          return _call(origSetHas, this, v === 0 ? 0 : v);
	        };
	        Value.preserveToString(Set.prototype.has, origSetHas);
	        var origSetDel = Set.prototype['delete'];
	        Set.prototype['delete'] = function SetDelete(v) {
	          return _call(origSetDel, this, v === 0 ? 0 : v);
	        };
	        Value.preserveToString(Set.prototype['delete'], origSetDel);
	      }
	      var mapSupportsSubclassing = supportsSubclassing(globals.Map, function (M) {
	        var m = new M([]);
	        // Firefox 32 is ok with the instantiating the subclass but will
	        // throw when the map is used.
	        m.set(42, 42);
	        return m instanceof M;
	      });
	      // without Object.setPrototypeOf, subclassing is not possible
	      var mapFailsToSupportSubclassing = Object.setPrototypeOf && !mapSupportsSubclassing;
	      var mapRequiresNew = (function () {
	        try {
	          return !(globals.Map() instanceof globals.Map);
	        } catch (e) {
	          return e instanceof TypeError;
	        }
	      }());
	      if (globals.Map.length !== 0 || mapFailsToSupportSubclassing || !mapRequiresNew) {
	        globals.Map = function Map() {
	          if (!(this instanceof Map)) {
	            throw new TypeError('Constructor Map requires "new"');
	          }
	          var m = new OrigMap();
	          if (arguments.length > 0) {
	            addIterableToMap(Map, m, arguments[0]);
	          }
	          delete m.constructor;
	          Object.setPrototypeOf(m, Map.prototype);
	          return m;
	        };
	        globals.Map.prototype = OrigMap.prototype;
	        defineProperty(globals.Map.prototype, 'constructor', globals.Map, true);
	        Value.preserveToString(globals.Map, OrigMap);
	      }
	      var setSupportsSubclassing = supportsSubclassing(globals.Set, function (S) {
	        var s = new S([]);
	        s.add(42, 42);
	        return s instanceof S;
	      });
	      // without Object.setPrototypeOf, subclassing is not possible
	      var setFailsToSupportSubclassing = Object.setPrototypeOf && !setSupportsSubclassing;
	      var setRequiresNew = (function () {
	        try {
	          return !(globals.Set() instanceof globals.Set);
	        } catch (e) {
	          return e instanceof TypeError;
	        }
	      }());
	      if (globals.Set.length !== 0 || setFailsToSupportSubclassing || !setRequiresNew) {
	        var OrigSet = globals.Set;
	        globals.Set = function Set() {
	          if (!(this instanceof Set)) {
	            throw new TypeError('Constructor Set requires "new"');
	          }
	          var s = new OrigSet();
	          if (arguments.length > 0) {
	            addIterableToSet(Set, s, arguments[0]);
	          }
	          delete s.constructor;
	          Object.setPrototypeOf(s, Set.prototype);
	          return s;
	        };
	        globals.Set.prototype = OrigSet.prototype;
	        defineProperty(globals.Set.prototype, 'constructor', globals.Set, true);
	        Value.preserveToString(globals.Set, OrigSet);
	      }
	      var newMap = new globals.Map();
	      var mapIterationThrowsStopIterator = !valueOrFalseIfThrows(function () {
	        return newMap.keys().next().done;
	      });
	      /*
	        - In Firefox < 23, Map#size is a function.
	        - In all current Firefox, Set#entries/keys/values & Map#clear do not exist
	        - https://bugzilla.mozilla.org/show_bug.cgi?id=869996
	        - In Firefox 24, Map and Set do not implement forEach
	        - In Firefox 25 at least, Map and Set are callable without "new"
	      */
	      if (
	        typeof globals.Map.prototype.clear !== 'function' ||
	        new globals.Set().size !== 0 ||
	        newMap.size !== 0 ||
	        typeof globals.Map.prototype.keys !== 'function' ||
	        typeof globals.Set.prototype.keys !== 'function' ||
	        typeof globals.Map.prototype.forEach !== 'function' ||
	        typeof globals.Set.prototype.forEach !== 'function' ||
	        isCallableWithoutNew(globals.Map) ||
	        isCallableWithoutNew(globals.Set) ||
	        typeof newMap.keys().next !== 'function' || // Safari 8
	        mapIterationThrowsStopIterator || // Firefox 25
	        !mapSupportsSubclassing
	      ) {
	        defineProperties(globals, {
	          Map: collectionShims.Map,
	          Set: collectionShims.Set
	        }, true);
	      }

	      if (globals.Set.prototype.keys !== globals.Set.prototype.values) {
	        // Fixed in WebKit with https://bugs.webkit.org/show_bug.cgi?id=144190
	        defineProperty(globals.Set.prototype, 'keys', globals.Set.prototype.values, true);
	      }

	      // Shim incomplete iterator implementations.
	      addIterator(Object.getPrototypeOf((new globals.Map()).keys()));
	      addIterator(Object.getPrototypeOf((new globals.Set()).keys()));

	      if (functionsHaveNames && globals.Set.prototype.has.name !== 'has') {
	        // Microsoft Edge v0.11.10074.0 is missing a name on Set#has
	        var anonymousSetHas = globals.Set.prototype.has;
	        overrideNative(globals.Set.prototype, 'has', function has(key) {
	          return _call(anonymousSetHas, this, key);
	        });
	      }
	    }
	    defineProperties(globals, collectionShims);
	    addDefaultSpecies(globals.Map);
	    addDefaultSpecies(globals.Set);
	  }

	  var throwUnlessTargetIsObject = function throwUnlessTargetIsObject(target) {
	    if (!ES.TypeIsObject(target)) {
	      throw new TypeError('target must be an object');
	    }
	  };

	  // Some Reflect methods are basically the same as
	  // those on the Object global, except that a TypeError is thrown if
	  // target isn't an object. As well as returning a boolean indicating
	  // the success of the operation.
	  var ReflectShims = {
	    // Apply method in a functional form.
	    apply: function apply() {
	      return ES.Call(ES.Call, null, arguments);
	    },

	    // New operator in a functional form.
	    construct: function construct(constructor, args) {
	      if (!ES.IsConstructor(constructor)) {
	        throw new TypeError('First argument must be a constructor.');
	      }
	      var newTarget = arguments.length > 2 ? arguments[2] : constructor;
	      if (!ES.IsConstructor(newTarget)) {
	        throw new TypeError('new.target must be a constructor.');
	      }
	      return ES.Construct(constructor, args, newTarget, 'internal');
	    },

	    // When deleting a non-existent or configurable property,
	    // true is returned.
	    // When attempting to delete a non-configurable property,
	    // it will return false.
	    deleteProperty: function deleteProperty(target, key) {
	      throwUnlessTargetIsObject(target);
	      if (supportsDescriptors) {
	        var desc = Object.getOwnPropertyDescriptor(target, key);

	        if (desc && !desc.configurable) {
	          return false;
	        }
	      }

	      // Will return true.
	      return delete target[key];
	    },

	    has: function has(target, key) {
	      throwUnlessTargetIsObject(target);
	      return key in target;
	    }
	  };

	  if (Object.getOwnPropertyNames) {
	    Object.assign(ReflectShims, {
	      // Basically the result of calling the internal [[OwnPropertyKeys]].
	      // Concatenating propertyNames and propertySymbols should do the trick.
	      // This should continue to work together with a Symbol shim
	      // which overrides Object.getOwnPropertyNames and implements
	      // Object.getOwnPropertySymbols.
	      ownKeys: function ownKeys(target) {
	        throwUnlessTargetIsObject(target);
	        var keys = Object.getOwnPropertyNames(target);

	        if (ES.IsCallable(Object.getOwnPropertySymbols)) {
	          _pushApply(keys, Object.getOwnPropertySymbols(target));
	        }

	        return keys;
	      }
	    });
	  }

	  var callAndCatchException = function ConvertExceptionToBoolean(func) {
	    return !throwsError(func);
	  };

	  if (Object.preventExtensions) {
	    Object.assign(ReflectShims, {
	      isExtensible: function isExtensible(target) {
	        throwUnlessTargetIsObject(target);
	        return Object.isExtensible(target);
	      },
	      preventExtensions: function preventExtensions(target) {
	        throwUnlessTargetIsObject(target);
	        return callAndCatchException(function () {
	          Object.preventExtensions(target);
	        });
	      }
	    });
	  }

	  if (supportsDescriptors) {
	    var internalGet = function get(target, key, receiver) {
	      var desc = Object.getOwnPropertyDescriptor(target, key);

	      if (!desc) {
	        var parent = Object.getPrototypeOf(target);

	        if (parent === null) {
	          return void 0;
	        }

	        return internalGet(parent, key, receiver);
	      }

	      if ('value' in desc) {
	        return desc.value;
	      }

	      if (desc.get) {
	        return ES.Call(desc.get, receiver);
	      }

	      return void 0;
	    };

	    var internalSet = function set(target, key, value, receiver) {
	      var desc = Object.getOwnPropertyDescriptor(target, key);

	      if (!desc) {
	        var parent = Object.getPrototypeOf(target);

	        if (parent !== null) {
	          return internalSet(parent, key, value, receiver);
	        }

	        desc = {
	          value: void 0,
	          writable: true,
	          enumerable: true,
	          configurable: true
	        };
	      }

	      if ('value' in desc) {
	        if (!desc.writable) {
	          return false;
	        }

	        if (!ES.TypeIsObject(receiver)) {
	          return false;
	        }

	        var existingDesc = Object.getOwnPropertyDescriptor(receiver, key);

	        if (existingDesc) {
	          return Reflect.defineProperty(receiver, key, {
	            value: value
	          });
	        } else {
	          return Reflect.defineProperty(receiver, key, {
	            value: value,
	            writable: true,
	            enumerable: true,
	            configurable: true
	          });
	        }
	      }

	      if (desc.set) {
	        _call(desc.set, receiver, value);
	        return true;
	      }

	      return false;
	    };

	    Object.assign(ReflectShims, {
	      defineProperty: function defineProperty(target, propertyKey, attributes) {
	        throwUnlessTargetIsObject(target);
	        return callAndCatchException(function () {
	          Object.defineProperty(target, propertyKey, attributes);
	        });
	      },

	      getOwnPropertyDescriptor: function getOwnPropertyDescriptor(target, propertyKey) {
	        throwUnlessTargetIsObject(target);
	        return Object.getOwnPropertyDescriptor(target, propertyKey);
	      },

	      // Syntax in a functional form.
	      get: function get(target, key) {
	        throwUnlessTargetIsObject(target);
	        var receiver = arguments.length > 2 ? arguments[2] : target;

	        return internalGet(target, key, receiver);
	      },

	      set: function set(target, key, value) {
	        throwUnlessTargetIsObject(target);
	        var receiver = arguments.length > 3 ? arguments[3] : target;

	        return internalSet(target, key, value, receiver);
	      }
	    });
	  }

	  if (Object.getPrototypeOf) {
	    var objectDotGetPrototypeOf = Object.getPrototypeOf;
	    ReflectShims.getPrototypeOf = function getPrototypeOf(target) {
	      throwUnlessTargetIsObject(target);
	      return objectDotGetPrototypeOf(target);
	    };
	  }

	  if (Object.setPrototypeOf && ReflectShims.getPrototypeOf) {
	    var willCreateCircularPrototype = function (object, lastProto) {
	      var proto = lastProto;
	      while (proto) {
	        if (object === proto) {
	          return true;
	        }
	        proto = ReflectShims.getPrototypeOf(proto);
	      }
	      return false;
	    };

	    Object.assign(ReflectShims, {
	      // Sets the prototype of the given object.
	      // Returns true on success, otherwise false.
	      setPrototypeOf: function setPrototypeOf(object, proto) {
	        throwUnlessTargetIsObject(object);
	        if (proto !== null && !ES.TypeIsObject(proto)) {
	          throw new TypeError('proto must be an object or null');
	        }

	        // If they already are the same, we're done.
	        if (proto === Reflect.getPrototypeOf(object)) {
	          return true;
	        }

	        // Cannot alter prototype if object not extensible.
	        if (Reflect.isExtensible && !Reflect.isExtensible(object)) {
	          return false;
	        }

	        // Ensure that we do not create a circular prototype chain.
	        if (willCreateCircularPrototype(object, proto)) {
	          return false;
	        }

	        Object.setPrototypeOf(object, proto);

	        return true;
	      }
	    });
	  }
	  var defineOrOverrideReflectProperty = function (key, shim) {
	    if (!ES.IsCallable(globals.Reflect[key])) {
	      defineProperty(globals.Reflect, key, shim);
	    } else {
	      var acceptsPrimitives = valueOrFalseIfThrows(function () {
	        globals.Reflect[key](1);
	        globals.Reflect[key](NaN);
	        globals.Reflect[key](true);
	        return true;
	      });
	      if (acceptsPrimitives) {
	        overrideNative(globals.Reflect, key, shim);
	      }
	    }
	  };
	  Object.keys(ReflectShims).forEach(function (key) {
	    defineOrOverrideReflectProperty(key, ReflectShims[key]);
	  });
	  var originalReflectGetProto = globals.Reflect.getPrototypeOf;
	  if (functionsHaveNames && originalReflectGetProto && originalReflectGetProto.name !== 'getPrototypeOf') {
	    overrideNative(globals.Reflect, 'getPrototypeOf', function getPrototypeOf(target) {
	      return _call(originalReflectGetProto, globals.Reflect, target);
	    });
	  }
	  if (globals.Reflect.setPrototypeOf) {
	    if (valueOrFalseIfThrows(function () {
	      globals.Reflect.setPrototypeOf(1, {});
	      return true;
	    })) {
	      overrideNative(globals.Reflect, 'setPrototypeOf', ReflectShims.setPrototypeOf);
	    }
	  }
	  if (globals.Reflect.defineProperty) {
	    if (!valueOrFalseIfThrows(function () {
	      var basic = !globals.Reflect.defineProperty(1, 'test', { value: 1 });
	      // "extensible" fails on Edge 0.12
	      var extensible = typeof Object.preventExtensions !== 'function' || !globals.Reflect.defineProperty(Object.preventExtensions({}), 'test', {});
	      return basic && extensible;
	    })) {
	      overrideNative(globals.Reflect, 'defineProperty', ReflectShims.defineProperty);
	    }
	  }
	  if (globals.Reflect.construct) {
	    if (!valueOrFalseIfThrows(function () {
	      var F = function F() {};
	      return globals.Reflect.construct(function () {}, [], F) instanceof F;
	    })) {
	      overrideNative(globals.Reflect, 'construct', ReflectShims.construct);
	    }
	  }

	  if (String(new Date(NaN)) !== 'Invalid Date') {
	    var dateToString = Date.prototype.toString;
	    var shimmedDateToString = function toString() {
	      var valueOf = +this;
	      if (valueOf !== valueOf) {
	        return 'Invalid Date';
	      }
	      return ES.Call(dateToString, this);
	    };
	    overrideNative(Date.prototype, 'toString', shimmedDateToString);
	  }

	  // Annex B HTML methods
	  // http://www.ecma-international.org/ecma-262/6.0/#sec-additional-properties-of-the-string.prototype-object
	  var stringHTMLshims = {
	    anchor: function anchor(name) { return ES.CreateHTML(this, 'a', 'name', name); },
	    big: function big() { return ES.CreateHTML(this, 'big', '', ''); },
	    blink: function blink() { return ES.CreateHTML(this, 'blink', '', ''); },
	    bold: function bold() { return ES.CreateHTML(this, 'b', '', ''); },
	    fixed: function fixed() { return ES.CreateHTML(this, 'tt', '', ''); },
	    fontcolor: function fontcolor(color) { return ES.CreateHTML(this, 'font', 'color', color); },
	    fontsize: function fontsize(size) { return ES.CreateHTML(this, 'font', 'size', size); },
	    italics: function italics() { return ES.CreateHTML(this, 'i', '', ''); },
	    link: function link(url) { return ES.CreateHTML(this, 'a', 'href', url); },
	    small: function small() { return ES.CreateHTML(this, 'small', '', ''); },
	    strike: function strike() { return ES.CreateHTML(this, 'strike', '', ''); },
	    sub: function sub() { return ES.CreateHTML(this, 'sub', '', ''); },
	    sup: function sub() { return ES.CreateHTML(this, 'sup', '', ''); }
	  };
	  _forEach(Object.keys(stringHTMLshims), function (key) {
	    var method = String.prototype[key];
	    var shouldOverwrite = false;
	    if (ES.IsCallable(method)) {
	      var output = _call(method, '', ' " ');
	      var quotesCount = _concat([], output.match(/"/g)).length;
	      shouldOverwrite = output !== output.toLowerCase() || quotesCount > 2;
	    } else {
	      shouldOverwrite = true;
	    }
	    if (shouldOverwrite) {
	      overrideNative(String.prototype, key, stringHTMLshims[key]);
	    }
	  });

	  var JSONstringifiesSymbols = (function () {
	    // Microsoft Edge v0.12 stringifies Symbols incorrectly
	    if (!hasSymbols) { return false; } // Symbols are not supported
	    var stringify = typeof JSON === 'object' && typeof JSON.stringify === 'function' ? JSON.stringify : null;
	    if (!stringify) { return false; } // JSON.stringify is not supported
	    if (typeof stringify(Symbol()) !== 'undefined') { return true; } // Symbols should become `undefined`
	    if (stringify([Symbol()]) !== '[null]') { return true; } // Symbols in arrays should become `null`
	    var obj = { a: Symbol() };
	    obj[Symbol()] = true;
	    if (stringify(obj) !== '{}') { return true; } // Symbol-valued keys *and* Symbol-valued properties should be omitted
	    return false;
	  }());
	  var JSONstringifyAcceptsObjectSymbol = valueOrFalseIfThrows(function () {
	    // Chrome 45 throws on stringifying object symbols
	    if (!hasSymbols) { return true; } // Symbols are not supported
	    return JSON.stringify(Object(Symbol())) === '{}' && JSON.stringify([Object(Symbol())]) === '[{}]';
	  });
	  if (JSONstringifiesSymbols || !JSONstringifyAcceptsObjectSymbol) {
	    var origStringify = JSON.stringify;
	    overrideNative(JSON, 'stringify', function stringify(value) {
	      if (typeof value === 'symbol') { return; }
	      var replacer;
	      if (arguments.length > 1) {
	        replacer = arguments[1];
	      }
	      var args = [value];
	      if (!isArray(replacer)) {
	        var replaceFn = ES.IsCallable(replacer) ? replacer : null;
	        var wrappedReplacer = function (key, val) {
	          var parsedValue = replaceFn ? _call(replaceFn, this, key, val) : val;
	          if (typeof parsedValue !== 'symbol') {
	            if (Type.symbol(parsedValue)) {
	              return assignTo({})(parsedValue);
	            } else {
	              return parsedValue;
	            }
	          }
	        };
	        args.push(wrappedReplacer);
	      } else {
	        // create wrapped replacer that handles an array replacer?
	        args.push(replacer);
	      }
	      if (arguments.length > 2) {
	        args.push(arguments[2]);
	      }
	      return origStringify.apply(this, args);
	    });
	  }

	  return globals;
	}));

	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }()), __webpack_require__(2)))

/***/ },
/* 2 */
/***/ function(module, exports) {

	// shim for using process in browser
	var process = module.exports = {};

	// cached from whatever global is present so that test runners that stub it
	// don't break things.  But we need to wrap it in a try catch in case it is
	// wrapped in strict mode code which doesn't define any globals.  It's inside a
	// function because try/catches deoptimize in certain engines.

	var cachedSetTimeout;
	var cachedClearTimeout;

	function defaultSetTimout() {
	    throw new Error('setTimeout has not been defined');
	}
	function defaultClearTimeout () {
	    throw new Error('clearTimeout has not been defined');
	}
	(function () {
	    try {
	        if (typeof setTimeout === 'function') {
	            cachedSetTimeout = setTimeout;
	        } else {
	            cachedSetTimeout = defaultSetTimout;
	        }
	    } catch (e) {
	        cachedSetTimeout = defaultSetTimout;
	    }
	    try {
	        if (typeof clearTimeout === 'function') {
	            cachedClearTimeout = clearTimeout;
	        } else {
	            cachedClearTimeout = defaultClearTimeout;
	        }
	    } catch (e) {
	        cachedClearTimeout = defaultClearTimeout;
	    }
	} ())
	function runTimeout(fun) {
	    if (cachedSetTimeout === setTimeout) {
	        //normal enviroments in sane situations
	        return setTimeout(fun, 0);
	    }
	    // if setTimeout wasn't available but was latter defined
	    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
	        cachedSetTimeout = setTimeout;
	        return setTimeout(fun, 0);
	    }
	    try {
	        // when when somebody has screwed with setTimeout but no I.E. maddness
	        return cachedSetTimeout(fun, 0);
	    } catch(e){
	        try {
	            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
	            return cachedSetTimeout.call(null, fun, 0);
	        } catch(e){
	            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
	            return cachedSetTimeout.call(this, fun, 0);
	        }
	    }


	}
	function runClearTimeout(marker) {
	    if (cachedClearTimeout === clearTimeout) {
	        //normal enviroments in sane situations
	        return clearTimeout(marker);
	    }
	    // if clearTimeout wasn't available but was latter defined
	    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
	        cachedClearTimeout = clearTimeout;
	        return clearTimeout(marker);
	    }
	    try {
	        // when when somebody has screwed with setTimeout but no I.E. maddness
	        return cachedClearTimeout(marker);
	    } catch (e){
	        try {
	            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
	            return cachedClearTimeout.call(null, marker);
	        } catch (e){
	            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
	            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
	            return cachedClearTimeout.call(this, marker);
	        }
	    }



	}
	var queue = [];
	var draining = false;
	var currentQueue;
	var queueIndex = -1;

	function cleanUpNextTick() {
	    if (!draining || !currentQueue) {
	        return;
	    }
	    draining = false;
	    if (currentQueue.length) {
	        queue = currentQueue.concat(queue);
	    } else {
	        queueIndex = -1;
	    }
	    if (queue.length) {
	        drainQueue();
	    }
	}

	function drainQueue() {
	    if (draining) {
	        return;
	    }
	    var timeout = runTimeout(cleanUpNextTick);
	    draining = true;

	    var len = queue.length;
	    while(len) {
	        currentQueue = queue;
	        queue = [];
	        while (++queueIndex < len) {
	            if (currentQueue) {
	                currentQueue[queueIndex].run();
	            }
	        }
	        queueIndex = -1;
	        len = queue.length;
	    }
	    currentQueue = null;
	    draining = false;
	    runClearTimeout(timeout);
	}

	process.nextTick = function (fun) {
	    var args = new Array(arguments.length - 1);
	    if (arguments.length > 1) {
	        for (var i = 1; i < arguments.length; i++) {
	            args[i - 1] = arguments[i];
	        }
	    }
	    queue.push(new Item(fun, args));
	    if (queue.length === 1 && !draining) {
	        runTimeout(drainQueue);
	    }
	};

	// v8 likes predictible objects
	function Item(fun, array) {
	    this.fun = fun;
	    this.array = array;
	}
	Item.prototype.run = function () {
	    this.fun.apply(null, this.array);
	};
	process.title = 'browser';
	process.browser = true;
	process.env = {};
	process.argv = [];
	process.version = ''; // empty string to avoid regexp issues
	process.versions = {};

	function noop() {}

	process.on = noop;
	process.addListener = noop;
	process.once = noop;
	process.off = noop;
	process.removeListener = noop;
	process.removeAllListeners = noop;
	process.emit = noop;

	process.binding = function (name) {
	    throw new Error('process.binding is not supported');
	};

	process.cwd = function () { return '/' };
	process.chdir = function (dir) {
	    throw new Error('process.chdir is not supported');
	};
	process.umask = function() { return 0; };


/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {'use strict';

	exports.__esModule = true;
	exports.compose = exports.applyMiddleware = exports.bindActionCreators = exports.combineReducers = exports.createStore = undefined;

	var _createStore = __webpack_require__(4);

	var _createStore2 = _interopRequireDefault(_createStore);

	var _combineReducers = __webpack_require__(13);

	var _combineReducers2 = _interopRequireDefault(_combineReducers);

	var _bindActionCreators = __webpack_require__(15);

	var _bindActionCreators2 = _interopRequireDefault(_bindActionCreators);

	var _applyMiddleware = __webpack_require__(16);

	var _applyMiddleware2 = _interopRequireDefault(_applyMiddleware);

	var _compose = __webpack_require__(17);

	var _compose2 = _interopRequireDefault(_compose);

	var _warning = __webpack_require__(14);

	var _warning2 = _interopRequireDefault(_warning);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

	/*
	* This is a dummy function to check if the function name has been altered by minification.
	* If the function has been minified and NODE_ENV !== 'production', warn the user.
	*/
	function isCrushed() {}

	if (process.env.NODE_ENV !== 'production' && typeof isCrushed.name === 'string' && isCrushed.name !== 'isCrushed') {
	  (0, _warning2['default'])('You are currently using minified code outside of NODE_ENV === \'production\'. ' + 'This means that you are running a slower development build of Redux. ' + 'You can use loose-envify (https://github.com/zertosh/loose-envify) for browserify ' + 'or DefinePlugin for webpack (http://stackoverflow.com/questions/30030031) ' + 'to ensure you have the correct code for your production build.');
	}

	exports.createStore = _createStore2['default'];
	exports.combineReducers = _combineReducers2['default'];
	exports.bindActionCreators = _bindActionCreators2['default'];
	exports.applyMiddleware = _applyMiddleware2['default'];
	exports.compose = _compose2['default'];
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	exports.__esModule = true;
	exports.ActionTypes = undefined;
	exports['default'] = createStore;

	var _isPlainObject = __webpack_require__(5);

	var _isPlainObject2 = _interopRequireDefault(_isPlainObject);

	var _symbolObservable = __webpack_require__(9);

	var _symbolObservable2 = _interopRequireDefault(_symbolObservable);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

	/**
	 * These are private action types reserved by Redux.
	 * For any unknown actions, you must return the current state.
	 * If the current state is undefined, you must return the initial state.
	 * Do not reference these action types directly in your code.
	 */
	var ActionTypes = exports.ActionTypes = {
	  INIT: '@@redux/INIT'
	};

	/**
	 * Creates a Redux store that holds the state tree.
	 * The only way to change the data in the store is to call `dispatch()` on it.
	 *
	 * There should only be a single store in your app. To specify how different
	 * parts of the state tree respond to actions, you may combine several reducers
	 * into a single reducer function by using `combineReducers`.
	 *
	 * @param {Function} reducer A function that returns the next state tree, given
	 * the current state tree and the action to handle.
	 *
	 * @param {any} [preloadedState] The initial state. You may optionally specify it
	 * to hydrate the state from the server in universal apps, or to restore a
	 * previously serialized user session.
	 * If you use `combineReducers` to produce the root reducer function, this must be
	 * an object with the same shape as `combineReducers` keys.
	 *
	 * @param {Function} enhancer The store enhancer. You may optionally specify it
	 * to enhance the store with third-party capabilities such as middleware,
	 * time travel, persistence, etc. The only store enhancer that ships with Redux
	 * is `applyMiddleware()`.
	 *
	 * @returns {Store} A Redux store that lets you read the state, dispatch actions
	 * and subscribe to changes.
	 */
	function createStore(reducer, preloadedState, enhancer) {
	  var _ref2;

	  if (typeof preloadedState === 'function' && typeof enhancer === 'undefined') {
	    enhancer = preloadedState;
	    preloadedState = undefined;
	  }

	  if (typeof enhancer !== 'undefined') {
	    if (typeof enhancer !== 'function') {
	      throw new Error('Expected the enhancer to be a function.');
	    }

	    return enhancer(createStore)(reducer, preloadedState);
	  }

	  if (typeof reducer !== 'function') {
	    throw new Error('Expected the reducer to be a function.');
	  }

	  var currentReducer = reducer;
	  var currentState = preloadedState;
	  var currentListeners = [];
	  var nextListeners = currentListeners;
	  var isDispatching = false;

	  function ensureCanMutateNextListeners() {
	    if (nextListeners === currentListeners) {
	      nextListeners = currentListeners.slice();
	    }
	  }

	  /**
	   * Reads the state tree managed by the store.
	   *
	   * @returns {any} The current state tree of your application.
	   */
	  function getState() {
	    return currentState;
	  }

	  /**
	   * Adds a change listener. It will be called any time an action is dispatched,
	   * and some part of the state tree may potentially have changed. You may then
	   * call `getState()` to read the current state tree inside the callback.
	   *
	   * You may call `dispatch()` from a change listener, with the following
	   * caveats:
	   *
	   * 1. The subscriptions are snapshotted just before every `dispatch()` call.
	   * If you subscribe or unsubscribe while the listeners are being invoked, this
	   * will not have any effect on the `dispatch()` that is currently in progress.
	   * However, the next `dispatch()` call, whether nested or not, will use a more
	   * recent snapshot of the subscription list.
	   *
	   * 2. The listener should not expect to see all state changes, as the state
	   * might have been updated multiple times during a nested `dispatch()` before
	   * the listener is called. It is, however, guaranteed that all subscribers
	   * registered before the `dispatch()` started will be called with the latest
	   * state by the time it exits.
	   *
	   * @param {Function} listener A callback to be invoked on every dispatch.
	   * @returns {Function} A function to remove this change listener.
	   */
	  function subscribe(listener) {
	    if (typeof listener !== 'function') {
	      throw new Error('Expected listener to be a function.');
	    }

	    var isSubscribed = true;

	    ensureCanMutateNextListeners();
	    nextListeners.push(listener);

	    return function unsubscribe() {
	      if (!isSubscribed) {
	        return;
	      }

	      isSubscribed = false;

	      ensureCanMutateNextListeners();
	      var index = nextListeners.indexOf(listener);
	      nextListeners.splice(index, 1);
	    };
	  }

	  /**
	   * Dispatches an action. It is the only way to trigger a state change.
	   *
	   * The `reducer` function, used to create the store, will be called with the
	   * current state tree and the given `action`. Its return value will
	   * be considered the **next** state of the tree, and the change listeners
	   * will be notified.
	   *
	   * The base implementation only supports plain object actions. If you want to
	   * dispatch a Promise, an Observable, a thunk, or something else, you need to
	   * wrap your store creating function into the corresponding middleware. For
	   * example, see the documentation for the `redux-thunk` package. Even the
	   * middleware will eventually dispatch plain object actions using this method.
	   *
	   * @param {Object} action A plain object representing “what changed”. It is
	   * a good idea to keep actions serializable so you can record and replay user
	   * sessions, or use the time travelling `redux-devtools`. An action must have
	   * a `type` property which may not be `undefined`. It is a good idea to use
	   * string constants for action types.
	   *
	   * @returns {Object} For convenience, the same action object you dispatched.
	   *
	   * Note that, if you use a custom middleware, it may wrap `dispatch()` to
	   * return something else (for example, a Promise you can await).
	   */
	  function dispatch(action) {
	    if (!(0, _isPlainObject2['default'])(action)) {
	      throw new Error('Actions must be plain objects. ' + 'Use custom middleware for async actions.');
	    }

	    if (typeof action.type === 'undefined') {
	      throw new Error('Actions may not have an undefined "type" property. ' + 'Have you misspelled a constant?');
	    }

	    if (isDispatching) {
	      throw new Error('Reducers may not dispatch actions.');
	    }

	    try {
	      isDispatching = true;
	      currentState = currentReducer(currentState, action);
	    } finally {
	      isDispatching = false;
	    }

	    var listeners = currentListeners = nextListeners;
	    for (var i = 0; i < listeners.length; i++) {
	      listeners[i]();
	    }

	    return action;
	  }

	  /**
	   * Replaces the reducer currently used by the store to calculate the state.
	   *
	   * You might need this if your app implements code splitting and you want to
	   * load some of the reducers dynamically. You might also need this if you
	   * implement a hot reloading mechanism for Redux.
	   *
	   * @param {Function} nextReducer The reducer for the store to use instead.
	   * @returns {void}
	   */
	  function replaceReducer(nextReducer) {
	    if (typeof nextReducer !== 'function') {
	      throw new Error('Expected the nextReducer to be a function.');
	    }

	    currentReducer = nextReducer;
	    dispatch({ type: ActionTypes.INIT });
	  }

	  /**
	   * Interoperability point for observable/reactive libraries.
	   * @returns {observable} A minimal observable of state changes.
	   * For more information, see the observable proposal:
	   * https://github.com/zenparsing/es-observable
	   */
	  function observable() {
	    var _ref;

	    var outerSubscribe = subscribe;
	    return _ref = {
	      /**
	       * The minimal observable subscription method.
	       * @param {Object} observer Any object that can be used as an observer.
	       * The observer object should have a `next` method.
	       * @returns {subscription} An object with an `unsubscribe` method that can
	       * be used to unsubscribe the observable from the store, and prevent further
	       * emission of values from the observable.
	       */
	      subscribe: function subscribe(observer) {
	        if (typeof observer !== 'object') {
	          throw new TypeError('Expected the observer to be an object.');
	        }

	        function observeState() {
	          if (observer.next) {
	            observer.next(getState());
	          }
	        }

	        observeState();
	        var unsubscribe = outerSubscribe(observeState);
	        return { unsubscribe: unsubscribe };
	      }
	    }, _ref[_symbolObservable2['default']] = function () {
	      return this;
	    }, _ref;
	  }

	  // When a store is created, an "INIT" action is dispatched so that every
	  // reducer returns their initial state. This effectively populates
	  // the initial state tree.
	  dispatch({ type: ActionTypes.INIT });

	  return _ref2 = {
	    dispatch: dispatch,
	    subscribe: subscribe,
	    getState: getState,
	    replaceReducer: replaceReducer
	  }, _ref2[_symbolObservable2['default']] = observable, _ref2;
	}

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

	var getPrototype = __webpack_require__(6),
	    isHostObject = __webpack_require__(7),
	    isObjectLike = __webpack_require__(8);

	/** `Object#toString` result references. */
	var objectTag = '[object Object]';

	/** Used for built-in method references. */
	var objectProto = Object.prototype;

	/** Used to resolve the decompiled source of functions. */
	var funcToString = Function.prototype.toString;

	/** Used to check objects for own properties. */
	var hasOwnProperty = objectProto.hasOwnProperty;

	/** Used to infer the `Object` constructor. */
	var objectCtorString = funcToString.call(Object);

	/**
	 * Used to resolve the
	 * [`toStringTag`](http://ecma-international.org/ecma-262/6.0/#sec-object.prototype.tostring)
	 * of values.
	 */
	var objectToString = objectProto.toString;

	/**
	 * Checks if `value` is a plain object, that is, an object created by the
	 * `Object` constructor or one with a `[[Prototype]]` of `null`.
	 *
	 * @static
	 * @memberOf _
	 * @since 0.8.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a plain object,
	 *  else `false`.
	 * @example
	 *
	 * function Foo() {
	 *   this.a = 1;
	 * }
	 *
	 * _.isPlainObject(new Foo);
	 * // => false
	 *
	 * _.isPlainObject([1, 2, 3]);
	 * // => false
	 *
	 * _.isPlainObject({ 'x': 0, 'y': 0 });
	 * // => true
	 *
	 * _.isPlainObject(Object.create(null));
	 * // => true
	 */
	function isPlainObject(value) {
	  if (!isObjectLike(value) ||
	      objectToString.call(value) != objectTag || isHostObject(value)) {
	    return false;
	  }
	  var proto = getPrototype(value);
	  if (proto === null) {
	    return true;
	  }
	  var Ctor = hasOwnProperty.call(proto, 'constructor') && proto.constructor;
	  return (typeof Ctor == 'function' &&
	    Ctor instanceof Ctor && funcToString.call(Ctor) == objectCtorString);
	}

	module.exports = isPlainObject;


/***/ },
/* 6 */
/***/ function(module, exports) {

	/* Built-in method references for those with the same name as other `lodash` methods. */
	var nativeGetPrototype = Object.getPrototypeOf;

	/**
	 * Gets the `[[Prototype]]` of `value`.
	 *
	 * @private
	 * @param {*} value The value to query.
	 * @returns {null|Object} Returns the `[[Prototype]]`.
	 */
	function getPrototype(value) {
	  return nativeGetPrototype(Object(value));
	}

	module.exports = getPrototype;


/***/ },
/* 7 */
/***/ function(module, exports) {

	/**
	 * Checks if `value` is a host object in IE < 9.
	 *
	 * @private
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is a host object, else `false`.
	 */
	function isHostObject(value) {
	  // Many host objects are `Object` objects that can coerce to strings
	  // despite having improperly defined `toString` methods.
	  var result = false;
	  if (value != null && typeof value.toString != 'function') {
	    try {
	      result = !!(value + '');
	    } catch (e) {}
	  }
	  return result;
	}

	module.exports = isHostObject;


/***/ },
/* 8 */
/***/ function(module, exports) {

	/**
	 * Checks if `value` is object-like. A value is object-like if it's not `null`
	 * and has a `typeof` result of "object".
	 *
	 * @static
	 * @memberOf _
	 * @since 4.0.0
	 * @category Lang
	 * @param {*} value The value to check.
	 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
	 * @example
	 *
	 * _.isObjectLike({});
	 * // => true
	 *
	 * _.isObjectLike([1, 2, 3]);
	 * // => true
	 *
	 * _.isObjectLike(_.noop);
	 * // => false
	 *
	 * _.isObjectLike(null);
	 * // => false
	 */
	function isObjectLike(value) {
	  return !!value && typeof value == 'object';
	}

	module.exports = isObjectLike;


/***/ },
/* 9 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(10);


/***/ },
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(global, module) {'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});

	var _ponyfill = __webpack_require__(12);

	var _ponyfill2 = _interopRequireDefault(_ponyfill);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

	var root; /* global window */


	if (typeof self !== 'undefined') {
	  root = self;
	} else if (typeof window !== 'undefined') {
	  root = window;
	} else if (typeof global !== 'undefined') {
	  root = global;
	} else if (true) {
	  root = module;
	} else {
	  root = Function('return this')();
	}

	var result = (0, _ponyfill2['default'])(root);
	exports['default'] = result;
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }()), __webpack_require__(11)(module)))

/***/ },
/* 11 */
/***/ function(module, exports) {

	module.exports = function(module) {
		if(!module.webpackPolyfill) {
			module.deprecate = function() {};
			module.paths = [];
			// module.parent = undefined by default
			module.children = [];
			module.webpackPolyfill = 1;
		}
		return module;
	}


/***/ },
/* 12 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports['default'] = symbolObservablePonyfill;
	function symbolObservablePonyfill(root) {
		var result;
		var _Symbol = root.Symbol;

		if (typeof _Symbol === 'function') {
			if (_Symbol.observable) {
				result = _Symbol.observable;
			} else {
				result = _Symbol('observable');
				_Symbol.observable = result;
			}
		} else {
			result = '@@observable';
		}

		return result;
	};

/***/ },
/* 13 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {'use strict';

	exports.__esModule = true;
	exports['default'] = combineReducers;

	var _createStore = __webpack_require__(4);

	var _isPlainObject = __webpack_require__(5);

	var _isPlainObject2 = _interopRequireDefault(_isPlainObject);

	var _warning = __webpack_require__(14);

	var _warning2 = _interopRequireDefault(_warning);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

	function getUndefinedStateErrorMessage(key, action) {
	  var actionType = action && action.type;
	  var actionName = actionType && '"' + actionType.toString() + '"' || 'an action';

	  return 'Given action ' + actionName + ', reducer "' + key + '" returned undefined. ' + 'To ignore an action, you must explicitly return the previous state.';
	}

	function getUnexpectedStateShapeWarningMessage(inputState, reducers, action, unexpectedKeyCache) {
	  var reducerKeys = Object.keys(reducers);
	  var argumentName = action && action.type === _createStore.ActionTypes.INIT ? 'preloadedState argument passed to createStore' : 'previous state received by the reducer';

	  if (reducerKeys.length === 0) {
	    return 'Store does not have a valid reducer. Make sure the argument passed ' + 'to combineReducers is an object whose values are reducers.';
	  }

	  if (!(0, _isPlainObject2['default'])(inputState)) {
	    return 'The ' + argumentName + ' has unexpected type of "' + {}.toString.call(inputState).match(/\s([a-z|A-Z]+)/)[1] + '". Expected argument to be an object with the following ' + ('keys: "' + reducerKeys.join('", "') + '"');
	  }

	  var unexpectedKeys = Object.keys(inputState).filter(function (key) {
	    return !reducers.hasOwnProperty(key) && !unexpectedKeyCache[key];
	  });

	  unexpectedKeys.forEach(function (key) {
	    unexpectedKeyCache[key] = true;
	  });

	  if (unexpectedKeys.length > 0) {
	    return 'Unexpected ' + (unexpectedKeys.length > 1 ? 'keys' : 'key') + ' ' + ('"' + unexpectedKeys.join('", "') + '" found in ' + argumentName + '. ') + 'Expected to find one of the known reducer keys instead: ' + ('"' + reducerKeys.join('", "') + '". Unexpected keys will be ignored.');
	  }
	}

	function assertReducerSanity(reducers) {
	  Object.keys(reducers).forEach(function (key) {
	    var reducer = reducers[key];
	    var initialState = reducer(undefined, { type: _createStore.ActionTypes.INIT });

	    if (typeof initialState === 'undefined') {
	      throw new Error('Reducer "' + key + '" returned undefined during initialization. ' + 'If the state passed to the reducer is undefined, you must ' + 'explicitly return the initial state. The initial state may ' + 'not be undefined.');
	    }

	    var type = '@@redux/PROBE_UNKNOWN_ACTION_' + Math.random().toString(36).substring(7).split('').join('.');
	    if (typeof reducer(undefined, { type: type }) === 'undefined') {
	      throw new Error('Reducer "' + key + '" returned undefined when probed with a random type. ' + ('Don\'t try to handle ' + _createStore.ActionTypes.INIT + ' or other actions in "redux/*" ') + 'namespace. They are considered private. Instead, you must return the ' + 'current state for any unknown actions, unless it is undefined, ' + 'in which case you must return the initial state, regardless of the ' + 'action type. The initial state may not be undefined.');
	    }
	  });
	}

	/**
	 * Turns an object whose values are different reducer functions, into a single
	 * reducer function. It will call every child reducer, and gather their results
	 * into a single state object, whose keys correspond to the keys of the passed
	 * reducer functions.
	 *
	 * @param {Object} reducers An object whose values correspond to different
	 * reducer functions that need to be combined into one. One handy way to obtain
	 * it is to use ES6 `import * as reducers` syntax. The reducers may never return
	 * undefined for any action. Instead, they should return their initial state
	 * if the state passed to them was undefined, and the current state for any
	 * unrecognized action.
	 *
	 * @returns {Function} A reducer function that invokes every reducer inside the
	 * passed object, and builds a state object with the same shape.
	 */
	function combineReducers(reducers) {
	  var reducerKeys = Object.keys(reducers);
	  var finalReducers = {};
	  for (var i = 0; i < reducerKeys.length; i++) {
	    var key = reducerKeys[i];

	    if (process.env.NODE_ENV !== 'production') {
	      if (typeof reducers[key] === 'undefined') {
	        (0, _warning2['default'])('No reducer provided for key "' + key + '"');
	      }
	    }

	    if (typeof reducers[key] === 'function') {
	      finalReducers[key] = reducers[key];
	    }
	  }
	  var finalReducerKeys = Object.keys(finalReducers);

	  if (process.env.NODE_ENV !== 'production') {
	    var unexpectedKeyCache = {};
	  }

	  var sanityError;
	  try {
	    assertReducerSanity(finalReducers);
	  } catch (e) {
	    sanityError = e;
	  }

	  return function combination() {
	    var state = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];
	    var action = arguments[1];

	    if (sanityError) {
	      throw sanityError;
	    }

	    if (process.env.NODE_ENV !== 'production') {
	      var warningMessage = getUnexpectedStateShapeWarningMessage(state, finalReducers, action, unexpectedKeyCache);
	      if (warningMessage) {
	        (0, _warning2['default'])(warningMessage);
	      }
	    }

	    var hasChanged = false;
	    var nextState = {};
	    for (var i = 0; i < finalReducerKeys.length; i++) {
	      var key = finalReducerKeys[i];
	      var reducer = finalReducers[key];
	      var previousStateForKey = state[key];
	      var nextStateForKey = reducer(previousStateForKey, action);
	      if (typeof nextStateForKey === 'undefined') {
	        var errorMessage = getUndefinedStateErrorMessage(key, action);
	        throw new Error(errorMessage);
	      }
	      nextState[key] = nextStateForKey;
	      hasChanged = hasChanged || nextStateForKey !== previousStateForKey;
	    }
	    return hasChanged ? nextState : state;
	  };
	}
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 14 */
/***/ function(module, exports) {

	'use strict';

	exports.__esModule = true;
	exports['default'] = warning;
	/**
	 * Prints a warning in the console if it exists.
	 *
	 * @param {String} message The warning message.
	 * @returns {void}
	 */
	function warning(message) {
	  /* eslint-disable no-console */
	  if (typeof console !== 'undefined' && typeof console.error === 'function') {
	    console.error(message);
	  }
	  /* eslint-enable no-console */
	  try {
	    // This error was thrown as a convenience so that if you enable
	    // "break on all exceptions" in your console,
	    // it would pause the execution at this line.
	    throw new Error(message);
	    /* eslint-disable no-empty */
	  } catch (e) {}
	  /* eslint-enable no-empty */
	}

/***/ },
/* 15 */
/***/ function(module, exports) {

	'use strict';

	exports.__esModule = true;
	exports['default'] = bindActionCreators;
	function bindActionCreator(actionCreator, dispatch) {
	  return function () {
	    return dispatch(actionCreator.apply(undefined, arguments));
	  };
	}

	/**
	 * Turns an object whose values are action creators, into an object with the
	 * same keys, but with every function wrapped into a `dispatch` call so they
	 * may be invoked directly. This is just a convenience method, as you can call
	 * `store.dispatch(MyActionCreators.doSomething())` yourself just fine.
	 *
	 * For convenience, you can also pass a single function as the first argument,
	 * and get a function in return.
	 *
	 * @param {Function|Object} actionCreators An object whose values are action
	 * creator functions. One handy way to obtain it is to use ES6 `import * as`
	 * syntax. You may also pass a single function.
	 *
	 * @param {Function} dispatch The `dispatch` function available on your Redux
	 * store.
	 *
	 * @returns {Function|Object} The object mimicking the original object, but with
	 * every action creator wrapped into the `dispatch` call. If you passed a
	 * function as `actionCreators`, the return value will also be a single
	 * function.
	 */
	function bindActionCreators(actionCreators, dispatch) {
	  if (typeof actionCreators === 'function') {
	    return bindActionCreator(actionCreators, dispatch);
	  }

	  if (typeof actionCreators !== 'object' || actionCreators === null) {
	    throw new Error('bindActionCreators expected an object or a function, instead received ' + (actionCreators === null ? 'null' : typeof actionCreators) + '. ' + 'Did you write "import ActionCreators from" instead of "import * as ActionCreators from"?');
	  }

	  var keys = Object.keys(actionCreators);
	  var boundActionCreators = {};
	  for (var i = 0; i < keys.length; i++) {
	    var key = keys[i];
	    var actionCreator = actionCreators[key];
	    if (typeof actionCreator === 'function') {
	      boundActionCreators[key] = bindActionCreator(actionCreator, dispatch);
	    }
	  }
	  return boundActionCreators;
	}

/***/ },
/* 16 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	exports.__esModule = true;

	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

	exports['default'] = applyMiddleware;

	var _compose = __webpack_require__(17);

	var _compose2 = _interopRequireDefault(_compose);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

	/**
	 * Creates a store enhancer that applies middleware to the dispatch method
	 * of the Redux store. This is handy for a variety of tasks, such as expressing
	 * asynchronous actions in a concise manner, or logging every action payload.
	 *
	 * See `redux-thunk` package as an example of the Redux middleware.
	 *
	 * Because middleware is potentially asynchronous, this should be the first
	 * store enhancer in the composition chain.
	 *
	 * Note that each middleware will be given the `dispatch` and `getState` functions
	 * as named arguments.
	 *
	 * @param {...Function} middlewares The middleware chain to be applied.
	 * @returns {Function} A store enhancer applying the middleware.
	 */
	function applyMiddleware() {
	  for (var _len = arguments.length, middlewares = Array(_len), _key = 0; _key < _len; _key++) {
	    middlewares[_key] = arguments[_key];
	  }

	  return function (createStore) {
	    return function (reducer, preloadedState, enhancer) {
	      var store = createStore(reducer, preloadedState, enhancer);
	      var _dispatch = store.dispatch;
	      var chain = [];

	      var middlewareAPI = {
	        getState: store.getState,
	        dispatch: function dispatch(action) {
	          return _dispatch(action);
	        }
	      };
	      chain = middlewares.map(function (middleware) {
	        return middleware(middlewareAPI);
	      });
	      _dispatch = _compose2['default'].apply(undefined, chain)(store.dispatch);

	      return _extends({}, store, {
	        dispatch: _dispatch
	      });
	    };
	  };
	}

/***/ },
/* 17 */
/***/ function(module, exports) {

	"use strict";

	exports.__esModule = true;
	exports["default"] = compose;
	/**
	 * Composes single-argument functions from right to left. The rightmost
	 * function can take multiple arguments as it provides the signature for
	 * the resulting composite function.
	 *
	 * @param {...Function} funcs The functions to compose.
	 * @returns {Function} A function obtained by composing the argument functions
	 * from right to left. For example, compose(f, g, h) is identical to doing
	 * (...args) => f(g(h(...args))).
	 */

	function compose() {
	  for (var _len = arguments.length, funcs = Array(_len), _key = 0; _key < _len; _key++) {
	    funcs[_key] = arguments[_key];
	  }

	  if (funcs.length === 0) {
	    return function (arg) {
	      return arg;
	    };
	  }

	  if (funcs.length === 1) {
	    return funcs[0];
	  }

	  var last = funcs[funcs.length - 1];
	  var rest = funcs.slice(0, -1);
	  return function () {
	    return rest.reduceRight(function (composed, f) {
	      return f(composed);
	    }, last.apply(undefined, arguments));
	  };
	}

/***/ },
/* 18 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	exports.__esModule = true;
	exports.connect = exports.Provider = undefined;

	var _Provider = __webpack_require__(19);

	var _Provider2 = _interopRequireDefault(_Provider);

	var _connect = __webpack_require__(52);

	var _connect2 = _interopRequireDefault(_connect);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

	exports.Provider = _Provider2["default"];
	exports.connect = _connect2["default"];

/***/ },
/* 19 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {'use strict';

	exports.__esModule = true;
	exports["default"] = undefined;

	var _react = __webpack_require__(20);

	var _storeShape = __webpack_require__(50);

	var _storeShape2 = _interopRequireDefault(_storeShape);

	var _warning = __webpack_require__(51);

	var _warning2 = _interopRequireDefault(_warning);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var didWarnAboutReceivingStore = false;
	function warnAboutReceivingStore() {
	  if (didWarnAboutReceivingStore) {
	    return;
	  }
	  didWarnAboutReceivingStore = true;

	  (0, _warning2["default"])('<Provider> does not support changing `store` on the fly. ' + 'It is most likely that you see this error because you updated to ' + 'Redux 2.x and React Redux 2.x which no longer hot reload reducers ' + 'automatically. See https://github.com/reactjs/react-redux/releases/' + 'tag/v2.0.0 for the migration instructions.');
	}

	var Provider = function (_Component) {
	  _inherits(Provider, _Component);

	  Provider.prototype.getChildContext = function getChildContext() {
	    return { store: this.store };
	  };

	  function Provider(props, context) {
	    _classCallCheck(this, Provider);

	    var _this = _possibleConstructorReturn(this, _Component.call(this, props, context));

	    _this.store = props.store;
	    return _this;
	  }

	  Provider.prototype.render = function render() {
	    return _react.Children.only(this.props.children);
	  };

	  return Provider;
	}(_react.Component);

	exports["default"] = Provider;


	if (process.env.NODE_ENV !== 'production') {
	  Provider.prototype.componentWillReceiveProps = function (nextProps) {
	    var store = this.store;
	    var nextStore = nextProps.store;


	    if (store !== nextStore) {
	      warnAboutReceivingStore();
	    }
	  };
	}

	Provider.propTypes = {
	  store: _storeShape2["default"].isRequired,
	  children: _react.PropTypes.element.isRequired
	};
	Provider.childContextTypes = {
	  store: _storeShape2["default"].isRequired
	};
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 20 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = __webpack_require__(21);


/***/ },
/* 21 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _assign = __webpack_require__(22);

	var ReactChildren = __webpack_require__(23);
	var ReactComponent = __webpack_require__(36);
	var ReactPureComponent = __webpack_require__(39);
	var ReactClass = __webpack_require__(40);
	var ReactDOMFactories = __webpack_require__(42);
	var ReactElement = __webpack_require__(27);
	var ReactPropTypes = __webpack_require__(47);
	var ReactVersion = __webpack_require__(48);

	var onlyChild = __webpack_require__(49);
	var warning = __webpack_require__(29);

	var createElement = ReactElement.createElement;
	var createFactory = ReactElement.createFactory;
	var cloneElement = ReactElement.cloneElement;

	if (process.env.NODE_ENV !== 'production') {
	  var ReactElementValidator = __webpack_require__(43);
	  createElement = ReactElementValidator.createElement;
	  createFactory = ReactElementValidator.createFactory;
	  cloneElement = ReactElementValidator.cloneElement;
	}

	var __spread = _assign;

	if (process.env.NODE_ENV !== 'production') {
	  var warned = false;
	  __spread = function () {
	    process.env.NODE_ENV !== 'production' ? warning(warned, 'React.__spread is deprecated and should not be used. Use ' + 'Object.assign directly or another helper function with similar ' + 'semantics. You may be seeing this warning due to your compiler. ' + 'See https://fb.me/react-spread-deprecation for more details.') : void 0;
	    warned = true;
	    return _assign.apply(null, arguments);
	  };
	}

	var React = {

	  // Modern

	  Children: {
	    map: ReactChildren.map,
	    forEach: ReactChildren.forEach,
	    count: ReactChildren.count,
	    toArray: ReactChildren.toArray,
	    only: onlyChild
	  },

	  Component: ReactComponent,
	  PureComponent: ReactPureComponent,

	  createElement: createElement,
	  cloneElement: cloneElement,
	  isValidElement: ReactElement.isValidElement,

	  // Classic

	  PropTypes: ReactPropTypes,
	  createClass: ReactClass.createClass,
	  createFactory: createFactory,
	  createMixin: function (mixin) {
	    // Currently a noop. Will be used to validate and trace mixins.
	    return mixin;
	  },

	  // This looks DOM specific but these are actually isomorphic helpers
	  // since they are just generating DOM strings.
	  DOM: ReactDOMFactories,

	  version: ReactVersion,

	  // Deprecated hook for JSX spread, don't use this for anything.
	  __spread: __spread
	};

	module.exports = React;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 22 */
/***/ function(module, exports) {

	'use strict';
	/* eslint-disable no-unused-vars */
	var hasOwnProperty = Object.prototype.hasOwnProperty;
	var propIsEnumerable = Object.prototype.propertyIsEnumerable;

	function toObject(val) {
		if (val === null || val === undefined) {
			throw new TypeError('Object.assign cannot be called with null or undefined');
		}

		return Object(val);
	}

	function shouldUseNative() {
		try {
			if (!Object.assign) {
				return false;
			}

			// Detect buggy property enumeration order in older V8 versions.

			// https://bugs.chromium.org/p/v8/issues/detail?id=4118
			var test1 = new String('abc');  // eslint-disable-line
			test1[5] = 'de';
			if (Object.getOwnPropertyNames(test1)[0] === '5') {
				return false;
			}

			// https://bugs.chromium.org/p/v8/issues/detail?id=3056
			var test2 = {};
			for (var i = 0; i < 10; i++) {
				test2['_' + String.fromCharCode(i)] = i;
			}
			var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
				return test2[n];
			});
			if (order2.join('') !== '0123456789') {
				return false;
			}

			// https://bugs.chromium.org/p/v8/issues/detail?id=3056
			var test3 = {};
			'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
				test3[letter] = letter;
			});
			if (Object.keys(Object.assign({}, test3)).join('') !==
					'abcdefghijklmnopqrst') {
				return false;
			}

			return true;
		} catch (e) {
			// We don't expect any of the above to throw, but better to be safe.
			return false;
		}
	}

	module.exports = shouldUseNative() ? Object.assign : function (target, source) {
		var from;
		var to = toObject(target);
		var symbols;

		for (var s = 1; s < arguments.length; s++) {
			from = Object(arguments[s]);

			for (var key in from) {
				if (hasOwnProperty.call(from, key)) {
					to[key] = from[key];
				}
			}

			if (Object.getOwnPropertySymbols) {
				symbols = Object.getOwnPropertySymbols(from);
				for (var i = 0; i < symbols.length; i++) {
					if (propIsEnumerable.call(from, symbols[i])) {
						to[symbols[i]] = from[symbols[i]];
					}
				}
			}
		}

		return to;
	};


/***/ },
/* 23 */
/***/ function(module, exports, __webpack_require__) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var PooledClass = __webpack_require__(24);
	var ReactElement = __webpack_require__(27);

	var emptyFunction = __webpack_require__(30);
	var traverseAllChildren = __webpack_require__(33);

	var twoArgumentPooler = PooledClass.twoArgumentPooler;
	var fourArgumentPooler = PooledClass.fourArgumentPooler;

	var userProvidedKeyEscapeRegex = /\/+/g;
	function escapeUserProvidedKey(text) {
	  return ('' + text).replace(userProvidedKeyEscapeRegex, '$&/');
	}

	/**
	 * PooledClass representing the bookkeeping associated with performing a child
	 * traversal. Allows avoiding binding callbacks.
	 *
	 * @constructor ForEachBookKeeping
	 * @param {!function} forEachFunction Function to perform traversal with.
	 * @param {?*} forEachContext Context to perform context with.
	 */
	function ForEachBookKeeping(forEachFunction, forEachContext) {
	  this.func = forEachFunction;
	  this.context = forEachContext;
	  this.count = 0;
	}
	ForEachBookKeeping.prototype.destructor = function () {
	  this.func = null;
	  this.context = null;
	  this.count = 0;
	};
	PooledClass.addPoolingTo(ForEachBookKeeping, twoArgumentPooler);

	function forEachSingleChild(bookKeeping, child, name) {
	  var func = bookKeeping.func,
	      context = bookKeeping.context;

	  func.call(context, child, bookKeeping.count++);
	}

	/**
	 * Iterates through children that are typically specified as `props.children`.
	 *
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.children.foreach
	 *
	 * The provided forEachFunc(child, index) will be called for each
	 * leaf child.
	 *
	 * @param {?*} children Children tree container.
	 * @param {function(*, int)} forEachFunc
	 * @param {*} forEachContext Context for forEachContext.
	 */
	function forEachChildren(children, forEachFunc, forEachContext) {
	  if (children == null) {
	    return children;
	  }
	  var traverseContext = ForEachBookKeeping.getPooled(forEachFunc, forEachContext);
	  traverseAllChildren(children, forEachSingleChild, traverseContext);
	  ForEachBookKeeping.release(traverseContext);
	}

	/**
	 * PooledClass representing the bookkeeping associated with performing a child
	 * mapping. Allows avoiding binding callbacks.
	 *
	 * @constructor MapBookKeeping
	 * @param {!*} mapResult Object containing the ordered map of results.
	 * @param {!function} mapFunction Function to perform mapping with.
	 * @param {?*} mapContext Context to perform mapping with.
	 */
	function MapBookKeeping(mapResult, keyPrefix, mapFunction, mapContext) {
	  this.result = mapResult;
	  this.keyPrefix = keyPrefix;
	  this.func = mapFunction;
	  this.context = mapContext;
	  this.count = 0;
	}
	MapBookKeeping.prototype.destructor = function () {
	  this.result = null;
	  this.keyPrefix = null;
	  this.func = null;
	  this.context = null;
	  this.count = 0;
	};
	PooledClass.addPoolingTo(MapBookKeeping, fourArgumentPooler);

	function mapSingleChildIntoContext(bookKeeping, child, childKey) {
	  var result = bookKeeping.result,
	      keyPrefix = bookKeeping.keyPrefix,
	      func = bookKeeping.func,
	      context = bookKeeping.context;


	  var mappedChild = func.call(context, child, bookKeeping.count++);
	  if (Array.isArray(mappedChild)) {
	    mapIntoWithKeyPrefixInternal(mappedChild, result, childKey, emptyFunction.thatReturnsArgument);
	  } else if (mappedChild != null) {
	    if (ReactElement.isValidElement(mappedChild)) {
	      mappedChild = ReactElement.cloneAndReplaceKey(mappedChild,
	      // Keep both the (mapped) and old keys if they differ, just as
	      // traverseAllChildren used to do for objects as children
	      keyPrefix + (mappedChild.key && (!child || child.key !== mappedChild.key) ? escapeUserProvidedKey(mappedChild.key) + '/' : '') + childKey);
	    }
	    result.push(mappedChild);
	  }
	}

	function mapIntoWithKeyPrefixInternal(children, array, prefix, func, context) {
	  var escapedPrefix = '';
	  if (prefix != null) {
	    escapedPrefix = escapeUserProvidedKey(prefix) + '/';
	  }
	  var traverseContext = MapBookKeeping.getPooled(array, escapedPrefix, func, context);
	  traverseAllChildren(children, mapSingleChildIntoContext, traverseContext);
	  MapBookKeeping.release(traverseContext);
	}

	/**
	 * Maps children that are typically specified as `props.children`.
	 *
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.children.map
	 *
	 * The provided mapFunction(child, key, index) will be called for each
	 * leaf child.
	 *
	 * @param {?*} children Children tree container.
	 * @param {function(*, int)} func The map function.
	 * @param {*} context Context for mapFunction.
	 * @return {object} Object containing the ordered map of results.
	 */
	function mapChildren(children, func, context) {
	  if (children == null) {
	    return children;
	  }
	  var result = [];
	  mapIntoWithKeyPrefixInternal(children, result, null, func, context);
	  return result;
	}

	function forEachSingleChildDummy(traverseContext, child, name) {
	  return null;
	}

	/**
	 * Count the number of children that are typically specified as
	 * `props.children`.
	 *
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.children.count
	 *
	 * @param {?*} children Children tree container.
	 * @return {number} The number of children.
	 */
	function countChildren(children, context) {
	  return traverseAllChildren(children, forEachSingleChildDummy, null);
	}

	/**
	 * Flatten a children object (typically specified as `props.children`) and
	 * return an array with appropriately re-keyed children.
	 *
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.children.toarray
	 */
	function toArray(children) {
	  var result = [];
	  mapIntoWithKeyPrefixInternal(children, result, null, emptyFunction.thatReturnsArgument);
	  return result;
	}

	var ReactChildren = {
	  forEach: forEachChildren,
	  map: mapChildren,
	  mapIntoWithKeyPrefixInternal: mapIntoWithKeyPrefixInternal,
	  count: countChildren,
	  toArray: toArray
	};

	module.exports = ReactChildren;

/***/ },
/* 24 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	var _prodInvariant = __webpack_require__(25);

	var invariant = __webpack_require__(26);

	/**
	 * Static poolers. Several custom versions for each potential number of
	 * arguments. A completely generic pooler is easy to implement, but would
	 * require accessing the `arguments` object. In each of these, `this` refers to
	 * the Class itself, not an instance. If any others are needed, simply add them
	 * here, or in their own files.
	 */
	var oneArgumentPooler = function (copyFieldsFrom) {
	  var Klass = this;
	  if (Klass.instancePool.length) {
	    var instance = Klass.instancePool.pop();
	    Klass.call(instance, copyFieldsFrom);
	    return instance;
	  } else {
	    return new Klass(copyFieldsFrom);
	  }
	};

	var twoArgumentPooler = function (a1, a2) {
	  var Klass = this;
	  if (Klass.instancePool.length) {
	    var instance = Klass.instancePool.pop();
	    Klass.call(instance, a1, a2);
	    return instance;
	  } else {
	    return new Klass(a1, a2);
	  }
	};

	var threeArgumentPooler = function (a1, a2, a3) {
	  var Klass = this;
	  if (Klass.instancePool.length) {
	    var instance = Klass.instancePool.pop();
	    Klass.call(instance, a1, a2, a3);
	    return instance;
	  } else {
	    return new Klass(a1, a2, a3);
	  }
	};

	var fourArgumentPooler = function (a1, a2, a3, a4) {
	  var Klass = this;
	  if (Klass.instancePool.length) {
	    var instance = Klass.instancePool.pop();
	    Klass.call(instance, a1, a2, a3, a4);
	    return instance;
	  } else {
	    return new Klass(a1, a2, a3, a4);
	  }
	};

	var fiveArgumentPooler = function (a1, a2, a3, a4, a5) {
	  var Klass = this;
	  if (Klass.instancePool.length) {
	    var instance = Klass.instancePool.pop();
	    Klass.call(instance, a1, a2, a3, a4, a5);
	    return instance;
	  } else {
	    return new Klass(a1, a2, a3, a4, a5);
	  }
	};

	var standardReleaser = function (instance) {
	  var Klass = this;
	  !(instance instanceof Klass) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Trying to release an instance into a pool of a different type.') : _prodInvariant('25') : void 0;
	  instance.destructor();
	  if (Klass.instancePool.length < Klass.poolSize) {
	    Klass.instancePool.push(instance);
	  }
	};

	var DEFAULT_POOL_SIZE = 10;
	var DEFAULT_POOLER = oneArgumentPooler;

	/**
	 * Augments `CopyConstructor` to be a poolable class, augmenting only the class
	 * itself (statically) not adding any prototypical fields. Any CopyConstructor
	 * you give this may have a `poolSize` property, and will look for a
	 * prototypical `destructor` on instances.
	 *
	 * @param {Function} CopyConstructor Constructor that can be used to reset.
	 * @param {Function} pooler Customizable pooler.
	 */
	var addPoolingTo = function (CopyConstructor, pooler) {
	  // Casting as any so that flow ignores the actual implementation and trusts
	  // it to match the type we declared
	  var NewKlass = CopyConstructor;
	  NewKlass.instancePool = [];
	  NewKlass.getPooled = pooler || DEFAULT_POOLER;
	  if (!NewKlass.poolSize) {
	    NewKlass.poolSize = DEFAULT_POOL_SIZE;
	  }
	  NewKlass.release = standardReleaser;
	  return NewKlass;
	};

	var PooledClass = {
	  addPoolingTo: addPoolingTo,
	  oneArgumentPooler: oneArgumentPooler,
	  twoArgumentPooler: twoArgumentPooler,
	  threeArgumentPooler: threeArgumentPooler,
	  fourArgumentPooler: fourArgumentPooler,
	  fiveArgumentPooler: fiveArgumentPooler
	};

	module.exports = PooledClass;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 25 */
/***/ function(module, exports) {

	/**
	 * Copyright (c) 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */
	'use strict';

	/**
	 * WARNING: DO NOT manually require this module.
	 * This is a replacement for `invariant(...)` used by the error code system
	 * and will _only_ be required by the corresponding babel pass.
	 * It always throws.
	 */

	function reactProdInvariant(code) {
	  var argCount = arguments.length - 1;

	  var message = 'Minified React error #' + code + '; visit ' + 'http://facebook.github.io/react/docs/error-decoder.html?invariant=' + code;

	  for (var argIdx = 0; argIdx < argCount; argIdx++) {
	    message += '&args[]=' + encodeURIComponent(arguments[argIdx + 1]);
	  }

	  message += ' for the full message or use the non-minified dev environment' + ' for full errors and additional helpful warnings.';

	  var error = new Error(message);
	  error.name = 'Invariant Violation';
	  error.framesToPop = 1; // we don't care about reactProdInvariant's own frame

	  throw error;
	}

	module.exports = reactProdInvariant;

/***/ },
/* 26 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright (c) 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	/**
	 * Use invariant() to assert state which your program assumes to be true.
	 *
	 * Provide sprintf-style format (only %s is supported) and arguments
	 * to provide information about what broke and what you were
	 * expecting.
	 *
	 * The invariant message will be stripped in production, but the invariant
	 * will remain to ensure logic does not differ in production.
	 */

	function invariant(condition, format, a, b, c, d, e, f) {
	  if (process.env.NODE_ENV !== 'production') {
	    if (format === undefined) {
	      throw new Error('invariant requires an error message argument');
	    }
	  }

	  if (!condition) {
	    var error;
	    if (format === undefined) {
	      error = new Error('Minified exception occurred; use the non-minified dev environment ' + 'for the full error message and additional helpful warnings.');
	    } else {
	      var args = [a, b, c, d, e, f];
	      var argIndex = 0;
	      error = new Error(format.replace(/%s/g, function () {
	        return args[argIndex++];
	      }));
	      error.name = 'Invariant Violation';
	    }

	    error.framesToPop = 1; // we don't care about invariant's own frame
	    throw error;
	  }
	}

	module.exports = invariant;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 27 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2014-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _assign = __webpack_require__(22);

	var ReactCurrentOwner = __webpack_require__(28);

	var warning = __webpack_require__(29);
	var canDefineProperty = __webpack_require__(31);
	var hasOwnProperty = Object.prototype.hasOwnProperty;

	var REACT_ELEMENT_TYPE = __webpack_require__(32);

	var RESERVED_PROPS = {
	  key: true,
	  ref: true,
	  __self: true,
	  __source: true
	};

	var specialPropKeyWarningShown, specialPropRefWarningShown;

	function hasValidRef(config) {
	  if (process.env.NODE_ENV !== 'production') {
	    if (hasOwnProperty.call(config, 'ref')) {
	      var getter = Object.getOwnPropertyDescriptor(config, 'ref').get;
	      if (getter && getter.isReactWarning) {
	        return false;
	      }
	    }
	  }
	  return config.ref !== undefined;
	}

	function hasValidKey(config) {
	  if (process.env.NODE_ENV !== 'production') {
	    if (hasOwnProperty.call(config, 'key')) {
	      var getter = Object.getOwnPropertyDescriptor(config, 'key').get;
	      if (getter && getter.isReactWarning) {
	        return false;
	      }
	    }
	  }
	  return config.key !== undefined;
	}

	function defineKeyPropWarningGetter(props, displayName) {
	  var warnAboutAccessingKey = function () {
	    if (!specialPropKeyWarningShown) {
	      specialPropKeyWarningShown = true;
	      process.env.NODE_ENV !== 'production' ? warning(false, '%s: `key` is not a prop. Trying to access it will result ' + 'in `undefined` being returned. If you need to access the same ' + 'value within the child component, you should pass it as a different ' + 'prop. (https://fb.me/react-special-props)', displayName) : void 0;
	    }
	  };
	  warnAboutAccessingKey.isReactWarning = true;
	  Object.defineProperty(props, 'key', {
	    get: warnAboutAccessingKey,
	    configurable: true
	  });
	}

	function defineRefPropWarningGetter(props, displayName) {
	  var warnAboutAccessingRef = function () {
	    if (!specialPropRefWarningShown) {
	      specialPropRefWarningShown = true;
	      process.env.NODE_ENV !== 'production' ? warning(false, '%s: `ref` is not a prop. Trying to access it will result ' + 'in `undefined` being returned. If you need to access the same ' + 'value within the child component, you should pass it as a different ' + 'prop. (https://fb.me/react-special-props)', displayName) : void 0;
	    }
	  };
	  warnAboutAccessingRef.isReactWarning = true;
	  Object.defineProperty(props, 'ref', {
	    get: warnAboutAccessingRef,
	    configurable: true
	  });
	}

	/**
	 * Factory method to create a new React element. This no longer adheres to
	 * the class pattern, so do not use new to call it. Also, no instanceof check
	 * will work. Instead test $$typeof field against Symbol.for('react.element') to check
	 * if something is a React Element.
	 *
	 * @param {*} type
	 * @param {*} key
	 * @param {string|object} ref
	 * @param {*} self A *temporary* helper to detect places where `this` is
	 * different from the `owner` when React.createElement is called, so that we
	 * can warn. We want to get rid of owner and replace string `ref`s with arrow
	 * functions, and as long as `this` and owner are the same, there will be no
	 * change in behavior.
	 * @param {*} source An annotation object (added by a transpiler or otherwise)
	 * indicating filename, line number, and/or other information.
	 * @param {*} owner
	 * @param {*} props
	 * @internal
	 */
	var ReactElement = function (type, key, ref, self, source, owner, props) {
	  var element = {
	    // This tag allow us to uniquely identify this as a React Element
	    $$typeof: REACT_ELEMENT_TYPE,

	    // Built-in properties that belong on the element
	    type: type,
	    key: key,
	    ref: ref,
	    props: props,

	    // Record the component responsible for creating this element.
	    _owner: owner
	  };

	  if (process.env.NODE_ENV !== 'production') {
	    // The validation flag is currently mutative. We put it on
	    // an external backing store so that we can freeze the whole object.
	    // This can be replaced with a WeakMap once they are implemented in
	    // commonly used development environments.
	    element._store = {};

	    // To make comparing ReactElements easier for testing purposes, we make
	    // the validation flag non-enumerable (where possible, which should
	    // include every environment we run tests in), so the test framework
	    // ignores it.
	    if (canDefineProperty) {
	      Object.defineProperty(element._store, 'validated', {
	        configurable: false,
	        enumerable: false,
	        writable: true,
	        value: false
	      });
	      // self and source are DEV only properties.
	      Object.defineProperty(element, '_self', {
	        configurable: false,
	        enumerable: false,
	        writable: false,
	        value: self
	      });
	      // Two elements created in two different places should be considered
	      // equal for testing purposes and therefore we hide it from enumeration.
	      Object.defineProperty(element, '_source', {
	        configurable: false,
	        enumerable: false,
	        writable: false,
	        value: source
	      });
	    } else {
	      element._store.validated = false;
	      element._self = self;
	      element._source = source;
	    }
	    if (Object.freeze) {
	      Object.freeze(element.props);
	      Object.freeze(element);
	    }
	  }

	  return element;
	};

	/**
	 * Create and return a new ReactElement of the given type.
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.createelement
	 */
	ReactElement.createElement = function (type, config, children) {
	  var propName;

	  // Reserved names are extracted
	  var props = {};

	  var key = null;
	  var ref = null;
	  var self = null;
	  var source = null;

	  if (config != null) {
	    if (hasValidRef(config)) {
	      ref = config.ref;
	    }
	    if (hasValidKey(config)) {
	      key = '' + config.key;
	    }

	    self = config.__self === undefined ? null : config.__self;
	    source = config.__source === undefined ? null : config.__source;
	    // Remaining properties are added to a new props object
	    for (propName in config) {
	      if (hasOwnProperty.call(config, propName) && !RESERVED_PROPS.hasOwnProperty(propName)) {
	        props[propName] = config[propName];
	      }
	    }
	  }

	  // Children can be more than one argument, and those are transferred onto
	  // the newly allocated props object.
	  var childrenLength = arguments.length - 2;
	  if (childrenLength === 1) {
	    props.children = children;
	  } else if (childrenLength > 1) {
	    var childArray = Array(childrenLength);
	    for (var i = 0; i < childrenLength; i++) {
	      childArray[i] = arguments[i + 2];
	    }
	    if (process.env.NODE_ENV !== 'production') {
	      if (Object.freeze) {
	        Object.freeze(childArray);
	      }
	    }
	    props.children = childArray;
	  }

	  // Resolve default props
	  if (type && type.defaultProps) {
	    var defaultProps = type.defaultProps;
	    for (propName in defaultProps) {
	      if (props[propName] === undefined) {
	        props[propName] = defaultProps[propName];
	      }
	    }
	  }
	  if (process.env.NODE_ENV !== 'production') {
	    if (key || ref) {
	      if (typeof props.$$typeof === 'undefined' || props.$$typeof !== REACT_ELEMENT_TYPE) {
	        var displayName = typeof type === 'function' ? type.displayName || type.name || 'Unknown' : type;
	        if (key) {
	          defineKeyPropWarningGetter(props, displayName);
	        }
	        if (ref) {
	          defineRefPropWarningGetter(props, displayName);
	        }
	      }
	    }
	  }
	  return ReactElement(type, key, ref, self, source, ReactCurrentOwner.current, props);
	};

	/**
	 * Return a function that produces ReactElements of a given type.
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.createfactory
	 */
	ReactElement.createFactory = function (type) {
	  var factory = ReactElement.createElement.bind(null, type);
	  // Expose the type on the factory and the prototype so that it can be
	  // easily accessed on elements. E.g. `<Foo />.type === Foo`.
	  // This should not be named `constructor` since this may not be the function
	  // that created the element, and it may not even be a constructor.
	  // Legacy hook TODO: Warn if this is accessed
	  factory.type = type;
	  return factory;
	};

	ReactElement.cloneAndReplaceKey = function (oldElement, newKey) {
	  var newElement = ReactElement(oldElement.type, newKey, oldElement.ref, oldElement._self, oldElement._source, oldElement._owner, oldElement.props);

	  return newElement;
	};

	/**
	 * Clone and return a new ReactElement using element as the starting point.
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.cloneelement
	 */
	ReactElement.cloneElement = function (element, config, children) {
	  var propName;

	  // Original props are copied
	  var props = _assign({}, element.props);

	  // Reserved names are extracted
	  var key = element.key;
	  var ref = element.ref;
	  // Self is preserved since the owner is preserved.
	  var self = element._self;
	  // Source is preserved since cloneElement is unlikely to be targeted by a
	  // transpiler, and the original source is probably a better indicator of the
	  // true owner.
	  var source = element._source;

	  // Owner will be preserved, unless ref is overridden
	  var owner = element._owner;

	  if (config != null) {
	    if (hasValidRef(config)) {
	      // Silently steal the ref from the parent.
	      ref = config.ref;
	      owner = ReactCurrentOwner.current;
	    }
	    if (hasValidKey(config)) {
	      key = '' + config.key;
	    }

	    // Remaining properties override existing props
	    var defaultProps;
	    if (element.type && element.type.defaultProps) {
	      defaultProps = element.type.defaultProps;
	    }
	    for (propName in config) {
	      if (hasOwnProperty.call(config, propName) && !RESERVED_PROPS.hasOwnProperty(propName)) {
	        if (config[propName] === undefined && defaultProps !== undefined) {
	          // Resolve default props
	          props[propName] = defaultProps[propName];
	        } else {
	          props[propName] = config[propName];
	        }
	      }
	    }
	  }

	  // Children can be more than one argument, and those are transferred onto
	  // the newly allocated props object.
	  var childrenLength = arguments.length - 2;
	  if (childrenLength === 1) {
	    props.children = children;
	  } else if (childrenLength > 1) {
	    var childArray = Array(childrenLength);
	    for (var i = 0; i < childrenLength; i++) {
	      childArray[i] = arguments[i + 2];
	    }
	    props.children = childArray;
	  }

	  return ReactElement(element.type, key, ref, self, source, owner, props);
	};

	/**
	 * Verifies the object is a ReactElement.
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.isvalidelement
	 * @param {?object} object
	 * @return {boolean} True if `object` is a valid component.
	 * @final
	 */
	ReactElement.isValidElement = function (object) {
	  return typeof object === 'object' && object !== null && object.$$typeof === REACT_ELEMENT_TYPE;
	};

	module.exports = ReactElement;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 28 */
/***/ function(module, exports) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	/**
	 * Keeps track of the current owner.
	 *
	 * The current owner is the component who should own any components that are
	 * currently being constructed.
	 */
	var ReactCurrentOwner = {

	  /**
	   * @internal
	   * @type {ReactComponent}
	   */
	  current: null

	};

	module.exports = ReactCurrentOwner;

/***/ },
/* 29 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2014-2015, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var emptyFunction = __webpack_require__(30);

	/**
	 * Similar to invariant but only logs a warning if the condition is not met.
	 * This can be used to log issues in development environments in critical
	 * paths. Removing the logging code for production environments will keep the
	 * same logic and follow the same code paths.
	 */

	var warning = emptyFunction;

	if (process.env.NODE_ENV !== 'production') {
	  (function () {
	    var printWarning = function printWarning(format) {
	      for (var _len = arguments.length, args = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
	        args[_key - 1] = arguments[_key];
	      }

	      var argIndex = 0;
	      var message = 'Warning: ' + format.replace(/%s/g, function () {
	        return args[argIndex++];
	      });
	      if (typeof console !== 'undefined') {
	        console.error(message);
	      }
	      try {
	        // --- Welcome to debugging React ---
	        // This error was thrown as a convenience so that you can use this stack
	        // to find the callsite that caused this warning to fire.
	        throw new Error(message);
	      } catch (x) {}
	    };

	    warning = function warning(condition, format) {
	      if (format === undefined) {
	        throw new Error('`warning(condition, format, ...args)` requires a warning ' + 'message argument');
	      }

	      if (format.indexOf('Failed Composite propType: ') === 0) {
	        return; // Ignore CompositeComponent proptype check.
	      }

	      if (!condition) {
	        for (var _len2 = arguments.length, args = Array(_len2 > 2 ? _len2 - 2 : 0), _key2 = 2; _key2 < _len2; _key2++) {
	          args[_key2 - 2] = arguments[_key2];
	        }

	        printWarning.apply(undefined, [format].concat(args));
	      }
	    };
	  })();
	}

	module.exports = warning;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 30 */
/***/ function(module, exports) {

	"use strict";

	/**
	 * Copyright (c) 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	function makeEmptyFunction(arg) {
	  return function () {
	    return arg;
	  };
	}

	/**
	 * This function accepts and discards inputs; it has no side effects. This is
	 * primarily useful idiomatically for overridable function endpoints which
	 * always need to be callable, since JS lacks a null-call idiom ala Cocoa.
	 */
	var emptyFunction = function emptyFunction() {};

	emptyFunction.thatReturns = makeEmptyFunction;
	emptyFunction.thatReturnsFalse = makeEmptyFunction(false);
	emptyFunction.thatReturnsTrue = makeEmptyFunction(true);
	emptyFunction.thatReturnsNull = makeEmptyFunction(null);
	emptyFunction.thatReturnsThis = function () {
	  return this;
	};
	emptyFunction.thatReturnsArgument = function (arg) {
	  return arg;
	};

	module.exports = emptyFunction;

/***/ },
/* 31 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	var canDefineProperty = false;
	if (process.env.NODE_ENV !== 'production') {
	  try {
	    // $FlowFixMe https://github.com/facebook/flow/issues/285
	    Object.defineProperty({}, 'x', { get: function () {} });
	    canDefineProperty = true;
	  } catch (x) {
	    // IE will fail on defineProperty
	  }
	}

	module.exports = canDefineProperty;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 32 */
/***/ function(module, exports) {

	/**
	 * Copyright 2014-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	// The Symbol used to tag the ReactElement type. If there is no native Symbol
	// nor polyfill, then a plain number is used for performance.

	var REACT_ELEMENT_TYPE = typeof Symbol === 'function' && Symbol['for'] && Symbol['for']('react.element') || 0xeac7;

	module.exports = REACT_ELEMENT_TYPE;

/***/ },
/* 33 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _prodInvariant = __webpack_require__(25);

	var ReactCurrentOwner = __webpack_require__(28);
	var REACT_ELEMENT_TYPE = __webpack_require__(32);

	var getIteratorFn = __webpack_require__(34);
	var invariant = __webpack_require__(26);
	var KeyEscapeUtils = __webpack_require__(35);
	var warning = __webpack_require__(29);

	var SEPARATOR = '.';
	var SUBSEPARATOR = ':';

	/**
	 * This is inlined from ReactElement since this file is shared between
	 * isomorphic and renderers. We could extract this to a
	 *
	 */

	/**
	 * TODO: Test that a single child and an array with one item have the same key
	 * pattern.
	 */

	var didWarnAboutMaps = false;

	/**
	 * Generate a key string that identifies a component within a set.
	 *
	 * @param {*} component A component that could contain a manual key.
	 * @param {number} index Index that is used if a manual key is not provided.
	 * @return {string}
	 */
	function getComponentKey(component, index) {
	  // Do some typechecking here since we call this blindly. We want to ensure
	  // that we don't block potential future ES APIs.
	  if (component && typeof component === 'object' && component.key != null) {
	    // Explicit key
	    return KeyEscapeUtils.escape(component.key);
	  }
	  // Implicit key determined by the index in the set
	  return index.toString(36);
	}

	/**
	 * @param {?*} children Children tree container.
	 * @param {!string} nameSoFar Name of the key path so far.
	 * @param {!function} callback Callback to invoke with each child found.
	 * @param {?*} traverseContext Used to pass information throughout the traversal
	 * process.
	 * @return {!number} The number of children in this subtree.
	 */
	function traverseAllChildrenImpl(children, nameSoFar, callback, traverseContext) {
	  var type = typeof children;

	  if (type === 'undefined' || type === 'boolean') {
	    // All of the above are perceived as null.
	    children = null;
	  }

	  if (children === null || type === 'string' || type === 'number' ||
	  // The following is inlined from ReactElement. This means we can optimize
	  // some checks. React Fiber also inlines this logic for similar purposes.
	  type === 'object' && children.$$typeof === REACT_ELEMENT_TYPE) {
	    callback(traverseContext, children,
	    // If it's the only child, treat the name as if it was wrapped in an array
	    // so that it's consistent if the number of children grows.
	    nameSoFar === '' ? SEPARATOR + getComponentKey(children, 0) : nameSoFar);
	    return 1;
	  }

	  var child;
	  var nextName;
	  var subtreeCount = 0; // Count of children found in the current subtree.
	  var nextNamePrefix = nameSoFar === '' ? SEPARATOR : nameSoFar + SUBSEPARATOR;

	  if (Array.isArray(children)) {
	    for (var i = 0; i < children.length; i++) {
	      child = children[i];
	      nextName = nextNamePrefix + getComponentKey(child, i);
	      subtreeCount += traverseAllChildrenImpl(child, nextName, callback, traverseContext);
	    }
	  } else {
	    var iteratorFn = getIteratorFn(children);
	    if (iteratorFn) {
	      var iterator = iteratorFn.call(children);
	      var step;
	      if (iteratorFn !== children.entries) {
	        var ii = 0;
	        while (!(step = iterator.next()).done) {
	          child = step.value;
	          nextName = nextNamePrefix + getComponentKey(child, ii++);
	          subtreeCount += traverseAllChildrenImpl(child, nextName, callback, traverseContext);
	        }
	      } else {
	        if (process.env.NODE_ENV !== 'production') {
	          var mapsAsChildrenAddendum = '';
	          if (ReactCurrentOwner.current) {
	            var mapsAsChildrenOwnerName = ReactCurrentOwner.current.getName();
	            if (mapsAsChildrenOwnerName) {
	              mapsAsChildrenAddendum = ' Check the render method of `' + mapsAsChildrenOwnerName + '`.';
	            }
	          }
	          process.env.NODE_ENV !== 'production' ? warning(didWarnAboutMaps, 'Using Maps as children is not yet fully supported. It is an ' + 'experimental feature that might be removed. Convert it to a ' + 'sequence / iterable of keyed ReactElements instead.%s', mapsAsChildrenAddendum) : void 0;
	          didWarnAboutMaps = true;
	        }
	        // Iterator will provide entry [k,v] tuples rather than values.
	        while (!(step = iterator.next()).done) {
	          var entry = step.value;
	          if (entry) {
	            child = entry[1];
	            nextName = nextNamePrefix + KeyEscapeUtils.escape(entry[0]) + SUBSEPARATOR + getComponentKey(child, 0);
	            subtreeCount += traverseAllChildrenImpl(child, nextName, callback, traverseContext);
	          }
	        }
	      }
	    } else if (type === 'object') {
	      var addendum = '';
	      if (process.env.NODE_ENV !== 'production') {
	        addendum = ' If you meant to render a collection of children, use an array ' + 'instead or wrap the object using createFragment(object) from the ' + 'React add-ons.';
	        if (children._isReactElement) {
	          addendum = ' It looks like you\'re using an element created by a different ' + 'version of React. Make sure to use only one copy of React.';
	        }
	        if (ReactCurrentOwner.current) {
	          var name = ReactCurrentOwner.current.getName();
	          if (name) {
	            addendum += ' Check the render method of `' + name + '`.';
	          }
	        }
	      }
	      var childrenString = String(children);
	       true ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Objects are not valid as a React child (found: %s).%s', childrenString === '[object Object]' ? 'object with keys {' + Object.keys(children).join(', ') + '}' : childrenString, addendum) : _prodInvariant('31', childrenString === '[object Object]' ? 'object with keys {' + Object.keys(children).join(', ') + '}' : childrenString, addendum) : void 0;
	    }
	  }

	  return subtreeCount;
	}

	/**
	 * Traverses children that are typically specified as `props.children`, but
	 * might also be specified through attributes:
	 *
	 * - `traverseAllChildren(this.props.children, ...)`
	 * - `traverseAllChildren(this.props.leftPanelChildren, ...)`
	 *
	 * The `traverseContext` is an optional argument that is passed through the
	 * entire traversal. It can be used to store accumulations or anything else that
	 * the callback might find relevant.
	 *
	 * @param {?*} children Children tree object.
	 * @param {!function} callback To invoke upon traversing each child.
	 * @param {?*} traverseContext Context for traversal.
	 * @return {!number} The number of children in this subtree.
	 */
	function traverseAllChildren(children, callback, traverseContext) {
	  if (children == null) {
	    return 0;
	  }

	  return traverseAllChildrenImpl(children, '', callback, traverseContext);
	}

	module.exports = traverseAllChildren;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 34 */
/***/ function(module, exports) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	/* global Symbol */

	var ITERATOR_SYMBOL = typeof Symbol === 'function' && Symbol.iterator;
	var FAUX_ITERATOR_SYMBOL = '@@iterator'; // Before Symbol spec.

	/**
	 * Returns the iterator method function contained on the iterable object.
	 *
	 * Be sure to invoke the function with the iterable as context:
	 *
	 *     var iteratorFn = getIteratorFn(myIterable);
	 *     if (iteratorFn) {
	 *       var iterator = iteratorFn.call(myIterable);
	 *       ...
	 *     }
	 *
	 * @param {?object} maybeIterable
	 * @return {?function}
	 */
	function getIteratorFn(maybeIterable) {
	  var iteratorFn = maybeIterable && (ITERATOR_SYMBOL && maybeIterable[ITERATOR_SYMBOL] || maybeIterable[FAUX_ITERATOR_SYMBOL]);
	  if (typeof iteratorFn === 'function') {
	    return iteratorFn;
	  }
	}

	module.exports = getIteratorFn;

/***/ },
/* 35 */
/***/ function(module, exports) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	/**
	 * Escape and wrap key so it is safe to use as a reactid
	 *
	 * @param {string} key to be escaped.
	 * @return {string} the escaped key.
	 */

	function escape(key) {
	  var escapeRegex = /[=:]/g;
	  var escaperLookup = {
	    '=': '=0',
	    ':': '=2'
	  };
	  var escapedString = ('' + key).replace(escapeRegex, function (match) {
	    return escaperLookup[match];
	  });

	  return '$' + escapedString;
	}

	/**
	 * Unescape and unwrap key for human-readable display
	 *
	 * @param {string} key to unescape.
	 * @return {string} the unescaped key.
	 */
	function unescape(key) {
	  var unescapeRegex = /(=0|=2)/g;
	  var unescaperLookup = {
	    '=0': '=',
	    '=2': ':'
	  };
	  var keySubstring = key[0] === '.' && key[1] === '$' ? key.substring(2) : key.substring(1);

	  return ('' + keySubstring).replace(unescapeRegex, function (match) {
	    return unescaperLookup[match];
	  });
	}

	var KeyEscapeUtils = {
	  escape: escape,
	  unescape: unescape
	};

	module.exports = KeyEscapeUtils;

/***/ },
/* 36 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _prodInvariant = __webpack_require__(25);

	var ReactNoopUpdateQueue = __webpack_require__(37);

	var canDefineProperty = __webpack_require__(31);
	var emptyObject = __webpack_require__(38);
	var invariant = __webpack_require__(26);
	var warning = __webpack_require__(29);

	/**
	 * Base class helpers for the updating state of a component.
	 */
	function ReactComponent(props, context, updater) {
	  this.props = props;
	  this.context = context;
	  this.refs = emptyObject;
	  // We initialize the default updater but the real one gets injected by the
	  // renderer.
	  this.updater = updater || ReactNoopUpdateQueue;
	}

	ReactComponent.prototype.isReactComponent = {};

	/**
	 * Sets a subset of the state. Always use this to mutate
	 * state. You should treat `this.state` as immutable.
	 *
	 * There is no guarantee that `this.state` will be immediately updated, so
	 * accessing `this.state` after calling this method may return the old value.
	 *
	 * There is no guarantee that calls to `setState` will run synchronously,
	 * as they may eventually be batched together.  You can provide an optional
	 * callback that will be executed when the call to setState is actually
	 * completed.
	 *
	 * When a function is provided to setState, it will be called at some point in
	 * the future (not synchronously). It will be called with the up to date
	 * component arguments (state, props, context). These values can be different
	 * from this.* because your function may be called after receiveProps but before
	 * shouldComponentUpdate, and this new state, props, and context will not yet be
	 * assigned to this.
	 *
	 * @param {object|function} partialState Next partial state or function to
	 *        produce next partial state to be merged with current state.
	 * @param {?function} callback Called after state is updated.
	 * @final
	 * @protected
	 */
	ReactComponent.prototype.setState = function (partialState, callback) {
	  !(typeof partialState === 'object' || typeof partialState === 'function' || partialState == null) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'setState(...): takes an object of state variables to update or a function which returns an object of state variables.') : _prodInvariant('85') : void 0;
	  this.updater.enqueueSetState(this, partialState);
	  if (callback) {
	    this.updater.enqueueCallback(this, callback, 'setState');
	  }
	};

	/**
	 * Forces an update. This should only be invoked when it is known with
	 * certainty that we are **not** in a DOM transaction.
	 *
	 * You may want to call this when you know that some deeper aspect of the
	 * component's state has changed but `setState` was not called.
	 *
	 * This will not invoke `shouldComponentUpdate`, but it will invoke
	 * `componentWillUpdate` and `componentDidUpdate`.
	 *
	 * @param {?function} callback Called after update is complete.
	 * @final
	 * @protected
	 */
	ReactComponent.prototype.forceUpdate = function (callback) {
	  this.updater.enqueueForceUpdate(this);
	  if (callback) {
	    this.updater.enqueueCallback(this, callback, 'forceUpdate');
	  }
	};

	/**
	 * Deprecated APIs. These APIs used to exist on classic React classes but since
	 * we would like to deprecate them, we're not going to move them over to this
	 * modern base class. Instead, we define a getter that warns if it's accessed.
	 */
	if (process.env.NODE_ENV !== 'production') {
	  var deprecatedAPIs = {
	    isMounted: ['isMounted', 'Instead, make sure to clean up subscriptions and pending requests in ' + 'componentWillUnmount to prevent memory leaks.'],
	    replaceState: ['replaceState', 'Refactor your code to use setState instead (see ' + 'https://github.com/facebook/react/issues/3236).']
	  };
	  var defineDeprecationWarning = function (methodName, info) {
	    if (canDefineProperty) {
	      Object.defineProperty(ReactComponent.prototype, methodName, {
	        get: function () {
	          process.env.NODE_ENV !== 'production' ? warning(false, '%s(...) is deprecated in plain JavaScript React classes. %s', info[0], info[1]) : void 0;
	          return undefined;
	        }
	      });
	    }
	  };
	  for (var fnName in deprecatedAPIs) {
	    if (deprecatedAPIs.hasOwnProperty(fnName)) {
	      defineDeprecationWarning(fnName, deprecatedAPIs[fnName]);
	    }
	  }
	}

	module.exports = ReactComponent;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 37 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2015-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var warning = __webpack_require__(29);

	function warnNoop(publicInstance, callerName) {
	  if (process.env.NODE_ENV !== 'production') {
	    var constructor = publicInstance.constructor;
	    process.env.NODE_ENV !== 'production' ? warning(false, '%s(...): Can only update a mounted or mounting component. ' + 'This usually means you called %s() on an unmounted component. ' + 'This is a no-op. Please check the code for the %s component.', callerName, callerName, constructor && (constructor.displayName || constructor.name) || 'ReactClass') : void 0;
	  }
	}

	/**
	 * This is the abstract API for an update queue.
	 */
	var ReactNoopUpdateQueue = {

	  /**
	   * Checks whether or not this composite component is mounted.
	   * @param {ReactClass} publicInstance The instance we want to test.
	   * @return {boolean} True if mounted, false otherwise.
	   * @protected
	   * @final
	   */
	  isMounted: function (publicInstance) {
	    return false;
	  },

	  /**
	   * Enqueue a callback that will be executed after all the pending updates
	   * have processed.
	   *
	   * @param {ReactClass} publicInstance The instance to use as `this` context.
	   * @param {?function} callback Called after state is updated.
	   * @internal
	   */
	  enqueueCallback: function (publicInstance, callback) {},

	  /**
	   * Forces an update. This should only be invoked when it is known with
	   * certainty that we are **not** in a DOM transaction.
	   *
	   * You may want to call this when you know that some deeper aspect of the
	   * component's state has changed but `setState` was not called.
	   *
	   * This will not invoke `shouldComponentUpdate`, but it will invoke
	   * `componentWillUpdate` and `componentDidUpdate`.
	   *
	   * @param {ReactClass} publicInstance The instance that should rerender.
	   * @internal
	   */
	  enqueueForceUpdate: function (publicInstance) {
	    warnNoop(publicInstance, 'forceUpdate');
	  },

	  /**
	   * Replaces all of the state. Always use this or `setState` to mutate state.
	   * You should treat `this.state` as immutable.
	   *
	   * There is no guarantee that `this.state` will be immediately updated, so
	   * accessing `this.state` after calling this method may return the old value.
	   *
	   * @param {ReactClass} publicInstance The instance that should rerender.
	   * @param {object} completeState Next state.
	   * @internal
	   */
	  enqueueReplaceState: function (publicInstance, completeState) {
	    warnNoop(publicInstance, 'replaceState');
	  },

	  /**
	   * Sets a subset of the state. This only exists because _pendingState is
	   * internal. This provides a merging strategy that is not available to deep
	   * properties which is confusing. TODO: Expose pendingState or don't use it
	   * during the merge.
	   *
	   * @param {ReactClass} publicInstance The instance that should rerender.
	   * @param {object} partialState Next partial state to be merged with state.
	   * @internal
	   */
	  enqueueSetState: function (publicInstance, partialState) {
	    warnNoop(publicInstance, 'setState');
	  }
	};

	module.exports = ReactNoopUpdateQueue;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 38 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright (c) 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var emptyObject = {};

	if (process.env.NODE_ENV !== 'production') {
	  Object.freeze(emptyObject);
	}

	module.exports = emptyObject;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 39 */
/***/ function(module, exports, __webpack_require__) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _assign = __webpack_require__(22);

	var ReactComponent = __webpack_require__(36);
	var ReactNoopUpdateQueue = __webpack_require__(37);

	var emptyObject = __webpack_require__(38);

	/**
	 * Base class helpers for the updating state of a component.
	 */
	function ReactPureComponent(props, context, updater) {
	  // Duplicated from ReactComponent.
	  this.props = props;
	  this.context = context;
	  this.refs = emptyObject;
	  // We initialize the default updater but the real one gets injected by the
	  // renderer.
	  this.updater = updater || ReactNoopUpdateQueue;
	}

	function ComponentDummy() {}
	ComponentDummy.prototype = ReactComponent.prototype;
	ReactPureComponent.prototype = new ComponentDummy();
	ReactPureComponent.prototype.constructor = ReactPureComponent;
	// Avoid an extra prototype jump for these methods.
	_assign(ReactPureComponent.prototype, ReactComponent.prototype);
	ReactPureComponent.prototype.isPureReactComponent = true;

	module.exports = ReactPureComponent;

/***/ },
/* 40 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _prodInvariant = __webpack_require__(25),
	    _assign = __webpack_require__(22);

	var ReactComponent = __webpack_require__(36);
	var ReactElement = __webpack_require__(27);
	var ReactPropTypeLocationNames = __webpack_require__(41);
	var ReactNoopUpdateQueue = __webpack_require__(37);

	var emptyObject = __webpack_require__(38);
	var invariant = __webpack_require__(26);
	var warning = __webpack_require__(29);

	var MIXINS_KEY = 'mixins';

	// Helper function to allow the creation of anonymous functions which do not
	// have .name set to the name of the variable being assigned to.
	function identity(fn) {
	  return fn;
	}

	/**
	 * Policies that describe methods in `ReactClassInterface`.
	 */


	var injectedMixins = [];

	/**
	 * Composite components are higher-level components that compose other composite
	 * or host components.
	 *
	 * To create a new type of `ReactClass`, pass a specification of
	 * your new class to `React.createClass`. The only requirement of your class
	 * specification is that you implement a `render` method.
	 *
	 *   var MyComponent = React.createClass({
	 *     render: function() {
	 *       return <div>Hello World</div>;
	 *     }
	 *   });
	 *
	 * The class specification supports a specific protocol of methods that have
	 * special meaning (e.g. `render`). See `ReactClassInterface` for
	 * more the comprehensive protocol. Any other properties and methods in the
	 * class specification will be available on the prototype.
	 *
	 * @interface ReactClassInterface
	 * @internal
	 */
	var ReactClassInterface = {

	  /**
	   * An array of Mixin objects to include when defining your component.
	   *
	   * @type {array}
	   * @optional
	   */
	  mixins: 'DEFINE_MANY',

	  /**
	   * An object containing properties and methods that should be defined on
	   * the component's constructor instead of its prototype (static methods).
	   *
	   * @type {object}
	   * @optional
	   */
	  statics: 'DEFINE_MANY',

	  /**
	   * Definition of prop types for this component.
	   *
	   * @type {object}
	   * @optional
	   */
	  propTypes: 'DEFINE_MANY',

	  /**
	   * Definition of context types for this component.
	   *
	   * @type {object}
	   * @optional
	   */
	  contextTypes: 'DEFINE_MANY',

	  /**
	   * Definition of context types this component sets for its children.
	   *
	   * @type {object}
	   * @optional
	   */
	  childContextTypes: 'DEFINE_MANY',

	  // ==== Definition methods ====

	  /**
	   * Invoked when the component is mounted. Values in the mapping will be set on
	   * `this.props` if that prop is not specified (i.e. using an `in` check).
	   *
	   * This method is invoked before `getInitialState` and therefore cannot rely
	   * on `this.state` or use `this.setState`.
	   *
	   * @return {object}
	   * @optional
	   */
	  getDefaultProps: 'DEFINE_MANY_MERGED',

	  /**
	   * Invoked once before the component is mounted. The return value will be used
	   * as the initial value of `this.state`.
	   *
	   *   getInitialState: function() {
	   *     return {
	   *       isOn: false,
	   *       fooBaz: new BazFoo()
	   *     }
	   *   }
	   *
	   * @return {object}
	   * @optional
	   */
	  getInitialState: 'DEFINE_MANY_MERGED',

	  /**
	   * @return {object}
	   * @optional
	   */
	  getChildContext: 'DEFINE_MANY_MERGED',

	  /**
	   * Uses props from `this.props` and state from `this.state` to render the
	   * structure of the component.
	   *
	   * No guarantees are made about when or how often this method is invoked, so
	   * it must not have side effects.
	   *
	   *   render: function() {
	   *     var name = this.props.name;
	   *     return <div>Hello, {name}!</div>;
	   *   }
	   *
	   * @return {ReactComponent}
	   * @nosideeffects
	   * @required
	   */
	  render: 'DEFINE_ONCE',

	  // ==== Delegate methods ====

	  /**
	   * Invoked when the component is initially created and about to be mounted.
	   * This may have side effects, but any external subscriptions or data created
	   * by this method must be cleaned up in `componentWillUnmount`.
	   *
	   * @optional
	   */
	  componentWillMount: 'DEFINE_MANY',

	  /**
	   * Invoked when the component has been mounted and has a DOM representation.
	   * However, there is no guarantee that the DOM node is in the document.
	   *
	   * Use this as an opportunity to operate on the DOM when the component has
	   * been mounted (initialized and rendered) for the first time.
	   *
	   * @param {DOMElement} rootNode DOM element representing the component.
	   * @optional
	   */
	  componentDidMount: 'DEFINE_MANY',

	  /**
	   * Invoked before the component receives new props.
	   *
	   * Use this as an opportunity to react to a prop transition by updating the
	   * state using `this.setState`. Current props are accessed via `this.props`.
	   *
	   *   componentWillReceiveProps: function(nextProps, nextContext) {
	   *     this.setState({
	   *       likesIncreasing: nextProps.likeCount > this.props.likeCount
	   *     });
	   *   }
	   *
	   * NOTE: There is no equivalent `componentWillReceiveState`. An incoming prop
	   * transition may cause a state change, but the opposite is not true. If you
	   * need it, you are probably looking for `componentWillUpdate`.
	   *
	   * @param {object} nextProps
	   * @optional
	   */
	  componentWillReceiveProps: 'DEFINE_MANY',

	  /**
	   * Invoked while deciding if the component should be updated as a result of
	   * receiving new props, state and/or context.
	   *
	   * Use this as an opportunity to `return false` when you're certain that the
	   * transition to the new props/state/context will not require a component
	   * update.
	   *
	   *   shouldComponentUpdate: function(nextProps, nextState, nextContext) {
	   *     return !equal(nextProps, this.props) ||
	   *       !equal(nextState, this.state) ||
	   *       !equal(nextContext, this.context);
	   *   }
	   *
	   * @param {object} nextProps
	   * @param {?object} nextState
	   * @param {?object} nextContext
	   * @return {boolean} True if the component should update.
	   * @optional
	   */
	  shouldComponentUpdate: 'DEFINE_ONCE',

	  /**
	   * Invoked when the component is about to update due to a transition from
	   * `this.props`, `this.state` and `this.context` to `nextProps`, `nextState`
	   * and `nextContext`.
	   *
	   * Use this as an opportunity to perform preparation before an update occurs.
	   *
	   * NOTE: You **cannot** use `this.setState()` in this method.
	   *
	   * @param {object} nextProps
	   * @param {?object} nextState
	   * @param {?object} nextContext
	   * @param {ReactReconcileTransaction} transaction
	   * @optional
	   */
	  componentWillUpdate: 'DEFINE_MANY',

	  /**
	   * Invoked when the component's DOM representation has been updated.
	   *
	   * Use this as an opportunity to operate on the DOM when the component has
	   * been updated.
	   *
	   * @param {object} prevProps
	   * @param {?object} prevState
	   * @param {?object} prevContext
	   * @param {DOMElement} rootNode DOM element representing the component.
	   * @optional
	   */
	  componentDidUpdate: 'DEFINE_MANY',

	  /**
	   * Invoked when the component is about to be removed from its parent and have
	   * its DOM representation destroyed.
	   *
	   * Use this as an opportunity to deallocate any external resources.
	   *
	   * NOTE: There is no `componentDidUnmount` since your component will have been
	   * destroyed by that point.
	   *
	   * @optional
	   */
	  componentWillUnmount: 'DEFINE_MANY',

	  // ==== Advanced methods ====

	  /**
	   * Updates the component's currently mounted DOM representation.
	   *
	   * By default, this implements React's rendering and reconciliation algorithm.
	   * Sophisticated clients may wish to override this.
	   *
	   * @param {ReactReconcileTransaction} transaction
	   * @internal
	   * @overridable
	   */
	  updateComponent: 'OVERRIDE_BASE'

	};

	/**
	 * Mapping from class specification keys to special processing functions.
	 *
	 * Although these are declared like instance properties in the specification
	 * when defining classes using `React.createClass`, they are actually static
	 * and are accessible on the constructor instead of the prototype. Despite
	 * being static, they must be defined outside of the "statics" key under
	 * which all other static methods are defined.
	 */
	var RESERVED_SPEC_KEYS = {
	  displayName: function (Constructor, displayName) {
	    Constructor.displayName = displayName;
	  },
	  mixins: function (Constructor, mixins) {
	    if (mixins) {
	      for (var i = 0; i < mixins.length; i++) {
	        mixSpecIntoComponent(Constructor, mixins[i]);
	      }
	    }
	  },
	  childContextTypes: function (Constructor, childContextTypes) {
	    if (process.env.NODE_ENV !== 'production') {
	      validateTypeDef(Constructor, childContextTypes, 'childContext');
	    }
	    Constructor.childContextTypes = _assign({}, Constructor.childContextTypes, childContextTypes);
	  },
	  contextTypes: function (Constructor, contextTypes) {
	    if (process.env.NODE_ENV !== 'production') {
	      validateTypeDef(Constructor, contextTypes, 'context');
	    }
	    Constructor.contextTypes = _assign({}, Constructor.contextTypes, contextTypes);
	  },
	  /**
	   * Special case getDefaultProps which should move into statics but requires
	   * automatic merging.
	   */
	  getDefaultProps: function (Constructor, getDefaultProps) {
	    if (Constructor.getDefaultProps) {
	      Constructor.getDefaultProps = createMergedResultFunction(Constructor.getDefaultProps, getDefaultProps);
	    } else {
	      Constructor.getDefaultProps = getDefaultProps;
	    }
	  },
	  propTypes: function (Constructor, propTypes) {
	    if (process.env.NODE_ENV !== 'production') {
	      validateTypeDef(Constructor, propTypes, 'prop');
	    }
	    Constructor.propTypes = _assign({}, Constructor.propTypes, propTypes);
	  },
	  statics: function (Constructor, statics) {
	    mixStaticSpecIntoComponent(Constructor, statics);
	  },
	  autobind: function () {} };

	function validateTypeDef(Constructor, typeDef, location) {
	  for (var propName in typeDef) {
	    if (typeDef.hasOwnProperty(propName)) {
	      // use a warning instead of an invariant so components
	      // don't show up in prod but only in __DEV__
	      process.env.NODE_ENV !== 'production' ? warning(typeof typeDef[propName] === 'function', '%s: %s type `%s` is invalid; it must be a function, usually from ' + 'React.PropTypes.', Constructor.displayName || 'ReactClass', ReactPropTypeLocationNames[location], propName) : void 0;
	    }
	  }
	}

	function validateMethodOverride(isAlreadyDefined, name) {
	  var specPolicy = ReactClassInterface.hasOwnProperty(name) ? ReactClassInterface[name] : null;

	  // Disallow overriding of base class methods unless explicitly allowed.
	  if (ReactClassMixin.hasOwnProperty(name)) {
	    !(specPolicy === 'OVERRIDE_BASE') ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClassInterface: You are attempting to override `%s` from your class specification. Ensure that your method names do not overlap with React methods.', name) : _prodInvariant('73', name) : void 0;
	  }

	  // Disallow defining methods more than once unless explicitly allowed.
	  if (isAlreadyDefined) {
	    !(specPolicy === 'DEFINE_MANY' || specPolicy === 'DEFINE_MANY_MERGED') ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClassInterface: You are attempting to define `%s` on your component more than once. This conflict may be due to a mixin.', name) : _prodInvariant('74', name) : void 0;
	  }
	}

	/**
	 * Mixin helper which handles policy validation and reserved
	 * specification keys when building React classes.
	 */
	function mixSpecIntoComponent(Constructor, spec) {
	  if (!spec) {
	    if (process.env.NODE_ENV !== 'production') {
	      var typeofSpec = typeof spec;
	      var isMixinValid = typeofSpec === 'object' && spec !== null;

	      process.env.NODE_ENV !== 'production' ? warning(isMixinValid, '%s: You\'re attempting to include a mixin that is either null ' + 'or not an object. Check the mixins included by the component, ' + 'as well as any mixins they include themselves. ' + 'Expected object but got %s.', Constructor.displayName || 'ReactClass', spec === null ? null : typeofSpec) : void 0;
	    }

	    return;
	  }

	  !(typeof spec !== 'function') ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClass: You\'re attempting to use a component class or function as a mixin. Instead, just use a regular object.') : _prodInvariant('75') : void 0;
	  !!ReactElement.isValidElement(spec) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClass: You\'re attempting to use a component as a mixin. Instead, just use a regular object.') : _prodInvariant('76') : void 0;

	  var proto = Constructor.prototype;
	  var autoBindPairs = proto.__reactAutoBindPairs;

	  // By handling mixins before any other properties, we ensure the same
	  // chaining order is applied to methods with DEFINE_MANY policy, whether
	  // mixins are listed before or after these methods in the spec.
	  if (spec.hasOwnProperty(MIXINS_KEY)) {
	    RESERVED_SPEC_KEYS.mixins(Constructor, spec.mixins);
	  }

	  for (var name in spec) {
	    if (!spec.hasOwnProperty(name)) {
	      continue;
	    }

	    if (name === MIXINS_KEY) {
	      // We have already handled mixins in a special case above.
	      continue;
	    }

	    var property = spec[name];
	    var isAlreadyDefined = proto.hasOwnProperty(name);
	    validateMethodOverride(isAlreadyDefined, name);

	    if (RESERVED_SPEC_KEYS.hasOwnProperty(name)) {
	      RESERVED_SPEC_KEYS[name](Constructor, property);
	    } else {
	      // Setup methods on prototype:
	      // The following member methods should not be automatically bound:
	      // 1. Expected ReactClass methods (in the "interface").
	      // 2. Overridden methods (that were mixed in).
	      var isReactClassMethod = ReactClassInterface.hasOwnProperty(name);
	      var isFunction = typeof property === 'function';
	      var shouldAutoBind = isFunction && !isReactClassMethod && !isAlreadyDefined && spec.autobind !== false;

	      if (shouldAutoBind) {
	        autoBindPairs.push(name, property);
	        proto[name] = property;
	      } else {
	        if (isAlreadyDefined) {
	          var specPolicy = ReactClassInterface[name];

	          // These cases should already be caught by validateMethodOverride.
	          !(isReactClassMethod && (specPolicy === 'DEFINE_MANY_MERGED' || specPolicy === 'DEFINE_MANY')) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClass: Unexpected spec policy %s for key %s when mixing in component specs.', specPolicy, name) : _prodInvariant('77', specPolicy, name) : void 0;

	          // For methods which are defined more than once, call the existing
	          // methods before calling the new property, merging if appropriate.
	          if (specPolicy === 'DEFINE_MANY_MERGED') {
	            proto[name] = createMergedResultFunction(proto[name], property);
	          } else if (specPolicy === 'DEFINE_MANY') {
	            proto[name] = createChainedFunction(proto[name], property);
	          }
	        } else {
	          proto[name] = property;
	          if (process.env.NODE_ENV !== 'production') {
	            // Add verbose displayName to the function, which helps when looking
	            // at profiling tools.
	            if (typeof property === 'function' && spec.displayName) {
	              proto[name].displayName = spec.displayName + '_' + name;
	            }
	          }
	        }
	      }
	    }
	  }
	}

	function mixStaticSpecIntoComponent(Constructor, statics) {
	  if (!statics) {
	    return;
	  }
	  for (var name in statics) {
	    var property = statics[name];
	    if (!statics.hasOwnProperty(name)) {
	      continue;
	    }

	    var isReserved = name in RESERVED_SPEC_KEYS;
	    !!isReserved ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClass: You are attempting to define a reserved property, `%s`, that shouldn\'t be on the "statics" key. Define it as an instance property instead; it will still be accessible on the constructor.', name) : _prodInvariant('78', name) : void 0;

	    var isInherited = name in Constructor;
	    !!isInherited ? process.env.NODE_ENV !== 'production' ? invariant(false, 'ReactClass: You are attempting to define `%s` on your component more than once. This conflict may be due to a mixin.', name) : _prodInvariant('79', name) : void 0;
	    Constructor[name] = property;
	  }
	}

	/**
	 * Merge two objects, but throw if both contain the same key.
	 *
	 * @param {object} one The first object, which is mutated.
	 * @param {object} two The second object
	 * @return {object} one after it has been mutated to contain everything in two.
	 */
	function mergeIntoWithNoDuplicateKeys(one, two) {
	  !(one && two && typeof one === 'object' && typeof two === 'object') ? process.env.NODE_ENV !== 'production' ? invariant(false, 'mergeIntoWithNoDuplicateKeys(): Cannot merge non-objects.') : _prodInvariant('80') : void 0;

	  for (var key in two) {
	    if (two.hasOwnProperty(key)) {
	      !(one[key] === undefined) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'mergeIntoWithNoDuplicateKeys(): Tried to merge two objects with the same key: `%s`. This conflict may be due to a mixin; in particular, this may be caused by two getInitialState() or getDefaultProps() methods returning objects with clashing keys.', key) : _prodInvariant('81', key) : void 0;
	      one[key] = two[key];
	    }
	  }
	  return one;
	}

	/**
	 * Creates a function that invokes two functions and merges their return values.
	 *
	 * @param {function} one Function to invoke first.
	 * @param {function} two Function to invoke second.
	 * @return {function} Function that invokes the two argument functions.
	 * @private
	 */
	function createMergedResultFunction(one, two) {
	  return function mergedResult() {
	    var a = one.apply(this, arguments);
	    var b = two.apply(this, arguments);
	    if (a == null) {
	      return b;
	    } else if (b == null) {
	      return a;
	    }
	    var c = {};
	    mergeIntoWithNoDuplicateKeys(c, a);
	    mergeIntoWithNoDuplicateKeys(c, b);
	    return c;
	  };
	}

	/**
	 * Creates a function that invokes two functions and ignores their return vales.
	 *
	 * @param {function} one Function to invoke first.
	 * @param {function} two Function to invoke second.
	 * @return {function} Function that invokes the two argument functions.
	 * @private
	 */
	function createChainedFunction(one, two) {
	  return function chainedFunction() {
	    one.apply(this, arguments);
	    two.apply(this, arguments);
	  };
	}

	/**
	 * Binds a method to the component.
	 *
	 * @param {object} component Component whose method is going to be bound.
	 * @param {function} method Method to be bound.
	 * @return {function} The bound method.
	 */
	function bindAutoBindMethod(component, method) {
	  var boundMethod = method.bind(component);
	  if (process.env.NODE_ENV !== 'production') {
	    boundMethod.__reactBoundContext = component;
	    boundMethod.__reactBoundMethod = method;
	    boundMethod.__reactBoundArguments = null;
	    var componentName = component.constructor.displayName;
	    var _bind = boundMethod.bind;
	    boundMethod.bind = function (newThis) {
	      for (var _len = arguments.length, args = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
	        args[_key - 1] = arguments[_key];
	      }

	      // User is trying to bind() an autobound method; we effectively will
	      // ignore the value of "this" that the user is trying to use, so
	      // let's warn.
	      if (newThis !== component && newThis !== null) {
	        process.env.NODE_ENV !== 'production' ? warning(false, 'bind(): React component methods may only be bound to the ' + 'component instance. See %s', componentName) : void 0;
	      } else if (!args.length) {
	        process.env.NODE_ENV !== 'production' ? warning(false, 'bind(): You are binding a component method to the component. ' + 'React does this for you automatically in a high-performance ' + 'way, so you can safely remove this call. See %s', componentName) : void 0;
	        return boundMethod;
	      }
	      var reboundMethod = _bind.apply(boundMethod, arguments);
	      reboundMethod.__reactBoundContext = component;
	      reboundMethod.__reactBoundMethod = method;
	      reboundMethod.__reactBoundArguments = args;
	      return reboundMethod;
	    };
	  }
	  return boundMethod;
	}

	/**
	 * Binds all auto-bound methods in a component.
	 *
	 * @param {object} component Component whose method is going to be bound.
	 */
	function bindAutoBindMethods(component) {
	  var pairs = component.__reactAutoBindPairs;
	  for (var i = 0; i < pairs.length; i += 2) {
	    var autoBindKey = pairs[i];
	    var method = pairs[i + 1];
	    component[autoBindKey] = bindAutoBindMethod(component, method);
	  }
	}

	/**
	 * Add more to the ReactClass base class. These are all legacy features and
	 * therefore not already part of the modern ReactComponent.
	 */
	var ReactClassMixin = {

	  /**
	   * TODO: This will be deprecated because state should always keep a consistent
	   * type signature and the only use case for this, is to avoid that.
	   */
	  replaceState: function (newState, callback) {
	    this.updater.enqueueReplaceState(this, newState);
	    if (callback) {
	      this.updater.enqueueCallback(this, callback, 'replaceState');
	    }
	  },

	  /**
	   * Checks whether or not this composite component is mounted.
	   * @return {boolean} True if mounted, false otherwise.
	   * @protected
	   * @final
	   */
	  isMounted: function () {
	    return this.updater.isMounted(this);
	  }
	};

	var ReactClassComponent = function () {};
	_assign(ReactClassComponent.prototype, ReactComponent.prototype, ReactClassMixin);

	/**
	 * Module for creating composite components.
	 *
	 * @class ReactClass
	 */
	var ReactClass = {

	  /**
	   * Creates a composite component class given a class specification.
	   * See https://facebook.github.io/react/docs/top-level-api.html#react.createclass
	   *
	   * @param {object} spec Class specification (which must define `render`).
	   * @return {function} Component constructor function.
	   * @public
	   */
	  createClass: function (spec) {
	    // To keep our warnings more understandable, we'll use a little hack here to
	    // ensure that Constructor.name !== 'Constructor'. This makes sure we don't
	    // unnecessarily identify a class without displayName as 'Constructor'.
	    var Constructor = identity(function (props, context, updater) {
	      // This constructor gets overridden by mocks. The argument is used
	      // by mocks to assert on what gets mounted.

	      if (process.env.NODE_ENV !== 'production') {
	        process.env.NODE_ENV !== 'production' ? warning(this instanceof Constructor, 'Something is calling a React component directly. Use a factory or ' + 'JSX instead. See: https://fb.me/react-legacyfactory') : void 0;
	      }

	      // Wire up auto-binding
	      if (this.__reactAutoBindPairs.length) {
	        bindAutoBindMethods(this);
	      }

	      this.props = props;
	      this.context = context;
	      this.refs = emptyObject;
	      this.updater = updater || ReactNoopUpdateQueue;

	      this.state = null;

	      // ReactClasses doesn't have constructors. Instead, they use the
	      // getInitialState and componentWillMount methods for initialization.

	      var initialState = this.getInitialState ? this.getInitialState() : null;
	      if (process.env.NODE_ENV !== 'production') {
	        // We allow auto-mocks to proceed as if they're returning null.
	        if (initialState === undefined && this.getInitialState._isMockFunction) {
	          // This is probably bad practice. Consider warning here and
	          // deprecating this convenience.
	          initialState = null;
	        }
	      }
	      !(typeof initialState === 'object' && !Array.isArray(initialState)) ? process.env.NODE_ENV !== 'production' ? invariant(false, '%s.getInitialState(): must return an object or null', Constructor.displayName || 'ReactCompositeComponent') : _prodInvariant('82', Constructor.displayName || 'ReactCompositeComponent') : void 0;

	      this.state = initialState;
	    });
	    Constructor.prototype = new ReactClassComponent();
	    Constructor.prototype.constructor = Constructor;
	    Constructor.prototype.__reactAutoBindPairs = [];

	    injectedMixins.forEach(mixSpecIntoComponent.bind(null, Constructor));

	    mixSpecIntoComponent(Constructor, spec);

	    // Initialize the defaultProps property after all mixins have been merged.
	    if (Constructor.getDefaultProps) {
	      Constructor.defaultProps = Constructor.getDefaultProps();
	    }

	    if (process.env.NODE_ENV !== 'production') {
	      // This is a tag to indicate that the use of these method names is ok,
	      // since it's used with createClass. If it's not, then it's likely a
	      // mistake so we'll warn you to use the static property, property
	      // initializer or constructor respectively.
	      if (Constructor.getDefaultProps) {
	        Constructor.getDefaultProps.isReactClassApproved = {};
	      }
	      if (Constructor.prototype.getInitialState) {
	        Constructor.prototype.getInitialState.isReactClassApproved = {};
	      }
	    }

	    !Constructor.prototype.render ? process.env.NODE_ENV !== 'production' ? invariant(false, 'createClass(...): Class specification must implement a `render` method.') : _prodInvariant('83') : void 0;

	    if (process.env.NODE_ENV !== 'production') {
	      process.env.NODE_ENV !== 'production' ? warning(!Constructor.prototype.componentShouldUpdate, '%s has a method called ' + 'componentShouldUpdate(). Did you mean shouldComponentUpdate()? ' + 'The name is phrased as a question because the function is ' + 'expected to return a value.', spec.displayName || 'A component') : void 0;
	      process.env.NODE_ENV !== 'production' ? warning(!Constructor.prototype.componentWillRecieveProps, '%s has a method called ' + 'componentWillRecieveProps(). Did you mean componentWillReceiveProps()?', spec.displayName || 'A component') : void 0;
	    }

	    // Reduce time spent doing lookups by setting these on the prototype.
	    for (var methodName in ReactClassInterface) {
	      if (!Constructor.prototype[methodName]) {
	        Constructor.prototype[methodName] = null;
	      }
	    }

	    return Constructor;
	  },

	  injection: {
	    injectMixin: function (mixin) {
	      injectedMixins.push(mixin);
	    }
	  }

	};

	module.exports = ReactClass;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 41 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	var ReactPropTypeLocationNames = {};

	if (process.env.NODE_ENV !== 'production') {
	  ReactPropTypeLocationNames = {
	    prop: 'prop',
	    context: 'context',
	    childContext: 'child context'
	  };
	}

	module.exports = ReactPropTypeLocationNames;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 42 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var ReactElement = __webpack_require__(27);

	/**
	 * Create a factory that creates HTML tag elements.
	 *
	 * @private
	 */
	var createDOMFactory = ReactElement.createFactory;
	if (process.env.NODE_ENV !== 'production') {
	  var ReactElementValidator = __webpack_require__(43);
	  createDOMFactory = ReactElementValidator.createFactory;
	}

	/**
	 * Creates a mapping from supported HTML tags to `ReactDOMComponent` classes.
	 * This is also accessible via `React.DOM`.
	 *
	 * @public
	 */
	var ReactDOMFactories = {
	  a: createDOMFactory('a'),
	  abbr: createDOMFactory('abbr'),
	  address: createDOMFactory('address'),
	  area: createDOMFactory('area'),
	  article: createDOMFactory('article'),
	  aside: createDOMFactory('aside'),
	  audio: createDOMFactory('audio'),
	  b: createDOMFactory('b'),
	  base: createDOMFactory('base'),
	  bdi: createDOMFactory('bdi'),
	  bdo: createDOMFactory('bdo'),
	  big: createDOMFactory('big'),
	  blockquote: createDOMFactory('blockquote'),
	  body: createDOMFactory('body'),
	  br: createDOMFactory('br'),
	  button: createDOMFactory('button'),
	  canvas: createDOMFactory('canvas'),
	  caption: createDOMFactory('caption'),
	  cite: createDOMFactory('cite'),
	  code: createDOMFactory('code'),
	  col: createDOMFactory('col'),
	  colgroup: createDOMFactory('colgroup'),
	  data: createDOMFactory('data'),
	  datalist: createDOMFactory('datalist'),
	  dd: createDOMFactory('dd'),
	  del: createDOMFactory('del'),
	  details: createDOMFactory('details'),
	  dfn: createDOMFactory('dfn'),
	  dialog: createDOMFactory('dialog'),
	  div: createDOMFactory('div'),
	  dl: createDOMFactory('dl'),
	  dt: createDOMFactory('dt'),
	  em: createDOMFactory('em'),
	  embed: createDOMFactory('embed'),
	  fieldset: createDOMFactory('fieldset'),
	  figcaption: createDOMFactory('figcaption'),
	  figure: createDOMFactory('figure'),
	  footer: createDOMFactory('footer'),
	  form: createDOMFactory('form'),
	  h1: createDOMFactory('h1'),
	  h2: createDOMFactory('h2'),
	  h3: createDOMFactory('h3'),
	  h4: createDOMFactory('h4'),
	  h5: createDOMFactory('h5'),
	  h6: createDOMFactory('h6'),
	  head: createDOMFactory('head'),
	  header: createDOMFactory('header'),
	  hgroup: createDOMFactory('hgroup'),
	  hr: createDOMFactory('hr'),
	  html: createDOMFactory('html'),
	  i: createDOMFactory('i'),
	  iframe: createDOMFactory('iframe'),
	  img: createDOMFactory('img'),
	  input: createDOMFactory('input'),
	  ins: createDOMFactory('ins'),
	  kbd: createDOMFactory('kbd'),
	  keygen: createDOMFactory('keygen'),
	  label: createDOMFactory('label'),
	  legend: createDOMFactory('legend'),
	  li: createDOMFactory('li'),
	  link: createDOMFactory('link'),
	  main: createDOMFactory('main'),
	  map: createDOMFactory('map'),
	  mark: createDOMFactory('mark'),
	  menu: createDOMFactory('menu'),
	  menuitem: createDOMFactory('menuitem'),
	  meta: createDOMFactory('meta'),
	  meter: createDOMFactory('meter'),
	  nav: createDOMFactory('nav'),
	  noscript: createDOMFactory('noscript'),
	  object: createDOMFactory('object'),
	  ol: createDOMFactory('ol'),
	  optgroup: createDOMFactory('optgroup'),
	  option: createDOMFactory('option'),
	  output: createDOMFactory('output'),
	  p: createDOMFactory('p'),
	  param: createDOMFactory('param'),
	  picture: createDOMFactory('picture'),
	  pre: createDOMFactory('pre'),
	  progress: createDOMFactory('progress'),
	  q: createDOMFactory('q'),
	  rp: createDOMFactory('rp'),
	  rt: createDOMFactory('rt'),
	  ruby: createDOMFactory('ruby'),
	  s: createDOMFactory('s'),
	  samp: createDOMFactory('samp'),
	  script: createDOMFactory('script'),
	  section: createDOMFactory('section'),
	  select: createDOMFactory('select'),
	  small: createDOMFactory('small'),
	  source: createDOMFactory('source'),
	  span: createDOMFactory('span'),
	  strong: createDOMFactory('strong'),
	  style: createDOMFactory('style'),
	  sub: createDOMFactory('sub'),
	  summary: createDOMFactory('summary'),
	  sup: createDOMFactory('sup'),
	  table: createDOMFactory('table'),
	  tbody: createDOMFactory('tbody'),
	  td: createDOMFactory('td'),
	  textarea: createDOMFactory('textarea'),
	  tfoot: createDOMFactory('tfoot'),
	  th: createDOMFactory('th'),
	  thead: createDOMFactory('thead'),
	  time: createDOMFactory('time'),
	  title: createDOMFactory('title'),
	  tr: createDOMFactory('tr'),
	  track: createDOMFactory('track'),
	  u: createDOMFactory('u'),
	  ul: createDOMFactory('ul'),
	  'var': createDOMFactory('var'),
	  video: createDOMFactory('video'),
	  wbr: createDOMFactory('wbr'),

	  // SVG
	  circle: createDOMFactory('circle'),
	  clipPath: createDOMFactory('clipPath'),
	  defs: createDOMFactory('defs'),
	  ellipse: createDOMFactory('ellipse'),
	  g: createDOMFactory('g'),
	  image: createDOMFactory('image'),
	  line: createDOMFactory('line'),
	  linearGradient: createDOMFactory('linearGradient'),
	  mask: createDOMFactory('mask'),
	  path: createDOMFactory('path'),
	  pattern: createDOMFactory('pattern'),
	  polygon: createDOMFactory('polygon'),
	  polyline: createDOMFactory('polyline'),
	  radialGradient: createDOMFactory('radialGradient'),
	  rect: createDOMFactory('rect'),
	  stop: createDOMFactory('stop'),
	  svg: createDOMFactory('svg'),
	  text: createDOMFactory('text'),
	  tspan: createDOMFactory('tspan')
	};

	module.exports = ReactDOMFactories;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 43 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2014-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	/**
	 * ReactElementValidator provides a wrapper around a element factory
	 * which validates the props passed to the element. This is intended to be
	 * used only in DEV and could be replaced by a static type checker for languages
	 * that support it.
	 */

	'use strict';

	var ReactCurrentOwner = __webpack_require__(28);
	var ReactComponentTreeHook = __webpack_require__(44);
	var ReactElement = __webpack_require__(27);

	var checkReactTypeSpec = __webpack_require__(45);

	var canDefineProperty = __webpack_require__(31);
	var getIteratorFn = __webpack_require__(34);
	var warning = __webpack_require__(29);

	function getDeclarationErrorAddendum() {
	  if (ReactCurrentOwner.current) {
	    var name = ReactCurrentOwner.current.getName();
	    if (name) {
	      return ' Check the render method of `' + name + '`.';
	    }
	  }
	  return '';
	}

	/**
	 * Warn if there's no key explicitly set on dynamic arrays of children or
	 * object keys are not valid. This allows us to keep track of children between
	 * updates.
	 */
	var ownerHasKeyUseWarning = {};

	function getCurrentComponentErrorInfo(parentType) {
	  var info = getDeclarationErrorAddendum();

	  if (!info) {
	    var parentName = typeof parentType === 'string' ? parentType : parentType.displayName || parentType.name;
	    if (parentName) {
	      info = ' Check the top-level render call using <' + parentName + '>.';
	    }
	  }
	  return info;
	}

	/**
	 * Warn if the element doesn't have an explicit key assigned to it.
	 * This element is in an array. The array could grow and shrink or be
	 * reordered. All children that haven't already been validated are required to
	 * have a "key" property assigned to it. Error statuses are cached so a warning
	 * will only be shown once.
	 *
	 * @internal
	 * @param {ReactElement} element Element that requires a key.
	 * @param {*} parentType element's parent's type.
	 */
	function validateExplicitKey(element, parentType) {
	  if (!element._store || element._store.validated || element.key != null) {
	    return;
	  }
	  element._store.validated = true;

	  var memoizer = ownerHasKeyUseWarning.uniqueKey || (ownerHasKeyUseWarning.uniqueKey = {});

	  var currentComponentErrorInfo = getCurrentComponentErrorInfo(parentType);
	  if (memoizer[currentComponentErrorInfo]) {
	    return;
	  }
	  memoizer[currentComponentErrorInfo] = true;

	  // Usually the current owner is the offender, but if it accepts children as a
	  // property, it may be the creator of the child that's responsible for
	  // assigning it a key.
	  var childOwner = '';
	  if (element && element._owner && element._owner !== ReactCurrentOwner.current) {
	    // Give the component that originally created this child.
	    childOwner = ' It was passed a child from ' + element._owner.getName() + '.';
	  }

	  process.env.NODE_ENV !== 'production' ? warning(false, 'Each child in an array or iterator should have a unique "key" prop.' + '%s%s See https://fb.me/react-warning-keys for more information.%s', currentComponentErrorInfo, childOwner, ReactComponentTreeHook.getCurrentStackAddendum(element)) : void 0;
	}

	/**
	 * Ensure that every element either is passed in a static location, in an
	 * array with an explicit keys property defined, or in an object literal
	 * with valid key property.
	 *
	 * @internal
	 * @param {ReactNode} node Statically passed child of any type.
	 * @param {*} parentType node's parent's type.
	 */
	function validateChildKeys(node, parentType) {
	  if (typeof node !== 'object') {
	    return;
	  }
	  if (Array.isArray(node)) {
	    for (var i = 0; i < node.length; i++) {
	      var child = node[i];
	      if (ReactElement.isValidElement(child)) {
	        validateExplicitKey(child, parentType);
	      }
	    }
	  } else if (ReactElement.isValidElement(node)) {
	    // This element was passed in a valid location.
	    if (node._store) {
	      node._store.validated = true;
	    }
	  } else if (node) {
	    var iteratorFn = getIteratorFn(node);
	    // Entry iterators provide implicit keys.
	    if (iteratorFn) {
	      if (iteratorFn !== node.entries) {
	        var iterator = iteratorFn.call(node);
	        var step;
	        while (!(step = iterator.next()).done) {
	          if (ReactElement.isValidElement(step.value)) {
	            validateExplicitKey(step.value, parentType);
	          }
	        }
	      }
	    }
	  }
	}

	/**
	 * Given an element, validate that its props follow the propTypes definition,
	 * provided by the type.
	 *
	 * @param {ReactElement} element
	 */
	function validatePropTypes(element) {
	  var componentClass = element.type;
	  if (typeof componentClass !== 'function') {
	    return;
	  }
	  var name = componentClass.displayName || componentClass.name;
	  if (componentClass.propTypes) {
	    checkReactTypeSpec(componentClass.propTypes, element.props, 'prop', name, element, null);
	  }
	  if (typeof componentClass.getDefaultProps === 'function') {
	    process.env.NODE_ENV !== 'production' ? warning(componentClass.getDefaultProps.isReactClassApproved, 'getDefaultProps is only used on classic React.createClass ' + 'definitions. Use a static property named `defaultProps` instead.') : void 0;
	  }
	}

	var ReactElementValidator = {

	  createElement: function (type, props, children) {
	    var validType = typeof type === 'string' || typeof type === 'function';
	    // We warn in this case but don't throw. We expect the element creation to
	    // succeed and there will likely be errors in render.
	    if (!validType) {
	      process.env.NODE_ENV !== 'production' ? warning(false, 'React.createElement: type should not be null, undefined, boolean, or ' + 'number. It should be a string (for DOM elements) or a ReactClass ' + '(for composite components).%s', getDeclarationErrorAddendum()) : void 0;
	    }

	    var element = ReactElement.createElement.apply(this, arguments);

	    // The result can be nullish if a mock or a custom function is used.
	    // TODO: Drop this when these are no longer allowed as the type argument.
	    if (element == null) {
	      return element;
	    }

	    // Skip key warning if the type isn't valid since our key validation logic
	    // doesn't expect a non-string/function type and can throw confusing errors.
	    // We don't want exception behavior to differ between dev and prod.
	    // (Rendering will throw with a helpful message and as soon as the type is
	    // fixed, the key warnings will appear.)
	    if (validType) {
	      for (var i = 2; i < arguments.length; i++) {
	        validateChildKeys(arguments[i], type);
	      }
	    }

	    validatePropTypes(element);

	    return element;
	  },

	  createFactory: function (type) {
	    var validatedFactory = ReactElementValidator.createElement.bind(null, type);
	    // Legacy hook TODO: Warn if this is accessed
	    validatedFactory.type = type;

	    if (process.env.NODE_ENV !== 'production') {
	      if (canDefineProperty) {
	        Object.defineProperty(validatedFactory, 'type', {
	          enumerable: false,
	          get: function () {
	            process.env.NODE_ENV !== 'production' ? warning(false, 'Factory.type is deprecated. Access the class directly ' + 'before passing it to createFactory.') : void 0;
	            Object.defineProperty(this, 'type', {
	              value: type
	            });
	            return type;
	          }
	        });
	      }
	    }

	    return validatedFactory;
	  },

	  cloneElement: function (element, props, children) {
	    var newElement = ReactElement.cloneElement.apply(this, arguments);
	    for (var i = 2; i < arguments.length; i++) {
	      validateChildKeys(arguments[i], newElement.type);
	    }
	    validatePropTypes(newElement);
	    return newElement;
	  }

	};

	module.exports = ReactElementValidator;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 44 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2016-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	var _prodInvariant = __webpack_require__(25);

	var ReactCurrentOwner = __webpack_require__(28);

	var invariant = __webpack_require__(26);
	var warning = __webpack_require__(29);

	function isNative(fn) {
	  // Based on isNative() from Lodash
	  var funcToString = Function.prototype.toString;
	  var hasOwnProperty = Object.prototype.hasOwnProperty;
	  var reIsNative = RegExp('^' + funcToString
	  // Take an example native function source for comparison
	  .call(hasOwnProperty)
	  // Strip regex characters so we can use it for regex
	  .replace(/[\\^$.*+?()[\]{}|]/g, '\\$&')
	  // Remove hasOwnProperty from the template to make it generic
	  .replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, '$1.*?') + '$');
	  try {
	    var source = funcToString.call(fn);
	    return reIsNative.test(source);
	  } catch (err) {
	    return false;
	  }
	}

	var canUseCollections =
	// Array.from
	typeof Array.from === 'function' &&
	// Map
	typeof Map === 'function' && isNative(Map) &&
	// Map.prototype.keys
	Map.prototype != null && typeof Map.prototype.keys === 'function' && isNative(Map.prototype.keys) &&
	// Set
	typeof Set === 'function' && isNative(Set) &&
	// Set.prototype.keys
	Set.prototype != null && typeof Set.prototype.keys === 'function' && isNative(Set.prototype.keys);

	if (canUseCollections) {
	  var itemMap = new Map();
	  var rootIDSet = new Set();

	  var setItem = function (id, item) {
	    itemMap.set(id, item);
	  };
	  var getItem = function (id) {
	    return itemMap.get(id);
	  };
	  var removeItem = function (id) {
	    itemMap['delete'](id);
	  };
	  var getItemIDs = function () {
	    return Array.from(itemMap.keys());
	  };

	  var addRoot = function (id) {
	    rootIDSet.add(id);
	  };
	  var removeRoot = function (id) {
	    rootIDSet['delete'](id);
	  };
	  var getRootIDs = function () {
	    return Array.from(rootIDSet.keys());
	  };
	} else {
	  var itemByKey = {};
	  var rootByKey = {};

	  // Use non-numeric keys to prevent V8 performance issues:
	  // https://github.com/facebook/react/pull/7232
	  var getKeyFromID = function (id) {
	    return '.' + id;
	  };
	  var getIDFromKey = function (key) {
	    return parseInt(key.substr(1), 10);
	  };

	  var setItem = function (id, item) {
	    var key = getKeyFromID(id);
	    itemByKey[key] = item;
	  };
	  var getItem = function (id) {
	    var key = getKeyFromID(id);
	    return itemByKey[key];
	  };
	  var removeItem = function (id) {
	    var key = getKeyFromID(id);
	    delete itemByKey[key];
	  };
	  var getItemIDs = function () {
	    return Object.keys(itemByKey).map(getIDFromKey);
	  };

	  var addRoot = function (id) {
	    var key = getKeyFromID(id);
	    rootByKey[key] = true;
	  };
	  var removeRoot = function (id) {
	    var key = getKeyFromID(id);
	    delete rootByKey[key];
	  };
	  var getRootIDs = function () {
	    return Object.keys(rootByKey).map(getIDFromKey);
	  };
	}

	var unmountedIDs = [];

	function purgeDeep(id) {
	  var item = getItem(id);
	  if (item) {
	    var childIDs = item.childIDs;

	    removeItem(id);
	    childIDs.forEach(purgeDeep);
	  }
	}

	function describeComponentFrame(name, source, ownerName) {
	  return '\n    in ' + (name || 'Unknown') + (source ? ' (at ' + source.fileName.replace(/^.*[\\\/]/, '') + ':' + source.lineNumber + ')' : ownerName ? ' (created by ' + ownerName + ')' : '');
	}

	function getDisplayName(element) {
	  if (element == null) {
	    return '#empty';
	  } else if (typeof element === 'string' || typeof element === 'number') {
	    return '#text';
	  } else if (typeof element.type === 'string') {
	    return element.type;
	  } else {
	    return element.type.displayName || element.type.name || 'Unknown';
	  }
	}

	function describeID(id) {
	  var name = ReactComponentTreeHook.getDisplayName(id);
	  var element = ReactComponentTreeHook.getElement(id);
	  var ownerID = ReactComponentTreeHook.getOwnerID(id);
	  var ownerName;
	  if (ownerID) {
	    ownerName = ReactComponentTreeHook.getDisplayName(ownerID);
	  }
	  process.env.NODE_ENV !== 'production' ? warning(element, 'ReactComponentTreeHook: Missing React element for debugID %s when ' + 'building stack', id) : void 0;
	  return describeComponentFrame(name, element && element._source, ownerName);
	}

	var ReactComponentTreeHook = {
	  onSetChildren: function (id, nextChildIDs) {
	    var item = getItem(id);
	    !item ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Item must have been set') : _prodInvariant('144') : void 0;
	    item.childIDs = nextChildIDs;

	    for (var i = 0; i < nextChildIDs.length; i++) {
	      var nextChildID = nextChildIDs[i];
	      var nextChild = getItem(nextChildID);
	      !nextChild ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Expected hook events to fire for the child before its parent includes it in onSetChildren().') : _prodInvariant('140') : void 0;
	      !(nextChild.childIDs != null || typeof nextChild.element !== 'object' || nextChild.element == null) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Expected onSetChildren() to fire for a container child before its parent includes it in onSetChildren().') : _prodInvariant('141') : void 0;
	      !nextChild.isMounted ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Expected onMountComponent() to fire for the child before its parent includes it in onSetChildren().') : _prodInvariant('71') : void 0;
	      if (nextChild.parentID == null) {
	        nextChild.parentID = id;
	        // TODO: This shouldn't be necessary but mounting a new root during in
	        // componentWillMount currently causes not-yet-mounted components to
	        // be purged from our tree data so their parent id is missing.
	      }
	      !(nextChild.parentID === id) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Expected onBeforeMountComponent() parent and onSetChildren() to be consistent (%s has parents %s and %s).', nextChildID, nextChild.parentID, id) : _prodInvariant('142', nextChildID, nextChild.parentID, id) : void 0;
	    }
	  },
	  onBeforeMountComponent: function (id, element, parentID) {
	    var item = {
	      element: element,
	      parentID: parentID,
	      text: null,
	      childIDs: [],
	      isMounted: false,
	      updateCount: 0
	    };
	    setItem(id, item);
	  },
	  onBeforeUpdateComponent: function (id, element) {
	    var item = getItem(id);
	    if (!item || !item.isMounted) {
	      // We may end up here as a result of setState() in componentWillUnmount().
	      // In this case, ignore the element.
	      return;
	    }
	    item.element = element;
	  },
	  onMountComponent: function (id) {
	    var item = getItem(id);
	    !item ? process.env.NODE_ENV !== 'production' ? invariant(false, 'Item must have been set') : _prodInvariant('144') : void 0;
	    item.isMounted = true;
	    var isRoot = item.parentID === 0;
	    if (isRoot) {
	      addRoot(id);
	    }
	  },
	  onUpdateComponent: function (id) {
	    var item = getItem(id);
	    if (!item || !item.isMounted) {
	      // We may end up here as a result of setState() in componentWillUnmount().
	      // In this case, ignore the element.
	      return;
	    }
	    item.updateCount++;
	  },
	  onUnmountComponent: function (id) {
	    var item = getItem(id);
	    if (item) {
	      // We need to check if it exists.
	      // `item` might not exist if it is inside an error boundary, and a sibling
	      // error boundary child threw while mounting. Then this instance never
	      // got a chance to mount, but it still gets an unmounting event during
	      // the error boundary cleanup.
	      item.isMounted = false;
	      var isRoot = item.parentID === 0;
	      if (isRoot) {
	        removeRoot(id);
	      }
	    }
	    unmountedIDs.push(id);
	  },
	  purgeUnmountedComponents: function () {
	    if (ReactComponentTreeHook._preventPurging) {
	      // Should only be used for testing.
	      return;
	    }

	    for (var i = 0; i < unmountedIDs.length; i++) {
	      var id = unmountedIDs[i];
	      purgeDeep(id);
	    }
	    unmountedIDs.length = 0;
	  },
	  isMounted: function (id) {
	    var item = getItem(id);
	    return item ? item.isMounted : false;
	  },
	  getCurrentStackAddendum: function (topElement) {
	    var info = '';
	    if (topElement) {
	      var name = getDisplayName(topElement);
	      var owner = topElement._owner;
	      info += describeComponentFrame(name, topElement._source, owner && owner.getName());
	    }

	    var currentOwner = ReactCurrentOwner.current;
	    var id = currentOwner && currentOwner._debugID;

	    info += ReactComponentTreeHook.getStackAddendumByID(id);
	    return info;
	  },
	  getStackAddendumByID: function (id) {
	    var info = '';
	    while (id) {
	      info += describeID(id);
	      id = ReactComponentTreeHook.getParentID(id);
	    }
	    return info;
	  },
	  getChildIDs: function (id) {
	    var item = getItem(id);
	    return item ? item.childIDs : [];
	  },
	  getDisplayName: function (id) {
	    var element = ReactComponentTreeHook.getElement(id);
	    if (!element) {
	      return null;
	    }
	    return getDisplayName(element);
	  },
	  getElement: function (id) {
	    var item = getItem(id);
	    return item ? item.element : null;
	  },
	  getOwnerID: function (id) {
	    var element = ReactComponentTreeHook.getElement(id);
	    if (!element || !element._owner) {
	      return null;
	    }
	    return element._owner._debugID;
	  },
	  getParentID: function (id) {
	    var item = getItem(id);
	    return item ? item.parentID : null;
	  },
	  getSource: function (id) {
	    var item = getItem(id);
	    var element = item ? item.element : null;
	    var source = element != null ? element._source : null;
	    return source;
	  },
	  getText: function (id) {
	    var element = ReactComponentTreeHook.getElement(id);
	    if (typeof element === 'string') {
	      return element;
	    } else if (typeof element === 'number') {
	      return '' + element;
	    } else {
	      return null;
	    }
	  },
	  getUpdateCount: function (id) {
	    var item = getItem(id);
	    return item ? item.updateCount : 0;
	  },


	  getRootIDs: getRootIDs,
	  getRegisteredIDs: getItemIDs
	};

	module.exports = ReactComponentTreeHook;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 45 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var _prodInvariant = __webpack_require__(25);

	var ReactPropTypeLocationNames = __webpack_require__(41);
	var ReactPropTypesSecret = __webpack_require__(46);

	var invariant = __webpack_require__(26);
	var warning = __webpack_require__(29);

	var ReactComponentTreeHook;

	if (typeof process !== 'undefined' && process.env && process.env.NODE_ENV === 'test') {
	  // Temporary hack.
	  // Inline requires don't work well with Jest:
	  // https://github.com/facebook/react/issues/7240
	  // Remove the inline requires when we don't need them anymore:
	  // https://github.com/facebook/react/pull/7178
	  ReactComponentTreeHook = __webpack_require__(44);
	}

	var loggedTypeFailures = {};

	/**
	 * Assert that the values match with the type specs.
	 * Error messages are memorized and will only be shown once.
	 *
	 * @param {object} typeSpecs Map of name to a ReactPropType
	 * @param {object} values Runtime values that need to be type-checked
	 * @param {string} location e.g. "prop", "context", "child context"
	 * @param {string} componentName Name of the component for error messages.
	 * @param {?object} element The React element that is being type-checked
	 * @param {?number} debugID The React component instance that is being type-checked
	 * @private
	 */
	function checkReactTypeSpec(typeSpecs, values, location, componentName, element, debugID) {
	  for (var typeSpecName in typeSpecs) {
	    if (typeSpecs.hasOwnProperty(typeSpecName)) {
	      var error;
	      // Prop type validation may throw. In case they do, we don't want to
	      // fail the render phase where it didn't fail before. So we log it.
	      // After these have been cleaned up, we'll let them throw.
	      try {
	        // This is intentionally an invariant that gets caught. It's the same
	        // behavior as without this statement except with a better message.
	        !(typeof typeSpecs[typeSpecName] === 'function') ? process.env.NODE_ENV !== 'production' ? invariant(false, '%s: %s type `%s` is invalid; it must be a function, usually from React.PropTypes.', componentName || 'React class', ReactPropTypeLocationNames[location], typeSpecName) : _prodInvariant('84', componentName || 'React class', ReactPropTypeLocationNames[location], typeSpecName) : void 0;
	        error = typeSpecs[typeSpecName](values, typeSpecName, componentName, location, null, ReactPropTypesSecret);
	      } catch (ex) {
	        error = ex;
	      }
	      process.env.NODE_ENV !== 'production' ? warning(!error || error instanceof Error, '%s: type specification of %s `%s` is invalid; the type checker ' + 'function must return `null` or an `Error` but returned a %s. ' + 'You may have forgotten to pass an argument to the type checker ' + 'creator (arrayOf, instanceOf, objectOf, oneOf, oneOfType, and ' + 'shape all require an argument).', componentName || 'React class', ReactPropTypeLocationNames[location], typeSpecName, typeof error) : void 0;
	      if (error instanceof Error && !(error.message in loggedTypeFailures)) {
	        // Only monitor this failure once because there tends to be a lot of the
	        // same error.
	        loggedTypeFailures[error.message] = true;

	        var componentStackInfo = '';

	        if (process.env.NODE_ENV !== 'production') {
	          if (!ReactComponentTreeHook) {
	            ReactComponentTreeHook = __webpack_require__(44);
	          }
	          if (debugID !== null) {
	            componentStackInfo = ReactComponentTreeHook.getStackAddendumByID(debugID);
	          } else if (element !== null) {
	            componentStackInfo = ReactComponentTreeHook.getCurrentStackAddendum(element);
	          }
	        }

	        process.env.NODE_ENV !== 'production' ? warning(false, 'Failed %s type: %s%s', location, error.message, componentStackInfo) : void 0;
	      }
	    }
	  }
	}

	module.exports = checkReactTypeSpec;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 46 */
/***/ function(module, exports) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 * 
	 */

	'use strict';

	var ReactPropTypesSecret = 'SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED';

	module.exports = ReactPropTypesSecret;

/***/ },
/* 47 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	var ReactElement = __webpack_require__(27);
	var ReactPropTypeLocationNames = __webpack_require__(41);
	var ReactPropTypesSecret = __webpack_require__(46);

	var emptyFunction = __webpack_require__(30);
	var getIteratorFn = __webpack_require__(34);
	var warning = __webpack_require__(29);

	/**
	 * Collection of methods that allow declaration and validation of props that are
	 * supplied to React components. Example usage:
	 *
	 *   var Props = require('ReactPropTypes');
	 *   var MyArticle = React.createClass({
	 *     propTypes: {
	 *       // An optional string prop named "description".
	 *       description: Props.string,
	 *
	 *       // A required enum prop named "category".
	 *       category: Props.oneOf(['News','Photos']).isRequired,
	 *
	 *       // A prop named "dialog" that requires an instance of Dialog.
	 *       dialog: Props.instanceOf(Dialog).isRequired
	 *     },
	 *     render: function() { ... }
	 *   });
	 *
	 * A more formal specification of how these methods are used:
	 *
	 *   type := array|bool|func|object|number|string|oneOf([...])|instanceOf(...)
	 *   decl := ReactPropTypes.{type}(.isRequired)?
	 *
	 * Each and every declaration produces a function with the same signature. This
	 * allows the creation of custom validation functions. For example:
	 *
	 *  var MyLink = React.createClass({
	 *    propTypes: {
	 *      // An optional string or URI prop named "href".
	 *      href: function(props, propName, componentName) {
	 *        var propValue = props[propName];
	 *        if (propValue != null && typeof propValue !== 'string' &&
	 *            !(propValue instanceof URI)) {
	 *          return new Error(
	 *            'Expected a string or an URI for ' + propName + ' in ' +
	 *            componentName
	 *          );
	 *        }
	 *      }
	 *    },
	 *    render: function() {...}
	 *  });
	 *
	 * @internal
	 */

	var ANONYMOUS = '<<anonymous>>';

	var ReactPropTypes = {
	  array: createPrimitiveTypeChecker('array'),
	  bool: createPrimitiveTypeChecker('boolean'),
	  func: createPrimitiveTypeChecker('function'),
	  number: createPrimitiveTypeChecker('number'),
	  object: createPrimitiveTypeChecker('object'),
	  string: createPrimitiveTypeChecker('string'),
	  symbol: createPrimitiveTypeChecker('symbol'),

	  any: createAnyTypeChecker(),
	  arrayOf: createArrayOfTypeChecker,
	  element: createElementTypeChecker(),
	  instanceOf: createInstanceTypeChecker,
	  node: createNodeChecker(),
	  objectOf: createObjectOfTypeChecker,
	  oneOf: createEnumTypeChecker,
	  oneOfType: createUnionTypeChecker,
	  shape: createShapeTypeChecker
	};

	/**
	 * inlined Object.is polyfill to avoid requiring consumers ship their own
	 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/is
	 */
	/*eslint-disable no-self-compare*/
	function is(x, y) {
	  // SameValue algorithm
	  if (x === y) {
	    // Steps 1-5, 7-10
	    // Steps 6.b-6.e: +0 != -0
	    return x !== 0 || 1 / x === 1 / y;
	  } else {
	    // Step 6.a: NaN == NaN
	    return x !== x && y !== y;
	  }
	}
	/*eslint-enable no-self-compare*/

	/**
	 * We use an Error-like object for backward compatibility as people may call
	 * PropTypes directly and inspect their output. However we don't use real
	 * Errors anymore. We don't inspect their stack anyway, and creating them
	 * is prohibitively expensive if they are created too often, such as what
	 * happens in oneOfType() for any type before the one that matched.
	 */
	function PropTypeError(message) {
	  this.message = message;
	  this.stack = '';
	}
	// Make `instanceof Error` still work for returned errors.
	PropTypeError.prototype = Error.prototype;

	function createChainableTypeChecker(validate) {
	  if (process.env.NODE_ENV !== 'production') {
	    var manualPropTypeCallCache = {};
	  }
	  function checkType(isRequired, props, propName, componentName, location, propFullName, secret) {
	    componentName = componentName || ANONYMOUS;
	    propFullName = propFullName || propName;
	    if (process.env.NODE_ENV !== 'production') {
	      if (secret !== ReactPropTypesSecret && typeof console !== 'undefined') {
	        var cacheKey = componentName + ':' + propName;
	        if (!manualPropTypeCallCache[cacheKey]) {
	          process.env.NODE_ENV !== 'production' ? warning(false, 'You are manually calling a React.PropTypes validation ' + 'function for the `%s` prop on `%s`. This is deprecated ' + 'and will not work in production with the next major version. ' + 'You may be seeing this warning due to a third-party PropTypes ' + 'library. See https://fb.me/react-warning-dont-call-proptypes ' + 'for details.', propFullName, componentName) : void 0;
	          manualPropTypeCallCache[cacheKey] = true;
	        }
	      }
	    }
	    if (props[propName] == null) {
	      var locationName = ReactPropTypeLocationNames[location];
	      if (isRequired) {
	        if (props[propName] === null) {
	          return new PropTypeError('The ' + locationName + ' `' + propFullName + '` is marked as required ' + ('in `' + componentName + '`, but its value is `null`.'));
	        }
	        return new PropTypeError('The ' + locationName + ' `' + propFullName + '` is marked as required in ' + ('`' + componentName + '`, but its value is `undefined`.'));
	      }
	      return null;
	    } else {
	      return validate(props, propName, componentName, location, propFullName);
	    }
	  }

	  var chainedCheckType = checkType.bind(null, false);
	  chainedCheckType.isRequired = checkType.bind(null, true);

	  return chainedCheckType;
	}

	function createPrimitiveTypeChecker(expectedType) {
	  function validate(props, propName, componentName, location, propFullName, secret) {
	    var propValue = props[propName];
	    var propType = getPropType(propValue);
	    if (propType !== expectedType) {
	      var locationName = ReactPropTypeLocationNames[location];
	      // `propValue` being instance of, say, date/regexp, pass the 'object'
	      // check, but we can offer a more precise error message here rather than
	      // 'of type `object`'.
	      var preciseType = getPreciseType(propValue);

	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of type ' + ('`' + preciseType + '` supplied to `' + componentName + '`, expected ') + ('`' + expectedType + '`.'));
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function createAnyTypeChecker() {
	  return createChainableTypeChecker(emptyFunction.thatReturns(null));
	}

	function createArrayOfTypeChecker(typeChecker) {
	  function validate(props, propName, componentName, location, propFullName) {
	    if (typeof typeChecker !== 'function') {
	      return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside arrayOf.');
	    }
	    var propValue = props[propName];
	    if (!Array.isArray(propValue)) {
	      var locationName = ReactPropTypeLocationNames[location];
	      var propType = getPropType(propValue);
	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an array.'));
	    }
	    for (var i = 0; i < propValue.length; i++) {
	      var error = typeChecker(propValue, i, componentName, location, propFullName + '[' + i + ']', ReactPropTypesSecret);
	      if (error instanceof Error) {
	        return error;
	      }
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function createElementTypeChecker() {
	  function validate(props, propName, componentName, location, propFullName) {
	    var propValue = props[propName];
	    if (!ReactElement.isValidElement(propValue)) {
	      var locationName = ReactPropTypeLocationNames[location];
	      var propType = getPropType(propValue);
	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement.'));
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function createInstanceTypeChecker(expectedClass) {
	  function validate(props, propName, componentName, location, propFullName) {
	    if (!(props[propName] instanceof expectedClass)) {
	      var locationName = ReactPropTypeLocationNames[location];
	      var expectedClassName = expectedClass.name || ANONYMOUS;
	      var actualClassName = getClassName(props[propName]);
	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of type ' + ('`' + actualClassName + '` supplied to `' + componentName + '`, expected ') + ('instance of `' + expectedClassName + '`.'));
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function createEnumTypeChecker(expectedValues) {
	  if (!Array.isArray(expectedValues)) {
	    process.env.NODE_ENV !== 'production' ? warning(false, 'Invalid argument supplied to oneOf, expected an instance of array.') : void 0;
	    return emptyFunction.thatReturnsNull;
	  }

	  function validate(props, propName, componentName, location, propFullName) {
	    var propValue = props[propName];
	    for (var i = 0; i < expectedValues.length; i++) {
	      if (is(propValue, expectedValues[i])) {
	        return null;
	      }
	    }

	    var locationName = ReactPropTypeLocationNames[location];
	    var valuesString = JSON.stringify(expectedValues);
	    return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of value `' + propValue + '` ' + ('supplied to `' + componentName + '`, expected one of ' + valuesString + '.'));
	  }
	  return createChainableTypeChecker(validate);
	}

	function createObjectOfTypeChecker(typeChecker) {
	  function validate(props, propName, componentName, location, propFullName) {
	    if (typeof typeChecker !== 'function') {
	      return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside objectOf.');
	    }
	    var propValue = props[propName];
	    var propType = getPropType(propValue);
	    if (propType !== 'object') {
	      var locationName = ReactPropTypeLocationNames[location];
	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an object.'));
	    }
	    for (var key in propValue) {
	      if (propValue.hasOwnProperty(key)) {
	        var error = typeChecker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
	        if (error instanceof Error) {
	          return error;
	        }
	      }
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function createUnionTypeChecker(arrayOfTypeCheckers) {
	  if (!Array.isArray(arrayOfTypeCheckers)) {
	    process.env.NODE_ENV !== 'production' ? warning(false, 'Invalid argument supplied to oneOfType, expected an instance of array.') : void 0;
	    return emptyFunction.thatReturnsNull;
	  }

	  function validate(props, propName, componentName, location, propFullName) {
	    for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
	      var checker = arrayOfTypeCheckers[i];
	      if (checker(props, propName, componentName, location, propFullName, ReactPropTypesSecret) == null) {
	        return null;
	      }
	    }

	    var locationName = ReactPropTypeLocationNames[location];
	    return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`.'));
	  }
	  return createChainableTypeChecker(validate);
	}

	function createNodeChecker() {
	  function validate(props, propName, componentName, location, propFullName) {
	    if (!isNode(props[propName])) {
	      var locationName = ReactPropTypeLocationNames[location];
	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`, expected a ReactNode.'));
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function createShapeTypeChecker(shapeTypes) {
	  function validate(props, propName, componentName, location, propFullName) {
	    var propValue = props[propName];
	    var propType = getPropType(propValue);
	    if (propType !== 'object') {
	      var locationName = ReactPropTypeLocationNames[location];
	      return new PropTypeError('Invalid ' + locationName + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
	    }
	    for (var key in shapeTypes) {
	      var checker = shapeTypes[key];
	      if (!checker) {
	        continue;
	      }
	      var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
	      if (error) {
	        return error;
	      }
	    }
	    return null;
	  }
	  return createChainableTypeChecker(validate);
	}

	function isNode(propValue) {
	  switch (typeof propValue) {
	    case 'number':
	    case 'string':
	    case 'undefined':
	      return true;
	    case 'boolean':
	      return !propValue;
	    case 'object':
	      if (Array.isArray(propValue)) {
	        return propValue.every(isNode);
	      }
	      if (propValue === null || ReactElement.isValidElement(propValue)) {
	        return true;
	      }

	      var iteratorFn = getIteratorFn(propValue);
	      if (iteratorFn) {
	        var iterator = iteratorFn.call(propValue);
	        var step;
	        if (iteratorFn !== propValue.entries) {
	          while (!(step = iterator.next()).done) {
	            if (!isNode(step.value)) {
	              return false;
	            }
	          }
	        } else {
	          // Iterator will provide entry [k,v] tuples rather than values.
	          while (!(step = iterator.next()).done) {
	            var entry = step.value;
	            if (entry) {
	              if (!isNode(entry[1])) {
	                return false;
	              }
	            }
	          }
	        }
	      } else {
	        return false;
	      }

	      return true;
	    default:
	      return false;
	  }
	}

	function isSymbol(propType, propValue) {
	  // Native Symbol.
	  if (propType === 'symbol') {
	    return true;
	  }

	  // 19.4.3.5 Symbol.prototype[@@toStringTag] === 'Symbol'
	  if (propValue['@@toStringTag'] === 'Symbol') {
	    return true;
	  }

	  // Fallback for non-spec compliant Symbols which are polyfilled.
	  if (typeof Symbol === 'function' && propValue instanceof Symbol) {
	    return true;
	  }

	  return false;
	}

	// Equivalent of `typeof` but with special handling for array and regexp.
	function getPropType(propValue) {
	  var propType = typeof propValue;
	  if (Array.isArray(propValue)) {
	    return 'array';
	  }
	  if (propValue instanceof RegExp) {
	    // Old webkits (at least until Android 4.0) return 'function' rather than
	    // 'object' for typeof a RegExp. We'll normalize this here so that /bla/
	    // passes PropTypes.object.
	    return 'object';
	  }
	  if (isSymbol(propType, propValue)) {
	    return 'symbol';
	  }
	  return propType;
	}

	// This handles more types than `getPropType`. Only used for error messages.
	// See `createPrimitiveTypeChecker`.
	function getPreciseType(propValue) {
	  var propType = getPropType(propValue);
	  if (propType === 'object') {
	    if (propValue instanceof Date) {
	      return 'date';
	    } else if (propValue instanceof RegExp) {
	      return 'regexp';
	    }
	  }
	  return propType;
	}

	// Returns class name of the object, if any.
	function getClassName(propValue) {
	  if (!propValue.constructor || !propValue.constructor.name) {
	    return ANONYMOUS;
	  }
	  return propValue.constructor.name;
	}

	module.exports = ReactPropTypes;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 48 */
/***/ function(module, exports) {

	/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */

	'use strict';

	module.exports = '15.4.0';

/***/ },
/* 49 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-present, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 *
	 */
	'use strict';

	var _prodInvariant = __webpack_require__(25);

	var ReactElement = __webpack_require__(27);

	var invariant = __webpack_require__(26);

	/**
	 * Returns the first child in a collection of children and verifies that there
	 * is only one child in the collection.
	 *
	 * See https://facebook.github.io/react/docs/top-level-api.html#react.children.only
	 *
	 * The current implementation of this function assumes that a single child gets
	 * passed without a wrapper, but the purpose of this helper function is to
	 * abstract away the particular structure of children.
	 *
	 * @param {?object} children Child collection structure.
	 * @return {ReactElement} The first and only `ReactElement` contained in the
	 * structure.
	 */
	function onlyChild(children) {
	  !ReactElement.isValidElement(children) ? process.env.NODE_ENV !== 'production' ? invariant(false, 'React.Children.only expected to receive a single React element child.') : _prodInvariant('143') : void 0;
	  return children;
	}

	module.exports = onlyChild;
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 50 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	exports.__esModule = true;

	var _react = __webpack_require__(20);

	exports["default"] = _react.PropTypes.shape({
	  subscribe: _react.PropTypes.func.isRequired,
	  dispatch: _react.PropTypes.func.isRequired,
	  getState: _react.PropTypes.func.isRequired
	});

/***/ },
/* 51 */
/***/ function(module, exports) {

	'use strict';

	exports.__esModule = true;
	exports["default"] = warning;
	/**
	 * Prints a warning in the console if it exists.
	 *
	 * @param {String} message The warning message.
	 * @returns {void}
	 */
	function warning(message) {
	  /* eslint-disable no-console */
	  if (typeof console !== 'undefined' && typeof console.error === 'function') {
	    console.error(message);
	  }
	  /* eslint-enable no-console */
	  try {
	    // This error was thrown as a convenience so that if you enable
	    // "break on all exceptions" in your console,
	    // it would pause the execution at this line.
	    throw new Error(message);
	    /* eslint-disable no-empty */
	  } catch (e) {}
	  /* eslint-enable no-empty */
	}

/***/ },
/* 52 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {'use strict';

	exports.__esModule = true;

	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

	exports["default"] = connect;

	var _react = __webpack_require__(20);

	var _storeShape = __webpack_require__(50);

	var _storeShape2 = _interopRequireDefault(_storeShape);

	var _shallowEqual = __webpack_require__(53);

	var _shallowEqual2 = _interopRequireDefault(_shallowEqual);

	var _wrapActionCreators = __webpack_require__(54);

	var _wrapActionCreators2 = _interopRequireDefault(_wrapActionCreators);

	var _warning = __webpack_require__(51);

	var _warning2 = _interopRequireDefault(_warning);

	var _isPlainObject = __webpack_require__(5);

	var _isPlainObject2 = _interopRequireDefault(_isPlainObject);

	var _hoistNonReactStatics = __webpack_require__(55);

	var _hoistNonReactStatics2 = _interopRequireDefault(_hoistNonReactStatics);

	var _invariant = __webpack_require__(56);

	var _invariant2 = _interopRequireDefault(_invariant);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var defaultMapStateToProps = function defaultMapStateToProps(state) {
	  return {};
	}; // eslint-disable-line no-unused-vars
	var defaultMapDispatchToProps = function defaultMapDispatchToProps(dispatch) {
	  return { dispatch: dispatch };
	};
	var defaultMergeProps = function defaultMergeProps(stateProps, dispatchProps, parentProps) {
	  return _extends({}, parentProps, stateProps, dispatchProps);
	};

	function getDisplayName(WrappedComponent) {
	  return WrappedComponent.displayName || WrappedComponent.name || 'Component';
	}

	var errorObject = { value: null };
	function tryCatch(fn, ctx) {
	  try {
	    return fn.apply(ctx);
	  } catch (e) {
	    errorObject.value = e;
	    return errorObject;
	  }
	}

	// Helps track hot reloading.
	var nextVersion = 0;

	function connect(mapStateToProps, mapDispatchToProps, mergeProps) {
	  var options = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};

	  var shouldSubscribe = Boolean(mapStateToProps);
	  var mapState = mapStateToProps || defaultMapStateToProps;

	  var mapDispatch = void 0;
	  if (typeof mapDispatchToProps === 'function') {
	    mapDispatch = mapDispatchToProps;
	  } else if (!mapDispatchToProps) {
	    mapDispatch = defaultMapDispatchToProps;
	  } else {
	    mapDispatch = (0, _wrapActionCreators2["default"])(mapDispatchToProps);
	  }

	  var finalMergeProps = mergeProps || defaultMergeProps;
	  var _options$pure = options.pure,
	      pure = _options$pure === undefined ? true : _options$pure,
	      _options$withRef = options.withRef,
	      withRef = _options$withRef === undefined ? false : _options$withRef;

	  var checkMergedEquals = pure && finalMergeProps !== defaultMergeProps;

	  // Helps track hot reloading.
	  var version = nextVersion++;

	  return function wrapWithConnect(WrappedComponent) {
	    var connectDisplayName = 'Connect(' + getDisplayName(WrappedComponent) + ')';

	    function checkStateShape(props, methodName) {
	      if (!(0, _isPlainObject2["default"])(props)) {
	        (0, _warning2["default"])(methodName + '() in ' + connectDisplayName + ' must return a plain object. ' + ('Instead received ' + props + '.'));
	      }
	    }

	    function computeMergedProps(stateProps, dispatchProps, parentProps) {
	      var mergedProps = finalMergeProps(stateProps, dispatchProps, parentProps);
	      if (process.env.NODE_ENV !== 'production') {
	        checkStateShape(mergedProps, 'mergeProps');
	      }
	      return mergedProps;
	    }

	    var Connect = function (_Component) {
	      _inherits(Connect, _Component);

	      Connect.prototype.shouldComponentUpdate = function shouldComponentUpdate() {
	        return !pure || this.haveOwnPropsChanged || this.hasStoreStateChanged;
	      };

	      function Connect(props, context) {
	        _classCallCheck(this, Connect);

	        var _this = _possibleConstructorReturn(this, _Component.call(this, props, context));

	        _this.version = version;
	        _this.store = props.store || context.store;

	        (0, _invariant2["default"])(_this.store, 'Could not find "store" in either the context or ' + ('props of "' + connectDisplayName + '". ') + 'Either wrap the root component in a <Provider>, ' + ('or explicitly pass "store" as a prop to "' + connectDisplayName + '".'));

	        var storeState = _this.store.getState();
	        _this.state = { storeState: storeState };
	        _this.clearCache();
	        return _this;
	      }

	      Connect.prototype.computeStateProps = function computeStateProps(store, props) {
	        if (!this.finalMapStateToProps) {
	          return this.configureFinalMapState(store, props);
	        }

	        var state = store.getState();
	        var stateProps = this.doStatePropsDependOnOwnProps ? this.finalMapStateToProps(state, props) : this.finalMapStateToProps(state);

	        if (process.env.NODE_ENV !== 'production') {
	          checkStateShape(stateProps, 'mapStateToProps');
	        }
	        return stateProps;
	      };

	      Connect.prototype.configureFinalMapState = function configureFinalMapState(store, props) {
	        var mappedState = mapState(store.getState(), props);
	        var isFactory = typeof mappedState === 'function';

	        this.finalMapStateToProps = isFactory ? mappedState : mapState;
	        this.doStatePropsDependOnOwnProps = this.finalMapStateToProps.length !== 1;

	        if (isFactory) {
	          return this.computeStateProps(store, props);
	        }

	        if (process.env.NODE_ENV !== 'production') {
	          checkStateShape(mappedState, 'mapStateToProps');
	        }
	        return mappedState;
	      };

	      Connect.prototype.computeDispatchProps = function computeDispatchProps(store, props) {
	        if (!this.finalMapDispatchToProps) {
	          return this.configureFinalMapDispatch(store, props);
	        }

	        var dispatch = store.dispatch;

	        var dispatchProps = this.doDispatchPropsDependOnOwnProps ? this.finalMapDispatchToProps(dispatch, props) : this.finalMapDispatchToProps(dispatch);

	        if (process.env.NODE_ENV !== 'production') {
	          checkStateShape(dispatchProps, 'mapDispatchToProps');
	        }
	        return dispatchProps;
	      };

	      Connect.prototype.configureFinalMapDispatch = function configureFinalMapDispatch(store, props) {
	        var mappedDispatch = mapDispatch(store.dispatch, props);
	        var isFactory = typeof mappedDispatch === 'function';

	        this.finalMapDispatchToProps = isFactory ? mappedDispatch : mapDispatch;
	        this.doDispatchPropsDependOnOwnProps = this.finalMapDispatchToProps.length !== 1;

	        if (isFactory) {
	          return this.computeDispatchProps(store, props);
	        }

	        if (process.env.NODE_ENV !== 'production') {
	          checkStateShape(mappedDispatch, 'mapDispatchToProps');
	        }
	        return mappedDispatch;
	      };

	      Connect.prototype.updateStatePropsIfNeeded = function updateStatePropsIfNeeded() {
	        var nextStateProps = this.computeStateProps(this.store, this.props);
	        if (this.stateProps && (0, _shallowEqual2["default"])(nextStateProps, this.stateProps)) {
	          return false;
	        }

	        this.stateProps = nextStateProps;
	        return true;
	      };

	      Connect.prototype.updateDispatchPropsIfNeeded = function updateDispatchPropsIfNeeded() {
	        var nextDispatchProps = this.computeDispatchProps(this.store, this.props);
	        if (this.dispatchProps && (0, _shallowEqual2["default"])(nextDispatchProps, this.dispatchProps)) {
	          return false;
	        }

	        this.dispatchProps = nextDispatchProps;
	        return true;
	      };

	      Connect.prototype.updateMergedPropsIfNeeded = function updateMergedPropsIfNeeded() {
	        var nextMergedProps = computeMergedProps(this.stateProps, this.dispatchProps, this.props);
	        if (this.mergedProps && checkMergedEquals && (0, _shallowEqual2["default"])(nextMergedProps, this.mergedProps)) {
	          return false;
	        }

	        this.mergedProps = nextMergedProps;
	        return true;
	      };

	      Connect.prototype.isSubscribed = function isSubscribed() {
	        return typeof this.unsubscribe === 'function';
	      };

	      Connect.prototype.trySubscribe = function trySubscribe() {
	        if (shouldSubscribe && !this.unsubscribe) {
	          this.unsubscribe = this.store.subscribe(this.handleChange.bind(this));
	          this.handleChange();
	        }
	      };

	      Connect.prototype.tryUnsubscribe = function tryUnsubscribe() {
	        if (this.unsubscribe) {
	          this.unsubscribe();
	          this.unsubscribe = null;
	        }
	      };

	      Connect.prototype.componentDidMount = function componentDidMount() {
	        this.trySubscribe();
	      };

	      Connect.prototype.componentWillReceiveProps = function componentWillReceiveProps(nextProps) {
	        if (!pure || !(0, _shallowEqual2["default"])(nextProps, this.props)) {
	          this.haveOwnPropsChanged = true;
	        }
	      };

	      Connect.prototype.componentWillUnmount = function componentWillUnmount() {
	        this.tryUnsubscribe();
	        this.clearCache();
	      };

	      Connect.prototype.clearCache = function clearCache() {
	        this.dispatchProps = null;
	        this.stateProps = null;
	        this.mergedProps = null;
	        this.haveOwnPropsChanged = true;
	        this.hasStoreStateChanged = true;
	        this.haveStatePropsBeenPrecalculated = false;
	        this.statePropsPrecalculationError = null;
	        this.renderedElement = null;
	        this.finalMapDispatchToProps = null;
	        this.finalMapStateToProps = null;
	      };

	      Connect.prototype.handleChange = function handleChange() {
	        if (!this.unsubscribe) {
	          return;
	        }

	        var storeState = this.store.getState();
	        var prevStoreState = this.state.storeState;
	        if (pure && prevStoreState === storeState) {
	          return;
	        }

	        if (pure && !this.doStatePropsDependOnOwnProps) {
	          var haveStatePropsChanged = tryCatch(this.updateStatePropsIfNeeded, this);
	          if (!haveStatePropsChanged) {
	            return;
	          }
	          if (haveStatePropsChanged === errorObject) {
	            this.statePropsPrecalculationError = errorObject.value;
	          }
	          this.haveStatePropsBeenPrecalculated = true;
	        }

	        this.hasStoreStateChanged = true;
	        this.setState({ storeState: storeState });
	      };

	      Connect.prototype.getWrappedInstance = function getWrappedInstance() {
	        (0, _invariant2["default"])(withRef, 'To access the wrapped instance, you need to specify ' + '{ withRef: true } as the fourth argument of the connect() call.');

	        return this.refs.wrappedInstance;
	      };

	      Connect.prototype.render = function render() {
	        var haveOwnPropsChanged = this.haveOwnPropsChanged,
	            hasStoreStateChanged = this.hasStoreStateChanged,
	            haveStatePropsBeenPrecalculated = this.haveStatePropsBeenPrecalculated,
	            statePropsPrecalculationError = this.statePropsPrecalculationError,
	            renderedElement = this.renderedElement;


	        this.haveOwnPropsChanged = false;
	        this.hasStoreStateChanged = false;
	        this.haveStatePropsBeenPrecalculated = false;
	        this.statePropsPrecalculationError = null;

	        if (statePropsPrecalculationError) {
	          throw statePropsPrecalculationError;
	        }

	        var shouldUpdateStateProps = true;
	        var shouldUpdateDispatchProps = true;
	        if (pure && renderedElement) {
	          shouldUpdateStateProps = hasStoreStateChanged || haveOwnPropsChanged && this.doStatePropsDependOnOwnProps;
	          shouldUpdateDispatchProps = haveOwnPropsChanged && this.doDispatchPropsDependOnOwnProps;
	        }

	        var haveStatePropsChanged = false;
	        var haveDispatchPropsChanged = false;
	        if (haveStatePropsBeenPrecalculated) {
	          haveStatePropsChanged = true;
	        } else if (shouldUpdateStateProps) {
	          haveStatePropsChanged = this.updateStatePropsIfNeeded();
	        }
	        if (shouldUpdateDispatchProps) {
	          haveDispatchPropsChanged = this.updateDispatchPropsIfNeeded();
	        }

	        var haveMergedPropsChanged = true;
	        if (haveStatePropsChanged || haveDispatchPropsChanged || haveOwnPropsChanged) {
	          haveMergedPropsChanged = this.updateMergedPropsIfNeeded();
	        } else {
	          haveMergedPropsChanged = false;
	        }

	        if (!haveMergedPropsChanged && renderedElement) {
	          return renderedElement;
	        }

	        if (withRef) {
	          this.renderedElement = (0, _react.createElement)(WrappedComponent, _extends({}, this.mergedProps, {
	            ref: 'wrappedInstance'
	          }));
	        } else {
	          this.renderedElement = (0, _react.createElement)(WrappedComponent, this.mergedProps);
	        }

	        return this.renderedElement;
	      };

	      return Connect;
	    }(_react.Component);

	    Connect.displayName = connectDisplayName;
	    Connect.WrappedComponent = WrappedComponent;
	    Connect.contextTypes = {
	      store: _storeShape2["default"]
	    };
	    Connect.propTypes = {
	      store: _storeShape2["default"]
	    };

	    if (process.env.NODE_ENV !== 'production') {
	      Connect.prototype.componentWillUpdate = function componentWillUpdate() {
	        if (this.version === version) {
	          return;
	        }

	        // We are hot reloading!
	        this.version = version;
	        this.trySubscribe();
	        this.clearCache();
	      };
	    }

	    return (0, _hoistNonReactStatics2["default"])(Connect, WrappedComponent);
	  };
	}
	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 53 */
/***/ function(module, exports) {

	"use strict";

	exports.__esModule = true;
	exports["default"] = shallowEqual;
	function shallowEqual(objA, objB) {
	  if (objA === objB) {
	    return true;
	  }

	  var keysA = Object.keys(objA);
	  var keysB = Object.keys(objB);

	  if (keysA.length !== keysB.length) {
	    return false;
	  }

	  // Test for A's keys different from B.
	  var hasOwn = Object.prototype.hasOwnProperty;
	  for (var i = 0; i < keysA.length; i++) {
	    if (!hasOwn.call(objB, keysA[i]) || objA[keysA[i]] !== objB[keysA[i]]) {
	      return false;
	    }
	  }

	  return true;
	}

/***/ },
/* 54 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	exports.__esModule = true;
	exports["default"] = wrapActionCreators;

	var _redux = __webpack_require__(3);

	function wrapActionCreators(actionCreators) {
	  return function (dispatch) {
	    return (0, _redux.bindActionCreators)(actionCreators, dispatch);
	  };
	}

/***/ },
/* 55 */
/***/ function(module, exports) {

	/**
	 * Copyright 2015, Yahoo! Inc.
	 * Copyrights licensed under the New BSD License. See the accompanying LICENSE file for terms.
	 */
	'use strict';

	var REACT_STATICS = {
	    childContextTypes: true,
	    contextTypes: true,
	    defaultProps: true,
	    displayName: true,
	    getDefaultProps: true,
	    mixins: true,
	    propTypes: true,
	    type: true
	};

	var KNOWN_STATICS = {
	    name: true,
	    length: true,
	    prototype: true,
	    caller: true,
	    arguments: true,
	    arity: true
	};

	var isGetOwnPropertySymbolsAvailable = typeof Object.getOwnPropertySymbols === 'function';

	module.exports = function hoistNonReactStatics(targetComponent, sourceComponent, customStatics) {
	    if (typeof sourceComponent !== 'string') { // don't hoist over string (html) components
	        var keys = Object.getOwnPropertyNames(sourceComponent);

	        /* istanbul ignore else */
	        if (isGetOwnPropertySymbolsAvailable) {
	            keys = keys.concat(Object.getOwnPropertySymbols(sourceComponent));
	        }

	        for (var i = 0; i < keys.length; ++i) {
	            if (!REACT_STATICS[keys[i]] && !KNOWN_STATICS[keys[i]] && (!customStatics || !customStatics[keys[i]])) {
	                try {
	                    targetComponent[keys[i]] = sourceComponent[keys[i]];
	                } catch (error) {

	                }
	            }
	        }
	    }

	    return targetComponent;
	};


/***/ },
/* 56 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {/**
	 * Copyright 2013-2015, Facebook, Inc.
	 * All rights reserved.
	 *
	 * This source code is licensed under the BSD-style license found in the
	 * LICENSE file in the root directory of this source tree. An additional grant
	 * of patent rights can be found in the PATENTS file in the same directory.
	 */

	'use strict';

	/**
	 * Use invariant() to assert state which your program assumes to be true.
	 *
	 * Provide sprintf-style format (only %s is supported) and arguments
	 * to provide information about what broke and what you were
	 * expecting.
	 *
	 * The invariant message will be stripped in production, but the invariant
	 * will remain to ensure logic does not differ in production.
	 */

	var invariant = function(condition, format, a, b, c, d, e, f) {
	  if (process.env.NODE_ENV !== 'production') {
	    if (format === undefined) {
	      throw new Error('invariant requires an error message argument');
	    }
	  }

	  if (!condition) {
	    var error;
	    if (format === undefined) {
	      error = new Error(
	        'Minified exception occurred; use the non-minified dev environment ' +
	        'for the full error message and additional helpful warnings.'
	      );
	    } else {
	      var args = [a, b, c, d, e, f];
	      var argIndex = 0;
	      error = new Error(
	        format.replace(/%s/g, function() { return args[argIndex++]; })
	      );
	      error.name = 'Invariant Violation';
	    }

	    error.framesToPop = 1; // we don't care about invariant's own frame
	    throw error;
	  }
	};

	module.exports = invariant;

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 57 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.FormCtrl = exports.rootReducer = undefined;

	var _extends2 = __webpack_require__(58);

	var _extends3 = _interopRequireDefault(_extends2);

	var _ajaxService = __webpack_require__(96);

	var _ajaxService2 = _interopRequireDefault(_ajaxService);

	var _redux = __webpack_require__(3);

	var _is_js = __webpack_require__(132);

	var _is_js2 = _interopRequireDefault(_is_js);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	/* ============================================ reducers ============================================================ */
	var State = {
		cities: []
	};

	var Model = function Model() {
		var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : State;
		var action = arguments[1];

		switch (action.type) {
			case 'GET_CITIES':
				return (0, _extends3.default)({}, state, { cities: action.Sections });
			default:
				return state;
		}
	};

	var rootReducer = (0, _redux.combineReducers)({ Form: Model });

	exports.rootReducer = rootReducer;
	/* ================================================================================================================== */

	/* ===================================== actions ==================================================================== */

	var Ajax = new _ajaxService2.default({
		baseURL: '/rest/help'
	});

	var FormCtrl = {
		mapStateToProps: function mapStateToProps(state) {
			return state;
		},
		mapDispatchToProps: function mapDispatchToProps(dispatch) {
			return {
				getCity: function getCity() {
					var parent = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
				}
			};
		}
	};
	exports.FormCtrl = FormCtrl;
	/* ================================================================================================================== */

/***/ },
/* 58 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	exports.__esModule = true;

	var _assign = __webpack_require__(59);

	var _assign2 = _interopRequireDefault(_assign);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = _assign2.default || function (target) {
	  for (var i = 1; i < arguments.length; i++) {
	    var source = arguments[i];

	    for (var key in source) {
	      if (Object.prototype.hasOwnProperty.call(source, key)) {
	        target[key] = source[key];
	      }
	    }
	  }

	  return target;
	};

/***/ },
/* 59 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(60), __esModule: true };

/***/ },
/* 60 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(61);
	module.exports = __webpack_require__(64).Object.assign;

/***/ },
/* 61 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.3.1 Object.assign(target, source)
	var $export = __webpack_require__(62);

	$export($export.S + $export.F, 'Object', {assign: __webpack_require__(77)});

/***/ },
/* 62 */
/***/ function(module, exports, __webpack_require__) {

	var global    = __webpack_require__(63)
	  , core      = __webpack_require__(64)
	  , ctx       = __webpack_require__(65)
	  , hide      = __webpack_require__(67)
	  , PROTOTYPE = 'prototype';

	var $export = function(type, name, source){
	  var IS_FORCED = type & $export.F
	    , IS_GLOBAL = type & $export.G
	    , IS_STATIC = type & $export.S
	    , IS_PROTO  = type & $export.P
	    , IS_BIND   = type & $export.B
	    , IS_WRAP   = type & $export.W
	    , exports   = IS_GLOBAL ? core : core[name] || (core[name] = {})
	    , expProto  = exports[PROTOTYPE]
	    , target    = IS_GLOBAL ? global : IS_STATIC ? global[name] : (global[name] || {})[PROTOTYPE]
	    , key, own, out;
	  if(IS_GLOBAL)source = name;
	  for(key in source){
	    // contains in native
	    own = !IS_FORCED && target && target[key] !== undefined;
	    if(own && key in exports)continue;
	    // export native or passed
	    out = own ? target[key] : source[key];
	    // prevent global pollution for namespaces
	    exports[key] = IS_GLOBAL && typeof target[key] != 'function' ? source[key]
	    // bind timers to global for call from export context
	    : IS_BIND && own ? ctx(out, global)
	    // wrap global constructors for prevent change them in library
	    : IS_WRAP && target[key] == out ? (function(C){
	      var F = function(a, b, c){
	        if(this instanceof C){
	          switch(arguments.length){
	            case 0: return new C;
	            case 1: return new C(a);
	            case 2: return new C(a, b);
	          } return new C(a, b, c);
	        } return C.apply(this, arguments);
	      };
	      F[PROTOTYPE] = C[PROTOTYPE];
	      return F;
	    // make static versions for prototype methods
	    })(out) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
	    // export proto methods to core.%CONSTRUCTOR%.methods.%NAME%
	    if(IS_PROTO){
	      (exports.virtual || (exports.virtual = {}))[key] = out;
	      // export proto methods to core.%CONSTRUCTOR%.prototype.%NAME%
	      if(type & $export.R && expProto && !expProto[key])hide(expProto, key, out);
	    }
	  }
	};
	// type bitmap
	$export.F = 1;   // forced
	$export.G = 2;   // global
	$export.S = 4;   // static
	$export.P = 8;   // proto
	$export.B = 16;  // bind
	$export.W = 32;  // wrap
	$export.U = 64;  // safe
	$export.R = 128; // real proto method for `library` 
	module.exports = $export;

/***/ },
/* 63 */
/***/ function(module, exports) {

	// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
	var global = module.exports = typeof window != 'undefined' && window.Math == Math
	  ? window : typeof self != 'undefined' && self.Math == Math ? self : Function('return this')();
	if(typeof __g == 'number')__g = global; // eslint-disable-line no-undef

/***/ },
/* 64 */
/***/ function(module, exports) {

	var core = module.exports = {version: '2.4.0'};
	if(typeof __e == 'number')__e = core; // eslint-disable-line no-undef

/***/ },
/* 65 */
/***/ function(module, exports, __webpack_require__) {

	// optional / simple context binding
	var aFunction = __webpack_require__(66);
	module.exports = function(fn, that, length){
	  aFunction(fn);
	  if(that === undefined)return fn;
	  switch(length){
	    case 1: return function(a){
	      return fn.call(that, a);
	    };
	    case 2: return function(a, b){
	      return fn.call(that, a, b);
	    };
	    case 3: return function(a, b, c){
	      return fn.call(that, a, b, c);
	    };
	  }
	  return function(/* ...args */){
	    return fn.apply(that, arguments);
	  };
	};

/***/ },
/* 66 */
/***/ function(module, exports) {

	module.exports = function(it){
	  if(typeof it != 'function')throw TypeError(it + ' is not a function!');
	  return it;
	};

/***/ },
/* 67 */
/***/ function(module, exports, __webpack_require__) {

	var dP         = __webpack_require__(68)
	  , createDesc = __webpack_require__(76);
	module.exports = __webpack_require__(72) ? function(object, key, value){
	  return dP.f(object, key, createDesc(1, value));
	} : function(object, key, value){
	  object[key] = value;
	  return object;
	};

/***/ },
/* 68 */
/***/ function(module, exports, __webpack_require__) {

	var anObject       = __webpack_require__(69)
	  , IE8_DOM_DEFINE = __webpack_require__(71)
	  , toPrimitive    = __webpack_require__(75)
	  , dP             = Object.defineProperty;

	exports.f = __webpack_require__(72) ? Object.defineProperty : function defineProperty(O, P, Attributes){
	  anObject(O);
	  P = toPrimitive(P, true);
	  anObject(Attributes);
	  if(IE8_DOM_DEFINE)try {
	    return dP(O, P, Attributes);
	  } catch(e){ /* empty */ }
	  if('get' in Attributes || 'set' in Attributes)throw TypeError('Accessors not supported!');
	  if('value' in Attributes)O[P] = Attributes.value;
	  return O;
	};

/***/ },
/* 69 */
/***/ function(module, exports, __webpack_require__) {

	var isObject = __webpack_require__(70);
	module.exports = function(it){
	  if(!isObject(it))throw TypeError(it + ' is not an object!');
	  return it;
	};

/***/ },
/* 70 */
/***/ function(module, exports) {

	module.exports = function(it){
	  return typeof it === 'object' ? it !== null : typeof it === 'function';
	};

/***/ },
/* 71 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = !__webpack_require__(72) && !__webpack_require__(73)(function(){
	  return Object.defineProperty(__webpack_require__(74)('div'), 'a', {get: function(){ return 7; }}).a != 7;
	});

/***/ },
/* 72 */
/***/ function(module, exports, __webpack_require__) {

	// Thank's IE8 for his funny defineProperty
	module.exports = !__webpack_require__(73)(function(){
	  return Object.defineProperty({}, 'a', {get: function(){ return 7; }}).a != 7;
	});

/***/ },
/* 73 */
/***/ function(module, exports) {

	module.exports = function(exec){
	  try {
	    return !!exec();
	  } catch(e){
	    return true;
	  }
	};

/***/ },
/* 74 */
/***/ function(module, exports, __webpack_require__) {

	var isObject = __webpack_require__(70)
	  , document = __webpack_require__(63).document
	  // in old IE typeof document.createElement is 'object'
	  , is = isObject(document) && isObject(document.createElement);
	module.exports = function(it){
	  return is ? document.createElement(it) : {};
	};

/***/ },
/* 75 */
/***/ function(module, exports, __webpack_require__) {

	// 7.1.1 ToPrimitive(input [, PreferredType])
	var isObject = __webpack_require__(70);
	// instead of the ES6 spec version, we didn't implement @@toPrimitive case
	// and the second argument - flag - preferred type is a string
	module.exports = function(it, S){
	  if(!isObject(it))return it;
	  var fn, val;
	  if(S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it)))return val;
	  if(typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it)))return val;
	  if(!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it)))return val;
	  throw TypeError("Can't convert object to primitive value");
	};

/***/ },
/* 76 */
/***/ function(module, exports) {

	module.exports = function(bitmap, value){
	  return {
	    enumerable  : !(bitmap & 1),
	    configurable: !(bitmap & 2),
	    writable    : !(bitmap & 4),
	    value       : value
	  };
	};

/***/ },
/* 77 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	// 19.1.2.1 Object.assign(target, source, ...)
	var getKeys  = __webpack_require__(78)
	  , gOPS     = __webpack_require__(93)
	  , pIE      = __webpack_require__(94)
	  , toObject = __webpack_require__(95)
	  , IObject  = __webpack_require__(82)
	  , $assign  = Object.assign;

	// should work with symbols and should have deterministic property order (V8 bug)
	module.exports = !$assign || __webpack_require__(73)(function(){
	  var A = {}
	    , B = {}
	    , S = Symbol()
	    , K = 'abcdefghijklmnopqrst';
	  A[S] = 7;
	  K.split('').forEach(function(k){ B[k] = k; });
	  return $assign({}, A)[S] != 7 || Object.keys($assign({}, B)).join('') != K;
	}) ? function assign(target, source){ // eslint-disable-line no-unused-vars
	  var T     = toObject(target)
	    , aLen  = arguments.length
	    , index = 1
	    , getSymbols = gOPS.f
	    , isEnum     = pIE.f;
	  while(aLen > index){
	    var S      = IObject(arguments[index++])
	      , keys   = getSymbols ? getKeys(S).concat(getSymbols(S)) : getKeys(S)
	      , length = keys.length
	      , j      = 0
	      , key;
	    while(length > j)if(isEnum.call(S, key = keys[j++]))T[key] = S[key];
	  } return T;
	} : $assign;

/***/ },
/* 78 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.2.14 / 15.2.3.14 Object.keys(O)
	var $keys       = __webpack_require__(79)
	  , enumBugKeys = __webpack_require__(92);

	module.exports = Object.keys || function keys(O){
	  return $keys(O, enumBugKeys);
	};

/***/ },
/* 79 */
/***/ function(module, exports, __webpack_require__) {

	var has          = __webpack_require__(80)
	  , toIObject    = __webpack_require__(81)
	  , arrayIndexOf = __webpack_require__(85)(false)
	  , IE_PROTO     = __webpack_require__(89)('IE_PROTO');

	module.exports = function(object, names){
	  var O      = toIObject(object)
	    , i      = 0
	    , result = []
	    , key;
	  for(key in O)if(key != IE_PROTO)has(O, key) && result.push(key);
	  // Don't enum bug & hidden keys
	  while(names.length > i)if(has(O, key = names[i++])){
	    ~arrayIndexOf(result, key) || result.push(key);
	  }
	  return result;
	};

/***/ },
/* 80 */
/***/ function(module, exports) {

	var hasOwnProperty = {}.hasOwnProperty;
	module.exports = function(it, key){
	  return hasOwnProperty.call(it, key);
	};

/***/ },
/* 81 */
/***/ function(module, exports, __webpack_require__) {

	// to indexed object, toObject with fallback for non-array-like ES3 strings
	var IObject = __webpack_require__(82)
	  , defined = __webpack_require__(84);
	module.exports = function(it){
	  return IObject(defined(it));
	};

/***/ },
/* 82 */
/***/ function(module, exports, __webpack_require__) {

	// fallback for non-array-like ES3 and non-enumerable old V8 strings
	var cof = __webpack_require__(83);
	module.exports = Object('z').propertyIsEnumerable(0) ? Object : function(it){
	  return cof(it) == 'String' ? it.split('') : Object(it);
	};

/***/ },
/* 83 */
/***/ function(module, exports) {

	var toString = {}.toString;

	module.exports = function(it){
	  return toString.call(it).slice(8, -1);
	};

/***/ },
/* 84 */
/***/ function(module, exports) {

	// 7.2.1 RequireObjectCoercible(argument)
	module.exports = function(it){
	  if(it == undefined)throw TypeError("Can't call method on  " + it);
	  return it;
	};

/***/ },
/* 85 */
/***/ function(module, exports, __webpack_require__) {

	// false -> Array#indexOf
	// true  -> Array#includes
	var toIObject = __webpack_require__(81)
	  , toLength  = __webpack_require__(86)
	  , toIndex   = __webpack_require__(88);
	module.exports = function(IS_INCLUDES){
	  return function($this, el, fromIndex){
	    var O      = toIObject($this)
	      , length = toLength(O.length)
	      , index  = toIndex(fromIndex, length)
	      , value;
	    // Array#includes uses SameValueZero equality algorithm
	    if(IS_INCLUDES && el != el)while(length > index){
	      value = O[index++];
	      if(value != value)return true;
	    // Array#toIndex ignores holes, Array#includes - not
	    } else for(;length > index; index++)if(IS_INCLUDES || index in O){
	      if(O[index] === el)return IS_INCLUDES || index || 0;
	    } return !IS_INCLUDES && -1;
	  };
	};

/***/ },
/* 86 */
/***/ function(module, exports, __webpack_require__) {

	// 7.1.15 ToLength
	var toInteger = __webpack_require__(87)
	  , min       = Math.min;
	module.exports = function(it){
	  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
	};

/***/ },
/* 87 */
/***/ function(module, exports) {

	// 7.1.4 ToInteger
	var ceil  = Math.ceil
	  , floor = Math.floor;
	module.exports = function(it){
	  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
	};

/***/ },
/* 88 */
/***/ function(module, exports, __webpack_require__) {

	var toInteger = __webpack_require__(87)
	  , max       = Math.max
	  , min       = Math.min;
	module.exports = function(index, length){
	  index = toInteger(index);
	  return index < 0 ? max(index + length, 0) : min(index, length);
	};

/***/ },
/* 89 */
/***/ function(module, exports, __webpack_require__) {

	var shared = __webpack_require__(90)('keys')
	  , uid    = __webpack_require__(91);
	module.exports = function(key){
	  return shared[key] || (shared[key] = uid(key));
	};

/***/ },
/* 90 */
/***/ function(module, exports, __webpack_require__) {

	var global = __webpack_require__(63)
	  , SHARED = '__core-js_shared__'
	  , store  = global[SHARED] || (global[SHARED] = {});
	module.exports = function(key){
	  return store[key] || (store[key] = {});
	};

/***/ },
/* 91 */
/***/ function(module, exports) {

	var id = 0
	  , px = Math.random();
	module.exports = function(key){
	  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
	};

/***/ },
/* 92 */
/***/ function(module, exports) {

	// IE 8- don't enum bug keys
	module.exports = (
	  'constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf'
	).split(',');

/***/ },
/* 93 */
/***/ function(module, exports) {

	exports.f = Object.getOwnPropertySymbols;

/***/ },
/* 94 */
/***/ function(module, exports) {

	exports.f = {}.propertyIsEnumerable;

/***/ },
/* 95 */
/***/ function(module, exports, __webpack_require__) {

	// 7.1.13 ToObject(argument)
	var defined = __webpack_require__(84);
	module.exports = function(it){
	  return Object(defined(it));
	};

/***/ },
/* 96 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _assign = __webpack_require__(59);

	var _assign2 = _interopRequireDefault(_assign);

	var _classCallCheck2 = __webpack_require__(97);

	var _classCallCheck3 = _interopRequireDefault(_classCallCheck2);

	var _axios = __webpack_require__(98);

	var _axios2 = _interopRequireDefault(_axios);

	var _sweetalert = __webpack_require__(123);

	var _sweetalert2 = _interopRequireDefault(_sweetalert);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	_axios2.default.defaults.headers.post['Content-Type'] = 'application/json';

	var Ajax = function Ajax() {
		var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
		var option = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
		(0, _classCallCheck3.default)(this, Ajax);

		this.params = {
			headers: { 'Accept': 'application/json', 'ContentType': 'application/json' },
			baseURL: '/rest',
			transformResponse: [function (data) {
				if (typeof data == 'string') {
					data = JSON.parse(data);
				}

				var text = 'Внутренняя ошибка сервера';
				if (data.STATUS == 0) {
					if (data.ERRORS != null && data.ERRORS.length > 0) {
						text = data.ERRORS.join("\n");
						if (option.SHOW_SYSTEM_MSG && data.SYSTEM_ERR != null && data.SYSTEM_ERR.length > 0) {
							text = data.SYSTEM_ERR.join("\n");
						}
					}
					(0, _sweetalert2.default)({
						type: 'error',
						title: 'Ошибка!',
						text: text
					});
				}

				return data;
			}]
		};

		this.params = (0, _assign2.default)(this.params, params);

		return _axios2.default.create(this.params);
	};

	exports.default = Ajax;

/***/ },
/* 97 */
/***/ function(module, exports) {

	"use strict";

	exports.__esModule = true;

	exports.default = function (instance, Constructor) {
	  if (!(instance instanceof Constructor)) {
	    throw new TypeError("Cannot call a class as a function");
	  }
	};

/***/ },
/* 98 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(99);

/***/ },
/* 99 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);
	var bind = __webpack_require__(101);
	var Axios = __webpack_require__(102);
	var defaults = __webpack_require__(103);

	/**
	 * Create an instance of Axios
	 *
	 * @param {Object} defaultConfig The default config for the instance
	 * @return {Axios} A new instance of Axios
	 */
	function createInstance(defaultConfig) {
	  var context = new Axios(defaultConfig);
	  var instance = bind(Axios.prototype.request, context);

	  // Copy axios.prototype to instance
	  utils.extend(instance, Axios.prototype, context);

	  // Copy context to instance
	  utils.extend(instance, context);

	  return instance;
	}

	// Create the default instance to be exported
	var axios = createInstance(defaults);

	// Expose Axios class to allow class inheritance
	axios.Axios = Axios;

	// Factory for creating new instances
	axios.create = function create(instanceConfig) {
	  return createInstance(utils.merge(defaults, instanceConfig));
	};

	// Expose Cancel & CancelToken
	axios.Cancel = __webpack_require__(120);
	axios.CancelToken = __webpack_require__(121);
	axios.isCancel = __webpack_require__(117);

	// Expose all/spread
	axios.all = function all(promises) {
	  return Promise.all(promises);
	};
	axios.spread = __webpack_require__(122);

	module.exports = axios;

	// Allow use of default import syntax in TypeScript
	module.exports.default = axios;


/***/ },
/* 100 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var bind = __webpack_require__(101);

	/*global toString:true*/

	// utils is a library of generic helper functions non-specific to axios

	var toString = Object.prototype.toString;

	/**
	 * Determine if a value is an Array
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is an Array, otherwise false
	 */
	function isArray(val) {
	  return toString.call(val) === '[object Array]';
	}

	/**
	 * Determine if a value is an ArrayBuffer
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
	 */
	function isArrayBuffer(val) {
	  return toString.call(val) === '[object ArrayBuffer]';
	}

	/**
	 * Determine if a value is a FormData
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is an FormData, otherwise false
	 */
	function isFormData(val) {
	  return (typeof FormData !== 'undefined') && (val instanceof FormData);
	}

	/**
	 * Determine if a value is a view on an ArrayBuffer
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
	 */
	function isArrayBufferView(val) {
	  var result;
	  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
	    result = ArrayBuffer.isView(val);
	  } else {
	    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
	  }
	  return result;
	}

	/**
	 * Determine if a value is a String
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a String, otherwise false
	 */
	function isString(val) {
	  return typeof val === 'string';
	}

	/**
	 * Determine if a value is a Number
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a Number, otherwise false
	 */
	function isNumber(val) {
	  return typeof val === 'number';
	}

	/**
	 * Determine if a value is undefined
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if the value is undefined, otherwise false
	 */
	function isUndefined(val) {
	  return typeof val === 'undefined';
	}

	/**
	 * Determine if a value is an Object
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is an Object, otherwise false
	 */
	function isObject(val) {
	  return val !== null && typeof val === 'object';
	}

	/**
	 * Determine if a value is a Date
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a Date, otherwise false
	 */
	function isDate(val) {
	  return toString.call(val) === '[object Date]';
	}

	/**
	 * Determine if a value is a File
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a File, otherwise false
	 */
	function isFile(val) {
	  return toString.call(val) === '[object File]';
	}

	/**
	 * Determine if a value is a Blob
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a Blob, otherwise false
	 */
	function isBlob(val) {
	  return toString.call(val) === '[object Blob]';
	}

	/**
	 * Determine if a value is a Function
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a Function, otherwise false
	 */
	function isFunction(val) {
	  return toString.call(val) === '[object Function]';
	}

	/**
	 * Determine if a value is a Stream
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a Stream, otherwise false
	 */
	function isStream(val) {
	  return isObject(val) && isFunction(val.pipe);
	}

	/**
	 * Determine if a value is a URLSearchParams object
	 *
	 * @param {Object} val The value to test
	 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
	 */
	function isURLSearchParams(val) {
	  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
	}

	/**
	 * Trim excess whitespace off the beginning and end of a string
	 *
	 * @param {String} str The String to trim
	 * @returns {String} The String freed of excess whitespace
	 */
	function trim(str) {
	  return str.replace(/^\s*/, '').replace(/\s*$/, '');
	}

	/**
	 * Determine if we're running in a standard browser environment
	 *
	 * This allows axios to run in a web worker, and react-native.
	 * Both environments support XMLHttpRequest, but not fully standard globals.
	 *
	 * web workers:
	 *  typeof window -> undefined
	 *  typeof document -> undefined
	 *
	 * react-native:
	 *  typeof document.createElement -> undefined
	 */
	function isStandardBrowserEnv() {
	  return (
	    typeof window !== 'undefined' &&
	    typeof document !== 'undefined' &&
	    typeof document.createElement === 'function'
	  );
	}

	/**
	 * Iterate over an Array or an Object invoking a function for each item.
	 *
	 * If `obj` is an Array callback will be called passing
	 * the value, index, and complete array for each item.
	 *
	 * If 'obj' is an Object callback will be called passing
	 * the value, key, and complete object for each property.
	 *
	 * @param {Object|Array} obj The object to iterate
	 * @param {Function} fn The callback to invoke for each item
	 */
	function forEach(obj, fn) {
	  // Don't bother if no value provided
	  if (obj === null || typeof obj === 'undefined') {
	    return;
	  }

	  // Force an array if not already something iterable
	  if (typeof obj !== 'object' && !isArray(obj)) {
	    /*eslint no-param-reassign:0*/
	    obj = [obj];
	  }

	  if (isArray(obj)) {
	    // Iterate over array values
	    for (var i = 0, l = obj.length; i < l; i++) {
	      fn.call(null, obj[i], i, obj);
	    }
	  } else {
	    // Iterate over object keys
	    for (var key in obj) {
	      if (Object.prototype.hasOwnProperty.call(obj, key)) {
	        fn.call(null, obj[key], key, obj);
	      }
	    }
	  }
	}

	/**
	 * Accepts varargs expecting each argument to be an object, then
	 * immutably merges the properties of each object and returns result.
	 *
	 * When multiple objects contain the same key the later object in
	 * the arguments list will take precedence.
	 *
	 * Example:
	 *
	 * ```js
	 * var result = merge({foo: 123}, {foo: 456});
	 * console.log(result.foo); // outputs 456
	 * ```
	 *
	 * @param {Object} obj1 Object to merge
	 * @returns {Object} Result of all merge properties
	 */
	function merge(/* obj1, obj2, obj3, ... */) {
	  var result = {};
	  function assignValue(val, key) {
	    if (typeof result[key] === 'object' && typeof val === 'object') {
	      result[key] = merge(result[key], val);
	    } else {
	      result[key] = val;
	    }
	  }

	  for (var i = 0, l = arguments.length; i < l; i++) {
	    forEach(arguments[i], assignValue);
	  }
	  return result;
	}

	/**
	 * Extends object a by mutably adding to it the properties of object b.
	 *
	 * @param {Object} a The object to be extended
	 * @param {Object} b The object to copy properties from
	 * @param {Object} thisArg The object to bind function to
	 * @return {Object} The resulting value of object a
	 */
	function extend(a, b, thisArg) {
	  forEach(b, function assignValue(val, key) {
	    if (thisArg && typeof val === 'function') {
	      a[key] = bind(val, thisArg);
	    } else {
	      a[key] = val;
	    }
	  });
	  return a;
	}

	module.exports = {
	  isArray: isArray,
	  isArrayBuffer: isArrayBuffer,
	  isFormData: isFormData,
	  isArrayBufferView: isArrayBufferView,
	  isString: isString,
	  isNumber: isNumber,
	  isObject: isObject,
	  isUndefined: isUndefined,
	  isDate: isDate,
	  isFile: isFile,
	  isBlob: isBlob,
	  isFunction: isFunction,
	  isStream: isStream,
	  isURLSearchParams: isURLSearchParams,
	  isStandardBrowserEnv: isStandardBrowserEnv,
	  forEach: forEach,
	  merge: merge,
	  extend: extend,
	  trim: trim
	};


/***/ },
/* 101 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function bind(fn, thisArg) {
	  return function wrap() {
	    var args = new Array(arguments.length);
	    for (var i = 0; i < args.length; i++) {
	      args[i] = arguments[i];
	    }
	    return fn.apply(thisArg, args);
	  };
	};


/***/ },
/* 102 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var defaults = __webpack_require__(103);
	var utils = __webpack_require__(100);
	var InterceptorManager = __webpack_require__(114);
	var dispatchRequest = __webpack_require__(115);
	var isAbsoluteURL = __webpack_require__(118);
	var combineURLs = __webpack_require__(119);

	/**
	 * Create a new instance of Axios
	 *
	 * @param {Object} instanceConfig The default config for the instance
	 */
	function Axios(instanceConfig) {
	  this.defaults = instanceConfig;
	  this.interceptors = {
	    request: new InterceptorManager(),
	    response: new InterceptorManager()
	  };
	}

	/**
	 * Dispatch a request
	 *
	 * @param {Object} config The config specific for this request (merged with this.defaults)
	 */
	Axios.prototype.request = function request(config) {
	  /*eslint no-param-reassign:0*/
	  // Allow for axios('example/url'[, config]) a la fetch API
	  if (typeof config === 'string') {
	    config = utils.merge({
	      url: arguments[0]
	    }, arguments[1]);
	  }

	  config = utils.merge(defaults, this.defaults, { method: 'get' }, config);

	  // Support baseURL config
	  if (config.baseURL && !isAbsoluteURL(config.url)) {
	    config.url = combineURLs(config.baseURL, config.url);
	  }

	  // Hook up interceptors middleware
	  var chain = [dispatchRequest, undefined];
	  var promise = Promise.resolve(config);

	  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
	    chain.unshift(interceptor.fulfilled, interceptor.rejected);
	  });

	  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
	    chain.push(interceptor.fulfilled, interceptor.rejected);
	  });

	  while (chain.length) {
	    promise = promise.then(chain.shift(), chain.shift());
	  }

	  return promise;
	};

	// Provide aliases for supported request methods
	utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
	  /*eslint func-names:0*/
	  Axios.prototype[method] = function(url, config) {
	    return this.request(utils.merge(config || {}, {
	      method: method,
	      url: url
	    }));
	  };
	});

	utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
	  /*eslint func-names:0*/
	  Axios.prototype[method] = function(url, data, config) {
	    return this.request(utils.merge(config || {}, {
	      method: method,
	      url: url,
	      data: data
	    }));
	  };
	});

	module.exports = Axios;


/***/ },
/* 103 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {'use strict';

	var utils = __webpack_require__(100);
	var normalizeHeaderName = __webpack_require__(104);

	var PROTECTION_PREFIX = /^\)\]\}',?\n/;
	var DEFAULT_CONTENT_TYPE = {
	  'Content-Type': 'application/x-www-form-urlencoded'
	};

	function setContentTypeIfUnset(headers, value) {
	  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
	    headers['Content-Type'] = value;
	  }
	}

	function getDefaultAdapter() {
	  var adapter;
	  if (typeof XMLHttpRequest !== 'undefined') {
	    // For browsers use XHR adapter
	    adapter = __webpack_require__(105);
	  } else if (typeof process !== 'undefined') {
	    // For node use HTTP adapter
	    adapter = __webpack_require__(105);
	  }
	  return adapter;
	}

	var defaults = {
	  adapter: getDefaultAdapter(),

	  transformRequest: [function transformRequest(data, headers) {
	    normalizeHeaderName(headers, 'Content-Type');
	    if (utils.isFormData(data) ||
	      utils.isArrayBuffer(data) ||
	      utils.isStream(data) ||
	      utils.isFile(data) ||
	      utils.isBlob(data)
	    ) {
	      return data;
	    }
	    if (utils.isArrayBufferView(data)) {
	      return data.buffer;
	    }
	    if (utils.isURLSearchParams(data)) {
	      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
	      return data.toString();
	    }
	    if (utils.isObject(data)) {
	      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
	      return JSON.stringify(data);
	    }
	    return data;
	  }],

	  transformResponse: [function transformResponse(data) {
	    /*eslint no-param-reassign:0*/
	    if (typeof data === 'string') {
	      data = data.replace(PROTECTION_PREFIX, '');
	      try {
	        data = JSON.parse(data);
	      } catch (e) { /* Ignore */ }
	    }
	    return data;
	  }],

	  timeout: 0,

	  xsrfCookieName: 'XSRF-TOKEN',
	  xsrfHeaderName: 'X-XSRF-TOKEN',

	  maxContentLength: -1,

	  validateStatus: function validateStatus(status) {
	    return status >= 200 && status < 300;
	  }
	};

	defaults.headers = {
	  common: {
	    'Accept': 'application/json, text/plain, */*'
	  }
	};

	utils.forEach(['delete', 'get', 'head'], function forEachMehtodNoData(method) {
	  defaults.headers[method] = {};
	});

	utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
	  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
	});

	module.exports = defaults;

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 104 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	module.exports = function normalizeHeaderName(headers, normalizedName) {
	  utils.forEach(headers, function processHeader(value, name) {
	    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
	      headers[normalizedName] = value;
	      delete headers[name];
	    }
	  });
	};


/***/ },
/* 105 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process) {'use strict';

	var utils = __webpack_require__(100);
	var settle = __webpack_require__(106);
	var buildURL = __webpack_require__(109);
	var parseHeaders = __webpack_require__(110);
	var isURLSameOrigin = __webpack_require__(111);
	var createError = __webpack_require__(107);
	var btoa = (typeof window !== 'undefined' && window.btoa && window.btoa.bind(window)) || __webpack_require__(112);

	module.exports = function xhrAdapter(config) {
	  return new Promise(function dispatchXhrRequest(resolve, reject) {
	    var requestData = config.data;
	    var requestHeaders = config.headers;

	    if (utils.isFormData(requestData)) {
	      delete requestHeaders['Content-Type']; // Let the browser set it
	    }

	    var request = new XMLHttpRequest();
	    var loadEvent = 'onreadystatechange';
	    var xDomain = false;

	    // For IE 8/9 CORS support
	    // Only supports POST and GET calls and doesn't returns the response headers.
	    // DON'T do this for testing b/c XMLHttpRequest is mocked, not XDomainRequest.
	    if (process.env.NODE_ENV !== 'test' &&
	        typeof window !== 'undefined' &&
	        window.XDomainRequest && !('withCredentials' in request) &&
	        !isURLSameOrigin(config.url)) {
	      request = new window.XDomainRequest();
	      loadEvent = 'onload';
	      xDomain = true;
	      request.onprogress = function handleProgress() {};
	      request.ontimeout = function handleTimeout() {};
	    }

	    // HTTP basic authentication
	    if (config.auth) {
	      var username = config.auth.username || '';
	      var password = config.auth.password || '';
	      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
	    }

	    request.open(config.method.toUpperCase(), buildURL(config.url, config.params, config.paramsSerializer), true);

	    // Set the request timeout in MS
	    request.timeout = config.timeout;

	    // Listen for ready state
	    request[loadEvent] = function handleLoad() {
	      if (!request || (request.readyState !== 4 && !xDomain)) {
	        return;
	      }

	      // The request errored out and we didn't get a response, this will be
	      // handled by onerror instead
	      // With one exception: request that using file: protocol, most browsers
	      // will return status as 0 even though it's a successful request
	      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
	        return;
	      }

	      // Prepare the response
	      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
	      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
	      var response = {
	        data: responseData,
	        // IE sends 1223 instead of 204 (https://github.com/mzabriskie/axios/issues/201)
	        status: request.status === 1223 ? 204 : request.status,
	        statusText: request.status === 1223 ? 'No Content' : request.statusText,
	        headers: responseHeaders,
	        config: config,
	        request: request
	      };

	      settle(resolve, reject, response);

	      // Clean up request
	      request = null;
	    };

	    // Handle low level network errors
	    request.onerror = function handleError() {
	      // Real errors are hidden from us by the browser
	      // onerror should only fire if it's a network error
	      reject(createError('Network Error', config));

	      // Clean up request
	      request = null;
	    };

	    // Handle timeout
	    request.ontimeout = function handleTimeout() {
	      reject(createError('timeout of ' + config.timeout + 'ms exceeded', config, 'ECONNABORTED'));

	      // Clean up request
	      request = null;
	    };

	    // Add xsrf header
	    // This is only done if running in a standard browser environment.
	    // Specifically not if we're in a web worker, or react-native.
	    if (utils.isStandardBrowserEnv()) {
	      var cookies = __webpack_require__(113);

	      // Add xsrf header
	      var xsrfValue = (config.withCredentials || isURLSameOrigin(config.url)) && config.xsrfCookieName ?
	          cookies.read(config.xsrfCookieName) :
	          undefined;

	      if (xsrfValue) {
	        requestHeaders[config.xsrfHeaderName] = xsrfValue;
	      }
	    }

	    // Add headers to the request
	    if ('setRequestHeader' in request) {
	      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
	        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
	          // Remove Content-Type if data is undefined
	          delete requestHeaders[key];
	        } else {
	          // Otherwise add header to the request
	          request.setRequestHeader(key, val);
	        }
	      });
	    }

	    // Add withCredentials to request if needed
	    if (config.withCredentials) {
	      request.withCredentials = true;
	    }

	    // Add responseType to request if needed
	    if (config.responseType) {
	      try {
	        request.responseType = config.responseType;
	      } catch (e) {
	        if (request.responseType !== 'json') {
	          throw e;
	        }
	      }
	    }

	    // Handle progress if needed
	    if (typeof config.onDownloadProgress === 'function') {
	      request.addEventListener('progress', config.onDownloadProgress);
	    }

	    // Not all browsers support upload events
	    if (typeof config.onUploadProgress === 'function' && request.upload) {
	      request.upload.addEventListener('progress', config.onUploadProgress);
	    }

	    if (config.cancelToken) {
	      // Handle cancellation
	      config.cancelToken.promise.then(function onCanceled(cancel) {
	        if (!request) {
	          return;
	        }

	        request.abort();
	        reject(cancel);
	        // Clean up request
	        request = null;
	      });
	    }

	    if (requestData === undefined) {
	      requestData = null;
	    }

	    // Send the request
	    request.send(requestData);
	  });
	};

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2)))

/***/ },
/* 106 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var createError = __webpack_require__(107);

	/**
	 * Resolve or reject a Promise based on response status.
	 *
	 * @param {Function} resolve A function that resolves the promise.
	 * @param {Function} reject A function that rejects the promise.
	 * @param {object} response The response.
	 */
	module.exports = function settle(resolve, reject, response) {
	  var validateStatus = response.config.validateStatus;
	  // Note: status is not exposed by XDomainRequest
	  if (!response.status || !validateStatus || validateStatus(response.status)) {
	    resolve(response);
	  } else {
	    reject(createError(
	      'Request failed with status code ' + response.status,
	      response.config,
	      null,
	      response
	    ));
	  }
	};


/***/ },
/* 107 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var enhanceError = __webpack_require__(108);

	/**
	 * Create an Error with the specified message, config, error code, and response.
	 *
	 * @param {string} message The error message.
	 * @param {Object} config The config.
	 * @param {string} [code] The error code (for example, 'ECONNABORTED').
	 @ @param {Object} [response] The response.
	 * @returns {Error} The created error.
	 */
	module.exports = function createError(message, config, code, response) {
	  var error = new Error(message);
	  return enhanceError(error, config, code, response);
	};


/***/ },
/* 108 */
/***/ function(module, exports) {

	'use strict';

	/**
	 * Update an Error with the specified config, error code, and response.
	 *
	 * @param {Error} error The error to update.
	 * @param {Object} config The config.
	 * @param {string} [code] The error code (for example, 'ECONNABORTED').
	 @ @param {Object} [response] The response.
	 * @returns {Error} The error.
	 */
	module.exports = function enhanceError(error, config, code, response) {
	  error.config = config;
	  if (code) {
	    error.code = code;
	  }
	  error.response = response;
	  return error;
	};


/***/ },
/* 109 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	function encode(val) {
	  return encodeURIComponent(val).
	    replace(/%40/gi, '@').
	    replace(/%3A/gi, ':').
	    replace(/%24/g, '$').
	    replace(/%2C/gi, ',').
	    replace(/%20/g, '+').
	    replace(/%5B/gi, '[').
	    replace(/%5D/gi, ']');
	}

	/**
	 * Build a URL by appending params to the end
	 *
	 * @param {string} url The base of the url (e.g., http://www.google.com)
	 * @param {object} [params] The params to be appended
	 * @returns {string} The formatted url
	 */
	module.exports = function buildURL(url, params, paramsSerializer) {
	  /*eslint no-param-reassign:0*/
	  if (!params) {
	    return url;
	  }

	  var serializedParams;
	  if (paramsSerializer) {
	    serializedParams = paramsSerializer(params);
	  } else if (utils.isURLSearchParams(params)) {
	    serializedParams = params.toString();
	  } else {
	    var parts = [];

	    utils.forEach(params, function serialize(val, key) {
	      if (val === null || typeof val === 'undefined') {
	        return;
	      }

	      if (utils.isArray(val)) {
	        key = key + '[]';
	      }

	      if (!utils.isArray(val)) {
	        val = [val];
	      }

	      utils.forEach(val, function parseValue(v) {
	        if (utils.isDate(v)) {
	          v = v.toISOString();
	        } else if (utils.isObject(v)) {
	          v = JSON.stringify(v);
	        }
	        parts.push(encode(key) + '=' + encode(v));
	      });
	    });

	    serializedParams = parts.join('&');
	  }

	  if (serializedParams) {
	    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
	  }

	  return url;
	};


/***/ },
/* 110 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	/**
	 * Parse headers into an object
	 *
	 * ```
	 * Date: Wed, 27 Aug 2014 08:58:49 GMT
	 * Content-Type: application/json
	 * Connection: keep-alive
	 * Transfer-Encoding: chunked
	 * ```
	 *
	 * @param {String} headers Headers needing to be parsed
	 * @returns {Object} Headers parsed into an object
	 */
	module.exports = function parseHeaders(headers) {
	  var parsed = {};
	  var key;
	  var val;
	  var i;

	  if (!headers) { return parsed; }

	  utils.forEach(headers.split('\n'), function parser(line) {
	    i = line.indexOf(':');
	    key = utils.trim(line.substr(0, i)).toLowerCase();
	    val = utils.trim(line.substr(i + 1));

	    if (key) {
	      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
	    }
	  });

	  return parsed;
	};


/***/ },
/* 111 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	module.exports = (
	  utils.isStandardBrowserEnv() ?

	  // Standard browser envs have full support of the APIs needed to test
	  // whether the request URL is of the same origin as current location.
	  (function standardBrowserEnv() {
	    var msie = /(msie|trident)/i.test(navigator.userAgent);
	    var urlParsingNode = document.createElement('a');
	    var originURL;

	    /**
	    * Parse a URL to discover it's components
	    *
	    * @param {String} url The URL to be parsed
	    * @returns {Object}
	    */
	    function resolveURL(url) {
	      var href = url;

	      if (msie) {
	        // IE needs attribute set twice to normalize properties
	        urlParsingNode.setAttribute('href', href);
	        href = urlParsingNode.href;
	      }

	      urlParsingNode.setAttribute('href', href);

	      // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
	      return {
	        href: urlParsingNode.href,
	        protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
	        host: urlParsingNode.host,
	        search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
	        hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
	        hostname: urlParsingNode.hostname,
	        port: urlParsingNode.port,
	        pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
	                  urlParsingNode.pathname :
	                  '/' + urlParsingNode.pathname
	      };
	    }

	    originURL = resolveURL(window.location.href);

	    /**
	    * Determine if a URL shares the same origin as the current location
	    *
	    * @param {String} requestURL The URL to test
	    * @returns {boolean} True if URL shares the same origin, otherwise false
	    */
	    return function isURLSameOrigin(requestURL) {
	      var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
	      return (parsed.protocol === originURL.protocol &&
	            parsed.host === originURL.host);
	    };
	  })() :

	  // Non standard browser envs (web workers, react-native) lack needed support.
	  (function nonStandardBrowserEnv() {
	    return function isURLSameOrigin() {
	      return true;
	    };
	  })()
	);


/***/ },
/* 112 */
/***/ function(module, exports) {

	'use strict';

	// btoa polyfill for IE<10 courtesy https://github.com/davidchambers/Base64.js

	var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

	function E() {
	  this.message = 'String contains an invalid character';
	}
	E.prototype = new Error;
	E.prototype.code = 5;
	E.prototype.name = 'InvalidCharacterError';

	function btoa(input) {
	  var str = String(input);
	  var output = '';
	  for (
	    // initialize result and counter
	    var block, charCode, idx = 0, map = chars;
	    // if the next str index does not exist:
	    //   change the mapping table to "="
	    //   check if d has no fractional digits
	    str.charAt(idx | 0) || (map = '=', idx % 1);
	    // "8 - idx % 1 * 8" generates the sequence 2, 4, 6, 8
	    output += map.charAt(63 & block >> 8 - idx % 1 * 8)
	  ) {
	    charCode = str.charCodeAt(idx += 3 / 4);
	    if (charCode > 0xFF) {
	      throw new E();
	    }
	    block = block << 8 | charCode;
	  }
	  return output;
	}

	module.exports = btoa;


/***/ },
/* 113 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	module.exports = (
	  utils.isStandardBrowserEnv() ?

	  // Standard browser envs support document.cookie
	  (function standardBrowserEnv() {
	    return {
	      write: function write(name, value, expires, path, domain, secure) {
	        var cookie = [];
	        cookie.push(name + '=' + encodeURIComponent(value));

	        if (utils.isNumber(expires)) {
	          cookie.push('expires=' + new Date(expires).toGMTString());
	        }

	        if (utils.isString(path)) {
	          cookie.push('path=' + path);
	        }

	        if (utils.isString(domain)) {
	          cookie.push('domain=' + domain);
	        }

	        if (secure === true) {
	          cookie.push('secure');
	        }

	        document.cookie = cookie.join('; ');
	      },

	      read: function read(name) {
	        var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
	        return (match ? decodeURIComponent(match[3]) : null);
	      },

	      remove: function remove(name) {
	        this.write(name, '', Date.now() - 86400000);
	      }
	    };
	  })() :

	  // Non standard browser env (web workers, react-native) lack needed support.
	  (function nonStandardBrowserEnv() {
	    return {
	      write: function write() {},
	      read: function read() { return null; },
	      remove: function remove() {}
	    };
	  })()
	);


/***/ },
/* 114 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	function InterceptorManager() {
	  this.handlers = [];
	}

	/**
	 * Add a new interceptor to the stack
	 *
	 * @param {Function} fulfilled The function to handle `then` for a `Promise`
	 * @param {Function} rejected The function to handle `reject` for a `Promise`
	 *
	 * @return {Number} An ID used to remove interceptor later
	 */
	InterceptorManager.prototype.use = function use(fulfilled, rejected) {
	  this.handlers.push({
	    fulfilled: fulfilled,
	    rejected: rejected
	  });
	  return this.handlers.length - 1;
	};

	/**
	 * Remove an interceptor from the stack
	 *
	 * @param {Number} id The ID that was returned by `use`
	 */
	InterceptorManager.prototype.eject = function eject(id) {
	  if (this.handlers[id]) {
	    this.handlers[id] = null;
	  }
	};

	/**
	 * Iterate over all the registered interceptors
	 *
	 * This method is particularly useful for skipping over any
	 * interceptors that may have become `null` calling `eject`.
	 *
	 * @param {Function} fn The function to call for each interceptor
	 */
	InterceptorManager.prototype.forEach = function forEach(fn) {
	  utils.forEach(this.handlers, function forEachHandler(h) {
	    if (h !== null) {
	      fn(h);
	    }
	  });
	};

	module.exports = InterceptorManager;


/***/ },
/* 115 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);
	var transformData = __webpack_require__(116);
	var isCancel = __webpack_require__(117);
	var defaults = __webpack_require__(103);

	/**
	 * Throws a `Cancel` if cancellation has been requested.
	 */
	function throwIfCancellationRequested(config) {
	  if (config.cancelToken) {
	    config.cancelToken.throwIfRequested();
	  }
	}

	/**
	 * Dispatch a request to the server using the configured adapter.
	 *
	 * @param {object} config The config that is to be used for the request
	 * @returns {Promise} The Promise to be fulfilled
	 */
	module.exports = function dispatchRequest(config) {
	  throwIfCancellationRequested(config);

	  // Ensure headers exist
	  config.headers = config.headers || {};

	  // Transform request data
	  config.data = transformData(
	    config.data,
	    config.headers,
	    config.transformRequest
	  );

	  // Flatten headers
	  config.headers = utils.merge(
	    config.headers.common || {},
	    config.headers[config.method] || {},
	    config.headers || {}
	  );

	  utils.forEach(
	    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
	    function cleanHeaderConfig(method) {
	      delete config.headers[method];
	    }
	  );

	  var adapter = config.adapter || defaults.adapter;

	  return adapter(config).then(function onAdapterResolution(response) {
	    throwIfCancellationRequested(config);

	    // Transform response data
	    response.data = transformData(
	      response.data,
	      response.headers,
	      config.transformResponse
	    );

	    return response;
	  }, function onAdapterRejection(reason) {
	    if (!isCancel(reason)) {
	      throwIfCancellationRequested(config);

	      // Transform response data
	      if (reason && reason.response) {
	        reason.response.data = transformData(
	          reason.response.data,
	          reason.response.headers,
	          config.transformResponse
	        );
	      }
	    }

	    return Promise.reject(reason);
	  });
	};


/***/ },
/* 116 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var utils = __webpack_require__(100);

	/**
	 * Transform the data for a request or a response
	 *
	 * @param {Object|String} data The data to be transformed
	 * @param {Array} headers The headers for the request or response
	 * @param {Array|Function} fns A single function or Array of functions
	 * @returns {*} The resulting transformed data
	 */
	module.exports = function transformData(data, headers, fns) {
	  /*eslint no-param-reassign:0*/
	  utils.forEach(fns, function transform(fn) {
	    data = fn(data, headers);
	  });

	  return data;
	};


/***/ },
/* 117 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function isCancel(value) {
	  return !!(value && value.__CANCEL__);
	};


/***/ },
/* 118 */
/***/ function(module, exports) {

	'use strict';

	/**
	 * Determines whether the specified URL is absolute
	 *
	 * @param {string} url The URL to test
	 * @returns {boolean} True if the specified URL is absolute, otherwise false
	 */
	module.exports = function isAbsoluteURL(url) {
	  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
	  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
	  // by any combination of letters, digits, plus, period, or hyphen.
	  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
	};


/***/ },
/* 119 */
/***/ function(module, exports) {

	'use strict';

	/**
	 * Creates a new URL by combining the specified URLs
	 *
	 * @param {string} baseURL The base URL
	 * @param {string} relativeURL The relative URL
	 * @returns {string} The combined URL
	 */
	module.exports = function combineURLs(baseURL, relativeURL) {
	  return baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '');
	};


/***/ },
/* 120 */
/***/ function(module, exports) {

	'use strict';

	/**
	 * A `Cancel` is an object that is thrown when an operation is canceled.
	 *
	 * @class
	 * @param {string=} message The message.
	 */
	function Cancel(message) {
	  this.message = message;
	}

	Cancel.prototype.toString = function toString() {
	  return 'Cancel' + (this.message ? ': ' + this.message : '');
	};

	Cancel.prototype.__CANCEL__ = true;

	module.exports = Cancel;


/***/ },
/* 121 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var Cancel = __webpack_require__(120);

	/**
	 * A `CancelToken` is an object that can be used to request cancellation of an operation.
	 *
	 * @class
	 * @param {Function} executor The executor function.
	 */
	function CancelToken(executor) {
	  if (typeof executor !== 'function') {
	    throw new TypeError('executor must be a function.');
	  }

	  var resolvePromise;
	  this.promise = new Promise(function promiseExecutor(resolve) {
	    resolvePromise = resolve;
	  });

	  var token = this;
	  executor(function cancel(message) {
	    if (token.reason) {
	      // Cancellation has already been requested
	      return;
	    }

	    token.reason = new Cancel(message);
	    resolvePromise(token.reason);
	  });
	}

	/**
	 * Throws a `Cancel` if cancellation has been requested.
	 */
	CancelToken.prototype.throwIfRequested = function throwIfRequested() {
	  if (this.reason) {
	    throw this.reason;
	  }
	};

	/**
	 * Returns an object that contains a new `CancelToken` and a function that, when called,
	 * cancels the `CancelToken`.
	 */
	CancelToken.source = function source() {
	  var cancel;
	  var token = new CancelToken(function executor(c) {
	    cancel = c;
	  });
	  return {
	    token: token,
	    cancel: cancel
	  };
	};

	module.exports = CancelToken;


/***/ },
/* 122 */
/***/ function(module, exports) {

	'use strict';

	/**
	 * Syntactic sugar for invoking a function and expanding an array for arguments.
	 *
	 * Common use case would be to use `Function.prototype.apply`.
	 *
	 *  ```js
	 *  function f(x, y, z) {}
	 *  var args = [1, 2, 3];
	 *  f.apply(null, args);
	 *  ```
	 *
	 * With `spread` this example can be re-written.
	 *
	 *  ```js
	 *  spread(function(x, y, z) {})([1, 2, 3]);
	 *  ```
	 *
	 * @param {Function} callback
	 * @returns {Function}
	 */
	module.exports = function spread(callback) {
	  return function wrap(arr) {
	    return callback.apply(null, arr);
	  };
	};


/***/ },
/* 123 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _interopRequireWildcard = function (obj) { return obj && obj.__esModule ? obj : { 'default': obj }; };

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	// SweetAlert
	// 2014-2015 (c) - Tristan Edwards
	// github.com/t4t5/sweetalert

	/*
	 * jQuery-like functions for manipulating the DOM
	 */

	var _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation = __webpack_require__(124);

	/*
	 * Handy utilities
	 */

	var _extend$hexToRgb$isIE8$logStr$colorLuminance = __webpack_require__(125);

	/*
	 *  Handle sweetAlert's DOM elements
	 */

	var _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition = __webpack_require__(126);

	// Handle button events and keyboard events

	var _handleButton$handleConfirm$handleCancel = __webpack_require__(129);

	var _handleKeyDown = __webpack_require__(130);

	var _handleKeyDown2 = _interopRequireWildcard(_handleKeyDown);

	// Default values

	var _defaultParams = __webpack_require__(127);

	var _defaultParams2 = _interopRequireWildcard(_defaultParams);

	var _setParameters = __webpack_require__(131);

	var _setParameters2 = _interopRequireWildcard(_setParameters);

	/*
	 * Remember state in cases where opening and handling a modal will fiddle with it.
	 * (We also use window.previousActiveElement as a global variable)
	 */
	var previousWindowKeyDown;
	var lastFocusedButton;

	/*
	 * Global sweetAlert function
	 * (this is what the user calls)
	 */
	var sweetAlert, swal;

	exports['default'] = sweetAlert = swal = function () {
	  var customizations = arguments[0];

	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.addClass(document.body, 'stop-scrolling');
	  _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.resetInput();

	  /*
	   * Use argument if defined or default value from params object otherwise.
	   * Supports the case where a default value is boolean true and should be
	   * overridden by a corresponding explicit argument which is boolean false.
	   */
	  function argumentOrDefault(key) {
	    var args = customizations;
	    return args[key] === undefined ? _defaultParams2['default'][key] : args[key];
	  }

	  if (customizations === undefined) {
	    _extend$hexToRgb$isIE8$logStr$colorLuminance.logStr('SweetAlert expects at least 1 attribute!');
	    return false;
	  }

	  var params = _extend$hexToRgb$isIE8$logStr$colorLuminance.extend({}, _defaultParams2['default']);

	  switch (typeof customizations) {

	    // Ex: swal("Hello", "Just testing", "info");
	    case 'string':
	      params.title = customizations;
	      params.text = arguments[1] || '';
	      params.type = arguments[2] || '';
	      break;

	    // Ex: swal({ title:"Hello", text: "Just testing", type: "info" });
	    case 'object':
	      if (customizations.title === undefined) {
	        _extend$hexToRgb$isIE8$logStr$colorLuminance.logStr('Missing "title" argument!');
	        return false;
	      }

	      params.title = customizations.title;

	      for (var customName in _defaultParams2['default']) {
	        params[customName] = argumentOrDefault(customName);
	      }

	      // Show "Confirm" instead of "OK" if cancel button is visible
	      params.confirmButtonText = params.showCancelButton ? 'Confirm' : _defaultParams2['default'].confirmButtonText;
	      params.confirmButtonText = argumentOrDefault('confirmButtonText');

	      // Callback function when clicking on "OK"/"Cancel"
	      params.doneFunction = arguments[1] || null;

	      break;

	    default:
	      _extend$hexToRgb$isIE8$logStr$colorLuminance.logStr('Unexpected type of argument! Expected "string" or "object", got ' + typeof customizations);
	      return false;

	  }

	  _setParameters2['default'](params);
	  _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.fixVerticalPosition();
	  _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.openModal(arguments[1]);

	  // Modal interactions
	  var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getModal();

	  /*
	   * Make sure all modal buttons respond to all events
	   */
	  var $buttons = modal.querySelectorAll('button');
	  var buttonEvents = ['onclick', 'onmouseover', 'onmouseout', 'onmousedown', 'onmouseup', 'onfocus'];
	  var onButtonEvent = function onButtonEvent(e) {
	    return _handleButton$handleConfirm$handleCancel.handleButton(e, params, modal);
	  };

	  for (var btnIndex = 0; btnIndex < $buttons.length; btnIndex++) {
	    for (var evtIndex = 0; evtIndex < buttonEvents.length; evtIndex++) {
	      var btnEvt = buttonEvents[evtIndex];
	      $buttons[btnIndex][btnEvt] = onButtonEvent;
	    }
	  }

	  // Clicking outside the modal dismisses it (if allowed by user)
	  _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getOverlay().onclick = onButtonEvent;

	  previousWindowKeyDown = window.onkeydown;

	  var onKeyEvent = function onKeyEvent(e) {
	    return _handleKeyDown2['default'](e, params, modal);
	  };
	  window.onkeydown = onKeyEvent;

	  window.onfocus = function () {
	    // When the user has focused away and focused back from the whole window.
	    setTimeout(function () {
	      // Put in a timeout to jump out of the event sequence.
	      // Calling focus() in the event sequence confuses things.
	      if (lastFocusedButton !== undefined) {
	        lastFocusedButton.focus();
	        lastFocusedButton = undefined;
	      }
	    }, 0);
	  };

	  // Show alert with enabled buttons always
	  swal.enableButtons();
	};

	/*
	 * Set default params for each popup
	 * @param {Object} userParams
	 */
	sweetAlert.setDefaults = swal.setDefaults = function (userParams) {
	  if (!userParams) {
	    throw new Error('userParams is required');
	  }
	  if (typeof userParams !== 'object') {
	    throw new Error('userParams has to be a object');
	  }

	  _extend$hexToRgb$isIE8$logStr$colorLuminance.extend(_defaultParams2['default'], userParams);
	};

	/*
	 * Animation when closing modal
	 */
	sweetAlert.close = swal.close = function () {
	  var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getModal();

	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.fadeOut(_sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getOverlay(), 5);
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.fadeOut(modal, 5);
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass(modal, 'showSweetAlert');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.addClass(modal, 'hideSweetAlert');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass(modal, 'visible');

	  /*
	   * Reset icon animations
	   */
	  var $successIcon = modal.querySelector('.sa-icon.sa-success');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($successIcon, 'animate');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($successIcon.querySelector('.sa-tip'), 'animateSuccessTip');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($successIcon.querySelector('.sa-long'), 'animateSuccessLong');

	  var $errorIcon = modal.querySelector('.sa-icon.sa-error');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($errorIcon, 'animateErrorIcon');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($errorIcon.querySelector('.sa-x-mark'), 'animateXMark');

	  var $warningIcon = modal.querySelector('.sa-icon.sa-warning');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($warningIcon, 'pulseWarning');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($warningIcon.querySelector('.sa-body'), 'pulseWarningIns');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($warningIcon.querySelector('.sa-dot'), 'pulseWarningIns');

	  // Reset custom class (delay so that UI changes aren't visible)
	  setTimeout(function () {
	    var customClass = modal.getAttribute('data-custom-class');
	    _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass(modal, customClass);
	  }, 300);

	  // Make page scrollable again
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass(document.body, 'stop-scrolling');

	  // Reset the page to its previous state
	  window.onkeydown = previousWindowKeyDown;
	  if (window.previousActiveElement) {
	    window.previousActiveElement.focus();
	  }
	  lastFocusedButton = undefined;
	  clearTimeout(modal.timeout);

	  return true;
	};

	/*
	 * Validation of the input field is done by user
	 * If something is wrong => call showInputError with errorMessage
	 */
	sweetAlert.showInputError = swal.showInputError = function (errorMessage) {
	  var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getModal();

	  var $errorIcon = modal.querySelector('.sa-input-error');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.addClass($errorIcon, 'show');

	  var $errorContainer = modal.querySelector('.sa-error-container');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.addClass($errorContainer, 'show');

	  $errorContainer.querySelector('p').innerHTML = errorMessage;

	  setTimeout(function () {
	    sweetAlert.enableButtons();
	  }, 1);

	  modal.querySelector('input').focus();
	};

	/*
	 * Reset input error DOM elements
	 */
	sweetAlert.resetInputError = swal.resetInputError = function (event) {
	  // If press enter => ignore
	  if (event && event.keyCode === 13) {
	    return false;
	  }

	  var $modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getModal();

	  var $errorIcon = $modal.querySelector('.sa-input-error');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($errorIcon, 'show');

	  var $errorContainer = $modal.querySelector('.sa-error-container');
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation.removeClass($errorContainer, 'show');
	};

	/*
	 * Disable confirm and cancel buttons
	 */
	sweetAlert.disableButtons = swal.disableButtons = function (event) {
	  var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getModal();
	  var $confirmButton = modal.querySelector('button.confirm');
	  var $cancelButton = modal.querySelector('button.cancel');
	  $confirmButton.disabled = true;
	  $cancelButton.disabled = true;
	};

	/*
	 * Enable confirm and cancel buttons
	 */
	sweetAlert.enableButtons = swal.enableButtons = function (event) {
	  var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition.getModal();
	  var $confirmButton = modal.querySelector('button.confirm');
	  var $cancelButton = modal.querySelector('button.cancel');
	  $confirmButton.disabled = false;
	  $cancelButton.disabled = false;
	};

	if (typeof window !== 'undefined') {
	  // The 'handle-click' module requires
	  // that 'sweetAlert' was set as global.
	  window.sweetAlert = window.swal = sweetAlert;
	} else {
	  _extend$hexToRgb$isIE8$logStr$colorLuminance.logStr('SweetAlert is a frontend module!');
	}
	module.exports = exports['default'];

/***/ },
/* 124 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	var hasClass = function hasClass(elem, className) {
	  return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
	};

	var addClass = function addClass(elem, className) {
	  if (!hasClass(elem, className)) {
	    elem.className += ' ' + className;
	  }
	};

	var removeClass = function removeClass(elem, className) {
	  var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
	  if (hasClass(elem, className)) {
	    while (newClass.indexOf(' ' + className + ' ') >= 0) {
	      newClass = newClass.replace(' ' + className + ' ', ' ');
	    }
	    elem.className = newClass.replace(/^\s+|\s+$/g, '');
	  }
	};

	var escapeHtml = function escapeHtml(str) {
	  var div = document.createElement('div');
	  div.appendChild(document.createTextNode(str));
	  return div.innerHTML;
	};

	var _show = function _show(elem) {
	  elem.style.opacity = '';
	  elem.style.display = 'block';
	};

	var show = function show(elems) {
	  if (elems && !elems.length) {
	    return _show(elems);
	  }
	  for (var i = 0; i < elems.length; ++i) {
	    _show(elems[i]);
	  }
	};

	var _hide = function _hide(elem) {
	  elem.style.opacity = '';
	  elem.style.display = 'none';
	};

	var hide = function hide(elems) {
	  if (elems && !elems.length) {
	    return _hide(elems);
	  }
	  for (var i = 0; i < elems.length; ++i) {
	    _hide(elems[i]);
	  }
	};

	var isDescendant = function isDescendant(parent, child) {
	  var node = child.parentNode;
	  while (node !== null) {
	    if (node === parent) {
	      return true;
	    }
	    node = node.parentNode;
	  }
	  return false;
	};

	var getTopMargin = function getTopMargin(elem) {
	  elem.style.left = '-9999px';
	  elem.style.display = 'block';

	  var height = elem.clientHeight,
	      padding;
	  if (typeof getComputedStyle !== 'undefined') {
	    // IE 8
	    padding = parseInt(getComputedStyle(elem).getPropertyValue('padding-top'), 10);
	  } else {
	    padding = parseInt(elem.currentStyle.padding);
	  }

	  elem.style.left = '';
	  elem.style.display = 'none';
	  return '-' + parseInt((height + padding) / 2) + 'px';
	};

	var fadeIn = function fadeIn(elem, interval) {
	  if (+elem.style.opacity < 1) {
	    interval = interval || 16;
	    elem.style.opacity = 0;
	    elem.style.display = 'block';
	    var last = +new Date();
	    var tick = (function (_tick) {
	      function tick() {
	        return _tick.apply(this, arguments);
	      }

	      tick.toString = function () {
	        return _tick.toString();
	      };

	      return tick;
	    })(function () {
	      elem.style.opacity = +elem.style.opacity + (new Date() - last) / 100;
	      last = +new Date();

	      if (+elem.style.opacity < 1) {
	        setTimeout(tick, interval);
	      }
	    });
	    tick();
	  }
	  elem.style.display = 'block'; //fallback IE8
	};

	var fadeOut = function fadeOut(elem, interval) {
	  interval = interval || 16;
	  elem.style.opacity = 1;
	  var last = +new Date();
	  var tick = (function (_tick2) {
	    function tick() {
	      return _tick2.apply(this, arguments);
	    }

	    tick.toString = function () {
	      return _tick2.toString();
	    };

	    return tick;
	  })(function () {
	    elem.style.opacity = +elem.style.opacity - (new Date() - last) / 100;
	    last = +new Date();

	    if (+elem.style.opacity > 0) {
	      setTimeout(tick, interval);
	    } else {
	      elem.style.display = 'none';
	    }
	  });
	  tick();
	};

	var fireClick = function fireClick(node) {
	  // Taken from http://www.nonobtrusive.com/2011/11/29/programatically-fire-crossbrowser-click-event-with-javascript/
	  // Then fixed for today's Chrome browser.
	  if (typeof MouseEvent === 'function') {
	    // Up-to-date approach
	    var mevt = new MouseEvent('click', {
	      view: window,
	      bubbles: false,
	      cancelable: true
	    });
	    node.dispatchEvent(mevt);
	  } else if (document.createEvent) {
	    // Fallback
	    var evt = document.createEvent('MouseEvents');
	    evt.initEvent('click', false, false);
	    node.dispatchEvent(evt);
	  } else if (document.createEventObject) {
	    node.fireEvent('onclick');
	  } else if (typeof node.onclick === 'function') {
	    node.onclick();
	  }
	};

	var stopEventPropagation = function stopEventPropagation(e) {
	  // In particular, make sure the space bar doesn't scroll the main window.
	  if (typeof e.stopPropagation === 'function') {
	    e.stopPropagation();
	    e.preventDefault();
	  } else if (window.event && window.event.hasOwnProperty('cancelBubble')) {
	    window.event.cancelBubble = true;
	  }
	};

	exports.hasClass = hasClass;
	exports.addClass = addClass;
	exports.removeClass = removeClass;
	exports.escapeHtml = escapeHtml;
	exports._show = _show;
	exports.show = show;
	exports._hide = _hide;
	exports.hide = hide;
	exports.isDescendant = isDescendant;
	exports.getTopMargin = getTopMargin;
	exports.fadeIn = fadeIn;
	exports.fadeOut = fadeOut;
	exports.fireClick = fireClick;
	exports.stopEventPropagation = stopEventPropagation;

/***/ },
/* 125 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	/*
	 * Allow user to pass their own params
	 */
	var extend = function extend(a, b) {
	  for (var key in b) {
	    if (b.hasOwnProperty(key)) {
	      a[key] = b[key];
	    }
	  }
	  return a;
	};

	/*
	 * Convert HEX codes to RGB values (#000000 -> rgb(0,0,0))
	 */
	var hexToRgb = function hexToRgb(hex) {
	  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	  return result ? parseInt(result[1], 16) + ', ' + parseInt(result[2], 16) + ', ' + parseInt(result[3], 16) : null;
	};

	/*
	 * Check if the user is using Internet Explorer 8 (for fallbacks)
	 */
	var isIE8 = function isIE8() {
	  return window.attachEvent && !window.addEventListener;
	};

	/*
	 * IE compatible logging for developers
	 */
	var logStr = function logStr(string) {
	  if (window.console) {
	    // IE...
	    window.console.log('SweetAlert: ' + string);
	  }
	};

	/*
	 * Set hover, active and focus-states for buttons 
	 * (source: http://www.sitepoint.com/javascript-generate-lighter-darker-color)
	 */
	var colorLuminance = function colorLuminance(hex, lum) {
	  // Validate hex string
	  hex = String(hex).replace(/[^0-9a-f]/gi, '');
	  if (hex.length < 6) {
	    hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
	  }
	  lum = lum || 0;

	  // Convert to decimal and change luminosity
	  var rgb = '#';
	  var c;
	  var i;

	  for (i = 0; i < 3; i++) {
	    c = parseInt(hex.substr(i * 2, 2), 16);
	    c = Math.round(Math.min(Math.max(0, c + c * lum), 255)).toString(16);
	    rgb += ('00' + c).substr(c.length);
	  }

	  return rgb;
	};

	exports.extend = extend;
	exports.hexToRgb = hexToRgb;
	exports.isIE8 = isIE8;
	exports.logStr = logStr;
	exports.colorLuminance = colorLuminance;

/***/ },
/* 126 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _interopRequireWildcard = function (obj) { return obj && obj.__esModule ? obj : { 'default': obj }; };

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _hexToRgb = __webpack_require__(125);

	var _removeClass$getTopMargin$fadeIn$show$addClass = __webpack_require__(124);

	var _defaultParams = __webpack_require__(127);

	var _defaultParams2 = _interopRequireWildcard(_defaultParams);

	/*
	 * Add modal + overlay to DOM
	 */

	var _injectedHTML = __webpack_require__(128);

	var _injectedHTML2 = _interopRequireWildcard(_injectedHTML);

	var modalClass = '.sweet-alert';
	var overlayClass = '.sweet-overlay';

	var sweetAlertInitialize = function sweetAlertInitialize() {
	  var sweetWrap = document.createElement('div');
	  sweetWrap.innerHTML = _injectedHTML2['default'];

	  // Append elements to body
	  while (sweetWrap.firstChild) {
	    document.body.appendChild(sweetWrap.firstChild);
	  }
	};

	/*
	 * Get DOM element of modal
	 */
	var getModal = (function (_getModal) {
	  function getModal() {
	    return _getModal.apply(this, arguments);
	  }

	  getModal.toString = function () {
	    return _getModal.toString();
	  };

	  return getModal;
	})(function () {
	  var $modal = document.querySelector(modalClass);

	  if (!$modal) {
	    sweetAlertInitialize();
	    $modal = getModal();
	  }

	  return $modal;
	});

	/*
	 * Get DOM element of input (in modal)
	 */
	var getInput = function getInput() {
	  var $modal = getModal();
	  if ($modal) {
	    return $modal.querySelector('input');
	  }
	};

	/*
	 * Get DOM element of overlay
	 */
	var getOverlay = function getOverlay() {
	  return document.querySelector(overlayClass);
	};

	/*
	 * Add box-shadow style to button (depending on its chosen bg-color)
	 */
	var setFocusStyle = function setFocusStyle($button, bgColor) {
	  var rgbColor = _hexToRgb.hexToRgb(bgColor);
	  $button.style.boxShadow = '0 0 2px rgba(' + rgbColor + ', 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)';
	};

	/*
	 * Animation when opening modal
	 */
	var openModal = function openModal(callback) {
	  var $modal = getModal();
	  _removeClass$getTopMargin$fadeIn$show$addClass.fadeIn(getOverlay(), 10);
	  _removeClass$getTopMargin$fadeIn$show$addClass.show($modal);
	  _removeClass$getTopMargin$fadeIn$show$addClass.addClass($modal, 'showSweetAlert');
	  _removeClass$getTopMargin$fadeIn$show$addClass.removeClass($modal, 'hideSweetAlert');

	  window.previousActiveElement = document.activeElement;
	  var $okButton = $modal.querySelector('button.confirm');
	  $okButton.focus();

	  setTimeout(function () {
	    _removeClass$getTopMargin$fadeIn$show$addClass.addClass($modal, 'visible');
	  }, 500);

	  var timer = $modal.getAttribute('data-timer');

	  if (timer !== 'null' && timer !== '') {
	    var timerCallback = callback;
	    $modal.timeout = setTimeout(function () {
	      var doneFunctionExists = (timerCallback || null) && $modal.getAttribute('data-has-done-function') === 'true';
	      if (doneFunctionExists) {
	        timerCallback(null);
	      } else {
	        sweetAlert.close();
	      }
	    }, timer);
	  }
	};

	/*
	 * Reset the styling of the input
	 * (for example if errors have been shown)
	 */
	var resetInput = function resetInput() {
	  var $modal = getModal();
	  var $input = getInput();

	  _removeClass$getTopMargin$fadeIn$show$addClass.removeClass($modal, 'show-input');
	  $input.value = _defaultParams2['default'].inputValue;
	  $input.setAttribute('type', _defaultParams2['default'].inputType);
	  $input.setAttribute('placeholder', _defaultParams2['default'].inputPlaceholder);

	  resetInputError();
	};

	var resetInputError = function resetInputError(event) {
	  // If press enter => ignore
	  if (event && event.keyCode === 13) {
	    return false;
	  }

	  var $modal = getModal();

	  var $errorIcon = $modal.querySelector('.sa-input-error');
	  _removeClass$getTopMargin$fadeIn$show$addClass.removeClass($errorIcon, 'show');

	  var $errorContainer = $modal.querySelector('.sa-error-container');
	  _removeClass$getTopMargin$fadeIn$show$addClass.removeClass($errorContainer, 'show');
	};

	/*
	 * Set "margin-top"-property on modal based on its computed height
	 */
	var fixVerticalPosition = function fixVerticalPosition() {
	  var $modal = getModal();
	  $modal.style.marginTop = _removeClass$getTopMargin$fadeIn$show$addClass.getTopMargin(getModal());
	};

	exports.sweetAlertInitialize = sweetAlertInitialize;
	exports.getModal = getModal;
	exports.getOverlay = getOverlay;
	exports.getInput = getInput;
	exports.setFocusStyle = setFocusStyle;
	exports.openModal = openModal;
	exports.resetInput = resetInput;
	exports.resetInputError = resetInputError;
	exports.fixVerticalPosition = fixVerticalPosition;

/***/ },
/* 127 */
/***/ function(module, exports) {

	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	var defaultParams = {
	  title: '',
	  text: '',
	  type: null,
	  allowOutsideClick: false,
	  showConfirmButton: true,
	  showCancelButton: false,
	  closeOnConfirm: true,
	  closeOnCancel: true,
	  confirmButtonText: 'OK',
	  confirmButtonColor: '#8CD4F5',
	  cancelButtonText: 'Cancel',
	  imageUrl: null,
	  imageSize: null,
	  timer: null,
	  customClass: '',
	  html: false,
	  animation: true,
	  allowEscapeKey: true,
	  inputType: 'text',
	  inputPlaceholder: '',
	  inputValue: '',
	  showLoaderOnConfirm: false
	};

	exports['default'] = defaultParams;
	module.exports = exports['default'];

/***/ },
/* 128 */
/***/ function(module, exports) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	var injectedHTML =

	// Dark overlay
	"<div class=\"sweet-overlay\" tabIndex=\"-1\"></div>" +

	// Modal
	"<div class=\"sweet-alert\">" +

	// Error icon
	"<div class=\"sa-icon sa-error\">\n      <span class=\"sa-x-mark\">\n        <span class=\"sa-line sa-left\"></span>\n        <span class=\"sa-line sa-right\"></span>\n      </span>\n    </div>" +

	// Warning icon
	"<div class=\"sa-icon sa-warning\">\n      <span class=\"sa-body\"></span>\n      <span class=\"sa-dot\"></span>\n    </div>" +

	// Info icon
	"<div class=\"sa-icon sa-info\"></div>" +

	// Success icon
	"<div class=\"sa-icon sa-success\">\n      <span class=\"sa-line sa-tip\"></span>\n      <span class=\"sa-line sa-long\"></span>\n\n      <div class=\"sa-placeholder\"></div>\n      <div class=\"sa-fix\"></div>\n    </div>" + "<div class=\"sa-icon sa-custom\"></div>" +

	// Title, text and input
	"<h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type=\"text\" tabIndex=\"3\" />\n      <div class=\"sa-input-error\"></div>\n    </fieldset>" +

	// Input errors
	"<div class=\"sa-error-container\">\n      <div class=\"icon\">!</div>\n      <p>Not valid!</p>\n    </div>" +

	// Cancel and confirm buttons
	"<div class=\"sa-button-container\">\n      <button class=\"cancel\" tabIndex=\"2\">Cancel</button>\n      <div class=\"sa-confirm-button-container\">\n        <button class=\"confirm\" tabIndex=\"1\">OK</button>" +

	// Loading animation
	"<div class=\"la-ball-fall\">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div>" +

	// End of modal
	"</div>";

	exports["default"] = injectedHTML;
	module.exports = exports["default"];

/***/ },
/* 129 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _colorLuminance = __webpack_require__(125);

	var _getModal = __webpack_require__(126);

	var _hasClass$isDescendant = __webpack_require__(124);

	/*
	 * User clicked on "Confirm"/"OK" or "Cancel"
	 */
	var handleButton = function handleButton(event, params, modal) {
	  var e = event || window.event;
	  var target = e.target || e.srcElement;

	  var targetedConfirm = target.className.indexOf('confirm') !== -1;
	  var targetedOverlay = target.className.indexOf('sweet-overlay') !== -1;
	  var modalIsVisible = _hasClass$isDescendant.hasClass(modal, 'visible');
	  var doneFunctionExists = params.doneFunction && modal.getAttribute('data-has-done-function') === 'true';

	  // Since the user can change the background-color of the confirm button programmatically,
	  // we must calculate what the color should be on hover/active
	  var normalColor, hoverColor, activeColor;
	  if (targetedConfirm && params.confirmButtonColor) {
	    normalColor = params.confirmButtonColor;
	    hoverColor = _colorLuminance.colorLuminance(normalColor, -0.04);
	    activeColor = _colorLuminance.colorLuminance(normalColor, -0.14);
	  }

	  function shouldSetConfirmButtonColor(color) {
	    if (targetedConfirm && params.confirmButtonColor) {
	      target.style.backgroundColor = color;
	    }
	  }

	  switch (e.type) {
	    case 'mouseover':
	      shouldSetConfirmButtonColor(hoverColor);
	      break;

	    case 'mouseout':
	      shouldSetConfirmButtonColor(normalColor);
	      break;

	    case 'mousedown':
	      shouldSetConfirmButtonColor(activeColor);
	      break;

	    case 'mouseup':
	      shouldSetConfirmButtonColor(hoverColor);
	      break;

	    case 'focus':
	      var $confirmButton = modal.querySelector('button.confirm');
	      var $cancelButton = modal.querySelector('button.cancel');

	      if (targetedConfirm) {
	        $cancelButton.style.boxShadow = 'none';
	      } else {
	        $confirmButton.style.boxShadow = 'none';
	      }
	      break;

	    case 'click':
	      var clickedOnModal = modal === target;
	      var clickedOnModalChild = _hasClass$isDescendant.isDescendant(modal, target);

	      // Ignore click outside if allowOutsideClick is false
	      if (!clickedOnModal && !clickedOnModalChild && modalIsVisible && !params.allowOutsideClick) {
	        break;
	      }

	      if (targetedConfirm && doneFunctionExists && modalIsVisible) {
	        handleConfirm(modal, params);
	      } else if (doneFunctionExists && modalIsVisible || targetedOverlay) {
	        handleCancel(modal, params);
	      } else if (_hasClass$isDescendant.isDescendant(modal, target) && target.tagName === 'BUTTON') {
	        sweetAlert.close();
	      }
	      break;
	  }
	};

	/*
	 *  User clicked on "Confirm"/"OK"
	 */
	var handleConfirm = function handleConfirm(modal, params) {
	  var callbackValue = true;

	  if (_hasClass$isDescendant.hasClass(modal, 'show-input')) {
	    callbackValue = modal.querySelector('input').value;

	    if (!callbackValue) {
	      callbackValue = '';
	    }
	  }

	  params.doneFunction(callbackValue);

	  if (params.closeOnConfirm) {
	    sweetAlert.close();
	  }
	  // Disable cancel and confirm button if the parameter is true
	  if (params.showLoaderOnConfirm) {
	    sweetAlert.disableButtons();
	  }
	};

	/*
	 *  User clicked on "Cancel"
	 */
	var handleCancel = function handleCancel(modal, params) {
	  // Check if callback function expects a parameter (to track cancel actions)
	  var functionAsStr = String(params.doneFunction).replace(/\s/g, '');
	  var functionHandlesCancel = functionAsStr.substring(0, 9) === 'function(' && functionAsStr.substring(9, 10) !== ')';

	  if (functionHandlesCancel) {
	    params.doneFunction(false);
	  }

	  if (params.closeOnCancel) {
	    sweetAlert.close();
	  }
	};

	exports['default'] = {
	  handleButton: handleButton,
	  handleConfirm: handleConfirm,
	  handleCancel: handleCancel
	};
	module.exports = exports['default'];

/***/ },
/* 130 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _stopEventPropagation$fireClick = __webpack_require__(124);

	var _setFocusStyle = __webpack_require__(126);

	var handleKeyDown = function handleKeyDown(event, params, modal) {
	  var e = event || window.event;
	  var keyCode = e.keyCode || e.which;

	  var $okButton = modal.querySelector('button.confirm');
	  var $cancelButton = modal.querySelector('button.cancel');
	  var $modalButtons = modal.querySelectorAll('button[tabindex]');

	  if ([9, 13, 32, 27].indexOf(keyCode) === -1) {
	    // Don't do work on keys we don't care about.
	    return;
	  }

	  var $targetElement = e.target || e.srcElement;

	  var btnIndex = -1; // Find the button - note, this is a nodelist, not an array.
	  for (var i = 0; i < $modalButtons.length; i++) {
	    if ($targetElement === $modalButtons[i]) {
	      btnIndex = i;
	      break;
	    }
	  }

	  if (keyCode === 9) {
	    // TAB
	    if (btnIndex === -1) {
	      // No button focused. Jump to the confirm button.
	      $targetElement = $okButton;
	    } else {
	      // Cycle to the next button
	      if (btnIndex === $modalButtons.length - 1) {
	        $targetElement = $modalButtons[0];
	      } else {
	        $targetElement = $modalButtons[btnIndex + 1];
	      }
	    }

	    _stopEventPropagation$fireClick.stopEventPropagation(e);
	    $targetElement.focus();

	    if (params.confirmButtonColor) {
	      _setFocusStyle.setFocusStyle($targetElement, params.confirmButtonColor);
	    }
	  } else {
	    if (keyCode === 13) {
	      if ($targetElement.tagName === 'INPUT') {
	        $targetElement = $okButton;
	        $okButton.focus();
	      }

	      if (btnIndex === -1) {
	        // ENTER/SPACE clicked outside of a button.
	        $targetElement = $okButton;
	      } else {
	        // Do nothing - let the browser handle it.
	        $targetElement = undefined;
	      }
	    } else if (keyCode === 27 && params.allowEscapeKey === true) {
	      $targetElement = $cancelButton;
	      _stopEventPropagation$fireClick.fireClick($targetElement, e);
	    } else {
	      // Fallback - let the browser handle it.
	      $targetElement = undefined;
	    }
	  }
	};

	exports['default'] = handleKeyDown;
	module.exports = exports['default'];

/***/ },
/* 131 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _isIE8 = __webpack_require__(125);

	var _getModal$getInput$setFocusStyle = __webpack_require__(126);

	var _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide = __webpack_require__(124);

	var alertTypes = ['error', 'warning', 'info', 'success', 'input', 'prompt'];

	/*
	 * Set type, text and actions on modal
	 */
	var setParameters = function setParameters(params) {
	  var modal = _getModal$getInput$setFocusStyle.getModal();

	  var $title = modal.querySelector('h2');
	  var $text = modal.querySelector('p');
	  var $cancelBtn = modal.querySelector('button.cancel');
	  var $confirmBtn = modal.querySelector('button.confirm');

	  /*
	   * Title
	   */
	  $title.innerHTML = params.html ? params.title : _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.escapeHtml(params.title).split('\n').join('<br>');

	  /*
	   * Text
	   */
	  $text.innerHTML = params.html ? params.text : _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.escapeHtml(params.text || '').split('\n').join('<br>');
	  if (params.text) _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.show($text);

	  /*
	   * Custom class
	   */
	  if (params.customClass) {
	    _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass(modal, params.customClass);
	    modal.setAttribute('data-custom-class', params.customClass);
	  } else {
	    // Find previously set classes and remove them
	    var customClass = modal.getAttribute('data-custom-class');
	    _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.removeClass(modal, customClass);
	    modal.setAttribute('data-custom-class', '');
	  }

	  /*
	   * Icon
	   */
	  _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.hide(modal.querySelectorAll('.sa-icon'));

	  if (params.type && !_isIE8.isIE8()) {
	    var _ret = (function () {

	      var validType = false;

	      for (var i = 0; i < alertTypes.length; i++) {
	        if (params.type === alertTypes[i]) {
	          validType = true;
	          break;
	        }
	      }

	      if (!validType) {
	        logStr('Unknown alert type: ' + params.type);
	        return {
	          v: false
	        };
	      }

	      var typesWithIcons = ['success', 'error', 'warning', 'info'];
	      var $icon = undefined;

	      if (typesWithIcons.indexOf(params.type) !== -1) {
	        $icon = modal.querySelector('.sa-icon.' + 'sa-' + params.type);
	        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.show($icon);
	      }

	      var $input = _getModal$getInput$setFocusStyle.getInput();

	      // Animate icon
	      switch (params.type) {

	        case 'success':
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon, 'animate');
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon.querySelector('.sa-tip'), 'animateSuccessTip');
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon.querySelector('.sa-long'), 'animateSuccessLong');
	          break;

	        case 'error':
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon, 'animateErrorIcon');
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon.querySelector('.sa-x-mark'), 'animateXMark');
	          break;

	        case 'warning':
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon, 'pulseWarning');
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon.querySelector('.sa-body'), 'pulseWarningIns');
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass($icon.querySelector('.sa-dot'), 'pulseWarningIns');
	          break;

	        case 'input':
	        case 'prompt':
	          $input.setAttribute('type', params.inputType);
	          $input.value = params.inputValue;
	          $input.setAttribute('placeholder', params.inputPlaceholder);
	          _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.addClass(modal, 'show-input');
	          setTimeout(function () {
	            $input.focus();
	            $input.addEventListener('keyup', swal.resetInputError);
	          }, 400);
	          break;
	      }
	    })();

	    if (typeof _ret === 'object') {
	      return _ret.v;
	    }
	  }

	  /*
	   * Custom image
	   */
	  if (params.imageUrl) {
	    var $customIcon = modal.querySelector('.sa-icon.sa-custom');

	    $customIcon.style.backgroundImage = 'url(' + params.imageUrl + ')';
	    _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.show($customIcon);

	    var _imgWidth = 80;
	    var _imgHeight = 80;

	    if (params.imageSize) {
	      var dimensions = params.imageSize.toString().split('x');
	      var imgWidth = dimensions[0];
	      var imgHeight = dimensions[1];

	      if (!imgWidth || !imgHeight) {
	        logStr('Parameter imageSize expects value with format WIDTHxHEIGHT, got ' + params.imageSize);
	      } else {
	        _imgWidth = imgWidth;
	        _imgHeight = imgHeight;
	      }
	    }

	    $customIcon.setAttribute('style', $customIcon.getAttribute('style') + 'width:' + _imgWidth + 'px; height:' + _imgHeight + 'px');
	  }

	  /*
	   * Show cancel button?
	   */
	  modal.setAttribute('data-has-cancel-button', params.showCancelButton);
	  if (params.showCancelButton) {
	    $cancelBtn.style.display = 'inline-block';
	  } else {
	    _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.hide($cancelBtn);
	  }

	  /*
	   * Show confirm button?
	   */
	  modal.setAttribute('data-has-confirm-button', params.showConfirmButton);
	  if (params.showConfirmButton) {
	    $confirmBtn.style.display = 'inline-block';
	  } else {
	    _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.hide($confirmBtn);
	  }

	  /*
	   * Custom text on cancel/confirm buttons
	   */
	  if (params.cancelButtonText) {
	    $cancelBtn.innerHTML = _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.escapeHtml(params.cancelButtonText);
	  }
	  if (params.confirmButtonText) {
	    $confirmBtn.innerHTML = _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide.escapeHtml(params.confirmButtonText);
	  }

	  /*
	   * Custom color on confirm button
	   */
	  if (params.confirmButtonColor) {
	    // Set confirm button to selected background color
	    $confirmBtn.style.backgroundColor = params.confirmButtonColor;

	    // Set the confirm button color to the loading ring
	    $confirmBtn.style.borderLeftColor = params.confirmLoadingButtonColor;
	    $confirmBtn.style.borderRightColor = params.confirmLoadingButtonColor;

	    // Set box-shadow to default focused button
	    _getModal$getInput$setFocusStyle.setFocusStyle($confirmBtn, params.confirmButtonColor);
	  }

	  /*
	   * Allow outside click
	   */
	  modal.setAttribute('data-allow-outside-click', params.allowOutsideClick);

	  /*
	   * Callback function
	   */
	  var hasDoneFunction = params.doneFunction ? true : false;
	  modal.setAttribute('data-has-done-function', hasDoneFunction);

	  /*
	   * Animation
	   */
	  if (!params.animation) {
	    modal.setAttribute('data-animation', 'none');
	  } else if (typeof params.animation === 'string') {
	    modal.setAttribute('data-animation', params.animation); // Custom animation
	  } else {
	    modal.setAttribute('data-animation', 'pop');
	  }

	  /*
	   * Timer
	   */
	  modal.setAttribute('data-timer', params.timer);
	};

	exports['default'] = setParameters;
	module.exports = exports['default'];

/***/ },
/* 132 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;/* WEBPACK VAR INJECTION */(function(global) {/*!
	 * is.js 0.8.0
	 * Author: Aras Atasaygin
	 */

	// AMD with global, Node, or global
	;(function(root, factory) {    // eslint-disable-line no-extra-semi
	    if (true) {
	        // AMD. Register as an anonymous module.
	        !(__WEBPACK_AMD_DEFINE_RESULT__ = function() {
	            // Also create a global in case some scripts
	            // that are loaded still are looking for
	            // a global even when an AMD loader is in use.
	            return (root.is = factory());
	        }.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	    } else if (typeof exports === 'object') {
	        // Node. Does not work with strict CommonJS, but
	        // only CommonJS-like enviroments that support module.exports,
	        // like Node.
	        module.exports = factory();
	    } else {
	        // Browser globals (root is self)
	        root.is = factory();
	    }
	}(this, function() {

	    // Baseline
	    /* -------------------------------------------------------------------------- */

	    // define 'is' object and current version
	    var is = {};
	    is.VERSION = '0.8.0';

	    // define interfaces
	    is.not = {};
	    is.all = {};
	    is.any = {};

	    // cache some methods to call later on
	    var toString = Object.prototype.toString;
	    var slice = Array.prototype.slice;
	    var hasOwnProperty = Object.prototype.hasOwnProperty;

	    // helper function which reverses the sense of predicate result
	    function not(func) {
	        return function() {
	            return !func.apply(null, slice.call(arguments));
	        };
	    }

	    // helper function which call predicate function per parameter and return true if all pass
	    function all(func) {
	        return function() {
	            var params = getParams(arguments);
	            var length = params.length;
	            for (var i = 0; i < length; i++) {
	                if (!func.call(null, params[i])) {
	                    return false;
	                }
	            }
	            return true;
	        };
	    }

	    // helper function which call predicate function per parameter and return true if any pass
	    function any(func) {
	        return function() {
	            var params = getParams(arguments);
	            var length = params.length;
	            for (var i = 0; i < length; i++) {
	                if (func.call(null, params[i])) {
	                    return true;
	                }
	            }
	            return false;
	        };
	    }

	    // build a 'comparator' object for various comparison checks
	    var comparator = {
	        '<': function(a, b) { return a < b; },
	        '<=': function(a, b) { return a <= b; },
	        '>': function(a, b) { return a > b; },
	        '>=': function(a, b) { return a >= b; }
	    }

	    // helper function which compares a version to a range
	    function compareVersion(version, range) {
	        var string = (range + '');
	        var n = +(string.match(/\d+/) || NaN);
	        var op = string.match(/^[<>]=?|/)[0];
	        return comparator[op] ? comparator[op](version, n) : (version == n || n !== n);
	    }

	    // helper function which extracts params from arguments
	    function getParams(args) {
	        var params = slice.call(args);
	        var length = params.length;
	        if (length === 1 && is.array(params[0])) {    // support array
	            params = params[0];
	        }
	        return params;
	    }

	    // Type checks
	    /* -------------------------------------------------------------------------- */

	    // is a given value Arguments?
	    is.arguments = function(value) {    // fallback check is for IE
	        return toString.call(value) === '[object Arguments]' ||
	            (value != null && typeof value === 'object' && 'callee' in value);
	    };

	    // is a given value Array?
	    is.array = Array.isArray || function(value) {    // check native isArray first
	        return toString.call(value) === '[object Array]';
	    };

	    // is a given value Boolean?
	    is.boolean = function(value) {
	        return value === true || value === false || toString.call(value) === '[object Boolean]';
	    };

	    // is a given value Char?
	    is.char = function(value) {
	        return is.string(value) && value.length === 1;
	    };

	    // is a given value Date Object?
	    is.date = function(value) {
	        return toString.call(value) === '[object Date]';
	    };

	    // is a given object a DOM node?
	    is.domNode = function(object) {
	        return is.object(object) && object.nodeType > 0;
	    };

	    // is a given value Error object?
	    is.error = function(value) {
	        return toString.call(value) === '[object Error]';
	    };

	    // is a given value function?
	    is['function'] = function(value) {    // fallback check is for IE
	        return toString.call(value) === '[object Function]' || typeof value === 'function';
	    };

	    // is given value a pure JSON object?
	    is.json = function(value) {
	        return toString.call(value) === '[object Object]';
	    };

	    // is a given value NaN?
	    is.nan = function(value) {    // NaN is number :) Also it is the only value which does not equal itself
	        return value !== value;
	    };

	    // is a given value null?
	    is['null'] = function(value) {
	        return value === null;
	    };

	    // is a given value number?
	    is.number = function(value) {
	        return is.not.nan(value) && toString.call(value) === '[object Number]';
	    };

	    // is a given value object?
	    is.object = function(value) {
	        return Object(value) === value;
	    };

	    // is a given value RegExp?
	    is.regexp = function(value) {
	        return toString.call(value) === '[object RegExp]';
	    };

	    // are given values same type?
	    // prevent NaN, Number same type check
	    is.sameType = function(value, other) {
	        var tag = toString.call(value);
	        if (tag !== toString.call(other)) {
	            return false;
	        }
	        if (tag === '[object Number]') {
	            return !is.any.nan(value, other) || is.all.nan(value, other);
	        }
	        return true;
	    };
	    // sameType method does not support 'all' and 'any' interfaces
	    is.sameType.api = ['not'];

	    // is a given value String?
	    is.string = function(value) {
	        return toString.call(value) === '[object String]';
	    };

	    // is a given value undefined?
	    is.undefined = function(value) {
	        return value === void 0;
	    };

	    // is a given value window?
	    // setInterval method is only available for window object
	    is.windowObject = function(value) {
	        return value != null && typeof value === 'object' && 'setInterval' in value;
	    };

	    // Presence checks
	    /* -------------------------------------------------------------------------- */

	    //is a given value empty? Objects, arrays, strings
	    is.empty = function(value) {
	        if (is.object(value)) {
	            var length = Object.getOwnPropertyNames(value).length;
	            if (length === 0 || (length === 1 && is.array(value)) ||
	                    (length === 2 && is.arguments(value))) {
	                return true;
	            }
	            return false;
	        }
	        return value === '';
	    };

	    // is a given value existy?
	    is.existy = function(value) {
	        return value != null;
	    };

	    // is a given value falsy?
	    is.falsy = function(value) {
	        return !value;
	    };

	    // is a given value truthy?
	    is.truthy = not(is.falsy);

	    // Arithmetic checks
	    /* -------------------------------------------------------------------------- */

	    // is a given number above minimum parameter?
	    is.above = function(n, min) {
	        return is.all.number(n, min) && n > min;
	    };
	    // above method does not support 'all' and 'any' interfaces
	    is.above.api = ['not'];

	    // is a given number decimal?
	    is.decimal = function(n) {
	        return is.number(n) && n % 1 !== 0;
	    };

	    // are given values equal? supports numbers, strings, regexes, booleans
	    // TODO: Add object and array support
	    is.equal = function(value, other) {
	        // check 0 and -0 equity with Infinity and -Infinity
	        if (is.all.number(value, other)) {
	            return value === other && 1 / value === 1 / other;
	        }
	        // check regexes as strings too
	        if (is.all.string(value, other) || is.all.regexp(value, other)) {
	            return '' + value === '' + other;
	        }
	        if (is.all.boolean(value, other)) {
	            return value === other;
	        }
	        return false;
	    };
	    // equal method does not support 'all' and 'any' interfaces
	    is.equal.api = ['not'];

	    // is a given number even?
	    is.even = function(n) {
	        return is.number(n) && n % 2 === 0;
	    };

	    // is a given number finite?
	    is.finite = isFinite || function(n) {
	        return is.not.infinite(n) && is.not.nan(n);
	    };

	    // is a given number infinite?
	    is.infinite = function(n) {
	        return n === Infinity || n === -Infinity;
	    };

	    // is a given number integer?
	    is.integer = function(n) {
	        return is.number(n) && n % 1 === 0;
	    };

	    // is a given number negative?
	    is.negative = function(n) {
	        return is.number(n) && n < 0;
	    };

	    // is a given number odd?
	    is.odd = function(n) {
	        return is.number(n) && n % 2 === 1;
	    };

	    // is a given number positive?
	    is.positive = function(n) {
	        return is.number(n) && n > 0;
	    };

	    // is a given number above maximum parameter?
	    is.under = function(n, max) {
	        return is.all.number(n, max) && n < max;
	    };
	    // least method does not support 'all' and 'any' interfaces
	    is.under.api = ['not'];

	    // is a given number within minimum and maximum parameters?
	    is.within = function(n, min, max) {
	        return is.all.number(n, min, max) && n > min && n < max;
	    };
	    // within method does not support 'all' and 'any' interfaces
	    is.within.api = ['not'];

	    // Regexp checks
	    /* -------------------------------------------------------------------------- */
	    // Steven Levithan, Jan Goyvaerts: Regular Expressions Cookbook
	    // Scott Gonzalez: Email address validation

	    // dateString match m/d/yy and mm/dd/yyyy, allowing any combination of one or two digits for the day and month, and two or four digits for the year
	    // eppPhone match extensible provisioning protocol format
	    // nanpPhone match north american number plan format
	    // time match hours, minutes, and seconds, 24-hour clock
	    var regexes = {
	        affirmative: /^(?:1|t(?:rue)?|y(?:es)?|ok(?:ay)?)$/,
	        alphaNumeric: /^[A-Za-z0-9]+$/,
	        caPostalCode: /^(?!.*[DFIOQU])[A-VXY][0-9][A-Z]\s?[0-9][A-Z][0-9]$/,
	        creditCard: /^(?:(4[0-9]{12}(?:[0-9]{3})?)|(5[1-5][0-9]{14})|(6(?:011|5[0-9]{2})[0-9]{12})|(3[47][0-9]{13})|(3(?:0[0-5]|[68][0-9])[0-9]{11})|((?:2131|1800|35[0-9]{3})[0-9]{11}))$/,
	        dateString: /^(1[0-2]|0?[1-9])([\/-])(3[01]|[12][0-9]|0?[1-9])(?:\2)(?:[0-9]{2})?[0-9]{2}$/,
	        email: /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i, // eslint-disable-line no-control-regex
	        eppPhone: /^\+[0-9]{1,3}\.[0-9]{4,14}(?:x.+)?$/,
	        hexadecimal: /^(?:0x)?[0-9a-fA-F]+$/,
	        hexColor: /^#?([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/,
	        ipv4: /^(?:(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])$/,
	        ipv6: /^((?=.*::)(?!.*::.+::)(::)?([\dA-F]{1,4}:(:|\b)|){5}|([\dA-F]{1,4}:){6})((([\dA-F]{1,4}((?!\3)::|:\b|$))|(?!\2\3)){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i,
	        nanpPhone: /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/,
	        socialSecurityNumber: /^(?!000|666)[0-8][0-9]{2}-?(?!00)[0-9]{2}-?(?!0000)[0-9]{4}$/,
	        timeString: /^(2[0-3]|[01]?[0-9]):([0-5]?[0-9]):([0-5]?[0-9])$/,
	        ukPostCode: /^[A-Z]{1,2}[0-9RCHNQ][0-9A-Z]?\s?[0-9][ABD-HJLNP-UW-Z]{2}$|^[A-Z]{2}-?[0-9]{4}$/,
	        url: /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/i,
	        usZipCode: /^[0-9]{5}(?:-[0-9]{4})?$/
	    };

	    function regexpCheck(regexp, regexes) {
	        is[regexp] = function(value) {
	            return regexes[regexp].test(value);
	        };
	    }

	    // create regexp checks methods from 'regexes' object
	    for (var regexp in regexes) {
	        if (regexes.hasOwnProperty(regexp)) {
	            regexpCheck(regexp, regexes);
	        }
	    }

	    // simplify IP checks by calling the regex helpers for IPv4 and IPv6
	    is.ip = function(value) {
	        return is.ipv4(value) || is.ipv6(value);
	    };

	    // String checks
	    /* -------------------------------------------------------------------------- */

	    // is a given string or sentence capitalized?
	    is.capitalized = function(string) {
	        if (is.not.string(string)) {
	            return false;
	        }
	        var words = string.split(' ');
	        for (var i = 0; i < words.length; i++) {
	            var word = words[i];
	            if (word.length) {
	                var chr = word.charAt(0);
	                if (chr !== chr.toUpperCase()) {
	                    return false;
	                }
	            }
	        }
	        return true;
	    };

	    // is string end with a given target parameter?
	    is.endWith = function(string, target) {
	        if (is.not.string(string)) {
	            return false;
	        }
	        target += '';
	        var position = string.length - target.length;
	        return position >= 0 && string.indexOf(target, position) === position;
	    };
	    // endWith method does not support 'all' and 'any' interfaces
	    is.endWith.api = ['not'];

	    // is a given string include parameter target?
	    is.include = function(string, target) {
	        return string.indexOf(target) > -1;
	    };
	    // include method does not support 'all' and 'any' interfaces
	    is.include.api = ['not'];

	    // is a given string all lowercase?
	    is.lowerCase = function(string) {
	        return is.string(string) && string === string.toLowerCase();
	    };

	    // is a given string palindrome?
	    is.palindrome = function(string) {
	        if (is.not.string(string)) {
	            return false;
	        }
	        string = string.replace(/[^a-zA-Z0-9]+/g, '').toLowerCase();
	        var length = string.length - 1;
	        for (var i = 0, half = Math.floor(length / 2); i <= half; i++) {
	            if (string.charAt(i) !== string.charAt(length - i)) {
	                return false;
	            }
	        }
	        return true;
	    };

	    // is a given value space?
	    // horizantal tab: 9, line feed: 10, vertical tab: 11, form feed: 12, carriage return: 13, space: 32
	    is.space = function(value) {
	        if (is.not.char(value)) {
	            return false;
	        }
	        var charCode = value.charCodeAt(0);
	        return (charCode > 8 && charCode < 14) || charCode === 32;
	    };

	    // is string start with a given target parameter?
	    is.startWith = function(string, target) {
	        return is.string(string) && string.indexOf(target) === 0;
	    };
	    // startWith method does not support 'all' and 'any' interfaces
	    is.startWith.api = ['not'];

	    // is a given string all uppercase?
	    is.upperCase = function(string) {
	        return is.string(string) && string === string.toUpperCase();
	    };

	    // Time checks
	    /* -------------------------------------------------------------------------- */

	    var days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
	    var months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];

	    // is a given dates day equal given day parameter?
	    is.day = function(date, day) {
	        return is.date(date) && day.toLowerCase() === days[date.getDay()];
	    };
	    // day method does not support 'all' and 'any' interfaces
	    is.day.api = ['not'];

	    // is a given date in daylight saving time?
	    is.dayLightSavingTime = function(date) {
	        var january = new Date(date.getFullYear(), 0, 1);
	        var july = new Date(date.getFullYear(), 6, 1);
	        var stdTimezoneOffset = Math.max(january.getTimezoneOffset(), july.getTimezoneOffset());
	        return date.getTimezoneOffset() < stdTimezoneOffset;
	    };

	    // is a given date future?
	    is.future = function(date) {
	        var now = new Date();
	        return is.date(date) && date.getTime() > now.getTime();
	    };

	    // is date within given range?
	    is.inDateRange = function(date, start, end) {
	        if (is.not.date(date) || is.not.date(start) || is.not.date(end)) {
	            return false;
	        }
	        var stamp = date.getTime();
	        return stamp > start.getTime() && stamp < end.getTime();
	    };
	    // inDateRange method does not support 'all' and 'any' interfaces
	    is.inDateRange.api = ['not'];

	    // is a given date in last month range?
	    is.inLastMonth = function(date) {
	        return is.inDateRange(date, new Date(new Date().setMonth(new Date().getMonth() - 1)), new Date());
	    };

	    // is a given date in last week range?
	    is.inLastWeek = function(date) {
	        return is.inDateRange(date, new Date(new Date().setDate(new Date().getDate() - 7)), new Date());
	    };

	    // is a given date in last year range?
	    is.inLastYear = function(date) {
	        return is.inDateRange(date, new Date(new Date().setFullYear(new Date().getFullYear() - 1)), new Date());
	    };

	    // is a given date in next month range?
	    is.inNextMonth = function(date) {
	        return is.inDateRange(date, new Date(), new Date(new Date().setMonth(new Date().getMonth() + 1)));
	    };

	    // is a given date in next week range?
	    is.inNextWeek = function(date) {
	        return is.inDateRange(date, new Date(), new Date(new Date().setDate(new Date().getDate() + 7)));
	    };

	    // is a given date in next year range?
	    is.inNextYear = function(date) {
	        return is.inDateRange(date, new Date(), new Date(new Date().setFullYear(new Date().getFullYear() + 1)));
	    };

	    // is the given year a leap year?
	    is.leapYear = function(year) {
	        return is.number(year) && ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0);
	    };

	    // is a given dates month equal given month parameter?
	    is.month = function(date, month) {
	        return is.date(date) && month.toLowerCase() === months[date.getMonth()];
	    };
	    // month method does not support 'all' and 'any' interfaces
	    is.month.api = ['not'];

	    // is a given date past?
	    is.past = function(date) {
	        var now = new Date();
	        return is.date(date) && date.getTime() < now.getTime();
	    };

	    // is a given date in the parameter quarter?
	    is.quarterOfYear = function(date, quarter) {
	        return is.date(date) && is.number(quarter) && quarter === Math.floor((date.getMonth() + 3) / 3);
	    };
	    // quarterOfYear method does not support 'all' and 'any' interfaces
	    is.quarterOfYear.api = ['not'];

	    // is a given date indicate today?
	    is.today = function(date) {
	        var now = new Date();
	        var todayString = now.toDateString();
	        return is.date(date) && date.toDateString() === todayString;
	    };

	    // is a given date indicate tomorrow?
	    is.tomorrow = function(date) {
	        var now = new Date();
	        var tomorrowString = new Date(now.setDate(now.getDate() + 1)).toDateString();
	        return is.date(date) && date.toDateString() === tomorrowString;
	    };

	    // is a given date weekend?
	    // 6: Saturday, 0: Sunday
	    is.weekend = function(date) {
	        return is.date(date) && (date.getDay() === 6 || date.getDay() === 0);
	    };

	    // is a given date weekday?
	    is.weekday = not(is.weekend);

	    // is a given dates year equal given year parameter?
	    is.year = function(date, year) {
	        return is.date(date) && is.number(year) && year === date.getFullYear();
	    };
	    // year method does not support 'all' and 'any' interfaces
	    is.year.api = ['not'];

	    // is a given date indicate yesterday?
	    is.yesterday = function(date) {
	        var now = new Date();
	        var yesterdayString = new Date(now.setDate(now.getDate() - 1)).toDateString();
	        return is.date(date) && date.toDateString() === yesterdayString;
	    };

	    // Environment checks
	    /* -------------------------------------------------------------------------- */

	    var freeGlobal = is.windowObject(typeof global == 'object' && global) && global;
	    var freeSelf = is.windowObject(typeof self == 'object' && self) && self;
	    var thisGlobal = is.windowObject(typeof this == 'object' && this) && this;
	    var root = freeGlobal || freeSelf || thisGlobal || Function('return this')();

	    var document = freeSelf && freeSelf.document;
	    var previousIs = root.is;

	    // store navigator properties to use later
	    var navigator = freeSelf && freeSelf.navigator;
	    var appVersion = (navigator && navigator.appVersion || '').toLowerCase();
	    var userAgent = (navigator && navigator.userAgent || '').toLowerCase();
	    var vendor = (navigator && navigator.vendor || '').toLowerCase();

	    // is current device android?
	    is.android = function() {
	        return /android/.test(userAgent);
	    };
	    // android method does not support 'all' and 'any' interfaces
	    is.android.api = ['not'];

	    // is current device android phone?
	    is.androidPhone = function() {
	        return /android/.test(userAgent) && /mobile/.test(userAgent);
	    };
	    // androidPhone method does not support 'all' and 'any' interfaces
	    is.androidPhone.api = ['not'];

	    // is current device android tablet?
	    is.androidTablet = function() {
	        return /android/.test(userAgent) && !/mobile/.test(userAgent);
	    };
	    // androidTablet method does not support 'all' and 'any' interfaces
	    is.androidTablet.api = ['not'];

	    // is current device blackberry?
	    is.blackberry = function() {
	        return /blackberry/.test(userAgent) || /bb10/.test(userAgent);
	    };
	    // blackberry method does not support 'all' and 'any' interfaces
	    is.blackberry.api = ['not'];

	    // is current browser chrome?
	    // parameter is optional
	    is.chrome = function(range) {
	        var match = /google inc/.test(vendor) ? userAgent.match(/(?:chrome|crios)\/(\d+)/) : null;
	        return match !== null && compareVersion(match[1], range);
	    };
	    // chrome method does not support 'all' and 'any' interfaces
	    is.chrome.api = ['not'];

	    // is current device desktop?
	    is.desktop = function() {
	        return is.not.mobile() && is.not.tablet();
	    };
	    // desktop method does not support 'all' and 'any' interfaces
	    is.desktop.api = ['not'];

	    // is current browser edge?
	    // parameter is optional
	    is.edge = function(range) {
	        var match = userAgent.match(/edge\/(\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // edge method does not support 'all' and 'any' interfaces
	    is.edge.api = ['not'];

	    // is current browser firefox?
	    // parameter is optional
	    is.firefox = function(range) {
	        var match = userAgent.match(/(?:firefox|fxios)\/(\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // firefox method does not support 'all' and 'any' interfaces
	    is.firefox.api = ['not'];

	    // is current browser internet explorer?
	    // parameter is optional
	    is.ie = function(range) {
	        var match = userAgent.match(/(?:msie |trident.+?; rv:)(\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // ie method does not support 'all' and 'any' interfaces
	    is.ie.api = ['not'];

	    // is current device ios?
	    is.ios = function() {
	        return is.iphone() || is.ipad() || is.ipod();
	    };
	    // ios method does not support 'all' and 'any' interfaces
	    is.ios.api = ['not'];

	    // is current device ipad?
	    // parameter is optional
	    is.ipad = function(range) {
	        var match = userAgent.match(/ipad.+?os (\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // ipad method does not support 'all' and 'any' interfaces
	    is.ipad.api = ['not'];

	    // is current device iphone?
	    // parameter is optional
	    is.iphone = function(range) {
	        // original iPhone doesn't have the os portion of the UA
	        var match = userAgent.match(/iphone(?:.+?os (\d+))?/);
	        return match !== null && compareVersion(match[1] || 1, range);
	    };
	    // iphone method does not support 'all' and 'any' interfaces
	    is.iphone.api = ['not'];

	    // is current device ipod?
	    // parameter is optional
	    is.ipod = function(range) {
	        var match = userAgent.match(/ipod.+?os (\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // ipod method does not support 'all' and 'any' interfaces
	    is.ipod.api = ['not'];

	    // is current operating system linux?
	    is.linux = function() {
	        return /linux/.test(appVersion);
	    };
	    // linux method does not support 'all' and 'any' interfaces
	    is.linux.api = ['not'];

	    // is current operating system mac?
	    is.mac = function() {
	        return /mac/.test(appVersion);
	    };
	    // mac method does not support 'all' and 'any' interfaces
	    is.mac.api = ['not'];

	    // is current device mobile?
	    is.mobile = function() {
	        return is.iphone() || is.ipod() || is.androidPhone() || is.blackberry() || is.windowsPhone();
	    };
	    // mobile method does not support 'all' and 'any' interfaces
	    is.mobile.api = ['not'];

	    // is current state offline?
	    is.offline = not(is.online);
	    // offline method does not support 'all' and 'any' interfaces
	    is.offline.api = ['not'];

	    // is current state online?
	    is.online = function() {
	        return !navigator || navigator.onLine === true;
	    };
	    // online method does not support 'all' and 'any' interfaces
	    is.online.api = ['not'];

	    // is current browser opera?
	    // parameter is optional
	    is.opera = function(range) {
	        var match = userAgent.match(/(?:^opera.+?version|opr)\/(\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // opera method does not support 'all' and 'any' interfaces
	    is.opera.api = ['not'];

	    // is current browser phantomjs?
	    // parameter is optional
	    is.phantom = function(range) {
	        var match = userAgent.match(/phantomjs\/(\d+)/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // phantom method does not support 'all' and 'any' interfaces
	    is.phantom.api = ['not'];

	    // is current browser safari?
	    // parameter is optional
	    is.safari = function(range) {
	        var match = userAgent.match(/version\/(\d+).+?safari/);
	        return match !== null && compareVersion(match[1], range);
	    };
	    // safari method does not support 'all' and 'any' interfaces
	    is.safari.api = ['not'];

	    // is current device tablet?
	    is.tablet = function() {
	        return is.ipad() || is.androidTablet() || is.windowsTablet();
	    };
	    // tablet method does not support 'all' and 'any' interfaces
	    is.tablet.api = ['not'];

	    // is current device supports touch?
	    is.touchDevice = function() {
	        return !!document && ('ontouchstart' in freeSelf ||
	            ('DocumentTouch' in freeSelf && document instanceof DocumentTouch));
	    };
	    // touchDevice method does not support 'all' and 'any' interfaces
	    is.touchDevice.api = ['not'];

	    // is current operating system windows?
	    is.windows = function() {
	        return /win/.test(appVersion);
	    };
	    // windows method does not support 'all' and 'any' interfaces
	    is.windows.api = ['not'];

	    // is current device windows phone?
	    is.windowsPhone = function() {
	        return is.windows() && /phone/.test(userAgent);
	    };
	    // windowsPhone method does not support 'all' and 'any' interfaces
	    is.windowsPhone.api = ['not'];

	    // is current device windows tablet?
	    is.windowsTablet = function() {
	        return is.windows() && is.not.windowsPhone() && /touch/.test(userAgent);
	    };
	    // windowsTablet method does not support 'all' and 'any' interfaces
	    is.windowsTablet.api = ['not'];

	    // Object checks
	    /* -------------------------------------------------------------------------- */

	    // has a given object got parameterized count property?
	    is.propertyCount = function(object, count) {
	        if (is.not.object(object) || is.not.number(count)) {
	            return false;
	        }
	        var n = 0;
	        for (var property in object) {
	            if (hasOwnProperty.call(object, property) && ++n > count) {
	                return false;
	            }
	        }
	        return n === count;
	    };
	    // propertyCount method does not support 'all' and 'any' interfaces
	    is.propertyCount.api = ['not'];

	    // is given object has parameterized property?
	    is.propertyDefined = function(object, property) {
	        return is.object(object) && is.string(property) && property in object;
	    };
	    // propertyDefined method does not support 'all' and 'any' interfaces
	    is.propertyDefined.api = ['not'];

	    // Array checks
	    /* -------------------------------------------------------------------------- */

	    // is a given item in an array?
	    is.inArray = function(value, array) {
	        if (is.not.array(array)) {
	            return false;
	        }
	        for (var i = 0; i < array.length; i++) {
	            if (array[i] === value) {
	                return true;
	            }
	        }
	        return false;
	    };
	    // inArray method does not support 'all' and 'any' interfaces
	    is.inArray.api = ['not'];

	    // is a given array sorted?
	    is.sorted = function(array, sign) {
	        if (is.not.array(array)) {
	            return false;
	        }
	        var predicate = comparator[sign] || comparator['>='];
	        for (var i = 1; i < array.length; i++) {
	            if (!predicate(array[i], array[i - 1])) {
	                return false;
	            }
	        }
	        return true;
	    };

	    // API
	    // Set 'not', 'all' and 'any' interfaces to methods based on their api property
	    /* -------------------------------------------------------------------------- */

	    function setInterfaces() {
	        var options = is;
	        for (var option in options) {
	            if (hasOwnProperty.call(options, option) && is['function'](options[option])) {
	                var interfaces = options[option].api || ['not', 'all', 'any'];
	                for (var i = 0; i < interfaces.length; i++) {
	                    if (interfaces[i] === 'not') {
	                        is.not[option] = not(is[option]);
	                    }
	                    if (interfaces[i] === 'all') {
	                        is.all[option] = all(is[option]);
	                    }
	                    if (interfaces[i] === 'any') {
	                        is.any[option] = any(is[option]);
	                    }
	                }
	            }
	        }
	    }
	    setInterfaces();

	    // Configuration methods
	    // Intentionally added after setInterfaces function
	    /* -------------------------------------------------------------------------- */

	    // change namespace of library to prevent name collisions
	    // var preferredName = is.setNamespace();
	    // preferredName.odd(3);
	    // => true
	    is.setNamespace = function() {
	        root.is = previousIs;
	        return this;
	    };

	    // set optional regexes to methods
	    is.setRegexp = function(regexp, name) {
	        for (var r in regexes) {
	            if (hasOwnProperty.call(regexes, r) && (name === r)) {
	                regexes[r] = regexp;
	            }
	        }
	    };

	    return is;
	}));

	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },
/* 133 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _getPrototypeOf = __webpack_require__(134);

	var _getPrototypeOf2 = _interopRequireDefault(_getPrototypeOf);

	var _classCallCheck2 = __webpack_require__(97);

	var _classCallCheck3 = _interopRequireDefault(_classCallCheck2);

	var _createClass2 = __webpack_require__(139);

	var _createClass3 = _interopRequireDefault(_createClass2);

	var _possibleConstructorReturn2 = __webpack_require__(143);

	var _possibleConstructorReturn3 = _interopRequireDefault(_possibleConstructorReturn2);

	var _inherits2 = __webpack_require__(178);

	var _inherits3 = _interopRequireDefault(_inherits2);

	var _jsxFileName = '/home/bitrix/ext_www/ul.profitweb.biz/local/components/ul/help.feedback/app/Form.js';

	var _controller = __webpack_require__(57);

	var _reactRedux = __webpack_require__(18);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	var Form = function (_React$Component) {
		(0, _inherits3.default)(Form, _React$Component);

		function Form(props) {
			(0, _classCallCheck3.default)(this, Form);
			return (0, _possibleConstructorReturn3.default)(this, (Form.__proto__ || (0, _getPrototypeOf2.default)(Form)).call(this, props));
		}

		(0, _createClass3.default)(Form, [{
			key: 'render',
			value: function render() {
				return React.createElement(
					'formHelp',
					{
						__source: {
							fileName: _jsxFileName,
							lineNumber: 12
						},
						__self: this
					},
					React.createElement(
						'div',
						{ className: 'ph-me', __source: {
								fileName: _jsxFileName,
								lineNumber: 13
							},
							__self: this
						},
						React.createElement(
							'span',
							{
								__source: {
									fileName: _jsxFileName,
									lineNumber: 14
								},
								__self: this
							},
							'\u041F\u0435\u0440\u0435\u0437\u0432\u043E\u043D\u0438\u0442\u0435 \u043C\u043D\u0435'
						),
						React.createElement(
							'a',
							{ href: 'javascript:', __source: {
									fileName: _jsxFileName,
									lineNumber: 15
								},
								__self: this
							},
							'\u0417\u0430\u0434\u0430\u0442\u044C \u0432\u043E\u043F\u0440\u043E\u0441'
						)
					),
					React.createElement(
						'div',
						{ className: 'order1__form', __source: {
								fileName: _jsxFileName,
								lineNumber: 17
							},
							__self: this
						},
						React.createElement(
							'form',
							{
								__source: {
									fileName: _jsxFileName,
									lineNumber: 18
								},
								__self: this
							},
							React.createElement(
								'div',
								{ className: 'b-ib lk__profile', __source: {
										fileName: _jsxFileName,
										lineNumber: 19
									},
									__self: this
								},
								React.createElement(
									'span',
									{ className: 'span_help', __source: {
											fileName: _jsxFileName,
											lineNumber: 19
										},
										__self: this
									},
									'\u0413\u043E\u0440\u043E\u0434'
								),
								React.createElement(
									'div',
									{ className: 'b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-header-popup__filter_lk_280 b-ib', __source: {
											fileName: _jsxFileName,
											lineNumber: 20
										},
										__self: this
									},
									React.createElement(
										'div',
										{ className: 'b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib', __source: {
												fileName: _jsxFileName,
												lineNumber: 21
											},
											__self: this
										},
										React.createElement(
											'select',
											{ className: 'b-custom-select js-custom-select2', __source: {
													fileName: _jsxFileName,
													lineNumber: 22
												},
												__self: this
											},
											React.createElement(
												'option',
												{ selected: 'selected', value: '\u0421\u0430\u043C\u0430\u0440\u0430', __source: {
														fileName: _jsxFileName,
														lineNumber: 23
													},
													__self: this
												},
												'\u0421\u0430\u043C\u0430\u0440\u0430'
											),
											React.createElement(
												'option',
												{ value: '\u0414\u0435\u0440\u0435\u0432\u043D\u044F \u043A\u0430\u0431\u0430\u0447\u043A\u0438', __source: {
														fileName: _jsxFileName,
														lineNumber: 24
													},
													__self: this
												},
												'\u0414\u0435\u0440\u0435\u0432\u043D\u044F \u043A\u0430\u0431\u0430\u0447\u043A\u0438'
											),
											React.createElement(
												'option',
												{ value: '\u0427\u0435\u0440\u043F\u0435\u043F\u043E\u0432\u0435\u0446', __source: {
														fileName: _jsxFileName,
														lineNumber: 25
													},
													__self: this
												},
												'\u0427\u0435\u0440\u043F\u0435\u043F\u043E\u0432\u0435\u0446'
											)
										)
									)
								)
							),
							React.createElement(
								'label',
								{ className: 'form__label', __source: {
										fileName: _jsxFileName,
										lineNumber: 30
									},
									__self: this
								},
								React.createElement('input', { type: 'text', placeholder: '\u0412\u0430\u0448 \u043D\u043E\u043C\u0435\u0440', className: 'form__input form__input_middle form__input_help', __source: {
										fileName: _jsxFileName,
										lineNumber: 31
									},
									__self: this
								}),
								React.createElement(
									'span',
									{ className: 'span_help', __source: {
											fileName: _jsxFileName,
											lineNumber: 31
										},
										__self: this
									},
									'\u0422\u0435\u043B\u0435\u0444\u043E\u043D'
								)
							),
							React.createElement(
								'label',
								{ className: 'form__label', __source: {
										fileName: _jsxFileName,
										lineNumber: 33
									},
									__self: this
								},
								React.createElement('input', { type: 'text', placeholder: '\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0438\u043C\u044F', className: 'form__input form__input_middle form__input_help', __source: {
										fileName: _jsxFileName,
										lineNumber: 34
									},
									__self: this
								}),
								React.createElement(
									'span',
									{ className: 'span_help', __source: {
											fileName: _jsxFileName,
											lineNumber: 34
										},
										__self: this
									},
									'\u0418\u043C\u044F'
								)
							),
							React.createElement(
								'div',
								{ className: 'help-wrapper', __source: {
										fileName: _jsxFileName,
										lineNumber: 36
									},
									__self: this
								},
								React.createElement(
									'div',
									{ className: 'help__left b-ib', __source: {
											fileName: _jsxFileName,
											lineNumber: 37
										},
										__self: this
									},
									React.createElement(
										'span',
										{
											__source: {
												fileName: _jsxFileName,
												lineNumber: 37
											},
											__self: this
										},
										'\u041F\u0440\u0438\u0447\u0438\u043D\u0430'
									)
								),
								React.createElement(
									'div',
									{ className: 'help__right b-ib', __source: {
											fileName: _jsxFileName,
											lineNumber: 38
										},
										__self: this
									},
									React.createElement(
										'div',
										{ className: 'order1__radio', __source: {
												fileName: _jsxFileName,
												lineNumber: 39
											},
											__self: this
										},
										React.createElement(
											'label',
											{ className: 'filter__label filter__label_radio filter__label_radio-yellow', __source: {
													fileName: _jsxFileName,
													lineNumber: 40
												},
												__self: this
											},
											React.createElement('input', { checked: true, type: 'radio', name: 'adr', className: 'filter__checkbox filter__checkbox_radio', __source: {
													fileName: _jsxFileName,
													lineNumber: 41
												},
												__self: this
											}),
											React.createElement('i', {
												__source: {
													fileName: _jsxFileName,
													lineNumber: 41
												},
												__self: this
											}),
											React.createElement(
												'div',
												{ className: 'check__wrapper b-ib', __source: {
														fileName: _jsxFileName,
														lineNumber: 42
													},
													__self: this
												},
												React.createElement(
													'span',
													{ className: 'history__order2', __source: {
															fileName: _jsxFileName,
															lineNumber: 42
														},
														__self: this
													},
													'\u0425\u043E\u0447\u0443 \u0441\u0434\u0435\u043B\u0430\u0442\u044C \u0437\u0430\u043A\u0430\u0437'
												)
											)
										),
										React.createElement(
											'label',
											{ className: 'filter__label filter__label_radio filter__label_radio-yellow', __source: {
													fileName: _jsxFileName,
													lineNumber: 44
												},
												__self: this
											},
											React.createElement('input', { checked: true, type: 'radio', name: 'adr', className: 'filter__checkbox filter__checkbox_radio', __source: {
													fileName: _jsxFileName,
													lineNumber: 45
												},
												__self: this
											}),
											React.createElement('i', {
												__source: {
													fileName: _jsxFileName,
													lineNumber: 45
												},
												__self: this
											}),
											React.createElement(
												'div',
												{ className: 'check__wrapper b-ib', __source: {
														fileName: _jsxFileName,
														lineNumber: 46
													},
													__self: this
												},
												React.createElement(
													'span',
													{ className: 'history__order2', __source: {
															fileName: _jsxFileName,
															lineNumber: 46
														},
														__self: this
													},
													'\u041D\u0435 \u043F\u0440\u0438\u0448\u0435\u043B \u0437\u0430\u043A\u0430\u0437'
												)
											)
										),
										React.createElement(
											'label',
											{ className: 'filter__label filter__label_radio filter__label_radio-yellow', __source: {
													fileName: _jsxFileName,
													lineNumber: 48
												},
												__self: this
											},
											React.createElement('input', { type: 'radio', name: 'adr', className: 'filter__checkbox filter__checkbox_radio', __source: {
													fileName: _jsxFileName,
													lineNumber: 49
												},
												__self: this
											}),
											React.createElement('i', {
												__source: {
													fileName: _jsxFileName,
													lineNumber: 49
												},
												__self: this
											}),
											React.createElement(
												'div',
												{ className: 'check__wrapper b-ib', __source: {
														fileName: _jsxFileName,
														lineNumber: 50
													},
													__self: this
												},
												React.createElement(
													'span',
													{ className: 'history__order2', __source: {
															fileName: _jsxFileName,
															lineNumber: 50
														},
														__self: this
													},
													'\u0416\u0430\u043B\u043E\u0431\u0430'
												)
											)
										)
									)
								)
							)
						),
						React.createElement(
							'button',
							{ className: 'b-button b-button_green b-button_check b-button_big b-button_width', __source: {
									fileName: _jsxFileName,
									lineNumber: 56
								},
								__self: this
							},
							' \u041E\u0442\u043F\u0440\u0430\u0432\u0438\u0442\u044C \u0437\u0430\u044F\u0432\u043A\u0443'
						),
						React.createElement(
							'span',
							{ className: 'help__small', __source: {
									fileName: _jsxFileName,
									lineNumber: 57
								},
								__self: this
							},
							'\u041E\u043F\u0435\u0440\u0430\u0442\u043E\u0440 \u043F\u0435\u0440\u0435\u0437\u0432\u0430\u043D\u0438\u0432\u0430\u0435\u0442 \u0432 \u0440\u0430\u0431\u043E\u0447\u0435\u0435 \u0432\u0440\u0435\u043C\u044F \u0432 \u0442\u0435\u0447\u0435\u043D\u0438\u0435 2 \u0447\u0430\u0441\u043E\u0432'
						)
					)
				);
			}
		}]);
		return Form;
	}(React.Component);

	exports.default = (0, _reactRedux.connect)(_controller.FormCtrl.mapStateToProps, _controller.FormCtrl.mapDispatchToProps)(Form);

/***/ },
/* 134 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(135), __esModule: true };

/***/ },
/* 135 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(136);
	module.exports = __webpack_require__(64).Object.getPrototypeOf;

/***/ },
/* 136 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.2.9 Object.getPrototypeOf(O)
	var toObject        = __webpack_require__(95)
	  , $getPrototypeOf = __webpack_require__(137);

	__webpack_require__(138)('getPrototypeOf', function(){
	  return function getPrototypeOf(it){
	    return $getPrototypeOf(toObject(it));
	  };
	});

/***/ },
/* 137 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.2.9 / 15.2.3.2 Object.getPrototypeOf(O)
	var has         = __webpack_require__(80)
	  , toObject    = __webpack_require__(95)
	  , IE_PROTO    = __webpack_require__(89)('IE_PROTO')
	  , ObjectProto = Object.prototype;

	module.exports = Object.getPrototypeOf || function(O){
	  O = toObject(O);
	  if(has(O, IE_PROTO))return O[IE_PROTO];
	  if(typeof O.constructor == 'function' && O instanceof O.constructor){
	    return O.constructor.prototype;
	  } return O instanceof Object ? ObjectProto : null;
	};

/***/ },
/* 138 */
/***/ function(module, exports, __webpack_require__) {

	// most Object methods by ES6 should accept primitives
	var $export = __webpack_require__(62)
	  , core    = __webpack_require__(64)
	  , fails   = __webpack_require__(73);
	module.exports = function(KEY, exec){
	  var fn  = (core.Object || {})[KEY] || Object[KEY]
	    , exp = {};
	  exp[KEY] = exec(fn);
	  $export($export.S + $export.F * fails(function(){ fn(1); }), 'Object', exp);
	};

/***/ },
/* 139 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	exports.__esModule = true;

	var _defineProperty = __webpack_require__(140);

	var _defineProperty2 = _interopRequireDefault(_defineProperty);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = function () {
	  function defineProperties(target, props) {
	    for (var i = 0; i < props.length; i++) {
	      var descriptor = props[i];
	      descriptor.enumerable = descriptor.enumerable || false;
	      descriptor.configurable = true;
	      if ("value" in descriptor) descriptor.writable = true;
	      (0, _defineProperty2.default)(target, descriptor.key, descriptor);
	    }
	  }

	  return function (Constructor, protoProps, staticProps) {
	    if (protoProps) defineProperties(Constructor.prototype, protoProps);
	    if (staticProps) defineProperties(Constructor, staticProps);
	    return Constructor;
	  };
	}();

/***/ },
/* 140 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(141), __esModule: true };

/***/ },
/* 141 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(142);
	var $Object = __webpack_require__(64).Object;
	module.exports = function defineProperty(it, key, desc){
	  return $Object.defineProperty(it, key, desc);
	};

/***/ },
/* 142 */
/***/ function(module, exports, __webpack_require__) {

	var $export = __webpack_require__(62);
	// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
	$export($export.S + $export.F * !__webpack_require__(72), 'Object', {defineProperty: __webpack_require__(68).f});

/***/ },
/* 143 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	exports.__esModule = true;

	var _typeof2 = __webpack_require__(144);

	var _typeof3 = _interopRequireDefault(_typeof2);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = function (self, call) {
	  if (!self) {
	    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
	  }

	  return call && ((typeof call === "undefined" ? "undefined" : (0, _typeof3.default)(call)) === "object" || typeof call === "function") ? call : self;
	};

/***/ },
/* 144 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	exports.__esModule = true;

	var _iterator = __webpack_require__(145);

	var _iterator2 = _interopRequireDefault(_iterator);

	var _symbol = __webpack_require__(164);

	var _symbol2 = _interopRequireDefault(_symbol);

	var _typeof = typeof _symbol2.default === "function" && typeof _iterator2.default === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj; };

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = typeof _symbol2.default === "function" && _typeof(_iterator2.default) === "symbol" ? function (obj) {
	  return typeof obj === "undefined" ? "undefined" : _typeof(obj);
	} : function (obj) {
	  return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj === "undefined" ? "undefined" : _typeof(obj);
	};

/***/ },
/* 145 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(146), __esModule: true };

/***/ },
/* 146 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(147);
	__webpack_require__(159);
	module.exports = __webpack_require__(163).f('iterator');

/***/ },
/* 147 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	var $at  = __webpack_require__(148)(true);

	// 21.1.3.27 String.prototype[@@iterator]()
	__webpack_require__(149)(String, 'String', function(iterated){
	  this._t = String(iterated); // target
	  this._i = 0;                // next index
	// 21.1.5.2.1 %StringIteratorPrototype%.next()
	}, function(){
	  var O     = this._t
	    , index = this._i
	    , point;
	  if(index >= O.length)return {value: undefined, done: true};
	  point = $at(O, index);
	  this._i += point.length;
	  return {value: point, done: false};
	});

/***/ },
/* 148 */
/***/ function(module, exports, __webpack_require__) {

	var toInteger = __webpack_require__(87)
	  , defined   = __webpack_require__(84);
	// true  -> String#at
	// false -> String#codePointAt
	module.exports = function(TO_STRING){
	  return function(that, pos){
	    var s = String(defined(that))
	      , i = toInteger(pos)
	      , l = s.length
	      , a, b;
	    if(i < 0 || i >= l)return TO_STRING ? '' : undefined;
	    a = s.charCodeAt(i);
	    return a < 0xd800 || a > 0xdbff || i + 1 === l || (b = s.charCodeAt(i + 1)) < 0xdc00 || b > 0xdfff
	      ? TO_STRING ? s.charAt(i) : a
	      : TO_STRING ? s.slice(i, i + 2) : (a - 0xd800 << 10) + (b - 0xdc00) + 0x10000;
	  };
	};

/***/ },
/* 149 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	var LIBRARY        = __webpack_require__(150)
	  , $export        = __webpack_require__(62)
	  , redefine       = __webpack_require__(151)
	  , hide           = __webpack_require__(67)
	  , has            = __webpack_require__(80)
	  , Iterators      = __webpack_require__(152)
	  , $iterCreate    = __webpack_require__(153)
	  , setToStringTag = __webpack_require__(157)
	  , getPrototypeOf = __webpack_require__(137)
	  , ITERATOR       = __webpack_require__(158)('iterator')
	  , BUGGY          = !([].keys && 'next' in [].keys()) // Safari has buggy iterators w/o `next`
	  , FF_ITERATOR    = '@@iterator'
	  , KEYS           = 'keys'
	  , VALUES         = 'values';

	var returnThis = function(){ return this; };

	module.exports = function(Base, NAME, Constructor, next, DEFAULT, IS_SET, FORCED){
	  $iterCreate(Constructor, NAME, next);
	  var getMethod = function(kind){
	    if(!BUGGY && kind in proto)return proto[kind];
	    switch(kind){
	      case KEYS: return function keys(){ return new Constructor(this, kind); };
	      case VALUES: return function values(){ return new Constructor(this, kind); };
	    } return function entries(){ return new Constructor(this, kind); };
	  };
	  var TAG        = NAME + ' Iterator'
	    , DEF_VALUES = DEFAULT == VALUES
	    , VALUES_BUG = false
	    , proto      = Base.prototype
	    , $native    = proto[ITERATOR] || proto[FF_ITERATOR] || DEFAULT && proto[DEFAULT]
	    , $default   = $native || getMethod(DEFAULT)
	    , $entries   = DEFAULT ? !DEF_VALUES ? $default : getMethod('entries') : undefined
	    , $anyNative = NAME == 'Array' ? proto.entries || $native : $native
	    , methods, key, IteratorPrototype;
	  // Fix native
	  if($anyNative){
	    IteratorPrototype = getPrototypeOf($anyNative.call(new Base));
	    if(IteratorPrototype !== Object.prototype){
	      // Set @@toStringTag to native iterators
	      setToStringTag(IteratorPrototype, TAG, true);
	      // fix for some old engines
	      if(!LIBRARY && !has(IteratorPrototype, ITERATOR))hide(IteratorPrototype, ITERATOR, returnThis);
	    }
	  }
	  // fix Array#{values, @@iterator}.name in V8 / FF
	  if(DEF_VALUES && $native && $native.name !== VALUES){
	    VALUES_BUG = true;
	    $default = function values(){ return $native.call(this); };
	  }
	  // Define iterator
	  if((!LIBRARY || FORCED) && (BUGGY || VALUES_BUG || !proto[ITERATOR])){
	    hide(proto, ITERATOR, $default);
	  }
	  // Plug for library
	  Iterators[NAME] = $default;
	  Iterators[TAG]  = returnThis;
	  if(DEFAULT){
	    methods = {
	      values:  DEF_VALUES ? $default : getMethod(VALUES),
	      keys:    IS_SET     ? $default : getMethod(KEYS),
	      entries: $entries
	    };
	    if(FORCED)for(key in methods){
	      if(!(key in proto))redefine(proto, key, methods[key]);
	    } else $export($export.P + $export.F * (BUGGY || VALUES_BUG), NAME, methods);
	  }
	  return methods;
	};

/***/ },
/* 150 */
/***/ function(module, exports) {

	module.exports = true;

/***/ },
/* 151 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(67);

/***/ },
/* 152 */
/***/ function(module, exports) {

	module.exports = {};

/***/ },
/* 153 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	var create         = __webpack_require__(154)
	  , descriptor     = __webpack_require__(76)
	  , setToStringTag = __webpack_require__(157)
	  , IteratorPrototype = {};

	// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
	__webpack_require__(67)(IteratorPrototype, __webpack_require__(158)('iterator'), function(){ return this; });

	module.exports = function(Constructor, NAME, next){
	  Constructor.prototype = create(IteratorPrototype, {next: descriptor(1, next)});
	  setToStringTag(Constructor, NAME + ' Iterator');
	};

/***/ },
/* 154 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
	var anObject    = __webpack_require__(69)
	  , dPs         = __webpack_require__(155)
	  , enumBugKeys = __webpack_require__(92)
	  , IE_PROTO    = __webpack_require__(89)('IE_PROTO')
	  , Empty       = function(){ /* empty */ }
	  , PROTOTYPE   = 'prototype';

	// Create object with fake `null` prototype: use iframe Object with cleared prototype
	var createDict = function(){
	  // Thrash, waste and sodomy: IE GC bug
	  var iframe = __webpack_require__(74)('iframe')
	    , i      = enumBugKeys.length
	    , lt     = '<'
	    , gt     = '>'
	    , iframeDocument;
	  iframe.style.display = 'none';
	  __webpack_require__(156).appendChild(iframe);
	  iframe.src = 'javascript:'; // eslint-disable-line no-script-url
	  // createDict = iframe.contentWindow.Object;
	  // html.removeChild(iframe);
	  iframeDocument = iframe.contentWindow.document;
	  iframeDocument.open();
	  iframeDocument.write(lt + 'script' + gt + 'document.F=Object' + lt + '/script' + gt);
	  iframeDocument.close();
	  createDict = iframeDocument.F;
	  while(i--)delete createDict[PROTOTYPE][enumBugKeys[i]];
	  return createDict();
	};

	module.exports = Object.create || function create(O, Properties){
	  var result;
	  if(O !== null){
	    Empty[PROTOTYPE] = anObject(O);
	    result = new Empty;
	    Empty[PROTOTYPE] = null;
	    // add "__proto__" for Object.getPrototypeOf polyfill
	    result[IE_PROTO] = O;
	  } else result = createDict();
	  return Properties === undefined ? result : dPs(result, Properties);
	};


/***/ },
/* 155 */
/***/ function(module, exports, __webpack_require__) {

	var dP       = __webpack_require__(68)
	  , anObject = __webpack_require__(69)
	  , getKeys  = __webpack_require__(78);

	module.exports = __webpack_require__(72) ? Object.defineProperties : function defineProperties(O, Properties){
	  anObject(O);
	  var keys   = getKeys(Properties)
	    , length = keys.length
	    , i = 0
	    , P;
	  while(length > i)dP.f(O, P = keys[i++], Properties[P]);
	  return O;
	};

/***/ },
/* 156 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = __webpack_require__(63).document && document.documentElement;

/***/ },
/* 157 */
/***/ function(module, exports, __webpack_require__) {

	var def = __webpack_require__(68).f
	  , has = __webpack_require__(80)
	  , TAG = __webpack_require__(158)('toStringTag');

	module.exports = function(it, tag, stat){
	  if(it && !has(it = stat ? it : it.prototype, TAG))def(it, TAG, {configurable: true, value: tag});
	};

/***/ },
/* 158 */
/***/ function(module, exports, __webpack_require__) {

	var store      = __webpack_require__(90)('wks')
	  , uid        = __webpack_require__(91)
	  , Symbol     = __webpack_require__(63).Symbol
	  , USE_SYMBOL = typeof Symbol == 'function';

	var $exports = module.exports = function(name){
	  return store[name] || (store[name] =
	    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
	};

	$exports.store = store;

/***/ },
/* 159 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(160);
	var global        = __webpack_require__(63)
	  , hide          = __webpack_require__(67)
	  , Iterators     = __webpack_require__(152)
	  , TO_STRING_TAG = __webpack_require__(158)('toStringTag');

	for(var collections = ['NodeList', 'DOMTokenList', 'MediaList', 'StyleSheetList', 'CSSRuleList'], i = 0; i < 5; i++){
	  var NAME       = collections[i]
	    , Collection = global[NAME]
	    , proto      = Collection && Collection.prototype;
	  if(proto && !proto[TO_STRING_TAG])hide(proto, TO_STRING_TAG, NAME);
	  Iterators[NAME] = Iterators.Array;
	}

/***/ },
/* 160 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	var addToUnscopables = __webpack_require__(161)
	  , step             = __webpack_require__(162)
	  , Iterators        = __webpack_require__(152)
	  , toIObject        = __webpack_require__(81);

	// 22.1.3.4 Array.prototype.entries()
	// 22.1.3.13 Array.prototype.keys()
	// 22.1.3.29 Array.prototype.values()
	// 22.1.3.30 Array.prototype[@@iterator]()
	module.exports = __webpack_require__(149)(Array, 'Array', function(iterated, kind){
	  this._t = toIObject(iterated); // target
	  this._i = 0;                   // next index
	  this._k = kind;                // kind
	// 22.1.5.2.1 %ArrayIteratorPrototype%.next()
	}, function(){
	  var O     = this._t
	    , kind  = this._k
	    , index = this._i++;
	  if(!O || index >= O.length){
	    this._t = undefined;
	    return step(1);
	  }
	  if(kind == 'keys'  )return step(0, index);
	  if(kind == 'values')return step(0, O[index]);
	  return step(0, [index, O[index]]);
	}, 'values');

	// argumentsList[@@iterator] is %ArrayProto_values% (9.4.4.6, 9.4.4.7)
	Iterators.Arguments = Iterators.Array;

	addToUnscopables('keys');
	addToUnscopables('values');
	addToUnscopables('entries');

/***/ },
/* 161 */
/***/ function(module, exports) {

	module.exports = function(){ /* empty */ };

/***/ },
/* 162 */
/***/ function(module, exports) {

	module.exports = function(done, value){
	  return {value: value, done: !!done};
	};

/***/ },
/* 163 */
/***/ function(module, exports, __webpack_require__) {

	exports.f = __webpack_require__(158);

/***/ },
/* 164 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(165), __esModule: true };

/***/ },
/* 165 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(166);
	__webpack_require__(175);
	__webpack_require__(176);
	__webpack_require__(177);
	module.exports = __webpack_require__(64).Symbol;

/***/ },
/* 166 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';
	// ECMAScript 6 symbols shim
	var global         = __webpack_require__(63)
	  , has            = __webpack_require__(80)
	  , DESCRIPTORS    = __webpack_require__(72)
	  , $export        = __webpack_require__(62)
	  , redefine       = __webpack_require__(151)
	  , META           = __webpack_require__(167).KEY
	  , $fails         = __webpack_require__(73)
	  , shared         = __webpack_require__(90)
	  , setToStringTag = __webpack_require__(157)
	  , uid            = __webpack_require__(91)
	  , wks            = __webpack_require__(158)
	  , wksExt         = __webpack_require__(163)
	  , wksDefine      = __webpack_require__(168)
	  , keyOf          = __webpack_require__(169)
	  , enumKeys       = __webpack_require__(170)
	  , isArray        = __webpack_require__(171)
	  , anObject       = __webpack_require__(69)
	  , toIObject      = __webpack_require__(81)
	  , toPrimitive    = __webpack_require__(75)
	  , createDesc     = __webpack_require__(76)
	  , _create        = __webpack_require__(154)
	  , gOPNExt        = __webpack_require__(172)
	  , $GOPD          = __webpack_require__(174)
	  , $DP            = __webpack_require__(68)
	  , $keys          = __webpack_require__(78)
	  , gOPD           = $GOPD.f
	  , dP             = $DP.f
	  , gOPN           = gOPNExt.f
	  , $Symbol        = global.Symbol
	  , $JSON          = global.JSON
	  , _stringify     = $JSON && $JSON.stringify
	  , PROTOTYPE      = 'prototype'
	  , HIDDEN         = wks('_hidden')
	  , TO_PRIMITIVE   = wks('toPrimitive')
	  , isEnum         = {}.propertyIsEnumerable
	  , SymbolRegistry = shared('symbol-registry')
	  , AllSymbols     = shared('symbols')
	  , OPSymbols      = shared('op-symbols')
	  , ObjectProto    = Object[PROTOTYPE]
	  , USE_NATIVE     = typeof $Symbol == 'function'
	  , QObject        = global.QObject;
	// Don't use setters in Qt Script, https://github.com/zloirock/core-js/issues/173
	var setter = !QObject || !QObject[PROTOTYPE] || !QObject[PROTOTYPE].findChild;

	// fallback for old Android, https://code.google.com/p/v8/issues/detail?id=687
	var setSymbolDesc = DESCRIPTORS && $fails(function(){
	  return _create(dP({}, 'a', {
	    get: function(){ return dP(this, 'a', {value: 7}).a; }
	  })).a != 7;
	}) ? function(it, key, D){
	  var protoDesc = gOPD(ObjectProto, key);
	  if(protoDesc)delete ObjectProto[key];
	  dP(it, key, D);
	  if(protoDesc && it !== ObjectProto)dP(ObjectProto, key, protoDesc);
	} : dP;

	var wrap = function(tag){
	  var sym = AllSymbols[tag] = _create($Symbol[PROTOTYPE]);
	  sym._k = tag;
	  return sym;
	};

	var isSymbol = USE_NATIVE && typeof $Symbol.iterator == 'symbol' ? function(it){
	  return typeof it == 'symbol';
	} : function(it){
	  return it instanceof $Symbol;
	};

	var $defineProperty = function defineProperty(it, key, D){
	  if(it === ObjectProto)$defineProperty(OPSymbols, key, D);
	  anObject(it);
	  key = toPrimitive(key, true);
	  anObject(D);
	  if(has(AllSymbols, key)){
	    if(!D.enumerable){
	      if(!has(it, HIDDEN))dP(it, HIDDEN, createDesc(1, {}));
	      it[HIDDEN][key] = true;
	    } else {
	      if(has(it, HIDDEN) && it[HIDDEN][key])it[HIDDEN][key] = false;
	      D = _create(D, {enumerable: createDesc(0, false)});
	    } return setSymbolDesc(it, key, D);
	  } return dP(it, key, D);
	};
	var $defineProperties = function defineProperties(it, P){
	  anObject(it);
	  var keys = enumKeys(P = toIObject(P))
	    , i    = 0
	    , l = keys.length
	    , key;
	  while(l > i)$defineProperty(it, key = keys[i++], P[key]);
	  return it;
	};
	var $create = function create(it, P){
	  return P === undefined ? _create(it) : $defineProperties(_create(it), P);
	};
	var $propertyIsEnumerable = function propertyIsEnumerable(key){
	  var E = isEnum.call(this, key = toPrimitive(key, true));
	  if(this === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key))return false;
	  return E || !has(this, key) || !has(AllSymbols, key) || has(this, HIDDEN) && this[HIDDEN][key] ? E : true;
	};
	var $getOwnPropertyDescriptor = function getOwnPropertyDescriptor(it, key){
	  it  = toIObject(it);
	  key = toPrimitive(key, true);
	  if(it === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key))return;
	  var D = gOPD(it, key);
	  if(D && has(AllSymbols, key) && !(has(it, HIDDEN) && it[HIDDEN][key]))D.enumerable = true;
	  return D;
	};
	var $getOwnPropertyNames = function getOwnPropertyNames(it){
	  var names  = gOPN(toIObject(it))
	    , result = []
	    , i      = 0
	    , key;
	  while(names.length > i){
	    if(!has(AllSymbols, key = names[i++]) && key != HIDDEN && key != META)result.push(key);
	  } return result;
	};
	var $getOwnPropertySymbols = function getOwnPropertySymbols(it){
	  var IS_OP  = it === ObjectProto
	    , names  = gOPN(IS_OP ? OPSymbols : toIObject(it))
	    , result = []
	    , i      = 0
	    , key;
	  while(names.length > i){
	    if(has(AllSymbols, key = names[i++]) && (IS_OP ? has(ObjectProto, key) : true))result.push(AllSymbols[key]);
	  } return result;
	};

	// 19.4.1.1 Symbol([description])
	if(!USE_NATIVE){
	  $Symbol = function Symbol(){
	    if(this instanceof $Symbol)throw TypeError('Symbol is not a constructor!');
	    var tag = uid(arguments.length > 0 ? arguments[0] : undefined);
	    var $set = function(value){
	      if(this === ObjectProto)$set.call(OPSymbols, value);
	      if(has(this, HIDDEN) && has(this[HIDDEN], tag))this[HIDDEN][tag] = false;
	      setSymbolDesc(this, tag, createDesc(1, value));
	    };
	    if(DESCRIPTORS && setter)setSymbolDesc(ObjectProto, tag, {configurable: true, set: $set});
	    return wrap(tag);
	  };
	  redefine($Symbol[PROTOTYPE], 'toString', function toString(){
	    return this._k;
	  });

	  $GOPD.f = $getOwnPropertyDescriptor;
	  $DP.f   = $defineProperty;
	  __webpack_require__(173).f = gOPNExt.f = $getOwnPropertyNames;
	  __webpack_require__(94).f  = $propertyIsEnumerable;
	  __webpack_require__(93).f = $getOwnPropertySymbols;

	  if(DESCRIPTORS && !__webpack_require__(150)){
	    redefine(ObjectProto, 'propertyIsEnumerable', $propertyIsEnumerable, true);
	  }

	  wksExt.f = function(name){
	    return wrap(wks(name));
	  }
	}

	$export($export.G + $export.W + $export.F * !USE_NATIVE, {Symbol: $Symbol});

	for(var symbols = (
	  // 19.4.2.2, 19.4.2.3, 19.4.2.4, 19.4.2.6, 19.4.2.8, 19.4.2.9, 19.4.2.10, 19.4.2.11, 19.4.2.12, 19.4.2.13, 19.4.2.14
	  'hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables'
	).split(','), i = 0; symbols.length > i; )wks(symbols[i++]);

	for(var symbols = $keys(wks.store), i = 0; symbols.length > i; )wksDefine(symbols[i++]);

	$export($export.S + $export.F * !USE_NATIVE, 'Symbol', {
	  // 19.4.2.1 Symbol.for(key)
	  'for': function(key){
	    return has(SymbolRegistry, key += '')
	      ? SymbolRegistry[key]
	      : SymbolRegistry[key] = $Symbol(key);
	  },
	  // 19.4.2.5 Symbol.keyFor(sym)
	  keyFor: function keyFor(key){
	    if(isSymbol(key))return keyOf(SymbolRegistry, key);
	    throw TypeError(key + ' is not a symbol!');
	  },
	  useSetter: function(){ setter = true; },
	  useSimple: function(){ setter = false; }
	});

	$export($export.S + $export.F * !USE_NATIVE, 'Object', {
	  // 19.1.2.2 Object.create(O [, Properties])
	  create: $create,
	  // 19.1.2.4 Object.defineProperty(O, P, Attributes)
	  defineProperty: $defineProperty,
	  // 19.1.2.3 Object.defineProperties(O, Properties)
	  defineProperties: $defineProperties,
	  // 19.1.2.6 Object.getOwnPropertyDescriptor(O, P)
	  getOwnPropertyDescriptor: $getOwnPropertyDescriptor,
	  // 19.1.2.7 Object.getOwnPropertyNames(O)
	  getOwnPropertyNames: $getOwnPropertyNames,
	  // 19.1.2.8 Object.getOwnPropertySymbols(O)
	  getOwnPropertySymbols: $getOwnPropertySymbols
	});

	// 24.3.2 JSON.stringify(value [, replacer [, space]])
	$JSON && $export($export.S + $export.F * (!USE_NATIVE || $fails(function(){
	  var S = $Symbol();
	  // MS Edge converts symbol values to JSON as {}
	  // WebKit converts symbol values to JSON as null
	  // V8 throws on boxed symbols
	  return _stringify([S]) != '[null]' || _stringify({a: S}) != '{}' || _stringify(Object(S)) != '{}';
	})), 'JSON', {
	  stringify: function stringify(it){
	    if(it === undefined || isSymbol(it))return; // IE8 returns string on undefined
	    var args = [it]
	      , i    = 1
	      , replacer, $replacer;
	    while(arguments.length > i)args.push(arguments[i++]);
	    replacer = args[1];
	    if(typeof replacer == 'function')$replacer = replacer;
	    if($replacer || !isArray(replacer))replacer = function(key, value){
	      if($replacer)value = $replacer.call(this, key, value);
	      if(!isSymbol(value))return value;
	    };
	    args[1] = replacer;
	    return _stringify.apply($JSON, args);
	  }
	});

	// 19.4.3.4 Symbol.prototype[@@toPrimitive](hint)
	$Symbol[PROTOTYPE][TO_PRIMITIVE] || __webpack_require__(67)($Symbol[PROTOTYPE], TO_PRIMITIVE, $Symbol[PROTOTYPE].valueOf);
	// 19.4.3.5 Symbol.prototype[@@toStringTag]
	setToStringTag($Symbol, 'Symbol');
	// 20.2.1.9 Math[@@toStringTag]
	setToStringTag(Math, 'Math', true);
	// 24.3.3 JSON[@@toStringTag]
	setToStringTag(global.JSON, 'JSON', true);

/***/ },
/* 167 */
/***/ function(module, exports, __webpack_require__) {

	var META     = __webpack_require__(91)('meta')
	  , isObject = __webpack_require__(70)
	  , has      = __webpack_require__(80)
	  , setDesc  = __webpack_require__(68).f
	  , id       = 0;
	var isExtensible = Object.isExtensible || function(){
	  return true;
	};
	var FREEZE = !__webpack_require__(73)(function(){
	  return isExtensible(Object.preventExtensions({}));
	});
	var setMeta = function(it){
	  setDesc(it, META, {value: {
	    i: 'O' + ++id, // object ID
	    w: {}          // weak collections IDs
	  }});
	};
	var fastKey = function(it, create){
	  // return primitive with prefix
	  if(!isObject(it))return typeof it == 'symbol' ? it : (typeof it == 'string' ? 'S' : 'P') + it;
	  if(!has(it, META)){
	    // can't set metadata to uncaught frozen object
	    if(!isExtensible(it))return 'F';
	    // not necessary to add metadata
	    if(!create)return 'E';
	    // add missing metadata
	    setMeta(it);
	  // return object ID
	  } return it[META].i;
	};
	var getWeak = function(it, create){
	  if(!has(it, META)){
	    // can't set metadata to uncaught frozen object
	    if(!isExtensible(it))return true;
	    // not necessary to add metadata
	    if(!create)return false;
	    // add missing metadata
	    setMeta(it);
	  // return hash weak collections IDs
	  } return it[META].w;
	};
	// add metadata on freeze-family methods calling
	var onFreeze = function(it){
	  if(FREEZE && meta.NEED && isExtensible(it) && !has(it, META))setMeta(it);
	  return it;
	};
	var meta = module.exports = {
	  KEY:      META,
	  NEED:     false,
	  fastKey:  fastKey,
	  getWeak:  getWeak,
	  onFreeze: onFreeze
	};

/***/ },
/* 168 */
/***/ function(module, exports, __webpack_require__) {

	var global         = __webpack_require__(63)
	  , core           = __webpack_require__(64)
	  , LIBRARY        = __webpack_require__(150)
	  , wksExt         = __webpack_require__(163)
	  , defineProperty = __webpack_require__(68).f;
	module.exports = function(name){
	  var $Symbol = core.Symbol || (core.Symbol = LIBRARY ? {} : global.Symbol || {});
	  if(name.charAt(0) != '_' && !(name in $Symbol))defineProperty($Symbol, name, {value: wksExt.f(name)});
	};

/***/ },
/* 169 */
/***/ function(module, exports, __webpack_require__) {

	var getKeys   = __webpack_require__(78)
	  , toIObject = __webpack_require__(81);
	module.exports = function(object, el){
	  var O      = toIObject(object)
	    , keys   = getKeys(O)
	    , length = keys.length
	    , index  = 0
	    , key;
	  while(length > index)if(O[key = keys[index++]] === el)return key;
	};

/***/ },
/* 170 */
/***/ function(module, exports, __webpack_require__) {

	// all enumerable object keys, includes symbols
	var getKeys = __webpack_require__(78)
	  , gOPS    = __webpack_require__(93)
	  , pIE     = __webpack_require__(94);
	module.exports = function(it){
	  var result     = getKeys(it)
	    , getSymbols = gOPS.f;
	  if(getSymbols){
	    var symbols = getSymbols(it)
	      , isEnum  = pIE.f
	      , i       = 0
	      , key;
	    while(symbols.length > i)if(isEnum.call(it, key = symbols[i++]))result.push(key);
	  } return result;
	};

/***/ },
/* 171 */
/***/ function(module, exports, __webpack_require__) {

	// 7.2.2 IsArray(argument)
	var cof = __webpack_require__(83);
	module.exports = Array.isArray || function isArray(arg){
	  return cof(arg) == 'Array';
	};

/***/ },
/* 172 */
/***/ function(module, exports, __webpack_require__) {

	// fallback for IE11 buggy Object.getOwnPropertyNames with iframe and window
	var toIObject = __webpack_require__(81)
	  , gOPN      = __webpack_require__(173).f
	  , toString  = {}.toString;

	var windowNames = typeof window == 'object' && window && Object.getOwnPropertyNames
	  ? Object.getOwnPropertyNames(window) : [];

	var getWindowNames = function(it){
	  try {
	    return gOPN(it);
	  } catch(e){
	    return windowNames.slice();
	  }
	};

	module.exports.f = function getOwnPropertyNames(it){
	  return windowNames && toString.call(it) == '[object Window]' ? getWindowNames(it) : gOPN(toIObject(it));
	};


/***/ },
/* 173 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.2.7 / 15.2.3.4 Object.getOwnPropertyNames(O)
	var $keys      = __webpack_require__(79)
	  , hiddenKeys = __webpack_require__(92).concat('length', 'prototype');

	exports.f = Object.getOwnPropertyNames || function getOwnPropertyNames(O){
	  return $keys(O, hiddenKeys);
	};

/***/ },
/* 174 */
/***/ function(module, exports, __webpack_require__) {

	var pIE            = __webpack_require__(94)
	  , createDesc     = __webpack_require__(76)
	  , toIObject      = __webpack_require__(81)
	  , toPrimitive    = __webpack_require__(75)
	  , has            = __webpack_require__(80)
	  , IE8_DOM_DEFINE = __webpack_require__(71)
	  , gOPD           = Object.getOwnPropertyDescriptor;

	exports.f = __webpack_require__(72) ? gOPD : function getOwnPropertyDescriptor(O, P){
	  O = toIObject(O);
	  P = toPrimitive(P, true);
	  if(IE8_DOM_DEFINE)try {
	    return gOPD(O, P);
	  } catch(e){ /* empty */ }
	  if(has(O, P))return createDesc(!pIE.f.call(O, P), O[P]);
	};

/***/ },
/* 175 */
/***/ function(module, exports) {

	

/***/ },
/* 176 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(168)('asyncIterator');

/***/ },
/* 177 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(168)('observable');

/***/ },
/* 178 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	exports.__esModule = true;

	var _setPrototypeOf = __webpack_require__(179);

	var _setPrototypeOf2 = _interopRequireDefault(_setPrototypeOf);

	var _create = __webpack_require__(183);

	var _create2 = _interopRequireDefault(_create);

	var _typeof2 = __webpack_require__(144);

	var _typeof3 = _interopRequireDefault(_typeof2);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = function (subClass, superClass) {
	  if (typeof superClass !== "function" && superClass !== null) {
	    throw new TypeError("Super expression must either be null or a function, not " + (typeof superClass === "undefined" ? "undefined" : (0, _typeof3.default)(superClass)));
	  }

	  subClass.prototype = (0, _create2.default)(superClass && superClass.prototype, {
	    constructor: {
	      value: subClass,
	      enumerable: false,
	      writable: true,
	      configurable: true
	    }
	  });
	  if (superClass) _setPrototypeOf2.default ? (0, _setPrototypeOf2.default)(subClass, superClass) : subClass.__proto__ = superClass;
	};

/***/ },
/* 179 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(180), __esModule: true };

/***/ },
/* 180 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(181);
	module.exports = __webpack_require__(64).Object.setPrototypeOf;

/***/ },
/* 181 */
/***/ function(module, exports, __webpack_require__) {

	// 19.1.3.19 Object.setPrototypeOf(O, proto)
	var $export = __webpack_require__(62);
	$export($export.S, 'Object', {setPrototypeOf: __webpack_require__(182).set});

/***/ },
/* 182 */
/***/ function(module, exports, __webpack_require__) {

	// Works with __proto__ only. Old v8 can't work with null proto objects.
	/* eslint-disable no-proto */
	var isObject = __webpack_require__(70)
	  , anObject = __webpack_require__(69);
	var check = function(O, proto){
	  anObject(O);
	  if(!isObject(proto) && proto !== null)throw TypeError(proto + ": can't set as prototype!");
	};
	module.exports = {
	  set: Object.setPrototypeOf || ('__proto__' in {} ? // eslint-disable-line
	    function(test, buggy, set){
	      try {
	        set = __webpack_require__(65)(Function.call, __webpack_require__(174).f(Object.prototype, '__proto__').set, 2);
	        set(test, []);
	        buggy = !(test instanceof Array);
	      } catch(e){ buggy = true; }
	      return function setPrototypeOf(O, proto){
	        check(O, proto);
	        if(buggy)O.__proto__ = proto;
	        else set(O, proto);
	        return O;
	      };
	    }({}, false) : undefined),
	  check: check
	};

/***/ },
/* 183 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = { "default": __webpack_require__(184), __esModule: true };

/***/ },
/* 184 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(185);
	var $Object = __webpack_require__(64).Object;
	module.exports = function create(P, D){
	  return $Object.create(P, D);
	};

/***/ },
/* 185 */
/***/ function(module, exports, __webpack_require__) {

	var $export = __webpack_require__(62)
	// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
	$export($export.S, 'Object', {create: __webpack_require__(154)});

/***/ }
/******/ ]);