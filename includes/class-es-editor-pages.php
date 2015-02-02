<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines our admin slider editor pages.
 *
 * @uses   ES_Slider
 * @uses   ES_Sliders_List_Table
 * @uses   Easing_Slider
 * @author Matthew Ruddy
 */
class ES_Editor_Pages {

	/**
	 * Adds our editor user capabilities
	 *
	 * @return void
	 */
	public function add_capabilities() {

		// Get user role
		$role = get_role( 'administrator' );

		// Add the capabilities
		$role->add_cap( 'easingslider_edit_sliders' );
		$role->add_cap( 'easingslider_publish_sliders' );

	}

	/**
	 * Removes our editor user capabilities
	 *
	 * @return void
	 */
	public function remove_capabilities() {

		// Get user role
		$role = get_role( 'administrator' );

		// Remove the capabilities
		$role->remove_cap( 'easingslider_edit_sliders' );
		$role->remove_cap( 'easingslider_publish_sliders' );

	}

	/**
	 * Adds our "All Sliders" page to the plugin admin menu
	 *
	 * @return void
	 */
	public function add_edit_page() {

		// "All Sliders" page
		$hook = add_submenu_page(
			'easingslider_edit_sliders',
			__( 'Sliders', 'easingslider' ),
			__( 'All Sliders', 'easingslider' ),
			'easingslider_edit_sliders',
			'easingslider_edit_sliders',
			array( $this, 'display_edit_view' )
		);

		// Page-specific hooks
		add_action( "load-{$hook}",                array( $this, 'do_edit_actions' ) );
	 	add_action( "load-{$hook}",                array( $this, 'add_screen_options' ) );
		add_action( "load-{$hook}",                array( $this, 'hide_media_tabs' ) );
		add_action( "admin_print_styles-{$hook}",  array( $this, 'enqueue_styles' ) );
		add_action( "admin_print_scripts-{$hook}", array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Adds our "Add New" page to the plugin admin menu
	 *
	 * @return void
	 */
	public function add_publish_page() {

		// "Add New" page
		$hook = add_submenu_page(
			'easingslider_edit_sliders',
			__( 'Add New Slider', 'easingslider' ),
			__( 'Add New', 'easingslider' ),
			'easingslider_publish_sliders',
			'easingslider_publish_slider',
			array( $this, 'display_publish_view' )
		);

		// Page-specific hooks
		add_action( "load-{$hook}",                array( $this, 'do_publish_actions' ) );
		add_action( "load-{$hook}",                array( $this, 'add_screen_options' ) );
		add_action( "load-{$hook}",                array( $this, 'hide_media_tabs' ) );
		add_action( "admin_print_styles-{$hook}",  array( $this, 'enqueue_styles' ) );
		add_action( "admin_print_scripts-{$hook}", array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Registers all of our editor assets
	 *
	 * @return void
	 */
	public function register_assets() {

		// Get our directories
		$css_dir = plugin_dir_url( Easing_Slider::$file ) . 'css';
		$js_dir  = plugin_dir_url( Easing_Slider::$file ) . 'js';

		// Get file suffix
		$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Dependancies
		$dependancies = array( 'jquery', 'jquery-ui-sortable', 'backbone', 'media-grid' );

		// Register our stylesheets
		wp_register_style( 'easingslider-editor-pages', "{$css_dir}/editor-pages{$suffix}.css", false, Easing_Slider::$version );

		// Register our javascripts
		wp_register_script( 'easingslider-editor-pages', "{$js_dir}/editor-pages{$suffix}.js", $dependancies, Easing_Slider::$version, true );

	}

	/**
	 * Enqueues all of our editor styles
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// Enqueue our stylesheets
		wp_enqueue_style( 'easingslider-editor-pages' );

	}

	/**
	 * Enqueues all of our editor scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// Load media grid localizations
		wp_localize_script( 'media-grid', '_wpMediaGridSettings', array(
			'adminUrl' => parse_url( self_admin_url(), PHP_URL_PATH ),
		) );

		// Load our localizations
		wp_localize_script( 'easingslider-editor-pages', '_easingsliderEditorL10n', array(
			'warn'          => __( 'Are you sure you wish to do this? This cannot be reversed.', 'easingslider' ),
			'plugin_url'    => '/wp-content/plugins/'. dirname( plugin_basename( __FILE__ ) ) .'/',
			'delete_slide'  => __( 'Are you sure you wish to delete this slide? This cannot be reversed.', 'easingslider' ),
			'delete_slides' => __( 'Are you sure you wish to delete all of this slider\'s images? This cannot be reversed.', 'easingslider' ),
			'media_upload'  => array(
				'title'              => __( 'Edit Slide', 'easingslider' ),
				'update'             => __( 'Update', 'easingslider' ),
				'image_from_media'   => __( 'Image from Media', 'easingslider' ),
				'insert_into_slider' => __( 'Insert into Slider', 'easingslider' )
			)
		) );

		// Enqueue our javascripts
		wp_enqueue_media();
		wp_enqueue_script( 'easingslider-editor-pages' );

		// Print our backbone templates
		add_action( 'admin_footer', array( $this, 'print_backbone_templates' ) );

	}

	/**
	 * Adds our editor screen options
	 *
	 * @return void
	 */
	public function add_screen_options() {

		// Show the appropriate screen options
		if ( ! isset( $_GET['edit'] ) ) {

			/**
			 * WP_List_Table automatically adds the table columns to our screen options when initialised,
			 * so let's trigger this by creating a new instance of our class.
			 */
			$list_table = new ES_Sliders_List_Table();

			// Add the sliders per page screen option
			add_screen_option( 'per_page', array(
				'label'   => __( 'Sliders per page', 'easingslider' ),
				'default' => 20,
				'option'  => 'sliders_per_page'
			) );

		}

	}

	/**
	 * Sets our editor screen option
	 *
	 * @param  mixed $status
	 * @param  mixed $option The screen option
	 * @param  mixed $value  The screen option value
	 * @return mixed
	 */
	public function set_screen_option( $status, $option, $value ) {

		if ( 'sliders_per_page' == $option ) {
			return $value;
		}

	}

	/**
	 * Hides any Media Upload (possibly from other plugins) that we don't want to show
	 *
	 * @return void
	 */
	public function hide_media_tabs() {

		add_filter( 'media_upload_tabs', '__return_empty_array', 99999 );

	}

	/**
	 * Prints our backbone.js templates
	 *
	 * @return void
	 */
	public function print_backbone_templates() {

		// Require our templates
		require plugin_dir_path( dirname( __FILE__ ) ) . 'backbone/edit-slide.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'backbone/slide.php';

	}

	/**
	 * Gets our form fields
	 *
	 * @return array
	 */
	public function get_form_fields() {

		$data = apply_filters( 'easingslider_pre_get_editor_form_fields', array() );

		// Set the form fields
		if ( isset( $_POST['post_title'] ) ) {
			$data['post_title'] = $_POST['post_title'];
		}
		if ( isset( $_POST['general'] ) )  {
			$data['general'] = (object) easingslider_validate_data( $_POST['general'] );
		}
		if ( isset( $_POST['dimensions'] ) )  {
			$data['dimensions'] = (object) easingslider_validate_data( $_POST['dimensions'] );
		}
		if ( isset( $_POST['transitions'] ) )  {
			$data['transitions'] = (object) easingslider_validate_data( $_POST['transitions'] );
		}
		if ( isset( $_POST['navigation'] ) )  {
			$data['navigation'] = (object) easingslider_validate_data( $_POST['navigation'] );
		}
		if ( isset( $_POST['playback'] ) )  {
			$data['playback'] = (object) easingslider_validate_data( $_POST['playback'] );
		}
		if ( isset( $_POST['slides'] ) )  {
			$data['slides'] = array_map( 'easingslider_validate_data', $_POST['slides'] );

			$data['slides'] = array_map( 'json_decode', $data['slides'] );
		}
		else {
			$data['slides'] = array();
		}

		return $data;

	}

	/**
	 * Does our edit actions
	 *
	 * @return void
	 */
	public function do_edit_actions() {

		// Continue if the save button has been pressed
		if ( isset( $_POST['save'] ) && isset( $_GET['edit'] ) ) {

			// Bail if nonce is invalid
			if ( ! check_admin_referer( 'save' ) ) {
				return;
			}

			// Get and validate the ID, protecting against XSS attacks
			$id = esc_attr( $_GET['edit'] );

			// Get our slider
			$slider = ES_Slider::find( $id );

			// Update attributes
			$slider->set( $this->get_form_fields() );

			// Save the slider
			$slider->save();

			// Trigger actions
			do_action( 'easingslider_save_slider_actions', $slider );

			// Tell the user
			easingslider_show_notice( __( 'Slider has been saved successfully.', 'easingslider' ), 'updated' );

		}

		// Trigger actions
		do_action( 'easingslider_do_editor_actions' );

	}

	/**
	 * Does our publish actions
	 *
	 * @return void
	 */
	public function do_publish_actions() {

		// Continue if the save button has been pressed
		if ( isset( $_POST['save'] ) ) {

			// Bail if nonce is invalid
			if ( ! check_admin_referer( 'save' ) ) {
				return;
			}

			// Create a new slider
			$slider = ES_Slider::create();

			// Set attributes
			$slider->set( $this->get_form_fields() );

			// Save the slider
			$slider->save();

			// Trigger actions
			do_action( 'easingslider_publish_slider_actions', $slider );

			// Redirect to the editor for our new slider
			wp_redirect( "admin.php?page=easingslider_edit_sliders&edit={$slider->ID}" );

		}

		// Trigger actions
		do_action( 'easingslider_do_editor_actions' );

	}

	/**
	 * Displays the edit view
	 *
	 * @return void
	 */
	public function display_edit_view() {

		// Get the page
		$page = $_GET['page'];

		// Show the appropriate view
		if ( isset( $_GET['edit'] ) ) {

			// Get and validate the ID, protecting against XSS attacks
			$id = esc_attr( $_GET['edit'] );

			// Get the slider by its ID
			$slider = ES_Slider::find( $id );

			// Display the editor
			require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/edit-slider.php';

		}
		else {

			// Get the sliders list table
			$list_table = new ES_Sliders_List_Table();

			// Display the list
			require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/list-sliders.php';

		}

	}

	/**
	 * Displays the publish view
	 *
	 * @return void
	 */
	public function display_publish_view() {

		// Get the current page
		$page = $_GET['page'];

		// Initiate a new slider
		$slider = ES_Slider::create();

		// Display the view
		require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/edit-slider.php';

	}

}