<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Admin Panel';
$judges = getAllJudges();
$users = getAllUsers();
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Admin Dashboard</h2>
        </div>
        
        <div class="grid grid-3">
            <div class="stat-card">
                <div class="stat-number"><?= count($judges) ?></div>
                <div class="stat-label">Total Judges</div>
                <a href="manage_judges.php" class="btn btn-primary mt-2">Manage Judges</a>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?= count($users) ?></div>
                <div class="stat-label">Total Participants</div>
                <a href="manage_users.php" class="btn btn-primary mt-2">Manage Users</a>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">
                    <?= array_sum(array_column(getScoreboard(), 'total_points')) ?>
                </div>
                <div class="stat-label">Total Points Awarded</div>
                <a href="/scoring_system/public/scoreboard.php" class="btn btn-secondary mt-2">View Scoreboard</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Recent Judges</h3>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Display Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($judges, 0, 5) as $judge): ?>
                    <tr>
                        <td><?= htmlspecialchars($judge['id']) ?></td>
                        <td><?= htmlspecialchars($judge['username']) ?></td>
                        <td><?= htmlspecialchars($judge['display_name']) ?></td>
                        <td><?= htmlspecialchars($judge['email']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="text-center mt-2">
                <a href="add_judge.php" class="btn btn-primary">View All Judges</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>