<?php

	/**
	 * Extends the WordPress widget class by specifying our own custom Modal Login Widget
	 *
	 * @version 1.1.1
	 * @since 1.0
	 */
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
				'description' => __( 'Display a login/logout link that displays a pop-up login form', 'geissinger-wpml' ),
			);

			$this->WP_Widget( 'Geissinger_WP_Modal_Login_Widget', __( 'WP Modal Login', 'geissinger-wpml' ), $widget_opts );
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

			$widget_title = ( ! empty( $instance['widget-title'] ) ) ? apply_filters( 'widget-title', $instance['widget-title'] ) : '';
			$login_text   = ( ! empty( $instance['login-text'] ) )   ? apply_filters( 'login-text',   $instance['login-text'] )   : '';
			$logout_text  = ( ! empty( $instance['logout-text'] ) )  ? apply_filters( 'logout-text',  $instance['logout-text'] )  : '';
			$logout_url   = ( ! empty( $instance['logout-url'] ) )   ? apply_filters( 'logout-url',   $instance['logout-url'] )   : '';
			$show_admin   = ( ! empty( $instance['show-admin'] ) )   ? apply_filters( 'show-admin',   $instance['show-admin'] )   : false;

			echo $before_widget;

			include_once( 'views/widget.php');

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
			$instance['show-admin']   = intval( $new_instance['show-admin'] );

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
					'show-admin'	=> 1,
				)
			);

			$widget_title = strip_tags( stripslashes( $instance['widget-title'] ) );
			$login_text   = strip_tags( stripslashes( $instance['login-text'] ) );
			$logout_text  = strip_tags( stripslashes( $instance['logout-text'] ) );
			$logout_url   = strip_tags( stripslashes( $instance['logout-url'] ) );
			$show_admin	  = intval( $instance['show-admin'] );

			include( 'views/form.php' );
		}
	}
