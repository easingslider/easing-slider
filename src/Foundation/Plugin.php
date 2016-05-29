<?php

namespace EasingSlider\Foundation;

use Auryn\Injector as Container;
use EasingSlider\Foundation\Contracts\Plugin as PluginContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Plugin extends Container implements PluginContract
{
	/**
	 * The one true plugin instance
	 *
	 * @var \EasingSlider\Foundation\Plugin
	 */
	private static $instance;

	/**
	 * Aliases
	 *
	 * @var array
	 */
	protected $aliases = array();

	/**
	 * Singletons
	 *
	 * @var array
	 */
	protected $singletons = array();

	/**
	 * Main plugin instance
	 *
	 * Insures that only one instance of our plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @return \EasingSlider\Foundation\Plugin
	 */
	public final static function instance()
	{
		// Create instance if not already created
		if ( ! isset(self::$instance) && ! (self::$instance instanceof static)) {
			self::$instance = new static();
			self::$instance->bindAliases();
			self::$instance->bindSingletons();
			self::$instance->boot();
		}

		return self::$instance;
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
			$this->alias($contract, $class);
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
			$this->share($singleton);
		}

		$this->share($this);
	}

	/**
	 * Boots the plugin
	 *
	 * @return void
	 */
	abstract protected function boot();
}
