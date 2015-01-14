<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for "Extensions" discovery page.
 *
 * @uses   Easing_Slider
 * @author Matthew Ruddy
 */
class ES_Extensions_Page {

	/**
	 * Adds our "extensions" page user capabilities
	 *
	 * @return void
	 */
	public function add_capabilities() {

		// Get user role
		$role = get_role( 'administrator' );

		// Add the capability
		$role->add_cap( 'easingslider_discover_extensions' );

	}

	/**
	 * Removes our "extensions" page user capabilities
	 *
	 * @return void
	 */
	public function remove_capabilities() {

		// Get user role
		$role = get_role( 'administrator' );

		// Remove the capability
		$role->remove_cap( 'easingslider_discover_extensions' );

	}

	/**
	 * Adds our page to the "Sliders" menu
	 *
	 * @return void
	 */
	public function add_submenu_page() {

		// "Extensions" page
		$hook = add_submenu_page(
			'easingslider_edit_sliders',
			__( 'Extensions', 'easingslider' ),
			__( 'Extensions', 'easingslider' ),
			'easingslider_discover_extensions',
			'easingslider_discover_extensions',
			array( $this, 'display_view' )
		);

		// Page-specific hooks
		add_action( "admin_print_styles-{$hook}", array( $this, 'enqueue_styles' ) );

	}

	/**
	 * Registers all of our extensions page assets
	 *
	 * @return void
	 */
	public function register_assets() {

		// Get our directory
		$css_dir = plugin_dir_url( Easing_Slider::$file ) . '/css';

		// Get file suffix
		$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Register our stylesheet
		wp_register_style( 'easingslider-extensions-page', "{$css_dir}/extensions-page{$suffix}.css", false, Easing_Slider::$version );

	}

	/**
	 * Enqueues all of our extensions page styles
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// Enqueue our stylesheet
		wp_enqueue_style( 'easingslider-extensions-page' );

	}

	/**
	 * Returns an array of available extensions
	 *
	 * @return array
	 */
	public function available_extensions() {

		$available_extensions = array(
			(object) array(
				'title'   => __( 'Visual Customizer', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2014/12/customizer.jpg',
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi inventore corporis a omnis placeat cum, quam modi repellat incidunt, corrupti enim voluptate iusto unde sapiente labore suscipit tenetur voluptatem. Debitis.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/visual-customizer/'
			),
			(object) array(
				'title'   => __( 'HTML Captions', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2014/12/captions.jpg',
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi inventore corporis a omnis placeat cum, quam modi repellat incidunt, corrupti enim voluptate iusto unde sapiente labore suscipit tenetur voluptatem. Debitis.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/html-captions/'
			),
			(object) array(
				'title'   => __( 'Touch & Swipe', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2014/12/touch.jpg',
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi inventore corporis a omnis placeat cum, quam modi repellat incidunt, corrupti enim voluptate iusto unde sapiente labore suscipit tenetur voluptatem. Debitis.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/touch-swipe/'
			),
			(object) array(
				'title'   => __( 'Lightbox', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2014/12/lightbox.jpg',
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi inventore corporis a omnis placeat cum, quam modi repellat incidunt, corrupti enim voluptate iusto unde sapiente labore suscipit tenetur voluptatem. Debitis.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/lightbox/'
			),
			(object) array(
				'title'   => __( 'Images from URL', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2014/12/images-from-url.jpg',
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi inventore corporis a omnis placeat cum, quam modi repellat incidunt, corrupti enim voluptate iusto unde sapiente labore suscipit tenetur voluptatem. Debitis.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/images-url/'
			),
			(object) array(
				'title'   => __( 'Featured Content', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2014/12/featured-content.jpg',
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi inventore corporis a omnis placeat cum, quam modi repellat incidunt, corrupti enim voluptate iusto unde sapiente labore suscipit tenetur voluptatem. Debitis.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/featured-content/'
			)
		);

		// Randomize
		shuffle( $available_extensions );

		return $available_extensions;

	}

	/**
	 * Displays the view
	 *
	 * @return void
	 */
	public function display_view() {

		// Get available extensions
		$extensions = $this->available_extensions();

		// Display the view
		require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/discover-extensions.php';

	}

}