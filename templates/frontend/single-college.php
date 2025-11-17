<?php
/**
 * Single College Detail Page Template
 * Comprehensive college information display with multiple sections
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get college ID
$college_id = isset($atts['id']) ? intval($atts['id']) : 0;

// Try to get from URL if not provided
if (!$college_id && isset($_GET['college_id'])) {
    $college_id = intval($_GET['college_id']);
}

// Try to get from current post
if (!$college_id && get_post_type() === 'ck_college') {
    $college_id = get_the_ID();
}

if (!$college_id) {
    echo '<div class="ck-college-error"><p>College not found. Please select a valid college.</p></div>';
    return;
}

$college = get_post($college_id);
if (!$college || $college->post_type !== 'ck_college') {
    echo '<div class="ck-college-error"><p>Invalid college selected.</p></div>';
    return;
}

// Get college meta data
$short_name = get_post_meta($college_id, '_ck_short_name', true);
$established = get_post_meta($college_id, '_ck_established', true);
$ownership = get_post_meta($college_id, '_ck_ownership', true);
$accreditation = get_post_meta($college_id, '_ck_accreditation', true);
$nirf_rank = get_post_meta($college_id, '_ck_nirf_rank', true);
$fees_range = get_post_meta($college_id, '_ck_fees_range', true);
$avg_placement = get_post_meta($college_id, '_ck_avg_placement', true);
$highest_placement = get_post_meta($college_id, '_ck_highest_placement', true);
$courses_offered = get_post_meta($college_id, '_ck_courses_offered', true);
$website = get_post_meta($college_id, '_ck_website', true);
$intake = get_post_meta($college_id, '_ck_intake', true);
$campus_size = get_post_meta($college_id, '_ck_campus_size', true);

// Get taxonomies
$college_types = wp_get_post_terms($college_id, 'college_type', array('fields' => 'names'));
$college_states = wp_get_post_terms($college_id, 'college_state', array('fields' => 'names'));
$college_cities = wp_get_post_terms($college_id, 'college_city', array('fields' => 'names'));

$college_type = !empty($college_types) ? $college_types[0] : 'College';
$state = !empty($college_states) ? $college_states[0] : '';
$city = !empty($college_cities) ? $college_cities[0] : '';

// Include header if available
if (file_exists(CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/site-header.php')) {
    include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/site-header.php';
}
?>

<!-- Breadcrumb -->
<div class="college-breadcrumb">
    <div class="breadcrumb-inner">
        <a href="<?php echo home_url('/'); ?>">Home</a>
        <span class="sep">/</span>
        <a href="<?php echo home_url('/colleges/'); ?>">Colleges</a>
        <span class="sep">/</span>
        <span class="current"><?php echo esc_html($short_name ?: $college->post_title); ?></span>
    </div>
</div>

<div class="ck-college-detail-wrapper">
<div class="ck-college-detail">
    <!-- Hero Section -->
    <section class="college-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="college-badges">
                <span class="badge type"><?php echo esc_html($college_type); ?></span>
                <?php if ($nirf_rank): ?>
                <span class="badge rank">NIRF Rank #<?php echo esc_html($nirf_rank); ?></span>
                <?php endif; ?>
                <span class="badge ownership"><?php echo esc_html($ownership ?: 'Institution'); ?></span>
            </div>
            <h1><?php echo esc_html($college->post_title); ?></h1>
            <?php if ($short_name): ?>
            <p class="short-name"><?php echo esc_html($short_name); ?></p>
            <?php endif; ?>
            <p class="location">
                <span class="icon">📍</span>
                <?php echo esc_html($city); ?><?php echo $city && $state ? ', ' : ''; ?><?php echo esc_html($state); ?>
            </p>
            <div class="hero-actions">
                <a href="#apply-section" class="btn-primary">Apply Now</a>
                <a href="<?php echo esc_url($website ?: '#'); ?>" target="_blank" class="btn-secondary">Visit Website</a>
                <button class="btn-outline" onclick="window.print();">Download Brochure</button>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon">🏛️</div>
            <div class="stat-value"><?php echo esc_html($established ?: 'N/A'); ?></div>
            <div class="stat-label">Established</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-value">#<?php echo esc_html($nirf_rank ?: 'N/A'); ?></div>
            <div class="stat-label">NIRF Rank</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value"><?php echo esc_html($fees_range ?: 'N/A'); ?></div>
            <div class="stat-label">Fees Range</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💼</div>
            <div class="stat-value"><?php echo esc_html($avg_placement ?: 'N/A'); ?></div>
            <div class="stat-label">Avg. Placement</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🎓</div>
            <div class="stat-value"><?php echo esc_html($intake ?: 'N/A'); ?></div>
            <div class="stat-label">Total Intake</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏆</div>
            <div class="stat-value"><?php echo esc_html($accreditation ?: 'N/A'); ?></div>
            <div class="stat-label">Accreditation</div>
        </div>
    </section>

    <!-- Navigation Tabs with Progress -->
    <div class="college-tabs-wrapper">
        <div class="scroll-progress-bar">
            <div class="scroll-progress-fill" id="scroll-progress"></div>
        </div>
        <nav class="college-tabs" id="college-tabs">
            <a href="#overview" class="tab active">Overview</a>
            <a href="#highlights" class="tab">Highlights</a>
            <a href="#courses" class="tab">Courses & Fees</a>
            <a href="#placements" class="tab">Placements</a>
            <a href="#scholarships" class="tab">Scholarships</a>
            <a href="#facilities" class="tab">Facilities</a>
            <a href="#gallery" class="tab">Gallery</a>
            <a href="#ranking" class="tab">Ranking</a>
            <a href="#contact" class="tab">Contact</a>
        </nav>
    </div>

    <div class="college-content">
        <!-- Overview Section -->
        <section id="overview" class="content-section">
            <h2>🏛️ Overview</h2>
            <div class="overview-content">
                <?php echo wpautop($college->post_content); ?>

                <div class="overview-details">
                    <div class="detail-item">
                        <strong>Full Name:</strong>
                        <span><?php echo esc_html($college->post_title); ?></span>
                    </div>
                    <?php if ($short_name): ?>
                    <div class="detail-item">
                        <strong>Short Name:</strong>
                        <span><?php echo esc_html($short_name); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="detail-item">
                        <strong>Type:</strong>
                        <span><?php echo esc_html($college_type); ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Ownership:</strong>
                        <span><?php echo esc_html($ownership ?: 'N/A'); ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Established:</strong>
                        <span><?php echo esc_html($established ?: 'N/A'); ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Location:</strong>
                        <span><?php echo esc_html($city . ', ' . $state); ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Campus Size:</strong>
                        <span><?php echo esc_html($campus_size ?: 'N/A'); ?></span>
                    </div>
                    <div class="detail-item">
                        <strong>Accreditation:</strong>
                        <span><?php echo esc_html($accreditation ?: 'N/A'); ?></span>
                    </div>
                    <?php if ($website): ?>
                    <div class="detail-item">
                        <strong>Official Website:</strong>
                        <span><a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Highlights Section -->
        <section id="highlights" class="content-section">
            <h2>✨ Highlights</h2>
            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-icon">🏆</div>
                    <h4>NIRF Ranking</h4>
                    <p>Ranked #<?php echo esc_html($nirf_rank ?: 'N/A'); ?> in India</p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">📚</div>
                    <h4>Courses Offered</h4>
                    <p><?php echo esc_html($courses_offered ?: 'Multiple Programs'); ?></p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">💼</div>
                    <h4>Placement Rate</h4>
                    <p>Average Package: <?php echo esc_html($avg_placement ?: 'N/A'); ?></p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">🎯</div>
                    <h4>Highest Package</h4>
                    <p><?php echo esc_html($highest_placement ?: 'N/A'); ?></p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">🏫</div>
                    <h4>Campus Size</h4>
                    <p><?php echo esc_html($campus_size ?: 'N/A'); ?></p>
                </div>
                <div class="highlight-card">
                    <div class="highlight-icon">👥</div>
                    <h4>Student Intake</h4>
                    <p><?php echo esc_html($intake ?: 'N/A'); ?> students/year</p>
                </div>
            </div>
        </section>

        <!-- Courses & Fees Section -->
        <section id="courses" class="content-section">
            <h2>📚 Courses, Fees & Eligibility</h2>
            <div class="courses-table-wrapper">
                <table class="courses-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Duration</th>
                            <th>Fees (Per Year)</th>
                            <th>Eligibility</th>
                            <th>Seats</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $courses_list = explode(', ', $courses_offered);
                        $course_details = array(
                            'B.Tech' => array('duration' => '4 Years', 'eligibility' => '10+2 with PCM, JEE Main', 'seats' => '60-120'),
                            'M.Tech' => array('duration' => '2 Years', 'eligibility' => 'B.Tech/BE, GATE', 'seats' => '20-40'),
                            'PhD' => array('duration' => '3-5 Years', 'eligibility' => 'M.Tech/ME, Written Test', 'seats' => '10-20'),
                            'MBA' => array('duration' => '2 Years', 'eligibility' => 'Graduation, CAT/GMAT', 'seats' => '60-120'),
                            'MSc' => array('duration' => '2 Years', 'eligibility' => 'BSc, JAM/Written Test', 'seats' => '30-60'),
                            'MCA' => array('duration' => '2 Years', 'eligibility' => 'BCA/Graduation with Maths', 'seats' => '30-60'),
                            'MBBS' => array('duration' => '5.5 Years', 'eligibility' => '10+2 with PCB, NEET', 'seats' => '100-250'),
                            'MD' => array('duration' => '3 Years', 'eligibility' => 'MBBS, NEET PG', 'seats' => '20-50'),
                            'MS' => array('duration' => '3 Years', 'eligibility' => 'MBBS, NEET PG', 'seats' => '20-50'),
                            'PGDM' => array('duration' => '2 Years', 'eligibility' => 'Graduation, CAT/XAT', 'seats' => '120-240'),
                        );

                        foreach ($courses_list as $course):
                            $course = trim($course);
                            $details = isset($course_details[$course]) ? $course_details[$course] : array('duration' => '2-4 Years', 'eligibility' => 'As per course requirements', 'seats' => 'Variable');
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($course); ?></strong></td>
                            <td><?php echo esc_html($details['duration']); ?></td>
                            <td class="fees"><?php echo esc_html($fees_range ?: 'Contact College'); ?></td>
                            <td><?php echo esc_html($details['eligibility']); ?></td>
                            <td><?php echo esc_html($details['seats']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Placements Section -->
        <section id="placements" class="content-section">
            <h2>💼 Placements</h2>
            <div class="placement-stats">
                <div class="placement-stat">
                    <div class="stat-number"><?php echo esc_html($avg_placement ?: 'N/A'); ?></div>
                    <div class="stat-title">Average Package</div>
                </div>
                <div class="placement-stat highlight">
                    <div class="stat-number"><?php echo esc_html($highest_placement ?: 'N/A'); ?></div>
                    <div class="stat-title">Highest Package</div>
                </div>
                <div class="placement-stat">
                    <div class="stat-number">90%+</div>
                    <div class="stat-title">Placement Rate</div>
                </div>
                <div class="placement-stat">
                    <div class="stat-number">500+</div>
                    <div class="stat-title">Companies Visit</div>
                </div>
            </div>

            <h3>Top Recruiters</h3>
            <div class="recruiters-grid">
                <?php
                $recruiters = array('Google', 'Microsoft', 'Amazon', 'Facebook', 'Apple', 'TCS', 'Infosys', 'Wipro', 'Accenture', 'Deloitte', 'Goldman Sachs', 'Morgan Stanley');
                foreach ($recruiters as $recruiter):
                ?>
                <div class="recruiter-badge"><?php echo esc_html($recruiter); ?></div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Scholarships Section -->
        <section id="scholarships" class="content-section">
            <h2>🎓 Scholarships</h2>
            <div class="scholarships-list">
                <div class="scholarship-card">
                    <h4>Merit-Based Scholarship</h4>
                    <p><strong>Amount:</strong> Up to 100% tuition fee waiver</p>
                    <p><strong>Eligibility:</strong> Top 10% in entrance exam</p>
                </div>
                <div class="scholarship-card">
                    <h4>Need-Based Financial Aid</h4>
                    <p><strong>Amount:</strong> Varies based on family income</p>
                    <p><strong>Eligibility:</strong> Family income below 8 LPA</p>
                </div>
                <div class="scholarship-card">
                    <h4>SC/ST/OBC Scholarship</h4>
                    <p><strong>Amount:</strong> Full tuition + stipend</p>
                    <p><strong>Eligibility:</strong> Category certificate required</p>
                </div>
                <div class="scholarship-card">
                    <h4>Sports Scholarship</h4>
                    <p><strong>Amount:</strong> 25-50% fee waiver</p>
                    <p><strong>Eligibility:</strong> National/State level players</p>
                </div>
            </div>
        </section>

        <!-- Facilities Section -->
        <section id="facilities" class="content-section">
            <h2>🏫 Facilities</h2>
            <div class="facilities-grid">
                <div class="facility-item">
                    <span class="facility-icon">📚</span>
                    <span class="facility-name">Central Library</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">💻</span>
                    <span class="facility-name">Computer Labs</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🔬</span>
                    <span class="facility-name">Research Labs</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🏠</span>
                    <span class="facility-name">Hostel</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🍽️</span>
                    <span class="facility-name">Cafeteria</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🏃</span>
                    <span class="facility-name">Sports Complex</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🏥</span>
                    <span class="facility-name">Medical Center</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">📶</span>
                    <span class="facility-name">Wi-Fi Campus</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🚌</span>
                    <span class="facility-name">Transport</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🏦</span>
                    <span class="facility-name">Bank & ATM</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🎭</span>
                    <span class="facility-name">Auditorium</span>
                </div>
                <div class="facility-item">
                    <span class="facility-icon">🏋️</span>
                    <span class="facility-name">Gymnasium</span>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery" class="content-section">
            <h2>📸 Gallery</h2>
            <div class="gallery-placeholder">
                <div class="gallery-item">
                    <div class="placeholder-image">
                        <span>🏛️</span>
                        <p>Main Building</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="placeholder-image">
                        <span>📚</span>
                        <p>Library</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="placeholder-image">
                        <span>💻</span>
                        <p>Computer Lab</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="placeholder-image">
                        <span>🏠</span>
                        <p>Hostel</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="placeholder-image">
                        <span>🌳</span>
                        <p>Campus</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <div class="placeholder-image">
                        <span>🏃</span>
                        <p>Sports Ground</p>
                    </div>
                </div>
            </div>
            <p class="gallery-note">Contact college administration for actual campus images</p>
        </section>

        <!-- Ranking Section -->
        <section id="ranking" class="content-section">
            <h2>📊 Ranking & Recognition</h2>
            <div class="ranking-cards">
                <div class="ranking-item">
                    <div class="ranking-badge">NIRF</div>
                    <div class="ranking-details">
                        <h4>National Institutional Ranking Framework</h4>
                        <p class="rank">Rank #<?php echo esc_html($nirf_rank ?: 'N/A'); ?></p>
                        <p class="year">2024</p>
                    </div>
                </div>
                <div class="ranking-item">
                    <div class="ranking-badge">NAAC</div>
                    <div class="ranking-details">
                        <h4>Accreditation</h4>
                        <p class="rank"><?php echo esc_html($accreditation ?: 'Accredited'); ?></p>
                        <p class="year">Valid</p>
                    </div>
                </div>
                <?php if ($college_type === 'IIT' || $college_type === 'NIT' || $college_type === 'IIIT'): ?>
                <div class="ranking-item">
                    <div class="ranking-badge">IOE</div>
                    <div class="ranking-details">
                        <h4>Institute of Eminence</h4>
                        <p class="rank">National Importance</p>
                        <p class="year">Government of India</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="content-section">
            <h2>📞 Contact Information</h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <h4>📍 Address</h4>
                    <p>
                        <?php echo esc_html($college->post_title); ?><br>
                        <?php echo esc_html($city); ?>, <?php echo esc_html($state); ?><br>
                        India
                    </p>
                </div>
                <div class="contact-card">
                    <h4>🌐 Website</h4>
                    <p><a href="<?php echo esc_url($website ?: '#'); ?>" target="_blank"><?php echo esc_html($website ?: 'Visit official website'); ?></a></p>
                </div>
                <div class="contact-card">
                    <h4>📧 Email</h4>
                    <p>admissions@<?php echo strtolower(str_replace(' ', '', $short_name ?: 'college')); ?>.ac.in</p>
                </div>
                <div class="contact-card">
                    <h4>📞 Phone</h4>
                    <p>Contact through official website</p>
                </div>
            </div>
        </section>

        <!-- Apply Section -->
        <section id="apply-section" class="content-section apply-section">
            <h2>🎯 Apply to <?php echo esc_html($short_name ?: $college->post_title); ?></h2>
            <div class="apply-cta">
                <p>Ready to start your journey at one of India's premier institutions?</p>
                <div class="apply-buttons">
                    <a href="<?php echo home_url('/apply/?college=' . $college_id); ?>" class="btn-apply-large">Apply Now</a>
                    <a href="<?php echo home_url('/student-login/'); ?>" class="btn-register">Register / Login</a>
                </div>
            </div>
        </section>

        <!-- Related Colleges -->
        <section class="content-section">
            <h2>🔗 Similar Colleges</h2>
            <div class="related-colleges">
                <?php
                $related_args = array(
                    'post_type' => 'ck_college',
                    'posts_per_page' => 4,
                    'post__not_in' => array($college_id),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'college_type',
                            'field' => 'name',
                            'terms' => $college_type,
                        ),
                    ),
                );
                $related_query = new WP_Query($related_args);

                if ($related_query->have_posts()):
                    while ($related_query->have_posts()): $related_query->the_post();
                        $rel_rank = get_post_meta(get_the_ID(), '_ck_nirf_rank', true);
                        $rel_placement = get_post_meta(get_the_ID(), '_ck_avg_placement', true);
                ?>
                <a href="<?php echo add_query_arg('college_id', get_the_ID(), home_url('/college-details/')); ?>" class="related-college-card">
                    <h4><?php the_title(); ?></h4>
                    <p>NIRF Rank: #<?php echo esc_html($rel_rank ?: 'N/A'); ?></p>
                    <p>Avg Package: <?php echo esc_html($rel_placement ?: 'N/A'); ?></p>
                </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
    </div>
</div>
</div><!-- .ck-college-detail-wrapper -->

<?php
// Include footer
if (file_exists(CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/site-footer.php')) {
    include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/site-footer.php';
}
?>

<style>
/* College Detail Page Styles */
.ck-college-detail-wrapper {
    position: relative;
    overflow: visible; /* Critical for sticky to work */
    min-height: 100vh;
}

