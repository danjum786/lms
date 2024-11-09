<?php
include("./includes/header.php");
include("./includes/db.php");

// Ensure user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the course ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['showAlert'] = ['color' => 'error', 'msg' => 'Invalid course ID.'];
    header("Location: view_courses.php");
    exit();
}

$course_id = intval($_GET['id']);

// Fetch existing course data
$sql = "SELECT title, created_at FROM courses WHERE course_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$course) {
    $_SESSION['showAlert'] = ['color' => 'error', 'msg' => 'Course not found.'];
    header("Location: view_courses.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    // Update course details
    $update_sql = "UPDATE courses SET title = ? WHERE course_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, 'si', $title, $course_id);
    $update_success = mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);

    if ($update_success) {
        $_SESSION['showAlert'] = ['color' => '#155724', 'msg' => 'Course updated successfully.'];
        header("Location: course_manage.php");
        exit();
    } else {
        $_SESSION['showAlert'] = ['color' => '#721c24', 'msg' => 'Failed to update course.'];
    }
}
?>

<div class="main-content">
    <h1>Edit Course</h1>

    <!-- Display success/error message if exists -->
    <?php
    if (isset($_SESSION['showAlert'])) {
        showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
        unset($_SESSION['showAlert']);
    }
    ?>

    <!-- Edit Course Form -->
    <div class="form-container">
        <form action="" method="POST" class="form">
            <div class="form-group">
                <label class="form-label" for="title">Course Title</label>
                <input type="text" id="title" name="title" class="form-input" value="<?php echo htmlspecialchars($course['title']); ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Course</button>
                <a href="view_courses.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include("./includes/footer.php"); ?>