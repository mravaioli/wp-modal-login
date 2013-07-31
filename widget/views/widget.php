<?php
	echo $args['before_title'];

	if ( ! empty( $widget_title ) )
		echo esc_html( $widget_title );

	echo $args['after_title'];

	echo add_modal_login_button( $login_text, $logout_text, $logout_url, $show_admin );