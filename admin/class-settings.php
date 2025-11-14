<?php
/**
 * Settings Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Settings {

    /**
     * Get setting value
     */
    public static function get($key, $default = '') {
        return get_option('ck_oneform_' . $key, $default);
    }

    /**
     * Update setting value
     */
    public static function update($key, $value) {
        return update_option('ck_oneform_' . $key, $value);
    }

    /**
     * Delete setting
     */
    public static function delete($key) {
        return delete_option('ck_oneform_' . $key);
    }

    /**
     * Get all settings
     */
    public static function get_all() {
        return array(
            'email_notifications' => self::get('email_notifications', 'yes'),
            'admin_email' => self::get('admin_email', get_option('admin_email')),
            'enable_payments' => self::get('enable_payments', 'no'),
            'currency' => self::get('currency', 'INR'),
            'payment_gateway' => self::get('payment_gateway', 'razorpay'),
            'razorpay_key' => self::get('razorpay_key'),
            'razorpay_secret' => self::get('razorpay_secret'),
        );
    }
}
