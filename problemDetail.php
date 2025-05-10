<?php
session_start();
include '../connect.php'; // Include your database connection

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $problem_details = trim($_POST['problem_details']);
    $solution = trim($_POST['solution']);

    // Check if fields are empty
    if (empty($problem_details) || empty($solution)) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO problem_reports (problem_details, solution, reported_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $problem_details, $solution);

    if ($stmt->execute()) {
        $log_sql = "INSERT INTO log (username, role, event, date) VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $event = "Submit Problem Report";
        $log_stmt->bind_param("sss", $_SESSION['username'], $_SESSION['role'], $event);
        $log_stmt->execute();
        echo "<script>alert('Problem report submitted successfully!'); window.location.href='problemDetail.php';</script>";
    } else {
        echo "<script>alert('Error submitting report: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Fetch all problem reports
$report_query = "SELECT * FROM problem_reports ORDER BY reported_at DESC";
$report_result = $conn->query($report_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Billing System - Problem Reports</title>
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

        .form-container {
            margin: 20px auto;
            width: 90%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .form-container h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .table-container {
            margin: 20px auto;
            width: 90%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        td.problemdetails {
            max-width: 250px;
            /* You can adjust the max width as needed */
            word-wrap: break-word;
            /* Allows long words to break and wrap into the next line */
            white-space: normal;
            /* Allows the text to wrap */
        }

        td.solution {
            max-width: 350px;
            /* You can adjust the max width as needed */
            word-wrap: break-word;
            /* Allows long words to break and wrap into the next line */
            white-space: normal;
            /* Allows the text to wrap */
        }

        .no-reports {
            font-size: 18px;
            color: #dc3545;
            font-weight: bold;
            text-align: center;
            padding: 20px;
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

    <!-- Form Section -->
    <div class="form-container">
        <h2>Submit a Problem Report</h2>
        <form action="" method="POST">
            <label for="problem-details">Problem Details:</label>
            <textarea id="problem-details" name="problem_details" rows="4" required></textarea>

            <label for="solution">Solution:</label>
            <textarea id="solution" name="solution" rows="4" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Problem Reports Table Section -->
    <div class="table-container">
        <h2>Problem Reports</h2>

        <?php if ($report_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Problem Details</th>
                    <th>Solution</th>
                    <th>Submitted At</th>
                </tr>

                <?php while ($report = $report_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $report['id'] ?></td>
                        <td class='problemdetails'><?= htmlspecialchars($report['problem_details']) ?></td>
                        <td class='solution'><?= htmlspecialchars($report['solution']) ?></td>
                        <td><?= $report['reported_at'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else: ?>
            <p class="no-reports">No problem reports submitted yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>