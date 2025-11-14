<?php
/**
 * AJAX Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Ajax {

    public function __construct() {
        // Public AJAX actions
        add_action('wp_ajax_ck_oneform_submit_application', array($this, 'submit_application'));
        add_action('wp_ajax_ck_oneform_register_student', array($this, 'register_student'));
        add_action('wp_ajax_nopriv_ck_oneform_register_student', array($this, 'register_student'));
        add_action('wp_ajax_ck_oneform_check_status', array($this, 'check_application_status'));
        add_action('wp_ajax_nopriv_ck_oneform_check_status', array($this, 'check_application_status'));
        add_action('wp_ajax_ck_oneform_process_payment', array($this, 'process_payment'));

        // Admin AJAX actions
        add_action('wp_ajax_ck_oneform_update_application_status', array($this, 'update_application_status'));
        add_action('wp_ajax_ck_oneform_delete_application', array($this, 'delete_application'));
    }

    /**
     * Submit application via AJAX
     */
    public function submit_application() {
        $result = CK_OneForm_Forms::process_application($_POST);
        wp_send_json($result);
    }

    /**
     * Register student via AJAX
     */
    public function register_student() {
        $result = CK_OneForm_Forms::process_registration($_POST);
        wp_send_json($result);
    }

    /**
     * Check application status via AJAX
     */
    public function check_application_status() {
        if (empty($_POST['application_number'])) {
            wp_send_json(array(
                'success' => false,
                'message' => __('Application number is required', 'ck-oneform')
            ));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';
        $application_number = sanitize_text_field($_POST['application_number']);

        $application = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE application_number = %s",
            $application_number
        ));

        if ($application) {
            wp_send_json(array(
                'success' => true,
                'application' => $application
            ));
        }

        wp_send_json(array(
            'success' => false,
            'message' => __('Application not found', 'ck-oneform')
        ));
    }

    /**
     * Process payment via AJAX
     */
    public function process_payment() {
        if (!is_user_logged_in()) {
            wp_send_json(array('success' => false, 'message' => __('You must be logged in', 'ck-oneform')));
        }

        // Payment processing logic here
        // This would integrate with payment gateways like Razorpay, PayU, etc.

        wp_send_json(array(
            'success' => true,
            'message' => __('Payment processed successfully', 'ck-oneform')
        ));
    }

    /**
     * Update application status (Admin only)
     */
    public function update_application_status() {
        if (!current_user_can('manage_options')) {
            wp_send_json(array('success' => false, 'message' => __('Unauthorized', 'ck-oneform')));
        }

        $application_id = intval($_POST['application_id']);
        $new_status = sanitize_text_field($_POST['status']);

        $result = CK_OneForm_Database::update_application($application_id, array(
            'status' => $new_status
        ));

        if ($result !== false) {
            wp_send_json(array('success' => true, 'message' => __('Status updated', 'ck-oneform')));
        }

        wp_send_json(array('success' => false, 'message' => __('Failed to update status', 'ck-oneform')));
    }

    /**
     * Delete application (Admin only)
     */
    public function delete_application() {
        if (!current_user_can('manage_options')) {
            wp_send_json(array('success' => false, 'message' => __('Unauthorized', 'ck-oneform')));
        }

        global $wpdb;
        $application_id = intval($_POST['application_id']);
        $table = $wpdb->prefix . 'ck_oneform_applications';

        $result = $wpdb->delete($table, array('id' => $application_id));

        if ($result) {
            wp_send_json(array('success' => true, 'message' => __('Application deleted', 'ck-oneform')));
        }

        wp_send_json(array('success' => false, 'message' => __('Failed to delete', 'ck-oneform')));
    }
}

new CK_OneForm_Ajax();
