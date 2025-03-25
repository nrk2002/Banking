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

// Google reCAPTCHA Secret Key (Replace with your actual secret key)
$recaptcha_secret = "6LeyxvwqAAAAAAOQJYNJroYAfpypzKFQ9yjLvra7";

// Check if the reCAPTCHA response is set
if (!isset($_POST['g-recaptcha-response'])) {
    echo "<script>alert('reCAPTCHA verification failed!'); window.location.href='login.html';</script>";
    exit();
}

// Verify reCAPTCHA with Google
$recaptcha_response = $_POST['g-recaptcha-response'];
$verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response&remoteip=$ip_address";

$response = file_get_contents($verify_url);
$response_keys = json_decode($response, true);

if (!$response_keys["success"]) {
    echo "<script>alert('reCAPTCHA verification failed!'); window.location.href='login.html';</script>";
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
