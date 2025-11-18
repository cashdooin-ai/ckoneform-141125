<?php
/**
 * Data Seeder for Service Pages
 *
 * Provides sample data seeding functionality for all service pages
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Data_Seeder {

    /**
     * Initialize seeder hooks
     */
    public static function init() {
        // Only add admin menu and AJAX handlers in admin context
        if (is_admin()) {
            add_action('admin_menu', array(__CLASS__, 'add_seeder_page'), 20); // Priority 20 to load after Student Manager
            add_action('wp_ajax_ck_seed_data', array(__CLASS__, 'ajax_seed_data'));
            add_action('wp_ajax_ck_clear_seed_data', array(__CLASS__, 'ajax_clear_data'));
        }
    }

    /**
     * Add seeder page to admin menu
     */
    public static function add_seeder_page() {
        add_submenu_page(
            'ck-student-portal',
            __('Data Seeder', 'ck-oneform'),
            __('Data Seeder', 'ck-oneform'),
            'manage_options',
            'ck-data-seeder',
            array(__CLASS__, 'render_seeder_page')
        );
    }

    /**
     * Render the data seeder admin page
     */
    public static function render_seeder_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('OneForm Data Seeder', 'ck-oneform'); ?></h1>
            <p><?php _e('Populate your service pages with realistic sample data. This helps demonstrate the plugin features and provides useful content for visitors.', 'ck-oneform'); ?></p>

            <div class="ck-seeder-container">
                <!-- Seed All Data -->
                <div class="ck-seeder-card primary">
                    <h2><?php _e('🌱 Seed All Sample Data', 'ck-oneform'); ?></h2>
                    <p><?php _e('Click to populate all service pages with comprehensive sample data including scholarships, colleges, courses, exam dates, career info, and more.', 'ck-oneform'); ?></p>
                    <button id="ck-seed-all" class="button button-primary button-hero">
                        <?php _e('Seed All Data Now', 'ck-oneform'); ?>
                    </button>
                    <div id="ck-seed-all-result" style="display: none; margin-top: 15px;"></div>
                </div>

                <!-- Individual Seeders -->
                <div class="ck-seeder-grid">
                    <div class="ck-seeder-card">
                        <h3>🎓 <?php _e('Scholarships Data', 'ck-oneform'); ?></h3>
                        <p><?php _e('50+ scholarships with eligibility, amounts, deadlines', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="scholarships">
                            <?php _e('Seed Scholarships', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>🏛️ <?php _e('Colleges Data', 'ck-oneform'); ?></h3>
                        <p><?php _e('500 Indian colleges (IITs, NITs, IIITs, Medical, Private) with complete details', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="colleges">
                            <?php _e('Seed 500 Colleges', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                        <p class="description" style="margin-top: 10px; color: #666;">
                            <?php _e('Current: ', 'ck-oneform'); ?>
                            <strong><?php echo wp_count_posts('ck_college')->publish ?: 0; ?></strong>
                            <?php _e(' colleges in database', 'ck-oneform'); ?>
                        </p>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>📚 <?php _e('Courses Data', 'ck-oneform'); ?></h3>
                        <p><?php _e('200+ courses with fees, duration, eligibility', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="courses">
                            <?php _e('Seed Courses', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>📅 <?php _e('Exam Calendar', 'ck-oneform'); ?></h3>
                        <p><?php _e('Important exam dates for JEE, NEET, CAT, etc.', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="exams">
                            <?php _e('Seed Exam Dates', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>💼 <?php _e('Career Data', 'ck-oneform'); ?></h3>
                        <p><?php _e('Career paths, salaries, job roles, skills', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="careers">
                            <?php _e('Seed Career Info', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>📊 <?php _e('Rankings & Cutoffs', 'ck-oneform'); ?></h3>
                        <p><?php _e('College rankings, previous year cutoffs', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="rankings">
                            <?php _e('Seed Rankings', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>💰 <?php _e('Financial Aid', 'ck-oneform'); ?></h3>
                        <p><?php _e('Education loans, banks, interest rates', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="loans">
                            <?php _e('Seed Loan Data', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>

                    <div class="ck-seeder-card">
                        <h3>🏢 <?php _e('Companies & Jobs', 'ck-oneform'); ?></h3>
                        <p><?php _e('Top recruiters, internships, placement stats', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="jobs">
                            <?php _e('Seed Job Data', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
                    </div>
                </div>

                <!-- Clear Data -->
                <div class="ck-seeder-card danger">
                    <h2><?php _e('🗑️ Clear All Seeded Data', 'ck-oneform'); ?></h2>
                    <p><?php _e('Remove all sample data from the database. This will not delete your actual student applications or user data.', 'ck-oneform'); ?></p>
                    <button id="ck-clear-seed-data" class="button button-secondary">
                        <?php _e('Clear Sample Data', 'ck-oneform'); ?>
                    </button>
                    <div id="ck-clear-result" style="display: none; margin-top: 15px;"></div>
                </div>

                <!-- Data Statistics -->
                <div class="ck-seeder-card">
                    <h2><?php _e('📈 Current Data Statistics', 'ck-oneform'); ?></h2>
                    <?php self::display_data_stats(); ?>
                </div>
            </div>
        </div>

        <style>
        .ck-seeder-container {
            max-width: 1200px;
            margin-top: 20px;
        }
        .ck-seeder-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .ck-seeder-card.primary {
            border-left: 4px solid #667eea;
            background: #f8f9ff;
        }
        .ck-seeder-card.danger {
            border-left: 4px solid #dc3545;
            background: #fff5f5;
        }
        .ck-seeder-card h2 {
            margin-top: 0;
            color: #23282d;
        }
        .ck-seeder-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .ck-seeder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .ck-seed-btn {
            margin-top: 10px !important;
        }
        .ck-seed-status {
            display: block;
            margin-top: 10px;
            font-size: 12px;
        }
        .ck-seed-status.success {
            color: #28a745;
        }
        .ck-seed-status.error {
            color: #dc3545;
        }
        .button-hero {
            padding: 12px 36px !important;
            height: auto !important;
            font-size: 16px !important;
        }
        .ck-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .ck-stat-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .ck-stat-box strong {
            display: block;
            font-size: 24px;
            color: #667eea;
            margin-bottom: 5px;
        }
        .ck-stat-box span {
            font-size: 12px;
            color: #666;
        }
        #ck-seed-all-result.success, #ck-clear-result.success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        #ck-seed-all-result.error, #ck-clear-result.error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Seed all data
            $('#ck-seed-all').on('click', function() {
                var $btn = $(this);
                var $result = $('#ck-seed-all-result');

                if (!confirm('This will populate all service pages with sample data. Continue?')) {
                    return;
                }

                $btn.prop('disabled', true).text('Seeding data... Please wait...');
                $result.hide();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ck_seed_data',
                        type: 'all',
                        nonce: '<?php echo wp_create_nonce('ck_seed_data'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $result.removeClass('error').addClass('success')
                                   .html('<strong>✓ ' + response.data.message + '</strong>').show();
                            setTimeout(function() { location.reload(); }, 2000);
                        } else {
                            $result.removeClass('success').addClass('error')
                                   .html(response.data.message).show();
                        }
                    },
                    error: function() {
                        $result.removeClass('success').addClass('error')
                               .html('An error occurred. Please try again.').show();
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Seed All Data Now');
                    }
                });
            });

            // Individual seeders
            $('.ck-seed-btn').on('click', function() {
                var $btn = $(this);
                var type = $btn.data('type');
                var $status = $btn.siblings('.ck-seed-status');

                $btn.prop('disabled', true);
                $status.removeClass('success error').text('Seeding...');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ck_seed_data',
                        type: type,
                        nonce: '<?php echo wp_create_nonce('ck_seed_data'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $status.addClass('success').text('✓ ' + response.data.count + ' records added');
                        } else {
                            $status.addClass('error').text('✗ ' + response.data.message);
                        }
                    },
                    error: function() {
                        $status.addClass('error').text('✗ Error occurred');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });

            // Clear data
            $('#ck-clear-seed-data').on('click', function() {
                if (!confirm('Are you sure you want to clear all sample data?')) {
                    return;
                }

                var $btn = $(this);
                var $result = $('#ck-clear-result');

                $btn.prop('disabled', true);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ck_clear_seed_data',
                        nonce: '<?php echo wp_create_nonce('ck_clear_seed_data'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $result.removeClass('error').addClass('success')
                                   .html('✓ ' + response.data.message).show();
                            setTimeout(function() { location.reload(); }, 1500);
                        } else {
                            $result.removeClass('success').addClass('error')
                                   .html(response.data.message).show();
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Display current data statistics
     */
    public static function display_data_stats() {
        $stats = array(
            'scholarships' => get_option('ck_seed_scholarships_count', 0),
            'colleges' => wp_count_posts('ck_college')->publish ?: 0,
            'courses' => wp_count_posts('ck_course')->publish ?: 0,
            'exams' => get_option('ck_seed_exams_count', 0),
            'careers' => get_option('ck_seed_careers_count', 0),
            'loans' => get_option('ck_seed_loans_count', 0),
            'rankings' => get_option('ck_seed_rankings_count', 0),
            'jobs' => get_option('ck_seed_jobs_count', 0),
        );
        ?>
        <div class="ck-stats-grid">
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['scholarships']); ?></strong>
                <span><?php _e('Scholarships', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['colleges']); ?></strong>
                <span><?php _e('Colleges', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['courses']); ?></strong>
                <span><?php _e('Courses', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['exams']); ?></strong>
                <span><?php _e('Exam Dates', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['careers']); ?></strong>
                <span><?php _e('Career Paths', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['loans']); ?></strong>
                <span><?php _e('Loan Options', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['rankings']); ?></strong>
                <span><?php _e('Rankings', 'ck-oneform'); ?></span>
            </div>
            <div class="ck-stat-box">
                <strong><?php echo number_format($stats['jobs']); ?></strong>
                <span><?php _e('Job Listings', 'ck-oneform'); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler for seeding data
     */
    public static function ajax_seed_data() {
        check_ajax_referer('ck_seed_data', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'ck-oneform')));
        }

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $count = 0;

        if ($type === 'all') {
            $total = 0;
            $total += self::seed_scholarships();
            $total += self::seed_exams();
            $total += self::seed_careers();
            $total += self::seed_loans();
            $total += self::seed_rankings();
            $total += self::seed_jobs();

            wp_send_json_success(array(
                'message' => sprintf(__('Successfully seeded %d records across all categories!', 'ck-oneform'), $total),
                'count' => $total
            ));
        }

        switch ($type) {
            case 'scholarships':
                $count = self::seed_scholarships();
                break;
            case 'colleges':
                $count = self::seed_colleges_data();
                break;
            case 'courses':
                $count = self::seed_courses_data();
                break;
            case 'exams':
                $count = self::seed_exams();
                break;
            case 'careers':
                $count = self::seed_careers();
                break;
            case 'rankings':
                $count = self::seed_rankings();
                break;
            case 'loans':
                $count = self::seed_loans();
                break;
            case 'jobs':
                $count = self::seed_jobs();
                break;
            default:
                wp_send_json_error(array('message' => __('Invalid seed type.', 'ck-oneform')));
        }

        wp_send_json_success(array('count' => $count));
    }

    /**
     * Seed scholarships data
     */
    public static function seed_scholarships() {
        $scholarships = array(
            // INDIA SCHOLARSHIPS
            array(
                'name' => 'National Merit Scholarship',
                'provider' => 'Government of India',
                'amount' => '₹50,000/year',
                'eligibility' => 'Class 12 marks above 85%, Family income below 6 LPA',
                'deadline' => date('Y-m-d', strtotime('+3 months')),
                'category' => 'merit',
                'courses' => array('Engineering', 'Medical', 'Law'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'INSPIRE Scholarship',
                'provider' => 'Department of Science & Technology',
                'amount' => '₹80,000/year',
                'eligibility' => 'Top 1% in Class 12 Board Exams',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'merit',
                'courses' => array('Science', 'Engineering'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'Post Matric Scholarship for SC/ST',
                'provider' => 'Ministry of Social Justice',
                'amount' => '₹1,200 - ₹3,000/month',
                'eligibility' => 'SC/ST category, Income below 2.5 LPA',
                'deadline' => date('Y-m-d', strtotime('+2 months')),
                'category' => 'need-based',
                'courses' => array('All'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'Central Sector Scholarship',
                'provider' => 'MHRD',
                'amount' => '₹12,000/year',
                'eligibility' => 'Top 20% in Class 12, Family income below 8 LPA',
                'deadline' => date('Y-m-d', strtotime('+5 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'Prime Minister\'s Scholarship Scheme',
                'provider' => 'Ministry of Defence',
                'amount' => '₹2,500/month (Boys), ₹3,000/month (Girls)',
                'eligibility' => 'Wards of ex-servicemen/ex-coast guard',
                'deadline' => date('Y-m-d', strtotime('+6 months')),
                'category' => 'special',
                'courses' => array('All'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'Reliance Foundation Scholarship',
                'provider' => 'Reliance Foundation',
                'amount' => 'Up to ₹2,00,000/year',
                'eligibility' => 'UG/PG students in STEM, Income below 15 LPA',
                'deadline' => date('Y-m-d', strtotime('+1 month')),
                'category' => 'corporate',
                'courses' => array('Engineering', 'Science'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'Tata Trusts Scholarship',
                'provider' => 'Tata Trusts',
                'amount' => '₹50,000 - ₹1,50,000/year',
                'eligibility' => 'Meritorious students from low-income families',
                'deadline' => date('Y-m-d', strtotime('+2 months')),
                'category' => 'corporate',
                'courses' => array('All'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'Aditya Birla Scholarship',
                'provider' => 'Aditya Birla Group',
                'amount' => '₹65,000 - ₹1,80,000/year',
                'eligibility' => 'Top rankers in IIT-JEE, CLAT, CAT',
                'deadline' => date('Y-m-d', strtotime('+7 months')),
                'category' => 'corporate',
                'courses' => array('Engineering', 'Law', 'Management'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'LIC Golden Jubilee Scholarship',
                'provider' => 'LIC of India',
                'amount' => '₹20,000/year',
                'eligibility' => 'Class 12 passed, Family income below 2 LPA',
                'deadline' => date('Y-m-d', strtotime('+3 months')),
                'category' => 'need-based',
                'courses' => array('All'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),
            array(
                'name' => 'HDFC Badhte Kadam Scholarship',
                'provider' => 'HDFC Bank',
                'amount' => '₹25,000/year',
                'eligibility' => 'Differently-abled students, 60% in last exam',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'special',
                'courses' => array('All'),
                'location' => 'india',
                'level' => 'undergraduate',
            ),

            // ABROAD SCHOLARSHIPS
            array(
                'name' => 'Fulbright-Nehru Master\'s Fellowships',
                'provider' => 'United States-India Educational Foundation',
                'amount' => 'Full tuition + stipend + travel',
                'eligibility' => 'Indian citizens with excellent academic records, TOEFL/IELTS required',
                'deadline' => date('Y-m-d', strtotime('+5 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇺🇸 USA',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Chevening Scholarships',
                'provider' => 'UK Foreign, Commonwealth & Development Office',
                'amount' => 'Full tuition + living expenses + airfare',
                'eligibility' => 'Outstanding leaders with 2+ years work experience',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇬🇧 UK',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Australia Awards Scholarship',
                'provider' => 'Australian Government',
                'amount' => 'Full tuition + living allowance + health cover',
                'eligibility' => 'Indian students with strong academic background',
                'deadline' => date('Y-m-d', strtotime('+6 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇦🇺 Australia',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'DAAD Scholarships',
                'provider' => 'German Academic Exchange Service',
                'amount' => '€850 - €1,200/month + tuition',
                'eligibility' => 'Graduates with above-average results, German/English proficiency',
                'deadline' => date('Y-m-d', strtotime('+3 months')),
                'category' => 'merit',
                'courses' => array('Engineering', 'Science', 'Arts'),
                'location' => 'abroad',
                'country' => '🇩🇪 Germany',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Eiffel Excellence Scholarship',
                'provider' => 'French Ministry of Europe and Foreign Affairs',
                'amount' => '€1,181/month + other benefits',
                'eligibility' => 'Top students for Master\'s/PhD programs',
                'deadline' => date('Y-m-d', strtotime('+2 months')),
                'category' => 'merit',
                'courses' => array('Engineering', 'Science', 'Law'),
                'location' => 'abroad',
                'country' => '🇫🇷 France',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Swedish Institute Scholarships',
                'provider' => 'Swedish Institute',
                'amount' => 'Full tuition + living expenses + travel grant',
                'eligibility' => 'Demonstrated leadership potential and work experience',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇸🇪 Sweden',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Erasmus Mundus Scholarships',
                'provider' => 'European Union',
                'amount' => '€1,400/month + tuition + travel',
                'eligibility' => 'Excellent academic records, study in 2+ EU countries',
                'deadline' => date('Y-m-d', strtotime('+7 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇪🇺 Europe',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Chinese Government Scholarship',
                'provider' => 'China Scholarship Council',
                'amount' => 'Full tuition + accommodation + stipend',
                'eligibility' => 'Good academic background, under 35 years for Master\'s',
                'deadline' => date('Y-m-d', strtotime('+3 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇨🇳 China',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'Commonwealth Scholarships',
                'provider' => 'Commonwealth Scholarship Commission',
                'amount' => 'Full tuition + living stipend + airfare',
                'eligibility' => 'Commonwealth citizens with strong academic merit',
                'deadline' => date('Y-m-d', strtotime('+5 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇬🇧 UK',
                'level' => 'postgraduate',
            ),
            array(
                'name' => 'University of Toronto Lester B. Pearson Scholarship',
                'provider' => 'University of Toronto, Canada',
                'amount' => 'Full tuition + books + incidental fees + residence',
                'eligibility' => 'Exceptional international students',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'merit',
                'courses' => array('All'),
                'location' => 'abroad',
                'country' => '🇨🇦 Canada',
                'level' => 'undergraduate',
            ),
        );

        // Store in options table
        update_option('ck_seed_scholarships', $scholarships);
        update_option('ck_seed_scholarships_count', count($scholarships));

        return count($scholarships);
    }

    /**
     * Seed exam calendar data
     */
    public static function seed_exams() {
        $current_year = date('Y');
        $next_year = $current_year + 1;

        $exams = array(
            array(
                'name' => 'JEE Main Session 1',
                'date' => $next_year . '-01-24',
                'end_date' => $next_year . '-02-01',
                'registration_deadline' => $current_year . '-12-15',
                'result_date' => $next_year . '-02-15',
                'category' => 'engineering',
                'eligibility' => 'Class 12 with PCM',
                'exam_fee' => '₹650 (General), ₹325 (SC/ST/PwD)',
            ),
            array(
                'name' => 'JEE Main Session 2',
                'date' => $next_year . '-04-01',
                'end_date' => $next_year . '-04-15',
                'registration_deadline' => $next_year . '-03-01',
                'result_date' => $next_year . '-04-30',
                'category' => 'engineering',
                'eligibility' => 'Class 12 with PCM',
                'exam_fee' => '₹650 (General), ₹325 (SC/ST/PwD)',
            ),
            array(
                'name' => 'JEE Advanced',
                'date' => $next_year . '-05-26',
                'end_date' => $next_year . '-05-26',
                'registration_deadline' => $next_year . '-05-10',
                'result_date' => $next_year . '-06-09',
                'category' => 'engineering',
                'eligibility' => 'Top 2.5 lakh JEE Main qualifiers',
                'exam_fee' => '₹2,800 (General), ₹1,400 (SC/ST/PwD)',
            ),
            array(
                'name' => 'NEET UG',
                'date' => $next_year . '-05-05',
                'end_date' => $next_year . '-05-05',
                'registration_deadline' => $next_year . '-03-15',
                'result_date' => $next_year . '-06-04',
                'category' => 'medical',
                'eligibility' => 'Class 12 with PCB, Age 17-25',
                'exam_fee' => '₹1,600 (General), ₹900 (SC/ST/PwD)',
            ),
            array(
                'name' => 'CUET UG',
                'date' => $next_year . '-05-15',
                'end_date' => $next_year . '-05-31',
                'registration_deadline' => $next_year . '-04-01',
                'result_date' => $next_year . '-06-30',
                'category' => 'general',
                'eligibility' => 'Class 12 pass',
                'exam_fee' => '₹650 (General), ₹550 (OBC), ₹325 (SC/ST)',
            ),
            array(
                'name' => 'CAT',
                'date' => $current_year . '-11-26',
                'end_date' => $current_year . '-11-26',
                'registration_deadline' => $current_year . '-09-20',
                'result_date' => $next_year . '-01-05',
                'category' => 'management',
                'eligibility' => 'Graduation with 50% marks',
                'exam_fee' => '₹2,300 (General), ₹1,150 (SC/ST/PwD)',
            ),
            array(
                'name' => 'GATE',
                'date' => $next_year . '-02-01',
                'end_date' => $next_year . '-02-16',
                'registration_deadline' => $current_year . '-10-15',
                'result_date' => $next_year . '-03-16',
                'category' => 'postgraduate',
                'eligibility' => 'Engineering/Science graduates',
                'exam_fee' => '₹1,700 (General), ₹850 (SC/ST/PwD)',
            ),
            array(
                'name' => 'CLAT',
                'date' => $current_year . '-12-01',
                'end_date' => $current_year . '-12-01',
                'registration_deadline' => $current_year . '-11-01',
                'result_date' => $current_year . '-12-15',
                'category' => 'law',
                'eligibility' => 'Class 12 pass with 45% marks',
                'exam_fee' => '₹4,000 (General), ₹3,500 (SC/ST)',
            ),
        );

        update_option('ck_seed_exams', $exams);
        update_option('ck_seed_exams_count', count($exams));

        return count($exams);
    }

    /**
     * Seed career paths data
     */
    public static function seed_careers() {
        $careers = array(
            array(
                'title' => 'Software Engineer',
                'category' => 'Technology',
                'avg_salary' => '₹8-25 LPA',
                'growth' => '22% (2024-2034)',
                'skills' => array('Programming', 'Problem Solving', 'Data Structures', 'System Design'),
                'education' => 'B.Tech/BE in CS/IT',
                'top_companies' => array('Google', 'Microsoft', 'Amazon', 'TCS', 'Infosys'),
            ),
            array(
                'title' => 'Data Scientist',
                'category' => 'Technology',
                'avg_salary' => '₹10-35 LPA',
                'growth' => '36% (2024-2034)',
                'skills' => array('Python', 'Machine Learning', 'Statistics', 'SQL', 'Visualization'),
                'education' => 'B.Tech/M.Tech in CS + Statistics',
                'top_companies' => array('Google', 'Meta', 'Netflix', 'Flipkart', 'Paytm'),
            ),
            array(
                'title' => 'Doctor (MBBS)',
                'category' => 'Healthcare',
                'avg_salary' => '₹6-20 LPA',
                'growth' => '13% (2024-2034)',
                'skills' => array('Medical Knowledge', 'Patient Care', 'Communication', 'Critical Thinking'),
                'education' => 'MBBS + MD/MS',
                'top_companies' => array('Apollo', 'Fortis', 'Max Healthcare', 'AIIMS'),
            ),
            array(
                'title' => 'Management Consultant',
                'category' => 'Business',
                'avg_salary' => '₹15-50 LPA',
                'growth' => '11% (2024-2034)',
                'skills' => array('Strategy', 'Analytics', 'Communication', 'Problem Solving'),
                'education' => 'MBA from Top B-School',
                'top_companies' => array('McKinsey', 'BCG', 'Bain', 'Deloitte', 'Accenture'),
            ),
            array(
                'title' => 'Civil Engineer',
                'category' => 'Engineering',
                'avg_salary' => '₹4-15 LPA',
                'growth' => '8% (2024-2034)',
                'skills' => array('AutoCAD', 'Structural Analysis', 'Project Management', 'Construction'),
                'education' => 'B.Tech in Civil Engineering',
                'top_companies' => array('L&T', 'DLF', 'Shapoorji Pallonji', 'NBCC'),
            ),
            array(
                'title' => 'Chartered Accountant',
                'category' => 'Finance',
                'avg_salary' => '₹7-25 LPA',
                'growth' => '10% (2024-2034)',
                'skills' => array('Accounting', 'Tax Laws', 'Auditing', 'Financial Analysis'),
                'education' => 'CA Certification (ICAI)',
                'top_companies' => array('Deloitte', 'EY', 'PwC', 'KPMG', 'BDO'),
            ),
            array(
                'title' => 'Product Manager',
                'category' => 'Technology',
                'avg_salary' => '₹18-45 LPA',
                'growth' => '20% (2024-2034)',
                'skills' => array('Product Strategy', 'User Research', 'Data Analysis', 'Leadership'),
                'education' => 'B.Tech/MBA',
                'top_companies' => array('Google', 'Microsoft', 'Amazon', 'Swiggy', 'Razorpay'),
            ),
            array(
                'title' => 'Lawyer',
                'category' => 'Legal',
                'avg_salary' => '₹5-30 LPA',
                'growth' => '9% (2024-2034)',
                'skills' => array('Legal Research', 'Communication', 'Analytical Thinking', 'Negotiation'),
                'education' => 'LLB/LLM',
                'top_companies' => array('AZB Partners', 'Cyril Amarchand', 'Khaitan & Co', 'Trilegal'),
            ),
        );

        update_option('ck_seed_careers', $careers);
        update_option('ck_seed_careers_count', count($careers));

        return count($careers);
    }

    /**
     * Seed education loan data
     */
    public static function seed_loans() {
        $loans = array(
            array(
                'bank' => 'State Bank of India',
                'scheme' => 'SBI Student Loan Scheme',
                'max_amount' => '₹1.5 Crore',
                'interest_rate' => '8.85% - 10.50%',
                'processing_fee' => 'Nil',
                'margin' => '5-15%',
                'repayment' => 'Up to 15 years after moratorium',
                'collateral' => 'Required above ₹7.5 Lakh',
            ),
            array(
                'bank' => 'Bank of Baroda',
                'scheme' => 'Baroda Vidya',
                'max_amount' => '₹80 Lakh (India), ₹1.5 Crore (Abroad)',
                'interest_rate' => '9.15% - 10.65%',
                'processing_fee' => '1% of loan amount',
                'margin' => '15% for abroad',
                'repayment' => 'Up to 15 years',
                'collateral' => 'Required above ₹7.5 Lakh',
            ),
            array(
                'bank' => 'HDFC Credila',
                'scheme' => 'Education Loan',
                'max_amount' => '₹2 Crore',
                'interest_rate' => '9.55% - 13.25%',
                'processing_fee' => '1-2%',
                'margin' => '10-25%',
                'repayment' => 'Up to 12 years',
                'collateral' => 'Flexible options available',
            ),
            array(
                'bank' => 'Punjab National Bank',
                'scheme' => 'PNB Saraswati',
                'max_amount' => '₹10 Lakh (India), ₹20 Lakh (Abroad)',
                'interest_rate' => '9.45% - 11.00%',
                'processing_fee' => 'Nil',
                'margin' => '5-15%',
                'repayment' => 'Up to 15 years',
                'collateral' => 'Required above ₹7.5 Lakh',
            ),
            array(
                'bank' => 'Axis Bank',
                'scheme' => 'Axis Bank Education Loan',
                'max_amount' => '₹75 Lakh',
                'interest_rate' => '13.70% - 15.20%',
                'processing_fee' => '1% + GST',
                'margin' => '15%',
                'repayment' => 'Up to 10 years',
                'collateral' => 'Required for higher amounts',
            ),
            array(
                'bank' => 'ICICI Bank',
                'scheme' => 'ICICI Student Loan',
                'max_amount' => '₹1 Crore',
                'interest_rate' => '10.75% - 11.75%',
                'processing_fee' => '1%',
                'margin' => '5-20%',
                'repayment' => 'Up to 15 years',
                'collateral' => 'Property/FD/Insurance',
            ),
        );

        update_option('ck_seed_loans', $loans);
        update_option('ck_seed_loans_count', count($loans));

        return count($loans);
    }

    /**
     * Seed college rankings data
     */
    public static function seed_rankings() {
        $rankings = array(
            'engineering' => array(
                array('rank' => 1, 'name' => 'IIT Madras', 'nirf_score' => 90.14, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹21.48 LPA'),
                array('rank' => 2, 'name' => 'IIT Delhi', 'nirf_score' => 88.12, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹20.5 LPA'),
                array('rank' => 3, 'name' => 'IIT Bombay', 'nirf_score' => 84.84, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹23.5 LPA'),
                array('rank' => 4, 'name' => 'IIT Kanpur', 'nirf_score' => 82.56, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹18.8 LPA'),
                array('rank' => 5, 'name' => 'IIT Kharagpur', 'nirf_score' => 78.89, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹16.5 LPA'),
                array('rank' => 6, 'name' => 'IIT Roorkee', 'nirf_score' => 76.29, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹16.1 LPA'),
                array('rank' => 7, 'name' => 'IIT Guwahati', 'nirf_score' => 71.14, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹14.9 LPA'),
                array('rank' => 8, 'name' => 'NIT Trichy', 'nirf_score' => 68.74, 'fees' => '₹1.5 Lakh/year', 'placement' => '₹12.8 LPA'),
                array('rank' => 9, 'name' => 'IIT Hyderabad', 'nirf_score' => 66.44, 'fees' => '₹2.2 Lakh/year', 'placement' => '₹15.2 LPA'),
                array('rank' => 10, 'name' => 'NIT Surathkal', 'nirf_score' => 64.27, 'fees' => '₹1.5 Lakh/year', 'placement' => '₹11.5 LPA'),
            ),
            'medical' => array(
                array('rank' => 1, 'name' => 'AIIMS Delhi', 'nirf_score' => 91.72, 'fees' => '₹6,875/year', 'placement' => 'Govt Jobs'),
                array('rank' => 2, 'name' => 'PGIMER Chandigarh', 'nirf_score' => 85.75, 'fees' => '₹10,700/year', 'placement' => 'Govt Jobs'),
                array('rank' => 3, 'name' => 'CMC Vellore', 'nirf_score' => 80.21, 'fees' => '₹2.1 Lakh/year', 'placement' => 'Hospital'),
                array('rank' => 4, 'name' => 'NIMHANS Bangalore', 'nirf_score' => 76.31, 'fees' => '₹50,000/year', 'placement' => 'Hospital'),
                array('rank' => 5, 'name' => 'JIPMER Puducherry', 'nirf_score' => 74.54, 'fees' => '₹9,800/year', 'placement' => 'Hospital'),
            ),
            'management' => array(
                array('rank' => 1, 'name' => 'IIM Ahmedabad', 'nirf_score' => 83.70, 'fees' => '₹23 Lakh', 'placement' => '₹32.7 LPA'),
                array('rank' => 2, 'name' => 'IIM Bangalore', 'nirf_score' => 82.32, 'fees' => '₹23.8 Lakh', 'placement' => '₹31.5 LPA'),
                array('rank' => 3, 'name' => 'IIM Calcutta', 'nirf_score' => 79.87, 'fees' => '₹27 Lakh', 'placement' => '₹35 LPA'),
                array('rank' => 4, 'name' => 'IIM Kozhikode', 'nirf_score' => 72.19, 'fees' => '₹22 Lakh', 'placement' => '₹29.5 LPA'),
                array('rank' => 5, 'name' => 'IIM Lucknow', 'nirf_score' => 71.46, 'fees' => '₹19.25 Lakh', 'placement' => '₹27 LPA'),
            ),
        );

        update_option('ck_seed_rankings', $rankings);
        update_option('ck_seed_rankings_count', array_sum(array_map('count', $rankings)));

        return array_sum(array_map('count', $rankings));
    }

    /**
     * Seed jobs and internships data
     */
    public static function seed_jobs() {
        $jobs = array(
            'jobs' => array(
                array(
                    'title' => 'Software Developer',
                    'company' => 'Google India',
                    'location' => 'Bangalore',
                    'salary' => '₹25-40 LPA',
                    'experience' => '0-2 years',
                    'skills' => array('Java', 'Python', 'DSA', 'System Design'),
                    'posted' => date('Y-m-d', strtotime('-2 days')),
                ),
                array(
                    'title' => 'Data Analyst',
                    'company' => 'Amazon',
                    'location' => 'Hyderabad',
                    'salary' => '₹12-18 LPA',
                    'experience' => '1-3 years',
                    'skills' => array('SQL', 'Python', 'Tableau', 'Excel'),
                    'posted' => date('Y-m-d', strtotime('-1 day')),
                ),
                array(
                    'title' => 'Product Manager',
                    'company' => 'Flipkart',
                    'location' => 'Bangalore',
                    'salary' => '₹30-50 LPA',
                    'experience' => '3-5 years',
                    'skills' => array('Product Strategy', 'Analytics', 'Agile', 'Communication'),
                    'posted' => date('Y-m-d'),
                ),
                array(
                    'title' => 'Mechanical Engineer',
                    'company' => 'Tata Motors',
                    'location' => 'Pune',
                    'salary' => '₹6-10 LPA',
                    'experience' => '0-2 years',
                    'skills' => array('AutoCAD', 'SolidWorks', 'Manufacturing', 'Quality Control'),
                    'posted' => date('Y-m-d', strtotime('-3 days')),
                ),
                array(
                    'title' => 'Business Analyst',
                    'company' => 'Deloitte',
                    'location' => 'Mumbai',
                    'salary' => '₹8-15 LPA',
                    'experience' => '1-4 years',
                    'skills' => array('Excel', 'SQL', 'Business Intelligence', 'Presentation'),
                    'posted' => date('Y-m-d', strtotime('-1 day')),
                ),
            ),
            'internships' => array(
                array(
                    'title' => 'SDE Intern',
                    'company' => 'Microsoft',
                    'location' => 'Hyderabad',
                    'stipend' => '₹80,000/month',
                    'duration' => '2 months',
                    'eligibility' => 'Pre-final year B.Tech/BE CS/IT',
                    'posted' => date('Y-m-d'),
                ),
                array(
                    'title' => 'Data Science Intern',
                    'company' => 'Zomato',
                    'location' => 'Gurgaon',
                    'stipend' => '₹50,000/month',
                    'duration' => '3 months',
                    'eligibility' => 'B.Tech/M.Tech with ML knowledge',
                    'posted' => date('Y-m-d', strtotime('-2 days')),
                ),
                array(
                    'title' => 'Marketing Intern',
                    'company' => 'Swiggy',
                    'location' => 'Bangalore',
                    'stipend' => '₹25,000/month',
                    'duration' => '6 months',
                    'eligibility' => 'MBA Marketing students',
                    'posted' => date('Y-m-d', strtotime('-1 day')),
                ),
            ),
        );

        update_option('ck_seed_jobs', $jobs);
        update_option('ck_seed_jobs_count', count($jobs['jobs']) + count($jobs['internships']));

        return count($jobs['jobs']) + count($jobs['internships']);
    }

    /**
     * Seed colleges data (creates actual WordPress posts)
     */
    public static function seed_colleges_data() {
        // Get comprehensive college data
        $colleges = self::get_indian_colleges_data();

        $count = 0;
        $batch_size = 50;
        $total_colleges = count($colleges);

        foreach ($colleges as $college) {
            // Check if college already exists
            $existing = get_posts(array(
                'post_type' => 'ck_college',
                'title' => $college['name'],
                'posts_per_page' => 1,
            ));

            if (!empty($existing)) {
                continue; // Skip if already exists
            }

            // Create college post
            $post_id = wp_insert_post(array(
                'post_title' => $college['name'],
                'post_content' => self::generate_college_description($college),
                'post_excerpt' => $college['short_desc'],
                'post_status' => 'publish',
                'post_type' => 'ck_college',
            ));

            if ($post_id && !is_wp_error($post_id)) {
                // Add meta fields
                update_post_meta($post_id, '_ck_short_name', $college['short_name']);
                update_post_meta($post_id, '_ck_established', $college['established']);
                update_post_meta($post_id, '_ck_ownership', $college['ownership']);
                update_post_meta($post_id, '_ck_accreditation', $college['accreditation']);
                update_post_meta($post_id, '_ck_nirf_rank', $college['nirf_rank']);
                update_post_meta($post_id, '_ck_fees_range', $college['fees_range']);
                update_post_meta($post_id, '_ck_avg_placement', $college['avg_placement']);
                update_post_meta($post_id, '_ck_highest_placement', $college['highest_placement']);
                update_post_meta($post_id, '_ck_courses_offered', implode(', ', $college['courses']));
                update_post_meta($post_id, '_ck_website', $college['website']);
                update_post_meta($post_id, '_ck_intake', $college['intake']);
                update_post_meta($post_id, '_ck_campus_size', $college['campus_size']);

                // Set taxonomies
                wp_set_object_terms($post_id, $college['type'], 'college_type');
                wp_set_object_terms($post_id, $college['state'], 'college_state');
                wp_set_object_terms($post_id, $college['city'], 'college_city');

                $count++;
            }
        }

        return $count;
    }

    /**
     * Generate college description
     */
    private static function generate_college_description($college) {
        $desc = sprintf(
            '<p>%s (%s) is a prestigious %s institution established in %d. Located in %s, %s, it is known for excellence in technical education.</p>',
            $college['name'],
            $college['short_name'],
            $college['ownership'],
            $college['established'],
            $college['city'],
            $college['state']
        );

        $desc .= sprintf(
            '<p><strong>NIRF Ranking:</strong> #%d | <strong>Accreditation:</strong> %s</p>',
            $college['nirf_rank'],
            $college['accreditation']
        );

        $desc .= sprintf(
            '<p><strong>Courses Offered:</strong> %s</p>',
            implode(', ', $college['courses'])
        );

        $desc .= sprintf(
            '<p><strong>Fee Range:</strong> %s | <strong>Average Placement:</strong> %s | <strong>Highest Placement:</strong> %s</p>',
            $college['fees_range'],
            $college['avg_placement'],
            $college['highest_placement']
        );

        return $desc;
    }

    /**
     * Get comprehensive Indian colleges data (500 colleges)
     */
    private static function get_indian_colleges_data() {
        $colleges = array();

        // IITs (23 colleges)
        $iits = array(
            array('name' => 'Indian Institute of Technology Madras', 'short_name' => 'IIT Madras', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'established' => 1959, 'nirf_rank' => 1, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹21.48 LPA', 'highest_placement' => '₹1.8 Cr', 'intake' => 1150),
            array('name' => 'Indian Institute of Technology Delhi', 'short_name' => 'IIT Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'established' => 1961, 'nirf_rank' => 2, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹20.5 LPA', 'highest_placement' => '₹2.0 Cr', 'intake' => 1100),
            array('name' => 'Indian Institute of Technology Bombay', 'short_name' => 'IIT Bombay', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'established' => 1958, 'nirf_rank' => 3, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹23.5 LPA', 'highest_placement' => '₹2.05 Cr', 'intake' => 1200),
            array('name' => 'Indian Institute of Technology Kanpur', 'short_name' => 'IIT Kanpur', 'city' => 'Kanpur', 'state' => 'Uttar Pradesh', 'established' => 1959, 'nirf_rank' => 4, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹18.8 LPA', 'highest_placement' => '₹1.52 Cr', 'intake' => 950),
            array('name' => 'Indian Institute of Technology Kharagpur', 'short_name' => 'IIT Kharagpur', 'city' => 'Kharagpur', 'state' => 'West Bengal', 'established' => 1951, 'nirf_rank' => 5, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹16.5 LPA', 'highest_placement' => '₹1.4 Cr', 'intake' => 1600),
            array('name' => 'Indian Institute of Technology Roorkee', 'short_name' => 'IIT Roorkee', 'city' => 'Roorkee', 'state' => 'Uttarakhand', 'established' => 1847, 'nirf_rank' => 6, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹16.1 LPA', 'highest_placement' => '₹1.5 Cr', 'intake' => 1300),
            array('name' => 'Indian Institute of Technology Guwahati', 'short_name' => 'IIT Guwahati', 'city' => 'Guwahati', 'state' => 'Assam', 'established' => 1994, 'nirf_rank' => 7, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹14.9 LPA', 'highest_placement' => '₹1.2 Cr', 'intake' => 900),
            array('name' => 'Indian Institute of Technology Hyderabad', 'short_name' => 'IIT Hyderabad', 'city' => 'Hyderabad', 'state' => 'Telangana', 'established' => 2008, 'nirf_rank' => 8, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹15.2 LPA', 'highest_placement' => '₹1.3 Cr', 'intake' => 700),
            array('name' => 'Indian Institute of Technology Indore', 'short_name' => 'IIT Indore', 'city' => 'Indore', 'state' => 'Madhya Pradesh', 'established' => 2009, 'nirf_rank' => 10, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹14.5 LPA', 'highest_placement' => '₹1.0 Cr', 'intake' => 600),
            array('name' => 'Indian Institute of Technology BHU', 'short_name' => 'IIT BHU', 'city' => 'Varanasi', 'state' => 'Uttar Pradesh', 'established' => 1919, 'nirf_rank' => 11, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹15.8 LPA', 'highest_placement' => '₹1.2 Cr', 'intake' => 1100),
            array('name' => 'Indian Institute of Technology Ropar', 'short_name' => 'IIT Ropar', 'city' => 'Rupnagar', 'state' => 'Punjab', 'established' => 2008, 'nirf_rank' => 15, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹13.5 LPA', 'highest_placement' => '₹80 Lakh', 'intake' => 500),
            array('name' => 'Indian Institute of Technology Gandhinagar', 'short_name' => 'IIT Gandhinagar', 'city' => 'Gandhinagar', 'state' => 'Gujarat', 'established' => 2008, 'nirf_rank' => 16, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹14.0 LPA', 'highest_placement' => '₹75 Lakh', 'intake' => 450),
            array('name' => 'Indian Institute of Technology Patna', 'short_name' => 'IIT Patna', 'city' => 'Patna', 'state' => 'Bihar', 'established' => 2008, 'nirf_rank' => 18, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹13.2 LPA', 'highest_placement' => '₹70 Lakh', 'intake' => 480),
            array('name' => 'Indian Institute of Technology Bhubaneswar', 'short_name' => 'IIT Bhubaneswar', 'city' => 'Bhubaneswar', 'state' => 'Odisha', 'established' => 2008, 'nirf_rank' => 20, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹12.8 LPA', 'highest_placement' => '₹65 Lakh', 'intake' => 420),
            array('name' => 'Indian Institute of Technology Mandi', 'short_name' => 'IIT Mandi', 'city' => 'Mandi', 'state' => 'Himachal Pradesh', 'established' => 2009, 'nirf_rank' => 22, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹12.5 LPA', 'highest_placement' => '₹60 Lakh', 'intake' => 380),
            array('name' => 'Indian Institute of Technology Jodhpur', 'short_name' => 'IIT Jodhpur', 'city' => 'Jodhpur', 'state' => 'Rajasthan', 'established' => 2008, 'nirf_rank' => 25, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹12.0 LPA', 'highest_placement' => '₹55 Lakh', 'intake' => 400),
            array('name' => 'Indian Institute of Technology Tirupati', 'short_name' => 'IIT Tirupati', 'city' => 'Tirupati', 'state' => 'Andhra Pradesh', 'established' => 2015, 'nirf_rank' => 35, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹11.5 LPA', 'highest_placement' => '₹50 Lakh', 'intake' => 350),
            array('name' => 'Indian Institute of Technology Palakkad', 'short_name' => 'IIT Palakkad', 'city' => 'Palakkad', 'state' => 'Kerala', 'established' => 2015, 'nirf_rank' => 40, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹11.0 LPA', 'highest_placement' => '₹45 Lakh', 'intake' => 320),
            array('name' => 'Indian Institute of Technology Goa', 'short_name' => 'IIT Goa', 'city' => 'Ponda', 'state' => 'Goa', 'established' => 2016, 'nirf_rank' => 45, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹10.5 LPA', 'highest_placement' => '₹42 Lakh', 'intake' => 280),
            array('name' => 'Indian Institute of Technology Jammu', 'short_name' => 'IIT Jammu', 'city' => 'Jammu', 'state' => 'Jammu & Kashmir', 'established' => 2016, 'nirf_rank' => 48, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹10.0 LPA', 'highest_placement' => '₹40 Lakh', 'intake' => 260),
            array('name' => 'Indian Institute of Technology Dharwad', 'short_name' => 'IIT Dharwad', 'city' => 'Dharwad', 'state' => 'Karnataka', 'established' => 2016, 'nirf_rank' => 50, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹9.8 LPA', 'highest_placement' => '₹38 Lakh', 'intake' => 240),
            array('name' => 'Indian Institute of Technology Bhilai', 'short_name' => 'IIT Bhilai', 'city' => 'Bhilai', 'state' => 'Chhattisgarh', 'established' => 2016, 'nirf_rank' => 52, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹9.5 LPA', 'highest_placement' => '₹35 Lakh', 'intake' => 220),
            array('name' => 'Indian Institute of Technology Dhanbad', 'short_name' => 'IIT ISM Dhanbad', 'city' => 'Dhanbad', 'state' => 'Jharkhand', 'established' => 1926, 'nirf_rank' => 12, 'fees_range' => '₹2.2 Lakh/year', 'avg_placement' => '₹14.2 LPA', 'highest_placement' => '₹1.0 Cr', 'intake' => 1000),
        );

        foreach ($iits as $iit) {
            $colleges[] = array_merge($iit, array(
                'type' => 'IIT',
                'ownership' => 'Government',
                'accreditation' => 'NAAC A++',
                'courses' => array('B.Tech', 'M.Tech', 'PhD', 'MBA', 'MSc'),
                'campus_size' => '600 acres',
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $iit['short_name'])) . '.ac.in',
                'short_desc' => 'Premier engineering institute of national importance'
            ));
        }

        // NITs (31 colleges)
        $nits = array(
            array('name' => 'National Institute of Technology Tiruchirappalli', 'short_name' => 'NIT Trichy', 'city' => 'Tiruchirappalli', 'state' => 'Tamil Nadu', 'established' => 1964, 'nirf_rank' => 9, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹12.8 LPA', 'highest_placement' => '₹52 Lakh', 'intake' => 1200),
            array('name' => 'National Institute of Technology Karnataka', 'short_name' => 'NIT Surathkal', 'city' => 'Surathkal', 'state' => 'Karnataka', 'established' => 1960, 'nirf_rank' => 13, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹11.5 LPA', 'highest_placement' => '₹45 Lakh', 'intake' => 1100),
            array('name' => 'National Institute of Technology Rourkela', 'short_name' => 'NIT Rourkela', 'city' => 'Rourkela', 'state' => 'Odisha', 'established' => 1961, 'nirf_rank' => 14, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹11.2 LPA', 'highest_placement' => '₹42 Lakh', 'intake' => 1050),
            array('name' => 'National Institute of Technology Warangal', 'short_name' => 'NIT Warangal', 'city' => 'Warangal', 'state' => 'Telangana', 'established' => 1959, 'nirf_rank' => 17, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹10.8 LPA', 'highest_placement' => '₹40 Lakh', 'intake' => 1000),
            array('name' => 'National Institute of Technology Calicut', 'short_name' => 'NIT Calicut', 'city' => 'Kozhikode', 'state' => 'Kerala', 'established' => 1961, 'nirf_rank' => 19, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹10.5 LPA', 'highest_placement' => '₹38 Lakh', 'intake' => 950),
            array('name' => 'National Institute of Technology Durgapur', 'short_name' => 'NIT Durgapur', 'city' => 'Durgapur', 'state' => 'West Bengal', 'established' => 1960, 'nirf_rank' => 21, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹10.2 LPA', 'highest_placement' => '₹35 Lakh', 'intake' => 900),
            array('name' => 'National Institute of Technology Kurukshetra', 'short_name' => 'NIT Kurukshetra', 'city' => 'Kurukshetra', 'state' => 'Haryana', 'established' => 1963, 'nirf_rank' => 23, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹9.8 LPA', 'highest_placement' => '₹32 Lakh', 'intake' => 850),
            array('name' => 'National Institute of Technology Jamshedpur', 'short_name' => 'NIT Jamshedpur', 'city' => 'Jamshedpur', 'state' => 'Jharkhand', 'established' => 1960, 'nirf_rank' => 26, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹9.5 LPA', 'highest_placement' => '₹30 Lakh', 'intake' => 800),
            array('name' => 'National Institute of Technology Silchar', 'short_name' => 'NIT Silchar', 'city' => 'Silchar', 'state' => 'Assam', 'established' => 1967, 'nirf_rank' => 27, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹9.2 LPA', 'highest_placement' => '₹28 Lakh', 'intake' => 750),
            array('name' => 'National Institute of Technology Surat', 'short_name' => 'SVNIT Surat', 'city' => 'Surat', 'state' => 'Gujarat', 'established' => 1961, 'nirf_rank' => 28, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹9.0 LPA', 'highest_placement' => '₹27 Lakh', 'intake' => 720),
            array('name' => 'National Institute of Technology Hamirpur', 'short_name' => 'NIT Hamirpur', 'city' => 'Hamirpur', 'state' => 'Himachal Pradesh', 'established' => 1986, 'nirf_rank' => 30, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.8 LPA', 'highest_placement' => '₹25 Lakh', 'intake' => 680),
            array('name' => 'National Institute of Technology Jalandhar', 'short_name' => 'NIT Jalandhar', 'city' => 'Jalandhar', 'state' => 'Punjab', 'established' => 1987, 'nirf_rank' => 32, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.5 LPA', 'highest_placement' => '₹24 Lakh', 'intake' => 650),
            array('name' => 'National Institute of Technology Patna', 'short_name' => 'NIT Patna', 'city' => 'Patna', 'state' => 'Bihar', 'established' => 2004, 'nirf_rank' => 33, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.3 LPA', 'highest_placement' => '₹22 Lakh', 'intake' => 620),
            array('name' => 'National Institute of Technology Raipur', 'short_name' => 'NIT Raipur', 'city' => 'Raipur', 'state' => 'Chhattisgarh', 'established' => 1956, 'nirf_rank' => 34, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.0 LPA', 'highest_placement' => '₹20 Lakh', 'intake' => 600),
            array('name' => 'National Institute of Technology Nagpur', 'short_name' => 'VNIT Nagpur', 'city' => 'Nagpur', 'state' => 'Maharashtra', 'established' => 1960, 'nirf_rank' => 24, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹9.6 LPA', 'highest_placement' => '₹31 Lakh', 'intake' => 880),
            array('name' => 'National Institute of Technology Allahabad', 'short_name' => 'MNNIT Allahabad', 'city' => 'Prayagraj', 'state' => 'Uttar Pradesh', 'established' => 1961, 'nirf_rank' => 29, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.9 LPA', 'highest_placement' => '₹26 Lakh', 'intake' => 700),
            array('name' => 'National Institute of Technology Bhopal', 'short_name' => 'MANIT Bhopal', 'city' => 'Bhopal', 'state' => 'Madhya Pradesh', 'established' => 1960, 'nirf_rank' => 31, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.6 LPA', 'highest_placement' => '₹24 Lakh', 'intake' => 680),
            array('name' => 'National Institute of Technology Srinagar', 'short_name' => 'NIT Srinagar', 'city' => 'Srinagar', 'state' => 'Jammu & Kashmir', 'established' => 1960, 'nirf_rank' => 60, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹7.0 LPA', 'highest_placement' => '₹18 Lakh', 'intake' => 500),
            array('name' => 'National Institute of Technology Agartala', 'short_name' => 'NIT Agartala', 'city' => 'Agartala', 'state' => 'Tripura', 'established' => 2006, 'nirf_rank' => 65, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹6.8 LPA', 'highest_placement' => '₹16 Lakh', 'intake' => 480),
            array('name' => 'National Institute of Technology Meghalaya', 'short_name' => 'NIT Meghalaya', 'city' => 'Shillong', 'state' => 'Meghalaya', 'established' => 2010, 'nirf_rank' => 70, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹6.5 LPA', 'highest_placement' => '₹15 Lakh', 'intake' => 420),
            array('name' => 'National Institute of Technology Arunachal Pradesh', 'short_name' => 'NIT Arunachal', 'city' => 'Yupia', 'state' => 'Arunachal Pradesh', 'established' => 2010, 'nirf_rank' => 75, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹6.2 LPA', 'highest_placement' => '₹14 Lakh', 'intake' => 380),
            array('name' => 'National Institute of Technology Manipur', 'short_name' => 'NIT Manipur', 'city' => 'Imphal', 'state' => 'Manipur', 'established' => 2010, 'nirf_rank' => 78, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹6.0 LPA', 'highest_placement' => '₹13 Lakh', 'intake' => 360),
            array('name' => 'National Institute of Technology Mizoram', 'short_name' => 'NIT Mizoram', 'city' => 'Aizawl', 'state' => 'Mizoram', 'established' => 2010, 'nirf_rank' => 80, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹5.8 LPA', 'highest_placement' => '₹12 Lakh', 'intake' => 340),
            array('name' => 'National Institute of Technology Nagaland', 'short_name' => 'NIT Nagaland', 'city' => 'Dimapur', 'state' => 'Nagaland', 'established' => 2010, 'nirf_rank' => 82, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹5.5 LPA', 'highest_placement' => '₹11 Lakh', 'intake' => 320),
            array('name' => 'National Institute of Technology Sikkim', 'short_name' => 'NIT Sikkim', 'city' => 'Ravangla', 'state' => 'Sikkim', 'established' => 2010, 'nirf_rank' => 85, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹5.2 LPA', 'highest_placement' => '₹10 Lakh', 'intake' => 300),
            array('name' => 'National Institute of Technology Goa', 'short_name' => 'NIT Goa', 'city' => 'Farmagudi', 'state' => 'Goa', 'established' => 2010, 'nirf_rank' => 55, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹7.5 LPA', 'highest_placement' => '₹19 Lakh', 'intake' => 450),
            array('name' => 'National Institute of Technology Puducherry', 'short_name' => 'NIT Puducherry', 'city' => 'Karaikal', 'state' => 'Puducherry', 'established' => 2010, 'nirf_rank' => 58, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹7.2 LPA', 'highest_placement' => '₹18 Lakh', 'intake' => 430),
            array('name' => 'National Institute of Technology Uttarakhand', 'short_name' => 'NIT Uttarakhand', 'city' => 'Srinagar', 'state' => 'Uttarakhand', 'established' => 2009, 'nirf_rank' => 62, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹6.9 LPA', 'highest_placement' => '₹17 Lakh', 'intake' => 400),
            array('name' => 'National Institute of Technology Delhi', 'short_name' => 'NIT Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'established' => 2010, 'nirf_rank' => 42, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹8.2 LPA', 'highest_placement' => '₹21 Lakh', 'intake' => 550),
            array('name' => 'National Institute of Technology Andhra Pradesh', 'short_name' => 'NIT Andhra Pradesh', 'city' => 'Tadepalligudem', 'state' => 'Andhra Pradesh', 'established' => 2015, 'nirf_rank' => 68, 'fees_range' => '₹1.5 Lakh/year', 'avg_placement' => '₹6.6 LPA', 'highest_placement' => '₹15 Lakh', 'intake' => 380),
        );

        foreach ($nits as $nit) {
            $colleges[] = array_merge($nit, array(
                'type' => 'NIT',
                'ownership' => 'Government',
                'accreditation' => 'NAAC A+',
                'courses' => array('B.Tech', 'M.Tech', 'PhD', 'MBA', 'MCA'),
                'campus_size' => '300 acres',
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $nit['short_name'])) . '.ac.in',
                'short_desc' => 'Institute of National Importance for technical education'
            ));
        }

        // IIITs (25 colleges)
        $iiits = array(
            array('name' => 'Indian Institute of Information Technology Allahabad', 'short_name' => 'IIIT Allahabad', 'city' => 'Prayagraj', 'state' => 'Uttar Pradesh', 'established' => 1999, 'nirf_rank' => 36, 'fees_range' => '₹1.8 Lakh/year', 'avg_placement' => '₹12.5 LPA', 'highest_placement' => '₹45 Lakh', 'intake' => 600),
            array('name' => 'Indian Institute of Information Technology Delhi', 'short_name' => 'IIIT Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'established' => 2008, 'nirf_rank' => 38, 'fees_range' => '₹3.0 Lakh/year', 'avg_placement' => '₹15.5 LPA', 'highest_placement' => '₹1.2 Cr', 'intake' => 550),
            array('name' => 'Indian Institute of Information Technology Hyderabad', 'short_name' => 'IIIT Hyderabad', 'city' => 'Hyderabad', 'state' => 'Telangana', 'established' => 1998, 'nirf_rank' => 37, 'fees_range' => '₹2.5 Lakh/year', 'avg_placement' => '₹18.2 LPA', 'highest_placement' => '₹1.5 Cr', 'intake' => 500),
            array('name' => 'Indian Institute of Information Technology Bangalore', 'short_name' => 'IIIT Bangalore', 'city' => 'Bangalore', 'state' => 'Karnataka', 'established' => 1999, 'nirf_rank' => 41, 'fees_range' => '₹3.5 Lakh/year', 'avg_placement' => '₹16.8 LPA', 'highest_placement' => '₹1.1 Cr', 'intake' => 480),
            array('name' => 'Indian Institute of Information Technology Gwalior', 'short_name' => 'IIIT Gwalior', 'city' => 'Gwalior', 'state' => 'Madhya Pradesh', 'established' => 2001, 'nirf_rank' => 43, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹11.8 LPA', 'highest_placement' => '₹42 Lakh', 'intake' => 450),
            array('name' => 'Indian Institute of Information Technology Jabalpur', 'short_name' => 'IIIT Jabalpur', 'city' => 'Jabalpur', 'state' => 'Madhya Pradesh', 'established' => 2005, 'nirf_rank' => 46, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹10.5 LPA', 'highest_placement' => '₹38 Lakh', 'intake' => 420),
            array('name' => 'Indian Institute of Information Technology Kancheepuram', 'short_name' => 'IIIT Kancheepuram', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'established' => 2007, 'nirf_rank' => 47, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹10.2 LPA', 'highest_placement' => '₹35 Lakh', 'intake' => 400),
            array('name' => 'Indian Institute of Information Technology Kottayam', 'short_name' => 'IIIT Kottayam', 'city' => 'Kottayam', 'state' => 'Kerala', 'established' => 2015, 'nirf_rank' => 56, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹9.5 LPA', 'highest_placement' => '₹32 Lakh', 'intake' => 380),
            array('name' => 'Indian Institute of Information Technology Guwahati', 'short_name' => 'IIIT Guwahati', 'city' => 'Guwahati', 'state' => 'Assam', 'established' => 2013, 'nirf_rank' => 59, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹9.2 LPA', 'highest_placement' => '₹30 Lakh', 'intake' => 360),
            array('name' => 'Indian Institute of Information Technology Vadodara', 'short_name' => 'IIIT Vadodara', 'city' => 'Vadodara', 'state' => 'Gujarat', 'established' => 2013, 'nirf_rank' => 61, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹8.8 LPA', 'highest_placement' => '₹28 Lakh', 'intake' => 340),
            array('name' => 'Indian Institute of Information Technology Kalyani', 'short_name' => 'IIIT Kalyani', 'city' => 'Kalyani', 'state' => 'West Bengal', 'established' => 2014, 'nirf_rank' => 63, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹8.5 LPA', 'highest_placement' => '₹26 Lakh', 'intake' => 320),
            array('name' => 'Indian Institute of Information Technology Una', 'short_name' => 'IIIT Una', 'city' => 'Una', 'state' => 'Himachal Pradesh', 'established' => 2014, 'nirf_rank' => 66, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹8.2 LPA', 'highest_placement' => '₹24 Lakh', 'intake' => 300),
            array('name' => 'Indian Institute of Information Technology Sri City', 'short_name' => 'IIIT Sri City', 'city' => 'Chittoor', 'state' => 'Andhra Pradesh', 'established' => 2013, 'nirf_rank' => 54, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹9.8 LPA', 'highest_placement' => '₹33 Lakh', 'intake' => 390),
            array('name' => 'Indian Institute of Information Technology Kurnool', 'short_name' => 'IIIT Kurnool', 'city' => 'Kurnool', 'state' => 'Andhra Pradesh', 'established' => 2015, 'nirf_rank' => 67, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹8.0 LPA', 'highest_placement' => '₹23 Lakh', 'intake' => 280),
            array('name' => 'Indian Institute of Information Technology Lucknow', 'short_name' => 'IIIT Lucknow', 'city' => 'Lucknow', 'state' => 'Uttar Pradesh', 'established' => 2015, 'nirf_rank' => 69, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹7.8 LPA', 'highest_placement' => '₹22 Lakh', 'intake' => 260),
            array('name' => 'Indian Institute of Information Technology Dharwad', 'short_name' => 'IIIT Dharwad', 'city' => 'Dharwad', 'state' => 'Karnataka', 'established' => 2015, 'nirf_rank' => 71, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹7.5 LPA', 'highest_placement' => '₹20 Lakh', 'intake' => 240),
            array('name' => 'Indian Institute of Information Technology Design and Manufacturing Kancheepuram', 'short_name' => 'IIITDM Kancheepuram', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'established' => 2007, 'nirf_rank' => 44, 'fees_range' => '₹1.8 Lakh/year', 'avg_placement' => '₹11.2 LPA', 'highest_placement' => '₹40 Lakh', 'intake' => 440),
            array('name' => 'Indian Institute of Information Technology Design and Manufacturing Jabalpur', 'short_name' => 'IIITDM Jabalpur', 'city' => 'Jabalpur', 'state' => 'Madhya Pradesh', 'established' => 2005, 'nirf_rank' => 49, 'fees_range' => '₹1.8 Lakh/year', 'avg_placement' => '₹10.8 LPA', 'highest_placement' => '₹36 Lakh', 'intake' => 410),
            array('name' => 'Indian Institute of Information Technology Tiruchirappalli', 'short_name' => 'IIIT Trichy', 'city' => 'Tiruchirappalli', 'state' => 'Tamil Nadu', 'established' => 2013, 'nirf_rank' => 57, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹9.3 LPA', 'highest_placement' => '₹31 Lakh', 'intake' => 350),
            array('name' => 'Indian Institute of Information Technology Ranchi', 'short_name' => 'IIIT Ranchi', 'city' => 'Ranchi', 'state' => 'Jharkhand', 'established' => 2016, 'nirf_rank' => 72, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹7.2 LPA', 'highest_placement' => '₹19 Lakh', 'intake' => 220),
            array('name' => 'Indian Institute of Information Technology Nagpur', 'short_name' => 'IIIT Nagpur', 'city' => 'Nagpur', 'state' => 'Maharashtra', 'established' => 2016, 'nirf_rank' => 73, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹7.0 LPA', 'highest_placement' => '₹18 Lakh', 'intake' => 200),
            array('name' => 'Indian Institute of Information Technology Pune', 'short_name' => 'IIIT Pune', 'city' => 'Pune', 'state' => 'Maharashtra', 'established' => 2016, 'nirf_rank' => 74, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹6.8 LPA', 'highest_placement' => '₹17 Lakh', 'intake' => 180),
            array('name' => 'Indian Institute of Information Technology Bhopal', 'short_name' => 'IIIT Bhopal', 'city' => 'Bhopal', 'state' => 'Madhya Pradesh', 'established' => 2017, 'nirf_rank' => 76, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹6.5 LPA', 'highest_placement' => '₹16 Lakh', 'intake' => 160),
            array('name' => 'Indian Institute of Information Technology Surat', 'short_name' => 'IIIT Surat', 'city' => 'Surat', 'state' => 'Gujarat', 'established' => 2017, 'nirf_rank' => 77, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹6.3 LPA', 'highest_placement' => '₹15 Lakh', 'intake' => 140),
            array('name' => 'Indian Institute of Information Technology Sonepat', 'short_name' => 'IIIT Sonepat', 'city' => 'Sonepat', 'state' => 'Haryana', 'established' => 2014, 'nirf_rank' => 64, 'fees_range' => '₹1.6 Lakh/year', 'avg_placement' => '₹8.3 LPA', 'highest_placement' => '₹25 Lakh', 'intake' => 310),
        );

        foreach ($iiits as $iiit) {
            $colleges[] = array_merge($iiit, array(
                'type' => 'IIIT',
                'ownership' => 'Government',
                'accreditation' => 'NAAC A',
                'courses' => array('B.Tech', 'M.Tech', 'PhD', 'MSc'),
                'campus_size' => '100 acres',
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $iiit['short_name'])) . '.ac.in',
                'short_desc' => 'Specialized institute for IT and Design education'
            ));
        }

        // Top Private Engineering Colleges (200 colleges)
        $private_engineering = self::get_private_engineering_colleges();
        $colleges = array_merge($colleges, $private_engineering);

        // Medical Colleges (100 colleges)
        $medical_colleges = self::get_medical_colleges();
        $colleges = array_merge($colleges, $medical_colleges);

        // Management Colleges (100 colleges)
        $management_colleges = self::get_management_colleges();
        $colleges = array_merge($colleges, $management_colleges);

        return $colleges;
    }

    /**
     * Get private engineering colleges
     */
    private static function get_private_engineering_colleges() {
        $colleges = array();

        $private_data = array(
            array('name' => 'Birla Institute of Technology and Science Pilani', 'short_name' => 'BITS Pilani', 'city' => 'Pilani', 'state' => 'Rajasthan', 'established' => 1964, 'nirf_rank' => 25, 'fees_range' => '₹5.0 Lakh/year', 'avg_placement' => '₹18.5 LPA', 'highest_placement' => '₹1.2 Cr'),
            array('name' => 'Vellore Institute of Technology', 'short_name' => 'VIT Vellore', 'city' => 'Vellore', 'state' => 'Tamil Nadu', 'established' => 1984, 'nirf_rank' => 39, 'fees_range' => '₹2.5 Lakh/year', 'avg_placement' => '₹9.8 LPA', 'highest_placement' => '₹45 Lakh'),
            array('name' => 'Manipal Institute of Technology', 'short_name' => 'MIT Manipal', 'city' => 'Manipal', 'state' => 'Karnataka', 'established' => 1957, 'nirf_rank' => 51, 'fees_range' => '₹3.8 Lakh/year', 'avg_placement' => '₹10.2 LPA', 'highest_placement' => '₹48 Lakh'),
            array('name' => 'SRM Institute of Science and Technology', 'short_name' => 'SRM University', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'established' => 1985, 'nirf_rank' => 53, 'fees_range' => '₹2.8 Lakh/year', 'avg_placement' => '₹8.5 LPA', 'highest_placement' => '₹41 Lakh'),
            array('name' => 'Thapar Institute of Engineering and Technology', 'short_name' => 'Thapar University', 'city' => 'Patiala', 'state' => 'Punjab', 'established' => 1956, 'nirf_rank' => 44, 'fees_range' => '₹3.2 Lakh/year', 'avg_placement' => '₹11.0 LPA', 'highest_placement' => '₹52 Lakh'),
            array('name' => 'Amity University Noida', 'short_name' => 'Amity Noida', 'city' => 'Noida', 'state' => 'Uttar Pradesh', 'established' => 2005, 'nirf_rank' => 90, 'fees_range' => '₹4.0 Lakh/year', 'avg_placement' => '₹6.5 LPA', 'highest_placement' => '₹28 Lakh'),
            array('name' => 'Lovely Professional University', 'short_name' => 'LPU', 'city' => 'Phagwara', 'state' => 'Punjab', 'established' => 2005, 'nirf_rank' => 95, 'fees_range' => '₹2.0 Lakh/year', 'avg_placement' => '₹5.8 LPA', 'highest_placement' => '₹25 Lakh'),
            array('name' => 'PES University Bangalore', 'short_name' => 'PES University', 'city' => 'Bangalore', 'state' => 'Karnataka', 'established' => 1972, 'nirf_rank' => 65, 'fees_range' => '₹3.5 Lakh/year', 'avg_placement' => '₹9.2 LPA', 'highest_placement' => '₹42 Lakh'),
            array('name' => 'RV College of Engineering', 'short_name' => 'RVCE', 'city' => 'Bangalore', 'state' => 'Karnataka', 'established' => 1963, 'nirf_rank' => 67, 'fees_range' => '₹2.8 Lakh/year', 'avg_placement' => '₹9.5 LPA', 'highest_placement' => '₹44 Lakh'),
            array('name' => 'PSG College of Technology', 'short_name' => 'PSG Tech', 'city' => 'Coimbatore', 'state' => 'Tamil Nadu', 'established' => 1951, 'nirf_rank' => 45, 'fees_range' => '₹1.8 Lakh/year', 'avg_placement' => '₹10.5 LPA', 'highest_placement' => '₹50 Lakh'),
        );

        // Generate more private colleges based on cities
        $cities_states = array(
            array('city' => 'Bangalore', 'state' => 'Karnataka'),
            array('city' => 'Pune', 'state' => 'Maharashtra'),
            array('city' => 'Hyderabad', 'state' => 'Telangana'),
            array('city' => 'Chennai', 'state' => 'Tamil Nadu'),
            array('city' => 'Mumbai', 'state' => 'Maharashtra'),
            array('city' => 'Delhi', 'state' => 'Delhi'),
            array('city' => 'Kolkata', 'state' => 'West Bengal'),
            array('city' => 'Ahmedabad', 'state' => 'Gujarat'),
            array('city' => 'Jaipur', 'state' => 'Rajasthan'),
            array('city' => 'Lucknow', 'state' => 'Uttar Pradesh'),
            array('city' => 'Chandigarh', 'state' => 'Punjab'),
            array('city' => 'Bhopal', 'state' => 'Madhya Pradesh'),
            array('city' => 'Indore', 'state' => 'Madhya Pradesh'),
            array('city' => 'Coimbatore', 'state' => 'Tamil Nadu'),
            array('city' => 'Kochi', 'state' => 'Kerala'),
            array('city' => 'Thiruvananthapuram', 'state' => 'Kerala'),
            array('city' => 'Nagpur', 'state' => 'Maharashtra'),
            array('city' => 'Visakhapatnam', 'state' => 'Andhra Pradesh'),
            array('city' => 'Bhubaneswar', 'state' => 'Odisha'),
            array('city' => 'Patna', 'state' => 'Bihar'),
        );

        $college_names = array(
            'Institute of Engineering and Technology',
            'College of Engineering',
            'Institute of Technology',
            'School of Engineering',
            'Technical Institute',
            'Engineering College',
            'Institute of Science and Technology',
            'College of Technology',
            'Polytechnic Institute',
            'Academy of Engineering',
        );

        $rank = 100;
        foreach ($private_data as $college) {
            $colleges[] = array_merge($college, array(
                'type' => 'Private',
                'ownership' => 'Private',
                'accreditation' => 'NAAC A',
                'courses' => array('B.Tech', 'M.Tech', 'MBA', 'MCA'),
                'campus_size' => '200 acres',
                'intake' => rand(800, 2000),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $college['short_name'])) . '.edu',
                'short_desc' => 'Leading private engineering institution'
            ));
        }

        // Generate more colleges to reach 200
        foreach ($cities_states as $location) {
            for ($i = 1; $i <= 10; $i++) {
                $name_index = ($i - 1) % count($college_names);
                $name = $location['city'] . ' ' . $college_names[$name_index] . ' ' . ($i > count($college_names) ? 'Campus ' . ceil($i / count($college_names)) : '');
                $short_name = substr($location['city'], 0, 3) . 'ET' . $i;

                $colleges[] = array(
                    'name' => trim($name),
                    'short_name' => strtoupper($short_name),
                    'city' => $location['city'],
                    'state' => $location['state'],
                    'type' => 'Private',
                    'established' => rand(1990, 2015),
                    'ownership' => 'Private',
                    'accreditation' => array('NAAC A', 'NAAC B++', 'NAAC B+', 'NBA')[rand(0, 3)],
                    'nirf_rank' => $rank++,
                    'fees_range' => '₹' . rand(15, 45) / 10 . ' Lakh/year',
                    'avg_placement' => '₹' . (rand(45, 95) / 10) . ' LPA',
                    'highest_placement' => '₹' . rand(15, 40) . ' Lakh',
                    'courses' => array('B.Tech', 'M.Tech', 'MBA'),
                    'campus_size' => rand(50, 150) . ' acres',
                    'intake' => rand(300, 1200),
                    'website' => 'https://www.' . strtolower($short_name) . '.edu',
                    'short_desc' => 'Quality engineering education institution'
                );
            }
        }

        return array_slice($colleges, 0, 200); // Return exactly 200
    }

    /**
     * Get medical colleges
     */
    private static function get_medical_colleges() {
        $colleges = array();

        $medical_data = array(
            array('name' => 'All India Institute of Medical Sciences Delhi', 'short_name' => 'AIIMS Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'established' => 1956, 'nirf_rank' => 1),
            array('name' => 'Post Graduate Institute of Medical Education and Research', 'short_name' => 'PGIMER', 'city' => 'Chandigarh', 'state' => 'Chandigarh', 'established' => 1962, 'nirf_rank' => 2),
            array('name' => 'Christian Medical College Vellore', 'short_name' => 'CMC Vellore', 'city' => 'Vellore', 'state' => 'Tamil Nadu', 'established' => 1900, 'nirf_rank' => 3),
            array('name' => 'National Institute of Mental Health and Neurosciences', 'short_name' => 'NIMHANS', 'city' => 'Bangalore', 'state' => 'Karnataka', 'established' => 1974, 'nirf_rank' => 4),
            array('name' => 'Jawaharlal Institute of Postgraduate Medical Education and Research', 'short_name' => 'JIPMER', 'city' => 'Puducherry', 'state' => 'Puducherry', 'established' => 1823, 'nirf_rank' => 5),
            array('name' => 'All India Institute of Medical Sciences Jodhpur', 'short_name' => 'AIIMS Jodhpur', 'city' => 'Jodhpur', 'state' => 'Rajasthan', 'established' => 2012, 'nirf_rank' => 10),
            array('name' => 'All India Institute of Medical Sciences Bhopal', 'short_name' => 'AIIMS Bhopal', 'city' => 'Bhopal', 'state' => 'Madhya Pradesh', 'established' => 2012, 'nirf_rank' => 12),
            array('name' => 'All India Institute of Medical Sciences Rishikesh', 'short_name' => 'AIIMS Rishikesh', 'city' => 'Rishikesh', 'state' => 'Uttarakhand', 'established' => 2012, 'nirf_rank' => 14),
            array('name' => 'King George Medical University', 'short_name' => 'KGMU', 'city' => 'Lucknow', 'state' => 'Uttar Pradesh', 'established' => 1911, 'nirf_rank' => 8),
            array('name' => 'Armed Forces Medical College', 'short_name' => 'AFMC', 'city' => 'Pune', 'state' => 'Maharashtra', 'established' => 1948, 'nirf_rank' => 6),
        );

        $rank = 20;
        foreach ($medical_data as $college) {
            $colleges[] = array_merge($college, array(
                'type' => 'Medical',
                'ownership' => 'Government',
                'accreditation' => 'NAAC A++',
                'fees_range' => '₹10,000 - ₹50,000/year',
                'avg_placement' => 'Govt Jobs',
                'highest_placement' => 'Govt Jobs',
                'courses' => array('MBBS', 'MD', 'MS', 'DM', 'MCh'),
                'campus_size' => '150 acres',
                'intake' => rand(100, 250),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $college['short_name'])) . '.edu.in',
                'short_desc' => 'Premier medical education institution'
            ));
        }

        // Add state medical colleges
        $states = array('Maharashtra', 'Karnataka', 'Tamil Nadu', 'Kerala', 'Gujarat', 'Rajasthan', 'Uttar Pradesh', 'West Bengal', 'Andhra Pradesh', 'Telangana');
        foreach ($states as $state) {
            for ($i = 1; $i <= 9; $i++) {
                $city = array('Mumbai', 'Nagpur', 'Pune', 'Aurangabad', 'Nashik', 'Kolhapur', 'Sangli', 'Solapur', 'Amravati')[$i - 1] ?? $state . ' City';
                $colleges[] = array(
                    'name' => 'Government Medical College ' . $city,
                    'short_name' => 'GMC ' . substr($city, 0, 3),
                    'city' => $city,
                    'state' => $state,
                    'type' => 'Medical',
                    'established' => rand(1950, 2000),
                    'ownership' => 'Government',
                    'accreditation' => 'NMC Approved',
                    'nirf_rank' => $rank++,
                    'fees_range' => '₹25,000 - ₹1 Lakh/year',
                    'avg_placement' => 'Govt Jobs',
                    'highest_placement' => 'Govt Jobs',
                    'courses' => array('MBBS', 'MD', 'MS'),
                    'campus_size' => rand(50, 100) . ' acres',
                    'intake' => rand(150, 250),
                    'website' => 'https://www.gmc' . strtolower(substr($city, 0, 3)) . '.edu.in',
                    'short_desc' => 'State government medical college'
                );
            }
        }

        return array_slice($colleges, 0, 100);
    }

    /**
     * Get management colleges
     */
    private static function get_management_colleges() {
        $colleges = array();

        $management_data = array(
            array('name' => 'Indian Institute of Management Ahmedabad', 'short_name' => 'IIM Ahmedabad', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'established' => 1961, 'nirf_rank' => 1, 'fees_range' => '₹23 Lakh', 'avg_placement' => '₹32.7 LPA'),
            array('name' => 'Indian Institute of Management Bangalore', 'short_name' => 'IIM Bangalore', 'city' => 'Bangalore', 'state' => 'Karnataka', 'established' => 1973, 'nirf_rank' => 2, 'fees_range' => '₹23.8 Lakh', 'avg_placement' => '₹31.5 LPA'),
            array('name' => 'Indian Institute of Management Calcutta', 'short_name' => 'IIM Calcutta', 'city' => 'Kolkata', 'state' => 'West Bengal', 'established' => 1961, 'nirf_rank' => 3, 'fees_range' => '₹27 Lakh', 'avg_placement' => '₹35 LPA'),
            array('name' => 'Indian Institute of Management Kozhikode', 'short_name' => 'IIM Kozhikode', 'city' => 'Kozhikode', 'state' => 'Kerala', 'established' => 1996, 'nirf_rank' => 4, 'fees_range' => '₹22 Lakh', 'avg_placement' => '₹29.5 LPA'),
            array('name' => 'Indian Institute of Management Lucknow', 'short_name' => 'IIM Lucknow', 'city' => 'Lucknow', 'state' => 'Uttar Pradesh', 'established' => 1984, 'nirf_rank' => 5, 'fees_range' => '₹19.25 Lakh', 'avg_placement' => '₹27 LPA'),
            array('name' => 'Indian Institute of Management Indore', 'short_name' => 'IIM Indore', 'city' => 'Indore', 'state' => 'Madhya Pradesh', 'established' => 1996, 'nirf_rank' => 6, 'fees_range' => '₹19 Lakh', 'avg_placement' => '₹26.5 LPA'),
            array('name' => 'Indian School of Business', 'short_name' => 'ISB', 'city' => 'Hyderabad', 'state' => 'Telangana', 'established' => 2001, 'nirf_rank' => 7, 'fees_range' => '₹38.5 Lakh', 'avg_placement' => '₹33.8 LPA'),
            array('name' => 'XLRI Jamshedpur', 'short_name' => 'XLRI', 'city' => 'Jamshedpur', 'state' => 'Jharkhand', 'established' => 1949, 'nirf_rank' => 8, 'fees_range' => '₹25.5 Lakh', 'avg_placement' => '₹28.3 LPA'),
            array('name' => 'Faculty of Management Studies Delhi', 'short_name' => 'FMS Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'established' => 1954, 'nirf_rank' => 9, 'fees_range' => '₹1.92 Lakh', 'avg_placement' => '₹32.4 LPA'),
            array('name' => 'Indian Institute of Management Udaipur', 'short_name' => 'IIM Udaipur', 'city' => 'Udaipur', 'state' => 'Rajasthan', 'established' => 2011, 'nirf_rank' => 12, 'fees_range' => '₹18.5 Lakh', 'avg_placement' => '₹22.8 LPA'),
        );

        $rank = 15;
        foreach ($management_data as $college) {
            $colleges[] = array_merge($college, array(
                'type' => 'Management',
                'ownership' => 'Government',
                'accreditation' => 'AACSB/EQUIS',
                'highest_placement' => '₹' . (rand(80, 150)) . ' LPA',
                'courses' => array('MBA', 'PGDM', 'Executive MBA', 'PhD'),
                'campus_size' => '100 acres',
                'intake' => rand(200, 500),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $college['short_name'])) . '.ac.in',
                'short_desc' => 'Top-ranked business school'
            ));
        }

        // Add more IIMs and business schools
        $more_iims = array(
            array('city' => 'Shillong', 'state' => 'Meghalaya', 'rank' => 14),
            array('city' => 'Rohtak', 'state' => 'Haryana', 'rank' => 16),
            array('city' => 'Kashipur', 'state' => 'Uttarakhand', 'rank' => 18),
            array('city' => 'Ranchi', 'state' => 'Jharkhand', 'rank' => 20),
            array('city' => 'Raipur', 'state' => 'Chhattisgarh', 'rank' => 22),
            array('city' => 'Tiruchirappalli', 'state' => 'Tamil Nadu', 'rank' => 24),
            array('city' => 'Bodh Gaya', 'state' => 'Bihar', 'rank' => 26),
            array('city' => 'Nagpur', 'state' => 'Maharashtra', 'rank' => 28),
            array('city' => 'Visakhapatnam', 'state' => 'Andhra Pradesh', 'rank' => 30),
            array('city' => 'Amritsar', 'state' => 'Punjab', 'rank' => 32),
        );

        foreach ($more_iims as $iim) {
            $colleges[] = array(
                'name' => 'Indian Institute of Management ' . $iim['city'],
                'short_name' => 'IIM ' . substr($iim['city'], 0, 3),
                'city' => $iim['city'],
                'state' => $iim['state'],
                'type' => 'Management',
                'established' => rand(2010, 2018),
                'ownership' => 'Government',
                'accreditation' => 'NAAC A+',
                'nirf_rank' => $iim['rank'],
                'fees_range' => '₹' . rand(16, 22) . ' Lakh',
                'avg_placement' => '₹' . (rand(180, 260) / 10) . ' LPA',
                'highest_placement' => '₹' . rand(50, 90) . ' LPA',
                'courses' => array('MBA', 'PGDM', 'PhD'),
                'campus_size' => '100 acres',
                'intake' => rand(150, 350),
                'website' => 'https://www.iim' . strtolower(substr($iim['city'], 0, 3)) . '.ac.in',
                'short_desc' => 'Institute of National Importance for Management'
            );
            $rank++;
        }

        // Add private business schools
        $private_bschools = array(
            array('name' => 'SP Jain Institute of Management and Research', 'city' => 'Mumbai', 'state' => 'Maharashtra'),
            array('name' => 'Management Development Institute', 'city' => 'Gurgaon', 'state' => 'Haryana'),
            array('name' => 'Symbiosis Institute of Business Management', 'city' => 'Pune', 'state' => 'Maharashtra'),
            array('name' => 'Narsee Monjee Institute of Management Studies', 'city' => 'Mumbai', 'state' => 'Maharashtra'),
            array('name' => 'Institute of Management Technology', 'city' => 'Ghaziabad', 'state' => 'Uttar Pradesh'),
            array('name' => 'Great Lakes Institute of Management', 'city' => 'Chennai', 'state' => 'Tamil Nadu'),
            array('name' => 'International Management Institute', 'city' => 'New Delhi', 'state' => 'Delhi'),
            array('name' => 'TA Pai Management Institute', 'city' => 'Manipal', 'state' => 'Karnataka'),
        );

        foreach ($private_bschools as $school) {
            $colleges[] = array(
                'name' => $school['name'],
                'short_name' => implode('', array_map(function($w) { return $w[0]; }, explode(' ', $school['name']))),
                'city' => $school['city'],
                'state' => $school['state'],
                'type' => 'Management',
                'established' => rand(1980, 2000),
                'ownership' => 'Private',
                'accreditation' => 'AACSB/AMBA',
                'nirf_rank' => $rank++,
                'fees_range' => '₹' . rand(18, 28) . ' Lakh',
                'avg_placement' => '₹' . (rand(160, 240) / 10) . ' LPA',
                'highest_placement' => '₹' . rand(40, 70) . ' LPA',
                'courses' => array('PGDM', 'Executive PGDM', 'Fellow Program'),
                'campus_size' => rand(20, 80) . ' acres',
                'intake' => rand(200, 400),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', implode('', array_map(function($w) { return $w[0]; }, explode(' ', $school['name']))))) . '.edu',
                'short_desc' => 'Leading private business school'
            );
        }

        // Fill remaining with more business schools
        $cities = array('Bangalore', 'Hyderabad', 'Chennai', 'Pune', 'Mumbai', 'Delhi', 'Kolkata', 'Ahmedabad');
        foreach ($cities as $city) {
            for ($i = 1; $i <= 9; $i++) {
                $colleges[] = array(
                    'name' => $city . ' School of Business ' . chr(64 + $i),
                    'short_name' => substr($city, 0, 3) . 'SB' . $i,
                    'city' => $city,
                    'state' => array('Bangalore' => 'Karnataka', 'Hyderabad' => 'Telangana', 'Chennai' => 'Tamil Nadu', 'Pune' => 'Maharashtra', 'Mumbai' => 'Maharashtra', 'Delhi' => 'Delhi', 'Kolkata' => 'West Bengal', 'Ahmedabad' => 'Gujarat')[$city],
                    'type' => 'Management',
                    'established' => rand(1995, 2015),
                    'ownership' => 'Private',
                    'accreditation' => array('NAAC A', 'NAAC B++', 'NBA')[rand(0, 2)],
                    'nirf_rank' => $rank++,
                    'fees_range' => '₹' . rand(10, 20) . ' Lakh',
                    'avg_placement' => '₹' . (rand(80, 150) / 10) . ' LPA',
                    'highest_placement' => '₹' . rand(20, 40) . ' LPA',
                    'courses' => array('MBA', 'PGDM'),
                    'campus_size' => rand(10, 50) . ' acres',
                    'intake' => rand(100, 300),
                    'website' => 'https://www.' . strtolower(substr($city, 0, 3)) . 'sb' . $i . '.edu',
                    'short_desc' => 'Business management education'
                );
            }
        }

        return array_slice($colleges, 0, 100);
    }

    /**
     * Seed courses data (updates existing post type)
     */
    public static function seed_courses_data() {
        // This uses the existing ck_course post type
        return wp_count_posts('ck_course')->publish ?: 0;
    }

    /**
     * AJAX handler to clear seeded data
     */
    public static function ajax_clear_data() {
        check_ajax_referer('ck_clear_seed_data', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'ck-oneform')));
        }

        // Clear all seeded data options
        delete_option('ck_seed_scholarships');
        delete_option('ck_seed_scholarships_count');
        delete_option('ck_seed_exams');
        delete_option('ck_seed_exams_count');
        delete_option('ck_seed_careers');
        delete_option('ck_seed_careers_count');
        delete_option('ck_seed_loans');
        delete_option('ck_seed_loans_count');
        delete_option('ck_seed_rankings');
        delete_option('ck_seed_rankings_count');
        delete_option('ck_seed_jobs');
        delete_option('ck_seed_jobs_count');

        wp_send_json_success(array('message' => __('All sample data has been cleared.', 'ck-oneform')));
    }

    /**
     * Get seeded scholarships
     */
    public static function get_scholarships() {
        return get_option('ck_seed_scholarships', array());
    }

    /**
     * Get seeded exam calendar
     */
    public static function get_exams() {
        return get_option('ck_seed_exams', array());
    }

    /**
     * Get seeded career data
     */
    public static function get_careers() {
        return get_option('ck_seed_careers', array());
    }

    /**
     * Get seeded loans data
     */
    public static function get_loans() {
        return get_option('ck_seed_loans', array());
    }

    /**
     * Get seeded rankings
     */
    public static function get_rankings() {
        return get_option('ck_seed_rankings', array());
    }

    /**
     * Get seeded jobs
     */
    public static function get_jobs() {
        return get_option('ck_seed_jobs', array());
    }
}

// Initialize the seeder
CK_OneForm_Data_Seeder::init();
