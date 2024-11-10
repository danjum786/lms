<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Retrieve lesson ID from URL
if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id']; // The lesson for which MCQs are being added
} else {
    echo "Lesson ID is missing!";
    exit();
}

// Fetch lesson details (Optional)
$sql = "SELECT * FROM lessons WHERE lesson_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $lesson_id);
mysqli_stmt_execute($stmt);
$lesson = mysqli_stmt_get_result($stmt)->fetch_assoc();
if (!$lesson) {
    echo "Lesson not found!";
    exit();
}

?>

<div class="main-content">
    <div class="form-container">
        <h1>Add MCQs for Lesson: <?php echo htmlspecialchars($lesson['title']); ?></h1>

        <!-- MCQ Form -->
        <form action="quiz_add_process.php?lesson_id=<?php echo $lesson['lesson_id'] ?>" method="POST">
            <div class="form-group">
                <label for="question_1">Question 1</label>
                <input type="text" id="question_1" name="questions[0][question]" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="option_1_1">Option 1</label>
                <input type="text" id="option_1_1" name="questions[0][options][0]" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="option_1_2">Option 2</label>
                <input type="text" id="option_1_2" name="questions[0][options][1]" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="option_1_3">Option 3</label>
                <input type="text" id="option_1_3" name="questions[0][options][2]" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="option_1_4">Option 4</label>
                <input type="text" id="option_1_4" name="questions[0][options][3]" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="correct_1">Correct Option</label>
                <select name="questions[0][correct]" class="form-input">
                    <option value="0">Option 1</option>
                    <option value="1">Option 2</option>
                    <option value="2">Option 3</option>
                    <option value="3">Option 4</option>
                </select>
            </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add MCQs</button>
    </div>
    </form>
</div>
</div>

<?php
include("./includes/footer.php");
?>