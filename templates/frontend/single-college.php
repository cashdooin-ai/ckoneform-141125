<?php
/**
 * Single College Details Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    $college_id = get_the_ID();

    // Get all college meta data
    $short_name = get_post_meta($college_id, 'short_name', true);
    $type = get_post_meta($college_id, 'college_type', true);
    $state = get_post_meta($college_id, 'state', true);
    $city = get_post_meta($college_id, 'city', true);
    $nirf_rank = get_post_meta($college_id, 'nirf_rank', true);
    $ck_rank = get_post_meta($college_id, 'ck_rank', true);
    $established = get_post_meta($college_id, 'established', true);
    $accreditation = get_post_meta($college_id, 'accreditation', true);
    $courses = get_post_meta($college_id, 'courses', true);
    $fees_range = get_post_meta($college_id, 'fees_range', true);
    $website = get_post_meta($college_id, 'website', true);
    $ownership = get_post_meta($college_id, 'ownership', true);
    $category = get_post_meta($college_id, 'category', true);
    $affiliation = get_post_meta($college_id, 'affiliation', true);
    $facilities = get_post_meta($college_id, 'facilities', true);
    $total_students = get_post_meta($college_id, 'total_students', true);
    $faculty_count = get_post_meta($college_id, 'faculty_count', true);
    $campus_size = get_post_meta($college_id, 'campus_size', true);
    $placements = get_post_meta($college_id, 'placement_percentage', true);
    $avg_package = get_post_meta($college_id, 'avg_package', true);
    $highest_package = get_post_meta($college_id, 'highest_package', true);
?>

<div class="ck-single-college">
    <div class="container">
        <!-- College Header -->
        <div class="college-header-section">
            <div class="college-header-content">
                <div class="college-title-area">
                    <?php if ($type): ?>
                        <span class="college-type-badge <?php echo esc_attr(strtolower($type)); ?>">
                            <?php echo esc_html($type); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="college-title"><?php the_title(); ?></h1>

                    <?php if ($short_name): ?>
                        <p class="college-short-name"><?php echo esc_html($short_name); ?></p>
                    <?php endif; ?>

                    <div class="college-location">
                        <span class="location-icon">📍</span>
                        <?php echo esc_html($city . ', ' . $state); ?>
                    </div>
                </div>

                <div class="college-actions">
                    <button type="button" class="btn btn-apply" data-college-id="<?php echo $college_id; ?>">
                        Apply Now
                    </button>
                    <?php if ($website): ?>
                        <a href="<?php echo esc_url($website); ?>" target="_blank" class="btn btn-website">
                            Visit Website
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rankings -->
            <?php if ($nirf_rank || $ck_rank): ?>
                <div class="college-rankings-bar">
                    <?php if ($nirf_rank): ?>
                        <div class="ranking-item">
                            <span class="rank-label">NIRF Rank</span>
                            <span class="rank-value">#<?php echo esc_html($nirf_rank); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($ck_rank): ?>
                        <div class="ranking-item">
                            <span class="rank-label">CK Rank</span>
                            <span class="rank-value">#<?php echo esc_html($ck_rank); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Stats -->
        <div class="college-quick-stats">
            <div class="stat-item">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <div class="stat-label">Established</div>
                    <div class="stat-value"><?php echo esc_html($established ?: 'N/A'); ?></div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">⭐</div>
                <div class="stat-content">
                    <div class="stat-label">Accreditation</div>
                    <div class="stat-value"><?php echo esc_html($accreditation ?: 'N/A'); ?></div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon">🏛️</div>
                <div class="stat-content">
                    <div class="stat-label">Ownership</div>
                    <div class="stat-value"><?php echo esc_html($ownership ?: 'N/A'); ?></div>
                </div>
            </div>

            <?php if ($total_students): ?>
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <div class="stat-label">Students</div>
                        <div class="stat-value"><?php echo esc_html(number_format($total_students)); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sticky Navigation with Progress Bar -->
        <div class="college-sticky-nav" id="college-sticky-nav">
            <div class="container">
                <div class="nav-progress-bar" id="nav-progress-bar"></div>
                <nav class="college-nav-tabs">
                    <a href="#about" class="nav-tab active" data-section="about">About</a>
                    <?php if ($courses): ?><a href="#courses" class="nav-tab" data-section="courses">Courses</a><?php endif; ?>
                    <?php if ($facilities): ?><a href="#facilities" class="nav-tab" data-section="facilities">Facilities</a><?php endif; ?>
                    <?php if ($placements || $avg_package || $highest_package): ?><a href="#placements" class="nav-tab" data-section="placements">Placements</a><?php endif; ?>
                    <a href="#fees" class="nav-tab" data-section="fees">Fees</a>
                    <a href="#admission" class="nav-tab" data-section="admission">Admission</a>
                    <a href="#contact" class="nav-tab" data-section="contact">Contact</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="college-content-area">
            <div class="college-main">
                <!-- About Section -->
                <div class="content-section" id="about">
                    <h2>About <?php the_title(); ?></h2>
                    <div class="section-content">
                        <?php
                        $content = get_the_content();
                        if ($content) {
                            echo wpautop($content);
                        } else {
                            echo '<p>Detailed information about this college will be available soon.</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Courses Section -->
                <?php if ($courses): ?>
                    <div class="content-section" id="courses">
                        <h2>Courses Offered</h2>
                        <div class="section-content">
                            <div class="courses-list">
                                <?php echo wpautop(esc_html($courses)); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Facilities Section -->
                <?php if ($facilities): ?>
                    <div class="content-section" id="facilities">
                        <h2>Facilities</h2>
                        <div class="section-content">
                            <div class="facilities-list">
                                <?php echo wpautop(esc_html($facilities)); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Placement Section -->
                <?php if ($placements || $avg_package || $highest_package): ?>
                    <div class="content-section" id="placements">
                        <h2>Placements</h2>
                        <div class="section-content">
                            <div class="placement-stats">
                                <?php if ($placements): ?>
                                    <div class="placement-stat">
                                        <div class="placement-label">Placement Rate</div>
                                        <div class="placement-value"><?php echo esc_html($placements); ?>%</div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($avg_package): ?>
                                    <div class="placement-stat">
                                        <div class="placement-label">Average Package</div>
                                        <div class="placement-value">₹<?php echo esc_html($avg_package); ?> LPA</div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($highest_package): ?>
                                    <div class="placement-stat">
                                        <div class="placement-label">Highest Package</div>
                                        <div class="placement-value">₹<?php echo esc_html($highest_package); ?> LPA</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Fees Section -->
                <div class="content-section" id="fees">
                    <h2>Fee Structure</h2>
                    <div class="section-content">
                        <div class="fee-details">
                            <?php if ($fees_range): ?>
                                <p><strong>Annual Fees:</strong> ₹<?php echo esc_html($fees_range); ?></p>
                                <p class="fee-note">The fee structure may vary based on the course and category. Contact the college for detailed fee breakdown.</p>
                            <?php else: ?>
                                <p>Please contact the college for detailed fee structure information.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Admission Section -->
                <div class="content-section" id="admission">
                    <h2>Admission Process</h2>
                    <div class="section-content">
                        <p>For admission to <?php the_title(); ?>, please follow the application process outlined below:</p>
                        <ol class="admission-steps">
                            <li>Fill out the online application form</li>
                            <li>Submit required documents</li>
                            <li>Pay the application fee</li>
                            <li>Attend counseling (if required)</li>
                            <li>Complete the admission process</li>
                        </ol>
                        <button type="button" class="btn btn-primary btn-apply" data-college-id="<?php echo $college_id; ?>">
                            Start Application Process
                        </button>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="content-section" id="contact">
                    <h2>Contact Information</h2>
                    <div class="section-content">
                        <div class="contact-details">
                            <p><strong>Address:</strong> <?php echo esc_html($city . ', ' . $state); ?></p>
                            <?php if ($website): ?>
                                <p><strong>Website:</strong> <a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a></p>
                            <?php endif; ?>
                            <p class="contact-note">For more information, please visit the college website or contact them directly.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="college-sidebar">
                <!-- Fee Structure -->
                <div class="sidebar-card">
                    <h3>Fee Structure</h3>
                    <div class="fee-amount">
                        <?php if ($fees_range): ?>
                            ₹<?php echo esc_html($fees_range); ?>
                            <span class="fee-period">per year</span>
                        <?php else: ?>
                            Contact for details
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Key Details -->
                <div class="sidebar-card">
                    <h3>Key Details</h3>
                    <div class="details-list">
                        <?php if ($category): ?>
                            <div class="detail-item">
                                <span class="detail-label">Category:</span>
                                <span class="detail-value"><?php echo esc_html($category); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($affiliation): ?>
                            <div class="detail-item">
                                <span class="detail-label">Affiliation:</span>
                                <span class="detail-value"><?php echo esc_html($affiliation); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($faculty_count): ?>
                            <div class="detail-item">
                                <span class="detail-label">Faculty:</span>
                                <span class="detail-value"><?php echo esc_html($faculty_count); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($campus_size): ?>
                            <div class="detail-item">
                                <span class="detail-label">Campus Size:</span>
                                <span class="detail-value"><?php echo esc_html($campus_size); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="sidebar-card cta-card">
                    <h3>Interested?</h3>
                    <p>Apply to this college now and get started with your admission process.</p>
                    <button type="button" class="btn btn-primary btn-block btn-apply" data-college-id="<?php echo $college_id; ?>">
                        Apply to this College
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ck-single-college {
    padding: 40px 0;
    background: #f8f9fa;
}

.ck-single-college .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Sticky Navigation */
.college-sticky-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.college-sticky-nav.scrolled {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.nav-progress-bar {
    height: 3px;
    background: linear-gradient(90deg, #2271b1, #0073aa);
    width: 0%;
    transition: width 0.1s ease;
}

.college-nav-tabs {
    display: flex;
    gap: 5px;
    padding: 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.college-nav-tabs::-webkit-scrollbar {
    display: none;
}

.nav-tab {
    padding: 16px 24px;
    color: #555;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    white-space: nowrap;
    cursor: pointer;
}

.nav-tab:hover {
    color: #2271b1;
    background: #f8f9fa;
}

.nav-tab.active {
    color: #2271b1;
    border-bottom-color: #2271b1;
    font-weight: 600;
}

/* Header Section */
.college-header-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.college-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
    margin-bottom: 20px;
}

.college-type-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.college-type-badge.iit {
    background: #fef3c7;
    color: #92400e;
}

.college-type-badge.nit {
    background: #dbeafe;
    color: #1e40af;
}

.college-type-badge.iiit {
    background: #e0e7ff;
    color: #3730a3;
}

.college-type-badge.medical {
    background: #fee2e2;
    color: #991b1b;
}

.college-type-badge.private,
.college-type-badge.university {
    background: #f3e8ff;
    color: #6b21a8;
}

.college-title {
    font-size: 2.5rem;
    margin: 0 0 10px 0;
    color: #1a1a1a;
    line-height: 1.2;
}

.college-short-name {
    font-size: 1.2rem;
    color: #667eea;
    font-weight: 600;
    margin: 0 0 15px 0;
}

.college-location {
    font-size: 1.1rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 8px;
}

.college-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-width: 200px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
}

.btn-apply {
    background: #667eea;
    color: white;
}

.btn-apply:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-website {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-website:hover {
    background: #667eea;
    color: white;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-block {
    width: 100%;
    display: block;
}

/* Rankings Bar */
.college-rankings-bar {
    display: flex;
    gap: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
}

.ranking-item {
    flex: 1;
    text-align: center;
    color: white;
}

.rank-label {
    display: block;
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.rank-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
}

/* Quick Stats */
.college-quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-item {
    background: white;
    padding: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.stat-icon {
    font-size: 2.5rem;
}

.stat-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a1a;
}

/* Content Area */
.college-content-area {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

.content-section {
    background: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.content-section h2 {
    font-size: 1.8rem;
    margin: 0 0 20px 0;
    color: #1a1a1a;
    padding-bottom: 15px;
    border-bottom: 2px solid #667eea;
}

.section-content {
    color: #444;
    line-height: 1.7;
}

.courses-list,
.facilities-list {
    line-height: 1.8;
}

/* Placement Stats */
.placement-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.placement-stat {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.placement-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 8px;
}

.placement-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #10b981;
}

/* Sidebar */
.sidebar-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.sidebar-card h3 {
    font-size: 1.3rem;
    margin: 0 0 15px 0;
    color: #1a1a1a;
}

.fee-amount {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
}

.fee-period {
    display: block;
    font-size: 14px;
    color: #666;
    font-weight: 400;
    margin-top: 5px;
}

.details-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-label {
    font-weight: 600;
    color: #666;
}

.detail-value {
    color: #1a1a1a;
}

.cta-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.cta-card h3,
.cta-card p {
    color: white;
}

.cta-card .btn-primary {
    background: white;
    color: #667eea;
}

.cta-card .btn-primary:hover {
    background: #f0f0f0;
}

/* Responsive */
@media (max-width: 992px) {
    .college-content-area {
        grid-template-columns: 1fr;
    }

    .college-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .college-title {
        font-size: 1.8rem;
    }

    .college-header-content {
        flex-direction: column;
    }

    .college-actions {
        width: 100%;
    }

    .college-rankings-bar {
        flex-direction: column;
        gap: 15px;
    }

    .college-quick-stats {
        grid-template-columns: 1fr;
    }

    .placement-stats {
        grid-template-columns: 1fr;
    }

    .nav-tab {
        padding: 12px 16px;
        font-size: 13px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Sticky Navigation & Progress Bar functionality
    const stickyNav = $('#college-sticky-nav');
    const progressBar = $('#nav-progress-bar');
    const navTabs = $('.nav-tab');
    const sections = $('.content-section');

    // Smooth scrolling to sections
    navTabs.on('click', function(e) {
        e.preventDefault();
        const targetId = $(this).attr('href');
        const targetSection = $(targetId);

        if (targetSection.length) {
            const offsetTop = targetSection.offset().top - stickyNav.outerHeight() - 20;

            $('html, body').animate({
                scrollTop: offsetTop
            }, 500, function() {
                // Update active tab after scrolling
                updateActiveTab(targetId.substring(1));
            });
        }
    });

    // Update progress bar and active tab on scroll
    $(window).on('scroll', function() {
        // Progress bar calculation
        const winScroll = $(window).scrollTop();
        const height = $(document).height() - $(window).height();
        const scrolled = (winScroll / height) * 100;
        progressBar.css('width', scrolled + '%');

        // Add shadow to sticky nav when scrolled
        if (winScroll > 100) {
            stickyNav.addClass('scrolled');
        } else {
            stickyNav.removeClass('scrolled');
        }

        // Update active tab based on visible section
        let currentSection = '';
        const scrollPos = winScroll + stickyNav.outerHeight() + 100;

        sections.each(function() {
            const sectionTop = $(this).offset().top;
            const sectionBottom = sectionTop + $(this).outerHeight();
            const sectionId = $(this).attr('id');

            if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                currentSection = sectionId;
            }
        });

        if (currentSection) {
            updateActiveTab(currentSection);
        }
    });

    // Function to update active tab
    function updateActiveTab(sectionId) {
        navTabs.removeClass('active');
        navTabs.filter('[data-section="' + sectionId + '"]').addClass('active');
    }

    // Apply button functionality
    $('.btn-apply').on('click', function() {
        const collegeId = $(this).data('college-id');
        // Redirect to application page
        window.location.href = '<?php echo esc_url(home_url('/apply/')); ?>?college=' + collegeId;
    });
});
</script>

<?php
endwhile;

get_footer();
