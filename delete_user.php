<?php
include("./includes/db.php");
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot perform this action'
    ];
    header("Location: login.php");
    exit();
}

if (isset($_POST['user_id'])) {
    $user_id = (int) $_POST['user_id'];

    // Delete user from database
    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "success";
    } else {
        echo "error";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
