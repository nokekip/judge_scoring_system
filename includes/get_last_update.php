<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT MAX(updated_at) as last_updated FROM scores");
    $result = $stmt->fetch();
    
    echo json_encode([
        'last_updated' => $result['last_updated'] ?: date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    echo json_encode([
        'last_updated' => date('Y-m-d H:i:s'),
        'error' => $e->getMessage()
    ]);
}