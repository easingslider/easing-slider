<?php

namespace EasingSlider\Plugin\Activation;

use EasingSlider\Foundation\Activation\Activator as BaseActivator;
use EasingSlider\Foundation\Contracts\Capabilities\Capabilities;
use EasingSlider\Foundation\Contracts\Repositories\Repository;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Activator extends BaseActivator
{
	/**
	 * Capabilities
	 *
	 * @var \EasingSlider\Foundation\Contracts\Capabilities\Capabilities
	 */
	protected $capabilities;

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Capabilities\Capabilities $capabilities
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository   $sliders
	 * @return void
	 */
	public function __construct(Capabilities $capabilities, Repository $sliders)
	{
		$this->capabilities = $capabilities;
		$this->sliders = $sliders;
	}

	/**
	 * Creates our capabilities
	 *
	 * @return void
	 */
	protected function createCapabilities()
	{
		$this->capabilities->addToRoles();
	}

	/**
	 * Recreate our rewrite rules to avoid issues with any custom post types.
	 *
	 * To ensure rules are correctly flushed, we have to register our custom post type(s) here.
	 * This is because the `init` hook has fired before the plugin was activated internally.
	 * 
	 * Don't like this approach. I'd prefer to have the plugin make no assumptions
	 * about the repository, but this is the simplest solution.
	 *
	 * Ensure that you don't get caught out in future here if changing a repository from
	 * a post type to a custom database table.
	 *
	 * @return void
	 */
	protected function flushRewriteRules()
	{
		$this->sliders->registerPostType();

		flush_rewrite_rules();
	}

	/**
	 * Executes activation
	 *
	 * @return void
	 */
	public function activate()
	{
		$this->createCapabilities();

		$this->flushRewriteRules();
	}
}
