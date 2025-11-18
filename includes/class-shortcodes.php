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
        add_shortcode('ck_college_details', array(__CLASS__, 'college_details'));
        add_shortcode('ck_oneform_apply', array(__CLASS__, 'enhanced_application'));
        add_shortcode('ck_student_login', array(__CLASS__, 'student_login'));
        add_shortcode('ck_student_dashboard', array(__CLASS__, 'student_dashboard'));
        add_shortcode('ck_mega_menu', array(__CLASS__, 'mega_menu'));
        add_shortcode('ck_service_page', array(__CLASS__, 'service_page'));
        add_shortcode('ck_lead_capture_form', array(__CLASS__, 'lead_capture_form'));
        add_shortcode('ck_take_test', array(__CLASS__, 'take_test'));
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
     * College details page shortcode
     * Usage: [ck_college_details] - reads ?college_id from URL
     */
    public static function college_details($atts) {
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/college-details-page.php';
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

    /**
     * Student login/registration page
     */
    public static function student_login($atts) {
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/student-login.php';
        return ob_get_clean();
    }

    /**
     * Student dashboard page
     */
    public static function student_dashboard($atts) {
        // Check if student is logged in
        if (!CK_OneForm_Student_Auth::is_student_logged_in()) {
            return '<p>Please <a href="' . home_url('/student-login/') . '">login</a> to access your dashboard.</p>';
        }

        // Get dashboard settings to determine which template to use
        $settings = get_option('ck_dashboard_settings', array('dashboard_template' => 'modern'));
        $template = ($settings['dashboard_template'] ?? 'modern') === 'modern'
            ? 'templates/frontend/student-dashboard-modern.php'
            : 'templates/frontend/student-dashboard-full.php';

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . $template;
        return ob_get_clean();
    }

    /**
     * Lead capture form shortcode
     * Usage: [ck_lead_capture_form variant="full" source="Homepage" title="Get Free Counseling"]
     * Variants: full, compact, inline, floating
     */
    public static function lead_capture_form($atts) {
        $atts = shortcode_atts(array(
            'variant' => 'full',
            'source' => 'Website',
            'title' => 'Get Free Counseling',
            'subtitle' => 'Our experts will guide you to your dream college',
            'button_text' => 'Get Free Consultation',
        ), $atts);

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/lead-capture-form.php';
        return ob_get_clean();
    }

    /**
     * Mega Menu shortcode
     */
    public static function mega_menu($atts) {
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/mega-menu.php';
        return ob_get_clean();
    }

    /**
     * Service Page shortcode
     * Usage: [ck_service_page slug="mock-tests"] or pass ?service=mock-tests in URL
     */
    public static function service_page($atts) {
        $atts = shortcode_atts(array(
            'slug' => '',
        ), $atts);

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/service-page.php';
        return ob_get_clean();
    }

    /**
     * Test taking interface
     * Usage: [ck_take_test]
     * Note: Requires ?test_id=X in URL
     */
    public static function take_test($atts) {
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/test-taking.php';
        return ob_get_clean();
    }
}
