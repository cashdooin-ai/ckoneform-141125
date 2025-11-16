<?php
/**
 * Test Taking Interface for Students
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!CK_OneForm_Student_Auth::is_student_logged_in()) {
    wp_redirect(home_url('/student-login/'));
    exit;
}

$student = CK_OneForm_Student_Auth::get_current_student();
$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

if (!$test_id) {
    echo '<p>Invalid test. <a href="' . home_url('/student-dashboard/') . '">Back to Dashboard</a></p>';
    return;
}

global $wpdb;

$test = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}ck_oneform_mock_tests WHERE id = %d",
    $test_id
));

if (!$test) {
    echo '<p>Test not found. <a href="' . home_url('/student-dashboard/') . '">Back to Dashboard</a></p>';
    return;
}

// Check assignment
$assignment = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}ck_oneform_test_assignments WHERE test_id = %d AND student_id = %d",
    $test_id,
    $student->id
));

if (!$assignment) {
    echo '<p>This test is not assigned to you. <a href="' . home_url('/student-dashboard/') . '">Back to Dashboard</a></p>';
    return;
}

$questions = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}ck_oneform_questions WHERE test_id = %d ORDER BY question_number ASC",
    $test_id
));

if (count($questions) === 0) {
    echo '<p>This test has no questions yet. <a href="' . home_url('/student-dashboard/') . '">Back to Dashboard</a></p>';
    return;
}
?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="ck-test-taking-page">
    <!-- Test Info Screen (Before Start) -->
    <div id="test-info-screen" class="test-screen">
        <div class="test-info-container">
            <div class="test-header">
                <span class="exam-badge badge-<?php echo strtolower($test->exam_type); ?>">
                    <?php echo esc_html($test->exam_type); ?>
                </span>
                <h1><?php echo esc_html($test->title); ?></h1>
                <p><?php echo esc_html($test->description); ?></p>
            </div>

            <div class="test-stats">
                <div class="stat">
                    <i class="fas fa-clock"></i>
                    <span class="stat-value"><?php echo intval($test->duration); ?></span>
                    <span class="stat-label">Minutes</span>
                </div>
                <div class="stat">
                    <i class="fas fa-question-circle"></i>
                    <span class="stat-value"><?php echo count($questions); ?></span>
                    <span class="stat-label">Questions</span>
                </div>
                <div class="stat">
                    <i class="fas fa-star"></i>
                    <span class="stat-value"><?php echo intval($test->total_marks); ?></span>
                    <span class="stat-label">Total Marks</span>
                </div>
                <div class="stat">
                    <i class="fas fa-check-circle"></i>
                    <span class="stat-value"><?php echo intval($test->passing_marks); ?></span>
                    <span class="stat-label">Passing Marks</span>
                </div>
            </div>

            <div class="test-instructions">
                <h3><i class="fas fa-info-circle"></i> Instructions</h3>
                <div class="instructions-content">
                    <?php echo nl2br(esc_html($test->instructions)); ?>
                </div>
            </div>

            <div class="test-warnings">
                <div class="warning-item">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Attempts Used: <strong><?php echo $assignment->attempts_used; ?>/<?php echo $assignment->max_attempts; ?></strong></span>
                </div>
                <?php if ($test->negative_marking): ?>
                <div class="warning-item">
                    <i class="fas fa-minus-circle"></i>
                    <span>Negative Marking: <strong>Yes</strong></span>
                </div>
                <?php endif; ?>
                <div class="warning-item">
                    <i class="fas fa-clock"></i>
                    <span>Auto-submit when time runs out</span>
                </div>
            </div>

            <?php if ($assignment->attempts_used >= $assignment->max_attempts): ?>
            <div class="no-attempts-left">
                <i class="fas fa-ban"></i>
                <p>You have used all your attempts for this test.</p>
                <a href="<?php echo home_url('/student-dashboard/'); ?>" class="btn-back">Back to Dashboard</a>
            </div>
            <?php else: ?>
            <button id="start-test-btn" class="btn-start-test">
                <i class="fas fa-play-circle"></i> Start Test Now
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Test Taking Screen -->
    <div id="test-taking-screen" class="test-screen" style="display: none;">
        <div class="test-header-bar">
            <div class="test-title">
                <h2><?php echo esc_html($test->title); ?></h2>
            </div>
            <div class="timer-section">
                <i class="fas fa-clock"></i>
                <span id="timer">00:00:00</span>
            </div>
            <button id="submit-test-btn" class="btn-submit-test">
                <i class="fas fa-paper-plane"></i> Submit Test
            </button>
        </div>

        <div class="test-content">
            <div class="questions-panel">
                <?php foreach ($questions as $index => $question): ?>
                <div class="question-item" id="question-<?php echo $question->id; ?>" data-question-id="<?php echo $question->id; ?>" style="<?php echo $index > 0 ? 'display: none;' : ''; ?>">
                    <div class="question-number">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></div>
                    <div class="question-text">
                        <?php echo nl2br(esc_html($question->question_text)); ?>
                    </div>
                    <div class="question-options">
                        <label class="option-label">
                            <input type="radio" name="answer_<?php echo $question->id; ?>" value="A">
                            <span class="option-marker">A</span>
                            <span class="option-text"><?php echo esc_html($question->option_a); ?></span>
                        </label>
                        <label class="option-label">
                            <input type="radio" name="answer_<?php echo $question->id; ?>" value="B">
                            <span class="option-marker">B</span>
                            <span class="option-text"><?php echo esc_html($question->option_b); ?></span>
                        </label>
                        <label class="option-label">
                            <input type="radio" name="answer_<?php echo $question->id; ?>" value="C">
                            <span class="option-marker">C</span>
                            <span class="option-text"><?php echo esc_html($question->option_c); ?></span>
                        </label>
                        <label class="option-label">
                            <input type="radio" name="answer_<?php echo $question->id; ?>" value="D">
                            <span class="option-marker">D</span>
                            <span class="option-text"><?php echo esc_html($question->option_d); ?></span>
                        </label>
                    </div>
                    <div class="question-meta">
                        <span><i class="fas fa-star"></i> <?php echo intval($question->marks); ?> Mark(s)</span>
                        <?php if ($test->negative_marking && $question->negative_marks > 0): ?>
                        <span><i class="fas fa-minus"></i> -<?php echo floatval($question->negative_marks); ?> for wrong answer</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="question-navigation">
                    <button id="prev-btn" class="nav-btn" disabled><i class="fas fa-chevron-left"></i> Previous</button>
                    <span id="current-question-label">1 / <?php echo count($questions); ?></span>
                    <button id="next-btn" class="nav-btn">Next <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="question-palette">
                <h3><i class="fas fa-th"></i> Question Palette</h3>
                <div class="palette-grid">
                    <?php foreach ($questions as $index => $question): ?>
                    <button class="palette-btn not-visited" data-index="<?php echo $index; ?>" data-question-id="<?php echo $question->id; ?>">
                        <?php echo $index + 1; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="palette-legend">
                    <div class="legend-item"><span class="legend-color answered"></span> Answered</div>
                    <div class="legend-item"><span class="legend-color not-answered"></span> Not Answered</div>
                    <div class="legend-item"><span class="legend-color not-visited"></span> Not Visited</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Screen -->
    <div id="result-screen" class="test-screen" style="display: none;">
        <div class="result-container">
            <div id="result-icon" class="result-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h1 id="result-title">Test Completed!</h1>
            <p id="result-message">Your results are ready.</p>

            <div class="result-stats">
                <div class="result-stat">
                    <span class="stat-label">Score</span>
                    <span id="result-score" class="stat-value">0/0</span>
                </div>
                <div class="result-stat">
                    <span class="stat-label">Percentage</span>
                    <span id="result-percentage" class="stat-value">0%</span>
                </div>
                <div class="result-stat">
                    <span class="stat-label">Correct</span>
                    <span id="result-correct" class="stat-value correct">0</span>
                </div>
                <div class="result-stat">
                    <span class="stat-label">Wrong</span>
                    <span id="result-wrong" class="stat-value wrong">0</span>
                </div>
                <div class="result-stat">
                    <span class="stat-label">Attempted</span>
                    <span id="result-attempted" class="stat-value">0/0</span>
                </div>
                <div class="result-stat">
                    <span class="stat-label">Time Taken</span>
                    <span id="result-time" class="stat-value">0 mins</span>
                </div>
            </div>

            <div id="result-status" class="result-status passed">
                <i class="fas fa-check-circle"></i> PASSED
            </div>

            <a href="<?php echo home_url('/student-dashboard/'); ?>" class="btn-dashboard">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var testId = <?php echo $test_id; ?>;
    var totalQuestions = <?php echo count($questions); ?>;
    var duration = <?php echo $test->duration; ?>;
    var attemptId = 0;
    var currentQuestion = 0;
    var answers = {};
    var timeLeft = duration * 60;
    var timerInterval = null;
    var startTime = null;

    // Question IDs array
    var questionIds = [<?php echo implode(',', array_map(function($q) { return $q->id; }, $questions)); ?>];

    // Start test
    $('#start-test-btn').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Starting...');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_start_test',
                nonce: '<?php echo wp_create_nonce('ck-test-taking'); ?>',
                test_id: testId
            },
            success: function(response) {
                if (response.success) {
                    attemptId = response.data.attempt_id;
                    startTime = Date.now();

                    $('#test-info-screen').fadeOut(300, function() {
                        $('#test-taking-screen').fadeIn(300);
                        startTimer();
                        $('.palette-btn').first().removeClass('not-visited').addClass('not-answered');
                    });
                } else {
                    alert(response.data.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-play-circle"></i> Start Test Now');
                }
            },
            error: function() {
                alert('Failed to start test. Please try again.');
                $btn.prop('disabled', false).html('<i class="fas fa-play-circle"></i> Start Test Now');
            }
        });
    });

    // Timer
    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(function() {
            timeLeft--;
            updateTimerDisplay();
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitTest();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        var hours = Math.floor(timeLeft / 3600);
        var minutes = Math.floor((timeLeft % 3600) / 60);
        var seconds = timeLeft % 60;
        $('#timer').text(
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0')
        );

        if (timeLeft <= 300) {
            $('#timer').css('color', '#ef4444');
        }
    }

    // Navigation
    function showQuestion(index) {
        $('.question-item').hide();
        $('#question-' + questionIds[index]).show();
        currentQuestion = index;

        $('#prev-btn').prop('disabled', index === 0);
        $('#next-btn').prop('disabled', index === totalQuestions - 1);
        $('#current-question-label').text((index + 1) + ' / ' + totalQuestions);

        // Mark as visited
        var $paletteBtn = $(`.palette-btn[data-index="${index}"]`);
        if ($paletteBtn.hasClass('not-visited')) {
            $paletteBtn.removeClass('not-visited').addClass('not-answered');
        }
    }

    $('#prev-btn').on('click', function() {
        if (currentQuestion > 0) {
            showQuestion(currentQuestion - 1);
        }
    });

    $('#next-btn').on('click', function() {
        if (currentQuestion < totalQuestions - 1) {
            showQuestion(currentQuestion + 1);
        }
    });

    // Palette navigation
    $('.palette-btn').on('click', function() {
        showQuestion($(this).data('index'));
    });

    // Answer selection
    $('input[type="radio"]').on('change', function() {
        var questionId = $(this).attr('name').replace('answer_', '');
        answers[questionId] = $(this).val();

        // Update palette
        var index = questionIds.indexOf(parseInt(questionId));
        $(`.palette-btn[data-index="${index}"]`).removeClass('not-answered not-visited').addClass('answered');
    });

    // Submit test
    $('#submit-test-btn').on('click', function() {
        var answeredCount = Object.keys(answers).length;
        var confirmMessage = `You have answered ${answeredCount} out of ${totalQuestions} questions.\n\nAre you sure you want to submit the test?`;

        if (confirm(confirmMessage)) {
            submitTest();
        }
    });

    function submitTest() {
        clearInterval(timerInterval);

        var timeTaken = Math.round((Date.now() - startTime) / 60000);

        $('#test-taking-screen').fadeOut(300);
        $('#result-screen').fadeIn(300);
        $('#result-title').text('Submitting...');
        $('#result-message').text('Please wait while we calculate your score.');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'ck_submit_test',
                nonce: '<?php echo wp_create_nonce('ck-test-taking'); ?>',
                attempt_id: attemptId,
                answers: answers,
                time_taken: timeTaken
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;

                    $('#result-score').text(data.score.toFixed(2) + '/' + data.total_marks);
                    $('#result-percentage').text(data.percentage.toFixed(1) + '%');
                    $('#result-correct').text(data.correct);
                    $('#result-wrong').text(data.wrong);
                    $('#result-attempted').text(data.attempted + '/' + data.total_questions);
                    $('#result-time').text(data.time_taken + ' mins');

                    if (data.passed) {
                        $('#result-icon').html('<i class="fas fa-trophy"></i>');
                        $('#result-title').text('Congratulations!');
                        $('#result-message').text('You have passed the test.');
                        $('#result-status').removeClass('failed').addClass('passed').html('<i class="fas fa-check-circle"></i> PASSED');
                    } else {
                        $('#result-icon').html('<i class="fas fa-times-circle"></i>').css('color', '#ef4444');
                        $('#result-title').text('Test Completed');
                        $('#result-message').text('Better luck next time!');
                        $('#result-status').removeClass('passed').addClass('failed').html('<i class="fas fa-times-circle"></i> FAILED');
                    }
                } else {
                    $('#result-title').text('Error');
                    $('#result-message').text(response.data.message);
                }
            },
            error: function() {
                $('#result-title').text('Error');
                $('#result-message').text('Failed to submit test. Please contact support.');
            }
        });
    }

    // Prevent accidental close
    window.onbeforeunload = function() {
        if (attemptId > 0 && timerInterval !== null) {
            return 'Are you sure you want to leave? Your test will be submitted automatically.';
        }
    };
});
</script>

<style>
.ck-test-taking-page {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    min-height: 100vh;
    background: #f0f2f5;
}

/* Info Screen */
.test-info-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
}

