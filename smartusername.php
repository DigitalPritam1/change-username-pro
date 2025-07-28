<?php
/**
 * Plugin Name: SmartUsername – Secure Admin Login Renamer & Username Editor Tool
 * Plugin URI: https://wordpress.org/plugins/smartusername/
 * Description: Easily change WordPress usernames from the admin dashboard. Perfect for administrators who need to update user login names without creating new accounts. Simple, secure, and user-friendly interface.
 * Version: 1.0.0
 * Author: Pritam Sonone
 * Author URI: https://profiles.wordpress.org/digitalpritam/
 * Text Domain: smartusername
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SU_SmartUsernamePlugin {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'su_add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'su_process_username_change' ) );
    }

    public function su_add_admin_menu() {
        add_users_page(
            __( 'Change Username', 'smartusername' ),
            __( 'Change Username', 'smartusername' ),
            'manage_options',
            'smartusername',
            array( $this, 'su_render_admin_page' )
        );
    }

    public function su_render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Change Username', 'smartusername' ); ?></h1>
            <?php 
            // Check for success message using transient instead of GET parameter
            if ( get_transient( 'su_username_changed_' . get_current_user_id() ) ) : 
                delete_transient( 'su_username_changed_' . get_current_user_id() );
            ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'Congratulations! Username has been successfully changed.', 'smartusername' ); ?></p>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <?php wp_nonce_field( 'su_change_username', 'su_nonce' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="current_username"><?php esc_html_e( 'Current Username', 'smartusername' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="current_username" id="current_username" value="<?php echo esc_attr( wp_get_current_user()->user_login ); ?>" class="regular-text" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="new_username"><?php esc_html_e( 'New Username', 'smartusername' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="new_username" id="new_username" class="regular-text" required />
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Change Username', 'smartusername' ); ?>" />
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * Update the user_name in database.
     */
    public function su_process_username_change() {
        if ( isset( $_POST['su_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['su_nonce'] ) ), 'su_change_username' ) ) {
            if ( isset( $_POST['new_username'] ) && ! empty( $_POST['new_username'] ) ) {
                $current_user = wp_get_current_user();
                $new_username = sanitize_user( wp_unslash( $_POST['new_username'] ) );

                // Check if username already exists
                if ( username_exists( $new_username ) ) {
                    wp_die( esc_html__( 'Username already exists. Please choose a different username.', 'smartusername' ) );
                }

                // Update the user login (username) using WordPress function
                $user_data = array(
                    'ID' => $current_user->ID,
                    'user_login' => $new_username
                );
                
                // Use wp_update_user but we need to use a filter to allow user_login changes
                add_filter( 'send_password_change_email', '__return_false' );
                add_filter( 'send_email_change_email', '__return_false' );
                
                // Direct database query is necessary as WordPress doesn't provide a function to change user_login
                // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
                global $wpdb;
                $wpdb->update(
                    $wpdb->users,
                    array( 'user_login' => $new_username ),
                    array( 'ID' => $current_user->ID ),
                    array( '%s' ),
                    array( '%d' )
                );
                // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
                
                // Clear user cache
                wp_cache_delete( $current_user->ID, 'users' );
                wp_cache_delete( $current_user->user_login, 'userlogins' );
                wp_cache_delete( $new_username, 'userlogins' );

                // Set success message using transient
                set_transient( 'su_username_changed_' . get_current_user_id(), true, 30 );

                // Log the user out after changing the username
                wp_logout();

                // Redirect to the login page
                wp_redirect( admin_url( 'users.php?page=smartusername' ) );
                exit;
            }
        }
    }
}

new SU_SmartUsernamePlugin();

// Add a "Change Username" link next to the "Deactivate" link
function su_username_add_settings_link($links) {
    // Only show the link to users with manage_options capability
    if (current_user_can('manage_options')) {
        $settings_link = '<a href="' . admin_url('users.php?page=smartusername') . '">Change Username</a>';
        $links = array_merge($links, [$settings_link]);
    }
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'su_username_add_settings_link');