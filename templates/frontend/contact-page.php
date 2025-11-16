<?php
/**
 * Contact Page Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get admin email
$admin_email = get_option('ck_oneform_admin_email', get_option('admin_email'));
?>

<div class="ck-contact-page">
    <div class="ck-contact-hero">
        <h1><?php _e('Contact Us', 'ck-oneform'); ?></h1>
        <p><?php _e('We\'re here to help you with your college admission journey', 'ck-oneform'); ?></p>
    </div>

    <div class="ck-contact-container">
        <div class="ck-contact-grid">
            <!-- Contact Information -->
            <div class="ck-contact-info">
                <h2><?php _e('Get in Touch', 'ck-oneform'); ?></h2>

                <div class="ck-contact-card">
                    <div class="ck-contact-icon">
                        <span class="dashicons dashicons-location"></span>
                    </div>
                    <div class="ck-contact-details">
                        <h3><?php _e('Our Office', 'ck-oneform'); ?></h3>
                        <p>CollegeKampus Education Pvt. Ltd.<br>
                        123 Education Street<br>
                        New Delhi, India - 110001</p>
                    </div>
                </div>

                <div class="ck-contact-card">
                    <div class="ck-contact-icon">
                        <span class="dashicons dashicons-phone"></span>
                    </div>
                    <div class="ck-contact-details">
                        <h3><?php _e('Phone', 'ck-oneform'); ?></h3>
                        <p>+91 11 2345 6789<br>
                        +91 98765 43210</p>
                        <small><?php _e('Mon-Sat: 9:00 AM - 6:00 PM', 'ck-oneform'); ?></small>
                    </div>
                </div>

                <div class="ck-contact-card">
                    <div class="ck-contact-icon">
                        <span class="dashicons dashicons-email"></span>
                    </div>
                    <div class="ck-contact-details">
                        <h3><?php _e('Email', 'ck-oneform'); ?></h3>
                        <p><a href="mailto:<?php echo esc_attr($admin_email); ?>"><?php echo esc_html($admin_email); ?></a><br>
                        <a href="mailto:support@collegekampus.com">support@collegekampus.com</a></p>
                    </div>
                </div>

                <div class="ck-contact-card">
                    <div class="ck-contact-icon">
                        <span class="dashicons dashicons-share"></span>
                    </div>
                    <div class="ck-contact-details">
                        <h3><?php _e('Follow Us', 'ck-oneform'); ?></h3>
                        <div class="ck-social-links">
                            <a href="#" target="_blank" rel="noopener"><span class="dashicons dashicons-facebook"></span></a>
                            <a href="#" target="_blank" rel="noopener"><span class="dashicons dashicons-twitter"></span></a>
                            <a href="#" target="_blank" rel="noopener"><span class="dashicons dashicons-linkedin"></span></a>
                            <a href="#" target="_blank" rel="noopener"><span class="dashicons dashicons-instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="ck-contact-form-wrapper">
                <h2><?php _e('Send Us a Message', 'ck-oneform'); ?></h2>

                <form id="ck-contact-form" class="ck-contact-form" method="post">
                    <?php wp_nonce_field('ck_contact_form', 'ck_contact_nonce'); ?>

                    <div class="ck-form-row">
                        <div class="ck-form-group">
                            <label for="contact_name"><?php _e('Full Name', 'ck-oneform'); ?> <span class="required">*</span></label>
                            <input type="text" id="contact_name" name="contact_name" required>
                        </div>
                        <div class="ck-form-group">
                            <label for="contact_email"><?php _e('Email Address', 'ck-oneform'); ?> <span class="required">*</span></label>
                            <input type="email" id="contact_email" name="contact_email" required>
                        </div>
                    </div>

                    <div class="ck-form-row">
                        <div class="ck-form-group">
                            <label for="contact_phone"><?php _e('Phone Number', 'ck-oneform'); ?></label>
                            <input type="tel" id="contact_phone" name="contact_phone">
                        </div>
                        <div class="ck-form-group">
                            <label for="contact_subject"><?php _e('Subject', 'ck-oneform'); ?> <span class="required">*</span></label>
                            <select id="contact_subject" name="contact_subject" required>
                                <option value=""><?php _e('Select Subject', 'ck-oneform'); ?></option>
                                <option value="admission"><?php _e('Admission Inquiry', 'ck-oneform'); ?></option>
                                <option value="course"><?php _e('Course Information', 'ck-oneform'); ?></option>
                                <option value="scholarship"><?php _e('Scholarship Query', 'ck-oneform'); ?></option>
                                <option value="technical"><?php _e('Technical Support', 'ck-oneform'); ?></option>
                                <option value="feedback"><?php _e('Feedback', 'ck-oneform'); ?></option>
                                <option value="other"><?php _e('Other', 'ck-oneform'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="ck-form-group full-width">
                        <label for="contact_message"><?php _e('Your Message', 'ck-oneform'); ?> <span class="required">*</span></label>
                        <textarea id="contact_message" name="contact_message" rows="6" required></textarea>
                    </div>

                    <div class="ck-form-submit">
                        <button type="submit" class="ck-btn ck-btn-primary">
                            <span class="dashicons dashicons-email-alt"></span>
                            <?php _e('Send Message', 'ck-oneform'); ?>
                        </button>
                    </div>

                    <div id="contact-form-message" class="ck-form-message" style="display: none;"></div>
                </form>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="ck-contact-quick-links">
            <h2><?php _e('Quick Links', 'ck-oneform'); ?></h2>
            <div class="ck-quick-links-grid">
                <a href="<?php echo home_url('/student-registration/'); ?>" class="ck-quick-link">
                    <span class="dashicons dashicons-admin-users"></span>
                    <span><?php _e('Register Now', 'ck-oneform'); ?></span>
                </a>
                <a href="<?php echo home_url('/application-form/'); ?>" class="ck-quick-link">
                    <span class="dashicons dashicons-welcome-write-blog"></span>
                    <span><?php _e('Apply Online', 'ck-oneform'); ?></span>
                </a>
                <a href="<?php echo home_url('/application-status/'); ?>" class="ck-quick-link">
                    <span class="dashicons dashicons-search"></span>
                    <span><?php _e('Track Application', 'ck-oneform'); ?></span>
                </a>
                <a href="<?php echo home_url('/faq/'); ?>" class="ck-quick-link">
                    <span class="dashicons dashicons-sos"></span>
                    <span><?php _e('FAQs', 'ck-oneform'); ?></span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.ck-contact-page {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.ck-contact-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 20px;
    text-align: center;
    border-radius: 10px;
    margin-bottom: 40px;
}

.ck-contact-hero h1 {
    margin: 0 0 10px 0;
    font-size: 2.5em;
}

.ck-contact-hero p {
    margin: 0;
    font-size: 1.2em;
    opacity: 0.9;
}

.ck-contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.ck-contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
}

.ck-contact-info h2,
.ck-contact-form-wrapper h2 {
    margin-top: 0;
    margin-bottom: 30px;
    color: #333;
    font-size: 1.8em;
}

.ck-contact-card {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.ck-contact-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.ck-contact-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ck-contact-icon .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}

.ck-contact-details h3 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.1em;
}

.ck-contact-details p {
    margin: 0;
    color: #666;
    line-height: 1.6;
}

.ck-contact-details small {
    display: block;
    margin-top: 5px;
    color: #888;
}

.ck-contact-details a {
    color: #667eea;
    text-decoration: none;
}

.ck-contact-details a:hover {
    text-decoration: underline;
}

.ck-social-links {
    display: flex;
    gap: 10px;
    margin-top: 5px;
}

.ck-social-links a {
    background: #667eea;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.ck-social-links a:hover {
    background: #764ba2;
}

.ck-contact-form-wrapper {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.ck-contact-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.ck-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.ck-form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.ck-form-group .required {
    color: #e74c3c;
}

.ck-form-group input,
.ck-form-group select,
.ck-form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.ck-form-group input:focus,
.ck-form-group select:focus,
.ck-form-group textarea:focus {
    outline: none;
    border-color: #667eea;
}

.ck-form-group textarea {
    resize: vertical;
}

.ck-form-submit {
    text-align: center;
    margin-top: 10px;
}

.ck-btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.ck-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.ck-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
}

.ck-form-message {
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    margin-top: 10px;
}

.ck-form-message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.ck-form-message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.ck-contact-quick-links {
    background: #f8f9fa;
    padding: 40px;
    border-radius: 10px;
}

.ck-contact-quick-links h2 {
    text-align: center;
    margin-top: 0;
    margin-bottom: 30px;
}

.ck-quick-links-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.ck-quick-link {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    color: #333;
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.ck-quick-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.ck-quick-link .dashicons {
    font-size: 30px;
    width: 30px;
    height: 30px;
    color: #667eea;
}

@media (max-width: 768px) {
    .ck-contact-grid {
        grid-template-columns: 1fr;
    }

    .ck-form-row {
        grid-template-columns: 1fr;
    }

    .ck-quick-links-grid {
        grid-template-columns: 1fr 1fr;
    }

    .ck-contact-hero h1 {
        font-size: 2em;
    }
}

@media (max-width: 480px) {
    .ck-quick-links-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#ck-contact-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var $message = $('#contact-form-message');

        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update spinning"></span> Sending...');
        $message.hide();

        $.ajax({
            url: ckOneForm.ajax_url,
            type: 'POST',
            data: {
                action: 'ck_submit_contact_form',
                nonce: $form.find('#ck_contact_nonce').val(),
                name: $('#contact_name').val(),
                email: $('#contact_email').val(),
                phone: $('#contact_phone').val(),
                subject: $('#contact_subject').val(),
                message: $('#contact_message').val()
            },
            success: function(response) {
                if (response.success) {
                    $message.removeClass('error').addClass('success').html(response.data.message).slideDown();
                    $form[0].reset();
                } else {
                    $message.removeClass('success').addClass('error').html(response.data.message).slideDown();
                }
            },
            error: function() {
                $message.removeClass('success').addClass('error').html('An error occurred. Please try again.').slideDown();
            },
            complete: function() {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-email-alt"></span> Send Message');
            }
        });
    });
});
</script>
