<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="dashboard">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="logo">
                <a href="./index.php">My Logo</a>
            </div>
            <button id="sidebar-toggle" class="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <!-- Sidebar -->
        <?php include './includes/sidebar.php'; ?>