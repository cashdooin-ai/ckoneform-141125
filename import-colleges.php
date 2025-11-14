<?php
/**
 * College Data Importer
 *
 * Run this once to import top 500 colleges in India
 * Usage: WordPress Admin → Tools → Import Colleges
 * Or run via WP-CLI: wp eval-file import-colleges.php
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/../../../');
    require_once(ABSPATH . 'wp-load.php');
}

class CK_OneForm_College_Importer {

    /**
     * Sample data - Top colleges in India
     * You can replace this with your actual list
     */
    public static function get_college_data() {
        return array(
            // Top Engineering Colleges
            array(
                'name' => 'Indian Institute of Technology (IIT) Delhi',
                'location' => 'New Delhi',
                'type' => 'government',
                'category' => 'engineering',
                'ranking' => 1,
                'courses' => array('B.Tech', 'M.Tech', 'PhD'),
                'description' => 'One of the premier engineering institutes in India',
            ),
            array(
                'name' => 'Indian Institute of Technology (IIT) Bombay',
                'location' => 'Mumbai, Maharashtra',
                'type' => 'government',
                'category' => 'engineering',
                'ranking' => 2,
                'courses' => array('B.Tech', 'M.Tech', 'PhD'),
                'description' => 'Leading technical institute with excellent placement records',
            ),
            array(
                'name' => 'Indian Institute of Technology (IIT) Madras',
                'location' => 'Chennai, Tamil Nadu',
                'type' => 'government',
                'category' => 'engineering',
                'ranking' => 3,
                'courses' => array('B.Tech', 'M.Tech', 'PhD'),
                'description' => 'Top engineering college with world-class research facilities',
            ),
            array(
                'name' => 'Indian Institute of Technology (IIT) Kanpur',
                'location' => 'Kanpur, Uttar Pradesh',
                'type' => 'government',
                'category' => 'engineering',
                'ranking' => 4,
                'courses' => array('B.Tech', 'M.Tech', 'PhD'),
                'description' => 'Renowned for computer science and aerospace engineering',
            ),
            array(
                'name' => 'Indian Institute of Technology (IIT) Kharagpur',
                'location' => 'Kharagpur, West Bengal',
                'type' => 'government',
                'category' => 'engineering',
                'ranking' => 5,
                'courses' => array('B.Tech', 'M.Tech', 'PhD'),
                'description' => 'First IIT established in India',
            ),

            // Top Medical Colleges
            array(
                'name' => 'All India Institute of Medical Sciences (AIIMS) Delhi',
                'location' => 'New Delhi',
                'type' => 'government',
                'category' => 'medical',
                'ranking' => 1,
                'courses' => array('MBBS', 'MD', 'MS'),
                'description' => 'Premier medical institute in India',
            ),
            array(
                'name' => 'Christian Medical College (CMC) Vellore',
                'location' => 'Vellore, Tamil Nadu',
                'type' => 'private',
                'category' => 'medical',
                'ranking' => 2,
                'courses' => array('MBBS', 'MD', 'MS'),
                'description' => 'Top private medical college',
            ),
            array(
                'name' => 'Post Graduate Institute of Medical Education and Research (PGIMER)',
                'location' => 'Chandigarh',
                'type' => 'government',
                'category' => 'medical',
                'ranking' => 3,
                'courses' => array('MBBS', 'MD', 'MS'),
                'description' => 'Leading medical research institute',
            ),

            // Top Management Colleges
            array(
                'name' => 'Indian Institute of Management (IIM) Ahmedabad',
                'location' => 'Ahmedabad, Gujarat',
                'type' => 'government',
                'category' => 'management',
                'ranking' => 1,
                'courses' => array('MBA', 'PGDM', 'Executive MBA'),
                'description' => 'Top business school in India',
            ),
            array(
                'name' => 'Indian Institute of Management (IIM) Bangalore',
                'location' => 'Bangalore, Karnataka',
                'type' => 'government',
                'category' => 'management',
                'ranking' => 2,
                'courses' => array('MBA', 'PGDM', 'Executive MBA'),
                'description' => 'Premier management institute',
            ),
            array(
                'name' => 'Indian Institute of Management (IIM) Calcutta',
                'location' => 'Kolkata, West Bengal',
                'type' => 'government',
                'category' => 'management',
                'ranking' => 3,
                'courses' => array('MBA', 'PGDM', 'Executive MBA'),
                'description' => 'One of the oldest IIMs',
            ),

            // Top Universities
            array(
                'name' => 'University of Delhi',
                'location' => 'New Delhi',
                'type' => 'government',
                'category' => 'university',
                'ranking' => 1,
                'courses' => array('BA', 'B.Sc', 'B.Com', 'MA', 'M.Sc'),
                'description' => 'Premier central university',
            ),
            array(
                'name' => 'Jawaharlal Nehru University (JNU)',
                'location' => 'New Delhi',
                'type' => 'government',
                'category' => 'university',
                'ranking' => 2,
                'courses' => array('BA', 'MA', 'MPhil', 'PhD'),
                'description' => 'Leading research university',
            ),
            array(
                'name' => 'Banaras Hindu University (BHU)',
                'location' => 'Varanasi, Uttar Pradesh',
                'type' => 'government',
                'category' => 'university',
                'ranking' => 3,
                'courses' => array('BA', 'B.Sc', 'B.Tech', 'MA', 'M.Sc'),
                'description' => 'One of the largest residential universities',
            ),

            // Add more colleges here - this is just a sample
            // You can expand this array to 500+ colleges
        );
    }

    /**
     * Extended list template - Add more colleges following this pattern
     */
    public static function get_more_colleges() {
        return array(
            // NITs (National Institutes of Technology)
            array('name' => 'NIT Trichy', 'location' => 'Tiruchirappalli, Tamil Nadu', 'type' => 'government', 'category' => 'engineering'),
            array('name' => 'NIT Warangal', 'location' => 'Warangal, Telangana', 'type' => 'government', 'category' => 'engineering'),
            array('name' => 'NIT Surathkal', 'location' => 'Surathkal, Karnataka', 'type' => 'government', 'category' => 'engineering'),
            array('name' => 'NIT Rourkela', 'location' => 'Rourkela, Odisha', 'type' => 'government', 'category' => 'engineering'),

            // IIITs (Indian Institutes of Information Technology)
            array('name' => 'IIIT Hyderabad', 'location' => 'Hyderabad, Telangana', 'type' => 'government', 'category' => 'engineering'),
            array('name' => 'IIIT Bangalore', 'location' => 'Bangalore, Karnataka', 'type' => 'government', 'category' => 'engineering'),
            array('name' => 'IIIT Delhi', 'location' => 'New Delhi', 'type' => 'government', 'category' => 'engineering'),

            // State Universities
            array('name' => 'Anna University', 'location' => 'Chennai, Tamil Nadu', 'type' => 'government', 'category' => 'university'),
            array('name' => 'Savitribai Phule Pune University', 'location' => 'Pune, Maharashtra', 'type' => 'government', 'category' => 'university'),
            array('name' => 'Jadavpur University', 'location' => 'Kolkata, West Bengal', 'type' => 'government', 'category' => 'university'),

            // Private Universities
            array('name' => 'Manipal Institute of Technology', 'location' => 'Manipal, Karnataka', 'type' => 'private', 'category' => 'engineering'),
            array('name' => 'Vellore Institute of Technology (VIT)', 'location' => 'Vellore, Tamil Nadu', 'type' => 'private', 'category' => 'engineering'),
            array('name' => 'SRM Institute of Science and Technology', 'location' => 'Chennai, Tamil Nadu', 'type' => 'private', 'category' => 'engineering'),
            array('name' => 'BITS Pilani', 'location' => 'Pilani, Rajasthan', 'type' => 'private', 'category' => 'engineering'),

            // Add 480+ more colleges here...
        );
    }

    /**
     * Import colleges into WordPress
     */
    public static function import_colleges() {
        $colleges = array_merge(self::get_college_data(), self::get_more_colleges());
        $imported = 0;
        $skipped = 0;

        foreach ($colleges as $college) {
            // Check if college already exists
            $existing = get_page_by_title($college['name'], OBJECT, 'ck_college');

            if ($existing) {
                $skipped++;
                continue;
            }

            // Create college post
            $college_id = wp_insert_post(array(
                'post_title' => $college['name'],
                'post_content' => isset($college['description']) ? $college['description'] : '',
                'post_status' => 'publish',
                'post_type' => 'ck_college',
                'post_excerpt' => isset($college['description']) ? wp_trim_words($college['description'], 20) : '',
            ));

            if (!is_wp_error($college_id)) {
                // Add meta data
                update_post_meta($college_id, '_ck_college_location', $college['location']);
                update_post_meta($college_id, '_ck_college_type', $college['type']);
                update_post_meta($college_id, '_ck_college_category', $college['category']);

                if (isset($college['ranking'])) {
                    update_post_meta($college_id, '_ck_college_ranking', $college['ranking']);
                }

                if (isset($college['courses'])) {
                    update_post_meta($college_id, '_ck_college_courses', $college['courses']);
                }

                // Set taxonomy term
                wp_set_object_terms($college_id, $college['type'], 'college_type');

                $imported++;
            }
        }

        return array(
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => count($colleges)
        );
    }

    /**
     * Import from CSV file
     */
    public static function import_from_csv($file_path) {
        if (!file_exists($file_path)) {
            return array('error' => 'File not found');
        }

        $handle = fopen($file_path, 'r');
        $headers = fgetcsv($handle); // First row as headers

        $imported = 0;
        $skipped = 0;

        while (($data = fgetcsv($handle)) !== FALSE) {
            $college = array_combine($headers, $data);

            // Check if exists
            $existing = get_page_by_title($college['name'], OBJECT, 'ck_college');
            if ($existing) {
                $skipped++;
                continue;
            }

            // Create college
            $college_id = wp_insert_post(array(
                'post_title' => $college['name'],
                'post_content' => isset($college['description']) ? $college['description'] : '',
                'post_status' => 'publish',
                'post_type' => 'ck_college',
            ));

            if (!is_wp_error($college_id)) {
                foreach ($college as $key => $value) {
                    if ($key != 'name' && $key != 'description') {
                        update_post_meta($college_id, '_ck_college_' . $key, $value);
                    }
                }
                $imported++;
            }
        }

        fclose($handle);

        return array(
            'imported' => $imported,
            'skipped' => $skipped
        );
    }
}

// Run import if accessed directly
if (php_sapi_name() === 'cli' || (isset($_GET['import_colleges']) && current_user_can('manage_options'))) {
    $result = CK_OneForm_College_Importer::import_colleges();
    echo "Import completed:\n";
    echo "Imported: " . $result['imported'] . "\n";
    echo "Skipped: " . $result['skipped'] . "\n";
    echo "Total: " . $result['total'] . "\n";
}
