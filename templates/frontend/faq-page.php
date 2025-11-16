<?php
/**
 * FAQ Page Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// FAQ Data
$faqs = array(
    'general' => array(
        'title' => __('General Questions', 'ck-oneform'),
        'icon' => 'info',
        'questions' => array(
            array(
                'q' => __('What is CollegeKampus OneForm?', 'ck-oneform'),
                'a' => __('CollegeKampus OneForm is a unified college application platform that allows students to apply to multiple colleges through a single application form. It simplifies the admission process by eliminating the need to fill multiple forms for different colleges.', 'ck-oneform')
            ),
            array(
                'q' => __('Is CollegeKampus free to use?', 'ck-oneform'),
                'a' => __('Registration on CollegeKampus is free. However, individual colleges may charge their own application fees which are paid directly through our secure payment gateway.', 'ck-oneform')
            ),
            array(
                'q' => __('Which colleges are available on the platform?', 'ck-oneform'),
                'a' => __('We partner with 500+ colleges across India including Government colleges, Private universities, Deemed universities, and Autonomous institutions offering courses in Engineering, Medical, Law, Management, Arts, Science, and more.', 'ck-oneform')
            ),
            array(
                'q' => __('How do I contact support?', 'ck-oneform'),
                'a' => __('You can reach our support team via email at support@collegekampus.com, call us at +91 11 2345 6789, or use the contact form on our website. We are available Monday to Saturday, 9 AM to 6 PM.', 'ck-oneform')
            ),
        )
    ),
    'registration' => array(
        'title' => __('Registration & Account', 'ck-oneform'),
        'icon' => 'admin-users',
        'questions' => array(
            array(
                'q' => __('How do I create an account?', 'ck-oneform'),
                'a' => __('Click on "Register Now" button, fill in your details (name, email, phone, password), agree to terms and conditions, and submit. You will receive a confirmation email and be automatically logged in.', 'ck-oneform')
            ),
            array(
                'q' => __('I forgot my password. How do I reset it?', 'ck-oneform'),
                'a' => __('Click on "Forgot Password" on the login page, enter your registered email address, and we will send you a password reset link. Follow the link to create a new password.', 'ck-oneform')
            ),
            array(
                'q' => __('Can I update my profile information?', 'ck-oneform'),
                'a' => __('Yes, you can update your profile information by logging into your dashboard and clicking on "Edit Profile". However, some information may be locked after you submit an application.', 'ck-oneform')
            ),
            array(
                'q' => __('What documents do I need for registration?', 'ck-oneform'),
                'a' => __('For basic registration, you only need a valid email address and phone number. Additional documents like photographs, mark sheets, and certificates are required when you fill the application form.', 'ck-oneform')
            ),
        )
    ),
    'application' => array(
        'title' => __('Application Process', 'ck-oneform'),
        'icon' => 'welcome-write-blog',
        'questions' => array(
            array(
                'q' => __('How do I apply to colleges?', 'ck-oneform'),
                'a' => __('After registration, go to the Application Form page, fill in your personal and academic details, upload required documents, select your preferred colleges and courses, review your application, and submit. You can apply to multiple colleges simultaneously.', 'ck-oneform')
            ),
            array(
                'q' => __('Can I apply to multiple colleges at once?', 'ck-oneform'),
                'a' => __('Yes! That\'s the key benefit of OneForm. You fill your details once and select multiple colleges you wish to apply to. Your single application is sent to all selected colleges.', 'ck-oneform')
            ),
            array(
                'q' => __('What documents are required for application?', 'ck-oneform'),
                'a' => __('Typically required documents include: Passport size photograph, Class 10th marksheet, Class 12th marksheet (if applicable), Valid ID proof (Aadhaar/Passport), Category certificate (if applicable), and any additional documents specific to certain colleges.', 'ck-oneform')
            ),
            array(
                'q' => __('Can I edit my application after submission?', 'ck-oneform'),
                'a' => __('Once submitted, applications cannot be edited to maintain data integrity. However, if you need to make corrections, contact our support team immediately and they will guide you through the process.', 'ck-oneform')
            ),
            array(
                'q' => __('What is the application number?', 'ck-oneform'),
                'a' => __('After successful submission, you receive a unique application number (format: CKOF-XXXXXX). This number is used to track your application status and for all future correspondence.', 'ck-oneform')
            ),
        )
    ),
    'payment' => array(
        'title' => __('Payments & Fees', 'ck-oneform'),
        'icon' => 'money-alt',
        'questions' => array(
            array(
                'q' => __('What payment methods are accepted?', 'ck-oneform'),
                'a' => __('We accept multiple payment methods including Credit/Debit Cards, Net Banking, UPI, and popular wallets through our payment partners - Razorpay, PayU, and Paytm. All transactions are secured with 256-bit encryption.', 'ck-oneform')
            ),
            array(
                'q' => __('Is the payment secure?', 'ck-oneform'),
                'a' => __('Absolutely! We use industry-standard SSL encryption and PCI-DSS compliant payment gateways. Your financial information is never stored on our servers and is processed directly by trusted payment processors.', 'ck-oneform')
            ),
            array(
                'q' => __('Can I get a refund if I cancel my application?', 'ck-oneform'),
                'a' => __('Refund policies vary by college. Generally, if you cancel before the application is processed, you may be eligible for a partial refund. Please check individual college policies or contact support for specific cases.', 'ck-oneform')
            ),
            array(
                'q' => __('Will I get a payment receipt?', 'ck-oneform'),
                'a' => __('Yes, immediately after successful payment, you will receive a payment confirmation email with a receipt. You can also download receipts from your dashboard at any time.', 'ck-oneform')
            ),
        )
    ),
    'status' => array(
        'title' => __('Application Status & Tracking', 'ck-oneform'),
        'icon' => 'search',
        'questions' => array(
            array(
                'q' => __('How do I track my application status?', 'ck-oneform'),
                'a' => __('You can track your application in three ways: 1) Log into your dashboard to see all applications and their statuses, 2) Use the public status checker with your application number, 3) Check your email for status update notifications.', 'ck-oneform')
            ),
            array(
                'q' => __('What do the different status mean?', 'ck-oneform'),
                'a' => __('Pending - Application received and under review, Under Review - Being evaluated by college, Shortlisted - Selected for next round, Approved - Admission offered, Rejected - Not selected, Waitlisted - On waiting list.', 'ck-oneform')
            ),
            array(
                'q' => __('How long does it take to get a response?', 'ck-oneform'),
                'a' => __('Response time varies by college and course. Typically, initial acknowledgment comes within 24-48 hours, while final decisions may take 2-4 weeks depending on the college\'s admission cycle.', 'ck-oneform')
            ),
            array(
                'q' => __('Will I be notified of status changes?', 'ck-oneform'),
                'a' => __('Yes, you will receive email notifications for every status change. You can also opt-in for SMS notifications. Important updates are also shown in your dashboard.', 'ck-oneform')
            ),
        )
    ),
    'mock_tests' => array(
        'title' => __('Mock Tests & Preparation', 'ck-oneform'),
        'icon' => 'edit',
        'questions' => array(
            array(
                'q' => __('What mock tests are available?', 'ck-oneform'),
                'a' => __('We offer mock tests for major competitive exams including CUET, JEE Main, JEE Advanced, NEET, CAT, GATE, and more. Tests are designed by experts and follow the actual exam pattern.', 'ck-oneform')
            ),
            array(
                'q' => __('Are mock tests free?', 'ck-oneform'),
                'a' => __('Some basic mock tests are free for all registered users. Premium mock tests with detailed analytics and personalized feedback require a subscription or one-time payment.', 'ck-oneform')
            ),
            array(
                'q' => __('How are mock tests scored?', 'ck-oneform'),
                'a' => __('Tests are automatically scored based on the actual exam marking scheme including negative marking where applicable. You get instant results with detailed analysis of your performance.', 'ck-oneform')
            ),
            array(
                'q' => __('Can I retake a mock test?', 'ck-oneform'),
                'a' => __('Yes, you can retake mock tests to improve your score. Your best score is recorded for reference. Each attempt is analyzed to show your improvement.', 'ck-oneform')
            ),
        )
    ),
);
?>

<div class="ck-faq-page">
    <div class="ck-faq-hero">
        <h1><?php _e('Frequently Asked Questions', 'ck-oneform'); ?></h1>
        <p><?php _e('Find answers to common questions about CollegeKampus OneForm', 'ck-oneform'); ?></p>

        <!-- Search Box -->
        <div class="ck-faq-search">
            <input type="text" id="faq-search" placeholder="<?php _e('Search for questions...', 'ck-oneform'); ?>">
            <span class="dashicons dashicons-search"></span>
        </div>
    </div>

    <div class="ck-faq-container">
        <!-- Category Navigation -->
        <div class="ck-faq-categories">
            <?php foreach ($faqs as $key => $category): ?>
            <a href="#faq-<?php echo esc_attr($key); ?>" class="ck-faq-category-link">
                <span class="dashicons dashicons-<?php echo esc_attr($category['icon']); ?>"></span>
                <span><?php echo esc_html($category['title']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- FAQ Sections -->
        <div class="ck-faq-sections">
            <?php foreach ($faqs as $key => $category): ?>
            <div id="faq-<?php echo esc_attr($key); ?>" class="ck-faq-section">
                <div class="ck-section-header">
                    <span class="dashicons dashicons-<?php echo esc_attr($category['icon']); ?>"></span>
                    <h2><?php echo esc_html($category['title']); ?></h2>
                </div>

                <div class="ck-faq-accordion">
                    <?php foreach ($category['questions'] as $index => $faq): ?>
                    <div class="ck-faq-item" data-searchable="<?php echo esc_attr(strtolower($faq['q'] . ' ' . $faq['a'])); ?>">
                        <div class="ck-faq-question">
                            <span class="ck-q-text"><?php echo esc_html($faq['q']); ?></span>
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </div>
                        <div class="ck-faq-answer">
                            <p><?php echo esc_html($faq['a']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Still Have Questions -->
        <div class="ck-faq-cta">
            <h2><?php _e('Still Have Questions?', 'ck-oneform'); ?></h2>
            <p><?php _e('Can\'t find what you\'re looking for? Our support team is here to help!', 'ck-oneform'); ?></p>
            <div class="ck-faq-cta-buttons">
                <a href="<?php echo home_url('/contact-us/'); ?>" class="ck-btn ck-btn-primary">
                    <span class="dashicons dashicons-email"></span>
                    <?php _e('Contact Support', 'ck-oneform'); ?>
                </a>
                <a href="tel:+911123456789" class="ck-btn ck-btn-secondary">
                    <span class="dashicons dashicons-phone"></span>
                    <?php _e('Call Us', 'ck-oneform'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.ck-faq-page {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.ck-faq-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 20px;
    text-align: center;
    border-radius: 10px;
    margin-bottom: 40px;
}

.ck-faq-hero h1 {
    margin: 0 0 10px 0;
    font-size: 2.5em;
}

.ck-faq-hero p {
    margin: 0 0 30px 0;
    font-size: 1.1em;
    opacity: 0.9;
}

.ck-faq-search {
    max-width: 500px;
    margin: 0 auto;
    position: relative;
}

.ck-faq-search input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    box-sizing: border-box;
}

.ck-faq-search input:focus {
    outline: none;
}

.ck-faq-search .dashicons {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
}

.ck-faq-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.ck-faq-categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 40px;
}

.ck-faq-category-link {
    background: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    color: #333;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.ck-faq-category-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    color: #667eea;
}

.ck-faq-category-link .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
    color: #667eea;
}

.ck-faq-section {
    margin-bottom: 40px;
}

.ck-section-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 3px solid #667eea;
}

.ck-section-header .dashicons {
    font-size: 30px;
    width: 30px;
    height: 30px;
    color: #667eea;
}

.ck-section-header h2 {
    margin: 0;
    color: #333;
    font-size: 1.5em;
}

.ck-faq-accordion {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ck-faq-item {
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: box-shadow 0.3s;
}

.ck-faq-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.12);
}

.ck-faq-item.hidden {
    display: none;
}

.ck-faq-question {
    padding: 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: #333;
    transition: background 0.3s;
}

.ck-faq-question:hover {
    background: #f8f9fa;
}

.ck-faq-question .dashicons {
    color: #667eea;
    transition: transform 0.3s;
}

.ck-faq-item.active .ck-faq-question .dashicons {
    transform: rotate(180deg);
}

.ck-faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, padding 0.3s;
    background: #f8f9fa;
}

.ck-faq-item.active .ck-faq-answer {
    max-height: 500px;
    padding: 0 20px 20px 20px;
}

.ck-faq-answer p {
    margin: 0;
    color: #555;
    line-height: 1.8;
}

.ck-faq-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 50px;
    border-radius: 15px;
    text-align: center;
    margin-top: 50px;
}

.ck-faq-cta h2 {
    color: white;
    margin: 0 0 15px 0;
}

.ck-faq-cta p {
    margin: 0 0 30px 0;
    opacity: 0.9;
}

.ck-faq-cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.ck-btn {
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.ck-btn-primary {
    background: white;
    color: #667eea;
}

.ck-btn-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.ck-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.highlight {
    background: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
}

@media (max-width: 768px) {
    .ck-faq-hero h1 {
        font-size: 2em;
    }

    .ck-faq-categories {
        grid-template-columns: repeat(2, 1fr);
    }

    .ck-faq-cta {
        padding: 30px 20px;
    }

    .ck-faq-cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .ck-faq-categories {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Accordion functionality
    $('.ck-faq-question').on('click', function() {
        var $item = $(this).closest('.ck-faq-item');
        var wasActive = $item.hasClass('active');

        // Close all other items in the same section
        $item.siblings('.ck-faq-item').removeClass('active');

        // Toggle current item
        if (!wasActive) {
            $item.addClass('active');
        } else {
            $item.removeClass('active');
        }
    });

    // Search functionality
    $('#faq-search').on('input', function() {
        var searchTerm = $(this).val().toLowerCase().trim();

        if (searchTerm.length < 2) {
            // Show all items
            $('.ck-faq-item').removeClass('hidden');
            $('.ck-faq-section').show();
            return;
        }

        // Hide/show items based on search
        $('.ck-faq-item').each(function() {
            var searchable = $(this).data('searchable');
            if (searchable.indexOf(searchTerm) !== -1) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });

        // Hide sections with no visible items
        $('.ck-faq-section').each(function() {
            var $visibleItems = $(this).find('.ck-faq-item:not(.hidden)');
            if ($visibleItems.length === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // Smooth scroll for category links
    $('.ck-faq-category-link').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(target).offset().top - 100
        }, 500);
    });
});
</script>
