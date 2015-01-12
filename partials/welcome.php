<div class="wrap about-wrap">
	<h1><?php printf( __( 'Welcome to Easing Slider %s', 'easingslider' ), $version ); ?></h1>
	<div class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Easing Slider %s is ready to help you create beautiful sliders easily, quickly and for free!', 'easingslider' ), $version ); ?></div>
	<div class="easingslider-badge"><?php printf( __( 'Version %s', 'easingslider' ), $version ); ?></div>

    <h2 class="nav-tab-wrapper">
        <?php foreach ( $tabs as $slug => $tab ) : ?>
            <a href="index.php?page=easingslider-<?php echo $tab['slug']; ?>" class="nav-tab <?php echo ( $current_tab == $slug ) ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $tab['title'] ); ?></a>
        <?php endforeach; ?>
    </h2>

    <?php
    	/**
    	 * Load the appropraite subview
    	 */
    	require plugin_dir_path( __FILE__ ) . "welcome_{$current_tab}.php";
    ?>
</div>