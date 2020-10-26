let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .combine([
       'resources/assets/js/theme.js',
       'resources/assets/js/extension/*.js',
   ], 'public/js/all.js')
   .styles([
       'resources/assets/css/main.css',
       'resources/assets/css/custom.css',
   ], 'public/css/all.css')
;
// mix.js('resources/assets/js/app.js', 'public/js')
//     .scripts([
//         'resources/assets/js/extension/choices.js',
//         'resources/assets/js/extension/custom-materialize.js',
//         'resources/assets/js/extension/flatpickr.js',
//         'resources/assets/js/theme.js'
//     ], 'public/js/all2.js')
//     .styles([
//         'resources/assets/css/main.css'
//     ], 'public/css/all.css')
// ;
