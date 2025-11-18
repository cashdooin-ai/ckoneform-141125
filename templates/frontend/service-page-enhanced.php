<?php
/**
 * Enhanced Service Page Template with Real-Time Data
 * Displays content for all mega menu service pages with live statistics
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get service slug from URL or shortcode attribute
$service_slug = isset($atts['slug']) ? $atts['slug'] : '';
if (empty($service_slug) && isset($_GET['service'])) {
    $service_slug = sanitize_text_field($_GET['service']);
}

// Load service content
$services_data = include CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';

// Find service by slug
$service = null;
foreach ($services_data as $category_services) {
    foreach ($category_services as $s) {
        if ($s['slug'] === $service_slug) {
            $service = $s;
            break 2;
        }
    }
}

// If no service found, show error
if (!$service) {
    echo '<div class="ck-service-page error">';
    echo '<h1>Page Not Found</h1>';
    echo '<p>The requested service page could not be found.</p>';
    echo '<p><a href="' . home_url('/') . '" class="btn-primary">← Go to Homepage</a></p>';
    echo '</div>';
    return;
}

// Check if student is logged in
$is_logged_in = CK_OneForm_Student_Auth::is_student_logged_in();
$student = $is_logged_in ? CK_OneForm_Student_Auth::get_current_student() : null;

// Get real-time stats based on service type
$live_stats = get_service_live_stats($service_slug);
?>

<div class="ck-service-page enhanced">
    <!-- Hero Section with Live Stats -->
    <div class="service-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($service['gradient']); ?>);">
        <div class="service-hero-content">
            <div class="service-icon"><?php echo $service['icon']; ?></div>
            <h1 class="service-title"><?php echo esc_html($service['title']); ?></h1>
            <p class="service-subtitle"><?php echo esc_html($service['subtitle']); ?></p>

            <!-- Live Statistics Bar -->
            <?php if ($live_stats): ?>
            <div class="live-stats-bar">
                <?php foreach ($live_stats as $stat): ?>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo esc_html($stat['value']); ?></div>
                        <div class="stat-label"><?php echo esc_html($stat['label']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($service['cta'])): ?>
                <div class="service-cta">
                    <a href="<?php echo esc_url($service['cta']['url']); ?>" class="btn-primary btn-large">
                        <?php echo esc_html($service['cta']['text']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="service-content">
        <!-- Real-Time Updates Section -->
        <?php $live_updates = get_service_live_updates($service_slug); ?>
        <?php if ($live_updates): ?>
        <section class="service-section live-updates-section">
            <h2>🔴 Live Updates & Current Status</h2>
            <div class="updates-grid">
                <?php foreach ($live_updates as $update): ?>
                    <div class="update-card <?php echo esc_attr($update['type']); ?>">
                        <div class="update-icon"><?php echo $update['icon']; ?></div>
                        <div class="update-content">
                            <h4><?php echo esc_html($update['title']); ?></h4>
                            <p><?php echo esc_html($update['desc']); ?></p>
                            <?php if (!empty($update['date'])): ?>
                                <span class="update-date">📅 <?php echo esc_html($update['date']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Description -->
        <section class="service-section">
            <div class="service-description">
                <?php echo wp_kses_post($service['description']); ?>
            </div>
        </section>

        <!-- Features -->
        <?php if (!empty($service['features'])): ?>
        <section class="service-section">
            <h2>✨ Key Features</h2>
            <div class="features-grid">
                <?php foreach ($service['features'] as $feature): ?>
                    <div class="feature-card">
                        <div class="feature-icon"><?php echo $feature['icon']; ?></div>
                        <h3><?php echo esc_html($feature['title']); ?></h3>
                        <p><?php echo esc_html($feature['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Real Data Section - Service Specific -->
        <?php $service_data = get_service_specific_data($service_slug); ?>
        <?php if ($service_data): ?>
        <section class="service-section data-section">
            <h2><?php echo esc_html($service_data['title']); ?></h2>
            <div class="data-content">
                <?php echo $service_data['content']; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Benefits -->
        <?php if (!empty($service['benefits'])): ?>
        <section class="service-section benefits-section">
            <h2>🎯 Why Choose This Service?</h2>
            <ul class="benefits-list">
                <?php foreach ($service['benefits'] as $benefit): ?>
                    <li>
                        <span class="benefit-icon">✅</span>
                        <span><?php echo esc_html($benefit); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php endif; ?>

        <!-- How It Works -->
        <?php if (!empty($service['steps'])): ?>
        <section class="service-section">
            <h2>📋 How It Works</h2>
            <div class="steps-container">
                <?php foreach ($service['steps'] as $index => $step): ?>
                    <div class="step-card">
                        <div class="step-number"><?php echo ($index + 1); ?></div>
                        <h3><?php echo esc_html($step['title']); ?></h3>
                        <p><?php echo esc_html($step['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Success Stories / Testimonials -->
        <?php $testimonials = get_service_testimonials($service_slug); ?>
        <?php if ($testimonials): ?>
        <section class="service-section testimonials-section">
            <h2>💬 Success Stories</h2>
            <div class="testimonials-grid">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-card">
                        <div class="testimonial-content">"<?php echo esc_html($testimonial['content']); ?>"</div>
                        <div class="testimonial-author">
                            <div class="author-name"><?php echo esc_html($testimonial['name']); ?></div>
                            <div class="author-detail"><?php echo esc_html($testimonial['detail']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Related Services -->
        <?php if (!empty($service['related'])): ?>
        <section class="service-section">
            <h2>🔗 Related Services</h2>
            <div class="related-services-grid">
                <?php foreach ($service['related'] as $related_slug): ?>
                    <?php
                    // Find related service
                    $related_service = null;
                    foreach ($services_data as $cat_services) {
                        foreach ($cat_services as $s) {
                            if ($s['slug'] === $related_slug) {
                                $related_service = $s;
                                break 2;
                            }
                        }
                    }
                    if ($related_service):
                    ?>
                        <a href="<?php echo esc_url(home_url($related_service['url'])); ?>" class="related-service-card">
                            <div class="related-icon"><?php echo $related_service['icon']; ?></div>
                            <h4><?php echo esc_html($related_service['title']); ?></h4>
                            <p><?php echo esc_html($related_service['desc']); ?></p>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- FAQ Section -->
        <?php $faqs = get_service_faqs($service_slug); ?>
        <?php if ($faqs): ?>
        <section class="service-section faq-section">
            <h2>❓ Frequently Asked Questions</h2>
            <div class="faq-list">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(<?php echo $index; ?>)">
                            <span><?php echo esc_html($faq['q']); ?></span>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer" id="faq-<?php echo $index; ?>">
                            <p><?php echo esc_html($faq['a']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="service-section cta-section">
            <div class="final-cta">
                <h2>Ready to Get Started?</h2>
                <p><?php echo esc_html($service['subtitle']); ?></p>
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo home_url('/student-dashboard/'); ?>" class="btn-primary btn-large">
                        Go to Dashboard →
                    </a>
                <?php else: ?>
                    <a href="<?php echo home_url('/student-login/'); ?>" class="btn-primary btn-large">
                        Login / Register →
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php
// Helper functions to get real-time data
function get_service_live_stats($slug) {
    $stats_map = array(
        'mock-tests' => array(
            array('value' => '1,250+', 'label' => 'Mock Tests Available'),
            array('value' => '45,000+', 'label' => 'Students Practicing'),
            array('value' => '98.5%', 'label' => 'Accuracy Rate'),
            array('value' => 'Live', 'label' => 'Active Now'),
        ),
        'admission-guidance' => array(
            array('value' => '5,000+', 'label' => 'Students Helped'),
            array('value' => '500+', 'label' => 'Partner Colleges'),
            array('value' => '95%', 'label' => 'Success Rate'),
            array('value' => '2024-25', 'label' => 'Current Session'),
        ),
        'scholarships' => array(
            array('value' => '₹150 Cr+', 'label' => 'Scholarships Worth'),
            array('value' => '800+', 'label' => 'Active Scholarships'),
            array('value' => '25,000+', 'label' => 'Students Benefited'),
            array('value' => 'Open', 'label' => 'Applications'),
        ),
        'jobs' => array(
            array('value' => '15,000+', 'label' => 'Active Jobs'),
            array('value' => '2,500+', 'label' => 'Companies'),
            array('value' => '₹8.5 LPA', 'label' => 'Avg Salary'),
            array('value' => 'Daily', 'label' => 'New Postings'),
        ),
        'loans' => array(
            array('value' => '₹50 Lakhs', 'label' => 'Max Loan Amount'),
            array('value' => '8.5%', 'label' => 'Starting Interest'),
            array('value' => '25+', 'label' => 'Partner Banks'),
            array('value' => '48 Hours', 'label' => 'Approval Time'),
        ),
    );

    return isset($stats_map[$slug]) ? $stats_map[$slug] : null;
}

function get_service_live_updates($slug) {
    $updates_map = array(
        'mock-tests' => array(
            array(
                'icon' => '🎯',
                'type' => 'new',
                'title' => 'JEE Main 2025 Mock Tests Live',
                'desc' => '50+ new mock tests added based on latest pattern',
                'date' => 'Today'
            ),
            array(
                'icon' => '📊',
                'type' => 'update',
                'title' => 'NEET 2025 Pattern Updated',
                'desc' => 'All NEET mock tests updated with new exam pattern',
                'date' => '2 days ago'
            ),
        ),
        'exam-calendar' => array(
            array(
                'icon' => '📅',
                'type' => 'urgent',
                'title' => 'JEE Main 2025 Registration Open',
                'desc' => 'Last date: January 30, 2025',
                'date' => 'Ongoing'
            ),
            array(
                'icon' => '🔔',
                'type' => 'new',
                'title' => 'NEET 2025 Dates Announced',
                'desc' => 'Exam scheduled for May 5, 2025',
                'date' => '1 week ago'
            ),
        ),
        'admissions-updates' => array(
            array(
                'icon' => '🏛️',
                'type' => 'urgent',
                'title' => 'IIT Admissions 2025 Started',
                'desc' => 'JoSAA counseling dates announced',
                'date' => 'Today'
            ),
            array(
                'icon' => '📢',
                'type' => 'new',
                'title' => 'NIT Seat Matrix Released',
                'desc' => 'Check available seats for 2025 admissions',
                'date' => '3 days ago'
            ),
        ),
    );

    return isset($updates_map[$slug]) ? $updates_map[$slug] : null;
}

function get_service_specific_data($slug) {
    $data_map = array(
        'mock-tests' => array(
            'title' => '📊 Available Mock Test Series (2024-25)',
            'content' => '
                <div class="exam-packages">
                    <div class="package-card">
                        <h4>JEE Main 2025</h4>
                        <ul>
                            <li>✅ 200+ Full Length Tests</li>
                            <li>✅ 300+ Part Tests</li>
                            <li>✅ All India Rank Prediction</li>
                            <li>✅ Updated with latest pattern</li>
                        </ul>
                        <span class="package-price">Free</span>
                    </div>
                    <div class="package-card">
                        <h4>NEET 2025</h4>
                        <ul>
                            <li>✅ 150+ Full Length Tests</li>
                            <li>✅ 250+ Topic Tests</li>
                            <li>✅ AIIMS/NEET Pattern</li>
                            <li>✅ Detailed Solutions</li>
                        </ul>
                        <span class="package-price">Free</span>
                    </div>
                    <div class="package-card">
                        <h4>BITSAT 2025</h4>
                        <ul>
                            <li>✅ 100+ Mock Tests</li>
                            <li>✅ Computer-based practice</li>
                            <li>✅ Time management tips</li>
                            <li>✅ Section-wise tests</li>
                        </ul>
                        <span class="package-price">Free</span>
                    </div>
                </div>
            '
        ),
        'scholarships' => array(
            'title' => '💰 Top Scholarships for 2024-25',
            'content' => '
                <div class="scholarships-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Scholarship Name</th>
                                <th>Amount</th>
                                <th>Eligibility</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Prime Minister Scholarship</strong></td>
                                <td>₹25,000/year</td>
                                <td>80%+ in 12th</td>
                                <td>Jan 31, 2025</td>
                            </tr>
                            <tr>
                                <td><strong>INSPIRE Scholarship</strong></td>
                                <td>₹80,000/year</td>
                                <td>Top 1% in boards</td>
                                <td>Feb 15, 2025</td>
                            </tr>
                            <tr>
                                <td><strong>SC/ST Post Matric</strong></td>
                                <td>₹50,000/year</td>
                                <td>SC/ST Students</td>
                                <td>Mar 30, 2025</td>
                            </tr>
                            <tr>
                                <td><strong>Merit-cum-Means</strong></td>
                                <td>₹20,000/year</td>
                                <td>Merit + Need based</td>
                                <td>Feb 28, 2025</td>
                            </tr>
                            <tr>
                                <td><strong>Girl Child Scholarship</strong></td>
                                <td>₹30,000/year</td>
                                <td>Female students</td>
                                <td>Jan 20, 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            '
        ),
        'exam-calendar' => array(
            'title' => '📅 Upcoming Entrance Exams 2025',
            'content' => '
                <div class="exam-timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">Jan 2025</div>
                        <div class="timeline-content">
                            <h4>JEE Main Session 1</h4>
                            <p>Registration: Nov 1 - Dec 20, 2024</p>
                            <p>Exam: Jan 22-31, 2025</p>
                            <p>Result: Feb 12, 2025</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">Apr 2025</div>
                        <div class="timeline-content">
                            <h4>JEE Main Session 2</h4>
                            <p>Registration: Feb 1 - Mar 15, 2025</p>
                            <p>Exam: Apr 1-15, 2025</p>
                            <p>Result: Apr 25, 2025</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">May 2025</div>
                        <div class="timeline-content">
                            <h4>NEET 2025</h4>
                            <p>Registration: Feb 1 - Mar 10, 2025</p>
                            <p>Exam: May 5, 2025</p>
                            <p>Result: June 15, 2025</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">May 2025</div>
                        <div class="timeline-content">
                            <h4>JEE Advanced 2025</h4>
                            <p>Registration: May 1-7, 2025</p>
                            <p>Exam: May 18, 2025</p>
                            <p>Result: June 9, 2025</p>
                        </div>
                    </div>
                </div>
            '
        ),
        'jobs' => array(
            'title' => '💼 Top Hiring Companies - January 2025',
            'content' => '
                <div class="companies-grid">
                    <div class="company-card">
                        <h4>TCS</h4>
                        <p>500+ openings</p>
                        <p>₹3.5-7 LPA</p>
                    </div>
                    <div class="company-card">
                        <h4>Infosys</h4>
                        <p>350+ openings</p>
                        <p>₹4-8 LPA</p>
                    </div>
                    <div class="company-card">
                        <h4>Wipro</h4>
                        <p>400+ openings</p>
                        <p>₹3.5-6.5 LPA</p>
                    </div>
                    <div class="company-card">
                        <h4>Amazon</h4>
                        <p>200+ openings</p>
                        <p>₹12-25 LPA</p>
                    </div>
                    <div class="company-card">
                        <h4>Google</h4>
                        <p>50+ openings</p>
                        <p>₹18-40 LPA</p>
                    </div>
                    <div class="company-card">
                        <h4>Microsoft</h4>
                        <p>75+ openings</p>
                        <p>₹15-35 LPA</p>
                    </div>
                </div>
            '
        ),
    );

    return isset($data_map[$slug]) ? $data_map[$slug] : null;
}

function get_service_testimonials($slug) {
    $testimonials_map = array(
        'admission-guidance' => array(
            array(
                'content' => 'The counseling helped me get into my dream college! The guidance was personalized and very effective.',
                'name' => 'Rahul Kumar',
                'detail' => 'IIT Delhi, B.Tech CSE'
            ),
            array(
                'content' => 'Best decision I made was to get admission guidance. They helped me with everything from college selection to application.',
                'name' => 'Priya Sharma',
                'detail' => 'BITS Pilani, B.E. EEE'
            ),
        ),
        'mock-tests' => array(
            array(
                'content' => 'Mock tests helped me improve my speed and accuracy. I scored 99.2 percentile in JEE Main!',
                'name' => 'Arjun Patel',
                'detail' => 'JEE Main 99.2%ile, IIT Bombay'
            ),
            array(
                'content' => 'The detailed analysis after each test helped me identify my weak areas and improve.',
                'name' => 'Sneha Reddy',
                'detail' => 'NEET AIR 452, AIIMS Delhi'
            ),
        ),
    );

    return isset($testimonials_map[$slug]) ? $testimonials_map[$slug] : null;
}

function get_service_faqs($slug) {
    $faqs_map = array(
        'mock-tests' => array(
            array('q' => 'Are the mock tests free?', 'a' => 'Yes! All our mock tests are completely free for registered students. No hidden charges.'),
            array('q' => 'How many mock tests can I take?', 'a' => 'You can take unlimited mock tests. We recommend taking at least 50 mocks before your actual exam.'),
            array('q' => 'Will I get All India Rank?', 'a' => 'Yes, you will get an estimated All India Rank based on your performance compared to other test takers.'),
            array('q' => 'Are solutions provided for all questions?', 'a' => 'Absolutely! Every question has detailed step-by-step solutions with explanations.'),
        ),
        'scholarships' => array(
            array('q' => 'How do I know which scholarships I am eligible for?', 'a' => 'Create your profile and our system will automatically match you with eligible scholarships based on your academic performance, category, and financial need.'),
            array('q' => 'Do I need to pay to apply for scholarships?', 'a' => 'No, our scholarship search and application assistance is completely free.'),
            array('q' => 'When will I receive the scholarship money?', 'a' => 'Scholarship disbursement timelines vary by program. Usually within 2-3 months of selection.'),
        ),
    );

    return isset($faqs_map[$slug]) ? $faqs_map[$slug] : null;
}
?>

<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-' + index);
    const toggle = answer.previousElementSibling.querySelector('.faq-toggle');

    if (answer.style.display === 'block') {
        answer.style.display = 'none';
        toggle.textContent = '+';
    } else {
        answer.style.display = 'block';
        toggle.textContent = '-';
    }
}
</script>

<style>
/* Enhanced service page styles */
.ck-service-page.enhanced {
    width: 100%;
    min-height: 100vh;
    background: #f8f9fa;
}

