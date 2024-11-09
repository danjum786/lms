<?php
session_start();
include("./includes/functions.php");
include("./includes/db.php");

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $created_by = $_SESSION['user_id']; // Assuming the admin is logged in and their ID is stored in session

    // Insert course into the database
    $sql = "INSERT INTO courses (title, created_by) VALUES ('$title', '$created_by')";
    if (mysqli_query($conn, $sql)) {
        // Set success message
        $_SESSION['showAlert'] = [
            'color' => '#155724',
            'msg' => 'Course added successfully!'
        ];
    } else {
        // Set error message
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Error adding course. Please try again.'
        ];
    }

    // Redirect to the add course page
    header("Location: course_add.php");
    exit();
}
