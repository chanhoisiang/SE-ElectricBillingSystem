<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM USERS WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$customer_id = $row['id'];

$sql = "SELECT inquiry_id, inquiry_type, description, status, submission_date FROM inquiry WHERE customer_id = '$customer_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Inquiry Status</title>
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

        .status-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .status-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .status-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .status-container th,
        .status-container td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .status-container th {
            background-color: #f0f0f0;
        }

        .status-container td {
            max-width: 200px;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .return-button {
            display: inline-block;
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
        <a href="customerMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>

    <div class="status-container">
        <h2>Inquiry Status</h2>
        <table>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Submission Date</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['inquiry_type']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['submission_date']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No inquiries found</td></tr>";
            }
            ?>
        </table>
        <a href="customerMenu.php">
            <button class="return-button">Return</button>
        </a>
    </div>
</body>

</html>

<?php $conn->close(); ?>