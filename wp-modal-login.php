<?php
	/*
	Plugin Name: WP Modal Login
	Plugin URI: http://wp-modal-login.colegeissinger.com
	Description: A highly configurable and versatile modal (pop-up) login form.
	Version: 2.0.6
	Author: Cole Geissinger
	Author URI: http://www.colegeissinger.com
	Text Domain: geissinger-wpml
	License: GPLv2 or later

	Copyright 2013 Cole Geissinger (cole@colegeissing.com)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	*/


	// Return our data saved in the database to load some things.
	$wpml_settings = get_option( 'geissinger_wpml_options' );

	// Load our text domain
	// TODO: loading via the core class doesn't seem to work despite following the documentation on the Codex? Fix that... ^_^
	load_plugin_textdomain( 'geissinger-wpml', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	// Load our primary class.
	require_once( 'includes/class-wp-modal-login.php' );

	// Load our widget class.
	if ( isset( $wpml_settings['display-widget'] ) )
		require_once( 'widget/class-login-widget.php' );

	// Load our shortcode TinyMCE button, but only when in the admin area.
	if ( is_admin() ) {
		if ( isset( $wpml_settings['display-shortcode-btn'] ) )
			require_once( 'includes/tinymce-shortcode-btn/modal-login-init.php' );

		// Load the admin page also! :P
		require_once( 'includes/wpml-admin-page.php' );
	}


	/**
	 * Create a helper function for adding our login goodness
	 * @param String $login_text  The text for the login link. Default 'Login'.
	 * @param String $logout_text The text for the logout link. Default 'Logout'.
	 * @param String $logout_url  The url to redirect to when users logout. Empty by default.
	 * @param Bool   $show_admin  The setting to display the link to the admin area when logged in.
	 * @return HTML
	 *
	 * @version 1.0
	 * @since 2.0
	 */
	function add_modal_login_button( $login_text = 'Login', $logout_text = 'Logout', $logout_url = '', $show_admin = true ) {
		global $wp_modal_login_class;

		// Make sure our class is really truly loaded.
		if ( isset( $wp_modal_login_class ) ) {
			echo $wp_modal_login_class->modal_login_btn( $login_text, $logout_text, $logout_url, $show_admin );
		} else {
			echo __( 'ERROR: WP Modal Login class not loaded.', 'geissinger-wpml' );
		}
	}


	// Load this shiz.
	if ( class_exists( 'Geissinger_WP_Modal_Login' ) )
		$wp_modal_login_class = new Geissinger_WP_Modal_Login;
