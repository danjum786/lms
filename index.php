<?php
include("./includes/header.php");
checkUser();
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
    <h1 style="text-align: center;">Welcome <?php echo ucfirst($_SESSION['user_name']) ?></h1>
    
</div>




<?php
include("./includes/footer.php");

?>