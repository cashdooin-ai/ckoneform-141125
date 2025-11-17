<?php
/**
 * Dynamic Service Page Template
 * Displays content for all mega menu service pages
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

// Get all services for navigation
$all_services = array();
foreach ($services_data as $cat_services) {
    foreach ($cat_services as $s) {
        $all_services[$s['slug']] = $s;
    }
}

// Find current category for breadcrumb
$current_category = '';
$category_names = array(
    'admissions' => 'Admissions',
    'learning' => 'Learning Resources',
    'financial' => 'Financial Services',
    'support' => 'Support',
    'tools' => 'Tools & Resources',
    'career' => 'Career Services',
    'opportunities' => 'Opportunities',
);
foreach ($services_data as $cat_key => $cat_services) {
    foreach ($cat_services as $s) {
        if ($s['slug'] === $service_slug) {
            $current_category = $cat_key;
            break 2;
        }
    }
}
?>

<?php
// Include professional header
include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/site-header.php';
?>

<!-- Breadcrumb Navigation -->
<div class="ck-service-breadcrumb">
    <div class="breadcrumb-container">
        <a href="<?php echo home_url('/oneform-home/'); ?>"><?php _e('Home', 'ck-oneform'); ?></a>
        <span class="separator">/</span>
        <a href="<?php echo home_url('/services/'); ?>"><?php _e('Services', 'ck-oneform'); ?></a>
        <?php if ($current_category && isset($category_names[$current_category])): ?>
            <span class="separator">/</span>
            <span class="category"><?php echo esc_html($category_names[$current_category]); ?></span>
        <?php endif; ?>
        <span class="separator">/</span>
        <span class="current"><?php echo esc_html($service['title']); ?></span>
    </div>
</div>

<div class="ck-service-page">
    <!-- Hero Section -->
    <div class="service-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($service['gradient']); ?>);">
        <div class="service-hero-content">
            <div class="service-icon"><?php echo $service['icon']; ?></div>
            <h1 class="service-title"><?php echo esc_html($service['title']); ?></h1>
            <p class="service-subtitle"><?php echo esc_html($service['subtitle']); ?></p>

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

        <!-- Dynamic Data Section - Shows relevant seeded data based on service type -->
        <?php
        // Determine which data to show based on service slug
        $show_scholarships = in_array($service_slug, array('scholarships-database', 'financial-aid', 'scholarship-finder', 'merit-scholarships'));
        $show_exams = in_array($service_slug, array('exam-calendar', 'entrance-exams', 'exam-prep', 'jee-preparation', 'neet-preparation', 'cat-preparation'));
        $show_careers = in_array($service_slug, array('career-guidance', 'career-paths', 'career-counseling', 'career-assessment', 'job-profiles'));
        $show_loans = in_array($service_slug, array('education-loans', 'financial-aid', 'loan-assistance', 'bank-loans', 'emi-calculator'));
        $show_rankings = in_array($service_slug, array('college-rankings', 'top-colleges', 'nirf-rankings', 'college-comparison', 'best-colleges'));
        $show_jobs = in_array($service_slug, array('placement-prep', 'job-portal', 'internships', 'campus-placements', 'job-opportunities'));

        // Load seeded data if available and relevant
        if (class_exists('CK_OneForm_Data_Seeder')):
        ?>

        <?php if ($show_scholarships):
            $scholarships = CK_OneForm_Data_Seeder::get_scholarships();
            if (!empty($scholarships)):
        ?>
        <section class="service-section dynamic-data-section">
            <h2>🎓 Featured Scholarships</h2>
            <p class="section-intro">Browse through latest scholarship opportunities matching your profile:</p>
            <div class="data-cards-grid">
                <?php foreach (array_slice($scholarships, 0, 6) as $scholarship): ?>
                <div class="data-card scholarship-card">
                    <div class="card-header">
                        <h3><?php echo esc_html($scholarship['name']); ?></h3>
                        <span class="card-badge <?php echo esc_attr($scholarship['category']); ?>"><?php echo esc_html(ucfirst($scholarship['category'])); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="card-info">
                            <span class="info-label">Provider:</span>
                            <span class="info-value"><?php echo esc_html($scholarship['provider']); ?></span>
                        </div>
                        <div class="card-info">
                            <span class="info-label">Amount:</span>
                            <span class="info-value highlight"><?php echo esc_html($scholarship['amount']); ?></span>
                        </div>
                        <div class="card-info">
                            <span class="info-label">Eligibility:</span>
                            <span class="info-value"><?php echo esc_html($scholarship['eligibility']); ?></span>
                        </div>
                        <div class="card-info">
                            <span class="info-label">Deadline:</span>
                            <span class="info-value deadline"><?php echo date('d M Y', strtotime($scholarship['deadline'])); ?></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo home_url('/student-login/'); ?>" class="btn-apply">Apply Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; endif; ?>

        <?php if ($show_exams):
            $exams = CK_OneForm_Data_Seeder::get_exams();
            if (!empty($exams)):
        ?>
        <section class="service-section dynamic-data-section">
            <h2>📅 Upcoming Exam Calendar</h2>
            <p class="section-intro">Important exam dates you need to mark in your calendar:</p>
            <div class="exam-timeline">
                <?php foreach ($exams as $exam): ?>
                <div class="exam-card">
                    <div class="exam-date">
                        <span class="day"><?php echo date('d', strtotime($exam['date'])); ?></span>
                        <span class="month"><?php echo date('M', strtotime($exam['date'])); ?></span>
                        <span class="year"><?php echo date('Y', strtotime($exam['date'])); ?></span>
                    </div>
                    <div class="exam-details">
                        <h3><?php echo esc_html($exam['name']); ?></h3>
                        <div class="exam-meta">
                            <span class="badge category-<?php echo esc_attr($exam['category']); ?>"><?php echo esc_html(ucfirst($exam['category'])); ?></span>
                            <?php if ($exam['date'] !== $exam['end_date']): ?>
                            <span class="exam-range">Until <?php echo date('d M', strtotime($exam['end_date'])); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="exam-info-grid">
                            <div><strong>Registration Deadline:</strong> <?php echo date('d M Y', strtotime($exam['registration_deadline'])); ?></div>
                            <div><strong>Result Date:</strong> <?php echo date('d M Y', strtotime($exam['result_date'])); ?></div>
                            <div><strong>Eligibility:</strong> <?php echo esc_html($exam['eligibility']); ?></div>
                            <div><strong>Exam Fee:</strong> <?php echo esc_html($exam['exam_fee']); ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; endif; ?>

        <?php if ($show_careers):
            $careers = CK_OneForm_Data_Seeder::get_careers();
            if (!empty($careers)):
        ?>
        <section class="service-section dynamic-data-section">
            <h2>💼 Explore Career Paths</h2>
            <p class="section-intro">Discover high-demand career options and their growth potential:</p>
            <div class="careers-grid">
                <?php foreach ($careers as $career): ?>
                <div class="career-card">
                    <div class="career-header">
                        <h3><?php echo esc_html($career['title']); ?></h3>
                        <span class="career-category"><?php echo esc_html($career['category']); ?></span>
                    </div>
                    <div class="career-stats">
                        <div class="stat">
                            <span class="stat-label">Avg Salary</span>
                            <span class="stat-value"><?php echo esc_html($career['avg_salary']); ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Growth</span>
                            <span class="stat-value growth"><?php echo esc_html($career['growth']); ?></span>
                        </div>
                    </div>
                    <div class="career-info">
                        <strong>Education Required:</strong>
                        <p><?php echo esc_html($career['education']); ?></p>
                    </div>
                    <div class="career-skills">
                        <strong>Key Skills:</strong>
                        <div class="skills-tags">
                            <?php foreach ($career['skills'] as $skill): ?>
                            <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="top-companies">
                        <strong>Top Companies:</strong>
                        <p><?php echo esc_html(implode(', ', $career['top_companies'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; endif; ?>

        <?php if ($show_loans):
            $loans = CK_OneForm_Data_Seeder::get_loans();
            if (!empty($loans)):
        ?>
        <section class="service-section dynamic-data-section">
            <h2>💰 Education Loan Options</h2>
            <p class="section-intro">Compare education loans from top banks in India:</p>
            <div class="loans-table-wrapper">
                <table class="loans-table">
                    <thead>
                        <tr>
                            <th>Bank</th>
                            <th>Scheme</th>
                            <th>Max Amount</th>
                            <th>Interest Rate</th>
                            <th>Processing Fee</th>
                            <th>Repayment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><strong><?php echo esc_html($loan['bank']); ?></strong></td>
                            <td><?php echo esc_html($loan['scheme']); ?></td>
                            <td class="highlight"><?php echo esc_html($loan['max_amount']); ?></td>
                            <td><?php echo esc_html($loan['interest_rate']); ?></td>
                            <td><?php echo esc_html($loan['processing_fee']); ?></td>
                            <td><?php echo esc_html($loan['repayment']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php endif; endif; ?>

        <?php if ($show_rankings):
            $rankings = CK_OneForm_Data_Seeder::get_rankings();
            if (!empty($rankings)):
        ?>
        <section class="service-section dynamic-data-section">
            <h2>📊 NIRF College Rankings</h2>
            <p class="section-intro">Latest National Institutional Ranking Framework (NIRF) rankings:</p>

            <!-- Engineering Rankings -->
            <?php if (!empty($rankings['engineering'])): ?>
            <div class="rankings-section">
                <h3>🎓 Top Engineering Colleges</h3>
                <div class="rankings-cards">
                    <?php foreach (array_slice($rankings['engineering'], 0, 5) as $college): ?>
                    <div class="ranking-card">
                        <div class="rank-badge">#<?php echo esc_html($college['rank']); ?></div>
                        <h4><?php echo esc_html($college['name']); ?></h4>
                        <div class="ranking-stats">
                            <div><span>NIRF Score:</span> <strong><?php echo esc_html($college['nirf_score']); ?></strong></div>
                            <div><span>Fees:</span> <strong><?php echo esc_html($college['fees']); ?></strong></div>
                            <div><span>Avg Placement:</span> <strong class="highlight"><?php echo esc_html($college['placement']); ?></strong></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Management Rankings -->
            <?php if (!empty($rankings['management'])): ?>
            <div class="rankings-section">
                <h3>💼 Top Management Colleges</h3>
                <div class="rankings-cards">
                    <?php foreach (array_slice($rankings['management'], 0, 5) as $college): ?>
                    <div class="ranking-card">
                        <div class="rank-badge">#<?php echo esc_html($college['rank']); ?></div>
                        <h4><?php echo esc_html($college['name']); ?></h4>
                        <div class="ranking-stats">
                            <div><span>NIRF Score:</span> <strong><?php echo esc_html($college['nirf_score']); ?></strong></div>
                            <div><span>Fees:</span> <strong><?php echo esc_html($college['fees']); ?></strong></div>
                            <div><span>Avg Placement:</span> <strong class="highlight"><?php echo esc_html($college['placement']); ?></strong></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Medical Rankings -->
            <?php if (!empty($rankings['medical'])): ?>
            <div class="rankings-section">
                <h3>⚕️ Top Medical Colleges</h3>
                <div class="rankings-cards">
                    <?php foreach (array_slice($rankings['medical'], 0, 5) as $college): ?>
                    <div class="ranking-card">
                        <div class="rank-badge">#<?php echo esc_html($college['rank']); ?></div>
                        <h4><?php echo esc_html($college['name']); ?></h4>
                        <div class="ranking-stats">
                            <div><span>NIRF Score:</span> <strong><?php echo esc_html($college['nirf_score']); ?></strong></div>
                            <div><span>Fees:</span> <strong><?php echo esc_html($college['fees']); ?></strong></div>
                            <div><span>Placement:</span> <strong class="highlight"><?php echo esc_html($college['placement']); ?></strong></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>
        <?php endif; endif; ?>

        <?php if ($show_jobs):
            $jobs_data = CK_OneForm_Data_Seeder::get_jobs();
            if (!empty($jobs_data)):
        ?>
        <section class="service-section dynamic-data-section">
            <h2>🏢 Latest Job & Internship Opportunities</h2>
            <p class="section-intro">Fresh opportunities from top companies:</p>

            <!-- Jobs -->
            <?php if (!empty($jobs_data['jobs'])): ?>
            <h3>💼 Recent Job Openings</h3>
            <div class="jobs-grid">
                <?php foreach ($jobs_data['jobs'] as $job): ?>
                <div class="job-card">
                    <div class="job-header">
                        <h4><?php echo esc_html($job['title']); ?></h4>
                        <span class="company"><?php echo esc_html($job['company']); ?></span>
                    </div>
                    <div class="job-meta">
                        <span class="location">📍 <?php echo esc_html($job['location']); ?></span>
                        <span class="experience">⏱️ <?php echo esc_html($job['experience']); ?></span>
                        <span class="salary">💰 <?php echo esc_html($job['salary']); ?></span>
                    </div>
                    <div class="job-skills">
                        <?php foreach ($job['skills'] as $skill): ?>
                        <span class="skill-tag"><?php echo esc_html($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="job-footer">
                        <span class="posted">Posted: <?php echo date('d M Y', strtotime($job['posted'])); ?></span>
                        <a href="<?php echo home_url('/student-login/'); ?>" class="btn-apply-small">Apply</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Internships -->
            <?php if (!empty($jobs_data['internships'])): ?>
            <h3 style="margin-top: 40px;">🎯 Internship Opportunities</h3>
            <div class="jobs-grid">
                <?php foreach ($jobs_data['internships'] as $internship): ?>
                <div class="job-card internship">
                    <div class="job-header">
                        <h4><?php echo esc_html($internship['title']); ?></h4>
                        <span class="company"><?php echo esc_html($internship['company']); ?></span>
                    </div>
                    <div class="job-meta">
                        <span class="location">📍 <?php echo esc_html($internship['location']); ?></span>
                        <span class="duration">⏱️ <?php echo esc_html($internship['duration']); ?></span>
                        <span class="stipend">💰 <?php echo esc_html($internship['stipend']); ?></span>
                    </div>
                    <div class="job-eligibility">
                        <strong>Eligibility:</strong> <?php echo esc_html($internship['eligibility']); ?>
                    </div>
                    <div class="job-footer">
                        <span class="posted">Posted: <?php echo date('d M Y', strtotime($internship['posted'])); ?></span>
                        <a href="<?php echo home_url('/student-login/'); ?>" class="btn-apply-small">Apply</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>
        <?php endif; endif; ?>

        <?php endif; // end class_exists check ?>

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

        <!-- All Services Quick Navigation -->
        <section class="service-section">
            <h2>🌐 Explore All Services</h2>
            <div class="all-services-nav">
                <?php
                $count = 0;
                foreach ($all_services as $slug => $s):
                    if ($slug !== $service_slug && $count < 12):
                ?>
                    <a href="<?php echo home_url('/services/?service=' . esc_attr($slug)); ?>" class="service-quick-link">
                        <span class="quick-icon"><?php echo $s['icon']; ?></span>
                        <span class="quick-title"><?php echo esc_html($s['title']); ?></span>
                    </a>
                <?php
                        $count++;
                    endif;
                endforeach;
                ?>
                <a href="<?php echo home_url('/services/'); ?>" class="service-quick-link view-all">
                    <span class="quick-icon">📋</span>
                    <span class="quick-title"><?php _e('View All Services', 'ck-oneform'); ?></span>
                </a>
            </div>
        </section>
    </div>
</div>

<?php
// Include professional footer
include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/site-footer.php';
?>

<style>
/* Breadcrumb Styles */
.ck-service-breadcrumb {
    background: #f8f9fa;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    font-size: 14px;
    color: #666;
}

