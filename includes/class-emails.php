<?php
/**
 * Email Handler
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Emails {

    /**
     * Send application confirmation email
     */
    public static function send_application_confirmation($application_id) {
        $application = CK_OneForm_Database::get_application($application_id);
        if (!$application) {
            return false;
        }

        $user = get_userdata($application->user_id);
        $to = $user->user_email;
        $subject = __('Application Submitted - CollegeKampus OneForm', 'ck-oneform');

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/emails/application-confirmation.php';
        $message = ob_get_clean();

        $headers = array('Content-Type: text/html; charset=UTF-8');

        return wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Send admin notification
     */
    public static function send_admin_notification($application_id) {
        $application = CK_OneForm_Database::get_application($application_id);
        if (!$application) {
            return false;
        }

        $admin_email = get_option('ck_oneform_admin_email', get_option('admin_email'));
        $subject = __('New Application Received - CollegeKampus OneForm', 'ck-oneform');

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/emails/admin-notification.php';
        $message = ob_get_clean();

        $headers = array('Content-Type: text/html; charset=UTF-8');

        return wp_mail($admin_email, $subject, $message, $headers);
    }

    /**
     * Send welcome email
     */
    public static function send_welcome_email($user_id) {
        $user = get_userdata($user_id);
        if (!$user) {
            return false;
        }

        $to = $user->user_email;
        $subject = __('Welcome to CollegeKampus OneForm', 'ck-oneform');

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/emails/welcome.php';
        $message = ob_get_clean();

        $headers = array('Content-Type: text/html; charset=UTF-8');

        return wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Send status update email
     */
    public static function send_status_update($application_id, $new_status) {
        $application = CK_OneForm_Database::get_application($application_id);
        if (!$application) {
            return false;
        }

        $user = get_userdata($application->user_id);
        $to = $user->user_email;
        $subject = __('Application Status Updated - CollegeKampus OneForm', 'ck-oneform');

        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/emails/status-update.php';
        $message = ob_get_clean();

        $headers = array('Content-Type: text/html; charset=UTF-8');

        return wp_mail($to, $subject, $message, $headers);
    }
}
