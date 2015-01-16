<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines our "Welcome" page functionality
 *
 * @uses   Easing_Slider
 * @author Matthew Ruddy
 */
class ES_Welcome_Pages {

	/**
	 * Adds our "Welcome" page(s) to the dashboard
	 *
	 * @return void
	 */
	public function add_dashboard_pages() {

		// "About" page
		$hooks[] = add_dashboard_page(
			__( 'Welcome to Easing Slider', 'easingslider' ),
			__( 'Welcome to Easing Slider', 'easingslider' ),
			'manage_options',
			'easingslider-about',
			array( $this, 'display_view' )
		);

		// "Getting Started" page
		$hooks[] = add_dashboard_page(
			__( 'Getting started with Easing Slider', 'easingslider' ),
			__( 'Getting started with Easing Slider', 'easingslider' ),
			'manage_options',
			'easingslider-getting-started',
			array( $this, 'display_view' )
		);

		// "Credits" page
		$hooks[] = add_dashboard_page(
			__( 'The people that build Easing Slider', 'easingslider' ),
			__( 'The people that build Easing Slider', 'easingslider' ),
			'manage_options',
			'easingslider-credits',
			array( $this, 'display_view' )
		);

		// Page-specific hooks
		foreach ( $hooks as $hook ) {
			add_action( "admin_print_styles-{$hook}", array( $this, 'enqueue_styles' ) );
		}

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
		wp_register_style( 'easingslider-welcome-pages', "{$css_dir}/welcome-pages{$suffix}.css", false, Easing_Slider::$version );

	}

	/**
	 * Enqueues all of our extensions page styles
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// Enqueue our stylesheet
		wp_enqueue_style( 'easingslider-welcome-pages' );

	}

	/**
	 * Hides the individual Dashboard pages
	 *
	 * @return void
	 */
	public function hide_individual_pages() {

		// Remove the subpages
		remove_submenu_page( 'index.php', 'easingslider-about' );
		remove_submenu_page( 'index.php', 'easingslider-getting-started' );
		remove_submenu_page( 'index.php', 'easingslider-credits' );

	}

	/**
	 * Gets the tabs for our "Welcome" page
	 *
	 * @return array
	 */
	protected function get_tabs() {

		return apply_filters( 'easingslider_welcome_tabs', array(
			'about'           => array( 'title' => __( 'What\'s New', 'easingslider' ),     'slug' => 'about' ),
			'getting-started' => array( 'title' => __( 'Getting Started', 'easingslider' ), 'slug' => 'getting-started' ),
			'credits'         => array( 'title' => __( 'Credits', 'easingslider' ),         'slug' => 'credits' )
		) );

	}

	/**
	 * Sets our redirect transient, used to tell us to redirect to the "Welcome" page(s)
	 *
	 * @return void
	 */
	public function set_redirect_transient() {

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		set_transient( '_easingslider_welcome_redirect', true, 30 );

	}

	/**
	 * Redirects the user to the welcome page if welcome transient exits
	 *
	 * @return void
	 */
	public function redirect_to_welcome() {

		// Bail if we have no transient
		if ( ! get_transient( '_easingslider_welcome_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_easingslider_welcome_redirect' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Get the previous plugin version from database
		$version = get_option( 'easingslider_version' );

		// Trigger hooks
		do_action( 'easingslider_pre_redirect_to_welcome', $version );

		// Redirect the user appropriately
		if ( version_compare( $version, Easing_Slider::$version, '=' ) ) {

			// This is a first time install. Redirect to "Getting Started".
			wp_safe_redirect( admin_url( 'index.php?page=easingslider-getting-started' ) );
			exit;

		}
		else {

			// The plugin has just been updated. Redirect to "About".
			wp_safe_redirect( admin_url( 'index.php?page=easingslider-about' ) );
			exit;

		}

	}

	/**
	 * Displays the view
	 *
	 * @return void
	 */
	public function display_view() {

		// Get the current page
		$page = $_GET['page'];

		// Get the settings tabs
		$tabs = $this->get_tabs();

		// Get the current tab
		$current_tab = str_replace( 'easingslider-', '', $page );

		// Get the plugin version
		$version = Easing_Slider::$version;

		// Get the images directory
		$image_dir = plugins_url( plugin_basename( dirname( __DIR__ ) ) ) . '/images';

		// Display the view
		require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/welcome.php';
		
	}

}