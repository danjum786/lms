<?php
include("./includes/header.php");
include("./includes/db.php"); // Ensure to include your database connection file

// Redirect if not logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['showAlert'] = [
        'color' => '#721c24',
        'msg' => 'You cannot access this page'
    ];
    header("Location: login.php");
    exit();
}


// Retrieve users data
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!-- Main Content Area -->
<div class="main-content">
    <h1>Users List</h1>
    <?php $counter = 1;
    if (mysqli_num_rows($result) > 0): ?>

        <table class="data-table" style="overflow-x: auto; min-width:800px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <!-- <td><?php echo $row['user_id']; ?></td> -->
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo ucfirst($row['role']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="edit_user.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-edit">Edit</a>
                            <button class="btn btn-delete" onclick="deleteUser(<?php echo $row['user_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php $counter++;
                endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No users found.</p>
    <?php endif; ?>
</div>

<?php
include("./includes/footer.php");
mysqli_close($conn); // Close database connection
?>