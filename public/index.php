<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Scoring System Home';
$participant_count = count(getAllUsers());
$judge_count = count(getAllJudges());
$recent_scores = array_slice(getScoreboard(), 0, 3);
?>

<div class="container">
    <div class="hero-section">
        <h1>Welcome to the Competition Scoring System</h1>
        <p class="lead">A professional platform for judges to evaluate participants and display real-time results.</p>
        
        <div class="cta-buttons">
            <a href="scoreboard.php" class="btn btn-primary btn-lg">View Live Scoreboard</a>
            <a href="/scoring_system/judge/" class="btn btn-secondary btn-lg">Judge Portal</a>
        </div>
    </div>

    <div class="stats-grid grid grid-3">
        <div class="stat-card">
            <div class="stat-number"><?= $participant_count ?></div>
            <div class="stat-label">Participants</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $judge_count ?></div>
            <div class="stat-label">Judges</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?= array_sum(array_column(getScoreboard(), 'total_scores')) ?>
            </div>
            <div class="stat-label">Scores Submitted</div>
        </div>
    </div>

    <?php if (!empty($recent_scores)): ?>
    <div class="card">
        <div class="card-header">
            <h2>Current Top Performers</h2>
        </div>
        <div class="top-performers">
            <?php foreach ($recent_scores as $index => $participant): ?>
            <div class="performer rank-<?= $index + 1 ?>">
                <span class="rank"><?= $index + 1 ?></span>
                <span class="name"><?= htmlspecialchars($participant['display_name']) ?></span>
                <span class="score"><?= $participant['total_points'] ?> pts</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>How It Works</h2>
        </div>
        <div class="grid grid-2">
            <div class="step">
                <h3>For Judges</h3>
                <ol>
                    <li>Login to the judge portal</li>
                    <li>Select a participant</li>
                    <li>Submit your score (0-100)</li>
                    <li>View your scoring history</li>
                </ol>
            </div>
            <div class="step">
                <h3>For Participants</h3>
                <ol>
                    <li>View the public scoreboard</li>
                    <li>See your current ranking</li>
                    <li>Track judge evaluations</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>