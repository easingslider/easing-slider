<?php

namespace EasingSlider\Foundation\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\Panel;
use EasingSlider\Foundation\Contracts\Admin\Panels\Panel as PanelContract;
use EasingSlider\Foundation\Contracts\Options\OptionArray;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class SettingsApiPanel extends Panel implements PanelContract
{
	/**
	 * Option
	 *
	 * @var \EasingSlider\Foundation\Contracts\Options\OptionArray
	 */
	protected $option;

	/**
	 * Page Slug
	 *
	 * @var string|false
	 */
	protected $page;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Options\OptionArray $option
	 * @return array
	 */
	public function __construct(OptionArray $option)
	{
		$this->option = $option;

		// Get the page slug
		$this->page = $this->getPage();

		// Define hooks
		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_action('admin_init', array($this, 'registerFields'));
	}

	/**
	 * Gets the page slug
	 *
	 * @return string
	 */
	protected function getPage()
	{
		if ( ! empty($_GET['page'])) {
			return $_GET['page'];
		}

		return false;
	}

	/**
	 * Registers the settings fields
	 *
	 * @return void
	 */
	public function registerFields()
	{
		$optionName = $this->option->getName();

		// Loop through settings sections
		foreach ($this->getSections() as $tab => $settings) {

			// Add the section
			add_settings_section("{$optionName}_{$tab}", __return_null(), '__return_false', "{$optionName}_{$tab}");

			// Loop through settings fields
			foreach ($settings as $key => $option) {

				// Add the field
				add_settings_field(
					"{$optionName}[{$key}]",
					isset($option['name']) ? $option['name'] : '',
					is_callable(array($this, $option['type'] . 'Callback')) ? array($this, $option['type'] . 'Callback') : array($this, 'missingCallback'),
					"{$optionName}_{$tab}",
					"{$optionName}_{$tab}",
					array(
						'id'      => $key,
						'desc'    => ! empty($option['desc']) ? $option['desc'] : '',
						'name'    => isset($option['name']) ? $option['name'] : null,
						'section' => $tab,
						'label'   => isset($option['label']) ? $option['label'] : null,
						'action'  => isset($option['action']) ? $option['action'] : null,
						'size'    => isset($option['size']) ? $option['size'] : 'regular',
						'max'     => isset($option['max']) ? $option['max'] : 999999,
						'min'     => isset($option['min']) ? $option['min'] : 0,
						'step'    => isset($option['step']) ? $option['step'] : 1,
						'options' => isset($option['options']) ? $option['options'] : '',
						'std'     => isset($option['std']) ? $option['std'] : ''
					)
				);

			}

		}

		// Creates the settings in the options table
		register_setting($optionName, $optionName, array($this, 'sanitizeInput'));
	}

	/**
	 * Validates the settings input
	 *
	 * @param  array $input
	 * @return array
	 */
	public function validateInput($input)
	{
		// Get new validator instance
		$validator = $this->validator();

		// Validate and return response
		return $validator->validate($input);
	}

	/**
	 * Sanitizes the settings input
	 *
	 * @param  array $input The settings input
	 * @return array
	 */
	public function sanitizeInput($input = array())
	{
		// Return if no referer
		if (empty($_POST['_wp_http_referer'])) {
			return $input;
		}

		// Return if empty
		if (empty($input)) {
			return $input;
		}

		// Parse referer
		parse_str($_POST['_wp_http_referer'], $referrer);

		// Get the settings sections and tabs
		$settings = $this->getSections();
		$tabs = $this->getTabs();

		// Get the current tab
		$tab = isset($referrer['tab']) ? $referrer['tab'] : current($tabs);
		
		// Validate
		$input = $this->validateInput($input);

		// Tell user settings have been updated
		add_settings_error('easingslider-notices', '', __('Settings updated.', 'easingslider'), 'updated');

		// Merge input with current settings
		return array_merge($this->option->getValue(), $input);
	}

	/**
	 * Gets the active tab
	 *
	 * @return string
	 */
	protected function getActiveTab()
	{
		$tabs = $this->getTabs();

		// Get from URL
		if (isset($_GET['tab']) && array_key_exists($_GET['tab'], $tabs)) {
			return $_GET['tab'];
		}

		// Fallback to first tab
		return current(array_keys($tabs));
	}

	/**
	 * Gets the name for a HTML attribute
	 *
	 * @param  string $attribute
	 * @return string
	 */
	protected function getNameAttribute($attribute)
	{
		return esc_attr($this->option->getName() .'['. $attribute .']');
	}

	/**
	 * Renders the header
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function headerCallback($args)
	{
		echo '<hr />';
	}

	/**
	 * Render an information field
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function infoCallback($args)
	{
		echo $args['std'];
	}

	/**
	 * Renders a checkbox
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function checkboxCallback($args)
	{
		$checked = ($this->option[$args['id']] && true === $this->option[$args['id']]) ? true : false;

		?>
			<input type="hidden" name="<?php echo $this->getNameAttribute($args['id']); ?>" value="false">
			<input type="checkbox" name="<?php echo $this->getNameAttribute($args['id']); ?>" id="<?php echo esc_attr($args['id']); ?>" value="true" <?php checked(true, $checked); ?>>
			<label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['desc']); ?></label>
		<?php
	}

	/**
	 * Renders multiple checkboxes
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function multicheckCallback($args)
	{
		?>
			<?php if ( ! empty($args['options'])) : ?>
				<?php foreach ($args['options'] as $key => $option) : ?>
					<?php $checked = ($this->option[$args['id']] && $this->option[$args['id']][$key] && true === $this->option[$args['id'][$key]]) ? true : false; ?>

					<input type="hidden" name="<?php echo $this->getNameAttribute($args['id']); ?>[<?php echo esc_attr($key); ?>]" value="false">
					<input type="checkbox" name="<?php echo $this->getNameAttribute($args['id']); ?>[<?php echo esc_attr($key); ?>]" id="<?php echo esc_attr($args['id'] .'-'. $key); ?>" value="true" <?php checked(true, $checked); ?>>
					<label for="<?php echo esc_attr($args['id'] .'-'. $key); ?>"><?php echo esc_html($option); ?></label>
				<?php endforeach; ?>
			<?php else : ?>
				<span><?php _e('No options available.', 'easingslider'); ?></span>
			<?php endif; ?>

			<p class="description"><?php echo esc_html($args['desc']); ?></p>
		<?php
	}

	/**
	 * Renders radio options
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function radioCallback($args)
	{
		$checked = ($this->option[$args['id']]) ? $this->option[$args['id']] : $args['std'];

		?>
			<?php if ( ! empty($args['options'])) : ?>
				<?php foreach ($args['options'] as $key => $option) : ?>

					<input type="radio" name="<?php echo $this->getNameAttribute($args['id']); ?>" id="<?php echo esc_attr($args['id'] .'-'. $key); ?>" value="<?php echo esc_attr($key); ?>" <?php checked($key, $this->option[$args['id']]); ?>>
					<label for="<?php echo esc_attr($args['id'] .'-'. $key); ?>"><?php echo esc_html($option); ?></label>
				<?php endforeach; ?>
			<?php else : ?>
				<span><?php _e('No options available.', 'easingslider'); ?></span>
			<?php endif; ?>

			<p class="description"><?php echo esc_html($args['desc']); ?></p>
		<?php
	}

	/**
	 * Renders a text field
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function textCallback($args)
	{
		$value = ($this->option[$args['id']]) ? $this->option[$args['id']] : $args['std'];

		?>
			<input type="text" name="<?php echo $this->getNameAttribute($args['id']); ?>" id="<?php echo esc_attr($args['id']); ?>" value="<?php echo esc_attr($value); ?>">
			<label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['desc']); ?></label>
		<?php
	}

	/**
	 * Renders a number field
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function numberCallback($args)
	{
		$value = ($this->option[$args['id']]) ? $this->option[$args['id']] : $args['std'];

		?>
			<input type="number" name="<?php echo $this->getNameAttribute($args['id']); ?>" id="<?php echo esc_attr($args['id']); ?>" min="<?php echo esc_attr($args['min']); ?>" max="<?php echo esc_attr($args['max']); ?>" step="<?php echo esc_attr($args['step']); ?>" class="<?php echo esc_attr($args['size'] .'-text'); ?>" value="<?php echo esc_attr($value); ?>">
			<label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['desc']); ?></label>
		<?php
	}

	/**
	 * Renders a textarea
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function textareaCallback($args)
	{
		$value = ($this->option[$args['id']]) ? $this->option[$args['id']] : $args['std'];

		?>
			<textarea name="<?php echo $this->getNameAttribute($args['id']); ?>" id="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($value); ?>"></textarea>
			<label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['desc']); ?></label>
		<?php
	}

	/**
	 * Renders a password field
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function passwordCallback($args)
	{
		$value = ($this->option[$args['id']]) ? $this->option[$args['id']] : $args['std'];

		?>
			<input type="password" name="<?php echo $this->getNameAttribute($args['id']); ?>" id="<?php echo esc_attr($args['id']); ?>" value="<?php echo esc_attr($value); ?>">
			<label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['desc']); ?></label>
		<?php
	}

	/**
	 * Tells the user that the specified callback is missing
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function missingCallback($args)
	{
		printf(__('The callback function used for the <strong>%s</strong> setting is missing.', 'easingslider'), $args['id']);
	}

	/**
	 * Renders a select box field
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function selectCallback($args)
	{
		$selected = ($this->option[$args['id']]) ? $this->option[$args['id']] : $args['std'];

		?>
			<?php if ( ! empty($args['options'])) : ?>
				<select name="<?php echo $this->getNameAttribute($args['id']); ?>">
					<?php foreach ($args['options'] as $key => $option) : ?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $selected); ?>><?php echo esc_html($option); ?></option>
					<?php endforeach; ?>
				</select>
			<?php else : ?>
				<span><?php _e('No options available.', 'easingslider'); ?></span>
			<?php endif; ?>

			<label for="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html($args['desc']); ?></label>
		<?php
	}

	/**
	 * Renders an action field
	 *
	 * @param  array $args The setting arguments
	 * @return void
	 */
	public function actionCallback($args)
	{
		$url = wp_nonce_url("?page={$this->page}&easingslider_action={$args['action']}", $args['action']);

		?>
			<a href="<?php echo esc_attr($url); ?>" class="button button-secondary delete">
				<?php echo esc_html($args['label']); ?>
			</a>
			<p class="description"><?php echo esc_html($args['desc']); ?></p>
		<?php
	}

	/**
	 * Get tabs
	 *
	 * @return array
	 */
	abstract protected function getTabs();

	/**
	 * Get sections
	 *
	 * @return array
	 */
	abstract protected function getSections();

	/**
	 * Gets a new validator instance
	 *
	 * @return array
	 */
	abstract protected function validator();
}
