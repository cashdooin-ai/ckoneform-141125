<?php
/**
 * College Importer Admin Class
 *
 * Handles bulk import of colleges from PHP array or CSV
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_College_Importer {

    /**
     * Initialize the importer
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu_page'));
        add_action('admin_post_ck_import_colleges', array(__CLASS__, 'handle_import'));
        add_action('admin_post_ck_import_colleges_csv', array(__CLASS__, 'handle_csv_import'));
        add_action('wp_ajax_ck_delete_all_colleges', array(__CLASS__, 'delete_all_colleges'));
    }

    /**
     * Add admin menu page
     */
    public static function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=ck_oneform',
            __('Import Colleges', 'ck-oneform'),
            __('Import Colleges', 'ck-oneform'),
            'manage_options',
            'ck-import-colleges',
            array(__CLASS__, 'render_page')
        );
    }

    /**
     * Render the import page
     */
    public static function render_page() {
        // Get statistics
        $total_colleges = wp_count_posts('ck_college')->publish;
        $college_types = wp_get_object_terms(
            get_posts(array('post_type' => 'ck_college', 'fields' => 'ids', 'posts_per_page' => -1)),
            'college_type',
            array('fields' => 'names')
        );

        ?>
        <div class="wrap">
            <h1><?php _e('Import Colleges', 'ck-oneform'); ?></h1>

            <?php if (isset($_GET['imported'])): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php printf(__('%d colleges imported successfully!', 'ck-oneform'), intval($_GET['imported'])); ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['csv_imported'])): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php printf(__('%d colleges imported from CSV successfully!', 'ck-oneform'), intval($_GET['csv_imported'])); ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html($_GET['error']); ?></p>
                </div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="ck-import-stats" style="background: #fff; padding: 20px; margin: 20px 0; border-left: 4px solid #2271b1;">
                <h2>📊 Current Statistics</h2>
                <p><strong>Total Colleges:</strong> <?php echo $total_colleges; ?></p>
                <?php if (!empty($college_types)): ?>
                    <p><strong>College Types:</strong> <?php echo implode(', ', array_unique($college_types)); ?></p>
                <?php endif; ?>
            </div>

            <!-- Import from Database -->
            <div class="card" style="max-width: 800px;">
                <h2>📥 Import from Pre-loaded Database</h2>
                <p>Import 500+ top Indian colleges including IIT, NIT, IIIT, GFTI, Medical, and Private colleges.</p>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="ck_import_colleges">
                    <?php wp_nonce_field('ck_import_colleges', 'ck_import_nonce'); ?>

                    <table class="form-table">
                        <tr>
                            <th><?php _e('Import Options', 'ck-oneform'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="skip_existing" value="1" checked>
                                    <?php _e('Skip existing colleges (check by name)', 'ck-oneform'); ?>
                                </label>
                                <br>
                                <label>
                                    <input type="checkbox" name="update_existing" value="1">
                                    <?php _e('Update existing colleges with new data', 'ck-oneform'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('College Types to Import', 'ck-oneform'); ?></th>
                            <td>
                                <label><input type="checkbox" name="types[]" value="IIT" checked> IIT (23 colleges)</label><br>
                                <label><input type="checkbox" name="types[]" value="NIT" checked> NIT (31 colleges)</label><br>
                                <label><input type="checkbox" name="types[]" value="IIIT" checked> IIIT (25 colleges)</label><br>
                                <label><input type="checkbox" name="types[]" value="GFTI" checked> GFTI (Government Funded)</label><br>
                                <label><input type="checkbox" name="types[]" value="Medical" checked> Medical Colleges</label><br>
                                <label><input type="checkbox" name="types[]" value="Private" checked> Top Private Colleges</label><br>
                                <p class="description">Select which types of colleges to import</p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary button-hero">
                            🚀 Import Colleges from Database
                        </button>
                    </p>
                </form>
            </div>

            <!-- Import from CSV -->
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>📄 Import from CSV File</h2>
                <p>Upload a CSV file with college data. The CSV should have these columns:</p>
                <code>name, short_name, type, category, state, city, nirf_rank, ck_rank, established, ownership, accreditation, courses, fees_range, website</code>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="ck_import_colleges_csv">
                    <?php wp_nonce_field('ck_import_colleges_csv', 'ck_import_csv_nonce'); ?>

                    <table class="form-table">
                        <tr>
                            <th><?php _e('Select CSV File', 'ck-oneform'); ?></th>
                            <td>
                                <input type="file" name="csv_file" accept=".csv" required>
                                <p class="description">Upload a CSV file with college data</p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('CSV Options', 'ck-oneform'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="has_header" value="1" checked>
                                    <?php _e('First row is header', 'ck-oneform'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            📤 Upload and Import CSV
                        </button>
                    </p>
                </form>

                <p>
                    <a href="<?php echo CK_ONEFORM_PLUGIN_URL . 'data/college-import-sample.csv'; ?>" class="button" download>
                        ⬇️ Download Sample CSV Template
                    </a>
                </p>
            </div>

            <!-- Danger Zone -->
            <div class="card" style="max-width: 800px; margin-top: 20px; border-left: 4px solid #dc3232;">
                <h2 style="color: #dc3232;">⚠️ Danger Zone</h2>
                <p>Delete all college posts. This action cannot be undone!</p>

                <button type="button" class="button button-delete" id="delete-all-colleges" style="background: #dc3232; color: white; border-color: #dc3232;">
                    🗑️ Delete All Colleges
                </button>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#delete-all-colleges').on('click', function() {
                if (!confirm('Are you sure you want to delete ALL college posts? This cannot be undone!')) {
                    return;
                }

                if (!confirm('This will permanently delete <?php echo $total_colleges; ?> college posts. Are you absolutely sure?')) {
                    return;
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ck_delete_all_colleges',
                        nonce: '<?php echo wp_create_nonce('delete_all_colleges'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.data);
                        }
                    }
                });
            });
        });
        </script>

        <style>
        .ck-import-stats {
            display: grid;
            gap: 10px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .card h2 {
            margin-top: 0;
        }
        .button-hero {
            font-size: 14px;
            height: 46px;
            line-height: 44px;
            padding: 0 36px;
        }
        </style>
        <?php
    }

    /**
     * Handle import from database
     */
    public static function handle_import() {
        // Verify nonce
        if (!isset($_POST['ck_import_nonce']) || !wp_verify_nonce($_POST['ck_import_nonce'], 'ck_import_colleges')) {
            wp_die('Security check failed');
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        // Load colleges data
        $colleges_file = CK_ONEFORM_PLUGIN_DIR . 'data/indian-colleges-comprehensive.php';
        if (!file_exists($colleges_file)) {
            wp_redirect(admin_url('edit.php?post_type=ck_oneform&page=ck-import-colleges&error=' . urlencode('Colleges data file not found')));
            exit;
        }

        $all_colleges = include $colleges_file;

        // Filter by types if specified
        $selected_types = isset($_POST['types']) ? $_POST['types'] : array();
        if (!empty($selected_types)) {
            $all_colleges = array_filter($all_colleges, function($college) use ($selected_types) {
                return in_array($college['type'], $selected_types);
            });
        }

        $skip_existing = isset($_POST['skip_existing']);
        $update_existing = isset($_POST['update_existing']);
        $imported_count = 0;

        foreach ($all_colleges as $college_data) {
            $result = self::import_college($college_data, $skip_existing, $update_existing);
            if ($result) {
                $imported_count++;
            }
        }

        wp_redirect(admin_url('edit.php?post_type=ck_oneform&page=ck-import-colleges&imported=' . $imported_count));
        exit;
    }

    /**
     * Handle CSV import
     */
    public static function handle_csv_import() {
        // Verify nonce
        if (!isset($_POST['ck_import_csv_nonce']) || !wp_verify_nonce($_POST['ck_import_csv_nonce'], 'ck_import_colleges_csv')) {
            wp_die('Security check failed');
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }

        // Check if file was uploaded
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            wp_redirect(admin_url('edit.php?post_type=ck_oneform&page=ck-import-colleges&error=' . urlencode('File upload failed')));
            exit;
        }

        $csv_file = $_FILES['csv_file']['tmp_name'];
        $has_header = isset($_POST['has_header']);

        $imported_count = 0;
        $row_number = 0;

        if (($handle = fopen($csv_file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row_number++;

                // Skip header row
                if ($has_header && $row_number === 1) {
                    continue;
                }

                // Parse CSV row
                $college_data = self::parse_csv_row($row);

                if ($college_data) {
                    $result = self::import_college($college_data, true, false);
                    if ($result) {
                        $imported_count++;
                    }
                }
            }
            fclose($handle);
        }

        wp_redirect(admin_url('edit.php?post_type=ck_oneform&page=ck-import-colleges&csv_imported=' . $imported_count));
        exit;
    }

    /**
     * Parse CSV row into college data
     */
    private static function parse_csv_row($row) {
        if (count($row) < 14) {
            return false;
        }

        return array(
            'name' => $row[0],
            'short_name' => $row[1],
            'type' => $row[2],
            'category' => $row[3],
            'state' => $row[4],
            'city' => $row[5],
            'nirf_rank' => intval($row[6]),
            'ck_rank' => intval($row[7]),
            'established' => intval($row[8]),
            'ownership' => $row[9],
            'accreditation' => $row[10],
            'courses' => $row[11],
            'fees_range' => $row[12],
            'website' => $row[13],
        );
    }

    /**
     * Import a single college
     */
    private static function import_college($college_data, $skip_existing = true, $update_existing = false) {
        // Check if college already exists
        $existing = get_page_by_title($college_data['name'], OBJECT, 'ck_college');

        if ($existing) {
            if ($skip_existing && !$update_existing) {
                return false;
            }
            $post_id = $existing->ID;
            $is_update = true;
        } else {
            $is_update = false;
        }

        // Prepare post data
        $post_data = array(
            'post_title' => $college_data['name'],
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'ck_college',
        );

        // Insert or update post
        if ($is_update) {
            $post_data['ID'] = $post_id;
            wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if (!$post_id || is_wp_error($post_id)) {
            return false;
        }

        // Save meta data
        update_post_meta($post_id, 'short_name', $college_data['short_name']);
        update_post_meta($post_id, 'college_type', $college_data['type']);
        update_post_meta($post_id, 'category', $college_data['category']);
        update_post_meta($post_id, 'state', $college_data['state']);
        update_post_meta($post_id, 'city', $college_data['city']);
        update_post_meta($post_id, 'nirf_rank', $college_data['nirf_rank']);
        update_post_meta($post_id, 'ck_rank', $college_data['ck_rank']);
        update_post_meta($post_id, 'established', $college_data['established']);
        update_post_meta($post_id, 'ownership', $college_data['ownership']);
        update_post_meta($post_id, 'accreditation', $college_data['accreditation']);
        update_post_meta($post_id, 'courses', $college_data['courses']);
        update_post_meta($post_id, 'fees_range', $college_data['fees_range']);
        update_post_meta($post_id, 'website', $college_data['website']);

        // Set college type taxonomy
        wp_set_object_terms($post_id, $college_data['type'], 'college_type');
        wp_set_object_terms($post_id, $college_data['state'], 'college_state');
        wp_set_object_terms($post_id, $college_data['city'], 'college_city');

        return true;
    }

    /**
     * Delete all colleges
     */
    public static function delete_all_colleges() {
        check_ajax_referer('delete_all_colleges', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $colleges = get_posts(array(
            'post_type' => 'ck_college',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));

        $deleted = 0;
        foreach ($colleges as $college_id) {
            if (wp_delete_post($college_id, true)) {
                $deleted++;
            }
        }

        wp_send_json_success(array('message' => sprintf('%d colleges deleted successfully', $deleted)));
    }
}

// Initialize the importer
CK_OneForm_College_Importer::init();
