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
   .js('resources/assets/js/default-app.js', 'public/js')
   .js('resources/assets/js/bill.js', 'public/js')
   .scripts(['resources/assets/js/fontawesome-all.min.js'], 'public/js/vendors.js')
   .sass('resources/assets/sass/app.scss', 'public/css');
