/*! gugur-pro - v2.7.3 - 28-10-2019 */
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 14);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ElementEditorModule = __webpack_require__(5);

module.exports = ElementEditorModule.extend({

	__construct: function __construct() {
		this.cache = {};
		ElementEditorModule.prototype.__construct.apply(this, arguments);
	},

	getName: function getName() {
		return '';
	},

	getCacheKey: function getCacheKey(args) {
		return JSON.stringify({
			service: this.getName(),
			data: args
		});
	},

	fetchCache: function fetchCache(type, cacheKey, requestArgs) {
		var _this = this;

		return gugurPro.ajax.addRequest('forms_panel_action_data', {
			unique_id: 'integrations_' + this.getName(),
			data: requestArgs,
			success: function success(data) {
				_this.cache[type] = _.extend({}, _this.cache[type]);
				_this.cache[type][cacheKey] = data[type];
			}
		});
	},

	updateOptions: function updateOptions(name, options) {
		var controlView = this.getEditorControlView(name);

		if (controlView) {
			this.getEditorControlModel(name).set('options', options);

			controlView.render();
		}
	},

	onInit: function onInit() {
		this.addSectionListener('section_' + this.getName(), this.onSectionActive);
	},

	onSectionActive: function onSectionActive() {
		this.onApiUpdate();
	},

	onApiUpdate: function onApiUpdate() {}
});

/***/ }),
/* 2 */,
/* 3 */,
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$edi) {
	_inherits(_class, _gugurModules$edi);

	function _class() {
		var _ref;

		_classCallCheck(this, _class);

		for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
			args[_key] = arguments[_key];
		}

		var _this = _possibleConstructorReturn(this, (_ref = _class.__proto__ || Object.getPrototypeOf(_class)).call.apply(_ref, [this].concat(args)));

		_this.template = _.noop;

		_this.activeTab = 'content';

		_this.listenTo(_this.model, 'change', _this.onModelChange);
		return _this;
	}

	_createClass(_class, [{
		key: 'getNamespaceArray',
		value: function getNamespaceArray() {
			return ['popup', 'display-settings'];
		}
	}, {
		key: 'className',
		value: function className() {
			return _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'className', this).call(this) + ' gugur-popup__display-settings';
		}
	}, {
		key: 'toggleGroup',
		value: function toggleGroup(groupName, $groupElement) {
			$groupElement.toggleClass('gugur-active', !!this.model.get(groupName));
		}
	}, {
		key: 'onRenderTemplate',
		value: function onRenderTemplate() {
			this.activateFirstSection();
		}
	}, {
		key: 'onRender',
		value: function onRender() {
			var _this2 = this;

			var name = this.getOption('name');

			var $groupWrapper = void 0;

			this.children.each(function (child) {
				var type = child.model.get('type');

				if ('heading' !== type) {
					if ($groupWrapper) {
						$groupWrapper.append(child.$el);
					}

					return;
				}

				var groupName = child.model.get('name').replace('_heading', '');

				$groupWrapper = jQuery('<div>', {
					id: 'gugur-popup__' + name + '-controls-group--' + groupName,
					class: 'gugur-popup__display-settings_controls_group'
				});

				var $imageWrapper = jQuery('<div>', { class: 'gugur-popup__display-settings_controls_group__icon' }),
				    $image = jQuery('<img>', { src: gugurPro.config.urls.modules + ('popup/assets/images/' + name + '/' + groupName + '.svg') });

				$imageWrapper.html($image);

				$groupWrapper.html($imageWrapper);

				child.$el.before($groupWrapper);

				$groupWrapper.append(child.$el);

				_this2.toggleGroup(groupName, $groupWrapper);
			});
		}
	}, {
		key: 'onModelChange',
		value: function onModelChange() {
			var changedControlName = Object.keys(this.model.changed)[0],
			    changedControlView = this.getControlViewByName(changedControlName);

			if ('switcher' !== changedControlView.model.get('type')) {
				return;
			}

			this.toggleGroup(changedControlName, changedControlView.$el.parent());
		}
	}]);

	return _class;
}(gugurModules.editor.views.ControlsStack);

exports.default = _class;

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	elementType: null,

	__construct: function __construct(elementType) {
		this.elementType = elementType;

		this.addEditorListener();
	},

	addEditorListener: function addEditorListener() {
		var self = this;

		if (self.onElementChange) {
			var eventName = 'change';

			if ('global' !== self.elementType) {
				eventName += ':' + self.elementType;
			}

			gugur.channels.editor.on(eventName, function (controlView, elementView) {
				self.onElementChange(controlView.model.get('name'), controlView, elementView);
			});
		}
	},

	addControlSpinner: function addControlSpinner(name) {
		var $el = this.getEditorControlView(name).$el,
		    $input = $el.find(':input');

		if ($input.attr('disabled')) {
			return;
		}

		$input.attr('disabled', true);

		$el.find('.gugur-control-title').after('<span class="gugur-control-spinner"><i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
	},

	removeControlSpinner: function removeControlSpinner(name) {
		var $controlEl = this.getEditorControlView(name).$el;

		$controlEl.find(':input').attr('disabled', false);
		$controlEl.find('.gugur-control-spinner').remove();
	},

	addSectionListener: function addSectionListener(section, callback) {
		var self = this;

		gugur.channels.editor.on('section:activated', function (sectionName, editor) {
			var model = editor.getOption('editedElementView').getEditModel(),
			    currentElementType = model.get('elType'),
			    _arguments = arguments;

			if ('widget' === currentElementType) {
				currentElementType = model.get('widgetType');
			}

			if (self.elementType === currentElementType && section === sectionName) {
				setTimeout(function () {
					callback.apply(self, _arguments);
				}, 10);
			}
		});
	}
});

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var inlineControlsStack = __webpack_require__(61);

module.exports = inlineControlsStack.extend({
	id: 'gugur-theme-builder-conditions-view',

	template: '#tmpl-gugur-theme-builder-conditions-view',

	childViewContainer: '#gugur-theme-builder-conditions-controls',

	childViewOptions: function childViewOptions() {
		return {
			elementSettingsModel: this.model
		};
	}
});

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _repeaterRow = __webpack_require__(62);

var _repeaterRow2 = _interopRequireDefault(_repeaterRow);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

module.exports = gugur.modules.controls.Repeater.extend({

	childView: _repeaterRow2.default,

	updateActiveRow: function updateActiveRow() {},

	initialize: function initialize() {
		gugur.modules.controls.Repeater.prototype.initialize.apply(this, arguments);

		this.config = gugurPro.config.theme_builder;

		this.updateConditionsOptions(this.config.settings.template_type);
	},

	checkConflicts: function checkConflicts(model) {
		var modelId = model.get('_id'),
		    rowId = 'gugur-condition-id-' + modelId,
		    errorMessageId = 'gugur-conditions-conflict-message-' + modelId,
		    $error = jQuery('#' + errorMessageId);

		// On render - the row isn't exist, so don't cache it.
		jQuery('#' + rowId).removeClass('gugur-error');

		$error.remove();

		gugurPro.ajax.addRequest('theme_builder_conditions_check_conflicts', {
			unique_id: rowId,
			data: {
				condition: model.toJSON({ remove: ['default'] })
			},
			success: function success(data) {
				if (!_.isEmpty(data)) {
					jQuery('#' + rowId).addClass('gugur-error').after('<div id="' + errorMessageId + '" class="gugur-conditions-conflict-message">' + data + '</div>');
				}
			}
		});
	},

	updateConditionsOptions: function updateConditionsOptions(templateType) {
		var self = this,
		    conditionType = self.config.types[templateType].condition_type,
		    options = {};

		_([conditionType]).each(function (conditionId, conditionIndex) {
			var conditionConfig = self.config.conditions[conditionId],
			    group = {
				label: conditionConfig.label,
				options: {}
			};

			group.options[conditionId] = conditionConfig.all_label;

			_(conditionConfig.sub_conditions).each(function (subConditionId) {
				group.options[subConditionId] = self.config.conditions[subConditionId].label;
			});

			options[conditionIndex] = group;
		});

		var fields = this.model.get('fields');

		fields[1].default = conditionType;

		if ('general' === conditionType) {
			fields[1].groups = options;
		} else {
			fields[2].groups = options;
		}
	},

	onRender: function onRender() {
		this.ui.btnAddRow.text(gugurPro.translate('add_condition'));

		var self = this;

		this.collection.each(function (model) {
			self.checkConflicts(model);
		});
	},

	// Overwrite the original + checkConflicts.
	onRowControlChange: function onRowControlChange(model) {
		this.checkConflicts(model);
	}
});

/***/ }),
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _editor = __webpack_require__(15);

var _editor2 = _interopRequireDefault(_editor);

var _editor3 = __webpack_require__(16);

var _editor4 = _interopRequireDefault(_editor3);

var _editor5 = __webpack_require__(17);

var _editor6 = _interopRequireDefault(_editor5);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var gugurPro = Marionette.Application.extend({
	config: {},

	modules: {},

	initModules: function initModules() {
		var QueryControl = __webpack_require__(19),
		    Forms = __webpack_require__(21),
		    Library = __webpack_require__(37),
		    GlobalWidget = __webpack_require__(39),
		    FlipBox = __webpack_require__(51),
		    ShareButtons = __webpack_require__(52),
		    AssetsManager = __webpack_require__(53),
		    ThemeElements = __webpack_require__(55),
		    ThemeBuilder = __webpack_require__(57);

		this.modules = {
			queryControl: new QueryControl(),
			forms: new Forms(),
			library: new Library(),
			customCSS: new _editor2.default(),
			globalWidget: new GlobalWidget(),
			flipBox: new FlipBox(),
			motionFX: new _editor4.default(),
			popup: new _editor6.default(),
			shareButtons: new ShareButtons(),
			assetsManager: new AssetsManager(),
			themeElements: new ThemeElements(),
			themeBuilder: new ThemeBuilder()
		};
	},

	ajax: {
		prepareArgs: function prepareArgs(args) {
			args[0] = 'pro_' + args[0];

			return args;
		},

		send: function send() {
			return gugurCommon.ajax.send.apply(gugurCommon.ajax, this.prepareArgs(arguments));
		},

		addRequest: function addRequest() {
			return gugurCommon.ajax.addRequest.apply(gugurCommon.ajax, this.prepareArgs(arguments));
		}
	},

	translate: function translate(stringKey, templateArgs) {
		return gugurCommon.translate(stringKey, null, templateArgs, this.config.i18n);
	},

	onStart: function onStart() {
		this.config = gugurProEditorConfig;

		this.initModules();

		jQuery(window).on('gugur:init', this.ongugurInit);
	},

	ongugurInit: function ongugurInit() {
		gugurPro.libraryRemoveGetProButtons();

		gugur.debug.addURLToWatch('gugur-pro/assets');
	},

	libraryRemoveGetProButtons: function libraryRemoveGetProButtons() {
		gugur.hooks.addFilter('gugur/editor/template-library/template/action-button', function (viewID, templateData) {
			return templateData.isPro && !gugurPro.config.isActive ? '#tmpl-gugur-pro-template-library-activate-license-button' : '#tmpl-gugur-template-library-insert-button';
		});
	}
});

window.gugurPro = new gugurPro();

gugurPro.start();

/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$edi) {
	_inherits(_class, _gugurModules$edi);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'addPageCustomCss',
		value: function addPageCustomCss() {
			var customCSS = gugur.settings.page.model.get('custom_css');

			if (customCSS) {
				customCSS = customCSS.replace(/selector/g, gugur.config.settings.page.cssWrapperSelector);

				gugur.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
			}
		}
	}, {
		key: 'addCustomCss',
		value: function addCustomCss(css, view) {
			var model = view.getEditModel(),
			    customCSS = model.get('settings').get('custom_css');

			if (customCSS) {
				css += customCSS.replace(/selector/g, '.gugur-element.gugur-element-' + view.model.id);
			}

			return css;
		}
	}, {
		key: 'ongugurInit',
		value: function ongugurInit() {
			gugur.hooks.addFilter('editor/style/styleText', this.addCustomCss);

			gugur.settings.page.model.on('change', this.addPageCustomCss);

			gugur.on('navigator:init', this.onNavigatorInit.bind(this));
		}
	}, {
		key: 'onNavigatorInit',
		value: function onNavigatorInit() {
			gugur.navigator.indicators.customCSS = {
				icon: 'code-bold',
				settingKeys: ['custom_css'],
				title: gugurPro.translate('custom_css'),
				section: 'section_custom_css'
			};
		}
	}, {
		key: 'ongugurPreviewLoaded',
		value: function ongugurPreviewLoaded() {
			this.addPageCustomCss();
		}
	}]);

	return _class;
}(gugurModules.editor.utils.Module);

exports.default = _class;

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$edi) {
	_inherits(_class, _gugurModules$edi);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'ongugurInit',
		value: function ongugurInit() {
			gugur.on('navigator:init', this.onNavigatorInit.bind(this));
		}
	}, {
		key: 'onNavigatorInit',
		value: function onNavigatorInit() {
			gugur.navigator.indicators.motionFX = {
				icon: 'flash',
				title: gugurPro.translate('motion_effects'),
				settingKeys: ['motion_fx_motion_fx_scrolling', 'motion_fx_motion_fx_mouse', 'background_motion_fx_motion_fx_scrolling', 'background_motion_fx_motion_fx_mouse'],
				section: 'section_effects'
			};
		}
	}]);

	return _class;
}(gugurModules.editor.utils.Module);

exports.default = _class;

/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

