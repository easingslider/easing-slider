<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php _e( 'Asset Loading', 'easingslider' ); ?></th>
			<td>
				<label for="assets_header">
					<input type="radio" name="settings[load_assets]" id="assets_header" value="header" <?php checked( $settings->load_assets, 'header' ); ?>>
					<span><?php _e( 'Compatibility (header)', 'easingslider' ); ?></span>
				</label>

				<label for="assets_footer">
					<input type="radio" name="settings[load_assets]" id="assets_footer" value="footer" <?php checked( $settings->load_assets, 'footer' ); ?>>
					<span><?php _e( 'Optimized (footer)', 'easingslider' ); ?></span>
				</label>

				<p class="description"><?php _e( 'This option controls where the plugin\'s scripts and styling are loaded. "Compatibility" will load them in the page header, which is less performant but also less likely to suffer conflicts with other plugins. "Optimized" conditionally loads CSS and JS in the page footer, which is better for performance, but more likely to encounter errors. We recommend trying "Optimized" and reverting back if you encounter any issues.', 'easingslider' ); ?></p>
			</td>
		</tr>
	</tbody>
</table>

<hr><h3><?php _e( 'Data Settings', 'easingslider' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php _e( 'Remove data on uninstall?', 'easingslider' ); ?></th>
			<td>
				<label for="remove_data">
					<input type="hidden" name="settings[remove_data]" value="false">
					<input type="checkbox" name="settings[remove_data]" id="remove_data" value="true" <?php checked( $settings->remove_data, true ); ?>>
					<span style="display: inline;"><?php _e( 'Check this box if you would like Easing Slider to completely remove all of its data when the plugin is deleted.', 'easingslider' ); ?></span>
				</label>

				<p class="description"><?php _e( 'Be careful before enabling this option, as the data cannot be recovered once it has been deleted.', 'easingslider' ); ?></p>
			</td>
		</tr>
	</tbody>
</table>

<hr><h3><?php _e( 'Installation Settings', 'easingslider' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php _e( 'PHP Version', 'easingslider' ); ?></th>
			<td><?php echo phpversion(); ?></td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'MySQL Version', 'easingslider' ); ?></th>
			<td><?php global $wpdb; echo $wpdb->get_var( 'SELECT VERSION()' ); ?></td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'WordPress Version', 'easingslider' ); ?></th>
			<td><?php global $wp_version; echo $wp_version; ?></td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Plugin Version', 'easingslider' ); ?></th>
			<td><?php echo Easing_Slider::$version; ?></td>
		</tr>
	</tbody>
</table>