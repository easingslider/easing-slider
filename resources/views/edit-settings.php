<div class="wrap">
	<h1 class="nav-tab-wrapper">
		<?php
			foreach ($tabs as $tabID => $tab_name) :

				// Get tab URL
				$tab_url = add_query_arg(array(
					'tab' => $tabID
				));

				// Print the tab
				?>
					<a href="<?php echo esc_url($tab_url); ?>" title="<?php echo esc_attr($tab_name); ?>'" class="nav-tab <?php if ($activeTab == $tabID) { ?>nav-tab-active<?php } ?>">
						<?php echo esc_html($tab_name); ?>
					</a>
				<?php

			endforeach;
		?>
	</h1>

	<?php settings_errors(); ?>

	<div id="tab_container">
		<form method="post" action="options.php">
			<table class="form-table">
				<?php
					settings_fields($optionName);
					do_settings_fields("{$optionName}_{$activeTab}", "easingslider_settings_{$activeTab}");
				?>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
</div>
