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
$sort_by = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'nirf_rank';

// Build query args
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'ck_college',
    'posts_per_page' => 20,
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
    $args['meta_key'] = 'nirf_rank';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'ASC';
} elseif ($sort_by === 'ck_rank') {
    $args['meta_key'] = 'ck_rank';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'ASC';
} elseif ($sort_by === 'name') {
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
} elseif ($sort_by === 'established') {
    $args['meta_key'] = 'established';
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

            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">🔍 Apply Filters</button>
                <a href="<?php echo esc_url(remove_query_arg(array('college_type', 'state', 'city', 'category', 's', 'sort'))); ?>"
                   class="btn-reset">🔄 Reset All</a>
                <button type="button" class="btn-select-mode" id="toggle-selection-mode">
                    ✅ Multi-Select Mode
                </button>
            </div>
        </form>
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
            Showing <strong><?php echo $colleges_query->post_count; ?></strong> of
            <strong><?php echo $colleges_query->found_posts; ?></strong> colleges
        </p>
    </div>

    <!-- Colleges Grid -->
    <div class="colleges-grid" id="colleges-grid">
        <?php if ($colleges_query->have_posts()): ?>
            <?php while ($colleges_query->have_posts()): $colleges_query->the_post();
                $college_id = get_the_ID();
                $short_name = get_post_meta($college_id, 'short_name', true);
                $type = get_post_meta($college_id, 'college_type', true);
                $state = get_post_meta($college_id, 'state', true);
                $city = get_post_meta($college_id, 'city', true);
                $nirf_rank = get_post_meta($college_id, 'nirf_rank', true);
                $ck_rank = get_post_meta($college_id, 'ck_rank', true);
                $established = get_post_meta($college_id, 'established', true);
                $accreditation = get_post_meta($college_id, 'accreditation', true);
                $courses = get_post_meta($college_id, 'courses', true);
                $fees_range = get_post_meta($college_id, 'fees_range', true);
                $website = get_post_meta($college_id, 'website', true);
                $ownership = get_post_meta($college_id, 'ownership', true);
            ?>
            <div class="college-card" data-college-id="<?php echo $college_id; ?>">
                <div class="card-header">
                    <div class="college-badge <?php echo strtolower($type); ?>">
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

                <div class="card-body">
                    <h3 class="college-name"><?php echo get_the_title(); ?></h3>

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

                <div class="card-footer">
                    <a href="<?php echo get_permalink($college_id); ?>"
                       class="btn-view-details">
                        📖 View Details
                    </a>
                    <?php if ($website): ?>
                        <a href="<?php echo esc_url($website); ?>"
                           target="_blank"
                           class="btn-website">
                            🌐 Visit Website
                        </a>
                    <?php endif; ?>
                    <button type="button"
                            class="btn-select-college"
                            data-college-id="<?php echo $college_id; ?>"
                            data-college-name="<?php echo esc_attr(get_the_title()); ?>">
                        ➕ Select College
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="no-results">
                <h3>😔 No colleges found</h3>
                <p>Try adjusting your filters or search criteria.</p>
                <a href="<?php echo esc_url(remove_query_arg(array('college_type', 'state', 'city', 'category', 's', 'sort'))); ?>"
                   class="btn-reset">Reset Filters</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
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
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

.colleges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.college-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
    position: relative;
}

.college-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.college-card.selected {
    border: 3px solid #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.card-header {
    background: #f8f9fa;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
    flex-wrap: wrap;
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
    font-size: 14px;
    display: inline-block;
}

.btn-view-details {
    background: #f59e0b;
    color: white;
}

.btn-view-details:hover {
    background: #d97706;
    transform: translateY(-2px);
}

.btn-website {
    background: #667eea;
    color: white;
}

.btn-website:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-select-college {
    background: #10b981;
    color: white;
}

.btn-select-college:hover {
    background: #059669;
    transform: translateY(-2px);
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

    .card-footer {
        flex-direction: column;
    }

    .btn-view-details,
    .btn-website,
    .btn-select-college {
        width: 100%;
        flex: none;
    }
}
</style>
