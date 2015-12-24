<?php

class DA_Widget extends WP_Widget
{
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct()
	{
		parent::__construct(
			'da_widget',
			__( 'Advertising', 'da' ),
			array(
				'description' => __( 'Display Advertising', 'da' ),
			)
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance )
	{
		// outputs the content of the widget
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 *
	 * @return void
	 */
	public function form( $instance )
	{
		$terms          = get_terms( 'position' );
		$current_term   = esc_attr( $instance['position'] );
		$current_num    = esc_attr( $instance['number'] );
		$current_num    = empty( $current_num ) ? 5 : $current_num;
?>
		<p>
			<label><?php _e( 'Position' ); ?>:</label>
			<select name="<?php echo $this->get_field_name( 'position' ); ?>">
				<option value=""><?php _e( 'All', 'da' ); ?></option>
				<?php
				foreach ( $terms as $term )
				{
					printf( '
						<option value="%s"%s>%s</option>',
						$term->term_id,
						$term->term_id == $current_term ? 'selected="selected"' : '',
						$term->name
					);
				}
				?>
			</select>
		</p>
		<p>
			<label><?php _e( 'Number' ); ?>:</label>
			<input type="number" value="<?php echo $current_num; ?>" name="<?php echo $this->get_field_name( 'number' ); ?>">
		</p>
<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return void
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance               = $old_instance;
		$instance['position']   = strip_tags( $new_instance['position'] );
		$instance['number']     = strip_tags( $new_instance['number'] );

		return $instance;
	}
}

add_action( 'widgets_init', function()
{
	register_widget( 'DA_Widget' );
} );