<?php
/**
 * Modal Templates
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Loading Modal -->
<div class="ck-oneform-modal" id="ck-oneform-loading-modal">
    <div class="modal-content">
        <div class="spinner"></div>
        <p><?php _e('Processing...', 'ck-oneform'); ?></p>
    </div>
</div>

<!-- Success Modal -->
<div class="ck-oneform-modal" id="ck-oneform-success-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-icon success">✓</div>
        <h3><?php _e('Success!', 'ck-oneform'); ?></h3>
        <p class="modal-message"></p>
        <button class="btn btn-primary modal-close-btn"><?php _e('OK', 'ck-oneform'); ?></button>
    </div>
</div>

<!-- Error Modal -->
<div class="ck-oneform-modal" id="ck-oneform-error-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-icon error">✗</div>
        <h3><?php _e('Error', 'ck-oneform'); ?></h3>
        <p class="modal-message"></p>
        <button class="btn btn-primary modal-close-btn"><?php _e('Close', 'ck-oneform'); ?></button>
    </div>
</div>
