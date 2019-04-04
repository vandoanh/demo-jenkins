const mix = require('laravel-mix');
const SWPrecacheWebpackPlugin = require('sw-precache-webpack-plugin');

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

mix.js('resources/js/app.js', 'public/static/js/app.js')
    .js('resources/js/app.be.js', 'public/static/js/app.be.js')
    .js('resources/js/sw-custom.js', 'public/static/js/sw-custom.js')
    .styles([
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/select2/dist/css/select2.css',
        'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.css',
        'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.min.css',
        'node_modules/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css',
        'node_modules/@fortawesome/fontawesome-free/css/all.css',
        'node_modules/toastr/build/toastr.css'
    ], 'public/static/css/vendor.css')
    .styles([
        'resources/css/style.css',
        'resources/css/helper.css'
    ], 'public/static/css/app.css')
    .styles('resources/css/style.be.css', 'public/static/css/app.be.css')
    .copy('node_modules/ckeditor-full/config.js', 'public/static/js/ckeditor/config.js')
    .copy('node_modules/ckeditor-full/styles.js', 'public/static/js/ckeditor/styles.js')
    .copy('node_modules/ckeditor-full/contents.css', 'public/static/js/ckeditor/contents.css')
    .copyDirectory('node_modules/ckeditor-full/skins', 'public/static/js/ckeditor/skins')
    .copyDirectory('node_modules/ckeditor-full/lang', 'public/static/js/ckeditor/lang')
    .copyDirectory('node_modules/ckeditor-full/plugins', 'public/static/js/ckeditor/plugins')
    .copyDirectory('resources/images', 'public/static/images')
    .copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/static/webfonts');

if (mix.inProduction()) {
    mix.version();

    mix.webpackConfig({
        plugins: [
            new SWPrecacheWebpackPlugin({
                cacheId: 'pwa',
                filename: 'service-worker.js',
                importScripts: [
                    'static/js/sw-custom.js'
                ],
                staticFileGlobs: [],
                minify: true,
                stripPrefix: '',
                handleFetch: false,
                dynamicUrlToDependencies: {},
                staticFileGlobsIgnorePatterns: [/\.map$/, /mix-manifest\.json$/, /manifest\.json$/, /service-worker\.js$/],
                runtimeCaching: [{
                    urlPattern: /^https:\/\/fonts\.googleapis\.com\//,
                    handler: 'cacheFirst'
                }]
            })
        ]
    });
}
