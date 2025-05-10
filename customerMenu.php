<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System</title>
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

        .search-bar {
            margin: 20px;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .button-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .button-container button {
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

        .button-container button:hover {
            background-color: #7a7a7a;
        }

        a {
            text-decoration: none;
        }

        .button-container button.hidden {
            display: none;
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

    <!-- Search bar form to filter menu buttons -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search menu items..." onkeyup="filterMenu()">
    </div>

    <div class="button-container">
        <a href="view_pay_bills.php">
            <button class="menu-button">View and Pay Bills</button>
        </a>
        <a href="transaction_history.php">
            <button class="menu-button">View Transaction History</button>
        </a>
        <a href="inquiries_complaints.php">
            <button class="menu-button">Inquiry/Complaint/Feedback</button>
        </a>
        <a href="requestAccountUpdate.php">
            <button class="menu-button">Account Details Update</button>
        </a>
        <a href="technicianCustomerChat.php">
            <button class="menu-button">Technician Customer Chat</button>
        </a>
    </div>

    <script>
        // Function to filter menu items based on search input
        function filterMenu() {
            var input, filter, buttons, button, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            buttons = document.getElementsByClassName("menu-button");

            // Loop through all menu buttons and hide those that don't match the search
            for (i = 0; i < buttons.length; i++) {
                button = buttons[i];
                txtValue = button.textContent || button.innerText;

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    button.classList.remove("hidden");
                } else {
                    button.classList.add("hidden");
                }
            }
        }
    </script>
</body>

</html>