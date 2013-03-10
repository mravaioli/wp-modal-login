( function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack( 'wp_modal_shortcode' );

    tinymce.create( 'tinymce.plugins.wp_modal_shortcode', {
        /**
        * Initializes the plugin, this will be executed after the plugin has been created.
        * This call is done before the editor instance has finished it's initialization so use the onInit event
        * of the editor instance to intercept that event.
        *
        * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
        * @param {string} url Absolute URL to where the plugin is located.
        */
        init : function( ed, url ) {
            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
            ed.addCommand( 'mcebtn_wp_modal_shortcode', function() {
                ed.windowManager.open( {
                    file : url + '/popup.php',
                    width : 600 + ed.getLang( 'example.delta_width', 0 ),
                    height : 200 + ed.getLang( 'example.delta_height', 0 ),
                    inline : 1
                }, {
                    plugin_url : url // Plugin absolute URL
                    //some_custom_arg : 'custom arg' // Custom argument
                });
            });

            // Register example button
            ed.addButton( 'wp_modal_shortcode', {
                title : 'Add Modal Login',
                cmd : 'mcebtn_wp_modal_shortcode',
                image : url + '/images/icon.png'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add( function( ed, cm, n ) {
                cm.setActive( 'wp_modal_shortcode', n.nodeName === 'IMG' );
            });
        },

        /**
         * Creates control instances based in the incoming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like list-boxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use in order to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function( n, cm ) { return null; },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Add Modal Login',
                author : 'Cole Geissinger',
                authorurl : 'http://www.colegeissinger.com',
                infourl : 'http://wp-modal-login.colegeissinger.com',
                version : "1.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'wp_modal_shortcode', tinymce.plugins.wp_modal_shortcode );
})();