.ck-college-detail {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    color: #333;
    position: relative;
}

/* Breadcrumb */
.college-breadcrumb {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb-inner {
    max-width: 1200px;
    margin: 0 auto;
    font-size: 14px;
}

.breadcrumb-inner a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb-inner .sep {
    margin: 0 10px;
    color: #999;
}

.breadcrumb-inner .current {
    color: #333;
    font-weight: 600;
}

/* Hero Section */
.college-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 20px;
    color: white;
    text-align: center;
    position: relative;
}

.hero-content {
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.college-badges {
    margin-bottom: 20px;
}

.college-badges .badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin: 5px;
    text-transform: uppercase;
}

.badge.type { background: #ffd700; color: #333; }
.badge.rank { background: #28a745; color: white; }
.badge.ownership { background: rgba(255,255,255,0.2); }

.college-hero h1 {
    font-size: 2.8rem;
    font-weight: 800;
    margin: 0 0 10px 0;
    color: white;
}

.college-hero .short-name {
    font-size: 1.3rem;
    opacity: 0.9;
    margin-bottom: 15px;
}

.college-hero .location {
    font-size: 1.1rem;
    opacity: 0.95;
    margin-bottom: 30px;
}

.hero-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-actions .btn-primary {
    background: white;
    color: #667eea;
    padding: 14px 32px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.3s;
}

.hero-actions .btn-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
}

.hero-actions .btn-outline {
    background: transparent;
    color: white;
    border: 2px solid rgba(255,255,255,0.5);
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: -40px auto 40px;
    padding: 0 20px;
    position: relative;
    z-index: 3;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 10px;
}

.stat-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 13px;
    color: #666;
    text-transform: uppercase;
}