if (!gugurProEditorConfig.useComponentsRouter) {
	module.exports = __webpack_require__(18);
} else {
	var DisplaySettingsControlsStack = __webpack_require__(4);

	var Module = function (_gugurModules$edi) {
		_inherits(Module, _gugurModules$edi);

		function Module() {
			var _ref;

			_classCallCheck(this, Module);

			for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
				args[_key] = arguments[_key];
			}

			var _this = _possibleConstructorReturn(this, (_ref = Module.__proto__ || Object.getPrototypeOf(Module)).call.apply(_ref, [this].concat(args)));

			_this.displaySettingsTypes = {
				triggers: {
					icon: 'eicon-click'
				},
				timing: {
					icon: 'eicon-cog'
				}
			};
			return _this;
		}

		_createClass(Module, [{
			key: 'addPublishTabs',
			value: function addPublishTabs() {
				var config = gugur.config.document.displaySettings,
				    component = $e.components.get('theme-builder-publish');

				jQuery.each(this.displaySettingsTypes, function (type, data) {
					// Init models for editor save.
					data.model = new gugurModules.editor.elements.models.BaseSettings(config[type].settings, { controls: config[type].controls });

					component.addTab(type, {
						// Todo: use import instead of require.
						View: DisplaySettingsControlsStack.default,
						viewOptions: {
							name: type,
							id: 'gugur-popup-' + type + '__controls',
							model: data.model,
							controls: data.model.controls
						},
						name: type,
						title: gugurPro.translate(type),
						description: gugurPro.translate('popup_publish_screen_' + type + '_description'),
						image: gugurPro.config.urls.modules + ('popup/assets/images/' + type + '-tab.svg')
					});
				});
			}
		}, {
			key: 'addPanelFooterSubmenuItems',
			value: function addPanelFooterSubmenuItems() {
				var component = $e.components.get('theme-builder-publish');
				jQuery.each(this.displaySettingsTypes, function (type, data) {
					gugur.getPanelView().footer.currentView.addSubMenuItem('saver-options', {
						before: 'save-template',
						name: type,
						icon: data.icon,
						title: gugurPro.translate(type),
						callback: function callback() {
							return $e.route(component.getTabRoute(type));
						}
					});
				});
			}
		}, {
			key: 'addLibraryTab',
			value: function addLibraryTab() {
				var library = $e.components.get('library');

				library.removeTab('templates/pages');

				library.addTab('templates/popups', {
					title: gugurPro.translate('popups'),
					filter: {
						source: 'remote',
						type: 'popup'
					}
				}, 1);

				library.setDefaultRoute('templates/popups');
			}
		}, {
			key: 'initIntroduction',
			value: function initIntroduction() {
				var introduction = void 0;

				this.getIntroduction = function () {
					if (!introduction) {
						introduction = new gugurModules.editor.utils.Introduction({
							introductionKey: 'popupSettings',
							dialogOptions: {
								id: 'gugur-popup-settings-introduction',
								headerMessage: '<i class="eicon-info"></i>' + gugurPro.translate('popup_settings_introduction_title'),
								message: gugurPro.translate('popup_settings_introduction_message'),
								closeButton: true,
								closeButtonClass: 'eicon-close',
								position: {
									my: 'left bottom',
									at: 'right bottom-5',
									autoRefresh: true
								},
								hide: {
									onOutsideClick: false
								}
							}
						});
					}

					return introduction;
				};
			}
		}, {
			key: 'ongugurInit',
			value: function ongugurInit() {
				var _this2 = this;

				if ('popup' !== gugur.config.document.type) {
					return;
				}

				gugur.on('panel:init', function () {
					return _this2.ongugurPanelInit();
				});

				gugur.saver.on('save', function () {
					return _this2.onEditorSave();
				});

				this.addLibraryTab();
			}
		}, {
			key: 'ongugurPanelInit',
			value: function ongugurPanelInit() {
				this.addPublishTabs();

				this.addPanelFooterSubmenuItems();

				if (!gugur.config.user.introduction.popupSettings) {
					this.initIntroduction();
				}
			}
		}, {
			key: 'ongugurPreviewLoaded',
			value: function ongugurPreviewLoaded() {
				if ('popup' !== gugur.config.document.type) {
					return;
				}

				$e.route('panel/page-settings/settings');

				if (!gugur.config.user.introduction.popupSettings) {
					gugur.getPanelView().getCurrentPageView().on('destroy', this.onPageSettingsDestroy.bind(this));
				}
			}
		}, {
			key: 'onPageSettingsDestroy',
			value: function onPageSettingsDestroy() {
				var introduction = this.getIntroduction();

				introduction.show(gugur.getPanelView().footer.currentView.ui.settings[0]);

				introduction.setViewed();
			}
		}, {
			key: 'onEditorSave',
			value: function onEditorSave() {
				var settings = {};

				jQuery.each(this.displaySettingsTypes, function (type, data) {
					settings[type] = data.model.toJSON({ remove: ['default'] });
				});

				gugurPro.ajax.addRequest('popup_save_display_settings', {
					data: {
						settings: settings
					}
				});
			}
		}]);

		return Module;
	}(gugurModules.editor.utils.Module);

	module.exports = Module;
}

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _displaySettings = __webpack_require__(4);

var _displaySettings2 = _interopRequireDefault(_displaySettings);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$edi) {
	_inherits(_class, _gugurModules$edi);

	function _class() {
		var _ref;

		_classCallCheck(this, _class);

		for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
			args[_key] = arguments[_key];
		}

		var _this = _possibleConstructorReturn(this, (_ref = _class.__proto__ || Object.getPrototypeOf(_class)).call.apply(_ref, [this].concat(args)));

		_this.displaySettingsTypes = {
			triggers: {
				icon: 'eicon-click'
			},
			timing: {
				icon: 'eicon-cog'
			}
		};
		return _this;
	}

	_createClass(_class, [{
		key: 'initDisplaySettingsModels',
		value: function initDisplaySettingsModels() {
			var config = gugur.config.document.displaySettings;

			jQuery.each(this.displaySettingsTypes, function (type, data) {
				data.model = new gugurModules.editor.elements.models.BaseSettings(config[type].settings, { controls: config[type].controls });
			});
		}
	}, {
		key: 'addDisplaySettingsPublishScreens',
		value: function addDisplaySettingsPublishScreens(publishLayout) {
			jQuery.each(this.displaySettingsTypes, function (type, data) {
				var model = data.model;

				publishLayout.addScreen({
					View: _displaySettings2.default,
					viewOptions: {
						name: type,
						id: 'gugur-popup-' + type + '__controls',
						model: model,
						controls: model.controls
					},
					name: type,
					title: gugurPro.translate(type),
					description: gugurPro.translate('popup_publish_screen_' + type + '_description'),
					image: gugurPro.config.urls.modules + ('popup/assets/images/' + type + '-tab.svg')
				});
			});
		}
	}, {
		key: 'addPanelFooterSubmenuItems',
		value: function addPanelFooterSubmenuItems() {
			var _this2 = this;

			jQuery.each(this.displaySettingsTypes, function (type, data) {
				gugur.getPanelView().footer.currentView.addSubMenuItem('saver-options', {
					before: 'save-template',
					name: type,
					icon: data.icon,
					title: gugurPro.translate(type),
					callback: function callback() {
						return _this2.onPanelFooterSubmenuItemClick(type);
					}
				});
			});
		}
	}, {
		key: 'addLibraryScreen',
		value: function addLibraryScreen() {
			var screens = gugur.templates.getScreens(),
			    pagesIndex = screens.indexOf(_.findWhere(screens, { type: 'pages' }));

			screens.splice(pagesIndex - 1, 1, {
				name: 'popups',
				source: 'remote',
				type: 'popup',
				title: gugurPro.translate('popups')
			});

			gugur.templates.setDefaultScreen('popups');
		}
	}, {
		key: 'initIntroduction',
		value: function initIntroduction() {
			var introduction = void 0;

			this.getIntroduction = function () {
				if (!introduction) {
					introduction = new gugurModules.editor.utils.Introduction({
						introductionKey: 'popupSettings',
						dialogOptions: {
							id: 'gugur-popup-settings-introduction',
							headerMessage: '<i class="eicon-info"></i>' + gugurPro.translate('popup_settings_introduction_title'),
							message: gugurPro.translate('popup_settings_introduction_message'),
							closeButton: true,
							closeButtonClass: 'eicon-close',
							position: {
								my: 'left bottom',
								at: 'right bottom-5',
								autoRefresh: true
							},
							hide: {
								onOutsideClick: false
							}
						}
					});
				}

				return introduction;
			};
		}
	}, {
		key: 'ongugurInit',
		value: function ongugurInit() {
			var _this3 = this;

			if ('popup' !== gugur.config.document.type) {
				return;
			}

			gugur.on('panel:init', function () {
				return _this3.ongugurPanelInit();
			});

			gugur.saver.on('save', function () {
				return _this3.onEditorSave();
			});

			this.addLibraryScreen();
		}
	}, {
		key: 'ongugurPanelInit',
		value: function ongugurPanelInit() {
			this.initDisplaySettingsModels();

			gugurPro.modules.themeBuilder.on('publish-layout:init', this.addDisplaySettingsPublishScreens.bind(this));

			this.addPanelFooterSubmenuItems();

			if (!gugur.config.user.introduction.popupSettings) {
				this.initIntroduction();
			}
		}
	}, {
		key: 'ongugurPreviewLoaded',
		value: function ongugurPreviewLoaded() {
			if ('popup' !== gugur.config.document.type) {
				return;
			}

			var panel = gugur.getPanelView();

			panel.footer.currentView.showSettingsPage('page_settings');

			if (!gugur.config.user.introduction.popupSettings) {
				panel.getCurrentPageView().on('destroy', this.onPageSettingsDestroy.bind(this));
			}
		}
	}, {
		key: 'onPageSettingsDestroy',
		value: function onPageSettingsDestroy() {
			var introduction = this.getIntroduction();

			introduction.show(gugur.getPanelView().footer.currentView.ui.settings[0]);

			introduction.setViewed();
		}
	}, {
		key: 'onEditorSave',
		value: function onEditorSave() {
			var settings = {};

			jQuery.each(this.displaySettingsTypes, function (type, data) {
				settings[type] = data.model.toJSON({ remove: ['default'] });
			});

			gugurPro.ajax.addRequest('popup_save_display_settings', {
				data: {
					settings: settings
				}
			});
		}
	}, {
		key: 'onPanelFooterSubmenuItemClick',
		value: function onPanelFooterSubmenuItemClick(type) {
			gugurPro.modules.themeBuilder.showPublishModal();

			gugurPro.modules.themeBuilder.getPublishLayout().modalContent.currentView.showScreenByName(type);
		}
	}]);

	return _class;
}(gugurModules.editor.utils.Module);

exports.default = _class;

/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	ongugurPreviewLoaded: function ongugurPreviewLoaded() {
		gugur.addControlView('Query', __webpack_require__(20));
	}
});

/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.controls.Select2.extend({

	cache: null,

	isTitlesReceived: false,

	getSelect2Placeholder: function getSelect2Placeholder() {
		return {
			id: '',
			text: gugurPro.translate('all')
		};
	},

	getControlValueByName: function getControlValueByName(controlName) {
		var name = this.model.get('group_prefix') + controlName;
		return this.elementSettingsModel.attributes[name];
	},

	getQueryDataDeprecated: function getQueryDataDeprecated() {
		return {
			filter_type: this.model.get('filter_type'),
			object_type: this.model.get('object_type'),
			include_type: this.model.get('include_type'),
			query: this.model.get('query')
		};
	},


	getQueryData: function getQueryData() {
		// Use a clone to keep model data unchanged:
		var autocomplete = gugurCommon.helpers.cloneObject(this.model.get('autocomplete'));

		if (_.isEmpty(autocomplete.query)) {
			autocomplete.query = {};
		}
		// Specific for Group_Control_Query
		if ('cpt_tax' === autocomplete.object) {
			autocomplete.object = 'tax';
			if (_.isEmpty(autocomplete.query) || _.isEmpty(autocomplete.query.post_type)) {
				autocomplete.query.post_type = this.getControlValueByName('post_type');
			}
		}
		return {
			autocomplete: autocomplete
		};
	},

	getSelect2DefaultOptions: function getSelect2DefaultOptions() {
		var self = this;

		return jQuery.extend(gugur.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
			ajax: {
				transport: function transport(params, success, failure) {
					var bcFormat = !_.isEmpty(self.model.get('filter_type'));

					var data = {},
					    action = 'panel_posts_control_filter_autocomplete';

					if (bcFormat) {
						data = self.getQueryDataDeprecated();
						action = 'panel_posts_control_filter_autocomplete_deprecated';
					} else {
						data = self.getQueryData();
					}

					data.q = params.data.q;
					return gugurPro.ajax.addRequest(action, {
						data: data,
						success: success,
						error: failure
					});
				},
				data: function data(params) {
					return {
						q: params.term,
						page: params.page
					};
				},
				cache: true
			},
			escapeMarkup: function escapeMarkup(markup) {
				return markup;
			},
			minimumInputLength: 1
		});
	},

	getValueTitles: function getValueTitles() {
		var self = this,
		    data = {},
		    bcFormat = !_.isEmpty(this.model.get('filter_type'));

		var ids = this.getControlValue(),
		    action = 'query_control_value_titles',
		    filterTypeName = 'autocomplete',
		    filterType = {};

		if (bcFormat) {
			filterTypeName = 'filter_type';
			filterType = this.model.get(filterTypeName).object;
			data.filter_type = filterType;
			data.object_type = self.model.get('object_type');
			data.include_type = self.model.get('include_type');
			data.unique_id = '' + self.cid + filterType;
			action = 'query_control_value_titles_deprecated';
		} else {
			filterType = this.model.get(filterTypeName).object;
			data.get_titles = self.getQueryData().autocomplete;
			data.unique_id = '' + self.cid + filterType;
		}

		if (!ids || !filterType) {
			return;
		}

		if (!_.isArray(ids)) {
			ids = [ids];
		}

		gugurCommon.ajax.loadObjects({
			action: action,
			ids: ids,
			data: data,
			before: function before() {
				self.addControlSpinner();
			},
			success: function success(ajaxData) {
				self.isTitlesReceived = true;

				self.model.set('options', ajaxData);

				self.render();
			}
		});
	},

	addControlSpinner: function addControlSpinner() {
		this.ui.select.prop('disabled', true);
		this.$el.find('.gugur-control-title').after('<span class="gugur-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
	},

	onReady: function onReady() {
		// Safari takes it's time to get the original select width
		setTimeout(gugur.modules.controls.Select2.prototype.onReady.bind(this));

		if (!this.isTitlesReceived) {
			this.getValueTitles();
		}
	}
});

/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	ongugurInit: function ongugurInit() {
		var ReplyToField = __webpack_require__(22),
		    Recaptcha = __webpack_require__(23),
		    Shortcode = __webpack_require__(24),
		    MailerLite = __webpack_require__(25),
		    Mailchimp = __webpack_require__(26),
		    Drip = __webpack_require__(27),
		    ActiveCampaign = __webpack_require__(28),
		    GetResponse = __webpack_require__(29),
		    ConvertKit = __webpack_require__(30);

		this.replyToField = new ReplyToField();
		this.mailchimp = new Mailchimp('form');
		this.shortcode = new Shortcode('form');
		this.recaptcha = new Recaptcha('form');
		this.drip = new Drip('form');
		this.activecampaign = new ActiveCampaign('form');
		this.getresponse = new GetResponse('form');
		this.convertkit = new ConvertKit('form');
		this.mailerlite = new MailerLite('form');

		// Form fields
		var TimeField = __webpack_require__(31),
		    DateField = __webpack_require__(32),
		    AcceptanceField = __webpack_require__(33),
		    UploadField = __webpack_require__(34),
		    TelField = __webpack_require__(35);

		this.Fields = {
			time: new TimeField('form'),
			date: new DateField('form'),
			tel: new TelField('form'),
			acceptance: new AcceptanceField('form'),
			upload: new UploadField('form')
		};

		gugur.addControlView('Fields_map', __webpack_require__(36));
	}
});

/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function () {
	var editor, editedModel, replyToControl;

	var setReplyToControl = function setReplyToControl() {
		replyToControl = editor.collection.findWhere({ name: 'email_reply_to' });
	};

	var getReplyToView = function getReplyToView() {
		return editor.children.findByModelCid(replyToControl.cid);
	};

	var refreshReplyToElement = function refreshReplyToElement() {
		var replyToView = getReplyToView();

		if (replyToView) {
			replyToView.render();
		}
	};

	var updateReplyToOptions = function updateReplyToOptions() {
		var settingsModel = editedModel.get('settings'),
		    emailModels = settingsModel.get('form_fields').where({ field_type: 'email' }),
		    emailFields;

		emailModels = _.reject(emailModels, { field_label: '' });

		emailFields = _.map(emailModels, function (model) {
			return {
				id: model.get('custom_id'),
				label: gugurPro.translate('x_field', [model.get('field_label')])
			};
		});

		replyToControl.set('options', { '': replyToControl.get('options')[''] });

		_.each(emailFields, function (emailField) {
			replyToControl.get('options')[emailField.id] = emailField.label;
		});

		refreshReplyToElement();
	};

	var updateDefaultReplyTo = function updateDefaultReplyTo(settingsModel) {
		replyToControl.get('options')[''] = settingsModel.get('email_from');

		refreshReplyToElement();
	};

	var onFormFieldsChange = function onFormFieldsChange(changedModel) {
		// If it's repeater field
		if (changedModel.get('custom_id')) {
			if ('email' === changedModel.get('field_type')) {
				updateReplyToOptions();
			}
		}

		if (changedModel.changed.email_from) {
			updateDefaultReplyTo(changedModel);
		}
	};

	var onPanelShow = function onPanelShow(panel, model) {
		editor = panel.getCurrentPageView();

		editedModel = model;

		setReplyToControl();

		var settingsModel = editedModel.get('settings');

		settingsModel.on('change', onFormFieldsChange);

		updateDefaultReplyTo(settingsModel);

		updateReplyToOptions();
	};

	var init = function init() {
		gugur.hooks.addAction('panel/open_editor/widget/form', onPanelShow);
	};

	init();
};

