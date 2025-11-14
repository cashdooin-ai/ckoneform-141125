<?php
/**
 * Admin Panel
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('OneForm', 'ck-oneform'),
            __('OneForm', 'ck-oneform'),
            'manage_options',
            'ck-oneform',
            array($this, 'dashboard_page'),
            'dashicons-feedback',
            30
        );

        add_submenu_page(
            'ck-oneform',
            __('Dashboard', 'ck-oneform'),
            __('Dashboard', 'ck-oneform'),
            'manage_options',
            'ck-oneform',
            array($this, 'dashboard_page')
        );

        add_submenu_page(
            'ck-oneform',
            __('Applications', 'ck-oneform'),
            __('Applications', 'ck-oneform'),
            'manage_options',
            'ck-oneform-applications',
            array($this, 'applications_page')
        );

        add_submenu_page(
            'ck-oneform',
            __('Payments', 'ck-oneform'),
            __('Payments', 'ck-oneform'),
            'manage_options',
            'ck-oneform-payments',
            array($this, 'payments_page')
        );

        add_submenu_page(
            'ck-oneform',
            __('Settings', 'ck-oneform'),
            __('Settings', 'ck-oneform'),
            'manage_options',
            'ck-oneform-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Dashboard page
     */
    public function dashboard_page() {
        include CK_ONEFORM_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }

    /**
     * Applications page
     */
    public function applications_page() {
        include CK_ONEFORM_PLUGIN_DIR . 'templates/admin/applications.php';
    }

    /**
     * Payments page
     */
    public function payments_page() {
        include CK_ONEFORM_PLUGIN_DIR . 'templates/admin/payments.php';
    }

    /**
     * Settings page
     */
    public function settings_page() {
        include CK_ONEFORM_PLUGIN_DIR . 'templates/admin/settings.php';
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('ck_oneform_settings', 'ck_oneform_email_notifications');
        register_setting('ck_oneform_settings', 'ck_oneform_admin_email');
        register_setting('ck_oneform_settings', 'ck_oneform_enable_payments');
        register_setting('ck_oneform_settings', 'ck_oneform_currency');
        register_setting('ck_oneform_settings', 'ck_oneform_payment_gateway');
        register_setting('ck_oneform_settings', 'ck_oneform_razorpay_key');
        register_setting('ck_oneform_settings', 'ck_oneform_razorpay_secret');
    }
}

new CK_OneForm_Admin();
