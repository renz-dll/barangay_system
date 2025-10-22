<?php
session_start();

if (!isset($_SESSION['admin'])) {  }else{
    header("Location: dashboard.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$message = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin by username
    $result = $conn->query("SELECT * FROM admins WHERE username='$username'");
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];
            $message = "Login successful!";
            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); width: 300px; }
        h2 { text-align: center; margin-bottom: 20px; }
        input { width: 93%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; border: none; border-radius: 5px; background: #007bff; color: white; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { text-align: center; margin-bottom: 10px; color: red; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if($message) echo "<div class='message'>$message</div>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>