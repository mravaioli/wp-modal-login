<?php

	/**
	 * 
	 */
	class Geissinger_Add_WP_Modal_Shortcode {

		// Set the name of our TinyMCE button
		var $button_name = 'wp_modal_shortcode';

		function Geissinger_Add_WP_Modal_Shortcode()  {
			// Set the path to our TinyMCE plugin files
			$this->path = plugins_url( '/', __FILE__ );

			// Load our button in initialization
			add_action( 'init', array( & $this, 'add_button' ) );
		}

		function add_button() {
			// Check if the current user has the rights to edit posts or pages
			if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
				return;

			// Add our button only if rich text editing is enabled
			if ( get_user_option( 'rich_editing' ) == 'true' ) {

				$post_uri = $_SERVER['REQUEST_URI'];

				// Check if we are viewing either post editor and add our button
				if ( strstr( $post_uri, 'post.php' ) || strstr( $post_uri, 'post-new.php' ) || strstr( $post_uri, 'page.php' ) || strstr( $post_uri, 'page-new.php' ) ) {
					add_filter( 'mce_external_plugins', array( & $this, 'add_tinymce_plugin' ), 5 );
					add_filter( 'mce_buttons', array( & $this, 'register_button' ), 5 );
					add_filter( 'mce_external_languages', array( & $this, 'add_tinymce_langs_path' ) );
				}
			}
		}

		function register_button( $buttons ) {
			array_push( $buttons, 'separator', $this->button_name );

			return $buttons;
		}

		function add_tinymce_plugin( $plugin_array ) {
			global $page_handle;

			$post_uri = $_SERVER['REQUEST_URI'];

			if ( isset( $_GET['post_type'] ) ) {
				$post_type_get = $_GET['post_type'];
			}

			$post_id = $_GET['post'];
			$post = get_post( $post_id );
			$post_type = $post->post_type;

			$plugin_array[ $this->button_name ] =  $this->path . 'editor-plugin.min.js';

			return $plugin_array;
		}

		function add_tinymce_langs_path( $plugin_array ) {
			// Load the TinyMCE language file
			$plugin_array[ $this->button_name ] = $this->path . 'languages/langs.php';

			return $plugin_array;
		}
	}

	$tinymce_button = new Geissinger_Add_WP_Modal_Shortcode();

?>