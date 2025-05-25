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
    $current_path = $_SERVER['REQUEST_URI'];
    $base_url = '/scoring_system';

    // Determine which additional CSS to load
    if (strpos($current_path, '/judge/') !== false) {
        // All judge portal pages
        echo '<link rel="stylesheet" href="'.$base_url.'/assets/css/judge-portal.css">';
    } elseif (strpos($current_path, '/admin/') !== false) {
        // All admin pages
        echo '<link rel="stylesheet" href="'.$base_url.'/assets/css/admin.css">';
    } elseif (basename($_SERVER['PHP_SELF']) === 'scoreboard.php') {
        // Public scoreboard
        echo '<link rel="stylesheet" href="'.$base_url.'/assets/css/scoreboard.css">';
    } elseif (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($current_path, '/public/') !== false) {
        // Only the public homepage (not admin/judge index.php)
        echo '<link rel="stylesheet" href="'.$base_url.'/assets/css/home.css">';
    }

    // Debug output (remove in production)
    echo '<!-- Loading: '.htmlspecialchars(basename($_SERVER['PHP_SELF'])).' from '.htmlspecialchars($current_path).' -->';
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