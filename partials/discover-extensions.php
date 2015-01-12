<div class="wrap">
	<h2>
		<?php _e( 'Extensions ', 'easingslider' ); ?>

        <a href="http://easingslider.com/extensions" target="_blank" class="add-new-h2">
            <?php _e( 'Browse All', 'easingslider' ); ?>
        </a>
	</h2>

	<p><?php _e( 'These extensions provide additional functionality to Easing Slider.', 'easingslider' ); ?></p>
	
	<?php foreach ( $extensions as $extension ) : ?>
		<div class="extension">
			<a href="<?php echo esc_attr( $extension->link ); ?>" title="<?php echo esc_attr( $extension->title ); ?>">
				<img src="<?php echo esc_attr( $extension->image ); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_attr( $extension->title ); ?>" title="<?php echo esc_attr( $extension->title ); ?>" />
			</a>
			<h3 class="extension-title"><?php echo esc_html( $extension->title ); ?></h3>
			<p><?php echo esc_attr( $extension->content ); ?></p>
			<a href="<?php echo esc_attr( $extension->link ); ?>" title="<?php echo esc_attr( $extension->title ); ?>" class="button-primary"><?php _e( 'Get this extension', 'easingslider' ); ?></a>
		</div>
	<?php endforeach; ?>
</div>