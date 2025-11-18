<?php
/**
 * AJAX Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Ajax {

    public function __construct() {
        // Public AJAX actions
        add_action('wp_ajax_ck_oneform_submit_application', array($this, 'submit_application'));
        add_action('wp_ajax_ck_oneform_register_student', array($this, 'register_student'));
        add_action('wp_ajax_nopriv_ck_oneform_register_student', array($this, 'register_student'));
        add_action('wp_ajax_ck_oneform_check_status', array($this, 'check_application_status'));
        add_action('wp_ajax_nopriv_ck_oneform_check_status', array($this, 'check_application_status'));
        add_action('wp_ajax_ck_oneform_process_payment', array($this, 'process_payment'));
        add_action('wp_ajax_load_more_colleges', array($this, 'load_more_colleges'));
        add_action('wp_ajax_nopriv_load_more_colleges', array($this, 'load_more_colleges'));

        // Admin AJAX actions
        add_action('wp_ajax_ck_oneform_update_application_status', array($this, 'update_application_status'));
        add_action('wp_ajax_ck_oneform_delete_application', array($this, 'delete_application'));
    }

    /**
     * Submit application via AJAX
     */
    public function submit_application() {
        $result = CK_OneForm_Forms::process_application($_POST);
        wp_send_json($result);
    }

    /**
     * Register student via AJAX
     */
    public function register_student() {
        $result = CK_OneForm_Forms::process_registration($_POST);
        wp_send_json($result);
    }

    /**
     * Check application status via AJAX
     */
    public function check_application_status() {
        if (empty($_POST['application_number'])) {
            wp_send_json(array(
                'success' => false,
                'message' => __('Application number is required', 'ck-oneform')
            ));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_applications';
        $application_number = sanitize_text_field($_POST['application_number']);

        $application = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE application_number = %s",
            $application_number
        ));

        if ($application) {
            wp_send_json(array(
                'success' => true,
                'application' => $application
            ));
        }

        wp_send_json(array(
            'success' => false,
            'message' => __('Application not found', 'ck-oneform')
        ));
    }

    /**
     * Process payment via AJAX
     */
    public function process_payment() {
        if (!is_user_logged_in()) {
            wp_send_json(array('success' => false, 'message' => __('You must be logged in', 'ck-oneform')));
        }

        // Payment processing logic here
        // This would integrate with payment gateways like Razorpay, PayU, etc.

        wp_send_json(array(
            'success' => true,
            'message' => __('Payment processed successfully', 'ck-oneform')
        ));
    }

    /**
     * Update application status (Admin only)
     */
    public function update_application_status() {
        if (!current_user_can('manage_options')) {
            wp_send_json(array('success' => false, 'message' => __('Unauthorized', 'ck-oneform')));
        }

        $application_id = intval($_POST['application_id']);
        $new_status = sanitize_text_field($_POST['status']);

        $result = CK_OneForm_Database::update_application($application_id, array(
            'status' => $new_status
        ));

        if ($result !== false) {
            wp_send_json(array('success' => true, 'message' => __('Status updated', 'ck-oneform')));
        }

        wp_send_json(array('success' => false, 'message' => __('Failed to update status', 'ck-oneform')));
    }

    /**
     * Delete application (Admin only)
     */
    public function delete_application() {
        if (!current_user_can('manage_options')) {
            wp_send_json(array('success' => false, 'message' => __('Unauthorized', 'ck-oneform')));
        }

        global $wpdb;
        $application_id = intval($_POST['application_id']);
        $table = $wpdb->prefix . 'ck_oneform_applications';

        $result = $wpdb->delete($table, array('id' => $application_id));

        if ($result) {
            wp_send_json(array('success' => true, 'message' => __('Application deleted', 'ck-oneform')));
        }

        wp_send_json(array('success' => false, 'message' => __('Failed to delete', 'ck-oneform')));
    }

    /**
     * Load more colleges via AJAX
     */
    public function load_more_colleges() {
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
        $college_type = isset($_POST['college_type']) ? sanitize_text_field($_POST['college_type']) : '';
        $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
        $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $search = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
        $sort_by = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'nirf_rank';

        // Build query args
        $args = array(
            'post_type' => 'ck_college',
            'posts_per_page' => 20,
            'paged' => $paged,
            'post_status' => 'publish',
        );

        // Add search
        if (!empty($search)) {
            $args['s'] = $search;
        }

        // Add taxonomy filters
        $tax_query = array('relation' => 'AND');

        if (!empty($college_type)) {
            $tax_query[] = array(
                'taxonomy' => 'college_type',
                'field' => 'slug',
                'terms' => $college_type,
            );
        }

        if (!empty($state)) {
            $tax_query[] = array(
                'taxonomy' => 'college_state',
                'field' => 'slug',
                'terms' => $state,
            );
        }

        if (!empty($city)) {
            $tax_query[] = array(
                'taxonomy' => 'college_city',
                'field' => 'slug',
                'terms' => $city,
            );
        }

        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }

        // Add meta query for category filter
        if (!empty($category)) {
            $args['meta_query'] = array(
                array(
                    'key' => 'category',
                    'value' => $category,
                    'compare' => '=',
                ),
            );
        }

        // Add sorting
        if ($sort_by === 'nirf_rank') {
            $args['meta_key'] = 'nirf_rank';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        } elseif ($sort_by === 'ck_rank') {
            $args['meta_key'] = 'ck_rank';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        } elseif ($sort_by === 'name') {
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        } elseif ($sort_by === 'established') {
            $args['meta_key'] = 'established';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }

        $colleges_query = new WP_Query($args);

        if (!$colleges_query->have_posts()) {
            wp_send_json_error(array('message' => __('No more colleges found', 'ck-oneform')));
        }

        ob_start();

        while ($colleges_query->have_posts()) : $colleges_query->the_post();
            $college_id = get_the_ID();
            $short_name = get_post_meta($college_id, 'short_name', true);
            $type = get_post_meta($college_id, 'college_type', true);
            $state = get_post_meta($college_id, 'state', true);
            $city = get_post_meta($college_id, 'city', true);
            $nirf_rank = get_post_meta($college_id, 'nirf_rank', true);
            $ck_rank = get_post_meta($college_id, 'ck_rank', true);
            $established = get_post_meta($college_id, 'established', true);
            $accreditation = get_post_meta($college_id, 'accreditation', true);
            $courses = get_post_meta($college_id, 'courses', true);
            $fees_range = get_post_meta($college_id, 'fees_range', true);
            $website = get_post_meta($college_id, 'website', true);
            $ownership = get_post_meta($college_id, 'ownership', true);
            ?>
            <div class="college-card" data-college-id="<?php echo $college_id; ?>">
                <div class="card-header">
                    <div class="college-badge <?php echo strtolower($type); ?>">
                        <?php echo esc_html($type); ?>
                    </div>
                    <?php if ($nirf_rank): ?>
                        <div class="rank-badge">
                            #<?php echo $nirf_rank; ?> NIRF
                        </div>
                    <?php endif; ?>
                    <div class="selection-checkbox" style="display: none;">
                        <input type="checkbox"
                               class="college-select-checkbox"
                               data-college-id="<?php echo $college_id; ?>"
                               data-college-name="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                </div>

                <div class="card-body">
                    <h3 class="college-name"><?php echo get_the_title(); ?></h3>

                    <?php if ($short_name): ?>
                        <p class="college-short-name"><?php echo esc_html($short_name); ?></p>
                    <?php endif; ?>

                    <div class="college-meta">
                        <span class="meta-item">
                            📍 <?php echo esc_html($city . ', ' . $state); ?>
                        </span>

                        <?php if ($established): ?>
                            <span class="meta-item">
                                📅 Est. <?php echo esc_html($established); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($accreditation): ?>
                            <span class="meta-item">
                                ⭐ <?php echo esc_html($accreditation); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($ownership): ?>
                            <span class="meta-item">
                                🏛️ <?php echo esc_html($ownership); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($courses): ?>
                        <div class="college-courses">
                            <strong>Courses:</strong> <?php echo esc_html($courses); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($fees_range): ?>
                        <div class="college-fees">
                            <strong>Annual Fees:</strong> ₹<?php echo esc_html($fees_range); ?>
                        </div>
                    <?php endif; ?>

                    <div class="college-rankings">
                        <?php if ($nirf_rank): ?>
                            <span class="ranking-item">NIRF: #<?php echo $nirf_rank; ?></span>
                        <?php endif; ?>
                        <?php if ($ck_rank): ?>
                            <span class="ranking-item">CK: #<?php echo $ck_rank; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="<?php echo get_permalink($college_id); ?>"
                       class="btn-view-details">
                        📖 View Details
                    </a>
                    <?php if ($website): ?>
                        <a href="<?php echo esc_url($website); ?>"
                           target="_blank"
                           class="btn-website">
                            🌐 Visit Website
                        </a>
                    <?php endif; ?>
                    <button type="button"
                            class="btn-select-college"
                            data-college-id="<?php echo $college_id; ?>"
                            data-college-name="<?php echo esc_attr(get_the_title()); ?>">
                        ➕ Select College
                    </button>
                </div>
            </div>
        <?php endwhile;

        $html = ob_get_clean();
        wp_reset_postdata();

        wp_send_json_success(array('html' => $html));
    }
}

new CK_OneForm_Ajax();
