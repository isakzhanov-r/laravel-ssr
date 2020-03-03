let mix = require('laravel-mix');
const path = require('path');

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

mix
    .webpackConfig({
        resolve: {
            extensions: ['.js', '.json', '.vue'],
            alias: {
                '~vue': path.join(__dirname, './src/vue')
            }
        }
    })
    .js('src/server.js', 'public/js')
    .js('src/server-broken.js', 'public/js');

