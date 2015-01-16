<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines our admin settings.
 *
 * @uses   Easing_Slider
 * @author Matthew Ruddy
 */
class ES_Settings_Page {

	/**
	 * Adds our settings user capabilities
	 *
	 * @return void
	 */
	public function add_capabilities() {

		// Get user role
		$role = get_role( 'administrator' );

		// Add the capability
		$role->add_cap( 'easingslider_manage_settings' );

	}

	/**
	 * Removes our settings user capabilities
	 *
	 * @return void
	 */
	public function remove_capabilities() {

		// Get user role
		$role = get_role( 'administrator' );

		// Remove the capability
		$role->remove_cap( 'easingslider_manage_settings' );

	}

	/**
	 * Adds our "Settings" page to the plugin admin menu
	 *
	 * @return void
	 */
	public function add_submenu_page() {

		// "Settings" page
		$hook = add_submenu_page(
			'easingslider_edit_sliders',
			__( 'Edit Settings', 'easingslider' ),
			__( 'Settings', 'easingslider' ),
			'easingslider_manage_settings',
			'easingslider_manage_settings',
			array( $this, 'display_view' )
		);

		// Page-specific hooks
		add_action( "load-{$hook}",                array( $this, 'do_actions' ) );
		add_action( "admin_print_styles-{$hook}",  array( $this, 'enqueue_styles' ) );

	}

	/**
	 * Registers all of our settings assets
	 *
	 * @return void
	 */
	public function register_assets() {

		// Get our directory
		$css_dir = plugin_dir_url( Easing_Slider::$file ) . 'css';

		// Get file suffix
		$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Register our stylesheet
		wp_register_style( 'easingslider-settings-page', "{$css_dir}/settings-page{$suffix}.css", false, Easing_Slider::$version );

	}

	/**
	 * Enqueues all of our settings styles
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// Enqueue our stylesheet
		wp_enqueue_style( 'easingslider-settings-page' );

	}

	/**
	 * Gets the tabs for our "Settings" page
	 *
	 * @return array
	 */
	public function get_tabs() {

		return apply_filters( 'easingslider_settings_tabs', array(
			'general'  => __( 'General', 'easingslider' ),
			'licenses' => __( 'Licenses', 'easingslider' )
		) );

	}

	/**
	 * Gets the current "Settings" tab
	 *
	 * @return string
	 */
	public function current_tab() {

		return ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

	}

	/**
	 * Gets our form fields
	 *
	 * @return array
	 */
	public function get_form_fields() {

		$data = apply_filters( 'easingslider_pre_get_settings_form_fields', array() );

		// Set the form fields
		if ( isset( $_POST['settings'] ) && is_array( $_POST['settings'] ) ) {
			$data = easingslider_validate_data( array_merge( $data, $_POST['settings'] ) );
		}
		
		return $data;

	}

	/**
	 * Does our actions
	 *
	 * @return void
	 */
	public function do_actions() {

		// Continue if the save button has been pressed
		if ( isset( $_POST['save'] ) ) {

			// Bail if nonce is invalid
			if ( ! check_admin_referer( 'save' ) ) {
				return;
			}

			// Get the current settings
			$settings = get_option( 'easingslider_settings' );
		
			// Merge the updated settings
			$settings = (object) array_merge( (array) $settings, $this->get_form_fields() );

			// Update the settings
			update_option( 'easingslider_settings', $settings );

			// Trigger actions
			do_action( 'easingslider_save_settings_actions' );

			// Tell the user
			easingslider_show_notice( __( 'Settings have been saved successfully.', 'easingslider' ), 'updated' );
		
		}

		// Trigger actions
		do_action( 'easingslider_do_settings_actions' );

	}

	/**
	 * Displays the view
	 *
	 * @return void
	 */
	public function display_view() {

		// Get the current page
		$page = $_GET['page'];

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		// Get the tabs
		$tabs = $this->get_tabs();

		// Get the current tab
		$current_tab = $this->current_tab();

		// Display the view
		require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/edit-settings.php';

	}

}