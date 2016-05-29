<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\SettingsApiPanel;
use EasingSlider\Plugin\Admin\Validators\Settings as SettingsValidator;
use EasingSlider\Plugin\Contracts\Options\Settings as SettingsOption;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Settings extends SettingsApiPanel
{
	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Contracts\Options\Settings $settings
	 * @return void
	 */
	public function __construct(SettingsOption $settings)
	{
		parent::__construct($settings);
	}

	/**
	 * Gets the tabs
	 *
	 * @return array
	 */
	protected function getTabs()
	{
		return apply_filters('easingslider_settings_tabs', array(
			'general'     => __('General', 'easingslider'),
			'diagnostics' => __('Diagnostics', 'easingslider')
		));
	}

	/**
	 * Gets the sections
	 *
	 * @return array
	 */
	protected function getSections()
	{
		global $wpdb, $wp_version;

		$sections = array(
			'general' => apply_filters('easingslider_settings_general', array(
				'assets_header' => array(
					'name' => __('Asset Loading', 'easingslider'),
					'type' => 'header'
				),
				'load_in_footer' => array(
					'name' => __('Load in Footer', 'easingslider'),
					'type' => 'checkbox',
					'desc' => __('Should Easing Slider load it\'s CSS & Javascript in the page footer? This reduces page loading time, but may be prone to conflicts with other plugins.', 'easingslider'),
					'std'  => false
				),
				'data_header' => array(
					'name' => __('Data Settings', 'easingslider'),
					'type' => 'header'
				),
				'remove_data' => array(
					'name' => __('Remove data on uninstall?', 'easingslider'),
					'type' => 'checkbox',
					'desc' => __('Check this box if you would like Easing Slider to completely remove all of its data when the plugin is deleted.', 'easingslider'),
					'std'  => false
				),
				'reset_plugin' => array(
					'name'   => __('Reset data to defaults', 'easingslider'),
					'type'   => 'action',
					'action' => 'reset_plugin',
					'label'  => __('Reset Plugin', 'easingslider'),
					'desc'   => __('Click this button to completely reset your Easing Slider settings back to defaults. Be careful, as this process cannot be reversed!', 'easingslider'),
				),
			)),
			'diagnostics' => apply_filters('easingslider_settings_diagnostics', array(
				'installation_header' => array(
					'name' => __('System Settings', 'easingslider'),
					'type' => 'header'
				),
				'php_version' => array(
					'name' => __('PHP Version', 'easingslider'),
					'type' => 'info',
					'std'  => phpversion()
				),
				'mysql_version' => array(
					'name' => __('MySQL Version', 'easingslider'),
					'type' => 'info',
					'std'  => $wpdb->get_var('SELECT VERSION()')
				),
				'wordpress_version' => array(
					'name' => __('WordPress Version', 'easingslider'),
					'type' => 'info',
					'std'  => $wp_version
				),
				'easingslider_version' => array(
					'name' => __('Plugin Version', 'easingslider'),
					'type' => 'info',
					'std'  => EASINGSLIDER_VERSION
				),
			))
		);

		return apply_filters('easingslider_settings_sections', $sections);
	}

	/**
	 * Gets a new validator instance
	 *
	 * @return array
	 */
	protected function validator()
	{
		return new SettingsValidator();
	}

	/**
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display()
	{
		$this->showView('edit-settings', array(
			'activeTab'  => $this->getActiveTab(),
			'optionName' => $this->option->getName(),
			'sections'   => $this->getSections(),
			'tabs'       => $this->getTabs()
		));
	}
}
