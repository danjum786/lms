<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Check if the lesson ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: lesson_manage.php");
    exit();
}

$lesson_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch lesson data to prefill the form
$sql = "SELECT * FROM lessons WHERE lesson_id = '$lesson_id'";
$result = mysqli_query($conn, $sql);
$lesson = mysqli_fetch_assoc($result);

if (!$lesson) {
    $_SESSION['showAlert'] = [
        'color' => 'danger',
        'msg' => 'Lesson not found.'
    ];
    header("Location: lesson_manage.php");
    exit();
}

// Fetch courses for the course selection dropdown
$coursesSql = "SELECT course_id, title FROM courses";
$coursesResult = mysqli_query($conn, $coursesSql);

// Handle form submission for updating lesson details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $updateSql = "UPDATE lessons SET course_id = '$course_id', title = '$title', content = '$content' WHERE lesson_id = '$lesson_id'";

    if (mysqli_query($conn, $updateSql)) {
        $_SESSION['showAlert'] = [
            'color' => 'success',
            'msg' => 'Lesson updated successfully.'
        ];
        header("Location: lesson_manage.php");
        exit();
    } else {
        $_SESSION['showAlert'] = [
            'color' => 'danger',
            'msg' => 'Error updating lesson: ' . mysqli_error($conn)
        ];
    }
}
?>

<div class="main-content">
    <div class="form-container">
        <h1>Edit Lesson</h1>

        <!-- Display alert if available -->
        <?php
        if (isset($_SESSION['showAlert'])) {
            showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
            unset($_SESSION['showAlert']);
        }
        ?>

        <!-- Edit Lesson Form -->
        <form class="form" action="lesson_edit.php?id=<?php echo $lesson_id; ?>" method="POST">
            <div class="form-group">
                <label for="course_id" class="form-label">Select Course</label>
                <select id="course_id" name="course_id" class="form-input" required>
                    <option value="">-- Select Course --</option>
                    <?php while ($course = mysqli_fetch_assoc($coursesResult)): ?>
                        <option value="<?php echo htmlspecialchars($course['course_id']); ?>" <?php echo ($course['course_id'] == $lesson['course_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course['title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Lesson Title</label>
                <input type="text" id="title" name="title" class="form-input" value="<?php echo htmlspecialchars($lesson['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" class="form-input" rows="6" required><?php echo htmlspecialchars($lesson['content']); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Lesson</button>
            </div>
        </form>
    </div>
</div>

<?php include("./includes/footer.php"); ?>