/**
 * Multi-College Selection JavaScript
 *
 * @package CK_OneForm
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        const MAX_COLLEGES = 10;
        let selectedCount = 0;

        /**
         * Update selected count display
         */
        function updateSelectedCount() {
            selectedCount = $('.college-checkbox-input:checked').length;
            $('#selected-count').text(selectedCount);

            // Disable checkboxes if max reached
            if (selectedCount >= MAX_COLLEGES) {
                $('.college-checkbox-input:not(:checked)').prop('disabled', true);
                $('.college-card').not('.college-checkbox-input:checked + .college-card').addClass('disabled');
            } else {
                $('.college-checkbox-input').prop('disabled', false);
                $('.college-card').removeClass('disabled');
            }

            // Update summary
            updateSummary();
        }

        /**
         * Update application summary
         */
        function updateSummary() {
            const selectedColleges = [];
            $('.college-checkbox-input:checked').each(function() {
                const card = $(this).siblings('.college-card');
                selectedColleges.push({
                    id: $(this).val(),
                    name: card.find('.college-name').text(),
                    location: card.find('.college-location').text().trim()
                });
            });

            if (selectedColleges.length === 0) {
                $('#selected-colleges-summary').html(
                    '<p>' + ckOneForm.noCollegesSelected + '</p>'
                );
            } else {
                let html = '<div class="selected-colleges-list"><h3>You are applying to:</h3><ol>';
                selectedColleges.forEach(function(college) {
                    html += '<li><strong>' + college.name + '</strong> - ' + college.location + '</li>';
                });
                html += '</ol></div>';
                $('#selected-colleges-summary').html(html);
            }
        }

        /**
         * College checkbox change
         */
        $(document).on('change', '.college-checkbox-input', function() {
            updateSelectedCount();
        });

        /**
         * Clear all selections
         */
        $('#clear-selection').on('click', function() {
            $('.college-checkbox-input').prop('checked', false);
            updateSelectedCount();
        });

        /**
         * Search colleges
         */
        $('#college-search').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();

            $('.college-item').each(function() {
                const name = $(this).data('name');
                const location = $(this).data('location');

                if (name.indexOf(searchTerm) > -1 || location.indexOf(searchTerm) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Hide/show state groups if all items hidden
            $('.state-group').each(function() {
                const visibleItems = $(this).find('.college-item:visible').length;
                if (visibleItems === 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });

            // Show no results message
            const totalVisible = $('.college-item:visible').length;
            if (totalVisible === 0) {
                $('#no-colleges-message').show();
            } else {
                $('#no-colleges-message').hide();
            }
        });

        /**
         * Filter colleges by type/category
         */
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');

            const filter = $(this).data('filter');

            if (filter === 'all') {
                $('.college-item').show();
                $('.state-group').show();
            } else {
                $('.college-item').each(function() {
                    const type = $(this).data('type');
                    const category = $(this).data('category');

                    if (type === filter || category === filter) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Hide/show state groups
                $('.state-group').each(function() {
                    const visibleItems = $(this).find('.college-item:visible').length;
                    if (visibleItems === 0) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            }

            $('#no-colleges-message').hide();
        });

        /**
         * Form validation before submit
         */
        $('#ck-oneform-application-form').on('submit', function(e) {
            if (selectedCount === 0) {
                e.preventDefault();
                alert('Please select at least one college to apply to.');
                $('html, body').animate({
                    scrollTop: $('.college-selection-section').offset().top - 100
                }, 500);
                return false;
            }

            if (selectedCount > MAX_COLLEGES) {
                e.preventDefault();
                alert('You can only select up to ' + MAX_COLLEGES + ' colleges.');
                return false;
            }
        });

        // Initialize
        updateSelectedCount();
    });

})(jQuery);
