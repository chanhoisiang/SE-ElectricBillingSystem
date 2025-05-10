<?php
include '../connect.php';

$sql = "SELECT id, username, role FROM users";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username to delete the account
    $username = $_POST['username'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role = $row['role'];

        // Delete the user account
        $deleteStmt = $conn->prepare("DELETE FROM users WHERE username = ?");
        $deleteStmt->bind_param("s", $username);

        if ($deleteStmt->execute()) {
            // ✅ Log successful account deletion
            $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $event = "Deleted account (Username: $username, Role: $role)";
            $log_stmt->bind_param("sss", $username, $role, $event);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('Account successfully deleted!');window.location.href='deleteAccount.php';</script>";
        } else {
            // ✅ Log error if deletion fails
            $error_message = $conn->error;
            $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $event = "Failed to delete account (Username: $username, Role: $role) - Error: $error_message";
            $log_stmt->bind_param("sss", $username, $role, $event);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('Error deleting account: $error_message');window.location.href='deleteAccount.php';</script>";
        }
        $deleteStmt->close();
    } else {
        echo "<script>alert('Username does not exist!');window.location.href='deleteAccount.php';</script>";
    }

    // Close the prepared statements
    $stmt->close();
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Delete Account</title>
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

        .content-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .content-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .content-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-container table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #e0e0e0;
        }

        .content-container button {
            width: 120px;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .content-container button:hover {
            background-color: #0056b3;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
        }

        a {
            text-decoration: none;
        }

        .update-form {
            margin-top: 20px;
            text-align: left;
        }

        .update-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .update-form input,
        .update-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .update-form button {
            width: 120px;
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-form button:hover {
            background-color: #218838;
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
    <div class="content-container">
        <h2>Delete User Account</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['role']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }
            ?>
        </table>

        <!-- Form to Update User Role -->
        <div class="update-form">
            <h2>Delete User Account</h2>
            <form method="POST" action="">
                <label for="name">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required>
                <button type="submit">Delete</button>
            </form>
        </div>
    </div>
</body>

</html>