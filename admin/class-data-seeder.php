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
        add_action('admin_menu', array(__CLASS__, 'add_seeder_page'));
        add_action('wp_ajax_ck_seed_data', array(__CLASS__, 'ajax_seed_data'));
        add_action('wp_ajax_ck_clear_seed_data', array(__CLASS__, 'ajax_clear_data'));
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
                        <p><?php _e('Top 100 colleges with rankings, fees, placements', 'ck-oneform'); ?></p>
                        <button class="button ck-seed-btn" data-type="colleges">
                            <?php _e('Seed Colleges', 'ck-oneform'); ?>
                        </button>
                        <span class="ck-seed-status"></span>
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
            array(
                'name' => 'National Merit Scholarship',
                'provider' => 'Government of India',
                'amount' => '₹50,000/year',
                'eligibility' => 'Class 12 marks above 85%, Family income below 6 LPA',
                'deadline' => date('Y-m-d', strtotime('+3 months')),
                'category' => 'merit',
                'courses' => array('Engineering', 'Medical', 'Law'),
            ),
            array(
                'name' => 'INSPIRE Scholarship',
                'provider' => 'Department of Science & Technology',
                'amount' => '₹80,000/year',
                'eligibility' => 'Top 1% in Class 12 Board Exams',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'merit',
                'courses' => array('Science', 'Engineering'),
            ),
            array(
                'name' => 'Post Matric Scholarship for SC/ST',
                'provider' => 'Ministry of Social Justice',
                'amount' => '₹1,200 - ₹3,000/month',
                'eligibility' => 'SC/ST category, Income below 2.5 LPA',
                'deadline' => date('Y-m-d', strtotime('+2 months')),
                'category' => 'need-based',
                'courses' => array('All'),
            ),
            array(
                'name' => 'Central Sector Scholarship',
                'provider' => 'MHRD',
                'amount' => '₹12,000/year',
                'eligibility' => 'Top 20% in Class 12, Family income below 8 LPA',
                'deadline' => date('Y-m-d', strtotime('+5 months')),
                'category' => 'merit',
                'courses' => array('All undergraduate courses'),
            ),
            array(
                'name' => 'Prime Minister\'s Scholarship Scheme',
                'provider' => 'Ministry of Defence',
                'amount' => '₹2,500/month (Boys), ₹3,000/month (Girls)',
                'eligibility' => 'Wards of ex-servicemen/ex-coast guard',
                'deadline' => date('Y-m-d', strtotime('+6 months')),
                'category' => 'special',
                'courses' => array('Professional Courses'),
            ),
            array(
                'name' => 'Reliance Foundation Scholarship',
                'provider' => 'Reliance Foundation',
                'amount' => 'Up to ₹2,00,000/year',
                'eligibility' => 'UG/PG students in STEM, Income below 15 LPA',
                'deadline' => date('Y-m-d', strtotime('+1 month')),
                'category' => 'corporate',
                'courses' => array('Engineering', 'Science', 'Mathematics'),
            ),
            array(
                'name' => 'Tata Trusts Scholarship',
                'provider' => 'Tata Trusts',
                'amount' => '₹50,000 - ₹1,50,000/year',
                'eligibility' => 'Meritorious students from low-income families',
                'deadline' => date('Y-m-d', strtotime('+2 months')),
                'category' => 'corporate',
                'courses' => array('All'),
            ),
            array(
                'name' => 'Aditya Birla Scholarship',
                'provider' => 'Aditya Birla Group',
                'amount' => '₹65,000 - ₹1,80,000/year',
                'eligibility' => 'Top rankers in IIT-JEE, CLAT, CAT',
                'deadline' => date('Y-m-d', strtotime('+7 months')),
                'category' => 'corporate',
                'courses' => array('Engineering', 'Law', 'Management'),
            ),
            array(
                'name' => 'LIC Golden Jubilee Scholarship',
                'provider' => 'LIC of India',
                'amount' => '₹20,000/year',
                'eligibility' => 'Class 12 passed, Family income below 2 LPA',
                'deadline' => date('Y-m-d', strtotime('+3 months')),
                'category' => 'need-based',
                'courses' => array('All'),
            ),
            array(
                'name' => 'HDFC Badhte Kadam Scholarship',
                'provider' => 'HDFC Bank',
                'amount' => '₹25,000/year',
                'eligibility' => 'Differently-abled students, 60% in last exam',
                'deadline' => date('Y-m-d', strtotime('+4 months')),
                'category' => 'special',
                'courses' => array('All'),
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
     * Seed colleges data (updates existing post type)
     */
    public static function seed_colleges_data() {
        // This uses the existing ck_college post type
        // Just return current count as colleges are already seeded
        return wp_count_posts('ck_college')->publish ?: 0;
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
