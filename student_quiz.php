<?php
include("./includes/header.php");
include("./includes/db.php");

// Check if student is logged in
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
//     $_SESSION['showAlert'] = [
//         'color' => '#721c24',
//         'msg' => 'You cannot access this page'
//     ];
//     header("Location: login.php");
//     exit();
// }

$lesson_id = $_GET['lesson_id'];
$student_id = $_SESSION['user_id'];

// Fetch the quiz for the lesson
$sql = "SELECT * FROM lesson_quizzes WHERE lesson_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $lesson_id);
mysqli_stmt_execute($stmt);
$quiz = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$quiz) {
    echo "No quiz available for this lesson.";
    exit();
}

// Fetch questions and options for the quiz
$sql = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $quiz['quiz_id']);
mysqli_stmt_execute($stmt);
$questions = mysqli_stmt_get_result($stmt);

?>

<div class="quiz-container">
    <h1>Quiz: <?php echo htmlspecialchars($quiz['title']); ?></h1>
    <form action="quiz_submit.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz['quiz_id']; ?>">

        <?php while ($question = mysqli_fetch_assoc($questions)) : ?>
            <div class="question">
                <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                <?php
                // Fetch options for the current question
                $sql = "SELECT * FROM quiz_options WHERE question_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $question['question_id']);
                mysqli_stmt_execute($stmt);
                $options = mysqli_stmt_get_result($stmt);
                ?>
                <?php while ($option = mysqli_fetch_assoc($options)) : ?>
                    <div class="option">
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="<?php echo $option['option_id']; ?>">
                            <?php echo htmlspecialchars($option['option_text']); ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endwhile; ?>

        <button type="submit">Submit Quiz</button>
    </form>
</div>

<?php include("./includes/footer.php"); ?>