<?php
/**
 * Functions for importing properties from a CSV file.
 */

// Function to find an attachment by filename
if ( ! function_exists( 'miihost_get_attachment_id_by_filename' ) ) {
    function miihost_get_attachment_id_by_filename( $filename ) {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid LIKE %s",
            '%' . $wpdb->esc_like( $filename )
        );
        $attachment_id = $wpdb->get_var( $query );
        return $attachment_id;
    }
}

if ( ! function_exists( 'miihost_import_csv_properties' ) ) {
    function miihost_import_csv_properties() {
        // Check if the import has already run
        if ( get_option( 'miihost_properties_imported_from_csv' ) ) {
            error_log('MIIHOST DEBUG: Property import already run. Skipping.');
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::warning( 'Properties have already been imported.' );
            } else {
                echo '<p>Properties have already been imported. To run again, delete the `miihost_properties_imported_from_csv` option.</p>';
            }
            return;
        }

        error_log('MIIHOST DEBUG: Starting property import from CSV.');

        $csv_file_path = ABSPATH . '../../../iHostDocs/site data/Cleaned_Properties_Data.csv'; // Corrected path using ABSPATH - Attempt 2 using ABSPATH

        if ( ! file_exists( $csv_file_path ) ) {
            error_log('MIIHOST ERROR: CSV file not found at ' . $csv_file_path);
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::error( 'CSV file not found: ' . $csv_file_path );
            } else {
                echo '<p>ERROR: CSV file not found at ' . esc_html($csv_file_path) . '</p>';
            }
            return;
        }

        $file_handle = fopen( $csv_file_path, 'r' );
        if ( ! $file_handle ) {
            error_log('MIIHOST ERROR: Could not open CSV file.');
             if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::error( 'Could not open CSV file.' );
            } else {
                echo '<p>ERROR: Could not open CSV file.</p>';
            }
            return;
        }

        $header = fgetcsv( $file_handle ); // Skip header row
        if (!$header) {
            error_log('MIIHOST ERROR: Could not read CSV header.');
            fclose($file_handle);
            return;
        }
        
        // Map CSV headers to array keys for easier access
        // title,slug,description,user_id,property_type_id,location_id,amenity_ids,address,city,state,zip_code,latitude,longitude,bedrooms,bathrooms,max_guests,nightly_rate,booking_url,ical_url,has_direct_booking,status,is_featured,views,featured_image
        $expected_headers = ['title','slug','description','user_id','property_type_id','location_id','amenity_ids','address','city','state','zip_code','latitude','longitude','bedrooms','bathrooms','max_guests','nightly_rate','booking_url','ical_url','has_direct_booking','status','is_featured','views','featured_image'];
        // Basic validation of headers
        if (count($header) !== count($expected_headers)) {
             error_log('MIIHOST ERROR: CSV header count mismatch. Expected ' . count($expected_headers) . ', got ' . count($header));
             fclose($file_handle);
             return;
        }


        $property_counter = 0;
        $imported_count = 0;
        $admin_user_id = 1; // Assign posts to admin user ID 1

        while ( ( $row = fgetcsv( $file_handle ) ) !== false ) {
            $property_counter++;
            $data = array_combine( $header, $row );

            $post_title = !empty($data['title']) ? sanitize_text_field($data['title']) : 'Property ' . $property_counter;
            $post_slug = !empty($data['slug']) ? sanitize_title($data['slug']) : sanitize_title($post_title);
            $post_content = !empty($data['description']) ? wp_kses_post($data['description']) : '';
            $post_status = !empty($data['status']) ? sanitize_text_field($data['status']) : 'publish'; // Default to publish

            // Check if post with this slug already exists to avoid duplicates if script is partially run
            $existing_post = get_page_by_path($post_slug, OBJECT, 'property');
            if ($existing_post) {
                error_log('MIIHOST DEBUG: Property with slug ' . $post_slug . ' already exists. Skipping.');
                continue;
            }

            $post_args = array(
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_status'  => $post_status,
                'post_type'    => 'property',
                'post_author'  => $admin_user_id,
                'post_name'    => $post_slug,
            );

            $post_id = wp_insert_post( $post_args );

            if ( is_wp_error( $post_id ) ) {
                error_log('MIIHOST ERROR: Failed to insert post for ' . $post_title . ': ' . $post_id->get_error_message());
                continue;
            }

            error_log('MIIHOST DEBUG: Imported property: ' . $post_title . ' (ID: ' . $post_id . ')');

            // Update Carbon Fields meta data
            carbon_set_post_meta( $post_id, 'crb_property_type_id', sanitize_text_field($data['property_type_id']) );
            carbon_set_post_meta( $post_id, 'crb_location_id', sanitize_text_field($data['location_id']) );
            carbon_set_post_meta( $post_id, 'crb_amenity_ids', sanitize_textarea_field($data['amenity_ids']) );
            carbon_set_post_meta( $post_id, 'crb_address', sanitize_text_field($data['address']) );
            carbon_set_post_meta( $post_id, 'crb_city', sanitize_text_field($data['city']) );
            carbon_set_post_meta( $post_id, 'crb_state', sanitize_text_field($data['state']) );
            carbon_set_post_meta( $post_id, 'crb_zip_code', sanitize_text_field($data['zip_code']) );
            carbon_set_post_meta( $post_id, 'crb_latitude', sanitize_text_field($data['latitude']) );
            carbon_set_post_meta( $post_id, 'crb_longitude', sanitize_text_field($data['longitude']) );
            carbon_set_post_meta( $post_id, 'crb_property_bedrooms_text', sanitize_text_field($data['bedrooms']) );
            carbon_set_post_meta( $post_id, 'crb_property_bathrooms_text', sanitize_text_field($data['bathrooms']) );
            carbon_set_post_meta( $post_id, 'crb_max_guests_text', sanitize_text_field($data['max_guests']) );
            carbon_set_post_meta( $post_id, 'crb_nightly_rate_text', sanitize_text_field($data['nightly_rate']) );
            carbon_set_post_meta( $post_id, 'crb_booking_url', esc_url_raw($data['booking_url']) );
            carbon_set_post_meta( $post_id, 'crb_ical_url', esc_url_raw($data['ical_url']) );
            carbon_set_post_meta( $post_id, 'crb_has_direct_booking', $data['has_direct_booking'] === '1' ? true : false );
            carbon_set_post_meta( $post_id, 'crb_is_featured', $data['is_featured'] === '1' ? true : false );
            carbon_set_post_meta( $post_id, 'crb_views_text', sanitize_text_field($data['views']) );

            // Set featured image
            $featured_image_filename = 'property-featured-' . $property_counter . '.jpg'; // Assumes .jpg, adjust if needed
            $attachment_id = miihost_get_attachment_id_by_filename( $featured_image_filename );
            if ( $attachment_id ) {
                set_post_thumbnail( $post_id, $attachment_id );
                error_log('MIIHOST DEBUG: Set featured image ' . $featured_image_filename . ' for post ID ' . $post_id);
            } else {
                error_log('MIIHOST WARNING: Featured image ' . $featured_image_filename . ' not found in Media Library for post ID ' . $post_id);
            }
            $imported_count++;
        }

        fclose( $file_handle );

        // Mark import as complete
        update_option( 'miihost_properties_imported_from_csv', true );
        error_log('MIIHOST DEBUG: Property import completed. Imported ' . $imported_count . ' properties.');
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::success( 'Properties imported successfully: ' . $imported_count );
        } else {
             echo '<p>Properties imported successfully! Imported ' . esc_html($imported_count) . ' properties.</p>';
        }
    }
}

