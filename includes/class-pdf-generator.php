<?php
/**
 * PDF Generator
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_PDF_Generator {

    /**
     * Generate application PDF
     */
    public static function generate_application_pdf($application_id) {
        $application = CK_OneForm_Database::get_application($application_id);
        if (!$application) {
            return false;
        }

        // Get user data
        $user = get_userdata($application->user_id);
        $form_data = json_decode($application->form_data, true);

        // Generate HTML content
        ob_start();
        include CK_ONEFORM_PLUGIN_DIR . 'templates/pdf/application.php';
        $html = ob_get_clean();

        // Use a PDF library (you would need to include a library like TCPDF or FPDF)
        // For now, this is a placeholder
        // Example with TCPDF (would need to be installed separately):
        /*
        require_once(CK_ONEFORM_PLUGIN_DIR . 'vendor/tcpdf/tcpdf.php');

        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = 'application-' . $application->application_number . '.pdf';
        $filepath = wp_upload_dir()['basedir'] . '/oneform-pdfs/' . $filename;

        $pdf->Output($filepath, 'F');

        return $filepath;
        */

        // For now, return HTML
        return $html;
    }

    /**
     * Download application PDF
     */
    public static function download_pdf($application_id) {
        if (!is_user_logged_in()) {
            wp_die(__('You must be logged in to download PDF', 'ck-oneform'));
        }

        $application = CK_OneForm_Database::get_application($application_id);

        // Check if user owns this application or is admin
        if ($application->user_id != get_current_user_id() && !current_user_can('manage_options')) {
            wp_die(__('You do not have permission to download this PDF', 'ck-oneform'));
        }

        $pdf_content = self::generate_application_pdf($application_id);

        // Set headers for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="application-' . $application->application_number . '.pdf"');

        echo $pdf_content;
        exit;
    }
}
