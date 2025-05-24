<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Live Scoreboard';
$scoreboard = getScoreboard();
$last_updated = date('Y-m-d H:i:s');
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="scoreboard-header">
                <h2>Live Scoreboard</h2>
                <div class="scoreboard-legend">
                    <span class="legend-item"><span class="legend-icon gold"></span> Gold</span>
                    <span class="legend-item"><span class="legend-icon silver"></span> Silver</span>
                    <span class="legend-item"><span class="legend-icon bronze"></span> Bronze</span>
                </div>
                <div class="last-updated">
                    Last updated: <span class="update-time"><?= date('g:i A') ?></span>
                    <span class="auto-refresh-status">(Auto-refreshing every 30 seconds)</span>
                </div>
            </div>
        </div>

        <?php if (empty($scoreboard)): ?>
            <div class="alert alert-info">No scores available yet. Check back later!</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="scoreboard-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Participant</th>
                            <th>Total Score</th>
                            <th>Evaluations</th>
                            <th>Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scoreboard as $index => $participant): 
                            $average = $participant['total_scores'] > 0 
                                ? round($participant['total_points'] / $participant['total_scores'], 1)
                                : 0;
                        ?>
                        <tr class="<?= $index < 3 ? 'top-rank top-' . ($index + 1) : '' ?>">
                            <td>
                                <span class="scoreboard-rank rank-<?= $index + 1 ?>">
                                    <?= $index + 1 ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($participant['display_name']) ?></td>
                            <td class="points-display"><?= $participant['total_points'] ?></td>
                            <td><?= $participant['total_scores'] ?></td>
                            <td><?= $average ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Auto-refresh functionality
let lastUpdateTime = '<?= $last_updated ?>';
const refreshInterval = 30000; // 30 seconds

function checkForUpdates() {
    fetch('/scoring_system/includes/get_last_update.php')
        .then(response => response.json())
        .then(data => {
            if (new Date(data.last_updated) > new Date(lastUpdateTime)) {
                location.reload();
            }
        })
        .catch(error => console.error('Error checking updates:', error));
}

// Refresh the page every 30 seconds
setInterval(() => {
    checkForUpdates();
}, refreshInterval);

// Update the displayed time every minute
setInterval(() => {
    document.querySelector('.update-time').textContent = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
}, 60000);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>