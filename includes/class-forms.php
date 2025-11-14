<?php
/**
 * Forms Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Forms {

    /**
     * Process application form submission
     */
    public static function process_application($form_data) {
        // Verify nonce
        if (!isset($form_data['nonce']) || !wp_verify_nonce($form_data['nonce'], 'ck-oneform-application')) {
            return array('success' => false, 'message' => __('Security check failed', 'ck-oneform'));
        }

        // Check if user is logged in
        if (!is_user_logged_in()) {
            return array('success' => false, 'message' => __('You must be logged in to submit an application', 'ck-oneform'));
        }

        $user_id = get_current_user_id();

        // Sanitize form data
        $sanitized_data = self::sanitize_form_data($form_data);

        // Validate form data
        $validation = self::validate_application_data($sanitized_data);
        if (!$validation['valid']) {
            return array('success' => false, 'message' => $validation['message']);
        }

        // Create application
        $application_id = CK_OneForm_Database::create_application(array(
            'user_id' => $user_id,
            'form_id' => isset($sanitized_data['form_id']) ? $sanitized_data['form_id'] : 0,
            'form_data' => $sanitized_data,
            'status' => 'pending'
        ));

        if ($application_id) {
            // Handle file uploads
            if (!empty($_FILES)) {
                self::handle_file_uploads($application_id, $user_id);
            }

            // Send notification emails
            CK_OneForm_Emails::send_application_confirmation($application_id);
            CK_OneForm_Emails::send_admin_notification($application_id);

            return array(
                'success' => true,
                'message' => __('Application submitted successfully', 'ck-oneform'),
                'application_id' => $application_id
            );
        }

        return array('success' => false, 'message' => __('Failed to submit application', 'ck-oneform'));
    }

    /**
     * Process student registration
     */
    public static function process_registration($form_data) {
        // Verify nonce
        if (!isset($form_data['nonce']) || !wp_verify_nonce($form_data['nonce'], 'ck-oneform-registration')) {
            return array('success' => false, 'message' => __('Security check failed', 'ck-oneform'));
        }

        // Validate registration data
        $validation = self::validate_registration_data($form_data);
        if (!$validation['valid']) {
            return array('success' => false, 'message' => $validation['message']);
        }

        // Create user account
        $user_id = wp_create_user(
            sanitize_user($form_data['username']),
            $form_data['password'],
            sanitize_email($form_data['email'])
        );

        if (is_wp_error($user_id)) {
            return array('success' => false, 'message' => $user_id->get_error_message());
        }

        // Update user meta
        update_user_meta($user_id, 'first_name', sanitize_text_field($form_data['first_name']));
        update_user_meta($user_id, 'last_name', sanitize_text_field($form_data['last_name']));
        update_user_meta($user_id, 'phone', sanitize_text_field($form_data['phone']));

        // Send welcome email
        CK_OneForm_Emails::send_welcome_email($user_id);

        // Auto login user
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        return array(
            'success' => true,
            'message' => __('Registration successful! You are now logged in.', 'ck-oneform'),
            'user_id' => $user_id
        );
    }

    /**
     * Sanitize form data
     */
    private static function sanitize_form_data($data) {
        $sanitized = array();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitize_form_data($value);
            } else {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }

        return $sanitized;
    }

    /**
     * Validate application data
     */
    private static function validate_application_data($data) {
        $required_fields = array('full_name', 'email', 'phone', 'course_id');

        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return array(
                    'valid' => false,
                    'message' => sprintf(__('Field %s is required', 'ck-oneform'), $field)
                );
            }
        }

        // Validate email
        if (!is_email($data['email'])) {
            return array('valid' => false, 'message' => __('Invalid email address', 'ck-oneform'));
        }

        return array('valid' => true);
    }

    /**
     * Validate registration data
     */
    private static function validate_registration_data($data) {
        // Check required fields
        $required = array('username', 'email', 'password', 'first_name', 'last_name', 'phone');

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return array(
                    'valid' => false,
                    'message' => sprintf(__('%s is required', 'ck-oneform'), ucfirst($field))
                );
            }
        }

        // Validate email
        if (!is_email($data['email'])) {
            return array('valid' => false, 'message' => __('Invalid email address', 'ck-oneform'));
        }

        // Check if username exists
        if (username_exists($data['username'])) {
            return array('valid' => false, 'message' => __('Username already exists', 'ck-oneform'));
        }

        // Check if email exists
        if (email_exists($data['email'])) {
            return array('valid' => false, 'message' => __('Email already registered', 'ck-oneform'));
        }

        return array('valid' => true);
    }

    /**
     * Handle file uploads
     */
    private static function handle_file_uploads($application_id, $user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_documents';

        require_once(ABSPATH . 'wp-admin/includes/file.php');

        foreach ($_FILES as $field_name => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $upload = wp_handle_upload($file, array('test_form' => false));

                if (!isset($upload['error'])) {
                    $wpdb->insert($table, array(
                        'application_id' => $application_id,
                        'user_id' => $user_id,
                        'document_type' => $field_name,
                        'file_name' => basename($upload['file']),
                        'file_path' => $upload['url'],
                        'file_size' => $file['size'],
                    ));
                }
            }
        }
    }
}
