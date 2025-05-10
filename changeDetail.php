<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Change of Account Details Request</title>
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

        .account-details-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .account-details-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .account-details-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .account-details-container th,
        .account-details-container td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .account-details-container th {
            background-color: #f0f0f0;
        }

        .update-button {
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

        .update-button:hover {
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
    <div class="account-details-container">
        <h2>Change of Account Details Request</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Status</th>
                <th>Select</th>
            </tr>
            <tr>
                <td>12/1/2025</td>
                <td>Ali</td>
                <td>Request: Change Username</td>
                <td class="checkbox"><input type="checkbox" checked></td>
            </tr>
        </table>
        <button class="update-button">Update</button>
    </div>
</body>

</html>