/**
 * CollegeKampus OneForm - Frontend JavaScript
 *
 * @package CK_OneForm
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Registration Form Submit
         */
        $('#ck-oneform-registration-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = form.serialize();

            // Validate password match
            var password = form.find('#password').val();
            var confirmPassword = form.find('#confirm_password').val();

            if (password !== confirmPassword) {
                showMessage(form, 'Passwords do not match', 'error');
                return false;
            }

            // Show loading
            showLoading();

            $.ajax({
                url: ckOneForm.ajax_url,
                type: 'POST',
                data: {
                    action: 'ck_oneform_register_student',
                    ...Object.fromEntries(new URLSearchParams(formData))
                },
                success: function(response) {
                    hideLoading();

                    if (response.success) {
                        showSuccessModal(response.message);
                        // Redirect to dashboard after 2 seconds
                        setTimeout(function() {
                            window.location.href = '/my-applications/';
                        }, 2000);
                    } else {
                        showMessage(form, response.message, 'error');
                    }
                },
                error: function() {
                    hideLoading();
                    showMessage(form, 'An error occurred. Please try again.', 'error');
                }
            });
        });

        /**
         * Application Form Submit
         */
        $('#ck-oneform-application-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this)[0];
            var formData = new FormData(form);
            formData.append('action', 'ck_oneform_submit_application');

            // Show loading
            showLoading();

            $.ajax({
                url: ckOneForm.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoading();

                    if (response.success) {
                        showSuccessModal(response.message + '<br><br>Application ID: ' + response.application_id);
                        // Reset form
                        $(form).trigger('reset');
                        // Redirect to dashboard
                        setTimeout(function() {
                            window.location.href = '/my-applications/';
                        }, 3000);
                    } else {
                        showMessage($(form), response.message, 'error');
                    }
                },
                error: function() {
                    hideLoading();
                    showMessage($(form), 'An error occurred. Please try again.', 'error');
                }
            });
        });

        /**
         * Status Check Form
         */
        $('#ck-oneform-status-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var applicationNumber = form.find('#application_number').val();

            showLoading();

            $.ajax({
                url: ckOneForm.ajax_url,
                type: 'POST',
                data: {
                    action: 'ck_oneform_check_status',
                    application_number: applicationNumber
                },
                success: function(response) {
                    hideLoading();

                    if (response.success) {
                        $('#result-app-number').text(response.application.application_number);
                        $('#result-submission-date').text(formatDate(response.application.submission_date));
                        $('#result-status').html('<span class="badge badge-' + response.application.status + '">' + response.application.status.toUpperCase() + '</span>');
                        $('#result-updated-date').text(formatDate(response.application.updated_date));
                        $('#status-result').slideDown();
                    } else {
                        showMessage(form, response.message, 'error');
                        $('#status-result').slideUp();
                    }
                },
                error: function() {
                    hideLoading();
                    showMessage(form, 'An error occurred. Please try again.', 'error');
                }
            });
        });

        /**
         * Payment Form Submit
         */
        $('#ck-oneform-payment-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = form.serialize();

            showLoading();

            $.ajax({
                url: ckOneForm.ajax_url,
                type: 'POST',
                data: {
                    action: 'ck_oneform_process_payment',
                    ...Object.fromEntries(new URLSearchParams(formData))
                },
                success: function(response) {
                    hideLoading();

                    if (response.success) {
                        showSuccessModal(response.message);
                        // Here you would typically redirect to payment gateway
                        // or show payment options modal
                    } else {
                        showMessage(form, response.message, 'error');
                    }
                },
                error: function() {
                    hideLoading();
                    showMessage(form, 'An error occurred. Please try again.', 'error');
                }
            });
        });

        /**
         * View Application
         */
        $(document).on('click', '.view-application', function(e) {
            e.preventDefault();
            var applicationId = $(this).data('id');

            // This would open a modal or redirect to application details page
            window.location.href = '?view_application=' + applicationId;
        });

        /**
         * Download PDF
         */
        $(document).on('click', '.download-pdf', function(e) {
            e.preventDefault();
            var applicationId = $(this).data('id');

            window.location.href = ckOneForm.ajax_url + '?action=ck_oneform_download_pdf&application_id=' + applicationId;
        });

        /**
         * Helper Functions
         */

        function showMessage(form, message, type) {
            var messageDiv = form.find('.form-message');
            messageDiv.removeClass('success error').addClass(type).html(message).show();

            // Scroll to message
            $('html, body').animate({
                scrollTop: messageDiv.offset().top - 100
            }, 500);
        }

        function showLoading() {
            $('#ck-oneform-loading-modal').addClass('active');
        }

        function hideLoading() {
            $('#ck-oneform-loading-modal').removeClass('active');
        }

        function showSuccessModal(message) {
            $('#ck-oneform-success-modal .modal-message').html(message);
            $('#ck-oneform-success-modal').addClass('active');
        }

        function showErrorModal(message) {
            $('#ck-oneform-error-modal .modal-message').html(message);
            $('#ck-oneform-error-modal').addClass('active');
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        /**
         * Modal Close
         */
        $('.close-modal, .modal-close-btn').on('click', function() {
            $(this).closest('.ck-oneform-modal').removeClass('active');
        });

        // Close modal on outside click
        $('.ck-oneform-modal').on('click', function(e) {
            if ($(e.target).is('.ck-oneform-modal')) {
                $(this).removeClass('active');
            }
        });

        /**
         * Form Validation
         */
        $('input[type="email"]').on('blur', function() {
            var email = $(this).val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });

        $('input[type="tel"]').on('blur', function() {
            var phone = $(this).val();
            var phoneRegex = /^[0-9]{10}$/;

            if (phone && !phoneRegex.test(phone)) {
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });

        /**
         * File Upload Preview
         */
        $('input[type="file"]').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            var fileSize = this.files[0] ? (this.files[0].size / 1024 / 1024).toFixed(2) : 0;

            if (fileSize > 5) {
                alert('File size must be less than 5MB');
                $(this).val('');
                return false;
            }

            if (fileName) {
                var preview = $(this).next('.file-preview');
                if (preview.length === 0) {
                    $(this).after('<small class="file-preview">' + fileName + ' (' + fileSize + ' MB)</small>');
                } else {
                    preview.text(fileName + ' (' + fileSize + ' MB)');
                }
            }
        });

    });

})(jQuery);
