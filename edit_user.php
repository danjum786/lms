<?php
include("./includes/header.php");
include("./includes/db.php"); // Database connection

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Assuming this part is used to process form submission
$message = '';
$messageType = ''; // This will hold 'success' or 'error' class

// Get user ID from URL
$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

// Fetch user details
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    showAlert("#721c24", "User does not exist");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $query = "UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['showAlert'] = [
            'msg' => 'User details updated successfully',
            'color' => '#155724'
        ];
    } else {
        $_SESSION['showAlert'] = [
            'msg' => 'Something went wrong! Please try again.',
            'color' => '#721c24'
        ];
    }
    mysqli_stmt_close($stmt);

    // Refresh user data
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
<?php
// Display the alert if a session message exists
if (isset($_SESSION['showAlert'])) {
    showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
    unset($_SESSION['showAlert']); // Clear the alert from the session
}
?>

<!-- Main Content Area -->
<div class="main-content">
    <div class="form-container">
        <h1>Edit User</h1>
        <?php if ($message): ?>
            <p class="message <?= $messageType; ?>" id="message"><?= $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="instructor" <?php if ($user['role'] == 'instructor') echo 'selected'; ?>>Instructor</option>
                    <option value="student" <?php if ($user['role'] == 'student') echo 'selected'; ?>>Student</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include("./includes/footer.php"); ?>