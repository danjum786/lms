<?php
session_start();
include("./includes/functions.php");

// Redirect logged-in users away from the register page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Display alert if a session message exists
if (isset($_SESSION['showAlert'])) {
    showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
    unset($_SESSION['showAlert']); // Clear alert from session
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .form-container {
            width: 500px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .form-container h2 {
            text-align: center;
            padding: 15px 0px;
            font-size: 30px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form class="form" action="register_process.php" method="POST">
            <h2 class="form-title">Student Registration</h2>

            <!-- Full Name Input -->
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-input" required>
            </div>

            <!-- Email Input -->
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <!-- Confirm Password Input -->
            <div class="form-group">
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
            </div>

            <!-- Register Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>

        </form>
    </div>

</body>

</html>