<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This code is for debugging purposes.
 * Uncomment the lines to force WordPress to check for plugin updates on every page load.
 */
// set_transient( 'update_plugins', null );
// set_site_transient( 'update_plugins', null );

/**
 * This class handles all the update-related stuff for extensions, including adding a license section to the "Licenses" settings tab.
 *
 * @author Matthew Ruddy
 */
class ES_Update_Manager {

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Plugin version
	 *
	 * @var int
	 */
	protected $version;

	/**
	 * License key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Our API Url
	 *
	 * @var string
	 */
	public $api_url = 'http://easingslider.com/api/';

	/**
	 * Transient expiry time (default 6 hours)
	 *
	 * @var int
	 */
	public $transient_expiry = 21600;

	/**
	 * Constructor
	 *
	 * @param  array  $name    The plugin name
	 * @param  string $slug    The plugin slug
	 * @param  int    $version The plugin version
	 * @return void
	 */
	public function __construct( $name, $slug, $version ) {

		// Establish variables
		$this->name    = $name;
		$this->slug    = $slug;
		$this->version = $version;

		// Set the license key
		$this->set_key();

		// Define hooks for this license
		$this->define_hooks();

	}

	/**
	 * Sets the license key
	 *
	 * @return void
	 */
	protected function set_key() {

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		// Get the license key index
		$key_index = "{$this->slug}_license_key";

		// Set the license key if it exists
		if ( isset( $settings->{$key_index} ) ) {
			$this->key = $settings->{$key_index};
		}

	}

	/**
	 * Define hooks for this license
	 *
	 * @return void
	 */
	protected function define_hooks() {

		add_action( 'easingslider_do_settings_actions',       array( $this, 'attempt_deactivation' ) );
		add_action( 'easingslider_do_settings_actions',       array( $this, 'attempt_registration' ) );
		add_action( 'easingslider_print_license_fields',      array( $this, 'display_license_field' ) );
		add_filter( 'pre_set_transient_update_plugins',       array( $this, 'modify_updates_transient' ) );
		add_filter( 'pre_set_site_transient_update_plugins',  array( $this, 'modify_updates_transient' ) );
		add_filter( 'plugins_api',                            array( $this, 'get_update_info' ), 10, 3 );

	}

	/**
	 * Registers the license key to the current site
	 *
	 * @return boolean|string
	 */
	public function register() {

		// Make the validation request
		$request = wp_remote_post( $this->api_url, array(
			'timeout' => 10,
			'body'    => array(
				'action'      => 'register_license',
				'key'         => $this->key,
				'slug'        => $this->slug,
				'url'         => home_url(),
				'version'     => $this->version
			)
		) );

		// Bail if WordPress error was received (HTTP API may have failed or may not have access required)
		if ( is_wp_error( $request ) ) {

			return $request->get_error_message();

		}

		// Get the response headers
		$headers = (object) wp_remote_retrieve_headers( $request );

		// Get the response body
		$response = json_decode( wp_remote_retrieve_body( $request ) );
				
		// Bail if no response was received (service may temporarily be offline)
		if ( ! isset( $response->response ) ) {

			return __( 'The license key registration service is temporarily unavailable. Please try again later.', 'easingslider' );

		}

		// If registration was a success, return true.
		if ( 200 == $headers->status ) {

			// Flag the license as valid
			update_option( "{$this->slug}_license_is_valid", true );

			return true;

		}

		return $response->response;
	
	}

	/**
	 * Deactivates the license registration for the current site
	 *
	 * @return boolean|string
	 */
	public function deactivate() {

		// Make the validation request
		$request = wp_remote_post( $this->api_url, array(
			'timeout' => 10,
			'body'    => array(
				'action'      => 'deactivate_license',
				'key'         => $this->key,
				'slug'        => $this->slug,
				'url'         => home_url(),
				'version'     => $this->version
			)
		) );

		// Bail if WordPress error was received (HTTP API may have failed or may not have access required)
		if ( is_wp_error( $request ) ) {

			return $request->get_error_message();

		}

		// Get the response headers
		$headers = (object) wp_remote_retrieve_headers( $request );

		// If license was successfully deactivated, return true.
		if ( 204 == $headers->status ) {

			// Delete the license validation flag
			delete_option( "{$this->slug}_license_is_valid" );

			return true;

		}

		// If we've gotten here, there's been an error. Tell the user.
		return __( 'Your license could not be deactivated. Please contact support to have it deactivated correctly.', 'easingslider' );
	
	}

