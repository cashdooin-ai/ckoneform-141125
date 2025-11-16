<?php
/**
 * Admin Dashboard Settings & Customization
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Dashboard_Settings {

    /**
     * Initialize
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
        add_action('admin_post_ck_save_dashboard_settings', array(__CLASS__, 'save_dashboard_settings'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }

    /**
     * Add settings menu
     */
    public static function add_settings_menu() {
        add_submenu_page(
            'ck-student-portal',
            'Dashboard Settings',
            'Dashboard Settings',
            'manage_options',
            'ck-dashboard-settings',
            array(__CLASS__, 'settings_page')
        );
    }

    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('ck_dashboard_settings_group', 'ck_dashboard_settings');
    }

    /**
     * Settings page
     */
    public static function settings_page() {
        $settings = get_option('ck_dashboard_settings', array(
            'show_overview' => true,
            'show_applications' => true,
            'show_services' => true,
            'show_tests' => true,
            'show_offers' => true,
            'show_documents' => true,
            'show_profile' => true,
            'welcome_message' => 'Welcome to your personalized dashboard!',
            'theme_color' => '#667eea',
            'announcement' => '',
            'dashboard_template' => 'modern',
        ));

        if (isset($_GET['settings_saved'])) {
            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }
        ?>
        <div class="wrap">
            <h1><span class="dashicons dashicons-dashboard" style="font-size: 30px; margin-right: 10px;"></span> Dashboard Settings</h1>
            <p>Customize the student dashboard appearance and features.</p>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="ck-settings-form">
                <?php wp_nonce_field('ck_dashboard_settings_nonce', 'dashboard_settings_nonce'); ?>
                <input type="hidden" name="action" value="ck_save_dashboard_settings">

                <!-- Dashboard Template Selection -->
                <div class="settings-section">
                    <h2><span class="dashicons dashicons-layout"></span> Dashboard Template</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Template Style</th>
                            <td>
                                <label>
                                    <input type="radio" name="dashboard_template" value="modern" <?php checked($settings['dashboard_template'] ?? 'modern', 'modern'); ?>>
                                    <strong>Modern (Recommended)</strong> - Sidebar navigation with cards
                                </label><br><br>
                                <label>
                                    <input type="radio" name="dashboard_template" value="classic" <?php checked($settings['dashboard_template'] ?? 'modern', 'classic'); ?>>
                                    <strong>Classic</strong> - Tab-based top navigation
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Appearance Settings -->
                <div class="settings-section">
                    <h2><span class="dashicons dashicons-art"></span> Appearance</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Theme Color</th>
                            <td>
                                <input type="color" name="theme_color" value="<?php echo esc_attr($settings['theme_color']); ?>" style="width: 100px; height: 40px;">
                                <p class="description">Primary color used throughout the dashboard.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Welcome Message</th>
                            <td>
                                <input type="text" name="welcome_message" value="<?php echo esc_attr($settings['welcome_message']); ?>" class="large-text">
                                <p class="description">Message shown on the dashboard overview page.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Announcement Banner</th>
                            <td>
                                <textarea name="announcement" rows="3" class="large-text"><?php echo esc_textarea($settings['announcement'] ?? ''); ?></textarea>
                                <p class="description">Important announcement shown at the top of dashboard. Leave empty to hide.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Dashboard Sections -->
                <div class="settings-section">
                    <h2><span class="dashicons dashicons-visibility"></span> Visible Sections</h2>
                    <p>Choose which sections are visible in the student dashboard.</p>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Dashboard Tabs</th>
                            <td>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_overview" value="1" <?php checked(!empty($settings['show_overview'])); ?>>
                                    <strong>Overview</strong> - Dashboard home with statistics
                                </label>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_applications" value="1" <?php checked(!empty($settings['show_applications'])); ?>>
                                    <strong>Applications</strong> - College application tracking
                                </label>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_services" value="1" <?php checked(!empty($settings['show_services'])); ?>>
                                    <strong>Services</strong> - Purchased services list
                                </label>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_tests" value="1" <?php checked(!empty($settings['show_tests'])); ?>>
                                    <strong>Mock Tests</strong> - Available tests and results
                                </label>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_offers" value="1" <?php checked(!empty($settings['show_offers'])); ?>>
                                    <strong>Offers</strong> - Special offers and discounts
                                </label>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_documents" value="1" <?php checked(!empty($settings['show_documents'])); ?>>
                                    <strong>Documents</strong> - Document upload and management
                                </label>
                                <label style="display: block; margin-bottom: 10px;">
                                    <input type="checkbox" name="show_profile" value="1" <?php checked(!empty($settings['show_profile'])); ?>>
                                    <strong>Profile</strong> - Profile settings and password change
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Quick Preview -->
                <div class="settings-section">
                    <h2><span class="dashicons dashicons-welcome-view-site"></span> Preview</h2>
                    <div id="preview-container" style="background: #f0f2f5; padding: 20px; border-radius: 8px;">
                        <p><strong>Active Tabs Preview:</strong></p>
                        <div id="tabs-preview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                        <p style="margin-top: 15px;"><strong>Theme Color:</strong> <span id="color-preview" style="display: inline-block; width: 100px; height: 20px; border-radius: 4px; vertical-align: middle;"></span></p>
                    </div>
                </div>

                <p class="submit">
                    <input type="submit" name="submit" class="button button-primary button-large" value="Save Dashboard Settings">
                </p>
            </form>
        </div>

        <script>
        jQuery(document).ready(function($) {
            function updatePreview() {
                // Update tabs preview
                var tabs = [];
                if ($('[name="show_overview"]').is(':checked')) tabs.push('Overview');
                if ($('[name="show_applications"]').is(':checked')) tabs.push('Applications');
                if ($('[name="show_services"]').is(':checked')) tabs.push('Services');
                if ($('[name="show_tests"]').is(':checked')) tabs.push('Mock Tests');
                if ($('[name="show_offers"]').is(':checked')) tabs.push('Offers');
                if ($('[name="show_documents"]').is(':checked')) tabs.push('Documents');
                if ($('[name="show_profile"]').is(':checked')) tabs.push('Profile');

                var color = $('[name="theme_color"]').val();
                var html = '';
                tabs.forEach(function(tab) {
                    html += '<span style="background: ' + color + '; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px;">' + tab + '</span>';
                });
                $('#tabs-preview').html(html);
                $('#color-preview').css('background', color);
            }

            $('input[type="checkbox"], input[name="theme_color"]').on('change', updatePreview);
            updatePreview();
        });
        </script>

        <style>
        .settings-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .settings-section h2 {
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .settings-section h2 .dashicons {
            color: #667eea;
        }
        .ck-settings-form .form-table th {
            width: 200px;
        }
        </style>
        <?php
    }

    /**
     * Save dashboard settings
     */
    public static function save_dashboard_settings() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_dashboard_settings_nonce', 'dashboard_settings_nonce');

        $settings = array(
            'show_overview' => isset($_POST['show_overview']) ? true : false,
            'show_applications' => isset($_POST['show_applications']) ? true : false,
            'show_services' => isset($_POST['show_services']) ? true : false,
            'show_tests' => isset($_POST['show_tests']) ? true : false,
            'show_offers' => isset($_POST['show_offers']) ? true : false,
            'show_documents' => isset($_POST['show_documents']) ? true : false,
            'show_profile' => isset($_POST['show_profile']) ? true : false,
            'welcome_message' => sanitize_text_field($_POST['welcome_message'] ?? ''),
            'theme_color' => sanitize_hex_color($_POST['theme_color'] ?? '#667eea'),
            'announcement' => wp_kses_post($_POST['announcement'] ?? ''),
            'dashboard_template' => sanitize_text_field($_POST['dashboard_template'] ?? 'modern'),
        );

        update_option('ck_dashboard_settings', $settings);

        wp_redirect(admin_url('admin.php?page=ck-dashboard-settings&settings_saved=1'));
        exit;
    }
}

// Initialize
CK_OneForm_Dashboard_Settings::init();
