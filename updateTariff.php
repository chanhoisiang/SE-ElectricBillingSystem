<?php
include '../connect.php'; // Ensure you have a proper connection file

// Initialize variables
$currentPricing = "";

// Fetch the current tariff
$sql = "SELECT tariff_rates FROM systemsetting WHERE setting_id = 1"; // Assuming 'id = 1' identifies the tariff
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentPricing = $row['tariff_rates'];
}

// Handle form submission to update the tariff
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPricing = $_POST['new-pricing'];

    // Validate the input
    if (is_numeric($newPricing) && $newPricing > 0) {
        $updateSql = "UPDATE systemsetting SET tariff_rates = ? WHERE setting_id = 1"; // Update the specific tariff
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("d", $newPricing); // 'd' for decimal

        if ($stmt->execute()) {
            // ✅ Log successful tariff update
            $log_sql = "INSERT INTO log (username, event, date) VALUES (?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $event = "Tariff updated from $currentPricing to $newPricing";
            $username = "Admin"; // Change this to dynamically get the admin username if available
            $log_stmt->bind_param("ss", $username, $event);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('Tariff updated successfully!');</script>";
            $currentPricing = $newPricing; // Update the displayed value
        } else {
            // ✅ Log error if tariff update fails
            $error_message = $conn->error;
            $log_sql = "INSERT INTO log (username, event, date) VALUES (?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $event = "Failed to update tariff from $currentPricing to $newPricing - Error: $error_message";
            $username = "Admin";
            $log_stmt->bind_param("ss", $username, $event);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('Error updating tariff: " . $conn->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please enter a valid numeric value.');</script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Update Tariffs</title>
    <style>
        /* Your CSS styles from before */
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

        .form-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            width: 100px;
            padding: 10px;
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
        <a href="adminMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>
    <div class="form-container">
        <h2>Update Tariffs</h2>
        <form method="POST" action="">
            <div style="text-align: left; width: 80%;">
                <label>Current Pricing:</label>
                <span>$<?php echo htmlspecialchars($currentPricing); ?> per unit</span>
            </div>

            <label for="new-pricing">New Pricing:</label>
            <input type="text" id="new-pricing" name="new-pricing" placeholder="Enter new pricing">

            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>