<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This code is for debugging purposes.
 * Uncomment this line to force Easing Slider to fetch the available extensions
 */
// delete_transient( 'easingslider_available_extensions' );

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
	 * @return false|array
	 */
	public function available_extensions() {

		// Get the cached extensions feed. If it has expired, fetch it again.
		if ( false === ( $available_extensions = get_transient( 'easingslider_available_extensions' ) ) ) {

			// Fetch the feed
			$request = wp_remote_get( 'http://easingslider.com/?feed=extensions' );

			// Return false if we encounter an error
			if ( is_wp_error( $request ) ) {
				return false;
			}

			// Check for response body
			if ( ! isset( $request['body'] ) ) {
				return false;
			}

			// Get available extensions
			$available_extensions = json_decode( $request['body'] );

			// Cache response
			set_transient( 'easingslider_available_extensions', $available_extensions, 3600 );

		}

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