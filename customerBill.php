<?php
include '../connect.php';

// Fetch all bills
$bill_query = "SELECT * FROM bill ";
$stmt = $conn->prepare($bill_query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch overdue bills
$overdue_query = "SELECT * FROM bill WHERE payment_status = 'Overdue'";
$overdue_stmt = $conn->prepare($overdue_query);
$overdue_stmt->execute();
$overdue_result = $overdue_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Customer Bill</title>
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
            width: 70%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .overdue {
            background-color: #ffdddd;
            color: red;
            font-weight: bold;
        }

        .no-bills {
            font-size: 18px;
            color: #28a745;
            font-weight: bold;
            text-align: center;
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

    <div class="bill-container">
        <h2>All Customer Bills</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Bill ID</th>
                    <th>Customer_ID</th>
                    <th>Units</th>
                    <th>Tariff Rate</th>
                    <th>Total Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($bill = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $bill['bill_id'] ?></td>
                        <td><?= $bill['customer_id'] ?></td>
                        <td><?= $bill['unit'] ?></td>
                        <td><?= number_format($bill['tariff_rates'], 2) ?></td>
                        <td>RM <?= number_format($bill['total_amount'], 2) ?></td>
                        <td><?= $bill['due_date'] ?></td>
                        <td><?= $bill['payment_status'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else: ?>
            <p class="no-bills">All Bills Clear!</p>
        <?php endif; ?>
    </div>

    <!-- Overdue Bills Section -->
    <div class="bill-container">
        <h2>Overdue Bills</h2>

        <?php if ($overdue_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Bill ID</th>
                    <th>Customer_ID</th>
                    <th>Units</th>
                    <th>Tariff Rate</th>
                    <th>Total Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($overdue = $overdue_result->fetch_assoc()) { ?>
                    <tr class="overdue">
                        <td><?= $overdue['bill_id'] ?></td>
                        <td><?= $bill['customer_id'] ?></td>
                        <td><?= $overdue['unit'] ?></td>
                        <td><?= number_format($overdue['tariff_rates'], 2) ?></td>
                        <td>RM <?= number_format($overdue['total_amount'], 2) ?></td>
                        <td><?= $overdue['due_date'] ?></td>
                        <td><?= $overdue['payment_status'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else: ?>
            <p class="no-bills">No Overdue Bills!</p>
        <?php endif; ?>
    </div>
</body>

</html>