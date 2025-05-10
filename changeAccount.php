<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all inquiries from the database
$sql = "SELECT inquiry_id, submission_date, customer_id, inquiry_type, status, description 
        FROM inquiry 
        WHERE inquiry_type = 'RequestUpdate'";
$result = $conn->query($sql);

// Fetch customer names for display
$customer_sql = "SELECT id, username, email, address, phonenumber FROM users WHERE role = 'customer'";
$customer_result = $conn->query($customer_sql);
$customers = [];
while ($customer_row = $customer_result->fetch_assoc()) {
    $customers[$customer_row['id']] = $customer_row;
}

// Update inquiry status (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $inquiry_id = $_POST['inquiry_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE inquiry SET status = ? WHERE inquiry_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $inquiry_id);
    $stmt->execute();

    // Log the event
    $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
    $log_stmt = $conn->prepare($log_sql);
    $event = "Updated Inquiry Status (ID: $inquiry_id to $status)";
    $log_stmt->bind_param("sss", $_SESSION['username'], $_SESSION['role'], $event);
    $log_stmt->execute();

    // Redirect to avoid form resubmission
    header("Location: changeAccount.php");
    exit();
}

// Update customer account details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $new_username = $_POST['new_username'];
    $new_email = $_POST['new_email'];
    $new_address = $_POST['new_address'];
    $new_phone = $_POST['new_phone'];

    $update_customer_sql = "UPDATE users SET username = ?, email = ?, address = ?, phonenumber = ? WHERE id = ?";
    $stmt = $conn->prepare($update_customer_sql);
    $stmt->bind_param("ssssi", $new_username, $new_email, $new_address, $new_phone, $customer_id);
    $stmt->execute();

    // Log the event
    $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
    $log_stmt = $conn->prepare($log_sql);
    $event = "Updated Customer Account (ID: $customer_id)";
    $log_stmt->bind_param("sss", $_SESSION['username'], $_SESSION['role'], $event);
    $log_stmt->execute();

    // Handle password separately
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $update_password_sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_password_sql);
        $stmt->bind_param("si", $new_password, $customer_id);
        $stmt->execute();

        // Log password update
        $event = "Updated Customer Password (ID: $customer_id)";
        $log_stmt->bind_param("sss", $_SESSION['username'], $_SESSION['role'], $event);
        $log_stmt->execute();
    }

    echo "<script>alert('Customer information updated successfully!'); window.location.href='changeAccount.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Customer Account Details Request</title>
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

        .inquiries-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .inquiries-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .inquiries-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .inquiries-container th,
        .inquiries-container td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .inquiries-container th {
            background-color: #f0f0f0;
        }

        .inquiries-container td.description {
            max-width: 300px;
            /* You can adjust the max width as needed */
            word-wrap: break-word;
            /* Allows long words to break and wrap into the next line */
            white-space: normal;
            /* Allows the text to wrap */
        }

        .read-button {
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

        .read-button:hover {
            background-color: #0056b3;
        }

        .checkbox {
            text-align: center;
        }

        .customer-update-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .customer-update-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .customer-update-container label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        .customer-update-container input,
        .customer-update-container select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .customer-update-container button {
            margin-top: 15px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .customer-update-container button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
        }
    </style>
    <script>
        function fillCustomerInfo() {
            let customerSelect = document.getElementById("customer_id");
            let selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (selectedOption.value !== "") {
                let customerInfo = JSON.parse(selectedOption.getAttribute("data-info"));

                document.getElementById("new_username").value = customerInfo.username;
                document.getElementById("new_email").value = customerInfo.email;
                document.getElementById("new_address").value = customerInfo.address;
                document.getElementById("new_phone").value = customerInfo.phonenumber;
            } else {
                document.getElementById("new_username").value = "";
                document.getElementById("new_email").value = "";
                document.getElementById("new_address").value = "";
                document.getElementById("new_phone").value = "";
            }
        }
    </script>

</head>

<body>
    <div class="header">
        <a href="staffMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>

    <div class="inquiries-container">
        <h2>Customer Account Details Request</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Customer_Id</th>
                <th>Customer</th>
                <th>Event Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Get customer name from the customer list array
                    $customer_name = isset($customers[$row['customer_id']]) ? $customers[$row['customer_id']]['username'] : "Unknown";

                    echo "<tr>
                            <td>{$row['submission_date']}</td>
                            <td>{$row['customer_id']}</td>
                            <td>{$customer_name}</td>
                            <td>{$row['inquiry_type']}</td>
                            <td class='description'>{$row['description']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='inquiry_id' value='{$row['inquiry_id']}'>
                                    <select name='status'>
                                        <option value='Unread' " . ($row['status'] == 'Unread' ? 'selected' : '') . ">Unread</option>
                                        <option value='Read' " . ($row['status'] == 'Read' ? 'selected' : '') . ">Read</option>
                                    </select>
                                    <button type='submit' name='update_status' class='read-button'>Update</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No inquiries found</td></tr>";
            }
            ?>
        </table>
    </div>
    <div class="customer-update-container">
        <h2>Update Customer Information</h2>
        <form method="POST">
            <label for="customer_id">Select Customer:</label>
            <select name="customer_id" id="customer_id" onchange="fillCustomerInfo()" required>
                <option value="">-- Select Customer --</option>
                <?php
                foreach ($customers as $id => $customer) {
                    $customer_data = htmlspecialchars(json_encode($customer), ENT_QUOTES, 'UTF-8');
                    echo "<option value='{$id}' data-info='{$customer_data}'>{$customer['username']} ({$customer['email']})</option>";
                }
                ?>
            </select>

            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" required>

            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" required>

            <label for="new_address">New Address:</label>
            <input type="text" id="new_address" name="new_address" required>

            <label for="new_phone">New Phone Number:</label>
            <input type="text" id="new_phone" name="new_phone" required>

            <label for="new_password">New Password (Leave blank to keep unchanged):</label>
            <input type="password" id="new_password" name="new_password">


            <button type="submit" name="update_customer">Update Customer</button>
        </form>
    </div>
</body>

</html>

<?php $conn->close(); ?>