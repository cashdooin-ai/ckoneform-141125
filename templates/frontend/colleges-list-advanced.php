<?php
/**
 * Advanced College List Template with Filters
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get filter parameters
$selected_type = isset($_GET['college_type']) ? sanitize_text_field($_GET['college_type']) : '';
$selected_state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
$selected_city = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : '';
$selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$sort_by = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'name'; // Sort by name by default
$view_mode = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'card'; // card or list view

// Build query args
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
// Use shortcode limit as default, allow URL parameter to override
$default_per_page = isset($shortcode_atts['limit']) ? intval($shortcode_atts['limit']) : 30;
$per_page = isset($_GET['per_page']) ? sanitize_text_field($_GET['per_page']) : $default_per_page;
// Handle "all" option
if ($per_page === 'all') {
    $per_page = -1; // WordPress convention for all posts
} else {
    $per_page = intval($per_page);
    if ($per_page > 500) $per_page = 500; // Cap at 500
}
$args = array(
    'post_type' => 'ck_college',
    'posts_per_page' => $per_page,
    'paged' => $paged,
    'post_status' => 'publish',
);

// Add search
if (!empty($search_query)) {
    $args['s'] = $search_query;
}

// Add taxonomy filters
$tax_query = array('relation' => 'AND');

if (!empty($selected_type)) {
    $tax_query[] = array(
        'taxonomy' => 'college_type',
        'field' => 'slug',
        'terms' => $selected_type,
    );
}

if (!empty($selected_state)) {
    $tax_query[] = array(
        'taxonomy' => 'college_state',
        'field' => 'slug',
        'terms' => $selected_state,
    );
}

if (!empty($selected_city)) {
    $tax_query[] = array(
        'taxonomy' => 'college_city',
        'field' => 'slug',
        'terms' => $selected_city,
    );
}

if (count($tax_query) > 1) {
    $args['tax_query'] = $tax_query;
}

// Add meta query for category filter
if (!empty($selected_category)) {
    $args['meta_query'] = array(
        array(
            'key' => 'category',
            'value' => $selected_category,
            'compare' => '=',
        ),
    );
}

// Add sorting
if ($sort_by === 'nirf_rank') {
    $args['meta_key'] = '_ck_nirf_rank';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'ASC';
} elseif ($sort_by === 'ck_rank') {
    $args['meta_key'] = '_ck_ck_rank';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'ASC';
} elseif ($sort_by === 'name') {
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
} elseif ($sort_by === 'established') {
    $args['meta_key'] = '_ck_established';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'DESC';
}

$colleges_query = new WP_Query($args);

// Get all filter options
$all_types = get_terms(array('taxonomy' => 'college_type', 'hide_empty' => true));
$all_states = get_terms(array('taxonomy' => 'college_state', 'hide_empty' => true));
$all_cities = get_terms(array('taxonomy' => 'college_city', 'hide_empty' => true));

// Get unique categories from meta
global $wpdb;
$categories = $wpdb->get_col("SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'category' AND meta_value != ''");
?>

<div class="ck-colleges-list-advanced">

    <!-- Header Section -->
    <div class="colleges-header">
        <div class="header-content">
            <h1>🏛️ Top Colleges in India</h1>
            <p class="subtitle">Explore and select from <?php echo $colleges_query->found_posts; ?> premium colleges across India</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="colleges-filters">
        <form method="get" action="" id="college-filter-form">
            <div class="filters-grid">

                <!-- Search -->
                <div class="filter-item filter-search">
                    <label>🔍 Search Colleges</label>
                    <input type="text"
                           name="s"
                           value="<?php echo esc_attr($search_query); ?>"
                           placeholder="Search by name, city, state..."
                           class="filter-input">
                </div>

                <!-- College Type Filter -->
                <div class="filter-item">
                    <label>🎓 College Type</label>
                    <select name="college_type" class="filter-select">
                        <option value="">All Types</option>
                        <?php foreach ($all_types as $type): ?>
                            <option value="<?php echo esc_attr($type->slug); ?>"
                                    <?php selected($selected_type, $type->slug); ?>>
                                <?php echo esc_html($type->name); ?> (<?php echo $type->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- State Filter -->
                <div class="filter-item">
                    <label>📍 State</label>
                    <select name="state" class="filter-select" id="state-filter">
                        <option value="">All States</option>
                        <?php foreach ($all_states as $state): ?>
                            <option value="<?php echo esc_attr($state->slug); ?>"
                                    <?php selected($selected_state, $state->slug); ?>>
                                <?php echo esc_html($state->name); ?> (<?php echo $state->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- City Filter -->
                <div class="filter-item">
                    <label>🏙️ City</label>
                    <select name="city" class="filter-select">
                        <option value="">All Cities</option>
                        <?php foreach ($all_cities as $city): ?>
                            <option value="<?php echo esc_attr($city->slug); ?>"
                                    <?php selected($selected_city, $city->slug); ?>>
                                <?php echo esc_html($city->name); ?> (<?php echo $city->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="filter-item">
                    <label>📚 Category</label>
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo esc_attr($category); ?>"
                                    <?php selected($selected_category, $category); ?>>
                                <?php echo esc_html($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="filter-item">
                    <label>📊 Sort By</label>
                    <select name="sort" class="filter-select">
                        <option value="nirf_rank" <?php selected($sort_by, 'nirf_rank'); ?>>NIRF Ranking</option>
                        <option value="ck_rank" <?php selected($sort_by, 'ck_rank'); ?>>CK Ranking</option>
                        <option value="name" <?php selected($sort_by, 'name'); ?>>Name (A-Z)</option>
                        <option value="established" <?php selected($sort_by, 'established'); ?>>Newest First</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div class="filter-item">
                    <label>📄 Per Page</label>
                    <select name="per_page" class="filter-select">
                        <option value="30" <?php selected($per_page, 30); ?>>30 Colleges</option>
                        <option value="60" <?php selected($per_page, 60); ?>>60 Colleges</option>
                        <option value="90" <?php selected($per_page, 90); ?>>90 Colleges</option>
                        <option value="120" <?php selected($per_page, 120); ?>>120 Colleges</option>
                        <option value="150" <?php selected($per_page, 150); ?>>150 Colleges</option>
                        <option value="all" <?php selected($per_page, -1); ?>>All Colleges</option>
                    </select>
                </div>

            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">🔍 Apply Filters</button>
                <a href="<?php echo esc_url(remove_query_arg(array('college_type', 'state', 'city', 'category', 's', 'sort', 'per_page', 'view'))); ?>"
                   class="btn-reset">🔄 Reset All</a>
                <button type="button" class="btn-select-mode" id="toggle-selection-mode">
                    ✅ Multi-Select Mode
                </button>
                <input type="hidden" name="view" id="view-mode-input" value="<?php echo esc_attr($view_mode); ?>">
            </div>
        </form>

        <!-- View Toggle -->
        <div class="view-toggle">
            <span class="view-label">View:</span>
            <button type="button" class="view-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>" data-view="card">
                <span class="view-icon">▦</span> Card
            </button>
            <button type="button" class="view-btn <?php echo $view_mode === 'list' ? 'active' : ''; ?>" data-view="list">
                <span class="view-icon">≡</span> List
            </button>
        </div>
    </div>

    <!-- Selected Colleges Counter -->
    <div class="selected-colleges-bar" id="selected-bar" style="display: none;">
        <div class="selected-content">
            <span class="selected-count">Selected: <strong id="selected-count">0</strong> / 10 colleges</span>
            <button type="button" class="btn-view-selected" id="view-selected">View Selected</button>
            <button type="button" class="btn-apply-selected">Apply Now</button>
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info">
        <p>
            Showing <strong id="shown-count"><?php echo $colleges_query->post_count; ?></strong> of
            <strong id="total-count"><?php echo $colleges_query->found_posts; ?></strong> colleges
        </p>
    </div>

    <!-- Colleges Container (supports both card and list view) -->
    <div class="colleges-container <?php echo $view_mode === 'list' ? 'list-view' : 'card-view'; ?>" id="colleges-container">
        <?php if ($colleges_query->have_posts()): ?>
            <?php
            $college_counter = 0;
            while ($colleges_query->have_posts()): $colleges_query->the_post();
                $college_id = get_the_ID();
                // Try new meta keys first, fallback to old ones
                $short_name = get_post_meta($college_id, '_ck_short_name', true) ?: get_post_meta($college_id, 'short_name', true);
                $type_terms = wp_get_post_terms($college_id, 'college_type', array('fields' => 'names'));
                $type = !empty($type_terms) ? $type_terms[0] : (get_post_meta($college_id, 'college_type', true) ?: 'College');
                $state_terms = wp_get_post_terms($college_id, 'college_state', array('fields' => 'names'));
                $state = !empty($state_terms) ? $state_terms[0] : get_post_meta($college_id, 'state', true);
                $city_terms = wp_get_post_terms($college_id, 'college_city', array('fields' => 'names'));
                $city = !empty($city_terms) ? $city_terms[0] : get_post_meta($college_id, 'city', true);
                $nirf_rank = get_post_meta($college_id, '_ck_nirf_rank', true) ?: get_post_meta($college_id, 'nirf_rank', true);
                $ck_rank = get_post_meta($college_id, 'ck_rank', true);
                $established = get_post_meta($college_id, '_ck_established', true) ?: get_post_meta($college_id, 'established', true);
                $accreditation = get_post_meta($college_id, '_ck_accreditation', true) ?: get_post_meta($college_id, 'accreditation', true);
                $courses = get_post_meta($college_id, '_ck_courses_offered', true) ?: get_post_meta($college_id, 'courses', true);
                $fees_range = get_post_meta($college_id, '_ck_fees_range', true) ?: get_post_meta($college_id, 'fees_range', true);
                $website = get_post_meta($college_id, '_ck_website', true) ?: get_post_meta($college_id, 'website', true);
                $ownership = get_post_meta($college_id, '_ck_ownership', true) ?: get_post_meta($college_id, 'ownership', true);
                $avg_placement = get_post_meta($college_id, '_ck_avg_placement', true);
                $detail_url = add_query_arg('college_id', $college_id, home_url('/college-details/'));
                $college_counter++;
            ?>
            <!-- College Item (works for both card and list view) -->
            <div class="college-item" data-college-id="<?php echo $college_id; ?>" data-index="<?php echo $college_counter; ?>">
                <div class="item-header">
                    <div class="college-badge <?php echo strtolower(preg_replace('/[^a-z0-9]/', '', strtolower($type))); ?>">
                        <?php echo esc_html($type); ?>
                    </div>
                    <?php if ($nirf_rank): ?>
                        <div class="rank-badge">
                            #<?php echo $nirf_rank; ?> NIRF
                        </div>
                    <?php endif; ?>
                    <div class="selection-checkbox" style="display: none;">
                        <input type="checkbox"
                               class="college-select-checkbox"
                               data-college-id="<?php echo $college_id; ?>"
                               data-college-name="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                </div>

                <div class="item-body">
                    <div class="item-main">
                        <h3 class="college-name">
                            <a href="<?php echo esc_url($detail_url); ?>"><?php echo get_the_title(); ?></a>
                        </h3>

                        <?php if ($short_name): ?>
                            <p class="college-short-name"><?php echo esc_html($short_name); ?></p>
                        <?php endif; ?>

                        <div class="college-meta">
                            <span class="meta-item">
                                📍 <?php echo esc_html($city . ', ' . $state); ?>
                            </span>

                            <?php if ($established): ?>
                                <span class="meta-item">
                                    📅 Est. <?php echo esc_html($established); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($accreditation): ?>
                                <span class="meta-item">
                                    ⭐ <?php echo esc_html($accreditation); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($ownership): ?>
                                <span class="meta-item">
                                    🏛️ <?php echo esc_html($ownership); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="item-details">
                        <?php if ($courses): ?>
                            <div class="college-courses">
                                <strong>Courses:</strong> <?php echo esc_html($courses); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($fees_range): ?>
                            <div class="college-fees">
                                <strong>Annual Fees:</strong> ₹<?php echo esc_html($fees_range); ?>
                            </div>
                        <?php endif; ?>

                        <div class="college-rankings">
                            <?php if ($nirf_rank): ?>
                                <span class="ranking-item">NIRF: #<?php echo $nirf_rank; ?></span>
                            <?php endif; ?>
                            <?php if ($ck_rank): ?>
                                <span class="ranking-item">CK: #<?php echo $ck_rank; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="item-footer">
                    <a href="<?php echo esc_url($detail_url); ?>"
                       class="btn-view-details">
                        📋 View Details
                    </a>
                    <?php if ($website): ?>
                        <a href="<?php echo esc_url($website); ?>"
                           target="_blank"
                           class="btn-website">
                            🌐 Website
                        </a>
                    <?php endif; ?>
                    <button type="button"
                            class="btn-select-college"
                            data-college-id="<?php echo $college_id; ?>"
                            data-college-name="<?php echo esc_attr(get_the_title()); ?>">
                        ➕ Select
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="no-results">
                <h3>😔 No colleges found</h3>
                <p>Try adjusting your filters or search criteria.</p>
                <a href="<?php echo esc_url(remove_query_arg(array('college_type', 'state', 'city', 'category', 's', 'sort', 'view', 'per_page'))); ?>"
                   class="btn-reset">Reset Filters</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Load More Button -->
    <?php if ($colleges_query->found_posts > $colleges_query->post_count): ?>
        <div class="load-more-section" id="load-more-section">
            <button type="button" class="btn-load-more" id="load-more-btn"
                    data-page="1"
                    data-per-load="10"
                    data-total="<?php echo $colleges_query->found_posts; ?>"
                    data-loaded="<?php echo $colleges_query->post_count; ?>">
                📥 Load More (10 Colleges)
            </button>
            <p class="load-more-info">
                Loaded <span id="loaded-count"><?php echo $colleges_query->post_count; ?></span> of <?php echo $colleges_query->found_posts; ?> colleges
            </p>
        </div>
    <?php endif; ?>

    <!-- Traditional Pagination (hidden by default, shown if JS disabled) -->
    <noscript>
    <?php if ($colleges_query->max_num_pages > 1): ?>
        <div class="colleges-pagination">
            <?php
            echo paginate_links(array(
                'total' => $colleges_query->max_num_pages,
                'current' => $paged,
                'prev_text' => '« Previous',
                'next_text' => 'Next »',
            ));
            ?>
        </div>
    <?php endif; ?>
    </noscript>

</div>

<!-- Selected Colleges Modal -->
<div class="selected-modal" id="selected-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2>Selected Colleges (<span id="modal-count">0</span>)</h2>
            <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" id="selected-list"></div>
        <div class="modal-footer">
            <button type="button" class="btn-clear-all">Clear All</button>
            <button type="button" class="btn-proceed">Proceed to Application</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    let selectedColleges = [];
    const MAX_SELECTIONS = 10;

    // Toggle selection mode
    $('#toggle-selection-mode').on('click', function() {
        $('body').toggleClass('selection-mode');
        $('.selection-checkbox').toggle();
        $('.btn-select-college').toggle();

        if ($('body').hasClass('selection-mode')) {
            $(this).text('❌ Cancel Selection');
            $('#selected-bar').show();
        } else {
            $(this).text('✅ Multi-Select Mode');
            $('#selected-bar').hide();
        }
    });

    // Handle college selection
    $(document).on('click', '.btn-select-college, .college-select-checkbox', function() {
        const collegeId = $(this).data('college-id');
        const collegeName = $(this).data('college-name');

        const index = selectedColleges.findIndex(c => c.id === collegeId);

        if (index > -1) {
            // Deselect
            selectedColleges.splice(index, 1);
            $(`.college-card[data-college-id="${collegeId}"]`).removeClass('selected');
            $(`.college-select-checkbox[data-college-id="${collegeId}"]`).prop('checked', false);
        } else {
            // Select
            if (selectedColleges.length >= MAX_SELECTIONS) {
                alert(`You can only select up to ${MAX_SELECTIONS} colleges`);
                return;
            }
            selectedColleges.push({ id: collegeId, name: collegeName });
            $(`.college-card[data-college-id="${collegeId}"]`).addClass('selected');
            $(`.college-select-checkbox[data-college-id="${collegeId}"]`).prop('checked', true);
        }

        updateSelectedCount();
    });

    // Update selected count
    function updateSelectedCount() {
        $('#selected-count, #modal-count').text(selectedColleges.length);

        if (selectedColleges.length > 0) {
            $('#selected-bar').show();
        } else {
            $('#selected-bar').hide();
        }
    }

    // View selected
    $('#view-selected').on('click', function() {
        let html = '<div class="selected-colleges-list">';
        selectedColleges.forEach((college, index) => {
            html += `
                <div class="selected-item">
                    <span class="item-number">${index + 1}.</span>
                    <span class="item-name">${college.name}</span>
                    <button type="button" class="btn-remove-selected" data-college-id="${college.id}">Remove</button>
                </div>
            `;
        });
        html += '</div>';

        $('#selected-list').html(html);
        $('#selected-modal').show();
    });

    // Close modal
    $('.modal-close, .modal-overlay').on('click', function() {
        $('#selected-modal').hide();
    });

    // Remove from selected
    $(document).on('click', '.btn-remove-selected', function() {
        const collegeId = $(this).data('college-id');
        const index = selectedColleges.findIndex(c => c.id === collegeId);

        if (index > -1) {
            selectedColleges.splice(index, 1);
            $(`.college-card[data-college-id="${collegeId}"]`).removeClass('selected');
            $(`.college-select-checkbox[data-college-id="${collegeId}"]`).prop('checked', false);
            updateSelectedCount();
            $('#view-selected').trigger('click'); // Refresh modal
        }
    });

    // Clear all
    $('.btn-clear-all').on('click', function() {
        selectedColleges = [];
        $('.college-card').removeClass('selected');
        $('.college-select-checkbox').prop('checked', false);
        updateSelectedCount();
        $('#selected-modal').hide();
    });

    // Proceed to application
    $('.btn-proceed, .btn-apply-selected').on('click', function() {
        if (selectedColleges.length === 0) {
            alert('Please select at least one college');
            return;
        }

        // Store selected colleges in sessionStorage
        sessionStorage.setItem('selectedColleges', JSON.stringify(selectedColleges));

        // Redirect to enhanced application form
        // Note: Create a page with slug 'apply' and add shortcode [ck_oneform_apply]
        window.location.href = '<?php echo esc_url(home_url('/apply/')); ?>?colleges=' + selectedColleges.map(c => c.id).join(',');
    });

    // View Toggle functionality
    $('.view-btn').on('click', function() {
        const viewMode = $(this).data('view');

        // Update hidden input
        $('#view-mode-input').val(viewMode);

        // Update button states
        $('.view-btn').removeClass('active');
        $(this).addClass('active');

        // Toggle container classes
        $('#colleges-container').removeClass('card-view list-view').addClass(viewMode + '-view');

        // Update URL without reload (optional)
        const url = new URL(window.location);
        url.searchParams.set('view', viewMode);
        window.history.pushState({}, '', url);
    });

    // Load More functionality
    let visibleCount = <?php echo $colleges_query->post_count; ?>;
    const totalCount = <?php echo $colleges_query->found_posts; ?>;
    const perLoad = 10;

    // Initially hide items beyond the first batch (for load more to work)
    // Since we're loading all items server-side, we'll hide them with JS
    const $allItems = $('.college-item');
    const initialVisible = Math.min(<?php echo $per_page === -1 ? 30 : $per_page; ?>, $allItems.length);

    $allItems.each(function(index) {
        if (index >= initialVisible) {
            $(this).hide();
        }
    });
    visibleCount = initialVisible;
    $('#shown-count').text(visibleCount);
    $('#loaded-count').text(visibleCount);

    // Update load more button visibility
    function updateLoadMoreButton() {
        if (visibleCount >= $allItems.length) {
            $('#load-more-section').hide();
        } else {
            $('#load-more-section').show();
            $('#loaded-count').text(visibleCount);
        }
    }
    updateLoadMoreButton();

    // Load More Click Handler
    $('#load-more-btn').on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).text('Loading...');

        // Simulate loading delay for better UX
        setTimeout(function() {
            const newVisible = Math.min(visibleCount + perLoad, $allItems.length);

            for (let i = visibleCount; i < newVisible; i++) {
                $allItems.eq(i).fadeIn(300);
            }

            visibleCount = newVisible;
            $('#shown-count').text(visibleCount);
            $('#loaded-count').text(visibleCount);

            $btn.prop('disabled', false).text('📥 Load More (10 Colleges)');

            updateLoadMoreButton();
        }, 500);
    });
});
</script>

<style>
.ck-colleges-list-advanced {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.colleges-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 40px;
    border-radius: 12px;
    margin-bottom: 30px;
    text-align: center;
}

.colleges-header h1 {
    font-size: 2.5rem;
    margin: 0 0 10px 0;
    color: white;
}

.subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
}

.colleges-filters {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.filter-item label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
    background: white;
    /* Ensure text displays fully */
    overflow: visible;
    text-overflow: ellipsis;
    white-space: normal;
}

