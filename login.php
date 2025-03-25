<?php
session_start();
$conn = new mysqli("localhost", "root", "", "banking_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ip_address = $_SERVER['REMOTE_ADDR'];
$time = time();
$limit_time = $time - 60; // Only allow 3 attempts per 60 seconds

// Check login attempts for this IP
$check = $conn->query("SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address='$ip_address' AND timestamp > $limit_time");
$row = $check->fetch_assoc();

if ($row['attempts'] > 3) {
    echo "<script>alert('Too many requests. Try again later.'); window.location.href='login.html';</script>";
    exit();
}

// Process Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"]; 
    $role = $_POST["role"];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=? AND role=?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $name, $stored_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if ($password === $stored_password) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $name;
            $_SESSION['role'] = $role;
            $_SESSION['login_attempts'] = 0; 

            echo "<script>alert('Login successful!'); window.location.href='dashboard.php';</script>";
            exit();
        } else {
            $_SESSION['login_attempts'] += 1;
            echo "<script>alert('Invalid credentials!'); window.location.href='login.html';</script>";
        }
    } else {
        $_SESSION['login_attempts'] += 1;
        echo "<script>alert('User not found!'); window.location.href='login.html';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
