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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__map_global_block_js__ = __webpack_require__(/*! ./map-global/block.js */ 13);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__map_location_block_js__ = __webpack_require__(/*! ./map-location/block.js */ 16);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__map_tour_block_js__ = __webpack_require__(/*! ./map-tour/block.js */ 19);\n/**\n * Gutenberg Blocks\n *\n * All blocks related JavaScript files should be imported here.\n * You can create a new block folder in this dir and include code\n * for that block here as well.\n *\n * All blocks should be included here since this is the file that\n * Webpack is compiling as the input file.\n */\n\n\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9ja3MuanM/N2I1YiJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEd1dGVuYmVyZyBCbG9ja3NcbiAqXG4gKiBBbGwgYmxvY2tzIHJlbGF0ZWQgSmF2YVNjcmlwdCBmaWxlcyBzaG91bGQgYmUgaW1wb3J0ZWQgaGVyZS5cbiAqIFlvdSBjYW4gY3JlYXRlIGEgbmV3IGJsb2NrIGZvbGRlciBpbiB0aGlzIGRpciBhbmQgaW5jbHVkZSBjb2RlXG4gKiBmb3IgdGhhdCBibG9jayBoZXJlIGFzIHdlbGwuXG4gKlxuICogQWxsIGJsb2NrcyBzaG91bGQgYmUgaW5jbHVkZWQgaGVyZSBzaW5jZSB0aGlzIGlzIHRoZSBmaWxlIHRoYXRcbiAqIFdlYnBhY2sgaXMgY29tcGlsaW5nIGFzIHRoZSBpbnB1dCBmaWxlLlxuICovXG5cbmltcG9ydCAnLi9tYXAtZ2xvYmFsL2Jsb2NrLmpzJztcbmltcG9ydCAnLi9tYXAtbG9jYXRpb24vYmxvY2suanMnO1xuaW1wb3J0ICcuL21hcC10b3VyL2Jsb2NrLmpzJztcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9ibG9ja3MuanNcbi8vIG1vZHVsZSBpZCA9IDBcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */
/*!*********************************!*\
  !*** ./src/map-global/block.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 14);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 15);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n\n\n\nvar __ = wp.i18n.__;\nvar _wp$blocks = wp.blocks,\n    registerBlockType = _wp$blocks.registerBlockType,\n    getBlockDefaultClassName = _wp$blocks.getBlockDefaultClassName;\nvar _wp$editor = wp.editor,\n    PlainText = _wp$editor.PlainText,\n    InspectorControls = _wp$editor.InspectorControls;\n\n\nregisterBlockType('placepress/block-map-global', {\n    title: __('Map: Global'),\n    icon: 'location-alt',\n    category: 'placepress',\n    keywords: [__('Map'), __('Global'), __('PlacePress')],\n    supports: {\n        anchor: true,\n        html: false,\n        multiple: false,\n        reusable: false\n    },\n    description: __('A block for adding the global map.'),\n    attributes: {\n        content: {\n            type: 'string',\n            selector: '.map-global-pp'\n        }\n    },\n    edit: function edit(props) {\n        var className = props.className,\n            setAttributes = props.setAttributes;\n        var attributes = props.attributes;\n\n\n        function changeContent(changes) {\n            setAttributes({\n                content: changes\n            });\n        }\n        return wp.element.createElement(\n            'div',\n            { className: props.className },\n            wp.element.createElement(PlainText, {\n                className: 'map-global-pp',\n                tagName: 'p',\n                placeholder: __(\"Enter something here.\", 'wp_placepress'),\n                value: attributes.content,\n                onChange: changeContent\n            })\n        );\n    },\n    save: function save(props) {\n        var className = getBlockDefaultClassName('placepress/block-map-global');\n        var attributes = props.attributes;\n\n\n        return wp.element.createElement(\n            'div',\n            { className: className },\n            wp.element.createElement(\n                'p',\n                { 'class': 'map-global-pp' },\n                attributes.content\n            )\n        );\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTMuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLWdsb2JhbC9ibG9jay5qcz9iZmJjIl0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCAnLi9zdHlsZS5zY3NzJztcbmltcG9ydCAnLi9lZGl0b3Iuc2Nzcyc7XG5cbnZhciBfXyA9IHdwLmkxOG4uX187XG52YXIgX3dwJGJsb2NrcyA9IHdwLmJsb2NrcyxcbiAgICByZWdpc3RlckJsb2NrVHlwZSA9IF93cCRibG9ja3MucmVnaXN0ZXJCbG9ja1R5cGUsXG4gICAgZ2V0QmxvY2tEZWZhdWx0Q2xhc3NOYW1lID0gX3dwJGJsb2Nrcy5nZXRCbG9ja0RlZmF1bHRDbGFzc05hbWU7XG52YXIgX3dwJGVkaXRvciA9IHdwLmVkaXRvcixcbiAgICBQbGFpblRleHQgPSBfd3AkZWRpdG9yLlBsYWluVGV4dCxcbiAgICBJbnNwZWN0b3JDb250cm9scyA9IF93cCRlZGl0b3IuSW5zcGVjdG9yQ29udHJvbHM7XG5cblxucmVnaXN0ZXJCbG9ja1R5cGUoJ3BsYWNlcHJlc3MvYmxvY2stbWFwLWdsb2JhbCcsIHtcbiAgICB0aXRsZTogX18oJ01hcDogR2xvYmFsJyksXG4gICAgaWNvbjogJ2xvY2F0aW9uLWFsdCcsXG4gICAgY2F0ZWdvcnk6ICdwbGFjZXByZXNzJyxcbiAgICBrZXl3b3JkczogW19fKCdNYXAnKSwgX18oJ0dsb2JhbCcpLCBfXygnUGxhY2VQcmVzcycpXSxcbiAgICBzdXBwb3J0czoge1xuICAgICAgICBhbmNob3I6IHRydWUsXG4gICAgICAgIGh0bWw6IGZhbHNlLFxuICAgICAgICBtdWx0aXBsZTogZmFsc2UsXG4gICAgICAgIHJldXNhYmxlOiBmYWxzZVxuICAgIH0sXG4gICAgZGVzY3JpcHRpb246IF9fKCdBIGJsb2NrIGZvciBhZGRpbmcgdGhlIGdsb2JhbCBtYXAuJyksXG4gICAgYXR0cmlidXRlczoge1xuICAgICAgICBjb250ZW50OiB7XG4gICAgICAgICAgICB0eXBlOiAnc3RyaW5nJyxcbiAgICAgICAgICAgIHNlbGVjdG9yOiAnLm1hcC1nbG9iYWwtcHAnXG4gICAgICAgIH1cbiAgICB9LFxuICAgIGVkaXQ6IGZ1bmN0aW9uIGVkaXQocHJvcHMpIHtcbiAgICAgICAgdmFyIGNsYXNzTmFtZSA9IHByb3BzLmNsYXNzTmFtZSxcbiAgICAgICAgICAgIHNldEF0dHJpYnV0ZXMgPSBwcm9wcy5zZXRBdHRyaWJ1dGVzO1xuICAgICAgICB2YXIgYXR0cmlidXRlcyA9IHByb3BzLmF0dHJpYnV0ZXM7XG5cblxuICAgICAgICBmdW5jdGlvbiBjaGFuZ2VDb250ZW50KGNoYW5nZXMpIHtcbiAgICAgICAgICAgIHNldEF0dHJpYnV0ZXMoe1xuICAgICAgICAgICAgICAgIGNvbnRlbnQ6IGNoYW5nZXNcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAnZGl2JyxcbiAgICAgICAgICAgIHsgY2xhc3NOYW1lOiBwcm9wcy5jbGFzc05hbWUgfSxcbiAgICAgICAgICAgIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChQbGFpblRleHQsIHtcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtYXAtZ2xvYmFsLXBwJyxcbiAgICAgICAgICAgICAgICB0YWdOYW1lOiAncCcsXG4gICAgICAgICAgICAgICAgcGxhY2Vob2xkZXI6IF9fKFwiRW50ZXIgc29tZXRoaW5nIGhlcmUuXCIsICd3cF9wbGFjZXByZXNzJyksXG4gICAgICAgICAgICAgICAgdmFsdWU6IGF0dHJpYnV0ZXMuY29udGVudCxcbiAgICAgICAgICAgICAgICBvbkNoYW5nZTogY2hhbmdlQ29udGVudFxuICAgICAgICAgICAgfSlcbiAgICAgICAgKTtcbiAgICB9LFxuICAgIHNhdmU6IGZ1bmN0aW9uIHNhdmUocHJvcHMpIHtcbiAgICAgICAgdmFyIGNsYXNzTmFtZSA9IGdldEJsb2NrRGVmYXVsdENsYXNzTmFtZSgncGxhY2VwcmVzcy9ibG9jay1tYXAtZ2xvYmFsJyk7XG4gICAgICAgIHZhciBhdHRyaWJ1dGVzID0gcHJvcHMuYXR0cmlidXRlcztcblxuXG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAnZGl2JyxcbiAgICAgICAgICAgIHsgY2xhc3NOYW1lOiBjbGFzc05hbWUgfSxcbiAgICAgICAgICAgIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcbiAgICAgICAgICAgICAgICAncCcsXG4gICAgICAgICAgICAgICAgeyAnY2xhc3MnOiAnbWFwLWdsb2JhbC1wcCcgfSxcbiAgICAgICAgICAgICAgICBhdHRyaWJ1dGVzLmNvbnRlbnRcbiAgICAgICAgICAgIClcbiAgICAgICAgKTtcbiAgICB9XG59KTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9tYXAtZ2xvYmFsL2Jsb2NrLmpzXG4vLyBtb2R1bGUgaWQgPSAxM1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiJBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///13\n");

/***/ }),
/* 14 */
/*!***********************************!*\
  !*** ./src/map-global/style.scss ***!
  \***********************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTQuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLWdsb2JhbC9zdHlsZS5zY3NzPzk3ZWUiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9tYXAtZ2xvYmFsL3N0eWxlLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDE0XG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///14\n");

/***/ }),
/* 15 */
/*!************************************!*\
  !*** ./src/map-global/editor.scss ***!
  \************************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTUuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLWdsb2JhbC9lZGl0b3Iuc2Nzcz80MDdmIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvbWFwLWdsb2JhbC9lZGl0b3Iuc2Nzc1xuLy8gbW9kdWxlIGlkID0gMTVcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///15\n");

/***/ }),
/* 16 */
/*!***********************************!*\
  !*** ./src/map-location/block.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 17);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 18);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n\n\n\nvar __ = wp.i18n.__;\nvar _wp$blocks = wp.blocks,\n    registerBlockType = _wp$blocks.registerBlockType,\n    getBlockDefaultClassName = _wp$blocks.getBlockDefaultClassName;\nvar _wp$editor = wp.editor,\n    PlainText = _wp$editor.PlainText,\n    InspectorControls = _wp$editor.InspectorControls;\n\n\nregisterBlockType('placepress/block-map-location', {\n    title: __('Map: Location'),\n    icon: 'location',\n    category: 'placepress',\n    keywords: [__('Map'), __('Location'), __('PlacePress')],\n    supports: {\n        anchor: true,\n        html: false,\n        multiple: false,\n        reusable: false\n    },\n    description: __('A block for adding a location map.'),\n    attributes: {\n        content: {\n            type: 'string',\n            selector: '.map-location-pp'\n        }\n    },\n    edit: function edit(props) {\n        var className = props.className,\n            setAttributes = props.setAttributes;\n        var attributes = props.attributes;\n\n\n        function changeContent(changes) {\n            setAttributes({\n                content: changes\n            });\n        }\n        return wp.element.createElement(\n            'div',\n            { className: props.className },\n            wp.element.createElement(PlainText, {\n                className: 'map-location-pp',\n                tagName: 'p',\n                placeholder: __(\"Enter coordinates here.\", 'wp_placepress'),\n                value: attributes.content,\n                onChange: changeContent\n            })\n        );\n    },\n    save: function save(props) {\n        var className = getBlockDefaultClassName('placepress/block-map-location');\n        var attributes = props.attributes;\n\n\n        return wp.element.createElement(\n            'div',\n            { className: className },\n            wp.element.createElement(\n                'p',\n                { 'class': 'map-location-pp' },\n                attributes.content\n            )\n        );\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTYuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLWxvY2F0aW9uL2Jsb2NrLmpzPzYyM2MiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0ICcuL3N0eWxlLnNjc3MnO1xuaW1wb3J0ICcuL2VkaXRvci5zY3NzJztcblxudmFyIF9fID0gd3AuaTE4bi5fXztcbnZhciBfd3AkYmxvY2tzID0gd3AuYmxvY2tzLFxuICAgIHJlZ2lzdGVyQmxvY2tUeXBlID0gX3dwJGJsb2Nrcy5yZWdpc3RlckJsb2NrVHlwZSxcbiAgICBnZXRCbG9ja0RlZmF1bHRDbGFzc05hbWUgPSBfd3AkYmxvY2tzLmdldEJsb2NrRGVmYXVsdENsYXNzTmFtZTtcbnZhciBfd3AkZWRpdG9yID0gd3AuZWRpdG9yLFxuICAgIFBsYWluVGV4dCA9IF93cCRlZGl0b3IuUGxhaW5UZXh0LFxuICAgIEluc3BlY3RvckNvbnRyb2xzID0gX3dwJGVkaXRvci5JbnNwZWN0b3JDb250cm9scztcblxuXG5yZWdpc3RlckJsb2NrVHlwZSgncGxhY2VwcmVzcy9ibG9jay1tYXAtbG9jYXRpb24nLCB7XG4gICAgdGl0bGU6IF9fKCdNYXA6IExvY2F0aW9uJyksXG4gICAgaWNvbjogJ2xvY2F0aW9uJyxcbiAgICBjYXRlZ29yeTogJ3BsYWNlcHJlc3MnLFxuICAgIGtleXdvcmRzOiBbX18oJ01hcCcpLCBfXygnTG9jYXRpb24nKSwgX18oJ1BsYWNlUHJlc3MnKV0sXG4gICAgc3VwcG9ydHM6IHtcbiAgICAgICAgYW5jaG9yOiB0cnVlLFxuICAgICAgICBodG1sOiBmYWxzZSxcbiAgICAgICAgbXVsdGlwbGU6IGZhbHNlLFxuICAgICAgICByZXVzYWJsZTogZmFsc2VcbiAgICB9LFxuICAgIGRlc2NyaXB0aW9uOiBfXygnQSBibG9jayBmb3IgYWRkaW5nIGEgbG9jYXRpb24gbWFwLicpLFxuICAgIGF0dHJpYnV0ZXM6IHtcbiAgICAgICAgY29udGVudDoge1xuICAgICAgICAgICAgdHlwZTogJ3N0cmluZycsXG4gICAgICAgICAgICBzZWxlY3RvcjogJy5tYXAtbG9jYXRpb24tcHAnXG4gICAgICAgIH1cbiAgICB9LFxuICAgIGVkaXQ6IGZ1bmN0aW9uIGVkaXQocHJvcHMpIHtcbiAgICAgICAgdmFyIGNsYXNzTmFtZSA9IHByb3BzLmNsYXNzTmFtZSxcbiAgICAgICAgICAgIHNldEF0dHJpYnV0ZXMgPSBwcm9wcy5zZXRBdHRyaWJ1dGVzO1xuICAgICAgICB2YXIgYXR0cmlidXRlcyA9IHByb3BzLmF0dHJpYnV0ZXM7XG5cblxuICAgICAgICBmdW5jdGlvbiBjaGFuZ2VDb250ZW50KGNoYW5nZXMpIHtcbiAgICAgICAgICAgIHNldEF0dHJpYnV0ZXMoe1xuICAgICAgICAgICAgICAgIGNvbnRlbnQ6IGNoYW5nZXNcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAnZGl2JyxcbiAgICAgICAgICAgIHsgY2xhc3NOYW1lOiBwcm9wcy5jbGFzc05hbWUgfSxcbiAgICAgICAgICAgIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChQbGFpblRleHQsIHtcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICdtYXAtbG9jYXRpb24tcHAnLFxuICAgICAgICAgICAgICAgIHRhZ05hbWU6ICdwJyxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogX18oXCJFbnRlciBjb29yZGluYXRlcyBoZXJlLlwiLCAnd3BfcGxhY2VwcmVzcycpLFxuICAgICAgICAgICAgICAgIHZhbHVlOiBhdHRyaWJ1dGVzLmNvbnRlbnQsXG4gICAgICAgICAgICAgICAgb25DaGFuZ2U6IGNoYW5nZUNvbnRlbnRcbiAgICAgICAgICAgIH0pXG4gICAgICAgICk7XG4gICAgfSxcbiAgICBzYXZlOiBmdW5jdGlvbiBzYXZlKHByb3BzKSB7XG4gICAgICAgIHZhciBjbGFzc05hbWUgPSBnZXRCbG9ja0RlZmF1bHRDbGFzc05hbWUoJ3BsYWNlcHJlc3MvYmxvY2stbWFwLWxvY2F0aW9uJyk7XG4gICAgICAgIHZhciBhdHRyaWJ1dGVzID0gcHJvcHMuYXR0cmlidXRlcztcblxuXG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAnZGl2JyxcbiAgICAgICAgICAgIHsgY2xhc3NOYW1lOiBjbGFzc05hbWUgfSxcbiAgICAgICAgICAgIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcbiAgICAgICAgICAgICAgICAncCcsXG4gICAgICAgICAgICAgICAgeyAnY2xhc3MnOiAnbWFwLWxvY2F0aW9uLXBwJyB9LFxuICAgICAgICAgICAgICAgIGF0dHJpYnV0ZXMuY29udGVudFxuICAgICAgICAgICAgKVxuICAgICAgICApO1xuICAgIH1cbn0pO1xuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL21hcC1sb2NhdGlvbi9ibG9jay5qc1xuLy8gbW9kdWxlIGlkID0gMTZcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///16\n");

/***/ }),
/* 17 */
/*!*************************************!*\
  !*** ./src/map-location/style.scss ***!
  \*************************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTcuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLWxvY2F0aW9uL3N0eWxlLnNjc3M/ZjdjMSJdLCJzb3VyY2VzQ29udGVudCI6WyIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL21hcC1sb2NhdGlvbi9zdHlsZS5zY3NzXG4vLyBtb2R1bGUgaWQgPSAxN1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiJBQUFBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///17\n");

/***/ }),
/* 18 */
/*!**************************************!*\
  !*** ./src/map-location/editor.scss ***!
  \**************************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTguanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLWxvY2F0aW9uL2VkaXRvci5zY3NzPzE5N2EiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9tYXAtbG9jYXRpb24vZWRpdG9yLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDE4XG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///18\n");

/***/ }),
/* 19 */
/*!*******************************!*\
  !*** ./src/map-tour/block.js ***!
  \*******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 20);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 21);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n\n\n\nvar __ = wp.i18n.__;\nvar _wp$blocks = wp.blocks,\n    registerBlockType = _wp$blocks.registerBlockType,\n    getBlockDefaultClassName = _wp$blocks.getBlockDefaultClassName;\nvar _wp$editor = wp.editor,\n    PlainText = _wp$editor.PlainText,\n    InspectorControls = _wp$editor.InspectorControls;\n\n\nregisterBlockType('placepress/block-map-tour', {\n    title: __('Map: Tour'),\n    icon: 'location',\n    category: 'placepress',\n    keywords: [__('Map'), __('Tour'), __('PlacePress')],\n    supports: {\n        anchor: true,\n        html: false,\n        multiple: false,\n        reusable: false\n    },\n    description: __('A block for adding a tour map.'),\n    attributes: {\n        content: {\n            type: 'string',\n            selector: '.map-location-pp'\n        }\n    },\n    edit: function edit(props) {\n        var className = props.className,\n            setAttributes = props.setAttributes;\n        var attributes = props.attributes;\n\n\n        function changeContent(changes) {\n            setAttributes({\n                content: changes\n            });\n        }\n        return wp.element.createElement(\n            'div',\n            { className: props.className },\n            wp.element.createElement(PlainText, {\n                className: 'map-tour-pp',\n                tagName: 'p',\n                placeholder: __(\"Enter something else here.\", 'wp_placepress'),\n                value: attributes.content,\n                onChange: changeContent\n            })\n        );\n    },\n    save: function save(props) {\n        var className = getBlockDefaultClassName('placepress/block-map-tour');\n        var attributes = props.attributes;\n\n\n        return wp.element.createElement(\n            'div',\n            { className: className },\n            wp.element.createElement(\n                'p',\n                { 'class': 'map-tour-pp' },\n                attributes.content\n            )\n        );\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMTkuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLXRvdXIvYmxvY2suanM/ODI5MyJdLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgJy4vc3R5bGUuc2Nzcyc7XG5pbXBvcnQgJy4vZWRpdG9yLnNjc3MnO1xuXG52YXIgX18gPSB3cC5pMThuLl9fO1xudmFyIF93cCRibG9ja3MgPSB3cC5ibG9ja3MsXG4gICAgcmVnaXN0ZXJCbG9ja1R5cGUgPSBfd3AkYmxvY2tzLnJlZ2lzdGVyQmxvY2tUeXBlLFxuICAgIGdldEJsb2NrRGVmYXVsdENsYXNzTmFtZSA9IF93cCRibG9ja3MuZ2V0QmxvY2tEZWZhdWx0Q2xhc3NOYW1lO1xudmFyIF93cCRlZGl0b3IgPSB3cC5lZGl0b3IsXG4gICAgUGxhaW5UZXh0ID0gX3dwJGVkaXRvci5QbGFpblRleHQsXG4gICAgSW5zcGVjdG9yQ29udHJvbHMgPSBfd3AkZWRpdG9yLkluc3BlY3RvckNvbnRyb2xzO1xuXG5cbnJlZ2lzdGVyQmxvY2tUeXBlKCdwbGFjZXByZXNzL2Jsb2NrLW1hcC10b3VyJywge1xuICAgIHRpdGxlOiBfXygnTWFwOiBUb3VyJyksXG4gICAgaWNvbjogJ2xvY2F0aW9uJyxcbiAgICBjYXRlZ29yeTogJ3BsYWNlcHJlc3MnLFxuICAgIGtleXdvcmRzOiBbX18oJ01hcCcpLCBfXygnVG91cicpLCBfXygnUGxhY2VQcmVzcycpXSxcbiAgICBzdXBwb3J0czoge1xuICAgICAgICBhbmNob3I6IHRydWUsXG4gICAgICAgIGh0bWw6IGZhbHNlLFxuICAgICAgICBtdWx0aXBsZTogZmFsc2UsXG4gICAgICAgIHJldXNhYmxlOiBmYWxzZVxuICAgIH0sXG4gICAgZGVzY3JpcHRpb246IF9fKCdBIGJsb2NrIGZvciBhZGRpbmcgYSB0b3VyIG1hcC4nKSxcbiAgICBhdHRyaWJ1dGVzOiB7XG4gICAgICAgIGNvbnRlbnQ6IHtcbiAgICAgICAgICAgIHR5cGU6ICdzdHJpbmcnLFxuICAgICAgICAgICAgc2VsZWN0b3I6ICcubWFwLWxvY2F0aW9uLXBwJ1xuICAgICAgICB9XG4gICAgfSxcbiAgICBlZGl0OiBmdW5jdGlvbiBlZGl0KHByb3BzKSB7XG4gICAgICAgIHZhciBjbGFzc05hbWUgPSBwcm9wcy5jbGFzc05hbWUsXG4gICAgICAgICAgICBzZXRBdHRyaWJ1dGVzID0gcHJvcHMuc2V0QXR0cmlidXRlcztcbiAgICAgICAgdmFyIGF0dHJpYnV0ZXMgPSBwcm9wcy5hdHRyaWJ1dGVzO1xuXG5cbiAgICAgICAgZnVuY3Rpb24gY2hhbmdlQ29udGVudChjaGFuZ2VzKSB7XG4gICAgICAgICAgICBzZXRBdHRyaWJ1dGVzKHtcbiAgICAgICAgICAgICAgICBjb250ZW50OiBjaGFuZ2VzXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuICAgICAgICAgICAgJ2RpdicsXG4gICAgICAgICAgICB7IGNsYXNzTmFtZTogcHJvcHMuY2xhc3NOYW1lIH0sXG4gICAgICAgICAgICB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoUGxhaW5UZXh0LCB7XG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnbWFwLXRvdXItcHAnLFxuICAgICAgICAgICAgICAgIHRhZ05hbWU6ICdwJyxcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogX18oXCJFbnRlciBzb21ldGhpbmcgZWxzZSBoZXJlLlwiLCAnd3BfcGxhY2VwcmVzcycpLFxuICAgICAgICAgICAgICAgIHZhbHVlOiBhdHRyaWJ1dGVzLmNvbnRlbnQsXG4gICAgICAgICAgICAgICAgb25DaGFuZ2U6IGNoYW5nZUNvbnRlbnRcbiAgICAgICAgICAgIH0pXG4gICAgICAgICk7XG4gICAgfSxcbiAgICBzYXZlOiBmdW5jdGlvbiBzYXZlKHByb3BzKSB7XG4gICAgICAgIHZhciBjbGFzc05hbWUgPSBnZXRCbG9ja0RlZmF1bHRDbGFzc05hbWUoJ3BsYWNlcHJlc3MvYmxvY2stbWFwLXRvdXInKTtcbiAgICAgICAgdmFyIGF0dHJpYnV0ZXMgPSBwcm9wcy5hdHRyaWJ1dGVzO1xuXG5cbiAgICAgICAgcmV0dXJuIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcbiAgICAgICAgICAgICdkaXYnLFxuICAgICAgICAgICAgeyBjbGFzc05hbWU6IGNsYXNzTmFtZSB9LFxuICAgICAgICAgICAgd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuICAgICAgICAgICAgICAgICdwJyxcbiAgICAgICAgICAgICAgICB7ICdjbGFzcyc6ICdtYXAtdG91ci1wcCcgfSxcbiAgICAgICAgICAgICAgICBhdHRyaWJ1dGVzLmNvbnRlbnRcbiAgICAgICAgICAgIClcbiAgICAgICAgKTtcbiAgICB9XG59KTtcblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9tYXAtdG91ci9ibG9jay5qc1xuLy8gbW9kdWxlIGlkID0gMTlcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///19\n");

/***/ }),
/* 20 */
/*!*********************************!*\
  !*** ./src/map-tour/style.scss ***!
  \*********************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMjAuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLXRvdXIvc3R5bGUuc2Nzcz8xY2NkIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvbWFwLXRvdXIvc3R5bGUuc2Nzc1xuLy8gbW9kdWxlIGlkID0gMjBcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///20\n");

/***/ }),
/* 21 */
/*!**********************************!*\
  !*** ./src/map-tour/editor.scss ***!
  \**********************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMjEuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvbWFwLXRvdXIvZWRpdG9yLnNjc3M/NDBiOSJdLCJzb3VyY2VzQ29udGVudCI6WyIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL21hcC10b3VyL2VkaXRvci5zY3NzXG4vLyBtb2R1bGUgaWQgPSAyMVxuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiJBQUFBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///21\n");

/***/ })
/******/ ]);