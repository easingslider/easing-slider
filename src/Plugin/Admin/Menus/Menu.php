<?php

namespace EasingSlider\Plugin\Admin\Menus;

use EasingSlider\Foundation\Admin\Menus\Menu as BaseMenu;
use EasingSlider\Foundation\Contracts\Admin\Panels\Panels;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Menu extends BaseMenu
{
	/**
	 * Panels
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Panels\Panels
	 */
	protected $panels;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Panels\Panels $panels
	 * @return void
	 */
	public function __construct(Panels $panels)
	{
		$this->panels = $panels;

		parent::__construct();
	}

	/**
	 * Setup menu
	 *
	 * @return void
	 */
	public function setupMenu()
	{
		$this->setupTopLevelMenu();
		$this->setupAllSlidersPanel();
		$this->setupAddNewPanel();
		$this->setupSettingsPanel();
		$this->setupAddonsPanel();
	}

	/**
	 * Sets up the toplevel page for this menu
	 *
	 * @return void
	 */
	protected function setupToplevelMenu()
	{
		$this->setToplevelMenu(
			__('Sliders', 'easingslider'),
			__('Sliders', 'easingslider'),
			'easingslider_edit_sliders',
			'easingslider',
			null,
			'dashicons-images-alt'
		);
	}

	/**
	 * Sets up the "All Sliders" page
	 *
	 * @return void
	 */
	protected function setupAllSlidersPanel()
	{
		$this->addSubmenuPage(
			__('Sliders', 'easingslider'),
			__('All Sliders', 'easingslider'),
			'easingslider_edit_sliders',
			'easingslider',
			array($this->panels['all_sliders'], 'display')
		);
	}

	/**
	 * Sets up the "Add New" page
	 *
	 * @return void
	 */
	protected function setupAddNewPanel()
	{
		$this->addSubmenuPage(
			__('Add New Slider', 'easingslider'),
			__('Add New', 'easingslider'),
			'easingslider_publish_sliders',
			'easingslider-add-new',
			array($this->panels['create_slider'], 'display')
		);
	}

	/**
	 * Sets up the "Settings" page
	 *
	 * @return void
	 */
	protected function setupSettingsPanel()
	{
		$this->addSubmenuPage(
			__('Settings', 'easingslider'),
			__('Settings', 'easingslider'),
			'easingslider_manage_settings',
			'easingslider-settings',
			array($this->panels['settings'], 'display')
		);
	}

	/**
	 * Sets up the "Addons" page
	 *
	 * @return void
	 */
	protected function setupAddonsPanel()
	{
		$this->addSubmenuPage(
			__('Addons', 'easingslider'),
			__('Get More Features!', 'easingslider'),
			'easingslider_manage_addons',
			'easingslider-addons',
			array($this->panels['addons'], 'display')
		);
	}
}
