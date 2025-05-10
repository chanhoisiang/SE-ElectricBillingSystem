<?php
include '../connect.php';

// Fetch user data from the database
$sql = "SELECT id, username, role FROM users";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user-id'];
    $newRole = $_POST['new-role'];

    // Validate input
    if (!is_numeric($userId) || empty($newRole)) {
        echo "<script>alert('Invalid input. Please try again!'); window.history.back();</script>";
        exit();
    }

    // Start transaction for safety
    $conn->begin_transaction();

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);

    if ($stmt->execute()) {
        // Log the role change
        $logStmt = $conn->prepare("INSERT INTO log (username, event, date) VALUES (?, ?, NOW())");
        $event = "Updated user ID $userId to role: $newRole";
        $adminUser = "Admin"; // Change to dynamic session username if available
        $logStmt->bind_param("ss", $adminUser, $event);
        $logStmt->execute();
        $logStmt->close();

        $conn->commit(); // Commit the transaction
        echo "<script>alert('User role updated successfully!'); window.location.href='managePermission.php';</script>";
    } else {
        $conn->rollback(); // Rollback if there's an error
        echo "<script>alert('Error updating role: " . $conn->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Manage Permission</title>
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
        <h2>Manage Permissions</h2>
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
            <h2>Update User Role</h2>
            <form method="POST" action="">
                <label for="user-id">Enter User ID:</label>
                <input type="text" id="user-id" name="user-id" placeholder="Enter User ID" required>

                <label for="new-role">Select New Role:</label>
                <select id="new-role" name="new-role" required>
                    <option value="">Select Role</option>
                    <option value="customer">Customer</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                    <option value="technician">Technician</option>
                </select>

                <button type="submit">Update Role</button>
            </form>
        </div>
    </div>
</body>

</html>