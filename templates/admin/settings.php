<?php
/**
 * Admin Settings Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['ck_oneform_save_settings']) && check_admin_referer('ck_oneform_settings')) {
    update_option('ck_oneform_email_notifications', sanitize_text_field($_POST['email_notifications']));
    update_option('ck_oneform_admin_email', sanitize_email($_POST['admin_email']));
    update_option('ck_oneform_enable_payments', sanitize_text_field($_POST['enable_payments']));
    update_option('ck_oneform_currency', sanitize_text_field($_POST['currency']));
    update_option('ck_oneform_payment_gateway', sanitize_text_field($_POST['payment_gateway']));
    update_option('ck_oneform_razorpay_key', sanitize_text_field($_POST['razorpay_key']));
    update_option('ck_oneform_razorpay_secret', sanitize_text_field($_POST['razorpay_secret']));

    echo '<div class="notice notice-success"><p>' . __('Settings saved successfully.', 'ck-oneform') . '</p></div>';
}

$settings = CK_OneForm_Settings::get_all();
?>

<div class="wrap ck-oneform-admin">
    <h1><?php _e('OneForm Settings', 'ck-oneform'); ?></h1>

    <form method="post" action="" class="ck-oneform-settings">
        <?php wp_nonce_field('ck_oneform_settings'); ?>

        <!-- Email Settings -->
        <div class="ck-settings-section">
            <h2><?php _e('Email Settings', 'ck-oneform'); ?></h2>

            <div class="ck-form-group">
                <label for="email_notifications">
                    <input type="checkbox" id="email_notifications" name="email_notifications" value="yes" <?php checked($settings['email_notifications'], 'yes'); ?>>
                    <?php _e('Enable Email Notifications', 'ck-oneform'); ?>
                </label>
                <p class="description"><?php _e('Send email notifications for new applications and status updates', 'ck-oneform'); ?></p>
            </div>

            <div class="ck-form-group">
                <label for="admin_email"><?php _e('Admin Email Address', 'ck-oneform'); ?></label>
                <input type="email" id="admin_email" name="admin_email" value="<?php echo esc_attr($settings['admin_email']); ?>">
                <p class="description"><?php _e('Email address to receive admin notifications', 'ck-oneform'); ?></p>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="ck-settings-section">
            <h2><?php _e('Payment Settings', 'ck-oneform'); ?></h2>

            <div class="ck-form-group">
                <label for="enable_payments">
                    <input type="checkbox" id="enable_payments" name="enable_payments" value="yes" <?php checked($settings['enable_payments'], 'yes'); ?>>
                    <?php _e('Enable Payment System', 'ck-oneform'); ?>
                </label>
            </div>

            <div class="ck-form-group">
                <label for="currency"><?php _e('Currency', 'ck-oneform'); ?></label>
                <select id="currency" name="currency">
                    <option value="INR" <?php selected($settings['currency'], 'INR'); ?>>INR (₹)</option>
                    <option value="USD" <?php selected($settings['currency'], 'USD'); ?>>USD ($)</option>
                    <option value="EUR" <?php selected($settings['currency'], 'EUR'); ?>>EUR (€)</option>
                </select>
            </div>

            <div class="ck-form-group">
                <label for="payment_gateway"><?php _e('Payment Gateway', 'ck-oneform'); ?></label>
                <select id="payment_gateway" name="payment_gateway">
                    <option value="razorpay" <?php selected($settings['payment_gateway'], 'razorpay'); ?>>Razorpay</option>
                    <option value="payu" <?php selected($settings['payment_gateway'], 'payu'); ?>>PayU</option>
                    <option value="paytm" <?php selected($settings['payment_gateway'], 'paytm'); ?>>Paytm</option>
                </select>
            </div>

            <div class="ck-form-group">
                <label for="razorpay_key"><?php _e('Razorpay Key ID', 'ck-oneform'); ?></label>
                <input type="text" id="razorpay_key" name="razorpay_key" value="<?php echo esc_attr($settings['razorpay_key']); ?>">
            </div>

            <div class="ck-form-group">
                <label for="razorpay_secret"><?php _e('Razorpay Secret Key', 'ck-oneform'); ?></label>
                <input type="text" id="razorpay_secret" name="razorpay_secret" value="<?php echo esc_attr($settings['razorpay_secret']); ?>">
            </div>
        </div>

        <p class="submit">
            <button type="submit" name="ck_oneform_save_settings" class="button button-primary">
                <?php _e('Save Settings', 'ck-oneform'); ?>
            </button>
        </p>
    </form>
</div>
