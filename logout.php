<?php
include 'connect.php';
session_start();

// Get the current user's details before destroying the session
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];

    // Insert logout event into log table
    $log_sql = "INSERT INTO log (username, role, event) VALUES (?, ?, 'User logged out')";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("ss", $username, $role);
    $log_stmt->execute();
    $log_stmt->close();
}

// Destroy session and redirect to login page
session_destroy();
header("Location: index.php");
exit();
