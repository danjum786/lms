<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch lessons with course titles
$sql = "SELECT lessons.lesson_id,lessons.course_id, lessons.title, lessons.created_at, courses.title AS course_title 
        FROM lessons 
        JOIN courses ON lessons.course_id = courses.course_id";
$result = mysqli_query($conn, $sql);
?>

<div class="main-content">
    <h1>Manage Lessons</h1>

    <!-- Display success/error message if exists -->
    <?php
    if (isset($_SESSION['showAlert'])) {
        showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
        unset($_SESSION['showAlert']);
    }
    ?>

    <!-- Lessons Table -->
    <table class="data-table" style="overflow-x: auto; min-width:800px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lesson Title</th>
                <th>Course</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $counter = 1;
                while ($lesson = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <td><?php echo htmlspecialchars($lesson['title']); ?></td>
                        <td><?php echo htmlspecialchars($lesson['course_title']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($lesson['created_at'])); ?></td>
                        <td>
                            <a href="quiz_add.php?lesson_id=<?php echo $lesson['lesson_id']; ?>" class="btn btn-view">Add Quiz</a>
                            <a href="lesson_edit.php?id=<?php echo $lesson['lesson_id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="lesson_delete.php?id=<?php echo $lesson['lesson_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this lesson?');">Delete</a>
                            <a href="lesson_view.php?id=<?php echo $lesson['lesson_id']; ?>" class="btn btn-view">View</a>
                        </td>
                    </tr>
                <?php $counter++;
                endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No lessons found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("./includes/footer.php"); ?>