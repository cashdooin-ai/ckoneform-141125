<?php
/**
 * User Dashboard Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_User_Dashboard {

    /**
     * Get user applications
     */
    public static function get_user_applications($user_id) {
        return CK_OneForm_Database::get_user_applications($user_id);
    }

    /**
     * Get application statistics
     */
    public static function get_user_stats($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';

        $stats = array();

        $stats['total'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d",
            $user_id
        ));

        $stats['pending'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d AND status = 'pending'",
            $user_id
        ));

        $stats['approved'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d AND status = 'approved'",
            $user_id
        ));

        $stats['rejected'] = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d AND status = 'rejected'",
            $user_id
        ));

        return $stats;
    }

    /**
     * Get user documents
     */
    public static function get_user_documents($user_id, $application_id = 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_documents';

        if ($application_id) {
            return $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table WHERE user_id = %d AND application_id = %d",
                $user_id, $application_id
            ));
        }

        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d",
            $user_id
        ));
    }

    /**
     * Get user payments
     */
    public static function get_user_payments($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_payments';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY payment_date DESC",
            $user_id
        ));
    }
}
