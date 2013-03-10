<?php

	class Geissinger_WP_Modal_Login_Widget extends WP_Widget {

		/**
		 * The widget constructor. Specifies the classname and description, instantiates the widget,
		 * loads localization files, and includes necessary scripts and styles.
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		function Geissinger_WP_Modal_Login_Widget() {
			$widget_opts = array(
				'classname'   => 'wpml-widget',
				'description' => __( 'Display a login/logout link that displays a pop-up login form', 'geissinger_wpml' ),
			);

			$this->WP_Widget( 'Geissinger_WP_Modal_Login_Widget', __( 'WP Modal Login', 'geissinger_wpml' ), $widget_ops );
		}


		/**
		 * Outputs the content of the form in the widgets page.
		 * @param  Array $args      The array of form elements
		 * @param  Array $instance
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );

			$widget_title = empty( $instance['widget-title'] ) ? '' : apply_filters( 'widget-title', $instance['widget-title'] );
			$login_text   = empty( $instance['login-text'] )   ? '' : apply_filters( 'login-text', $instance['login-text'] );
			$logout_text  = empty( $instance['logout-text'] )  ? '' : apply_filters( 'logout-text', $instance['logout-text'] );
			$logout_url   = empty( $instance['logout-url'] )   ? '' : apply_filters( 'logout-url', $instance['logout-url'] );

			echo $before_widget;

			if ( ! empty( $instance['widget-title'] ) ) {
				echo $args['before_title'];

				echo esc_html( $instance['widget-title'] );

				echo $args['after_title'];
			}

			echo Geissinger_WP_Modal_Login::modal_login_btn( $login_text, $logout_text, $logout_url );

			echo $after_widget;
		}


		/**
		 * Processes the widget's options to be saved.
		 * @param  Array $new_instance  The new instance of values to be generated via the update.
		 * @param  [type] $old_instance The previous instance of values before the update.
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['widget-title'] = strip_tags( stripslashes( $new_instance['widget-title'] ) );
			$instance['login-text']   = strip_tags( stripslashes( $new_instance['login-text'] ) );
			$instance['logout-text']  = strip_tags( stripslashes( $new_instance['logout-text'] ) );
			$instance['logout-url']   = strip_tags( stripslashes( $new_instance['logout-url'] ) );

			return $instance;
		}


		/**
		 * Generates the administration form for the widget.
		 * @param  Array $instance The array of keys and values for the widget.
		 *
		 * @version 1.0
		 * @since 1.0
		 */
		function form( $instance ) {
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'widget-title' => '',
					'login-text' 	=> '',
					'logout-text'  => '',
					'logout-url' 	=> '',
				)
			);

			$widget_title = strip_tags( stripslashes( $instance['widget-title'] ) );
			$login_text   = strip_tags( stripslashes( $instance['login-text'] ) );
			$logout_text  = strip_tags( stripslashes( $instance['logout-text'] ) );
			$logout_url   = strip_tags( stripslashes( $instance['logout-url'] ) );

			include( 'views/form.php' );
		}
	}