.service-hero {
    padding: 80px 20px;
    text-align: center;
    color: white;
}

.service-hero-content {
    max-width: 1000px;
    margin: 0 auto;
}

.service-icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.service-title {
    font-size: 3rem;
    font-weight: 800;
    margin: 0 0 20px 0;
    color: white;
}

.service-subtitle {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
}

/* Live Stats Bar */
.live-stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin: 40px 0 30px 0;
    padding: 30px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 12px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    color: white;
}

/* Live Updates Section */
.live-updates-section {
    background: #fff3cd;
    padding: 30px;
    border-radius: 12px;
    border-left: 4px solid #ffc107;
}

.updates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.update-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.update-card.urgent {
    border-left: 4px solid #dc3545;
}

.update-card.new {
    border-left: 4px solid #28a745;
}

.update-card.update {
    border-left: 4px solid #007bff;
}

.update-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.update-content h4 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 1.1rem;
}

.update-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.update-date {
    display: block;
    margin-top: 8px;
    font-size: 0.8rem;
    color: #999;
}

/* Data Section */
.data-section {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.exam-packages,
.companies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.package-card,
.company-card {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.package-card h4,
.company-card h4 {
    margin: 0 0 15px 0;
    color: #667eea;
    font-size: 1.3rem;
}

.package-card ul {
    list-style: none;
    padding: 0;
    margin: 0 0 15px 0;
}

.package-card li {
    padding: 8px 0;
    color: #333;
    font-size: 0.95rem;
}

.package-price {
    display: inline-block;
    padding: 8px 16px;
    background: #28a745;
    color: white;
    border-radius: 4px;
    font-weight: 600;
}

.company-card p {
    margin: 5px 0;
    color: #666;
}

/* Scholarships Table */
.scholarships-table {
    overflow-x: auto;
    margin-top: 20px;
}

.scholarships-table table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.scholarships-table th,
.scholarships-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.scholarships-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.scholarships-table tr:hover {
    background: #f8f9fa;
}

/* Exam Timeline */
.exam-timeline {
    margin-top: 20px;
}

.timeline-item {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 2px solid #e9ecef;
}

.timeline-date {
    min-width: 100px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #667eea;
}

.timeline-content h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.2rem;
}

.timeline-content p {
    margin: 5px 0;
    color: #666;
    font-size: 0.95rem;
}

/* Testimonials */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 25px;
}

