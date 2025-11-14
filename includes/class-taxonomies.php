<?php
/**
 * Custom Taxonomies
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Taxonomies {

    /**
     * Register custom taxonomies
     */
    public static function register_taxonomies() {
        // Form Categories
        register_taxonomy('form_category', 'ck_oneform', array(
            'labels' => array(
                'name' => __('Form Categories', 'ck-oneform'),
                'singular_name' => __('Form Category', 'ck-oneform'),
                'search_items' => __('Search Form Categories', 'ck-oneform'),
                'all_items' => __('All Form Categories', 'ck-oneform'),
                'edit_item' => __('Edit Form Category', 'ck-oneform'),
                'update_item' => __('Update Form Category', 'ck-oneform'),
                'add_new_item' => __('Add New Form Category', 'ck-oneform'),
                'new_item_name' => __('New Form Category Name', 'ck-oneform'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'form-category'),
        ));

        // Course Categories
        register_taxonomy('course_category', 'ck_course', array(
            'labels' => array(
                'name' => __('Course Categories', 'ck-oneform'),
                'singular_name' => __('Course Category', 'ck-oneform'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'course-category'),
        ));

        // College Types
        register_taxonomy('college_type', 'ck_college', array(
            'labels' => array(
                'name' => __('College Types', 'ck-oneform'),
                'singular_name' => __('College Type', 'ck-oneform'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'college-type'),
        ));
    }
}
