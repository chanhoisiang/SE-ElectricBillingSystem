<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $address = $_POST['address'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    $role = $_POST['role'];

    $checkUsername = "SELECT * FROM users WHERE username='$username'";
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $resultUsername = $conn->query($checkUsername);
    $resultEmail = $conn->query($checkEmail);

    if ($resultUsername->num_rows > 0) {
        echo "<script>alert('Username Already Exists!');</script>";
    } else {
        if ($resultEmail->num_rows > 0) {
            echo "<script>alert('Email Address Already Exists!');</script>";
        } else {
            $insertQuery = "INSERT INTO users(name, username, address, phonenumber, email, password, role)
                            VALUES ('$name','$username','$address','$phonenumber','$email','$password','$role')";
            if ($conn->query($insertQuery) === TRUE) {
                // ✅ Log successful account creation
                $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
                $log_stmt = $conn->prepare($log_sql);
                $event = "Created new account (Username: $username, Role: $role)";
                $log_stmt->bind_param("sss", $username, $role, $event);
                $log_stmt->execute();

                echo "<script>alert('User added successfully!'); window.location.href='createAccount.php';</script>";
            } else {
                // ✅ Log error if account creation fails
                $error_message = $conn->error;
                $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
                $log_stmt = $conn->prepare($log_sql);
                $event = "Failed to create account (Username: $username, Role: $role) - Error: $error_message";
                $log_stmt->bind_param("sss", $username, $role, $event);
                $log_stmt->execute();

                echo "<script>alert('Error: $error_message');</script>";
            }
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Create Account</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #b0b0b0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .logo {
            width: 50px;
            height: 50px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .header h1 {
            margin: 0;
            color: white;
            font-size: 24px;
        }

        .form-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .form-container label {
            flex: 1 1 calc(50% - 20px);
            text-align: left;
            font-weight: bold;
        }

        .form-container input {
            flex: 1 1 calc(50% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="adminMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>
    <div class="form-container">
        <h2>Create Account</h2>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter name" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter address" required>
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phonenumber" name="phonenumber" placeholder="Enter phone number" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
                <option value="technician">Technician</option>
            </select>
            <button type="submit">Create Account</button>
        </form>
    </div>
</body>

</html>