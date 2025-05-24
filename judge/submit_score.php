<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Submit Score';
$message = '';

// Demo judge ID - would come from session in real app
$judge_id = 1;
$judge = getJudgeById($judge_id);

// Get user ID from query string
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$user = getUserById($user_id);

if (!$user) {
    header('Location: select_user.php');
    exit;
}

// Check if judge already scored this user
$existing_score = getExistingScore($judge_id, $user_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $points = isset($_POST['points']) ? (int)$_POST['points'] : 0;
    $comments = sanitizeInput($_POST['comments'] ?? '');

    if ($points < 0 || $points > 100) {
        $_SESSION['message'] = errorMessage('Points must be between 0 and 100');
    } else {
        if (submitScore($user_id, $judge_id, $points, $comments)) {
            $_SESSION['message'] = successMessage(
                $existing_score ? 'Score updated successfully!' : 'Score submitted successfully!'
            );
        } else {
            $_SESSION['message'] = errorMessage('Failed to submit score');
        }
    }
    
    // Redirect to prevent form resubmission
    header("Location: submit_score.php?user_id=$user_id");
    exit;
}

// Check for messages in session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Score Submission</h2>
            <p>Evaluating: <?= htmlspecialchars($user['display_name']) ?></p>
        </div>

        <?= $message ?>

        <form method="post" class="score-form">
            <div class="form-group">
                <label for="points">Score (0-100)</label>
                <input type="number" id="points" name="points" 
                       min="0" max="100" 
                       value="<?= $existing_score['points'] ?? '' ?>" 
                       class="form-control" required>
                <small class="text-muted">Enter a score between 0 and 100</small>
            </div>
            
            <div class="form-group">
                <label for="comments">Comments (Optional)</label>
                <textarea id="comments" name="comments" class="form-control" rows="4"><?= 
                    htmlspecialchars($existing_score['comments'] ?? '') 
                ?></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <?= $existing_score ? 'Update Score' : 'Submit Score' ?>
                </button>
                <a href="select_user.php" class="btn btn-secondary">Back to Participants</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>