/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	enqueueRecaptchaJs: function enqueueRecaptchaJs(url, type) {
		if (!gugurFrontend.elements.$body.find('[src="' + url + '"]').length) {
			gugurFrontend.elements.$body.append('<scr' + 'ipt src="' + url + '" id="recaptcha-' + type + '"</scri' + 'pt>');
		}
	},
	renderField: function renderField(inputField, item) {
		inputField += '<div class="gugur-field ' + item.field_type + ' ">';
		inputField += this.getDataSettings(item);
		inputField += '</div>';

		return inputField;
	},
	getDataSettings: function getDataSettings(item) {
		var config = gugurPro.config.forms[item.field_type],
		    srcURL = 'https://www.google.com/recaptcha/api.js?render=explicit';

		if (!config.enabled) {
			return '<div class="gugur-alert gugur-alert-info">' + config.setup_message + '</div>';
		}

		var recaptchaData = 'data-sitekey="' + config.site_key + '" data-type="' + config.type + '"';

		switch (config.type) {
			case 'v3':
				recaptchaData += ' data-action="form" data-size="invisible" data-badge="' + item.recaptcha_badge + '"';
				break;
			case 'v2_checkbox':
				recaptchaData += ' data-theme="' + item.recaptcha_style + '"';
				recaptchaData += ' data-size="' + item.recaptcha_size + '"';
				break;
		}
		this.enqueueRecaptchaJs(srcURL, config.type);

		return '<div class="gugur-g-recaptcha' + _.escape(item.css_classes) + '" ' + recaptchaData + '></div>';
	},


	filterItem: function filterItem(item) {
		if ('recaptcha' === item.field_type) {
			item.field_label = false;
		}

		return item;
	},

	onInit: function onInit() {
		gugur.hooks.addFilter('gugur_pro/forms/content_template/item', this.filterItem);
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/recaptcha', this.renderField, 10, 2);
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/recaptcha_v3', this.renderField, 10, 2);
	}
});

/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ElementEditorModule = __webpack_require__(5);

module.exports = ElementEditorModule.extend({
	lastRemovedModelId: false,
	collectionEventsAttached: false,

	formFieldEvents: {
		ADD: 'add',
		SORT: 'sort',
		DUPLICATE: 'duplicate',
		CHANGE: 'change'
	},

	getExistId: function getExistId(id) {
		var exist = this.getEditorControlView('form_fields').collection.filter(function (model) {
			return id === model.get('custom_id');
		});

		return exist.length > 1;
	},
	getFormFieldsView: function getFormFieldsView() {
		return this.getEditorControlView('form_fields');
	},
	onFieldUpdate: function onFieldUpdate(collection, update) {
		if (!update.add) {
			return;
		}
		var event = this.formFieldEvents.ADD;
		var addedModel = update.changes.added[0];
		if (update.at) {
			if (this.lastRemovedModelId && addedModel.attributes.custom_id === this.lastRemovedModelId) {
				event = this.formFieldEvents.SORT;
			} else {
				event = this.formFieldEvents.DUPLICATE;
			}
			this.lastRemovedModelId = false;
		}
		this.updateIdAndShortcode(addedModel, event);
	},
	onFieldChanged: function onFieldChanged(model) {
		if (!_.isUndefined(model.changed.custom_id)) {
			this.updateIdAndShortcode(model, this.formFieldEvents.CHANGE);
		}
	},
	onFieldRemoved: function onFieldRemoved(model) {
		this.lastRemovedModelId = model.attributes.custom_id;
		this.getFormFieldsView().children.each(this.updateShortcode);
	},
	updateIdAndShortcode: function updateIdAndShortcode(model, event) {
		var _this = this;

		var view = this.getFormFieldsView().children.findByModel(model);

		_.defer(function () {
			_this.updateId(view, event);
			_this.updateShortcode(view);
		});
	},
	getFieldId: function getFieldId(model, event) {
		if (event === this.formFieldEvents.ADD || event === this.formFieldEvents.DUPLICATE) {
			return model.get('_id');
		}
		var customId = model.get('custom_id');
		return customId ? customId : model.get('_id');
	},
	updateId: function updateId(view, event) {
		var id = this.getFieldId(view.model, event),
		    sanitizedId = id.replace(/[^\w]/, '_'),
		    fieldIndex = 1,
		    isNew = event === this.formFieldEvents.ADD || event === this.formFieldEvents.DUPLICATE;
		var IdView = view.children.filter(function (childrenView) {
			return 'custom_id' === childrenView.model.get('name');
		});

		while (sanitizedId !== id || this.getExistId(id) || isNew) {
			if (sanitizedId !== id) {
				id = sanitizedId;
			} else if (isNew || this.getExistId(id)) {
				id = 'field_' + fieldIndex;
				sanitizedId = id;
			}

			view.model.attributes.custom_id = id;
			IdView[0].render();
			IdView[0].$el.find('input').focus();
			fieldIndex++;
			isNew = false;
		}
	},
	updateShortcode: function updateShortcode(view) {
		var template = _.template('[field id="<%= id %>"]')({
			title: view.model.get('field_label'),
			id: view.model.get('custom_id')
		});

		view.$el.find('.gugur-form-field-shortcode').focus(function () {
			this.select();
		}).val(template);
	},
	onSectionActive: function onSectionActive() {
		var controlView = this.getEditorControlView('form_fields');

		controlView.children.each(this.updateShortcode);

		if (!controlView.collection.shortcodeEventsAttached) {
			controlView.collection.on('change', this.onFieldChanged).on('update', this.onFieldUpdate).on('remove', this.onFieldRemoved);
			controlView.collection.shortcodeEventsAttached = true;
		}
	},
	onInit: function onInit() {
		this.addSectionListener('section_form_fields', this.onSectionActive);
	}
});

/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BaseIntegrationModule = __webpack_require__(1);

module.exports = BaseIntegrationModule.extend({
	fields: {},

	getName: function getName() {
		return 'mailerlite';
	},

	onElementChange: function onElementChange(setting) {
		switch (setting) {
			case 'mailerlite_api_key_source':
			case 'mailerlite_custom_api_key':
				this.onMailerliteApiKeyUpdate();
				break;
			case 'mailerlite_group':
				this.updateFieldsMapping();
				break;
		}
	},

	onMailerliteApiKeyUpdate: function onMailerliteApiKeyUpdate() {
		var self = this,
		    controlView = self.getEditorControlView('mailerlite_custom_api_key'),
		    GlobalApiKeycontrolView = self.getEditorControlView('mailerlite_api_key_source');

		if ('default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue()) {
			self.updateOptions('mailerlite_group', []);
			self.getEditorControlView('mailerlite_group').setValue('');
			return;
		}

		self.addControlSpinner('mailerlite_group');

		var cacheKey = this.getCacheKey({
			type: 'groups',
			controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
		});

		self.getMailerliteCache('groups', 'groups', cacheKey).done(function (data) {
			self.updateOptions('mailerlite_group', data.groups);
			self.fields = data.fields;
		});
	},

	updateFieldsMapping: function updateFieldsMapping() {
		var controlView = this.getEditorControlView('mailerlite_group');

		if (!controlView.getControlValue()) {
			return;
		}

		var remoteFields = [{
			remote_label: gugur.translate('Email'),
			remote_type: 'email',
			remote_id: 'email',
			remote_required: true
		}, {
			remote_label: gugur.translate('Name'),
			remote_type: 'text',
			remote_id: 'name',
			remote_required: false
		}, {
			remote_label: gugur.translate('Last Name'),
			remote_type: 'text',
			remote_id: 'last_name',
			remote_required: false
		}, {
			remote_label: gugur.translate('Company'),
			remote_type: 'text',
			remote_id: 'company',
			remote_required: false
		}, {
			remote_label: gugur.translate('Phone'),
			remote_type: 'text',
			remote_id: 'phone',
			remote_required: false
		}, {
			remote_label: gugur.translate('Country'),
			remote_type: 'text',
			remote_id: 'country',
			remote_required: false
		}, {
			remote_label: gugur.translate('State'),
			remote_type: 'text',
			remote_id: 'state',
			remote_required: false
		}, {
			remote_label: gugur.translate('City'),
			remote_type: 'text',
			remote_id: 'city',
			remote_required: false
		}, {
			remote_label: gugur.translate('Zip'),
			remote_type: 'text',
			remote_id: 'zip',
			remote_required: false
		}];

		for (var field in this.fields) {
			if (this.fields.hasOwnProperty(field)) {
				remoteFields.push(this.fields[field]);
			}
		}

		this.getEditorControlView('mailerlite_fields_map').updateMap(remoteFields);
	},

	getMailerliteCache: function getMailerliteCache(type, action, cacheKey, requestArgs) {
		if (_.has(this.cache[type], cacheKey)) {
			var data = {};
			data[type] = this.cache[type][cacheKey];
			return jQuery.Deferred().resolve(data);
		}

		requestArgs = _.extend({}, requestArgs, {
			service: 'mailerlite',
			mailerlite_action: action,
			custom_api_key: this.getEditorControlView('mailerlite_custom_api_key').getControlValue(),
			api_key: this.getEditorControlView('mailerlite_api_key_source').getControlValue()
		});

		return this.fetchCache(type, cacheKey, requestArgs);
	},

	onSectionActive: function onSectionActive() {
		BaseIntegrationModule.prototype.onSectionActive.apply(this, arguments);

		this.onMailerliteApiKeyUpdate();
	}

});

/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BaseIntegrationModule = __webpack_require__(1);

module.exports = BaseIntegrationModule.extend({
	getName: function getName() {
		return 'mailchimp';
	},

	onElementChange: function onElementChange(setting) {
		switch (setting) {
			case 'mailchimp_api_key_source':
			case 'mailchimp_api_key':
				this.onApiUpdate();
				break;
			case 'mailchimp_list':
				this.onMailchimpListUpdate();
				break;
		}
	},

	onApiUpdate: function onApiUpdate() {
		var self = this,
		    controlView = self.getEditorControlView('mailchimp_api_key'),
		    GlobalApiKeycontrolView = self.getEditorControlView('mailchimp_api_key_source');

		if ('default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue()) {
			self.updateOptions('mailchimp_list', []);
			self.getEditorControlView('mailchimp_list').setValue('');
			return;
		}

		self.addControlSpinner('mailchimp_list');
		var cacheKey = this.getCacheKey({
			type: 'lists',
			controls: [controlView.getControlValue(), GlobalApiKeycontrolView.getControlValue()]
		});

		self.getMailchimpCache('lists', 'lists', cacheKey).done(function (data) {
			self.updateOptions('mailchimp_list', data.lists);
			self.updatMailchimpList();
		});
	},

	onMailchimpListUpdate: function onMailchimpListUpdate() {
		this.updateOptions('mailchimp_groups', []);
		this.getEditorControlView('mailchimp_groups').setValue('');
		this.updatMailchimpList();
	},

	updatMailchimpList: function updatMailchimpList() {
		var self = this,
		    controlView = self.getEditorControlView('mailchimp_list');

		if (!controlView.getControlValue()) {
			return;
		}

		self.addControlSpinner('mailchimp_groups');
		var cacheKey = this.getCacheKey({
			type: 'list_details',
			controls: [controlView.getControlValue()]
		});

		self.getMailchimpCache('list_details', 'list_details', controlView.getControlValue(), {
			mailchimp_list: controlView.getControlValue()
		}).done(function (data) {
			self.updateOptions('mailchimp_groups', data.list_details.groups);
			self.getEditorControlView('mailchimp_fields_map').updateMap(data.list_details.fields);
		});
	},

	getMailchimpCache: function getMailchimpCache(type, action, cacheKey, requestArgs) {
		if (_.has(this.cache[type], cacheKey)) {
			var data = {};
			data[type] = this.cache[type][cacheKey];
			return jQuery.Deferred().resolve(data);
		}

		requestArgs = _.extend({}, requestArgs, {
			service: 'mailchimp',
			mailchimp_action: action,
			api_key: this.getEditorControlView('mailchimp_api_key').getControlValue(),
			use_global_api_key: this.getEditorControlView('mailchimp_api_key_source').getControlValue()
		});

		return this.fetchCache(type, cacheKey, requestArgs);
	},

	onSectionActive: function onSectionActive() {
		BaseIntegrationModule.prototype.onSectionActive.apply(this, arguments);

		this.onApiUpdate();
	}
});

/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BaseIntegrationModule = __webpack_require__(1);

module.exports = BaseIntegrationModule.extend({
	getName: function getName() {
		return 'drip';
	},

	onElementChange: function onElementChange(setting) {
		switch (setting) {
			case 'drip_api_token_source':
			case 'drip_custom_api_token':
				this.onApiUpdate();
				break;
			case 'drip_account':
				this.onDripAccountsUpdate();
				break;
		}
	},

	onApiUpdate: function onApiUpdate() {
		var self = this,
		    controlView = self.getEditorControlView('drip_api_token_source'),
		    customControlView = self.getEditorControlView('drip_custom_api_token');

		if ('default' !== controlView.getControlValue() && '' === customControlView.getControlValue()) {
			self.updateOptions('drip_account', []);
			self.getEditorControlView('drip_account').setValue('');
			return;
		}

		self.addControlSpinner('drip_account');
		var cacheKey = this.getCacheKey({
			type: 'accounts',
			controls: [controlView.getControlValue(), customControlView.getControlValue()]
		});

		self.getDripCache('accounts', 'accounts', controlView.getControlValue()).done(function (data) {
			self.updateOptions('drip_account', data.accounts);
		});
	},

	onDripAccountsUpdate: function onDripAccountsUpdate() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping: function updateFieldsMapping() {
		var controlView = this.getEditorControlView('drip_account');

		if (!controlView.getControlValue()) {
			return;
		}

		var remoteFields = {
			remote_label: gugur.translate('Email'),
			remote_type: 'email',
			remote_id: 'email',
			remote_required: true
		};

		this.getEditorControlView('drip_fields_map').updateMap([remoteFields]);
	},

	getDripCache: function getDripCache(type, action, cacheKey, requestArgs) {
		if (_.has(this.cache[type], cacheKey)) {
			var data = {};
			data[type] = this.cache[type][cacheKey];
			return jQuery.Deferred().resolve(data);
		}

		requestArgs = _.extend({}, requestArgs, {
			service: 'drip',
			drip_action: action,
			api_token: this.getEditorControlView('drip_api_token_source').getControlValue(),
			custom_api_token: this.getEditorControlView('drip_custom_api_token').getControlValue()
		});

		return this.fetchCache(type, cacheKey, requestArgs);
	}
});

/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BaseIntegrationModule = __webpack_require__(1);

