(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.string.match.js */ "./node_modules/core-js/modules/es.string.match.js");
/* harmony import */ var core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _bootstrap_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./bootstrap.js */ "./assets/bootstrap.js");
/* harmony import */ var _styles_app_css__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./styles/app.css */ "./assets/styles/app.css");
/* harmony import */ var _styles_app_css__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_styles_app_css__WEBPACK_IMPORTED_MODULE_4__);





/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
document.addEventListener('DOMContentLoaded', function () {
  // Language Toggle
  var langToggle = document.getElementById('language-toggle');
  var themeToggle = document.getElementById('theme-toggle');
  if (langToggle) {
    var _document$cookie$matc;
    var currentLang = ((_document$cookie$matc = document.cookie.match(/locale=([^;]+)/)) === null || _document$cookie$matc === void 0 ? void 0 : _document$cookie$matc[1]) || 'en';
    var circle = langToggle.querySelector('.bg-white');
    if (currentLang === 'fr') {
      circle.style.transform = 'translateX(20px)';
    }
    langToggle.setAttribute('data-lang', currentLang);
    langToggle.addEventListener('click', function () {
      var newLang = this.getAttribute('data-lang') === 'en' ? 'fr' : 'en';
      circle.style.transform = newLang === 'fr' ? 'translateX(20px)' : 'translateX(0)';
      this.setAttribute('data-lang', newLang);
      document.cookie = "locale=".concat(newLang, ";path=/;max-age=").concat(30 * 24 * 60 * 60);
      location.reload(); // Refresh the page to apply the new language
    });
  }

  // Theme Toggle
  if (themeToggle) {
    var currentTheme = localStorage.getItem('theme') || 'light';
    var _circle = themeToggle.querySelector('.bg-white');
    if (currentTheme === 'dark') {
      _circle.style.transform = 'translateX(20px)';
      document.documentElement.classList.add('dark');
    }
    themeToggle.setAttribute('data-theme', currentTheme);
    themeToggle.addEventListener('click', function () {
      var newTheme = this.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
      _circle.style.transform = newTheme === 'dark' ? 'translateX(20px)' : 'translateX(0)';
      this.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      document.documentElement.classList.toggle('dark');
    });
  }
});

// document.addEventListener('DOMContentLoaded', () => {
//     const themeToggle = document.querySelector('.theme-toggle');
//     const htmlElement = document.documentElement;

//     if (themeToggle) {
//         // Check for saved theme preference
//         const savedTheme = localStorage.getItem('theme');
//         if (savedTheme) {
//             htmlElement.classList.add(savedTheme);
//         }

//         themeToggle.addEventListener('click', () => {
//             htmlElement.classList.toggle('dark');
//             const isDark = htmlElement.classList.contains('dark');
//             localStorage.setItem('theme', isDark ? 'dark' : 'light');
//         });
//     }
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const menuButton = document.getElementById('menuButton');
//     const closeMenu = document.getElementById('closeMenu');
//     const mobileMenu = document.querySelector('.mobile-menu');
//     const langToggle = document.getElementById('langToggle');
//     const sliderButton = document.querySelector('.slider-button');
//     let currentLang = 'en';

//     menuButton.addEventListener('click', () => {
//         mobileMenu.classList.add('active');
//     });

//     closeMenu.addEventListener('click', () => {
//         mobileMenu.classList.remove('active');
//     });

//     langToggle.addEventListener('click', () => {
//         currentLang = currentLang === 'en' ? 'fr' : 'en';
//         sliderButton.classList.toggle('fr');

//         // Assuming you're using Symfony's translation
//         // fetch(`/change-locale/${currentLang}`, {
//         //     method: 'POST',
//         //     headers: {
//         //         'X-Requested-With': 'XMLHttpRequest'
//         //     }
//         // }).then(() => {
//         //     window.location.reload();
//         // });
//     });
// });

// document.addEventListener('DOMContentLoaded', () => {
//     const languageToggle = document.getElementById('languageToggle');
//     const languageSlider = document.getElementById('languageSlider');
//     const themeToggle = document.getElementById('themeToggle');
//     const themeSlider = document.getElementById('themeSlider');

//     // Language toggle
//     languageToggle.addEventListener('click', () => {
//         const currentLang = languageSlider.dataset.currentLang;
//         const newLang = currentLang === 'en' ? 'fr' : 'en';

//         languageSlider.style.transform = newLang === 'fr' ? 'translateX(48px)' : 'translateX(0)';

//         fetch(`/switch-language/${newLang}`, {
//             method: 'GET',
//             headers: {'X-Requested-With': 'XMLHttpRequest'}
//         }).then(() => window.location.reload());
//     });

//     // Theme toggle
//     themeToggle.addEventListener('click', () => {
//         const currentTheme = themeSlider.dataset.currentTheme;
//         const newTheme = currentTheme === 'light' ? 'dark' : 'light';

//         themeSlider.style.transform = newTheme === 'dark' ? 'translateX(48px)' : 'translateX(0)';
//         document.documentElement.classList.toggle('dark');

//         fetch(`/switch-theme/${newTheme}`, {
//             method: 'GET',
//             headers: {'X-Requested-With': 'XMLHttpRequest'}
//         });
//     });

//     // Initialize positions
//     if (languageSlider.dataset.currentLang === 'fr') {
//         languageSlider.style.transform = 'translateX(48px)';
//     }
//     if (themeSlider.dataset.currentTheme === 'dark') {
//         themeSlider.style.transform = 'translateX(48px)';
//     }
// });

// Seperated unto 

// document.getElementById("language-toggle").addEventListener("click", () => {
//     // Toggle between 'en' and 'fr'
//     let currentLang = document.getElementById("language-toggle").getAttribute("data-lang");
//     let newLang = currentLang === "en" ? "fr" : "en";
//     document.getElementById("language-toggle").setAttribute("data-lang", newLang);

//     // Set the locale cookie for Symfony (expires in 30 days)
//     document.cookie = `locale=${newLang};path=/;max-age=${30*24*60*60}`;

//     // Reload the page so Symfony loads the new locale
//     location.reload();
//   });

// document.addEventListener("DOMContentLoaded", () => {
//   const themeToggle = document.getElementById("theme-toggle");
//   const themeSlider = document.getElementById("theme-slider");
//   const sunIcon = document.getElementById("sun-icon");
//   const moonIcon = document.getElementById("moon-icon");

//   // Optionally, read the saved theme from localStorage
//   const savedTheme = localStorage.getItem("theme");
//   if (savedTheme === "dark") {
//     document.documentElement.classList.add("dark");
//     themeSlider.classList.add("translate-x-3.5");
//     sunIcon.parentElement.classList.add("opacity-0");
//     moonIcon.parentElement.classList.remove("opacity-0");
//   }

//   themeToggle.addEventListener("click", () => {
//     // Toggle dark mode class on the root element
//     document.documentElement.classList.toggle("dark");
//     let isDark = document.documentElement.classList.contains("dark");

//     // Animate the slider (assumes translate-x-0 for light and translate-x-3.5 for dark)
//     if (isDark) {
//       themeSlider.classList.remove("translate-x-0");
//       themeSlider.classList.add("translate-x-3.5");
//       // Toggle icon visibility
//       sunIcon.parentElement.classList.add("opacity-0");
//       moonIcon.parentElement.classList.remove("opacity-0");
//       localStorage.setItem("theme", "dark");
//     } else {
//       themeSlider.classList.remove("translate-x-3.5");
//       themeSlider.classList.add("translate-x-0");
//       // Toggle icon visibility
//       sunIcon.parentElement.classList.remove("opacity-0");
//       moonIcon.parentElement.classList.add("opacity-0");
//       localStorage.setItem("theme", "light");
//     }
//   });
// });

