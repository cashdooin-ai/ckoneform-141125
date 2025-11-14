<?php
/**
 * Simple Debug Template - Find the issue
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('ABSPATH')) {
    exit;
}

echo '<!-- OneForm Debug: Template loaded -->';
?>
<div class="ck-oneform-home-debug" style="padding: 40px; background: #f0f0f0;">

    <h1 style="color: #333;">OneForm Homepage - Debug Mode</h1>

    <div style="background: white; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h2>System Check</h2>
        <ul>
            <li>✓ Template file loaded successfully</li>
            <li>✓ WordPress version: <?php echo get_bloginfo('version'); ?></li>
            <li>✓ Plugin directory: <?php echo CK_ONEFORM_PLUGIN_DIR; ?></li>
            <li>✓ Current user: <?php echo is_user_logged_in() ? 'Logged in' : 'Not logged in'; ?></li>
        </ul>
    </div>

    <div style="background: #667eea; color: white; padding: 60px 20px; text-align: center; border-radius: 8px;">
        <h1 style="font-size: 2.5rem; margin-bottom: 20px;">Apply to Multiple Colleges</h1>
        <h2 style="font-size: 1.5rem; opacity: 0.9; margin-bottom: 30px;">with One Form</h2>
        <p style="font-size: 1.1rem; margin-bottom: 30px;">Fill one application and apply to 500+ colleges across India</p>

        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <?php if (!is_user_logged_in()) : ?>
                <a href="<?php echo wp_registration_url(); ?>" style="padding: 15px 40px; background: white; color: #667eea; text-decoration: none; border-radius: 5px; font-weight: 600;">
                    Register Now - Free
                </a>
                <a href="#" style="padding: 15px 40px; background: transparent; color: white; text-decoration: none; border-radius: 5px; border: 2px solid white; font-weight: 600;">
                    How It Works
                </a>
            <?php else : ?>
                <a href="<?php echo home_url('/application-form/'); ?>" style="padding: 15px 40px; background: white; color: #667eea; text-decoration: none; border-radius: 5px; font-weight: 600;">
                    Start Application
                </a>
                <a href="<?php echo home_url('/my-applications/'); ?>" style="padding: 15px 40px; background: transparent; color: white; text-decoration: none; border-radius: 5px; border: 2px solid white; font-weight: 600;">
                    My Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div style="background: white; padding: 40px 20px; margin: 20px 0; border-radius: 8px;">
        <h2 style="text-align: center; margin-bottom: 30px;">How It Works</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem;">📝</div>
                <h3>1. Register & Fill Form</h3>
                <p>Create account and fill one application</p>
            </div>
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem;">🏛️</div>
                <h3>2. Select Colleges</h3>
                <p>Choose up to 10 colleges</p>
            </div>
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem;">✅</div>
                <h3>3. Submit & Track</h3>
                <p>Track status from dashboard</p>
            </div>
        </div>
    </div>

    <div style="background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h3>Database Status:</h3>
        <?php
        global $wpdb;

        // Check tables
        $tables = array(
            'ck_oneform_applications',
            'ck_oneform_submissions_meta',
            'ck_oneform_payments',
            'ck_oneform_documents'
        );

        echo '<ul>';
        foreach ($tables as $table) {
            $full_table = $wpdb->prefix . $table;
            $exists = $wpdb->get_var("SHOW TABLES LIKE '$full_table'");
            if ($exists) {
                echo "<li style='color: green;'>✓ Table $table exists</li>";
            } else {
                echo "<li style='color: red;'>✗ Table $table missing</li>";
            }
        }

        // Check post types
        $college_count = wp_count_posts('ck_college');
        $course_count = wp_count_posts('ck_course');

        echo "<li>Colleges: " . (isset($college_count->publish) ? $college_count->publish : 0) . "</li>";
        echo "<li>Courses: " . (isset($course_count->publish) ? $course_count->publish : 0) . "</li>";
        echo '</ul>';
        ?>
    </div>

    <div style="text-align: center; margin: 40px 0;">
        <p><strong>If you see this page, the plugin is working!</strong></p>
        <p>The white page issue might be in the CSS or JavaScript files.</p>
    </div>

</div>
<?php
echo '<!-- OneForm Debug: Template completed -->';
?>
