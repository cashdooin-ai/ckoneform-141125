<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to CollegeKampus OneForm</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #667eea;">Welcome to CollegeKampus OneForm!</h2>

        <p>Dear <?php echo esc_html($user->display_name); ?>,</p>

        <p>Welcome to CollegeKampus OneForm! Your account has been successfully created.</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0;">
            <p><strong>Username:</strong> <?php echo esc_html($user->user_login); ?></p>
            <p><strong>Email:</strong> <?php echo esc_html($user->user_email); ?></p>
        </div>

        <p>You can now:</p>
        <ul>
            <li>Apply to multiple colleges with a single form</li>
            <li>Track your application status in real-time</li>
            <li>Manage all your applications from one dashboard</li>
            <li>Upload documents and make payments online</li>
        </ul>

        <p style="margin-top: 30px;">
            <a href="<?php echo home_url('/application-form/'); ?>" style="background-color: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Submit Your First Application
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
