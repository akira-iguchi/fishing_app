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

mix .browserSync('vuesplash.test')
    .js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/spot.scss', 'public/css/spot.css')
    .sass('resources/sass/user.scss', 'public/css/user.css')
    .sass('resources/sass/fishing_type.scss', 'public/css/fishing_type.css')
    .sass('resources/sass/app.scss', 'public/css')
    .version()
    .browserSync({
        files: [
            'resources/**/*',
            'public/**/*'
        ],
        proxy: '0.0.0.0',
        open: false
    });
