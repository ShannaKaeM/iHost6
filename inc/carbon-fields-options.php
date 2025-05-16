<?php
/**
 * Carbon Fields options definitions for MIIHost Theme - MINIMAL TEST
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'carbon_fields_register_fields', 'miihost_minimal_test_fields' );
add_action( 'carbon_fields_register_fields', 'miihost_add_business_fields' );
add_action( 'carbon_fields_register_fields', 'miihost_add_article_fields' );
add_action( 'carbon_fields_register_fields', 'miihost_add_profile_fields' );

/**
 * Define Carbon Fields for Profile CPT.
 */
function miihost_add_profile_fields() {
    Container::make( 'post_meta', __( 'Profile Details', 'miihost' ) )
        ->where( 'post_type', '=', 'profile' )
        ->add_fields( array(
            Field::make( 'text', 'crb_profile_public_email', __( 'Public Email Address', 'miihost' ) )
                ->set_help_text(__( 'Optional public contact email for this profile.', 'miihost')),
            Field::make( 'text', 'crb_profile_phone', __( 'Phone Number', 'miihost' ) ),
            Field::make( 'text', 'crb_profile_user_type_id', __( 'User Type ID', 'miihost' ) )
                ->set_help_text(__( 'Internal ID for user type. May become a taxonomy/role link.', 'miihost')),
            Field::make( 'text', 'crb_profile_location_id', __( 'Location ID', 'miihost' ) )
                ->set_help_text(__( 'Internal ID for location. May become a taxonomy/relationship field.', 'miihost')),
            Field::make( 'textarea', 'crb_profile_public_bio', __( 'Public Bio', 'miihost' ) )
                ->set_help_text(__( 'A short public biography for display. The main content editor can be used for a longer version.', 'miihost')),
        ) );
    error_log('MIIHOST DEBUG: miihost_add_profile_fields function executed.');
}

/**
 * Define Carbon Fields for Article CPT.
 */
function miihost_add_article_fields() {
    Container::make( 'post_meta', __( 'Article Details', 'miihost' ) )
        ->where( 'post_type', '=', 'article' )
        ->add_fields( array(
            Field::make( 'text', 'crb_article_type_id', __( 'Article Type ID', 'miihost' ) )
                ->set_help_text(__( 'Enter the ID for the type of article (e.g., news, blog, feature). This may become a taxonomy later.', 'miihost')),
        ) );
    error_log('MIIHOST DEBUG: miihost_add_article_fields function executed.');
}

/**
 * Define Carbon Fields for Business CPT.
 */
function miihost_add_business_fields() {
    Container::make( 'post_meta', __( 'Business Details', 'miihost' ) )
        ->where( 'post_type', '=', 'business' )
        ->add_fields( array(
            Field::make( 'textarea', 'crb_business_address', __( 'Address', 'miihost' ) ),
            Field::make( 'text', 'crb_business_city', __( 'City', 'miihost' ) ),
            Field::make( 'text', 'crb_business_state', __( 'State', 'miihost' ) ),
            Field::make( 'text', 'crb_business_zip_code', __( 'Zip Code', 'miihost' ) ),
            Field::make( 'text', 'crb_business_phone', __( 'Phone Number', 'miihost' ) ),
            Field::make( 'text', 'crb_business_email', __( 'Email Address', 'miihost' ) ),
            Field::make( 'text', 'crb_business_website', __( 'Website URL', 'miihost' ) ),
            Field::make( 'textarea', 'crb_business_social_media', __( 'Social Media Links', 'miihost' ) )
                ->set_help_text( __( 'Enter one URL per line or a JSON structure.', 'miihost' ) ),
            Field::make( 'textarea', 'crb_business_hours', __( 'Operating Hours', 'miihost' ) ),
            Field::make( 'text', 'crb_business_latitude', __( 'Latitude', 'miihost' ) ),
            Field::make( 'text', 'crb_business_longitude', __( 'Longitude', 'miihost' ) ),
            Field::make( 'checkbox', 'crb_business_is_claimed', __( 'Is Business Claimed?', 'miihost' ) ),
            Field::make( 'checkbox', 'crb_business_is_featured', __( 'Is Featured Business?', 'miihost' ) ),
        ) );
    error_log('MIIHOST DEBUG: miihost_add_business_fields function executed.');
}
function miihost_minimal_test_fields() {
    Container::make( 'post_meta', __( 'Property Details Test', 'miihost' ) ) // Changed title for clarity
        ->where( 'post_type', '=', 'property' )
        ->add_fields( array(
            Field::make( 'text', 'crb_property_bedrooms_text', __( 'Bedrooms (Text Input)', 'miihost' ) )
                ->set_help_text( __( 'Number of bedrooms', 'miihost' ) ),
            Field::make( 'text', 'crb_property_bathrooms_text', __( 'Bathrooms (Text Input)', 'miihost' ) )
                ->set_help_text( __( 'Number of bathrooms', 'miihost' ) ),
            Field::make( 'text', 'crb_property_type_id', __( 'Property Type ID', 'miihost' ) ),
            Field::make( 'text', 'crb_location_id', __( 'Location ID', 'miihost' ) ),
            Field::make( 'textarea', 'crb_amenity_ids', __( 'Amenity IDs (comma-separated)', 'miihost' ) ),
            Field::make( 'text', 'crb_address', __( 'Address', 'miihost' ) ),
            Field::make( 'text', 'crb_city', __( 'City', 'miihost' ) ),
            Field::make( 'text', 'crb_state', __( 'State', 'miihost' ) ),
            Field::make( 'text', 'crb_zip_code', __( 'Zip Code', 'miihost' ) ),
            Field::make( 'text', 'crb_latitude', __( 'Latitude', 'miihost' ) ),
            Field::make( 'text', 'crb_longitude', __( 'Longitude', 'miihost' ) ),
            Field::make( 'text', 'crb_max_guests_text', __( 'Max Guests (Text)', 'miihost' ) ),
            Field::make( 'text', 'crb_nightly_rate_text', __( 'Nightly Rate (Text)', 'miihost' ) ),
            Field::make( 'text', 'crb_booking_url', __( 'Booking URL', 'miihost' ) ),
            Field::make( 'text', 'crb_ical_url', __( 'iCal URL', 'miihost' ) ),
            Field::make( 'checkbox', 'crb_has_direct_booking', __( 'Has Direct Booking?', 'miihost' ) ),
            Field::make( 'checkbox', 'crb_is_featured', __( 'Is Featured?', 'miihost' ) ),
        ) );
    error_log('MIIHOST DEBUG: miihost_minimal_test_fields function executed with all CSV fields (numeric as text).');
}

// Add a log to see if this file is loaded and action is added
error_log('MIIHOST DEBUG: carbon-fields-options.php loaded and miihost_minimal_test_fields action added.');


