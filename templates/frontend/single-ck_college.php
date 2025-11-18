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

// Get WordPress header
get_header();
?>

<div class="ck-college-single-wrapper">
    <?php
    // Start WordPress loop
    while (have_posts()) :
        the_post();

        // Set up variables for single-college.php template
        $college_id = get_the_ID();
        $atts = array('id' => $college_id);

        // Include our comprehensive college detail template
        include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/single-college.php';

    endwhile;
    ?>
</div>

<?php
// Get WordPress footer
get_footer();
?>
