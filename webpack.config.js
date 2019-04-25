var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');
//var build=Encore.isProduction() ? '/build' : '/symfboot/build';
var publicPath = '/build';

if (!Encore.isProduction()) {
    require('dotenv').config({
        path: './.webpack.env',
    });

    if ('undefined' !== typeof process.env.ENCORE_PUBLIC_PATH) {
        publicPath = process.env.ENCORE_PUBLIC_PATH;
    } else {
        const guessFromPaths = [
            '/usr/local/var/www/htdocs',
            '/usr/local/var/www',
            process.env.HOME + '/Sites',
        ];

        for (var i = 0; i < guessFromPaths.length; i++) {
            var path = guessFromPaths[i];

            if (0 === __dirname.indexOf(path)) {
                path = __dirname.split(path);
                publicPath = path[1] + '/public/build';
                break;
            }
        }
    }
}

Encore
    .setOutputPath('public/build/')
    // aquesta línia hauria de començar per / o per http:// però en shared hosting
    // no funciona
    .setPublicPath(publicPath)
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .autoProvideVariables({
        "jQuery.tagsinput": "bootstrap-tagsinput"
    })
    .enableSassLoader()
    .enableVersioning()
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/tagsinput', './assets/js/tagsinput.js')
    //.addEntry('js/search','./assets/js/search.js')
    //.addEntry('js/admin', './assets/js/admin.js')
    //.addEntry('js/search', './assets/js/search.js')
    .addStyleEntry('css/app', ['./assets/css/app.scss'])
    .addStyleEntry('css/tagsinput',['./assets/css/tagsinput.css'])
    //.addStyleEntry('css/admin', ['./assets/scss/admin.scss'])
    .addPlugin(new CopyWebpackPlugin([
        // Copy the skins from tinymce to the build/js/skins directory
        { from: 'node_modules/tinymce/skins', to: 'js/skins' },
    ]))
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .setManifestKeyPrefix('build/')
;

module.exports = Encore.getWebpackConfig();