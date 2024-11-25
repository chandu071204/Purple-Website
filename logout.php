<?php
session_start();

// Check if the user is an admin or a regular user
if (isset($_SESSION['admin'])) {
    // If the user is an admin, redirect to index.php
    $redirectTo = 'index.php?message=logged_out';
} elseif (isset($_SESSION['user'])) {
    // If the user is a regular user, redirect to login.php
    $redirectTo = 'login.php?message=logged_out';
} else {
    // If no session found, redirect to login.php (default case)
    $redirectTo = 'login.php';
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

header("Location: $redirectTo");
exit();
?>
