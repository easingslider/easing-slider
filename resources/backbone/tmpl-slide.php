<script type="text/html" id="tmpl-easingslider-slide">
	<div class="attachment-preview {{ data.model.type }}-slide js--select-attachment">
		<div class="toolbar">
			<i class="dashicons dashicons-edit edit"></i>
			<i class="dashicons dashicons-no-alt remove"></i>
		</div>

		<div class="thumbnail">
			<# if ('image' === data.model.type) { #>
				<# if (data.attachment && data.attachment.sizes) { #>
					<# var image = data.attachment.sizes.thumbnail || data.attachment.sizes.medium || data.attachment.sizes.large || data.attachment.sizes.full #>
					<div class="centered">
						<img src="{{ image.url }}" draggable="true" alt="" />
					</div>
				<# } else { #>
					<div class="centered">
						<img src="{{ data.model.url }}" class="external-image" draggable="true" alt="" />
					</div>
					<div class="filename">
						<div>
							<span class="dashicons dashicons-admin-links thumbnail-dashicon"></span>
							<?php _e('Ext. Image', 'easingslider'); ?>
						</div>
					</div>
				<# } #>
			<# } #>
			
			<?php
				/**
				 * This is for our extensions and their custom thumbnails
				 */
				do_action('easingslider_print_thumbnail');
			?>
		</div>
	</div>

	<a class="check" href="#" title="<?php esc_attr_e('Deselect'); ?>" tabindex="-1"><div class="media-modal-icon"></div></a>

	<?php
		/**
		 * This hidden attribute is kept in-sync with our model data.
		 * Everytime a change is made to the model, it's data is dumped here so we can fetch it with PHP.
		 */
	?>
	<input type="hidden" name="slides[]" value="{{ JSON.stringify(data.model) }}" />
</script>
