const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');


mix.scripts([
   'public/assets/lib/jquery/dist/jquery.min.js',
   'public/assets/lib/bootstrap/js/popper.min.js',
   'public/assets/lib/bootstrap/js/bootstrap.min.js',
   'public/assets/lib/pace/pace.min.js',
   'public/assets/lib/jasny-bootstrap/js/jasny-bootstrap.min.js',
   'public/assets/lib/slimscroll/jquery.slimscroll.min.js',
   'public/assets/lib/nano-scroll/jquery.nanoscroller.min.js',
   'public/assets/lib/metisMenu/metisMenu.min.js',
   'public/assets/js/custom.js'
   ], 'public/js/pagina.js');
