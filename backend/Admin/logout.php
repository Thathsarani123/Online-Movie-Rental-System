<?php
session_start(); // Start the session

// Check if a session is active
if (isset($_SESSION['loggedInUser'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the index.php page outside of the backend/admin folder
    header("Location: ../../index.php");
    exit;
} else {
    // If no session exists, redirect to the index.php page outside of the backend/admin folder
    header("Location: ../../index.php");
    exit;
}
?>
