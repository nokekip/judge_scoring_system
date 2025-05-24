<?php
/**
 * core Functions for Judge System
 * Contains all utility functions used across the application
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Sanitize input data
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate email format
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Get all users (participants)
 * @return array
 */
function getAllUsers() {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query("Select * from users ORDER BY display_name");
        return $stmt->fetchAll();
    }
    catch (Exception $e) {
        error_log("Error fetching users: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all judges
 * @return array
 */
function getAllJudges() {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query("SELECT * FROM judges ORDER BY display_name");
        return $stmt->fetchAll();
    }
    catch (Exception $e) {
        error_log("Error fetching judges: " . $e->getMessage());
        return [];
    }
}

/**
 * Add a new judge
 * @param string $username
 * @param string $displayName
 * @param string $email
 * @return bool
 */
function addJudge($username, $displayName, $email = '') {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("INSERT INTO judges (username, display_name, email) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $displayName, $email]);
    }
    catch (Exception $e) {
        error_log("Error adding judge: " . $e->getMessage());
        return false;
    }
}

/**
 * Add a new user (participant)
 * @param string $username
 * @param string $displayName
 * @param string $email
 * @return bool
 */
function addUser($username, $displayName, $email = '') {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("INSERT INTO users (username, display_name, email) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $displayName, $email]);
    } catch (Exception $e) {
        error_log("Error adding user: " . $e->getMessage());
        return false;
    }
}

/**
 * Submit or update a score
 * @param int $userId
 * @param int $judgeId
 * @param int $points
 * @param string $comments
 * @return bool
 */
function submitScore($userId, $judgeId, $points, $comments = '') {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            INSERT INTO scores (user_id, judge_id, points, comments) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
                points = VALUES(points), 
                comments = VALUES(comments),
                updated_at = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$userId, $judgeId, $points, $comments]);
    } catch (Exception $e) {
        error_log("Error submitting score: " . $e->getMessage());
        return false;
    }
}

/**
 * Get scoreboard data with total points
 * @return array
 */
function getScoreboard() {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query("
            SELECT 
                u.id,
                u.username,
                u.display_name,
                COALESCE(SUM(s.points), 0) as total_points,
                COUNT(s.id) as total_scores
            FROM users u
            LEFT JOIN scores s ON u.id = s.user_id
            GROUP BY u.id, u.username, u.display_name
            ORDER BY total_points DESC, u.display_name
        ");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching scoreboard: " . $e->getMessage());
        return [];
    }
}

/**
 * Get detailed scores for a specific user
 * @param int $userId
 * @return array
 */
function getUserScores($userId) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            SELECT 
                s.*,
                j.display_name as judge_name,
                u.display_name as user_name
            FROM scores s
            JOIN judges j ON s.judge_id = j.id
            JOIN users u ON s.user_id = u.id
            WHERE s.user_id = ?
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching user scores: " . $e->getMessage());
        return [];
    }
}

/**
 * Check if judge has already scored a user
 * @param int $judgeId
 * @param int $userId
 * @return array|false
 */
function getExistingScore($judgeId, $userId) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT * FROM scores WHERE judge_id = ? AND user_id = ?");
        $stmt->execute([$judgeId, $userId]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Error checking existing score: " . $e->getMessage());
        return false;
    }
}

/**
 * Get judge by ID
 * @param int $judgeId
 * @return array|false
 */
function getJudgeById($judgeId) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT * FROM judges WHERE id = ?");
        $stmt->execute([$judgeId]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Error fetching judge: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user by ID
 * @param int $userId
 * @return array|false
 */
function getUserById($userId) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Error fetching user: " . $e->getMessage());
        return false;
    }
}

/**
 * Format timestamp for display
 * @param string $timestamp
 * @return string
 */
function formatDate($timestamp) {
    return date('M j, Y g:i A', strtotime($timestamp));
}

/**
 * Generate success message HTML
 * @param string $message
 * @return string
 */
function successMessage($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

/**
 * Generate error message HTML
 * @param string $message
 * @return string
 */
function errorMessage($message) {
    return '<div class="alert alert-error">' . htmlspecialchars($message) . '</div>';
}

/**
 * Check if username already exists for judges
 * @param string $username
 * @return bool
 */
function judgeUsernameExists($username) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM judges WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if username already exists for users
 * @param string $username
 * @return bool
 */
function userUsernameExists($username) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    } catch (Exception $e) {
        return false;
    }
}
?>
