<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\Panel;
use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Foundation\Contracts\Shortcodes\Shortcode;
use EasingSlider\Plugin\Admin\Panels\EditSlider as EditSliderPanel;
use EasingSlider\Plugin\Admin\Panels\ListSliders as ListSlidersPanel;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class AllSliders extends Panel
{
	/**
	 * Shortcode
	 *
	 * @var \EasingSlider\Foundation\Contracts\Shortcodes\Shortcode
	 */
	protected $shortcode;

	/**
	 * List table
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
	 * Gets the appropriate page
	 *
	 * @return \EasingSlider\Panels\PanelContract
	 */
	protected function determinePanel()
	{
		$id = $this->getID();

		// Determine the appropriate 
		if ($id) {
			return new EditSliderPanel(
				$this->sliders
			);
		} else {
			return new ListSlidersPanel(
				$this->sliders,
				$this->shortcode
			);
		}
	}

	/**
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display()
	{
		$panel = $this->determinePanel();

		return $panel->display();
	}
}
