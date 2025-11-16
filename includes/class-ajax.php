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

        // Contact form AJAX
        add_action('wp_ajax_ck_submit_contact_form', array($this, 'submit_contact_form'));
        add_action('wp_ajax_nopriv_ck_submit_contact_form', array($this, 'submit_contact_form'));
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

    /**
     * Submit contact form via AJAX
     */
    public function submit_contact_form() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ck_contact_form')) {
            wp_send_json_error(array('message' => __('Security check failed. Please refresh the page and try again.', 'ck-oneform')));
        }

        // Validate required fields
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            wp_send_json_error(array('message' => __('Please fill in all required fields.', 'ck-oneform')));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Please enter a valid email address.', 'ck-oneform')));
        }

        // Get admin email
        $admin_email = get_option('ck_oneform_admin_email', get_option('admin_email'));

        // Prepare email content
        $subject_map = array(
            'admission' => __('Admission Inquiry', 'ck-oneform'),
            'course' => __('Course Information', 'ck-oneform'),
            'scholarship' => __('Scholarship Query', 'ck-oneform'),
            'technical' => __('Technical Support', 'ck-oneform'),
            'feedback' => __('Feedback', 'ck-oneform'),
            'other' => __('General Inquiry', 'ck-oneform'),
        );

        $subject_text = isset($subject_map[$subject]) ? $subject_map[$subject] : $subject;

        $email_subject = sprintf(__('[CollegeKampus] New Contact Form: %s', 'ck-oneform'), $subject_text);

        $email_body = sprintf(
            __("New contact form submission:\n\nName: %s\nEmail: %s\nPhone: %s\nSubject: %s\n\nMessage:\n%s\n\n---\nSent from CollegeKampus OneForm Contact Page", 'ck-oneform'),
            $name,
            $email,
            $phone ?: 'Not provided',
            $subject_text,
            $message
        );

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );

        // Send email
        $sent = wp_mail($admin_email, $email_subject, $email_body, $headers);

        if ($sent) {
            // Send auto-reply to user
            $auto_reply_subject = __('Thank you for contacting CollegeKampus', 'ck-oneform');
            $auto_reply_body = sprintf(
                __("Dear %s,\n\nThank you for contacting CollegeKampus. We have received your message regarding \"%s\" and our team will get back to you within 24-48 hours.\n\nFor urgent queries, you can also reach us at:\nPhone: +91 11 2345 6789\nEmail: support@collegekampus.com\n\nBest regards,\nCollegeKampus Team\n\n---\nThis is an automated response. Please do not reply to this email.", 'ck-oneform'),
                $name,
                $subject_text
            );

            wp_mail($email, $auto_reply_subject, $auto_reply_body, $headers);

            wp_send_json_success(array(
                'message' => __('Thank you! Your message has been sent successfully. We will get back to you soon.', 'ck-oneform')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Sorry, there was an error sending your message. Please try again or contact us directly.', 'ck-oneform')
            ));
        }
    }
}

new CK_OneForm_Ajax();
