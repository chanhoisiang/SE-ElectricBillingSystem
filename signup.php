<?php
include 'connect.php';

$name = $_POST['name'];
$username = $_POST['username'];
$address = $_POST['address'];
$phonenumber = $_POST['phonenumber'];
$email = $_POST['email'];
$password = $_POST['password'];
$password = md5($password);

$checkUsername = "SELECT * FROM users WHERE username='$username'";
$checkEmail = "SELECT * FROM users WHERE email='$email'";
$resultUsername = $conn->query($checkUsername);
$resultEmail = $conn->query($checkEmail);

if ($resultUsername->num_rows > 0) {
    echo "<script>alert('Username Already Exists !'); window.history.back();</script>";
} else {
    if ($resultEmail->num_rows > 0) {
        echo "<script>alert('Email Address Already Exists !'); window.history.back();</script>";
    } else {
        $insertQuery = "INSERT INTO users(name, username, address, phonenumber, email, password, role)
                       VALUES ('$name','$username','$address','$phonenumber','$email','$password', 'customer')";
        if ($conn->query($insertQuery) === TRUE) {
            // Log the event
            $event = "User Sign Up";
            $role = "customer";
            $logQuery = "INSERT INTO log (username, role, event) VALUES ('$username', '$role', '$event')";
            $conn->query($logQuery);
            echo "<script>alert('Sign Up Successful'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
    }
}
