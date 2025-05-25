<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Judge ID required']);
    exit;
}

$judge_id = (int)$_GET['id'];
$judge = getJudgeById($judge_id);

if (!$judge) {
    http_response_code(404);
    echo json_encode(['error' => 'Judge not found']);
    exit;
}

echo json_encode($judge);