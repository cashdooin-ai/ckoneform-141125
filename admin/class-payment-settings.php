<?php
/**
 * Payment Gateway Settings
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Payment_Settings {

    /**
     * Initialize
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu'));
        add_action('admin_post_ck_save_payment_settings', array(__CLASS__, 'save_settings'));
    }

    /**
     * Add menu
     */
    public static function add_menu() {
        add_submenu_page(
            'ck-student-portal',
            'Payment Settings',
            'Payment Settings',
            'manage_options',
            'ck-payment-settings',
            array(__CLASS__, 'settings_page')
        );
    }

    /**
     * Settings page
     */
    public static function settings_page() {
        $settings = get_option('ck_payment_settings', array(
            'razorpay_key_id' => '',
            'razorpay_key_secret' => '',
            'razorpay_enabled' => false,
            'instamojo_api_key' => '',
            'instamojo_auth_token' => '',
            'instamojo_salt' => '',
            'instamojo_enabled' => false,
            'instamojo_sandbox' => true,
            'upi_id' => '',
            'upi_name' => '',
            'upi_enabled' => false,
            'currency' => 'INR',
            'success_page' => '',
            'failure_page' => '',
        ));

        if (isset($_GET['settings_saved'])) {
            echo '<div class="notice notice-success"><p>Payment settings saved successfully!</p></div>';
        }
        ?>
        <div class="wrap ck-payment-settings">
            <h1>
                <span class="dashicons dashicons-money-alt" style="font-size: 30px; margin-right: 10px;"></span>
                Payment Gateway Settings
            </h1>
            <p>Configure payment gateways for paid mock tests and services.</p>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ck_save_payment_settings">
                <?php wp_nonce_field('ck_payment_settings_nonce', 'payment_nonce'); ?>

                <!-- Razorpay Settings -->
                <div class="gateway-section">
                    <div class="gateway-header">
                        <img src="https://cdn.razorpay.com/logo.svg" alt="Razorpay" style="height: 30px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="razorpay_enabled" value="1" <?php checked(!empty($settings['razorpay_enabled'])); ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="gateway-desc">Accept payments via Credit/Debit Cards, UPI, Netbanking, Wallets</p>

                    <div class="gateway-fields">
                        <div class="field-group">
                            <label>Key ID</label>
                            <input type="text" name="razorpay_key_id" value="<?php echo esc_attr($settings['razorpay_key_id']); ?>" placeholder="rzp_live_xxxxxx">
                        </div>
                        <div class="field-group">
                            <label>Key Secret</label>
                            <input type="password" name="razorpay_key_secret" value="<?php echo esc_attr($settings['razorpay_key_secret']); ?>" placeholder="Your secret key">
                        </div>
                    </div>
                    <p class="gateway-help">
                        Get your API keys from <a href="https://dashboard.razorpay.com/app/keys" target="_blank">Razorpay Dashboard</a>
                    </p>
                </div>

                <!-- Instamojo Settings -->
                <div class="gateway-section">
                    <div class="gateway-header">
                        <img src="https://www.instamojo.com/assets/images/site/logo-instamojo.svg" alt="Instamojo" style="height: 30px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="instamojo_enabled" value="1" <?php checked(!empty($settings['instamojo_enabled'])); ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="gateway-desc">Accept payments via Instamojo payment links</p>

                    <div class="gateway-fields">
                        <div class="field-group">
                            <label>API Key</label>
                            <input type="text" name="instamojo_api_key" value="<?php echo esc_attr($settings['instamojo_api_key']); ?>" placeholder="Your API key">
                        </div>
                        <div class="field-group">
                            <label>Auth Token</label>
                            <input type="password" name="instamojo_auth_token" value="<?php echo esc_attr($settings['instamojo_auth_token']); ?>" placeholder="Your auth token">
                        </div>
                        <div class="field-group">
                            <label>Private Salt</label>
                            <input type="password" name="instamojo_salt" value="<?php echo esc_attr($settings['instamojo_salt']); ?>" placeholder="Your private salt">
                        </div>
                        <div class="field-group">
                            <label>
                                <input type="checkbox" name="instamojo_sandbox" value="1" <?php checked(!empty($settings['instamojo_sandbox'])); ?>>
                                Enable Sandbox Mode (for testing)
                            </label>
                        </div>
                    </div>
                    <p class="gateway-help">
                        Get your API keys from <a href="https://www.instamojo.com/integrations" target="_blank">Instamojo Dashboard</a>
                    </p>
                </div>

                <!-- UPI Direct Settings -->
                <div class="gateway-section">
                    <div class="gateway-header">
                        <div style="font-size: 24px; font-weight: bold; color: #5f259f;">UPI</div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="upi_enabled" value="1" <?php checked(!empty($settings['upi_enabled'])); ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="gateway-desc">Accept UPI payments directly to your UPI ID (manual verification required)</p>

                    <div class="gateway-fields">
                        <div class="field-group">
                            <label>Your UPI ID</label>
                            <input type="text" name="upi_id" value="<?php echo esc_attr($settings['upi_id']); ?>" placeholder="yourname@upi">
                        </div>
                        <div class="field-group">
                            <label>Payee Name</label>
                            <input type="text" name="upi_name" value="<?php echo esc_attr($settings['upi_name']); ?>" placeholder="Your Name or Business Name">
                        </div>
                    </div>
                    <p class="gateway-help">
                        Students will see your UPI ID and pay directly. You'll need to manually verify payments.
                    </p>
                </div>

                <!-- General Settings -->
                <div class="gateway-section">
                    <h3><span class="dashicons dashicons-admin-generic"></span> General Settings</h3>
                    <div class="gateway-fields">
                        <div class="field-group">
                            <label>Currency</label>
                            <select name="currency">
                                <option value="INR" <?php selected($settings['currency'], 'INR'); ?>>INR (₹)</option>
                                <option value="USD" <?php selected($settings['currency'], 'USD'); ?>>USD ($)</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label>Success Redirect Page</label>
                            <input type="url" name="success_page" value="<?php echo esc_attr($settings['success_page']); ?>" placeholder="<?php echo home_url('/payment-success/'); ?>">
                        </div>
                        <div class="field-group">
                            <label>Failure Redirect Page</label>
                            <input type="url" name="failure_page" value="<?php echo esc_attr($settings['failure_page']); ?>" placeholder="<?php echo home_url('/payment-failed/'); ?>">
                        </div>
                    </div>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <span class="dashicons dashicons-saved" style="vertical-align: middle;"></span>
                        Save Payment Settings
                    </button>
                </p>
            </form>

            <!-- Payment Transactions -->
            <div class="gateway-section">
                <h3><span class="dashicons dashicons-list-view"></span> Recent Transactions</h3>
                <?php self::render_transactions(); ?>
            </div>
        </div>

        <style>
        .ck-payment-settings {
            max-width: 900px;
        }

        .gateway-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .gateway-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .gateway-desc {
            color: #666;
            margin: 0 0 20px;
        }

        .gateway-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field-group label {
            font-weight: 600;
            color: #333;
        }

        .field-group input,
        .field-group select {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .field-group input:focus,
        .field-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .gateway-help {
            margin-top: 15px;
            font-size: 13px;
            color: #666;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
        }

        .gateway-help a {
            color: #667eea;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #10b981;
        }

        input:checked + .slider:before {
            transform: translateX(30px);
        }

        @media (max-width: 768px) {
            .gateway-fields {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
    }

    /**
     * Render transactions
     */
    private static function render_transactions() {
        global $wpdb;

        // Get recent transactions from assignments
        $table = $wpdb->prefix . 'ck_oneform_test_assignments';
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");

        if (!$table_exists) {
            echo '<p>No transactions yet.</p>';
            return;
        }

        $transactions = $wpdb->get_results("
            SELECT a.*, s.full_name, s.email, t.title as test_title
            FROM $table a
            LEFT JOIN {$wpdb->prefix}ck_oneform_students s ON a.student_id = s.id
            LEFT JOIN {$wpdb->prefix}ck_oneform_mock_tests t ON a.test_id = t.id
            WHERE a.payment_status != 'pending' OR a.payment_amount > 0
            ORDER BY a.assigned_at DESC
            LIMIT 20
        ");

        if (empty($transactions)) {
            echo '<p>No transactions yet.</p>';
            return;
        }

        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Test</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment ID</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $txn): ?>
                <tr>
                    <td>
                        <strong><?php echo esc_html($txn->full_name); ?></strong><br>
                        <small><?php echo esc_html($txn->email); ?></small>
                    </td>
                    <td><?php echo esc_html($txn->test_title); ?></td>
                    <td>₹<?php echo number_format($txn->payment_amount, 2); ?></td>
                    <td>
                        <span class="status-<?php echo esc_attr($txn->payment_status); ?>">
                            <?php echo ucfirst($txn->payment_status); ?>
                        </span>
                    </td>
                    <td><?php echo esc_html($txn->payment_id ?: '-'); ?></td>
                    <td><?php echo date('M d, Y', strtotime($txn->assigned_at)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <style>
        .status-verified { color: #10b981; font-weight: 600; }
        .status-pending { color: #f59e0b; font-weight: 600; }
        .status-failed { color: #ef4444; font-weight: 600; }
        </style>
        <?php
    }

    /**
     * Save settings
     */
    public static function save_settings() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_payment_settings_nonce', 'payment_nonce');

        $settings = array(
            'razorpay_key_id' => sanitize_text_field($_POST['razorpay_key_id'] ?? ''),
            'razorpay_key_secret' => sanitize_text_field($_POST['razorpay_key_secret'] ?? ''),
            'razorpay_enabled' => isset($_POST['razorpay_enabled']) ? true : false,
            'instamojo_api_key' => sanitize_text_field($_POST['instamojo_api_key'] ?? ''),
            'instamojo_auth_token' => sanitize_text_field($_POST['instamojo_auth_token'] ?? ''),
            'instamojo_salt' => sanitize_text_field($_POST['instamojo_salt'] ?? ''),
            'instamojo_enabled' => isset($_POST['instamojo_enabled']) ? true : false,
            'instamojo_sandbox' => isset($_POST['instamojo_sandbox']) ? true : false,
            'upi_id' => sanitize_text_field($_POST['upi_id'] ?? ''),
            'upi_name' => sanitize_text_field($_POST['upi_name'] ?? ''),
            'upi_enabled' => isset($_POST['upi_enabled']) ? true : false,
            'currency' => sanitize_text_field($_POST['currency'] ?? 'INR'),
            'success_page' => esc_url_raw($_POST['success_page'] ?? ''),
            'failure_page' => esc_url_raw($_POST['failure_page'] ?? ''),
        );

        update_option('ck_payment_settings', $settings);

        wp_redirect(admin_url('admin.php?page=ck-payment-settings&settings_saved=1'));
        exit;
    }
}

// Initialize
CK_OneForm_Payment_Settings::init();
