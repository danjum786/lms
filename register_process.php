<?php
session_start();
include("./includes/functions.php");  // Assuming this file has database connection and utility functions

// Database connection
include("./includes/db.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Passwords do not match.'
        ];
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $email_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $email_check_query);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Email already exists.'
        ];
        header("Location: register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_query = "INSERT INTO users (name, email, password, role) VALUES ('$full_name', '$email', '$hashed_password', 'student')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['showAlert'] = [
            'color' => '#155724',
            'msg' => 'Registration successful! You can now login.'
        ];
        header("Location: login.php");
    } else {
        $_SESSION['showAlert'] = [
            'color' => '#721c24',
            'msg' => 'Error during registration. Please try again.'
        ];
        header("Location: register.php");
    }
    exit();
} else {
    // Redirect to registration page if accessed directly
    header("Location: register.php");
    exit();
}

mysqli_close($conn); // Close database connection