module.exports = BaseIntegrationModule.extend({
	fields: {},

	getName: function getName() {
		return 'activecampaign';
	},

	onElementChange: function onElementChange(setting) {
		switch (setting) {
			case 'activecampaign_api_credentials_source':
			case 'activecampaign_api_key':
			case 'activecampaign_api_url':
				this.onApiUpdate();
				break;
			case 'activecampaign_list':
				this.onListUpdate();
				break;
		}
	},

	onApiUpdate: function onApiUpdate() {
		var self = this,
		    apikeyControlView = self.getEditorControlView('activecampaign_api_key'),
		    apiUrlControlView = self.getEditorControlView('activecampaign_api_url'),
		    apiCredControlView = self.getEditorControlView('activecampaign_api_credentials_source');

		if ('default' !== apiCredControlView.getControlValue() && ('' === apikeyControlView.getControlValue() || '' === apiUrlControlView.getControlValue())) {
			self.updateOptions('activecampaign_list', []);
			self.getEditorControlView('activecampaign_list').setValue('');
			return;
		}

		self.addControlSpinner('activecampaign_list');

		var cacheKey = this.getCacheKey({
			controls: [apiCredControlView.getControlValue(), apiUrlControlView.getControlValue(), apikeyControlView.getControlValue()]
		});
		self.getActiveCampaignCache('lists', 'activecampaign_list', cacheKey).done(function (data) {
			self.updateOptions('activecampaign_list', data.lists);
			self.fields = data.fields;
		});
	},

	onListUpdate: function onListUpdate() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping: function updateFieldsMapping() {
		var controlView = this.getEditorControlView('activecampaign_list');

		if (!controlView.getControlValue()) {
			return;
		}

		var remoteFields = [{
			remote_label: gugur.translate('Email'),
			remote_type: 'email',
			remote_id: 'email',
			remote_required: true
		}, {
			remote_label: gugur.translate('First Name'),
			remote_type: 'text',
			remote_id: 'first_name',
			remote_required: false
		}, {
			remote_label: gugur.translate('Last Name'),
			remote_type: 'text',
			remote_id: 'last_name',
			remote_required: false
		}, {
			remote_label: gugur.translate('Phone'),
			remote_type: 'text',
			remote_id: 'phone',
			remote_required: false
		}, {
			remote_label: gugur.translate('Organization name'),
			remote_type: 'text',
			remote_id: 'orgname',
			remote_required: false
		}];

		for (var field in this.fields) {
			if (this.fields.hasOwnProperty(field)) {
				remoteFields.push(this.fields[field]);
			}
		}

		this.getEditorControlView('activecampaign_fields_map').updateMap(remoteFields);
	},

	getActiveCampaignCache: function getActiveCampaignCache(type, action, cacheKey, requestArgs) {
		if (_.has(this.cache[type], cacheKey)) {
			var data = {};
			data[type] = this.cache[type][cacheKey];
			return jQuery.Deferred().resolve(data);
		}

		requestArgs = _.extend({}, requestArgs, {
			service: 'activecampaign',
			activecampaign_action: action,
			api_key: this.getEditorControlView('activecampaign_api_key').getControlValue(),
			api_url: this.getEditorControlView('activecampaign_api_url').getControlValue(),
			api_cred: this.getEditorControlView('activecampaign_api_credentials_source').getControlValue()
		});

		return this.fetchCache(type, cacheKey, requestArgs);
	}
});

/***/ }),
/* 29 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BaseIntegrationModule = __webpack_require__(1);

module.exports = BaseIntegrationModule.extend({
	getName: function getName() {
		return 'getresponse';
	},

	onElementChange: function onElementChange(setting) {
		switch (setting) {
			case 'getresponse_custom_api_key':
			case 'getresponse_api_key_source':
				this.onApiUpdate();
				break;
			case 'getresponse_list':
				this.onGetResonseListUpdate();
				break;
		}
	},

	onApiUpdate: function onApiUpdate() {
		var self = this,
		    controlView = self.getEditorControlView('getresponse_api_key_source'),
		    customControlView = self.getEditorControlView('getresponse_custom_api_key');

		if ('default' !== controlView.getControlValue() && '' === customControlView.getControlValue()) {
			self.updateOptions('getresponse_list', []);
			self.getEditorControlView('getresponse_list').setValue('');
			return;
		}

		self.addControlSpinner('getresponse_list');

		var cacheKey = this.getCacheKey({
			type: 'lists',
			controls: [controlView.getControlValue(), customControlView.getControlValue()]
		});

		self.getCache('lists', 'lists', cacheKey).done(function (data) {
			self.updateOptions('getresponse_list', data.lists);
		});
	},

	onGetResonseListUpdate: function onGetResonseListUpdate() {
		this.updatGetResonseList();
	},

	updatGetResonseList: function updatGetResonseList() {
		var self = this,
		    controlView = self.getEditorControlView('getresponse_list');

		if (!controlView.getControlValue()) {
			return;
		}

		self.addControlSpinner('getresponse_fields_map');
		var cacheKey = this.getCacheKey({
			type: 'fields',
			controls: [controlView.getControlValue()]
		});

		self.getCache('fields', 'get_fields', cacheKey, {
			getresponse_list: controlView.getControlValue()
		}).done(function (data) {
			self.getEditorControlView('getresponse_fields_map').updateMap(data.fields);
		});
	},

	getCache: function getCache(type, action, cacheKey, requestArgs) {
		if (_.has(this.cache[type], cacheKey)) {
			var data = {};
			data[type] = this.cache[type][cacheKey];
			return jQuery.Deferred().resolve(data);
		}

		requestArgs = _.extend({}, requestArgs, {
			service: 'getresponse',
			getresponse_action: action,
			api_key: this.getEditorControlView('getresponse_api_key_source').getControlValue(),
			custom_api_key: this.getEditorControlView('getresponse_custom_api_key').getControlValue()
		});

		return this.fetchCache(type, cacheKey, requestArgs);
	},

	onSectionActive: function onSectionActive() {
		BaseIntegrationModule.prototype.onSectionActive.apply(this, arguments);

		this.updatGetResonseList();
	}
});

/***/ }),
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BaseIntegrationModule = __webpack_require__(1);

module.exports = BaseIntegrationModule.extend({

	getName: function getName() {
		return 'convertkit';
	},

	onElementChange: function onElementChange(setting) {
		switch (setting) {
			case 'convertkit_api_key_source':
			case 'convertkit_custom_api_key':
				this.onApiUpdate();
				break;
			case 'convertkit_form':
				this.onListUpdate();
				break;
		}
	},

	onApiUpdate: function onApiUpdate() {
		var self = this,
		    apiKeyControlView = self.getEditorControlView('convertkit_api_key_source'),
		    customApikeyControlView = self.getEditorControlView('convertkit_custom_api_key');

		if ('default' !== apiKeyControlView.getControlValue() && '' === customApikeyControlView.getControlValue()) {
			self.updateOptions('convertkit_form', []);
			self.getEditorControlView('convertkit_form').setValue('');
			return;
		}

		self.addControlSpinner('convertkit_form');
		var cacheKey = this.getCacheKey({
			type: 'data',
			controls: [apiKeyControlView.getControlValue(), customApikeyControlView.getControlValue()]
		});

		self.getConvertKitCache('data', 'convertkit_get_forms', cacheKey).done(function (data) {
			self.updateOptions('convertkit_form', data.data.forms);
			self.updateOptions('convertkit_tags', data.data.tags);
		});
	},

	onListUpdate: function onListUpdate() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping: function updateFieldsMapping() {
		var controlView = this.getEditorControlView('convertkit_form');

		if (!controlView.getControlValue()) {
			return;
		}

		var remoteFields = [{
			remote_label: gugur.translate('Email'),
			remote_type: 'email',
			remote_id: 'email',
			remote_required: true
		}, {
			remote_label: gugur.translate('First Name'),
			remote_type: 'text',
			remote_id: 'first_name',
			remote_required: false
		}];

		this.getEditorControlView('convertkit_fields_map').updateMap(remoteFields);
	},

	getConvertKitCache: function getConvertKitCache(type, action, cacheKey, requestArgs) {
		if (_.has(this.cache[type], cacheKey)) {
			var data = {};
			data[type] = this.cache[type][cacheKey];
			return jQuery.Deferred().resolve(data);
		}

		requestArgs = _.extend({}, requestArgs, {
			service: 'convertkit',
			convertkit_action: action,
			api_key: this.getEditorControlView('convertkit_api_key_source').getControlValue(),
			custom_api_key: this.getEditorControlView('convertkit_custom_api_key').getControlValue()
		});

		return this.fetchCache(type, cacheKey, requestArgs);
	}
});

/***/ }),
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({

	renderField: function renderField(inputField, item, i, settings) {
		var itemClasses = _.escape(item.css_classes),
		    required = '',
		    placeholder = '';

		if (item.required) {
			required = 'required';
		}

		if (item.placeholder) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		if ('yes' === item.use_native_time) {
			itemClasses += ' gugur-use-native';
		}

		return '<input size="1" type="time"' + placeholder + ' class="gugur-field-textual gugur-time-field gugur-field gugur-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
	},

	onInit: function onInit() {
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/time', this.renderField, 10, 4);
	}
});

/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({

	renderField: function renderField(inputField, item, i, settings) {
		var itemClasses = _.escape(item.css_classes),
		    required = '',
		    min = '',
		    max = '',
		    placeholder = '';

		if (item.required) {
			required = 'required';
		}

		if (item.min_date) {
			min = ' min="' + item.min_date + '"';
		}

		if (item.max_date) {
			max = ' max="' + item.max_date + '"';
		}

		if (item.placeholder) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		if ('yes' === item.use_native_date) {
			itemClasses += ' gugur-use-native';
		}

		return '<input size="1"' + min + max + placeholder + ' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" class="gugur-field-textual gugur-date-field gugur-field gugur-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
	},

	onInit: function onInit() {
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/date', this.renderField, 10, 4);
	}
});

/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({

	renderField: function renderField(inputField, item, i, settings) {
		var itemClasses = _.escape(item.css_classes),
		    required = '',
		    label = '',
		    checked = '';

		if (item.required) {
			required = 'required';
		}

		if (item.acceptance_text) {
			label = '<label for="form_field_' + i + '">' + item.acceptance_text + '</label>';
		}

		if (item.checked_by_default) {
			checked = ' checked="checked"';
		}

		return '<div class="gugur-field-subgroup">' + '<span class="gugur-field-option">' + '<input size="1" type="checkbox"' + checked + ' class="gugur-acceptance-field gugur-field gugur-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' > ' + label + '</span></div>';
	},

	onInit: function onInit() {
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/acceptance', this.renderField, 10, 4);
	}
});

/***/ }),
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({

	renderField: function renderField(inputField, item, i, settings) {
		var itemClasses = _.escape(item.css_classes),
		    required = '',
		    multiple = '',
		    fieldName = 'form_field_';

		if (item.required) {
			required = 'required';
		}
		if (item.allow_multiple_upload) {
			multiple = ' multiple="multiple"';
			fieldName += '[]';
		}

		return '<input size="1"  type="file" class="gugur-file-field gugur-field gugur-size-' + settings.input_size + ' ' + itemClasses + '" name="' + fieldName + '" id="form_field_' + i + '" ' + required + multiple + ' >';
	},

	onInit: function onInit() {
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/upload', this.renderField, 10, 4);
	}
});

/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({

	renderField: function renderField(inputField, item, i, settings) {
		var itemClasses = _.escape(item.css_classes),
		    required = '',
		    placeholder = '';

		if (item.required) {
			required = 'required';
		}

		if (item.placeholder) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		itemClasses = 'gugur-field-textual ' + itemClasses;

		return '<input size="1" type="' + item.field_type + '" class="gugur-field-textual gugur-field gugur-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' pattern="[0-9()-]" >';
	},

	onInit: function onInit() {
		gugur.hooks.addFilter('gugur_pro/forms/content_template/field/tel', this.renderField, 10, 4);
	}
});