/* Navigation Tabs - Sticky on Scroll with Progress Indicator */
.college-tabs-wrapper {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 9999;
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    margin-bottom: 30px;
}

/* Scroll Progress Bar */
.scroll-progress-bar {
    height: 4px;
    background: #e9ecef;
    position: relative;
    width: 100%;
}

.scroll-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    width: 0%;
    transition: width 0.1s ease;
}

.college-tabs {
    display: flex;
    gap: 10px;
    background: #f8f9fa;
    padding: 15px 20px;
    justify-content: center;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    flex-wrap: nowrap; /* Don't wrap on mobile, scroll instead */
}

/* Hide scrollbar but keep functionality */
.college-tabs::-webkit-scrollbar {
    display: none;
}

.college-tabs.is-sticky {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

/* Add backdrop blur for better visibility when sticky */
@supports (backdrop-filter: blur(10px)) {
    .college-tabs.is-sticky {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
}

.college-tabs .tab {
    padding: 10px 20px;
    background: white;
    color: #333;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
    border: 1px solid #e9ecef;
    white-space: nowrap;
    flex-shrink: 0; /* Prevent tabs from shrinking */
}

.college-tabs .tab:hover,
.college-tabs .tab.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

/* Mobile: Show scroll hint */
@media (max-width: 768px) {
    .college-tabs {
        justify-content: flex-start;
        padding: 12px 15px;
        gap: 8px;
    }

    .college-tabs .tab {
        padding: 8px 16px;
        font-size: 13px;
    }

    /* Add fade effect to indicate more content */
    .college-tabs::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 4px; /* Account for progress bar */
        width: 40px;
        background: linear-gradient(90deg, transparent, rgba(248, 249, 250, 0.9));
        pointer-events: none;
    }

    .college-tabs.is-sticky::after {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.95));
    }
}

