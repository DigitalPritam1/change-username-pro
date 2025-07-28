<?php

class SmartUsername_Admin {

    public static function display_admin_page() {
        $message = '';
        
        // Check if nonce field is set, unslash, sanitize, and verify the nonce
        if ( isset( $_POST['smartusername_nonce'] ) && 
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['smartusername_nonce'] ) ), 'smartusername' ) ) {
            $message = self::process_username_change();
        }

        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Change Username', 'smartusername' ); ?></h1>
            <?php if ( ! empty( $message ) ) : ?>
                <?php echo wp_kses_post( $message ); ?>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field( 'smartusername', 'smartusername_nonce' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="user_login"><?php esc_html_e( 'Current Username', 'smartusername' ); ?></label></th>
                        <td><input name="user_login" type="text" id="user_login" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="new_user_login"><?php esc_html_e( 'New Username', 'smartusername' ); ?></label></th>
                        <td><input name="new_user_login" type="text" id="new_user_login" class="regular-text" required></td>
                    </tr>
                </table>
                <?php submit_button( esc_html__( 'Change Username', 'smartusername' ) ); ?>
            </form>
        </div>
        <?php
    }

    private static function process_username_change() {
        // Nonce is already verified in display_admin_page method
        // phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified in calling method
        if ( ! isset( $_POST['user_login'] ) || ! isset( $_POST['new_user_login'] ) ) {
            return '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Missing required fields.', 'smartusername' ) . '</p></div>';
        }

        $user_login     = sanitize_user( wp_unslash( $_POST['user_login'] ) );
        $new_user_login = sanitize_user( wp_unslash( $_POST['new_user_login'] ) );
        // phpcs:enable WordPress.Security.NonceVerification.Missing

        if ( username_exists( $new_user_login ) ) {
            return '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'The new username is already taken.', 'smartusername' ) . '</p></div>';
        }

        $user = get_user_by( 'login', $user_login );
        if ( ! $user ) {
            return '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Username not found.', 'smartusername' ) . '</p></div>';
        }

        // Update the user login using WordPress database methods with proper formatting
        // Direct database query is necessary as WordPress doesn't provide a function to change user_login
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
        global $wpdb;
        $result = $wpdb->update(
            $wpdb->users,
            array( 'user_login' => $new_user_login ),
            array( 'ID' => $user->ID ),
            array( '%s' ),
            array( '%d' )
        );
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
        
        if ( $result !== false ) {
            // Clear user cache
            wp_cache_delete( $user->ID, 'users' );
            wp_cache_delete( $user_login, 'userlogins' );
            wp_cache_delete( $new_user_login, 'userlogins' );
            
            return '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Username changed successfully.', 'smartusername' ) . '</p></div>';
        } else {
            return '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Failed to update username.', 'smartusername' ) . '</p></div>';
        }
    }
}