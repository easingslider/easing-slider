<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\Panel;
use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Foundation\Contracts\Shortcodes\Shortcode;
use EasingSlider\Plugin\Admin\ListTables\Sliders as SlidersListTable;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class ListSliders extends Panel
{
	/**
	 * Shortcode
	 *
	 * @var \EasingSlider\Foundation\Contracts\Shortcodes\Shortcode
	 */
	protected $shortcode;

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @param  \EasingSlider\Foundation\Contracts\Shortcodes\Shortcode    $shortcode
	 * @return void
	 */
	public function __construct(Repository $sliders, Shortcode $shortcode)
	{
		$this->sliders = $sliders;
		$this->shortcode = $shortcode;
	}

	/**
	 * Gets the page slug
	 *
	 * @return string|false
	 */
	protected function getPage()
	{
		if ( ! empty($_GET['page'])) {
			return $_GET['page'];
		}

		return false;
	}

	/**
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display()
	{
		$listTable = new SlidersListTable(
			$this->sliders,
			$this->shortcode
		);

		$this->showView('list-sliders', array(
			'listTable' => $listTable,
			'page'      => $this->getPage()
		));
	}
}
