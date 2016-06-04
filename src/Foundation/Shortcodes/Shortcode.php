<?php

namespace EasingSlider\Foundation\Shortcodes;

use EasingSlider\Foundation\Contracts\Shortcodes\Shortcode as ShortcodeContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Shortcode implements ShortcodeContract
{
	/**
	 * Tag
	 *
	 * @var string
	 */
	protected $tag;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_shortcode($this->tag(), array($this, 'render'));
	}

	/**
	 * Returns the shortcode tag
	 *
	 * @return string
	 */
	public function tag()
	{	
		return $this->tag;
	}

	/**
	 * Renders the shortcode
	 *
	 * @param  array $atts
	 * @return string
	 */
	public function render($atts = array())
	{
		//
	}
}
