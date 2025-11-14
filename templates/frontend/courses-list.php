<?php
/**
 * Courses List Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = array(
    'post_type' => 'ck_course',
    'posts_per_page' => isset($atts['limit']) ? intval($atts['limit']) : 10,
    'orderby' => 'title',
    'order' => 'ASC'
);

if (!empty($atts['category'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'course_category',
            'field' => 'slug',
            'terms' => $atts['category']
        )
    );
}

$courses = new WP_Query($args);
?>

<div class="ck-oneform-courses-list">
    <?php if ($courses->have_posts()) : ?>
        <div class="row">
            <?php while ($courses->have_posts()) : $courses->the_post(); ?>
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="course-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="course-content">
                            <h3><?php the_title(); ?></h3>
                            <?php if (has_excerpt()) : ?>
                                <p><?php echo get_the_excerpt(); ?></p>
                            <?php endif; ?>

                            <div class="course-meta">
                                <?php
                                $terms = get_the_terms(get_the_ID(), 'course_category');
                                if ($terms) :
                                    foreach ($terms as $term) :
                                ?>
                                        <span class="badge badge-secondary"><?php echo esc_html($term->name); ?></span>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>

                            <div class="course-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">
                                    <?php _e('View Details', 'ck-oneform'); ?>
                                </a>
                                <?php if (is_user_logged_in()) : ?>
                                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('application-form'))); ?>?course=<?php echo get_the_ID(); ?>" class="btn btn-sm btn-primary">
                                        <?php _e('Apply Now', 'ck-oneform'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($courses->max_num_pages > 1) : ?>
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $courses->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'prev_text' => __('&laquo; Previous', 'ck-oneform'),
                    'next_text' => __('Next &raquo;', 'ck-oneform'),
                ));
                ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <p><?php _e('No courses found.', 'ck-oneform'); ?></p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</div>
