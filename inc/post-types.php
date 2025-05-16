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
 * Register Additional Custom Post Types: Businesses, Articles, Profiles.
 */
function miihost_register_additional_cpts() {

    // --- Business CPT ---
    $business_labels = array(
        'name'                  => _x( 'Businesses', 'Post Type General Name', 'miihost' ),
        'singular_name'         => _x( 'Business', 'Post Type Singular Name', 'miihost' ),
        'menu_name'             => __( 'Businesses', 'miihost' ),
        'name_admin_bar'        => __( 'Business', 'miihost' ),
        'archives'              => __( 'Business Archives', 'miihost' ),
		'attributes'            => __( 'Business Attributes', 'miihost' ),
		'parent_item_colon'     => __( 'Parent Business:', 'miihost' ),
		'all_items'             => __( 'All Businesses', 'miihost' ),
		'add_new_item'          => __( 'Add New Business', 'miihost' ),
		'add_new'               => __( 'Add New', 'miihost' ),
		'new_item'              => __( 'New Business', 'miihost' ),
		'edit_item'             => __( 'Edit Business', 'miihost' ),
		'update_item'           => __( 'Update Business', 'miihost' ),
		'view_item'             => __( 'View Business', 'miihost' ),
		'view_items'            => __( 'View Businesses', 'miihost' ),
		'search_items'          => __( 'Search Business', 'miihost' ),
		'not_found'             => __( 'Not found', 'miihost' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'miihost' ),
		'featured_image'        => __( 'Featured Image', 'miihost' ),
		'set_featured_image'    => __( 'Set featured image', 'miihost' ),
		'remove_featured_image' => __( 'Remove featured image', 'miihost' ),
		'use_featured_image'    => __( 'Use as featured image', 'miihost' ),
		'insert_into_item'      => __( 'Insert into business', 'miihost' ),
		'uploaded_to_this_item' => __( 'Uploaded to this business', 'miihost' ),
		'items_list'            => __( 'Businesses list', 'miihost' ),
		'items_list_navigation' => __( 'Businesses list navigation', 'miihost' ),
		'filter_items_list'     => __( 'Filter businesses list', 'miihost' ),
    );
    $business_args = array(
        'label'                 => __( 'Business', 'miihost' ),
        'description'           => __( 'Custom post type for businesses', 'miihost' ),
        'labels'                => $business_labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-store',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'businesses',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'business', 'with_front' => true ),
        'show_in_rest'          => true,
    );
    register_post_type( 'business', $business_args );

    // --- Article CPT ---
    $article_labels = array(
        'name'                  => _x( 'Articles', 'Post Type General Name', 'miihost' ),
        'singular_name'         => _x( 'Article', 'Post Type Singular Name', 'miihost' ),
        'menu_name'             => __( 'Articles', 'miihost' ),
        'name_admin_bar'        => __( 'Article', 'miihost' ),
        'archives'              => __( 'Article Archives', 'miihost' ),
		'attributes'            => __( 'Article Attributes', 'miihost' ),
		'parent_item_colon'     => __( 'Parent Article:', 'miihost' ),
		'all_items'             => __( 'All Articles', 'miihost' ),
		'add_new_item'          => __( 'Add New Article', 'miihost' ),
		'add_new'               => __( 'Add New', 'miihost' ),
		'new_item'              => __( 'New Article', 'miihost' ),
		'edit_item'             => __( 'Edit Article', 'miihost' ),
		'update_item'           => __( 'Update Article', 'miihost' ),
		'view_item'             => __( 'View Article', 'miihost' ),
		'view_items'            => __( 'View Articles', 'miihost' ),
		'search_items'          => __( 'Search Article', 'miihost' ),
		'not_found'             => __( 'Not found', 'miihost' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'miihost' ),
		'featured_image'        => __( 'Featured Image', 'miihost' ),
		'set_featured_image'    => __( 'Set featured image', 'miihost' ),
		'remove_featured_image' => __( 'Remove featured image', 'miihost' ),
		'use_featured_image'    => __( 'Use as featured image', 'miihost' ),
		'insert_into_item'      => __( 'Insert into article', 'miihost' ),
		'uploaded_to_this_item' => __( 'Uploaded to this article', 'miihost' ),
		'items_list'            => __( 'Articles list', 'miihost' ),
		'items_list_navigation' => __( 'Articles list navigation', 'miihost' ),
		'filter_items_list'     => __( 'Filter articles list', 'miihost' ),
    );
    $article_args = array(
        'label'                 => __( 'Article', 'miihost' ),
        'description'           => __( 'Custom post type for articles', 'miihost' ),
        'labels'                => $article_labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-media-document',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'articles',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'article', 'with_front' => true ),
        'show_in_rest'          => true,
    );
    register_post_type( 'article', $article_args );

    // --- Profile CPT ---
    $profile_labels = array(
        'name'                  => _x( 'Profiles', 'Post Type General Name', 'miihost' ),
        'singular_name'         => _x( 'Profile', 'Post Type Singular Name', 'miihost' ),
        'menu_name'             => __( 'Profiles', 'miihost' ),
        'name_admin_bar'        => __( 'Profile', 'miihost' ),
        'archives'              => __( 'Profile Archives', 'miihost' ),
		'attributes'            => __( 'Profile Attributes', 'miihost' ),
		'parent_item_colon'     => __( 'Parent Profile:', 'miihost' ),
		'all_items'             => __( 'All Profiles', 'miihost' ),
		'add_new_item'          => __( 'Add New Profile', 'miihost' ),
		'add_new'               => __( 'Add New', 'miihost' ),
		'new_item'              => __( 'New Profile', 'miihost' ),
		'edit_item'             => __( 'Edit Profile', 'miihost' ),
		'update_item'           => __( 'Update Profile', 'miihost' ),
		'view_item'             => __( 'View Profile', 'miihost' ),
		'view_items'            => __( 'View Profiles', 'miihost' ),
		'search_items'          => __( 'Search Profile', 'miihost' ),
		'not_found'             => __( 'Not found', 'miihost' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'miihost' ),
		'featured_image'        => __( 'Featured Image', 'miihost' ),
		'set_featured_image'    => __( 'Set featured image', 'miihost' ),
		'remove_featured_image' => __( 'Remove featured image', 'miihost' ),
		'use_featured_image'    => __( 'Use as featured image', 'miihost' ),
		'insert_into_item'      => __( 'Insert into profile', 'miihost' ),
		'uploaded_to_this_item' => __( 'Uploaded to this profile', 'miihost' ),
		'items_list'            => __( 'Profiles list', 'miihost' ),
		'items_list_navigation' => __( 'Profiles list navigation', 'miihost' ),
		'filter_items_list'     => __( 'Filter profiles list', 'miihost' ),
    );
    $profile_args = array(
        'label'                 => __( 'Profile', 'miihost' ),
        'description'           => __( 'Custom post type for user profiles', 'miihost' ),
        'labels'                => $profile_labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 8,
        'menu_icon'             => 'dashicons-admin-users',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'profiles',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'profile', 'with_front' => true ),
        'show_in_rest'          => true,
    );
    register_post_type( 'profile', $profile_args );

} // End miihost_register_additional_cpts()
add_action( 'init', 'miihost_register_additional_cpts' );

/**
 * Flush rewrite rules on theme activation/deactivation for CPTs and Taxonomies.
 * This is important to ensure the new permalinks work immediately.
 */
function miihost_rewrite_flush() {
    miihost_register_property_cpt_and_taxonomies(); // Ensure Property CPT/Taxonomies are registered
    miihost_register_additional_cpts(); // Ensure additional CPTs are registered
    flush_rewrite_rules();
}
// register_activation_hook( __FILE__, 'miihost_rewrite_flush' ); // This might not work correctly if __FILE__ is in an included file. Better to run manually or on theme switch.

// A more reliable way to flush rules on theme activation if this file is in theme root or /inc
// If this file itself is the one being activated (not the case here), __FILE__ is fine.
// For theme activation, this hook is usually in functions.php or a file directly included by it early.
// Consider a manual flush for now or moving the activation hook logic.
// For now, we will rely on you visiting Settings > Permalinks once to refresh.
