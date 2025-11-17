<?php
/**
 * Site Footer Template
 *
 * Professional footer with navigation links, contact info, and social media
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get statistics
global $wpdb;
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}ck_oneform_applications'");
$total_applications = $table_exists ? (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_applications") : 5000;
$total_colleges = wp_count_posts('ck_college')->publish ?: 500;
$total_courses = wp_count_posts('ck_course')->publish ?: 150;
?>

<!-- Site Footer -->
<footer class="ck-site-footer">
    <!-- Newsletter Section -->
    <div class="ck-footer-newsletter">
        <div class="ck-footer-container">
            <div class="ck-newsletter-content">
                <div class="ck-newsletter-text">
                    <h3><?php _e('Stay Updated', 'ck-oneform'); ?></h3>
                    <p><?php _e('Get the latest admission updates, exam notifications, and career guidance in your inbox.', 'ck-oneform'); ?></p>
                </div>
                <form class="ck-newsletter-form" id="ck-newsletter-form">
                    <input type="email" placeholder="<?php _e('Enter your email address', 'ck-oneform'); ?>" required>
                    <button type="submit"><?php _e('Subscribe', 'ck-oneform'); ?></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="ck-footer-main">
        <div class="ck-footer-container">
            <div class="ck-footer-grid">
                <!-- About Section -->
                <div class="ck-footer-section ck-about-section">
                    <div class="ck-footer-logo">
                        <div class="ck-logo-mark">CK</div>
                        <div class="ck-logo-text">
                            <span class="ck-brand">CollegeKampus</span>
                            <span class="ck-tagline">OneForm Portal</span>
                        </div>
                    </div>
                    <p class="ck-footer-description">
                        <?php _e('Simplifying college admissions across India. Apply to 500+ colleges with a single form and track your journey to success.', 'ck-oneform'); ?>
                    </p>
                    <div class="ck-footer-stats">
                        <div class="ck-stat">
                            <strong><?php echo number_format($total_colleges); ?>+</strong>
                            <span><?php _e('Colleges', 'ck-oneform'); ?></span>
                        </div>
                        <div class="ck-stat">
                            <strong><?php echo number_format($total_courses); ?>+</strong>
                            <span><?php _e('Courses', 'ck-oneform'); ?></span>
                        </div>
                        <div class="ck-stat">
                            <strong><?php echo number_format($total_applications); ?>+</strong>
                            <span><?php _e('Applications', 'ck-oneform'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="ck-footer-section">
                    <h4><?php _e('Quick Links', 'ck-oneform'); ?></h4>
                    <ul class="ck-footer-links">
                        <li><a href="<?php echo home_url('/oneform-home/'); ?>"><?php _e('Home', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/about-us/'); ?>"><?php _e('About Us', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/services/'); ?>"><?php _e('Our Services', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/courses/'); ?>"><?php _e('Courses', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/colleges/'); ?>"><?php _e('Colleges', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/contact-us/'); ?>"><?php _e('Contact Us', 'ck-oneform'); ?></a></li>
                    </ul>
                </div>

                <!-- Student Resources -->
                <div class="ck-footer-section">
                    <h4><?php _e('Student Resources', 'ck-oneform'); ?></h4>
                    <ul class="ck-footer-links">
                        <li><a href="<?php echo home_url('/student-registration/'); ?>"><?php _e('Register Now', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/application-form/'); ?>"><?php _e('Apply Online', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/application-status/'); ?>"><?php _e('Track Application', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/mock-tests/'); ?>"><?php _e('Mock Tests', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/student-dashboard/'); ?>"><?php _e('Student Dashboard', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/faq/'); ?>"><?php _e('FAQ', 'ck-oneform'); ?></a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="ck-footer-section">
                    <h4><?php _e('Contact Us', 'ck-oneform'); ?></h4>
                    <ul class="ck-contact-info">
                        <li>
                            <i class="dashicons dashicons-location"></i>
                            <span>123 Education Street<br>New Delhi, India - 110001</span>
                        </li>
                        <li>
                            <i class="dashicons dashicons-phone"></i>
                            <span>+91 11 2345 6789<br>+91 98765 43210</span>
                        </li>
                        <li>
                            <i class="dashicons dashicons-email-alt"></i>
                            <span>info@collegekampus.com<br>support@collegekampus.com</span>
                        </li>
                        <li>
                            <i class="dashicons dashicons-clock"></i>
                            <span><?php _e('Mon - Sat: 9:00 AM - 6:00 PM', 'ck-oneform'); ?></span>
                        </li>
                    </ul>

                    <!-- Social Media -->
                    <div class="ck-social-links">
                        <a href="#" target="_blank" rel="noopener" aria-label="Facebook"><i class="dashicons dashicons-facebook"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="Twitter"><i class="dashicons dashicons-twitter"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="dashicons dashicons-linkedin"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="Instagram"><i class="dashicons dashicons-instagram"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="YouTube"><i class="dashicons dashicons-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="ck-footer-bottom">
        <div class="ck-footer-container">
            <div class="ck-footer-bottom-content">
                <div class="ck-copyright">
                    <p>&copy; <?php echo date('Y'); ?> CollegeKampus. <?php _e('All Rights Reserved.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-footer-bottom-links">
                    <a href="#"><?php _e('Privacy Policy', 'ck-oneform'); ?></a>
                    <a href="#"><?php _e('Terms of Service', 'ck-oneform'); ?></a>
                    <a href="#"><?php _e('Refund Policy', 'ck-oneform'); ?></a>
                    <a href="#"><?php _e('Sitemap', 'ck-oneform'); ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button class="ck-back-to-top" id="ck-back-to-top" aria-label="Back to top">
        <i class="dashicons dashicons-arrow-up-alt2"></i>
    </button>
</footer>

<style>
/* Footer Styles */
.ck-site-footer {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    position: relative;
}

