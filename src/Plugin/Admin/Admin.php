<?php

namespace EasingSlider\Plugin\Admin;

use EasingSlider\Foundation\Admin\Admin as BaseAdmin;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Admin extends BaseAdmin
{
	/**
	 * Admin Prefix
	 *
	 * @var string
	 */
	protected $prefix = 'easingslider';

	/**
	 * Actions
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Actions\Processor
	 */
	protected $actions;

	/**
	 * Ajax
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Ajax\Router
	 */
	protected $ajax;

	/**
	 * Assets
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Assets\Assets
	 */
	protected $assets;

	/**
	 * License Handler
	 *
	 * @var \EasingSlider\Plugin\Contracts\Admin\LicenseHandler
	 */
	protected $licenseHandler;

	/**
	 * Media Button
	 *
	 * @var \EasingSlider\Plugin\Admin\MediaButton
	 */
	protected $mediaButton;

	/**
	 * Menu
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Menus\Menu
	 */
	protected $menu;

	/**
	 * Notices
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler
	 */
	protected $notices;

	/**
	 * Upgrader
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrader
	 */
	protected $upgrader;

	/**
	 * Aliases
	 *
	 * @var array
	 */
	protected $aliases = array(
		'\EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler'          => '\EasingSlider\Foundation\Admin\Notices\NoticeHandler',
		'\EasingSlider\Foundation\Contracts\Admin\Actions\Processor'              => '\EasingSlider\Plugin\Admin\Actions\Processor',
		'\EasingSlider\Foundation\Contracts\Admin\Admin'                          => '\EasingSlider\Plugin\Admin\Admin',
		'\EasingSlider\Foundation\Contracts\Admin\Ajax\Router'                    => '\EasingSlider\Plugin\Admin\Ajax\Router',
		'\EasingSlider\Foundation\Contracts\Admin\Assets\Assets'                  => '\EasingSlider\Plugin\Admin\Assets\Assets',
		'\EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler' => '\EasingSlider\Plugin\Admin\LicenseHandler',
		'\EasingSlider\Foundation\Contracts\Admin\Menus\Menu'                     => '\EasingSlider\Plugin\Admin\Menus\Menu',
		'\EasingSlider\Foundation\Contracts\Admin\Panels\Panels'                  => '\EasingSlider\Plugin\Admin\Panels\Panels',
		'\EasingSlider\Foundation\Contracts\Admin\PluginUpdaters\PluginUpdater'   => '\EasingSlider\Plugin\Admin\AddonUpdater',
		'\EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrader'              => '\EasingSlider\Plugin\Admin\Upgrades\Upgrader',
		'\EasingSlider\Plugin\Contracts\Admin\Validators\License'                 => '\EasingSlider\Plugin\Admin\Validators\License',
		'\EasingSlider\Plugin\Contracts\Admin\Validators\Settings'                => '\EasingSlider\Plugin\Admin\Validators\Settings',
		'\EasingSlider\Plugin\Contracts\Admin\Validators\Slider'                  => '\EasingSlider\Plugin\Admin\Validators\Slider'
	);

	/**
	 * Singletons
	 *
	 * @var array
	 */
	protected $singletons = array(
		'\EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler',
		'\EasingSlider\Foundation\Contracts\Admin\Actions\Processor',
		'\EasingSlider\Foundation\Contracts\Admin\Ajax\Router',
		'\EasingSlider\Foundation\Contracts\Admin\Assets\Assets',
		'\EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler',
		'\EasingSlider\Foundation\Contracts\Admin\Menus\Menu',
		'\EasingSlider\Foundation\Contracts\Admin\Panels\Panels',
		'\EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrader'
	);

	/**
	 * Boot
	 *
	 * @return void
	 */
	protected function boot()
	{
		$this->actions        = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\Actions\Processor');
		$this->ajax           = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\Ajax\Router');
		$this->assets         = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\Assets\Assets');
		$this->licenseHandler = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler');
		$this->mediaButton    = $this->plugin->make('\EasingSlider\Plugin\Admin\MediaButton');
		$this->menu           = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\Menus\Menu');
		$this->notices        = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler');
		$this->upgrader       = $this->plugin->make('\EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrader');
	}
}
