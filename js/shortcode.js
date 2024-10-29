var AdManglerShortcode = new function(){
    this.editor = null;
    this.tinymce = null;
    this.insert_data = function (data) {
        var content = "[AdMangler width=\""+data.width+"\" height=\""+data.height+"\"";
        if ( null != data.position && 0 != data.position && '' != data.position )
            content += " position=\""+data.position+"\"";
        content += "]";
        
        this.tinymce.execCommand('mceInsertContent', false, content);
        this.editor.windowManager.close(this.editor.windowManager.params.mce_window_id);
    }
};
jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.wpse72394_plugin', {
        init : function(ed, url) {
                AdManglerShortcode.editor = ed;
                // Register command for when button is clicked
                ed.addCommand('wpse72394_insert_shortcode', function() {
                    ed.windowManager.open({
                        file : url + '/../shortcode.php',
                        width : 400 + parseInt(ed.getLang('emotions.delta_width', 0)),
                        height : 250 + parseInt(ed.getLang('emotions.delta_height', 0)),
                        inline : 1,
                        win : window
                    }, {
                        plugin_url : url
                    });

                    //content = tinyMCE.activeEditor.windowManager.test;
                    /*selected = tinyMCE.activeEditor.selection.getContent();

                    if( selected ){
                        //If text is selected when button is clicked
                        //Wrap shortcode around it.
                        content =  '[shortcode]'+selected+'[/shortcode]';
                    }else{
                        content =  '[shortcode]';
                    }
                    */
                    //tinymce.execCommand('mceInsertContent', false, content);
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('wpse72394_button', {title : 'Insert shortcode', cmd : 'wpse72394_insert_shortcode', image: url + '/../images/logo.gif' });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('wpse72394_button', tinymce.plugins.wpse72394_plugin);
    AdManglerShortcode.tinymce = tinymce;
});