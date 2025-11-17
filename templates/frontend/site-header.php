<?php
/**
 * Site Header with Mega Menu
 *
 * Professional header with mega menu dropdown for services
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get user state
$is_logged_in = is_user_logged_in();
$is_student_logged_in = class_exists('CK_OneForm_Student_Auth') ? CK_OneForm_Student_Auth::is_student_logged_in() : false;
$current_user = wp_get_current_user();

// Load service pages data for mega menu
$service_pages = array();
$service_pages_file = CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';
if (file_exists($service_pages_file)) {
    $service_pages = include $service_pages_file;
}

// Group services by category for mega menu
$service_categories = array(
    'admissions' => array('title' => __('Admissions', 'ck-oneform'), 'icon' => 'welcome-write-blog', 'items' => array()),
    'exam_prep' => array('title' => __('Exam Prep', 'ck-oneform'), 'icon' => 'edit', 'items' => array()),
    'career' => array('title' => __('Career Services', 'ck-oneform'), 'icon' => 'businessman', 'items' => array()),
    'scholarships' => array('title' => __('Scholarships', 'ck-oneform'), 'icon' => 'awards', 'items' => array()),
    'courses' => array('title' => __('Courses', 'ck-oneform'), 'icon' => 'book', 'items' => array()),
    'colleges' => array('title' => __('College Guide', 'ck-oneform'), 'icon' => 'building', 'items' => array()),
);

// Organize service pages into categories
if (!empty($service_pages)) {
    foreach ($service_pages as $slug => $page) {
        $category = isset($page['category']) ? $page['category'] : 'admissions';
        if (isset($service_categories[$category])) {
            $service_categories[$category]['items'][$slug] = $page['title'];
        }
    }
}
?>

<!-- Site Header -->
<header class="ck-main-header" id="ck-main-header">
    <div class="ck-header-top-bar">
        <div class="ck-header-container">
            <div class="ck-top-info">
                <span><i class="dashicons dashicons-phone"></i> +91 11 2345 6789</span>
                <span><i class="dashicons dashicons-email-alt"></i> info@collegekampus.com</span>
            </div>
            <div class="ck-top-links">
                <a href="<?php echo home_url('/faq/'); ?>"><?php _e('FAQ', 'ck-oneform'); ?></a>
                <a href="<?php echo home_url('/application-status/'); ?>"><?php _e('Track Application', 'ck-oneform'); ?></a>
                <?php if ($is_logged_in || $is_student_logged_in): ?>
                    <a href="<?php echo wp_logout_url(home_url('/oneform-home/')); ?>"><?php _e('Logout', 'ck-oneform'); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="ck-header-main">
        <div class="ck-header-container">
            <!-- Logo -->
            <div class="ck-logo">
                <a href="<?php echo home_url('/oneform-home/'); ?>">
                    <div class="ck-logo-mark">
                        <span>CK</span>
                    </div>
                    <div class="ck-logo-text">
                        <span class="ck-brand-name">CollegeKampus</span>
                        <span class="ck-brand-tagline">OneForm Portal</span>
                    </div>
                </a>
            </div>

            <!-- Mobile Toggle -->
            <button class="ck-mobile-toggle" id="ck-mobile-toggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Main Navigation -->
            <nav class="ck-primary-nav" id="ck-primary-nav">
                <ul class="ck-nav-menu">
                    <li class="ck-menu-item">
                        <a href="<?php echo home_url('/oneform-home/'); ?>"><?php _e('Home', 'ck-oneform'); ?></a>
                    </li>

                    <!-- Mega Menu - Services -->
                    <li class="ck-menu-item ck-has-mega-menu">
                        <a href="<?php echo home_url('/services/'); ?>">
                            <?php _e('Services', 'ck-oneform'); ?>
                            <i class="dashicons dashicons-arrow-down-alt2"></i>
                        </a>
                        <div class="ck-mega-menu">
                            <div class="ck-mega-menu-container">
                                <div class="ck-mega-menu-header">
                                    <h3><?php _e('Our Services', 'ck-oneform'); ?></h3>
                                    <p><?php _e('Everything you need for your college admission journey', 'ck-oneform'); ?></p>
                                </div>
                                <div class="ck-mega-menu-grid">
                                    <?php foreach ($service_categories as $cat_key => $category): ?>
                                        <?php if (!empty($category['items'])): ?>
                                        <div class="ck-mega-menu-category">
                                            <div class="ck-category-header">
                                                <i class="dashicons dashicons-<?php echo esc_attr($category['icon']); ?>"></i>
                                                <h4><?php echo esc_html($category['title']); ?></h4>
                                            </div>
                                            <ul class="ck-category-links">
                                                <?php
                                                $count = 0;
                                                foreach ($category['items'] as $slug => $title):
                                                    if ($count >= 5) break; // Limit to 5 items
                                                ?>
                                                    <li><a href="<?php echo home_url('/services/?service=' . esc_attr($slug)); ?>"><?php echo esc_html($title); ?></a></li>
                                                <?php
                                                    $count++;
                                                endforeach;
                                                ?>
                                                <?php if (count($category['items']) > 5): ?>
                                                    <li class="ck-view-all"><a href="<?php echo home_url('/services/'); ?>"><?php _e('View All', 'ck-oneform'); ?> &rarr;</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="ck-mega-menu-footer">
                                    <a href="<?php echo home_url('/services/'); ?>" class="ck-btn-view-all">
                                        <?php _e('View All 42+ Services', 'ck-oneform'); ?>
                                        <i class="dashicons dashicons-arrow-right-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Academics Dropdown -->
                    <li class="ck-menu-item ck-has-dropdown">
                        <a href="#">
                            <?php _e('Academics', 'ck-oneform'); ?>
                            <i class="dashicons dashicons-arrow-down-alt2"></i>
                        </a>
                        <ul class="ck-dropdown-menu">
                            <li><a href="<?php echo home_url('/courses/'); ?>"><i class="dashicons dashicons-book"></i> <?php _e('Our Courses', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/colleges/'); ?>"><i class="dashicons dashicons-building"></i> <?php _e('Partner Colleges', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/mock-tests/'); ?>"><i class="dashicons dashicons-edit"></i> <?php _e('Mock Tests', 'ck-oneform'); ?></a></li>
                        </ul>
                    </li>

                    <li class="ck-menu-item ck-highlight">
                        <a href="<?php echo home_url('/application-form/'); ?>"><?php _e('Apply Now', 'ck-oneform'); ?></a>
                    </li>

                    <!-- About Dropdown -->
                    <li class="ck-menu-item ck-has-dropdown">
                        <a href="#">
                            <?php _e('About', 'ck-oneform'); ?>
                            <i class="dashicons dashicons-arrow-down-alt2"></i>
                        </a>
                        <ul class="ck-dropdown-menu">
                            <li><a href="<?php echo home_url('/about-us/'); ?>"><i class="dashicons dashicons-groups"></i> <?php _e('About Us', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/contact-us/'); ?>"><i class="dashicons dashicons-email"></i> <?php _e('Contact Us', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/faq/'); ?>"><i class="dashicons dashicons-sos"></i> <?php _e('FAQ', 'ck-oneform'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <!-- User Actions -->
            <div class="ck-header-actions">
                <?php if ($is_logged_in || $is_student_logged_in): ?>
                    <div class="ck-user-account">
                        <button class="ck-account-trigger" id="ck-account-trigger">
                            <div class="ck-user-avatar">
                                <?php echo get_avatar($current_user->ID, 32); ?>
                            </div>
                            <span class="ck-user-name"><?php echo esc_html($current_user->display_name); ?></span>
                            <i class="dashicons dashicons-arrow-down-alt2"></i>
                        </button>
                        <div class="ck-account-dropdown" id="ck-account-dropdown">
                            <div class="ck-account-header">
                                <strong><?php echo esc_html($current_user->display_name); ?></strong>
                                <small><?php echo esc_html($current_user->user_email); ?></small>
                            </div>
                            <ul class="ck-account-menu">
                                <li><a href="<?php echo home_url('/student-dashboard/'); ?>"><i class="dashicons dashicons-dashboard"></i> <?php _e('Dashboard', 'ck-oneform'); ?></a></li>
                                <li><a href="<?php echo home_url('/my-applications/'); ?>"><i class="dashicons dashicons-list-view"></i> <?php _e('My Applications', 'ck-oneform'); ?></a></li>
                                <li><a href="<?php echo home_url('/mock-tests/'); ?>"><i class="dashicons dashicons-edit"></i> <?php _e('Mock Tests', 'ck-oneform'); ?></a></li>
                                <li><a href="<?php echo home_url('/payment/'); ?>"><i class="dashicons dashicons-money-alt"></i> <?php _e('Payments', 'ck-oneform'); ?></a></li>
                            </ul>
                            <div class="ck-account-footer">
                                <a href="<?php echo wp_logout_url(home_url('/oneform-home/')); ?>" class="ck-logout-btn">
                                    <i class="dashicons dashicons-exit"></i> <?php _e('Sign Out', 'ck-oneform'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo home_url('/student-login/'); ?>" class="ck-btn-login">
                        <?php _e('Login', 'ck-oneform'); ?>
                    </a>
                    <a href="<?php echo home_url('/student-registration/'); ?>" class="ck-btn-register">
                        <?php _e('Register', 'ck-oneform'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<style>
/* Header Styles */
.ck-main-header {
    position: sticky;
    top: 0;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.ck-header-top-bar {
    background: #2c3e50;
    color: #ecf0f1;
    font-size: 13px;
    padding: 8px 0;
}

.ck-header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ck-top-info {
    display: flex;
    gap: 25px;
}

.ck-top-info span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.ck-top-info .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}

