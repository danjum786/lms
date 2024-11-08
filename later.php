<?php
session_start();
$role = $_SESSION['role']; // Assume role is stored in session after login
?>
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Dashboard</h2>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
        <?php if ($role == 'admin') { ?>
            <li class="dropdown">
                <a href="#"><i class="fas fa-user"></i> Users <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Add User</a></li>
                    <li><a href="#">Manage Users</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#"><i class="fas fa-book"></i> Courses <i class="fas fa-caret-down caret"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Add Course</a></li>
                    <li><a href="#">Manage Courses</a></li>
                </ul>
            </li>
        <?php } elseif ($role == 'instructor') { ?>
            <li><a href="#"><i class="fas fa-book"></i> Manage Courses</a></li>
        <?php } ?>
        <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>