.ck-footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
}

/* Newsletter Section */
.ck-footer-newsletter {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 50px 0;
}

.ck-newsletter-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
}

.ck-newsletter-text h3 {
    margin: 0 0 10px 0;
    font-size: 28px;
}

.ck-newsletter-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.ck-newsletter-form {
    display: flex;
    gap: 10px;
    flex: 1;
    max-width: 500px;
}

.ck-newsletter-form input {
    flex: 1;
    padding: 15px 20px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
}

.ck-newsletter-form input:focus {
    outline: none;
}

.ck-newsletter-form button {
    background: #2c3e50;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.ck-newsletter-form button:hover {
    background: #34495e;
    transform: translateY(-2px);
}

/* Main Footer */
.ck-footer-main {
    background: #2c3e50;
    color: #ecf0f1;
    padding: 60px 0 40px;
}

.ck-footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
    gap: 40px;
}

.ck-footer-section h4 {
    color: white;
    font-size: 18px;
    margin: 0 0 25px 0;
    padding-bottom: 15px;
    position: relative;
}

.ck-footer-section h4::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: #667eea;
    border-radius: 2px;
}

/* About Section */
.ck-footer-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.ck-footer-logo .ck-logo-mark {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 800;
    font-size: 20px;
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ck-footer-logo .ck-brand {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: white;
}

.ck-footer-logo .ck-tagline {
    display: block;
    font-size: 11px;
    color: #95a5a6;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.ck-footer-description {
    color: #bdc3c7;
    line-height: 1.8;
    margin-bottom: 25px;
}

.ck-footer-stats {
    display: flex;
    gap: 25px;
}

.ck-stat {
    text-align: center;
}

.ck-stat strong {
    display: block;
    font-size: 24px;
    color: #667eea;
    font-weight: 700;
}

.ck-stat span {
    font-size: 12px;
    color: #95a5a6;
    text-transform: uppercase;
}

/* Footer Links */
.ck-footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ck-footer-links li {
    margin-bottom: 12px;
}

.ck-footer-links li a {
    color: #bdc3c7;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
}

.ck-footer-links li a:hover {
    color: #667eea;
    padding-left: 8px;
}

/* Contact Info */
.ck-contact-info {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ck-contact-info li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 15px;
    color: #bdc3c7;
    font-size: 14px;
    line-height: 1.6;
}

.ck-contact-info li .dashicons {
    color: #667eea;
    margin-top: 3px;
}

/* Social Links */
.ck-social-links {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.ck-social-links a {
    background: #34495e;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.ck-social-links a:hover {
    background: #667eea;
    transform: translateY(-3px);
}

/* Footer Bottom */
.ck-footer-bottom {
    background: #1a252f;
    padding: 20px 0;
}

.ck-footer-bottom-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ck-copyright p {
    margin: 0;
    color: #7f8c8d;
    font-size: 14px;
}

.ck-footer-bottom-links {
    display: flex;
    gap: 25px;
}

.ck-footer-bottom-links a {
    color: #7f8c8d;
    text-decoration: none;
    font-size: 13px;
    transition: color 0.3s;
}

.ck-footer-bottom-links a:hover {
    color: #667eea;
}

/* Back to Top */
.ck-back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.3s;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    z-index: 9999;
}

.ck-back-to-top.visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.ck-back-to-top:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.5);
}

.ck-back-to-top .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}

/* Mobile Responsive */
@media (max-width: 992px) {
    .ck-footer-grid {
        grid-template-columns: 1fr 1fr;
    }

    .ck-newsletter-content {
        flex-direction: column;
        text-align: center;
    }

    .ck-newsletter-form {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .ck-footer-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .ck-footer-section {
        text-align: center;
    }

    .ck-footer-section h4::after {
        left: 50%;
        transform: translateX(-50%);
    }

    .ck-footer-logo {
        justify-content: center;
    }

    .ck-footer-stats {
        justify-content: center;
    }

    .ck-contact-info li {
        justify-content: center;
        text-align: left;
    }

    .ck-social-links {
        justify-content: center;
    }

    .ck-footer-bottom-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .ck-newsletter-form {
        flex-direction: column;
    }

    .ck-back-to-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Back to top button
    var $backToTop = $('#ck-back-to-top');

    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $backToTop.addClass('visible');
        } else {
            $backToTop.removeClass('visible');
        }
    });

    $backToTop.on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 600);
    });

    // Newsletter form submission
    $('#ck-newsletter-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button');
        var email = $form.find('input[type="email"]').val();

        $btn.text('Subscribing...').prop('disabled', true);

        // Simulate subscription (you can add actual AJAX here)
        setTimeout(function() {
            $btn.text('Subscribed!').css('background', '#27ae60');
            $form.find('input').val('');
            setTimeout(function() {
                $btn.text('Subscribe').css('background', '').prop('disabled', false);
            }, 2000);
        }, 1000);
    });
});
</script>