.ck-top-links {
    display: flex;
    gap: 20px;
}

.ck-top-links a {
    color: #ecf0f1;
    text-decoration: none;
    transition: color 0.3s;
}

.ck-top-links a:hover {
    color: #3498db;
}

.ck-header-main {
    background: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 0;
}

.ck-header-main .ck-header-container {
    height: 80px;
}

.ck-logo a {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}

.ck-logo-mark {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 800;
    font-size: 22px;
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.ck-logo-text {
    display: flex;
    flex-direction: column;
}

.ck-brand-name {
    font-size: 22px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
}

.ck-brand-tagline {
    font-size: 11px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.ck-mobile-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    flex-direction: column;
    gap: 5px;
}

.ck-mobile-toggle span {
    width: 25px;
    height: 3px;
    background: #2c3e50;
    border-radius: 2px;
    transition: all 0.3s;
}

.ck-primary-nav {
    flex: 1;
    display: flex;
    justify-content: center;
}

.ck-nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    height: 80px;
}

.ck-menu-item {
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
}

.ck-menu-item > a {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 0 18px;
    color: #2c3e50;
    text-decoration: none;
    font-weight: 500;
    font-size: 15px;
    height: 100%;
    transition: color 0.3s;
}

.ck-menu-item > a:hover {
    color: #667eea;
}

