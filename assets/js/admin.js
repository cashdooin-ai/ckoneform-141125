/**
 * CollegeKampus OneForm - Admin JavaScript
 *
 * @package CK_OneForm
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Update Application Status
         */
        $('.ck-update-status').on('click', function(e) {
            e.preventDefault();

            var applicationId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = prompt('Enter new status (pending, approved, rejected):', currentStatus);

            if (!newStatus) return;

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ck_oneform_update_application_status',
                    application_id: applicationId,
                    status: newStatus,
                    nonce: ckOneFormAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Status updated successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        });

        /**
         * Delete Application
         */
        $('.ck-delete-application').on('click', function(e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to delete this application?')) {
                return;
            }

            var applicationId = $(this).data('id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ck_oneform_delete_application',
                    application_id: applicationId,
                    nonce: ckOneFormAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Application deleted successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        });

        /**
         * Export Applications
         */
        $('#ck-export-applications').on('click', function(e) {
            e.preventDefault();
            var status = $('#ck-export-status').val();
            window.location.href = ajaxurl + '?action=ck_oneform_export_csv&status=' + status;
        });

        /**
         * Filter Applications
         */
        $('#ck-filter-status').on('change', function() {
            var status = $(this).val();
            window.location.href = '?page=ck-oneform-applications&status=' + status;
        });

        /**
         * Search Applications
         */
        $('#ck-search-applications').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();

            $('.ck-oneform-table tbody tr').each(function() {
                var text = $(this).text().toLowerCase();

                if (text.indexOf(searchTerm) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        /**
         * Bulk Actions
         */
        $('#ck-bulk-action-apply').on('click', function(e) {
            e.preventDefault();

            var action = $('#ck-bulk-action').val();
            var selected = [];

            $('.ck-select-application:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                alert('Please select at least one application');
                return;
            }

            if (action === 'delete') {
                if (!confirm('Are you sure you want to delete ' + selected.length + ' application(s)?')) {
                    return;
                }
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ck_oneform_bulk_action',
                    bulk_action: action,
                    application_ids: selected,
                    nonce: ckOneFormAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        });

        /**
         * Select All Checkbox
         */
        $('#ck-select-all').on('change', function() {
            $('.ck-select-application').prop('checked', $(this).prop('checked'));
        });

    });

})(jQuery);
