<?php
	if ( ! empty( $instance['widget-title'] ) ) {
		echo $args['before_title'];

		echo esc_html( $instance['widget-title'] );

		echo $args['after_title'];
	}

	echo add_modal_login_button( $login_text, $logout_text, $logout_url, $show_admin );