<?php
session_start();
include '../connect.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'customer') {
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

// Fetch only paid bills (successful payments)
$paid_bills_query = "SELECT * FROM bill WHERE customer_id = ? AND payment_status = 'Paid'";
$stmt = $conn->prepare($paid_bills_query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Paid Bills</title>
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

        .bill-container {
            margin: 20px auto;
            width: 80%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .bill-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .pay-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .pay-btn:hover {
            background-color: #218838;
        }

        .no-bill-msg {
            font-size: 16px;
            color: rgb(123, 123, 123);
            font-weight: bold;
        }

        .back-btn {
            display: block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s ease-in-out;
        }

        .back-btn:hover {
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

    <div class="bill-container">
        <h2>Paid Bills</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Bill ID</th>
                    <th>Units</th>
                    <th>Tariff Rate</th>
                    <th>Total Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($bill = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $bill['bill_id'] ?></td>
                        <td><?= $bill['unit'] ?></td>
                        <td><?= number_format($bill['tariff_rates'], 2) ?></td>
                        <td>RM <?= number_format($bill['total_amount'], 2) ?></td>
                        <td><?= $bill['due_date'] ?></td>
                        <td><span style="color: green; font-weight: bold;"><?= $bill['payment_status'] ?></span></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else: ?>
            <p class="no-bill-msg">No paid bills found.</p>
        <?php endif; ?>

        <a href="customerMenu.php">
            <button class="back-btn">Back to Customer Menu</button>
        </a>
    </div>

</body>

</html>

<?php $conn->close(); ?>