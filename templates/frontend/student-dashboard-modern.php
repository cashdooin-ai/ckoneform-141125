<?php
/**
 * Modern Student Dashboard Template
 * Features: Tab-based navigation, modern UI, admin-customizable sections
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$student = CK_OneForm_Student_Auth::get_current_student();
if (!$student) {
    wp_redirect(home_url('/student-login/'));
    exit;
}

global $wpdb;

// Get dashboard settings from admin
$dashboard_settings = get_option('ck_dashboard_settings', array(
    'show_overview' => true,
    'show_applications' => true,
    'show_services' => true,
    'show_tests' => true,
    'show_offers' => true,
    'show_documents' => true,
    'show_profile' => true,
    'welcome_message' => 'Welcome to your personalized dashboard!',
    'theme_color' => '#667eea',
    'announcement' => '',
));

// Get student statistics
$applications_count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_applications WHERE student_id = %d",
    $student->id
));
$services_count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_student_services WHERE student_id = %d",
    $student->id
));
$tests_count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_student_tests WHERE student_id = %d",
    $student->id
));
$offers_count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ck_oneform_student_offers WHERE student_id = %d",
    $student->id
));

// Build active tabs based on admin settings
$tabs = array();
if ($dashboard_settings['show_overview']) $tabs['overview'] = array('icon' => 'fas fa-home', 'label' => 'Overview');
if ($dashboard_settings['show_applications']) $tabs['applications'] = array('icon' => 'fas fa-file-alt', 'label' => 'Applications');
if ($dashboard_settings['show_services']) $tabs['services'] = array('icon' => 'fas fa-shopping-bag', 'label' => 'Services');
if ($dashboard_settings['show_tests']) $tabs['tests'] = array('icon' => 'fas fa-clipboard-check', 'label' => 'Mock Tests');
if ($dashboard_settings['show_offers']) $tabs['offers'] = array('icon' => 'fas fa-gift', 'label' => 'Offers');
if ($dashboard_settings['show_documents']) $tabs['documents'] = array('icon' => 'fas fa-folder', 'label' => 'Documents');
if ($dashboard_settings['show_profile']) $tabs['profile'] = array('icon' => 'fas fa-user-cog', 'label' => 'Profile');

$theme_color = $dashboard_settings['theme_color'];
?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="ck-dashboard-modern">
    <!-- Top Header Bar -->
    <div class="dashboard-header">
        <div class="header-left">
            <div class="user-avatar">
                <?php echo strtoupper(substr($student->full_name, 0, 2)); ?>
            </div>
            <div class="user-info">
                <h2>Hello, <?php echo esc_html(explode(' ', $student->full_name)[0]); ?>!</h2>
                <span class="student-id">ID: <?php echo esc_html($student->student_id); ?></span>
            </div>
        </div>
        <div class="header-right">
            <button class="btn-notification" id="notificationBtn">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>
            <button class="btn-logout" onclick="window.location.href='<?php echo wp_nonce_url(admin_url('admin-ajax.php?action=ck_student_logout'), 'ck-student-auth', 'nonce'); ?>'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </div>

    <?php if (!empty($dashboard_settings['announcement'])): ?>
    <!-- Announcement Banner -->
    <div class="announcement-banner">
        <i class="fas fa-bullhorn"></i>
        <span><?php echo wp_kses_post($dashboard_settings['announcement']); ?></span>
        <button class="close-announcement"><i class="fas fa-times"></i></button>
    </div>
    <?php endif; ?>

    <!-- Main Dashboard Layout -->
    <div class="dashboard-layout">
        <!-- Sidebar Navigation -->
        <div class="dashboard-sidebar">
            <nav class="sidebar-nav">
                <?php $first = true; foreach ($tabs as $key => $tab): ?>
                <button class="nav-item <?php echo $first ? 'active' : ''; ?>" data-tab="<?php echo $key; ?>">
                    <i class="<?php echo $tab['icon']; ?>"></i>
                    <span><?php echo $tab['label']; ?></span>
                </button>
                <?php $first = false; endforeach; ?>
            </nav>

            <!-- Quick Stats -->
            <div class="sidebar-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo intval($applications_count); ?></span>
                    <span class="stat-label">Applications</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo intval($services_count); ?></span>
                    <span class="stat-label">Services</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo intval($offers_count); ?></span>
                    <span class="stat-label">Offers</span>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="dashboard-content">
            <?php if ($dashboard_settings['show_overview']): ?>
            <!-- Overview Tab -->
            <div class="tab-panel active" id="panel-overview">
                <div class="panel-header">
                    <h3><i class="fas fa-home"></i> Dashboard Overview</h3>
                    <p><?php echo esc_html($dashboard_settings['welcome_message']); ?></p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card gradient-1">
                        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="stat-content">
                            <h4><?php echo intval($applications_count); ?></h4>
                            <p>Total Applications</p>
                        </div>
                    </div>
                    <div class="stat-card gradient-2">
                        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                        <div class="stat-content">
                            <h4><?php echo intval($services_count); ?></h4>
                            <p>Services Purchased</p>
                        </div>
                    </div>
                    <div class="stat-card gradient-3">
                        <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
                        <div class="stat-content">
                            <h4><?php echo intval($tests_count); ?></h4>
                            <p>Tests Completed</p>
                        </div>
                    </div>
                    <div class="stat-card gradient-4">
                        <div class="stat-icon"><i class="fas fa-gift"></i></div>
                        <div class="stat-content">
                            <h4><?php echo intval($offers_count); ?></h4>
                            <p>Available Offers</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="activity-section">
                    <h4><i class="fas fa-clock"></i> Recent Activity</h4>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                            <div class="activity-content">
                                <p>Account created successfully</p>
                                <span><?php echo date('M d, Y', strtotime($student->created_at)); ?></span>
                            </div>
                        </div>
                        <?php if ($student->last_login): ?>
                        <div class="activity-item">
                            <div class="activity-icon"><i class="fas fa-sign-in-alt"></i></div>
                            <div class="activity-content">
                                <p>Last login</p>
                                <span><?php echo date('M d, Y h:i A', strtotime($student->last_login)); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h4><i class="fas fa-bolt"></i> Quick Actions</h4>
                    <div class="actions-grid">
                        <button class="action-btn" onclick="switchTab('applications')">
                            <i class="fas fa-plus-circle"></i>
                            <span>New Application</span>
                        </button>
                        <button class="action-btn" onclick="switchTab('tests')">
                            <i class="fas fa-play-circle"></i>
                            <span>Take Mock Test</span>
                        </button>
                        <button class="action-btn" onclick="switchTab('profile')">
                            <i class="fas fa-user-edit"></i>
                            <span>Update Profile</span>
                        </button>
                        <button class="action-btn" onclick="switchTab('documents')">
                            <i class="fas fa-upload"></i>
                            <span>Upload Documents</span>
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($dashboard_settings['show_applications']): ?>
            <!-- Applications Tab -->
            <div class="tab-panel" id="panel-applications">
                <div class="panel-header">
                    <h3><i class="fas fa-file-alt"></i> My Applications</h3>
                    <button class="btn-primary"><i class="fas fa-plus"></i> New Application</button>
                </div>

                <div class="applications-list">
                    <?php
                    $applications = $wpdb->get_results($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}ck_oneform_applications WHERE student_id = %d ORDER BY created_at DESC",
                        $student->id
                    ));

                    if ($applications):
                        foreach ($applications as $app):
                    ?>
                    <div class="application-card">
                        <div class="app-header">
                            <h4><?php echo esc_html($app->college_name ?? 'Application #' . $app->id); ?></h4>
                            <span class="status-badge status-<?php echo esc_attr($app->status); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $app->status)); ?>
                            </span>
                        </div>
                        <div class="app-details">
                            <p><i class="fas fa-calendar"></i> Applied: <?php echo date('M d, Y', strtotime($app->created_at)); ?></p>
                            <p><i class="fas fa-graduation-cap"></i> Course: <?php echo esc_html($app->course ?? 'N/A'); ?></p>
                        </div>
                        <div class="app-actions">
                            <button class="btn-sm btn-outline">View Details</button>
                            <button class="btn-sm btn-outline">Track Status</button>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h4>No Applications Yet</h4>
                        <p>Start your college journey by submitting your first application.</p>
                        <button class="btn-primary">Browse Colleges</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($dashboard_settings['show_services']): ?>
            <!-- Services Tab -->
            <div class="tab-panel" id="panel-services">
                <div class="panel-header">
                    <h3><i class="fas fa-shopping-bag"></i> Available Services</h3>
                    <p class="panel-subtitle">Explore our comprehensive range of services to help you succeed</p>
                </div>

                <div class="services-grid">
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
                            'college-comparison', 'application-tracking', 'document-verification'
                        ));

                        foreach ($services_data as $category => $category_services):
                            foreach ($category_services as $service):
                                // Show only featured services or limit to first 9
                                if (in_array($service['slug'], $featured_services) && $service_count < 9):
                                    $service_count++;
                    ?>
                    <div class="service-card">
                        <div class="service-icon" style="font-size: 2.5rem;">
                            <?php echo $service['icon']; ?>
                        </div>
                        <h4><?php echo esc_html($service['title']); ?></h4>
                        <p><?php echo esc_html($service['desc']); ?></p>
                        <a href="<?php echo esc_url(home_url($service['url'])); ?>" class="btn-outline btn-full">
                            Explore Service →
                        </a>
                    </div>
                    <?php
                                endif;
                            endforeach;
                        endforeach;
                    }
                    ?>
                </div>

                <div class="services-footer" style="text-align: center; margin-top: 30px;">
                    <a href="<?php echo home_url('/services/'); ?>" class="btn-primary btn-large">
                        View All Services →
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($dashboard_settings['show_tests']): ?>
            <!-- Mock Tests Tab -->
            <div class="tab-panel" id="panel-tests">
                <div class="panel-header">
                    <h3><i class="fas fa-clipboard-check"></i> Mock Tests</h3>
                </div>

                <div class="tests-container">
                    <!-- Available Tests -->
                    <div class="tests-section">
                        <h4>Available Tests</h4>
                        <div class="tests-grid">
                            <?php
                            $available_tests = $wpdb->get_results(
                                "SELECT * FROM {$wpdb->prefix}ck_oneform_mock_tests
                                WHERE status = 'active'
                                ORDER BY created_at DESC LIMIT 6"
                            );

                            if ($available_tests):
                                foreach ($available_tests as $test):
                            ?>
                            <div class="test-card">
                                <div class="test-header">
                                    <h5><?php echo esc_html($test->title); ?></h5>
                                    <span class="test-duration"><i class="fas fa-clock"></i> <?php echo intval($test->duration); ?> mins</span>
                                </div>
                                <p><?php echo esc_html($test->description); ?></p>
                                <div class="test-meta">
                                    <span><i class="fas fa-question-circle"></i> <?php echo intval($test->total_questions); ?> Questions</span>
                                    <span><i class="fas fa-star"></i> <?php echo intval($test->total_marks); ?> Marks</span>
                                </div>
                                <button class="btn-primary btn-full">Start Test</button>
                            </div>
                            <?php
                                endforeach;
                            else:
                            ?>
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>No Tests Available</h4>
                                <p>Check back later for new mock tests.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- My Results -->
                    <div class="tests-section">
                        <h4>My Test Results</h4>
                        <?php
                        $results = $wpdb->get_results($wpdb->prepare(
                            "SELECT st.*, mt.title
                            FROM {$wpdb->prefix}ck_oneform_student_tests st
                            LEFT JOIN {$wpdb->prefix}ck_oneform_mock_tests mt ON st.test_id = mt.id
                            WHERE st.student_id = %d
                            ORDER BY st.completed_at DESC",
                            $student->id
                        ));

                        if ($results):
                        ?>
                        <div class="results-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Test Name</th>
                                        <th>Score</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $result): ?>
                                    <tr>
                                        <td><?php echo esc_html($result->title); ?></td>
                                        <td><strong><?php echo intval($result->score); ?>/<?php echo intval($result->total_marks); ?></strong></td>
                                        <td><?php echo date('M d, Y', strtotime($result->completed_at)); ?></td>
                                        <td>
                                            <?php
                                            $percentage = ($result->score / $result->total_marks) * 100;
                                            if ($percentage >= 80) echo '<span class="badge-success">Excellent</span>';
                                            elseif ($percentage >= 60) echo '<span class="badge-warning">Good</span>';
                                            else echo '<span class="badge-danger">Needs Improvement</span>';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state small">
                            <p>You haven't taken any tests yet.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($dashboard_settings['show_offers']): ?>
            <!-- Offers Tab -->
            <div class="tab-panel" id="panel-offers">
                <div class="panel-header">
                    <h3><i class="fas fa-gift"></i> Special Offers</h3>
                </div>

                <div class="offers-grid">
                    <?php
                    $offers = $wpdb->get_results($wpdb->prepare(
                        "SELECT so.*, o.title, o.description, o.discount_type, o.discount_value, o.expiry_date
                        FROM {$wpdb->prefix}ck_oneform_student_offers so
                        LEFT JOIN {$wpdb->prefix}ck_oneform_offers o ON so.offer_id = o.id
                        WHERE so.student_id = %d AND o.expiry_date >= CURDATE()
                        ORDER BY o.expiry_date ASC",
                        $student->id
                    ));

                    if ($offers):
                        foreach ($offers as $offer):
                    ?>
                    <div class="offer-card">
                        <div class="offer-badge">
                            <?php
                            if ($offer->discount_type === 'percentage') {
                                echo intval($offer->discount_value) . '% OFF';
                            } else {
                                echo '₹' . number_format($offer->discount_value) . ' OFF';
                            }
                            ?>
                        </div>
                        <h4><?php echo esc_html($offer->title); ?></h4>
                        <p><?php echo esc_html($offer->description); ?></p>
                        <div class="offer-expiry">
                            <i class="fas fa-hourglass-half"></i>
                            Expires: <?php echo date('M d, Y', strtotime($offer->expiry_date)); ?>
                        </div>
                        <button class="btn-primary btn-full">Claim Offer</button>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <h4>No Offers Available</h4>
                        <p>Special offers will appear here when assigned to you.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($dashboard_settings['show_documents']): ?>
            <!-- Documents Tab -->
            <div class="tab-panel" id="panel-documents">
                <div class="panel-header">
                    <h3><i class="fas fa-folder"></i> My Documents</h3>
                    <button class="btn-primary"><i class="fas fa-upload"></i> Upload Document</button>
                </div>

                <div class="documents-grid">
                    <?php
                    $documents = $wpdb->get_results($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}ck_oneform_documents WHERE student_id = %d ORDER BY uploaded_at DESC",
                        $student->id
                    ));

                    if ($documents):
                        foreach ($documents as $doc):
                    ?>
                    <div class="document-card">
                        <div class="doc-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="doc-info">
                            <h5><?php echo esc_html($doc->document_type); ?></h5>
                            <p><?php echo esc_html($doc->file_name); ?></p>
                            <span class="doc-date"><?php echo date('M d, Y', strtotime($doc->uploaded_at)); ?></span>
                        </div>
                        <div class="doc-actions">
                            <button class="btn-icon" title="View"><i class="fas fa-eye"></i></button>
                            <button class="btn-icon" title="Download"><i class="fas fa-download"></i></button>
                            <button class="btn-icon danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="empty-state">
                        <i class="fas fa-file-upload"></i>
                        <h4>No Documents Uploaded</h4>
                        <p>Upload your important documents like marksheets, certificates, etc.</p>
                        <button class="btn-primary">Upload Now</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($dashboard_settings['show_profile']): ?>
            <!-- Profile Tab -->
            <div class="tab-panel" id="panel-profile">
                <div class="panel-header">
                    <h3><i class="fas fa-user-cog"></i> Profile Settings</h3>
                </div>

                <div class="profile-container">
                    <!-- Profile Info -->
                    <div class="profile-section">
                        <h4>Personal Information</h4>
                        <form id="profile-form" class="profile-form">
                            <?php wp_nonce_field('update-profile', 'profile_nonce'); ?>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" value="<?php echo esc_attr($student->full_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Email (Cannot change)</label>
                                    <input type="email" value="<?php echo esc_attr($student->email); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input type="tel" name="mobile" value="<?php echo esc_attr($student->mobile); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="dob" value="<?php echo esc_attr($student->dob); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender">
                                        <option value="">Select</option>
                                        <option value="male" <?php selected($student->gender, 'male'); ?>>Male</option>
                                        <option value="female" <?php selected($student->gender, 'female'); ?>>Female</option>
                                        <option value="other" <?php selected($student->gender, 'other'); ?>>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category">
                                        <option value="">Select</option>
                                        <option value="general" <?php selected($student->category, 'general'); ?>>General</option>
                                        <option value="obc" <?php selected($student->category, 'obc'); ?>>OBC</option>
                                        <option value="sc" <?php selected($student->category, 'sc'); ?>>SC</option>
                                        <option value="st" <?php selected($student->category, 'st'); ?>>ST</option>
                                        <option value="ews" <?php selected($student->category, 'ews'); ?>>EWS</option>
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label>Address</label>
                                    <textarea name="address" rows="3"><?php echo esc_textarea($student->address); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" value="<?php echo esc_attr($student->city); ?>">
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="state" value="<?php echo esc_attr($student->state); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Pincode</label>
                                    <input type="text" name="pincode" value="<?php echo esc_attr($student->pincode); ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="profile-section">
                        <h4>Change Password</h4>
                        <form id="password-form" class="password-form">
                            <?php wp_nonce_field('change-password', 'password_nonce'); ?>
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required minlength="6">
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    window.switchTab = function(tabName) {
        $('.nav-item').removeClass('active');
        $(`.nav-item[data-tab="${tabName}"]`).addClass('active');
        $('.tab-panel').removeClass('active');
        $(`#panel-${tabName}`).addClass('active');
    };

    $('.nav-item').on('click', function() {
        const tab = $(this).data('tab');
        switchTab(tab);
    });

    // Close announcement
    $('.close-announcement').on('click', function() {
        $(this).closest('.announcement-banner').slideUp();
    });

    // Profile form
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: $(this).serialize() + '&action=ck_update_student_profile&nonce=' + $('[name="profile_nonce"]').val(),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.data.message);
                } else {
                    showToast('error', response.data.message);
                }
            },
            error: function() {
                showToast('error', 'Failed to update profile');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Password form
    $('#password-form').on('submit', function(e) {
        e.preventDefault();
        const newPass = $(this).find('[name="new_password"]').val();
        const confirmPass = $(this).find('[name="confirm_password"]').val();

        if (newPass !== confirmPass) {
            showToast('error', 'Passwords do not match');
            return;
        }

        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: $(this).serialize() + '&action=ck_change_student_password&nonce=' + $('[name="password_nonce"]').val(),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.data.message);
                    $('#password-form')[0].reset();
                } else {
                    showToast('error', response.data.message);
                }
            },
            error: function() {
                showToast('error', 'Failed to change password');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    function showToast(type, message) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('body').append(toast);
        setTimeout(() => toast.addClass('show'), 100);
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>

<style>
:root {
    --primary: <?php echo esc_attr($theme_color); ?>;
    --primary-dark: <?php echo esc_attr($theme_color); ?>dd;
}

.ck-dashboard-modern {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    background: #f0f2f5;
    min-height: 100vh;
}

.dashboard-header {
    background: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
}

.user-info h2 {
    margin: 0;
    font-size: 1.3rem;
    color: #333;
}

.student-id {
    color: #666;
    font-size: 0.9rem;
}

.header-right {
    display: flex;
    gap: 15px;
    align-items: center;
}

.btn-notification {
    background: #f0f2f5;
    border: none;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
}

.btn-notification .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
}

.btn-logout {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-logout:hover {
    background: #dc2626;
}

.announcement-banner {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #92400e;
    padding: 12px 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.announcement-banner i {
    font-size: 1.2rem;
}

.close-announcement {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    color: #92400e;
}

.dashboard-layout {
    display: flex;
    padding: 20px;
    gap: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-sidebar {
    width: 250px;
    flex-shrink: 0;
}

.sidebar-nav {
    background: white;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 12px 15px;
    border: none;
    background: none;
    border-radius: 8px;
    cursor: pointer;
    color: #666;
    font-weight: 500;
    transition: all 0.3s;
    text-align: left;
}

.nav-item:hover {
    background: #f0f2f5;
    color: var(--primary);
}

.nav-item.active {
    background: var(--primary);
    color: white;
}

.nav-item i {
    width: 20px;
    text-align: center;
}

.sidebar-stats {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-item {
    text-align: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f2f5;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-number {
    display: block;
    font-size: 1.8rem;
    font-weight: bold;
    color: var(--primary);
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
}

.dashboard-content {
    flex: 1;
    min-width: 0;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.panel-header {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-header h3 {
    margin: 0;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.panel-header p {
    margin: 5px 0 0;
    color: #666;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.gradient-1 .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.gradient-2 .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.gradient-3 .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.gradient-4 .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

.stat-content h4 {
    margin: 0;
    font-size: 2rem;
    color: #333;
}

.stat-content p {
    margin: 5px 0 0;
    color: #666;
}

.activity-section, .quick-actions {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.activity-section h4, .quick-actions h4 {
    margin: 0 0 20px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f2f5;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #f0f2f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.activity-content p {
    margin: 0;
    font-weight: 500;
}

.activity-content span {
    font-size: 0.85rem;
    color: #666;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.action-btn {
    background: #f0f2f5;
    border: 2px solid transparent;
    padding: 20px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
}

.action-btn:hover {
    border-color: var(--primary);
    background: white;
}

.action-btn i {
    font-size: 24px;
    color: var(--primary);
    margin-bottom: 10px;
    display: block;
}

.action-btn span {
    font-weight: 500;
    color: #333;
}

.btn-primary {
    background: var(--primary);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-outline {
    background: white;
    color: var(--primary);
    border: 2px solid var(--primary);
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-outline:hover {
    background: var(--primary);
    color: white;
}

.btn-full {
    width: 100%;
    justify-content: center;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h4 {
    margin: 0 0 10px;
    color: #333;
}

.empty-state p {
    color: #666;
    margin: 0 0 20px;
}

.empty-state.small {
    padding: 30px;
}

.empty-state.small i {
    font-size: 2rem;
    margin-bottom: 10px;
}

/* Applications */
.applications-list {
    display: grid;
    gap: 20px;
}