.ck-menu-item > a .dashicons {
    font-size: 12px;
    width: 12px;
    height: 12px;
    transition: transform 0.3s;
}

.ck-menu-item:hover > a .dashicons {
    transform: rotate(180deg);
}

.ck-menu-item.ck-highlight > a {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    border-radius: 8px;
    padding: 10px 25px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    margin: 0 10px;
}

.ck-menu-item.ck-highlight > a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

/* Dropdown Menu */
.ck-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    min-width: 240px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    border-radius: 12px;
    list-style: none;
    padding: 10px 0;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(15px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ck-has-dropdown:hover .ck-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.ck-dropdown-menu li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #2c3e50;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
}

.ck-dropdown-menu li a:hover {
    background: #f8f9fa;
    color: #667eea;
    padding-left: 25px;
}

.ck-dropdown-menu li a .dashicons {
    color: #667eea;
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Mega Menu */
.ck-mega-menu {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(15px);
    background: white;
    width: 900px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.2);
    border-radius: 15px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.ck-has-mega-menu:hover .ck-mega-menu {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.ck-mega-menu-container {
    padding: 0;
}

.ck-mega-menu-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 30px;
}

.ck-mega-menu-header h3 {
    margin: 0 0 5px 0;
    font-size: 20px;
}

.ck-mega-menu-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.ck-mega-menu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    padding: 20px;
}

.ck-mega-menu-category {
    padding: 15px;
}

.ck-category-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
}

.ck-category-header .dashicons {
    color: #667eea;
    font-size: 20px;
    width: 20px;
    height: 20px;
}

.ck-category-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.ck-category-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ck-category-links li {
    margin-bottom: 8px;
}

.ck-category-links li a {
    color: #555;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s;
    display: block;
    padding: 4px 0;
}

.ck-category-links li a:hover {
    color: #667eea;
    padding-left: 8px;
}

