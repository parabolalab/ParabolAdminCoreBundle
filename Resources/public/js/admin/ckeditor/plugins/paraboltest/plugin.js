CKEDITOR.plugins.add( 'paraboltest', {
    icons: 'abbr',
    init: function( editor ) {
        alert('aaa');
    }
});
CKEDITOR.dialog.add( 'parabolBrowserDialog', this.path + 'dialogs/browser.js' );