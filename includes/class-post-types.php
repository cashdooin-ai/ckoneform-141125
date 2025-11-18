<?php
/**
 * Custom Post Types
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Post_Types {

    /**
     * Initialize hooks
     */
    public static function init() {
        add_filter('single_template', array(__CLASS__, 'load_single_college_template'));
    }

    /**
     * Load custom template for single college
     */
    public static function load_single_college_template($template) {
        if (is_singular('ck_college')) {
            $custom_template = CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/single-college.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }

    /**
     * Register custom taxonomies
     */
    public static function register_taxonomies() {
        // College Type Taxonomy
        register_taxonomy('college_type', 'ck_college', array(
            'labels' => array(
                'name' => __('College Types', 'ck-oneform'),
                'singular_name' => __('College Type', 'ck-oneform'),
                'search_items' => __('Search Types', 'ck-oneform'),
                'all_items' => __('All Types', 'ck-oneform'),
                'edit_item' => __('Edit Type', 'ck-oneform'),
                'update_item' => __('Update Type', 'ck-oneform'),
                'add_new_item' => __('Add New Type', 'ck-oneform'),
                'new_item_name' => __('New Type Name', 'ck-oneform'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'college-type'),
        ));

        // College State Taxonomy
        register_taxonomy('college_state', 'ck_college', array(
            'labels' => array(
                'name' => __('States', 'ck-oneform'),
                'singular_name' => __('State', 'ck-oneform'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'state'),
        ));

        // College City Taxonomy
        register_taxonomy('college_city', 'ck_college', array(
            'labels' => array(
                'name' => __('Cities', 'ck-oneform'),
                'singular_name' => __('City', 'ck-oneform'),
            ),
            'hierarchical' => true,
            'show_admin_column' => false,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'city'),
        ));
    }

    /**
     * Register custom post types
     */
    public static function register_post_types() {
        // Register Forms post type
        register_post_type('ck_oneform', array(
            'labels' => array(
                'name' => __('Application Forms', 'ck-oneform'),
                'singular_name' => __('Application Form', 'ck-oneform'),
                'add_new' => __('Add New Form', 'ck-oneform'),
                'add_new_item' => __('Add New Application Form', 'ck-oneform'),
                'edit_item' => __('Edit Application Form', 'ck-oneform'),
                'new_item' => __('New Application Form', 'ck-oneform'),
                'view_item' => __('View Application Form', 'ck-oneform'),
                'search_items' => __('Search Application Forms', 'ck-oneform'),
                'not_found' => __('No application forms found', 'ck-oneform'),
                'not_found_in_trash' => __('No application forms found in trash', 'ck-oneform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-feedback',
            'supports' => array('title', 'editor', 'thumbnail'),
            'rewrite' => array('slug' => 'application-forms'),
            'capability_type' => 'post',
        ));

        // Register Courses post type
        register_post_type('ck_course', array(
            'labels' => array(
                'name' => __('Courses', 'ck-oneform'),
                'singular_name' => __('Course', 'ck-oneform'),
                'add_new' => __('Add New Course', 'ck-oneform'),
                'add_new_item' => __('Add New Course', 'ck-oneform'),
                'edit_item' => __('Edit Course', 'ck-oneform'),
                'new_item' => __('New Course', 'ck-oneform'),
                'view_item' => __('View Course', 'ck-oneform'),
                'search_items' => __('Search Courses', 'ck-oneform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => 'edit.php?post_type=ck_oneform',
            'menu_icon' => 'dashicons-welcome-learn-more',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'rewrite' => array('slug' => 'courses'),
        ));

        // Register Colleges post type
        register_post_type('ck_college', array(
            'labels' => array(
                'name' => __('Colleges', 'ck-oneform'),
                'singular_name' => __('College', 'ck-oneform'),
                'add_new' => __('Add New College', 'ck-oneform'),
                'add_new_item' => __('Add New College', 'ck-oneform'),
                'edit_item' => __('Edit College', 'ck-oneform'),
                'new_item' => __('New College', 'ck-oneform'),
                'view_item' => __('View College', 'ck-oneform'),
                'search_items' => __('Search Colleges', 'ck-oneform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => 'edit.php?post_type=ck_oneform',
            'menu_icon' => 'dashicons-building',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'rewrite' => array('slug' => 'colleges'),
        ));
    }
}
