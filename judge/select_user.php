<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Select Participant';
$judge_id = 1; // Demo judge ID

// Get all users and mark which ones have been scored
$users = getAllUsers();
$scored_users = [];

foreach ($users as &$user) {
    $user['already_scored'] = (bool)getExistingScore($judge_id, $user['id']);
    
    // Track scored users separately for sorting
    $scored_users[$user['id']] = $user['already_scored'];
}

// Sort users - unscored first
usort($users, function($a, $b) use ($scored_users) {
    // If both are scored or both are unscored, sort by name
    if ($a['already_scored'] === $b['already_scored']) {
        return strcmp($a['display_name'], $b['display_name']);
    }
    // Unscored comes before scored
    return $a['already_scored'] ? 1 : -1;
});
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Select Participant to Score</h2>
        </div>

        <?php if (empty($users)): ?>
            <div class="alert alert-info">
                No participants available for scoring. Please contact the administrator.
            </div>
        <?php else: ?>
            <div class="participant-grid">
                <?php foreach ($users as $user): ?>
                <div class="participant-card <?= $user['already_scored'] ? 'scored' : 'unscored' ?>">
                    <div class="participant-info">
                        <h3><?= htmlspecialchars($user['display_name']) ?></h3>
                        <p class="text-muted">@<?= htmlspecialchars($user['username']) ?></p>
                        
                        <?php if ($user['already_scored']): ?>
                        <div class="existing-score">
                            <span class="badge">Already Scored</span>
                        </div>
                        <?php else: ?>
                        <div class="new-score-indicator">
                            <span class="badge badge-new">NEW</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="participant-actions">
                        <a href="submit_score.php?user_id=<?= $user['id'] ?>" 
                           class="btn <?= $user['already_scored'] ? 'btn-secondary' : 'btn-primary' ?>">
                            <?= $user['already_scored'] ? 'Update Score' : 'Score Now' ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>