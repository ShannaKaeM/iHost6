<?php
/**
 * Functions for publishing draft properties.
 */

if ( ! function_exists( 'miihost_publish_draft_properties' ) ) {
    function miihost_publish_draft_properties() {
        // Check if this action has already run to prevent accidental re-runs
        if ( get_option( 'miihost_draft_properties_published' ) ) {
            error_log('MIIHOST DEBUG: Draft properties already published. Skipping.');
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::warning( 'Draft properties have already been published.' );
            } else {
                echo '<p>Draft properties have already been published. To run again, delete the `miihost_draft_properties_published` option.</p>';
            }
            return;
        }

        error_log('MIIHOST DEBUG: Starting to publish draft properties.');

        $args = array(
            'post_type'      => 'property',
            'post_status'    => 'draft',
            'posts_per_page' => -1, // Process all draft properties
            'fields'         => 'ids', // Only get post IDs for efficiency
        );

        $draft_properties = get_posts( $args );

        if ( empty( $draft_properties ) ) {
            error_log('MIIHOST DEBUG: No draft properties found to publish.');
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::success( 'No draft properties found to publish.' );
            } else {
                echo '<p>No draft properties found to publish.</p>';
            }
            // Mark as run even if none found, to avoid re-running unnecessarily
            update_option( 'miihost_draft_properties_published', true );
            return;
        }

        $published_count = 0;
        foreach ( $draft_properties as $post_id ) {
            $update_args = array(
                'ID'          => $post_id,
                'post_status' => 'publish',
            );
            $result = wp_update_post( $update_args, true ); // true for WP_Error object on failure
            if ( is_wp_error( $result ) ) {
                error_log('MIIHOST ERROR: Failed to publish property ID ' . $post_id . ': ' . $result->get_error_message());
            } else {
                error_log('MIIHOST DEBUG: Published property ID ' . $post_id);
                $published_count++;
            }
        }

        // Mark action as complete
        update_option( 'miihost_draft_properties_published', true );
        error_log('MIIHOST DEBUG: Draft properties publishing completed. Published ' . $published_count . ' properties.');
         if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::success( 'Draft properties published successfully: ' . $published_count );
        } else {
            echo '<p>Draft properties published successfully! Published ' . esc_html($published_count) . ' properties.</p>';
        }
    }
}

/**
 * Action to trigger the publishing of draft properties.
 * You can trigger this by visiting your admin dashboard and adding ?miihost_do_publish_drafts=true to the URL.
 * Example: wp-admin/index.php?miihost_do_publish_drafts=true
 *
 * IMPORTANT: Remove or comment out this action hook after use.
 */
// add_action( 'admin_init', 'miihost_trigger_publish_drafts' ); // Trigger disabled after use
function miihost_trigger_publish_drafts() {
    if ( isset( $_GET['miihost_do_publish_drafts'] ) && $_GET['miihost_do_publish_drafts'] === 'true' ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }
        miihost_publish_draft_properties();
    }
}

?>
