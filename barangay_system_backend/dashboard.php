<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Admin username
$admin_username = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            margin: 50px auto;
            max-width: 600px;
            text-align: center;
        }
        .dashboard-box {
            background-color: #fff;
            padding: 40px;
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            display: block;
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
            transition: 0.3s;
        }
        .dashboard-box:hover {
            transform: scale(1.05);
            background-color: #007bff;
            color: white;
        }
        .logout {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #ff4d4d;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome, <?= htmlspecialchars($admin_username) ?>!</h1>
    <p>Barangay Management Dashboard</p>
</div>

<div class="container">
    <a href="residents.php" class="dashboard-box">➕ Add Residents</a>
    <a href="certificate.php" class="dashboard-box">📜 Issue Certificates</a>

    <br>
    <a href="dashboard.php?logout=true" class="logout">Logout</a>
</div>

</body>
</html>
