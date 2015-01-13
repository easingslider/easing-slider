<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a 'Sliders' widget to the WordPress widgets interface
 *
 * @uses   ES_Slider
 * @author Matthew Ruddy
 */
class ES_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		// Call parent constructor
		parent::__construct(
			'easingslider_widget',
			__( 'Slider', 'easingslider' ),
			array( 'description' => __( 'Display a slider using a widget', 'easingslider' ) )
		);

	}

	/**
	 * Registers our widget
	 *
	 * @return void
	 */
	public function register() {

		// Register this widget
		register_widget( __CLASS__ );

	}

	/**
	 * Widget logic
	 *
	 * @param array $args     The widget arguments
	 * @param array $instance The widget instance
	 * @return void
	 */
	public function widget( $args, $instance ) {

		// Extract arguments
		extract( $args );

		// Before widget
		echo $before_widget;

		// Display title
		if ( ! empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widgets_title', $instance['title'] ) . $after_title;
		}

		// Display the slider
		if ( ! empty( $instance['id'] ) ) {
			echo do_shortcode( "[easingslider id=\"{$instance['id']}\"]" );
		}

		// After widget
		echo $after_widget;

	}

	/**
	 * Returns updated settings array. Also does some sanatization.
	 *
	 * @param  array $new_instance The new widget settings
	 * @param  array $old_instance The old widget settings
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
	   
		return array(
			'id'    => intval( $new_instance['id'] ),
			'title' => strip_tags( $new_instance['title'] )
		);
		
	}

	/**
	 * Widget settings form
	 *
	 * @param array $instance The widget instance
	 */
	public function form( $instance ) {

		// Get all of the sliders
		$sliders = ES_Slider::all();

		// Print the settings
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'easingslider' ); ?></label>
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Select Slider:', 'easingslider' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" class="widefat">
					<option value="-1"><?php _e( '&#8212; Select &#8212;', 'easingslider' ); ?></option>
					<?php foreach ( $sliders as $slider ) : ?>
						<option value="<?php echo esc_attr( $slider->ID ); ?>" <?php if ( isset( $instance['id'] ) ) selected( $instance['id'], $slider->ID ); ?>><?php echo esc_html( $slider->post_title ) . sprintf( __( ' (ID #%s)', 'easingslider' ), $slider->ID ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		<?php

	}

}