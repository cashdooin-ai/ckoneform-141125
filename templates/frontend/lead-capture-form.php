<?php
/**
 * Lead Capture Form Template
 * Multiple variants for different use cases
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$form_id = 'lead-form-' . uniqid();
$variant = $atts['variant'] ?? 'full';
$source = $atts['source'] ?? 'Website';
$title = $atts['title'] ?? 'Get Free Counseling';
$subtitle = $atts['subtitle'] ?? 'Our experts will guide you to your dream college';
$button_text = $atts['button_text'] ?? 'Get Free Consultation';
$interests = array(
    'Engineering' => 'Engineering',
    'Medical' => 'Medical',
    'Commerce' => 'Commerce',
    'Arts' => 'Arts',
    'Science' => 'Science',
    'Law' => 'Law',
    'Management' => 'Management',
    'Design' => 'Design',
    'Other' => 'Other',
);

// Get UTM parameters
$utm_source = isset($_GET['utm_source']) ? sanitize_text_field($_GET['utm_source']) : '';
$utm_medium = isset($_GET['utm_medium']) ? sanitize_text_field($_GET['utm_medium']) : '';
$utm_campaign = isset($_GET['utm_campaign']) ? sanitize_text_field($_GET['utm_campaign']) : '';
?>

<?php if ($variant === 'full'): ?>
<!-- Full Lead Capture Form -->
<div class="ck-lead-form-container">
    <div class="lead-form-header">
        <h3><?php echo esc_html($title); ?></h3>
        <p><?php echo esc_html($subtitle); ?></p>
    </div>

    <form id="<?php echo $form_id; ?>" class="ck-lead-form">
        <div class="form-row">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" required placeholder="Your full name">
            </div>
        </div>

        <div class="form-row two-columns">
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" required placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" name="phone" required placeholder="9876543210" pattern="[0-9]{10}">
            </div>
        </div>

        <div class="form-row two-columns">
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" placeholder="Your city">
            </div>
            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" placeholder="Your state">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Interested In</label>
                <select name="interest">
                    <option value="">Select your interest</option>
                    <?php foreach ($interests as $key => $value): ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Message (Optional)</label>
                <textarea name="message" rows="3" placeholder="Tell us about your requirements..."></textarea>
            </div>
        </div>

        <input type="hidden" name="source" value="<?php echo esc_attr($source); ?>">
        <input type="hidden" name="utm_source" value="<?php echo esc_attr($utm_source); ?>">
        <input type="hidden" name="utm_medium" value="<?php echo esc_attr($utm_medium); ?>">
        <input type="hidden" name="utm_campaign" value="<?php echo esc_attr($utm_campaign); ?>">

        <button type="submit" class="btn-submit">
            <span class="btn-text"><?php echo esc_html($button_text); ?></span>
            <span class="btn-loading" style="display: none;">Submitting...</span>
        </button>

        <div class="form-message" style="display: none;"></div>

        <p class="privacy-note">
            <small>By submitting, you agree to our <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a>.</small>
        </p>
    </form>
</div>

<?php elseif ($variant === 'compact'): ?>
<!-- Compact Lead Form (for sidebar/popups) -->
<div class="ck-lead-form-compact">
    <div class="lead-form-header">
        <h4><?php echo esc_html($title); ?></h4>
    </div>

    <form id="<?php echo $form_id; ?>" class="ck-lead-form">
        <div class="form-group">
            <input type="text" name="name" required placeholder="Your Name *">
        </div>
        <div class="form-group">
            <input type="email" name="email" required placeholder="Email Address *">
        </div>
        <div class="form-group">
            <input type="tel" name="phone" required placeholder="Phone Number *" pattern="[0-9]{10}">
        </div>
        <div class="form-group">
            <select name="interest">
                <option value="">Select Interest</option>
                <?php foreach ($interests as $key => $value): ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" name="source" value="<?php echo esc_attr($source); ?>">
        <input type="hidden" name="utm_source" value="<?php echo esc_attr($utm_source); ?>">
        <input type="hidden" name="utm_medium" value="<?php echo esc_attr($utm_medium); ?>">
        <input type="hidden" name="utm_campaign" value="<?php echo esc_attr($utm_campaign); ?>">

        <button type="submit" class="btn-submit-compact">
            <span class="btn-text"><?php echo esc_html($button_text); ?></span>
            <span class="btn-loading" style="display: none;">...</span>
        </button>

        <div class="form-message" style="display: none;"></div>
    </form>
</div>

<?php elseif ($variant === 'inline'): ?>
<!-- Inline Lead Form (horizontal layout) -->
<div class="ck-lead-form-inline">
    <form id="<?php echo $form_id; ?>" class="ck-lead-form">
        <input type="text" name="name" required placeholder="Your Name *">
        <input type="email" name="email" required placeholder="Email *">
        <input type="tel" name="phone" required placeholder="Phone *" pattern="[0-9]{10}">
        <select name="interest">
            <option value="">Interest</option>
            <?php foreach ($interests as $key => $value): ?>
            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="source" value="<?php echo esc_attr($source); ?>">
        <input type="hidden" name="utm_source" value="<?php echo esc_attr($utm_source); ?>">
        <input type="hidden" name="utm_medium" value="<?php echo esc_attr($utm_medium); ?>">
        <input type="hidden" name="utm_campaign" value="<?php echo esc_attr($utm_campaign); ?>">
        <button type="submit" class="btn-inline">
            <span class="btn-text"><?php echo esc_html($button_text); ?></span>
            <span class="btn-loading" style="display: none;">...</span>
        </button>
    </form>
    <div class="form-message" style="display: none;"></div>
</div>

<?php elseif ($variant === 'floating'): ?>
<!-- Floating CTA Button with Popup Form -->
<div class="ck-floating-cta" id="floating-cta">
    <button class="floating-btn" onclick="document.getElementById('lead-popup').style.display='flex'">
        <span class="dashicons dashicons-phone"></span>
        Get Free Counseling
    </button>
</div>

<div class="ck-lead-popup" id="lead-popup" style="display: none;">
    <div class="popup-overlay" onclick="this.parentElement.style.display='none'"></div>
    <div class="popup-content">
        <button class="close-popup" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
        <div class="popup-header">
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($subtitle); ?></p>
        </div>
        <form id="<?php echo $form_id; ?>" class="ck-lead-form">
            <div class="form-group">
                <input type="text" name="name" required placeholder="Your Name *">
            </div>
            <div class="form-group">
                <input type="email" name="email" required placeholder="Email Address *">
            </div>
            <div class="form-group">
                <input type="tel" name="phone" required placeholder="Phone Number *" pattern="[0-9]{10}">
            </div>
            <div class="form-group">
                <select name="interest">
                    <option value="">Select Interest</option>
                    <?php foreach ($interests as $key => $value): ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <textarea name="message" rows="2" placeholder="Your message (optional)"></textarea>
            </div>

            <input type="hidden" name="source" value="<?php echo esc_attr($source); ?>">
            <input type="hidden" name="utm_source" value="<?php echo esc_attr($utm_source); ?>">
            <input type="hidden" name="utm_medium" value="<?php echo esc_attr($utm_medium); ?>">
            <input type="hidden" name="utm_campaign" value="<?php echo esc_attr($utm_campaign); ?>">

            <button type="submit" class="btn-popup">
                <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                <span class="btn-loading" style="display: none;">Submitting...</span>
            </button>

            <div class="form-message" style="display: none;"></div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
jQuery(document).ready(function($) {
    $('#<?php echo $form_id; ?>').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var $message = $form.siblings('.form-message').length ? $form.siblings('.form-message') : $form.find('.form-message');

        $btn.find('.btn-text').hide();
        $btn.find('.btn-loading').show();
        $btn.prop('disabled', true);

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_capture_lead',
                nonce: '<?php echo wp_create_nonce('ck-capture-lead'); ?>',
                name: $form.find('[name="name"]').val(),
                email: $form.find('[name="email"]').val(),
                phone: $form.find('[name="phone"]').val(),
                interest: $form.find('[name="interest"]').val(),
                city: $form.find('[name="city"]').val() || '',
                state: $form.find('[name="state"]').val() || '',
                message: $form.find('[name="message"]').val() || '',
                source: $form.find('[name="source"]').val(),
                utm_source: $form.find('[name="utm_source"]').val(),
                utm_medium: $form.find('[name="utm_medium"]').val(),
                utm_campaign: $form.find('[name="utm_campaign"]').val()
            },
            success: function(response) {
                if (response.success) {
                    $message.removeClass('error').addClass('success').html('✅ ' + response.data.message).show();
                    $form[0].reset();

                    // Track conversion (if analytics available)
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'lead_capture', {
                            'event_category': 'Lead',
                            'event_label': $form.find('[name="source"]').val()
                        });
                    }
                } else {
                    $message.removeClass('success').addClass('error').html('❌ ' + response.data.message).show();
                }
            },
            error: function() {
                $message.removeClass('success').addClass('error').html('❌ Something went wrong. Please try again.').show();
            },
            complete: function() {
                $btn.find('.btn-text').show();
                $btn.find('.btn-loading').hide();
                $btn.prop('disabled', false);
            }
        });
    });
});
</script>

<style>
/* Full Form Styles */
.ck-lead-form-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}