/***/ }),
/* 36 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.controls.Repeater.extend({
	onBeforeRender: function onBeforeRender() {
		this.$el.hide();
	},

	updateMap: function updateMap(fields) {
		var self = this,
		    savedMapObject = {};

		self.collection.each(function (model) {
			savedMapObject[model.get('remote_id')] = model.get('local_id');
		});

		self.collection.reset();

		_.each(fields, function (field) {
			var model = {
				remote_id: field.remote_id,
				remote_label: field.remote_label,
				remote_type: field.remote_type ? field.remote_type : '',
				remote_required: field.remote_required ? field.remote_required : false,
				local_id: savedMapObject[field.remote_id] ? savedMapObject[field.remote_id] : ''
			};

			self.collection.add(model);
		});

		self.render();
	},

	onRender: function onRender() {
		gugur.modules.controls.Base.prototype.onRender.apply(this, arguments);

		var self = this;

		self.children.each(function (view) {
			var localFieldsControl = view.children.last(),
			    options = {
				'': '- ' + gugur.translate('None') + ' -'
			},
			    label = view.model.get('remote_label');

			if (view.model.get('remote_required')) {
				label += '<span class="gugur-required">*</span>';
			}

			_.each(self.elementSettingsModel.get('form_fields').models, function (model, index) {
				// If it's an email field, add only email fields from thr form
				var remoteType = view.model.get('remote_type');

				if ('text' !== remoteType && remoteType !== model.get('field_type')) {
					return;
				}

				options[model.get('custom_id')] = model.get('field_label') || 'Field #' + (index + 1);
			});

			localFieldsControl.model.set('label', label);
			localFieldsControl.model.set('options', options);
			localFieldsControl.render();

			view.$el.find('.gugur-repeater-row-tools').hide();
			view.$el.find('.gugur-repeater-row-controls').removeClass('gugur-repeater-row-controls').find('.gugur-control').css({
				paddingBottom: 0
			});
		});

		self.$el.find('.gugur-button-wrapper').remove();

		if (self.children.length) {
			self.$el.show();
		}
	}
});

/***/ }),
/* 37 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	ongugurPreviewLoaded: function ongugurPreviewLoaded() {
		var EditButton = __webpack_require__(38);
		this.editButton = new EditButton();
	}
});

/***/ }),
/* 38 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function () {
	var self = this;

	self.onPanelShow = function (panel) {
		var model = panel.content.currentView.collection.findWhere({ name: 'template_id' });
		self.templateIdView = panel.content.currentView.children.findByModelCid(model.cid);

		// Change Edit link on render & on change template.
		self.templateIdView.elementSettingsModel.on('change', self.onTemplateIdChange);
		self.templateIdView.on('render', self.onTemplateIdChange);
	};

	self.onTemplateIdChange = function () {
		var templateID = self.templateIdView.elementSettingsModel.get('template_id'),
		    $editButton = self.templateIdView.$el.find('.gugur-edit-template');

		if (!templateID) {
			$editButton.remove();
			return;
		}

		var editUrl = gugurConfig.home_url + '?p=' + templateID + '&gugur';

		if ($editButton.length) {
			$editButton.prop('href', editUrl);
		} else {
			$editButton = jQuery('<a />', {
				target: '_blank',
				class: 'gugur-button gugur-button-default gugur-edit-template',
				href: editUrl,
				html: '<i class="eicon-pencil" /> ' + gugurPro.config.i18n.edit_template
			});

			self.templateIdView.$el.find('.gugur-control-input-wrapper').after($editButton);
		}
	};

	self.init = function () {
		gugur.hooks.addAction('panel/open_editor/widget/template', self.onPanelShow);
	};

	self.init();
};

/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (!gugurProEditorConfig.useComponentsRouter) {
	module.exports = __webpack_require__(40);
} else {
	module.exports = gugurModules.editor.utils.Module.extend({
		globalModels: {},

		panelWidgets: null,

		templatesAreSaved: true,

		addGlobalWidget: function addGlobalWidget(id, args) {
			args = _.extend({}, args, {
				categories: [],
				icon: gugur.config.widgets[args.widgetType].icon,
				widgetType: args.widgetType,
				custom: {
					templateID: id
				}
			});

			var globalModel = this.createGlobalModel(id, args);

			return this.panelWidgets.add(globalModel);
		},

		createGlobalModel: function createGlobalModel(id, modelArgs) {
			var globalModel = new gugur.modules.elements.models.Element(modelArgs),
			    settingsModel = globalModel.get('settings');

			globalModel.set('id', id);

			settingsModel.on('change', _.bind(this.onGlobalModelChange, this));

			return this.globalModels[id] = globalModel;
		},

		onGlobalModelChange: function onGlobalModelChange() {
			this.templatesAreSaved = false;
		},

		setWidgetType: function setWidgetType() {
			gugur.hooks.addFilter('element/view', function (DefaultView, model) {
				if (model.get('templateID')) {
					return __webpack_require__(46);
				}

				return DefaultView;
			});

			gugur.hooks.addFilter('element/model', function (DefaultModel, attrs) {
				if (attrs.templateID) {
					return __webpack_require__(47);
				}

				return DefaultModel;
			});
		},

		registerTemplateType: function registerTemplateType() {
			gugur.templates.registerTemplateType('widget', {
				showInLibrary: false,
				saveDialog: {
					title: gugurPro.translate('global_widget_save_title'),
					description: gugurPro.translate('global_widget_save_description')
				},
				prepareSavedData: function prepareSavedData(data) {
					data.widgetType = data.content[0].widgetType;

					return data;
				},
				ajaxParams: {
					success: this.onWidgetTemplateSaved.bind(this)
				}
			});
		},

		addSavedWidgetsToPanel: function addSavedWidgetsToPanel() {
			var self = this;

			self.panelWidgets = new Backbone.Collection();

			_.each(gugurPro.config.widget_templates, function (templateArgs, id) {
				self.addGlobalWidget(id, templateArgs);
			});

			gugur.hooks.addFilter('panel/elements/regionViews', function (regionViews) {
				_.extend(regionViews.global, {
					view: __webpack_require__(48),
					options: {
						collection: self.panelWidgets
					}
				});

				return regionViews;
			});
		},

		addPanelPage: function addPanelPage() {
			gugur.getPanelView().addPage('globalWidget', {
				view: __webpack_require__(50)
			});
		},

		getGlobalModels: function getGlobalModels(id) {
			if (!id) {
				return this.globalModels;
			}

			return this.globalModels[id];
		},

		saveTemplates: function saveTemplates() {
			if (!Object.keys(this.globalModels).length) {
				return;
			}

			var templatesData = [],
			    self = this;

			_.each(this.globalModels, function (templateModel, id) {
				if ('loaded' !== templateModel.get('settingsLoadedStatus')) {
					return;
				}

				var data = {
					content: JSON.stringify([templateModel.toJSON({ remove: ['default'] })]),
					source: 'local',
					type: 'widget',
					id: id
				};

				templatesData.push(data);
			});

			if (!templatesData.length) {
				return;
			}

			gugurCommon.ajax.addRequest('update_templates', {
				data: {
					templates: templatesData
				},
				success: function success() {
					self.templatesAreSaved = true;
				}
			});
		},

		setSaveButton: function setSaveButton() {
			gugur.saver.on('before:save:publish', _.bind(this.saveTemplates, this));
			gugur.saver.on('before:save:private', _.bind(this.saveTemplates, this));
		},

		requestGlobalModelSettings: function requestGlobalModelSettings(globalModel, callback) {
			gugur.templates.requestTemplateContent('local', globalModel.get('id'), {
				success: function success(data) {
					globalModel.set('settingsLoadedStatus', 'loaded').trigger('settings:loaded');

					var settings = data.content[0].settings,
					    settingsModel = globalModel.get('settings');

					// Don't track it in History
					gugur.history.history.setActive(false);

					settingsModel.handleRepeaterData(settings);

					settingsModel.set(settings);

					if (callback) {
						callback(globalModel);
					}

					gugur.history.history.setActive(true);
				}
			});
		},

		setWidgetContextMenuSaveAction: function setWidgetContextMenuSaveAction() {
			gugur.hooks.addFilter('elements/widget/contextMenuGroups', function (groups, widget) {
				var saveGroup = _.findWhere(groups, { name: 'save' });

				if (!saveGroup) {
					return groups;
				}

				var saveAction = _.findWhere(saveGroup.actions, { name: 'save' });

				saveAction.callback = widget.save.bind(widget);

				delete saveAction.shortcut;

				return groups;
			});
		},

		ongugurInit: function ongugurInit() {
			this.setWidgetType();

			this.registerTemplateType();

			this.setWidgetContextMenuSaveAction();
		},

		ongugurFrontendInit: function ongugurFrontendInit() {
			this.addSavedWidgetsToPanel();
		},

		ongugurPreviewLoaded: function ongugurPreviewLoaded(isFirst) {
			if (!isFirst) {
				return;
			}

			this.addPanelPage();
			this.setSaveButton();

			$e.routes.register('panel/editor', 'global', function (args) {
				gugur.getPanelView().setPage('globalWidget', 'Global Editing', { editedView: args.view });
			});
		},

		onWidgetTemplateSaved: function onWidgetTemplateSaved(data) {
			gugur.history.history.startItem({
				title: gugur.config.widgets[data.widgetType].title,
				type: gugurPro.translate('linked_to_global')
			});

			var widgetModel = gugur.templates.layout.modalContent.currentView.model,
			    widgetModelIndex = widgetModel.collection.indexOf(widgetModel);

			gugur.templates.layout.hideModal();

			data.elType = data.type;
			data.settings = widgetModel.get('settings').attributes;

			var globalModel = this.addGlobalWidget(data.template_id, data),
			    globalModelAttributes = globalModel.attributes;

			widgetModel.collection.add({
				id: gugur.helpers.getUniqueID(),
				elType: globalModelAttributes.type,
				templateID: globalModelAttributes.template_id,
				widgetType: 'global'
			}, { at: widgetModelIndex }, true);

			widgetModel.destroy();

			gugur.history.history.endItem();

			$e.route('panel/elements/global');
		}
	});
}

/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	globalModels: {},

	panelWidgets: null,

	templatesAreSaved: true,

	addGlobalWidget: function addGlobalWidget(id, args) {
		args = _.extend({}, args, {
			categories: [],
			icon: gugur.config.widgets[args.widgetType].icon,
			widgetType: args.widgetType,
			custom: {
				templateID: id
			}
		});

		var globalModel = this.createGlobalModel(id, args);

		return this.panelWidgets.add(globalModel);
	},

	createGlobalModel: function createGlobalModel(id, modelArgs) {
		var globalModel = new gugur.modules.elements.models.Element(modelArgs),
		    settingsModel = globalModel.get('settings');

		globalModel.set('id', id);

		settingsModel.on('change', _.bind(this.onGlobalModelChange, this));

		return this.globalModels[id] = globalModel;
	},

	onGlobalModelChange: function onGlobalModelChange() {
		this.templatesAreSaved = false;
	},

	setWidgetType: function setWidgetType() {
		gugur.hooks.addFilter('element/view', function (DefaultView, model) {
			if (model.get('templateID')) {
				return __webpack_require__(41);
			}

			return DefaultView;
		});

		gugur.hooks.addFilter('element/model', function (DefaultModel, attrs) {
			if (attrs.templateID) {
				return __webpack_require__(42);
			}

			return DefaultModel;
		});
	},

	registerTemplateType: function registerTemplateType() {
		gugur.templates.registerTemplateType('widget', {
			showInLibrary: false,
			saveDialog: {
				title: gugurPro.translate('global_widget_save_title'),
				description: gugurPro.translate('global_widget_save_description')
			},
			prepareSavedData: function prepareSavedData(data) {
				data.widgetType = data.content[0].widgetType;

				return data;
			},
			ajaxParams: {
				success: this.onWidgetTemplateSaved.bind(this)
			}
		});
	},

	addSavedWidgetsToPanel: function addSavedWidgetsToPanel() {
		var self = this;

		self.panelWidgets = new Backbone.Collection();

		_.each(gugurPro.config.widget_templates, function (templateArgs, id) {
			self.addGlobalWidget(id, templateArgs);
		});

		gugur.hooks.addFilter('panel/elements/regionViews', function (regionViews) {
			_.extend(regionViews.global, {
				view: __webpack_require__(43),
				options: {
					collection: self.panelWidgets
				}
			});

			return regionViews;
		});
	},

	addPanelPage: function addPanelPage() {
		gugur.getPanelView().addPage('globalWidget', {
			view: __webpack_require__(45)
		});
	},

	getGlobalModels: function getGlobalModels(id) {
		if (!id) {
			return this.globalModels;
		}

		return this.globalModels[id];
	},

	saveTemplates: function saveTemplates() {
		if (!Object.keys(this.globalModels).length) {
			return;
		}

		var templatesData = [],
		    self = this;

		_.each(this.globalModels, function (templateModel, id) {
			if ('loaded' !== templateModel.get('settingsLoadedStatus')) {
				return;
			}

			var data = {
				content: JSON.stringify([templateModel.toJSON({ remove: ['default'] })]),
				source: 'local',
				type: 'widget',
				id: id
			};

			templatesData.push(data);
		});

		if (!templatesData.length) {
			return;
		}

		gugurCommon.ajax.addRequest('update_templates', {
			data: {
				templates: templatesData
			},
			success: function success() {
				self.templatesAreSaved = true;
			}
		});
	},

	setSaveButton: function setSaveButton() {
		gugur.saver.on('before:save:publish', _.bind(this.saveTemplates, this));
		gugur.saver.on('before:save:private', _.bind(this.saveTemplates, this));
	},

	requestGlobalModelSettings: function requestGlobalModelSettings(globalModel, callback) {
		gugur.templates.requestTemplateContent('local', globalModel.get('id'), {
			success: function success(data) {
				globalModel.set('settingsLoadedStatus', 'loaded').trigger('settings:loaded');

				var settings = data.content[0].settings,
				    settingsModel = globalModel.get('settings');

				// Don't track it in History
				gugur.history.history.setActive(false);

				settingsModel.handleRepeaterData(settings);

				settingsModel.set(settings);

				if (callback) {
					callback(globalModel);
				}

				gugur.history.history.setActive(true);
			}
		});
	},

	setWidgetContextMenuSaveAction: function setWidgetContextMenuSaveAction() {
		gugur.hooks.addFilter('elements/widget/contextMenuGroups', function (groups, widget) {
			var saveGroup = _.findWhere(groups, { name: 'save' }),
			    saveAction = _.findWhere(saveGroup.actions, { name: 'save' });

			saveAction.callback = widget.save.bind(widget);

			delete saveAction.shortcut;

			return groups;
		});
	},

	ongugurInit: function ongugurInit() {
		this.setWidgetType();

		this.registerTemplateType();

		this.setWidgetContextMenuSaveAction();
	},

	ongugurFrontendInit: function ongugurFrontendInit() {
		this.addSavedWidgetsToPanel();
	},

	ongugurPreviewLoaded: function ongugurPreviewLoaded() {
		this.addPanelPage();
		this.setSaveButton();
	},

	onWidgetTemplateSaved: function onWidgetTemplateSaved(data) {
		gugur.history.history.startItem({
			title: gugur.config.widgets[data.widgetType].title,
			type: gugurPro.translate('linked_to_global')
		});

		var widgetModel = gugur.templates.getLayout().modalContent.currentView.model,
		    widgetModelIndex = widgetModel.collection.indexOf(widgetModel);

		gugur.templates.closeModal();

		data.elType = data.type;
		data.settings = widgetModel.get('settings').attributes;

		var globalModel = this.addGlobalWidget(data.template_id, data),
		    globalModelAttributes = globalModel.attributes;

		widgetModel.collection.add({
			id: gugur.helpers.getUniqueID(),
			elType: globalModelAttributes.type,
			templateID: globalModelAttributes.template_id,
			widgetType: 'global'
		}, { at: widgetModelIndex }, true);

		widgetModel.destroy();

		var panel = gugur.getPanelView();

		panel.setPage('elements');

		panel.getCurrentPageView().activateTab('global');

		gugur.history.history.endItem();
	}
});

/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var WidgetView = gugur.modules.elements.views.Widget,
    GlobalWidgetView;

GlobalWidgetView = WidgetView.extend({

	globalModel: null,

	className: function className() {
		return WidgetView.prototype.className.apply(this, arguments) + ' gugur-global-widget gugur-global-' + this.model.get('templateID');
	},

	initialize: function initialize() {
		var self = this,
		    previewSettings = self.model.get('previewSettings'),
		    globalModel = self.getGlobalModel();

		if (previewSettings) {
			globalModel.set('settingsLoadedStatus', 'loaded').trigger('settings:loaded');

			var settingsModel = globalModel.get('settings');

			settingsModel.handleRepeaterData(previewSettings);

			settingsModel.set(previewSettings, { silent: true });
		} else {
			var globalSettingsLoadedStatus = globalModel.get('settingsLoadedStatus');

			if (!globalSettingsLoadedStatus) {
				globalModel.set('settingsLoadedStatus', 'pending');

				gugurPro.modules.globalWidget.requestGlobalModelSettings(globalModel);
			}

			if ('loaded' !== globalSettingsLoadedStatus) {
				self.$el.addClass('gugur-loading');
			}

			globalModel.on('settings:loaded', function () {
				self.$el.removeClass('gugur-loading');

				self.render();
			});
		}

		WidgetView.prototype.initialize.apply(self, arguments);
	},

	getGlobalModel: function getGlobalModel() {
		if (!this.globalModel) {
			this.globalModel = gugurPro.modules.globalWidget.getGlobalModels(this.model.get('templateID'));
		}

		return this.globalModel;
	},

	getEditModel: function getEditModel() {
		return this.getGlobalModel();
	},

	getHTMLContent: function getHTMLContent(html) {
		if ('loaded' === this.getGlobalModel().get('settingsLoadedStatus')) {
			return WidgetView.prototype.getHTMLContent.call(this, html);
		}

		return '';
	},

	serializeModel: function serializeModel() {
		var globalModel = this.getGlobalModel();

		return globalModel.toJSON.apply(globalModel, _.rest(arguments));
	},

	edit: function edit() {
		gugur.getPanelView().setPage('globalWidget', 'Global Editing', { editedView: this });
	},

	unlink: function unlink() {
		var globalModel = this.getGlobalModel();

		gugur.history.history.startItem({
			title: globalModel.getTitle(),
			type: gugurPro.translate('unlink_widget')
		});

		var newModel = new gugur.modules.elements.models.Element({
			elType: 'widget',
			widgetType: globalModel.get('widgetType'),
			id: gugur.helpers.getUniqueID(),
			settings: gugur.helpers.cloneObject(globalModel.get('settings').attributes),
			defaultEditSettings: gugur.helpers.cloneObject(globalModel.get('editSettings').attributes)
		});

		this._parent.addChildModel(newModel, { at: this.model.collection.indexOf(this.model) });

		var newWidget = this._parent.children.findByModelCid(newModel.cid);

		this.model.destroy();

		gugur.history.history.endItem();

		if (newWidget.edit) {
			newWidget.edit();
		}

		newModel.trigger('request:edit');
	},

	onEditRequest: function onEditRequest() {
		gugur.getPanelView().setPage('globalWidget', 'Global Editing', { editedView: this });
	}
});

module.exports = GlobalWidgetView;

/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.elements.models.Element.extend({
	initialize: function initialize() {
		this.set({ widgetType: 'global' }, { silent: true });

		gugur.modules.elements.models.Element.prototype.initialize.apply(this, arguments);

		gugurFrontend.config.elements.data[this.cid].on('change', this.onSettingsChange.bind(this));
	},

	initSettings: function initSettings() {
		var globalModel = this.getGlobalModel(),
		    settingsModel = globalModel.get('settings');

		this.set('settings', settingsModel);

		gugurFrontend.config.elements.data[this.cid] = settingsModel;

		gugurFrontend.config.elements.editSettings[this.cid] = globalModel.get('editSettings');
	},

	initEditSettings: function initEditSettings() {},

	getGlobalModel: function getGlobalModel() {
		var templateID = this.get('templateID');

		return gugurPro.modules.globalWidget.getGlobalModels(templateID);
	},

	getTitle: function getTitle() {
		var title = this.getSetting('_title');

		if (!title) {
			title = this.getGlobalModel().get('title');
		}

		var global = gugurPro.translate('global');

		title = title.replace(new RegExp('\\(' + global + '\\)$'), '');

		return title + ' (' + global + ')';
	},

	getIcon: function getIcon() {
		return this.getGlobalModel().getIcon();
	},

	onSettingsChange: function onSettingsChange(model) {
		if (!model.changed.elements) {
			this.set('previewSettings', model.toJSON({ remove: ['default'] }), { silent: true });
		}
	},

	onDestroy: function onDestroy() {
		var panel = gugur.getPanelView(),
		    currentPageName = panel.getCurrentPageName();

		if (-1 !== ['editor', 'globalWidget'].indexOf(currentPageName)) {
			panel.setPage('elements');
		}
	}
});

/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.layouts.panel.pages.elements.views.Elements.extend({
	id: 'gugur-global-templates',

	getEmptyView: function getEmptyView() {
		if (this.collection.length) {
			return null;
		}

		return __webpack_require__(44);
	},

	onFilterEmpty: function onFilterEmpty() {}
});

/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var GlobalWidgetsView = gugur.modules.layouts.panel.pages.elements.views.Global;

module.exports = GlobalWidgetsView.extend({
	template: '#tmpl-gugur-panel-global-widget-no-templates',

	id: 'gugur-panel-global-widget-no-templates',

	className: 'gugur-nerd-box gugur-panel-nerd-box'
});

/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = Marionette.ItemView.extend({
	id: 'gugur-panel-global-widget',

	template: '#tmpl-gugur-panel-global-widget',

	ui: {
		editButton: '#gugur-global-widget-locked-edit .gugur-button',
		unlinkButton: '#gugur-global-widget-locked-unlink .gugur-button',
		loading: '#gugur-global-widget-loading'
	},

	events: {
		'click @ui.editButton': 'onEditButtonClick',
		'click @ui.unlinkButton': 'onUnlinkButtonClick'
	},

	initialize: function initialize() {
		this.initUnlinkDialog();
	},

	buildUnlinkDialog: function buildUnlinkDialog() {
		var self = this;

		return gugurCommon.dialogsManager.createWidget('confirm', {
			id: 'gugur-global-widget-unlink-dialog',
			headerMessage: gugurPro.translate('unlink_widget'),
			message: gugurPro.translate('dialog_confirm_unlink'),
			position: {
				my: 'center center',
				at: 'center center'
			},
			strings: {
				confirm: gugurPro.translate('unlink'),
				cancel: gugurPro.translate('cancel')
			},
			onConfirm: function onConfirm() {
				self.getOption('editedView').unlink();
			}
		});
	},

	initUnlinkDialog: function initUnlinkDialog() {
		var dialog;

		this.getUnlinkDialog = function () {
			if (!dialog) {
				dialog = this.buildUnlinkDialog();
			}

			return dialog;
		};
	},

	editGlobalModel: function editGlobalModel() {
		var editedView = this.getOption('editedView');

		gugur.getPanelView().openEditor(editedView.getEditModel(), editedView);
	},

	onEditButtonClick: function onEditButtonClick() {
		var self = this,
		    editedView = self.getOption('editedView'),
		    editedModel = editedView.getEditModel();

		if ('loaded' === editedModel.get('settingsLoadedStatus')) {
			self.editGlobalModel();

			return;
		}

		self.ui.loading.removeClass('gugur-hidden');

		gugurPro.modules.globalWidget.requestGlobalModelSettings(editedModel, function () {
			self.ui.loading.addClass('gugur-hidden');

			self.editGlobalModel();
		});
	},

	onUnlinkButtonClick: function onUnlinkButtonClick() {
		this.getUnlinkDialog().show();
	}
});

/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var WidgetView = gugur.modules.elements.views.Widget,
    GlobalWidgetView;

GlobalWidgetView = WidgetView.extend({

	globalModel: null,

	className: function className() {
		return WidgetView.prototype.className.apply(this, arguments) + ' gugur-global-widget gugur-global-' + this.model.get('templateID');
	},

	initialize: function initialize() {
		var self = this,
		    previewSettings = self.model.get('previewSettings'),
		    globalModel = self.getGlobalModel();

		if (previewSettings) {
			globalModel.set('settingsLoadedStatus', 'loaded').trigger('settings:loaded');

			var settingsModel = globalModel.get('settings');

			settingsModel.handleRepeaterData(previewSettings);

			settingsModel.set(previewSettings, { silent: true });
		} else {
			var globalSettingsLoadedStatus = globalModel.get('settingsLoadedStatus');

			if (!globalSettingsLoadedStatus) {
				globalModel.set('settingsLoadedStatus', 'pending');

				gugurPro.modules.globalWidget.requestGlobalModelSettings(globalModel);
			}

			if ('loaded' !== globalSettingsLoadedStatus) {
				self.$el.addClass('gugur-loading');
			}

			globalModel.on('settings:loaded', function () {
				self.$el.removeClass('gugur-loading');

				self.render();
			});
		}

		WidgetView.prototype.initialize.apply(self, arguments);
	},

	getGlobalModel: function getGlobalModel() {
		if (!this.globalModel) {
			this.globalModel = gugurPro.modules.globalWidget.getGlobalModels(this.model.get('templateID'));
		}

		return this.globalModel;
	},

	getEditModel: function getEditModel() {
		return this.getGlobalModel();
	},

	getHTMLContent: function getHTMLContent(html) {
		if ('loaded' === this.getGlobalModel().get('settingsLoadedStatus')) {
			return WidgetView.prototype.getHTMLContent.call(this, html);
		}

		return '';
	},

	serializeModel: function serializeModel() {
		var globalModel = this.getGlobalModel();

		return globalModel.toJSON.apply(globalModel, _.rest(arguments));
	},

	edit: function edit() {
		$e.route('panel/editor/global', {
			view: this
		});
	},

	unlink: function unlink() {
		var globalModel = this.getGlobalModel();

		gugur.history.history.startItem({
			title: globalModel.getTitle(),
			type: gugurPro.translate('unlink_widget')
		});

		var newModel = new gugur.modules.elements.models.Element({
			elType: 'widget',
			widgetType: globalModel.get('widgetType'),
			id: gugur.helpers.getUniqueID(),
			settings: gugur.helpers.cloneObject(globalModel.get('settings').attributes),
			defaultEditSettings: gugur.helpers.cloneObject(globalModel.get('editSettings').attributes)
		});

		this._parent.addChildModel(newModel, { at: this.model.collection.indexOf(this.model) });

		var newWidget = this._parent.children.findByModelCid(newModel.cid);

		this.model.destroy();

		gugur.history.history.endItem();

		if (newWidget.edit) {
			newWidget.edit();
		}

		newModel.trigger('request:edit');
	},

	onEditRequest: function onEditRequest() {
		this.edit();
	}
});

module.exports = GlobalWidgetView;

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.elements.models.Element.extend({
	initialize: function initialize() {
		this.set({ widgetType: 'global' }, { silent: true });

		gugur.modules.elements.models.Element.prototype.initialize.apply(this, arguments);

		gugurFrontend.config.elements.data[this.cid].on('change', this.onSettingsChange.bind(this));
	},

	initSettings: function initSettings() {
		var globalModel = this.getGlobalModel(),
		    settingsModel = globalModel.get('settings');

		this.set('settings', settingsModel);

		gugurFrontend.config.elements.data[this.cid] = settingsModel;

		gugurFrontend.config.elements.editSettings[this.cid] = globalModel.get('editSettings');
	},

	initEditSettings: function initEditSettings() {},

	getGlobalModel: function getGlobalModel() {
		var templateID = this.get('templateID');

		return gugurPro.modules.globalWidget.getGlobalModels(templateID);
	},

	getTitle: function getTitle() {
		var title = this.getSetting('_title');

		if (!title) {
			title = this.getGlobalModel().get('title');
		}

		var global = gugurPro.translate('global');

		title = title.replace(new RegExp('\\(' + global + '\\)$'), '');

		return title + ' (' + global + ')';
	},

	getIcon: function getIcon() {
		return this.getGlobalModel().getIcon();
	},

	onSettingsChange: function onSettingsChange(model) {
		if (!model.changed.elements) {
			this.set('previewSettings', model.toJSON({ remove: ['default'] }), { silent: true });
		}
	},

	onDestroy: function onDestroy() {
		// Can be also 'panel/editor/global'.
		if ($e.routes.isPartOf('panel/editor')) {
			$e.route('panel/elements/categories');
		}
	}
});

/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.layouts.panel.pages.elements.views.Elements.extend({
	id: 'gugur-global-templates',

	getEmptyView: function getEmptyView() {
		if (this.collection.length) {
			return null;
		}

		return __webpack_require__(49);
	},

	onFilterEmpty: function onFilterEmpty() {}
});

/***/ }),
/* 49 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var GlobalWidgetsView = gugur.modules.layouts.panel.pages.elements.views.Global;

module.exports = GlobalWidgetsView.extend({
	template: '#tmpl-gugur-panel-global-widget-no-templates',

	id: 'gugur-panel-global-widget-no-templates',

	className: 'gugur-nerd-box gugur-panel-nerd-box'
});

/***/ }),
/* 50 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = Marionette.ItemView.extend({
	id: 'gugur-panel-global-widget',

	template: '#tmpl-gugur-panel-global-widget',

	ui: {
		editButton: '#gugur-global-widget-locked-edit .gugur-button',
		unlinkButton: '#gugur-global-widget-locked-unlink .gugur-button',
		loading: '#gugur-global-widget-loading'
	},

	events: {
		'click @ui.editButton': 'onEditButtonClick',
		'click @ui.unlinkButton': 'onUnlinkButtonClick'
	},

	initialize: function initialize() {
		this.initUnlinkDialog();
	},

	buildUnlinkDialog: function buildUnlinkDialog() {
		var self = this;

		return gugurCommon.dialogsManager.createWidget('confirm', {
			id: 'gugur-global-widget-unlink-dialog',
			headerMessage: gugurPro.translate('unlink_widget'),
			message: gugurPro.translate('dialog_confirm_unlink'),
			position: {
				my: 'center center',
				at: 'center center'
			},
			strings: {
				confirm: gugurPro.translate('unlink'),
				cancel: gugurPro.translate('cancel')
			},
			onConfirm: function onConfirm() {
				self.getOption('editedView').unlink();
			}
		});
	},

	initUnlinkDialog: function initUnlinkDialog() {
		var dialog;

		this.getUnlinkDialog = function () {
			if (!dialog) {
				dialog = this.buildUnlinkDialog();
			}

			return dialog;
		};
	},

	editGlobalModel: function editGlobalModel() {
		var editedView = this.getOption('editedView');

		$e.run('panel/editor/open', {
			model: editedView.getEditModel(),
			view: editedView
		});
	},

	onEditButtonClick: function onEditButtonClick() {
		var self = this,
		    editedView = self.getOption('editedView'),
		    editedModel = editedView.getEditModel();

		if ('loaded' === editedModel.get('settingsLoadedStatus')) {
			self.editGlobalModel();

			return;
		}

		self.ui.loading.removeClass('gugur-hidden');

		gugurPro.modules.globalWidget.requestGlobalModelSettings(editedModel, function () {
			self.ui.loading.addClass('gugur-hidden');

			self.editGlobalModel();
		});
	},

	onUnlinkButtonClick: function onUnlinkButtonClick() {
		this.getUnlinkDialog().show();
	}
});

/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	ongugurInit: function ongugurInit() {
		gugur.channels.editor.on('section:activated', this.onSectionActivated);
	},

	onSectionActivated: function onSectionActivated(sectionName, editor) {
		var editedElement = editor.getOption('editedElementView');

		if ('flip-box' !== editedElement.model.get('widgetType')) {
			return;
		}

		var isSideBSection = -1 !== ['section_side_b_content', 'section_style_b'].indexOf(sectionName);

		editedElement.$el.toggleClass('gugur-flip-box--flipped', isSideBSection);

		var $backLayer = editedElement.$el.find('.gugur-flip-box__back');

		if (isSideBSection) {
			$backLayer.css('transition', 'none');
		}

		if (!isSideBSection) {
			setTimeout(function () {
				$backLayer.css('transition', '');
			}, 10);
		}
	}
});

/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	config: gugurPro.config.shareButtonsNetworks,

	networksClassDictionary: {
		google: 'fa fab fa-google-plus',
		pocket: 'fa fab fa-get-pocket',
		email: 'fa fas fa-envelope'
	},

	getNetworkClass: function getNetworkClass(networkName) {
		return this.networksClassDictionary[networkName] || 'fa fab fa-' + networkName;
	},

	getNetworkTitle: function getNetworkTitle(buttonSettings) {
		return buttonSettings.text || this.config[buttonSettings.button].title;
	},

	hasCounter: function hasCounter(networkName, settings) {
		return 'icon' !== settings.view && 'yes' === settings.show_counter && this.config[networkName].has_counter;
	}
});

/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	ongugurInit: function ongugurInit() {
		var FontsManager = __webpack_require__(54);

		this.assets = {
			font: new FontsManager()
		};
	}
});

/***/ }),
/* 54 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.Module.extend({

	_enqueuedFonts: [],
	_enqueuedTypekit: false,

	onFontChange: function onFontChange(fontType, font) {
		if ('custom' !== fontType && 'typekit' !== fontType) {
			return;
		}

		if (-1 !== this._enqueuedFonts.indexOf(font)) {
			return;
		}

		if ('typekit' === fontType && this._enqueuedTypekit) {
			return;
		}

		this.getCustomFont(fontType, font);
	},

	getCustomFont: function getCustomFont(fontType, font) {
		gugurPro.ajax.addRequest('assets_manager_panel_action_data', {
			unique_id: 'font_' + fontType + font,
			data: {
				service: 'font',
				type: fontType,
				font: font
			},
			success: function success(data) {
				if (data.font_face) {
					gugur.$previewContents.find('style:last').after('<style type="text/css">' + data.font_face + '</style>');
				}

				if (data.font_url) {
					gugur.$previewContents.find('link:last').after('<link href="' + data.font_url + '" rel="stylesheet" type="text/css">');
				}
			}
		});

		this._enqueuedFonts.push(font);

		if ('typekit' === fontType) {
			this._enqueuedTypekit = true;
		}
	},

	onInit: function onInit() {
		gugur.channels.editor.on('font:insertion', this.onFontChange.bind(this));
	}
});

/***/ }),
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.utils.Module.extend({
	ongugurPreviewLoaded: function ongugurPreviewLoaded() {
		var CommentsSkin = __webpack_require__(56);
		this.commentsSkin = new CommentsSkin();
	}
});