/* Content Sections */
.college-content {
    max-width: 1200px;
    scroll-padding-top: 80px;
    margin: 0 auto;
    padding: 0 20px;
}

.content-section {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.content-section h2 {
    font-size: 1.8rem;
    color: #333;
    margin: 0 0 25px 0;
    padding-bottom: 15px;
    border-bottom: 3px solid #667eea;
}

.content-section h3 {
    font-size: 1.3rem;
    color: #333;
    margin: 30px 0 20px;
}

/* Overview Details */
.overview-details {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
    margin-top: 30px;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
}

.detail-item {
    display: flex;
    gap: 10px;
}

.detail-item strong {
    color: #555;
    min-width: 140px;
}

.detail-item a {
    color: #667eea;
}

/* Highlights Grid */
.highlights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.highlight-card {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    transition: transform 0.3s;
}

.highlight-card:hover {
    transform: translateY(-3px);
}

.highlight-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.highlight-card h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.highlight-card p {
    margin: 0;
    color: #666;
}

/* Courses Table */
.courses-table-wrapper {
    overflow-x: auto;
}

.courses-table {
    width: 100%;
    border-collapse: collapse;
}

.courses-table th {
    background: #667eea;
    color: white;
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.courses-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
}

.courses-table tr:hover {
    background: #f8f9fa;
}

.courses-table .fees {
    color: #28a745;
    font-weight: 600;
}

/* Placement Stats */
.placement-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.placement-stat {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
}

.placement-stat.highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.placement-stat.highlight .stat-title {
    color: rgba(255,255,255,0.9);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 10px;
}

.placement-stat.highlight .stat-number {
    color: white;
}

.stat-title {
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
}

/* Recruiters Grid */
.recruiters-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.recruiter-badge {
    background: white;
    border: 2px solid #e9ecef;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    color: #333;
}

/* Scholarships */
.scholarships-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.scholarship-card {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    border-left: 4px solid #667eea;
}

.scholarship-card h4 {
    margin: 0 0 15px 0;
    color: #333;
}

.scholarship-card p {
    margin: 8px 0;
    color: #555;
    font-size: 14px;
}

/* Facilities */
.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.facility-icon {
    font-size: 1.5rem;
}

.facility-name {
    font-weight: 500;
    color: #333;
}

/* Gallery */
.gallery-placeholder {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.placeholder-image {
    background: #f8f9fa;
    height: 150px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed #ddd;
}

.placeholder-image span {
    font-size: 3rem;
    margin-bottom: 10px;
}

.placeholder-image p {
    margin: 0;
    color: #666;
    font-weight: 500;
}

.gallery-note {
    text-align: center;
    color: #999;
    margin-top: 20px;
    font-style: italic;
}

/* Ranking */
.ranking-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.ranking-item {
    display: flex;
    align-items: center;
    gap: 20px;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
}

.ranking-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.2rem;
    min-width: 80px;
    text-align: center;
}

.ranking-details h4 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 1rem;
}

