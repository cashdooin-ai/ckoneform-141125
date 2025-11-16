<?php
/**
 * Header Navigation Template
 *
 * Include this in your templates to add consistent navigation
 * Usage: include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/header-navigation.php';
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current page info
$current_page_id = get_the_ID();
$current_page_slug = get_post_field('post_name', $current_page_id);

// Check if user is logged in
$is_logged_in = is_user_logged_in();
$is_student_logged_in = class_exists('CK_OneForm_Student_Auth') ? CK_OneForm_Student_Auth::is_student_logged_in() : false;
?>

<header class="ck-site-header">
    <div class="ck-header-container">
        <!-- Logo -->
        <div class="ck-logo">
            <a href="<?php echo home_url('/oneform-home/'); ?>">
                <span class="ck-logo-icon">CK</span>
                <span class="ck-logo-text">CollegeKampus</span>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="ck-mobile-menu-toggle" aria-label="Toggle Menu">
            <span class="dashicons dashicons-menu"></span>
        </button>

        <!-- Main Navigation -->
        <nav class="ck-main-nav">
            <?php
            if (has_nav_menu('ck-oneform-main')) {
                wp_nav_menu(array(
                    'theme_location' => 'ck-oneform-main',
                    'container' => false,
                    'menu_class' => 'ck-nav-list',
                    'fallback_cb' => false,
                ));
            } else {
                // Fallback navigation
                ?>
                <ul class="ck-nav-list">
                    <li class="<?php echo $current_page_slug === 'oneform-home' ? 'current-menu-item' : ''; ?>">
                        <a href="<?php echo home_url('/oneform-home/'); ?>"><?php _e('Home', 'ck-oneform'); ?></a>
                    </li>
                    <li class="ck-has-dropdown <?php echo $current_page_slug === 'courses' || $current_page_slug === 'colleges' ? 'current-menu-item' : ''; ?>">
                        <a href="#"><?php _e('Academics', 'ck-oneform'); ?> <span class="dashicons dashicons-arrow-down-alt2"></span></a>
                        <ul class="ck-dropdown">
                            <li><a href="<?php echo home_url('/courses/'); ?>"><?php _e('Our Courses', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/colleges/'); ?>"><?php _e('Partner Colleges', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/services/'); ?>"><?php _e('All Services', 'ck-oneform'); ?></a></li>
                        </ul>
                    </li>
                    <li class="<?php echo $current_page_slug === 'application-form' ? 'current-menu-item' : ''; ?>">
                        <a href="<?php echo home_url('/application-form/'); ?>" class="ck-apply-btn"><?php _e('Apply Now', 'ck-oneform'); ?></a>
                    </li>
                    <li class="<?php echo $current_page_slug === 'mock-tests' ? 'current-menu-item' : ''; ?>">
                        <a href="<?php echo home_url('/mock-tests/'); ?>"><?php _e('Mock Tests', 'ck-oneform'); ?></a>
                    </li>
                    <li class="ck-has-dropdown">
                        <a href="#"><?php _e('Resources', 'ck-oneform'); ?> <span class="dashicons dashicons-arrow-down-alt2"></span></a>
                        <ul class="ck-dropdown">
                            <li><a href="<?php echo home_url('/about-us/'); ?>"><?php _e('About Us', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/faq/'); ?>"><?php _e('FAQ', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/contact-us/'); ?>"><?php _e('Contact Us', 'ck-oneform'); ?></a></li>
                            <li><a href="<?php echo home_url('/application-status/'); ?>"><?php _e('Track Application', 'ck-oneform'); ?></a></li>
                        </ul>
                    </li>
                </ul>
                <?php
            }
            ?>
        </nav>

        <!-- User Actions -->
        <div class="ck-user-actions">
            <?php if ($is_logged_in || $is_student_logged_in): ?>
                <div class="ck-user-menu">
                    <button class="ck-user-btn">
                        <span class="dashicons dashicons-admin-users"></span>
                        <span class="ck-user-name"><?php echo esc_html(wp_get_current_user()->display_name); ?></span>
                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                    </button>
                    <ul class="ck-user-dropdown">
                        <li><a href="<?php echo home_url('/student-dashboard/'); ?>"><span class="dashicons dashicons-dashboard"></span> <?php _e('Dashboard', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/my-applications/'); ?>"><span class="dashicons dashicons-list-view"></span> <?php _e('My Applications', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/mock-tests/'); ?>"><span class="dashicons dashicons-edit"></span> <?php _e('Mock Tests', 'ck-oneform'); ?></a></li>
                        <li><a href="<?php echo home_url('/payment/'); ?>"><span class="dashicons dashicons-money-alt"></span> <?php _e('Payments', 'ck-oneform'); ?></a></li>
                        <li class="ck-divider"></li>
                        <li><a href="<?php echo wp_logout_url(home_url('/oneform-home/')); ?>"><span class="dashicons dashicons-exit"></span> <?php _e('Logout', 'ck-oneform'); ?></a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="<?php echo home_url('/student-login/'); ?>" class="ck-btn-login">
                    <span class="dashicons dashicons-admin-users"></span>
                    <?php _e('Login', 'ck-oneform'); ?>
                </a>
                <a href="<?php echo home_url('/student-registration/'); ?>" class="ck-btn-register">
                    <?php _e('Register', 'ck-oneform'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Breadcrumbs -->
<?php
if (class_exists('CK_OneForm_Navigation')) {
    echo CK_OneForm_Navigation::get_breadcrumbs();
}
?>

<style>
.ck-site-header {
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
}

.ck-header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
}

.ck-logo a {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 10px;
}

.ck-logo-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 700;
    font-size: 20px;
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ck-logo-text {
    font-size: 22px;
    font-weight: 700;
    color: #333;
}

.ck-mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
}

.ck-mobile-menu-toggle .dashicons {
    font-size: 28px;
    width: 28px;
    height: 28px;
    color: #667eea;
}

.ck-main-nav {
    flex: 1;
    display: flex;
    justify-content: center;
}

.ck-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 5px;
}

.ck-nav-list > li {
    position: relative;
}

.ck-nav-list > li > a {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    border-radius: 8px;
    transition: background 0.3s, color 0.3s;
}

.ck-nav-list > li > a:hover,
.ck-nav-list > li.current-menu-item > a {
    background: #f0f0f0;
    color: #667eea;
}

.ck-nav-list > li > a .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}

.ck-apply-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.ck-apply-btn:hover {
    opacity: 0.9;
}

.ck-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    min-width: 220px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 10px;
    list-style: none;
    padding: 10px 0;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s;
    z-index: 100;
}

.ck-has-dropdown:hover .ck-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.ck-dropdown li a {
    display: block;
    padding: 10px 20px;
    color: #333;
    text-decoration: none;
    transition: background 0.3s;
}

.ck-dropdown li a:hover {
    background: #f8f9fa;
    color: #667eea;
}

.ck-user-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.ck-btn-login,
.ck-btn-register {
    padding: 8px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.ck-btn-login {
    color: #667eea;
    border: 2px solid #667eea;
}

.ck-btn-login:hover {
    background: #667eea;
    color: white;
}

.ck-btn-register {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.ck-btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.ck-user-menu {
    position: relative;
}

.ck-user-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s;
}

.ck-user-btn:hover {
    background: #e9ecef;
}

.ck-user-btn .dashicons {
    color: #667eea;
}

.ck-user-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: white;
    min-width: 220px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 10px;
    list-style: none;
    padding: 10px 0;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s;
    z-index: 100;
}

.ck-user-menu:hover .ck-user-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.ck-user-dropdown li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: #333;
    text-decoration: none;
    transition: background 0.3s;
}

.ck-user-dropdown li a:hover {
    background: #f8f9fa;
    color: #667eea;
}

.ck-user-dropdown .dashicons {
    color: #667eea;
}

.ck-divider {
    height: 1px;
    background: #e9ecef;
    margin: 8px 15px;
}

/* Mobile Styles */
@media (max-width: 992px) {
    .ck-mobile-menu-toggle {
        display: block;
    }

    .ck-main-nav {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: none;
    }

    .ck-main-nav.active {
        display: block;
    }

    .ck-nav-list {
        flex-direction: column;
        padding: 20px;
    }

    .ck-nav-list > li > a {
        justify-content: space-between;
    }

    .ck-dropdown {
        position: static;
        box-shadow: none;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 5px;
        opacity: 1;
        visibility: visible;
        transform: none;
        display: none;
    }

    .ck-has-dropdown.open .ck-dropdown {
        display: block;
    }

    .ck-user-actions {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        padding: 20px;
        border-top: 1px solid #e9ecef;
        flex-direction: column;
    }

    .ck-logo-text {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .ck-header-container {
        height: 60px;
    }

    .ck-logo-icon {
        width: 35px;
        height: 35px;
        font-size: 16px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Mobile menu toggle
    $('.ck-mobile-menu-toggle').on('click', function() {
        $('.ck-main-nav').toggleClass('active');
        $('.ck-user-actions').toggleClass('active');
    });

    // Mobile dropdown toggle
    $('.ck-has-dropdown > a').on('click', function(e) {
        if ($(window).width() <= 992) {
            e.preventDefault();
            $(this).parent().toggleClass('open');
        }
    });

    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ck-site-header').length) {
            $('.ck-main-nav').removeClass('active');
            $('.ck-user-actions').removeClass('active');
        }
    });
});
</script>
