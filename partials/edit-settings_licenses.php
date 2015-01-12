<p style="padding-top: 10px;"><?php printf( __( 'Below you can register your extension licenses. This is required to enable extension updates. You can browse all available extensions <a href="%s">here</a>.', 'easingslider' ), admin_url( 'admin.php?page=easingslider_discover_extensions' ) ); ?></p>

<table class="form-table">
	<tbody>
		<?php
			/**
			 * Print license settings fields
			 */
			do_action( 'easingslider_print_license_fields' );
		?>
	</tbody>
</table>