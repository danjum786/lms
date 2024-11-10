<?php
session_start();
include("./includes/db.php");

// Redirect if not logged in as admin and instructor
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'instructor')) {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}

// Get lesson ID from URL
if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];
    // echo $lesson_id;
} else {
    echo "Lesson ID is missing!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form inputs
    $lesson_id = intval($_GET['lesson_id']);
    $quiz_question = $conn->real_escape_string(trim($_POST['quiz_question']));
    $option_1 = $conn->real_escape_string(trim($_POST['option_1']));
    $option_2 = $conn->real_escape_string(trim($_POST['option_2']));
    $option_3 = $conn->real_escape_string(trim($_POST['option_3']));
    $option_4 = $conn->real_escape_string(trim($_POST['option_4']));
    $correct_option = intval($_POST['correct_option']);

    // Validate if all fields are provided
    if (empty($quiz_question) || empty($option_1) || empty($option_2) || empty($option_3) || empty($option_4) || empty($correct_option)) {
        die('All fields are required.');
    }

    // Prepare quiz data in JSON format
    $quiz_data = json_encode([
        'question' => $quiz_question,
        'options' => [
            1 => $option_1,
            2 => $option_2,
            3 => $option_3,
            4 => $option_4
        ],
        'correct_option' => $correct_option
    ]);

    // SQL to insert the quiz into the database
    $sql = "INSERT INTO quizzez (lesson_id, quiz_data) VALUES (?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('is', $lesson_id, $quiz_data);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['showAlert'] = [
                'color' => '#155724',
                'msg' => 'MCQs added successfully.'
            ];
            header("Location: lesson_manage.php");
            exit();
        } else {
            $_SESSION['showAlert'] = [
                'color' => '#721c24',
                'msg' => 'Error adding quiz: ' . mysqli_error($conn)
            ];
            // header("Location: quiz_add.php?lesson_id=$lesson_id");
            header("Location: quiz_addd.php?lesson_id=$lesson_id");
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
