<?php
/**
 * wordpress template theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wordpress_template_theme
 */


/**
 * Sets up theme support
 */
require get_template_directory() . '/functions/theme-support.php';

/**
 * Theme width support
 */
require get_template_directory() . '/functions/theme-width.php';

/**
 * Register widget area.
 */
require get_template_directory() . '/functions/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/functions/enqueue-scripts.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/functions/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/functions/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/functions/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/functions/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/functions/jetpack.php';
}

