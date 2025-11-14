<?php
/**
 * Colleges List Template
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = array(
    'post_type' => 'ck_college',
    'posts_per_page' => isset($atts['limit']) ? intval($atts['limit']) : 10,
    'orderby' => 'title',
    'order' => 'ASC'
);

if (!empty($atts['type'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'college_type',
            'field' => 'slug',
            'terms' => $atts['type']
        )
    );
}

$colleges = new WP_Query($args);
?>

<div class="ck-oneform-colleges-list">
    <?php if ($colleges->have_posts()) : ?>
        <div class="row">
            <?php while ($colleges->have_posts()) : $colleges->the_post(); ?>
                <div class="col-md-6 mb-4">
                    <div class="college-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="college-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="college-content">
                            <h3><?php the_title(); ?></h3>
                            <?php if (has_excerpt()) : ?>
                                <p><?php echo get_the_excerpt(); ?></p>
                            <?php endif; ?>

                            <div class="college-meta">
                                <?php
                                $terms = get_the_terms(get_the_ID(), 'college_type');
                                if ($terms) :
                                    foreach ($terms as $term) :
                                ?>
                                        <span class="badge badge-info"><?php echo esc_html($term->name); ?></span>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>

                            <div class="college-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">
                                    <?php _e('View Details', 'ck-oneform'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($colleges->max_num_pages > 1) : ?>
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $colleges->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'prev_text' => __('&laquo; Previous', 'ck-oneform'),
                    'next_text' => __('Next &raquo;', 'ck-oneform'),
                ));
                ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <p><?php _e('No colleges found.', 'ck-oneform'); ?></p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</div>
