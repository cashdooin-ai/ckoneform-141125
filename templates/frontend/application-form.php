<?php
/**
 * Application Form Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!is_user_logged_in()) {
    echo '<p>' . __('Please login to submit an application.', 'ck-oneform') . ' <a href="' . wp_login_url(get_permalink()) . '">' . __('Login', 'ck-oneform') . '</a></p>';
    return;
}

$form_fields = CK_OneForm_Frontend::get_form_fields();
?>

<div class="ck-oneform-application">
    <div class="container">
        <h2><?php _e('Application Form', 'ck-oneform'); ?></h2>
        <p><?php _e('Please fill in all required fields to submit your application', 'ck-oneform'); ?></p>

        <form id="ck-oneform-application-form" class="ck-oneform-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('ck-oneform-application', 'nonce'); ?>
            <input type="hidden" name="form_id" value="<?php echo esc_attr($atts['form_id']); ?>">

            <div class="form-sections">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3><?php _e('Personal Information', 'ck-oneform'); ?></h3>

                    <?php foreach ($form_fields as $field) :
                        if (in_array($field['name'], array('full_name', 'email', 'phone', 'date_of_birth', 'gender', 'address'))) :
                    ?>
                        <div class="form-group">
                            <label for="<?php echo esc_attr($field['name']); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <?php if ($field['required']) : ?>
                                    <span class="required">*</span>
                                <?php endif; ?>
                            </label>

                            <?php if ($field['type'] == 'select') : ?>
                                <select class="form-control" id="<?php echo esc_attr($field['name']); ?>" name="<?php echo esc_attr($field['name']); ?>" <?php echo $field['required'] ? 'required' : ''; ?>>
                                    <option value=""><?php _e('Select...', 'ck-oneform'); ?></option>
                                    <?php foreach ($field['options'] as $option) : ?>
                                        <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($field['type'] == 'textarea') : ?>
                                <textarea class="form-control" id="<?php echo esc_attr($field['name']); ?>" name="<?php echo esc_attr($field['name']); ?>" rows="3" <?php echo $field['required'] ? 'required' : ''; ?>></textarea>
                            <?php else : ?>
                                <input type="<?php echo esc_attr($field['type']); ?>" class="form-control" id="<?php echo esc_attr($field['name']); ?>" name="<?php echo esc_attr($field['name']); ?>" <?php echo $field['required'] ? 'required' : ''; ?>>
                            <?php endif; ?>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>

                <!-- Academic Information -->
                <div class="form-section">
                    <h3><?php _e('Academic Information', 'ck-oneform'); ?></h3>

                    <?php foreach ($form_fields as $field) :
                        if (in_array($field['name'], array('course_id', 'college_id'))) :
                    ?>
                        <div class="form-group">
                            <label for="<?php echo esc_attr($field['name']); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <?php if ($field['required']) : ?>
                                    <span class="required">*</span>
                                <?php endif; ?>
                            </label>

                            <select class="form-control" id="<?php echo esc_attr($field['name']); ?>" name="<?php echo esc_attr($field['name']); ?>" <?php echo $field['required'] ? 'required' : ''; ?>>
                                <option value=""><?php _e('Select...', 'ck-oneform'); ?></option>
                                <?php
                                if ($field['options'] == 'courses') {
                                    $courses = get_posts(array('post_type' => 'ck_course', 'posts_per_page' => -1));
                                    foreach ($courses as $course) {
                                        echo '<option value="' . esc_attr($course->ID) . '">' . esc_html($course->post_title) . '</option>';
                                    }
                                } elseif ($field['options'] == 'colleges') {
                                    $colleges = get_posts(array('post_type' => 'ck_college', 'posts_per_page' => -1));
                                    foreach ($colleges as $college) {
                                        echo '<option value="' . esc_attr($college->ID) . '">' . esc_html($college->post_title) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>

                <!-- Document Upload -->
                <div class="form-section">
                    <h3><?php _e('Document Upload', 'ck-oneform'); ?></h3>

                    <?php foreach ($form_fields as $field) :
                        if ($field['type'] == 'file') :
                    ?>
                        <div class="form-group">
                            <label for="<?php echo esc_attr($field['name']); ?>">
                                <?php echo esc_html($field['label']); ?>
                                <?php if ($field['required']) : ?>
                                    <span class="required">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="file" class="form-control-file" id="<?php echo esc_attr($field['name']); ?>" name="<?php echo esc_attr($field['name']); ?>" accept="<?php echo esc_attr($field['accept']); ?>" <?php echo $field['required'] ? 'required' : ''; ?> <?php echo isset($field['multiple']) && $field['multiple'] ? 'multiple' : ''; ?>>
                            <small class="form-text text-muted">
                                <?php echo sprintf(__('Accepted formats: %s', 'ck-oneform'), $field['accept']); ?>
                            </small>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="form-message"></div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <?php _e('Submit Application', 'ck-oneform'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
