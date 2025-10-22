<?php
session_start();   // must be first

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Connect to database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "barangay_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $first_name   = $conn->real_escape_string($_POST['first_name']);
    $middle_name  = $conn->real_escape_string($_POST['middle_name']);
    $last_name    = $conn->real_escape_string($_POST['last_name']);
    $age          = (int)$_POST['age'];
    $gender       = $conn->real_escape_string($_POST['gender']);
    $civil_status = $conn->real_escape_string($_POST['civil_status']);
    $address      = $conn->real_escape_string($_POST['address']);

    $sql = "INSERT INTO residents (first_name, middle_name, last_name, age, gender, civil_status, address) 
            VALUES ('$first_name', '$middle_name', '$last_name', $age, '$gender', '$civil_status', '$address')";

    if ($conn->query($sql) === TRUE) {
        $message = "Resident added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Resident</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Resident</h2>

    <?php
    if (isset($message)) {
        echo "<div class='message'>" . $message . "</div>";
    }
    ?>

    <form method="POST" action="residents.php?action=add">
        <label>First Name:</label>
        <input type="text" name="first_name" required>

        <label>Middle Name:</label>
        <input type="text" name="middle_name" required>

        <label>Last Name:</label>
        <input type="text" name="last_name" required>

        <label>Age:</label>
        <input type="number" name="age" required>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">--Select--</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Civil Status:</label>
        <select name="civil_status" required>
            <option value="">--Select--</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
        </select>

        <label>Address:</label>
        <input type="text" name="address" required>

        <button type="submit" name="submit">Add Resident</button>
    </form>
</div>

</body>
</html>
