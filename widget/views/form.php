<p>
	<label for="widget-title"><?php _e( 'Title:' ); ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'widget-title' ); ?>" name="<?php echo $this->get_field_name( 'widget-title' ); ?>" value="<?php echo $widget_title; ?>">
</p>

<p>
	<label for="login-text"><?php _e( 'Login Text:' ); ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'login-text' ); ?>" name="<?php echo $this->get_field_name( 'login-text' ); ?>" value="<?php echo $instance['login-text']; ?>">
</p>

<p>
	<label for="logout-text"><?php _e( 'Logout Text:' ); ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'logout-text' ); ?>" name="<?php echo $this->get_field_name( 'logout-text' ); ?>" value="<?php echo $instance['logout-text']; ?>">
</p>

<p>
	<label for="logout-url"><?php _e( 'Logout Redirection URL:' ); ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'logout-url' ); ?>" name="<?php echo $this->get_field_name( 'logout-url' ); ?>" value="<?php echo $instance['logout-url']; ?>">
</p>

<p>
	<label for="show-admin"><?php _e( 'Show Admin Link:' ); ?></label>
	<input type="checkbox" id="<?php echo $this->get_field_id( 'show-admin' ); ?>" name="<?php echo $this->get_field_name( 'show-admin' ); ?>" value="1" <?php checked( $instance['show-admin'] ); ?>
</p>
