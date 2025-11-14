<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Application Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #667eea;">New Application Received</h2>

        <p>A new application has been submitted through OneForm.</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0;">
            <p><strong>Application Number:</strong> <?php echo esc_html($application->application_number); ?></p>
            <p><strong>Student:</strong> <?php echo esc_html($user->display_name); ?></p>
            <p><strong>Email:</strong> <?php echo esc_html($user->user_email); ?></p>
            <p><strong>Submission Date:</strong> <?php echo date('F j, Y H:i', strtotime($application->submission_date)); ?></p>
        </div>

        <p>
            <a href="<?php echo admin_url('admin.php?page=ck-oneform-applications'); ?>" style="background-color: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Application
            </a>
        </p>
    </div>
</body>
</html>
