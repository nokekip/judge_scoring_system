<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_judges.php');
    exit;
}

$judge_id = (int)$_POST['id'];
$username = sanitizeInput($_POST['username']);
$display_name = sanitizeInput($_POST['display_name']);
$email = sanitizeInput($_POST['email']);

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("
        UPDATE judges 
        SET username = ?, display_name = ?, email = ?
        WHERE id = ?
    ");
    
    if ($stmt->execute([$username, $display_name, $email, $judge_id])) {
        $_SESSION['message'] = successMessage('Judge updated successfully!');
    } else {
        $_SESSION['message'] = errorMessage('Failed to update judge');
    }
} catch (Exception $e) {
    error_log("Error updating judge: " . $e->getMessage());
    $_SESSION['message'] = errorMessage('Database error occurred');
}

header('Location: manage_judges.php');