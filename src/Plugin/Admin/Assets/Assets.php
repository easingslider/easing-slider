<?php

namespace EasingSlider\Plugin\Admin\Assets;

use EasingSlider\Foundation\Admin\Assets\Assets as BaseAssets;
use EasingSlider\Foundation\Contracts\Admin\Menus\Menu;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Assets extends BaseAssets
{
	/**
	 * Menu
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Menus\Menu
	 */
	protected $menu;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Menus\Menu $menu
	 * @return void
	 */
	public function __construct(Menu $menu)
	{
		$this->menu = $menu;

		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_action('admin_enqueue_scripts', array($this, 'handleEnqueue'));
	}

	/**
	 * Handles enqueue on appropriate panels
	 *
	 * @param  string $currentHookSuffix
	 * @return void
	 */
	public function handleEnqueue($currentHookSuffix)
	{
		// Only enqueue assets on our admin submenus
		foreach ($this->menu->submenus as $hookSuffix) {
			if ($currentHookSuffix == $hookSuffix) {
				$this->enqueue();
			}
		}
	}

	/**
	 * Enqueues the localizations
	 *
	 * @return void
	 */
	public function enqueueLocalizations()
	{
		$localizations = apply_filters('easingslider_admin_localizations', array(
			'warn'          => __('Are you sure you wish to do this? This cannot be reversed.', 'easingslider'),
			'admin_url'     => parse_url(self_admin_url(), PHP_URL_PATH),
			'plugin_url'    => EASINGSLIDER_PLUGIN_URL,
			'base_url'      => easingslider_get_admin_base_url(),
			'delete_slide'  => __('Are you sure you wish to delete this slide? This cannot be reversed.', 'easingslider'),
			'delete_slides' => __('Are you sure you wish to delete all of this slider\'s images? This cannot be reversed.', 'easingslider'),
			'ftp_error'     => __('Unable to connect via FTP. Please make sure your credentials are correct and try again.', 'easingslider'),
			'media_upload'  => array(
				'title'              => __('Edit Slide', 'easingslider'),
				'back'               => __('Back', 'easingslider'),
				'update'             => __('Update', 'easingslider'),
				'replace'            => __('Replace', 'easingslider'),
				'replace_image'      => __('Replace Image', 'easingslider'),
				'image_from_media'   => __('Image from Media', 'easingslider'),
				'insert_into_slider' => __('Insert into Slider', 'easingslider')
			),
			'buttons' => array(
				'activate'     => __('Activate Addon', 'easingslider'),
				'deactivate'   => __('Deactivate Addon', 'easingslider'),
				'activating'   => __('Activating...', 'easingslider'),
				'deactivating' => __('Deactivating...', 'easingslider'),
				'installing'   => __('Installing...', 'easingslider')
			),
			'messages' => array(
				'active'   => __('Status: Active', 'easingslider'),
				'inactive' => __('Status: Inactive', 'easingslider')
			),
			'nonces' => array(
				'activate'   => wp_create_nonce('easingslider-activate-addon'),
				'deactivate' => wp_create_nonce('easingslider-deactivate-addon'),
				'install'    => wp_create_nonce('easingslider-install-addon')
			)
		));

		// Load localizations
		wp_localize_script('easingslider-admin', '_easingsliderAdminL10n', $localizations);
	}

	/**
	 * Enqueues the Backbone.js templates
	 *
	 * @return void
	 */
	public function enqueueBackboneTemplates()
	{
		require EASINGSLIDER_RESOURCES_DIR . 'backbone/tmpl-edit-slide-frame.php';
		require EASINGSLIDER_RESOURCES_DIR . 'backbone/tmpl-edit-slide.php';
		require EASINGSLIDER_RESOURCES_DIR . 'backbone/tmpl-slide.php';

		// Trigger hooks
		do_action('easingslider_admin_backbone_templates');
	}

	/**
	 * Enqueues the assets
	 *
	 * @return void
	 */
	public function enqueue()
	{
		$suffix = $this->getSuffix();

		// Enqueue WordPress Media
		wp_enqueue_media();

		// Load assets
		wp_enqueue_style('easingslider-admin', EASINGSLIDER_ASSETS_URL . "css/admin{$suffix}.css", false, EASINGSLIDER_VERSION);
		wp_enqueue_script('easingslider-admin', EASINGSLIDER_ASSETS_URL . "js/admin{$suffix}.js", array('jquery', 'jquery-ui-sortable', 'backbone', 'media-grid'), EASINGSLIDER_VERSION);

		// Load localizations
		$this->enqueueLocalizations();

		// Load backbone.js templates
		add_action('admin_footer', array($this, 'enqueueBackboneTemplates'));

		// Trigger hooks
		do_action('easingslider_admin_enqueue_assets', $suffix);
	}
}
