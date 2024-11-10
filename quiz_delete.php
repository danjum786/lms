<?php
include("./includes/db.php");
session_start();

// Check if the user is logged in as admin or instructor
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'instructor')) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot perform this action.'
    ];
    header("Location: login.php");
    exit();
}

// Check if the quiz_id is set
if (isset($_POST['quiz_id'])) {
    $quiz_id = mysqli_real_escape_string($conn, $_POST['quiz_id']);

    // SQL to delete the quiz from the database
    $sql = "DELETE FROM quizzez WHERE id = '$quiz_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['showAlert'] = [
            'color' => '#155724',
            'msg' => 'Quiz deleted successfully.'
        ];
    } else {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Cannot delete this lesson'
        ];
    }
} else {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'Invalid quiz ID.'
    ];
}

header("Location: lesson_manage.php"); // Redirect back to the lesson view
exit();
