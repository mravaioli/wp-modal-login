<?php
	/*
	Plugin Name: WP Modal Login
	Plugin URI: http://wp-modal-login.colegeissinger.com
	Description: A highly configurable and versatile modal (pop-up) login form.
	Version: 1.1
	Author: Cole Geissinger
	Author URI: http://www.colegeissinger.com
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


	// Define the core class
	class Geissinger_WP_Modal_Login {
		public $plugin_version = '1.1';

		/**
		 * Loads all of our required hooks and filters and other cool doodads.
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		public function __construct() {
			// Load our widget class
			include( 'widget/class-login-widget.php' );

			// Load our shortcode TinyMCE button
			include( 'includes/tinymce-shortcode-btn/modal-login-init.php' );

			add_action( 'wp_footer', array( $this, 'login_form' ) ); // Register our source code with the wp_footer().
			add_action( 'wp_enqueue_scripts', array( $this, 'print_resources' ) ); // Add our JavaScript to the front-end.
			add_action( 'widgets_init', create_function( '', 'register_widget( "Geissinger_WP_Modal_Login_Widget" );' ) );
			add_shortcode( 'wp-modal-login', array( $this, 'modal_login_btn_shortcode' ) ); // Add our shortcode action.
		}


		/**
		 * Add all of our scripts and styles with WordPress.
		 * @return void
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		public function print_resources() {
			wp_enqueue_style( 'wpml-styles', plugins_url( 'css/wp-modal-login.css', __FILE__ ), null, $plugin_version, 'screen' );
			wp_enqueue_script( 'wpml-script', plugins_url( 'js/wp-modal-login.min.js', __FILE__ ), array( 'jquery' ), $plugin_version, true );
		}


		/**
		 * The source code for our login form. It's pretty much straight from the wp-login.php, but reformatted for max customizations.
		 * @return html
		 * @actions before_wpml_title				Add items before the modal window title.
		 *          before_wpml_form				Add items before the form itself, or after the title.
		 *          inside_wpml_form_first		Add items before any of the form items but inside the form wrapper.
		 *          login_form 						Default WP action for custom login forms.
		 *          inside_wpml_form_submit		Add items in the submit wrapper.
		 *          inside_wpml_form_last		Add items at the end of the form wrapper after the submit button.
		 *          after_wpml_form				Add items after the form iteself.
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		public function login_form() {
			$rememberme = ! empty( $_POST['rememberme'] ); ?>
			<div id="login-box" class="login-popup">
				<a href="#" class="close-btn"></a>

				<?php do_action( 'before_wpml_title' ); ?>

				<h2><?php echo _e( 'Login' ); ?></h2>

				<?php do_action( 'before_wpml_form' ); ?>

				<form action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post" id="loginform" name="loginform">

					<?php do_action( 'inside_wpml_form_first' ); ?>

					<p>
						<label class="field-titles" for="user_login"><?php _e( 'Username' ); ?><br />
						<input type="text" name="log" id="user_login" class="input" value="<?php echo esc_attr( $user_login ); ?>" size="20" /></label>
					</p>
					<p>
						<label class="field-titles" for="user_pass"><?php _e( 'Password' ); ?><br />
						<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" /></label>
					</p>
					<?php do_action( 'login_form' ); ?>
					<p class="forgetmenot">
						<label class="forgetmenot-label" for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php esc_attr_e( 'Remember Me' ); ?></label>
					</p>
					<p class="submit">

						<?php do_action( 'inside_wpml_form_submit' ); ?>

						<input type="submit" name="wp-sumbit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In' ); ?>">
						<input type="hidden" name="redirect_to" value="<?php echo get_admin_url(); ?>" />
						<input type="hidden" name="testcookie" value="1" />
					</p><!--[END .submit]-->

					<?php do_action( 'inside_wpml_form_last' ); ?>

				</form><!--[END #loginform]-->

				<?php do_action( 'after_wpml_form' ); ?>
			</div><!--[END #login-box]-->
		<?php }


		/**
		 * Outputs the HTML for our login link.
		 * @param  String $text The text that contains the HTML for the
		 * @return String
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		public function modal_login_btn( $login_text, $logout_text, $logout_url ) {
			// Check if we have any over riding login text to be used.
			if ( isset( $login_text ) && $login_text == '' )
				$login_text = 'Login';

			// Check if we have any over riding logout text to be used.
			if ( isset( $logout_text ) && $logout_text == '' )
				$logout_text = 'Logout';

			// Check if we have an over riding logout redirection set.
			if ( isset( $logout_url ) && $logout_url == '' )
				$logout_url = home_url();

			// Is the user logged in? If so, serve them the logout button, else we'll show the login button.
			if ( is_user_logged_in() ) {
				$link = '<a href="' . wp_logout_url( esc_url( $logout_url ) ) . '" class="login">' . sprintf( sanitize_text_field( '%s' ), $logout_text ) . '</a>';
			} else {
				$link = '<a href="#login-box" class="login login-window">' . sprintf( sanitize_text_field( '%s' ), $login_text ) . '</a></li>';
			}


			return $link;
		}


		function modal_login_btn_shortcode( $atts ) {
			extract( shortcode_atts( array(
				'login_text'  => 'Login',
				'logout_text' => 'Logout',
				'logout_url'  => wp_logout_url( esc_url( home_url() ) ),
			), $atts ) );

			if ( is_user_logged_in() ) {
				$link = '<a href="' . $logout_url . '" class="login">' . sprintf( sanitize_text_field( '%s' ), $logout_text ) . '</a>';
			} else {
				$link = '<a href="#login-box" class="login login-window">' . sprintf( sanitize_text_field( '%s' ), $login_text ) . '</a></li>';
			}

			return $link;
		}
	}

	// Load this shiz.
	$wp_modal_login = new Geissinger_WP_Modal_Login;

