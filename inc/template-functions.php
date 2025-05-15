<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package MIIHost
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function miihost_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'miihost_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function miihost_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'miihost_pingback_header' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function miihost_post_classes( $classes ) {
	// Add a class for styling posts
	$classes[] = 'miihost-post';
	
	return $classes;
}
add_filter( 'post_class', 'miihost_post_classes' );

/**
 * Change the excerpt length
 */
function miihost_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'miihost_excerpt_length' );

/**
 * Change the excerpt more string
 */
function miihost_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'miihost_excerpt_more' );
