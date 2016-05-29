<?php

namespace EasingSlider\Foundation\Admin;

use EasingSlider\Foundation\Contracts\Admin\Admin as AdminContract;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Admin implements AdminContract
{
	/**
	 * Plugin
	 *
	 * @var \EasingSlider\Foundation\Contracts\Plugin
	 */
	protected $plugin;

	/**
	 * Admin Prefix
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Services
	 *
	 * @var array
	 */
	protected $services = array();

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Plugin $plugin
	 * @return void
	 */
	public function __construct(Plugin $plugin)
	{
		$this->plugin = $plugin;

		$this->bindAliases();

		$this->bindSingletons();

		$this->boot();
	}

	/**
	 * __call function.
	 *
	 * @param  string $name
	 * @param  array  $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{
		if (isset($this->$name)) {
			return $this->$name;
		}
	}

	/**
	 * Bind aliases
	 *
	 * @return void
	 */
	protected function bindAliases()
	{
		foreach ($this->aliases as $contract => $class) {
			$this->plugin->alias($contract, $class);
		}
	}

	/**
	 * Bind singletons
	 *
	 * @return void
	 */
	protected function bindSingletons()
	{
		foreach ($this->singletons as $singleton) {
			$this->plugin->share($singleton);
		}
	}

	/**
	 * Boot
	 *
	 * @return void
	 */
	abstract protected function boot();
}
