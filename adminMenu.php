<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Administrator Menu</title>
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

        .admin-menu-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .admin-menu-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .admin-menu-container button {
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

        .admin-menu-container button:hover {
            background-color: #b0b0b0;
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
    <div class="admin-menu-container">
        <h2>Administrator Menu</h2>
        <a href="createDeleteAccount.php">
            <button>Create or Delete Account</button>
        </a>
        <a href="updateTariff.php">
            <button>Update Tariffs</button>
        </a>
        <a href="paymentMethod.php">
            <button>Payment Method</button>
        </a>
        <a href="managePermission.php">
            <button>Manage Permission</button>
        </a>
    </div>
</body>

</html>