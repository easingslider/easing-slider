<?php

namespace EasingSlider\Foundation\Admin\Panels;

use EasingSlider\Foundation\Contracts\Admin\Panels\Panel as PanelContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Panel implements PanelContract
{
	/**
	 * Gets a parameter from the URL
	 *
	 * @param  string $param
	 * @return string|false
	 */
	protected function getUrlParam($param)
	{
		if ( ! empty($_GET[$param])) {
			return $_GET[$param];
		}

		return false;
	}

	/**
	 * Gets the page slug
	 *
	 * @return string|false
	 */
	protected function getPage()
	{
		return $this->getUrlParam('page');
	}

	/**
	 * Gets the page ID
	 *
	 * @return string|false
	 */
	protected function getID()
	{
		return $this->getUrlParam('edit');
	}

	/**
	 * Shows a view
	 *
	 * @param  string $view
	 * @param  array  $data
	 * @return void
	 */
	protected function showView($view, $data = array())
	{
		extract($data);

		require EASINGSLIDER_RESOURCES_DIR .'views/'. $view .'.php';
	}

	/**
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display()
	{
		//
	}
}
