<div class="wrap">
	<h2>
		<?php _e( 'Extensions for Easing Slider', 'easingslider' ); ?>

        <a href="http://easingslider.com/extensions" target="_blank" class="add-new-h2">
            <?php _e( 'Browse All', 'easingslider' ); ?>
        </a>
	</h2>

	<p><?php _e( 'These extensions enhance Easing Slider, adding new features, providing new slide types and integrating Easing Slider with other plugins.', 'easingslider' ); ?></p>
	
	<?php if ( $extensions ) : ?>
		<?php foreach ( $extensions as $extension ) : ?>
			<div class="extension">
				<a href="<?php echo esc_attr( $extension->link ); ?>" title="<?php echo esc_attr( $extension->title ); ?>">
					<img src="<?php echo esc_attr( $extension->image ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_attr( $extension->title ); ?>" title="<?php echo esc_attr( $extension->title ); ?>" />
				</a>
				<h3 class="extension-title"><?php echo esc_html( $extension->title ); ?></h3>
				<p><?php echo esc_attr( $extension->content ); ?></p>
				<a href="<?php echo esc_attr( $extension->link ); ?>" title="<?php echo esc_attr( $extension->title ); ?>" class="button-primary"><?php _e( 'Learn More &rarr;', 'easingslider' ); ?></a>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<p><?php printf( __( 'There was an error retrieving the extensions list from the server. Please try again later. <a href="%s" target="_blank">Click here</a> to view all extensions on our website.', 'easingslider' ), 'http://easingslider.com/extensions' ); ?></p>
	<?php endif; ?>
</div>