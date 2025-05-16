<?php
/**
 * Functions for importing Articles from a CSV file.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'init', 'miihost_trigger_article_import' );

function miihost_trigger_article_import() {
    if ( isset( $_GET['miihost_do_article_import'] ) && $_GET['miihost_do_article_import'] === 'true' ) {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'miihost_article_import_nonce' ) ) {
            wp_die( 'Nonce verification failed. Please try again.' );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }
        miihost_import_articles_from_csv();
        echo 'Article import process triggered. Check logs or article list for results.<br>';
        echo '<a href="' . esc_url(admin_url('edit.php?post_type=article')) . '">View Articles</a>';
    }
}

function miihost_get_article_import_trigger_url() {
    $nonce = wp_create_nonce( 'miihost_article_import_nonce' );
    return add_query_arg( array(
        'miihost_do_article_import' => 'true',
        '_wpnonce' => $nonce
    ), home_url('/') );
}

// To display an admin notice with the import link, uncomment the following lines.
// add_action('admin_notices', function() {
//     if (current_user_can('manage_options')) {
//         echo '<div class="notice notice-info is-dismissible"><p>To import articles, <a href="' . esc_url(miihost_get_article_import_trigger_url()) . '">click here</a>. (This message will be removed after testing the import.)</p></div>';
//     }
// });

function miihost_import_articles_from_csv() {
    $csv_file_path = get_theme_file_path( '../../../../../../iHostDocs/site data/Articles_Data__Final_Trim_.csv' );

    if ( ! file_exists( $csv_file_path ) ) {
        error_log( 'MIIHOST DEBUG: Article CSV file not found at ' . $csv_file_path );
        echo 'ERROR: Article CSV file not found.<br>';
        return;
    }

    if ( ( $handle = fopen( $csv_file_path, 'r' ) ) !== false ) {
        $header = fgetcsv( $handle ); // Read and discard header row
        if ($header === false) {
            error_log( 'MIIHOST DEBUG: Article CSV file is empty or header could not be read.' );
            echo 'ERROR: Article CSV file is empty or header could not be read.<br>';
            fclose( $handle );
            return;
        }

        $row_count = 0;
        $imported_count = 0;
        $skipped_count = 0;

        // CSV: id 2,title,slug,content,excerpt,featured_image,author_id,article_type_id
        // Index: 0     1     2     3       4         5             6           7
        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            $row_count++;

            $article_title      = !empty($row[1]) ? sanitize_text_field( $row[1] ) : '';
            $article_slug       = !empty($row[2]) ? sanitize_title( $row[2] ) : '';
            $article_content    = !empty($row[3]) ? wp_kses_post( $row[3] ) : '';
            $article_excerpt    = !empty($row[4]) ? wp_kses_post( $row[4] ) : '';
            // featured_image ($row[5]) is skipped as it's empty in CSV
            $author_id          = !empty($row[6]) ? intval( $row[6] ) : (get_current_user_id() ?: 1);
            $article_type_id_val= !empty($row[7]) ? sanitize_text_field( $row[7] ) : '';

            if ( empty( $article_title ) ) {
                error_log( "MIIHOST DEBUG: Skipped article row {$row_count} due to empty title." );
                $skipped_count++;
                continue;
            }

            // Check if article already exists by title
            $existing_article = get_page_by_title( $article_title, OBJECT, 'article' );
            if ( $existing_article ) {
                error_log( "MIIHOST DEBUG: Article '{$article_title}' already exists. Skipped." );
                $skipped_count++;
                continue;
            }

            // Check if author_id is valid, otherwise default to current user or admin
            if ( ! get_user_by( 'ID', $author_id ) ) {
                $current_user_id = get_current_user_id();
                $author_id = $current_user_id ?: 1; // Default to admin (ID 1) if no current user
            }

            $post_data = array(
                'post_title'   => $article_title,
                'post_name'    => $article_slug, // Use slug from CSV
                'post_content' => $article_content,
                'post_excerpt' => $article_excerpt,
                'post_status'  => 'publish', // Defaulting to publish for articles
                'post_type'    => 'article',
                'post_author'  => $author_id,
            );

            $post_id = wp_insert_post( $post_data );

            if ( is_wp_error( $post_id ) ) {
                error_log( "MIIHOST DEBUG: Error inserting article '{$article_title}': " . $post_id->get_error_message() );
                $skipped_count++;
                continue;
            }

            // Save Carbon Fields data
            carbon_set_post_meta( $post_id, 'crb_article_type_id', $article_type_id_val );

            error_log( "MIIHOST DEBUG: Successfully imported article '{$article_title}' (ID: {$post_id})." );
            $imported_count++;
        }

        fclose( $handle );
        error_log( "MIIHOST DEBUG: Article import complete. Total rows: {$row_count}, Imported: {$imported_count}, Skipped: {$skipped_count}." );
        echo "Article import complete. Total rows processed: {$row_count}, Imported: {$imported_count}, Skipped: {$skipped_count}.<br>";
    } else {
        error_log( 'MIIHOST DEBUG: Could not open article CSV file.' );
        echo 'ERROR: Could not open article CSV file.<br>';
    }
}

?>
