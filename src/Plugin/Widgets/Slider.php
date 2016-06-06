<?php

namespace EasingSlider\Plugin\Widgets;

use EasingSlider\Foundation\Widgets\Widget;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Slider extends Widget
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
	 * Boots the widget
	 *
	 * @return void
	 */
	protected function boot()
	{
		// Get dependencies through plugin as WordPress doesn't allow us to inject them into widgets
		$this->shortcode = Easing_Slider()->shortcode();
		$this->sliders = Easing_Slider()->sliders();
	}

	/**
	 * Gets the widget name
	 *
	 * @return string
	 */
	protected function getName()
	{
		return 'easingslider_widget';
	}

	/**
	 * Gets the widget title
	 *
	 * @return string
	 */
	protected function getTitle()
	{
		return __('Slider', 'easingslider');
	}

	/**
	 * Gets the widget description
	 *
	 * @return string
	 */
	protected function getDescription()
	{
		return __('Display a slider using a widget.', 'easingslider');
	}

	/**
	 * Updates the widget settings
	 *
	 * @param  array $newInstance
	 * @param  array $oldInstance
	 * @return array
	 */
	public function update($newInstance, $oldInstance)
	{
		return array(
			'id'    => intval($newInstance['id']),
			'title' => strip_tags($newInstance['title'])
		);
	}

	/**
	 * Shows the widget settings form
	 *
	 * @param  array $instance
	 * @return array
	 */
	public function form($instance)
	{
		// Get all published sliders
		$sliders = $this->sliders->all('publish');

		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'easingslider'); ?></label>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" class="widefat" value="<?php if (isset($instance['title'])) echo esc_attr($instance['title']); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Select Slider:', 'easingslider'); ?></label>
				<select id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" class="widefat">
					<option value="-1"><?php _e('&#8212; Select &#8212;', 'easingslider'); ?></option>
					<?php foreach ($sliders as $slider) : ?>
						<option value="<?php echo esc_attr($slider->ID); ?>" <?php if (isset($instance['id'])) selected($instance['id'], $slider->ID); ?>><?php echo esc_html($slider->post_title) . sprintf(__(' (ID #%s)', 'easingslider'), $slider->ID); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		<?php
	}

	/**
	 * Displays the widget content
	 *
	 * @param  array $instance
	 * @return void
	 */
	public function display($instance)
	{
		if ( ! empty($instance['id'])) {
			echo $this->shortcode->render(array('id' => $instance['id']));
		}
	}
}
