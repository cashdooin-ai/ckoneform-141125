<?php
/**
 * SEO Meta Fields for Colleges
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_SEO {

    /**
     * Initialize SEO functionality
     */
    public static function init() {
        // Add meta boxes for SEO fields
        add_action('add_meta_boxes', array(__CLASS__, 'add_seo_meta_boxes'));

        // Save SEO meta data
        add_action('save_post_ck_college', array(__CLASS__, 'save_seo_meta'), 10, 2);

        // Output SEO meta tags in head
        add_action('wp_head', array(__CLASS__, 'output_seo_meta_tags'), 1);

        // Add Open Graph tags
        add_action('wp_head', array(__CLASS__, 'output_open_graph_tags'), 2);

        // Add Twitter Card tags
        add_action('wp_head', array(__CLASS__, 'output_twitter_card_tags'), 3);
    }

    /**
     * Add SEO meta boxes to college post type
     */
    public static function add_seo_meta_boxes() {
        add_meta_box(
            'ck_college_seo',
            '🎯 SEO Settings - Optimize for Google',
            array(__CLASS__, 'render_seo_meta_box'),
            'ck_college',
            'normal',
            'high'
        );
    }

    /**
     * Render SEO meta box
     */
    public static function render_seo_meta_box($post) {
        wp_nonce_field('ck_college_seo_nonce', 'ck_college_seo_nonce_field');

        // Get existing values
        $seo_title = get_post_meta($post->ID, 'seo_title', true);
        $seo_description = get_post_meta($post->ID, 'seo_description', true);
        $seo_keywords = get_post_meta($post->ID, 'seo_keywords', true);
        $focus_keyword = get_post_meta($post->ID, 'focus_keyword', true);
        $canonical_url = get_post_meta($post->ID, 'canonical_url', true);
        $og_image = get_post_meta($post->ID, 'og_image', true);
        $robots_index = get_post_meta($post->ID, 'robots_index', true) ?: 'index';
        $robots_follow = get_post_meta($post->ID, 'robots_follow', true) ?: 'follow';

        ?>
        <style>
            .ck-seo-field {
                margin-bottom: 20px;
                padding: 15px;
                background: #f9f9f9;
                border-radius: 4px;
            }
            .ck-seo-field label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
                color: #23282d;
                font-size: 14px;
            }
            .ck-seo-field input[type="text"],
            .ck-seo-field textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
            }
            .ck-seo-field textarea {
                min-height: 100px;
                resize: vertical;
            }
            .ck-seo-help {
                display: block;
                margin-top: 5px;
                color: #666;
                font-size: 12px;
                font-style: italic;
            }
            .ck-seo-counter {
                float: right;
                color: #999;
                font-size: 12px;
            }
            .ck-seo-counter.good {
                color: #46b450;
            }
            .ck-seo-counter.warning {
                color: #f56e28;
            }
            .ck-seo-counter.bad {
                color: #dc3232;
            }
            .ck-seo-preview {
                margin: 20px 0;
                padding: 15px;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .ck-seo-preview h4 {
                margin: 0 0 10px 0;
                color: #23282d;
            }
            .google-preview {
                font-family: Arial, sans-serif;
            }
            .google-preview-title {
                color: #1a0dab;
                font-size: 18px;
                font-weight: 400;
                margin-bottom: 3px;
                cursor: pointer;
            }
            .google-preview-url {
                color: #006621;
                font-size: 14px;
                margin-bottom: 3px;
            }
            .google-preview-description {
                color: #545454;
                font-size: 13px;
                line-height: 1.4;
            }
            .ck-seo-tabs {
                margin-bottom: 15px;
                border-bottom: 1px solid #ddd;
            }
            .ck-seo-tab {
                display: inline-block;
                padding: 10px 20px;
                cursor: pointer;
                border: 1px solid transparent;
                border-bottom: none;
                background: #f9f9f9;
                margin-right: 5px;
                border-radius: 4px 4px 0 0;
            }
            .ck-seo-tab.active {
                background: white;
                border-color: #ddd;
                border-bottom: 1px solid white;
                margin-bottom: -1px;
            }
            .ck-seo-tab-content {
                display: none;
            }
            .ck-seo-tab-content.active {
                display: block;
            }
        </style>

        <div class="ck-seo-tabs">
            <div class="ck-seo-tab active" onclick="ckSwitchTab(event, 'basic')">📝 Basic SEO</div>
            <div class="ck-seo-tab" onclick="ckSwitchTab(event, 'social')">📱 Social Media</div>
            <div class="ck-seo-tab" onclick="ckSwitchTab(event, 'advanced')">⚙️ Advanced</div>
        </div>

        <!-- Basic SEO Tab -->
        <div id="basic" class="ck-seo-tab-content active">
            <div class="ck-seo-preview">
                <h4>📊 Google Search Preview</h4>
                <div class="google-preview">
                    <div class="google-preview-title" id="preview-title">
                        <?php echo $seo_title ?: get_the_title(); ?>
                    </div>
                    <div class="google-preview-url">
                        <?php echo get_permalink($post->ID); ?>
                    </div>
                    <div class="google-preview-description" id="preview-description">
                        <?php echo $seo_description ?: get_the_excerpt(); ?>
                    </div>
                </div>
            </div>

            <div class="ck-seo-field">
                <label for="seo_title">
                    SEO Title (Meta Title)
                    <span class="ck-seo-counter" id="title-counter">0/60</span>
                </label>
                <input type="text"
                       id="seo_title"
                       name="seo_title"
                       value="<?php echo esc_attr($seo_title); ?>"
                       maxlength="60"
                       placeholder="<?php echo esc_attr(get_the_title()); ?>">
                <span class="ck-seo-help">
                    📌 Optimal: 50-60 characters. This is what appears in Google search results as the clickable headline.
                </span>
            </div>

            <div class="ck-seo-field">
                <label for="seo_description">
                    SEO Meta Description
                    <span class="ck-seo-counter" id="desc-counter">0/160</span>
                </label>
                <textarea id="seo_description"
                          name="seo_description"
                          maxlength="160"
                          placeholder="Write a compelling description that will appear in Google search results..."><?php echo esc_textarea($seo_description); ?></textarea>
                <span class="ck-seo-help">
                    📌 Optimal: 150-160 characters. This appears below your title in search results. Make it compelling!
                </span>
            </div>

            <div class="ck-seo-field">
                <label for="focus_keyword">
                    Focus Keyword
                </label>
                <input type="text"
                       id="focus_keyword"
                       name="focus_keyword"
                       value="<?php echo esc_attr($focus_keyword); ?>"
                       placeholder="e.g., IIT Delhi, Best Engineering Colleges">
                <span class="ck-seo-help">
                    🎯 The main keyword you want this page to rank for on Google.
                </span>
            </div>

            <div class="ck-seo-field">
                <label for="seo_keywords">
                    SEO Keywords (Meta Keywords)
                </label>
                <input type="text"
                       id="seo_keywords"
                       name="seo_keywords"
                       value="<?php echo esc_attr($seo_keywords); ?>"
                       placeholder="engineering college, admissions, Delhi, IIT">
                <span class="ck-seo-help">
                    🔑 Comma-separated keywords. Example: engineering college, admissions, placement, fees
                </span>
            </div>
        </div>

        <!-- Social Media Tab -->
        <div id="social" class="ck-seo-tab-content">
            <div class="ck-seo-field">
                <label for="og_image">
                    Social Media Share Image (Open Graph Image)
                </label>
                <input type="text"
                       id="og_image"
                       name="og_image"
                       value="<?php echo esc_attr($og_image); ?>"
                       placeholder="https://example.com/image.jpg">
                <button type="button" class="button" onclick="ckUploadImage()">Upload Image</button>
                <span class="ck-seo-help">
                    🖼️ Recommended: 1200x630px. This image appears when your page is shared on Facebook, LinkedIn, WhatsApp, etc.
                </span>
                <?php if ($og_image): ?>
                    <div style="margin-top: 10px;">
                        <img src="<?php echo esc_url($og_image); ?>" style="max-width: 300px; height: auto;">
                    </div>
                <?php endif; ?>
            </div>

            <p style="padding: 15px; background: #e7f5fe; border-left: 4px solid #2196f3; margin: 20px 0;">
                <strong>💡 Pro Tip:</strong> The SEO title and description above will be used for social media shares too.
                If not set, we'll use the college name and excerpt automatically.
            </p>
        </div>

        <!-- Advanced Tab -->
        <div id="advanced" class="ck-seo-tab-content">
            <div class="ck-seo-field">
                <label for="canonical_url">
                    Canonical URL
                </label>
                <input type="text"
                       id="canonical_url"
                       name="canonical_url"
                       value="<?php echo esc_attr($canonical_url); ?>"
                       placeholder="<?php echo get_permalink($post->ID); ?>">
                <span class="ck-seo-help">
                    🔗 Leave empty to use default. Set this if you have duplicate content and want to specify the preferred URL.
                </span>
            </div>

            <div class="ck-seo-field">
                <label>Robots Meta Tag</label>
                <div style="margin-top: 10px;">
                    <label style="display: inline-block; margin-right: 20px;">
                        <select name="robots_index">
                            <option value="index" <?php selected($robots_index, 'index'); ?>>Index (Allow in Google)</option>
                            <option value="noindex" <?php selected($robots_index, 'noindex'); ?>>NoIndex (Hide from Google)</option>
                        </select>
                    </label>
                    <label style="display: inline-block;">
                        <select name="robots_follow">
                            <option value="follow" <?php selected($robots_follow, 'follow'); ?>>Follow Links</option>
                            <option value="nofollow" <?php selected($robots_follow, 'nofollow'); ?>>NoFollow Links</option>
                        </select>
                    </label>
                </div>
                <span class="ck-seo-help">
                    🤖 Control how search engines crawl this page. Default: Index, Follow (recommended for most pages)
                </span>
            </div>

            <div class="ck-seo-field" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                <strong>⚠️ SEO Best Practices:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Use focus keyword in title, description, and page content</li>
                    <li>Keep title under 60 characters</li>
                    <li>Keep description between 150-160 characters</li>
                    <li>Make description compelling - it's your ad copy in Google!</li>
                    <li>Use unique titles and descriptions for each college</li>
                    <li>Include location (city, state) in your keywords</li>
                </ul>
            </div>
        </div>

        <script>
        function ckSwitchTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("ck-seo-tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("ck-seo-tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        // Character counters and preview
        jQuery(document).ready(function($) {
            function updateCounter(input, counter, optimal) {
                var length = $(input).val().length;
                var $counter = $(counter);
                $counter.text(length + '/' + optimal);

                $counter.removeClass('good warning bad');
                if (length === 0) {
                    $counter.addClass('bad');
                } else if (length < optimal - 10) {
                    $counter.addClass('warning');
                } else if (length <= optimal) {
                    $counter.addClass('good');
                } else {
                    $counter.addClass('bad');
                }
            }

            function updatePreview() {
                var title = $('#seo_title').val() || '<?php echo esc_js(get_the_title()); ?>';
                var description = $('#seo_description').val() || '<?php echo esc_js(get_the_excerpt()); ?>';

                $('#preview-title').text(title);
                $('#preview-description').text(description);
            }

            $('#seo_title').on('input', function() {
                updateCounter(this, '#title-counter', 60);
                updatePreview();
            }).trigger('input');

            $('#seo_description').on('input', function() {
                updateCounter(this, '#desc-counter', 160);
                updatePreview();
            }).trigger('input');
        });

        function ckUploadImage() {
            var frame = wp.media({
                title: 'Select Social Share Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                jQuery('#og_image').val(attachment.url);
            });

            frame.open();
        }
        </script>
        <?php
    }

    /**
     * Save SEO meta data
     */
    public static function save_seo_meta($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['ck_college_seo_nonce_field']) ||
            !wp_verify_nonce($_POST['ck_college_seo_nonce_field'], 'ck_college_seo_nonce')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save SEO fields
        $fields = array(
            'seo_title',
            'seo_description',
            'seo_keywords',
            'focus_keyword',
            'canonical_url',
            'og_image',
            'robots_index',
            'robots_follow'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Output SEO meta tags in head section
     */
    public static function output_seo_meta_tags() {
        if (!is_singular('ck_college')) {
            return;
        }

        global $post;

        $seo_title = get_post_meta($post->ID, 'seo_title', true);
        $seo_description = get_post_meta($post->ID, 'seo_description', true);
        $seo_keywords = get_post_meta($post->ID, 'seo_keywords', true);
        $canonical_url = get_post_meta($post->ID, 'canonical_url', true);
        $robots_index = get_post_meta($post->ID, 'robots_index', true) ?: 'index';
        $robots_follow = get_post_meta($post->ID, 'robots_follow', true) ?: 'follow';

        // Use custom or default values
        $title = $seo_title ?: get_the_title();
        $description = $seo_description ?: get_the_excerpt();
        $canonical = $canonical_url ?: get_permalink();

        echo "\n<!-- CollegeKampus SEO Meta Tags -->\n";

        if ($title) {
            echo '<meta name="title" content="' . esc_attr($title) . '">' . "\n";
        }

        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }

        if ($seo_keywords) {
            echo '<meta name="keywords" content="' . esc_attr($seo_keywords) . '">' . "\n";
        }

        echo '<meta name="robots" content="' . esc_attr($robots_index . ', ' . $robots_follow) . '">' . "\n";
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
        echo "<!-- End CollegeKampus SEO -->\n\n";
    }

    /**
     * Output Open Graph tags for social media
     */
    public static function output_open_graph_tags() {
        if (!is_singular('ck_college')) {
            return;
        }

        global $post;

        $seo_title = get_post_meta($post->ID, 'seo_title', true);
        $seo_description = get_post_meta($post->ID, 'seo_description', true);
        $og_image = get_post_meta($post->ID, 'og_image', true);

        $title = $seo_title ?: get_the_title();
        $description = $seo_description ?: get_the_excerpt();
        $image = $og_image ?: (has_post_thumbnail() ? get_the_post_thumbnail_url($post->ID, 'large') : '');

        echo "\n<!-- Open Graph Meta Tags -->\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";

        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
        }

        echo "<!-- End Open Graph -->\n\n";
    }

    /**
     * Output Twitter Card tags
     */
    public static function output_twitter_card_tags() {
        if (!is_singular('ck_college')) {
            return;
        }

        global $post;

        $seo_title = get_post_meta($post->ID, 'seo_title', true);
        $seo_description = get_post_meta($post->ID, 'seo_description', true);
        $og_image = get_post_meta($post->ID, 'og_image', true);

        $title = $seo_title ?: get_the_title();
        $description = $seo_description ?: get_the_excerpt();
        $image = $og_image ?: (has_post_thumbnail() ? get_the_post_thumbnail_url($post->ID, 'large') : '');

        echo "\n<!-- Twitter Card Meta Tags -->\n";
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
        }

        echo "<!-- End Twitter Card -->\n\n";
    }
}

// Initialize SEO functionality
CK_OneForm_SEO::init();
