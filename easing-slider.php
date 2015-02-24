<?php

/*
	Plugin Name: Easing Slider
	Plugin URI: http://easingslider.com/
	Version: 2.2.1.1
	Author: Matthew Ruddy
	Author URI: http://matthewruddy.com/
	Description: Easing Slider is an easy to use slider plugin for WordPress. Simple, lightweight & designed to get the job done, it allows you to get creating sliders without any difficulty.
	License: GNU General Public License v2.0 or later
	License URI: http://www.opensource.org/licenses/gpl-license.php

	Copyright 2015 Matthew Ruddy

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Let's go!
if ( class_exists( 'Easing_Slider' ) ) {
	new Easing_Slider();
}

/**
 * Loads and defines the plugin functionality.
 *
 * @author Matthew Ruddy
 */
class Easing_Slider {

	/**
	 * Our plugin version
	 *
	 * @var string
	 */
	public static $version = '2.2.1.1';

	/**
	 * Our plugin file
	 *
	 * @var string
	 */
	public static $file = __FILE__;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		// Activation and uninstall hooks
		register_activation_hook( __FILE__, array( __CLASS__, 'do_activation' ) );
		register_uninstall_hook(  __FILE__, array( __CLASS__, 'do_uninstall' ) );

		// Load dependancies
		$this->load_dependancies();

		// Setup localization
		$this->set_locale();

		// Register post type
		$this->register_post_type();

		// Define hooks
		$this->define_hooks();