/**
 * Action to trigger the import.
 * You can trigger this by visiting your admin dashboard and adding ?miihost_do_property_import=true to the URL.
 * Example: wp-admin/index.php?miihost_do_property_import=true
 *
 * IMPORTANT: Remove or comment out this action hook after the import is successfully completed
 * to prevent accidental re-triggering or performance overhead.
 */
// add_action( 'admin_init', 'miihost_trigger_property_import' ); // Trigger disabled after initial import
function miihost_trigger_property_import() {
    if ( isset( $_GET['miihost_do_property_import'] ) && $_GET['miihost_do_property_import'] === 'true' ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }
        miihost_import_csv_properties();
        // It's good practice to redirect or remove the query arg to prevent re-running on refresh
        // For simplicity here, we rely on the get_option check within the import function.
        // You might want to wp_redirect( admin_url( 'index.php' ) ); exit; after successful import.
    }
}

// // If you prefer to use WP-CLI, you can register a command like this:
// if ( defined( 'WP_CLI' ) && WP_CLI ) {
//     /**
//      * Imports properties from a CSV file.
//      */
//     class MIIHost_Property_Import_Command extends WP_CLI_Command {
//         public function __invoke( $args, $assoc_args ) {
//             miihost_import_csv_properties();
//         }
//     }
//     WP_CLI::add_command( 'miihost import-properties', 'MIIHost_Property_Import_Command' );
// }

?>
