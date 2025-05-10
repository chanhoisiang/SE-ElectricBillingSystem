<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Staff Menu</title>
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

        .menu-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .menu-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .menu-container button {
            display: block;
            width: 80%;
            padding: 15px;
            margin: 10px auto;
            font-size: 16px;
            background-color: #d9d9d9;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .menu-container button:hover {
            background-color: #7a7a7a;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">🏠</div>
        <h1>ELECTRIC BILLING SYSTEM</h1>
        <a href="/se/logout.php">
            <div class="logo">↪</div>
        </a>
    </div>
    <div class="menu-container">
        <h2>STAFF MENU</h2>
        <a href="generateReport.php">
            <button>Generate Log Report</button>
        </a>
        <a href="customerBill.php">
            <button>Monitor Customer Bill</button>
        </a>
        <a href="createBill.php">
            <button>Create Customer Bill</button>
        </a>
        <a href="customerInquiry.php">
            <button>Customer Inquiries/Complaints/Feedback</button>
        </a>
        <a href="changeAccount.php">
            <button>Customer Account Details Request</button>
        </a>
        <a href="technicianStaffChat.php">
            <button>Technician Staff Issue</button>
        </a>
    </div>
</body>

</html>