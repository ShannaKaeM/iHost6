<?php
/**
 * Custom Post Type and Taxonomy definitions for MIIHost Theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Property Custom Post Type and Taxonomies.
 */
function miihost_register_property_cpt_and_taxonomies() {

	// Define labels for Property CPT
	$property_labels = array(
		'name'                  => _x( 'Properties', 'Post Type General Name', 'miihost' ),
		'singular_name'         => _x( 'Property', 'Post Type Singular Name', 'miihost' ),
		'menu_name'             => __( 'Properties', 'miihost' ),
		'name_admin_bar'        => __( 'Property', 'miihost' ),
		'archives'              => __( 'Property Archives', 'miihost' ),
		'attributes'            => __( 'Property Attributes', 'miihost' ),
		'parent_item_colon'     => __( 'Parent Property:', 'miihost' ),
		'all_items'             => __( 'All Properties', 'miihost' ),
		'add_new_item'          => __( 'Add New Property', 'miihost' ),
		'add_new'               => __( 'Add New', 'miihost' ),
		'new_item'              => __( 'New Property', 'miihost' ),
		'edit_item'             => __( 'Edit Property', 'miihost' ),
		'update_item'           => __( 'Update Property', 'miihost' ),
		'view_item'             => __( 'View Property', 'miihost' ),
		'view_items'            => __( 'View Properties', 'miihost' ),
		'search_items'          => __( 'Search Property', 'miihost' ),
		'not_found'             => __( 'Not found', 'miihost' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'miihost' ),
		'featured_image'        => __( 'Featured Image', 'miihost' ),
		'set_featured_image'    => __( 'Set featured image', 'miihost' ),
		'remove_featured_image' => __( 'Remove featured image', 'miihost' ),
		'use_featured_image'    => __( 'Use as featured image', 'miihost' ),
		'insert_into_item'      => __( 'Insert into property', 'miihost' ),
		'uploaded_to_this_item' => __( 'Uploaded to this property', 'miihost' ),
		'items_list'            => __( 'Properties list', 'miihost' ),
		'items_list_navigation' => __( 'Properties list navigation', 'miihost' ),
		'filter_items_list'     => __( 'Filter properties list', 'miihost' ),
	);
	$property_args = array(
		'label'                 => __( 'Property', 'miihost' ),
		'description'           => __( 'Custom post type for properties', 'miihost' ),
		'labels'                => $property_labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5, // Below Posts
		'menu_icon'             => 'dashicons-admin-home',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'properties', // Archive slug will be /properties/
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'property', 'with_front' => true ), // Single post slug will be /property/your-property-slug/
		'show_in_rest'          => true, // Enable Gutenberg editor and REST API support
	);
	register_post_type( 'property', $property_args );

	// Define labels for Property Categories Taxonomy
	$category_labels = array(
		'name'                       => _x( 'Property Categories', 'Taxonomy General Name', 'miihost' ),
		'singular_name'              => _x( 'Property Category', 'Taxonomy Singular Name', 'miihost' ),
		'menu_name'                  => __( 'Property Categories', 'miihost' ),
		'all_items'                  => __( 'All Categories', 'miihost' ),
		'parent_item'                => __( 'Parent Category', 'miihost' ),
		'parent_item_colon'          => __( 'Parent Category:', 'miihost' ),
		'new_item_name'              => __( 'New Category Name', 'miihost' ),
		'add_new_item'               => __( 'Add New Category', 'miihost' ),
		'edit_item'                  => __( 'Edit Category', 'miihost' ),
		'update_item'                => __( 'Update Category', 'miihost' ),
		'view_item'                  => __( 'View Category', 'miihost' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'miihost' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'miihost' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'miihost' ),
		'popular_items'              => __( 'Popular Categories', 'miihost' ),
		'search_items'               => __( 'Search Categories', 'miihost' ),
		'not_found'                  => __( 'Not Found', 'miihost' ),
		'no_terms'                   => __( 'No categories', 'miihost' ),
		'items_list'                 => __( 'Categories list', 'miihost' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'miihost' ),
	);
	$category_args = array(
		'labels'            => $category_labels,
		'hierarchical'      => true, // True for category-style taxonomy
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'rewrite'           => array( 'slug' => 'property-category' ), // URL slug for this taxonomy
		'show_in_rest'      => true, // Enable REST API support
	);
	register_taxonomy( 'property_category', array( 'property' ), $category_args ); // Associate with 'property' CPT

}
add_action( 'init', 'miihost_register_property_cpt_and_taxonomies' );

/**
 * Flush rewrite rules on theme activation/deactivation for CPTs and Taxonomies.
 * This is important to ensure the new permalinks work immediately.
 */
function miihost_rewrite_flush() {
    miihost_register_property_cpt_and_taxonomies(); // Ensure CPTs/Taxonomies are registered before flushing
    flush_rewrite_rules();
}
// register_activation_hook( __FILE__, 'miihost_rewrite_flush' ); // This might not work correctly if __FILE__ is in an included file. Better to run manually or on theme switch.

// A more reliable way to flush rules on theme activation if this file is in theme root or /inc
// If this file itself is the one being activated (not the case here), __FILE__ is fine.
// For theme activation, this hook is usually in functions.php or a file directly included by it early.
// Consider a manual flush for now or moving the activation hook logic.
// For now, we will rely on you visiting Settings > Permalinks once to refresh.
