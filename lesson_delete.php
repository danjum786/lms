<?php
include("./includes/db.php");
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Check if the lesson ID is provided
if (isset($_GET['id'])) {
    $lesson_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Check if there are any quizzes in this lesson
    $check_quizzes_query = "SELECT * FROM quizzez WHERE lesson_id = '$lesson_id'";
    $check_quizzes_result = mysqli_query($conn, $check_quizzes_query);

    if (mysqli_num_rows($check_quizzes_result) > 0) {
        // If quizzes are present in the lesson, prevent deletion
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'This lesson cannot be deleted because it contains quizzes.'
        ];
        header("Location: lesson_manage.php");
        exit();
    }

    // If no quizzes are present, delete the lesson
    $sql = "DELETE FROM lessons WHERE lesson_id = '$lesson_id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['showAlert'] = [
            'color' => '#155724',
            'msg' => 'Lesson deleted successfully.'
        ];
    } else {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Error deleting lesson: ' . mysqli_error($conn)
        ];
    }
}

// Redirect back to manage lessons page
header("Location: lesson_manage.php");
exit();
