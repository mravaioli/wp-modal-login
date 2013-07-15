<?php

	/**
	 * Our core class.
	 *
	 * This contains all the cool jazz that makes this plugin work.
	 *
	 * @version 2.1beta
	 * @since 1.0
	 */
	class Geissinger_WP_Modal_Login {

		// Set the version number
		public $plugin_version = '2.1';


		/**
		 * Loads all of our required hooks and filters and other cool doodads.
		 *
		 * @version 1.2
		 * @since 1.0
		 */
		public function __construct() {
			global $wpml_settings;

			// Register our source code with the wp_footer().
			add_action( 'wp_footer', array( $this, 'login_form' ) );

			// Add our JavaScript and Stylesheets to the front-end.
			add_action( 'wp_enqueue_scripts', array( $this, 'print_resources' ) );

			// Add the users custom CSS if they wish to add any.
			if ( isset( $wpml_settings['custom-css'] ) )
				add_action( 'wp_head', array( $this, 'print_custom_css' ) );

			// Add our lost password field.
			add_action( 'after_wpml_form', array( $this, 'additional_options' ) );

			// Add our shortcode action.
			add_shortcode( 'wp-modal-login', array( $this, 'modal_login_btn_shortcode' ) );

			// Register our widget but only if the user has allowed the widget option.
			if ( isset( $wpml_settings['display-widget'] ) )
				add_action( 'widgets_init', create_function( '', 'register_widget( "Geissinger_WP_Modal_Login_Widget" );' ) );

			// Allow us to run Ajax on the login.
			add_action( 'wp_ajax_nopriv_ajaxlogin', array( $this, 'ajax_login' ) );
		}


		/**
		 * Add all of our scripts and styles with WordPress.
		 * @return void
		 *
		 * @version 2.1
		 * @since 1.0
		 */
		public function print_resources() {
			global $wpml_settings;

			$theme = $wpml_settings['modal-theme'];


			wp_enqueue_style( 'wpml-styles', plugins_url( 'css/wp-modal-login.css', dirname( __FILE__ ) ), null, $this->plugin_version, 'screen' );

			// Load the right Modal Theme
			if ( ! isset( $theme ) || $theme == 'default' ) {
				wp_enqueue_style( 'wpml-default-theme', plugins_url( 'css/default.css', dirname( __FILE__ ) ), null, $this->plugin_version, 'screen' );
			} elseif ( $theme == 'theme-1' ) {
				wp_enqueue_style( 'wpml-theme-1', plugins_url( 'css/theme-1.css', dirname( __FILE__ ) ), null, $this->plugin_version, 'screen' );
			} elseif ( $theme == 'theme-2' ) {
				wp_enqueue_style( 'wpml-theme-2', plugins_url( 'css/theme-2.css', dirname( __FILE__ ) ), null, $this->plugin_version, 'screen' );
			}

			wp_enqueue_script( 'wpml-script', plugins_url( 'js/wp-modal-login.min.js', dirname( __FILE__ ) ), array( 'jquery' ), $this->plugin_version, true );

			// Set some localized JS and attach apply_filters() so users can add custom data to the wpml-script object or modify it
			if ( ! is_user_logged_in() ) {
				$localized = array(
					'ajax' 		     => admin_url( 'admin-ajax.php' ),
					'redirecturl' 	  => $_SERVER['REQUEST_URI'],
					'loadingmessage' => __( 'Checking Credentials...', 'geissinger-wpml' ),
				);
				wp_localize_script( 'wpml-script', 'wpml_script', apply_filters( 'geissinger_localized_info', $localized ) );
			}
		}


		/**
		 * Display any custom CSS that may be entered into the admin area
		 * @return String
		 *
		 * @version 1.0
		 * @since 2.0
		 */
		public function print_custom_css() {
			global $wpml_settings;

			echo '<style text="text/css">' . $wpml_settings['custom-css'] . "</style>\n";
		}


		/**
		 * The main function behind a large section of the Ajax-y goodness.
		 * @return void
		 *
		 * @version 1.1.1
		 * @since 2.0
		 */
		public function ajax_login() {

			// Check our nonce and make sure it's correct.
			check_ajax_referer( 'ajax-form-nonce', 'security' );

			// Get our form data.
			$data = array();

			// Set our feedback messages
			// Success messages are returned from built in WordPress errors for register and forgotten password retrieval.
			// TODO: update this to be filterable.
			$messages = (object) array(
				'login' => (object) array(
				 	 'failed'  => __( 'Wrong Username or Password!', 'geissinger_wpml' ),
				 	 'success' => __( 'Login Successful!', 'geissinger_wpml' ),
				 ),
				'register' => (object) array(
					'success' => __( 'Registration complete. Please check your e-mail.', 'geissinger-wpml' ),
				),
				'forgotten' => (object) array(
					'success' => __( 'Password Reset. Please check your email.', 'geissinger-wpml' ),
				),
			);

			// Send our ajax messages through apply_filters for easy text customization
			apply_filters( 'geissinger_ajax_messages', $messages );

			// Check that we are submitting the login form
			if ( isset( $_REQUEST['login'] ) )  {
				$data['user_login'] 	  = sanitize_user( $_REQUEST['username'] );
				$data['user_password'] = esc_attr( $_REQUEST['password'] );
				$data['rememberme'] 	  = esc_attr( $_REQUEST['rememberme'] );
				$user_login 			  = wp_signon( $data, false );

				// Check the results of our login and provide the needed feedback
				if ( is_wp_error( $user_login ) ) {
					echo json_encode( array(
						'loggedin' => false,
						'message'  => $messages->login->failed,
					) );
				} else {
					echo json_encode( array(
						'loggedin' => true,
						'message'  => $messages->login->success,
					) );
				}
			}

			// Check if we are submitting the register form
			elseif ( isset( $_REQUEST['register'] ) ) {
				$user_data = array(
					'user_login' => sanitize_user( $_REQUEST['username'] ),
					'user_email' => sanitize_email( $_REQUEST['email'] ),
				);
				$user_register = $this->register_new_user( $user_data['user_login'], $user_data['user_email'] );

				// Check if there were any issues with creating the new user
				if ( is_wp_error( $user_register ) ) {
					echo json_encode( array(
						'registerd' => false,
						'message'   => $user_register->get_error_message(),
					) );
				} else {
					echo json_encode( array(
						'registerd' => true,
						'message'	=> $messages->register->success,
					) );
				}
			}

			// Check if we are submitting the forgotten pwd form
			elseif ( isset( $_REQUEST['forgotten'] ) ) {

				// Check if we are sending an email or username and sanitize it appropriately
				if ( is_email( $_REQUEST['username'] ) ) {
					$username = sanitize_email( $_REQUEST['username'] );
				} else {
					$username = sanitize_user( $_REQUEST['username'] );
				}

				// Send our information
				$user_forgotten = $this->retrieve_password( $username );

				// Check if there were any errors when requesting a new password
				if ( is_wp_error( $user_forgotten ) ) {
					echo json_encode( array(
						'reset' 	 => false,
						'message' => $user_forgotten->get_error_message(),
					) );
				} else {
					echo json_encode( array(
						'reset'   => true,
						'message' => $messages->forgotten->success,
					) );
				}
			}

			die();
		}


		/**
		 * When users register an account we want to sanitize the information and generate a new password, add to the database and email both the site admin and the new user account with an auto-generated password.
		 * @param  String $user_login The username to be set to the new user account
		 * @param  String $user_email The email to be set to the new user account
		 * @return String
		 *
		 * @version 1.1
		 * @since 2.0
		 */
		function register_new_user( $user_login, $user_email ) {
			$errors = new WP_Error();
			$sanitized_user_login = sanitize_user( $user_login );
			$user_email = apply_filters( 'user_registration_email', $user_email );

			// Check the username was sanitized
			if ( $sanitized_user_login == '' ) {
				$errors->add( 'empty_username', __( 'Please enter a username.', 'geissinger-wpml' ) );
			} elseif ( ! validate_username( $user_login ) ) {
				$errors->add( 'invalid_username', __( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'geissinger-wpml' ) );
				$sanitized_user_login = '';
			} elseif ( username_exists( $sanitized_user_login ) ) {
				$errors->add( 'username_exists', __( 'This username is already registered. Please choose another one.', 'geissinger-wpml' ) );
			}

			// Check the e-mail address
			if ( $user_email == '' ) {
				$errors->add( 'empty_email', __( 'Please type your e-mail address.', 'geissinger-wpml' ) );
			} elseif ( ! is_email( $user_email ) ) {
				$errors->add( 'invalid_email', __( 'The email address isn\'t correct.', 'geissinger-wpml' ) );
				$user_email = '';
			} elseif ( email_exists( $user_email ) ) {
				$errors->add( 'email_exists', __( 'This email is already registered, please choose another one.', 'geissinger-wpml' ) );
			}

			$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

			if ( $errors->get_error_code() )
				return $errors;

			$user_pass = wp_generate_password( 12, false );
			$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );

			if ( ! $user_id ) {
				$errors->add( 'registerfail', __( 'Couldn\'t register you... please contact the site administrator', 'geissinger-wpml' ) );

				return $errors;
			}

			update_user_option( $user_id, 'default_password_nag', true, true ); // Set up the Password change nag.

			wp_new_user_notification( $user_id, $user_pass );

			return $user_id;
		}


		/**
		 * Setup our password retrieve function for the users that need to reset their login password.
		 * @param  String $user_data The username or email we need to search for to reset the password.
		 * @return Mixed
		 *
		 * @version 1.1
		 * @since 2.0
		 */
		function retrieve_password( $user_data ) {
			global $wpdb, $current_site;

			$errors = new WP_Error();

			if ( empty( $user_data ) ) {
				$errors->add( 'empty_username', __( 'Please enter a username or e-mail address.', 'geissinger-wpml' ) );
			} else if ( strpos( $user_data, '@' ) ) {
				$user_data = get_user_by( 'email', trim( $user_data ) );
				if ( empty( $user_data ) )
					$errors->add( 'invalid_email', __( 'There is no user registered with that email address.', 'geissinger-wpml'  ) );
			} else {
				$login = trim( $user_data );
				$user_data = get_user_by( 'login', $login );
			}

			do_action( 'lostpassword_post' );

			if ( $errors->get_error_code() )
				return $errors;

			if ( ! $user_data ) {
				$errors->add( 'invalidcombo', __( 'Invalid username or e-mail.', 'geissinger-wpml' ) );
				return $errors;
			}

			// redefining user_login ensures we return the right case in the email
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;

			do_action( 'retreive_password', $user_login );  // Misspelled and deprecated
			do_action( 'retrieve_password', $user_login );

			$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

			if ( ! $allow )
				return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user', 'geissinger-wpml' ) );
			else if ( is_wp_error( $allow ) )
				return $allow;

			$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );
			if ( empty( $key ) ) {
				// Generate something random for a key...
				$key = wp_generate_password( 20, false );
				do_action( 'retrieve_password_key', $user_login, $key );
				// Now insert the new md5 key into the db
				$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
			}
			$message = __( 'Someone requested that the password be reset for the following account:', 'geisinger-wpml' ) . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
			$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'geisinger-wpml' ) . "\r\n\r\n";
			$message .= __( 'To reset your password, visit the following address:', 'geisinger-wpml' ) . "\r\n\r\n";
			$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

			if ( is_multisite() ) {
				$blogname = $GLOBALS['current_site']->site_name;
			} else {
				// The blogname option is escaped with esc_html on the way into the database in sanitize_option
				// we want to reverse this for the plain text arena of emails.
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			}

			$title   = sprintf( __( '[%s] Password Reset' ), $blogname );
			$title   = apply_filters( 'retrieve_password_title', $title );
			$message = apply_filters( 'retrieve_password_message', $message, $key );

			if ( $message && ! wp_mail( $user_email, $title, $message ) ) {
				$errors->add( 'noemail', __( 'The e-mail could not be sent. Possible reason: your host may have disabled the mail() function.', 'geissinger-wpml' ) );

				return $errors;

				wp_die();
			}

			return true;
		}


		/**
		 * The source code for our login form. Version 2 supports ajaxable Login, Registration and Forgotten Password forms.
		 * @return html
		 * @actions before_wpml_title				Add items before the modal window title.
		 *          before_wpml_form				Add items before the form itself, or after the title.
		 *          inside_wpml_form_first		Add items before any of the form items but inside the form wrapper.
		 *          login_form 						Default WP action for custom login forms.
		 *          inside_wpml_form_submit		Add items in the submit wrapper.
		 *          inside_wpml_form_last		Add items at the end of the form wrapper after the submit button.
		 *          after_wpml_form				Add items after the form iteself.
		 *
		 * @version 2.0.1
		 * @since 1.0
		 */
		public function login_form() {
			global $user_ID, $user_identity;
			get_currentuserinfo();

			// Setup filterable titles
			$login_title     	  = apply_filters( 'geissinger_login_title', __( 'Login', 'geissinger-wpml' ) );
			$register_title  	  = apply_filters( 'geissinger_register_title', __( 'Register', 'geissinger-wpml' ) );
			$forgotten_title 	  = apply_filters( 'geissinger_forgotten_title', __( 'Forgotten Password?', 'geissinger-wpml' ) );

			// Setup filterable form labels
			$username_label  	  = apply_filters( 'geissinger_username_label', __( 'Username', 'geissinger-wpml' ) );
			$password_label  	  = apply_filters( 'geissinger_password_label', __( 'Password', 'geissinger-wpml' ) );
			$email_label	  	  = apply_filters( 'geissinger_email_label', __( 'Log In', 'geissinger-wpml' ) );
			$user_email_label   = apply_filters( 'geissinger_user_email_label', __( 'Username or Email', 'geissinger-wpml' ) );
			$remember_label  	  = apply_filters( 'geissinger_remember_label', __( 'Remember Me', 'geissinger-wpml' ) );

			// Setup filterable form buttons
			$login_btn_text     = apply_filters( 'geissinger_login_btn_text', __( 'Log In', 'geissinger-wpml' ) );
			$register_btn_text  = apply_filters( 'geissinger_register_btn_text', __( 'Sign Up', 'geissinger-wpml' ) );
			$forgotten_btn_text = apply_filters( 'geissinger_forgotten_btn_text', __( 'Username or Email', 'geissinger-wpml' ) ); ?>

			<div id="login-box" class="login-popup">
				<a href="#" class="close-btn"></a>

				<?php do_action( 'before_wpml_title' ); ?>

				<?php if( ! $user_ID ) : ?>
					<div class="section-container">

						<?php // Login Form ?>
						<div id="login" class="wpml-content">

							<h2><?php echo esc_attr( $login_title ); ?></h2>

							<?php do_action( 'before_wpml_login' ); ?>

							<form action="login" method="post" id="form" class="group" name="loginform">

								<?php do_action( 'inside_wpml_login_first' ); ?>

								<p>
									<label class="field-titles" for="login_user"><?php echo esc_attr( $username_label ); ?></label>
									<input type="text" name="log" id="login_user" class="input" value="<?php if ( isset( $user_login ) ) echo esc_attr( $user_login ); ?>" size="20" tabindex="1" />
								</p>

								<p>
									<label class="field-titles" for="login_pass"><?php echo esc_attr( $password_label ); ?></label>
									<input type="password" name="pwd" id="login_pass" class="input" value="" size="20" tabindex="2" />
								</p>

								<?php do_action( 'login_form' ); ?>

								<p id="forgetmenot">
									<label class="forgetmenot-label" for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php echo esc_attr( $remember_label ); ?></label>
								</p>

								<p class="submit">

									<?php do_action( 'inside_wpml_login_submit' ); ?>

									<input type="submit" name="wp-sumbit" id="wp-submit" class="button button-primary button-large" value="<?php echo esc_attr( $login_btn_text ); ?>" />
									<input type="hidden" name="login" value="true" />
									<?php wp_nonce_field( 'ajax-form-nonce', 'security' ); ?>

								</p><!--[END .submit]-->

								<?php do_action( 'inside_wpml_login_last' ); ?>

							</form><!--[END #loginform]-->
						</div><!--[END #login]-->

						<?php // Registration form ?>
						<div id="register" class="wpml-content" style="display:none;">

							<h2><?php echo $register_title; ?></h2>

							<?php do_action( 'before_wpml_register' ); ?>

							<form action="register" method="post" id="form" class="group" name="loginform">

								<?php do_action( 'inside_wpml_register_first' ); ?>

								<p>
									<label class="field-titles" for="reg_user"><?php echo esc_attr( $username_label ); ?></label>
									<input type="text" name="user_login" id="reg_user" class="input" value="<?php if ( isset( $user_login ) ) echo esc_attr( stripslashes( $user_login ) ); ?>" size="20" tabindex="1" />
								</p>

								<p>
									<label class="field-titles" for="reg_email"><?php echo esc_attr( $email_label ); ?></label>
									<input type="text" name="user_email" id="reg_email" class="input" value="<?php if ( isset( $user_email ) ) echo esc_attr( stripslashes( $user_email ) ); ?>" size="20" tabindex="3" />
								</p>

								<?php do_action( 'register_form' ); ?>

								<p class="submit">

									<?php do_action( 'inside_wpml_register_submit' ); ?>

									<input type="submit" name="user-sumbit" id="user-submit" class="button button-primary button-large" value="<?php echo esc_attr( $register_btn_text ); ?>" />
									<input type="hidden" name="register" value="true" />
									<?php wp_nonce_field( 'ajax-form-nonce', 'security' ); ?>

								</p><!--[END .submit]-->

								<?php do_action( 'inside_wpml_register_last' ); ?>

							</form>

						</div><!--[END #register]-->

						<?php // Forgotten Password ?>
						<div id="forgotten" class="wpml-content" style="display:none;">

							<h2><?php echo $forgotten_title; ?></h2>

							<?php do_action( 'before_wpml_forgotten' ); ?>

							<form action="forgotten" method="post" id="form" class="group" name="loginform">

								<?php do_action( 'inside_wpml_forgotton_first' ); ?>

								<p>
									<label class="field-titles" for="forgot_login"><?php echo esc_attr( $user_email_label ); ?></label>
									<input type="text" name="forgot_login" id="forgot_login" class="input" value="<?php if ( isset( $user_login ) ) echo esc_attr( stripslashes( $user_login ) ); ?>" size="20" />
								</p>

								<?php do_action( 'login_form', 'resetpass' ); ?>

								<p class="submit">

									<?php do_action( 'inside_wpml_forgotten_submit' ); ?>

									<input type="submit" name="user-submit" id="user-submit" class="button button-primary button-large" value="<?php echo esc_attr( $forgotten_btn_text ); ?>">
									<input type="hidden" name="forgotten" value="true" />
									<?php wp_nonce_field( 'ajax-form-nonce', 'security' ); ?>

								</p>

								<?php do_action( 'inside_wpml_forgotten_last' ); ?>

							</form>

						</div><!--[END #forgotten]-->
					</div><!--[END .section-container]-->
				<?php endif; ?>

				<?php do_action( 'after_wpml_form' ); ?>
			</div><!--[END #login-box]-->
		<?php }


		/**
		 * Adds some additional fields to the login_form(). Hooked through 'after_wpml_form'.
		 * @return void
		 *
		 * @version 1.1
		 * @since 2.0
		 */
		public function additional_options() {
			global $wpml_settings;

			$multisite_reg  = get_site_option( 'registration' );

			// Setup filterable button titles
			$register_link  = apply_filters( 'geissinger_register_link', __( 'Register', 'geissinger-wpml' ) );
			$forgotten_link = apply_filters( 'geissinger_forgotten_link', __( 'Lost your password?', 'geissinger-wpml' ) );
			$login_link 	 = apply_filters( 'geissinger_login_link', __( 'Back to Login', 'geissinger-wpml' ) );

			echo '<div id="additional-settings">';

			// Check if we have disabled this via the admin options first.
			if ( ! isset( $wpml_settings['remove-reg'] ) ) {
				if ( (get_option( 'users_can_register' ) && ! is_multisite() ) || ( $multisite_reg == 'all' || $multisite_reg == 'blog' || $multisite_reg == 'user' ) )
					echo '<span class="register-btn"><a href="#register" class="wpml-nav">' . esc_attr( $register_link ) . '</a></span>';
			}

			echo '<a href="#forgotten" class="wpml-nav">' . esc_attr( $forgotten_link ) . '</a>';

			echo '<div class="hide-login"> | <a href="#login" class="wpml-nav">' . esc_attr( $login_link ) . '</a></div>';

			echo '</div>';
		}


		/**
		 * Outputs the HTML for our login link.
		 * @param String  $login_text  The text for the login link. Default 'Login'.
		 * @param String  $logout_text The text for the logout link. Default 'Logout'.
		 * @param String  $logout_url  The url to redirect to when users logout. Empty by default.
		 * @param Boolean $show_admin  The setting to display the link to the admin area when logged in.
		 * @param Array   $class 		 Add in custom classes to the login/logout links. (added v2.1)
	 	 * @param Boolean $show_btn	 Apply a default button as defined in the admin options. (added v2.1)
		 * @return HTML
		 *
		 * @version 1.2
		 * @since 1.0
		 */
		public function modal_login_btn( $login_text = 'Login', $logout_text = 'Logout', $logout_url = '', $show_admin = true, $class = array(), $show_btn = false ) {
			// Check if we have an over riding logout redirection set. Other wise, default to the home page.
			if ( isset( $logout_url ) && $logout_url == '' )
				$logout_url = home_url();

			// Setup a filterable "view admin" link
			$view_admin = apply_filters( 'geissinger_view_admin_link', __( 'View Admin', 'geissinger-wpml' ) );

			$class = array(
				'login' => 'login-btn',
				'logout' => 'logout-btn',
				'global' => 'button',
			);

			// Parse out our array of classes
			if ( is_array( $class ) && ! empty( $class ) ) {

				// Contain our login classes in a variable
				if ( isset( $class['login'] ) && ! empty( $class['login'] ) )
					$login_class = ' ' . $class['login'];

				// Contain our logout classes in a variable;
				if ( isset( $class['logout'] ) && ! empty( $class['logout'] ) )
					$logout_class = ' ' . $class['logout'];

				// Get all of our global classes and append them to the login and logout classes
				if ( isset( $class['global'] ) && ! empty( $class['global'] ) ) {
					$global_class = $class['global'];

					$login_class = ' ' . $global_class . $login_class;
					$logout_class = ' ' . $global_class . $logout_class;
				}
			}

			// Is the user logged in? If so, serve them the logout button, else we'll show the login button.
			if ( is_user_logged_in() ) {
				$link = '<a href="' . wp_logout_url( esc_url( $logout_url ) ) . '" class="logout wpml-btn' . $logout_class . '">' . sprintf( _x( '%s', 'Logout Text', 'geissinger-wpml' ), sanitize_text_field( $logout_text ) ) . '</a>';
				if ( $show_admin )
					$link .= ' | <a href="' . esc_url( admin_url() ) . '">' . esc_attr( $view_admin ) . '</a>';
			} else {
				$link = '<a href="#login-box" class="login wpml-btn login-window' . $login_class . '">' . sprintf( _x( '%s', 'Login Text', 'geissinger-wpml' ), sanitize_text_field( $login_text ) ) . '</a></li>';
			}

			return $link;
		}


		/**
		 * The shortcode function. When we use the [wp-modal-login] shortcode, it will pull from this function.
		 * @param  Array $atts Contains all or any attributes used on the shortcode.
		 * @return String
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		function modal_login_btn_shortcode( $atts ) {
			extract( shortcode_atts( array(
				'login_text'   => 'Login',
				'logout_text'  => 'Logout',
				'logout_url'   => home_url(),
				'show_admin'   => false,
				'login_class'  => '',
				'logout_class' => '',
				'show_btn'		=> false,
			), $atts ) );

			if ( is_user_logged_in() ) {
				$link = '<a href="' . wp_logout_url( esc_url( $logout_url ) ) . '" class="logout wpml-btn ' . $login_class . '">' . sprintf( _x( '%s', 'Shortcode Logout Text', 'geissinger-wpml' ), sanitize_text_field( $logout_text ) ) . '</a>';
			} else {
				$link = '<a href="#login-box" class="login wpml-btn login-window ' . $logout_class . '">' . sprintf( _x( '%s', 'Shortcode Login Text', 'geissinger-wpml' ), sanitize_text_field( $login_text ) ) . '</a></li>';
			}

			return $link;
		}
	}