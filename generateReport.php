<?php
include '../connect.php'; // Include database connection

// Fetch all logs from the database
$log_query = "SELECT * FROM log ORDER BY date ASC ";
$result = $conn->query($log_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Audit Logs Data</title>
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

        .logs-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logs-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .logs-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .logs-container th,
        .logs-container td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .logs-container th {
            background-color: #f0f0f0;
        }

        .return-button {
            display: inline-block;
            margin: 20px auto;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }

        .return-button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="staffMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>
    <div class="logs-container">
        <h2>Audit Logs Data</h2>
        <table>
            <tr>
                <th>Log_ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Event</th>
                <th>Date</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($log = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $log['log_id'] ?></td>
                        <td><?= htmlspecialchars($log['username']) ?></td>
                        <td><?= htmlspecialchars($log['role']) ?></td>
                        <td><?= htmlspecialchars($log['event']) ?></td>
                        <td><?= $log['date'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No logs available</td>
                </tr>
            <?php endif; ?>
        </table>
        <a href="staffMenu.php">
            <button class="return-button">Return</button>
        </a>
    </div>
</body>

</html>