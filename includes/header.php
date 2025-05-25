<?php
session_start();
/**
 * Header Template
 * Contains HTML head and navigation
 */

// Get the current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$page_title = $page_title ?? 'Scoring System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - LAMP Scoring System</title>
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="/scoring_system/assets/css/style.css">

    <!-- Page-Specific CSS -->
    <?php
    $page = basename($_SERVER['PHP_SELF']);
    $page_path_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

    if (in_array('judge', $page_path_parts)) {
        echo '<link rel="stylesheet" href="/scoring_system/assets/css/judge-portal.css">';
    } elseif (in_array('admin', $page_path_parts)) {
        echo '<link rel="stylesheet" href="/scoring_system/assets/css/admin.css">';
    } elseif ($page === 'scoreboard.php') {
        echo '<link rel="stylesheet" href="/scoring_system/assets/css/scoreboard.css">';
    }

    if ($page === 'submit_score.php') {
        echo '<link rel="stylesheet" href="/scoring_system/assets/css/judge-portal.css">';
    }
    ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/scoring_system/assets/images/favicon.ico">
    
    <!-- Meta tags -->
    <meta name="description" content="LAMP Stack Scoring System for Judge-based Competitions">
    <meta name="author" content="Your Name">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="/scoring_system/public/">Scoring System</a></h1>
                </div>
                
                <nav class="main-nav">
                    <ul>
                        <li><a href="/scoring_system/public/" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="/scoring_system/public/scoreboard.php" class="<?php echo ($current_page == 'scoreboard.php') ? 'active' : ''; ?>">Scoreboard</a></li>
                        <li><a href="/scoring_system/admin/" class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) ? 'active' : ''; ?>">Admin</a></li>
                        <li><a href="/scoring_system/judge/" class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/judge/') !== false) ? 'active' : ''; ?>">Judge Portal</a></li>
                    </ul>
                </nav>
                
                <div class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container"><?php
/**
 * Note: The closing </div>, </main>, and other tags are in footer.php
 * This allows for flexible content layout between header and footer
 */