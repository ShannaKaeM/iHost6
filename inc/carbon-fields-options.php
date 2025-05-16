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


