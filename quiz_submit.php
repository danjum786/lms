<?php
include("./includes/db.php");
session_start();

// Check if the student is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You need to log in as a student to submit this quiz.'
    ];
    header("Location: login.php");
    exit();
}

// Get the student's ID and lesson ID
$student_id = $_SESSION['user_id'];
$lesson_id = $_POST['lesson_id'];
$answers = $_POST['answer']; // Array of answers indexed by quiz_id

// Prepare to insert answers into the database
foreach ($answers as $quiz_id => $answer) {
    $quiz_id = intval($quiz_id);
    $answer = intval($answer);

    // Insert each answer
    $insert_query = "INSERT INTO quiz_answers (student_id, quiz_id, question_id, answer, lesson_id) 
                     VALUES ('$student_id', '$quiz_id', '$quiz_id', '$answer', '$lesson_id')";
    $insert_result = mysqli_query($conn, $insert_query);

    if (!$insert_result) {
        echo "Failed to save answer for quiz ID $quiz_id. Please try again.";
        exit();
    }
}

$_SESSION['showAlert'] = [
    'color' => '#155724',
    'msg' => 'Quiz submitted successfully.'
];
header("Location: lesson_manage.php");
exit();
