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
    <h1>Welcome to the Dashboard</h1>
    <p>This is a responsive dashboard layout with a sidebar navigation menu.</p>
</div>





</div>


<?php
include("./includes/footer.php");

?>