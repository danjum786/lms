<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id'])) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}


// Fetch courses from the database
$sql = "SELECT course_id, title, created_at FROM courses";
$result = mysqli_query($conn, $sql);
?>

<div class="main-content">
    <h1>All Courses</h1>

    <!-- Display success/error message if exists -->
    <?php
    if (isset($_SESSION['showAlert'])) {
        showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
        unset($_SESSION['showAlert']);
    }
    ?>

    <!-- Courses Table -->
    <table class="data-table" style="overflow-x: auto; min-width:800px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Title</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $counter = 1;
                while ($course = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($course['created_at'])); ?></td>
                        <td>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="course_edit.php?id=<?php echo $course['course_id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="course_delete.php?id=<?php echo $course['course_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php $counter++;
                endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No courses found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("./includes/footer.php"); ?>