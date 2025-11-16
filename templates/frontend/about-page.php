<?php
/**
 * About Us Page Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get statistics
global $wpdb;
$table_applications = $wpdb->prefix . 'ck_oneform_applications';
$table_students = $wpdb->prefix . 'ck_oneform_students';

$total_applications = $wpdb->get_var("SELECT COUNT(*) FROM {$table_applications}");
$total_students = $wpdb->get_var("SELECT COUNT(*) FROM {$table_students}");
$total_colleges = wp_count_posts('ck_college')->publish;
$total_courses = wp_count_posts('ck_course')->publish;
?>

<div class="ck-about-page">
    <!-- Hero Section -->
    <div class="ck-about-hero">
        <h1><?php _e('About CollegeKampus', 'ck-oneform'); ?></h1>
        <p><?php _e('Empowering Students to Achieve Their Educational Dreams', 'ck-oneform'); ?></p>
    </div>

    <div class="ck-about-container">
        <!-- Mission & Vision -->
        <div class="ck-about-section">
            <div class="ck-about-grid">
                <div class="ck-about-card mission">
                    <div class="ck-card-icon">
                        <span class="dashicons dashicons-flag"></span>
                    </div>
                    <h2><?php _e('Our Mission', 'ck-oneform'); ?></h2>
                    <p><?php _e('To simplify the college admission process by providing a unified platform where students can explore, apply, and track their applications to multiple colleges with ease.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-about-card vision">
                    <div class="ck-card-icon">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <h2><?php _e('Our Vision', 'ck-oneform'); ?></h2>
                    <p><?php _e('To become India\'s leading college admission platform, making quality education accessible to every student regardless of their geographical location.', 'ck-oneform'); ?></p>
                </div>
            </div>
        </div>

        <!-- Story Section -->
        <div class="ck-about-section ck-story">
            <h2><?php _e('Our Story', 'ck-oneform'); ?></h2>
            <div class="ck-story-content">
                <p><?php _e('CollegeKampus was founded with a simple yet powerful idea: no student should miss out on their dream college due to lack of information or a complicated application process.', 'ck-oneform'); ?></p>
                <p><?php _e('We noticed that students across India face numerous challenges during the admission season - from finding the right colleges to managing multiple application forms, tracking deadlines, and understanding eligibility criteria. Our OneForm solution eliminates these hassles by allowing students to apply to multiple colleges through a single unified application.', 'ck-oneform'); ?></p>
                <p><?php _e('Today, we partner with hundreds of colleges across India, offering courses in Engineering, Medical, Law, Management, Arts, and more. Our platform has helped thousands of students secure admissions to their preferred institutions.', 'ck-oneform'); ?></p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="ck-about-section ck-stats">
            <h2><?php _e('Our Impact', 'ck-oneform'); ?></h2>
            <div class="ck-stats-grid">
                <div class="ck-stat-card">
                    <div class="ck-stat-number"><?php echo number_format($total_students ?: 1000); ?>+</div>
                    <div class="ck-stat-label"><?php _e('Registered Students', 'ck-oneform'); ?></div>
                </div>
                <div class="ck-stat-card">
                    <div class="ck-stat-number"><?php echo number_format($total_applications ?: 5000); ?>+</div>
                    <div class="ck-stat-label"><?php _e('Applications Processed', 'ck-oneform'); ?></div>
                </div>
                <div class="ck-stat-card">
                    <div class="ck-stat-number"><?php echo number_format($total_colleges ?: 500); ?>+</div>
                    <div class="ck-stat-label"><?php _e('Partner Colleges', 'ck-oneform'); ?></div>
                </div>
                <div class="ck-stat-card">
                    <div class="ck-stat-number"><?php echo number_format($total_courses ?: 200); ?>+</div>
                    <div class="ck-stat-label"><?php _e('Available Courses', 'ck-oneform'); ?></div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="ck-about-section">
            <h2><?php _e('Why Choose CollegeKampus?', 'ck-oneform'); ?></h2>
            <div class="ck-features-grid">
                <div class="ck-feature-card">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <h3><?php _e('One Application', 'ck-oneform'); ?></h3>
                    <p><?php _e('Apply to multiple colleges with a single form, saving time and effort.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-feature-card">
                    <span class="dashicons dashicons-clock"></span>
                    <h3><?php _e('Real-time Tracking', 'ck-oneform'); ?></h3>
                    <p><?php _e('Track your application status in real-time with instant notifications.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-feature-card">
                    <span class="dashicons dashicons-admin-users"></span>
                    <h3><?php _e('Expert Guidance', 'ck-oneform'); ?></h3>
                    <p><?php _e('Get personalized counseling from our education experts.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-feature-card">
                    <span class="dashicons dashicons-shield"></span>
                    <h3><?php _e('Secure & Safe', 'ck-oneform'); ?></h3>
                    <p><?php _e('Your data is protected with enterprise-grade security measures.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-feature-card">
                    <span class="dashicons dashicons-money-alt"></span>
                    <h3><?php _e('Scholarship Support', 'ck-oneform'); ?></h3>
                    <p><?php _e('Discover scholarship opportunities and financial aid options.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-feature-card">
                    <span class="dashicons dashicons-phone"></span>
                    <h3><?php _e('24/7 Support', 'ck-oneform'); ?></h3>
                    <p><?php _e('Our dedicated support team is always ready to assist you.', 'ck-oneform'); ?></p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="ck-about-section ck-team">
            <h2><?php _e('Our Leadership Team', 'ck-oneform'); ?></h2>
            <div class="ck-team-grid">
                <div class="ck-team-member">
                    <div class="ck-member-avatar">
                        <span class="dashicons dashicons-admin-users"></span>
                    </div>
                    <h3>Dr. Rajesh Kumar</h3>
                    <p class="ck-member-role"><?php _e('Founder & CEO', 'ck-oneform'); ?></p>
                    <p class="ck-member-bio"><?php _e('20+ years in education consulting with a vision to democratize college admissions.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-team-member">
                    <div class="ck-member-avatar">
                        <span class="dashicons dashicons-admin-users"></span>
                    </div>
                    <h3>Priya Sharma</h3>
                    <p class="ck-member-role"><?php _e('Chief Operating Officer', 'ck-oneform'); ?></p>
                    <p class="ck-member-bio"><?php _e('Expert in EdTech operations, ensuring seamless student experiences.', 'ck-oneform'); ?></p>
                </div>
                <div class="ck-team-member">
                    <div class="ck-member-avatar">
                        <span class="dashicons dashicons-admin-users"></span>
                    </div>
                    <h3>Amit Patel</h3>
                    <p class="ck-member-role"><?php _e('Head of Technology', 'ck-oneform'); ?></p>
                    <p class="ck-member-bio"><?php _e('Building robust and scalable technology solutions for education.', 'ck-oneform'); ?></p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="ck-about-cta">
            <h2><?php _e('Ready to Start Your Journey?', 'ck-oneform'); ?></h2>
            <p><?php _e('Join thousands of students who have found their perfect college through CollegeKampus.', 'ck-oneform'); ?></p>
            <div class="ck-cta-buttons">
                <a href="<?php echo home_url('/student-registration/'); ?>" class="ck-btn ck-btn-primary">
                    <?php _e('Register Now', 'ck-oneform'); ?>
                </a>
                <a href="<?php echo home_url('/contact-us/'); ?>" class="ck-btn ck-btn-secondary">
                    <?php _e('Contact Us', 'ck-oneform'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.ck-about-page {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.ck-about-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 20px;
    text-align: center;
    border-radius: 10px;
    margin-bottom: 50px;
}

.ck-about-hero h1 {
    margin: 0 0 15px 0;
    font-size: 3em;
}

.ck-about-hero p {
    margin: 0;
    font-size: 1.3em;
    opacity: 0.9;
}

.ck-about-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.ck-about-section {
    margin-bottom: 60px;
}

.ck-about-section h2 {
    text-align: center;
    font-size: 2.2em;
    color: #333;
    margin-bottom: 40px;
}

.ck-about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.ck-about-card {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.ck-about-card:hover {
    transform: translateY(-5px);
}

.ck-about-card.mission {
    border-top: 5px solid #667eea;
}

.ck-about-card.vision {
    border-top: 5px solid #764ba2;
}

.ck-card-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.ck-card-icon .dashicons {
    font-size: 36px;
    width: 36px;
    height: 36px;
}

.ck-about-card h2 {
    margin: 0 0 15px 0;
    font-size: 1.5em;
}

.ck-about-card p {
    margin: 0;
    color: #666;
    line-height: 1.8;
}

.ck-story {
    background: #f8f9fa;
    padding: 50px;
    border-radius: 15px;
}

.ck-story-content {
    max-width: 900px;
    margin: 0 auto;
}

.ck-story-content p {
    color: #555;
    line-height: 1.9;
    font-size: 1.1em;
    margin-bottom: 20px;
}

.ck-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.ck-stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 20px;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s;
}

.ck-stat-card:hover {
    transform: scale(1.05);
}

.ck-stat-number {
    font-size: 2.5em;
    font-weight: 700;
    margin-bottom: 10px;
}

.ck-stat-label {
    font-size: 1em;
    opacity: 0.9;
}

.ck-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.ck-feature-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}

.ck-feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.ck-feature-card .dashicons {
    font-size: 40px;
    width: 40px;
    height: 40px;
    color: #667eea;
    margin-bottom: 15px;
}

.ck-feature-card h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.ck-feature-card p {
    margin: 0;
    color: #666;
    line-height: 1.6;
}

.ck-team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.ck-team-member {
    background: white;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.ck-member-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.ck-member-avatar .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
}

.ck-team-member h3 {
    margin: 0 0 5px 0;
    color: #333;
}

.ck-member-role {
    color: #667eea;
    font-weight: 600;
    margin: 0 0 10px 0;
}

.ck-member-bio {
    color: #666;
    margin: 0;
    line-height: 1.6;
}

.ck-about-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px;
    border-radius: 15px;
    text-align: center;
}

.ck-about-cta h2 {
    color: white;
    margin: 0 0 15px 0;
}

.ck-about-cta p {
    margin: 0 0 30px 0;
    font-size: 1.2em;
    opacity: 0.9;
}

.ck-cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.ck-btn {
    padding: 15px 40px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
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

@media (max-width: 992px) {
    .ck-features-grid,
    .ck-team-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .ck-about-grid,
    .ck-stats-grid {
        grid-template-columns: 1fr;
    }

    .ck-about-hero h1 {
        font-size: 2.2em;
    }

    .ck-story {
        padding: 30px;
    }

    .ck-about-cta {
        padding: 40px 20px;
    }

    .ck-cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .ck-features-grid,
    .ck-team-grid {
        grid-template-columns: 1fr;
    }
}
</style>
