<?php
include("./includes/header.php");
checkUser();

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Display alert if exists
if (isset($_SESSION['showAlert'])) {
    showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
    unset($_SESSION['showAlert']);
}
?>

<!-- Main Content Area -->
<div class="main-content">

    <div class="form-container">
        <form class="form" action="course_add_process.php" method="POST">
            <h1 class="form-title">Add New Course</h1>

            <!-- Course Title Input -->
            <div class="form-group">
                <label class="form-label" for="title">Course Title</label>
                <input type="text" id="title" name="title" class="form-input" required>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Course</button>
            </div>

        </form>
    </div>
</div>


<?php
include("./includes/footer.php");

?>