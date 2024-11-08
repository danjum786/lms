<?php
include("./includes/functions.php");
checkUser();

?>
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin</h2>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="./index.php"><i class="fas fa-home"></i> Dashboard</a>
        </li>
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <li class="dropdown">
                <a href="#"><i class="fas fa-user"></i> Users <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="./add_user.php">Add User</a></li>
                    <li><a href="./manage_users.php">Manage Users</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#"><i class="fas fa-book"></i> Courses <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Add Course</a></li>
                    <li><a href="#">Manage Courses</a></li>
                </ul>
            </li>
        <?php } elseif ($_SESSION['role'] == 'instructor') { ?>
            <li><a href="#"><i class="fas fa-book"></i> Manage Courses</a></li>
        <?php } ?>
        <li>
            <a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>