
const $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');


// Import TinyMCE
import tinymce from 'tinymce/tinymce';

// A theme is also required
import 'tinymce/themes/silver/theme';

// Any plugins you want to use has to be imported
import 'tinymce/plugins/paste';
import 'tinymce/plugins/link';

// Initialize the app
tinymce.init({
    selector: '.mceditor',

    plugins: ['paste', 'link']
});