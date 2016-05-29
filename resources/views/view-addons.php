<div class="wrap">
	<h1>
		<?php _e('Addons for Easing Slider', 'easingslider'); ?>
		<a href="<?php echo esc_attr($addonsLink); ?>" class="page-title-action" target="_blank">
			<?php _e('Browse All Addons', 'easingslider'); ?>
		</a>
	</h1>

	<p><?php _e('These addons <em><strong>add functionality</strong></em> to Easing Slider.', 'easingslider'); ?></p>

	<div class="license-entry <?php echo ('valid' == $license['status']) ? 'license-valid' : 'license-required'; ?>">
		<form id="license" name="license" action="admin.php?page=<?php echo esc_attr($page); ?>" method="post">
			<?php if ('valid' == $license['status']) : ?>
				<p><strong><?php _e('Your license key:', 'easingslider' ); ?></strong></p>
				<input type="hidden" name="easingslider_action" value="deactivate_license">
				<span id="masked-key" class="masked-key"><?php echo esc_html($license->maskedKey()); ?></span>
				<input type="submit" class="button-primary" value="<?php _e('Deactivate License', 'easingslider'); ?>">
			<?php else : ?>
				<p><?php printf(__('Enter your license key to gain access to addons. Don\'t have a license key? <a href="%s" target="_blank">Purchase one here</a>.', 'easingslider'), $purchaseLink); ?></p>
				<input type="hidden" name="easingslider_action" value="activate_license">
				<input type="text" id="license-key" name="license_key" value="<?php echo esc_attr($license['key']); ?>">
				<input type="submit" class="button-primary" value="<?php _e('Activate License', 'easingslider'); ?>">
			<?php endif; ?>
		</form>
	</div>

	<?php if (false === $addons) : ?>
		<div class="error">
			<p><?php _e('There was an error retrieving the addons list from the server. Please try again later.', 'easingslider'); ?></p>
		</div>
	<?php else : ?>
		<div class="addons">
			<?php if ( ! empty($addons)) : ?>
				<?php foreach ($addons as $addon) : ?>
					<div class="addon">
						<a href="<?php echo esc_attr($addon->link); ?>" title="<?php echo esc_attr($addon->title); ?>" class="addon-thumbnail">
							<img src="<?php echo esc_attr($addon->image); ?>" class="attachment-showcase wp-post-image" alt="<?php echo esc_attr($addon->title); ?>" title="<?php echo esc_attr($addon->title); ?>" />
						</a>

						<h3 class="addon-title"><?php echo esc_html($addon->title); ?></h3>
						<p class="addon-desc"><?php echo esc_attr($addon->content); ?></p>
						
						<a href="<?php echo esc_attr($addon->link); ?>" target="_blank" title="<?php echo esc_attr($addon->title); ?>" class="addon-learn-more button-secondary"><?php _e('Learn More &rarr;', 'easingslider'); ?></a>
						
						<?php if ('no_license' == $addon->status) : ?>
							<div class="addon-status no-license">
								<span class="status-message"><?php printf(__('Please <a href="%s" target="_blank">purchase a license</a> key to gain access.', 'easingslider'), $purchaseLink); ?></span>
							</div>
						<?php elseif ('upgrade_license' == $addon->status) : ?>
							<div class="addon-status upgrade-license">
								<span class="status-message"><?php _e('Upgrade license to gain access to this addon.', 'easingslider'); ?></span>
							</div>
						<?php elseif ('available' == $addon->status) : ?>
							<?php if ($addon->slug) : ?>
								<?php $addonBasename = easingslider_get_addon_basename($addon->slug); ?>

								<?php if (is_plugin_active($addonBasename)) : ?>
									<div class="addon-status is-active">
										<span class="status-message"><?php _e('Status: Active', 'easingslider'); ?></span>
										<a href="#" data-plugin="<?php echo esc_attr($addonBasename); ?>" class="addon-action button-primary js-deactivate-addon"><?php _e('Deactivate Addon', 'easingslider'); ?></a>
									</div>
								<?php elseif ( ! isset($installedPlugins[$addonBasename])) : ?>
									<div class="addon-status not-installed">
										<span class="status-message"><?php _e('Status: Not Installed', 'easingslider'); ?></span>
										<a href="#" data-plugin="<?php echo esc_url($addon->download_link); ?>" class="addon-action button-primary js-install-addon"><?php _e('Install Addon', 'easingslider'); ?></a>
									</div>
								<?php elseif (is_plugin_inactive($addonBasename)) : ?>
									<div class="addon-status is-inactive">
										<span class="status-message"><?php _e('Status: Inactive', 'easingslider'); ?></span>
										<a href="#" data-plugin="<?php echo esc_attr($addonBasename); ?>" class="addon-action button-primary js-activate-addon"><?php _e('Activate Addon', 'easingslider'); ?></a>
									</div>
								<?php endif; ?>
							<?php else : ?>
								<div class="addon-status">
									<span><?php _e('Unavailable', 'easingslider'); ?></span>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<h3><?php _e('Oops!', 'easingslider'); ?></h3>
				<p><?php _e('Sorry, we couldn\'t retrieve the addons list. Instead, please view them on our website.', 'easingslider'); ?></p>
				<a href="<?php echo esc_attr($addonsLink); ?>" class="button-secondary" target="_blank"><?php _e('Browse All Addons', 'easingslider'); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
