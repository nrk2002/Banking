<?php
session_start();
$conn = new mysqli("localhost", "root", "", "banking_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"]; // No password hashing
    $confirm_password = $_POST["confirm_password"];
    
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }
    
    if ($role === "admin") {
        $secret_key = $_POST["secret_key"];
        if ($secret_key !== "Admin123") { // Change this secret key as needed
            die("Invalid secret key for admin registration!");
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO users (role, name, email, mobile, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $role, $name, $email, $mobile, $password);
    
    if ($stmt->execute()) {
        echo "Registration successful. <a href='login.html'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
