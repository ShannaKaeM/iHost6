<?php
/**
 * Functions for importing Businesses from a CSV file.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'init', 'miihost_trigger_business_import' );

function miihost_trigger_business_import() {
    if ( isset( $_GET['miihost_do_business_import'] ) && $_GET['miihost_do_business_import'] === 'true' ) {
        // Verify nonce
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'miihost_business_import_nonce' ) ) {
            wp_die( 'Nonce verification failed. Please try again.' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }

        miihost_import_businesses_from_csv();
        // Optional: Redirect or display a message
        // wp_redirect( admin_url( 'edit.php?post_type=business&imported=true' ) );
        // exit;
        echo 'Business import process triggered. Check logs or business list for results.<br>';
        echo '<a href="' . esc_url(admin_url('edit.php?post_type=business')) . '">View Businesses</a>';

    }
}

function miihost_get_business_import_trigger_url() {
    $nonce = wp_create_nonce( 'miihost_business_import_nonce' );
    return add_query_arg( array(
        'miihost_do_business_import' => 'true',
        '_wpnonce' => $nonce
    ), home_url('/') );
}

// You can add a button or link in the admin area if desired, for example:
// add_action('admin_notices', function() {
//     if (current_user_can('manage_options')) {
//         echo '<div class="notice notice-info is-dismissible"><p>To import businesses, <a href="' . esc_url(miihost_get_business_import_trigger_url()) . '">click here</a>. (This message will be removed after testing the import.)</p></div>';
//     }
// });

function miihost_import_businesses_from_csv() {
    $csv_file_path = get_theme_file_path( '../../../../../../iHostDocs/site data/Businesses_Data__Trimmed_Final_.csv' ); // Corrected path

    if ( ! file_exists( $csv_file_path ) ) {
        error_log( 'MIIHOST DEBUG: Business CSV file not found at ' . $csv_file_path );
        echo 'ERROR: Business CSV file not found.<br>';
        return;
    }

    if ( ( $handle = fopen( $csv_file_path, 'r' ) ) !== false ) {
        $header = fgetcsv( $handle ); // Read and discard header row
        if ($header === false) {
            error_log( 'MIIHOST DEBUG: Business CSV file is empty or header could not be read.' );
            echo 'ERROR: Business CSV file is empty or header could not be read.<br>';
            fclose( $handle );
            return;
        }

        $row_count = 0;
        $imported_count = 0;
        $skipped_count = 0;

        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            $row_count++;

            // Map CSV columns to variables (adjust indices as per your CSV structure)
            // id 2,name,slug,description,short_description,location_id,business_type_id,user_id,address,city,state,zip_code,phone,email,website,social_media,hours,latitude,longitude,is_claimed,status,is_featured
            $business_name        = !empty($row[1]) ? sanitize_text_field( $row[1] ) : '';
            $description          = !empty($row[3]) ? wp_kses_post( $row[3] ) : '';
            $short_description    = !empty($row[4]) ? wp_kses_post( $row[4] ) : '';
            $address              = !empty($row[8]) ? sanitize_textarea_field( $row[8] ) : '';
            $city                 = !empty($row[9]) ? sanitize_text_field( $row[9] ) : '';
            $state                = !empty($row[10]) ? sanitize_text_field( $row[10] ) : '';
            $zip_code             = !empty($row[11]) ? sanitize_text_field( $row[11] ) : '';
            $phone                = !empty($row[12]) ? sanitize_text_field( $row[12] ) : '';
            $email                = !empty($row[13]) ? sanitize_email( $row[13] ) : '';
            $website              = !empty($row[14]) ? esc_url_raw( $row[14] ) : '';
            $social_media         = !empty($row[15]) ? sanitize_textarea_field( $row[15] ) : '';
            $hours                = !empty($row[16]) ? sanitize_textarea_field( $row[16] ) : '';
            $latitude             = !empty($row[17]) ? sanitize_text_field( $row[17] ) : '';
            $longitude            = !empty($row[18]) ? sanitize_text_field( $row[18] ) : '';
            $is_claimed           = !empty($row[19]) && ($row[19] == '1' || strtolower($row[19]) === 'true');
            $post_status          = !empty($row[20]) ? sanitize_key( $row[20] ) : 'draft'; // Default to draft if status is empty
            $is_featured          = !empty($row[21]) && ($row[21] == '1' || strtolower($row[21]) === 'true');

            if ( empty( $business_name ) ) {
                error_log( "MIIHOST DEBUG: Skipped row {$row_count} due to empty business name." );
                $skipped_count++;
                continue;
            }

            // Check if business already exists by title to avoid duplicates
            $existing_business = get_page_by_title( $business_name, OBJECT, 'business' );
            if ( $existing_business ) {
                error_log( "MIIHOST DEBUG: Business '{$business_name}' already exists. Skipped." );
                $skipped_count++;
                continue;
            }

            $post_data = array(
                'post_title'   => $business_name,
                'post_content' => $description,
                'post_excerpt' => $short_description,
                'post_status'  => $post_status,
                'post_type'    => 'business',
                'post_author'  => get_current_user_id() ?: 1, // Assign to current user or admin
            );

            $post_id = wp_insert_post( $post_data );

            if ( is_wp_error( $post_id ) ) {
                error_log( "MIIHOST DEBUG: Error inserting business '{$business_name}': " . $post_id->get_error_message() );
                $skipped_count++;
                continue;
            }

            // Save Carbon Fields data
            carbon_set_post_meta( $post_id, 'crb_business_address', $address );
            carbon_set_post_meta( $post_id, 'crb_business_city', $city );
            carbon_set_post_meta( $post_id, 'crb_business_state', $state );
            carbon_set_post_meta( $post_id, 'crb_business_zip_code', $zip_code );
            carbon_set_post_meta( $post_id, 'crb_business_phone', $phone );
            carbon_set_post_meta( $post_id, 'crb_business_email', $email );
            carbon_set_post_meta( $post_id, 'crb_business_website', $website );
            carbon_set_post_meta( $post_id, 'crb_business_social_media', $social_media );
            carbon_set_post_meta( $post_id, 'crb_business_hours', $hours );
            carbon_set_post_meta( $post_id, 'crb_business_latitude', $latitude );
            carbon_set_post_meta( $post_id, 'crb_business_longitude', $longitude );
            carbon_set_post_meta( $post_id, 'crb_business_is_claimed', $is_claimed );
            carbon_set_post_meta( $post_id, 'crb_business_is_featured', $is_featured );

            error_log( "MIIHOST DEBUG: Successfully imported business '{$business_name}' (ID: {$post_id})." );
            $imported_count++;
        }

        fclose( $handle );
        error_log( "MIIHOST DEBUG: Business import complete. Total rows: {$row_count}, Imported: {$imported_count}, Skipped: {$skipped_count}." );
        echo "Business import complete. Total rows processed: {$row_count}, Imported: {$imported_count}, Skipped: {$skipped_count}.<br>";
    } else {
        error_log( 'MIIHOST DEBUG: Could not open business CSV file.' );
        echo 'ERROR: Could not open business CSV file.<br>';
    }
}

?>
