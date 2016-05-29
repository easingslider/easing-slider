<div class="wrap">
	<form id="slider" name="slider" action="admin.php?page=<?php echo esc_attr($page); if (isset($slider->ID)) { echo "&amp;edit=". esc_attr($slider->ID) .""; } ?>" method="post">
		<input type="hidden" name="post_type" value="<?php echo esc_attr($slider->post_type); ?>">
		<input type="hidden" name="post_status" value="<?php echo esc_attr($slider->post_status); ?>">

		<?php if (isset($slider->ID)) : ?>
			<input type="hidden" name="easingslider_action" value="update_slider">
			<input type="hidden" name="id" value="<?php echo esc_attr($slider->ID); ?>">
			<?php wp_nonce_field('update'); ?>
		<?php else : ?>
			<input type="hidden" name="easingslider_action" value="create_slider">
			<?php wp_nonce_field('create'); ?>
		<?php endif; ?>

		<h1>
			<?php if (isset($slider->ID)) : ?>
				<?php _e('Edit Slider', 'easingslider'); ?>
				<a href="admin.php?page=easingslider-add-new" class="page-title-action add-new-h2"><?php _e('Add New', 'easingslider'); ?></a>
			<?php else : ?>
				<?php _e('Add New Slider', 'easingslider'); ?>
			<?php endif; ?>
		</h1>

		<div class="toolbar-container">
			<div class="media-toolbar wp-filter">
				<div class="media-toolbar-secondary title-form">
					<label for="post_title"><?php _e('Name:', 'easingslider'); ?></label>
					<input type="text" name="post_title" id="post_title" autocomplete="off" placeholder="<?php _e('Enter a slider name', 'easingslider'); ?>" value="<?php echo esc_attr($slider->post_title); ?>">
					<a href="#" id="select-all" class="button media-button button-large hide"><?php _e('Select All', 'easingslider'); ?></a>
				</div>

				<div class="media-toolbar-primary <?php if ('media' != $slider->type) echo 'hidden'; ?>">
					<label for="randomize" class="randomize">
						<input type="hidden" name="randomize" value="false">
						<input type="checkbox" id="randomize" name="randomize" value="true" <?php checked($slider->randomize, true); ?>><?php _e('Randomize the slide order', 'easingslider'); ?>
					</label>
					<a href="#" id="add-slides" class="button media-button button-primary button-large"><?php _e('Add Slides', 'easingslider'); ?></a>
					<a href="#" id="bulk-select" class="button media-button button-large select-mode-toggle-button"><?php _e('Bulk Select', 'easingslider'); ?></a>
					<a href="#" id="cancel-select" class="button media-button button-large select-mode-toggle-button hide"><?php _e('Cancel Selection', 'easingslider'); ?></a>
					<a href="#" id="delete-slides" class="button media-button button-primary button-large hide"><?php _e('Delete Selected', 'easingslider'); ?></a>
				</div>
			</div>
		</div>

		<div class="main-container clearfix">
			<?php foreach ($types as $type => $label) : ?>
				<div data-type="<?php echo esc_attr($type); ?>" class="slides-container <?php if ($type != $slider->type) echo 'hidden'; ?>">
					<?php do_action("easingslider_admin_{$type}_slides_panel", $slider); ?>
				</div>
			<?php endforeach; ?>

			<div class="settings-container sidebar-container">
				<?php
					/**
					 * Keep our settings sidebar in a separate file for clarity. Woohoo!
					 */
					require 'edit-slider_sidebar.php';
				?>
			</div>
		</div>

		<?php
			/**
			 * This ensures that the slide's JSON is encoded correctly.
			 * Using PHP JSON encode can cause magic quote issues.
			*/
		?>
		<script type="text/javascript">var slides = '<?php echo addslashes(json_encode($slider->slides)); ?>';</script>
	</form>
</div>
