<?php
/**
 * Student Portal Admin Manager
 * Manage Services, Mock Tests, Offers, and Students
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Student_Manager {

    /**
     * Initialize admin hooks
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_post_ck_save_service', array(__CLASS__, 'save_service'));
        add_action('admin_post_ck_delete_service', array(__CLASS__, 'delete_service'));
        add_action('admin_post_ck_save_mock_test', array(__CLASS__, 'save_mock_test'));
        add_action('admin_post_ck_delete_mock_test', array(__CLASS__, 'delete_mock_test'));
        add_action('admin_post_ck_save_offer', array(__CLASS__, 'save_offer'));
        add_action('admin_post_ck_delete_offer', array(__CLASS__, 'delete_offer'));
        add_action('admin_post_ck_assign_offer_to_student', array(__CLASS__, 'assign_offer_to_student'));
        add_action('admin_post_ck_fix_database_tables', array(__CLASS__, 'fix_database_tables'));
        add_action('admin_notices', array(__CLASS__, 'admin_notices'));
    }

    /**
     * Add admin menu pages
     */
    public static function add_admin_menu() {
        add_menu_page(
            'Student Portal',
            'Student Portal',
            'manage_options',
            'ck-student-portal',
            array(__CLASS__, 'students_page'),
            'dashicons-groups',
            30
        );

        add_submenu_page(
            'ck-student-portal',
            'Students',
            'Students',
            'manage_options',
            'ck-student-portal',
            array(__CLASS__, 'students_page')
        );

        add_submenu_page(
            'ck-student-portal',
            'Services',
            'Services',
            'manage_options',
            'ck-services',
            array(__CLASS__, 'services_page')
        );

        add_submenu_page(
            'ck-student-portal',
            'Mock Tests',
            'Mock Tests',
            'manage_options',
            'ck-mock-tests',
            array(__CLASS__, 'mock_tests_page')
        );

        add_submenu_page(
            'ck-student-portal',
            'Offers',
            'Offers',
            'manage_options',
            'ck-offers',
            array(__CLASS__, 'offers_page')
        );
    }

    /**
     * Students list page
     */
    public static function students_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_students';

        // Get students
        $students = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC LIMIT 100");

        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");

        ?>
        <div class="wrap">
            <h1>Student Portal - All Students</h1>

            <?php if (!$table_exists): ?>
                <div class="notice notice-error">
                    <p><strong>⚠ Database Error:</strong> Student tables are missing!</p>
                    <p>
                        <a href="<?php echo admin_url('admin-post.php?action=ck_fix_database_tables'); ?>"
                           class="button button-primary button-large">
                            🔧 Fix Database Tables Now
                        </a>
                    </p>
                </div>
            <?php else: ?>
                <p>
                    <a href="<?php echo admin_url('admin-post.php?action=ck_fix_database_tables'); ?>"
                       class="button"
                       onclick="return confirm('This will recreate all database tables. Continue?');">
                        🔧 Recreate Database Tables
                    </a>
                </p>
            <?php endif; ?>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo esc_html($student->id); ?></td>
                                <td><strong><?php echo esc_html($student->student_id); ?></strong></td>
                                <td><?php echo esc_html($student->full_name); ?></td>
                                <td><?php echo esc_html($student->email); ?></td>
                                <td><?php echo esc_html($student->mobile); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo esc_attr($student->status); ?>">
                                        <?php echo esc_html(ucfirst($student->status)); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html(date('M d, Y', strtotime($student->created_at))); ?></td>
                                <td><?php echo $student->last_login ? esc_html(date('M d, Y', strtotime($student->last_login))) : 'Never'; ?></td>
                                <td>
                                    <a href="?page=ck-student-portal&action=view&id=<?php echo $student->id; ?>" class="button button-small">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <style>
            .status-badge {
                padding: 4px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: 600;
            }
            .status-active {
                background: #d1fae5;
                color: #065f46;
            }
            .status-inactive {
                background: #fee2e2;
                color: #991b1b;
            }
        </style>
        <?php
    }

    /**
     * Services management page
     */
    public static function services_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_services';

        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($action === 'edit' && $service_id) {
            $service = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $service_id));
        }

        ?>
        <div class="wrap">
            <h1>Manage Services</h1>

            <?php if ($action === 'list'): ?>
                <a href="?page=ck-services&action=add" class="button button-primary" style="margin-bottom: 20px;">Add New Service</a>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $services = $wpdb->get_results("SELECT * FROM $table ORDER BY display_order ASC");
                        if ($services):
                            foreach ($services as $service):
                        ?>
                            <tr>
                                <td><?php echo $service->id; ?></td>
                                <td><strong><?php echo esc_html($service->title); ?></strong></td>
                                <td><?php echo esc_html($service->service_type); ?></td>
                                <td>₹<?php echo number_format($service->price, 2); ?></td>
                                <td><?php echo esc_html(ucfirst($service->status)); ?></td>
                                <td><?php echo $service->display_order; ?></td>
                                <td>
                                    <a href="?page=ck-services&action=edit&id=<?php echo $service->id; ?>" class="button button-small">Edit</a>
                                    <a href="<?php echo admin_url('admin-post.php?action=ck_delete_service&id=' . $service->id); ?>"
                                       onclick="return confirm('Are you sure?')" class="button button-small">Delete</a>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="7">No services found. <a href="?page=ck-services&action=add">Add your first service</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <a href="?page=ck-services" class="button" style="margin-bottom: 20px;">← Back to Services</a>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="max-width: 800px;">
                    <input type="hidden" name="action" value="ck_save_service">
                    <?php if ($service_id): ?>
                        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                    <?php endif; ?>
                    <?php wp_nonce_field('ck_save_service'); ?>

                    <table class="form-table">
                        <tr>
                            <th><label for="title">Service Title *</label></th>
                            <td>
                                <input type="text" id="title" name="title" class="regular-text"
                                       value="<?php echo isset($service) ? esc_attr($service->title) : ''; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="description">Description</label></th>
                            <td>
                                <textarea id="description" name="description" rows="5" class="large-text"><?php echo isset($service) ? esc_textarea($service->description) : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="service_type">Service Type</label></th>
                            <td>
                                <select id="service_type" name="service_type" class="regular-text">
                                    <option value="counseling" <?php echo isset($service) && $service->service_type === 'counseling' ? 'selected' : ''; ?>>Career Counseling</option>
                                    <option value="documentation" <?php echo isset($service) && $service->service_type === 'documentation' ? 'selected' : ''; ?>>Document Verification</option>
                                    <option value="application" <?php echo isset($service) && $service->service_type === 'application' ? 'selected' : ''; ?>>Application Assistance</option>
                                    <option value="scholarship" <?php echo isset($service) && $service->service_type === 'scholarship' ? 'selected' : ''; ?>>Scholarship Guidance</option>
                                    <option value="other" <?php echo isset($service) && $service->service_type === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="price">Price (₹)</label></th>
                            <td>
                                <input type="number" id="price" name="price" class="regular-text" step="0.01"
                                       value="<?php echo isset($service) ? esc_attr($service->price) : '0'; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="features">Features (one per line)</label></th>
                            <td>
                                <textarea id="features" name="features" rows="5" class="large-text"><?php echo isset($service) ? esc_textarea($service->features) : ''; ?></textarea>
                                <p class="description">Enter each feature on a new line</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="icon">Icon (emoji or class)</label></th>
                            <td>
                                <input type="text" id="icon" name="icon" class="regular-text"
                                       value="<?php echo isset($service) ? esc_attr($service->icon) : '📋'; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="status">Status</label></th>
                            <td>
                                <select id="status" name="status">
                                    <option value="active" <?php echo isset($service) && $service->status === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo isset($service) && $service->status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="display_order">Display Order</label></th>
                            <td>
                                <input type="number" id="display_order" name="display_order" class="small-text"
                                       value="<?php echo isset($service) ? esc_attr($service->display_order) : '0'; ?>">
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php echo $service_id ? 'Update Service' : 'Add Service'; ?>
                        </button>
                    </p>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Mock tests management page
     */
    public static function mock_tests_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_mock_tests';

        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($action === 'edit' && $test_id) {
            $test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $test_id));
        }

        ?>
        <div class="wrap">
            <h1>Manage Mock Tests</h1>

            <?php if ($action === 'list'): ?>
                <a href="?page=ck-mock-tests&action=add" class="button button-primary" style="margin-bottom: 20px;">Add New Mock Test</a>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Title</th>
                            <th>Exam Type</th>
                            <th>Duration</th>
                            <th>Questions</th>
                            <th>Marks</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tests = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");
                        if ($tests):
                            foreach ($tests as $test):
                        ?>
                            <tr>
                                <td><?php echo $test->id; ?></td>
                                <td><strong><?php echo esc_html($test->title); ?></strong></td>
                                <td><?php echo esc_html($test->exam_type); ?></td>
                                <td><?php echo $test->duration; ?> min</td>
                                <td><?php echo $test->total_questions; ?></td>
                                <td><?php echo $test->total_marks; ?></td>
                                <td>₹<?php echo number_format($test->price, 2); ?></td>
                                <td><?php echo esc_html(ucfirst($test->status)); ?></td>
                                <td>
                                    <a href="?page=ck-mock-tests&action=edit&id=<?php echo $test->id; ?>" class="button button-small">Edit</a>
                                    <a href="<?php echo admin_url('admin-post.php?action=ck_delete_mock_test&id=' . $test->id); ?>"
                                       onclick="return confirm('Are you sure?')" class="button button-small">Delete</a>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="9">No mock tests found. <a href="?page=ck-mock-tests&action=add">Add your first mock test</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <a href="?page=ck-mock-tests" class="button" style="margin-bottom: 20px;">← Back to Mock Tests</a>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="max-width: 800px;">
                    <input type="hidden" name="action" value="ck_save_mock_test">
                    <?php if ($test_id): ?>
                        <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                    <?php endif; ?>
                    <?php wp_nonce_field('ck_save_mock_test'); ?>

                    <table class="form-table">
                        <tr>
                            <th><label for="title">Test Title *</label></th>
                            <td>
                                <input type="text" id="title" name="title" class="regular-text"
                                       value="<?php echo isset($test) ? esc_attr($test->title) : ''; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="description">Description</label></th>
                            <td>
                                <textarea id="description" name="description" rows="5" class="large-text"><?php echo isset($test) ? esc_textarea($test->description) : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="exam_type">Exam Type</label></th>
                            <td>
                                <select id="exam_type" name="exam_type" class="regular-text">
                                    <option value="JEE Main" <?php echo isset($test) && $test->exam_type === 'JEE Main' ? 'selected' : ''; ?>>JEE Main</option>
                                    <option value="JEE Advanced" <?php echo isset($test) && $test->exam_type === 'JEE Advanced' ? 'selected' : ''; ?>>JEE Advanced</option>
                                    <option value="NEET" <?php echo isset($test) && $test->exam_type === 'NEET' ? 'selected' : ''; ?>>NEET</option>
                                    <option value="BITSAT" <?php echo isset($test) && $test->exam_type === 'BITSAT' ? 'selected' : ''; ?>>BITSAT</option>
                                    <option value="GATE" <?php echo isset($test) && $test->exam_type === 'GATE' ? 'selected' : ''; ?>>GATE</option>
                                    <option value="Other" <?php echo isset($test) && $test->exam_type === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="duration">Duration (minutes)</label></th>
                            <td>
                                <input type="number" id="duration" name="duration" class="regular-text"
                                       value="<?php echo isset($test) ? esc_attr($test->duration) : '180'; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="total_questions">Total Questions</label></th>
                            <td>
                                <input type="number" id="total_questions" name="total_questions" class="regular-text"
                                       value="<?php echo isset($test) ? esc_attr($test->total_questions) : '100'; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="total_marks">Total Marks</label></th>
                            <td>
                                <input type="number" id="total_marks" name="total_marks" class="regular-text"
                                       value="<?php echo isset($test) ? esc_attr($test->total_marks) : '300'; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="price">Price (₹)</label></th>
                            <td>
                                <input type="number" id="price" name="price" class="regular-text" step="0.01"
                                       value="<?php echo isset($test) ? esc_attr($test->price) : '0'; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="thumbnail">Thumbnail URL</label></th>
                            <td>
                                <input type="url" id="thumbnail" name="thumbnail" class="regular-text"
                                       value="<?php echo isset($test) ? esc_attr($test->thumbnail) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="status">Status</label></th>
                            <td>
                                <select id="status" name="status">
                                    <option value="active" <?php echo isset($test) && $test->status === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo isset($test) && $test->status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php echo $test_id ? 'Update Mock Test' : 'Add Mock Test'; ?>
                        </button>
                    </p>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Offers management page
     */
    public static function offers_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_offers';

        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $offer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($action === 'edit' && $offer_id) {
            $offer = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $offer_id));
        }

        if ($action === 'assign') {
            self::assign_offer_form($offer_id);
            return;
        }

        ?>
        <div class="wrap">
            <h1>Manage Offers</h1>

            <?php if ($action === 'list'): ?>
                <a href="?page=ck-offers&action=add" class="button button-primary" style="margin-bottom: 20px;">Add New Offer</a>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $offers = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");
                        if ($offers):
                            foreach ($offers as $offer):
                        ?>
                            <tr>
                                <td><?php echo $offer->id; ?></td>
                                <td><strong><?php echo esc_html($offer->title); ?></strong></td>
                                <td><?php echo esc_html($offer->offer_type); ?></td>
                                <td>
                                    <?php
                                    echo $offer->discount_type === 'percentage'
                                        ? $offer->discount_value . '%'
                                        : '₹' . number_format($offer->discount_value, 2);
                                    ?>
                                </td>
                                <td><?php echo $offer->valid_from ? date('M d, Y', strtotime($offer->valid_from)) : '-'; ?></td>
                                <td><?php echo $offer->valid_until ? date('M d, Y', strtotime($offer->valid_until)) : '-'; ?></td>
                                <td><?php echo esc_html(ucfirst($offer->status)); ?></td>
                                <td>
                                    <a href="?page=ck-offers&action=edit&id=<?php echo $offer->id; ?>" class="button button-small">Edit</a>
                                    <a href="?page=ck-offers&action=assign&id=<?php echo $offer->id; ?>" class="button button-small button-primary">Assign to Students</a>
                                    <a href="<?php echo admin_url('admin-post.php?action=ck_delete_offer&id=' . $offer->id); ?>"
                                       onclick="return confirm('Are you sure?')" class="button button-small">Delete</a>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="8">No offers found. <a href="?page=ck-offers&action=add">Add your first offer</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <a href="?page=ck-offers" class="button" style="margin-bottom: 20px;">← Back to Offers</a>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="max-width: 800px;">
                    <input type="hidden" name="action" value="ck_save_offer">
                    <?php if ($offer_id): ?>
                        <input type="hidden" name="offer_id" value="<?php echo $offer_id; ?>">
                    <?php endif; ?>
                    <?php wp_nonce_field('ck_save_offer'); ?>

                    <table class="form-table">
                        <tr>
                            <th><label for="title">Offer Title *</label></th>
                            <td>
                                <input type="text" id="title" name="title" class="regular-text"
                                       value="<?php echo isset($offer) ? esc_attr($offer->title) : ''; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="description">Description</label></th>
                            <td>
                                <textarea id="description" name="description" rows="5" class="large-text"><?php echo isset($offer) ? esc_textarea($offer->description) : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="offer_type">Offer Type</label></th>
                            <td>
                                <select id="offer_type" name="offer_type" class="regular-text">
                                    <option value="application" <?php echo isset($offer) && $offer->offer_type === 'application' ? 'selected' : ''; ?>>Application Fee Discount</option>
                                    <option value="service" <?php echo isset($offer) && $offer->offer_type === 'service' ? 'selected' : ''; ?>>Service Discount</option>
                                    <option value="test" <?php echo isset($offer) && $offer->offer_type === 'test' ? 'selected' : ''; ?>>Mock Test Discount</option>
                                    <option value="general" <?php echo isset($offer) && $offer->offer_type === 'general' ? 'selected' : ''; ?>>General Discount</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="discount_type">Discount Type</label></th>
                            <td>
                                <select id="discount_type" name="discount_type">
                                    <option value="percentage" <?php echo isset($offer) && $offer->discount_type === 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                                    <option value="fixed" <?php echo isset($offer) && $offer->discount_type === 'fixed' ? 'selected' : ''; ?>>Fixed Amount</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="discount_value">Discount Value *</label></th>
                            <td>
                                <input type="number" id="discount_value" name="discount_value" class="regular-text" step="0.01"
                                       value="<?php echo isset($offer) ? esc_attr($offer->discount_value) : '0'; ?>" required>
                                <p class="description">Enter percentage (e.g., 10 for 10%) or fixed amount (e.g., 500 for ₹500)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="valid_from">Valid From</label></th>
                            <td>
                                <input type="datetime-local" id="valid_from" name="valid_from" class="regular-text"
                                       value="<?php echo isset($offer) && $offer->valid_from ? date('Y-m-d\TH:i', strtotime($offer->valid_from)) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="valid_until">Valid Until</label></th>
                            <td>
                                <input type="datetime-local" id="valid_until" name="valid_until" class="regular-text"
                                       value="<?php echo isset($offer) && $offer->valid_until ? date('Y-m-d\TH:i', strtotime($offer->valid_until)) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="terms">Terms & Conditions</label></th>
                            <td>
                                <textarea id="terms" name="terms" rows="5" class="large-text"><?php echo isset($offer) ? esc_textarea($offer->terms) : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="banner_image">Banner Image URL</label></th>
                            <td>
                                <input type="url" id="banner_image" name="banner_image" class="regular-text"
                                       value="<?php echo isset($offer) ? esc_attr($offer->banner_image) : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="status">Status</label></th>
                            <td>
                                <select id="status" name="status">
                                    <option value="active" <?php echo isset($offer) && $offer->status === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo isset($offer) && $offer->status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php echo $offer_id ? 'Update Offer' : 'Add Offer'; ?>
                        </button>
                    </p>
                </form>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Assign offer to students form
     */
    private static function assign_offer_form($offer_id) {
        global $wpdb;

        $offer = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ck_oneform_offers WHERE id = %d",
            $offer_id
        ));

        if (!$offer) {
            echo '<div class="wrap"><h1>Offer not found</h1></div>';
            return;
        }

        $students = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ck_oneform_students WHERE status = 'active' ORDER BY full_name ASC");

        ?>
        <div class="wrap">
            <h1>Assign Offer: <?php echo esc_html($offer->title); ?></h1>
            <a href="?page=ck-offers" class="button" style="margin-bottom: 20px;">← Back to Offers</a>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ck_assign_offer_to_student">
                <input type="hidden" name="offer_id" value="<?php echo $offer_id; ?>">
                <?php wp_nonce_field('ck_assign_offer'); ?>

                <table class="form-table">
                    <tr>
                        <th>Select Students</th>
                        <td>
                            <p><label><input type="checkbox" id="select-all"> Select All</label></p>
                            <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                <?php foreach ($students as $student): ?>
                                    <label style="display: block; padding: 5px;">
                                        <input type="checkbox" name="student_ids[]" value="<?php echo $student->id; ?>" class="student-checkbox">
                                        <?php echo esc_html($student->full_name); ?> (<?php echo esc_html($student->email); ?>) - <?php echo esc_html($student->student_id); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">Assign Offer to Selected Students</button>
                </p>
            </form>

            <script>
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            </script>
        </div>
        <?php
    }

    /**
     * Save service handler
     */
    public static function save_service() {
        check_admin_referer('ck_save_service');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_services';

        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'description' => sanitize_textarea_field($_POST['description']),
            'service_type' => sanitize_text_field($_POST['service_type']),
            'price' => floatval($_POST['price']),
            'features' => sanitize_textarea_field($_POST['features']),
            'icon' => sanitize_text_field($_POST['icon']),
            'status' => sanitize_text_field($_POST['status']),
            'display_order' => intval($_POST['display_order']),
        );

        if (isset($_POST['service_id']) && $_POST['service_id']) {
            $wpdb->update($table, $data, array('id' => intval($_POST['service_id'])));
        } else {
            $wpdb->insert($table, $data);
        }

        wp_redirect(admin_url('admin.php?page=ck-services'));
        exit;
    }

    /**
     * Delete service handler
     */
    public static function delete_service() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'ck_oneform_services', array('id' => intval($_GET['id'])));

        wp_redirect(admin_url('admin.php?page=ck-services'));
        exit;
    }

    /**
     * Save mock test handler
     */
    public static function save_mock_test() {
        check_admin_referer('ck_save_mock_test');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_mock_tests';

        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'description' => sanitize_textarea_field($_POST['description']),
            'exam_type' => sanitize_text_field($_POST['exam_type']),
            'duration' => intval($_POST['duration']),
            'total_questions' => intval($_POST['total_questions']),
            'total_marks' => intval($_POST['total_marks']),
            'price' => floatval($_POST['price']),
            'thumbnail' => esc_url_raw($_POST['thumbnail']),
            'status' => sanitize_text_field($_POST['status']),
        );

        if (isset($_POST['test_id']) && $_POST['test_id']) {
            $wpdb->update($table, $data, array('id' => intval($_POST['test_id'])));
        } else {
            $wpdb->insert($table, $data);
        }

        wp_redirect(admin_url('admin.php?page=ck-mock-tests'));
        exit;
    }

    /**
     * Delete mock test handler
     */
    public static function delete_mock_test() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'ck_oneform_mock_tests', array('id' => intval($_GET['id'])));

        wp_redirect(admin_url('admin.php?page=ck-mock-tests'));
        exit;
    }

    /**
     * Save offer handler
     */
    public static function save_offer() {
        check_admin_referer('ck_save_offer');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_offers';

        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'description' => sanitize_textarea_field($_POST['description']),
            'offer_type' => sanitize_text_field($_POST['offer_type']),
            'discount_value' => floatval($_POST['discount_value']),
            'discount_type' => sanitize_text_field($_POST['discount_type']),
            'valid_from' => !empty($_POST['valid_from']) ? date('Y-m-d H:i:s', strtotime($_POST['valid_from'])) : null,
            'valid_until' => !empty($_POST['valid_until']) ? date('Y-m-d H:i:s', strtotime($_POST['valid_until'])) : null,
            'terms' => sanitize_textarea_field($_POST['terms']),
            'banner_image' => esc_url_raw($_POST['banner_image']),
            'status' => sanitize_text_field($_POST['status']),
        );

        if (isset($_POST['offer_id']) && $_POST['offer_id']) {
            $wpdb->update($table, $data, array('id' => intval($_POST['offer_id'])));
        } else {
            $wpdb->insert($table, $data);
        }

        wp_redirect(admin_url('admin.php?page=ck-offers'));
        exit;
    }

    /**
     * Delete offer handler
     */
    public static function delete_offer() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'ck_oneform_offers', array('id' => intval($_GET['id'])));

        wp_redirect(admin_url('admin.php?page=ck-offers'));
        exit;
    }

    /**
     * Assign offer to students handler
     */
    public static function assign_offer_to_student() {
        check_admin_referer('ck_assign_offer');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_student_offers';
        $offer_id = intval($_POST['offer_id']);
        $student_ids = isset($_POST['student_ids']) ? array_map('intval', $_POST['student_ids']) : array();

        if (empty($student_ids)) {
            wp_redirect(admin_url('admin.php?page=ck-offers&error=no_students'));
            exit;
        }

        $admin_id = get_current_user_id();

        foreach ($student_ids as $student_id) {
            // Check if already assigned
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE student_id = %d AND offer_id = %d",
                $student_id, $offer_id
            ));

            if (!$exists) {
                $wpdb->insert($table, array(
                    'student_id' => $student_id,
                    'offer_id' => $offer_id,
                    'assigned_by' => $admin_id,
                    'is_used' => 0,
                ));
            }
        }

        wp_redirect(admin_url('admin.php?page=ck-offers&success=assigned'));
        exit;
    }

    /**
     * Fix database tables handler
     */
    public static function fix_database_tables() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        // Force create all tables
        CK_OneForm_Database::force_create_tables();

        wp_redirect(admin_url('admin.php?page=ck-student-portal&db_fixed=1'));
        exit;
    }

    /**
     * Admin notices
     */
    public static function admin_notices() {
        if (isset($_GET['db_fixed']) && $_GET['db_fixed'] == '1') {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p><strong>✓ Database tables have been created successfully!</strong></p>';
            echo '<p>All OneForm database tables are now ready. You can now register students.</p>';
            echo '</div>';
        }

        if (isset($_GET['success']) && $_GET['success'] == 'assigned') {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p><strong>✓ Offers assigned successfully!</strong></p>';
            echo '</div>';
        }

        if (isset($_GET['error']) && $_GET['error'] == 'no_students') {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>✗ Error:</strong> Please select at least one student.</p>';
            echo '</div>';
        }
    }
}

// Initialize
CK_OneForm_Student_Manager::init();
