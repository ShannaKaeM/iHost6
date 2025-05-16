<?php
/**
 * Functions for importing Profiles from a CSV file.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'init', 'miihost_trigger_profile_import' );

function miihost_trigger_profile_import() {
    if ( isset( $_GET['miihost_do_profile_import'] ) && $_GET['miihost_do_profile_import'] === 'true' ) {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'miihost_profile_import_nonce' ) ) {
            wp_die( 'Nonce verification failed. Please try again.' );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }
        miihost_import_profiles_from_csv();
        echo 'Profile import process triggered. Check logs or profile list for results.<br>';
        echo '<a href="' . esc_url(admin_url('edit.php?post_type=profile')) . '">View Profiles</a>';
    }
}

function miihost_get_profile_import_trigger_url() {
    $nonce = wp_create_nonce( 'miihost_profile_import_nonce' );
    return add_query_arg( array(
        'miihost_do_profile_import' => 'true',
        '_wpnonce' => $nonce
    ), home_url('/') );
}

// To display an admin notice with the import link, uncomment the following lines.
// add_action('admin_notices', function() {
//     if (current_user_can('manage_options')) {
//         echo '<div class="notice notice-info is-dismissible"><p>To import profiles, <a href="' . esc_url(miihost_get_profile_import_trigger_url()) . '">click here</a>. (This message will be removed after testing the import.)</p></div>';
//     }
// });

function miihost_import_profiles_from_csv() {
    $csv_file_path = get_theme_file_path( '../../../../../../iHostDocs/site data/Users_Data__No_id_2_.csv' );

    if ( ! file_exists( $csv_file_path ) ) {
        error_log( 'MIIHOST DEBUG: Profile CSV file not found at ' . $csv_file_path );
        echo 'ERROR: Profile CSV file not found.<br>';
        return;
    }

    if ( ( $handle = fopen( $csv_file_path, 'r' ) ) !== false ) {
        $header = fgetcsv( $handle ); // Read and discard header row
        if ($header === false) {
            error_log( 'MIIHOST DEBUG: Profile CSV file is empty or header could not be read.' );
            echo 'ERROR: Profile CSV file is empty or header could not be read.<br>';
            fclose( $handle );
            return;
        }

        $row_count = 0;
        $imported_count = 0;
        $skipped_count = 0;

        // CSV: name,email,user_type_id,location_id,email_verified_at,password,remember_token,phone,bio,profile_image
        // Index: 0    1       2             3           4                  5         6               7      8       9
        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            $row_count++;

            $profile_name       = !empty($row[0]) ? sanitize_text_field( $row[0] ) : '';
            $profile_email      = !empty($row[1]) ? sanitize_email( $row[1] ) : '';
            $user_type_id_val   = !empty($row[2]) ? sanitize_text_field( $row[2] ) : '';
            $location_id_val    = !empty($row[3]) ? sanitize_text_field( $row[3] ) : '';
            // email_verified_at ($row[4]), password ($row[5]), remember_token ($row[6]) are skipped
            $profile_phone      = !empty($row[7]) ? sanitize_text_field( $row[7] ) : '';
            $profile_bio        = !empty($row[8]) ? wp_kses_post( $row[8] ) : '';
            // profile_image ($row[9]) is skipped

            if ( empty( $profile_name ) ) {
                error_log( "MIIHOST DEBUG: Skipped profile row {$row_count} due to empty name." );
                $skipped_count++;
                continue;
            }

            // Check if profile already exists by title
            $existing_profile = get_page_by_title( $profile_name, OBJECT, 'profile' );
            if ( $existing_profile ) {
                error_log( "MIIHOST DEBUG: Profile '{$profile_name}' already exists. Skipped." );
                $skipped_count++;
                continue;
            }

            $post_data = array(
                'post_title'   => $profile_name,
                'post_content' => $profile_bio, // Using bio for main content
                'post_status'  => 'publish',   // Defaulting to publish
                'post_type'    => 'profile',
                'post_author'  => get_current_user_id() ?: 1, // Assign to current user or admin
            );

            $post_id = wp_insert_post( $post_data );

            if ( is_wp_error( $post_id ) ) {
                error_log( "MIIHOST DEBUG: Error inserting profile '{$profile_name}': " . $post_id->get_error_message() );
                $skipped_count++;
                continue;
            }

            // Save Carbon Fields data
            carbon_set_post_meta( $post_id, 'crb_profile_public_email', $profile_email );
            carbon_set_post_meta( $post_id, 'crb_profile_phone', $profile_phone );
            carbon_set_post_meta( $post_id, 'crb_profile_user_type_id', $user_type_id_val );
            carbon_set_post_meta( $post_id, 'crb_profile_location_id', $location_id_val );
            // carbon_set_post_meta( $post_id, 'crb_profile_public_bio', $profile_bio ); // Already in post_content

            error_log( "MIIHOST DEBUG: Successfully imported profile '{$profile_name}' (ID: {$post_id})." );
            $imported_count++;
        }

        fclose( $handle );
        error_log( "MIIHOST DEBUG: Profile import complete. Total rows: {$row_count}, Imported: {$imported_count}, Skipped: {$skipped_count}." );
        echo "Profile import complete. Total rows processed: {$row_count}, Imported: {$imported_count}, Skipped: {$skipped_count}.<br>";
    } else {
        error_log( 'MIIHOST DEBUG: Could not open profile CSV file.' );
        echo 'ERROR: Could not open profile CSV file.<br>';
    }
}

?>
