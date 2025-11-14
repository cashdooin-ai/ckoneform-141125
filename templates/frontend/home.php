<?php
/**
 * OneForm Home Page Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ck-oneform-home">
    <div class="ck-oneform-hero">
        <div class="ck-oneform-hero-content">
            <h1><?php _e('Welcome to CollegeKampus OneForm', 'ck-oneform'); ?></h1>
            <p class="lead"><?php _e('Your single application for multiple colleges and courses', 'ck-oneform'); ?></p>

            <?php if (!is_user_logged_in()) : ?>
                <div class="ck-oneform-cta">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('student-registration'))); ?>" class="btn btn-primary btn-lg">
                        <?php _e('Register Now', 'ck-oneform'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="btn btn-outline-primary btn-lg">
                        <?php _e('Login', 'ck-oneform'); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="ck-oneform-cta">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-primary btn-lg">
                        <?php _e('Apply Now', 'ck-oneform'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('my-applications'))); ?>" class="btn btn-outline-primary btn-lg">
                        <?php _e('My Dashboard', 'ck-oneform'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="ck-oneform-features">
        <div class="container">
            <h2 class="text-center"><?php _e('Why Choose OneForm?', 'ck-oneform'); ?></h2>

            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="dashicons dashicons-yes"></i>
                        <h3><?php _e('Single Application', 'ck-oneform'); ?></h3>
                        <p><?php _e('Fill one form and apply to multiple colleges and courses', 'ck-oneform'); ?></p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="dashicons dashicons-clock"></i>
                        <h3><?php _e('Save Time', 'ck-oneform'); ?></h3>
                        <p><?php _e('No need to fill multiple forms. Complete your application in minutes', 'ck-oneform'); ?></p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="dashicons dashicons-chart-line"></i>
                        <h3><?php _e('Track Status', 'ck-oneform'); ?></h3>
                        <p><?php _e('Monitor your application status in real-time from your dashboard', 'ck-oneform'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ck-oneform-courses-preview">
        <div class="container">
            <h2 class="text-center"><?php _e('Popular Courses', 'ck-oneform'); ?></h2>

            <?php
            $courses = get_posts(array(
                'post_type' => 'ck_course',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if ($courses) :
            ?>
                <div class="row">
                    <?php foreach ($courses as $course) : ?>
                        <div class="col-md-4">
                            <div class="course-card">
                                <?php if (has_post_thumbnail($course->ID)) : ?>
                                    <div class="course-thumbnail">
                                        <?php echo get_the_post_thumbnail($course->ID, 'medium'); ?>
                                    </div>
                                <?php endif; ?>
                                <h3><?php echo esc_html($course->post_title); ?></h3>
                                <p><?php echo esc_html(get_the_excerpt($course)); ?></p>
                                <a href="<?php echo get_permalink($course->ID); ?>" class="btn btn-sm btn-outline-primary">
                                    <?php _e('View Details', 'ck-oneform'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-center"><?php _e('No courses available at the moment.', 'ck-oneform'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="ck-oneform-stats">
        <div class="container">
            <div class="row text-center">
                <?php
                global $wpdb;
                $students = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$wpdb->prefix}ck_oneform_applications");
                $applications = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_applications");
                $colleges = wp_count_posts('ck_college')->publish;
                $courses_count = wp_count_posts('ck_course')->publish;
                ?>

                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="stat-number"><?php echo number_format($students); ?></h2>
                        <p><?php _e('Students Registered', 'ck-oneform'); ?></p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="stat-number"><?php echo number_format($applications); ?></h2>
                        <p><?php _e('Applications Submitted', 'ck-oneform'); ?></p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="stat-number"><?php echo number_format($colleges); ?></h2>
                        <p><?php _e('Partner Colleges', 'ck-oneform'); ?></p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="stat-number"><?php echo number_format($courses_count); ?></h2>
                        <p><?php _e('Courses Available', 'ck-oneform'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
