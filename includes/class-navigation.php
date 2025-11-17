<?php
/**
 * Navigation Handler
 *
 * Handles WordPress navigation menus, breadcrumbs, and navigation utilities
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Navigation {

    /**
     * Initialize navigation hooks
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'register_menus'));
        add_action('admin_menu', array(__CLASS__, 'add_menu_setup_page'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('wp_ajax_ck_setup_navigation', array(__CLASS__, 'ajax_setup_navigation'));
        add_action('wp_ajax_ck_create_service_pages', array(__CLASS__, 'ajax_create_service_pages'));
        add_action('wp_head', array(__CLASS__, 'add_breadcrumb_schema'));
        add_filter('body_class', array(__CLASS__, 'add_page_body_classes'));
    }

    /**
     * Register navigation settings
     */
    public static function register_settings() {
        register_setting('ck_navigation_settings', 'ck_enable_breadcrumbs');
        register_setting('ck_navigation_settings', 'ck_breadcrumb_home_text');
        register_setting('ck_navigation_settings', 'ck_breadcrumb_separator');
    }

    /**
     * Register WordPress navigation menus
     */
    public static function register_menus() {
        register_nav_menus(array(
            'ck-oneform-main' => __('OneForm Main Navigation', 'ck-oneform'),
            'ck-oneform-footer' => __('OneForm Footer Menu', 'ck-oneform'),
            'ck-oneform-quick-links' => __('OneForm Quick Links', 'ck-oneform'),
            'ck-oneform-student-portal' => __('OneForm Student Portal Menu', 'ck-oneform'),
        ));
    }

    /**
     * Add menu setup page under Student Portal menu
     */
    public static function add_menu_setup_page() {
        add_submenu_page(
            'ck-student-portal',
            __('Navigation Setup', 'ck-oneform'),
            __('Navigation Setup', 'ck-oneform'),
            'manage_options',
            'ck-navigation-setup',
            array(__CLASS__, 'render_setup_page')
        );
    }

    /**
     * Render navigation setup page
     */
    public static function render_setup_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('OneForm Navigation Setup', 'ck-oneform'); ?></h1>

            <div class="ck-nav-setup-container">
                <div class="ck-nav-card">
                    <h2><?php _e('Auto-Create Navigation Menus', 'ck-oneform'); ?></h2>
                    <p><?php _e('Automatically create WordPress navigation menus with all OneForm pages pre-configured.', 'ck-oneform'); ?></p>

                    <div class="ck-nav-options">
                        <h3><?php _e('Select Menus to Create:', 'ck-oneform'); ?></h3>
                        <label>
                            <input type="checkbox" name="menus[]" value="main" checked>
                            <?php _e('Main Navigation (Header Menu)', 'ck-oneform'); ?>
                        </label>
                        <label>
                            <input type="checkbox" name="menus[]" value="footer" checked>
                            <?php _e('Footer Menu', 'ck-oneform'); ?>
                        </label>
                        <label>
                            <input type="checkbox" name="menus[]" value="quick-links">
                            <?php _e('Quick Links Sidebar', 'ck-oneform'); ?>
                        </label>
                        <label>
                            <input type="checkbox" name="menus[]" value="student-portal">
                            <?php _e('Student Portal Menu', 'ck-oneform'); ?>
                        </label>
                    </div>

                    <button id="ck-create-menus" class="button button-primary button-large">
                        <?php _e('Create Navigation Menus', 'ck-oneform'); ?>
                    </button>

                    <div id="ck-nav-result" style="display: none; margin-top: 20px;"></div>
                </div>

                <div class="ck-nav-card" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                    <h2><?php _e('⚠️ Create All Service Pages', 'ck-oneform'); ?></h2>
                    <p><?php _e('Click this button to create all 39+ service pages (Mock Tests, Scholarships, Career Guidance, etc.). This is required for the mega menu links to work.', 'ck-oneform'); ?></p>

                    <?php
                    // Count existing service pages
                    $services_data_file = CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';
                    $existing_service_pages = 0;
                    $total_service_pages = 0;

                    if (file_exists($services_data_file)) {
                        $services_data = include $services_data_file;
                        foreach ($services_data as $category => $services) {
                            foreach ($services as $service) {
                                $total_service_pages++;
                                if (get_page_by_path($service['slug'])) {
                                    $existing_service_pages++;
                                }
                            }
                        }
                    }
                    ?>

                    <p><strong><?php echo sprintf(__('Status: %d of %d service pages created', 'ck-oneform'), $existing_service_pages, $total_service_pages); ?></strong></p>

                    <button id="ck-create-service-pages" class="button button-primary button-large" <?php echo ($existing_service_pages >= $total_service_pages) ? 'disabled' : ''; ?>>
                        <?php
                        if ($existing_service_pages >= $total_service_pages) {
                            _e('All Service Pages Created ✓', 'ck-oneform');
                        } else {
                            _e('Create All Service Pages Now', 'ck-oneform');
                        }
                        ?>
                    </button>

                    <div id="ck-service-pages-result" style="display: none; margin-top: 20px;"></div>
                </div>

                <div class="ck-nav-card">
                    <h2><?php _e('Available OneForm Pages', 'ck-oneform'); ?></h2>
                    <p><?php _e('These pages are automatically created by OneForm and can be added to your menus:', 'ck-oneform'); ?></p>

                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Page Title', 'ck-oneform'); ?></th>
                                <th><?php _e('Slug', 'ck-oneform'); ?></th>
                                <th><?php _e('Status', 'ck-oneform'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $oneform_pages = self::get_oneform_pages();
                            foreach ($oneform_pages as $slug => $title) {
                                $page = get_page_by_path($slug);
                                $status = $page ? '<span style="color: green;">✓ Exists</span>' : '<span style="color: red;">✗ Not Created</span>';
                                echo '<tr>';
                                echo '<td>' . esc_html($title) . '</td>';
                                echo '<td><code>/' . esc_html($slug) . '/</code></td>';
                                echo '<td>' . $status . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                    <p style="margin-top: 15px;">
                        <a href="<?php echo admin_url('nav-menus.php'); ?>" class="button">
                            <?php _e('Go to Appearance > Menus', 'ck-oneform'); ?>
                        </a>
                    </p>
                </div>

                <div class="ck-nav-card">
                    <h2><?php _e('Breadcrumb Settings', 'ck-oneform'); ?></h2>
                    <p><?php _e('Configure breadcrumb display for OneForm pages.', 'ck-oneform'); ?></p>

                    <form method="post" action="options.php">
                        <?php settings_fields('ck_navigation_settings'); ?>

                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Enable Breadcrumbs', 'ck-oneform'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="ck_enable_breadcrumbs" value="yes"
                                            <?php checked(get_option('ck_enable_breadcrumbs', 'yes'), 'yes'); ?>>
                                        <?php _e('Show breadcrumb navigation on OneForm pages', 'ck-oneform'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Home Text', 'ck-oneform'); ?></th>
                                <td>
                                    <input type="text" name="ck_breadcrumb_home_text" class="regular-text"
                                        value="<?php echo esc_attr(get_option('ck_breadcrumb_home_text', 'Home')); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Separator', 'ck-oneform'); ?></th>
                                <td>
                                    <input type="text" name="ck_breadcrumb_separator" class="small-text"
                                        value="<?php echo esc_attr(get_option('ck_breadcrumb_separator', '/')); ?>">
                                </td>
                            </tr>
                        </table>

                        <?php submit_button(__('Save Breadcrumb Settings', 'ck-oneform')); ?>
                    </form>
                </div>
            </div>
        </div>

        <style>
        .ck-nav-setup-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .ck-nav-card {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .ck-nav-card h2 {
            margin-top: 0;
            color: #23282d;
        }
        .ck-nav-options {
            margin: 20px 0;
        }
        .ck-nav-options label {
            display: block;
            margin-bottom: 10px;
        }
        .ck-nav-options input[type="checkbox"] {
            margin-right: 8px;
        }
        #ck-nav-result.success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        #ck-nav-result.error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#ck-create-menus').on('click', function() {
                var $btn = $(this);
                var $result = $('#ck-nav-result');
                var menus = [];

                $('input[name="menus[]"]:checked').each(function() {
                    menus.push($(this).val());
                });

                if (menus.length === 0) {
                    $result.removeClass('success').addClass('error')
                           .html('Please select at least one menu to create.').show();
                    return;
                }

                $btn.prop('disabled', true).text('Creating menus...');
                $result.hide();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ck_setup_navigation',
                        nonce: '<?php echo wp_create_nonce('ck_nav_setup'); ?>',
                        menus: menus
                    },
                    success: function(response) {
                        if (response.success) {
                            $result.removeClass('error').addClass('success')
                                   .html(response.data.message).show();
                        } else {
                            $result.removeClass('success').addClass('error')
                                   .html(response.data.message).show();
                        }
                    },
                    error: function() {
                        $result.removeClass('success').addClass('error')
                               .html('An error occurred. Please try again.').show();
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Create Navigation Menus');
                    }
                });
            });

            // Service Pages Creation
            $('#ck-create-service-pages').on('click', function() {
                var $btn = $(this);
                var $result = $('#ck-service-pages-result');

                $btn.prop('disabled', true).text('Creating service pages... Please wait...');
                $result.hide();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ck_create_service_pages',
                        nonce: '<?php echo wp_create_nonce('ck_service_pages_setup'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $result.removeClass('error').addClass('success')
                                   .html('<strong>✓ ' + response.data.message + '</strong>').show();
                            if (response.data.created > 0) {
                                $btn.text('All Service Pages Created ✓').prop('disabled', true);
                                // Reload page after 2 seconds to update status
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            }
                        } else {
                            $result.removeClass('success').addClass('error')
                                   .html(response.data.message).show();
                            $btn.prop('disabled', false).text('Create All Service Pages Now');
                        }
                    },
                    error: function() {
                        $result.removeClass('success').addClass('error')
                               .html('An error occurred. Please try again.').show();
                        $btn.prop('disabled', false).text('Create All Service Pages Now');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Get all OneForm pages
     */
    public static function get_oneform_pages() {
        return array(
            'oneform-home' => __('OneForm Home', 'ck-oneform'),
            'application-form' => __('Application Form', 'ck-oneform'),
            'student-registration' => __('Student Registration', 'ck-oneform'),
            'my-applications' => __('My Applications', 'ck-oneform'),
            'application-status' => __('Application Status', 'ck-oneform'),
            'payment' => __('Payment', 'ck-oneform'),
            'courses' => __('Our Courses', 'ck-oneform'),
            'colleges' => __('Partner Colleges', 'ck-oneform'),
            'student-login' => __('Student Login', 'ck-oneform'),
            'mock-tests' => __('Mock Tests', 'ck-oneform'),
            'student-dashboard' => __('Student Dashboard', 'ck-oneform'),
            'services' => __('Our Services', 'ck-oneform'),
            'contact-us' => __('Contact Us', 'ck-oneform'),
            'about-us' => __('About Us', 'ck-oneform'),
            'faq' => __('Frequently Asked Questions', 'ck-oneform'),
        );
    }

    /**
     * AJAX handler for navigation setup
     */
    public static function ajax_setup_navigation() {
        check_ajax_referer('ck_nav_setup', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'ck-oneform')));
        }

        $menus = isset($_POST['menus']) ? array_map('sanitize_text_field', $_POST['menus']) : array();
        $created = array();

        foreach ($menus as $menu_type) {
            $result = self::create_menu($menu_type);
            if ($result) {
                $created[] = $menu_type;
            }
        }

        if (count($created) > 0) {
            $message = sprintf(
                __('Successfully created %d menu(s): %s. Go to Appearance > Menus to assign them to menu locations.', 'ck-oneform'),
                count($created),
                implode(', ', $created)
            );
            wp_send_json_success(array('message' => $message));
        } else {
            wp_send_json_error(array('message' => __('No menus were created. They may already exist.', 'ck-oneform')));
        }
    }

    /**
     * Create a specific menu
     */
    private static function create_menu($type) {
        $menu_name = '';
        $menu_items = array();

        switch ($type) {
            case 'main':
                $menu_name = 'OneForm Main Menu';
                $menu_items = array(
                    'oneform-home' => __('Home', 'ck-oneform'),
                    'courses' => __('Courses', 'ck-oneform'),
                    'colleges' => __('Colleges', 'ck-oneform'),
                    'services' => __('Services', 'ck-oneform'),
                    'application-form' => __('Apply Now', 'ck-oneform'),
                    'student-login' => __('Login', 'ck-oneform'),
                    'about-us' => __('About Us', 'ck-oneform'),
                    'contact-us' => __('Contact', 'ck-oneform'),
                );
                break;

            case 'footer':
                $menu_name = 'OneForm Footer Menu';
                $menu_items = array(
                    'about-us' => __('About Us', 'ck-oneform'),
                    'contact-us' => __('Contact Us', 'ck-oneform'),
                    'faq' => __('FAQ', 'ck-oneform'),
                    'application-status' => __('Track Application', 'ck-oneform'),
                );
                break;

            case 'quick-links':
                $menu_name = 'OneForm Quick Links';
                $menu_items = array(
                    'student-registration' => __('Register', 'ck-oneform'),
                    'application-form' => __('Apply Now', 'ck-oneform'),
                    'application-status' => __('Check Status', 'ck-oneform'),
                    'mock-tests' => __('Mock Tests', 'ck-oneform'),
                );
                break;

            case 'student-portal':
                $menu_name = 'OneForm Student Portal';
                $menu_items = array(
                    'student-dashboard' => __('Dashboard', 'ck-oneform'),
                    'my-applications' => __('My Applications', 'ck-oneform'),
                    'mock-tests' => __('Mock Tests', 'ck-oneform'),
                    'payment' => __('Payments', 'ck-oneform'),
                    'student-login' => __('Logout', 'ck-oneform'),
                );
                break;
        }

        if (empty($menu_name)) {
            return false;
        }

        // Check if menu already exists
        $existing_menu = wp_get_nav_menu_object($menu_name);
        if ($existing_menu) {
            return false;
        }

        // Create the menu
        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) {
            return false;
        }

        // Add menu items
        $order = 1;
        foreach ($menu_items as $slug => $title) {
            $page = get_page_by_path($slug);
            if ($page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-position' => $order++,
                ));
            }
        }

        // Assign to registered location
        $locations = get_theme_mod('nav_menu_locations');
        $location_key = 'ck-oneform-' . str_replace('_', '-', $type);
        if (isset($locations[$location_key])) {
            $locations[$location_key] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        return true;
    }

    /**
     * Generate breadcrumbs for current page
     */
    public static function get_breadcrumbs() {
        if (get_option('ck_enable_breadcrumbs', 'yes') !== 'yes') {
            return '';
        }

        $separator = get_option('ck_breadcrumb_separator', '/');
        $home_text = get_option('ck_breadcrumb_home_text', 'Home');

        $breadcrumbs = '<nav class="ck-breadcrumbs" aria-label="breadcrumb">';
        $breadcrumbs .= '<ol class="ck-breadcrumb-list">';

        // Home link
        $breadcrumbs .= '<li class="ck-breadcrumb-item">';
        $breadcrumbs .= '<a href="' . home_url('/') . '">' . esc_html($home_text) . '</a>';
        $breadcrumbs .= '</li>';

        // Current page
        if (is_page()) {
            $parent_id = wp_get_post_parent_id(get_the_ID());

            // Add parent pages
            if ($parent_id) {
                $parents = array();
                while ($parent_id) {
                    $parent_page = get_post($parent_id);
                    $parents[] = '<li class="ck-breadcrumb-item"><a href="' . get_permalink($parent_page->ID) . '">' . esc_html($parent_page->post_title) . '</a></li>';
                    $parent_id = wp_get_post_parent_id($parent_page->ID);
                }
                $breadcrumbs .= implode('', array_reverse($parents));
            }

            // Current page
            $breadcrumbs .= '<li class="ck-breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
        }

        $breadcrumbs .= '</ol>';
        $breadcrumbs .= '</nav>';

        // Add CSS
        $breadcrumbs .= '<style>
            .ck-breadcrumbs {
                padding: 15px 0;
                margin-bottom: 20px;
            }
            .ck-breadcrumb-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
            }
            .ck-breadcrumb-item {
                display: inline-flex;
                align-items: center;
                color: #666;
            }
            .ck-breadcrumb-item:not(:last-child)::after {
                content: "' . esc_attr($separator) . '";
                margin: 0 10px;
                color: #999;
            }
            .ck-breadcrumb-item a {
                color: #667eea;
                text-decoration: none;
            }
            .ck-breadcrumb-item a:hover {
                text-decoration: underline;
            }
            .ck-breadcrumb-item.active {
                color: #333;
                font-weight: 600;
            }
        </style>';

        return $breadcrumbs;
    }

    /**
     * Add breadcrumb schema markup
     */
    public static function add_breadcrumb_schema() {
        if (!is_page() || get_option('ck_enable_breadcrumbs', 'yes') !== 'yes') {
            return;
        }

        $oneform_pages = self::get_oneform_pages();
        $current_slug = get_post_field('post_name', get_the_ID());

        if (!isset($oneform_pages[$current_slug])) {
            return;
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => get_bloginfo('name'),
                    'item' => home_url('/')
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => get_the_title(),
                    'item' => get_permalink()
                )
            )
        );

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add page-specific body classes
     */
    public static function add_page_body_classes($classes) {
        if (is_page()) {
            $oneform_pages = self::get_oneform_pages();
            $current_slug = get_post_field('post_name', get_the_ID());

            if (isset($oneform_pages[$current_slug])) {
                $classes[] = 'ck-oneform-page';
                $classes[] = 'ck-oneform-page-' . $current_slug;
            }
        }

        return $classes;
    }

    /**
     * Display a specific navigation menu
     */
    public static function display_menu($location = 'ck-oneform-main', $args = array()) {
        $defaults = array(
            'theme_location' => $location,
            'container' => 'nav',
            'container_class' => 'ck-nav-container',
            'menu_class' => 'ck-nav-menu',
            'fallback_cb' => array(__CLASS__, 'fallback_menu'),
            'depth' => 2,
        );

        $args = wp_parse_args($args, $defaults);

        wp_nav_menu($args);
    }

    /**
     * Fallback menu when no menu is assigned
     */
    public static function fallback_menu() {
        $pages = self::get_oneform_pages();
        $main_pages = array('oneform-home', 'courses', 'colleges', 'application-form', 'about-us', 'contact-us');

        echo '<nav class="ck-nav-container">';
        echo '<ul class="ck-nav-menu">';

        foreach ($main_pages as $slug) {
            if (isset($pages[$slug])) {
                $page = get_page_by_path($slug);
                if ($page) {
                    $active = is_page($page->ID) ? ' class="current-menu-item"' : '';
                    echo '<li' . $active . '><a href="' . get_permalink($page->ID) . '">' . esc_html($pages[$slug]) . '</a></li>';
                }
            }
        }

        echo '</ul>';
        echo '</nav>';
    }

    /**
     * AJAX handler for creating all service pages
     */
    public static function ajax_create_service_pages() {
        check_ajax_referer('ck_service_pages_setup', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'ck-oneform')));
        }

        // Load service pages data
        $services_data_file = CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';
        if (!file_exists($services_data_file)) {
            wp_send_json_error(array('message' => __('Service pages data file not found.', 'ck-oneform')));
        }

        $services_data = include $services_data_file;

        // Create a parent "Services" page if it doesn't exist
        $services_parent = get_page_by_path('services');
        $parent_id = 0;

        if (!$services_parent) {
            $parent_id = wp_insert_post(array(
                'post_title' => 'Our Services',
                'post_content' => '[ck_mega_menu]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => 'services',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
            ));
        } else {
            $parent_id = $services_parent->ID;
        }

        // Track created and existing pages
        $created_count = 0;
        $existing_count = 0;
        $created_pages = array();

        // Loop through all categories and services
        foreach ($services_data as $category => $services) {
            foreach ($services as $service) {
                $slug = isset($service['slug']) ? $service['slug'] : '';
                $title = isset($service['title']) ? $service['title'] : '';

                if (empty($slug) || empty($title)) {
                    continue;
                }

                // Check if page already exists
                $page_check = get_page_by_path($slug);

                if (!$page_check) {
                    // Create the service page with shortcode
                    $page_id = wp_insert_post(array(
                        'post_title' => $title,
                        'post_content' => '[ck_service_page slug="' . esc_attr($slug) . '"]',
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'post_name' => $slug,
                        'post_parent' => $parent_id,
                        'comment_status' => 'closed',
                        'ping_status' => 'closed',
                        'menu_order' => $created_count,
                    ));

                    if ($page_id && !is_wp_error($page_id)) {
                        // Add SEO meta if available
                        if (isset($service['subtitle'])) {
                            update_post_meta($page_id, '_ck_service_subtitle', $service['subtitle']);
                        }
                        if (isset($service['desc'])) {
                            update_post_meta($page_id, '_ck_service_desc', $service['desc']);
                        }
                        if (isset($service['icon'])) {
                            update_post_meta($page_id, '_ck_service_icon', $service['icon']);
                        }
                        $created_count++;
                        $created_pages[] = $title;
                    }
                } else {
                    $existing_count++;
                }
            }
        }

        // Flush rewrite rules
        flush_rewrite_rules();

        if ($created_count > 0) {
            update_option('ck_oneform_service_pages_count', $created_count);
            $message = sprintf(
                __('Successfully created %d service pages! (%d pages already existed) Go to Pages to see all created pages.', 'ck-oneform'),
                $created_count,
                $existing_count
            );
            wp_send_json_success(array(
                'message' => $message,
                'created' => $created_count,
                'existing' => $existing_count,
                'pages' => $created_pages
            ));
        } else {
            wp_send_json_success(array(
                'message' => sprintf(__('All %d service pages already exist. No new pages created.', 'ck-oneform'), $existing_count),
                'created' => 0,
                'existing' => $existing_count
            ));
        }
    }
}

// Initialize navigation
CK_OneForm_Navigation::init();
