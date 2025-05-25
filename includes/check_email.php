<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['email'])) {
    echo json_encode(['error' => 'Email parameter required']);
    exit;
}

$email = $_GET['email'];
$result = emailExists($email);

if ($result === "judge") {
    echo json_encode(['exists' => true, 'type' => 'judge']);
} elseif ($result === "participant") {
    echo json_encode(['exists' => true, 'type' => 'participant']);
} else {
    echo json_encode(['exists' => false]);
}