<?php

namespace EasingSlider\Foundation\Widgets;

use EasingSlider\Foundation\Contracts\Widgets\Widget as WidgetContract;
use WP_Widget;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Widget extends WP_Widget implements WidgetContract
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Boot, if method exists. Use this to set dependencies, as WordPress doesn't allow us to provide widget dependencies.
		if (method_exists($this, 'boot')) {
			$this->boot();
		}

		// Call parent constructor
		parent::__construct(
			$this->getName(),
			$this->getTitle(),
			array('description' => $this->getDescription())
		);

		// Register the widget
		$this->register();
	}

	/**
	 * Registers the widget
	 *
	 * @return void
	 */
	public function register()
	{
		$className = get_called_class();

		add_action('widgets_init', function () use ($className) {
			register_widget($className);
		});
	}

	/**
	 * Widget logic
	 *
	 * @param  array $args
	 * @param  array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{
		// Extract arguments
		extract($args);

		// Before widget
		echo $before_widget;

		// Display title
		if ( ! empty($instance['title'])) {
			echo $before_title . apply_filters('widgets_title', $instance['title']) . $after_title;
		}

		// Display the widget content
		$this->display($instance);

		// After widget
		echo $after_widget;
	}

	/**
	 * Gets the widget name
	 *
	 * @return string
	 */
	abstract protected function getName();

	/**
	 * Gets the widget title
	 *
	 * @return string
	 */
	abstract protected function getTitle();

	/**
	 * Gets the widget description
	 *
	 * @return string
	 */
	abstract protected function getDescription();

	/**
	 * Displays the widget content
	 *
	 * @param  array $instance
	 * @return void
	 */
	public function display($instance)
	{
		//
	}
}
