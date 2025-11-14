<?php
/**
 * Submissions Management
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Submissions {

    /**
     * Get all submissions
     */
    public static function get_all($args = array()) {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';

        $defaults = array(
            'limit' => 20,
            'offset' => 0,
            'status' => '',
            'orderby' => 'submission_date',
            'order' => 'DESC'
        );

        $args = wp_parse_args($args, $defaults);

        $where = '';
        if (!empty($args['status'])) {
            $where = $wpdb->prepare(" WHERE status = %s", $args['status']);
        }

        $query = "SELECT * FROM $table $where ORDER BY {$args['orderby']} {$args['order']} LIMIT {$args['limit']} OFFSET {$args['offset']}";

        return $wpdb->get_results($query);
    }

    /**
     * Get submission count
     */
    public static function get_count($status = '') {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';

        if ($status) {
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE status = %s", $status));
        }

        return $wpdb->get_var("SELECT COUNT(*) FROM $table");
    }

    /**
     * Get submissions by status
     */
    public static function get_by_status($status) {
        return self::get_all(array('status' => $status));
    }

    /**
     * Export submissions to CSV
     */
    public static function export_to_csv($status = '') {
        $submissions = self::get_all(array('status' => $status, 'limit' => 10000));

        if (empty($submissions)) {
            return false;
        }

        $filename = 'oneform-submissions-' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Headers
        fputcsv($output, array('Application Number', 'User ID', 'Status', 'Submission Date', 'Form Data'));

        // Data rows
        foreach ($submissions as $submission) {
            fputcsv($output, array(
                $submission->application_number,
                $submission->user_id,
                $submission->status,
                $submission->submission_date,
                $submission->form_data
            ));
        }

        fclose($output);
        exit;
    }
}
