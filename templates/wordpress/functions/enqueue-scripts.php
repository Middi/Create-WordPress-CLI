<?php
/**
 * Enqueue script tags
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package wordpress_template_theme
 */

function wordpress_template_theme_scripts() {

	wp_enqueue_style('wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Lora:400,700&display=swap', false);
	
	wp_enqueue_style('wordpress-template-theme-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/style.css' ));

	wp_enqueue_script( 'wordpress-template-theme-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'wordpress-template-theme-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script('wordpress-template-theme-scripts', get_stylesheet_directory_uri() . '/assets/js/main.min.js', array('jquery'), filemtime( get_stylesheet_directory() . '/assets/js/main.min.js' )
	);


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'wordpress_template_theme_scripts' );