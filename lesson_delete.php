<?php
include("./includes/db.php");
session_start();

// Redirect if not logged in as admin
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

    // Delete lesson from the database
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
