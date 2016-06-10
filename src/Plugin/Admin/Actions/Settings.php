<?php

namespace EasingSlider\Plugin\Admin\Actions;

use EasingSlider\Foundation\Admin\Actions\Actions;
use EasingSlider\Foundation\Contracts\Activation\Activator;
use EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler;
use EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller;
use EasingSlider\Plugin\Admin\Validators\Settings as SettingsValidator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Settings extends Actions
{
	/**
	 * Activator
	 *
	 * @var \EasingSlider\Foundation\Contracts\Activation\Activator
	 */
	protected $activator;

	/**
	 * Uninstaller
	 *
	 * @var \EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller
	 */
	protected $uninstaller;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler $notices
	 * @param  \EasingSlider\Foundation\Contracts\Activation\Activator        $activator
	 * @param  \EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller  $uninstaller
	 * @return void
	 */
	public function __construct(NoticeHandler $notices, Activator $activator, Uninstaller $uninstaller)
	{
		$this->activator = $activator;
		$this->uninstaller = $uninstaller;

		parent::__construct($notices);
	}

	/**
	 * Defines our actions
	 *
	 * @return void
	 */
	protected function defineActions()
	{
		add_action('easingslider_reset_plugin', array($this, 'resetPlugin'));
	}

	/**
	 * Gets a new validator instance
	 *
	 * @return array
	 */
	protected function validator()
	{
		return new SettingsValidator();
	}

	/**
	 * Resets the plugin by doing an uninstall then running an activation sequence
	 *
	 * @return void
	 */
	public function resetPlugin()
	{
		// Run an uninstallation
		$this->uninstaller->uninstall();

		// Run an activation
		$this->activator->activate();

		// Get the redirect URL
		$redirectUrl = admin_url('admin.php?page=easingslider-settings&easingslider_notice=reset_plugin');

		// Redirect back to settings page to avoid resetting the plugin again if the user refreshes the page.
		wp_safe_redirect($redirectUrl);
		exit();
	}
}
