<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Manage Participants';
$users = getAllUsers();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = sanitizeInput($_POST['username']);
    $display_name = sanitizeInput($_POST['display_name']);
    $email = sanitizeInput($_POST['email']);

    if (empty($username) || empty($display_name)) {
        $_SESSION['message'] = errorMessage('Username and display name are required');
    } elseif (userUsernameExists($username)) {
        $_SESSION['message'] = errorMessage('Username already exists');
    } else {
        $result = addUser($username, $display_name, $email);
        if ($result === true) {
            $_SESSION['message'] = successMessage('Participant added successfully!');
        } else {
            $_SESSION['message'] = errorMessage($result); // $result contains the error message
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
    $users = getAllUsers(); // Refresh the user list
}
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Manage Participants</h2>
            <p>Add and view competition participants</p>
        </div>
        
        <?php echo $message; ?>
        
        <div class="grid grid-2">
            <div class="card">
                <div class="card-header">
                    <h3>Add New Participant</h3>
                </div>
                
                <form method="post" class="mt-2">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="display_name">Display Name</label>
                        <input type="text" id="display_name" name="display_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="add_user" class="btn btn-primary">Add Participant</button>
                    </div>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Current Participants (<?= count($users) ?>)</h3>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Display Name</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['display_name']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>