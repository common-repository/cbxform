<!-- This file is used to markup the administration form of the widget. -->

<!-- Custom  Title Field -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','cbxform'); ?></label>

	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e('Select Form','cbxform'); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>">
		<?php
		$args = array(
			'post_type'      => 'cbxform',
			'orderby'        => 'ID',
			'order'          => 'DESC',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$my_query = new WP_Query($args);

		while ($my_query->have_posts()) : $my_query->the_post();
			$post_id = get_the_ID();
			echo  '<option value="'.$post_id.'" '.selected($post_id, $id, false).'>'.sprintf(__('%s (ID: %d)', 'cbxform'), get_the_title(),$post_id) . '</a></option>';
		endwhile;
		wp_reset_query(); //needed

		?>
	</select>
</p>
<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
<?php
do_action('cbxformsinglewidget_form_admin', $instance, $this)
?>