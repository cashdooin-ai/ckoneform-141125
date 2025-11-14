<?php
/**
 * Payment Page Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();
$applications = CK_OneForm_Database::get_user_applications($user_id);
$enable_payments = get_option('ck_oneform_enable_payments', 'no');
?>

<div class="ck-oneform-payment">
    <div class="container">
        <h2><?php _e('Application Fee Payment', 'ck-oneform'); ?></h2>

        <?php if ($enable_payments !== 'yes') : ?>
            <div class="alert alert-warning">
                <p><?php _e('Payment system is currently unavailable. Please contact the administrator.', 'ck-oneform'); ?></p>
            </div>
        <?php else : ?>

            <?php if ($applications) : ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="payment-form-card">
                            <h3><?php _e('Select Application', 'ck-oneform'); ?></h3>

                            <form id="ck-oneform-payment-form" class="ck-oneform-form">
                                <?php wp_nonce_field('ck-oneform-payment', 'nonce'); ?>

                                <div class="form-group">
                                    <label for="application_id"><?php _e('Application', 'ck-oneform'); ?></label>
                                    <select class="form-control" id="application_id" name="application_id" required>
                                        <option value=""><?php _e('Select an application...', 'ck-oneform'); ?></option>
                                        <?php foreach ($applications as $app) : ?>
                                            <option value="<?php echo esc_attr($app->id); ?>">
                                                <?php echo esc_html($app->application_number); ?> - <?php echo esc_html(ucfirst($app->status)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="amount"><?php _e('Amount', 'ck-oneform'); ?> (<?php echo get_option('ck_oneform_currency', 'INR'); ?>)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="500" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method"><?php _e('Payment Method', 'ck-oneform'); ?></label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="razorpay"><?php _e('Razorpay', 'ck-oneform'); ?></option>
                                        <option value="payu"><?php _e('PayU', 'ck-oneform'); ?></option>
                                        <option value="paytm"><?php _e('Paytm', 'ck-oneform'); ?></option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <?php _e('Proceed to Payment', 'ck-oneform'); ?>
                                </button>
                            </form>

                            <div class="form-message mt-3"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="payment-info-card">
                            <h4><?php _e('Payment Information', 'ck-oneform'); ?></h4>
                            <ul>
                                <li><?php _e('Application fee is non-refundable', 'ck-oneform'); ?></li>
                                <li><?php _e('Payment must be completed within 24 hours', 'ck-oneform'); ?></li>
                                <li><?php _e('You will receive a confirmation email', 'ck-oneform'); ?></li>
                                <li><?php _e('Keep the transaction ID for reference', 'ck-oneform'); ?></li>
                            </ul>

                            <div class="accepted-payments">
                                <h5><?php _e('We Accept', 'ck-oneform'); ?></h5>
                                <p><?php _e('Credit Cards, Debit Cards, Net Banking, UPI, Wallets', 'ck-oneform'); ?></p>
                            </div>
                        </div>

                        <!-- Payment History -->
                        <?php
                        $payments = CK_OneForm_User_Dashboard::get_user_payments($user_id);
                        if ($payments) :
                        ?>
                            <div class="payment-history-card mt-3">
                                <h4><?php _e('Recent Payments', 'ck-oneform'); ?></h4>
                                <div class="payment-list">
                                    <?php foreach (array_slice($payments, 0, 5) as $payment) : ?>
                                        <div class="payment-item">
                                            <div class="payment-amount">₹<?php echo number_format($payment->amount, 2); ?></div>
                                            <div class="payment-details">
                                                <div class="payment-date"><?php echo date('M j, Y', strtotime($payment->payment_date)); ?></div>
                                                <div class="payment-status <?php echo esc_attr($payment->status); ?>">
                                                    <?php echo esc_html(ucfirst($payment->status)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="alert alert-info">
                    <p><?php _e('You need to submit an application before making a payment.', 'ck-oneform'); ?></p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-primary">
                        <?php _e('Submit Application', 'ck-oneform'); ?>
                    </a>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>
