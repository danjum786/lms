<?php
session_start();
include("./includes/functions.php");

// Redirect logged-in users away from the login page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Display the alert if a session message exists
if (isset($_SESSION['showAlert'])) {
    showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
    unset($_SESSION['showAlert']); // Clear the alert from the session
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <form class="form" action="login_process.php" method="POST">
            <h2 class="form-title">Login</h2>

            <!-- Username Input -->
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <!-- Login Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

        </form>
    </div>


</body>

</html>