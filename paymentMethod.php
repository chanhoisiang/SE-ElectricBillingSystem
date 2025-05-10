<?php
include '../connect.php';

// Handle adding a new payment method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_method'])) {
    $paymentMethod = trim($_POST['method_name']); // Trim to remove extra spaces

    // Validate input (prevent empty input or duplicates)
    if (!empty($paymentMethod)) {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM payment WHERE payment_method = ?");
        $checkStmt->bind_param("s", $paymentMethod);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            echo "<script>alert('Payment method already exists!'); window.history.back();</script>";
            exit();
        }

        // Add payment method
        $conn->begin_transaction(); // Start transaction
        $stmt = $conn->prepare("INSERT INTO payment (payment_method) VALUES (?)");
        $stmt->bind_param("s", $paymentMethod);

        if ($stmt->execute()) {
            // Log success
            $logStmt = $conn->prepare("INSERT INTO log (username, event, date) VALUES (?, ?, NOW())");
            $event = "Added new payment method: $paymentMethod";
            $username = "Admin"; // Change to dynamic username if needed
            $logStmt->bind_param("ss", $username, $event);
            $logStmt->execute();
            $logStmt->close();

            $conn->commit(); // Commit transaction
            echo "<script>alert('Payment method added successfully!'); window.location.href='paymentMethod.php';</script>";
        } else {
            $conn->rollback(); // Rollback in case of failure
            echo "<script>alert('Error adding payment method: " . $conn->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please enter a valid payment method name!'); window.history.back();</script>";
    }
}

// Handle deleting a payment method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_method'])) {
    $paymentId = $_POST['method_id']; // Fixed field name

    if (!empty($paymentId) && is_numeric($paymentId)) {
        $conn->begin_transaction(); // Start transaction
        $stmt = $conn->prepare("DELETE FROM payment WHERE payment_id = ?");
        $stmt->bind_param("i", $paymentId);

        if ($stmt->execute()) {
            // Log deletion
            $logStmt = $conn->prepare("INSERT INTO log (username, event, date) VALUES (?, ?, NOW())");
            $event = "Deleted payment method with ID: $paymentId";
            $username = "Admin";
            $logStmt->bind_param("ss", $username, $event);
            $logStmt->execute();
            $logStmt->close();

            $conn->commit(); // Commit transaction
            echo "<script>alert('Payment method deleted successfully!'); window.location.href='paymentMethod.php';</script>";
        } else {
            $conn->rollback(); // Rollback in case of failure
            echo "<script>alert('Error deleting payment method: " . $conn->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid payment method ID!'); window.history.back();</script>";
    }
}

// Fetch payment methods from the database
$sql = "SELECT payment_id, payment_method FROM payment";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Payment Methods</title>
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

        .methods {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .methods form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        button {
            width: 200px;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .add-method-form {
            margin-top: 20px;
            text-align: center;
        }

        .add-method-form input {
            width: 200px;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-method-form button {
            background-color: #28a745;
        }

        .add-method-form button:hover {
            background-color: #218838;
        }

        a {
            text-decoration: none;
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
        <h2>Current Active Payment Methods:</h2>
        <div class="methods">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div>
                            <button>{$row['payment_method']}</button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='method_id' value='{$row['payment_id']}'>
                                <button type='submit' name='delete_method' class='delete-btn'>Delete</button>
                            </form>
                          </div>";
                }
            } else {
                echo "<p>No payment methods available.</p>";
            }
            ?>
        </div>

        <!-- Add New Payment Method Form -->
        <div class="add-method-form">
            <h2>Add New Payment Method</h2>
            <form method="POST">
                <input type="text" name="method_name" placeholder="Enter Payment Method" required>
                <button type="submit" name="add_method">Add</button>
            </form>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>