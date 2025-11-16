<?php
/**
 * Student Login & Registration Template
 * Separate from WordPress login
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Redirect if already logged in
if (CK_OneForm_Student_Auth::is_student_logged_in()) {
    wp_redirect(home_url('/student-dashboard/'));
    exit;
}
?>

<div class="ck-student-auth-page">
    <div class="auth-container">
        <div class="auth-tabs">
            <button class="auth-tab active" data-tab="login">Login</button>
            <button class="auth-tab" data-tab="register">Register</button>
        </div>

        <!-- Login Form -->
        <div class="auth-form-container" id="login-form-container">
            <div class="auth-header">
                <h2>👋 Welcome Back!</h2>
                <p>Login to your student dashboard</p>
            </div>

            <form id="student-login-form" class="auth-form">
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" required class="form-control" placeholder="your@email.com">
                </div>

                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required class="form-control" placeholder="Enter your password">
                </div>

                <div class="form-group-inline">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember_me">
                        Remember Me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-primary btn-full">
                    🔐 Login to Dashboard
                </button>

                <div class="form-note">
                    Don't have an account? <a href="#" class="switch-tab" data-tab="register">Register here</a>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="auth-form-container" id="register-form-container" style="display: none;">
            <div class="auth-header">
                <h2>🎓 Create Your Account</h2>
                <p>Join thousands of students applying to top colleges</p>
            </div>

            <form id="student-register-form" class="auth-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" required class="form-control" placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required class="form-control" placeholder="your@email.com">
                    </div>

                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="tel" name="mobile" required class="form-control" placeholder="9876543210" pattern="[0-9]{10}">
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">Select Category</option>
                            <option value="general">General</option>
                            <option value="obc">OBC</option>
                            <option value="sc">SC</option>
                            <option value="st">ST</option>
                            <option value="ews">EWS</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Password *</label>
                        <input type="password" name="password" required class="form-control" placeholder="Create a strong password" minlength="6">
                        <small>Minimum 6 characters</small>
                    </div>

                    <div class="form-group full-width">
                        <label>Confirm Password *</label>
                        <input type="password" name="confirm_password" required class="form-control" placeholder="Re-enter your password">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="accept_terms" required>
                        I accept the <a href="#" target="_blank">Terms & Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn-primary btn-full">
                    🚀 Create My Account
                </button>

                <div class="form-note">
                    Already have an account? <a href="#" class="switch-tab" data-tab="login">Login here</a>
                </div>
            </form>
        </div>

        <div class="auth-benefits">
            <h3>✨ Benefits of Student Dashboard</h3>
            <ul>
                <li>✅ Track all your college applications in one place</li>
                <li>✅ Get real-time updates on application status</li>
                <li>✅ Access exclusive mock tests and study materials</li>
                <li>✅ Receive special offers and discounts</li>
                <li>✅ Manage documents and payments easily</li>
                <li>✅ Get personalized college recommendations</li>
            </ul>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.auth-tab').on('click', function() {
        const tab = $(this).data('tab');

        $('.auth-tab').removeClass('active');
        $(this).addClass('active');

        $('.auth-form-container').hide();
        $(`#${tab}-form-container`).show();
    });

    $('.switch-tab').on('click', function(e) {
        e.preventDefault();
        const tab = $(this).data('tab');
        $(`.auth-tab[data-tab="${tab}"]`).trigger('click');
    });

    // Login form submission
    $('#student-login-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const originalText = $button.html();

        $button.prop('disabled', true).html('⏳ Logging in...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_student_login',
                nonce: '<?php echo wp_create_nonce('ck-student-auth'); ?>',
                email: $form.find('[name="email"]').val(),
                password: $form.find('[name="password"]').val()
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.data.message);

                    // Redirect to dashboard
                    setTimeout(function() {
                        window.location.href = '<?php echo home_url('/student-dashboard/'); ?>';
                    }, 1000);
                } else {
                    showMessage('error', response.data.message);
                    $button.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                showMessage('error', 'An error occurred. Please try again.');
                $button.prop('disabled', false).html(originalText);
            }
        });
    });

    // Registration form submission
    $('#student-register-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const password = $form.find('[name="password"]').val();
        const confirmPassword = $form.find('[name="confirm_password"]').val();

        if (password !== confirmPassword) {
            showMessage('error', 'Passwords do not match');
            return;
        }

        const $button = $form.find('button[type="submit"]');
        const originalText = $button.html();

        $button.prop('disabled', true).html('⏳ Creating account...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_student_register',
                nonce: '<?php echo wp_create_nonce('ck-student-auth'); ?>',
                full_name: $form.find('[name="full_name"]').val(),
                email: $form.find('[name="email"]').val(),
                mobile: $form.find('[name="mobile"]').val(),
                password: password,
                dob: $form.find('[name="dob"]').val(),
                gender: $form.find('[name="gender"]').val(),
                category: $form.find('[name="category"]').val()
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.data.message);

                    // Switch to login tab after 2 seconds
                    setTimeout(function() {
                        $('.auth-tab[data-tab="login"]').trigger('click');
                        $form[0].reset();
                    }, 2000);
                } else {
                    showMessage('error', response.data.message || 'Registration failed');
                }
                $button.prop('disabled', false).html(originalText);
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText);
                let errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseText) {
                    // Try to show actual error from server
                    errorMsg = 'Server Error: ' + xhr.responseText.substring(0, 200);
                }
                showMessage('error', errorMsg);
                $button.prop('disabled', false).html(originalText);
            }
        });
    });

    function showMessage(type, message) {
        const messageClass = type === 'success' ? 'auth-message-success' : 'auth-message-error';
        const icon = type === 'success' ? '✅' : '❌';

        const $message = $(`<div class="auth-message ${messageClass}">${icon} ${message}</div>`);
        $('.auth-form-container:visible .auth-form').prepend($message);

        setTimeout(function() {
            $message.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>

<style>
.ck-student-auth-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.auth-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    max-width: 600px;
    width: 100%;
    overflow: hidden;
}

.auth-tabs {
    display: flex;
    background: #f8f9fa;
}

.auth-tab {
    flex: 1;
    padding: 20px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-weight: 600;
    font-size: 16px;
    color: #666;
    transition: all 0.3s;
}

.auth-tab.active {
    background: white;
    color: #667eea;
    border-bottom: 3px solid #667eea;
}

.auth-form-container {
    padding: 40px;
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h2 {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #333;
}

.auth-header p {
    color: #666;
    font-size: 1.1rem;
}

.auth-form .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.auth-form .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.auth-form .form-group.full-width {
    grid-column: 1 / -1;
}

.auth-form .form-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.auth-form .form-control {
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.auth-form .form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group-inline {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.forgot-password {
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
}

.forgot-password:hover {
    text-decoration: underline;
}

.btn-primary.btn-full {
    width: 100%;
    padding: 14px 24px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 20px;
}

.btn-primary.btn-full:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary.btn-full:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.form-note {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
}

.form-note a, .switch-tab {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
}

.form-note a:hover, .switch-tab:hover {
    text-decoration: underline;
}

.auth-benefits {
    background: #f8f9fa;
    padding: 30px 40px;
    border-top: 1px solid #e0e0e0;
}

.auth-benefits h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.auth-benefits ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.auth-benefits li {
    padding: 10px 0;
    color: #555;
    font-size: 14px;
}

.auth-message {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}

.auth-message-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.auth-message-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

@media (max-width: 768px) {
    .auth-form .form-grid {
        grid-template-columns: 1fr;
    }

    .auth-form-container {
        padding: 30px 20px;
    }

    .auth-benefits {
        padding: 20px;
    }
}
</style>
