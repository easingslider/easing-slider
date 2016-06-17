<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Plugin\Admin\Upgrades\UpgradeTo300;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgradeTo306 extends UpgradeTo300
{
	/**
	 * The version we're upgrading from (or greater)
	 *
	 * @var string
	 */
	protected $upgradeFrom = '3.0.0';

	/**
	 * The version we're upgrading too
	 *
	 * @var string
	 */
	protected $upgradeTo = '3.0.6';

	/**
	 * Checks if a slider is missing data
	 *
	 * @param  int $id
	 * @return boolean
	 */
	protected function isMissingData($id)
	{
		// Check for post meta
		$metadata = get_post_meta($id, '_easingslider', true);

		return ( ! $metadata) ? true : false;
	}

	/**
	 * We had some issues with our v3.0.4 upgrade process due to a bug.
	 * Consequently we pulled the update and worked on a fix.
	 *
	 * Unfortunately some users would still have a semi-broken plugin as a result.
	 * Re-running the v3.0.0 upgrade should resolve the issue and regenerate any missing options.
	 * We also have to re-transfer the capabilities as these were also affected by the bug.
	 *
	 * @return void
	 */
	public function fixBrokenSliders()
	{
		$sliders = $this->getSliders();

		foreach ($sliders as $slider) {
			if ($this->isMissingData($slider->ID)) {
				$this->upgradeSlider($slider->ID);
			}
		}
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->fixBrokenSliders();
	}
}