/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function () {
	var self = this;

	self.onPanelShow = function (panel, model) {
		var settingsModel = model.get('settings');

		// If no skins - set the skin to `theme_comments`.
		if (!settingsModel.controls._skin.default) {
			settingsModel.set('_skin', 'theme_comments');
		}
	};

	self.init = function () {
		gugur.hooks.addAction('panel/open_editor/widget/post-comments', self.onPanelShow);
	};

	self.init();
};

/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (!gugurProEditorConfig.useComponentsRouter) {
	module.exports = __webpack_require__(58);
} else {
	var Component = __webpack_require__(64);

	module.exports = gugurModules.editor.utils.Module.extend({

		ongugurInit: function ongugurInit() {
			if (!gugurPro.config.theme_builder) {
				return;
			}

			gugur.channels.editor.on('page_settings:preview_settings:activated', this.onSectionPreviewSettingsActive);

			gugur.addControlView('Conditions_repeater', __webpack_require__(7));

			gugur.hooks.addFilter('panel/footer/behaviors', this.addFooterBehavior);

			gugur.saver.on('save', this.onEditorSave);

			this.setConditionsModel();

			this.component = $e.components.register(new Component.default({ manager: this }));
		},

		addFooterBehavior: function addFooterBehavior(behaviors) {
			behaviors.saver = {
				behaviorClass: __webpack_require__(67)
			};

			return behaviors;
		},

		saveAndReload: function saveAndReload() {
			gugur.saver.saveAutoSave({
				onSuccess: function onSuccess() {
					gugur.dynamicTags.cleanCache();
					gugur.reloadPreview();
				}
			});
		},

		onApplyPreview: function onApplyPreview() {
			this.saveAndReload();
		},

		onSectionPreviewSettingsActive: function onSectionPreviewSettingsActive() {
			this.updatePreviewIdOptions(true);
		},

		onPageSettingsChange: function onPageSettingsChange(model) {
			if (model.changed.preview_type) {
				model.set({
					preview_id: '',
					preview_search_term: ''
				});

				if ($e.routes.is('panel/page-settings/settings')) {
					this.updatePreviewIdOptions(true);
				}
			}

			if (!_.isUndefined(model.changed.page_template)) {
				gugur.saver.saveAutoSave({
					onSuccess: function onSuccess() {
						gugur.reloadPreview();

						gugur.once('preview:loaded', function () {
							$e.route('panel/page-settings/settings');
						});
					}
				});
			}
		},

		updatePreviewIdOptions: function updatePreviewIdOptions(render) {
			var previewType = gugur.settings.page.model.get('preview_type');

			if (!previewType) {
				return;
			}
			previewType = previewType.split('/');

			var currentView = gugur.getPanelView().getCurrentPageView(),
			    controlModel = currentView.collection.findWhere({
				name: 'preview_id'
			});

			if ('author' === previewType[1]) {
				controlModel.set({
					autocomplete: {
						object: 'author'
					}
				});
			} else if ('taxonomy' === previewType[0]) {
				controlModel.set({
					autocomplete: {
						object: 'tax',
						query: {
							taxonomy: previewType[1]
						}
					}
				});
			} else if ('single' === previewType[0]) {
				controlModel.set({
					autocomplete: {
						object: 'post',
						query: {
							post_type: previewType[1]
						}
					}
				});
			} else {
				controlModel.set({
					autocomplete: {
						object: ''
					}
				});
			}

			if (true === render) {
				// Can be model.
				var controlView = currentView.children.findByModel(controlModel);

				controlView.render();

				controlView.$el.toggle(!!controlModel.get('autocomplete').object);
			}
		},

		ongugurPreviewLoaded: function ongugurPreviewLoaded() {
			if (!gugurPro.config.theme_builder) {
				return;
			}

			gugur.getPanelView().on('set:page:page_settings', this.updatePreviewIdOptions);

			gugur.settings.page.model.on('change', this.onPageSettingsChange.bind(this));

			gugur.channels.editor.on('gugurThemeBuilder:ApplyPreview', this.onApplyPreview.bind(this));

			// Scroll to Editor. Timeout according to preview resize css animation duration.
			setTimeout(function () {
				gugur.$previewContents.find('html, body').animate({
					scrollTop: gugur.$previewContents.find('#gugur').offset().top - gugur.$preview[0].contentWindow.innerHeight / 2
				});
			}, 500);
		},

		setConditionsModel: function setConditionsModel() {
			var themeBuilderModuleConfig = gugurPro.config.theme_builder,
			    settings = themeBuilderModuleConfig.settings;

			this.conditionsModel = new gugurModules.editor.elements.models.BaseSettings(settings, {
				controls: themeBuilderModuleConfig.template_conditions.controls
			});
		},

		onEditorSave: function onEditorSave() {
			var _this = this;

			if (!this.conditionsModel) {
				return;
			}

			gugurPro.ajax.addRequest('theme_builder_save_conditions', {
				data: this.conditionsModel.toJSON({ remove: ['default'] }),
				success: function success() {
					gugurPro.config.theme_builder.settings.conditions = _this.conditionsModel.get('conditions');
				}
			});
		}
	});
}

