<?php
/**
 * Admin Applications List Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$applications = CK_OneForm_Submissions::get_all(array('status' => $status_filter));
?>

<div class="wrap ck-oneform-admin">
    <h1><?php _e('Applications', 'ck-oneform'); ?></h1>

    <!-- Filters -->
    <div style="margin-bottom: 20px;">
        <select id="ck-filter-status">
            <option value=""><?php _e('All Status', 'ck-oneform'); ?></option>
            <option value="pending" <?php selected($status_filter, 'pending'); ?>><?php _e('Pending', 'ck-oneform'); ?></option>
            <option value="approved" <?php selected($status_filter, 'approved'); ?>><?php _e('Approved', 'ck-oneform'); ?></option>
            <option value="rejected" <?php selected($status_filter, 'rejected'); ?>><?php _e('Rejected', 'ck-oneform'); ?></option>
        </select>

        <input type="text" id="ck-search-applications" placeholder="<?php _e('Search...', 'ck-oneform'); ?>" style="width: 300px;">

        <button id="ck-export-applications" class="button"><?php _e('Export CSV', 'ck-oneform'); ?></button>
    </div>

    <!-- Applications Table -->
    <div class="ck-oneform-table-container">
        <?php if ($applications) : ?>
            <table class="ck-oneform-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="ck-select-all"></th>
                        <th><?php _e('Application No.', 'ck-oneform'); ?></th>
                        <th><?php _e('User', 'ck-oneform'); ?></th>
                        <th><?php _e('Submission Date', 'ck-oneform'); ?></th>
                        <th><?php _e('Status', 'ck-oneform'); ?></th>
                        <th><?php _e('Actions', 'ck-oneform'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app) :
                        $user = get_userdata($app->user_id);
                    ?>
                        <tr>
                            <td><input type="checkbox" class="ck-select-application" value="<?php echo $app->id; ?>"></td>
                            <td><?php echo esc_html($app->application_number); ?></td>
                            <td><?php echo $user ? esc_html($user->display_name) : 'N/A'; ?></td>
                            <td><?php echo date('M j, Y', strtotime($app->submission_date)); ?></td>
                            <td>
                                <span class="status-badge <?php echo esc_attr($app->status); ?>">
                                    <?php echo esc_html(ucfirst($app->status)); ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" class="ck-btn ck-btn-primary ck-btn-sm ck-update-status" data-id="<?php echo $app->id; ?>" data-status="<?php echo esc_attr($app->status); ?>">
                                    <?php _e('Update Status', 'ck-oneform'); ?>
                                </a>
                                <a href="#" class="ck-btn ck-btn-danger ck-btn-sm ck-delete-application" data-id="<?php echo $app->id; ?>">
                                    <?php _e('Delete', 'ck-oneform'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php _e('No applications found.', 'ck-oneform'); ?></p>
        <?php endif; ?>
    </div>
</div>
