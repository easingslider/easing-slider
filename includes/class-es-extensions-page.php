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
		$css_dir = plugin_dir_url( Easing_Slider::$file ) . 'css';

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
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/customizer1.jpg',
				'content' => __( 'The Visual Customizer extension is a complete customization tool for beautifully sculpting the design of your sliders. Create a unique look and feel for each slider.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/visual-customizer/'
			),
			(object) array(
				'title'   => __( 'HTML Captions', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/captions1.jpg',
				'content' => __( 'The “HTML Captions” extensions makes adding HTML captions and content to Easing Slider a breeze.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/html-captions/'
			),
			(object) array(
				'title'   => __( 'Touch & Swipe', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/touch1.jpg',
				'content' => __( 'The “Touch & Swipe” extension enables slider touch gestures on mobile devices. iPhones, iPads, Androids, if it can be touched this is the extension for you.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/touch-swipe/'
			),
			(object) array(
				'title'   => __( 'Lightbox', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/lightbox1.jpg',
				'content' => __( 'The Lightbox extension adds support for the jQuery Lightbox script. This simple extension makes it incredibly easy to create lightbox images or galleries for your slider.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/lightbox/'
			),
			(object) array(
				'title'   => __( 'Images from URL', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/url-images.jpg',
				'content' => __( 'The Images from URL extension makes adding image slides from an external URL simple.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/images-url/'
			),
			(object) array(
				'title'   => __( 'Featured Content', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/featured-content1.jpg',
				'content' => __( 'The Featured Content extension allows you to source slides from posts types, taxonomies and other supported WordPress queries. Perfect for featured posts, etc.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/featured-content/'
			),
			(object) array(
				'title'   => __( 'Videos', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/video.jpg',
				'content' => __( 'The “Videos” extension makes adding video slides to your sliders a piece of cake. With this extension, you can create video slides from YouTube or Vimeo.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/videos/'
			),
			(object) array(
				'title'   => __( 'Schedule', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/schedule.jpg',
				'content' => __( 'The “Schedule” extension for Easing Slider allows you to easily schedule both sliders and individual slides to be displayed at specific times and dates.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/schedule/'
			),
			(object) array(
				'title'   => __( 'Nextgen Gallery', 'easingslider' ),
				'image'   => 'http://easingslider.com/wp-content/uploads/edd/2015/01/nextgen-gallery.jpg',
				'content' => __( 'The “Nextgen Gallery” extension allows you to easily integrate Easing Slider with your Nextgen galleries.', 'easingslider' ),
				'link'    => 'http://easingslider.com/extensions/nextgen-gallery/'
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