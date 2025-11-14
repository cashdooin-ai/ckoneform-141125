<?php
/**
 * Frontend Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Frontend {

    public function __construct() {
        add_filter('body_class', array($this, 'add_body_classes'));
        add_action('wp_footer', array($this, 'add_modals'));
    }

    /**
     * Add custom body classes
     */
    public function add_body_classes($classes) {
        if (is_page()) {
            global $post;
            if (has_shortcode($post->post_content, 'ck_oneform')) {
                $classes[] = 'ck-oneform-page';
            }
        }
        return $classes;
    }

    /**
     * Add modals to footer
     */
    public function add_modals() {
        if (is_page()) {
            global $post;
            if (has_shortcode($post->post_content, 'ck_oneform')) {
                include CK_ONEFORM_PLUGIN_DIR . 'templates/frontend/modals.php';
            }
        }
    }

    /**
     * Get form fields
     */
    public static function get_form_fields($form_id = 0) {
        // Default fields for application form
        $fields = array(
            array(
                'name' => 'full_name',
                'label' => __('Full Name', 'ck-oneform'),
                'type' => 'text',
                'required' => true
            ),
            array(
                'name' => 'email',
                'label' => __('Email Address', 'ck-oneform'),
                'type' => 'email',
                'required' => true
            ),
            array(
                'name' => 'phone',
                'label' => __('Phone Number', 'ck-oneform'),
                'type' => 'tel',
                'required' => true
            ),
            array(
                'name' => 'date_of_birth',
                'label' => __('Date of Birth', 'ck-oneform'),
                'type' => 'date',
                'required' => true
            ),
            array(
                'name' => 'gender',
                'label' => __('Gender', 'ck-oneform'),
                'type' => 'select',
                'options' => array('Male', 'Female', 'Other'),
                'required' => true
            ),
            array(
                'name' => 'address',
                'label' => __('Address', 'ck-oneform'),
                'type' => 'textarea',
                'required' => true
            ),
            array(
                'name' => 'course_id',
                'label' => __('Course', 'ck-oneform'),
                'type' => 'select',
                'options' => 'courses', // Special flag to load courses
                'required' => true
            ),
            array(
                'name' => 'college_id',
                'label' => __('Preferred College', 'ck-oneform'),
                'type' => 'select',
                'options' => 'colleges',
                'required' => false
            ),
            array(
                'name' => 'photo',
                'label' => __('Photograph', 'ck-oneform'),
                'type' => 'file',
                'accept' => 'image/*',
                'required' => true
            ),
            array(
                'name' => 'documents',
                'label' => __('Supporting Documents', 'ck-oneform'),
                'type' => 'file',
                'accept' => '.pdf,.doc,.docx',
                'required' => false,
                'multiple' => true
            ),
        );

        return apply_filters('ck_oneform_form_fields', $fields, $form_id);
    }
}

new CK_OneForm_Frontend();