/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _layout = __webpack_require__(59);

var _layout2 = _interopRequireDefault(_layout);

var _view = __webpack_require__(6);

var _view2 = _interopRequireDefault(_view);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

module.exports = gugurModules.editor.utils.Module.extend({
	ongugurInit: function ongugurInit() {
		if (!gugurPro.config.theme_builder) {
			return;
		}

		gugur.channels.editor.on('page_settings:preview_settings:activated', this.onSectionPreviewSettingsActive);

		gugur.addControlView('Conditions_repeater', __webpack_require__(7));

		gugur.hooks.addFilter('panel/footer/behaviors', this.addFooterBehavior);

		gugur.saver.on('save', this.onEditorSave);

		this.initPublishLayout();
	},

	addFooterBehavior: function addFooterBehavior(behaviors) {
		behaviors.saver = {
			behaviorClass: __webpack_require__(63)
		};

		return behaviors;
	},

	saveAndReload: function saveAndReload() {
		gugur.saver.saveAutoSave({
			onSuccess: function onSuccess() {
				gugur.dynamicTags.cleanCache();
				gugur.reloadPreview();
			}
		});
	},

	onApplyPreview: function onApplyPreview() {
		this.saveAndReload();
	},

	onSectionPreviewSettingsActive: function onSectionPreviewSettingsActive() {
		this.updatePreviewIdOptions(true);
	},

	onPageSettingsChange: function onPageSettingsChange(model) {
		if (model.changed.preview_type) {
			model.set({
				preview_id: '',
				preview_search_term: ''
			});

			if ('page_settings' === gugur.getPanelView().getCurrentPageName()) {
				this.updatePreviewIdOptions(true);
			}
		}

		if (!_.isUndefined(model.changed.page_template)) {
			gugur.saver.saveAutoSave({
				onSuccess: function onSuccess() {
					gugur.reloadPreview();

					gugur.once('preview:loaded', function () {
						gugur.getPanelView().setPage('page_settings');
					});
				}
			});
		}
	},

	updatePreviewIdOptions: function updatePreviewIdOptions(render) {
		var previewType = gugur.settings.page.model.get('preview_type');

		if (!previewType) {
			return;
		}
		previewType = previewType.split('/');

		var currentView = gugur.getPanelView().getCurrentPageView(),
		    controlModel = currentView.collection.findWhere({
			name: 'preview_id'
		});

		if ('author' === previewType[1]) {
			controlModel.set({
				autocomplete: {
					object: 'author'
				}
			});
		} else if ('taxonomy' === previewType[0]) {
			controlModel.set({
				autocomplete: {
					object: 'tax',
					query: {
						taxonomy: previewType[1]
					}
				}
			});
		} else if ('single' === previewType[0]) {
			controlModel.set({
				autocomplete: {
					object: 'post',
					query: {
						post_type: previewType[1]
					}
				}
			});
		} else {
			controlModel.set({
				autocomplete: {
					object: ''
				}
			});
		}

		if (true === render) {
			// Can be model.
			var controlView = currentView.children.findByModel(controlModel);

			controlView.render();

			controlView.$el.toggle(!!controlModel.get('autocomplete').object);
		}
	},

	ongugurPreviewLoaded: function ongugurPreviewLoaded() {
		if (!gugurPro.config.theme_builder) {
			return;
		}

		gugur.getPanelView().on('set:page:page_settings', this.updatePreviewIdOptions);

		gugur.settings.page.model.on('change', this.onPageSettingsChange.bind(this));

		gugur.channels.editor.on('gugurThemeBuilder:ApplyPreview', this.onApplyPreview.bind(this));

		// Scroll to Editor. Timeout according to preview resize css animation duration.
		setTimeout(function () {
			gugur.$previewContents.find('html, body').animate({
				scrollTop: gugur.$previewContents.find('#gugur').offset().top - gugur.$preview[0].contentWindow.innerHeight / 2
			});
		}, 500);
	},

	showPublishModal: function showPublishModal() {
		this.getPublishLayout().showModal();
	},

	initPublishLayout: function initPublishLayout() {
		var _this = this;

		var publishLayout = void 0;

		this.getPublishLayout = function () {
			if (!publishLayout) {
				publishLayout = new _layout2.default();

				_this.trigger('publish-layout:init', publishLayout);
			}

			return publishLayout;
		};

		this.on('publish-layout:init', this.addConditionsScreen);
	},

	addConditionsScreen: function addConditionsScreen(publishLayout) {
		var themeBuilderModuleConfig = gugurPro.config.theme_builder,
		    settings = themeBuilderModuleConfig.settings;

		this.conditionsModel = new gugurModules.editor.elements.models.BaseSettings(settings, {
			controls: themeBuilderModuleConfig.template_conditions.controls
		});

		publishLayout.addScreen({
			View: _view2.default,
			viewOptions: {
				model: this.conditionsModel,
				controls: this.conditionsModel.controls
			},
			name: 'conditions',
			title: gugurPro.translate('conditions'),
			description: gugurPro.translate('conditions_publish_screen_description'),
			image: gugurPro.config.urls.modules + 'theme-builder/assets/images/conditions-tab.svg'
		});
	},

	onEditorSave: function onEditorSave() {
		var _this2 = this;

		if (!this.conditionsModel) {
			return;
		}

		gugurPro.ajax.addRequest('theme_builder_save_conditions', {
			data: this.conditionsModel.toJSON({ remove: ['default'] }),
			success: function success() {
				gugurPro.config.theme_builder.settings.conditions = _this2.conditionsModel.get('conditions');
			}
		});
	}
});

/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _content = __webpack_require__(60);

var _content2 = _interopRequireDefault(_content);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$com) {
	_inherits(_class, _gugurModules$com);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'initialize',
		value: function initialize() {
			var _get2;

			for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
				args[_key] = arguments[_key];
			}

			(_get2 = _get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'initialize', this)).call.apply(_get2, [this].concat(args));

			this.screens = [];
		}
	}, {
		key: 'getModalOptions',
		value: function getModalOptions() {
			return {
				id: 'gugur-publish__modal',
				hide: {
					onButtonClick: false
				}
			};
		}
	}, {
		key: 'getLogoOptions',
		value: function getLogoOptions() {
			return {
				title: gugurPro.translate('publish_settings')
			};
		}
	}, {
		key: 'initModal',
		value: function initModal() {
			var _this2 = this;

			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'initModal', this).call(this);

			this.modal.addButton({
				name: 'publish',
				text: gugurPro.translate('save_and_close'),
				callback: function callback() {
					return _this2.onPublishButtonClick();
				}
			});

			this.modal.addButton({
				name: 'next',
				text: gugurPro.translate('next'),
				callback: function callback() {
					return _this2.onNextButtonClick();
				}
			});

			var $publishButton = this.modal.getElements('publish');

			this.modal.getElements('next').addClass('gugur-button-success').add($publishButton).addClass('gugur-button').removeClass('dialog-button');
		}
	}, {
		key: 'addScreen',
		value: function addScreen(screenData, at) {
			var index = undefined !== at ? at : this.screens.length;

			this.screens.splice(index, 0, screenData);
		}
	}, {
		key: 'showModal',
		value: function showModal() {
			if (!this.layoutInitialized) {
				this.showLogo();

				this.showContentView();

				this.layoutInitialized = true;
			}

			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'showModal', this).call(this);
		}
	}, {
		key: 'showContentView',
		value: function showContentView() {
			var _this3 = this;

			var contentView = new _content2.default({ screens: this.screens });

			contentView.on('screen:change', function (screen) {
				return _this3.onContentViewScreenChange(screen);
			});

			this.modalContent.show(contentView);
		}
	}, {
		key: 'onNextButtonClick',
		value: function onNextButtonClick() {
			var currentScreenIndex = this.screens.indexOf(this.modalContent.currentView.currentScreen);

			this.modalContent.currentView.showScreenByIndex(currentScreenIndex + 1);
		}
	}, {
		key: 'onPublishButtonClick',
		value: function onPublishButtonClick() {
			gugur.saver.defaultSave();

			this.hideModal();
		}
	}, {
		key: 'onContentViewScreenChange',
		value: function onContentViewScreenChange(screen) {
			var isLastScreen = this.screens.indexOf(screen) === this.screens.length - 1;

			this.modal.getElements('next').toggle(!isLastScreen);

			this.modal.getElements('publish').toggleClass('gugur-button-success', isLastScreen);
		}
	}]);

	return _class;
}(gugurModules.common.views.modal.Layout);

exports.default = _class;

/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$LayoutVie) {
	_inherits(_class, _Marionette$LayoutVie);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'id',
		value: function id() {
			return 'gugur-publish';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return Marionette.TemplateCache.get('#tmpl-gugur-publish');
		}
	}, {
		key: 'ui',
		value: function ui() {
			return {
				tabs: '.gugur-publish__tab'
			};
		}
	}, {
		key: 'events',
		value: function events() {
			return {
				'click @ui.tabs': 'onTabClick'
			};
		}
	}, {
		key: 'regions',
		value: function regions() {
			return {
				screen: '#gugur-publish__screen'
			};
		}
	}, {
		key: 'templateHelpers',
		value: function templateHelpers() {
			return {
				screens: this.screens
			};
		}
	}, {
		key: 'initialize',
		value: function initialize() {
			this.screens = this.getOption('screens');
		}
	}, {
		key: 'showScreenByName',
		value: function showScreenByName(screenName) {
			var screen = _.findWhere(this.screens, { name: screenName });

			this.showScreen(screen);
		}
	}, {
		key: 'showScreenByIndex',
		value: function showScreenByIndex(index) {
			this.showScreen(this.screens[index]);
		}
	}, {
		key: 'showScreen',
		value: function showScreen(screen) {
			this.screen.show(new screen.View(screen.viewOptions));

			if (this.ui.tabs.length) {
				if (this.$currentTab) {
					this.$currentTab.removeClass('gugur-active');
				}

				this.$currentTab = this.ui.tabs.filter('[data-screen="' + screen.name + '"]');

				this.$currentTab.addClass('gugur-active');
			}

			this.currentScreen = screen;

			this.trigger('screen:change', screen);
		}
	}, {
		key: 'onRender',
		value: function onRender() {
			this.showScreenByIndex(0);
		}
	}, {
		key: 'onTabClick',
		value: function onTabClick(event) {
			this.showScreenByName(event.currentTarget.dataset.screen);
		}
	}]);

	return _class;
}(Marionette.LayoutView);

