<?php
/**
 * Service Page Settings
 * Admin settings to control service pages and their data display
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Service_Settings {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_page'), 30);
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }

    /**
     * Add settings page to admin menu
     */
    public static function add_settings_page() {
        add_submenu_page(
            'ck-oneform',
            'Service Page Settings',
            'Service Settings',
            'manage_options',
            'ck-service-settings',
            array(__CLASS__, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('ck_service_settings', 'ck_service_display_options');
        register_setting('ck_service_settings', 'ck_service_featured_list');
    }

    /**
     * Render settings page
     */
    public static function render_settings_page() {
        // Get current settings
        $display_options = get_option('ck_service_display_options', array(
            'show_scholarships' => true,
            'show_exams' => true,
            'show_careers' => true,
            'show_loans' => true,
            'show_rankings' => true,
            'show_jobs' => true,
        ));

        $featured_services = get_option('ck_service_featured_list', array(
            'college-search', 'scholarships-database', 'exam-calendar',
            'career-guidance', 'education-loans', 'mock-tests',
            'college-comparison', 'application-tracking', 'document-verification'
        ));

        // Handle form submission
        if (isset($_POST['ck_service_settings_nonce']) && wp_verify_nonce($_POST['ck_service_settings_nonce'], 'ck_service_settings')) {
            // Save display options
            $new_display_options = array();
            $new_display_options['show_scholarships'] = isset($_POST['show_scholarships']);
            $new_display_options['show_exams'] = isset($_POST['show_exams']);
            $new_display_options['show_careers'] = isset($_POST['show_careers']);
            $new_display_options['show_loans'] = isset($_POST['show_loans']);
            $new_display_options['show_rankings'] = isset($_POST['show_rankings']);
            $new_display_options['show_jobs'] = isset($_POST['show_jobs']);

            update_option('ck_service_display_options', $new_display_options);

            // Save featured services
            if (isset($_POST['featured_services']) && is_array($_POST['featured_services'])) {
                update_option('ck_service_featured_list', array_map('sanitize_text_field', $_POST['featured_services']));
            }

            echo '<div class="notice notice-success"><p>Service settings saved successfully!</p></div>';

            // Refresh options
            $display_options = $new_display_options;
        }

        // Load all available services
        $services_data_file = CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';
        $all_services = array();

        if (file_exists($services_data_file)) {
            $services_data = include $services_data_file;
            foreach ($services_data as $category => $category_services) {
                foreach ($category_services as $service) {
                    $all_services[] = $service;
                }
            }
        }
        ?>

        <div class="wrap">
            <h1>📋 Service Page Settings</h1>
            <p class="description">Control which data sections appear on service pages and configure featured services for student dashboard</p>

            <form method="post" action="">
                <?php wp_nonce_field('ck_service_settings', 'ck_service_settings_nonce'); ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
                    <!-- Left Column: Data Display Options -->
                    <div class="card" style="padding: 20px;">
                        <h2 style="margin-top: 0;">🎯 Seeded Data Display Options</h2>
                        <p class="description">Choose which types of seeded data should appear on relevant service pages</p>

                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="show_scholarships">
                                        🎓 Scholarships Data
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_scholarships" id="show_scholarships" value="1" <?php checked(!empty($display_options['show_scholarships'])); ?>>
                                        Show scholarships on scholarship-related service pages
                                    </label>
                                    <p class="description">Displays on: Scholarships Database, Financial Aid, Merit Scholarships, etc.</p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="show_exams">
                                        📅 Exam Calendar
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_exams" id="show_exams" value="1" <?php checked(!empty($display_options['show_exams'])); ?>>
                                        Show exam calendar on exam-related service pages
                                    </label>
                                    <p class="description">Displays on: Exam Calendar, Entrance Exams, Exam Prep, JEE/NEET/CAT pages, etc.</p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="show_careers">
                                        💼 Career Paths
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_careers" id="show_careers" value="1" <?php checked(!empty($display_options['show_careers'])); ?>>
                                        Show career information on career-related service pages
                                    </label>
                                    <p class="description">Displays on: Career Guidance, Career Paths, Career Counseling, Job Profiles, etc.</p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="show_loans">
                                        💰 Education Loans
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_loans" id="show_loans" value="1" <?php checked(!empty($display_options['show_loans'])); ?>>
                                        Show loan options on financial service pages
                                    </label>
                                    <p class="description">Displays on: Education Loans, Financial Aid, Loan Assistance, EMI Calculator, etc.</p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="show_rankings">
                                        📊 College Rankings
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_rankings" id="show_rankings" value="1" <?php checked(!empty($display_options['show_rankings'])); ?>>
                                        Show NIRF rankings on ranking-related service pages
                                    </label>
                                    <p class="description">Displays on: College Rankings, Top Colleges, NIRF Rankings, College Comparison, etc.</p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="show_jobs">
                                        🏢 Jobs & Internships
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_jobs" id="show_jobs" value="1" <?php checked(!empty($display_options['show_jobs'])); ?>>
                                        Show job listings on placement-related service pages
                                    </label>
                                    <p class="description">Displays on: Placement Prep, Job Portal, Internships, Campus Placements, etc.</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Right Column: Featured Services -->
                    <div class="card" style="padding: 20px;">
                        <h2 style="margin-top: 0;">⭐ Featured Services</h2>
                        <p class="description">Select which services to feature in the student dashboard (max 12 recommended)</p>

                        <div style="max-height: 600px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; background: #f9f9f9; border-radius: 4px;">
                            <?php foreach ($all_services as $service): ?>
                                <label style="display: block; padding: 8px 0; border-bottom: 1px solid #eee;">
                                    <input type="checkbox"
                                           name="featured_services[]"
                                           value="<?php echo esc_attr($service['slug']); ?>"
                                           <?php checked(in_array($service['slug'], $featured_services)); ?>>
                                    <span style="font-size: 1.3em;"><?php echo $service['icon']; ?></span>
                                    <strong><?php echo esc_html($service['title']); ?></strong>
                                    <br>
                                    <span style="margin-left: 30px; font-size: 12px; color: #666;">
                                        <?php echo esc_html($service['desc']); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <p class="description" style="margin-top: 15px;">
                            <strong>Current selection:</strong> <span id="featured-count"><?php echo count($featured_services); ?></span> services
                        </p>
                    </div>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        💾 Save Service Settings
                    </button>
                </p>
            </form>

            <!-- Status Info -->
            <div class="card" style="padding: 20px; margin-top: 30px; background: #f0f9ff; border-left: 4px solid #0073aa;">
                <h3 style="margin-top: 0;">ℹ️ Information</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Service Pages:</strong> All service pages automatically pull relevant seeded data based on their slug/category</li>
                    <li><strong>Data Display:</strong> Uncheck options above to hide specific data types across all service pages</li>
                    <li><strong>Student Dashboard:</strong> Featured services appear in the "Services" tab of student dashboards</li>
                    <li><strong>Performance:</strong> Limiting featured services improves dashboard loading speed</li>
                </ul>
            </div>

            <!-- Quick Stats -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 30px;">
                <?php
                $stats = array(
                    array('label' => 'Total Services', 'value' => count($all_services), 'icon' => '📦'),
                    array('label' => 'Featured Services', 'value' => count($featured_services), 'icon' => '⭐'),
                    array('label' => 'Active Data Types', 'value' => count(array_filter($display_options)), 'icon' => '✅'),
                    array('label' => 'Service Pages', 'value' => count($all_services), 'icon' => '📄'),
                );

                foreach ($stats as $stat):
                ?>
                <div class="card" style="padding: 20px; text-align: center;">
                    <div style="font-size: 2.5rem;"><?php echo $stat['icon']; ?></div>
                    <div style="font-size: 2rem; font-weight: bold; color: #0073aa;"><?php echo $stat['value']; ?></div>
                    <div style="color: #666; font-size: 14px;"><?php echo $stat['label']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
        // Update featured count
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="featured_services[]"]');
            const countSpan = document.getElementById('featured-count');

            function updateCount() {
                const checked = document.querySelectorAll('input[name="featured_services[]"]:checked').length;
                countSpan.textContent = checked;

                if (checked > 12) {
                    countSpan.style.color = '#dc3232';
                    countSpan.style.fontWeight = 'bold';
                } else {
                    countSpan.style.color = '#46b450';
                    countSpan.style.fontWeight = 'normal';
                }
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateCount));
        });
        </script>

        <style>
        .form-table th {
            width: 200px;
        }
        .card {
            background: white;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        </style>
        <?php
    }
}

// Initialize
CK_OneForm_Service_Settings::init();