.test-header {
    text-align: center;
    margin-bottom: 40px;
}

.exam-badge {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.badge-cuet { background: #dbeafe; color: #1e40af; }
.badge-neet { background: #d1fae5; color: #065f46; }
.badge-jee { background: #fef3c7; color: #92400e; }
.badge-cat { background: #fce7f3; color: #9d174d; }

.test-header h1 {
    font-size: 2.5rem;
    color: #333;
    margin: 0 0 10px;
}

.test-header p {
    color: #666;
    font-size: 1.1rem;
}

.test-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.stat i {
    font-size: 2rem;
    color: #667eea;
    margin-bottom: 10px;
}

.stat .stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #333;
}

.stat .stat-label {
    display: block;
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}

.test-instructions {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.test-instructions h3 {
    margin: 0 0 15px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.instructions-content {
    color: #555;
    line-height: 1.8;
}

.test-warnings {
    background: #fef3c7;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
}

.warning-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: #92400e;
}

.warning-item i {
    width: 20px;
}

.btn-start-test {
    display: block;
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-start-test:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
}

.no-attempts-left {
    background: #fee2e2;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    color: #991b1b;
}

.no-attempts-left i {
    font-size: 3rem;
    margin-bottom: 15px;
}

.btn-back {
    display: inline-block;
    padding: 10px 25px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    margin-top: 15px;
}

/* Test Taking Screen */
.test-header-bar {
    background: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.test-title h2 {
    margin: 0;
    font-size: 1.3rem;
    color: #333;
}

.timer-section {
    background: #f0f2f5;
    padding: 10px 25px;
    border-radius: 30px;
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-submit-test {
    background: #ef4444;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-submit-test:hover {
    background: #dc2626;
}

.test-content {
    display: flex;
    gap: 20px;
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.questions-panel {
    flex: 1;
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.question-number {
    font-size: 14px;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 15px;
}

.question-text {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #333;
    margin-bottom: 25px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.question-options {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.option-label {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
}

.option-label:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.option-label input {
    display: none;
}

.option-marker {
    width: 40px;
    height: 40px;
    background: #f0f2f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #667eea;
}

.option-label input:checked ~ .option-marker {
    background: #667eea;
    color: white;
}

.option-label input:checked ~ .option-text {
    font-weight: 600;
    color: #333;
}

.option-text {
    flex: 1;
    font-size: 1rem;
    color: #555;
}

.question-meta {
    margin-top: 20px;
    display: flex;
    gap: 20px;
    font-size: 14px;
    color: #666;
}

.question-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e0e0e0;
}

.nav-btn {
    padding: 12px 30px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.nav-btn:hover:not(:disabled) {
    background: #5568d3;
}

.nav-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.question-palette {
    width: 280px;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    height: fit-content;
    position: sticky;
    top: 90px;
}

.question-palette h3 {
    margin: 0 0 20px;
    color: #333;
    font-size: 1.1rem;
}

.palette-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    margin-bottom: 20px;
}

.palette-btn {
    width: 100%;
    aspect-ratio: 1;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.palette-btn.answered {
    background: #10b981;
    color: white;
}

.palette-btn.not-answered {
    background: #ef4444;
    color: white;
}

.palette-btn.not-visited {
    background: #e0e0e0;
    color: #666;
}

.palette-btn:hover {
    transform: scale(1.1);
}

.palette-legend {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: #666;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.legend-color.answered { background: #10b981; }
.legend-color.not-answered { background: #ef4444; }
.legend-color.not-visited { background: #e0e0e0; }

/* Result Screen */
.result-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 60px 20px;
    text-align: center;
}

.result-icon {
    font-size: 5rem;
    color: #fbbf24;
    margin-bottom: 20px;
}

.result-container h1 {
    font-size: 2.5rem;
    color: #333;
    margin: 0 0 10px;
}

.result-container p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.result-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.result-stat {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.result-stat .stat-label {
    display: block;
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}

.result-stat .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.result-stat .stat-value.correct {
    color: #10b981;
}

.result-stat .stat-value.wrong {
    color: #ef4444;
}

.result-status {
    padding: 15px 30px;
    border-radius: 12px;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 30px;
}

.result-status.passed {
    background: #d1fae5;
    color: #065f46;
}

.result-status.failed {
    background: #fee2e2;
    color: #991b1b;
}

.btn-dashboard {
    display: inline-block;
    padding: 15px 40px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s;
}

.btn-dashboard:hover {
    background: #5568d3;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    color: white;
}

@media (max-width: 1024px) {
    .test-content {
        flex-direction: column;
    }

    .question-palette {
        width: 100%;
        position: static;
    }

    .palette-grid {
        grid-template-columns: repeat(10, 1fr);
    }
}

@media (max-width: 768px) {
    .test-stats {
        grid-template-columns: 1fr 1fr;
    }

    .result-stats {
        grid-template-columns: 1fr 1fr;
    }

    .test-header-bar {
        flex-wrap: wrap;
        gap: 10px;
    }

    .palette-grid {
        grid-template-columns: repeat(8, 1fr);
    }
}
</style>