// Slider JS
//   document.addEventListener("DOMContentLoaded", () => {
//     const toggleSwitch = document.querySelector("[role='switch']");
//     const toggleCircle = toggleSwitch.querySelector("span[aria-hidden='true']");

//     toggleSwitch.addEventListener("click", () => {
//         const isChecked = toggleSwitch.getAttribute("aria-checked") === "true";

//         // Toggle aria-checked
//         toggleSwitch.setAttribute("aria-checked", isChecked ? "false" : "true");

//         // Toggle Tailwind classes
//         toggleCircle.classList.toggle("translate-x-0", isChecked);
//         toggleCircle.classList.toggle("translate-x-3.5", !isChecked);

//         // Optional: Do something when toggled
//         console.log(`Switch is now: ${isChecked ? "Off" : "On"}`);
//     });
// });

/***/ }),

/***/ "./assets/bootstrap.js":
/*!*****************************!*\
  !*** ./assets/bootstrap.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   app: () => (/* binding */ app)
/* harmony export */ });
/* harmony import */ var _symfony_stimulus_bridge__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @symfony/stimulus-bridge */ "./node_modules/@symfony/stimulus-bridge/dist/index.js");


// Registers Stimulus controllers from controllers.json and in the controllers/ directory
var app = (0,_symfony_stimulus_bridge__WEBPACK_IMPORTED_MODULE_0__.startStimulusApp)(__webpack_require__("./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$"));
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

/***/ }),

/***/ "./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$":
/*!****************************************************************************************************************!*\
  !*** ./assets/controllers/ sync ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \.[jt]sx?$ ***!
  \****************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./csrf_protection_controller.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/csrf_protection_controller.js",
	"./hello_controller.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/hello_controller.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$";

/***/ }),

/***/ "./assets/styles/app.css":
/*!*******************************!*\
  !*** ./assets/styles/app.css ***!
  \*******************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nHookWebpackError: Module build failed (from ./node_modules/postcss-loader/dist/cjs.js):\nError: Cannot apply unknown utility class: bg-gray-900\n    at onInvalidCandidate (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:17:347)\n    at ne (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:12:115998)\n    at $e (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:17:310)\n    at Br (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:33:780)\n    at async qr (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:33:1071)\n    at async ot (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/node/dist/index.js:10:3272)\n    at async p (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/postcss/dist/index.js:8:3242)\n    at async Object.Once (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/postcss/dist/index.js:8:3443)\n    at async LazyResult.runAsync (/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss/lib/lazy-result.js:293:11)\n    at async Object.loader (/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss-loader/dist/index.js:84:14)\n    at tryRunOrWebpackError (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/HookWebpackError.js:86:9)\n    at __webpack_require_module__ (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5301:12)\n    at __webpack_require__ (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5258:18)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5330:20\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3485:9)\n    at done (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3527:9)\n    at Hook.eval [as callAsync] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:33:10), <anonymous>:15:1)\n    at Hook.CALL_ASYNC_DELEGATE [as _callAsync] (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/Hook.js:18:14)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5236:43\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3463:5)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5198:16\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3485:9)\n    at timesSync (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3463:5)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5166:15\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3485:9)\n    at done (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3527:9)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5112:8\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:3531:6\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/HookWebpackError.js:67:2\n    at Hook.eval [as callAsync] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:33:10), <anonymous>:15:1)\n    at Cache.store (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:111:20)\n    at ItemCacheFacade.store (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/CacheFacade.js:141:15)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:3530:11\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:95:34\n    at Array.<anonymous> (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/cache/MemoryCachePlugin.js:45:13)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:95:19\n    at Hook.eval [as callAsync] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:33:10), <anonymous>:19:1)\n    at Cache.get (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:79:18)\n    at ItemCacheFacade.get (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/CacheFacade.js:115:15)\n    at Compilation._codeGenerationModule (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:3498:9)\n    at codeGen (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5100:11)\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3463:5)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5130:14\n    at processQueue (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/util/processAsyncTree.js:61:4)\n    at process.processTicksAndRejections (node:internal/process/task_queues:77:11)\n-- inner error --\nError: Module build failed (from ./node_modules/postcss-loader/dist/cjs.js):\nError: Cannot apply unknown utility class: bg-gray-900\n    at onInvalidCandidate (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:17:347)\n    at ne (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:12:115998)\n    at $e (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:17:310)\n    at Br (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:33:780)\n    at async qr (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:33:1071)\n    at async ot (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/node/dist/index.js:10:3272)\n    at async p (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/postcss/dist/index.js:8:3242)\n    at async Object.Once (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/postcss/dist/index.js:8:3443)\n    at async LazyResult.runAsync (/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss/lib/lazy-result.js:293:11)\n    at async Object.loader (/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss-loader/dist/index.js:84:14)\n    at Object.<anonymous> (/home/davy/Desktop/sygenes/SYGENES/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[1].oneOf[1].use[1]!/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[1].oneOf[1].use[2]!/home/davy/Desktop/sygenes/SYGENES/assets/styles/app.css:1:7)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/javascript/JavascriptModulesPlugin.js:494:10\n    at Hook.eval [as call] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:19:10), <anonymous>:7:1)\n    at Hook.CALL_DELEGATE [as _call] (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/Hook.js:14:14)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5303:39\n    at tryRunOrWebpackError (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/HookWebpackError.js:81:7)\n    at __webpack_require_module__ (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5301:12)\n    at __webpack_require__ (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5258:18)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5330:20\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3485:9)\n    at done (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3527:9)\n    at Hook.eval [as callAsync] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:33:10), <anonymous>:15:1)\n    at Hook.CALL_ASYNC_DELEGATE [as _callAsync] (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/Hook.js:18:14)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5236:43\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3463:5)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5198:16\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3485:9)\n    at timesSync (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3463:5)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5166:15\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3485:9)\n    at done (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3527:9)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5112:8\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:3531:6\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/HookWebpackError.js:67:2\n    at Hook.eval [as callAsync] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:33:10), <anonymous>:15:1)\n    at Cache.store (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:111:20)\n    at ItemCacheFacade.store (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/CacheFacade.js:141:15)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:3530:11\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:95:34\n    at Array.<anonymous> (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/cache/MemoryCachePlugin.js:45:13)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:95:19\n    at Hook.eval [as callAsync] (eval at create (/home/davy/Desktop/sygenes/SYGENES/node_modules/tapable/lib/HookCodeFactory.js:33:10), <anonymous>:19:1)\n    at Cache.get (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Cache.js:79:18)\n    at ItemCacheFacade.get (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/CacheFacade.js:115:15)\n    at Compilation._codeGenerationModule (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:3498:9)\n    at codeGen (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5100:11)\n    at symbolIterator (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3482:9)\n    at timesSync (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:2297:7)\n    at Object.eachLimit (/home/davy/Desktop/sygenes/SYGENES/node_modules/neo-async/async.js:3463:5)\n    at /home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/Compilation.js:5130:14\n    at processQueue (/home/davy/Desktop/sygenes/SYGENES/node_modules/webpack/lib/util/processAsyncTree.js:61:4)\n    at process.processTicksAndRejections (node:internal/process/task_queues:77:11)\n\nGenerated code for /home/davy/Desktop/sygenes/SYGENES/node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[1].oneOf[1].use[1]!/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[1].oneOf[1].use[2]!/home/davy/Desktop/sygenes/SYGENES/assets/styles/app.css\n1 | throw new Error(\"Module build failed (from ./node_modules/postcss-loader/dist/cjs.js):\\nError: Cannot apply unknown utility class: bg-gray-900\\n    at onInvalidCandidate (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:17:347)\\n    at ne (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:12:115998)\\n    at $e (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:17:310)\\n    at Br (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:33:780)\\n    at async qr (/home/davy/Desktop/sygenes/SYGENES/node_modules/tailwindcss/dist/lib.js:33:1071)\\n    at async ot (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/node/dist/index.js:10:3272)\\n    at async p (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/postcss/dist/index.js:8:3242)\\n    at async Object.Once (/home/davy/Desktop/sygenes/SYGENES/node_modules/@tailwindcss/postcss/dist/index.js:8:3443)\\n    at async LazyResult.runAsync (/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss/lib/lazy-result.js:293:11)\\n    at async Object.loader (/home/davy/Desktop/sygenes/SYGENES/node_modules/postcss-loader/dist/index.js:84:14)\");");

/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/dist/webpack/loader.js!./assets/controllers.json":
/*!************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/dist/webpack/loader.js!./assets/controllers.json ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _symfony_ux_turbo_dist_turbo_controller_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @symfony/ux-turbo/dist/turbo_controller.js */ "./node_modules/@symfony/ux-turbo/dist/turbo_controller.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  'symfony--ux-turbo--turbo-core': _symfony_ux_turbo_dist_turbo_controller_js__WEBPACK_IMPORTED_MODULE_0__["default"],
});

