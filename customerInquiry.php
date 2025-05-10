<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all inquiries from the database
$sql = "SELECT inquiry_id, submission_date, customer_id, inquiry_type, status, description FROM inquiry";
$result = $conn->query($sql);

// Fetch customer names for display
$customer_sql = "SELECT id, username FROM USERS";
$customer_result = $conn->query($customer_sql);
$customers = [];
while ($customer_row = $customer_result->fetch_assoc()) {
    $customers[$customer_row['id']] = $customer_row['username'];
}

// Update inquiry status (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $inquiry_id = $_POST['inquiry_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE inquiry SET status = ? WHERE inquiry_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $inquiry_id);
    $stmt->execute();

    // Redirect to avoid form resubmission
    header("Location: customerInquiry.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Customer Inquiries/Feedback/Complaint</title>
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

    <div class="inquiries-container">
        <h2>Customer Inquiries/Complaints/Feedback</h2>
        <table>
            <tr>
                <th>Date</th>
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
                    $customer_name = isset($customers[$row['customer_id']]) ? $customers[$row['customer_id']] : "Unknown";

                    echo "<tr>
                            <td>{$row['submission_date']}</td>
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
</body>

</html>

<?php $conn->close(); ?>