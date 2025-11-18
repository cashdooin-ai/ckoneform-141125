<?php
/**
 * AI-Powered Scholarship Recommendation Engine
 * Provides personalized scholarship recommendations for India and abroad
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Scholarship_Recommender {

    /**
     * Get scholarship recommendations based on student profile
     */
    public static function get_recommendations($criteria = array()) {
        $scholarships = CK_OneForm_Data_Seeder::get_scholarships();

        if (empty($scholarships)) {
            return array('india' => array(), 'abroad' => array());
        }

        // Separate India and abroad scholarships
        $india_scholarships = array();
        $abroad_scholarships = array();

        foreach ($scholarships as $scholarship) {
            $location = isset($scholarship['location']) ? $scholarship['location'] : 'india';

            if ($location === 'india') {
                $india_scholarships[] = $scholarship;
            } else {
                $abroad_scholarships[] = $scholarship;
            }
        }

        // Apply AI-powered filtering if criteria provided
        if (!empty($criteria)) {
            $india_scholarships = self::filter_scholarships($india_scholarships, $criteria);
            $abroad_scholarships = self::filter_scholarships($abroad_scholarships, $criteria);
        }

        // Score and sort scholarships
        $india_scholarships = self::score_and_sort($india_scholarships, $criteria);
        $abroad_scholarships = self::score_and_sort($abroad_scholarships, $criteria);

        return array(
            'india' => array_slice($india_scholarships, 0, 6),
            'abroad' => array_slice($abroad_scholarships, 0, 6),
        );
    }

    /**
     * Filter scholarships based on criteria
     */
    private static function filter_scholarships($scholarships, $criteria) {
        $filtered = array();

        foreach ($scholarships as $scholarship) {
            $matches = true;

            // Filter by category
            if (!empty($criteria['category']) && $criteria['category'] !== 'all') {
                if (isset($scholarship['category']) && $scholarship['category'] !== $criteria['category']) {
                    $matches = false;
                }
            }

            // Filter by course/stream
            if (!empty($criteria['course']) && $criteria['course'] !== 'all') {
                if (isset($scholarship['courses'])) {
                    $courses = is_array($scholarship['courses']) ? $scholarship['courses'] : array($scholarship['courses']);
                    if (!in_array('All', $courses) && !in_array($criteria['course'], $courses)) {
                        $matches = false;
                    }
                }
            }

            // Filter by education level
            if (!empty($criteria['level']) && $criteria['level'] !== 'all') {
                if (isset($scholarship['level']) && $scholarship['level'] !== $criteria['level']) {
                    $matches = false;
                }
            }

            if ($matches) {
                $filtered[] = $scholarship;
            }
        }

        return $filtered;
    }

    /**
     * Score and sort scholarships based on relevance
     */
    private static function score_and_sort($scholarships, $criteria) {
        foreach ($scholarships as &$scholarship) {
            $score = 0;

            // Higher score for matching category
            if (!empty($criteria['category']) && isset($scholarship['category'])) {
                if ($scholarship['category'] === $criteria['category']) {
                    $score += 10;
                }
            }

            // Higher score for matching course
            if (!empty($criteria['course']) && isset($scholarship['courses'])) {
                $courses = is_array($scholarship['courses']) ? $scholarship['courses'] : array($scholarship['courses']);
                if (in_array($criteria['course'], $courses)) {
                    $score += 8;
                }
            }

            // Higher score for matching level
            if (!empty($criteria['level']) && isset($scholarship['level'])) {
                if ($scholarship['level'] === $criteria['level']) {
                    $score += 5;
                }
            }

            // Bonus for higher amounts
            if (isset($scholarship['amount'])) {
                $amount_str = $scholarship['amount'];
                if (preg_match('/(\d+)/', $amount_str, $matches)) {
                    $amount = intval($matches[1]);
                    if ($amount > 50000) {
                        $score += 3;
                    } elseif ($amount > 20000) {
                        $score += 2;
                    }
                }
            }

            // Bonus for upcoming deadlines (more urgent)
            if (isset($scholarship['deadline'])) {
                $deadline = strtotime($scholarship['deadline']);
                $today = time();
                $days_remaining = ($deadline - $today) / (60 * 60 * 24);

                if ($days_remaining > 0 && $days_remaining <= 30) {
                    $score += 5; // Very urgent
                } elseif ($days_remaining <= 60) {
                    $score += 3;
                } elseif ($days_remaining <= 90) {
                    $score += 1;
                }
            }

            $scholarship['_relevance_score'] = $score;
        }

        // Sort by score descending
        usort($scholarships, function($a, $b) {
            return ($b['_relevance_score'] ?? 0) - ($a['_relevance_score'] ?? 0);
        });

        return $scholarships;
    }

    /**
     * Render recommendation widget
     */
    public static function render_widget($criteria = array()) {
        $recommendations = self::get_recommendations($criteria);
        $india_scholarships = $recommendations['india'];
        $abroad_scholarships = $recommendations['abroad'];

        ob_start();
        ?>
        <section class="ai-scholarship-recommendations">
            <div class="recommendation-header">
                <h2>🤖 AI-Powered Scholarship Recommendations</h2>
                <p class="recommendation-subtitle">Smart recommendations tailored to your profile</p>
            </div>

            <!-- Recommendation Filters -->
            <div class="recommendation-filters">
                <form id="scholarship-filter-form" method="get">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label for="rec-category">Category</label>
                            <select name="category" id="rec-category" class="filter-select">
                                <option value="all">All Categories</option>
                                <option value="merit" <?php selected(isset($criteria['category']) ? $criteria['category'] : '', 'merit'); ?>>Merit-Based</option>
                                <option value="need-based" <?php selected(isset($criteria['category']) ? $criteria['category'] : '', 'need-based'); ?>>Need-Based</option>
                                <option value="corporate" <?php selected(isset($criteria['category']) ? $criteria['category'] : '', 'corporate'); ?>>Corporate</option>
                                <option value="special" <?php selected(isset($criteria['category']) ? $criteria['category'] : '', 'special'); ?>>Special Category</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <label for="rec-course">Stream/Course</label>
                            <select name="course" id="rec-course" class="filter-select">
                                <option value="all">All Streams</option>
                                <option value="Engineering" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Engineering'); ?>>Engineering</option>
                                <option value="Medical" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Medical'); ?>>Medical</option>
                                <option value="Science" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Science'); ?>>Science</option>
                                <option value="Commerce" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Commerce'); ?>>Commerce</option>
                                <option value="Arts" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Arts'); ?>>Arts</option>
                                <option value="Law" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Law'); ?>>Law</option>
                                <option value="Management" <?php selected(isset($criteria['course']) ? $criteria['course'] : '', 'Management'); ?>>Management</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <label for="rec-level">Education Level</label>
                            <select name="level" id="rec-level" class="filter-select">
                                <option value="all">All Levels</option>
                                <option value="undergraduate" <?php selected(isset($criteria['level']) ? $criteria['level'] : '', 'undergraduate'); ?>>Undergraduate</option>
                                <option value="postgraduate" <?php selected(isset($criteria['level']) ? $criteria['level'] : '', 'postgraduate'); ?>>Postgraduate</option>
                                <option value="diploma" <?php selected(isset($criteria['level']) ? $criteria['level'] : '', 'diploma'); ?>>Diploma</option>
                                <option value="research" <?php selected(isset($criteria['level']) ? $criteria['level'] : '', 'research'); ?>>Research/PhD</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <button type="submit" class="btn-filter">
                                🔍 Find Scholarships
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- India Scholarships -->
            <div class="recommendation-section">
                <h3 class="section-heading">
                    <span class="flag-icon">🇮🇳</span>
                    Recommended Scholarships in India
                    <span class="count-badge"><?php echo count($india_scholarships); ?> matches</span>
                </h3>

                <?php if (!empty($india_scholarships)): ?>
                    <div class="recommendations-grid">
                        <?php foreach ($india_scholarships as $scholarship): ?>
                            <div class="recommendation-card">
                                <div class="card-ribbon">
                                    <span class="relevance-score">
                                        ⭐ <?php echo isset($scholarship['_relevance_score']) ? $scholarship['_relevance_score'] : 0; ?>% Match
                                    </span>
                                </div>
                                <div class="scholarship-icon">🎓</div>
                                <h4><?php echo esc_html($scholarship['name']); ?></h4>
                                <div class="scholarship-provider">
                                    <?php echo esc_html($scholarship['provider']); ?>
                                </div>
                                <div class="scholarship-amount">
                                    💰 <?php echo esc_html($scholarship['amount']); ?>
                                </div>
                                <div class="scholarship-eligibility">
                                    ✅ <?php echo esc_html($scholarship['eligibility']); ?>
                                </div>
                                <div class="scholarship-deadline">
                                    ⏰ Deadline: <?php echo date('d M Y', strtotime($scholarship['deadline'])); ?>
                                </div>
                                <div class="scholarship-tags">
                                    <span class="tag category-<?php echo esc_attr($scholarship['category']); ?>">
                                        <?php echo ucfirst($scholarship['category']); ?>
                                    </span>
                                    <?php if (isset($scholarship['courses']) && is_array($scholarship['courses'])): ?>
                                        <?php foreach (array_slice($scholarship['courses'], 0, 2) as $course): ?>
                                            <span class="tag course-tag"><?php echo esc_html($course); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo home_url('/student-login/?redirect=scholarship&id=' . urlencode($scholarship['name'])); ?>" class="btn-apply-rec">
                                    Apply Now →
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-recommendations">
                        <p>No India scholarships match your current criteria. Try adjusting your filters.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Abroad Scholarships -->
            <div class="recommendation-section">
                <h3 class="section-heading">
                    <span class="flag-icon">🌍</span>
                    Recommended Scholarships Abroad
                    <span class="count-badge"><?php echo count($abroad_scholarships); ?> matches</span>
                </h3>

                <?php if (!empty($abroad_scholarships)): ?>
                    <div class="recommendations-grid">
                        <?php foreach ($abroad_scholarships as $scholarship): ?>
                            <div class="recommendation-card abroad">
                                <div class="card-ribbon">
                                    <span class="relevance-score">
                                        ⭐ <?php echo isset($scholarship['_relevance_score']) ? $scholarship['_relevance_score'] : 0; ?>% Match
                                    </span>
                                </div>
                                <div class="scholarship-icon">🌟</div>
                                <h4><?php echo esc_html($scholarship['name']); ?></h4>
                                <div class="scholarship-provider">
                                    <?php echo esc_html($scholarship['provider']); ?>
                                    <?php if (isset($scholarship['country'])): ?>
                                        <span class="country-flag"><?php echo esc_html($scholarship['country']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="scholarship-amount">
                                    💰 <?php echo esc_html($scholarship['amount']); ?>
                                </div>
                                <div class="scholarship-eligibility">
                                    ✅ <?php echo esc_html($scholarship['eligibility']); ?>
                                </div>
                                <div class="scholarship-deadline">
                                    ⏰ Deadline: <?php echo date('d M Y', strtotime($scholarship['deadline'])); ?>
                                </div>
                                <div class="scholarship-tags">
                                    <span class="tag category-<?php echo esc_attr($scholarship['category']); ?>">
                                        <?php echo ucfirst($scholarship['category']); ?>
                                    </span>
                                    <?php if (isset($scholarship['courses']) && is_array($scholarship['courses'])): ?>
                                        <?php foreach (array_slice($scholarship['courses'], 0, 2) as $course): ?>
                                            <span class="tag course-tag"><?php echo esc_html($course); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo home_url('/student-login/?redirect=scholarship&id=' . urlencode($scholarship['name'])); ?>" class="btn-apply-rec">
                                    Apply Now →
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-recommendations">
                        <p>No international scholarships match your current criteria. Try adjusting your filters.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- AI Insights -->
            <div class="ai-insights">
                <h3>💡 AI Insights</h3>
                <div class="insights-grid">
                    <div class="insight-card">
                        <div class="insight-icon">📊</div>
                        <div class="insight-content">
                            <strong>Total Matches</strong>
                            <p><?php echo count($india_scholarships) + count($abroad_scholarships); ?> scholarships match your profile</p>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon">⏰</div>
                        <div class="insight-content">
                            <strong>Urgent Deadlines</strong>
                            <p><?php echo self::count_urgent_deadlines(array_merge($india_scholarships, $abroad_scholarships)); ?> scholarships closing within 30 days</p>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon">💰</div>
                        <div class="insight-content">
                            <strong>Highest Value</strong>
                            <p><?php echo self::get_highest_value(array_merge($india_scholarships, $abroad_scholarships)); ?></p>
                        </div>
                    </div>
                    <div class="insight-card">
                        <div class="insight-icon">🎯</div>
                        <div class="insight-content">
                            <strong>Best Match</strong>
                            <p><?php echo self::get_best_match_category(array_merge($india_scholarships, $abroad_scholarships)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
        .ai-scholarship-recommendations {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 50px 30px;
            border-radius: 15px;
            margin: 40px 0;
        }

        .recommendation-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .recommendation-header h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .recommendation-subtitle {
            font-size: 1.2rem;
            color: #7f8c8d;
        }

        .recommendation-filters {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-item label {
            display: block;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            transition: all 0.3s;
        }

        .filter-select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-filter {
            width: 100%;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .recommendation-section {
            margin-bottom: 50px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        .flag-icon {
            font-size: 2rem;
        }

        .count-badge {
            margin-left: auto;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .recommendations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .recommendation-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .recommendation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #667eea;
        }

        .recommendation-card.abroad {
            border-top: 4px solid #3498db;
        }

        .card-ribbon {
            position: absolute;
            top: -10px;
            right: 15px;
        }

        .relevance-score {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .scholarship-icon {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 15px;
        }

        .recommendation-card h4 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin: 0 0 10px 0;
            line-height: 1.3;
        }

        .scholarship-provider {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .country-flag {
            background: #ecf0f1;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .scholarship-amount {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 700;
            margin: 12px 0;
            text-align: center;
            font-size: 1.1rem;
        }

        .scholarship-eligibility,
        .scholarship-deadline {
            font-size: 0.9rem;
            color: #555;
            margin: 8px 0;
            line-height: 1.5;
        }

        .scholarship-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 15px 0;
        }

        .tag {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .tag.category-merit {
            background: #ffd700;
            color: #333;
        }

        .tag.category-need-based {
            background: #28a745;
            color: white;
        }

        .tag.category-corporate {
            background: #17a2b8;
            color: white;
        }

        .tag.category-special {
            background: #fd7e14;
            color: white;
        }

        .tag.course-tag {
            background: #e9ecef;
            color: #495057;
        }

        .btn-apply-rec {
            display: block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-apply-rec:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .no-recommendations {
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .ai-insights {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .ai-insights h3 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .insight-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .insight-icon {
            font-size: 2.5rem;
        }

        .insight-content strong {
            display: block;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .insight-content p {
            margin: 0;
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .recommendation-header h2 {
                font-size: 1.8rem;
            }

            .recommendations-grid {
                grid-template-columns: 1fr;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .section-heading {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .count-badge {
                margin-left: 0;
            }

            .insights-grid {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
        return ob_get_clean();
    }

    /**
     * Helper: Count urgent deadlines
     */
    private static function count_urgent_deadlines($scholarships) {
        $count = 0;
        $today = time();

        foreach ($scholarships as $scholarship) {
            if (isset($scholarship['deadline'])) {
                $deadline = strtotime($scholarship['deadline']);
                $days = ($deadline - $today) / (60 * 60 * 24);
                if ($days > 0 && $days <= 30) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Helper: Get highest value scholarship
     */
    private static function get_highest_value($scholarships) {
        $highest = 0;
        $highest_name = 'N/A';

        foreach ($scholarships as $scholarship) {
            if (isset($scholarship['amount'])) {
                if (preg_match('/(\d+)/', $scholarship['amount'], $matches)) {
                    $amount = intval($matches[1]);
                    if ($amount > $highest) {
                        $highest = $amount;
                        $highest_name = $scholarship['name'];
                    }
                }
            }
        }

        return $highest > 0 ? $highest_name : 'No scholarships available';
    }

    /**
     * Helper: Get best match category
     */
    private static function get_best_match_category($scholarships) {
        if (empty($scholarships)) {
            return 'No matches yet';
        }

        // Get scholarship with highest score
        $best = $scholarships[0];
        return isset($best['category']) ? ucfirst($best['category']) . ' scholarships' : 'General scholarships';
    }
}
