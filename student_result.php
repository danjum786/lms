<?php
include("./includes/header.php");
include("./includes/db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Check if the user_id is provided in the URL
if (!isset($_GET['user_id'])) {
    header("Location: student_manage.php"); // Redirect if no user_id is provided
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

// Fetch all quizzes and their results grouped by lesson
$sql = "SELECT l.lesson_id, l.title, 
                q.id AS quiz_id, q.quiz_data,
                qa.answer AS student_answer, 
                q.lesson_id AS quiz_lesson_id
        FROM quizzez q
        JOIN lessons l ON l.lesson_id = q.lesson_id
        LEFT JOIN quiz_answers qa ON qa.quiz_id = q.id AND qa.student_id = '$user_id'";

$result = mysqli_query($conn, $sql);

// Initialize array to hold lessons and quiz results
$lessons = [];

while ($row = mysqli_fetch_assoc($result)) {
    $lesson_id = $row['lesson_id'];

    // Initialize lesson if not already in the array
    if (!isset($lessons[$lesson_id])) {
        $lessons[$lesson_id] = [
            'title' => $row['title'],
            'total_marks' => 0,
            'obtained_marks' => 0,
            'quizzes' => []
        ];
    }

    // Decode the quiz data (assuming JSON format for question options)
    $quiz_data = json_decode($row['quiz_data'], true);
    $student_answer = isset($row['student_answer']) ? $row['student_answer'] : null;
    $correct_option = isset($quiz_data['correct_option']) ? $quiz_data['correct_option'] : null;
    $is_correct = ($student_answer == $correct_option) ? 1 : 0;

    // Add quiz results to the lesson
    $lessons[$lesson_id]['quizzes'][] = [
        'quiz_id' => $row['quiz_id'],
        'quiz_data' => $quiz_data,
        'student_answer' => $student_answer,
        'correct_option' => $correct_option,
        'is_correct' => $is_correct
    ];

    // Update total marks and obtained marks for the lesson
    $lessons[$lesson_id]['total_marks']++;
    $lessons[$lesson_id]['obtained_marks'] += $is_correct;
}
?>

<div class="main-content">
    <h2>Student Results</h2>

    <?php
    if (!empty($lessons)) {
        foreach ($lessons as $lesson_id => $lesson) {
    ?>
            <div class="lesson-result">
                <h3><?php echo htmlspecialchars($lesson['title']); ?></h3>
                <p><strong>Total Marks:</strong> <?php echo $lesson['total_marks']; ?></p>
                <p><strong>Marks Obtained:</strong> <?php echo $lesson['obtained_marks']; ?></p>

                <div class="quizzes">
                    <?php
                    foreach ($lesson['quizzes'] as $quiz) {
                    ?>
                        <div class="quiz">
                            <h4><?php echo htmlspecialchars($quiz['quiz_data']['question']); ?></h4>
                            <ul class="quiz-options">
                                <?php
                                foreach ($quiz['quiz_data']['options'] as $option_num => $option_text) {
                                    $selected = ($quiz['student_answer'] == $option_num) ? 'checked' : '';
                                    echo "<li class='quiz-option'>Option $option_num: " . htmlspecialchars($option_text) . " $selected</li>";
                                }
                                ?>
                            </ul>
                            <p><strong>Your Answer:</strong> Option <?php echo $quiz['student_answer']; ?></p>
                            <p><strong>Correct Answer:</strong> Option <?php echo $quiz['correct_option']; ?></p>
                            <p><strong>Result:</strong> <?php echo $quiz['is_correct'] ? 'Correct' : 'Incorrect'; ?></p>
                        </div>
                        <hr>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <br><br>
    <?php
        }
    } else {
        echo "<p class='no-data'>No quiz results found for this student.</p>";
    }
    ?>
</div>

<?php include("./includes/footer.php"); ?>