<?php
/**
 * Complete Student Dashboard
 * All features: Applications, Services, Mock Tests, Offers, Profile
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check authentication
if (!CK_OneForm_Student_Auth::is_student_logged_in()) {
    echo '<p>Please <a href="' . home_url('/student-login/') . '">login</a> to access your dashboard.</p>';
    return;
}

$student = CK_OneForm_Student_Auth::get_current_student();
$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';

// Get statistics
global $wpdb;
$student_id = $student->id;

$total_applications = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_applications WHERE student_id = %d",
    $student_id
));

$total_services = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_student_services WHERE student_id = %d AND status = 'active'",
    $student_id
));

$total_tests = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_student_tests WHERE student_id = %d",
    $student_id
));

$total_offers = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_student_offers WHERE student_id = %d AND is_used = 0",
    $student_id
));
?>

<div class="ck-student-dashboard-full">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="student-info">
                <div class="student-avatar">
                    <?php echo strtoupper(substr($student->full_name, 0, 1)); ?>
                </div>
                <div>
                    <h1>Welcome back, <?php echo esc_html($student->full_name); ?>! 👋</h1>
                    <p>Student ID: <?php echo esc_html($student->student_id); ?> | Last login: <?php echo $student->last_login ? date('M d, Y', strtotime($student->last_login)) : 'First time'; ?></p>
                </div>
            </div>
            <div class="header-actions">
                <a href="#" id="dashboard-logout" class="btn-logout">🚪 Logout</a>
            </div>
        </div>
    </div>

    <!-- Dashboard Navigation -->
    <div class="dashboard-nav">
        <a href="?tab=overview" class="nav-item <?php echo $current_tab === 'overview' ? 'active' : ''; ?>">
            📊 Overview
        </a>
        <a href="?tab=applications" class="nav-item <?php echo $current_tab === 'applications' ? 'active' : ''; ?>">
            📝 Applications <?php if ($total_applications > 0) echo '<span class="badge">' . $total_applications . '</span>'; ?>
        </a>
        <a href="?tab=services" class="nav-item <?php echo $current_tab === 'services' ? 'active' : ''; ?>">
            🎯 My Services <?php if ($total_services > 0) echo '<span class="badge">' . $total_services . '</span>'; ?>
        </a>
        <a href="?tab=tests" class="nav-item <?php echo $current_tab === 'tests' ? 'active' : ''; ?>">
            📚 Mock Tests <?php if ($total_tests > 0) echo '<span class="badge">' . $total_tests . '</span>'; ?>
        </a>
        <a href="?tab=offers" class="nav-item <?php echo $current_tab === 'offers' ? 'active' : ''; ?>">
            🎁 My Offers <?php if ($total_offers > 0) echo '<span class="badge">' . $total_offers . '</span>'; ?>
        </a>
        <a href="?tab=profile" class="nav-item <?php echo $current_tab === 'profile' ? 'active' : ''; ?>">
            👤 Profile
        </a>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        
        <?php if ($current_tab === 'overview'): ?>
            <!-- Overview Tab -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-content">
                        <h3><?php echo $total_applications; ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🎯</div>
                    <div class="stat-content">
                        <h3><?php echo $total_services; ?></h3>
                        <p>Active Services</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-content">
                        <h3><?php echo $total_tests; ?></h3>
                        <p>Mock Tests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🎁</div>
                    <div class="stat-content">
                        <h3><?php echo $total_offers; ?></h3>
                        <p>Available Offers</p>
                    </div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="dashboard-section">
                <h2>📝 Recent Applications</h2>
                <?php
                $applications = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}ck_oneform_applications 
                    WHERE student_id = %d 
                    ORDER BY submission_date DESC 
                    LIMIT 5",
                    $student_id
                ));

                if ($applications):
                ?>
                    <div class="applications-list">
                        <?php foreach ($applications as $app): 
                            $status_class = strtolower($app->status);
                        ?>
                            <div class="application-item">
                                <div class="app-info">
                                    <h4>Application #<?php echo esc_html($app->application_number); ?></h4>
                                    <p>Submitted: <?php echo date('M d, Y', strtotime($app->submission_date)); ?></p>
                                </div>
                                <div class="app-status status-<?php echo $status_class; ?>">
                                    <?php echo ucfirst($app->status); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="?tab=applications" class="btn-view-all">View All Applications →</a>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No applications yet. <a href="<?php echo home_url('/colleges/'); ?>">Browse colleges</a> and apply now!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2>⚡ Quick Actions</h2>
                <div class="actions-grid">
                    <a href="<?php echo home_url('/colleges/'); ?>" class="action-card">
                        <span class="action-icon">🏛️</span>
                        <span class="action-title">Browse Colleges</span>
                    </a>
                    <a href="<?php echo home_url('/apply/'); ?>" class="action-card">
                        <span class="action-icon">📝</span>
                        <span class="action-title">Apply Now</span>
                    </a>
                    <a href="<?php echo home_url('/mock-tests/'); ?>" class="action-card">
                        <span class="action-icon">📚</span>
                        <span class="action-title">Take Mock Test</span>
                    </a>
                    <a href="?tab=profile" class="action-card">
                        <span class="action-icon">👤</span>
                        <span class="action-title">Edit Profile</span>
                    </a>
                </div>
            </div>

        <?php elseif ($current_tab === 'applications'): ?>
            <!-- Applications Tab -->
            <div class="dashboard-section">
                <h2>📝 My Applications</h2>
                <?php
                $all_applications = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}ck_oneform_applications 
                    WHERE student_id = %d 
                    ORDER BY submission_date DESC",
                    $student_id
                ));

                if ($all_applications):
                ?>
                    <div class="applications-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Application Number</th>
                                    <th>Colleges</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_applications as $app): 
                                    $college_ids = !empty($app->college_ids) ? explode(',', $app->college_ids) : array();
                                    $college_count = count($college_ids);
                                ?>
                                    <tr>
                                        <td><strong><?php echo esc_html($app->application_number); ?></strong></td>
                                        <td><?php echo $college_count; ?> college(s)</td>
                                        <td><?php echo date('M d, Y', strtotime($app->submission_date)); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($app->status); ?>">
                                                <?php echo ucfirst($app->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn-view-app" data-app-id="<?php echo $app->id; ?>">View Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No applications yet</h3>
                        <p>Start your journey by applying to colleges!</p>
                        <a href="<?php echo home_url('/colleges/'); ?>" class="btn-primary">Browse Colleges</a>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($current_tab === 'services'): ?>
            <!-- Services Tab -->
            <div class="dashboard-section">
                <h2>🎯 Available Services</h2>
                <p style="margin-bottom: 30px; color: #666;">Explore our comprehensive range of services to help you succeed in your college journey</p>

                <?php
                // Load service pages data
                $services_data_file = CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';
                if (file_exists($services_data_file)) {
                    $services_data = include $services_data_file;
                    $service_count = 0;

                    // Get featured services from admin settings
                    $featured_services = get_option('ck_service_featured_list', array(
                        'college-search', 'scholarships-database', 'exam-calendar',
                        'career-guidance', 'education-loans', 'mock-tests',
                        'college-comparison', 'application-tracking', 'document-verification',
                        'entrance-exams', 'career-counseling', 'merit-scholarships'
                    ));
                ?>
                    <div class="services-grid">
                        <?php
                        foreach ($services_data as $category => $category_services):
                            foreach ($category_services as $service):
                                // Show only featured services
                                if (in_array($service['slug'], $featured_services) && $service_count < 12):
                                    $service_count++;
                        ?>
                            <div class="service-card">
                                <div class="service-icon" style="font-size: 3rem;"><?php echo $service['icon']; ?></div>
                                <h3><?php echo esc_html($service['title']); ?></h3>
                                <p><?php echo esc_html($service['desc']); ?></p>
                                <a href="<?php echo esc_url(home_url($service['url'])); ?>" class="btn-primary" style="margin-top: 15px; display: inline-block;">
                                    Explore Service →
                                </a>
                            </div>
                        <?php
                                endif;
                            endforeach;
                        endforeach;
                        ?>
                    </div>

                    <div style="text-align: center; margin-top: 40px;">
                        <a href="<?php echo home_url('/services/'); ?>" class="btn-primary btn-large">
                            View All Services →
                        </a>
                    </div>
                <?php
                } else {
                ?>
                    <div class="empty-state">
                        <h3>No services available</h3>
                        <p>Services will appear here once they are configured.</p>
                    </div>
                <?php
                }
                ?>
            </div>

        <?php elseif ($current_tab === 'tests'): ?>
            <!-- Mock Tests Tab -->
            <div class="dashboard-section">
                <h2>📚 My Mock Tests</h2>
                <?php
                $tests = $wpdb->get_results($wpdb->prepare(
                    "SELECT st.*, mt.title, mt.exam_type, mt.duration, mt.total_questions, mt.total_marks
                    FROM {$wpdb->prefix}ck_oneform_student_tests st
                    INNER JOIN {$wpdb->prefix}ck_oneform_mock_tests mt ON st.test_id = mt.id
                    WHERE st.student_id = %d
                    ORDER BY st.purchased_date DESC",
                    $student_id
                ));

                if ($tests):
                ?>
                    <div class="tests-grid">
                        <?php foreach ($tests as $test): ?>
                            <div class="test-card">
                                <div class="test-header">
                                    <h3><?php echo esc_html($test->title); ?></h3>
                                    <span class="test-type"><?php echo esc_html($test->exam_type); ?></span>
                                </div>
                                <div class="test-details">
                                    <span>⏱️ <?php echo $test->duration; ?> mins</span>
                                    <span>❓ <?php echo $test->total_questions; ?> questions</span>
                                    <span>📊 <?php echo $test->total_marks; ?> marks</span>
                                </div>
                                <div class="test-stats">
                                    <div class="stat">
                                        <strong>Attempts:</strong> <?php echo $test->attempts; ?>
                                    </div>
                                    <?php if ($test->best_score): ?>
                                        <div class="stat">
                                            <strong>Best Score:</strong> <?php echo $test->best_score; ?>%
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button class="btn-take-test" data-test-id="<?php echo $test->test_id; ?>">
                                    <?php echo $test->attempts > 0 ? 'Retake Test' : 'Start Test'; ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No mock tests yet</h3>
                        <p>Practice makes perfect! Get mock tests to prepare for exams.</p>
                        <a href="<?php echo home_url('/mock-tests/'); ?>" class="btn-primary">Browse Mock Tests</a>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($current_tab === 'offers'): ?>
            <!-- Offers Tab -->
            <div class="dashboard-section">
                <h2>🎁 My Offers</h2>
                <?php
                $offers = $wpdb->get_results($wpdb->prepare(
                    "SELECT so.*, o.title, o.description, o.offer_type, o.discount_value, o.discount_type, o.valid_until, o.banner_image
                    FROM {$wpdb->prefix}ck_oneform_student_offers so
                    INNER JOIN {$wpdb->prefix}ck_oneform_offers o ON so.offer_id = o.id
                    WHERE so.student_id = %d
                    ORDER BY so.assigned_date DESC",
                    $student_id
                ));

                if ($offers):
                ?>
                    <div class="offers-grid">
                        <?php foreach ($offers as $offer): 
                            $is_expired = strtotime($offer->valid_until) < time();
                            $is_used = $offer->is_used == 1;
                        ?>
                            <div class="offer-card <?php echo $is_used ? 'used' : ($is_expired ? 'expired' : ''); ?>">
                                <?php if ($offer->banner_image): ?>
                                    <div class="offer-image">
                                        <img src="<?php echo esc_url($offer->banner_image); ?>" alt="<?php echo esc_attr($offer->title); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="offer-content">
                                    <h3><?php echo esc_html($offer->title); ?></h3>
                                    <p><?php echo esc_html($offer->description); ?></p>
                                    <div class="offer-discount">
                                        <?php 
                                        if ($offer->discount_type === 'percentage') {
                                            echo '<strong>' . $offer->discount_value . '% OFF</strong>';
                                        } else {
                                            echo '<strong>₹' . number_format($offer->discount_value) . ' OFF</strong>';
                                        }
                                        ?>
                                    </div>
                                    <div class="offer-validity">
                                        Valid until: <?php echo date('M d, Y', strtotime($offer->valid_until)); ?>
                                    </div>
                                    <?php if ($is_used): ?>
                                        <span class="offer-status">✅ Used on <?php echo date('M d, Y', strtotime($offer->used_date)); ?></span>
                                    <?php elseif ($is_expired): ?>
                                        <span class="offer-status expired">❌ Expired</span>
                                    <?php else: ?>
                                        <button class="btn-use-offer" data-offer-id="<?php echo $offer->offer_id; ?>">
                                            Use This Offer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No offers available</h3>
                        <p>Check back later for special offers and discounts!</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($current_tab === 'profile'): ?>
            <!-- Profile Tab -->
            <div class="dashboard-section">
                <h2>👤 My Profile</h2>
                <form id="profile-update-form" class="profile-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?php echo esc_attr($student->full_name); ?>" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo esc_attr($student->email); ?>" disabled class="form-control">
                            <small>Email cannot be changed</small>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="tel" name="mobile" value="<?php echo esc_attr($student->mobile); ?>" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?php echo esc_attr($student->dob); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="male" <?php selected($student->gender, 'male'); ?>>Male</option>
                                <option value="female" <?php selected($student->gender, 'female'); ?>>Female</option>
                                <option value="other" <?php selected($student->gender, 'other'); ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option value="">Select Category</option>
                                <option value="general" <?php selected($student->category, 'general'); ?>>General</option>
                                <option value="obc" <?php selected($student->category, 'obc'); ?>>OBC</option>
                                <option value="sc" <?php selected($student->category, 'sc'); ?>>SC</option>
                                <option value="st" <?php selected($student->category, 'st'); ?>>ST</option>
                                <option value="ews" <?php selected($student->category, 'ews'); ?>>EWS</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label>Address</label>
                            <textarea name="address" rows="3" class="form-control"><?php echo esc_textarea($student->address); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" value="<?php echo esc_attr($student->state); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" value="<?php echo esc_attr($student->city); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>PIN Code</label>
                            <input type="text" name="pincode" value="<?php echo esc_attr($student->pincode); ?>" pattern="[0-9]{6}" class="form-control">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">💾 Update Profile</button>
                    </div>
                </form>

                <!-- Change Password Section -->
                <div class="password-section">
                    <h3>🔐 Change Password</h3>
                    <form id="password-change-form">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label>Current Password</label>
                                <input type="password" name="current_password" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required minlength="6" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" required class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn-secondary">Change Password</button>
                    </form>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Logout
    $('#dashboard-logout').on('click', function(e) {
        e.preventDefault();
        $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
            action: 'ck_student_logout'
        }, function() {
            window.location.href = '<?php echo home_url('/student-login/'); ?>';
        });
    });

    // Profile update
    $('#profile-update-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const originalText = $btn.html();

        $btn.prop('disabled', true).html('⏳ Updating...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_update_student_profile',
                nonce: '<?php echo wp_create_nonce('update-profile'); ?>',
                full_name: $form.find('[name="full_name"]').val(),
                mobile: $form.find('[name="mobile"]').val(),
                dob: $form.find('[name="dob"]').val(),
                gender: $form.find('[name="gender"]').val(),
                category: $form.find('[name="category"]').val(),
                address: $form.find('[name="address"]').val(),
                state: $form.find('[name="state"]').val(),
                city: $form.find('[name="city"]').val(),
                pincode: $form.find('[name="pincode"]').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ Profile updated successfully!');
                    location.reload();
                } else {
                    alert('❌ Error: ' + response.data.message);
                }
                $btn.prop('disabled', false).html(originalText);
            },
            error: function() {
                alert('❌ An error occurred. Please try again.');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Password change
    $('#password-change-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        
        if ($form.find('[name="new_password"]').val() !== $form.find('[name="confirm_password"]').val()) {
            alert('❌ Passwords do not match!');
            return;
        }

        const $btn = $form.find('button[type="submit"]');
        const originalText = $btn.html();

        $btn.prop('disabled', true).html('⏳ Changing...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_change_student_password',
                nonce: '<?php echo wp_create_nonce('change-password'); ?>',
                current_password: $form.find('[name="current_password"]').val(),
                new_password: $form.find('[name="new_password"]').val()
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ Password changed successfully!');
                    $form[0].reset();
                } else {
                    alert('❌ Error: ' + response.data.message);
                }
                $btn.prop('disabled', false).html(originalText);
            },
            error: function() {
                alert('❌ An error occurred. Please try again.');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
