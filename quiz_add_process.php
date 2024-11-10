<?php
session_start();
include("./includes/db.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get lesson ID from URL
if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];
} else {
    echo "Lesson ID is missing!";
    exit();
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['questions'])) {
    $questions = $_POST['questions'];
    $quiz_title = "Quiz for Lesson " . $lesson_id;  // Optional title, adjust as needed

    // Insert a new quiz record in lesson_quizzes table
    $sql_quiz = "INSERT INTO lesson_quizzes (lesson_id, title, created_at, updated_at) 
                 VALUES ('$lesson_id', '$quiz_title', NOW(), NOW())";

    if (mysqli_query($conn, $sql_quiz)) {
        $quiz_id = mysqli_insert_id($conn);  // Get the inserted quiz ID

        // Loop through each question and insert it into quiz_questions
        foreach ($questions as $question_data) {
            $question_text = mysqli_real_escape_string($conn, $question_data['question']);

            // Insert question into quiz_questions table
            $sql_question = "INSERT INTO quiz_questions (lesson_quiz_id, question_text, created_at, updated_at) 
                             VALUES ('$quiz_id', '$question_text', NOW(), NOW())";

            if (mysqli_query($conn, $sql_question)) {
                $question_id = mysqli_insert_id($conn);  // Get the inserted question ID

                // Insert options for each question
                foreach ($question_data['options'] as $index => $option_text) {
                    $option_text = mysqli_real_escape_string($conn, $option_text);
                    $is_correct = ($index == $question_data['correct']) ? 1 : 0;  // Set correct answer

                    $sql_option = "INSERT INTO quiz_options (question_id, option_text, is_correct) 
                                   VALUES ('$question_id', '$option_text', '$is_correct')";
                    mysqli_query($conn, $sql_option);
                }
            }
        }

        $_SESSION['showAlert'] = [
            'color' => '#155724',
            'msg' => 'MCQs added successfully.'
        ];
        header("Location: lesson_view.php?lesson_id=$lesson_id");
        exit();
    } else {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Error adding quiz: ' . mysqli_error($conn)
        ];
        // header("Location: quiz_add.php?lesson_id=$lesson_id");
        header("Location: quiz_add.php?lesson_id=$lesson_id");
        exit();
    }
} else {
    echo "No questions submitted!";
}
