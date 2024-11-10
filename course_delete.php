<?php
session_start();
include("./includes/db.php");
include("./includes/functions.php");

// Ensure user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if course ID is provided
if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);

    // Prepare and execute the delete query
    $sql = "DELETE FROM courses WHERE course_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $course_id);
    $success = mysqli_stmt_execute($stmt);

    // Check if deletion was successful
    if ($success) {
        $_SESSION['showAlert'] = ['color' => '#155724', 'msg' => 'Course deleted successfully.'];
    } else {
        $_SESSION['showAlert'] = ['color' => '#721c24', 'msg' => 'Failed to delete course.'];
    }

    mysqli_stmt_close($stmt);
} else {
    $_SESSION['showAlert'] = ['color' => '#721c24', 'msg' => 'Invalid course ID.'];
}

// Redirect back to the courses listing page
header("Location: course_manage.php");
exit();