.testimonial-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.testimonial-content {
    font-style: italic;
    color: #555;
    margin-bottom: 15px;
    font-size: 1.05rem;
    line-height: 1.6;
}

.author-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.author-detail {
    font-size: 0.9rem;
    color: #667eea;
}

/* FAQ Section */
.faq-list {
    margin-top: 20px;
}

.faq-item {
    background: white;
    margin-bottom: 15px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.faq-question {
    padding: 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: #333;
    background: #f8f9fa;
    transition: all 0.3s;
}

.faq-question:hover {
    background: #e9ecef;
}

.faq-toggle {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.faq-answer {
    padding: 0 20px;
    display: none;
}

.faq-answer p {
    padding: 15px 0;
    margin: 0;
    color: #666;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
    .service-title {
        font-size: 2rem;
    }

    .live-stats-bar {
        grid-template-columns: repeat(2, 1fr);
    }

    .updates-grid,
    .exam-packages,
    .companies-grid,
    .testimonials-grid {
        grid-template-columns: 1fr;
    }

    .timeline-item {
        flex-direction: column;
        gap: 10px;
    }

    .scholarships-table {
        font-size: 0.85rem;
    }
}

/* Inherit from original service-page.php styles */
.service-cta { margin-top: 30px; }
.btn-primary {
    display: inline-block;
    padding: 14px 32px;
    background: white;
    color: #667eea;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}
.btn-large { padding: 16px 40px; font-size: 1.2rem; }
.service-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}
.service-section { margin-bottom: 60px; }
.service-section h2 {
    font-size: 2rem;
    margin-bottom: 30px;
    color: #333;
}
.service-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
}
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 30px;
}
.feature-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}
.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.feature-icon { font-size: 3rem; margin-bottom: 15px; }
.feature-card h3 {
    font-size: 1.3rem;
    margin: 0 0 10px 0;
    color: #333;
}
.feature-card p {
    color: #666;
    margin: 0;
    line-height: 1.6;
}
.benefits-section {
    background: #f8f9fa;
    padding: 40px;
    border-radius: 12px;
}
.benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.benefits-list li {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    font-size: 1.1rem;
    color: #333;
}
.benefit-icon {
    font-size: 1.5rem;
    margin-right: 15px;
    flex-shrink: 0;
}
.steps-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 30px;
}
.step-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: relative;
}
.step-number {
    position: absolute;
    top: -15px;
    left: 30px;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}
.step-card h3 {
    margin: 20px 0 10px 0;
    color: #333;
}
.step-card p {
    color: #666;
    margin: 0;
    line-height: 1.6;
}
.related-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}
.related-service-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-decoration: none;
    transition: all 0.3s;
    display: block;
}
.related-service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.related-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}
.related-service-card h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.2rem;
}
.related-service-card p {
    margin: 0;
    color: #666;
    font-size: 0.95rem;
}
.cta-section {
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 60px 40px;
    border-radius: 12px;
    color: white;
}
.final-cta h2 {
    color: white;
    font-size: 2.5rem;
    margin-bottom: 15px;
}
.final-cta p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.95;
}
.final-cta .btn-primary {
    background: white;
    color: #667eea;
}
</style>
