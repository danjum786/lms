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

// Fetch quizzes related to this lesson
$quiz_sql = "SELECT id, quiz_data FROM quizzez WHERE lesson_id = '$lesson_id'";
$quiz_result = mysqli_query($conn, $quiz_sql);
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

    <!-- Display Quizzes -->
    <div class="lesson-details">
        <div class="quizzes">
            <!-- Display the link or button to access the quiz -->
            <div style="display:flex; justify-content:space-between; padding: 10px;">
                <h3>Quizzes for this Lesson</h3>
                <a class="btn btn-edit" style="text-decoration: none;" href="quiz_paper.php?lesson_id=<?php echo $lesson_id; ?>">Take Quiz</a>
            </div>
            <?php
            if (mysqli_num_rows($quiz_result) > 0) {
                while ($quiz = mysqli_fetch_assoc($quiz_result)) {
                    $quiz_data = json_decode($quiz['quiz_data'], true);
            ?>
                    <div class="quiz">
                        <h4><?php echo htmlspecialchars($quiz_data['question']); ?></h4>
                        <ul class="quiz-options">
                            <?php
                            foreach ($quiz_data['options'] as $option_num => $option_text) {
                                echo "<li class='quiz-option'>Option $option_num: " . htmlspecialchars($option_text) . "</li>";
                            }
                            ?>
                        </ul>
                        <?php if ($_SESSION['role'] !== "student"): ?>
                            <!-- Only show the correct option to admins or instructors -->
                            <p><strong>Correct Option:</strong> Option <?php echo $quiz_data['correct_option']; ?></p>
                        <?php endif; ?>
                        <?php if ($_SESSION['role'] !== "student"): ?>
                            <!-- Delete Button -->
                            <form method="POST" action="quiz_delete.php" style="margin-top:20px;">
                                <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>" />
                                <button style="background-color: red; max-width: fit-content;" type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        <?php endif ?>
                    </div>
            <?php
                }
            } else {
                echo "<p>No quizzes available for this lesson.</p>";
            }
            ?>
        </div>

    </div>

</div>

<?php include("./includes/footer.php"); ?>