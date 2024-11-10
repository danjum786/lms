<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin and instructor
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'instructor')) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}
?>
<!-- Main Content Area -->
<div class="main-content">
    <?php
    $lesson_id  = '';
    // Retrieve lesson ID from URL
    if (isset($_GET['lesson_id'])) {
        $lesson_id = $_GET['lesson_id']; // The lesson for which MCQs are being added
    } else {
        echo "Lesson ID is missing!";
        exit();
    }
    // Fetch lessons from the database
    $sql = "SELECT lesson_id, title FROM lessons WHERE lesson_id = $lesson_id"; // Adjust the table name and column names if needed
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $conn->close();
    ?>

    <div class="form-container">
        <h1>Create MCQ for Lesson: <?php echo $row['title']; ?></h1>
        <form action="quiz_add_process.php?lesson_id=<?php echo $row['lesson_id']; ?>" method="POST">
            <div class="form-group">
                <label for="quiz_question">Quiz Question</label>
                <textarea name="quiz_question" id="quiz_question" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="option_1">Option 1</label>
                <input type="text" name="option_1" id="option_1" required>
            </div>

            <div class="form-group">
                <label for="option_2">Option 2</label>
                <input type="text" name="option_2" id="option_2" required>
            </div>

            <div class="form-group">
                <label for="option_3">Option 3</label>
                <input type="text" name="option_3" id="option_3" required>
            </div>

            <div class="form-group">
                <label for="option_4">Option 4</label>
                <input type="text" name="option_4" id="option_4" required>
            </div>

            <div class="form-group">
                <label for="correct_option">Correct Option</label>
                <select name="correct_option" id="correct_option" required>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>

            <button type="submit">Create Quiz</button>
        </form>
    </div>


</div>




<?php
include("./includes/footer.php");

?>