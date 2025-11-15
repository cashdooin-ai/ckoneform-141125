<?php
/**
 * Dynamic Service Page Template
 * Displays content for all mega menu service pages
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get service slug from URL or shortcode attribute
$service_slug = isset($atts['slug']) ? $atts['slug'] : '';
if (empty($service_slug) && isset($_GET['service'])) {
    $service_slug = sanitize_text_field($_GET['service']);
}

// Load service content
$services_data = include CK_ONEFORM_PLUGIN_DIR . 'data/service-pages-content.php';

// Find service by slug
$service = null;
foreach ($services_data as $category_services) {
    foreach ($category_services as $s) {
        if ($s['slug'] === $service_slug) {
            $service = $s;
            break 2;
        }
    }
}

// If no service found, show error
if (!$service) {
    echo '<div class="ck-service-page error">';
    echo '<h1>Page Not Found</h1>';
    echo '<p>The requested service page could not be found.</p>';
    echo '<p><a href="' . home_url('/') . '" class="btn-primary">← Go to Homepage</a></p>';
    echo '</div>';
    return;
}

// Check if student is logged in
$is_logged_in = CK_OneForm_Student_Auth::is_student_logged_in();
$student = $is_logged_in ? CK_OneForm_Student_Auth::get_current_student() : null;
?>

<div class="ck-service-page">
    <!-- Hero Section -->
    <div class="service-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($service['gradient']); ?>);">
        <div class="service-hero-content">
            <div class="service-icon"><?php echo $service['icon']; ?></div>
            <h1 class="service-title"><?php echo esc_html($service['title']); ?></h1>
            <p class="service-subtitle"><?php echo esc_html($service['subtitle']); ?></p>

            <?php if (!empty($service['cta'])): ?>
                <div class="service-cta">
                    <a href="<?php echo esc_url($service['cta']['url']); ?>" class="btn-primary btn-large">
                        <?php echo esc_html($service['cta']['text']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="service-content">
        <!-- Description -->
        <section class="service-section">
            <div class="service-description">
                <?php echo wp_kses_post($service['description']); ?>
            </div>
        </section>

        <!-- Features -->
        <?php if (!empty($service['features'])): ?>
        <section class="service-section">
            <h2>✨ Key Features</h2>
            <div class="features-grid">
                <?php foreach ($service['features'] as $feature): ?>
                    <div class="feature-card">
                        <div class="feature-icon"><?php echo $feature['icon']; ?></div>
                        <h3><?php echo esc_html($feature['title']); ?></h3>
                        <p><?php echo esc_html($feature['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Benefits -->
        <?php if (!empty($service['benefits'])): ?>
        <section class="service-section benefits-section">
            <h2>🎯 Why Choose This Service?</h2>
            <ul class="benefits-list">
                <?php foreach ($service['benefits'] as $benefit): ?>
                    <li>
                        <span class="benefit-icon">✅</span>
                        <span><?php echo esc_html($benefit); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php endif; ?>

        <!-- How It Works -->
        <?php if (!empty($service['steps'])): ?>
        <section class="service-section">
            <h2>📋 How It Works</h2>
            <div class="steps-container">
                <?php foreach ($service['steps'] as $index => $step): ?>
                    <div class="step-card">
                        <div class="step-number"><?php echo ($index + 1); ?></div>
                        <h3><?php echo esc_html($step['title']); ?></h3>
                        <p><?php echo esc_html($step['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Related Services -->
        <?php if (!empty($service['related'])): ?>
        <section class="service-section">
            <h2>🔗 Related Services</h2>
            <div class="related-services-grid">
                <?php foreach ($service['related'] as $related_slug): ?>
                    <?php
                    // Find related service
                    $related_service = null;
                    foreach ($services_data as $cat_services) {
                        foreach ($cat_services as $s) {
                            if ($s['slug'] === $related_slug) {
                                $related_service = $s;
                                break 2;
                            }
                        }
                    }
                    if ($related_service):
                    ?>
                        <a href="<?php echo esc_url(home_url($related_service['url'])); ?>" class="related-service-card">
                            <div class="related-icon"><?php echo $related_service['icon']; ?></div>
                            <h4><?php echo esc_html($related_service['title']); ?></h4>
                            <p><?php echo esc_html($related_service['desc']); ?></p>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="service-section cta-section">
            <div class="final-cta">
                <h2>Ready to Get Started?</h2>
                <p><?php echo esc_html($service['subtitle']); ?></p>
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo home_url('/student-dashboard/'); ?>" class="btn-primary btn-large">
                        Go to Dashboard →
                    </a>
                <?php else: ?>
                    <a href="<?php echo home_url('/student-login/'); ?>" class="btn-primary btn-large">
                        Login / Register →
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<style>
.ck-service-page {
    width: 100%;
    min-height: 100vh;
}

.service-hero {
    padding: 80px 20px;
    text-align: center;
    color: white;
}

.service-hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.service-icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.service-title {
    font-size: 3rem;
    font-weight: 800;
    margin: 0 0 20px 0;
    color: white;
}

.service-subtitle {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
}

.service-cta {
    margin-top: 30px;
}

.btn-primary {
    display: inline-block;
    padding: 14px 32px;
    background: white;
    color: #667eea;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

.btn-large {
    padding: 16px 40px;
    font-size: 1.2rem;
}

.service-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}

.service-section {
    margin-bottom: 60px;
}

.service-section h2 {
    font-size: 2rem;
    margin-bottom: 30px;
    color: #333;
}

.service-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.feature-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.feature-card h3 {
    font-size: 1.3rem;
    margin: 0 0 10px 0;
    color: #333;
}

.feature-card p {
    color: #666;
    margin: 0;
    line-height: 1.6;
}

.benefits-section {
    background: #f8f9fa;
    padding: 40px;
    border-radius: 12px;
}

.benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.benefits-list li {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    font-size: 1.1rem;
    color: #333;
}

.benefit-icon {
    font-size: 1.5rem;
    margin-right: 15px;
    flex-shrink: 0;
}

.steps-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.step-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: relative;
}

.step-number {
    position: absolute;
    top: -15px;
    left: 30px;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.step-card h3 {
    margin: 20px 0 10px 0;
    color: #333;
}

.step-card p {
    color: #666;
    margin: 0;
    line-height: 1.6;
}

.related-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.related-service-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-decoration: none;
    transition: all 0.3s;
    display: block;
}

.related-service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.related-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.related-service-card h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.2rem;
}

.related-service-card p {
    margin: 0;
    color: #666;
    font-size: 0.95rem;
}

.cta-section {
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 60px 40px;
    border-radius: 12px;
    color: white;
}

.final-cta h2 {
    color: white;
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.final-cta p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.95;
}

.final-cta .btn-primary {
    background: white;
    color: #667eea;
}

@media (max-width: 768px) {
    .service-title {
        font-size: 2rem;
    }

    .service-subtitle {
        font-size: 1.1rem;
    }

    .features-grid,
    .steps-container,
    .related-services-grid {
        grid-template-columns: 1fr;
    }

    .service-section h2 {
        font-size: 1.5rem;
    }
}
</style>
