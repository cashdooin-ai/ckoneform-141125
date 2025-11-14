<?php
/**
 * Enhanced Application Form with Multi-College Selection
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

// Get all colleges for multi-select
$all_colleges = get_posts(array(
    'post_type' => 'ck_college',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
));

// Group colleges by state for better organization
$colleges_by_state = array();
foreach ($all_colleges as $college) {
    $location = get_post_meta($college->ID, '_ck_college_location', true);
    $state = explode(',', $location);
    $state = isset($state[1]) ? trim($state[1]) : 'Other';

    if (!isset($colleges_by_state[$state])) {
        $colleges_by_state[$state] = array();
    }
    $colleges_by_state[$state][] = $college;
}
ksort($colleges_by_state);
?>

<div class="ck-oneform-application ck-oneform-multi-college">
    <div class="container">
        <div class="application-header">
            <h1><?php _e('Apply to Multiple Colleges with One Form', 'ck-oneform'); ?></h1>
            <p class="subtitle"><?php _e('Select your preferred colleges and apply to all of them with a single application', 'ck-oneform'); ?></p>
        </div>

        <form id="ck-oneform-application-form" class="ck-oneform-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('ck-oneform-application', 'nonce'); ?>
            <input type="hidden" name="form_id" value="<?php echo esc_attr($atts['form_id']); ?>">

            <!-- College Selection Section - MAIN FEATURE -->
            <div class="form-section college-selection-section">
                <h2><?php _e('Select Colleges', 'ck-oneform'); ?> <span class="required">*</span></h2>
                <p class="section-description">
                    <?php _e('Choose up to 10 colleges where you want to apply. Your application will be sent to all selected colleges.', 'ck-oneform'); ?>
                </p>

                <!-- Search/Filter -->
                <div class="college-filters">
                    <input type="text" id="college-search" class="form-control" placeholder="<?php _e('Search colleges by name or location...', 'ck-oneform'); ?>">

                    <div class="filter-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">
                            <?php _e('All Colleges', 'ck-oneform'); ?> (<?php echo count($all_colleges); ?>)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="government">
                            <?php _e('Government', 'ck-oneform'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="private">
                            <?php _e('Private', 'ck-oneform'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="engineering">
                            <?php _e('Engineering', 'ck-oneform'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="medical">
                            <?php _e('Medical', 'ck-oneform'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="management">
                            <?php _e('Management', 'ck-oneform'); ?>
                        </button>
                    </div>
                </div>

                <!-- Selected Colleges Counter -->
                <div class="selected-colleges-info">
                    <span id="selected-count">0</span> <?php _e('of 10 colleges selected', 'ck-oneform'); ?>
                    <button type="button" id="clear-selection" class="btn btn-sm btn-text">
                        <?php _e('Clear All', 'ck-oneform'); ?>
                    </button>
                </div>

                <!-- Colleges Grid -->
                <div class="colleges-grid" id="colleges-container">
                    <?php foreach ($colleges_by_state as $state => $colleges) : ?>
                        <div class="state-group">
                            <h3 class="state-header"><?php echo esc_html($state); ?></h3>

                            <?php foreach ($colleges as $college) :
                                $type = get_post_meta($college->ID, '_ck_college_type', true);
                                $category = get_post_meta($college->ID, '_ck_college_category', true);
                                $location = get_post_meta($college->ID, '_ck_college_location', true);
                                $ranking = get_post_meta($college->ID, '_ck_college_ranking', true);
                            ?>
                                <div class="college-item"
                                     data-type="<?php echo esc_attr($type); ?>"
                                     data-category="<?php echo esc_attr($category); ?>"
                                     data-name="<?php echo esc_attr(strtolower($college->post_title)); ?>"
                                     data-location="<?php echo esc_attr(strtolower($location)); ?>">

                                    <label class="college-checkbox">
                                        <input type="checkbox"
                                               name="colleges[]"
                                               value="<?php echo esc_attr($college->ID); ?>"
                                               class="college-checkbox-input">

                                        <div class="college-card">
                                            <div class="college-card-header">
                                                <h4 class="college-name"><?php echo esc_html($college->post_title); ?></h4>
                                                <?php if ($ranking) : ?>
                                                    <span class="college-rank">#<?php echo esc_html($ranking); ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="college-meta">
                                                <span class="college-location">
                                                    <i class="dashicons dashicons-location"></i>
                                                    <?php echo esc_html($location); ?>
                                                </span>

                                                <span class="college-type badge badge-<?php echo esc_attr($type); ?>">
                                                    <?php echo esc_html(ucfirst($type)); ?>
                                                </span>

                                                <?php if ($category) : ?>
                                                    <span class="college-category badge badge-secondary">
                                                        <?php echo esc_html(ucfirst($category)); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="college-checkmark">
                                                <i class="dashicons dashicons-yes"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- No Results Message -->
                <div id="no-colleges-message" style="display: none;">
                    <p><?php _e('No colleges found matching your criteria.', 'ck-oneform'); ?></p>
                </div>
            </div>

            <!-- Rest of the form sections -->
            <div class="form-section">
                <h2><?php _e('Personal Information', 'ck-oneform'); ?></h2>

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
                <h2><?php _e('Academic Information', 'ck-oneform'); ?></h2>

                <div class="form-group">
                    <label for="course_id">
                        <?php _e('Preferred Course', 'ck-oneform'); ?> <span class="required">*</span>
                    </label>
                    <select class="form-control" id="course_id" name="course_id" required>
                        <option value=""><?php _e('Select Course...', 'ck-oneform'); ?></option>
                        <?php
                        $courses = get_posts(array('post_type' => 'ck_course', 'posts_per_page' => -1));
                        foreach ($courses as $course) {
                            echo '<option value="' . esc_attr($course->ID) . '">' . esc_html($course->post_title) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="previous_qualification"><?php _e('Previous Qualification', 'ck-oneform'); ?> <span class="required">*</span></label>
                    <input type="text" class="form-control" id="previous_qualification" name="previous_qualification" required>
                </div>

                <div class="form-group">
                    <label for="percentage"><?php _e('Percentage/CGPA', 'ck-oneform'); ?> <span class="required">*</span></label>
                    <input type="text" class="form-control" id="percentage" name="percentage" required>
                </div>
            </div>

            <!-- Document Upload -->
            <div class="form-section">
                <h2><?php _e('Document Upload', 'ck-oneform'); ?></h2>

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

            <!-- Summary Section -->
            <div class="form-section application-summary">
                <h2><?php _e('Application Summary', 'ck-oneform'); ?></h2>
                <div id="selected-colleges-summary">
                    <p><?php _e('Please select at least one college from the list above.', 'ck-oneform'); ?></p>
                </div>
            </div>

            <div class="form-message"></div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <?php _e('Submit Application to Selected Colleges', 'ck-oneform'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Multi-College Selection Styles */
