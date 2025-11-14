<?php
/**
 * Student Registration Form Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ck-oneform-registration">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="registration-card">
                    <h2><?php _e('Student Registration', 'ck-oneform'); ?></h2>
                    <p><?php _e('Create your account to start applying to colleges', 'ck-oneform'); ?></p>

                    <form id="ck-oneform-registration-form" class="ck-oneform-form" method="post">
                        <?php wp_nonce_field('ck-oneform-registration', 'nonce'); ?>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="first_name"><?php _e('First Name', 'ck-oneform'); ?> <span class="required">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="last_name"><?php _e('Last Name', 'ck-oneform'); ?> <span class="required">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email"><?php _e('Email Address', 'ck-oneform'); ?> <span class="required">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone"><?php _e('Phone Number', 'ck-oneform'); ?> <span class="required">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label for="username"><?php _e('Username', 'ck-oneform'); ?> <span class="required">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <small class="form-text text-muted"><?php _e('Choose a unique username for login', 'ck-oneform'); ?></small>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password"><?php _e('Password', 'ck-oneform'); ?> <span class="required">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="confirm_password"><?php _e('Confirm Password', 'ck-oneform'); ?> <span class="required">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    <?php _e('I agree to the', 'ck-oneform'); ?>
                                    <a href="#" target="_blank"><?php _e('Terms and Conditions', 'ck-oneform'); ?></a>
                                </label>
                            </div>
                        </div>

                        <div class="form-message"></div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <?php _e('Register', 'ck-oneform'); ?>
                        </button>

                        <p class="text-center mt-3">
                            <?php _e('Already have an account?', 'ck-oneform'); ?>
                            <a href="<?php echo wp_login_url(get_permalink()); ?>"><?php _e('Login here', 'ck-oneform'); ?></a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
