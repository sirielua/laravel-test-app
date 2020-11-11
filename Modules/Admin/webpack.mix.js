const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

const publicPath = '../../public';
mix.setPublicPath(publicPath).mergeManifest();

// Architet-ui
    mix.copyDirectory(__dirname + '/Resources/assets/architect-ui/assets/images', publicPath + '/architect-ui/assets/images')
        .copyDirectory(__dirname + '/Resources/assets/architect-ui/assets/fonts', publicPath + '/architect-ui/assets/fonts')
        .copy(__dirname + '/Resources/assets/architect-ui/assets/scripts/main.js', publicPath + '/architect-ui/assets/scripts/main.js')
        .copy(__dirname + '/Resources/assets/architect-ui/main.css', publicPath + '/architect-ui/main.css');
    
// Admin + jQuery
    mix.js(__dirname + '/Resources/assets/js/app.js', 'js/admin.js')
        .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/admin.css');

    mix.scripts([
        // Datatables
        __dirname + '/Resources/assets/datatables/build/pdfmake.min.js',
        __dirname + '/Resources/assets/datatables/build/vfs_fonts.js',
        __dirname + '/Resources/assets/datatables/build/datatables.min.js',
    ], publicPath + '/js/admin-scripts.js');

    mix.styles([
        // Datatables
        __dirname + '/Resources/assets/datatables/build/style.css',
        __dirname + '/Resources/assets/datatables/style.css',
    ], publicPath + '/css/admin-styles.css');

if (mix.inProduction()) {
    mix.version();
}