.college-selection-section {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
}

.college-filters {
    margin-bottom: 25px;
}

#college-search {
    margin-bottom: 15px;
}

.filter-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-btn {
    transition: all 0.3s ease;
}

.filter-btn.active {
    background-color: #667eea;
    color: white;
    border-color: #667eea;
}

.selected-colleges-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 6px;
    margin-bottom: 20px;
    font-weight: 600;
}

#selected-count {
    color: #667eea;
    font-size: 1.2em;
}

.colleges-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.state-group {
    background: white;
    padding: 20px;
    border-radius: 8px;
}

.state-header {
    color: #667eea;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.college-item {
    margin-bottom: 10px;
}

.college-checkbox {
    display: block;
    cursor: pointer;
    margin: 0;
}

.college-checkbox-input {
    display: none;
}

.college-card {
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    transition: all 0.3s ease;
    position: relative;
}

.college-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.college-checkbox-input:checked + .college-card {
    border-color: #667eea;
    background-color: #f0f4ff;
}

.college-card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}

.college-name {
    margin: 0;
    font-size: 1.1em;
    color: #333;
}

.college-rank {
    background: #ffc107;
    color: #000;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: bold;
}

.college-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    font-size: 0.9em;
}

.college-location {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
}

.college-checkmark {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background: #667eea;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    color: white;
}

.college-checkbox-input:checked ~ .college-card .college-checkmark {
    display: flex;
}

.application-summary {
    background: #e8f5e9;
    border-left: 4px solid #4caf50;
}

#selected-colleges-summary {
    padding: 10px;
}

@media (max-width: 768px) {
    .filter-buttons {
        flex-direction: column;
    }

    .filter-btn {
        width: 100%;
    }
}
</style>
