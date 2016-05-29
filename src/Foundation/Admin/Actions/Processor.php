<?php

namespace EasingSlider\Foundation\Admin\Actions;

use EasingSlider\Foundation\Admin\Admin;
use EasingSlider\Foundation\Contracts\Admin\Actions\Processor as ProcessorContract;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Processor implements ProcessorContract
{
	/**
	 * Actions
	 *
	 * @var array
	 */
	protected $actions = array();

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
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_action('admin_init', array($this, 'process'));
	}

	/**
	 * Processes an action
	 *
	 * @return void
	 */
	public function process()
	{
		if (isset($_POST['easingslider_action'])) {
			do_action('easingslider_'. $_POST['easingslider_action'], $_POST);
		}
		elseif (isset($_GET['easingslider_action'])) {
			do_action('easingslider_'. $_GET['easingslider_action'], $_GET);
		}
	}

	/**
	 * Checks if an offset exists
	 *
	 * @param  mixed $offset
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->actions[$offset]);
	}

	/**
	 * Gets an offset
	 *
	 * @param  mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->actions[$offset];
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
		$this->actions[$offset] = $value;
	}

	/**
	 * Unsets a value
	 *
	 * @param  int $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->actions[$offset]);
	}

	/**
	 * Boot
	 *
	 * @return void
	 */
	abstract protected function boot();
}
