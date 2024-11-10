<?php
include("./includes/db.php");
include("./includes/header.php");

// Check if the user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Get the quiz for the current lesson
if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];

    // Get all quiz data for the lesson
    $quiz_query = "SELECT * FROM quizzez WHERE lesson_id = '$lesson_id'";
    $quiz_result = mysqli_query($conn, $quiz_query);

    if (mysqli_num_rows($quiz_result) == 0) {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'No quizzes available for this lesson'
        ];
        header("Location: lesson_manage.php");
        exit();
    }
}
$student_id = $_SESSION['user_id'];
$lesson_id = $_GET['lesson_id'];

// Check if the student has already taken the quiz for this lesson
$check_attempt_query = "SELECT * FROM quiz_answers WHERE student_id = '$student_id' AND lesson_id = '$lesson_id'";
$check_attempt_result = mysqli_query($conn, $check_attempt_query);

if (mysqli_num_rows($check_attempt_result) > 0) {

    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You have already taken this quiz'
    ];
    header("Location: lesson_manage.php");
    exit();
}
?>

<!-- Quiz Form for Student -->
<form action="quiz_submit.php" method="POST">
    <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>" />

    <?php
    while ($quiz = mysqli_fetch_assoc($quiz_result)) {
        $quiz_data = json_decode($quiz['quiz_data'], true);
        echo '<div class="quiz-question">';
        echo '<h3>' . htmlspecialchars($quiz_data['question']) . '</h3>';
        echo '<ul>';
        foreach ($quiz_data['options'] as $option_num => $option_text) {
            echo "<li><input type='radio' name='answer[{$quiz['id']}]' value='$option_num' /> $option_text</li>";
        }
        echo '</ul><br />';
        echo '</div>';
    }
    ?>
    <button type="submit">Submit Answer</button>
</form>
<?php include("./includes/footer.php"); ?>