.ranking-details .rank {
    font-size: 1.3rem;
    font-weight: 700;
    color: #667eea;
    margin: 0 0 5px 0;
}

.ranking-details .year {
    font-size: 12px;
    color: #999;
    margin: 0;
}

/* Contact */
.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.contact-card {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
}

.contact-card h4 {
    margin: 0 0 15px 0;
    color: #333;
}

.contact-card p {
    margin: 0;
    color: #555;
    line-height: 1.6;
}

.contact-card a {
    color: #667eea;
}

/* Apply Section */
.apply-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
}

.apply-section h2 {
    color: white;
    border-bottom-color: rgba(255,255,255,0.3);
}

.apply-cta p {
    font-size: 1.2rem;
    margin-bottom: 25px;
}

.apply-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-apply-large {
    background: white;
    color: #667eea;
    padding: 16px 40px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    text-decoration: none;
}

.btn-register {
    background: transparent;
    color: white;
    border: 2px solid white;
    padding: 14px 38px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
}

/* Related Colleges */
.related-colleges {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.related-college-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: transform 0.3s;
    display: block;
}

.related-college-card:hover {
    transform: translateY(-3px);
    background: #e9ecef;
}

.related-college-card h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1rem;
}

.related-college-card p {
    margin: 5px 0;
    color: #666;
    font-size: 13px;
}