.application-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.app-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.app-header h4 {
    margin: 0;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-submitted { background: #dbeafe; color: #1e40af; }
.status-under_review { background: #e0e7ff; color: #3730a3; }
.status-accepted { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }

.app-details {
    margin-bottom: 15px;
}

.app-details p {
    margin: 5px 0;
    color: #666;
    display: flex;
    align-items: center;
    gap: 8px;
}

.app-actions {
    display: flex;
    gap: 10px;
}

/* Services Grid */
.services-grid, .offers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.service-card, .offer-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s;
}

.service-card:hover, .offer-card:hover {
    transform: translateY(-5px);
}

.service-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    margin-bottom: 15px;
}

.service-card h4 {
    margin: 0 0 10px;
}

.service-card p {
    color: #666;
    margin: 0 0 15px;
}

.service-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 0.85rem;
}

.status-active {
    color: #10b981;
    font-weight: 600;
}

/* Offers */
.offer-card {
    position: relative;
}

.offer-badge {
    position: absolute;
    top: -10px;
    right: 20px;
    background: #ef4444;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
}

.offer-card h4 {
    margin: 15px 0 10px;
}

.offer-card p {
    color: #666;
    margin: 0 0 15px;
}

.offer-expiry {
    background: #fef3c7;
    color: #92400e;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Tests */
.tests-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.tests-section h4 {
    margin: 0 0 20px;
    color: #333;
}

.tests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.test-card {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s;
}

.test-card:hover {
    border-color: var(--primary);
}

.test-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.test-header h5 {
    margin: 0;
}

.test-duration {
    color: #666;
    font-size: 0.85rem;
}

.test-card p {
    color: #666;
    margin: 0 0 15px;
}

.test-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 0.85rem;
    color: #666;
}

