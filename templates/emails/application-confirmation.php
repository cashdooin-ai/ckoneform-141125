<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #667eea;">Application Submitted Successfully</h2>

        <p>Dear <?php echo esc_html($user->display_name); ?>,</p>

        <p>Thank you for submitting your application through CollegeKampus OneForm.</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0;">
            <p><strong>Application Number:</strong> <?php echo esc_html($application->application_number); ?></p>
            <p><strong>Submission Date:</strong> <?php echo date('F j, Y', strtotime($application->submission_date)); ?></p>
            <p><strong>Status:</strong> <?php echo esc_html(ucfirst($application->status)); ?></p>
        </div>

        <p>You can track your application status anytime by logging into your dashboard or using your application number.</p>

        <p style="margin-top: 30px;">
            <a href="<?php echo home_url('/my-applications/'); ?>" style="background-color: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Dashboard
            </a>
        </p>

        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            If you have any questions, please contact us at <?php echo get_option('admin_email'); ?>
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="color: #999; font-size: 12px;">
            This is an automated email. Please do not reply to this message.
        </p>
    </div>
</body>
</html>
