<?php

class SmartUsername {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ) );
        require_once SMARTUSERNAME_PATH . 'includes/class-smartusername-admin.php';
        require_once SMARTUSERNAME_PATH . 'includes/class-smartusername-frontend.php';
    }

    public static function activate() {
        // Actions to perform upon activation.
    }

    public static function deactivate() {
        // Actions to perform upon deactivation.
    }

    public static function add_admin_menu() {
        add_users_page(
            __( 'Change Username', 'smartusername' ),
            __( 'Change Username', 'smartusername' ),
            'manage_options',
            'smartusername',
            array( 'SmartUsername_Admin', 'display_admin_page' )
        );
    }

    public static function enqueue_admin_scripts( $hook ) {
        if ( $hook === 'users_page_smartusername' ) {
            wp_enqueue_style( 'smartusername-admin', SMARTUSERNAME_URL . 'assets/css/admin-style.css', array(), SMARTUSERNAME_VERSION );
            wp_enqueue_script( 'smartusername-admin', SMARTUSERNAME_URL . 'assets/js/admin-script.js', array( 'jquery' ), SMARTUSERNAME_VERSION, true );
        }
    }
}