.results-table {
    overflow-x: auto;
}

.results-table table {
    width: 100%;
    border-collapse: collapse;
}

.results-table th, .results-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.results-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.badge-success { background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; }
.badge-warning { background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; }
.badge-danger { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; }

/* Documents */
.documents-grid {
    display: grid;
    gap: 15px;
}

.document-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.doc-icon {
    width: 50px;
    height: 50px;
    background: #fee2e2;
    color: #ef4444;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.doc-info {
    flex: 1;
}

.doc-info h5 {
    margin: 0 0 5px;
}

.doc-info p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.doc-date {
    font-size: 0.8rem;
    color: #999;
}

.doc-actions {
    display: flex;
    gap: 10px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border: none;
    background: #f0f2f5;
    border-radius: 8px;
    cursor: pointer;
    color: #666;
    transition: all 0.3s;
}

.btn-icon:hover {
    background: var(--primary);
    color: white;
}

.btn-icon.danger:hover {
    background: #ef4444;
}

/* Profile */
.profile-container {
    display: grid;
    gap: 20px;
}

.profile-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.profile-section h4 {
    margin: 0 0 20px;
    color: #333;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.form-group input, .form-group select, .form-group textarea {
    padding: 10px 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.form-group input:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
}

.password-form .form-group {
    margin-bottom: 15px;
}

/* Toast Notifications */
.toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    padding: 15px 25px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 10000;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s;
}

.toast.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-success {
    background: #10b981;
}

.toast-error {
    background: #ef4444;
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-layout {
        flex-direction: column;
    }

    .dashboard-sidebar {
        width: 100%;
    }

    .sidebar-nav {
        display: flex;
        overflow-x: auto;
        padding: 5px;
    }

    .nav-item {
        flex-shrink: 0;
        flex-direction: column;
        padding: 10px 20px;
    }

    .nav-item span {
        font-size: 0.8rem;
    }

    .sidebar-stats {
        display: none;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        padding: 15px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .header-right {
        gap: 10px;
    }

    .btn-logout {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
}
</style>
