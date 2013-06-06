<?php
	// Load the WordPress Bootstrap
	$wp_include = "../wp-load.php";
	$i = 0;

	while ( ! file_exists( $wp_include ) && $i++ < 10 ) {
		$wp_include = "../$wp_include";
	}

	//let's load WordPress
	require( $wp_include );

	if ( ! is_user_logged_in() || !current_user_can( 'edit_posts' ) )
		wp_die( __( 'Oops! You can\'t call this page directly.', 'geissinger_wpml' ) );
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title><?php _e( 'Add Modal Login Shortcode', 'geissinger_wpml' ); ?></title>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />

		<link rel="stylesheet" href="<?php echo admin_url(); ?>css/wp-admin.min.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo includes_url(); ?>css/buttons.min.css" type="text/css" media="screen" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

		<!-- Load WordPress' built in TinyMCE scripts into our thickbox modal window -->
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/tinymce/utils/mctabs.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/tinymce/utils/form_utils.js"></script>

		<!-- Load our custom tinymce scripts which outputs all the HTML needed for our layouts -->
		<script language="javascript" type="text/javascript" src="<?php echo plugins_url( '/tinymce.js', __FILE__ ); ?>"></script>
	</head>
	<body id="shortcode-form" class="wp-core-ui" onLoad="tinyMCEPopup.executeOnLoad( 'init();' );">
		<form name="wp_modal_shortcode" action="#">
			<table class="form-table" style="width:90%;">
				<tr valign="top">
					<th scope="row">
						<label for="login-text"><?php _e( 'Login Text', 'geissinger_wpml' ); ?></label>
					</th>
					<td>
						<input type="text" name="login-text" id="login-text" value="" class="regular-text" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="logout-text"><?php _e( 'Logout Text', 'geissinger_wpml' ); ?></label>
					</th>
					<td>
						<input type="text" name="logout-text" id="logout-text" value="" class="regular-text" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="logout-url"><?php _e( 'Logout Redirect URL', 'geissinger_wpml' ); ?></label>
					</th>
					<td>
						<input type="text" name="logout-url" id="logout-url" value="" class="regular-text" />
					</td>
				</tr>
			</table>
			<p class="submit" style="width:90%;">
				<input type="submit" id="submit" name="submit-modal-shortcode" class="button button-primary" value="Insert Modal Login Shortcode" onClick="insertShortcode();" />
			</p>
		</form>
	</body>
</html>