.ck-category-links .ck-view-all a {
    color: #667eea;
    font-weight: 600;
}

.ck-mega-menu-footer {
    background: #f8f9fa;
    padding: 15px 30px;
    text-align: center;
    border-top: 1px solid #e9ecef;
}

.ck-btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s;
}

.ck-btn-view-all:hover {
    background: #667eea;
    color: white;
}

/* Header Actions */
.ck-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ck-btn-login {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    padding: 10px 20px;
    border: 2px solid #667eea;
    border-radius: 8px;
    transition: all 0.3s;
}

.ck-btn-login:hover {
    background: #667eea;
    color: white;
}

.ck-btn-register {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    font-weight: 600;
    padding: 10px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: all 0.3s;
}

.ck-btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

/* User Account Dropdown */
.ck-user-account {
    position: relative;
}

.ck-account-trigger {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8f9fa;
    border: none;
    padding: 8px 15px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s;
}

.ck-account-trigger:hover {
    background: #e9ecef;
}

.ck-user-avatar img {
    border-radius: 50%;
}

.ck-user-name {
    font-weight: 500;
    color: #2c3e50;
}

.ck-account-trigger .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    color: #7f8c8d;
    transition: transform 0.3s;
}

.ck-account-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: white;
    min-width: 280px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    border-radius: 12px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s;
    overflow: hidden;
}

.ck-user-account:hover .ck-account-dropdown,
.ck-account-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.ck-account-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
}

.ck-account-header strong {
    display: block;
    font-size: 16px;
}

.ck-account-header small {
    opacity: 0.9;
}

.ck-account-menu {
    list-style: none;
    padding: 10px 0;
    margin: 0;
}

.ck-account-menu li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #2c3e50;
    text-decoration: none;
    transition: all 0.3s;
}

.ck-account-menu li a:hover {
    background: #f8f9fa;
    color: #667eea;
}

.ck-account-menu li a .dashicons {
    color: #667eea;
}

.ck-account-footer {
    border-top: 1px solid #e9ecef;
    padding: 10px 15px;
}

.ck-logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px;
    background: #fee;
    color: #c0392b;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.ck-logout-btn:hover {
    background: #c0392b;
    color: white;
}

/* Mobile Styles */
@media (max-width: 1200px) {
    .ck-mega-menu {
        width: 700px;
    }
    .ck-mega-menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .ck-header-top-bar {
        display: none;
    }

    .ck-mobile-toggle {
        display: flex;
    }

    .ck-primary-nav {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        display: none;
        max-height: 70vh;
        overflow-y: auto;
    }

    .ck-primary-nav.active {
        display: block;
    }

    .ck-nav-menu {
        flex-direction: column;
        height: auto;
        padding: 20px;
    }

    .ck-menu-item {
        width: 100%;
        height: auto;
    }

    .ck-menu-item > a {
        padding: 15px;
        justify-content: space-between;
    }

    .ck-menu-item.ck-highlight > a {
        margin: 10px 0;
    }

    .ck-dropdown-menu,
    .ck-mega-menu {
        position: static;
        transform: none;
        box-shadow: none;
        width: 100%;
        opacity: 1;
        visibility: hidden;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s;
    }

    .ck-has-dropdown.open .ck-dropdown-menu,
    .ck-has-mega-menu.open .ck-mega-menu {
        visibility: visible;
        max-height: 500px;
    }

    .ck-mega-menu-grid {
        grid-template-columns: 1fr;
    }

    .ck-header-actions {
        display: none;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Mobile menu toggle
    $('#ck-mobile-toggle').on('click', function() {
        $('#ck-primary-nav').toggleClass('active');
        $(this).toggleClass('active');
    });

    // Mobile dropdown toggle
    $('.ck-has-dropdown > a, .ck-has-mega-menu > a').on('click', function(e) {
        if ($(window).width() <= 992) {
            e.preventDefault();
            $(this).parent().toggleClass('open').siblings().removeClass('open');
        }
    });

    // Sticky header shadow
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 50) {
            $('#ck-main-header').addClass('scrolled');
        } else {
            $('#ck-main-header').removeClass('scrolled');
        }
    });

    // Close menus when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ck-main-header').length) {
            $('#ck-primary-nav').removeClass('active');
            $('.ck-has-dropdown, .ck-has-mega-menu').removeClass('open');
        }
    });
});
</script>
