<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Add New Judge';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $display_name = sanitizeInput($_POST['display_name']);
    $email = sanitizeInput($_POST['email']);

    if (empty($username) || empty($display_name)) {
        $_SESSION['message'] = '<div class="alert alert-error">Username and display name are required</div>';
    } elseif (judgeUsernameExists($username)) {
        $_SESSION['message'] = '<div class="alert alert-error">Username already exists</div>';
    } else {
        if (addJudge($username, $display_name, $email)) {
            $_SESSION['message'] = '<div class="alert alert-success">Judge added successfully!</div>';
        } else {
            $_SESSION['message'] = '<div class="alert alert-error">Failed to add judge</div>';
        }
    }
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Display message from session if it exists
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Add New Judge</h2>
            <p>Add a new judge to the scoring system</p>
        </div>
        
        <?php echo $message; ?>
        
        <form method="post" class="mt-3">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
                <small class="text-muted">Must be unique</small>
            </div>
            
            <div class="form-group">
                <label for="display_name">Display Name</label>
                <input type="text" id="display_name" name="display_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control">
                <small class="text-muted">Optional</small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Add Judge</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>