.lead-form-header {
    text-align: center;
    margin-bottom: 25px;
}

.lead-form-header h3 {
    margin: 0 0 10px;
    color: #333;
    font-size: 1.8rem;
}

.lead-form-header p {
    margin: 0;
    color: #666;
}

.ck-lead-form .form-row {
    margin-bottom: 20px;
}

.ck-lead-form .form-row.two-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.ck-lead-form .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.ck-lead-form .form-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.ck-lead-form input,
.ck-lead-form select,
.ck-lead-form textarea {
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.ck-lead-form input:focus,
.ck-lead-form select:focus,
.ck-lead-form textarea:focus {
    outline: none;
    border-color: #667eea;
}

.btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.form-message {
    margin-top: 15px;
    padding: 12px;
    border-radius: 8px;
    font-weight: 500;
}

.form-message.success {
    background: #d1fae5;
    color: #065f46;
}

.form-message.error {
    background: #fee2e2;
    color: #991b1b;
}

.privacy-note {
    text-align: center;
    margin-top: 15px;
    color: #666;
}

.privacy-note a {
    color: #667eea;
    text-decoration: none;
}

/* Compact Form Styles */
.ck-lead-form-compact {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.ck-lead-form-compact .lead-form-header h4 {
    margin: 0 0 15px;
    color: #333;
    font-size: 1.2rem;
}

.ck-lead-form-compact .form-group {
    margin-bottom: 12px;
}

.ck-lead-form-compact input,
.ck-lead-form-compact select {
    width: 100%;
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}

.btn-submit-compact {
    width: 100%;
    padding: 12px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
}

.btn-submit-compact:hover {
    background: #5568d3;
}

/* Inline Form Styles */
.ck-lead-form-inline {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
}

.ck-lead-form-inline form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.ck-lead-form-inline input,
.ck-lead-form-inline select {
    flex: 1;
    min-width: 150px;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
}

.btn-inline {
    padding: 12px 30px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}

.btn-inline:hover {
    background: #5568d3;
}

.ck-lead-form-inline .form-message {
    width: 100%;
}

/* Floating CTA Styles */
.ck-floating-cta {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
}

.floating-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 25px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    display: flex;
    align-items: center;
    gap: 10px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
    50% { box-shadow: 0 8px 35px rgba(102, 126, 234, 0.6); }
    100% { box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
}

.floating-btn:hover {
    transform: scale(1.05);
}

.ck-lead-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
}

.popup-content {
    background: white;
    border-radius: 16px;
    padding: 30px;
    width: 450px;
    max-width: 90%;
    position: relative;
    z-index: 1;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.close-popup {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 30px;
    cursor: pointer;
    color: #999;
}

.close-popup:hover {
    color: #333;
}

.popup-header {
    text-align: center;
    margin-bottom: 20px;
}

.popup-header h3 {
    margin: 0 0 10px;
    color: #333;
}

.popup-header p {
    margin: 0;
    color: #666;
}

.popup-content .form-group {
    margin-bottom: 15px;
}

.popup-content input,
.popup-content select,
.popup-content textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
}

.btn-popup {
    width: 100%;
    padding: 14px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn-popup:hover {
    background: #5568d3;
}

@media (max-width: 768px) {
    .ck-lead-form .form-row.two-columns {
        grid-template-columns: 1fr;
    }

    .ck-lead-form-inline form {
        flex-direction: column;
    }

    .ck-lead-form-inline input,
    .ck-lead-form-inline select {
        width: 100%;
    }

    .ck-floating-cta {
        bottom: 20px;
        right: 20px;
    }

    .floating-btn {
        padding: 12px 20px;
        font-size: 14px;
    }
}
</style>
