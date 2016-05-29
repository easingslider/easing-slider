<?php

namespace EasingSlider\Plugin\Admin\Ajax;

use EasingSlider\Plugin\Admin\UpgraderSkin;
use Plugin_Upgrader;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class AddonInstaller
{
	/**
	 * Installs an addon
	 *
	 * @return array|false
	 */
	public function install()
	{
		global $hook_suffix;

		// Do security check
		check_ajax_referer('easingslider-install-addon', 'nonce');

		// Install the addon
		if (isset($_POST['plugin'])) {

			// Get the download URL
			$downloadUrl = $_POST['plugin'];

			// Set the current screen to avoid undefined notices
			set_current_screen();

			// Prepare variables
			$method = '';

			// Get URL
			$url = esc_url(add_query_arg(
				array(
					'page' => 'easingslider-settings'
				),
				admin_url('admin.php')
			));

			// Start output bufferring to catch the filesystem form if credentials are needed
			ob_start();
			if (false === ($credentials = request_filesystem_credentials($url, $method, false, false, null))) {
				$form = ob_get_clean();
				
				return array('form' => $form);
			}

			// If we are not authenticated, make it happen now
			if ( ! WP_Filesystem($credentials)) {
				ob_start();
				request_filesystem_credentials($url, $method, true, false, null);
				$form = ob_get_clean();

				return array('form' => $form);
			}

			// We do not need any extra credentials if we have gotten this far, so let's install the plugin
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			// Create the plugin upgrader with our custom skin
			$installer = new Plugin_Upgrader(new UpgraderSkin());
			$installer->install($downloadUrl);

			// Flush the cache and return the newly installed plugin basename.
			wp_cache_flush();
			if ($installer->plugin_info()) {
	            $pluginBasename = $installer->plugin_info();

				return array('plugin' => $pluginBasename);
			}
		}

		return false;
	}
}
