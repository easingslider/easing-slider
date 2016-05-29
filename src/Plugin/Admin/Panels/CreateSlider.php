<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Plugin\Admin\Panels\EditSlider;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class CreateSlider extends EditSlider
{
	/**
	 * Gets the slider
	 *
	 * @return \EasingSlider\Foundation\Contracts\Models\Model
	 */
	protected function getSlider()
	{
		return $this->sliders->make();
	}
}
