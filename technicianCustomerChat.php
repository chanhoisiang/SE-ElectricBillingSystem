<?php
session_start();
include '../connect.php'; // Database connection

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Fetch chat messages with usernames
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $message = trim($_POST['message']);
    $sender = $_POST['sender']; // Either 'customer' or 'technician'

    if (!empty($username) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO technician_customer_chat (username, sender, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $sender, $message);

        if ($stmt->execute()) {
            header("Location: technicianCustomerChat.php"); // Redirect back to chat page
            exit();
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
    }
    $conn->close();
}

$sql = "SELECT * FROM technician_customer_chat ORDER BY chat_time ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Customer Chat</title>
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
            justify-content: space-between;
            align-items: center;
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


        .chat-container {
            width: 90%;
            max-width: 700px;
            background-color: #dfe4ea;
            padding: 20px;
            border-radius: 15px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .chat-box {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            max-height: 300px;
            overflow-y: auto;
        }

        .chat-box p {
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 5px;
            max-width: 80%;
        }

        .customer {
            background-color: #d1ecf1;
            align-self: flex-end;
        }

        .technician {
            background-color: #f8d7da;
            align-self: flex-start;
        }

        .chat-input {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .chat-input input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .chat-input button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .chat-input button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="technicianMenu.php">
            <div class="logo">🏠</div>
        </a>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <div></div>
    </div>
    <div class="chat-container">
        <h2>Technician Customer Chat</h2>
        <div class="chat-box">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <p class="<?= $row['sender'] == 'customer' ? 'customer' : 'technician' ?>">
                    <strong><?= htmlspecialchars($row['username']) ?> (<?= ucfirst($row['sender']) ?>):</strong>
                    <?= htmlspecialchars($row['message']) ?>
                </p>
            <?php } ?>
        </div>
        <form class="chat-input" action="" method="POST">
            <input type="text" name="message" placeholder="Type your message here..." required>
            <input type="hidden" name="sender" value="technician">
            <button type="submit">Send</button>
        </form>
    </div>
</body>

</html>