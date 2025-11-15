<?php
/**
 * Debug Page - Check OneForm Plugin Status
 * Add this shortcode to a page: [ck_debug_status]
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add debug shortcode
 */
function ck_oneform_debug_shortcode() {
    global $wpdb;

    ob_start();
    ?>
    <div class="ck-debug-page" style="background: #f5f5f5; padding: 20px; border-radius: 8px; font-family: monospace;">
        <h2 style="color: #333; margin-top: 0;">🔍 OneForm Plugin Debug Status</h2>

        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 5px;">
            <h3 style="color: #667eea; margin-top: 0;">Plugin Information</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Plugin Version:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo CK_ONEFORM_VERSION; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Plugin Directory:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo CK_ONEFORM_PLUGIN_DIR; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>WordPress Version:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo get_bloginfo('version'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>PHP Version:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo PHP_VERSION; ?></td>
                </tr>
            </table>
        </div>

        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 5px;">
            <h3 style="color: #667eea; margin-top: 0;">Database Tables Status</h3>
            <?php
            $tables = array(
                'ck_oneform_applications' => 'Applications',
                'ck_oneform_payments' => 'Payments',
                'ck_oneform_documents' => 'Documents',
                'ck_oneform_students' => 'Students',
                'ck_oneform_student_sessions' => 'Student Sessions',
                'ck_oneform_services' => 'Services',
                'ck_oneform_mock_tests' => 'Mock Tests',
                'ck_oneform_offers' => 'Offers',
                'ck_oneform_student_services' => 'Student Services',
                'ck_oneform_student_tests' => 'Student Tests',
                'ck_oneform_student_offers' => 'Student Offers',
            );

            echo '<table style="width: 100%; border-collapse: collapse;">';
            foreach ($tables as $table_suffix => $table_name) {
                $table = $wpdb->prefix . $table_suffix;
                $exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
                $count = 0;

                if ($exists) {
                    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
                    $status = '<span style="color: #10b981; font-weight: bold;">✓ Exists</span>';
                } else {
                    $status = '<span style="color: #ef4444; font-weight: bold;">✗ Missing</span>';
                }

                echo '<tr>';
                echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>' . esc_html($table_name) . '</strong></td>';
                echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;"><code>' . esc_html($table) . '</code></td>';
                echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;">' . $status . '</td>';
                echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;">Records: ' . $count . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            ?>
        </div>

        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 5px;">
            <h3 style="color: #667eea; margin-top: 0;">AJAX Endpoints Status</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <?php
                $ajax_actions = array(
                    'ck_student_login' => 'Student Login',
                    'ck_student_register' => 'Student Registration',
                    'ck_student_logout' => 'Student Logout',
                    'ck_update_student_profile' => 'Update Profile',
                    'ck_change_student_password' => 'Change Password',
                );

                foreach ($ajax_actions as $action => $label) {
                    $has_action_nopriv = has_action('wp_ajax_nopriv_' . $action);
                    $has_action_auth = has_action('wp_ajax_' . $action);

                    $status = '';
                    if ($has_action_nopriv && $has_action_auth) {
                        $status = '<span style="color: #10b981; font-weight: bold;">✓ Both</span>';
                    } elseif ($has_action_nopriv) {
                        $status = '<span style="color: #f59e0b; font-weight: bold;">⚠ Public only</span>';
                    } elseif ($has_action_auth) {
                        $status = '<span style="color: #f59e0b; font-weight: bold;">⚠ Auth only</span>';
                    } else {
                        $status = '<span style="color: #ef4444; font-weight: bold;">✗ Not registered</span>';
                    }

                    echo '<tr>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>' . esc_html($label) . '</strong></td>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;"><code>' . esc_html($action) . '</code></td>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;">' . $status . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 5px;">
            <h3 style="color: #667eea; margin-top: 0;">Post Types & Shortcodes</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="padding: 8px; border-bottom: 1px solid #ddd; background: #f9f9f9;"><strong>Custom Post Types</strong></td>
                </tr>
                <?php
                $post_types = array('ck_college', 'ck_course', 'ck_oneform');
                foreach ($post_types as $post_type) {
                    $exists = post_type_exists($post_type);
                    $status = $exists ? '<span style="color: #10b981;">✓ Registered</span>' : '<span style="color: #ef4444;">✗ Not found</span>';
                    echo '<tr>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;"><code>' . esc_html($post_type) . '</code></td>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;">' . $status . '</td>';
                    echo '</tr>';
                }
                ?>
                <tr>
                    <td colspan="2" style="padding: 8px; border-bottom: 1px solid #ddd; background: #f9f9f9; padding-top: 20px;"><strong>Shortcodes</strong></td>
                </tr>
                <?php
                $shortcodes = array(
                    'ck_oneform_home' => 'Homepage',
                    'ck_oneform_colleges' => 'Colleges List',
                    'ck_oneform_apply' => 'Application Form',
                    'ck_student_login' => 'Student Login',
                    'ck_student_dashboard' => 'Student Dashboard',
                    'ck_mega_menu' => 'Mega Menu',
                );
                foreach ($shortcodes as $shortcode => $label) {
                    $exists = shortcode_exists($shortcode);
                    $status = $exists ? '<span style="color: #10b981;">✓ Active</span>' : '<span style="color: #ef4444;">✗ Missing</span>';
                    echo '<tr>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>' . esc_html($label) . '</strong> <code>[' . esc_html($shortcode) . ']</code></td>';
                    echo '<td style="padding: 8px; border-bottom: 1px solid #ddd;">' . $status . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 5px;">
            <h3 style="color: #667eea; margin-top: 0;">Student Authentication Test</h3>
            <?php
            $student_logged_in = CK_OneForm_Student_Auth::is_student_logged_in();
            if ($student_logged_in) {
                $student = CK_OneForm_Student_Auth::get_current_student();
                echo '<p style="color: #10b981; font-weight: bold;">✓ Student is logged in</p>';
                echo '<table style="width: 100%; border-collapse: collapse;">';
                echo '<tr><td style="padding: 8px;"><strong>Student ID:</strong></td><td style="padding: 8px;">' . esc_html($student->student_id) . '</td></tr>';
                echo '<tr><td style="padding: 8px;"><strong>Name:</strong></td><td style="padding: 8px;">' . esc_html($student->full_name) . '</td></tr>';
                echo '<tr><td style="padding: 8px;"><strong>Email:</strong></td><td style="padding: 8px;">' . esc_html($student->email) . '</td></tr>';
                echo '</table>';
            } else {
                echo '<p style="color: #f59e0b;">⚠ No student logged in</p>';
                echo '<p>Session cookie: ' . (isset($_COOKIE['ck_student_session']) ? 'Present' : 'Not found') . '</p>';
            }
            ?>
        </div>

        <div style="background: #fef3c7; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #f59e0b;">
            <h3 style="color: #92400e; margin-top: 0;">⚠ Troubleshooting Steps</h3>
            <ol style="color: #78350f; margin: 0;">
                <li>If tables are missing: Go to Plugins → Deactivate "CollegeKampus OneForm" → Reactivate it</li>
                <li>If AJAX actions are not registered: Clear all caches (WordPress, server, browser)</li>
                <li>If registration fails: Check if database tables exist above</li>
                <li>If login fails: Clear browser cookies and try again</li>
                <li>For detailed errors: Enable WordPress DEBUG mode in wp-config.php</li>
            </ol>
        </div>

        <div style="background: #dbeafe; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #3b82f6;">
            <h3 style="color: #1e40af; margin-top: 0;">📋 Quick Actions</h3>
            <p style="color: #1e3a8a; margin: 10px 0;">
                <strong>Student Login Page:</strong> <a href="<?php echo home_url('/student-login/'); ?>"><?php echo home_url('/student-login/'); ?></a>
            </p>
            <p style="color: #1e3a8a; margin: 10px 0;">
                <strong>Student Dashboard:</strong> <a href="<?php echo home_url('/student-dashboard/'); ?>"><?php echo home_url('/student-dashboard/'); ?></a>
            </p>
            <p style="color: #1e3a8a; margin: 10px 0;">
                <strong>Admin Panel:</strong> <a href="<?php echo admin_url('admin.php?page=ck-student-portal'); ?>">Student Portal Admin</a>
            </p>
        </div>

        <p style="text-align: center; color: #666; margin-top: 30px;">
            Generated on <?php echo date('Y-m-d H:i:s'); ?>
        </p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ck_debug_status', 'ck_oneform_debug_shortcode');