/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/csrf_protection_controller.js":
/*!****************************************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/csrf_protection_controller.js ***!
  \****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ controller)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");

const controller = class extends _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller {
    constructor(context) {
        super(context);
        this.__stimulusLazyController = true;
    }
    initialize() {
        if (this.application.controllers.find((controller) => {
            return controller.identifier === this.identifier && controller.__stimulusLazyController;
        })) {
            return;
        }
        Promise.all(/*! import() */[__webpack_require__.e("vendors-node_modules_core-js_modules_es_array-buffer_constructor_js-node_modules_core-js_modu-315700"), __webpack_require__.e("assets_controllers_csrf_protection_controller_js")]).then(__webpack_require__.bind(__webpack_require__, /*! ./assets/controllers/csrf_protection_controller.js */ "./assets/controllers/csrf_protection_controller.js")).then((controller) => {
            this.application.register(this.identifier, controller.default);
        });
    }
};


/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/hello_controller.js":
/*!******************************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/hello_controller.js ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _default)
/* harmony export */ });
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.symbol.js */ "./node_modules/core-js/modules/es.symbol.js");
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.symbol.description.js */ "./node_modules/core-js/modules/es.symbol.description.js");
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_symbol_iterator_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.symbol.iterator.js */ "./node_modules/core-js/modules/es.symbol.iterator.js");
/* harmony import */ var core_js_modules_es_symbol_iterator_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_iterator_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_symbol_to_primitive_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.symbol.to-primitive.js */ "./node_modules/core-js/modules/es.symbol.to-primitive.js");
/* harmony import */ var core_js_modules_es_symbol_to_primitive_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_to_primitive_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.error.cause.js */ "./node_modules/core-js/modules/es.error.cause.js");
/* harmony import */ var core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.error.to-string.js */ "./node_modules/core-js/modules/es.error.to-string.js");
/* harmony import */ var core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.array.iterator.js */ "./node_modules/core-js/modules/es.array.iterator.js");
/* harmony import */ var core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es_date_to_primitive_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/es.date.to-primitive.js */ "./node_modules/core-js/modules/es.date.to-primitive.js");
/* harmony import */ var core_js_modules_es_date_to_primitive_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_date_to_primitive_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! core-js/modules/es.function.bind.js */ "./node_modules/core-js/modules/es.function.bind.js");
/* harmony import */ var core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var core_js_modules_es_number_constructor_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! core-js/modules/es.number.constructor.js */ "./node_modules/core-js/modules/es.number.constructor.js");
/* harmony import */ var core_js_modules_es_number_constructor_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_number_constructor_js__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! core-js/modules/es.object.create.js */ "./node_modules/core-js/modules/es.object.create.js");
/* harmony import */ var core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! core-js/modules/es.object.define-property.js */ "./node_modules/core-js/modules/es.object.define-property.js");
/* harmony import */ var core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! core-js/modules/es.object.get-prototype-of.js */ "./node_modules/core-js/modules/es.object.get-prototype-of.js");
/* harmony import */ var core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! core-js/modules/es.object.proto.js */ "./node_modules/core-js/modules/es.object.proto.js");
/* harmony import */ var core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! core-js/modules/es.object.set-prototype-of.js */ "./node_modules/core-js/modules/es.object.set-prototype-of.js");
/* harmony import */ var core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_15__);
/* harmony import */ var core_js_modules_es_reflect_construct_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! core-js/modules/es.reflect.construct.js */ "./node_modules/core-js/modules/es.reflect.construct.js");
/* harmony import */ var core_js_modules_es_reflect_construct_js__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_reflect_construct_js__WEBPACK_IMPORTED_MODULE_16__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_17___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_17__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_18___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_18__);
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }



















function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(t, e) { if (e && ("object" == _typeof(e) || "function" == typeof e)) return e; if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined"); return _assertThisInitialized(t); }
function _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return e; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }
function _inherits(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, "prototype", { writable: !1 }), e && _setPrototypeOf(t, e); }
function _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }


