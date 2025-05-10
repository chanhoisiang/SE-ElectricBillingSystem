<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM USERS WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$customer_id = $row['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inquiry_type = "RequestUpdate";
    $description = $_POST['description'];
    $status = "Unread"; // Default status
    $submission_date = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO inquiry (customer_id, inquiry_type, description, status, submission_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $customer_id, $inquiry_type, $description, $status, $submission_date);

    if ($stmt->execute()) {
        // ✅ Log the inquiry submission
        $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $event = "Submitted account update request (Customer ID: $customer_id)";
        $role = "customer";
        $log_stmt->bind_param("sss", $username, $role, $event);
        $log_stmt->execute();

        echo "<script>alert('Request submitted successfully!'); window.location.href='requestAccountUpdate.php';</script>";
    } else {
        echo "<script>alert('Error submitting Request: " . $conn->error . "'); window.history.back();</script>";
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Feedback</title>
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

        .feedback-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .feedback-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .feedback-container textarea {
            width: 100%;
            height: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            resize: none;
        }

        .feedback-container button {
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

        .feedback-container button:hover {
            background-color: #0056b3;
        }

        .feedback-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 5px solid #007bff;
        }

        .feedback-item p {
            margin: 5px 0;
        }

        .feedback-item .date {
            font-size: 12px;
            color: gray;
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

    <div class="feedback-container">
        <h2>Request Account Details Update</h2>
        <form method="POST">
            <textarea name="description" placeholder="Write your request here..." required></textarea>
            <button type="submit" name="submit_feedback">SUBMIT</button>
        </form>
    </div>
</body>

</html>