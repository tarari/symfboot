var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');
Encore
    .setOutputPath('public/build/')
    .setPublicPath('build')
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
;

module.exports = Encore.getWebpackConfig();