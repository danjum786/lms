<?php
include("./includes/header.php");
include './includes/db.php'; // Database connection
checkUser();

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Check for duplicate email
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Email already exists
        $_SESSION['showAlert'] = [
            'msg' => 'Email already exists. Please use a different email.',
            'color' => 'red'
        ];
    } else {
        // Insert user data into the database if no duplicate is found
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['showAlert'] = [
                'msg' => 'Registration successful!',
                'color' => '#155724'
            ];
        } else {
            $_SESSION['showAlert'] = [
                'msg' => 'Something went wrong! Please try again.',
                'color' => '#721c24'
            ];
        }
    }
}

?>
<?php
// Display the alert if a session message exists
if (isset($_SESSION['showAlert'])) {
    showAlert($_SESSION['showAlert']['color'], $_SESSION['showAlert']['msg']);
    unset($_SESSION['showAlert']); // Clear the alert from the session
}
?>
<!-- Main Content Area -->
<div class="main-content">
    <div class="form-container">
        <h1>Add New User</h1>


        <form action="add_user.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="instructor">Instructor</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <button type="submit">Add User</button>
        </form>

    </div>
</div>



<?php
include("./includes/footer.php");
mysqli_close($conn);
?>