/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
var _default = /*#__PURE__*/function (_Controller) {
  function _default() {
    _classCallCheck(this, _default);
    return _callSuper(this, _default, arguments);
  }
  _inherits(_default, _Controller);
  return _createClass(_default, [{
    key: "connect",
    value: function connect() {
      this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }
  }]);
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_19__.Controller);


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_symfony_stimulus-bridge_dist_index_js-node_modules_symfony_ux-turbo_dist-4803ab"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFDd0I7O0FBRXhCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUMwQjtBQUUxQkEsT0FBTyxDQUFDQyxHQUFHLENBQUMsZ0VBQWdFLENBQUM7QUFFN0VDLFFBQVEsQ0FBQ0MsZ0JBQWdCLENBQUMsa0JBQWtCLEVBQUUsWUFBWTtFQUN4RDtFQUNBLElBQU1DLFVBQVUsR0FBR0YsUUFBUSxDQUFDRyxjQUFjLENBQUMsaUJBQWlCLENBQUM7RUFDN0QsSUFBTUMsV0FBVyxHQUFHSixRQUFRLENBQUNHLGNBQWMsQ0FBQyxjQUFjLENBQUM7RUFFM0QsSUFBSUQsVUFBVSxFQUFFO0lBQUEsSUFBQUcscUJBQUE7SUFDWixJQUFNQyxXQUFXLEdBQUcsRUFBQUQscUJBQUEsR0FBQUwsUUFBUSxDQUFDTyxNQUFNLENBQUNDLEtBQUssQ0FBQyxnQkFBZ0IsQ0FBQyxjQUFBSCxxQkFBQSx1QkFBdkNBLHFCQUFBLENBQTBDLENBQUMsQ0FBQyxLQUFJLElBQUk7SUFDeEUsSUFBTUksTUFBTSxHQUFHUCxVQUFVLENBQUNRLGFBQWEsQ0FBQyxXQUFXLENBQUM7SUFFcEQsSUFBSUosV0FBVyxLQUFLLElBQUksRUFBRTtNQUN0QkcsTUFBTSxDQUFDRSxLQUFLLENBQUNDLFNBQVMsR0FBRyxrQkFBa0I7SUFDL0M7SUFDQVYsVUFBVSxDQUFDVyxZQUFZLENBQUMsV0FBVyxFQUFFUCxXQUFXLENBQUM7SUFFakRKLFVBQVUsQ0FBQ0QsZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFlBQVk7TUFDN0MsSUFBTWEsT0FBTyxHQUFHLElBQUksQ0FBQ0MsWUFBWSxDQUFDLFdBQVcsQ0FBQyxLQUFLLElBQUksR0FBRyxJQUFJLEdBQUcsSUFBSTtNQUVyRU4sTUFBTSxDQUFDRSxLQUFLLENBQUNDLFNBQVMsR0FBR0UsT0FBTyxLQUFLLElBQUksR0FBRyxrQkFBa0IsR0FBRyxlQUFlO01BQ2hGLElBQUksQ0FBQ0QsWUFBWSxDQUFDLFdBQVcsRUFBRUMsT0FBTyxDQUFDO01BQ3ZDZCxRQUFRLENBQUNPLE1BQU0sYUFBQVMsTUFBQSxDQUFhRixPQUFPLHNCQUFBRSxNQUFBLENBQW1CLEVBQUUsR0FBRyxFQUFFLEdBQUcsRUFBRSxHQUFHLEVBQUUsQ0FBRTtNQUN6RUMsUUFBUSxDQUFDQyxNQUFNLENBQUMsQ0FBQyxDQUFDLENBQUM7SUFDdkIsQ0FBQyxDQUFDO0VBQ047O0VBRUE7RUFDQSxJQUFJZCxXQUFXLEVBQUU7SUFDYixJQUFNZSxZQUFZLEdBQUdDLFlBQVksQ0FBQ0MsT0FBTyxDQUFDLE9BQU8sQ0FBQyxJQUFJLE9BQU87SUFDN0QsSUFBTVosT0FBTSxHQUFHTCxXQUFXLENBQUNNLGFBQWEsQ0FBQyxXQUFXLENBQUM7SUFFckQsSUFBSVMsWUFBWSxLQUFLLE1BQU0sRUFBRTtNQUN6QlYsT0FBTSxDQUFDRSxLQUFLLENBQUNDLFNBQVMsR0FBRyxrQkFBa0I7TUFDM0NaLFFBQVEsQ0FBQ3NCLGVBQWUsQ0FBQ0MsU0FBUyxDQUFDQyxHQUFHLENBQUMsTUFBTSxDQUFDO0lBQ2xEO0lBQ0FwQixXQUFXLENBQUNTLFlBQVksQ0FBQyxZQUFZLEVBQUVNLFlBQVksQ0FBQztJQUVwRGYsV0FBVyxDQUFDSCxnQkFBZ0IsQ0FBQyxPQUFPLEVBQUUsWUFBWTtNQUM5QyxJQUFNd0IsUUFBUSxHQUFHLElBQUksQ0FBQ1YsWUFBWSxDQUFDLFlBQVksQ0FBQyxLQUFLLE9BQU8sR0FBRyxNQUFNLEdBQUcsT0FBTztNQUUvRU4sT0FBTSxDQUFDRSxLQUFLLENBQUNDLFNBQVMsR0FBR2EsUUFBUSxLQUFLLE1BQU0sR0FBRyxrQkFBa0IsR0FBRyxlQUFlO01BQ25GLElBQUksQ0FBQ1osWUFBWSxDQUFDLFlBQVksRUFBRVksUUFBUSxDQUFDO01BQ3pDTCxZQUFZLENBQUNNLE9BQU8sQ0FBQyxPQUFPLEVBQUVELFFBQVEsQ0FBQztNQUN2Q3pCLFFBQVEsQ0FBQ3NCLGVBQWUsQ0FBQ0MsU0FBUyxDQUFDSSxNQUFNLENBQUMsTUFBTSxDQUFDO0lBQ3JELENBQUMsQ0FBQztFQUNOO0FBQ0YsQ0FBQyxDQUFDOztBQUdGO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBR0E7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFHRTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUdBO0FBQ0Y7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7QUN0TzREOztBQUU1RDtBQUNPLElBQU1FLEdBQUcsR0FBR0QsMEVBQWdCLENBQUNFLHlJQUluQyxDQUFDO0FBQ0Y7QUFDQTs7Ozs7Ozs7OztBQ1RBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDdkJzRTtBQUN0RSxpRUFBZTtBQUNmLG1DQUFtQyxrRkFBWTtBQUMvQyxDQUFDOzs7Ozs7Ozs7Ozs7Ozs7O0FDSCtDO0FBQ2hELGlDQUFpQywwREFBVTtBQUMzQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsUUFBUSwwWUFBNkY7QUFDckc7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDaEJnRDs7QUFFaEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBUkEsSUFBQUcsUUFBQSwwQkFBQUMsV0FBQTtFQUFBLFNBQUFELFNBQUE7SUFBQUUsZUFBQSxPQUFBRixRQUFBO0lBQUEsT0FBQUcsVUFBQSxPQUFBSCxRQUFBLEVBQUFJLFNBQUE7RUFBQTtFQUFBQyxTQUFBLENBQUFMLFFBQUEsRUFBQUMsV0FBQTtFQUFBLE9BQUFLLFlBQUEsQ0FBQU4sUUFBQTtJQUFBTyxHQUFBO0lBQUFDLEtBQUEsRUFVSSxTQUFBQyxPQUFPQSxDQUFBLEVBQUc7TUFDTixJQUFJLENBQUNDLE9BQU8sQ0FBQ0MsV0FBVyxHQUFHLG1FQUFtRTtJQUNsRztFQUFDO0FBQUEsRUFId0JaLDJEQUFVIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2FwcC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvYm9vdHN0cmFwLmpzIiwid2VicGFjazovLy8gXFwuW2p0XXN4Iiwid2VicGFjazovLy8uL2Fzc2V0cy9jb250cm9sbGVycy5qc29uIiwid2VicGFjazovLy8uL2Fzc2V0cy9jb250cm9sbGVycy9jc3JmX3Byb3RlY3Rpb25fY29udHJvbGxlci5qcz81YjAzIiwid2VicGFjazovLy8uL2Fzc2V0cy9jb250cm9sbGVycy9oZWxsb19jb250cm9sbGVyLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIlxuaW1wb3J0ICcuL2Jvb3RzdHJhcC5qcyc7XG5cbi8qXG4gKiBXZWxjb21lIHRvIHlvdXIgYXBwJ3MgbWFpbiBKYXZhU2NyaXB0IGZpbGUhXG4gKlxuICogVGhpcyBmaWxlIHdpbGwgYmUgaW5jbHVkZWQgb250byB0aGUgcGFnZSB2aWEgdGhlIGltcG9ydG1hcCgpIFR3aWcgZnVuY3Rpb24sXG4gKiB3aGljaCBzaG91bGQgYWxyZWFkeSBiZSBpbiB5b3VyIGJhc2UuaHRtbC50d2lnLlxuICovXG5pbXBvcnQgJy4vc3R5bGVzL2FwcC5jc3MnO1xuXG5jb25zb2xlLmxvZygnVGhpcyBsb2cgY29tZXMgZnJvbSBhc3NldHMvYXBwLmpzIC0gd2VsY29tZSB0byBBc3NldE1hcHBlciEg8J+OiScpO1xuXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24gKCkge1xuICAvLyBMYW5ndWFnZSBUb2dnbGVcbiAgY29uc3QgbGFuZ1RvZ2dsZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdsYW5ndWFnZS10b2dnbGUnKTtcbiAgY29uc3QgdGhlbWVUb2dnbGUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGhlbWUtdG9nZ2xlJyk7XG5cbiAgaWYgKGxhbmdUb2dnbGUpIHtcbiAgICAgIGNvbnN0IGN1cnJlbnRMYW5nID0gZG9jdW1lbnQuY29va2llLm1hdGNoKC9sb2NhbGU9KFteO10rKS8pPy5bMV0gfHwgJ2VuJztcbiAgICAgIGNvbnN0IGNpcmNsZSA9IGxhbmdUb2dnbGUucXVlcnlTZWxlY3RvcignLmJnLXdoaXRlJyk7XG5cbiAgICAgIGlmIChjdXJyZW50TGFuZyA9PT0gJ2ZyJykge1xuICAgICAgICAgIGNpcmNsZS5zdHlsZS50cmFuc2Zvcm0gPSAndHJhbnNsYXRlWCgyMHB4KSc7XG4gICAgICB9XG4gICAgICBsYW5nVG9nZ2xlLnNldEF0dHJpYnV0ZSgnZGF0YS1sYW5nJywgY3VycmVudExhbmcpO1xuXG4gICAgICBsYW5nVG9nZ2xlLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgIGNvbnN0IG5ld0xhbmcgPSB0aGlzLmdldEF0dHJpYnV0ZSgnZGF0YS1sYW5nJykgPT09ICdlbicgPyAnZnInIDogJ2VuJztcblxuICAgICAgICAgIGNpcmNsZS5zdHlsZS50cmFuc2Zvcm0gPSBuZXdMYW5nID09PSAnZnInID8gJ3RyYW5zbGF0ZVgoMjBweCknIDogJ3RyYW5zbGF0ZVgoMCknO1xuICAgICAgICAgIHRoaXMuc2V0QXR0cmlidXRlKCdkYXRhLWxhbmcnLCBuZXdMYW5nKTtcbiAgICAgICAgICBkb2N1bWVudC5jb29raWUgPSBgbG9jYWxlPSR7bmV3TGFuZ307cGF0aD0vO21heC1hZ2U9JHszMCAqIDI0ICogNjAgKiA2MH1gO1xuICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpOyAvLyBSZWZyZXNoIHRoZSBwYWdlIHRvIGFwcGx5IHRoZSBuZXcgbGFuZ3VhZ2VcbiAgICAgIH0pO1xuICB9XG5cbiAgLy8gVGhlbWUgVG9nZ2xlXG4gIGlmICh0aGVtZVRvZ2dsZSkge1xuICAgICAgY29uc3QgY3VycmVudFRoZW1lID0gbG9jYWxTdG9yYWdlLmdldEl0ZW0oJ3RoZW1lJykgfHwgJ2xpZ2h0JztcbiAgICAgIGNvbnN0IGNpcmNsZSA9IHRoZW1lVG9nZ2xlLnF1ZXJ5U2VsZWN0b3IoJy5iZy13aGl0ZScpO1xuXG4gICAgICBpZiAoY3VycmVudFRoZW1lID09PSAnZGFyaycpIHtcbiAgICAgICAgICBjaXJjbGUuc3R5bGUudHJhbnNmb3JtID0gJ3RyYW5zbGF0ZVgoMjBweCknO1xuICAgICAgICAgIGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGFzc0xpc3QuYWRkKCdkYXJrJyk7XG4gICAgICB9XG4gICAgICB0aGVtZVRvZ2dsZS5zZXRBdHRyaWJ1dGUoJ2RhdGEtdGhlbWUnLCBjdXJyZW50VGhlbWUpO1xuXG4gICAgICB0aGVtZVRvZ2dsZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBjb25zdCBuZXdUaGVtZSA9IHRoaXMuZ2V0QXR0cmlidXRlKCdkYXRhLXRoZW1lJykgPT09ICdsaWdodCcgPyAnZGFyaycgOiAnbGlnaHQnO1xuXG4gICAgICAgICAgY2lyY2xlLnN0eWxlLnRyYW5zZm9ybSA9IG5ld1RoZW1lID09PSAnZGFyaycgPyAndHJhbnNsYXRlWCgyMHB4KScgOiAndHJhbnNsYXRlWCgwKSc7XG4gICAgICAgICAgdGhpcy5zZXRBdHRyaWJ1dGUoJ2RhdGEtdGhlbWUnLCBuZXdUaGVtZSk7XG4gICAgICAgICAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oJ3RoZW1lJywgbmV3VGhlbWUpO1xuICAgICAgICAgIGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGFzc0xpc3QudG9nZ2xlKCdkYXJrJyk7XG4gICAgICB9KTtcbiAgfVxufSk7XG5cblxuLy8gZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsICgpID0+IHtcbi8vICAgICBjb25zdCB0aGVtZVRvZ2dsZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy50aGVtZS10b2dnbGUnKTtcbi8vICAgICBjb25zdCBodG1sRWxlbWVudCA9IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudDtcbiAgICBcbi8vICAgICBpZiAodGhlbWVUb2dnbGUpIHtcbi8vICAgICAgICAgLy8gQ2hlY2sgZm9yIHNhdmVkIHRoZW1lIHByZWZlcmVuY2Vcbi8vICAgICAgICAgY29uc3Qgc2F2ZWRUaGVtZSA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKCd0aGVtZScpO1xuLy8gICAgICAgICBpZiAoc2F2ZWRUaGVtZSkge1xuLy8gICAgICAgICAgICAgaHRtbEVsZW1lbnQuY2xhc3NMaXN0LmFkZChzYXZlZFRoZW1lKTtcbi8vICAgICAgICAgfVxuICAgICAgICBcbi8vICAgICAgICAgdGhlbWVUb2dnbGUuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XG4vLyAgICAgICAgICAgICBodG1sRWxlbWVudC5jbGFzc0xpc3QudG9nZ2xlKCdkYXJrJyk7XG4vLyAgICAgICAgICAgICBjb25zdCBpc0RhcmsgPSBodG1sRWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoJ2RhcmsnKTtcbi8vICAgICAgICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKCd0aGVtZScsIGlzRGFyayA/ICdkYXJrJyA6ICdsaWdodCcpO1xuLy8gICAgICAgICB9KTtcbi8vICAgICB9XG4vLyB9KTtcblxuXG4vLyBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24oKSB7XG4vLyAgICAgY29uc3QgbWVudUJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtZW51QnV0dG9uJyk7XG4vLyAgICAgY29uc3QgY2xvc2VNZW51ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2Nsb3NlTWVudScpO1xuLy8gICAgIGNvbnN0IG1vYmlsZU1lbnUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubW9iaWxlLW1lbnUnKTtcbi8vICAgICBjb25zdCBsYW5nVG9nZ2xlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xhbmdUb2dnbGUnKTtcbi8vICAgICBjb25zdCBzbGlkZXJCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuc2xpZGVyLWJ1dHRvbicpO1xuLy8gICAgIGxldCBjdXJyZW50TGFuZyA9ICdlbic7XG5cbi8vICAgICBtZW51QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xuLy8gICAgICAgICBtb2JpbGVNZW51LmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xuLy8gICAgIH0pO1xuXG4vLyAgICAgY2xvc2VNZW51LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xuLy8gICAgICAgICBtb2JpbGVNZW51LmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xuLy8gICAgIH0pO1xuXG4vLyAgICAgbGFuZ1RvZ2dsZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbi8vICAgICAgICAgY3VycmVudExhbmcgPSBjdXJyZW50TGFuZyA9PT0gJ2VuJyA/ICdmcicgOiAnZW4nO1xuLy8gICAgICAgICBzbGlkZXJCdXR0b24uY2xhc3NMaXN0LnRvZ2dsZSgnZnInKTtcbiAgICAgICAgXG4vLyAgICAgICAgIC8vIEFzc3VtaW5nIHlvdSdyZSB1c2luZyBTeW1mb255J3MgdHJhbnNsYXRpb25cbi8vICAgICAgICAgLy8gZmV0Y2goYC9jaGFuZ2UtbG9jYWxlLyR7Y3VycmVudExhbmd9YCwge1xuLy8gICAgICAgICAvLyAgICAgbWV0aG9kOiAnUE9TVCcsXG4vLyAgICAgICAgIC8vICAgICBoZWFkZXJzOiB7XG4vLyAgICAgICAgIC8vICAgICAgICAgJ1gtUmVxdWVzdGVkLVdpdGgnOiAnWE1MSHR0cFJlcXVlc3QnXG4vLyAgICAgICAgIC8vICAgICB9XG4vLyAgICAgICAgIC8vIH0pLnRoZW4oKCkgPT4ge1xuLy8gICAgICAgICAvLyAgICAgd2luZG93LmxvY2F0aW9uLnJlbG9hZCgpO1xuLy8gICAgICAgICAvLyB9KTtcbi8vICAgICB9KTtcbi8vIH0pO1xuXG4vLyBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xuLy8gICAgIGNvbnN0IGxhbmd1YWdlVG9nZ2xlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2xhbmd1YWdlVG9nZ2xlJyk7XG4vLyAgICAgY29uc3QgbGFuZ3VhZ2VTbGlkZXIgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbGFuZ3VhZ2VTbGlkZXInKTtcbi8vICAgICBjb25zdCB0aGVtZVRvZ2dsZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0aGVtZVRvZ2dsZScpO1xuLy8gICAgIGNvbnN0IHRoZW1lU2xpZGVyID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3RoZW1lU2xpZGVyJyk7XG5cbi8vICAgICAvLyBMYW5ndWFnZSB0b2dnbGVcbi8vICAgICBsYW5ndWFnZVRvZ2dsZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbi8vICAgICAgICAgY29uc3QgY3VycmVudExhbmcgPSBsYW5ndWFnZVNsaWRlci5kYXRhc2V0LmN1cnJlbnRMYW5nO1xuLy8gICAgICAgICBjb25zdCBuZXdMYW5nID0gY3VycmVudExhbmcgPT09ICdlbicgPyAnZnInIDogJ2VuJztcbiAgICAgICAgXG4vLyAgICAgICAgIGxhbmd1YWdlU2xpZGVyLnN0eWxlLnRyYW5zZm9ybSA9IG5ld0xhbmcgPT09ICdmcicgPyAndHJhbnNsYXRlWCg0OHB4KScgOiAndHJhbnNsYXRlWCgwKSc7XG4gICAgICAgIFxuLy8gICAgICAgICBmZXRjaChgL3N3aXRjaC1sYW5ndWFnZS8ke25ld0xhbmd9YCwge1xuLy8gICAgICAgICAgICAgbWV0aG9kOiAnR0VUJyxcbi8vICAgICAgICAgICAgIGhlYWRlcnM6IHsnWC1SZXF1ZXN0ZWQtV2l0aCc6ICdYTUxIdHRwUmVxdWVzdCd9XG4vLyAgICAgICAgIH0pLnRoZW4oKCkgPT4gd2luZG93LmxvY2F0aW9uLnJlbG9hZCgpKTtcbi8vICAgICB9KTtcblxuLy8gICAgIC8vIFRoZW1lIHRvZ2dsZVxuLy8gICAgIHRoZW1lVG9nZ2xlLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xuLy8gICAgICAgICBjb25zdCBjdXJyZW50VGhlbWUgPSB0aGVtZVNsaWRlci5kYXRhc2V0LmN1cnJlbnRUaGVtZTtcbi8vICAgICAgICAgY29uc3QgbmV3VGhlbWUgPSBjdXJyZW50VGhlbWUgPT09ICdsaWdodCcgPyAnZGFyaycgOiAnbGlnaHQnO1xuICAgICAgICBcbi8vICAgICAgICAgdGhlbWVTbGlkZXIuc3R5bGUudHJhbnNmb3JtID0gbmV3VGhlbWUgPT09ICdkYXJrJyA/ICd0cmFuc2xhdGVYKDQ4cHgpJyA6ICd0cmFuc2xhdGVYKDApJztcbi8vICAgICAgICAgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsYXNzTGlzdC50b2dnbGUoJ2RhcmsnKTtcbiAgICAgICAgXG4vLyAgICAgICAgIGZldGNoKGAvc3dpdGNoLXRoZW1lLyR7bmV3VGhlbWV9YCwge1xuLy8gICAgICAgICAgICAgbWV0aG9kOiAnR0VUJyxcbi8vICAgICAgICAgICAgIGhlYWRlcnM6IHsnWC1SZXF1ZXN0ZWQtV2l0aCc6ICdYTUxIdHRwUmVxdWVzdCd9XG4vLyAgICAgICAgIH0pO1xuLy8gICAgIH0pO1xuXG4vLyAgICAgLy8gSW5pdGlhbGl6ZSBwb3NpdGlvbnNcbi8vICAgICBpZiAobGFuZ3VhZ2VTbGlkZXIuZGF0YXNldC5jdXJyZW50TGFuZyA9PT0gJ2ZyJykge1xuLy8gICAgICAgICBsYW5ndWFnZVNsaWRlci5zdHlsZS50cmFuc2Zvcm0gPSAndHJhbnNsYXRlWCg0OHB4KSc7XG4vLyAgICAgfVxuLy8gICAgIGlmICh0aGVtZVNsaWRlci5kYXRhc2V0LmN1cnJlbnRUaGVtZSA9PT0gJ2RhcmsnKSB7XG4vLyAgICAgICAgIHRoZW1lU2xpZGVyLnN0eWxlLnRyYW5zZm9ybSA9ICd0cmFuc2xhdGVYKDQ4cHgpJztcbi8vICAgICB9XG4vLyB9KTtcblxuXG4vLyBTZXBlcmF0ZWQgdW50byBcblxuXG4vLyBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImxhbmd1YWdlLXRvZ2dsZVwiKS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuLy8gICAgIC8vIFRvZ2dsZSBiZXR3ZWVuICdlbicgYW5kICdmcidcbi8vICAgICBsZXQgY3VycmVudExhbmcgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImxhbmd1YWdlLXRvZ2dsZVwiKS5nZXRBdHRyaWJ1dGUoXCJkYXRhLWxhbmdcIik7XG4vLyAgICAgbGV0IG5ld0xhbmcgPSBjdXJyZW50TGFuZyA9PT0gXCJlblwiID8gXCJmclwiIDogXCJlblwiO1xuLy8gICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwibGFuZ3VhZ2UtdG9nZ2xlXCIpLnNldEF0dHJpYnV0ZShcImRhdGEtbGFuZ1wiLCBuZXdMYW5nKTtcbiAgICBcbi8vICAgICAvLyBTZXQgdGhlIGxvY2FsZSBjb29raWUgZm9yIFN5bWZvbnkgKGV4cGlyZXMgaW4gMzAgZGF5cylcbi8vICAgICBkb2N1bWVudC5jb29raWUgPSBgbG9jYWxlPSR7bmV3TGFuZ307cGF0aD0vO21heC1hZ2U9JHszMCoyNCo2MCo2MH1gO1xuICAgIFxuLy8gICAgIC8vIFJlbG9hZCB0aGUgcGFnZSBzbyBTeW1mb255IGxvYWRzIHRoZSBuZXcgbG9jYWxlXG4vLyAgICAgbG9jYXRpb24ucmVsb2FkKCk7XG4vLyAgIH0pO1xuICBcblxuICAvLyBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiRE9NQ29udGVudExvYWRlZFwiLCAoKSA9PiB7XG4gIC8vICAgY29uc3QgdGhlbWVUb2dnbGUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInRoZW1lLXRvZ2dsZVwiKTtcbiAgLy8gICBjb25zdCB0aGVtZVNsaWRlciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwidGhlbWUtc2xpZGVyXCIpO1xuICAvLyAgIGNvbnN0IHN1bkljb24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInN1bi1pY29uXCIpO1xuICAvLyAgIGNvbnN0IG1vb25JY29uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJtb29uLWljb25cIik7XG4gIFxuICAvLyAgIC8vIE9wdGlvbmFsbHksIHJlYWQgdGhlIHNhdmVkIHRoZW1lIGZyb20gbG9jYWxTdG9yYWdlXG4gIC8vICAgY29uc3Qgc2F2ZWRUaGVtZSA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKFwidGhlbWVcIik7XG4gIC8vICAgaWYgKHNhdmVkVGhlbWUgPT09IFwiZGFya1wiKSB7XG4gIC8vICAgICBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImRhcmtcIik7XG4gIC8vICAgICB0aGVtZVNsaWRlci5jbGFzc0xpc3QuYWRkKFwidHJhbnNsYXRlLXgtMy41XCIpO1xuICAvLyAgICAgc3VuSWNvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJvcGFjaXR5LTBcIik7XG4gIC8vICAgICBtb29uSWNvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJvcGFjaXR5LTBcIik7XG4gIC8vICAgfVxuICBcbiAgLy8gICB0aGVtZVRvZ2dsZS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuICAvLyAgICAgLy8gVG9nZ2xlIGRhcmsgbW9kZSBjbGFzcyBvbiB0aGUgcm9vdCBlbGVtZW50XG4gIC8vICAgICBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xhc3NMaXN0LnRvZ2dsZShcImRhcmtcIik7XG4gIC8vICAgICBsZXQgaXNEYXJrID0gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsYXNzTGlzdC5jb250YWlucyhcImRhcmtcIik7XG4gIFxuICAvLyAgICAgLy8gQW5pbWF0ZSB0aGUgc2xpZGVyIChhc3N1bWVzIHRyYW5zbGF0ZS14LTAgZm9yIGxpZ2h0IGFuZCB0cmFuc2xhdGUteC0zLjUgZm9yIGRhcmspXG4gIC8vICAgICBpZiAoaXNEYXJrKSB7XG4gIC8vICAgICAgIHRoZW1lU2xpZGVyLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cmFuc2xhdGUteC0wXCIpO1xuICAvLyAgICAgICB0aGVtZVNsaWRlci5jbGFzc0xpc3QuYWRkKFwidHJhbnNsYXRlLXgtMy41XCIpO1xuICAvLyAgICAgICAvLyBUb2dnbGUgaWNvbiB2aXNpYmlsaXR5XG4gIC8vICAgICAgIHN1bkljb24ucGFyZW50RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwib3BhY2l0eS0wXCIpO1xuICAvLyAgICAgICBtb29uSWNvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJvcGFjaXR5LTBcIik7XG4gIC8vICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKFwidGhlbWVcIiwgXCJkYXJrXCIpO1xuICAvLyAgICAgfSBlbHNlIHtcbiAgLy8gICAgICAgdGhlbWVTbGlkZXIuY2xhc3NMaXN0LnJlbW92ZShcInRyYW5zbGF0ZS14LTMuNVwiKTtcbiAgLy8gICAgICAgdGhlbWVTbGlkZXIuY2xhc3NMaXN0LmFkZChcInRyYW5zbGF0ZS14LTBcIik7XG4gIC8vICAgICAgIC8vIFRvZ2dsZSBpY29uIHZpc2liaWxpdHlcbiAgLy8gICAgICAgc3VuSWNvbi5wYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJvcGFjaXR5LTBcIik7XG4gIC8vICAgICAgIG1vb25JY29uLnBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZChcIm9wYWNpdHktMFwiKTtcbiAgLy8gICAgICAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oXCJ0aGVtZVwiLCBcImxpZ2h0XCIpO1xuICAvLyAgICAgfVxuICAvLyAgIH0pO1xuICAvLyB9KTtcbiAgXG5cbiAgLy8gU2xpZGVyIEpTXG4vLyAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJET01Db250ZW50TG9hZGVkXCIsICgpID0+IHtcbi8vICAgICBjb25zdCB0b2dnbGVTd2l0Y2ggPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiW3JvbGU9J3N3aXRjaCddXCIpO1xuLy8gICAgIGNvbnN0IHRvZ2dsZUNpcmNsZSA9IHRvZ2dsZVN3aXRjaC5xdWVyeVNlbGVjdG9yKFwic3BhblthcmlhLWhpZGRlbj0ndHJ1ZSddXCIpO1xuICAgIFxuLy8gICAgIHRvZ2dsZVN3aXRjaC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuLy8gICAgICAgICBjb25zdCBpc0NoZWNrZWQgPSB0b2dnbGVTd2l0Y2guZ2V0QXR0cmlidXRlKFwiYXJpYS1jaGVja2VkXCIpID09PSBcInRydWVcIjtcbiAgICAgICAgXG4vLyAgICAgICAgIC8vIFRvZ2dsZSBhcmlhLWNoZWNrZWRcbi8vICAgICAgICAgdG9nZ2xlU3dpdGNoLnNldEF0dHJpYnV0ZShcImFyaWEtY2hlY2tlZFwiLCBpc0NoZWNrZWQgPyBcImZhbHNlXCIgOiBcInRydWVcIik7XG5cbi8vICAgICAgICAgLy8gVG9nZ2xlIFRhaWx3aW5kIGNsYXNzZXNcbi8vICAgICAgICAgdG9nZ2xlQ2lyY2xlLmNsYXNzTGlzdC50b2dnbGUoXCJ0cmFuc2xhdGUteC0wXCIsIGlzQ2hlY2tlZCk7XG4vLyAgICAgICAgIHRvZ2dsZUNpcmNsZS5jbGFzc0xpc3QudG9nZ2xlKFwidHJhbnNsYXRlLXgtMy41XCIsICFpc0NoZWNrZWQpO1xuXG4vLyAgICAgICAgIC8vIE9wdGlvbmFsOiBEbyBzb21ldGhpbmcgd2hlbiB0b2dnbGVkXG4vLyAgICAgICAgIGNvbnNvbGUubG9nKGBTd2l0Y2ggaXMgbm93OiAke2lzQ2hlY2tlZCA/IFwiT2ZmXCIgOiBcIk9uXCJ9YCk7XG4vLyAgICAgfSk7XG4vLyB9KTtcbiIsImltcG9ydCB7IHN0YXJ0U3RpbXVsdXNBcHAgfSBmcm9tICdAc3ltZm9ueS9zdGltdWx1cy1icmlkZ2UnO1xuXG4vLyBSZWdpc3RlcnMgU3RpbXVsdXMgY29udHJvbGxlcnMgZnJvbSBjb250cm9sbGVycy5qc29uIGFuZCBpbiB0aGUgY29udHJvbGxlcnMvIGRpcmVjdG9yeVxuZXhwb3J0IGNvbnN0IGFwcCA9IHN0YXJ0U3RpbXVsdXNBcHAocmVxdWlyZS5jb250ZXh0KFxuICAgICdAc3ltZm9ueS9zdGltdWx1cy1icmlkZ2UvbGF6eS1jb250cm9sbGVyLWxvYWRlciEuL2NvbnRyb2xsZXJzJyxcbiAgICB0cnVlLFxuICAgIC9cXC5banRdc3g/JC9cbikpO1xuLy8gcmVnaXN0ZXIgYW55IGN1c3RvbSwgM3JkIHBhcnR5IGNvbnRyb2xsZXJzIGhlcmVcbi8vIGFwcC5yZWdpc3Rlcignc29tZV9jb250cm9sbGVyX25hbWUnLCBTb21lSW1wb3J0ZWRDb250cm9sbGVyKTtcbiIsInZhciBtYXAgPSB7XG5cdFwiLi9jc3JmX3Byb3RlY3Rpb25fY29udHJvbGxlci5qc1wiOiBcIi4vbm9kZV9tb2R1bGVzL0BzeW1mb255L3N0aW11bHVzLWJyaWRnZS9sYXp5LWNvbnRyb2xsZXItbG9hZGVyLmpzIS4vYXNzZXRzL2NvbnRyb2xsZXJzL2NzcmZfcHJvdGVjdGlvbl9jb250cm9sbGVyLmpzXCIsXG5cdFwiLi9oZWxsb19jb250cm9sbGVyLmpzXCI6IFwiLi9ub2RlX21vZHVsZXMvQHN5bWZvbnkvc3RpbXVsdXMtYnJpZGdlL2xhenktY29udHJvbGxlci1sb2FkZXIuanMhLi9hc3NldHMvY29udHJvbGxlcnMvaGVsbG9fY29udHJvbGxlci5qc1wiXG59O1xuXG5cbmZ1bmN0aW9uIHdlYnBhY2tDb250ZXh0KHJlcSkge1xuXHR2YXIgaWQgPSB3ZWJwYWNrQ29udGV4dFJlc29sdmUocmVxKTtcblx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oaWQpO1xufVxuZnVuY3Rpb24gd2VicGFja0NvbnRleHRSZXNvbHZlKHJlcSkge1xuXHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKG1hcCwgcmVxKSkge1xuXHRcdHZhciBlID0gbmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIiArIHJlcSArIFwiJ1wiKTtcblx0XHRlLmNvZGUgPSAnTU9EVUxFX05PVF9GT1VORCc7XG5cdFx0dGhyb3cgZTtcblx0fVxuXHRyZXR1cm4gbWFwW3JlcV07XG59XG53ZWJwYWNrQ29udGV4dC5rZXlzID0gZnVuY3Rpb24gd2VicGFja0NvbnRleHRLZXlzKCkge1xuXHRyZXR1cm4gT2JqZWN0LmtleXMobWFwKTtcbn07XG53ZWJwYWNrQ29udGV4dC5yZXNvbHZlID0gd2VicGFja0NvbnRleHRSZXNvbHZlO1xubW9kdWxlLmV4cG9ydHMgPSB3ZWJwYWNrQ29udGV4dDtcbndlYnBhY2tDb250ZXh0LmlkID0gXCIuL2Fzc2V0cy9jb250cm9sbGVycyBzeW5jIHJlY3Vyc2l2ZSAuL25vZGVfbW9kdWxlcy9Ac3ltZm9ueS9zdGltdWx1cy1icmlkZ2UvbGF6eS1jb250cm9sbGVyLWxvYWRlci5qcyEgXFxcXC5banRdc3g/JFwiOyIsImltcG9ydCBjb250cm9sbGVyXzAgZnJvbSAnQHN5bWZvbnkvdXgtdHVyYm8vZGlzdC90dXJib19jb250cm9sbGVyLmpzJztcbmV4cG9ydCBkZWZhdWx0IHtcbiAgJ3N5bWZvbnktLXV4LXR1cmJvLS10dXJiby1jb3JlJzogY29udHJvbGxlcl8wLFxufTsiLCJpbXBvcnQgeyBDb250cm9sbGVyIH0gZnJvbSAnQGhvdHdpcmVkL3N0aW11bHVzJztcbmNvbnN0IGNvbnRyb2xsZXIgPSBjbGFzcyBleHRlbmRzIENvbnRyb2xsZXIge1xuICAgIGNvbnN0cnVjdG9yKGNvbnRleHQpIHtcbiAgICAgICAgc3VwZXIoY29udGV4dCk7XG4gICAgICAgIHRoaXMuX19zdGltdWx1c0xhenlDb250cm9sbGVyID0gdHJ1ZTtcbiAgICB9XG4gICAgaW5pdGlhbGl6ZSgpIHtcbiAgICAgICAgaWYgKHRoaXMuYXBwbGljYXRpb24uY29udHJvbGxlcnMuZmluZCgoY29udHJvbGxlcikgPT4ge1xuICAgICAgICAgICAgcmV0dXJuIGNvbnRyb2xsZXIuaWRlbnRpZmllciA9PT0gdGhpcy5pZGVudGlmaWVyICYmIGNvbnRyb2xsZXIuX19zdGltdWx1c0xhenlDb250cm9sbGVyO1xuICAgICAgICB9KSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGltcG9ydCgnL2hvbWUvZGF2eS9EZXNrdG9wL3N5Z2VuZXMvU1lHRU5FUy9hc3NldHMvY29udHJvbGxlcnMvY3NyZl9wcm90ZWN0aW9uX2NvbnRyb2xsZXIuanMnKS50aGVuKChjb250cm9sbGVyKSA9PiB7XG4gICAgICAgICAgICB0aGlzLmFwcGxpY2F0aW9uLnJlZ2lzdGVyKHRoaXMuaWRlbnRpZmllciwgY29udHJvbGxlci5kZWZhdWx0KTtcbiAgICAgICAgfSk7XG4gICAgfVxufTtcbmV4cG9ydCB7IGNvbnRyb2xsZXIgYXMgZGVmYXVsdCB9OyIsImltcG9ydCB7IENvbnRyb2xsZXIgfSBmcm9tICdAaG90d2lyZWQvc3RpbXVsdXMnO1xuXG4vKlxuICogVGhpcyBpcyBhbiBleGFtcGxlIFN0aW11bHVzIGNvbnRyb2xsZXIhXG4gKlxuICogQW55IGVsZW1lbnQgd2l0aCBhIGRhdGEtY29udHJvbGxlcj1cImhlbGxvXCIgYXR0cmlidXRlIHdpbGwgY2F1c2VcbiAqIHRoaXMgY29udHJvbGxlciB0byBiZSBleGVjdXRlZC4gVGhlIG5hbWUgXCJoZWxsb1wiIGNvbWVzIGZyb20gdGhlIGZpbGVuYW1lOlxuICogaGVsbG9fY29udHJvbGxlci5qcyAtPiBcImhlbGxvXCJcbiAqXG4gKiBEZWxldGUgdGhpcyBmaWxlIG9yIGFkYXB0IGl0IGZvciB5b3VyIHVzZSFcbiAqL1xuZXhwb3J0IGRlZmF1bHQgY2xhc3MgZXh0ZW5kcyBDb250cm9sbGVyIHtcbiAgICBjb25uZWN0KCkge1xuICAgICAgICB0aGlzLmVsZW1lbnQudGV4dENvbnRlbnQgPSAnSGVsbG8gU3RpbXVsdXMhIEVkaXQgbWUgaW4gYXNzZXRzL2NvbnRyb2xsZXJzL2hlbGxvX2NvbnRyb2xsZXIuanMnO1xuICAgIH1cbn1cbiJdLCJuYW1lcyI6WyJjb25zb2xlIiwibG9nIiwiZG9jdW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwibGFuZ1RvZ2dsZSIsImdldEVsZW1lbnRCeUlkIiwidGhlbWVUb2dnbGUiLCJfZG9jdW1lbnQkY29va2llJG1hdGMiLCJjdXJyZW50TGFuZyIsImNvb2tpZSIsIm1hdGNoIiwiY2lyY2xlIiwicXVlcnlTZWxlY3RvciIsInN0eWxlIiwidHJhbnNmb3JtIiwic2V0QXR0cmlidXRlIiwibmV3TGFuZyIsImdldEF0dHJpYnV0ZSIsImNvbmNhdCIsImxvY2F0aW9uIiwicmVsb2FkIiwiY3VycmVudFRoZW1lIiwibG9jYWxTdG9yYWdlIiwiZ2V0SXRlbSIsImRvY3VtZW50RWxlbWVudCIsImNsYXNzTGlzdCIsImFkZCIsIm5ld1RoZW1lIiwic2V0SXRlbSIsInRvZ2dsZSIsInN0YXJ0U3RpbXVsdXNBcHAiLCJhcHAiLCJyZXF1aXJlIiwiY29udGV4dCIsIkNvbnRyb2xsZXIiLCJfZGVmYXVsdCIsIl9Db250cm9sbGVyIiwiX2NsYXNzQ2FsbENoZWNrIiwiX2NhbGxTdXBlciIsImFyZ3VtZW50cyIsIl9pbmhlcml0cyIsIl9jcmVhdGVDbGFzcyIsImtleSIsInZhbHVlIiwiY29ubmVjdCIsImVsZW1lbnQiLCJ0ZXh0Q29udGVudCIsImRlZmF1bHQiXSwic291cmNlUm9vdCI6IiJ9