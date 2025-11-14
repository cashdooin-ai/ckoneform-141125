<?php
/**
 * Application Status Check Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ck-oneform-status-check">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="status-check-card">
                    <h2><?php _e('Check Application Status', 'ck-oneform'); ?></h2>
                    <p><?php _e('Enter your application number to check your application status', 'ck-oneform'); ?></p>

                    <form id="ck-oneform-status-form" class="ck-oneform-form">
                        <div class="form-group">
                            <label for="application_number"><?php _e('Application Number', 'ck-oneform'); ?></label>
                            <input type="text" class="form-control" id="application_number" name="application_number" placeholder="CK2025XXXXXX" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <?php _e('Check Status', 'ck-oneform'); ?>
                        </button>
                    </form>

                    <div id="status-result" class="mt-4" style="display:none;">
                        <div class="alert alert-info">
                            <h4><?php _e('Application Details', 'ck-oneform'); ?></h4>
                            <table class="table table-sm">
                                <tr>
                                    <th><?php _e('Application Number:', 'ck-oneform'); ?></th>
                                    <td id="result-app-number"></td>
                                </tr>
                                <tr>
                                    <th><?php _e('Submission Date:', 'ck-oneform'); ?></th>
                                    <td id="result-submission-date"></td>
                                </tr>
                                <tr>
                                    <th><?php _e('Status:', 'ck-oneform'); ?></th>
                                    <td id="result-status"></td>
                                </tr>
                                <tr>
                                    <th><?php _e('Last Updated:', 'ck-oneform'); ?></th>
                                    <td id="result-updated-date"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-message"></div>
                </div>
            </div>
        </div>
    </div>
</div>
