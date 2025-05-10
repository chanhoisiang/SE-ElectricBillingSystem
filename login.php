<?php
include 'connect.php';

$username = $_POST['username'];
$password = $_POST['password'];
$password = md5($password);

$sql = "SELECT * FROM users WHERE username='$username' and password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    // Insert login event into log table
    $log_sql = "INSERT INTO log (username, role, event) VALUES (?, ?, 'User logged in')";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("ss", $row['username'], $row['role']);
    $log_stmt->execute();
    $log_stmt->close();

    // Redirect based on role
    if ($row['role'] === 'admin') {
        header("Location: admin/adminMenu.php");
    } elseif ($row['role'] === 'customer') {
        header("Location: customer/customerMenu.php");
    } elseif ($row['role'] === 'staff') {
        header("Location: staff/staffMenu.php");
    } elseif ($row['role'] === 'technician') {
        header("Location: technician/technicianMenu.php");
    } else {
        echo "<script>alert('Invalid role assigned to the user.'); window.history.back();</script>";
    }
    exit();
} else {
    echo "<script>alert('Not Found, Incorrect Username or Password'); window.history.back();</script>";
}