/* ============================================
   MOBILE RESPONSIVE STYLES
   ============================================ */

/* Tablet and below */
@media (max-width: 992px) {
    .college-content {
        padding: 0 15px;
    }

    .content-section {
        padding: 30px 20px;
    }

    .quick-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .stat-card {
        padding: 15px;
    }

    .highlights-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .courses-table {
        font-size: 13px;
    }

    .facilities-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Mobile */
@media (max-width: 768px) {
    .college-hero {
        padding: 40px 20px;
        min-height: 400px;
    }

    .college-hero h1 {
        font-size: 1.8rem;
        line-height: 1.2;
    }

    .college-hero .short-name {
        font-size: 1.1rem;
    }

    .college-hero .location {
        font-size: 1rem;
    }

    .hero-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .hero-actions .btn-primary,
    .hero-actions .btn-secondary,
    .hero-actions .btn-outline {
        width: 100%;
        text-align: center;
    }

    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 20px 15px;
    }

    .stat-card {
        padding: 12px;
    }

    .stat-value {
        font-size: 1.2rem;
    }

    .stat-label {
        font-size: 11px;
    }

    .content-section {
        padding: 20px 15px;
        border-radius: 8px;
    }

    .content-section h2 {
        font-size: 1.4rem;
        margin-bottom: 20px;
    }

    .content-section h3 {
        font-size: 1.1rem;
        margin: 20px 0 15px;
    }

    .overview-details {
        grid-template-columns: 1fr;
        padding: 15px;
        gap: 10px;
    }

    .highlights-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .highlight-card {
        padding: 15px;
    }

    /* Make table scrollable horizontally */
    .courses-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .courses-table {
        font-size: 12px;
        min-width: 600px; /* Prevent table from being too narrow */
    }

    .courses-table th,
    .courses-table td {
        padding: 10px 8px;
    }

    .facilities-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .facility-item {
        padding: 15px;
    }

    .facility-icon {
        font-size: 2rem;
    }

    .placements-stats {
        grid-template-columns: 1fr;
    }

    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .ranking-cards {
        grid-template-columns: 1fr;
    }

    .related-colleges {
        grid-template-columns: 1fr;
    }

    .apply-buttons {
        flex-direction: column;
        gap: 10px;
    }

    .btn-apply-large,
    .btn-register {
        width: 100%;
        text-align: center;
    }
}

