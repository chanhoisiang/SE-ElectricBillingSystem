<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up</title>
    <script>
        function showForm(formId) {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('signup-form').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ccc;
            border-radius: 10px;
            padding: 30px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .container input[type="text"],
        .container input[type="email"],
        .container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #aaa;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .container a {
            text-decoration: none;
            font-size: 0.9em;
            color: #007BFF;
        }

        .container a:hover {
            text-decoration: underline;
        }

        .container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .container button:hover {
            background-color: #0056b3;
        }

        .tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
        }

        .tabs button {
            padding: 10px 20px;
            background-color: #eee;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .tabs button:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">👤</div>

        <!-- Login Form -->
        <form id="login-form" action="login.php" method="POST" style="display: block;">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <p>Don't have an account? <a href="#" onclick="showForm('signup-form')">Sign up</a></p>
            <button type="submit">Login</button>
        </form>

        <!-- Sign-Up Form -->
        <form id="signup-form" action="signup.php" method="POST" style="display: none;">
            <input type="text" name="name" id="name" placeholder="Name" required>
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="text" name="address" id="address" placeholder="Address" required>
            <input type="text" name="phonenumber" id="phonenumber" placeholder="Phone Number" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <p>Already have an account? <a href="#" onclick="showForm('login-form')">Log in</a></p>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>

</html>