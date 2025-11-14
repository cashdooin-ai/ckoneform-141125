<?php
/**
 * Admin Payments Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . 'ck_oneform_payments';
$payments = $wpdb->get_results("SELECT * FROM $table ORDER BY payment_date DESC LIMIT 50");
?>

<div class="wrap ck-oneform-admin">
    <h1><?php _e('Payments', 'ck-oneform'); ?></h1>

    <div class="ck-oneform-table-container">
        <?php if ($payments) : ?>
            <table class="ck-oneform-table">
                <thead>
                    <tr>
                        <th><?php _e('Transaction ID', 'ck-oneform'); ?></th>
                        <th><?php _e('Application', 'ck-oneform'); ?></th>
                        <th><?php _e('User', 'ck-oneform'); ?></th>
                        <th><?php _e('Amount', 'ck-oneform'); ?></th>
                        <th><?php _e('Payment Method', 'ck-oneform'); ?></th>
                        <th><?php _e('Status', 'ck-oneform'); ?></th>
                        <th><?php _e('Date', 'ck-oneform'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment) :
                        $user = get_userdata($payment->user_id);
                        $application = CK_OneForm_Database::get_application($payment->application_id);
                    ?>
                        <tr>
                            <td><?php echo esc_html($payment->transaction_id); ?></td>
                            <td><?php echo $application ? esc_html($application->application_number) : 'N/A'; ?></td>
                            <td><?php echo $user ? esc_html($user->display_name) : 'N/A'; ?></td>
                            <td><?php echo esc_html($payment->currency); ?> <?php echo number_format($payment->amount, 2); ?></td>
                            <td><?php echo esc_html(ucfirst($payment->payment_method)); ?></td>
                            <td>
                                <span class="status-badge <?php echo esc_attr($payment->status); ?>">
                                    <?php echo esc_html(ucfirst($payment->status)); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y H:i', strtotime($payment->payment_date)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php _e('No payments found.', 'ck-oneform'); ?></p>
        <?php endif; ?>
    </div>
</div>
