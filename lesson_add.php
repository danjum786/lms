<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch courses for the course selection dropdown
$sql = "SELECT course_id, title FROM courses";
$coursesResult = mysqli_query($conn, $sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "INSERT INTO lessons (course_id, title, content) VALUES ('$course_id', '$title', '$content')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['showAlert'] = [
            'color' => '#155724',
            'msg' => 'Lesson added successfully.'
        ];
        header("Location: lesson_manage.php");
        exit();
    } else {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Error adding lesson: ' . mysqli_error($conn)
        ];
    }
}
?>

<div class="main-content">
    <div class="form-container">
        <h1>Add New Lesson</h1>

        <!-- Display alert if available -->
        <?php
        if (isset($_SESSION['showAlert'])) {
            showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
            unset($_SESSION['showAlert']);
        }
        ?>

        <!-- Lesson Form -->
        <form class="form" action="lesson_add.php" method="POST">
            <div class="form-group">
                <label for="course_id" class="form-label">Select Course</label>
                <select id="course_id" name="course_id" class="form-input" required>
                    <option value="">-- Select Course --</option>
                    <?php while ($course = mysqli_fetch_assoc($coursesResult)): ?>
                        <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Lesson Title</label>
                <input type="text" id="title" name="title" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" class="form-input" rows="6" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Lesson</button>
            </div>
        </form>
    </div>
</div>

<?php include("./includes/footer.php"); ?>