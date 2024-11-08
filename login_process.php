<?php
session_start();
include("./includes/db.php"); // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        $_SESSION['showAlert'] = [
            'msg' => 'Input fields cannot be empty',
            'color' => '#721c24'
        ];
        header("Location: login.php");
        exit();
    }

    // Query to find the user with provided credentials
    $query = "SELECT * FROM Users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            $_SESSION['showAlert'] = [
                'msg' => 'Login successful',
                'color' => '#155724'
            ];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['showAlert'] = [
                'msg' => 'Incorrect password',
                'color' => '#721c24'
            ];
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['showAlert'] = [
            'msg' => 'Email does not exist',
            'color' => '#721c24'
        ];
        header("Location: login.php");
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header("Location: login.php");
    exit();
}
