<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve payment method and bill details
$payment_id = $_GET['payment_id'] ?? null;
$username = $_SESSION['username'];

// Get customer ID
$customer_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$customer_query->bind_param("s", $username);
$customer_query->execute();
$customer_query_result = $customer_query->get_result();
$customer_row = $customer_query_result->fetch_assoc();
$customer_id = $customer_row['id'];

// Fetch unpaid bill for the customer
$bill_query = $conn->prepare("SELECT bill_id, total_amount 
                              FROM bill 
                              WHERE customer_id = ? 
                              AND payment_status IN ('Pending', 'Overdue') 
                              ORDER BY due_date ASC 
                              LIMIT 1");
$bill_query->bind_param("i", $customer_id);
$bill_query->execute();
$bill_result = $bill_query->get_result();
$bill = $bill_result->fetch_assoc();

if ($bill && $payment_id) {
    // Update bill status to 'Paid'
    $update_query = $conn->prepare("UPDATE bill SET payment_status = 'Paid' WHERE bill_id = ?");
    $update_query->bind_param("i", $bill['bill_id']);
    $update_query->execute();

    // Get payment method
    $payment_method_query = $conn->prepare("SELECT payment_method FROM payment WHERE payment_id = ?");
    $payment_method_query->bind_param("i", $payment_id);
    $payment_method_query->execute();
    $payment_method_result = $payment_method_query->get_result();
    $payment_method_row = $payment_method_result->fetch_assoc();
    $payment_method = $payment_method_row['payment_method'];

    // Generate a unique reference ID
    $amount_paid = $bill['total_amount'];
    $reference_id = "REF" . strtoupper(uniqid());

    // ✅ Log the payment transaction
    $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
    $log_stmt = $conn->prepare($log_sql);
    $event = "Paid bill (Bill ID: {$bill['bill_id']}, Amount: RM{$amount_paid}, Payment Method: $payment_method)";
    $role = "customer";
    $log_stmt->bind_param("sss", $username, $role, $event);
    $log_stmt->execute();
} else {
    // If no pending bill or payment method, redirect to customer menu
    header("Location: customerMenu.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Payment Successful</title>
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

        .success-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-container h2 {
            font-size: 24px;
            color: #6a0dad;
            margin-bottom: 20px;
        }

        .success-container .details {
            font-size: 16px;
            margin: 10px 0;
        }

        .success-container .details span {
            font-weight: bold;
        }

        .finish-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }

        .finish-button:hover {
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
    <div class="success-container">
        <h2>Payment Successful</h2>
        <p class="details">Payment Method: <span><?php echo htmlspecialchars($payment_method); ?></span></p>
        <p class="details">Amount Paid: <span>$<?php echo number_format($amount_paid, 2); ?></span></p>
        <p class="details">Reference ID: <span><?php echo $reference_id; ?></span></p>
        <a href="customerMenu.php">
            <button class="finish-button">Finish</button>
        </a>
    </div>
</body>

</html>

<?php $conn->close(); ?>