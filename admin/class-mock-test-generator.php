<?php
/**
 * Mock Test Generator System
 * Complete test creation, management, and student assignment
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Mock_Test_Generator {

    /**
     * Initialize
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu'));
        add_action('admin_post_ck_save_mock_test_full', array(__CLASS__, 'save_mock_test'));
        add_action('admin_post_ck_delete_mock_test_full', array(__CLASS__, 'delete_mock_test'));
        add_action('admin_post_ck_save_test_questions', array(__CLASS__, 'save_questions'));
        add_action('admin_post_ck_assign_test', array(__CLASS__, 'assign_test'));
        add_action('wp_ajax_ck_start_test', array(__CLASS__, 'ajax_start_test'));
        add_action('wp_ajax_ck_submit_test', array(__CLASS__, 'ajax_submit_test'));
        add_action('wp_ajax_ck_save_answer', array(__CLASS__, 'ajax_save_answer'));
    }

    /**
     * Add admin menu
     */
    public static function add_menu() {
        add_submenu_page(
            'ck-student-portal',
            'Test Generator',
            'Test Generator',
            'manage_options',
            'ck-test-generator',
            array(__CLASS__, 'test_generator_page')
        );
    }

    /**
     * Create required tables
     */
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Questions table
        $questions_table = $wpdb->prefix . 'ck_oneform_questions';
        $wpdb->query("CREATE TABLE IF NOT EXISTS $questions_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            test_id bigint(20) UNSIGNED NOT NULL,
            question_number int(11) NOT NULL,
            question_text text NOT NULL,
            option_a text NOT NULL,
            option_b text NOT NULL,
            option_c text NOT NULL,
            option_d text NOT NULL,
            correct_answer char(1) NOT NULL,
            marks int(11) DEFAULT 1,
            negative_marks decimal(5,2) DEFAULT 0,
            explanation text,
            subject varchar(100) DEFAULT NULL,
            difficulty varchar(20) DEFAULT 'medium',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY test_id (test_id),
            KEY question_number (question_number)
        ) $charset_collate;");

        // Student test attempts table
        $attempts_table = $wpdb->prefix . 'ck_oneform_test_attempts';
        $wpdb->query("CREATE TABLE IF NOT EXISTS $attempts_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            test_id bigint(20) UNSIGNED NOT NULL,
            started_at datetime DEFAULT CURRENT_TIMESTAMP,
            completed_at datetime DEFAULT NULL,
            time_taken int(11) DEFAULT 0,
            total_questions int(11) DEFAULT 0,
            attempted int(11) DEFAULT 0,
            correct int(11) DEFAULT 0,
            wrong int(11) DEFAULT 0,
            score decimal(10,2) DEFAULT 0,
            percentage decimal(5,2) DEFAULT 0,
            status varchar(20) DEFAULT 'in_progress',
            answers longtext,
            PRIMARY KEY (id),
            KEY student_id (student_id),
            KEY test_id (test_id),
            KEY status (status)
        ) $charset_collate;");

        // Test assignments table
        $assignments_table = $wpdb->prefix . 'ck_oneform_test_assignments';
        $wpdb->query("CREATE TABLE IF NOT EXISTS $assignments_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            test_id bigint(20) UNSIGNED NOT NULL,
            student_id bigint(20) UNSIGNED NOT NULL,
            assigned_at datetime DEFAULT CURRENT_TIMESTAMP,
            due_date datetime DEFAULT NULL,
            max_attempts int(11) DEFAULT 1,
            attempts_used int(11) DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY test_student (test_id, student_id),
            KEY test_id (test_id),
            KEY student_id (student_id)
        ) $charset_collate;");
    }

    /**
     * Main test generator page
     */
    public static function test_generator_page() {
        global $wpdb;

        // Create tables if needed
        self::create_tables();

        $tests_table = $wpdb->prefix . 'ck_oneform_mock_tests';
        $questions_table = $wpdb->prefix . 'ck_oneform_questions';

        // Handle different views
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

        ?>
        <div class="wrap ck-test-generator">
            <h1>
                <span class="dashicons dashicons-welcome-learn-more" style="font-size: 30px; margin-right: 10px;"></span>
                Mock Test Generator
            </h1>

            <?php if (isset($_GET['saved'])): ?>
            <div class="notice notice-success"><p>Test saved successfully!</p></div>
            <?php endif; ?>

            <?php if (isset($_GET['questions_saved'])): ?>
            <div class="notice notice-success"><p>Questions saved successfully!</p></div>
            <?php endif; ?>

            <?php if (isset($_GET['assigned'])): ?>
            <div class="notice notice-success"><p>Test assigned to students successfully!</p></div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
            <div class="notice notice-success"><p>Test deleted successfully!</p></div>
            <?php endif; ?>

            <?php if ($action === 'create' || $action === 'edit'): ?>
                <?php self::render_test_form($test_id); ?>
            <?php elseif ($action === 'questions'): ?>
                <?php self::render_questions_form($test_id); ?>
            <?php elseif ($action === 'assign'): ?>
                <?php self::render_assign_form($test_id); ?>
            <?php elseif ($action === 'results'): ?>
                <?php self::render_test_results($test_id); ?>
            <?php else: ?>
                <?php self::render_tests_list(); ?>
            <?php endif; ?>
        </div>

        <style>
        .ck-test-generator {
            max-width: 1400px;
        }

        .test-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .test-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
        }

        .test-card h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .test-card .test-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 15px 0;
            font-size: 14px;
            color: #666;
        }

        .test-card .test-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .test-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-cuet { background: #dbeafe; color: #1e40af; }
        .badge-neet { background: #d1fae5; color: #065f46; }
        .badge-jee { background: #fef3c7; color: #92400e; }
        .badge-cat { background: #fce7f3; color: #9d174d; }
        .badge-other { background: #f3f4f6; color: #4b5563; }

        .test-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: none;
        }

        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #5568d3; color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; color: white; }
        .btn-info { background: #06b6d4; color: white; }
        .btn-info:hover { background: #0891b2; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; color: white; }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .form-section h2 {
            margin: 0 0 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .question-block {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #e0e0e0;
        }

        .question-block h4 {
            margin: 0 0 15px;
            color: #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }

        .option-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .option-group label {
            font-weight: 600;
            color: #667eea;
            min-width: 30px;
        }

        .option-group input {
            flex: 1;
            padding: 8px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
        }

        .correct-answer-group {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .correct-answer-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 8px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-weight: 600;
        }

        .correct-answer-group input[type="radio"]:checked + span {
            color: #10b981;
        }

        .correct-answer-group label:has(input:checked) {
            border-color: #10b981;
            background: #d1fae5;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .empty-state .dashicons {
            font-size: 60px;
            width: 60px;
            height: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-active { background: #d1fae5; color: #065f46; }
        .status-draft { background: #fef3c7; color: #92400e; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .stat-box h4 {
            margin: 0;
            font-size: 2rem;
            color: #667eea;
        }

        .stat-box p {
            margin: 5px 0 0;
            color: #666;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .options-grid {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
            }
        }
        </style>
        <?php
    }

    /**
     * Render tests list
     */
    private static function render_tests_list() {
        global $wpdb;
        $tests_table = $wpdb->prefix . 'ck_oneform_mock_tests';
        $questions_table = $wpdb->prefix . 'ck_oneform_questions';

        // Create mock_tests table if not exists
        $wpdb->query("CREATE TABLE IF NOT EXISTS $tests_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            exam_type varchar(50) DEFAULT 'other',
            duration int(11) DEFAULT 60,
            total_questions int(11) DEFAULT 0,
            total_marks int(11) DEFAULT 0,
            passing_marks int(11) DEFAULT 0,
            negative_marking tinyint(1) DEFAULT 0,
            shuffle_questions tinyint(1) DEFAULT 0,
            show_result_immediately tinyint(1) DEFAULT 1,
            instructions text,
            status varchar(20) DEFAULT 'draft',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) " . $wpdb->get_charset_collate() . ";");

        $tests = $wpdb->get_results("SELECT * FROM $tests_table ORDER BY created_at DESC");
        ?>
        <div class="actions-bar" style="margin-bottom: 20px;">
            <a href="<?php echo admin_url('admin.php?page=ck-test-generator&action=create'); ?>" class="button button-primary button-large">
                <span class="dashicons dashicons-plus-alt" style="vertical-align: middle;"></span> Create New Test
            </a>
        </div>

        <?php if (empty($tests)): ?>
        <div class="empty-state">
            <span class="dashicons dashicons-welcome-learn-more"></span>
            <h3>No Mock Tests Created</h3>
            <p>Create your first mock test for CUET, NEET, JEE, CAT or other exams.</p>
            <a href="<?php echo admin_url('admin.php?page=ck-test-generator&action=create'); ?>" class="btn-action btn-primary">
                Create Your First Test
            </a>
        </div>
        <?php else: ?>
        <div class="test-cards">
            <?php foreach ($tests as $test): ?>
            <?php
            $question_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $questions_table WHERE test_id = %d",
                $test->id
            ));
            ?>
            <div class="test-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span class="test-badge badge-<?php echo strtolower($test->exam_type); ?>">
                        <?php echo esc_html($test->exam_type); ?>
                    </span>
                    <span class="status-badge status-<?php echo esc_attr($test->status); ?>">
                        <?php echo ucfirst($test->status); ?>
                    </span>
                </div>

                <h3><?php echo esc_html($test->title); ?></h3>
                <p style="color: #666; margin: 0;"><?php echo esc_html(wp_trim_words($test->description, 20)); ?></p>

                <div class="test-meta">
                    <span><span class="dashicons dashicons-clock"></span> <?php echo intval($test->duration); ?> mins</span>
                    <span><span class="dashicons dashicons-editor-help"></span> <?php echo intval($question_count); ?>/<?php echo intval($test->total_questions); ?> Questions</span>
                    <span><span class="dashicons dashicons-awards"></span> <?php echo intval($test->total_marks); ?> Marks</span>
                </div>

                <div class="test-actions">
                    <a href="<?php echo admin_url('admin.php?page=ck-test-generator&action=edit&test_id=' . $test->id); ?>" class="btn-action btn-primary">
                        <span class="dashicons dashicons-edit"></span> Edit
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=ck-test-generator&action=questions&test_id=' . $test->id); ?>" class="btn-action btn-success">
                        <span class="dashicons dashicons-list-view"></span> Questions
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=ck-test-generator&action=assign&test_id=' . $test->id); ?>" class="btn-action btn-warning">
                        <span class="dashicons dashicons-groups"></span> Assign
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=ck-test-generator&action=results&test_id=' . $test->id); ?>" class="btn-action btn-info">
                        <span class="dashicons dashicons-chart-bar"></span> Results
                    </a>
                    <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=ck_delete_mock_test_full&test_id=' . $test->id), 'ck_delete_test_nonce', 'delete_nonce'); ?>"
                       class="btn-action btn-danger"
                       onclick="return confirm('Are you sure you want to delete this test? All questions and results will be deleted.');">
                        <span class="dashicons dashicons-trash"></span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif;
    }

    /**
     * Render test creation/edit form
     */
    private static function render_test_form($test_id = 0) {
        global $wpdb;
        $tests_table = $wpdb->prefix . 'ck_oneform_mock_tests';

        $test = null;
        if ($test_id) {
            $test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tests_table WHERE id = %d", $test_id));
        }

        ?>
        <div class="form-section">
            <h2>
                <span class="dashicons dashicons-welcome-write-blog"></span>
                <?php echo $test ? 'Edit Test' : 'Create New Mock Test'; ?>
            </h2>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ck_save_mock_test_full">
                <input type="hidden" name="test_id" value="<?php echo intval($test_id); ?>">
                <?php wp_nonce_field('ck_save_test_nonce', 'test_nonce'); ?>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Test Title *</label>
                        <input type="text" name="title" value="<?php echo $test ? esc_attr($test->title) : ''; ?>" required placeholder="e.g., CUET Mock Test Series 1">
                    </div>

                    <div class="form-group">
                        <label>Exam Type *</label>
                        <select name="exam_type" required>
                            <option value="">Select Exam Type</option>
                            <option value="CUET" <?php echo ($test && $test->exam_type === 'CUET') ? 'selected' : ''; ?>>CUET</option>
                            <option value="NEET" <?php echo ($test && $test->exam_type === 'NEET') ? 'selected' : ''; ?>>NEET</option>
                            <option value="JEE" <?php echo ($test && $test->exam_type === 'JEE') ? 'selected' : ''; ?>>JEE</option>
                            <option value="CAT" <?php echo ($test && $test->exam_type === 'CAT') ? 'selected' : ''; ?>>CAT</option>
                            <option value="GATE" <?php echo ($test && $test->exam_type === 'GATE') ? 'selected' : ''; ?>>GATE</option>
                            <option value="UPSC" <?php echo ($test && $test->exam_type === 'UPSC') ? 'selected' : ''; ?>>UPSC</option>
                            <option value="Other" <?php echo ($test && $test->exam_type === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="draft" <?php echo ($test && $test->status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="active" <?php echo ($test && $test->status === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($test && $test->status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Duration (minutes) *</label>
                        <input type="number" name="duration" value="<?php echo $test ? intval($test->duration) : 60; ?>" required min="1" max="300">
                    </div>

                    <div class="form-group">
                        <label>Total Questions *</label>
                        <input type="number" name="total_questions" value="<?php echo $test ? intval($test->total_questions) : 50; ?>" required min="1" max="500">
                    </div>

                    <div class="form-group">
                        <label>Total Marks *</label>
                        <input type="number" name="total_marks" value="<?php echo $test ? intval($test->total_marks) : 100; ?>" required min="1">
                    </div>

                    <div class="form-group">
                        <label>Passing Marks</label>
                        <input type="number" name="passing_marks" value="<?php echo $test ? intval($test->passing_marks) : 40; ?>" min="0">
                    </div>

                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Brief description of the test"><?php echo $test ? esc_textarea($test->description) : ''; ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Instructions</label>
                        <textarea name="instructions" rows="5" placeholder="Instructions for students taking the test"><?php echo $test ? esc_textarea($test->instructions) : 'Read all questions carefully before answering.\nEach question carries equal marks unless specified.\nThere is negative marking for wrong answers.\nDo not refresh the page during the test.\nSubmit your test before the time runs out.'; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="negative_marking" value="1" <?php echo ($test && $test->negative_marking) ? 'checked' : ''; ?>>
                            Enable Negative Marking
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="shuffle_questions" value="1" <?php echo ($test && $test->shuffle_questions) ? 'checked' : ''; ?>>
                            Shuffle Questions
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="show_result_immediately" value="1" <?php echo (!$test || $test->show_result_immediately) ? 'checked' : ''; ?>>
                            Show Result Immediately After Test
                        </label>
                    </div>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <span class="dashicons dashicons-saved" style="vertical-align: middle;"></span>
                        <?php echo $test ? 'Update Test' : 'Create Test'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=ck-test-generator'); ?>" class="button button-secondary button-large">Cancel</a>
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * Render questions form
     */
    private static function render_questions_form($test_id) {
        global $wpdb;
        $tests_table = $wpdb->prefix . 'ck_oneform_mock_tests';
        $questions_table = $wpdb->prefix . 'ck_oneform_questions';

        $test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tests_table WHERE id = %d", $test_id));

        if (!$test) {
            echo '<div class="notice notice-error"><p>Test not found.</p></div>';
            return;
        }

        $existing_questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $questions_table WHERE test_id = %d ORDER BY question_number ASC",
            $test_id
        ));

        $questions_map = array();
        foreach ($existing_questions as $q) {
            $questions_map[$q->question_number] = $q;
        }

        ?>
        <div class="form-section">
            <h2>
                <span class="dashicons dashicons-list-view"></span>
                Questions for: <?php echo esc_html($test->title); ?>
            </h2>
            <p><strong>Total Questions Required:</strong> <?php echo intval($test->total_questions); ?> |
               <strong>Added:</strong> <?php echo count($existing_questions); ?></p>
        </div>

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="ck_save_test_questions">
            <input type="hidden" name="test_id" value="<?php echo intval($test_id); ?>">
            <?php wp_nonce_field('ck_save_questions_nonce', 'questions_nonce'); ?>

            <?php for ($i = 1; $i <= $test->total_questions; $i++): ?>
            <?php $existing = isset($questions_map[$i]) ? $questions_map[$i] : null; ?>
            <div class="question-block">
                <h4>
                    Question <?php echo $i; ?>
                    <?php if ($existing): ?>
                    <span style="font-size: 12px; color: #10b981;">✓ Saved</span>
                    <?php endif; ?>
                </h4>

                <div class="form-group">
                    <label>Question Text *</label>
                    <textarea name="questions[<?php echo $i; ?>][text]" rows="3" required placeholder="Enter the question"><?php echo $existing ? esc_textarea($existing->question_text) : ''; ?></textarea>
                </div>

                <div class="options-grid">
                    <div class="option-group">
                        <label>A)</label>
                        <input type="text" name="questions[<?php echo $i; ?>][option_a]" value="<?php echo $existing ? esc_attr($existing->option_a) : ''; ?>" required placeholder="Option A">
                    </div>
                    <div class="option-group">
                        <label>B)</label>
                        <input type="text" name="questions[<?php echo $i; ?>][option_b]" value="<?php echo $existing ? esc_attr($existing->option_b) : ''; ?>" required placeholder="Option B">
                    </div>
                    <div class="option-group">
                        <label>C)</label>
                        <input type="text" name="questions[<?php echo $i; ?>][option_c]" value="<?php echo $existing ? esc_attr($existing->option_c) : ''; ?>" required placeholder="Option C">
                    </div>
                    <div class="option-group">
                        <label>D)</label>
                        <input type="text" name="questions[<?php echo $i; ?>][option_d]" value="<?php echo $existing ? esc_attr($existing->option_d) : ''; ?>" required placeholder="Option D">
                    </div>
                </div>

                <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <div class="form-group">
                        <label>Correct Answer *</label>
                        <div class="correct-answer-group">
                            <label><input type="radio" name="questions[<?php echo $i; ?>][correct]" value="A" <?php echo ($existing && $existing->correct_answer === 'A') ? 'checked' : ''; ?> required> <span>A</span></label>
                            <label><input type="radio" name="questions[<?php echo $i; ?>][correct]" value="B" <?php echo ($existing && $existing->correct_answer === 'B') ? 'checked' : ''; ?>> <span>B</span></label>
                            <label><input type="radio" name="questions[<?php echo $i; ?>][correct]" value="C" <?php echo ($existing && $existing->correct_answer === 'C') ? 'checked' : ''; ?>> <span>C</span></label>
                            <label><input type="radio" name="questions[<?php echo $i; ?>][correct]" value="D" <?php echo ($existing && $existing->correct_answer === 'D') ? 'checked' : ''; ?>> <span>D</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" name="questions[<?php echo $i; ?>][marks]" value="<?php echo $existing ? intval($existing->marks) : 1; ?>" min="1">
                    </div>
                    <div class="form-group">
                        <label>Negative Marks</label>
                        <input type="number" step="0.25" name="questions[<?php echo $i; ?>][negative]" value="<?php echo $existing ? floatval($existing->negative_marks) : 0.25; ?>" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Explanation (Optional)</label>
                    <textarea name="questions[<?php echo $i; ?>][explanation]" rows="2" placeholder="Explanation for the correct answer"><?php echo $existing ? esc_textarea($existing->explanation) : ''; ?></textarea>
                </div>
            </div>
            <?php endfor; ?>

            <p class="submit" style="position: sticky; bottom: 0; background: white; padding: 20px; margin: 0 -20px; border-top: 2px solid #667eea;">
                <button type="submit" class="button button-primary button-large">
                    <span class="dashicons dashicons-saved" style="vertical-align: middle;"></span>
                    Save All Questions
                </button>
                <a href="<?php echo admin_url('admin.php?page=ck-test-generator'); ?>" class="button button-secondary button-large">Back to Tests</a>
            </p>
        </form>
        <?php
    }

    /**
     * Render assign form
     */
    private static function render_assign_form($test_id) {
        global $wpdb;
        $tests_table = $wpdb->prefix . 'ck_oneform_mock_tests';
        $students_table = $wpdb->prefix . 'ck_oneform_students';
        $assignments_table = $wpdb->prefix . 'ck_oneform_test_assignments';

        $test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tests_table WHERE id = %d", $test_id));

        if (!$test) {
            echo '<div class="notice notice-error"><p>Test not found.</p></div>';
            return;
        }

        $students = $wpdb->get_results("SELECT * FROM $students_table ORDER BY full_name ASC");

        // Get already assigned students
        $assigned = $wpdb->get_col($wpdb->prepare(
            "SELECT student_id FROM $assignments_table WHERE test_id = %d",
            $test_id
        ));

        ?>
        <div class="form-section">
            <h2>
                <span class="dashicons dashicons-groups"></span>
                Assign Test: <?php echo esc_html($test->title); ?>
            </h2>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ck_assign_test">
                <input type="hidden" name="test_id" value="<?php echo intval($test_id); ?>">
                <?php wp_nonce_field('ck_assign_test_nonce', 'assign_nonce'); ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Max Attempts per Student</label>
                        <input type="number" name="max_attempts" value="3" min="1" max="10">
                    </div>
                    <div class="form-group">
                        <label>Due Date (Optional)</label>
                        <input type="date" name="due_date" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label>
                        <input type="checkbox" id="select-all-students">
                        <strong>Select All Students</strong>
                    </label>
                </div>

                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin: 20px 0;">
                    <?php if (empty($students)): ?>
                    <p>No students registered yet.</p>
                    <?php else: ?>
                    <?php foreach ($students as $student): ?>
                    <div style="padding: 10px; border-bottom: 1px solid #f0f0f0;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="students[]" value="<?php echo $student->id; ?>" class="student-checkbox" <?php echo in_array($student->id, $assigned) ? 'checked' : ''; ?>>
                            <strong><?php echo esc_html($student->full_name); ?></strong>
                            <span style="color: #666;">(<?php echo esc_html($student->email); ?>)</span>
                            <?php if (in_array($student->id, $assigned)): ?>
                            <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 10px; font-size: 11px;">Already Assigned</span>
                            <?php endif; ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <span class="dashicons dashicons-yes" style="vertical-align: middle;"></span>
                        Assign Test to Selected Students
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=ck-test-generator'); ?>" class="button button-secondary button-large">Cancel</a>
                </p>
            </form>
        </div>

        <script>
        jQuery('#select-all-students').on('change', function() {
            jQuery('.student-checkbox').prop('checked', this.checked);
        });
        </script>
        <?php
    }

    /**
     * Render test results
     */
    private static function render_test_results($test_id) {
        global $wpdb;
        $tests_table = $wpdb->prefix . 'ck_oneform_mock_tests';
        $attempts_table = $wpdb->prefix . 'ck_oneform_test_attempts';
        $students_table = $wpdb->prefix . 'ck_oneform_students';

        $test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tests_table WHERE id = %d", $test_id));

        if (!$test) {
            echo '<div class="notice notice-error"><p>Test not found.</p></div>';
            return;
        }

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT a.*, s.full_name, s.email
            FROM $attempts_table a
            LEFT JOIN $students_table s ON a.student_id = s.id
            WHERE a.test_id = %d AND a.status = 'completed'
            ORDER BY a.percentage DESC",
            $test_id
        ));

        // Calculate statistics
        $total_attempts = count($results);
        $avg_score = 0;
        $avg_percentage = 0;
        $passed = 0;

        if ($total_attempts > 0) {
            $total_score = 0;
            $total_percentage = 0;
            foreach ($results as $result) {
                $total_score += $result->score;
                $total_percentage += $result->percentage;
                if ($result->score >= $test->passing_marks) {
                    $passed++;
                }
            }
            $avg_score = $total_score / $total_attempts;
            $avg_percentage = $total_percentage / $total_attempts;
        }

        ?>
        <div class="form-section">
            <h2>
                <span class="dashicons dashicons-chart-bar"></span>
                Results: <?php echo esc_html($test->title); ?>
            </h2>
        </div>

        <div class="stats-row">
            <div class="stat-box">
                <h4><?php echo $total_attempts; ?></h4>
                <p>Total Attempts</p>
            </div>
            <div class="stat-box">
                <h4><?php echo number_format($avg_score, 1); ?></h4>
                <p>Average Score</p>
            </div>
            <div class="stat-box">
                <h4><?php echo number_format($avg_percentage, 1); ?>%</h4>
                <p>Average Percentage</p>
            </div>
            <div class="stat-box">
                <h4><?php echo $passed; ?>/<?php echo $total_attempts; ?></h4>
                <p>Pass Rate</p>
            </div>
        </div>

        <div class="form-section">
            <?php if (empty($results)): ?>
            <div class="empty-state">
                <span class="dashicons dashicons-chart-bar"></span>
                <h3>No Results Yet</h3>
                <p>No students have completed this test yet.</p>
            </div>
            <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Student</th>
                        <th>Attempted</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Time Taken</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($results as $result): ?>
                    <tr>
                        <td><strong>#<?php echo $rank++; ?></strong></td>
                        <td>
                            <strong><?php echo esc_html($result->full_name); ?></strong><br>
                            <small><?php echo esc_html($result->email); ?></small>
                        </td>
                        <td><?php echo intval($result->attempted); ?>/<?php echo intval($result->total_questions); ?></td>
                        <td style="color: #10b981; font-weight: 600;"><?php echo intval($result->correct); ?></td>
                        <td style="color: #ef4444; font-weight: 600;"><?php echo intval($result->wrong); ?></td>
                        <td><strong><?php echo number_format($result->score, 2); ?></strong></td>
                        <td>
                            <strong><?php echo number_format($result->percentage, 1); ?>%</strong>
                        </td>
                        <td><?php echo intval($result->time_taken); ?> mins</td>
                        <td><?php echo date('M d, Y h:i A', strtotime($result->completed_at)); ?></td>
                        <td>
                            <?php if ($result->score >= $test->passing_marks): ?>
                            <span class="status-badge status-active">Passed</span>
                            <?php else: ?>
                            <span class="status-badge status-inactive">Failed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <p>
            <a href="<?php echo admin_url('admin.php?page=ck-test-generator'); ?>" class="button button-secondary button-large">
                Back to Tests
            </a>
        </p>
        <?php
    }

    /**
     * Save mock test
     */
    public static function save_mock_test() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_save_test_nonce', 'test_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_mock_tests';

        $test_id = intval($_POST['test_id']);

        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
            'exam_type' => sanitize_text_field($_POST['exam_type']),
            'duration' => intval($_POST['duration']),
            'total_questions' => intval($_POST['total_questions']),
            'total_marks' => intval($_POST['total_marks']),
            'passing_marks' => intval($_POST['passing_marks'] ?? 0),
            'negative_marking' => isset($_POST['negative_marking']) ? 1 : 0,
            'shuffle_questions' => isset($_POST['shuffle_questions']) ? 1 : 0,
            'show_result_immediately' => isset($_POST['show_result_immediately']) ? 1 : 0,
            'instructions' => sanitize_textarea_field($_POST['instructions'] ?? ''),
            'status' => sanitize_text_field($_POST['status'] ?? 'draft'),
        );

        if ($test_id) {
            $wpdb->update($table, $data, array('id' => $test_id));
        } else {
            $wpdb->insert($table, $data);
            $test_id = $wpdb->insert_id;
        }

        wp_redirect(admin_url('admin.php?page=ck-test-generator&saved=1'));
        exit;
    }

    /**
     * Save questions
     */
    public static function save_questions() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_save_questions_nonce', 'questions_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_questions';

        $test_id = intval($_POST['test_id']);
        $questions = $_POST['questions'] ?? array();

        foreach ($questions as $number => $question) {
            if (empty($question['text'])) continue;

            $data = array(
                'test_id' => $test_id,
                'question_number' => intval($number),
                'question_text' => sanitize_textarea_field($question['text']),
                'option_a' => sanitize_text_field($question['option_a']),
                'option_b' => sanitize_text_field($question['option_b']),
                'option_c' => sanitize_text_field($question['option_c']),
                'option_d' => sanitize_text_field($question['option_d']),
                'correct_answer' => sanitize_text_field($question['correct']),
                'marks' => intval($question['marks'] ?? 1),
                'negative_marks' => floatval($question['negative'] ?? 0),
                'explanation' => sanitize_textarea_field($question['explanation'] ?? ''),
            );

            // Check if question exists
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table WHERE test_id = %d AND question_number = %d",
                $test_id,
                $number
            ));

            if ($existing) {
                $wpdb->update($table, $data, array('id' => $existing));
            } else {
                $wpdb->insert($table, $data);
            }
        }

        wp_redirect(admin_url('admin.php?page=ck-test-generator&action=questions&test_id=' . $test_id . '&questions_saved=1'));
        exit;
    }

    /**
     * Assign test
     */
    public static function assign_test() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_assign_test_nonce', 'assign_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_test_assignments';

        $test_id = intval($_POST['test_id']);
        $students = $_POST['students'] ?? array();
        $max_attempts = intval($_POST['max_attempts'] ?? 1);
        $due_date = !empty($_POST['due_date']) ? sanitize_text_field($_POST['due_date']) : null;

        foreach ($students as $student_id) {
            $data = array(
                'test_id' => $test_id,
                'student_id' => intval($student_id),
                'max_attempts' => $max_attempts,
                'due_date' => $due_date,
            );

            // Check if already assigned
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table WHERE test_id = %d AND student_id = %d",
                $test_id,
                $student_id
            ));

            if ($existing) {
                $wpdb->update($table, $data, array('id' => $existing));
            } else {
                $wpdb->insert($table, $data);
            }
        }

        wp_redirect(admin_url('admin.php?page=ck-test-generator&assigned=1'));
        exit;
    }

    /**
     * Delete mock test
     */
    public static function delete_mock_test() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_delete_test_nonce', 'delete_nonce');

        global $wpdb;

        $test_id = intval($_GET['test_id']);

        // Delete test
        $wpdb->delete($wpdb->prefix . 'ck_oneform_mock_tests', array('id' => $test_id));

        // Delete questions
        $wpdb->delete($wpdb->prefix . 'ck_oneform_questions', array('test_id' => $test_id));

        // Delete assignments
        $wpdb->delete($wpdb->prefix . 'ck_oneform_test_assignments', array('test_id' => $test_id));

        // Delete attempts
        $wpdb->delete($wpdb->prefix . 'ck_oneform_test_attempts', array('test_id' => $test_id));

        wp_redirect(admin_url('admin.php?page=ck-test-generator&deleted=1'));
        exit;
    }

    /**
     * AJAX: Start test
     */
    public static function ajax_start_test() {
        check_ajax_referer('ck-test-taking', 'nonce');

        if (!CK_OneForm_Student_Auth::is_student_logged_in()) {
            wp_send_json_error(array('message' => 'Please login first'));
        }

        global $wpdb;
        $student = CK_OneForm_Student_Auth::get_current_student();
        $test_id = intval($_POST['test_id']);

        // Check if test is assigned
        $assignment = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_test_assignments
            WHERE test_id = %d AND student_id = %d",
            $test_id,
            $student->id
        ));

        if (!$assignment) {
            wp_send_json_error(array('message' => 'This test is not assigned to you'));
        }

        // Check attempts
        if ($assignment->attempts_used >= $assignment->max_attempts) {
            wp_send_json_error(array('message' => 'You have exhausted all attempts for this test'));
        }

        // Get test info
        $test = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_mock_tests WHERE id = %d",
            $test_id
        ));

        // Get questions
        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_questions WHERE test_id = %d ORDER BY question_number ASC",
            $test_id
        ));

        if ($test->shuffle_questions) {
            shuffle($questions);
        }

        // Create attempt
        $wpdb->insert($wpdb->prefix . 'ck_oneform_test_attempts', array(
            'student_id' => $student->id,
            'test_id' => $test_id,
            'total_questions' => count($questions),
            'status' => 'in_progress',
        ));

        $attempt_id = $wpdb->insert_id;

        // Update attempts used
        $wpdb->update(
            $wpdb->prefix . 'ck_oneform_test_assignments',
            array('attempts_used' => $assignment->attempts_used + 1),
            array('id' => $assignment->id)
        );

        wp_send_json_success(array(
            'attempt_id' => $attempt_id,
            'test' => $test,
            'questions' => $questions,
            'duration' => $test->duration,
        ));
    }

    /**
     * AJAX: Submit test
     */
    public static function ajax_submit_test() {
        check_ajax_referer('ck-test-taking', 'nonce');

        global $wpdb;
        $student = CK_OneForm_Student_Auth::get_current_student();

        $attempt_id = intval($_POST['attempt_id']);
        $answers = $_POST['answers'] ?? array();
        $time_taken = intval($_POST['time_taken']);

        // Get attempt
        $attempt = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_test_attempts WHERE id = %d AND student_id = %d",
            $attempt_id,
            $student->id
        ));

        if (!$attempt) {
            wp_send_json_error(array('message' => 'Invalid attempt'));
        }

        // Get test and questions
        $test = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_mock_tests WHERE id = %d",
            $attempt->test_id
        ));

        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_questions WHERE test_id = %d",
            $attempt->test_id
        ));

        // Calculate score
        $correct = 0;
        $wrong = 0;
        $attempted = 0;
        $score = 0;

        foreach ($questions as $question) {
            if (isset($answers[$question->id])) {
                $attempted++;
                if ($answers[$question->id] === $question->correct_answer) {
                    $correct++;
                    $score += $question->marks;
                } else {
                    $wrong++;
                    if ($test->negative_marking) {
                        $score -= $question->negative_marks;
                    }
                }
            }
        }

        $percentage = ($score / $test->total_marks) * 100;

        // Update attempt
        $wpdb->update(
            $wpdb->prefix . 'ck_oneform_test_attempts',
            array(
                'completed_at' => current_time('mysql'),
                'time_taken' => $time_taken,
                'attempted' => $attempted,
                'correct' => $correct,
                'wrong' => $wrong,
                'score' => $score,
                'percentage' => $percentage,
                'status' => 'completed',
                'answers' => json_encode($answers),
            ),
            array('id' => $attempt_id)
        );

        // Also update student_tests table for dashboard
        $student_tests_table = $wpdb->prefix . 'ck_oneform_student_tests';
        $wpdb->insert($student_tests_table, array(
            'student_id' => $student->id,
            'test_id' => $attempt->test_id,
            'score' => $score,
            'total_marks' => $test->total_marks,
            'completed_at' => current_time('mysql'),
        ));

        wp_send_json_success(array(
            'score' => $score,
            'total_marks' => $test->total_marks,
            'percentage' => $percentage,
            'correct' => $correct,
            'wrong' => $wrong,
            'attempted' => $attempted,
            'total_questions' => count($questions),
            'time_taken' => $time_taken,
            'passed' => $score >= $test->passing_marks,
        ));
    }
}

// Initialize
CK_OneForm_Mock_Test_Generator::init();
