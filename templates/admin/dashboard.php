<?php
/**
 * Admin Dashboard Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . 'ck_oneform_applications';

// Get statistics
$total_applications = $wpdb->get_var("SELECT COUNT(*) FROM $table");
$pending_applications = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'pending'");
$approved_applications = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'approved'");
$rejected_applications = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'rejected'");

// Get recent applications
$recent_applications = $wpdb->get_results("SELECT * FROM $table ORDER BY submission_date DESC LIMIT 10");
?>

<div class="wrap ck-oneform-admin">
    <h1><?php _e('OneForm Dashboard', 'ck-oneform'); ?></h1>

    <!-- Statistics -->
    <div class="ck-oneform-admin-stats">
        <div class="ck-admin-stat-card">
            <h3><?php echo number_format($total_applications); ?></h3>
            <p><?php _e('Total Applications', 'ck-oneform'); ?></p>
        </div>

        <div class="ck-admin-stat-card pending">
            <h3><?php echo number_format($pending_applications); ?></h3>
            <p><?php _e('Pending Applications', 'ck-oneform'); ?></p>
        </div>

        <div class="ck-admin-stat-card approved">
            <h3><?php echo number_format($approved_applications); ?></h3>
            <p><?php _e('Approved Applications', 'ck-oneform'); ?></p>
        </div>

        <div class="ck-admin-stat-card rejected">
            <h3><?php echo number_format($rejected_applications); ?></h3>
            <p><?php _e('Rejected Applications', 'ck-oneform'); ?></p>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="ck-oneform-table-container">
        <h2><?php _e('Recent Applications', 'ck-oneform'); ?></h2>

        <?php if ($recent_applications) : ?>
            <table class="ck-oneform-table">
                <thead>
                    <tr>
                        <th><?php _e('Application No.', 'ck-oneform'); ?></th>
                        <th><?php _e('User', 'ck-oneform'); ?></th>
                        <th><?php _e('Submission Date', 'ck-oneform'); ?></th>
                        <th><?php _e('Status', 'ck-oneform'); ?></th>
                        <th><?php _e('Actions', 'ck-oneform'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_applications as $app) :
                        $user = get_userdata($app->user_id);
                    ?>
                        <tr>
                            <td><?php echo esc_html($app->application_number); ?></td>
                            <td><?php echo $user ? esc_html($user->display_name) : 'N/A'; ?></td>
                            <td><?php echo date('M j, Y H:i', strtotime($app->submission_date)); ?></td>
                            <td>
                                <span class="status-badge <?php echo esc_attr($app->status); ?>">
                                    <?php echo esc_html(ucfirst($app->status)); ?>
                                </span>
                            </td>
                            <td>
                                <a href="?page=ck-oneform-applications&action=view&id=<?php echo $app->id; ?>" class="ck-btn ck-btn-primary ck-btn-sm">
                                    <?php _e('View', 'ck-oneform'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                <a href="?page=ck-oneform-applications" class="button button-primary">
                    <?php _e('View All Applications', 'ck-oneform'); ?>
                </a>
            </p>
        <?php else : ?>
            <p><?php _e('No applications yet.', 'ck-oneform'); ?></p>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div style="margin-top: 30px;">
        <h2><?php _e('Quick Links', 'ck-oneform'); ?></h2>
        <p>
            <a href="post-new.php?post_type=ck_oneform" class="button"><?php _e('Create New Form', 'ck-oneform'); ?></a>
            <a href="post-new.php?post_type=ck_course" class="button"><?php _e('Add Course', 'ck-oneform'); ?></a>
            <a href="post-new.php?post_type=ck_college" class="button"><?php _e('Add College', 'ck-oneform'); ?></a>
            <a href="?page=ck-oneform-settings" class="button"><?php _e('Settings', 'ck-oneform'); ?></a>
        </p>
    </div>
</div>
