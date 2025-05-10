<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Create or Delete Account</title>
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

        .create-delete-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .create-delete-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .create-delete-container .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .create-delete-container button {
            width: 150px;
            padding: 15px;
            font-size: 16px;
            background-color: #d9d9d9;
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .create-delete-container button:hover {
            background-color: #b0b0b0;
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
    <div class="create-delete-container">
        <h2>Create or Delete Account</h2>
        <div class="button-container">
            <a href="createAccount.php">
                <button>Create Account</button>
            </a>
            <a href="deleteAccount.php">
                <button>Delete Account</button>
            </a>
        </div>
</body>

</html>