	/**
	 * Get plugin updates, requesting to server if required.
	 *
	 * @return array|false
	 */
	public function get_updates() {
		//
	}

	/**
	 * Attempts to register the license key to this site
	 *
	 * @return void
	 */
	public function attempt_registration() {

		// Bail if we have no license key
		if ( empty( $this->key ) ) {
			return;
		}

		// Bail if not registering this license
		if ( ! isset( $_POST["register-{$this->slug}"] ) OR ! isset( $_POST['save'] ) ) {
			return;
		}

		// Check security nonce
		if ( ! check_admin_referer( 'save' ) ) {
			return;
		}

		// Attempt to register the license
		$response = $this->register();

		// Handle response
		if ( true === $response ) {

			// Tell the user the license was registered successfully.
			easingslider_show_notice( sprintf( __( '"%s" License has been registered successfully.', 'easingslider' ), $this->name ), 'updated notice' );

		}
		else {

			// Tell the user we failed to register the license
			easingslider_show_notice( $response, 'error' );

		}

	}

	/**
	 * Attempts to deactive the license key for this site
	 *
	 * @return void
	 */
	public function attempt_deactivation() {

		// Bail if not deactivating this license
		if ( ! isset( $_POST["deactivate-{$this->slug}"] ) ) {
			return;
		}

		// Check security nonce
		if ( ! check_admin_referer( 'save' ) ) {
			return;
		}

		// Attempt to deactivate the license
		$response = $this->deactivate();

		// Handle response
		if ( true === $response ) {

			// Tell the user the license was registered successfully.
			easingslider_show_notice( sprintf( __( '"%s" License has been deactivated successfully.', 'easingslider' ), $this->name ), 'updated notice' );

		}
		else {

			// Tell the user we failed to deactivate the license
			easingslider_show_notice( $response, 'error' );

		}

	}

	/**
	 * Displays the license settings field
	 *
	 * @return void 
	 */
	public function display_license_field() {

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		// Get license validation flag
		$is_valid = get_option( "{$this->slug}_license_is_valid" );

		// Get the license key index
		$key_index = "{$this->slug}_license_key";
	
		// Display the field
		?>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $this->slug ); ?>">
						<?php printf( __( '"%s" License Key', 'easingslider' ), $this->name ); ?>
					</label>
				</th>
				<td>
					<input type="hidden" name="register-<?php echo esc_attr( $this->slug ); ?>" value="true">
					<span class="<?php if ( $is_valid ) { echo 'valid-license'; } ?>">
						<input type="text" name="<?php echo "settings[{$key_index}]"; ?>" class="regular-text" value="<?php if ( isset( $settings->{$key_index} ) ) { echo esc_attr( $settings->{$key_index} ); } ?>">
					</span>
					<?php if ( $is_valid ) : ?>
						<input type="submit" class="button button-secondary" id="deactivate" name="deactivate-<?php echo esc_attr( $this->slug ); ?>" value="<?php _e( 'Deactivate', 'easingslider' ); ?>">
					<?php endif; ?>
				</td>
			</tr>
		<?php

	}

	/**
	 * Displays plugin update information on the WordPress Plugins page
	 *
	 * @param  object $res
	 * @param  string $action The action occurring
	 * @param  object $args   The arguments
	 * @return object
	 */
	public function get_update_info( $res, $action, $args ) {

		return $res;
		
	}

	/**
	 * Modifies the WordPress updates transient to include our update information
	 *
	 * @param  object $checked_data The data of checked plugin updates
	 * @return object
	 */
	public function modify_updates_transient( $checked_data ) {
		
		return $checked_data;

	}

}