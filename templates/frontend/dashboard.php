<?php
/**
 * User Dashboard Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();
$user = wp_get_current_user();
$stats = CK_OneForm_User_Dashboard::get_user_stats($user_id);
$applications = CK_OneForm_User_Dashboard::get_user_applications($user_id);
?>

<div class="ck-oneform-dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h2><?php printf(__('Welcome, %s!', 'ck-oneform'), $user->display_name); ?></h2>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-primary">
                <?php _e('New Application', 'ck-oneform'); ?>
            </a>
        </div>

        <!-- Statistics -->
        <div class="dashboard-stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="dashicons dashicons-format-aside"></i></div>
                        <div class="stat-content">
                            <h3><?php echo $stats['total']; ?></h3>
                            <p><?php _e('Total Applications', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card pending">
                        <div class="stat-icon"><i class="dashicons dashicons-clock"></i></div>
                        <div class="stat-content">
                            <h3><?php echo $stats['pending']; ?></h3>
                            <p><?php _e('Pending', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card approved">
                        <div class="stat-icon"><i class="dashicons dashicons-yes"></i></div>
                        <div class="stat-content">
                            <h3><?php echo $stats['approved']; ?></h3>
                            <p><?php _e('Approved', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card rejected">
                        <div class="stat-icon"><i class="dashicons dashicons-no"></i></div>
                        <div class="stat-content">
                            <h3><?php echo $stats['rejected']; ?></h3>
                            <p><?php _e('Rejected', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="dashboard-applications">
            <h3><?php _e('My Applications', 'ck-oneform'); ?></h3>

            <?php if ($applications) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php _e('Application No.', 'ck-oneform'); ?></th>
                                <th><?php _e('Submission Date', 'ck-oneform'); ?></th>
                                <th><?php _e('Status', 'ck-oneform'); ?></th>
                                <th><?php _e('Actions', 'ck-oneform'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app) : ?>
                                <tr>
                                    <td><?php echo esc_html($app->application_number); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($app->submission_date)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo esc_attr($app->status); ?>">
                                            <?php echo esc_html(ucfirst($app->status)); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary view-application" data-id="<?php echo esc_attr($app->id); ?>">
                                            <?php _e('View', 'ck-oneform'); ?>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary download-pdf" data-id="<?php echo esc_attr($app->id); ?>">
                                            <?php _e('Download PDF', 'ck-oneform'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <p><?php _e('You have not submitted any applications yet.', 'ck-oneform'); ?></p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-primary">
                        <?php _e('Submit Your First Application', 'ck-oneform'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="dashboard-quick-links">
            <h3><?php _e('Quick Links', 'ck-oneform'); ?></h3>
            <div class="row">
                <div class="col-md-4">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="quick-link-card">
                        <i class="dashicons dashicons-edit"></i>
                        <span><?php _e('New Application', 'ck-oneform'); ?></span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-status'))); ?>" class="quick-link-card">
                        <i class="dashicons dashicons-search"></i>
                        <span><?php _e('Check Status', 'ck-oneform'); ?></span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('payment'))); ?>" class="quick-link-card">
                        <i class="dashicons dashicons-money"></i>
                        <span><?php _e('Make Payment', 'ck-oneform'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
