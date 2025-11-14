<?php
/**
 * Modern OneForm Home Page Template - IMPROVED DESIGN
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get statistics
global $wpdb;
$total_colleges = wp_count_posts('ck_college')->publish;
$total_courses = wp_count_posts('ck_course')->publish;
$total_applications = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_applications");
$total_students = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$wpdb->prefix}ck_oneform_applications");
?>

<div class="ck-oneform-modern-home">

    <!-- Hero Section with Gradient -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-text">
                    <h1 class="hero-title animate-fade-in">
                        <?php _e('Apply to Multiple Colleges', 'ck-oneform'); ?>
                        <span class="highlight"><?php _e('with One Form', 'ck-oneform'); ?></span>
                    </h1>
                    <p class="hero-subtitle animate-fade-in-delay">
                        <?php _e('Fill one application and apply to 500+ top colleges across India. Save time, simplify admissions.', 'ck-oneform'); ?>
                    </p>

                    <div class="hero-cta animate-slide-up">
                        <?php if (!is_user_logged_in()) : ?>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('student-registration'))); ?>" class="btn btn-hero btn-primary">
                                <span><?php _e('Register Now - It\'s Free', 'ck-oneform'); ?></span>
                                <i class="arrow-icon">→</i>
                            </a>
                            <a href="#how-it-works" class="btn btn-hero btn-outline">
                                <?php _e('How It Works', 'ck-oneform'); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-hero btn-primary">
                                <span><?php _e('Start Your Application', 'ck-oneform'); ?></span>
                                <i class="arrow-icon">→</i>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('my-applications'))); ?>" class="btn btn-hero btn-outline">
                                <?php _e('My Dashboard', 'ck-oneform'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Stats in Hero -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?php echo $total_colleges; ?>">0</span>
                            <span class="stat-label"><?php _e('Colleges', 'ck-oneform'); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?php echo $total_courses; ?>">0</span>
                            <span class="stat-label"><?php _e('Courses', 'ck-oneform'); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?php echo $total_students; ?>">0</span>
                            <span class="stat-label"><?php _e('Students Registered', 'ck-oneform'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="hero-image">
                    <div class="hero-illustration">
                        <!-- Animated illustration placeholder -->
                        <div class="illustration-wrapper">
                            <svg viewBox="0 0 500 500" class="animated-svg">
                                <!-- Students and college building illustration -->
                                <circle cx="250" cy="250" r="200" fill="url(#grad1)" opacity="0.2"/>
                                <defs>
                                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
        </div>
    </section>

    <!-- Trust Badge Section -->
    <section class="trust-badges">
        <div class="container">
            <div class="badges-wrapper">
                <div class="badge-item">
                    <i class="icon-check">✓</i>
                    <span><?php _e('100% Free Application', 'ck-oneform'); ?></span>
                </div>
                <div class="badge-item">
                    <i class="icon-check">✓</i>
                    <span><?php _e('500+ Partner Colleges', 'ck-oneform'); ?></span>
                </div>
                <div class="badge-item">
                    <i class="icon-check">✓</i>
                    <span><?php _e('Instant Status Updates', 'ck-oneform'); ?></span>
                </div>
                <div class="badge-item">
                    <i class="icon-check">✓</i>
                    <span><?php _e('Secure & Confidential', 'ck-oneform'); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('How It Works', 'ck-oneform'); ?></h2>
                <p class="section-subtitle"><?php _e('Simple 3-step process to apply to your dream colleges', 'ck-oneform'); ?></p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <circle cx="30" cy="30" r="28" fill="#667eea" opacity="0.1"/>
                            <path d="M30 15 L30 45 M15 30 L45 30" stroke="#667eea" stroke-width="3"/>
                        </svg>
                    </div>
                    <h3 class="step-title"><?php _e('Register & Fill Form', 'ck-oneform'); ?></h3>
                    <p class="step-description">
                        <?php _e('Create your account and fill one comprehensive application form with your academic details and documents.', 'ck-oneform'); ?>
                    </p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <circle cx="30" cy="30" r="28" fill="#667eea" opacity="0.1"/>
                            <rect x="15" y="20" width="30" height="25" rx="2" stroke="#667eea" stroke-width="2" fill="none"/>
                        </svg>
                    </div>
                    <h3 class="step-title"><?php _e('Select Multiple Colleges', 'ck-oneform'); ?></h3>
                    <p class="step-description">
                        <?php _e('Choose from 500+ colleges across India. Select up to 10 colleges where you want to apply.', 'ck-oneform'); ?>
                    </p>
                </div>

                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <circle cx="30" cy="30" r="28" fill="#667eea" opacity="0.1"/>
                            <path d="M20 30 L28 38 L42 22" stroke="#667eea" stroke-width="3" fill="none"/>
                        </svg>
                    </div>
                    <h3 class="step-title"><?php _e('Submit & Track', 'ck-oneform'); ?></h3>
                    <p class="step-description">
                        <?php _e('Submit your application to all selected colleges at once. Track status in real-time from your dashboard.', 'ck-oneform'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Why Choose OneForm?', 'ck-oneform'); ?></h2>
                <p class="section-subtitle"><?php _e('Everything you need for hassle-free college admissions', 'ck-oneform'); ?></p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3><?php _e('Save Time', 'ck-oneform'); ?></h3>
                    <p><?php _e('Fill one form instead of multiple applications. Save hours of repetitive data entry.', 'ck-oneform'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3><?php _e('Increase Chances', 'ck-oneform'); ?></h3>
                    <p><?php _e('Apply to multiple colleges simultaneously and increase your admission chances.', 'ck-oneform'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3><?php _e('Track Status', 'ck-oneform'); ?></h3>
                    <p><?php _e('Monitor all your applications from one dashboard. Get instant status updates.', 'ck-oneform'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3><?php _e('Free to Use', 'ck-oneform'); ?></h3>
                    <p><?php _e('No registration fees. No hidden charges. Completely free application process.', 'ck-oneform'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3><?php _e('Secure & Safe', 'ck-oneform'); ?></h3>
                    <p><?php _e('Your data is encrypted and secure. We never share your information without consent.', 'ck-oneform'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3><?php _e('Mobile Friendly', 'ck-oneform'); ?></h3>
                    <p><?php _e('Apply from anywhere, anytime. Fully responsive design works on all devices.', 'ck-oneform'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section">
        <div class="container">
            <div class="video-wrapper">
                <div class="video-content">
                    <h2><?php _e('See How OneForm Simplifies College Applications', 'ck-oneform'); ?></h2>
                    <p><?php _e('Watch this 2-minute video to understand the complete process', 'ck-oneform'); ?></p>

                    <div class="video-placeholder">
                        <!-- Replace with actual video embed -->
                        <div class="video-embed">
                            <button class="play-button">
                                <svg width="80" height="80" viewBox="0 0 80 80">
                                    <circle cx="40" cy="40" r="38" fill="white" opacity="0.9"/>
                                    <path d="M32 25 L32 55 L55 40 Z" fill="#667eea"/>
                                </svg>
                            </button>
                            <p class="video-text"><?php _e('Click to play introduction video', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Colleges Section -->
    <section class="popular-colleges-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Top Partner Colleges', 'ck-oneform'); ?></h2>
                <p class="section-subtitle"><?php _e('Apply to India\'s leading educational institutions', 'ck-oneform'); ?></p>
            </div>

            <div class="colleges-carousel">
                <?php
                $top_colleges = get_posts(array(
                    'post_type' => 'ck_college',
                    'posts_per_page' => 12,
                    'meta_key' => '_ck_college_ranking',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC'
                ));

                if ($top_colleges) :
                    foreach ($top_colleges as $college) :
                        $location = get_post_meta($college->ID, '_ck_college_location', true);
                        $type = get_post_meta($college->ID, '_ck_college_type', true);
                        $category = get_post_meta($college->ID, '_ck_college_category', true);
                ?>
                    <div class="college-card-mini">
                        <?php if (has_post_thumbnail($college->ID)) : ?>
                            <div class="college-logo">
                                <?php echo get_the_post_thumbnail($college->ID, 'thumbnail'); ?>
                            </div>
                        <?php else : ?>
                            <div class="college-logo-placeholder">
                                <span><?php echo substr($college->post_title, 0, 1); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="college-info">
                            <h4><?php echo esc_html($college->post_title); ?></h4>
                            <p class="college-location-mini"><?php echo esc_html($location); ?></p>
                            <span class="college-type-badge"><?php echo esc_html(ucfirst($type)); ?></span>
                        </div>
                    </div>
                <?php
                    endforeach;
                else :
                ?>
                    <p class="text-center"><?php _e('Loading colleges...', 'ck-oneform'); ?></p>
                <?php endif; ?>
            </div>

            <div class="text-center" style="margin-top: 40px;">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-primary btn-lg">
                    <?php _e('View All 500+ Colleges', 'ck-oneform'); ?> →
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Student Success Stories', 'ck-oneform'); ?></h2>
                <p class="section-subtitle"><?php _e('Hear from students who got admitted through OneForm', 'ck-oneform'); ?></p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">"</div>
                        <p class="testimonial-text">
                            <?php _e('OneForm made my college application process so easy! I applied to 8 colleges in just 30 minutes. Got admission in 3 of them. Highly recommended!', 'ck-oneform'); ?>
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">RS</div>
                        <div class="author-info">
                            <h4>Rahul Sharma</h4>
                            <p><?php _e('B.Tech CSE, IIT Delhi', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">"</div>
                        <p class="testimonial-text">
                            <?php _e('The dashboard feature is amazing! I could track all my applications in one place. The status updates were instant. Thank you OneForm team!', 'ck-oneform'); ?>
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">PK</div>
                        <div class="author-info">
                            <h4>Priya Kumari</h4>
                            <p><?php _e('MBA, IIM Bangalore', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">"</div>
                        <p class="testimonial-text">
                            <?php _e('Saved me so much time! Instead of filling 10 different forms, I filled just one. Got admission in my dream college. OneForm is a game changer!', 'ck-oneform'); ?>
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">AM</div>
                        <div class="author-info">
                            <h4>Amit Mehta</h4>
                            <p><?php _e('MBBS, AIIMS Delhi', 'ck-oneform'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2><?php _e('Ready to Apply to Your Dream College?', 'ck-oneform'); ?></h2>
                <p><?php _e('Join thousands of students who have simplified their college admissions with OneForm', 'ck-oneform'); ?></p>

                <div class="cta-buttons">
                    <?php if (!is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('student-registration'))); ?>" class="btn btn-cta btn-primary-large">
                            <?php _e('Get Started Free', 'ck-oneform'); ?> →
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>" class="btn btn-cta btn-primary-large">
                            <?php _e('Start Application Now', 'ck-oneform'); ?> →
                        </a>
                    <?php endif; ?>
                </div>

                <p class="cta-note">
                    <i class="icon-check">✓</i> <?php _e('No credit card required', 'ck-oneform'); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <i class="icon-check">✓</i> <?php _e('100% Free', 'ck-oneform'); ?>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <i class="icon-check">✓</i> <?php _e('2 minutes setup', 'ck-oneform'); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Frequently Asked Questions', 'ck-oneform'); ?></h2>
            </div>

            <div class="faq-grid">
                <div class="faq-item">
                    <h3 class="faq-question"><?php _e('Is OneForm really free?', 'ck-oneform'); ?></h3>
                    <p class="faq-answer"><?php _e('Yes! Creating an account and submitting applications through OneForm is completely free. There are no hidden charges.', 'ck-oneform'); ?></p>
                </div>

                <div class="faq-item">
                    <h3 class="faq-question"><?php _e('How many colleges can I apply to?', 'ck-oneform'); ?></h3>
                    <p class="faq-answer"><?php _e('You can apply to up to 10 colleges with a single OneForm application. Choose from 500+ partner colleges.', 'ck-oneform'); ?></p>
                </div>

                <div class="faq-item">
                    <h3 class="faq-question"><?php _e('How long does the application take?', 'ck-oneform'); ?></h3>
                    <p class="faq-answer"><?php _e('The application form takes about 15-20 minutes to complete. You can save and continue later if needed.', 'ck-oneform'); ?></p>
                </div>

                <div class="faq-item">
                    <h3 class="faq-question"><?php _e('When will I get admission results?', 'ck-oneform'); ?></h3>
                    <p class="faq-answer"><?php _e('Admission timelines vary by college. You can track all your application statuses in real-time from your dashboard.', 'ck-oneform'); ?></p>
                </div>
            </div>
        </div>
    </section>

</div>
