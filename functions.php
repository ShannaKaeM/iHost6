<?php

// Include the property import script
require_once get_stylesheet_directory() . '/inc/import-properties.php';

// Include the script to publish draft properties
require_once get_stylesheet_directory() . '/inc/publish-draft-properties.php';
// Load Composer's autoloader
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize Carbon Fields
// We'll hook this to 'after_setup_theme' to ensure WordPress is ready
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    \Carbon_Fields\Carbon_Fields::boot();
}

/**
 * MIIHost functions and definitions
 *
 * @package MIIHost
 */

if ( ! defined( 'MIIHOST_VERSION' ) ) {
	// Replace the version number as needed
	define( 'MIIHOST_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function miihost_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register menu locations
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'miihost' ),
			'footer'  => esc_html__( 'Footer Menu', 'miihost' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for custom colors.
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => esc_html__( 'Primary', 'miihost' ),
			'slug'  => 'primary',
			'color' => '#0073aa',
		),
		array(
			'name'  => esc_html__( 'Secondary', 'miihost' ),
			'slug'  => 'secondary',
			'color' => '#00a0d2',
		),
		array(
			'name'  => esc_html__( 'Dark Gray', 'miihost' ),
			'slug'  => 'dark-gray',
			'color' => '#333',
		),
		array(
			'name'  => esc_html__( 'Light Gray', 'miihost' ),
			'slug'  => 'light-gray',
			'color' => '#f8f8f8',
		),
		array(
			'name'  => esc_html__( 'White', 'miihost' ),
			'slug'  => 'white',
			'color' => '#fff',
		),
	) );
}
add_action( 'after_setup_theme', 'miihost_setup' );

/**
 * Set the content width in pixels, based on the theme's design.
 */
function miihost_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'miihost_content_width', 1200 );
}
add_action( 'after_setup_theme', 'miihost_content_width', 0 );

/**
 * Register widget area.
 */
function miihost_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'miihost' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'miihost' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Home Widgets', 'miihost' ),
			'id'            => 'home-widgets',
			'description'   => esc_html__( 'Add widgets to the homepage.', 'miihost' ),
			'before_widget' => '<div id="%1$s" class="widget home-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'miihost_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function miihost_scripts() {
	wp_enqueue_style( 'miihost-style', get_stylesheet_uri(), array(), MIIHOST_VERSION );
	wp_enqueue_script( 'miihost-navigation', get_template_directory_uri() . '/js/navigation.js', array(), MIIHOST_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'miihost_scripts' );

/**
 * Load Custom Post Type definitions.
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Load Carbon Fields options.
 */
require get_template_directory() . '/inc/carbon-fields-options.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
