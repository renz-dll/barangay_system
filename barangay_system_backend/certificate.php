<?php
session_start();   // must be first

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $resident_id = isset($_POST['resident_id']) ? (int)$_POST['resident_id'] : 0;
    $issued_by   = isset($_POST['issued_by']) ? $conn->real_escape_string($_POST['issued_by']) : '';
    $type        = isset($_POST['type']) ? $conn->real_escape_string($_POST['type']) : '';
    $purpose     = isset($_POST['purpose']) ? $conn->real_escape_string($_POST['purpose']) : '';

    $sql = "INSERT INTO certificates (resident_id, issued_by, type, purpose)
        VALUES ($resident_id, '$issued_by', '$type', '$purpose')";

    if ($conn->query($sql) === TRUE) {
        $message = "Certificate issued successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch residents and officials for dropdowns
$residents_result = $conn->query("SELECT id, first_name, middle_name, last_name FROM residents");
$officials_result = $conn->query("SELECT id, full_name FROM barangay_officials");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Issue Certificate</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }
    h2 {
        text-align: center;
        color: #333;
    }
    .message {
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        color: green;
    }
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        max-width: 500px;
        margin: 20px auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }
    button {
        margin-top: 20px;
        padding: 12px 20px;
        width: 100%;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    button:hover {
        background-color: #0056b3;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    table th, table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    table th {
        background-color: #007bff;
        color: white;
    }
    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>
</head>
<body>

<h2>Issue Certificate</h2>

<?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>

<form method="POST" action="certificate.php">
    <label>Resident:</label>
    <select name="resident_id" required>
        <option value="">--Select Resident--</option>
        <?php while ($resident = $residents_result->fetch_assoc()): ?>
            <option value="<?= $resident['id'] ?>">
                <?= $resident['first_name'] . ' ' . $resident['middle_name'] . ' ' . $resident['last_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Issued By (Official):</label>
    <select name="issued_by" required>
        <option value="">--Select Official--</option>
        <?php while ($official = $officials_result->fetch_assoc()): ?>
            <option value="<?= $official['id'] ?>"><?= $official['full_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Certificate Type:</label>
    <select name="type" required>
        <option value="">--Select Type--</option>
        <option value="Clearance">Clearance</option>
        <option value="Residency">Residency</option>
        <option value="Indigency">Indigency</option>
    </select>

    <label>Purpose:</label>
    <input type="text" name="purpose" required>

    <button type="submit" name="submit">Issue Certificate</button>
</form>

<h2>Issued Certificates</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Resident</th>
        <th>Issued By</th>
        <th>Type</th>
        <th>Purpose</th>
        <th>Issued Date</th>
    </tr>
    <?php
    $certs_result = $conn->query(
        "SELECT c.id, r.first_name, r.middle_name, r.last_name, 
                c.type, c.purpose, c.issued_date,
                o.full_name AS official_name
         FROM certificates c
         JOIN residents r ON c.resident_id = r.id
         JOIN barangay_officials o ON c.issued_by = o.id
         ORDER BY c.issued_date DESC"
    );
    while ($cert = $certs_result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $cert['id'] ?></td>
        <td><?= $cert['first_name'] . ' ' . $cert['middle_name'] . ' ' . $cert['last_name'] ?></td>
        <td><?= $cert['official_name'] ?></td>
        <td><?= $cert['type'] ?></td>
        <td><?= $cert['purpose'] ?></td>
        <td><?= $cert['issued_date'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
