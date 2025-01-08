<?php
// Start session
session_start();

// Destroy the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page or homepage
header("Location: login.php"); // Change "login.php" to the page you want to redirect to
exit();
?>
