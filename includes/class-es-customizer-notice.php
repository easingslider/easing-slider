<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class adds a notice to the WordPress admin area telling users that the 
 * "Customizer" functionality will soon be extracted into a separate extension.
 *
 * @author Matthew Ruddy
 */
class ES_Customizer_Notice {

	/**
	 * Sets the database "flag" option for this notice
	 *
	 * @return void
	 */
	public function set_flag() {

		add_option( 'easingslider_dismissed_customizer_notice', true );

	}

	/**
	 * Deletes the database "flag" option for this notice
	 *
	 * @return void
	 */
	public function unset_flag() {

		delete_option( 'easingslider_dismissed_customizer_notice' );

	}

	/**
	 * Dismisses the notice, if applicable
	 *
	 * @return void
	 */
	public function handle_dismiss() {

		// Check for dismissal
		if ( ! empty( $_GET['dismiss_customizer_notice'] ) ) {

			// Set the flag, telling us that the user has dismissed the notice
			$this->set_flag();

		}

	}

	/**
	 * Displays the notice
	 *
	 * @return void
	 */
	public function display() {

		// Bail if not an plugin admin page
		if ( ! isset( $_GET['page'] ) ) {
			return;
		}

		// Get the current page URL
		$current_page = "{$_SERVER['REQUEST_URI']}&amp;dismiss_customizer_notice=true";

		// Populate the message
		$message = sprintf( __( 'The Easing Slider "Customizer" functionality will soon be removed from this plugin and made available as an extension. If you use the "Customize" panel, download and activate the free extension <a href="%s" target="_blank">here</a> to prevents any disruptions. <a href="%s">Dismiss</a>.', 'easingslider' ), 'http://easingslider.com/extensions/visual-customizer', $current_page );

		// Bail if we've dismissed the notice
		if ( get_option( 'easingslider_dismissed_customizer_notice' ) ) {
			return;
		}

		// Tell the user
		if ( function_exists( 'easingslider_show_notice' ) ) {
			easingslider_show_notice( $message, 'updated notice' );
		}

	}
}