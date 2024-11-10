<?php
include("./includes/header.php");
include("./includes/db.php");

// Check if the student is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You need to log in as a student to view results.'
    ];
    header("Location: login.php");
    exit();
}

// Get student ID and lesson ID
$student_id = $_SESSION['user_id'];
$lesson_id = $_GET['lesson_id'];

// Fetch student's answers
$answers_query = "SELECT * FROM quiz_answers WHERE student_id = '$student_id' AND lesson_id = '$lesson_id'";
$answers_result = mysqli_query($conn, $answers_query);

if (mysqli_num_rows($answers_result) == 0) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'No result available for this quiz'
    ];
    header("Location: lesson_manage.php");
    exit();
}

// Initialize score variables
$total_questions = 0;
$correct_answers = 0;

?>

<div class="main-content">
    <h1>Quiz Results</h1>;
    <?php
    echo "<ul>";

    while ($answer_row = mysqli_fetch_assoc($answers_result)) {
        $quiz_id = $answer_row['quiz_id'];
        $student_answer = $answer_row['answer'];

        // Fetch correct answer from quiz data
        $quiz_query = "SELECT quiz_data FROM quizzez WHERE id = '$quiz_id'";
        $quiz_result = mysqli_query($conn, $quiz_query);
        $quiz_data = mysqli_fetch_assoc($quiz_result);
        $quiz_data = json_decode($quiz_data['quiz_data'], true);

        $correct_answer = $quiz_data['correct_option']; // Assuming 'correct_option' holds the correct answer

        // Display question and answer
        echo "<li>";
        echo "<strong>Question:</strong> " . htmlspecialchars($quiz_data['question']) . "<br>";
        echo "<strong>Your Answer:</strong> " . htmlspecialchars($quiz_data['options'][$student_answer]) . "<br>";
        echo "<strong>Correct Answer:</strong> " . htmlspecialchars($quiz_data['options'][$correct_answer]) . "<br>";

        // Check if the student's answer is correct
        if ($student_answer == $correct_answer) {
            echo "<span style='color: green;'>Correct</span>";
            $correct_answers++;
        } else {
            echo "<span style='color: red;'>Incorrect</span>";
        }
        echo "</li><br>";

        $total_questions++;
    }

    echo "</ul>";

    // Display final score
    echo "<h3>Total Score: $correct_answers / $total_questions</h3>";
    ?>
</div>

<?php include("./includes/footer.php"); ?>