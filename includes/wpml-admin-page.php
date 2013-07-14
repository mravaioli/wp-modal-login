<?php

	/**
	 * Creates the admin menu for our WP Modal Login page.
	 *
	 * TODO: Update code to OOP, for whatever reason, containing all of this into a PHP class has proven to cause errors??
	 */

	/**
	 * Add our custom JavaScript to the admin page ONLY.
	 * Action is carried out in geissinger_setup_wpml_admin_menu() and is setup to only load
	 * this function when our admin page is loaded.
	 * @return void
	 *
	 * @version 1.0
	 * @since 2.0
	 */
	function geissinger_admin_resources() {
		wp_enqueue_script( 'wpml-admin-script', plugins_url( 'js/wp-modal-login-admin.min.js', dirname( __FILE__ ) ), array( 'jquery' ), '2.0.5', true );
	}


	/**
	 * Register the admin page with the 'admin_menu'
	 * @return void
	 *
	 * @version 1.1
	 * @since 2.0
	 */
	function geissinger_setup_wpml_admin_menu() {
		$page = add_submenu_page( 'options-general.php', __( 'WP Modal Login', 'geissinger-wpml' ), __( 'WP Modal Login', 'geissinger-wpml' ), 'manage_options', 'wpml-options', 'geissinger_wpml_options', 99 );

		add_action( 'admin_print_styles-' . $page, 'geissinger_admin_resources' );
	}
	add_action( 'admin_menu', 'geissinger_setup_wpml_admin_menu' );


	/**
	 * Load our HTML that will create the outter shell of the admin page
	 * @return HTML
	 *
	 * @version 1.1
	 * @since 2.0
	 */
	function geissinger_wpml_options() {

		// Check that the user is able to view this page.
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'geissinger-wpml' ) ); ?>

		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2><?php _e( 'WordPress Modal Login Options', 'geissinger-wpml' ); ?></h2>

			<?php // settings_errors(); ?>

			<form action="options.php" method="post">
				<?php settings_fields( 'geissinger_wpml_settings_options' ); ?>
				<?php do_settings_sections( 'geissinger_wpml_settings_options' ); ?>
				<?php settings_fields( 'geissinger_wpml_widget_options' ); ?>
				<?php do_settings_sections( 'geissinger_wpml_widget_options' ); ?>
				<?php submit_button(); ?>
			</form>

		</div>
	<?php }


	/**
	 * Registers all of our sections and fields with the Settings API (http://codex.wordpress.org/Settings_API)
	 * @return void
	 *
	 * @version 1.
	 * @since 2.0
	 */
	function geissinger_init_settings_registration() {
		$option_name = 'geissinger_wpml_options';

		// Check if our settings options exist in the database. If not, add them.
		if ( get_option( 'geissinger_wpml_options' ) )
			add_option( 'geissinger_wpml_options' );

		// Define our settings sections.
		add_settings_section( 'wpml_settings_section', __( 'Modal Theme', 'geissinger-wpml' ), 'geissinger_wpml_settings_options', 'geissinger_wpml_settings_options' );
		add_settings_section( 'wpml_widget_settings_section', __( 'Widget Options', 'geissinger-wpml' ), 'geissinger_wpml_widget_options', 'geissinger_wpml_widget_options' );


		/*** Settings Fields for 'geissinger_wpmp_settings_options' section ***/
		add_settings_field( 'show_shortcode_button', __( 'Show Shortcode Button', 'geissinger-wpml' ), 'geissinger_settings_field_checkbox', 'geissinger_wpml_settings_options', 'wpml_settings_section', array(
			'options-name' => $option_name,
			'id' 				=> 'display-shortcode-btn',
			'class' 			=> '',
			'value' 		   => 'true',
			'label' 			=> __( 'Enable the shortcode button found with the page & post editor.', 'geissinger-wpml' ),
		) );
		add_settings_field( 'modal_theme', __( 'Select A Theme', 'geissinger-wpml' ), 'geissinger_settings_field_select', 'geissinger_wpml_settings_options', 'wpml_settings_section', array(
			'options-name' => $option_name,
			'id'				=> 'modal-theme',
			'class' 			=> '',
			'value'			=> array(
										'default' => __( 'Default' , 'geissinger-wpml' ),
										'theme-1' => __( 'Theme 1', 'geissinger-wpml' ),
										'theme-2' => __( 'Theme 2', 'geissinger-wpml' ),
										'custom'  => __( 'None', 'geissinger-wpml' ),
									),
			'label'			=> __( 'Select a new theme for your modal login window. You may choose to load no theme and load in your own custom CSS styles if you wish.', 'geissinger-wpml' ),
		) );
		add_settings_field( 'add_custom_css', __( 'Add Custom CSS', 'geissinger-wpml' ), 'geissinger_settings_field_checkbox', 'geissinger_wpml_settings_options', 'wpml_settings_section', array(
			'options-name' => $option_name,
			'id'				=> 'add-custom-css',
			'class' 			=> '',
			'value'			=> 'custom',
			'label'			=> __( 'Add custom CSS if you wish to over ride any styles in the selected theme.', 'geissinger-wpml' ),
		) );
		add_settings_field( 'custom_css', __( 'Custom CSS', 'geissinger-wpml' ), 'geissinger_settings_field_textarea', 'geissinger_wpml_settings_options', 'wpml_settings_section', array(
			'options-name' => $option_name,
			'id'				=> 'custom-css',
			'class'			=> '',
			'value'			=> '',
			'label'			=> __( 'Add your custom CSS code.' ),
		) );
		add_settings_field( 'remove_reg', __( 'Disable Register Button', 'geissinger-wpml' ), 'geissinger_settings_field_checkbox', 'geissinger_wpml_settings_options', 'wpml_settings_section', array(
			'options-name' => $option_name,
			'id'				=> 'remove-reg',
			'class'			=> '',
			'value'			=> '',
			'label'			=> __( 'Remove the "Register" link in the modal window. (Useful if you want to use a separate registration page)' ),
		) );


		/*** Settings Fields for 'geissinger_wpml_widget_options' ***/
		add_settings_field( 'show_widget', __( 'Show Widget', 'geissinger-wpml' ), 'geissinger_settings_field_checkbox', 'geissinger_wpml_widget_options', 'wpml_widget_settings_section', array(
			'options-name' => $option_name,
			'id' 				=> 'display-widget',
			'class' 			=> '',
			'value' 			=> 'true',
			'label' 			=> __( 'Enable the widget found in Appearance > Widgets.', 'geissinger-wpml' ),
		) );


		// Register our settings with WordPress so we can save to the Database
		register_setting( 'geissinger_wpml_settings_options', 'geissinger_wpml_options', 'geissinger_wpml_options_sanitize' );
		register_setting( 'geissinger_wpml_widget_options', 'geissinger_wpml_options', 'geissinger_wpml_options_sanitize' );
	}
	add_action( 'admin_init', 'geissinger_init_settings_registration' );


	/**
	 * This function is used in the add_settings_section() function for the theme options. Currently we have no data to really push here...
	 * @return void
	 *
	 * @version 1.0
	 * @since 2.0
	 */
	function geissinger_wpml_settings_options() {
		echo '<p>' . __( 'Customize the look and feel of the modal login window and other options', 'geissinger-wpml' ) . '.</p>';
	}


	/**
	 * This function is used in the add_settings_section() function for the widget options. Currently we have no data to really push here...
	 * @return void
	 *
	 * @version 1.0
	 * @since 2.0
	 */
	function geissinger_wpml_widget_options() {
		echo '<p>' . __( 'Customize the options for the Widget', 'geissinger-wpml' ) . '.</p>';
	}


	/**
	 * The callback function to display our textareas
	 * @param  Array $args An array of our arguments passed in the add_settings_field() function
	 * @return HTML
	 *
	 * @version 1.1
	 * @since 2.0
	 */
	function geissinger_settings_field_textarea( $args ) {
		// Set the options-name value to a variable
		$name = $args['options-name'] . '[' . $args['id'] . ']';

		// Get the options from the database
		$options = get_option( $args['options-name'] ); ?>

		<label for="<?php echo $args['id']; ?>"><?php esc_attr_e( $args['label'] ); ?></label><br />
		<textarea name="<?php echo $name; ?>" id="<?php echo $args['id']; ?>" class="large-text<?php if ( ! empty( $args['class'] ) ) echo ' ' . $args['class']; ?>" cols="30" rows="10"><?php esc_attr_e( $options[ $args['id'] ] ); ?></textarea>
	<?php }


	/**
	 * The callback function to display our checkboxes
	 * @param  Array $args An array of our arguments passed in the add_settings_field() function
	 * @return HTML
	 *
	 * @version 1.1
	 * @since 2.0
	 */
	function geissinger_settings_field_checkbox( $args ) {
		// Set the options-name value to a variable
		$name = $args['options-name'] . '[' . $args['id'] . ']';

		// Get the options from the database
		$options = get_option( $args['options-name'] ); ?>

		<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $args['id']; ?>" <?php if ( ! empty( $args['class'] ) ) echo 'class="' . $args['class'] . '" '; ?>value="<?php esc_attr_e( $args['value'] ); ?>" <?php if ( isset( $options[ $args['id'] ] ) ) checked( $args['value'], $options[ $args['id'] ], true ); ?> />
		<label for="<?php echo $args['id']; ?>"><?php esc_attr_e( $args['label'] ); ?></label>
	<?php }


	/**
	 * The callback function to display our selection dropdown
	 * @param  Array $args An array of our arguments passed in the add_settings_field() function
	 * @return HTML
	 *
	 * @version 1.1
	 * @since 2.0
	 */
	function geissinger_settings_field_select( $args ) {
		// Set the options-name value to a variable
		$name = $args['options-name'] . '[' . $args['id'] . ']';

		// Get the options from the database
		$options = get_option( $args['options-name'] ); ?>

		<select name="<?php echo $name; ?>" id="<?php echo $args['id']; ?>" <?php if ( ! empty( $args['class'] ) ) echo 'class="' . $args['class'] . '" '; ?>>
			<?php foreach ( $args['value'] as $key => $value ) : ?>
				<option value="<?php esc_attr_e( $key ); ?>"<?php if ( isset( $options[ $args['id'] ] ) ) selected( $key, $options[ $args['id'] ], true ); ?>><?php esc_attr_e( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<label for="<?php echo $args['id']; ?>" style="display:block;"><?php esc_attr_e( $args['label'] ); ?></label>
	<?php }


	/**
	 * Our sanitization function. This will clean any entrees before submitted to the database.
	 * @param  String $input The data to be sanitized
	 * @return String
	 *
	 * @version 1.0
	 * @since 2.0
	 */
	function geissinger_wpml_options_sanitize( $input ) {

		// Set our array for the sanitized options
		$output = array();

		// Loop through each of our $input options and sanitize them.
		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) )
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );
		}

		return apply_filters( 'geissinger_wpml_options_sanitize', $output, $input );
	}

