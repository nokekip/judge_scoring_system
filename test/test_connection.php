<?php
/**
 * Database Connection Test
 * This file tests the database connection and displays system information
 */

$page_title = "Connection Test";
require_once '../includes/header.php';
require_once '../includes/functions.php';

echo '<div class="card">';
echo '<div class="card-header">';
echo '<h2>🔧 System Connection Test</h2>';
echo '</div>';

// Test Database Connection
echo '<h3>Database Connection Test</h3>';
try {
    $pdo = getDbConnection();
    echo '<div class="alert alert-success"> Database connection successful!</div>';
    
    // Test data retrieval
    $users = getAllUsers();
    $judges = getAllJudges();
    $scoreboard = getScoreboard();
    
    echo '<div class="grid grid-3">';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . count($users) . '</div>';
    echo '<div class="stat-label">Total Users</div>';
    echo '</div>';
    
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . count($judges) . '</div>';
    echo '<div class="stat-label">Total Judges</div>';
    echo '</div>';
    
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . count($scoreboard) . '</div>';
    echo '<div class="stat-label">Scoreboard Entries</div>';
    echo '</div>';
    echo '</div>';
    
} catch (Exception $e) {
    echo '<div class="alert alert-error"> Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// System Information
echo '<h3>System Information</h3>';
echo '<table class="table">';
echo '<tr><td><strong>PHP Version</strong></td><td>' . phpversion() . '</td></tr>';
echo '<tr><td><strong>Server Software</strong></td><td>' . $_SERVER['SERVER_SOFTWARE'] . '</td></tr>';
echo '<tr><td><strong>Document Root</strong></td><td>' . $_SERVER['DOCUMENT_ROOT'] . '</td></tr>';
echo '<tr><td><strong>Server Name</strong></td><td>' . $_SERVER['SERVER_NAME'] . '</td></tr>';
echo '<tr><td><strong>Request URI</strong></td><td>' . $_SERVER['REQUEST_URI'] . '</td></tr>';
echo '</table>';

// Test Functions
echo '<h3>Function Tests</h3>';
$test_results = [];

// Test sanitization
$test_input = "<script>alert('test')</script>";
$sanitized = sanitizeInput($test_input);
$test_results[] = [
    'Function' => 'sanitizeInput()',
    'Status' => ($sanitized !== $test_input) ? ' Pass' : ' Fail',
    'Result' => htmlspecialchars($sanitized)
];

// Test email validation
$valid_email = validateEmail('test@example.com');
$invalid_email = validateEmail('invalid-email');
$test_results[] = [
    'Function' => 'validateEmail() - Valid',
    'Status' => $valid_email ? ' Pass' : ' Fail',
    'Result' => $valid_email ? 'true' : 'false'
];

$test_results[] = [
    'Function' => 'validateEmail() - Invalid',
    'Status' => !$invalid_email ? ' Pass' : ' Fail',
    'Result' => $invalid_email ? 'true' : 'false'
];

echo '<table class="table">';
echo '<thead><tr><th>Function</th><th>Status</th><th>Result</th></tr></thead>';
echo '<tbody>';
foreach ($test_results as $test) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($test['Function']) . '</td>';
    echo '<td>' . $test['Status'] . '</td>';
    echo '<td>' . htmlspecialchars($test['Result']) . '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

// Sample Data Display
if (!empty($users)) {
    echo '<h3>Sample Users</h3>';
    echo '<table class="table">';
    echo '<thead><tr><th>ID</th><th>Username</th><th>Display Name</th><th>Created</th></tr></thead>';
    echo '<tbody>';
    foreach (array_slice($users, 0, 5) as $user) {
        echo '<tr>';
        echo '<td>' . $user['id'] . '</td>';
        echo '<td>' . htmlspecialchars($user['username']) . '</td>';
        echo '<td>' . htmlspecialchars($user['display_name']) . '</td>';
        echo '<td>' . formatDate($user['created_at']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

if (!empty($judges)) {
    echo '<h3>Sample Judges</h3>';
    echo '<table class="table">';
    echo '<thead><tr><th>ID</th><th>Username</th><th>Display Name</th><th>Created</th></tr></thead>';
    echo '<tbody>';
    foreach ($judges as $judge) {
        echo '<tr>';
        echo '<td>' . $judge['id'] . '</td>';
        echo '<td>' . htmlspecialchars($judge['username']) . '</td>';
        echo '<td>' . htmlspecialchars($judge['display_name']) . '</td>';
        echo '<td>' . formatDate($judge['created_at']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}


echo '</div>'; // Close card

require_once '../includes/footer.php';
?>