		/**
		 * The customizer has been prepared for export as an "extension" in the near future.
		 * To keep our current users happy and prepare them for the changes, we've bootstrapped the customizer
		 * into the core plugin, alongside a notice informing them of these changes.
		 *
		 * Once the changeover is complete, this code will be removed.
		 */
		$this->bootstrap_customizer();

	}

	/**
	 * Activation
	 *
	 * @return void
	 */
	public static function do_activation() {

		global $wp_version;

		// Deactivate the plugin if the WordPress version is below the minimum required.
		if ( version_compare( $wp_version, '4.0', '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( __( sprintf( 'Sorry, but your version of WordPress, <strong>%s</strong>, is not supported. The plugin has been deactivated. <a href="%s">Return to the Dashboard.</a>', $wp_version, admin_url() ), 'easingslider' ) );
			return false;
		}

		// Add options
		add_option( 'easingslider_version', self::$version );
		add_option( 'easingslider_settings', (object) array(
			'image_resizing' => false,
			'load_assets'    => 'header',
			'remove_data'    => false
		) );

		// Let's flush rewrite rules as we're using a custom post type
		flush_rewrite_rules();

		// Trigger hooks
		do_action( 'easingslider_activate' );

	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public static function do_uninstall() {

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		// If enabled, remove the plugin data
		if ( $settings->remove_data ) {

			// Delete all of the sliders
			foreach ( ES_Slider::all() as $slider ) {
				ES_Slider::delete( $slider->ID );
			}

			// Delete options
			delete_option( 'easingslider_version' );
			delete_option( 'easingslider_settings' );

			// Remove data hook
			do_action( 'easingslider_remove_data' );

		}
			
		// Trigger hooks
		do_action( 'easingslider_uninstall' );

	}

	/**
	 * Load dependancies
	 *
	 * @return void
	 */
	protected function load_dependancies() {

		// The file responsible for loading our helpers
		require_once plugin_dir_path( __FILE__ ) . 'includes/helpers.php';

		// The class responsible for defining our admin editor
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-editor-pages.php';

		// The class responsible for discovering extensions
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-extensions-page.php';

		// The class responsible for resizing attachment images
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-image-resizer.php';

		// The class responsible for importing legacy settings
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-legacy.php';

		// The class responsible for adding our plugin toplevel menu
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-menu.php';

		// The class responsible for managing our migrations
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-migrations.php';

		// The class responsible for defining our admin settings
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-settings-page.php';

		// The class responsible for handling plugin shortcodes
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-shortcode.php';

		// The class responsible for managing our public facing functionality
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-slider.php';
		
		// The class responsible for handling our slider list table
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-sliders-list-table.php';

		// The class responsible for extension updates and licensing
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-update-manager.php';

		// The class responsible for displaying our welcome page(s)
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-welcome-pages.php';

		// The class responsible for adding a widget for displaying a slider
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-widget.php';

	}

	/**
	 * Set locale
	 *
	 * @return void
	 */
	protected function set_locale() {

		// Load plugin textdomain
		load_plugin_textdomain( 'easing-slider', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register our "easingslider" post type
	 *
	 * @return void
	 */
	protected function register_post_type() {

		// Register the post type
		register_post_type( 'easingslider', array(
			'query_var'           => false,
			'rewrite'             => false,
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			'show_ui'             => false,
			'labels'              => array(
				'name' => __( 'Sliders', 'easingslider' )
			)
		) );

	}

	/**
	 * Define menu hooks
	 *
	 * @return void
	 */
	protected function define_hooks() {

		// Initiate components
		$editor_pages      = new ES_Editor_Pages();
		$extensions_page   = new ES_Extensions_Page();
		$image_resizer     = new ES_Image_Resizer();
		$legacy            = new ES_Legacy();
		$menu              = new ES_Menu();
		$migrations        = new ES_Migrations();
		$settings_page     = new ES_Settings_Page();
		$shortcode         = new ES_Shortcode();
		$slider            = new ES_Slider();
		$welcome_pages     = new ES_Welcome_Pages();
		$widget            = new ES_Widget();

		/**
		 * Hook everything, "connect all the dots"!
		 *
		 * All of these actions connect the various parts of our plugin together.
		 * The idea behind this is to keep each "component" as separate as possible, decoupled from other components.
		 *
		 * These hooks bridge the gaps.
		 */
		add_action(    'admin_init',                           array( $editor_pages,      'register_assets' ) );
		add_action(    'admin_menu',                           array( $editor_pages,      'add_edit_page' ), 999 );
		add_action(    'admin_menu',                           array( $editor_pages,      'add_publish_page' ), 999 );
		add_action(    'easingslider_activate',                array( $editor_pages,      'add_capabilities' ) );
		add_action(    'easingslider_remove_data',             array( $editor_pages,      'remove_capabilities' ) );
		add_filter(    'set-screen-option',                    array( $editor_pages,      'set_screen_option' ), 10, 3 );

		add_action(    'admin_init',                           array( $extensions_page,   'register_assets' ) );
		add_action(    'admin_menu',                           array( $extensions_page,   'add_submenu_page' ), 99999 );
		add_action(    'easingslider_activate',                array( $extensions_page,   'add_capabilities' ) );
		add_action(    'easingslider_remove_data',             array( $extensions_page,   'remove_capabilities' ) );

		add_action(    'delete_attachment',                    array( $image_resizer,     'delete_resized_attachments' ) );

		add_action(    'init',                                 array( $legacy,            'lite_upgrade_from_200' ), 1 );
		add_action(    'init',                                 array( $legacy,            'lite_upgrade_from_100' ), 2 );
		add_action(    'init',                                 array( $legacy,            'pro_upgrade_from_200' ), 1 );
		add_action(    'init',                                 array( $legacy,            'pro_upgrade_from_100' ), 2 );
		add_action(    'easingslider_remove_data',             array( $legacy,            'remove_options' ) );
		add_action(    'easingslider_delete_slider',           array( $legacy,            'delete_lite_slider' ) );
		add_action(    'easingslider_delete_slider',           array( $legacy,            'delete_pro_slider' ) );
		add_action(    'easingslider_pre_redirect_to_welcome', array( $legacy,            'redirect_to_whats_new' ) );
		add_action(    'easingslider_display_shortcode',       array( $legacy,            'handle_lite_shortcode' ) );
		add_shortcode( 'easingsliderlite',                     array( $legacy,            'do_lite_shortcode' ) );
		add_shortcode( 'easingsliderpro',                      array( $legacy,            'do_pro_shortcode' ) );
		add_shortcode( 'rivasliderpro',                        array( $legacy,            'do_pro_shortcode' ) );

		add_action(    'admin_menu',                           array( $menu,              'add_toplevel_menu' ) );

		add_action(    'init',                                 array( $migrations,        'do_migrations' ) );
		add_action(    'easingslider_update_plugin',           array( $migrations,        'migrate_to_22' ) );
		add_action(    'easingslider_update_plugin',           array( $migrations,        'update_version' ), 999 );

		add_action(    'admin_init',                           array( $settings_page,     'register_assets' ) );
		add_action(    'admin_menu',                           array( $settings_page,     'add_submenu_page' ), 999 );
		add_action(    'easingslider_activate',                array( $settings_page,     'add_capabilities' ) );
		add_action(    'easingslider_remove_data',             array( $settings_page,     'remove_capabilities' ) );

		add_action(    'admin_footer',                         array( $shortcode,         'print_media_thickbox' ) );
		add_action(    'media_buttons',                        array( $shortcode,         'print_media_button' ), 999 );
		add_shortcode( 'easingslider',                         array( $shortcode,         'render' ) );

		add_action(    'init',                                 array( $slider,            'register_assets' ) );
		add_action(    'wp_enqueue_scripts',                   array( $slider,            'enqueue_assets' ) );
		add_filter(    'easingslider_pre_save_slider',         array( $slider,            'no_title' ) );
		add_filter(    'easingslider_pre_display_slider',      array( $slider,            'maybe_randomize' ) );
		add_filter(    'easingslider_get_html_data',           array( $slider,            'cleanup_data' ) );
		add_filter(    'easingslider_before_display_slider',   array( $slider,            'no_script' ), 10, 2 );
		add_filter(    'easingslider_before_slider_content',   array( $slider,            'add_preload' ), 10, 2 );
		add_filter(    'easingslider_display_image_slide',     array( $slider,            'add_image' ), 10, 3 );
		add_filter(    'easingslider_modify_image_url',        array( $slider,            'resize_image' ), 10, 3 );
		add_filter(    'easingslider_before_display_slide',    array( $slider,            'open_link' ), 10, 3 );
		add_filter(    'easingslider_after_display_slide',     array( $slider,            'close_link' ), 10, 3 );

		add_action(    'admin_init',                           array( $welcome_pages,     'register_assets' ) );
		add_action(    'admin_init',                           array( $welcome_pages,     'redirect_to_welcome' ) );
		add_action(    'admin_menu',                           array( $welcome_pages,     'add_dashboard_pages' ) );
		add_action(    'admin_head',                           array( $welcome_pages,     'hide_individual_pages' ) );
		add_action(    'easingslider_activate',                array( $welcome_pages,     'set_redirect_transient' ) );

		add_action(    'widgets_init',                         array( $widget,            'register' ) );

	}

	/**
	 * This method bootstraps the customization functionality, which will soon be exported into an extension.
	 * If the "Visual Customizer" extension is already activated, this method won't do anything.
	 *
	 * This method will be removed in due course. 
	 *
	 * @return void
	 */
	public function bootstrap_customizer() {

		// Bail if the customizer has already been loaded
		if ( ! class_exists( 'ES_Customizer' ) ) {

			// Load the customizer components
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-customizations.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-customizer-legacy.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-customizer-notice.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-es-customizer.php';

			// Initiate components
			$customizations    = new ES_Customizations();
			$customizer_legacy = new ES_Customizer_Legacy();
			$customizer_notice = new ES_Customizer_Notice();
			$customizer        = new ES_Customizer();

			/**
			 * Define hooks
			 */
			add_filter( 'easingslider_metadata_defaults',     array( $customizations,    'merge_defaults' ) );
			add_filter( 'easingslider_get_container_data',    array( $customizations,    'remove_data' ), 10, 2 );
			add_filter( 'easingslider_after_display_slider',  array( $customizations,    'drop_shadow' ), 10, 2 );
			add_filter( 'easingslider_before_display_slider', array( $customizations,    'display_styling' ), 10, 2 );

			add_action( 'init',                               array( $customizer_legacy, 'lite_upgrade_from_200' ), 1 );
			add_action( 'init',                               array( $customizer_legacy, 'lite_upgrade_from_100' ), 2 );
			add_action( 'init',                               array( $customizer_legacy, 'pro_upgrade_from_200' ) );
			add_action( 'easingslider_remove_data',           array( $customizer_legacy, 'remove_options' ) );

			/**
			 * We're not quite ready to display this notice yet.
			 * This code will be uncommented when the customizer is available as an extension.
			 */
			// add_action( 'admin_init',                         array( $customizer_notice, 'handle_dismiss' ) );
			// add_action( 'admin_init',                         array( $customizer_notice, 'display' ), 999 );
			// add_action( 'easingslider_uninstall',             array( $customizer_notice, 'unset_flag' ) );

			add_action( 'admin_init',                         array( $customizer,        'register_assets' ) );
			add_action( 'admin_menu',                         array( $customizer,        'add_submenu_page' ), 9999 );
			add_action( 'easingslider_activate',              array( $customizer,        'add_capabilities' ) );
			add_action( 'easingslider_remove_data',           array( $customizer,        'remove_capabilities' ) );

		}

	}

}