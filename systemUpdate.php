<?php
session_start();
include '../connect.php'; // Include database connection

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch the latest system update
$sql = "SELECT * FROM system_updates ORDER BY update_date DESC LIMIT 1";
$result = $conn->query($sql);
$update = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_version = $_POST['current_version'];
    $new_status = $_POST['status'];
    $new_features = $_POST['features'];

    // Update the system version and features
    $stmt = $conn->prepare("INSERT INTO system_updates (version, status, features) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $new_version, $new_status, $new_features);

    if ($stmt->execute()) {
        $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $event = "System Updated";
        $log_stmt->bind_param("sss", $_SESSION['username'], $_SESSION['role'], $event);
        $log_stmt->execute();
        echo "<script>alert('System updated successfully!'); window.location.href='systemUpdate.php';</script>";
    } else {
        echo "<script>alert('Error updating system: " . $conn->error . "');</script>";
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
    <title>Electric Billing System - Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #b0b0b0;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin: 30px auto;
            width: 90%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-container textarea {
            resize: vertical;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
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

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="technicianMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>

    <div class="form-container">
        <h2>System Update</h2>
        <form method="POST" action="">
            <label for="current-version">Current Version:</label>
            <input type="text" id="current-version" name="current_version" value="<?= $update['version'] ?? 'N/A'; ?>" required>

            <label for="status">Status:</label>
            <input type="text" id="status" name="status" value="<?= $update['status'] ?? 'Pending'; ?>" required>

            <label for="features">Add New Features:</label>
            <textarea id="features" name="features" rows="5"><?= $update['features'] ?? ''; ?></textarea>

            <button type="submit">Update Now</button>
        </form>
    </div>
</body>

</html>