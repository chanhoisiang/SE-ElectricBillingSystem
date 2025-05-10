<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Update overdue payments
$update_query = "UPDATE bill SET payment_status = 'Overdue' WHERE due_date < CURDATE() AND payment_status != 'Paid'";
$conn->query($update_query);

// Fetch customers with the "customer" role
$customer_query = "SELECT id, username FROM users WHERE role = 'customer'";
$customer_result = $conn->query($customer_query);

// Fetch tariff rate
$tariff_query = "SELECT tariff_rates FROM systemsetting WHERE setting_id = 1";
$tariff_result = $conn->query($tariff_query);
$tariff_rate = 0.00;

if ($tariff_result->num_rows > 0) {
    $row = $tariff_result->fetch_assoc();
    $tariff_rate = $row['tariff_rates']; // Set tariff rate
}

// Handle bill creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_bill'])) {
    $customer_id = $_POST['customer_id'];
    $unit = $_POST['unit'];
    $tariff_rates = $_POST['tariff_rates']; // This should be pre-filled
    $total_amount = $unit * $tariff_rates;
    $due_date = $_POST['due_date'];
    $payment_status = $_POST['payment_status'];

    // Insert bill into the database
    $insert_query = "INSERT INTO bill (customer_id, unit, tariff_rates, total_amount, due_date, payment_status) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iidiss", $customer_id, $unit, $tariff_rates, $total_amount, $due_date, $payment_status);

    if ($stmt->execute()) {
        // Log the event
        $log_query = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_query);
        $event = "Create Bill";
        $log_stmt->bind_param("sss", $_SESSION['username'], $_SESSION['role'], $event);
        $log_stmt->execute();

        echo "<script>alert('Bill created successfully!'); window.location.href='createBill.php';</script>";
    } else {
        echo "<script>alert('Error creating bill.'); window.location.href='createBill.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer Bill</title>
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
            width: 50%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .bill-container h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-top: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            margin-top: 15px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
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
    <div class="bill-container">
        <h2>Create Bill for Customer</h2>
        <form method="POST">
            <label for="customer_id">Select Customer:</label>
            <select name="customer_id" required>
                <option value="">-- Select Customer --</option>
                <?php
                while ($customer = $customer_result->fetch_assoc()) {
                    echo "<option value='{$customer['id']}'>{$customer['username']}</option>";
                }
                ?>
            </select>

            <label for="unit">Enter Units Consumed:</label>
            <input type="number" name="unit" id="unit" required>

            <label for="tariff_rates">Tariff Rate (per unit):</label>
            <input type="number" step="0.01" name="tariff_rates" id="tariff_rates" value="<?php echo $tariff_rate; ?>" readonly>

            <label for="total_amount">Total Amount:</label>
            <input type="text" id="total_amount" readonly>

            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" required>

            <label for="payment_status">Payment Status:</label>
            <select name="payment_status" required>
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
                <option value="Overdue">Overdue</option>
            </select>

            <button type="submit" name="create_bill">Create Bill</button>
        </form>
    </div>

    <script>
        document.getElementById('unit').addEventListener('input', function() {
            let unit = parseFloat(this.value) || 0;
            let tariffRate = parseFloat(document.getElementById('tariff_rates').value) || 0;
            let totalAmount = unit * tariffRate;
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
        });
    </script>

</body>

</html>

<?php $conn->close(); ?>