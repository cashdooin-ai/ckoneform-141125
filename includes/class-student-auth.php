<?php
/**
 * Student Authentication System
 * Separate from WordPress login
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Student_Auth {

    /**
     * Initialize authentication hooks
     */
    public static function init() {
        add_action('wp_ajax_nopriv_ck_student_login', array(__CLASS__, 'ajax_login'));
        add_action('wp_ajax_nopriv_ck_student_register', array(__CLASS__, 'ajax_register'));
        add_action('wp_ajax_ck_student_logout', array(__CLASS__, 'ajax_logout'));
        add_action('wp_ajax_nopriv_ck_student_logout', array(__CLASS__, 'ajax_logout'));
        add_action('wp_ajax_ck_update_student_profile', array(__CLASS__, 'ajax_update_profile'));
        add_action('wp_ajax_ck_change_student_password', array(__CLASS__, 'ajax_change_password'));
        add_action('init', array(__CLASS__, 'check_session'));
    }

    /**
     * Register a new student
     */
    public static function register_student($data) {
        global $wpdb;

        // Validate required fields
        $required = array('full_name', 'email', 'mobile', 'password');
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return array('success' => false, 'message' => ucfirst($field) . ' is required');
            }
        }

        // Validate email
        if (!is_email($data['email'])) {
            return array('success' => false, 'message' => 'Invalid email address');
        }

        // Check if email already exists
        $table = $wpdb->prefix . 'ck_oneform_students';
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE email = %s",
            $data['email']
        ));

        if ($existing) {
            return array('success' => false, 'message' => 'Email already registered');
        }

        // Generate unique student ID
        $student_id = self::generate_student_id();

        // Hash password
        $hashed_password = wp_hash_password($data['password']);

        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
        if (!$table_exists) {
            return array('success' => false, 'message' => 'Database error: Student table not found. Please contact administrator.');
        }

        // Insert student
        $inserted = $wpdb->insert($table, array(
            'student_id' => $student_id,
            'full_name' => sanitize_text_field($data['full_name']),
            'email' => sanitize_email($data['email']),
            'mobile' => sanitize_text_field($data['mobile']),
            'password' => $hashed_password,
            'dob' => !empty($data['dob']) ? sanitize_text_field($data['dob']) : null,
            'gender' => !empty($data['gender']) ? sanitize_text_field($data['gender']) : null,
            'category' => !empty($data['category']) ? sanitize_text_field($data['category']) : null,
            'address' => !empty($data['address']) ? sanitize_textarea_field($data['address']) : null,
            'state' => !empty($data['state']) ? sanitize_text_field($data['state']) : null,
            'city' => !empty($data['city']) ? sanitize_text_field($data['city']) : null,
            'pincode' => !empty($data['pincode']) ? sanitize_text_field($data['pincode']) : null,
        ));

        if ($inserted) {
            $student_data = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table WHERE id = %d",
                $wpdb->insert_id
            ));

            // Send welcome email
            self::send_welcome_email($student_data);

            return array(
                'success' => true,
                'message' => 'Registration successful! Please login.',
                'student_id' => $student_id
            );
        }

        // Get the actual error message
        $error_message = $wpdb->last_error ? $wpdb->last_error : 'Registration failed. Please try again.';
        return array('success' => false, 'message' => $error_message);
    }

    /**
     * Login student
     */
    public static function login_student($email, $password) {
        global $wpdb;

        $table = $wpdb->prefix . 'ck_oneform_students';

        // Get student by email
        $student = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE email = %s AND status = 'active'",
            $email
        ));

        if (!$student) {
            return array('success' => false, 'message' => 'Invalid email or password');
        }

        // Verify password
        if (!wp_check_password($password, $student->password)) {
            return array('success' => false, 'message' => 'Invalid email or password');
        }

        // Create session
        $session_token = self::create_session($student->id);

        if ($session_token) {
            // Update last login
            $wpdb->update($table, array(
                'last_login' => current_time('mysql')
            ), array('id' => $student->id));

            return array(
                'success' => true,
                'message' => 'Login successful!',
                'student' => array(
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'full_name' => $student->full_name,
                    'email' => $student->email,
                    'mobile' => $student->mobile,
                ),
                'session_token' => $session_token
            );
        }

        return array('success' => false, 'message' => 'Failed to create session');
    }

    /**
     * Create session for student
     */
    private static function create_session($student_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'ck_oneform_student_sessions';
        $session_token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+7 days'));

        $inserted = $wpdb->insert($table, array(
            'student_id' => $student_id,
            'session_token' => $session_token,
            'ip_address' => self::get_ip_address(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
            'expires_at' => $expires_at
        ));

        if ($inserted) {
            // Set cookie
            setcookie('ck_student_session', $session_token, strtotime('+7 days'), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
            return $session_token;
        }

        return false;
    }

    /**
     * Check if student is logged in
     */
    public static function is_student_logged_in() {
        return isset($_COOKIE['ck_student_session']) && self::validate_session($_COOKIE['ck_student_session']);
    }

    /**
     * Get current logged in student
     */
    public static function get_current_student() {
        if (!isset($_COOKIE['ck_student_session'])) {
            return false;
        }

        global $wpdb;
        $sessions_table = $wpdb->prefix . 'ck_oneform_student_sessions';
        $students_table = $wpdb->prefix . 'ck_oneform_students';

        $student = $wpdb->get_row($wpdb->prepare(
            "SELECT s.* FROM $students_table s
            INNER JOIN $sessions_table sess ON s.id = sess.student_id
            WHERE sess.session_token = %s
            AND sess.expires_at > NOW()
            AND s.status = 'active'",
            $_COOKIE['ck_student_session']
        ));

        return $student ?: false;
    }

    /**
     * Validate session
     */
    private static function validate_session($token) {
        global $wpdb;

        $table = $wpdb->prefix . 'ck_oneform_student_sessions';

        $valid = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table
            WHERE session_token = %s
            AND expires_at > NOW()",
            $token
        ));

        return $valid > 0;
    }

    /**
     * Logout student
     */
    public static function logout_student() {
        if (isset($_COOKIE['ck_student_session'])) {
            global $wpdb;
            $table = $wpdb->prefix . 'ck_oneform_student_sessions';

            // Delete session
            $wpdb->delete($table, array('session_token' => $_COOKIE['ck_student_session']));

            // Delete cookie
            setcookie('ck_student_session', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
            unset($_COOKIE['ck_student_session']);
        }

        return array('success' => true, 'message' => 'Logged out successfully');
    }

    /**
     * Check session on init
     */
    public static function check_session() {
        if (isset($_COOKIE['ck_student_session'])) {
            if (!self::validate_session($_COOKIE['ck_student_session'])) {
                // Invalid or expired session, clear cookie
                setcookie('ck_student_session', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
                unset($_COOKIE['ck_student_session']);
            }
        }
    }

    /**
     * AJAX Login Handler
     */
    public static function ajax_login() {
        check_ajax_referer('ck-student-auth', 'nonce');

        $email = sanitize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            wp_send_json_error(array('message' => 'Email and password are required'));
        }

        $result = self::login_student($email, $password);

        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }

    /**
     * AJAX Registration Handler
     */
    public static function ajax_register() {
        try {
            // Verify nonce with die=false to handle gracefully
            if (!wp_verify_nonce($_POST['nonce'] ?? '', 'ck-student-auth')) {
                wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
                return;
            }

            $data = array(
                'full_name' => sanitize_text_field($_POST['full_name'] ?? ''),
                'email' => sanitize_email($_POST['email'] ?? ''),
                'mobile' => sanitize_text_field($_POST['mobile'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'dob' => sanitize_text_field($_POST['dob'] ?? ''),
                'gender' => sanitize_text_field($_POST['gender'] ?? ''),
                'category' => sanitize_text_field($_POST['category'] ?? ''),
            );

            // Log for debugging
            error_log('OneForm Registration Attempt: ' . print_r($data, true));

            $result = self::register_student($data);

            // Log result
            error_log('OneForm Registration Result: ' . print_r($result, true));

            if ($result['success']) {
                wp_send_json_success($result);
            } else {
                wp_send_json_error($result);
            }
        } catch (Exception $e) {
            error_log('OneForm Registration Exception: ' . $e->getMessage());
            wp_send_json_error(array('message' => 'Error: ' . $e->getMessage()));
        }
    }

    /**
     * AJAX Logout Handler
     */
    public static function ajax_logout() {
        $result = self::logout_student();
        wp_send_json_success($result);
    }

    /**
     * Generate unique student ID
     */
    private static function generate_student_id() {
        $prefix = 'STU' . date('Y');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return $prefix . $random;
    }

    /**
     * Get IP Address
     */
    private static function get_ip_address() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '';
        }
    }

    /**
     * Send welcome email
     */
    private static function send_welcome_email($student) {
        $to = $student->email;
        $subject = 'Welcome to CollegeKampus OneForm';
        $message = "Hello {$student->full_name},\n\n";
        $message .= "Welcome to CollegeKampus OneForm!\n\n";
        $message .= "Your Student ID: {$student->student_id}\n";
        $message .= "Email: {$student->email}\n\n";
        $message .= "You can now login to your dashboard and start applying to colleges.\n\n";
        $message .= "Login here: " . home_url('/student-login/') . "\n\n";
        $message .= "Thank you!\nCollegeKampus Team";

        wp_mail($to, $subject, $message);
    }

    /**
     * Get student by ID
     */
    public static function get_student_by_id($student_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_students';

        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $student_id
        ));
    }

    /**
     * Update student profile
     */
    public static function update_student_profile($student_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_students';

        $update_data = array();

        $allowed_fields = array('full_name', 'mobile', 'dob', 'gender', 'category', 'address', 'state', 'city', 'pincode', 'photo_url');

        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $update_data[$field] = sanitize_text_field($data[$field]);
            }
        }

        if (empty($update_data)) {
            return false;
        }

        return $wpdb->update($table, $update_data, array('id' => $student_id));
    }

    /**
     * AJAX Update Profile Handler
     */
    public static function ajax_update_profile() {
        check_ajax_referer('update-profile', 'nonce');

        if (!self::is_student_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }

        $student = self::get_current_student();

        $data = array(
            'full_name' => sanitize_text_field($_POST['full_name'] ?? ''),
            'mobile' => sanitize_text_field($_POST['mobile'] ?? ''),
            'dob' => sanitize_text_field($_POST['dob'] ?? ''),
            'gender' => sanitize_text_field($_POST['gender'] ?? ''),
            'category' => sanitize_text_field($_POST['category'] ?? ''),
            'address' => sanitize_textarea_field($_POST['address'] ?? ''),
            'state' => sanitize_text_field($_POST['state'] ?? ''),
            'city' => sanitize_text_field($_POST['city'] ?? ''),
            'pincode' => sanitize_text_field($_POST['pincode'] ?? ''),
        );

        $result = self::update_student_profile($student->id, $data);

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Profile updated successfully!'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update profile'));
        }
    }

    /**
     * AJAX Change Password Handler
     */
    public static function ajax_change_password() {
        check_ajax_referer('change-password', 'nonce');

        if (!self::is_student_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }

        $student = self::get_current_student();

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate inputs
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            wp_send_json_error(array('message' => 'All password fields are required'));
        }

        if ($new_password !== $confirm_password) {
            wp_send_json_error(array('message' => 'New passwords do not match'));
        }

        if (strlen($new_password) < 6) {
            wp_send_json_error(array('message' => 'Password must be at least 6 characters'));
        }

        // Verify current password
        if (!wp_check_password($current_password, $student->password)) {
            wp_send_json_error(array('message' => 'Current password is incorrect'));
        }

        // Update password
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_students';
        $hashed_password = wp_hash_password($new_password);

        $result = $wpdb->update(
            $table,
            array('password' => $hashed_password),
            array('id' => $student->id)
        );

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Password changed successfully!'));
        } else {
            wp_send_json_error(array('message' => 'Failed to change password'));
        }
    }
}

// Initialize
CK_OneForm_Student_Auth::init();
