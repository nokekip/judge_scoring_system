<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// For demo purposes, we'll use judge ID 1
// In a real app, this would come from session/auth
$judge_id = 1;
$judge = getJudgeById($judge_id);
$page_title = 'Judge Portal: ' . htmlspecialchars($judge['display_name']);

// Get scores submitted by this judge
$pdo = getDbConnection();
$stmt = $pdo->prepare("
    SELECT COUNT(*) as total_scores, 
           AVG(points) as avg_score,
           MAX(created_at) as last_scored
    FROM scores 
    WHERE judge_id = ?
");
$stmt->execute([$judge_id]);
$stats = $stmt->fetch();

// Get recent scores
$stmt = $pdo->prepare("
    SELECT u.display_name, s.points, s.created_at
    FROM scores s
    JOIN users u ON s.user_id = u.id
    WHERE s.judge_id = ?
    ORDER BY s.created_at DESC
    LIMIT 5
");
$stmt->execute([$judge_id]);
$recent_scores = $stmt->fetchAll();
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Judge Dashboard</h2>
            <p>Welcome, <?= htmlspecialchars($judge['display_name']) ?></p>
        </div>

        <div class="grid grid-3">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total_scores'] ?></div>
                <div class="stat-label">Scores Submitted</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?= round($stats['avg_score'], 1) ?></div>
                <div class="stat-label">Average Score</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">
                    <?= $stats['last_scored'] ? formatDate($stats['last_scored']) : 'Never' ?>
                </div>
                <div class="stat-label">Last Scored</div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="select_user.php" class="btn btn-primary btn-lg">
                Score a Participant
            </a>
        </div>
    </div>

    <?php if ($recent_scores): ?>
    <div class="card mt-3">
        <div class="card-header">
            <h3>Your Recent Scores</h3>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_scores as $score): ?>
                <tr>
                    <td><?= htmlspecialchars($score['display_name']) ?></td>
                    <td class="points-display"><?= $score['points'] ?></td>
                    <td><?= formatDate($score['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>