<?php
include '../connect.php';

// Fetch available payment methods
$sql = "SELECT payment_id, payment_method FROM payment";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Payment Method</title>
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

        .payment-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .payment-container h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .payment-container .payment-btn {
            display: block;
            width: 220px;
            padding: 10px;
            margin: 10px auto;
            background-color: #b0b0b0;
            color: black;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .payment-container .payment-btn:hover {
            background-color: #7a7a7a;
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

    <div class="payment-container">
        <h2>Select a Payment Method</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<a href='paysuccess.php?payment_id={$row['payment_id']}' class='payment-btn'>{$row['payment_method']}</a>";
            }
        } else {
            echo "<p>No payment methods available.</p>";
        }
        ?>
    </div>
</body>

</html>