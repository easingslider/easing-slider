<?php

namespace EasingSlider\Foundation\Admin\Panels;

use ArrayAccess;
use EasingSlider\Foundation\Contracts\Admin\Panels\Panels as PanelsContract;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Panels implements PanelsContract, ArrayAccess
{
	/**
	 * Panels
	 *
	 * @var array
	 */
	protected $panels = array();

	/**
	 * Plugin
	 *
	 * @var \EasingSlider\Foundation\Contracts\Plugin
	 */
	protected $plugin;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Plugin $plugin
	 * @return void
	 */
	public function __construct(Plugin $plugin)
	{
		$this->plugin = $plugin;

		$this->defineHooks();

		$this->boot();
	}

	/**
	 * Checks if an offset exists
	 *
	 * @param  mixed $offset
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->panels[$offset]);
	}

	/**
	 * Gets an offset
	 *
	 * @param  mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->panels[$offset];
	}

	/**
	 * Sets a value
	 *
	 * @param  mixed $offset
	 * @param  mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->panels[$offset] = $value;
	}

	/**
	 * Unsets a value
	 *
	 * @param  int $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->panels[$offset]);
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		if (method_exists($this, 'getFooterText')) {
			add_action('admin_footer_text', array($this, 'modifyFooterText'));
		}
	}

	/**
	 * Modifies the plugin footer text with a custom message (optionally).
	 *
	 * @param  string $footerText
	 * @return string
	 */
	public function modifyFooterText($footerText)
	{
		//
	}

	/**
	 * Boot
	 *
	 * @return void
	 */
	abstract protected function boot();
}