exports.default = _class;

/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugurModules.editor.views.ControlsStack.extend({
	activeTab: 'content',

	activeSection: 'settings',

	initialize: function initialize() {
		this.collection = new Backbone.Collection(_.values(this.options.controls));
	},

	filter: function filter(model) {
		if ('section' === model.get('type')) {
			return true;
		}

		var section = model.get('section');

		return !section || section === this.activeSection;
	},

	childViewOptions: function childViewOptions() {
		return {
			elementSettingsModel: this.model
		};
	}
});

/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = gugur.modules.controls.RepeaterRow.extend({

	template: '#tmpl-gugur-theme-builder-conditions-repeater-row',

	childViewContainer: '.gugur-theme-builder-conditions-repeater-row-controls',

	id: function id() {
		return 'gugur-condition-id-' + this.model.get('_id');
	},

	onBeforeRender: function onBeforeRender() {
		var subNameModel = this.collection.findWhere({
			name: 'sub_name'
		}),
		    subIdModel = this.collection.findWhere({
			name: 'sub_id'
		}),
		    subConditionConfig = this.config.conditions[this.model.attributes.sub_name];

		subNameModel.attributes.groups = this.getOptions();

		if (subConditionConfig && subConditionConfig.controls) {
			_(subConditionConfig.controls).each(function (control) {
				subIdModel.set(control);
				subIdModel.set('name', 'sub_id');
			});
		}
	},

	initialize: function initialize() {
		gugur.modules.controls.RepeaterRow.prototype.initialize.apply(this, arguments);

		this.config = gugurPro.config.theme_builder;
	},

	updateOptions: function updateOptions() {
		if (this.model.changed.name) {
			this.model.set({
				sub_name: '',
				sub_id: ''
			});
		}

		if (this.model.changed.name || this.model.changed.sub_name) {
			this.model.set('sub_id', '', { silent: true });

			var subIdModel = this.collection.findWhere({
				name: 'sub_id'
			});

			subIdModel.set({
				type: 'select',
				options: {
					'': 'All'
				}
			});

			this.render();
		}

		if (this.model.changed.type) {
			this.setTypeAttribute();
		}
	},

	getOptions: function getOptions() {
		var self = this,
		    conditionConfig = self.config.conditions[this.model.get('name')];

		if (!conditionConfig) {
			return;
		}

		var options = {
			'': conditionConfig.all_label
		};

		_(conditionConfig.sub_conditions).each(function (conditionId, conditionIndex) {
			var subConditionConfig = self.config.conditions[conditionId],
			    group;

			if (!subConditionConfig) {
				return;
			}

			if (subConditionConfig.sub_conditions.length) {
				group = {
					label: subConditionConfig.label,
					options: {}
				};
				group.options[conditionId] = subConditionConfig.all_label;

				_(subConditionConfig.sub_conditions).each(function (subConditionId) {
					group.options[subConditionId] = self.config.conditions[subConditionId].label;
				});

				// Use a sting key - to keep order
				options['key' + conditionIndex] = group;
			} else {
				options[conditionId] = subConditionConfig.label;
			}
		});

		return options;
	},

	setTypeAttribute: function setTypeAttribute() {
		var typeView = this.children.findByModel(this.collection.findWhere({ name: 'type' }));

		typeView.$el.attr('data-gugur-condition-type', typeView.getControlValue());
	},

	onRender: function onRender() {
		var nameModel = this.collection.findWhere({
			name: 'name'
		}),
		    subNameModel = this.collection.findWhere({
			name: 'sub_name'
		}),
		    subIdModel = this.collection.findWhere({
			name: 'sub_id'
		}),
		    nameView = this.children.findByModel(nameModel),
		    subNameView = this.children.findByModel(subNameModel),
		    subIdView = this.children.findByModel(subIdModel),
		    conditionConfig = this.config.conditions[this.model.attributes.name],
		    subConditionConfig = this.config.conditions[this.model.attributes.sub_name],
		    typeConfig = this.config.types[this.config.settings.template_type];

		if (typeConfig.condition_type === nameView.getControlValue() && 'general' !== nameView.getControlValue() && !_.isEmpty(conditionConfig.sub_conditions)) {
			nameView.$el.hide();
		}

		if (!conditionConfig || _.isEmpty(conditionConfig.sub_conditions) && _.isEmpty(conditionConfig.controls) || !nameView.getControlValue() || 'general' === nameView.getControlValue()) {
			subNameView.$el.hide();
		}

		if (!subConditionConfig || _.isEmpty(subConditionConfig.controls) || !subNameView.getControlValue()) {
			subIdView.$el.hide();
		}

		// Avoid set a `single` for a-l-l singular types. (conflicted with 404 & custom cpt like Shops and Events plugins).
		if ('singular' === typeConfig.condition_type) {
			if ('' === subNameView.getControlValue()) {
				subNameView.setValue('post');
			}
		}

		this.setTypeAttribute();
	},

	onModelChange: function onModelChange() {
		gugur.modules.controls.RepeaterRow.prototype.onModelChange.apply(this, arguments);

		this.updateOptions();
	}
});

/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var SaverBehavior = gugur.modules.components.saver.behaviors.FooterSaver;

module.exports = SaverBehavior.extend({
	ui: function ui() {
		var ui = SaverBehavior.prototype.ui.apply(this, arguments);

		ui.menuConditions = '#gugur-panel-footer-sub-menu-item-conditions';
		ui.buttonPreviewSettings = '#gugur-panel-footer-theme-builder-button-preview-settings';
		ui.buttonOpenPreview = '#gugur-panel-footer-theme-builder-button-open-preview';

		return ui;
	},

	events: function events() {
		var events = SaverBehavior.prototype.events.apply(this, arguments);

		delete events['click @ui.buttonPreview'];

		events['click @ui.buttonPreviewSettings'] = 'onClickButtonPreviewSettings';
		events['click @ui.buttonOpenPreview'] = 'onClickButtonPreview';

		return events;
	},

	initialize: function initialize() {
		SaverBehavior.prototype.initialize.apply(this, arguments);

		gugur.settings.page.model.on('change', this.onChangeLocation.bind(this));
	},

	toggleMenuConditions: function toggleMenuConditions() {
		this.ui.menuConditions.toggle(!!gugurPro.config.theme_builder.settings.location);
	},

	onRender: function onRender() {
		SaverBehavior.prototype.onRender.apply(this, arguments);

		this.ui.menuConditions = this.view.addSubMenuItem('saver-options', {
			before: 'save-template',
			name: 'conditions',
			icon: 'eicon-flow',
			title: gugurPro.translate('display_conditions'),
			callback: function callback() {
				return gugurPro.modules.themeBuilder.showPublishModal();
			}
		});

		this.toggleMenuConditions();

		this.ui.buttonPreview.tipsy('disable').html(jQuery('#tmpl-gugur-theme-builder-button-preview').html()).addClass('gugur-panel-footer-theme-builder-buttons-wrapper gugur-toggle-state');
	},

	onChangeLocation: function onChangeLocation(settings) {
		if (!_.isUndefined(settings.changed.location)) {
			gugurPro.config.theme_builder.settings.location = settings.changed.location;
			this.toggleMenuConditions();
		}
	},

	onClickButtonPublish: function onClickButtonPublish() {
		var hasConditions = gugurPro.config.theme_builder.settings.conditions.length,
		    hasLocation = gugurPro.config.theme_builder.settings.location,
		    isDraft = 'draft' === gugur.settings.page.model.get('post_status');

		if (hasConditions && !isDraft || !hasLocation) {
			SaverBehavior.prototype.onClickButtonPublish.apply(this, arguments);
		} else {
			gugurPro.modules.themeBuilder.showPublishModal();
		}
	},

	onClickButtonPreviewSettings: function onClickButtonPreviewSettings() {
		var panel = gugur.getPanelView();
		panel.setPage('page_settings');
		panel.getCurrentPageView().activateSection('preview_settings');
		panel.getCurrentPageView()._renderChildren();
	}
});

/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _content = __webpack_require__(65);

var _content2 = _interopRequireDefault(_content);

var _layout = __webpack_require__(66);

var _layout2 = _interopRequireDefault(_layout);

var _view = __webpack_require__(6);

var _view2 = _interopRequireDefault(_view);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$com) {
	_inherits(_class, _gugurModules$com);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'getNamespace',
		value: function getNamespace() {
			return 'theme-builder-publish';
		}
	}, {
		key: 'getModalLayout',
		value: function getModalLayout() {
			return _layout2.default;
		}
	}, {
		key: 'defaultTabs',
		value: function defaultTabs() {
			return {
				conditions: {
					title: gugurPro.translate('conditions'),
					View: _view2.default,
					viewOptions: {
						model: this.manager.conditionsModel,
						controls: this.manager.conditionsModel.controls
					},
					name: 'conditions',
					description: gugurPro.translate('conditions_publish_screen_description'),
					image: gugurPro.config.urls.modules + 'theme-builder/assets/images/conditions-tab.svg'
				}
			};
		}
	}, {
		key: 'defaultCommands',
		value: function defaultCommands() {
			var _this2 = this;

			return {
				next: function next() {
					var tabs = Object.keys(_this2.tabs),
					    next = tabs[_this2.currentTabIndex + 1];

					if (next) {
						$e.route(_this2.getTabRoute(next));
					}
				},

				save: function save() {
					gugur.saver.defaultSave();

					_this2.layout.hideModal();
				}
			};
		}
	}, {
		key: 'getTabsWrapperSelector',
		value: function getTabsWrapperSelector() {
			return '#gugur-publish__tabs';
		}
	}, {
		key: 'renderTab',
		value: function renderTab(tab) {
			var tabs = this.getTabs(),
			    keys = Object.keys(tabs),
			    tabArgs = tabs[tab];

			this.currentTabIndex = keys.indexOf(tab);

			var isLastTab = !keys[this.currentTabIndex + 1];

			this.layout.modalContent.currentView.screen.show(new tabArgs.View(tabArgs.viewOptions));

			this.layout.modal.getElements('next').toggle(!isLastTab);

			this.layout.modal.getElements('publish').toggleClass('gugur-button-success', isLastTab);
		}
	}, {
		key: 'activateTab',
		value: function activateTab(tab) {
			$e.routes.saveState(this.getNamespace());

			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'activateTab', this).call(this, tab);
		}
	}, {
		key: 'open',
		value: function open() {
			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'open', this).call(this);

			if (!this.layoutContent) {
				this.layout.showLogo();
				this.layout.modalContent.show(new _content2.default({ component: this }));
				this.layoutContent = true;
			}

			return true;
		}
	}]);

	return _class;
}(gugurModules.common.ComponentModal);

exports.default = _class;

/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_Marionette$LayoutVie) {
	_inherits(_class, _Marionette$LayoutVie);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'id',
		value: function id() {
			return 'gugur-publish';
		}
	}, {
		key: 'getTemplate',
		value: function getTemplate() {
			return Marionette.TemplateCache.get('#tmpl-gugur-component-publish');
		}
	}, {
		key: 'regions',
		value: function regions() {
			return {
				screen: '#gugur-publish__screen'
			};
		}
	}, {
		key: 'templateHelpers',
		value: function templateHelpers() {
			return {
				tabs: this.getOption('component').getTabs()
			};
		}
	}]);

	return _class;
}(Marionette.LayoutView);

exports.default = _class;

/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_gugurModules$com) {
	_inherits(_class, _gugurModules$com);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'getModalOptions',
		value: function getModalOptions() {
			return {
				id: 'gugur-publish__modal',
				hide: {
					onButtonClick: false
				}
			};
		}
	}, {
		key: 'getLogoOptions',
		value: function getLogoOptions() {
			return {
				title: gugurPro.translate('publish_settings')
			};
		}
	}, {
		key: 'initModal',
		value: function initModal() {
			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'initModal', this).call(this);

			this.modal.addButton({
				name: 'publish',
				text: gugurPro.translate('save_and_close'),
				callback: function callback() {
					return $e.run('theme-builder-publish/save');
				}
			});

			this.modal.addButton({
				name: 'next',
				text: gugurPro.translate('next'),
				callback: function callback() {
					return $e.run('theme-builder-publish/next');
				}
			});

			var $publishButton = this.modal.getElements('publish');

			this.modal.getElements('next').addClass('gugur-button-success').add($publishButton).addClass('gugur-button').removeClass('dialog-button');
		}
	}]);

	return _class;
}(gugurModules.common.views.modal.Layout);

exports.default = _class;

/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var SaverBehavior = gugur.modules.components.saver.behaviors.FooterSaver;

module.exports = SaverBehavior.extend({
	ui: function ui() {
		var ui = SaverBehavior.prototype.ui.apply(this, arguments);

		ui.menuConditions = '#gugur-panel-footer-sub-menu-item-conditions';
		ui.buttonPreviewSettings = '#gugur-panel-footer-theme-builder-button-preview-settings';
		ui.buttonOpenPreview = '#gugur-panel-footer-theme-builder-button-open-preview';

		return ui;
	},

	events: function events() {
		var events = SaverBehavior.prototype.events.apply(this, arguments);

		delete events['click @ui.buttonPreview'];

		events['click @ui.buttonPreviewSettings'] = 'onClickButtonPreviewSettings';
		events['click @ui.buttonOpenPreview'] = 'onClickButtonPreview';

		return events;
	},

	initialize: function initialize() {
		SaverBehavior.prototype.initialize.apply(this, arguments);

		gugur.settings.page.model.on('change', this.onChangeLocation.bind(this));
	},

	toggleMenuConditions: function toggleMenuConditions() {
		this.ui.menuConditions.toggle(!!gugurPro.config.theme_builder.settings.location);
	},

	onRender: function onRender() {
		SaverBehavior.prototype.onRender.apply(this, arguments);

		this.ui.menuConditions = this.view.addSubMenuItem('saver-options', {
			before: 'save-template',
			name: 'conditions',
			icon: 'eicon-flow',
			title: gugurPro.translate('display_conditions'),
			callback: function callback() {
				return $e.route('theme-builder-publish/conditions');
			}
		});

		this.toggleMenuConditions();

		this.ui.buttonPreview.tipsy('disable').html(jQuery('#tmpl-gugur-theme-builder-button-preview').html()).addClass('gugur-panel-footer-theme-builder-buttons-wrapper gugur-toggle-state');
	},

	onChangeLocation: function onChangeLocation(settings) {
		if (!_.isUndefined(settings.changed.location)) {
			gugurPro.config.theme_builder.settings.location = settings.changed.location;
			this.toggleMenuConditions();
		}
	},

	onClickButtonPublish: function onClickButtonPublish() {
		var hasConditions = gugurPro.config.theme_builder.settings.conditions.length,
		    hasLocation = gugurPro.config.theme_builder.settings.location,
		    isDraft = 'draft' === gugur.settings.page.model.get('post_status');

		if (hasConditions && !isDraft || !hasLocation) {
			SaverBehavior.prototype.onClickButtonPublish.apply(this, arguments);
		} else {
			$e.route('theme-builder-publish/conditions');
		}
	},

	onClickButtonPreviewSettings: function onClickButtonPreviewSettings() {
		var panel = gugur.getPanelView();
		$e.route('panel/page-settings/settings');
		panel.getCurrentPageView().activateSection('preview_settings')._renderChildren();
	}
});

/***/ })
/******/ ]);
//# sourceMappingURL=editor.js.map