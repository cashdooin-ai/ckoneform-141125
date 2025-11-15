<?php
/**
 * Database Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Database {

    /**
     * Create custom database tables
     */
    public static function create_tables() {
        global $wpdb;

        // Enable error display for debugging
        $wpdb->show_errors();

        $charset_collate = $wpdb->get_charset_collate();

        // Students table (separate from WordPress users)
        $table_students = $wpdb->prefix . 'ck_oneform_students';
        $sql_students = "CREATE TABLE IF NOT EXISTS $table_students (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id varchar(50) NOT NULL UNIQUE,
            full_name varchar(255) NOT NULL,
            email varchar(255) NOT NULL UNIQUE,
            mobile varchar(20) NOT NULL,
            password varchar(255) NOT NULL,
            dob date,
            gender varchar(20),
            category varchar(50),
            address text,
            state varchar(100),
            city varchar(100),
            pincode varchar(10),
            photo_url varchar(500),
            status varchar(20) DEFAULT 'active',
            email_verified tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            last_login datetime,
            PRIMARY KEY (id),
            KEY email (email),
            KEY student_id (student_id),
            KEY status (status)
        ) $charset_collate;";

        // Applications table (updated to link with students)
        $table_applications = $wpdb->prefix . 'ck_oneform_applications';
        $sql_applications = "CREATE TABLE IF NOT EXISTS $table_applications (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED DEFAULT 0,
            form_id bigint(20) UNSIGNED NOT NULL,
            application_number varchar(50) NOT NULL UNIQUE,
            college_ids text,
            status varchar(50) DEFAULT 'pending',
            form_data longtext,
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            updated_date datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            admin_notes text,
            PRIMARY KEY (id),
            KEY student_id (student_id),
            KEY user_id (user_id),
            KEY form_id (form_id),
            KEY status (status),
            KEY application_number (application_number)
        ) $charset_collate;";

        // Form submissions meta table
        $table_submissions_meta = $wpdb->prefix . 'ck_oneform_submissions_meta';
        $sql_submissions_meta = "CREATE TABLE IF NOT EXISTS $table_submissions_meta (
            meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            submission_id bigint(20) UNSIGNED NOT NULL,
            meta_key varchar(255),
            meta_value longtext,
            PRIMARY KEY (meta_id),
            KEY submission_id (submission_id),
            KEY meta_key (meta_key)
        ) $charset_collate;";

        // Payments table
        $table_payments = $wpdb->prefix . 'ck_oneform_payments';
        $sql_payments = "CREATE TABLE IF NOT EXISTS $table_payments (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            application_id bigint(20) UNSIGNED NOT NULL,
            student_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED DEFAULT 0,
            transaction_id varchar(100),
            amount decimal(10,2) NOT NULL,
            currency varchar(10) DEFAULT 'INR',
            payment_method varchar(50),
            status varchar(50) DEFAULT 'pending',
            payment_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY application_id (application_id),
            KEY student_id (student_id),
            KEY user_id (user_id),
            KEY transaction_id (transaction_id)
        ) $charset_collate;";

        // Documents table
        $table_documents = $wpdb->prefix . 'ck_oneform_documents';
        $sql_documents = "CREATE TABLE IF NOT EXISTS $table_documents (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            application_id bigint(20) UNSIGNED NOT NULL,
            student_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED DEFAULT 0,
            document_type varchar(100),
            file_name varchar(255),
            file_path varchar(500),
            file_size bigint(20),
            uploaded_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY application_id (application_id),
            KEY student_id (student_id),
            KEY user_id (user_id)
        ) $charset_collate;";

        // Services table
        $table_services = $wpdb->prefix . 'ck_oneform_services';
        $sql_services = "CREATE TABLE IF NOT EXISTS $table_services (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            service_type varchar(50),
            price decimal(10,2) DEFAULT 0,
            features text,
            icon varchar(100),
            status varchar(20) DEFAULT 'active',
            display_order int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY status (status)
        ) $charset_collate;";

        // Mock Tests table
        $table_mock_tests = $wpdb->prefix . 'ck_oneform_mock_tests';
        $sql_mock_tests = "CREATE TABLE IF NOT EXISTS $table_mock_tests (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            exam_type varchar(100),
            duration int(11),
            total_questions int(11),
            total_marks int(11),
            price decimal(10,2) DEFAULT 0,
            thumbnail varchar(500),
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY status (status),
            KEY exam_type (exam_type)
        ) $charset_collate;";

        // Offers table
        $table_offers = $wpdb->prefix . 'ck_oneform_offers';
        $sql_offers = "CREATE TABLE IF NOT EXISTS $table_offers (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            offer_type varchar(50),
            discount_value decimal(10,2),
            discount_type varchar(20),
            valid_from datetime,
            valid_until datetime,
            terms text,
            banner_image varchar(500),
            target_students text,
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY status (status),
            KEY valid_from (valid_from),
            KEY valid_until (valid_until)
        ) $charset_collate;";

        // Student Services (purchased services)
        $table_student_services = $wpdb->prefix . 'ck_oneform_student_services';
        $sql_student_services = "CREATE TABLE IF NOT EXISTS $table_student_services (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            service_id bigint(20) UNSIGNED NOT NULL,
            order_id varchar(100),
            amount_paid decimal(10,2),
            status varchar(50) DEFAULT 'active',
            purchased_date datetime DEFAULT CURRENT_TIMESTAMP,
            expires_date datetime,
            PRIMARY KEY (id),
            KEY student_id (student_id),
            KEY service_id (service_id),
            KEY status (status)
        ) $charset_collate;";

        // Student Mock Tests (purchased/attempted tests)
        $table_student_tests = $wpdb->prefix . 'ck_oneform_student_tests';
        $sql_student_tests = "CREATE TABLE IF NOT EXISTS $table_student_tests (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            test_id bigint(20) UNSIGNED NOT NULL,
            order_id varchar(100),
            amount_paid decimal(10,2),
            status varchar(50) DEFAULT 'purchased',
            attempts int(11) DEFAULT 0,
            best_score decimal(5,2),
            purchased_date datetime DEFAULT CURRENT_TIMESTAMP,
            last_attempt_date datetime,
            PRIMARY KEY (id),
            KEY student_id (student_id),
            KEY test_id (test_id),
            KEY status (status)
        ) $charset_collate;";

        // Student Offers (assigned offers)
        $table_student_offers = $wpdb->prefix . 'ck_oneform_student_offers';
        $sql_student_offers = "CREATE TABLE IF NOT EXISTS $table_student_offers (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            offer_id bigint(20) UNSIGNED NOT NULL,
            assigned_by bigint(20) UNSIGNED,
            is_used tinyint(1) DEFAULT 0,
            used_date datetime,
            assigned_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY student_id (student_id),
            KEY offer_id (offer_id),
            KEY is_used (is_used)
        ) $charset_collate;";

        // Student Sessions (for custom auth)
        $table_sessions = $wpdb->prefix . 'ck_oneform_student_sessions';
        $sql_sessions = "CREATE TABLE IF NOT EXISTS $table_sessions (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            session_token varchar(255) NOT NULL UNIQUE,
            ip_address varchar(50),
            user_agent text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            expires_at datetime,
            PRIMARY KEY (id),
            KEY student_id (student_id),
            KEY session_token (session_token),
            KEY expires_at (expires_at)
        ) $charset_collate;";

        // Direct table creation (more reliable than dbDelta)
        $wpdb->query($sql_students);
        $wpdb->query($sql_applications);
        $wpdb->query($sql_submissions_meta);
        $wpdb->query($sql_payments);
        $wpdb->query($sql_documents);
        $wpdb->query($sql_services);
        $wpdb->query($sql_mock_tests);
        $wpdb->query($sql_offers);
        $wpdb->query($sql_student_services);
        $wpdb->query($sql_student_tests);
        $wpdb->query($sql_student_offers);
        $wpdb->query($sql_sessions);

        // Log any errors for debugging
        if ($wpdb->last_error) {
            error_log('OneForm DB Creation Error: ' . $wpdb->last_error);
        }
    }

    /**
     * Force create tables (manual trigger)
     */
    public static function force_create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Drop and recreate approach for manual fix
        $tables = array(
            'ck_oneform_students',
            'ck_oneform_applications',
            'ck_oneform_submissions_meta',
            'ck_oneform_payments',
            'ck_oneform_documents',
            'ck_oneform_services',
            'ck_oneform_mock_tests',
            'ck_oneform_offers',
            'ck_oneform_student_services',
            'ck_oneform_student_tests',
            'ck_oneform_student_offers',
            'ck_oneform_student_sessions',
        );

        // Just call create_tables which now has fallback
        self::create_tables();

        return true;
    }

    /**
     * Get application by ID
     */
    public static function get_application($application_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $application_id));
    }

    /**
     * Get applications by user ID
     */
    public static function get_user_applications($user_id, $limit = 10, $offset = 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY submission_date DESC LIMIT %d OFFSET %d",
            $user_id, $limit, $offset
        ));
    }

    /**
     * Create new application
     */
    public static function create_application($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';

        $application_number = self::generate_application_number();

        $wpdb->insert($table, array(
            'user_id' => $data['user_id'],
            'form_id' => $data['form_id'],
            'application_number' => $application_number,
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'form_data' => isset($data['form_data']) ? json_encode($data['form_data']) : '',
        ));

        return $wpdb->insert_id;
    }

    /**
     * Update application
     */
    public static function update_application($application_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';

        return $wpdb->update($table, $data, array('id' => $application_id));
    }

    /**
     * Generate unique application number
     */
    private static function generate_application_number() {
        $prefix = 'CK' . date('Y');
        $random = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        return $prefix . $random;
    }

    /**
     * Add submission meta
     */
    public static function add_submission_meta($submission_id, $meta_key, $meta_value) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_submissions_meta';

        return $wpdb->insert($table, array(
            'submission_id' => $submission_id,
            'meta_key' => $meta_key,
            'meta_value' => maybe_serialize($meta_value)
        ));
    }

    /**
     * Get submission meta
     */
    public static function get_submission_meta($submission_id, $meta_key = '', $single = false) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_submissions_meta';

        if ($meta_key) {
            $result = $wpdb->get_var($wpdb->prepare(
                "SELECT meta_value FROM $table WHERE submission_id = %d AND meta_key = %s",
                $submission_id, $meta_key
            ));
            return maybe_unserialize($result);
        }

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT meta_key, meta_value FROM $table WHERE submission_id = %d",
            $submission_id
        ));

        $meta = array();
        foreach ($results as $row) {
            $meta[$row->meta_key] = maybe_unserialize($row->meta_value);
        }

        return $meta;
    }
}
