<?php
/**
 * Shortcodes Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Shortcodes {

    /**
     * Register all shortcodes
     */
    public static function register_shortcodes() {
        add_shortcode('ck_oneform_home', array(__CLASS__, 'home_page'));
        add_shortcode('ck_oneform_application', array(__CLASS__, 'application_form'));
        add_shortcode('ck_oneform_registration', array(__CLASS__, 'registration_form'));
        add_shortcode('ck_oneform_dashboard', array(__CLASS__, 'user_dashboard'));
        add_shortcode('ck_oneform_status', array(__CLASS__, 'application_status'));
        add_shortcode('ck_oneform_payment', array(__CLASS__, 'payment_page'));
        add_shortcode('ck_oneform_courses', array(__CLASS__, 'courses_list'));
        add_shortcode('ck_oneform_colleges', array(__CLASS__, 'colleges_list'));
        add_shortcode('ck_oneform_apply', array(__CLASS__, 'enhanced_application'));
    }

    /**
     * Home page shortcode - Uses modern template
     */
    public static function home_page($atts) {
        // Use modern template with all features
        $template = 'templates/frontend/home-modern.php';

        // Check if file exists
        if (!file_exists(CK_ONEFORM_PLUGIN_DIR . $template)) {
            return '<p>Error: Template file not found at ' . CK_ONEFORM_PLUGIN_DIR . $template . '</p>';
        }

        ob_start();
        try {
            include CK_ONEFORM_PLUGIN_DIR . $template;
        } catch (Exception $e) {
            return '<p>Error loading template: ' . $e->getMessage() . '</p>';
        }
        return ob_get_clean();
    }

    /**
     * Application form shortcode
     */
    public static function application_form($atts) {
        $atts = shortcode_atts(array(
            'form_id' => 0,
            'course_id' => 0,
        ), $atts);

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/application-form.php';
        return ob_get_clean();
    }

    /**
     * Registration form shortcode
     */
    public static function registration_form($atts) {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already registered and logged in.', 'ck-oneform') . '</p>';
        }

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/registration-form.php';
        return ob_get_clean();
    }

    /**
     * User dashboard shortcode
     */
    public static function user_dashboard($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please login to view your dashboard.', 'ck-oneform') . ' <a href="' . wp_login_url(get_permalink()) . '">' . __('Login', 'ck-oneform') . '</a></p>';
        }

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/dashboard.php';
        return ob_get_clean();
    }

    /**
     * Application status shortcode
     */
    public static function application_status($atts) {
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/application-status.php';
        return ob_get_clean();
    }

    /**
     * Payment page shortcode
     */
    public static function payment_page($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please login to make a payment.', 'ck-oneform') . '</p>';
        }

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/payment.php';
        return ob_get_clean();
    }

    /**
     * Courses list shortcode
     */
    public static function courses_list($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'category' => '',
        ), $atts);

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/courses-list.php';
        return ob_get_clean();
    }

    /**
     * Colleges list shortcode
     */
    public static function colleges_list($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'type' => '',
            'advanced' => 'yes', // Use advanced template by default
        ), $atts);

        ob_start();

        if ($atts['advanced'] === 'yes') {
            include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/colleges-list-advanced.php';
        } else {
            include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/colleges-list.php';
        }

        return ob_get_clean();
    }

    /**
     * Enhanced multi-college application form
     */
    public static function enhanced_application($atts) {
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/application-form-enhanced.php';
        return ob_get_clean();
    }
}
