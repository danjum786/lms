<?php
include("./includes/functions.php");
checkUser();
?>
<div class="sidebar">
    <div class="sidebar-header">
        <h2><?php echo ucfirst($_SESSION['role']); ?></h2>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="./index.php"><i class="fas fa-home"></i> Dashboard</a>
        </li>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <!-- Admin: Show Users section -->
            <li class="dropdown">
                <a href="#"><i class="fas fa-user"></i> Users <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="./add_user.php">Add User</a></li>
                    <li><a href="./manage_users.php">Manage Users</a></li>
                </ul>
            </li>
            <!-- Admin: Show Courses section -->
            <li class="dropdown">
                <a href="#"><i class="fas fa-book"></i> Courses <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="./course_add.php">Add Course</a></li>
                    <li><a href="course_manage.php">Manage Courses</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#"><i class="fas fa-book"></i> Lessons <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="./lesson_add.php">Add Lesson</a></li>
                    <li><a href="lesson_manage.php">Manage Lessons</a></li>
                </ul>
            </li>
            <!-- Admin: Show Students section -->
            <li>
                <a href="#"><i class="fas fa-book"></i> Students</a>
            </li>
        <?php } elseif ($_SESSION['role'] == 'instructor') { ?>
            <!-- Instructor: Show Courses section -->
            <li class="dropdown">
                <a href="#"><i class="fas fa-book"></i> Courses <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="./course_add.php">Add Course</a></li>
                    <li><a href="#">Manage Courses</a></li>
                </ul>
            </li>
            <!-- Instructor: Show Students section -->
            <li>
                <a href="#"><i class="fas fa-book"></i> Students</a>
            </li>
        <?php } elseif ($_SESSION['role'] == 'student') { ?>
            <!-- Student: Show Students section only -->
            <li>
                <a href="#"><i class="fas fa-book"></i> Students</a>
            </li>
        <?php } ?>

        <li>
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>