/* Extra small mobile */
@media (max-width: 480px) {
    .college-hero h1 {
        font-size: 1.5rem;
    }

    .quick-stats {
        grid-template-columns: 1fr;
    }

    .stat-card {
        padding: 15px;
    }

    .facilities-grid {
        grid-template-columns: 1fr;
    }

    .gallery-grid {
        grid-template-columns: 1fr;
    }

    .college-badges .badge {
        font-size: 10px;
        padding: 4px 10px;
    }
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}
</style>

<script>
jQuery(document).ready(function($) {
    const $tabs = $('.college-tabs');
    const $tabsWrapper = $('.college-tabs-wrapper');
    const $progressFill = $('#scroll-progress');

    // Get initial position after page fully loads
    setTimeout(function() {
        var tabsTop = $tabsWrapper.length ? $tabsWrapper.offset().top : 0;

        // Tab navigation click handler
        $('.college-tabs .tab').on('click', function(e) {
            e.preventDefault();
            const $clickedTab = $(this);

            $('.college-tabs .tab').removeClass('active');
            $clickedTab.addClass('active');

            // Smooth scroll to section
            const targetId = $clickedTab.attr('href');
            const $target = $(targetId);
            if ($target.length) {
                const offsetTop = $target.offset().top - $tabsWrapper.outerHeight() - 20;
                $('html, body').animate({
                    scrollTop: offsetTop
                }, 500);

                // Scroll tab into view on mobile
                if ($(window).width() <= 768) {
                    const tabElement = $clickedTab[0];
                    tabElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                }
            }
        });

        // Highlight active section and detect sticky state on scroll
        function handleScroll() {
            var scrollPos = $(window).scrollTop();
            var docHeight = $(document).height();
            var windowHeight = $(window).height();

            // Calculate scroll progress (0-100%)
            var scrollPercent = (scrollPos / (docHeight - windowHeight)) * 100;
            $progressFill.css('width', scrollPercent + '%');

            // Add sticky class when scrolled past original position
            if (scrollPos > tabsTop - 10) {
                $tabs.addClass('is-sticky');
            } else {
                $tabs.removeClass('is-sticky');
            }

            // Highlight active section based on scroll position
            var currentSection = '';
            $('.content-section').each(function() {
                var top = $(this).offset().top - $tabsWrapper.outerHeight() - 60;
                var bottom = top + $(this).outerHeight();
                var id = $(this).attr('id');

                if (scrollPos >= top && scrollPos < bottom) {
                    currentSection = id;
                }
            });

            if (currentSection) {
                const $activeTab = $('.college-tabs .tab[href="#' + currentSection + '"]');
                $('.college-tabs .tab').removeClass('active');
                $activeTab.addClass('active');

                // Auto-scroll tab into view on mobile when scrolling
                if ($(window).width() <= 768 && $activeTab.length) {
                    const tabElement = $activeTab[0];
                    const tabsContainer = $tabs[0];
                    const tabLeft = tabElement.offsetLeft;
                    const tabWidth = tabElement.offsetWidth;
                    const containerScroll = tabsContainer.scrollLeft;
                    const containerWidth = tabsContainer.offsetWidth;

                    // Check if tab is not fully visible
                    if (tabLeft < containerScroll || tabLeft + tabWidth > containerScroll + containerWidth) {
                        tabsContainer.scrollTo({
                            left: tabLeft - (containerWidth / 2) + (tabWidth / 2),
                            behavior: 'smooth'
                        });
                    }
                }
            }
        }

        // Attach scroll handler with throttle for performance
        var scrollTimeout;
        $(window).on('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(handleScroll, 10);
        });

        // Initial check
        handleScroll();
    }, 100);
});
</script>
