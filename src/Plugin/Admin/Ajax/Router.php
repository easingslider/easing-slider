<?php

namespace EasingSlider\Plugin\Admin\Ajax;

use EasingSlider\Foundation\Admin\Ajax\Router as BaseRouter;
use EasingSlider\Plugin\Admin\Ajax\AddonActivator;
use EasingSlider\Plugin\Admin\Ajax\AddonDeactivator;
use EasingSlider\Plugin\Admin\Ajax\AddonInstaller;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Router extends BaseRouter
{
	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		$this->setAction('easingslider_activate_addon', array($this, 'activateAddon'));
		$this->setAction('easingslider_deactivate_addon', array($this, 'deactivateAddon'));
		$this->setAction('easingslider_install_addon', array($this, 'installAddon'));
	}

	/**
	 * Activates an addon
	 *
	 * @return void
	 */
	public function activateAddon()
	{
		$activator = new AddonActivator();

		$response = $activator->activate();

		if (false === $response) {
			$this->response(true);
		} else {
			$this->response($response);
		}
	}

	/**
	 * Deactivates an addon
	 *
	 * @return void
	 */
	public function deactivateAddon()
	{
		$deactivator = new AddonDeactivator();

		$response = $deactivator->deactivate();

		if (false === $response) {
			$this->response(true);
		} else {
			$this->response($response);
		}
	}

	/**
	 * Installs an addon
	 *
	 * @return void
	 */
	public function installAddon()
	{
		$installer = new AddonInstaller();

		$response = $installer->install();

		if (false === $response) {
			$this->response(true);
		} else {
			$this->response($response);
		}
	}
}