.breadcrumb-container a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb-container a:hover {
    text-decoration: underline;
}

.breadcrumb-container .separator {
    margin: 0 10px;
    color: #aaa;
}

.breadcrumb-container .current {
    color: #333;
    font-weight: 600;
}

/* All Services Navigation */
.all-services-nav {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.service-quick-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}

.service-quick-link:hover {
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-2px);
}

.service-quick-link.view-all {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.quick-icon {
    font-size: 1.3rem;
}

.quick-title {
    font-size: 13px;
    font-weight: 500;
}

.ck-service-page {
    width: 100%;
    min-height: 100vh;
}

.service-hero {
    padding: 80px 20px;
    text-align: center;
    color: white;
}

.service-hero-content {
    max-width: 800px;
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

.service-cta {
    margin-top: 30px;
}

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

.btn-large {
    padding: 16px 40px;
    font-size: 1.2rem;
}

.service-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}

.service-section {
    margin-bottom: 60px;
}

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

.feature-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

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

@media (max-width: 768px) {
    .service-title {
        font-size: 2rem;
    }

    .service-subtitle {
        font-size: 1.1rem;
    }

    .features-grid,
    .steps-container,
    .related-services-grid {
        grid-template-columns: 1fr;
    }

    .service-section h2 {
        font-size: 1.5rem;
    }
}

/* Dynamic Data Section Styles */
.dynamic-data-section {
    background: #fafbfc;
    padding: 40px;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.section-intro {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 25px;
}

/* Scholarship Cards */
.data-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.data-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.data-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.data-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.data-card .card-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.card-badge {
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    background: rgba(255,255,255,0.2);
}

.card-badge.merit { background: #ffd700; color: #333; }
.card-badge.need-based { background: #28a745; }
.card-badge.corporate { background: #17a2b8; }
.card-badge.special { background: #fd7e14; }

.card-body {
    padding: 20px;
}

.card-info {
    display: flex;
    margin-bottom: 10px;
    font-size: 14px;
}

.info-label {
    font-weight: 600;
    color: #555;
    min-width: 100px;
}

.info-value {
    color: #333;
}

.info-value.highlight {
    color: #28a745;
    font-weight: 600;
}

.info-value.deadline {
    color: #dc3545;
    font-weight: 600;
}

.card-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    text-align: center;
}

.btn-apply, .btn-apply-small {
    display: inline-block;
    padding: 10px 25px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-apply:hover, .btn-apply-small:hover {
    background: #764ba2;
    color: white;
}

.btn-apply-small {
    padding: 6px 15px;
    font-size: 13px;
}

/* Exam Timeline */
.exam-timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.exam-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    overflow: hidden;
}

.exam-date {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    min-width: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.exam-date .day {
    font-size: 2rem;
    font-weight: 700;
}

.exam-date .month {
    font-size: 1rem;
    text-transform: uppercase;
}

.exam-date .year {
    font-size: 0.9rem;
    opacity: 0.8;
}

.exam-details {
    padding: 20px;
    flex: 1;
}

.exam-details h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.exam-meta {
    margin-bottom: 15px;
}

.exam-meta .badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    margin-right: 10px;
}

.category-engineering { background: #e3f2fd; color: #1976d2; }
.category-medical { background: #ffebee; color: #c62828; }
.category-management { background: #fff3e0; color: #ef6c00; }
.category-general { background: #f3e5f5; color: #7b1fa2; }
.category-postgraduate { background: #e8f5e9; color: #2e7d32; }
.category-law { background: #fce4ec; color: #ad1457; }

.exam-range {
    font-size: 12px;
    color: #666;
}

.exam-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 8px;
    font-size: 13px;
    color: #555;
}

/* Career Cards */
.careers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.career-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 25px;
    transition: transform 0.3s;
}

.career-card:hover {
    transform: translateY(-3px);
}

.career-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.career-header h3 {
    margin: 0;
    color: #333;
    font-size: 1.3rem;
}

.career-category {
    background: #e3f2fd;
    color: #1976d2;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.career-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.career-stats .stat {
    flex: 1;
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.stat-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.stat-value.growth {
    color: #28a745;
}

.career-info, .career-skills, .top-companies {
    margin-bottom: 12px;
}

.career-info p, .top-companies p {
    margin: 5px 0 0 0;
    color: #555;
}

.skills-tags {
    margin-top: 8px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.skill-tag {
    background: #e9ecef;
    color: #495057;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
}

/* Loans Table */
.loans-table-wrapper {
    overflow-x: auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.loans-table {
    width: 100%;
    border-collapse: collapse;
}

.loans-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.loans-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
    color: #333;
}

.loans-table tr:hover {
    background: #f8f9fa;
}

.loans-table td.highlight {
    color: #28a745;
    font-weight: 600;
}

/* Rankings Section */
.rankings-section {
    margin-bottom: 30px;
}

.rankings-section h3 {
    color: #333;
    margin-bottom: 20px;
}

.rankings-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

.ranking-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    position: relative;
    transition: transform 0.3s;
}

.ranking-card:hover {
    transform: translateY(-3px);
}

.rank-badge {
    position: absolute;
    top: -10px;
    right: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 15px;
    font-weight: 700;
    font-size: 14px;
}

.ranking-card h4 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1.1rem;
}

.ranking-stats {
    font-size: 13px;
}

.ranking-stats div {
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
}

.ranking-stats span {
    color: #666;
}

.ranking-stats strong {
    color: #333;
}

.ranking-stats .highlight {
    color: #28a745;
}

/* Jobs Grid */
.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.job-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s;
}

.job-card:hover {
    transform: translateY(-3px);
}

.job-card.internship {
    border-left: 4px solid #17a2b8;
}

.job-header {
    margin-bottom: 15px;
}

.job-header h4 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 1.2rem;
}

.job-header .company {
    color: #667eea;
    font-weight: 600;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 13px;
    color: #666;
}

.job-skills {
    margin-bottom: 15px;
}

.job-eligibility {
    margin-bottom: 15px;
    font-size: 13px;
    color: #555;
}

.job-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.job-footer .posted {
    font-size: 12px;
    color: #999;
}

@media (max-width: 768px) {
    .dynamic-data-section {
        padding: 20px;
    }

    .data-cards-grid,
    .careers-grid,
    .jobs-grid {
        grid-template-columns: 1fr;
    }

    .exam-card {
        flex-direction: column;
    }

    .exam-date {
        flex-direction: row;
        gap: 10px;
    }

    .loans-table {
        font-size: 12px;
    }

    .loans-table th,
    .loans-table td {
        padding: 10px;
    }

    .rankings-cards {
        grid-template-columns: 1fr;
    }
}
</style>
