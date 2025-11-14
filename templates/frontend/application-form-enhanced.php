<?php
/**
 * Enhanced Multi-College Application Form
 * Integrated with college list selection
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

// Get selected colleges from URL parameter
$selected_college_ids = array();
if (isset($_GET['colleges'])) {
    $selected_college_ids = array_map('intval', explode(',', sanitize_text_field($_GET['colleges'])));
}

// Get college details
$selected_colleges = array();
$total_fees = 0;

foreach ($selected_college_ids as $college_id) {
    $college = get_post($college_id);
    if ($college && $college->post_type === 'ck_college') {
        $fees_range = get_post_meta($college_id, 'fees_range', true);
        $fees_parts = explode('-', $fees_range);
        $avg_fees = isset($fees_parts[0]) ? intval($fees_parts[0]) : 0;

        $selected_colleges[] = array(
            'id' => $college_id,
            'name' => $college->post_title,
            'short_name' => get_post_meta($college_id, 'short_name', true),
            'type' => get_post_meta($college_id, 'college_type', true),
            'state' => get_post_meta($college_id, 'state', true),
            'city' => get_post_meta($college_id, 'city', true),
            'fees' => $avg_fees,
            'fees_range' => $fees_range,
        );

        $total_fees += $avg_fees;
    }
}

// Application fee per college (example: ₹500 per college)
$application_fee_per_college = 500;
$total_application_fee = count($selected_colleges) * $application_fee_per_college;
?>

<div class="ck-multi-college-application">

    <!-- Progress Steps -->
    <div class="application-steps">
        <div class="step active" data-step="1">
            <div class="step-number">1</div>
            <div class="step-label">Review Colleges</div>
        </div>
        <div class="step" data-step="2">
            <div class="step-number">2</div>
            <div class="step-label">Personal Details</div>
        </div>
        <div class="step" data-step="3">
            <div class="step-number">3</div>
            <div class="step-label">Documents</div>
        </div>
        <div class="step" data-step="4">
            <div class="step-number">4</div>
            <div class="step-label">Payment</div>
        </div>
    </div>

    <?php if (empty($selected_colleges)): ?>
        <!-- No Colleges Selected -->
        <div class="no-selection">
            <h2>😔 No Colleges Selected</h2>
            <p>Please select colleges from our college list to apply.</p>
            <a href="<?php echo esc_url(home_url('/colleges/')); ?>" class="btn-primary">
                Browse Colleges
            </a>
        </div>
    <?php else: ?>

        <!-- Step 1: Review Selected Colleges -->
        <div class="application-step-content" id="step-1">
            <div class="step-header">
                <h2>📚 Review Your Selected Colleges (<?php echo count($selected_colleges); ?>)</h2>
                <p>You can remove colleges you don't want to apply to</p>
            </div>

            <div class="selected-colleges-review">
                <?php foreach ($selected_colleges as $index => $college): ?>
                    <div class="college-review-card" data-college-id="<?php echo $college['id']; ?>">
                        <div class="card-number"><?php echo $index + 1; ?></div>

                        <div class="card-content">
                            <div class="college-header-row">
                                <div>
                                    <h3><?php echo esc_html($college['name']); ?></h3>
                                    <?php if ($college['short_name']): ?>
                                        <p class="short-name"><?php echo esc_html($college['short_name']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="type-badge <?php echo strtolower($college['type']); ?>">
                                    <?php echo esc_html($college['type']); ?>
                                </span>
                            </div>

                            <div class="college-details">
                                <span class="detail-item">
                                    📍 <?php echo esc_html($college['city'] . ', ' . $college['state']); ?>
                                </span>
                                <span class="detail-item">
                                    💰 Avg. Fees: ₹<?php echo number_format($college['fees']); ?>/year
                                </span>
                            </div>
                        </div>

                        <button type="button" class="btn-remove-college" data-college-id="<?php echo $college['id']; ?>">
                            ✕ Remove
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="fee-summary">
                <div class="summary-row">
                    <span>Application Fee (₹<?php echo $application_fee_per_college; ?> × <?php echo count($selected_colleges); ?> colleges)</span>
                    <strong>₹<?php echo number_format($total_application_fee); ?></strong>
                </div>
                <div class="summary-row total">
                    <span>Total Application Fee</span>
                    <strong>₹<?php echo number_format($total_application_fee); ?></strong>
                </div>
                <p class="note">*College fees shown are average annual fees. Actual fees may vary.</p>
            </div>

            <div class="step-actions">
                <a href="<?php echo esc_url(home_url('/colleges/')); ?>" class="btn-secondary">
                    ← Back to College List
                </a>
                <button type="button" class="btn-primary" id="proceed-to-details">
                    Continue to Details →
                </button>
            </div>
        </div>

        <!-- Step 2: Personal Details -->
        <div class="application-step-content" id="step-2" style="display: none;">
            <div class="step-header">
                <h2>👤 Personal Information</h2>
                <p>Fill in your details for the application</p>
            </div>

            <form id="application-form" class="application-form">
                <input type="hidden" name="selected_colleges" id="selected-colleges-input" value="<?php echo esc_attr(implode(',', $selected_college_ids)); ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" required class="form-control" value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>">
                    </div>

                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="tel" name="mobile" required class="form-control" pattern="[0-9]{10}">
                    </div>

                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category" required class="form-control">
                            <option value="">Select Category</option>
                            <option value="general">General</option>
                            <option value="obc">OBC</option>
                            <option value="sc">SC</option>
                            <option value="st">ST</option>
                            <option value="ews">EWS</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Address *</label>
                        <textarea name="address" required class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>State *</label>
                        <input type="text" name="state" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>PIN Code *</label>
                        <input type="text" name="pincode" required class="form-control" pattern="[0-9]{6}">
                    </div>
                </div>

                <h3>Educational Qualification</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label>12th/Intermediate Board *</label>
                        <input type="text" name="board_12th" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>12th Percentage/CGPA *</label>
                        <input type="text" name="marks_12th" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Year of Passing *</label>
                        <input type="number" name="year_12th" required class="form-control" min="2000" max="2030">
                    </div>

                    <div class="form-group">
                        <label>Entrance Exam (if any)</label>
                        <select name="entrance_exam" class="form-control">
                            <option value="">Select Exam</option>
                            <option value="jee_main">JEE Main</option>
                            <option value="jee_advanced">JEE Advanced</option>
                            <option value="neet">NEET</option>
                            <option value="bitsat">BITSAT</option>
                            <option value="viteee">VITEEE</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Exam Score/Rank</label>
                        <input type="text" name="exam_score" class="form-control">
                    </div>
                </div>

                <div class="step-actions">
                    <button type="button" class="btn-secondary" id="back-to-review">
                        ← Back
                    </button>
                    <button type="button" class="btn-primary" id="proceed-to-documents">
                        Continue to Documents →
                    </button>
                </div>
            </form>
        </div>

        <!-- Step 3: Documents Upload -->
        <div class="application-step-content" id="step-3" style="display: none;">
            <div class="step-header">
                <h2>📄 Upload Documents</h2>
                <p>Please upload the required documents</p>
            </div>

            <div class="documents-upload">
                <div class="upload-item">
                    <label>Photograph (Recent passport size) *</label>
                    <input type="file" name="photo" accept="image/*" required class="form-control">
                    <small>Max size: 200KB, Format: JPG/PNG</small>
                </div>

                <div class="upload-item">
                    <label>Signature *</label>
                    <input type="file" name="signature" accept="image/*" required class="form-control">
                    <small>Max size: 100KB, Format: JPG/PNG</small>
                </div>

                <div class="upload-item">
                    <label>10th Marksheet *</label>
                    <input type="file" name="marksheet_10th" accept=".pdf,.jpg,.png" required class="form-control">
                    <small>Max size: 500KB, Format: PDF/JPG/PNG</small>
                </div>

                <div class="upload-item">
                    <label>12th Marksheet *</label>
                    <input type="file" name="marksheet_12th" accept=".pdf,.jpg,.png" required class="form-control">
                    <small>Max size: 500KB, Format: PDF/JPG/PNG</small>
                </div>

                <div class="upload-item">
                    <label>Category Certificate (if applicable)</label>
                    <input type="file" name="category_cert" accept=".pdf,.jpg,.png" class="form-control">
                    <small>Max size: 500KB, Format: PDF/JPG/PNG</small>
                </div>

                <div class="upload-item">
                    <label>Entrance Exam Scorecard (if applicable)</label>
                    <input type="file" name="exam_scorecard" accept=".pdf,.jpg,.png" class="form-control">
                    <small>Max size: 500KB, Format: PDF/JPG/PNG</small>
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-secondary" id="back-to-details">
                    ← Back
                </button>
                <button type="button" class="btn-primary" id="proceed-to-payment">
                    Continue to Payment →
                </button>
            </div>
        </div>

        <!-- Step 4: Payment -->
        <div class="application-step-content" id="step-4" style="display: none;">
            <div class="step-header">
                <h2>💳 Payment</h2>
                <p>Complete your payment to submit application</p>
            </div>

            <div class="payment-summary">
                <h3>Payment Summary</h3>

                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>College</th>
                            <th>Application Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($selected_colleges as $college): ?>
                            <tr>
                                <td><?php echo esc_html($college['short_name'] ?: $college['name']); ?></td>
                                <td>₹<?php echo number_format($application_fee_per_college); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <th>Total</th>
                            <th>₹<?php echo number_format($total_application_fee); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="payment-methods">
                <h3>Select Payment Method</h3>

                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="razorpay" checked>
                        <div class="option-content">
                            <strong>Razorpay</strong>
                            <small>Credit/Debit Card, UPI, Net Banking, Wallets</small>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="payu">
                        <div class="option-content">
                            <strong>PayU</strong>
                            <small>Credit/Debit Card, Net Banking</small>
                        </div>
                    </label>
                </div>
            </div>

            <div class="terms-acceptance">
                <label>
                    <input type="checkbox" name="accept_terms" required>
                    I accept the <a href="#" target="_blank">Terms & Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                </label>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-secondary" id="back-to-documents">
                    ← Back
                </button>
                <button type="submit" class="btn-primary btn-large" id="submit-application">
                    💳 Pay ₹<?php echo number_format($total_application_fee); ?> & Submit Application
                </button>
            </div>
        </div>

    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    let currentStep = 1;
    let selectedColleges = <?php echo json_encode($selected_college_ids); ?>;

    // Step navigation
    function goToStep(step) {
        // Hide all steps
        $('.application-step-content').hide();
        $('.step').removeClass('active');

        // Show current step
        $('#step-' + step).show();
        $('.step[data-step="' + step + '"]').addClass('active');

        // Mark previous steps as completed
        for (let i = 1; i < step; i++) {
            $('.step[data-step="' + i + '"]').addClass('completed');
        }

        currentStep = step;

        // Scroll to top
        $('html, body').animate({ scrollTop: 0 }, 300);
    }

    // Navigation buttons
    $('#proceed-to-details').on('click', function() {
        if (selectedColleges.length === 0) {
            alert('Please select at least one college');
            return;
        }
        goToStep(2);
    });

    $('#back-to-review').on('click', function() {
        goToStep(1);
    });

    $('#proceed-to-documents').on('click', function() {
        // Validate form
        if (!$('#application-form')[0].checkValidity()) {
            $('#application-form')[0].reportValidity();
            return;
        }
        goToStep(3);
    });

    $('#back-to-details').on('click', function() {
        goToStep(2);
    });

    $('#proceed-to-payment').on('click', function() {
        goToStep(4);
    });

    $('#back-to-documents').on('click', function() {
        goToStep(3);
    });

    // Remove college
    $('.btn-remove-college').on('click', function() {
        const collegeId = $(this).data('college-id');

        if (selectedColleges.length === 1) {
            alert('You must have at least one college selected');
            return;
        }

        if (confirm('Are you sure you want to remove this college from your application?')) {
            // Remove from array
            selectedColleges = selectedColleges.filter(id => id !== collegeId);

            // Remove card
            $(`.college-review-card[data-college-id="${collegeId}"]`).fadeOut(300, function() {
                $(this).remove();

                // Update numbers
                $('.college-review-card').each(function(index) {
                    $(this).find('.card-number').text(index + 1);
                });

                // Update counts and fees
                updateSummary();
            });
        }
    });

    function updateSummary() {
        const count = selectedColleges.length;
        const fee = <?php echo $application_fee_per_college; ?>;
        const total = count * fee;

        $('.step-header h2').html(`📚 Review Your Selected Colleges (${count})`);
        $('.fee-summary').html(`
            <div class="summary-row">
                <span>Application Fee (₹${fee} × ${count} colleges)</span>
                <strong>₹${total.toLocaleString()}</strong>
            </div>
            <div class="summary-row total">
                <span>Total Application Fee</span>
                <strong>₹${total.toLocaleString()}</strong>
            </div>
            <p class="note">*College fees shown are average annual fees. Actual fees may vary.</p>
        `);

        // Update hidden input
        $('#selected-colleges-input').val(selectedColleges.join(','));
    }

    // Form submission
    $('#submit-application').on('click', function(e) {
        e.preventDefault();

        if (!$('input[name="accept_terms"]').is(':checked')) {
            alert('Please accept the Terms & Conditions');
            return;
        }

        // Collect all form data
        const formData = new FormData($('#application-form')[0]);

        // Add documents
        $('.documents-upload input[type="file"]').each(function() {
            if (this.files[0]) {
                formData.append(this.name, this.files[0]);
            }
        });

        // Add payment method
        formData.append('payment_method', $('input[name="payment_method"]:checked').val());
        formData.append('action', 'ck_submit_multi_college_application');
        formData.append('nonce', '<?php echo wp_create_nonce('ck-multi-college-application'); ?>');

        // Show loading
        $(this).prop('disabled', true).html('⏳ Processing...');

        // Submit via AJAX
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Redirect to payment gateway or success page
                    window.location.href = response.data.redirect_url;
                } else {
                    alert('Error: ' + response.data.message);
                    $('#submit-application').prop('disabled', false).html('💳 Pay & Submit Application');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('#submit-application').prop('disabled', false).html('💳 Pay & Submit Application');
            }
        });
    });
});
</script>

<style>
.ck-multi-college-application {
    max-width: 1000px;
    margin: 40px auto;
    padding: 20px;
}

.application-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
    position: relative;
}

.application-steps::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    position: relative;
    z-index: 1;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 20px;
    transition: all 0.3s;
}

.step.active .step-number {
    background: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.step.completed .step-number {
    background: #10b981;
    color: white;
}

.step-label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.step.active .step-label {
    color: #667eea;
    font-weight: 600;
}

.step-header {
    text-align: center;
    margin-bottom: 30px;
}

.step-header h2 {
    font-size: 2rem;
    margin-bottom: 10px;
}

.step-header p {
    color: #666;
    font-size: 1.1rem;
}

.selected-colleges-review {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.college-review-card {
    background: white;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s;
}

.college-review-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
}

.card-content {
    flex: 1;
}

.college-header-row {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}

.college-header-row h3 {
    margin: 0 0 5px 0;
    font-size: 1.2rem;
}

.short-name {
    color: #667eea;
    font-weight: 600;
    margin: 0;
}

.type-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.type-badge.iit { background: #fef3c7; color: #92400e; }
.type-badge.nit { background: #dbeafe; color: #1e40af; }
.type-badge.iiit { background: #e0e7ff; color: #3730a3; }
.type-badge.medical { background: #fee2e2; color: #991b1b; }
.type-badge.private { background: #f3e8ff; color: #6b21a8; }
.type-badge.gfti { background: #d1fae5; color: #065f46; }

.college-details {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.detail-item {
    font-size: 14px;
    color: #666;
}

.btn-remove-college {
    background: #fee2e2;
    color: #991b1b;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-remove-college:hover {
    background: #fecaca;
}

.fee-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 16px;
}

.summary-row.total {
    border-top: 2px solid #e0e0e0;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 20px;
    color: #667eea;
}

.note {
    margin-top: 15px;
    font-size: 13px;
    color: #999;
    font-style: italic;
}

.application-form {
    background: white;
    padding: 30px;
    border-radius: 12px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
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
}

.form-control {
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.documents-upload {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.upload-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.upload-item label {
    font-weight: 600;
    color: #333;
}

.upload-item small {
    color: #999;
    font-size: 12px;
}

.payment-summary {
    background: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.summary-table th,
.summary-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.summary-table thead {
    background: #f8f9fa;
}

.summary-table .total-row {
    font-size: 18px;
    font-weight: 700;
    color: #667eea;
}

.payment-methods {
    background: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.payment-options {
    display: grid;
    gap: 15px;
    margin-top: 20px;
}

.payment-option {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s;
}

.payment-option:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.payment-option input[type="radio"] {
    width: 20px;
    height: 20px;
}

.payment-option input[type="radio"]:checked + .option-content {
    color: #667eea;
}

.terms-acceptance {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.terms-acceptance label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.step-actions {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    margin-top: 30px;
}

.btn-primary,
.btn-secondary {
    padding: 14px 28px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-size: 16px;
    transition: all 0.3s;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #f5f5f5;
    color: #666;
}

.btn-large {
    padding: 18px 36px;
    font-size: 18px;
}

.no-selection {
    text-align: center;
    padding: 60px 20px;
}

.no-selection h2 {
    font-size: 2rem;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .form-grid,
    .documents-upload {
        grid-template-columns: 1fr;
    }

    .application-steps {
        flex-wrap: wrap;
        gap: 20px;
    }

    .application-steps::before {
        display: none;
    }
}
</style>
