<?php
/**
 * AI Scholarship Recommender
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Scholarship_Recommender {

    /**
     * Initialize the recommender
     */
    public static function init() {
        add_shortcode('ck_scholarship_recommender', array(__CLASS__, 'render_recommender'));
        add_action('wp_ajax_get_scholarship_recommendations', array(__CLASS__, 'get_recommendations'));
        add_action('wp_ajax_nopriv_get_scholarship_recommendations', array(__CLASS__, 'get_recommendations'));
    }

    /**
     * Render the scholarship recommender widget
     */
    public static function render_recommender($atts) {
        $atts = shortcode_atts(array(
            'title' => 'AI Scholarship Recommender',
            'subtitle' => 'Get personalized scholarship recommendations based on your profile',
        ), $atts);

        ob_start();
        ?>
        <div class="ck-scholarship-recommender">
            <div class="recommender-header">
                <h2><?php echo esc_html($atts['title']); ?></h2>
                <p class="subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
            </div>

            <form id="scholarship-recommender-form" class="recommender-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="student-category">Category</label>
                        <select id="student-category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="general">General</option>
                            <option value="obc">OBC</option>
                            <option value="sc">SC</option>
                            <option value="st">ST</option>
                            <option value="ews">EWS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="student-gender">Gender</label>
                        <select id="student-gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="family-income">Annual Family Income (₹)</label>
                        <select id="family-income" name="income" required>
                            <option value="">Select Income Range</option>
                            <option value="below-1">Below 1 Lakh</option>
                            <option value="1-3">1-3 Lakhs</option>
                            <option value="3-5">3-5 Lakhs</option>
                            <option value="5-8">5-8 Lakhs</option>
                            <option value="above-8">Above 8 Lakhs</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="academic-score">Academic Score (%)</label>
                        <input type="number" id="academic-score" name="score" min="0" max="100" placeholder="Enter your score" required>
                    </div>

                    <div class="form-group">
                        <label for="study-level">Study Level</label>
                        <select id="study-level" name="level" required>
                            <option value="">Select Level</option>
                            <option value="undergraduate">Undergraduate</option>
                            <option value="postgraduate">Postgraduate</option>
                            <option value="phd">PhD</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="course-field">Field of Study</label>
                        <select id="course-field" name="field" required>
                            <option value="">Select Field</option>
                            <option value="engineering">Engineering</option>
                            <option value="medical">Medical</option>
                            <option value="science">Science</option>
                            <option value="arts">Arts</option>
                            <option value="commerce">Commerce</option>
                            <option value="law">Law</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-get-recommendations">
                    <span class="btn-text">🎯 Get Scholarship Recommendations</span>
                    <span class="btn-loading" style="display: none;">⏳ Analyzing...</span>
                </button>
            </form>

            <div id="scholarship-results" class="scholarship-results" style="display: none;">
                <h3>📚 Recommended Scholarships for You</h3>
                <div id="results-container"></div>
            </div>
        </div>

        <style>
        .ck-scholarship-recommender {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: 40px auto;
        }

        .recommender-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .recommender-header h2 {
            font-size: 2rem;
            color: #1a1a1a;
            margin: 0 0 10px 0;
        }

        .recommender-header .subtitle {
            font-size: 1.1rem;
            color: #666;
        }

        .recommender-form {
            margin-bottom: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-get-recommendations {
            width: 100%;
            padding: 16px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-get-recommendations:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-get-recommendations:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .scholarship-results {
            margin-top: 40px;
            padding-top: 40px;
            border-top: 2px dashed #e0e0e0;
        }

        .scholarship-results h3 {
            font-size: 1.5rem;
            margin-bottom: 24px;
            color: #1a1a1a;
        }

        .scholarship-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .scholarship-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 12px 0;
        }

        .scholarship-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 12px;
        }

        .scholarship-details {
            display: grid;
            gap: 8px;
            margin-bottom: 12px;
        }

        .scholarship-detail {
            color: #555;
            font-size: 14px;
        }

        .scholarship-match {
            display: inline-block;
            padding: 6px 16px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 12px;
        }

        .scholarship-apply-btn {
            margin-top: 16px;
            padding: 10px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .scholarship-apply-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#scholarship-recommender-form').on('submit', function(e) {
                e.preventDefault();

                const button = $('.btn-get-recommendations');
                const formData = $(this).serialize();

                // Show loading state
                button.prop('disabled', true);
                $('.btn-text').hide();
                $('.btn-loading').show();

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'get_scholarship_recommendations',
                        ...Object.fromEntries(new URLSearchParams(formData))
                    },
                    success: function(response) {
                        if (response.success && response.data.scholarships) {
                            displayScholarships(response.data.scholarships);
                            $('#scholarship-results').show();
                            $('html, body').animate({
                                scrollTop: $('#scholarship-results').offset().top - 100
                            }, 500);
                        } else {
                            alert('No scholarships found matching your criteria.');
                        }

                        // Reset button
                        button.prop('disabled', false);
                        $('.btn-text').show();
                        $('.btn-loading').hide();
                    },
                    error: function() {
                        alert('Error getting recommendations. Please try again.');
                        button.prop('disabled', false);
                        $('.btn-text').show();
                        $('.btn-loading').hide();
                    }
                });
            });

            function displayScholarships(scholarships) {
                let html = '';

                scholarships.forEach(function(scholarship) {
                    html += `
                        <div class="scholarship-card">
                            <h4 class="scholarship-name">${scholarship.name}</h4>
                            <div class="scholarship-amount">₹${scholarship.amount}</div>
                            <div class="scholarship-details">
                                <div class="scholarship-detail">📋 <strong>Eligibility:</strong> ${scholarship.eligibility}</div>
                                <div class="scholarship-detail">📅 <strong>Deadline:</strong> ${scholarship.deadline}</div>
                                <div class="scholarship-detail">🎓 <strong>For:</strong> ${scholarship.for}</div>
                            </div>
                            <span class="scholarship-match">${scholarship.match}% Match</span>
                            <br>
                            <button type="button" class="scholarship-apply-btn" onclick="window.open('${scholarship.link}', '_blank')">
                                Apply Now →
                            </button>
                        </div>
                    `;
                });

                $('#results-container').html(html);
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Get scholarship recommendations via AJAX
     */
    public static function get_recommendations() {
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
        $income = isset($_POST['income']) ? sanitize_text_field($_POST['income']) : '';
        $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
        $level = isset($_POST['level']) ? sanitize_text_field($_POST['level']) : '';
        $field = isset($_POST['field']) ? sanitize_text_field($_POST['field']) : '';

        // Get all available scholarships
        $all_scholarships = self::get_scholarship_database();

        // AI-based filtering and matching
        $matched_scholarships = array();

        foreach ($all_scholarships as $scholarship) {
            $match_score = self::calculate_match_score($scholarship, array(
                'category' => $category,
                'gender' => $gender,
                'income' => $income,
                'score' => $score,
                'level' => $level,
                'field' => $field,
            ));

            if ($match_score >= 50) {
                $scholarship['match'] = $match_score;
                $matched_scholarships[] = $scholarship;
            }
        }

        // Sort by match score
        usort($matched_scholarships, function($a, $b) {
            return $b['match'] - $a['match'];
        });

        // Return top 5 matches
        $matched_scholarships = array_slice($matched_scholarships, 0, 5);

        wp_send_json_success(array('scholarships' => $matched_scholarships));
    }

    /**
     * Calculate match score between student profile and scholarship
     */
    private static function calculate_match_score($scholarship, $profile) {
        $score = 0;
        $total_criteria = 0;

        // Category match
        if (!empty($scholarship['category'])) {
            $total_criteria++;
            if (in_array($profile['category'], $scholarship['category']) || in_array('all', $scholarship['category'])) {
                $score += 20;
            }
        }

        // Gender match
        if (!empty($scholarship['gender'])) {
            $total_criteria++;
            if (in_array($profile['gender'], $scholarship['gender']) || in_array('all', $scholarship['gender'])) {
                $score += 15;
            }
        }

        // Income match
        if (!empty($scholarship['income'])) {
            $total_criteria++;
            if (in_array($profile['income'], $scholarship['income']) || in_array('all', $scholarship['income'])) {
                $score += 20;
            }
        }

        // Academic score match
        if (!empty($scholarship['min_score'])) {
            $total_criteria++;
            if ($profile['score'] >= $scholarship['min_score']) {
                $score += 25;
            }
        }

        // Level match
        if (!empty($scholarship['level'])) {
            $total_criteria++;
            if (in_array($profile['level'], $scholarship['level']) || in_array('all', $scholarship['level'])) {
                $score += 10;
            }
        }

        // Field match
        if (!empty($scholarship['field'])) {
            $total_criteria++;
            if (in_array($profile['field'], $scholarship['field']) || in_array('all', $scholarship['field'])) {
                $score += 10;
            }
        }

        return $score;
    }

    /**
     * Scholarship database
     */
    private static function get_scholarship_database() {
        return array(
            array(
                'name' => 'National Merit Scholarship',
                'amount' => '50,000 per year',
                'eligibility' => 'Merit-based, minimum 75% marks',
                'deadline' => 'June 30, 2025',
                'for' => 'All fields, Undergraduate',
                'category' => array('all'),
                'gender' => array('all'),
                'income' => array('below-1', '1-3', '3-5'),
                'min_score' => 75,
                'level' => array('undergraduate'),
                'field' => array('all'),
                'link' => 'https://scholarships.gov.in'
            ),
            array(
                'name' => 'SC/ST Scholarship Scheme',
                'amount' => '60,000 per year',
                'eligibility' => 'For SC/ST students, family income < 2.5 LPA',
                'deadline' => 'July 15, 2025',
                'for' => 'All courses',
                'category' => array('sc', 'st'),
                'gender' => array('all'),
                'income' => array('below-1', '1-3'),
                'min_score' => 50,
                'level' => array('undergraduate', 'postgraduate'),
                'field' => array('all'),
                'link' => 'https://scholarships.gov.in'
            ),
            array(
                'name' => 'Girls Excellence Scholarship',
                'amount' => '40,000 per year',
                'eligibility' => 'For female students, minimum 70% marks',
                'deadline' => 'August 10, 2025',
                'for' => 'STEM fields',
                'category' => array('all'),
                'gender' => array('female'),
                'income' => array('all'),
                'min_score' => 70,
                'level' => array('undergraduate', 'postgraduate'),
                'field' => array('engineering', 'science', 'medical'),
                'link' => 'https://scholarships.gov.in'
            ),
            array(
                'name' => 'OBC Merit Scholarship',
                'amount' => '45,000 per year',
                'eligibility' => 'For OBC students, family income < 6 LPA',
                'deadline' => 'September 1, 2025',
                'for' => 'All fields',
                'category' => array('obc'),
                'gender' => array('all'),
                'income' => array('below-1', '1-3', '3-5', '5-8'),
                'min_score' => 60,
                'level' => array('undergraduate', 'postgraduate'),
                'field' => array('all'),
                'link' => 'https://scholarships.gov.in'
            ),
            array(
                'name' => 'Engineering Excellence Award',
                'amount' => '1,00,000 per year',
                'eligibility' => 'For engineering students with 85%+ marks',
                'deadline' => 'June 1, 2025',
                'for' => 'Engineering students',
                'category' => array('all'),
                'gender' => array('all'),
                'income' => array('all'),
                'min_score' => 85,
                'level' => array('undergraduate'),
                'field' => array('engineering'),
                'link' => 'https://aicte-india.org'
            ),
            array(
                'name' => 'Medical Students Support Scheme',
                'amount' => '80,000 per year',
                'eligibility' => 'For medical students, family income < 8 LPA',
                'deadline' => 'July 20, 2025',
                'for' => 'Medical and allied courses',
                'category' => array('all'),
                'gender' => array('all'),
                'income' => array('below-1', '1-3', '3-5', '5-8'),
                'min_score' => 70,
                'level' => array('undergraduate', 'postgraduate'),
                'field' => array('medical'),
                'link' => 'https://mci.gov.in'
            ),
            array(
                'name' => 'EWS Scholarship Program',
                'amount' => '55,000 per year',
                'eligibility' => 'For EWS category, family income < 8 LPA',
                'deadline' => 'August 30, 2025',
                'for' => 'All courses',
                'category' => array('ews'),
                'gender' => array('all'),
                'income' => array('below-1', '1-3', '3-5', '5-8'),
                'min_score' => 55,
                'level' => array('undergraduate', 'postgraduate'),
                'field' => array('all'),
                'link' => 'https://scholarships.gov.in'
            ),
            array(
                'name' => 'PhD Research Fellowship',
                'amount' => '31,000 per month',
                'eligibility' => 'For PhD scholars in all fields',
                'deadline' => 'Rolling',
                'for' => 'PhD students',
                'category' => array('all'),
                'gender' => array('all'),
                'income' => array('all'),
                'min_score' => 65,
                'level' => array('phd'),
                'field' => array('all'),
                'link' => 'https://ugc.ac.in'
            ),
        );
    }
}

// Initialize the scholarship recommender
CK_OneForm_Scholarship_Recommender::init();
