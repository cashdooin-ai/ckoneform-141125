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

        $charset_collate = $wpdb->get_charset_collate();

        // Applications table
        $table_applications = $wpdb->prefix . 'ck_oneform_applications';
        $sql_applications = "CREATE TABLE IF NOT EXISTS $table_applications (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            form_id bigint(20) UNSIGNED NOT NULL,
            application_number varchar(50) NOT NULL UNIQUE,
            status varchar(50) DEFAULT 'pending',
            form_data longtext,
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            updated_date datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY form_id (form_id),
            KEY status (status)
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
            user_id bigint(20) UNSIGNED NOT NULL,
            transaction_id varchar(100),
            amount decimal(10,2) NOT NULL,
            currency varchar(10) DEFAULT 'INR',
            payment_method varchar(50),
            status varchar(50) DEFAULT 'pending',
            payment_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY application_id (application_id),
            KEY user_id (user_id),
            KEY transaction_id (transaction_id)
        ) $charset_collate;";

        // Documents table
        $table_documents = $wpdb->prefix . 'ck_oneform_documents';
        $sql_documents = "CREATE TABLE IF NOT EXISTS $table_documents (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            application_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            document_type varchar(100),
            file_name varchar(255),
            file_path varchar(500),
            file_size bigint(20),
            uploaded_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY application_id (application_id),
            KEY user_id (user_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_applications);
        dbDelta($sql_submissions_meta);
        dbDelta($sql_payments);
        dbDelta($sql_documents);
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
