<?php easingslider_inline_styles($slider); ?>

<?php easingslider_inline_script($slider); ?>

<div data-id="<?php echo esc_attr($slider->ID); ?>" <?php easingslider_container_classes($slider); ?>>
	<?php foreach ($slider->slides as $slide) : ?>
		<div class="easingslider-slide">
			<?php easingslider_display_slide($slide, $slider); ?>
		</div>
	<?php endforeach; ?>
</div>
