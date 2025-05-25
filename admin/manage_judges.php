<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Manage Judges';
$judges = getAllJudges();
$message = '';

// Handle judge deletion
if (isset($_GET['delete'])) {
    $judge_id = (int)$_GET['delete'];
    if (deleteJudge($judge_id)) {
        $_SESSION['message'] = successMessage('Judge deleted successfully');
        header('Location: manage_judges.php');
        exit;
    } else {
        $message = errorMessage('Failed to delete judge');
    }
}

// Handle form submission
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $display_name = sanitizeInput($_POST['display_name']);
    $email = sanitizeInput($_POST['email']);

    if (empty($username) || empty($display_name)) {
        $message = errorMessage('Username and display name are required');
    } elseif (judgeUsernameExists($username)) {
        $message = errorMessage('Username already exists');
    } else {
        $result = addJudge($username, $display_name, $email);
        if ($result === true) {
            $_SESSION['message'] = successMessage('Judge added successfully!');
            header('Location: manage_judges.php');
            exit;
        } else {
            $message = errorMessage($result);
        }
    }
}

// Check for messages from redirects
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Manage Judges</h2>
            <button class="btn btn-primary" data-modal-open="addJudgeModal">
                Add New Judge
            </button>
        </div>

        <?= $message ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Display Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($judges as $judge): ?>
                    <tr>
                        <td><?= $judge['id'] ?></td>
                        <td><?= htmlspecialchars($judge['username']) ?></td>
                        <td><?= htmlspecialchars($judge['display_name']) ?></td>
                        <td><?= htmlspecialchars($judge['email']) ?></td>
                        <td>
                            <button data-modal-edit="<?= $judge['id'] ?>" 
                                    data-modal-target="editJudgeModal"
                                    class="btn btn-sm btn-secondary">
                                Edit
                            </button>
                            <a href="manage_judges.php?delete=<?= $judge['id'] ?>" 
                            class="btn btn-sm btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this judge?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Judge Modal -->
<dialog id="addJudgeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Judge</h3>
            <button data-modal-close class="close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="manage_judges.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="display_name">Display Name</label>
                    <input type="text" id="display_name" name="display_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email (optional)</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Judge</button>
                    <button type="button" class="btn btn-secondary" data-modal-close>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</dialog>

<!-- update judge modal-->
<dialog id="editJudgeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Judge</h3>
            <button data-modal-close class="close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="update_judge.php">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_display_name">Display Name</label>
                    <input type="text" id="edit_display_name" name="display_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email (optional)</label>
                    <input type="email" id="edit_email" name="email" class="form-control">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Judge</button>
                    <button type="button" class="btn btn-secondary" data-modal-close>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</dialog>

<!-- Load modal.js -->
<script src="/scoring_system/assets/js/modal.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>