<?php
/**
 * WordPress Template for Single College Post
 * This template is loaded by WordPress for single ck_college posts
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get the college ID from the current query
global $post;
$college_id = $post ? $post->ID : 0;

// Set up attributes for the template
$atts = array('id' => $college_id);

// Include our comprehensive college detail template
// This template includes its own header and footer
if (file_exists(CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/single-college.php')) {
    include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/single-college.php';
} else {
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Template Not Found</title>
        <?php wp_head(); ?>
    </head>
    <body>
        <div style="padding: 50px; text-align: center;">
            <h1>Template Error</h1>
            <p>College detail template not found.</p>
            <a href="<?php echo home_url('/colleges/'); ?>" style="color: #667eea;">← Back to Colleges</a>
        </div>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
}
?>
