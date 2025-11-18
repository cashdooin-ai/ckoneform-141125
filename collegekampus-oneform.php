<?php
/**
 * Plugin Name: CollegeKampus OneForm
 * Plugin URI: https://collegekampus.com
 * Description: Complete OneForm application and registration system for CollegeKampus portal
 * Version: 1.0.0
 * Author: CollegeKampus
 * Author URI: https://collegekampus.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ck-oneform
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CK_ONEFORM_VERSION', '1.0.0');
define('CK_ONEFORM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CK_ONEFORM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CK_ONEFORM_PLUGIN_FILE', __FILE__);
define('CK_ONEFORM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main CollegeKampus OneForm Class
 */
class CK_OneForm {

    /**
     * Single instance of the class
     */
    private static $instance = null;

    /**
     * Get class instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->includes();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'));
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core includes
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-database.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-post-types.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-taxonomies.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-forms.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-ajax.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-shortcodes.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-emails.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-pdf-generator.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'includes/class-student-auth.php';

        // Admin includes
        if (is_admin()) {
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-admin.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-settings.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-submissions.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-college-importer.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-student-manager.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-dashboard-settings.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-lead-manager.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-mock-test-generator.php';
            require_once CK_ONEFORM_PLUGIN_DIR . 'admin/class-payment-settings.php';
        }

        // Debug page (available to all users for troubleshooting)
        require_once CK_ONEFORM_PLUGIN_DIR . 'admin/debug-page.php';

        // Frontend includes
        require_once CK_ONEFORM_PLUGIN_DIR . 'public/class-frontend.php';
        require_once CK_ONEFORM_PLUGIN_DIR . 'public/class-user-dashboard.php';
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Create custom database tables
        CK_OneForm_Database::create_tables();

        // Register post types and flush rewrite rules
        CK_OneForm_Post_Types::register_post_types();
        flush_rewrite_rules();

        // Set default options
        $this->set_default_options();

        // Create default pages
        $this->create_default_pages();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('ck-oneform', false, dirname(CK_ONEFORM_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize post types hooks
        CK_OneForm_Post_Types::init();

        // Register post types
        CK_OneForm_Post_Types::register_post_types();

        // Register taxonomies (from both classes)
        CK_OneForm_Post_Types::register_taxonomies();
        CK_OneForm_Taxonomies::register_taxonomies();

        // Register shortcodes
        CK_OneForm_Shortcodes::register_shortcodes();
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        wp_enqueue_style('ck-oneform-admin', CK_ONEFORM_PLUGIN_URL . 'assets/css/admin.css', array(), CK_ONEFORM_VERSION);
        wp_enqueue_script('ck-oneform-admin', CK_ONEFORM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), CK_ONEFORM_VERSION, true);

        wp_localize_script('ck-oneform-admin', 'ckOneFormAdmin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ck-oneform-admin-nonce')
        ));
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_enqueue_scripts() {
        // Load base styles
        wp_enqueue_style('ck-oneform-frontend', CK_ONEFORM_PLUGIN_URL . 'assets/css/frontend.css', array(), CK_ONEFORM_VERSION);

        // Load fixes CSS with high priority to override theme conflicts
        wp_enqueue_style('ck-oneform-frontend-fixes', CK_ONEFORM_PLUGIN_URL . 'assets/css/frontend-fixes.css', array('ck-oneform-frontend'), CK_ONEFORM_VERSION, 'all');

        // Load responsive CSS with highest priority for mobile and input field fixes
        wp_enqueue_style('ck-oneform-responsive', CK_ONEFORM_PLUGIN_URL . 'assets/css/responsive.css', array('ck-oneform-frontend-fixes'), CK_ONEFORM_VERSION, 'all');

        wp_enqueue_script('ck-oneform-frontend', CK_ONEFORM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), CK_ONEFORM_VERSION, true);

        // Enqueue modern homepage styles and scripts on homepage
        if (is_page() && get_post() && has_shortcode(get_post()->post_content, 'ck_oneform_home')) {
            wp_enqueue_style('ck-oneform-home-modern', CK_ONEFORM_PLUGIN_URL . 'assets/css/home-modern.css', array('ck-oneform-frontend'), CK_ONEFORM_VERSION);
            wp_enqueue_script('ck-oneform-home-modern', CK_ONEFORM_PLUGIN_URL . 'assets/js/home-modern.js', array('jquery'), CK_ONEFORM_VERSION, true);
        }

        // Enqueue multi-college selection scripts on application page
        if (is_page() && get_post() && (has_shortcode(get_post()->post_content, 'ck_oneform_application') || has_shortcode(get_post()->post_content, 'ck_oneform_multi_college'))) {
            wp_enqueue_script('ck-oneform-multi-college', CK_ONEFORM_PLUGIN_URL . 'assets/js/multi-college-selection.js', array('jquery'), CK_ONEFORM_VERSION, true);
        }

        wp_localize_script('ck-oneform-frontend', 'ckOneForm', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ck-oneform-nonce'),
            'noCollegesSelected' => __('Please select at least one college to apply to.', 'ck-oneform')
        ));
    }

    /**
     * Set default options
     */
    private function set_default_options() {
        $defaults = array(
            'ck_oneform_email_notifications' => 'yes',
            'ck_oneform_admin_email' => get_option('admin_email'),
            'ck_oneform_enable_payments' => 'no',
            'ck_oneform_currency' => 'INR',
        );

        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }

    /**
     * Create default pages
     */
    private function create_default_pages() {
        $pages = array(
            'oneform-home' => array(
                'title' => 'OneForm Home',
                'content' => '[ck_oneform_home]'
            ),
            'application-form' => array(
                'title' => 'Application Form',
                'content' => '[ck_oneform_application]'
            ),
            'student-registration' => array(
                'title' => 'Student Registration',
                'content' => '[ck_oneform_registration]'
            ),
            'my-applications' => array(
                'title' => 'My Applications',
                'content' => '[ck_oneform_dashboard]'
            ),
            'application-status' => array(
                'title' => 'Application Status',
                'content' => '[ck_oneform_status]'
            ),
            'payment' => array(
                'title' => 'Payment',
                'content' => '[ck_oneform_payment]'
            ),
        );

        foreach ($pages as $slug => $page) {
            // Check if page already exists
            $page_check = get_page_by_path($slug);

            if (!$page_check) {
                wp_insert_post(array(
                    'post_title' => $page['title'],
                    'post_content' => $page['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug,
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                ));
            }
        }
    }
}

/**
 * Initialize the plugin
 */
function ck_oneform() {
    return CK_OneForm::get_instance();
}

// Start the plugin
ck_oneform();
