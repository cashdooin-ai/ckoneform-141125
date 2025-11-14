<?php
/**
 * Mega Menu Navigation Component
 * Categories: Admissions, Learning, Financial, Support, Resources, Career, Jobs
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check if student is logged in
$is_logged_in = CK_OneForm_Student_Auth::is_student_logged_in();
$student = $is_logged_in ? CK_OneForm_Student_Auth::get_current_student() : null;

// Define mega menu structure
$mega_menu = array(
    'admissions' => array(
        'title' => 'Admissions',
        'icon' => '🎓',
        'items' => array(
            array('title' => 'Browse Colleges', 'url' => '/colleges/', 'icon' => '🏛️', 'desc' => 'Explore 100+ top colleges'),
            array('title' => 'Apply Now', 'url' => '/apply/', 'icon' => '📝', 'desc' => 'Multi-college application'),
            array('title' => 'Admission Guidance', 'url' => '/services/admission-guidance/', 'icon' => '🎯', 'desc' => 'Expert counseling'),
            array('title' => 'Document Verification', 'url' => '/services/document-verification/', 'icon' => '✅', 'desc' => 'Verify your documents'),
            array('title' => 'Application Status', 'url' => '/student-dashboard/?tab=applications', 'icon' => '📊', 'desc' => 'Track applications'),
            array('title' => 'Admission Updates', 'url' => '/admissions/updates/', 'icon' => '🔔', 'desc' => 'Latest notifications'),
        )
    ),
    'learning' => array(
        'title' => 'Learning',
        'icon' => '📚',
        'items' => array(
            array('title' => 'Mock Tests', 'url' => '/mock-tests/', 'icon' => '📝', 'desc' => 'JEE, NEET, BITSAT tests'),
            array('title' => 'Study Materials', 'url' => '/study-materials/', 'icon' => '📖', 'desc' => 'Free resources'),
            array('title' => 'Video Lectures', 'url' => '/video-lectures/', 'icon' => '🎥', 'desc' => 'Expert lectures'),
            array('title' => 'Previous Year Papers', 'url' => '/previous-papers/', 'icon' => '📄', 'desc' => 'Solve past papers'),
            array('title' => 'Online Courses', 'url' => '/courses/', 'icon' => '💻', 'desc' => 'Structured learning'),
            array('title' => 'Live Classes', 'url' => '/live-classes/', 'icon' => '🎬', 'desc' => 'Interactive sessions'),
        )
    ),
    'financial' => array(
        'title' => 'Financial Tools',
        'icon' => '💰',
        'items' => array(
            array('title' => 'Scholarship Finder', 'url' => '/scholarships/', 'icon' => '🎓', 'desc' => 'Find scholarships'),
            array('title' => 'Education Loans', 'url' => '/loans/', 'icon' => '🏦', 'desc' => 'Loan assistance'),
            array('title' => 'Fee Calculator', 'url' => '/fee-calculator/', 'icon' => '🧮', 'desc' => 'Calculate costs'),
            array('title' => 'Financial Aid', 'url' => '/financial-aid/', 'icon' => '💵', 'desc' => 'Get financial help'),
            array('title' => 'Payment Plans', 'url' => '/payment-plans/', 'icon' => '📊', 'desc' => 'Flexible payments'),
            array('title' => 'Tax Benefits', 'url' => '/tax-benefits/', 'icon' => '📋', 'desc' => 'Education tax info'),
        )
    ),
    'support' => array(
        'title' => 'Support & Community',
        'icon' => '🤝',
        'items' => array(
            array('title' => 'Help Center', 'url' => '/help/', 'icon' => '❓', 'desc' => 'Get answers'),
            array('title' => 'Live Chat', 'url' => '/chat/', 'icon' => '💬', 'desc' => 'Chat with us'),
            array('title' => 'Student Forum', 'url' => '/forum/', 'icon' => '👥', 'desc' => 'Join discussions'),
            array('title' => 'Counseling', 'url' => '/counseling/', 'icon' => '🎯', 'desc' => 'Career guidance'),
            array('title' => 'FAQs', 'url' => '/faqs/', 'icon' => '📌', 'desc' => 'Common questions'),
            array('title' => 'Contact Us', 'url' => '/contact/', 'icon' => '📞', 'desc' => 'Reach out'),
        )
    ),
    'resources' => array(
        'title' => 'Resources',
        'icon' => '📦',
        'items' => array(
            array('title' => 'College Rankings', 'url' => '/rankings/', 'icon' => '🏆', 'desc' => 'NIRF, CK rankings'),
            array('title' => 'Cutoff Trends', 'url' => '/cutoffs/', 'icon' => '📈', 'desc' => 'Historical cutoffs'),
            array('title' => 'Exam Calendar', 'url' => '/exam-calendar/', 'icon' => '📅', 'desc' => 'Important dates'),
            array('title' => 'College Predictor', 'url' => '/predictor/', 'icon' => '🔮', 'desc' => 'Predict colleges'),
            array('title' => 'Blog & News', 'url' => '/blog/', 'icon' => '📰', 'desc' => 'Latest updates'),
            array('title' => 'Downloads', 'url' => '/downloads/', 'icon' => '⬇️', 'desc' => 'Useful documents'),
        )
    ),
    'career' => array(
        'title' => 'Career & Beyond',
        'icon' => '🚀',
        'items' => array(
            array('title' => 'Career Guidance', 'url' => '/career-guidance/', 'icon' => '🎯', 'desc' => 'Plan your career'),
            array('title' => 'Skill Development', 'url' => '/skills/', 'icon' => '💪', 'desc' => 'Build skills'),
            array('title' => 'Certification Courses', 'url' => '/certifications/', 'icon' => '📜', 'desc' => 'Get certified'),
            array('title' => 'Placement Prep', 'url' => '/placement-prep/', 'icon' => '🎓', 'desc' => 'Interview prep'),
            array('title' => 'Alumni Network', 'url' => '/alumni/', 'icon' => '🤝', 'desc' => 'Connect alumni'),
            array('title' => 'Industry Insights', 'url' => '/insights/', 'icon' => '💡', 'desc' => 'Market trends'),
        )
    ),
    'jobs' => array(
        'title' => 'Jobs & Internships',
        'icon' => '💼',
        'items' => array(
            array('title' => 'Job Portal', 'url' => '/jobs/', 'icon' => '💼', 'desc' => 'Browse jobs'),
            array('title' => 'Internships', 'url' => '/internships/', 'icon' => '👨‍💼', 'desc' => 'Find internships'),
            array('title' => 'Resume Builder', 'url' => '/resume-builder/', 'icon' => '📄', 'desc' => 'Create resume'),
            array('title' => 'Interview Tips', 'url' => '/interview-tips/', 'icon' => '💡', 'desc' => 'Ace interviews'),
            array('title' => 'Company Reviews', 'url' => '/company-reviews/', 'icon' => '⭐', 'desc' => 'Read reviews'),
            array('title' => 'Salary Guide', 'url' => '/salary-guide/', 'icon' => '💵', 'desc' => 'Know your worth'),
        )
    ),
);
?>

<nav class="ck-mega-menu">
    <div class="mega-menu-container">
        <!-- Logo & Brand -->
        <div class="menu-brand">
            <a href="<?php echo home_url('/'); ?>">
                <span class="brand-logo">🎓 CollegeKampus</span>
            </a>
        </div>

        <!-- Main Menu Items -->
        <div class="menu-items">
            <?php foreach ($mega_menu as $key => $menu): ?>
                <div class="menu-item" data-menu="<?php echo $key; ?>">
                    <button class="menu-trigger">
                        <span class="menu-icon"><?php echo $menu['icon']; ?></span>
                        <span class="menu-title"><?php echo $menu['title']; ?></span>
                        <span class="menu-arrow">▼</span>
                    </button>

                    <!-- Mega Menu Dropdown -->
                    <div class="mega-dropdown">
                        <div class="mega-dropdown-inner">
                            <h3 class="mega-title">
                                <?php echo $menu['icon']; ?> <?php echo $menu['title']; ?>
                            </h3>
                            <div class="mega-grid">
                                <?php foreach ($menu['items'] as $item): ?>
                                    <a href="<?php echo home_url($item['url']); ?>" class="mega-item">
                                        <div class="item-icon"><?php echo $item['icon']; ?></div>
                                        <div class="item-content">
                                            <h4 class="item-title"><?php echo $item['title']; ?></h4>
                                            <p class="item-desc"><?php echo $item['desc']; ?></p>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- User Section -->
        <div class="menu-user">
            <?php if ($is_logged_in): ?>
                <div class="user-menu">
                    <button class="user-trigger">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($student->full_name, 0, 1)); ?>
                        </div>
                        <span class="user-name"><?php echo esc_html($student->full_name); ?></span>
                        <span class="user-arrow">▼</span>
                    </button>

                    <div class="user-dropdown">
                        <div class="user-info">
                            <strong><?php echo esc_html($student->full_name); ?></strong>
                            <small><?php echo esc_html($student->student_id); ?></small>
                        </div>
                        <div class="user-links">
                            <a href="<?php echo home_url('/student-dashboard/'); ?>">
                                <span>📊</span> Dashboard
                            </a>
                            <a href="<?php echo home_url('/student-dashboard/?tab=profile'); ?>">
                                <span>👤</span> Profile
                            </a>
                            <a href="<?php echo home_url('/student-dashboard/?tab=applications'); ?>">
                                <span>📝</span> Applications
                            </a>
                            <a href="<?php echo home_url('/student-dashboard/?tab=services'); ?>">
                                <span>🎯</span> My Services
                            </a>
                            <a href="<?php echo home_url('/student-dashboard/?tab=offers'); ?>">
                                <span>🎁</span> My Offers
                            </a>
                            <a href="#" id="mega-menu-logout">
                                <span>🚪</span> Logout
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="<?php echo home_url('/student-login/'); ?>" class="btn-login">
                        Login
                    </a>
                    <a href="<?php echo home_url('/student-login/'); ?>" class="btn-register">
                        Register
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>

<script>
jQuery(document).ready(function($) {
    // Menu hover effects
    $('.menu-item').on('mouseenter', function() {
        $('.mega-dropdown').removeClass('active');
        $(this).find('.mega-dropdown').addClass('active');
    });

    $('.mega-menu-container').on('mouseleave', function() {
        $('.mega-dropdown').removeClass('active');
    });

    // User menu toggle
    $('.user-trigger').on('click', function(e) {
        e.stopPropagation();
        $('.user-dropdown').toggleClass('active');
    });

    // Close user menu when clicking outside
    $(document).on('click', function() {
        $('.user-dropdown').removeClass('active');
    });

    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function() {
        $('.menu-items').toggleClass('mobile-active');
        $(this).toggleClass('active');
    });

    // Mobile submenu toggle
    $('.menu-trigger').on('click', function() {
        if ($(window).width() <= 768) {
            $(this).next('.mega-dropdown').toggleClass('active');
        }
    });

    // Logout
    $('#mega-menu-logout').on('click', function(e) {
        e.preventDefault();
        $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
            action: 'ck_student_logout'
        }, function() {
            window.location.href = '<?php echo home_url('/student-login/'); ?>';
        });
    });
});
</script>

<style>
.ck-mega-menu {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.mega-menu-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
}

.menu-brand {
    flex-shrink: 0;
}

.brand-logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    text-decoration: none;
}

.menu-items {
    display: flex;
    gap: 5px;
    flex: 1;
    justify-content: center;
}

.menu-item {
    position: relative;
}

.menu-trigger {
    background: none;
    border: none;
    padding: 10px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    transition: all 0.3s;
    border-radius: 6px;
}

.menu-trigger:hover {
    background: #f0f4ff;
    color: #667eea;
}

.menu-icon {
    font-size: 18px;
}

.menu-arrow {
    font-size: 10px;
    transition: transform 0.3s;
}

.menu-item:hover .menu-arrow {
    transform: rotate(180deg);
}

.mega-dropdown {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-radius: 12px;
    padding: 30px;
    min-width: 600px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    margin-top: 10px;
}

.mega-dropdown.active {
    opacity: 1;
    visibility: visible;
    margin-top: 0;
}

.mega-dropdown-inner {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.mega-title {
    font-size: 1.3rem;
    color: #667eea;
    margin: 0;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.mega-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.mega-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s;
}

.mega-item:hover {
    background: #f0f4ff;
    transform: translateX(4px);
}

.item-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.item-content {
    flex: 1;
}

.item-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 4px 0;
}

.item-desc {
    font-size: 12px;
    color: #666;
    margin: 0;
}

.menu-user {
    flex-shrink: 0;
}

.user-menu {
    position: relative;
}

.user-trigger {
    background: none;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
    transition: all 0.3s;
}

.user-trigger:hover {
    background: #f0f4ff;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
}

.user-name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.user-arrow {
    font-size: 10px;
    color: #666;
}

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-radius: 12px;
    padding: 20px;
    min-width: 220px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    margin-top: 10px;
}

.user-dropdown.active {
    opacity: 1;
    visibility: visible;
    margin-top: 5px;
}

.user-info {
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 15px;
}

.user-info strong {
    display: block;
    font-size: 14px;
    color: #333;
}

.user-info small {
    display: block;
    font-size: 12px;
    color: #666;
    margin-top: 4px;
}

.user-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.user-links a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.user-links a:hover {
    background: #f0f4ff;
    color: #667eea;
}

.auth-buttons {
    display: flex;
    gap: 10px;
}

.btn-login,
.btn-register {
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-login {
    color: #667eea;
    background: #f0f4ff;
}

.btn-login:hover {
    background: #e0e7ff;
}

.btn-register {
    background: #667eea;
    color: white;
}

.btn-register:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.mobile-menu-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
}

.mobile-menu-toggle span {
    width: 25px;
    height: 3px;
    background: #333;
    transition: all 0.3s;
    border-radius: 2px;
}

.mobile-menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(7px, 7px);
}

.mobile-menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

@media (max-width: 1200px) {
    .mega-dropdown {
        min-width: 500px;
    }
}

@media (max-width: 768px) {
    .mega-menu-container {
        flex-wrap: wrap;
        height: auto;
        padding: 15px 20px;
    }

    .menu-brand {
        order: 1;
    }

    .mobile-menu-toggle {
        display: flex;
        order: 2;
    }

    .menu-items {
        order: 3;
        width: 100%;
        flex-direction: column;
        display: none;
        margin-top: 15px;
    }

    .menu-items.mobile-active {
        display: flex;
    }

    .menu-user {
        order: 4;
        width: 100%;
        margin-top: 15px;
    }

    .mega-dropdown {
        position: static;
        transform: none;
        min-width: auto;
        width: 100%;
        display: none;
    }

    .mega-dropdown.active {
        display: block;
    }

    .mega-grid {
        grid-template-columns: 1fr;
    }

    .auth-buttons {
        width: 100%;
    }

    .btn-login,
    .btn-register {
        flex: 1;
        text-align: center;
    }
}
</style>
