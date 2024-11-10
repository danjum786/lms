<?php
include("./includes/header.php");
include("./includes/db.php");

// Redirect if not logged in as admin or student
if (!isset($_SESSION['user_id'])) {
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

// Fetch the lesson data from the database
$sql = "SELECT lessons.lesson_id, lessons.title, lessons.content, courses.title AS course_title, lessons.created_at 
        FROM lessons 
        JOIN courses ON lessons.course_id = courses.course_id 
        WHERE lessons.lesson_id = '$lesson_id'";
$result = mysqli_query($conn, $sql);
$lesson = mysqli_fetch_assoc($result);

if (!$lesson) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'Lesson not found.'
    ];
    header("Location: lesson_manage.php");
    exit();
}
?>

<div class="main-content">

    <!-- Display alert if available -->
    <?php
    if (isset($_SESSION['showAlert'])) {
        showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
        unset($_SESSION['showAlert']);
    }
    ?>

    <!-- Lesson Details -->
    <div class="lesson-details">
        <h2><?php echo htmlspecialchars($lesson['title']); ?></h2>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($lesson['course_title']); ?></p>
        <p><strong>Created At:</strong> <?php echo date('Y-m-d', strtotime($lesson['created_at'])); ?></p>
        <div class="lesson-content">
            <p><?php echo nl2br(htmlspecialchars($lesson['content'])); ?></p>
        </div>
    </div>

    <!-- Display Quizzes for this Lesson -->
    <div class="lesson-quizzes">
        <h3>Quizzes for this Lesson</h3>

        <?php
        // Fetch quizzes associated with this lesson
        $sql_quizzes = "SELECT * FROM lesson_quizzes WHERE lesson_id = '$lesson_id'";
        $quizzes_result = mysqli_query($conn, $sql_quizzes);

        $counter = 1;
        if (mysqli_num_rows($quizzes_result) > 0) {
            while ($quiz = mysqli_fetch_assoc($quizzes_result)) {
                echo "<div class='quiz'>";

                // Fetch questions for this quiz
                $quiz_id = $quiz['quiz_id'];
                $sql_questions = "SELECT * FROM quiz_questions WHERE lesson_quiz_id = '$quiz_id'";
                $questions_result = mysqli_query($conn, $sql_questions);

                if (mysqli_num_rows($questions_result) > 0) {
                    while ($question = mysqli_fetch_assoc($questions_result)) {
                        echo "<div class='question'>";
                        echo "<p><strong>$counter Question:</strong> " . htmlspecialchars($question['question_text']) . "</p>";

                        // Fetch options for this question
                        $question_id = $question['question_id'];
                        $sql_options = "SELECT * FROM quiz_options WHERE question_id = '$question_id'";
                        $options_result = mysqli_query($conn, $sql_options);

                        echo "<ul class='quiz-list'>";
                        while ($option = mysqli_fetch_assoc($options_result)) {
                            $is_correct = $option['is_correct'] ? ' (Correct)' : '';
                            echo "<li class='quiz-item'>" . htmlspecialchars($option['option_text']) . $is_correct . "</li>";
                        }
                        echo "</ul>";
                        // echo <<<HTML
                        //         <button class="btn btn-delete" style="margin:20px 0px;" onclick="deleteQuiz({$quiz['quiz_id']})">Delete Quiz</button>
                        //         HTML;
                        echo "</div>"; // end question
                        $counter++;
                    }
                } else {
                    echo "<p>No questions available for this quiz.</p>";
                }
                echo "</div>"; // end quiz
            }
        } else {
            echo "<p>No quizzes available for this lesson.</p>";
        }
        ?>


    </div>
</div>

<?php include("./includes/footer.php"); ?>