/* Fix dropdown option display */
.filter-select option {
    padding: 8px;
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-filter,
.btn-reset,
.btn-select-mode {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    font-size: 14px;
}

.btn-filter {
    background: #667eea;
    color: white;
}

.btn-filter:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-reset {
    background: #f5f5f5;
    color: #666;
    text-decoration: none;
}

.btn-select-mode {
    background: #10b981;
    color: white;
}

.selected-colleges-bar {
    background: #10b981;
    color: white;
    padding: 15px 30px;
    border-radius: 8px;
    margin-bottom: 20px;
    position: sticky;
    top: 20px;
    z-index: 100;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.selected-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.selected-count {
    font-size: 1.1rem;
}

.btn-view-selected,
.btn-apply-selected {
    background: white;
    color: #10b981;
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.results-info {
    margin-bottom: 20px;
    color: #666;
}

/* View Toggle Styles */
.view-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

.view-label {
    font-weight: 600;
    color: #333;
}

.view-btn {
    padding: 8px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.view-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.view-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.view-icon {
    font-size: 1.2rem;
}

/* Colleges Container - Supports both card and list view */
.colleges-container {
    margin-bottom: 40px;
}

/* Card View */
.colleges-container.card-view {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

/* List View */
.colleges-container.list-view {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.college-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
    position: relative;
}

.college-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.college-item.selected {
    border: 3px solid #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* List view specific styles */
.list-view .college-item {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}

.list-view .college-item:hover {
    transform: translateY(-2px);
}

.list-view .item-header {
    width: 150px;
    flex-shrink: 0;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.list-view .item-body {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    padding: 15px;
}

.list-view .item-main {
    flex: 1;
    min-width: 250px;
}

.list-view .item-details {
    flex: 1;
    min-width: 200px;
    border-left: 1px solid #e0e0e0;
    padding-left: 15px;
}

.list-view .item-footer {
    width: 200px;
    flex-shrink: 0;
    flex-direction: column;
    justify-content: center;
}

.list-view .college-name {
    font-size: 1.1rem;
    margin-bottom: 5px;
}

/* Card view specific styles */
.card-view .item-header {
    background: #f8f9fa;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-view .item-body {
    padding: 20px;
}

.card-view .item-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
}

.item-header {
    background: #f8f9fa;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.item-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
}

/* Load More Button */
.load-more-section {
    text-align: center;
    margin: 30px 0;
}

.btn-load-more {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-load-more:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.btn-load-more:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.load-more-info {
    margin-top: 10px;
    color: #666;
    font-size: 14px;
}

.college-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.college-badge.iit {
    background: #fef3c7;
    color: #92400e;
}

.college-badge.nit {
    background: #dbeafe;
    color: #1e40af;
}

.college-badge.iiit {
    background: #e0e7ff;
    color: #3730a3;
}

.college-badge.medical {
    background: #fee2e2;
    color: #991b1b;
}

.college-badge.private {
    background: #f3e8ff;
    color: #6b21a8;
}

.rank-badge {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.card-body {
    padding: 20px;
}

.college-name {
    font-size: 1.2rem;
    margin: 0 0 8px 0;
    color: #1a1a1a;
    line-height: 1.3;
}

.college-name a {
    color: #1a1a1a;
    text-decoration: none;
    transition: color 0.3s;
}

.college-name a:hover {
    color: #667eea;
}

.college-short-name {
    color: #667eea;
    font-weight: 600;
    margin: 0 0 15px 0;
}

.college-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 12px;
}

.meta-item {
    font-size: 13px;
    color: #666;
}

.college-courses,
.college-fees {
    font-size: 14px;
    margin: 8px 0;
    color: #555;
}

.college-rankings {
    display: flex;
    gap: 10px;
    margin-top: 12px;
}

.ranking-item {
    background: #f0f4ff;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #3730a3;
}

.card-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
}

.btn-view-details,
.btn-website,
.btn-select-college {
    flex: 1;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    border: none;
    font-size: 13px;
}

.btn-view-details {
    background: #f59e0b;
    color: white;
}

.btn-view-details:hover {
    background: #d97706;
    color: white;
    transform: translateY(-2px);
}

.btn-website {
    background: #667eea;
    color: white;
}

.btn-website:hover {
    background: #5568d3;
    color: white;
}

.btn-select-college {
    background: #10b981;
    color: white;
}

.btn-select-college:hover {
    background: #059669;
}

.selection-checkbox {
    position: absolute;
    top: 15px;
    right: 15px;
}

.selection-checkbox input[type="checkbox"] {
    width: 24px;
    height: 24px;
    cursor: pointer;
}

.colleges-pagination {
    text-align: center;
    margin: 40px 0;
}

.colleges-pagination .page-numbers {
    display: inline-block;
    padding: 10px 16px;
    margin: 0 4px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}

.colleges-pagination .page-numbers.current {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.selected-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
}

.modal-header {
    padding: 20px 30px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-close {
    background: none;
    border: none;
    font-size: 32px;
    cursor: pointer;
    color: #999;
}

.modal-body {
    padding: 20px 30px;
    overflow-y: auto;
    flex: 1;
}

.selected-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.item-number {
    background: #667eea;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.item-name {
    flex: 1;
    font-weight: 500;
}

.btn-remove-selected {
    background: #ef4444;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 2px solid #f0f0f0;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-clear-all {
    background: #ef4444;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.btn-proceed {
    background: #10b981;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

@media (max-width: 768px) {
    .colleges-grid {
        grid-template-columns: 1fr;
    }

    .filters-grid {
        grid-template-columns: 1fr;
    }

    .colleges-header h1 {
        font-size: 1.8rem;
    }
}
</style>
