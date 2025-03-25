<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

/* Body Styling */
body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: linear-gradient(to right, #001f3f, #0074cc);
    color: white;
    text-align: center;
}

/* Dashboard Container */
.dashboard-container {
    width: 80%;
    max-width: 1000px;
}

/* Welcome Message */
h2 {
    font-size: 28px;
    font-weight: bold;
    color: #ffcc00;
    margin-bottom: 20px;
    animation: fadeIn 1s ease-in-out;
}

/* Welcome Box */
.welcome-box {
    background: rgba(255, 255, 255, 0.15);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    margin-bottom: 30px;
    text-align: center;
    animation: fadeIn 1.5s ease-in-out;
}

/* Flexbox for Actions */
.action-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

/* Transaction & History Cards */
.card {
    flex: 1;
    background: rgba(255, 255, 255, 0.2);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    text-align: center;
    transition: transform 0.3s ease-in-out, background 0.3s;
}

.card:hover {
    transform: scale(1.05);
    background: rgba(255, 255, 255, 0.3);
}

/* Icons */
.card i {
    font-size: 40px;
    margin-bottom: 10px;
    color: #ffcc00;
}

/* Buttons */
.card a {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 20px;
    background: #ffcc00;
    color: #001f3f;
    font-weight: bold;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;
}

.card a:hover {
    background: #ffaa00;
}

/* Logout Button */
.logout-btn {
    display: inline-block;
    margin-top: 30px;
    background: #e74c3c;
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
}

.logout-btn:hover {
    background: #c0392b;
    transform: scale(1.05);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .action-container {
        flex-direction: column;
    }
}
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>! 🎉</h2>

        <div class="welcome-box">
            <p>Manage your banking transactions securely.</p>
            <p>Select an option below to proceed.</p>
        </div>

        <div class="action-container">
            <!-- Make Transaction Card -->
            <div class="card">
                <i class="fas fa-money-check-alt"></i>
                <h3>Make a Transaction</h3>
                <p>Send or receive funds securely.</p>
                <a href="make_transaction.php">Proceed</a>
            </div>

            <!-- Transaction History Card -->
            <div class="card">
                <i class="fas fa-history"></i>
                <h3>Transaction History</h3>
                <p>View your past transactions.</p>
                <a href="transaction_history.php">View</a>
            </div>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

</body>
</html>
