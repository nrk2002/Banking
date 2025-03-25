<?php
session_start();
$conn = new mysqli("localhost", "root", "", "banking_system");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT beneficiary_account, beneficiary_name, ifsc_code, bank_name_branch, amount, 
               transaction_type, payment_method, transaction_date 
        FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { width: 80%; margin: auto; background: #fff; padding: 20px; border-radius: 10px; 
                     box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #28a745; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Transaction History</h2>
    <a href="transaction.php">Make a New Transaction</a>
    <table>
        <tr>
            <th>Beneficiary Account</th>
            <th>Beneficiary Name</th>
            <th>IFSC Code</th>
            <th>Bank & Branch</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Payment Method</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["beneficiary_account"]); ?></td>
                <td><?php echo htmlspecialchars($row["beneficiary_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["ifsc_code"]); ?></td>
                <td><?php echo htmlspecialchars($row["bank_name_branch"]); ?></td>
                <td>₹<?php echo number_format($row["amount"], 2); ?></td>
                <td><?php echo ucfirst($row["transaction_type"]); ?></td>
                <td><?php echo str_replace("_", " ", ucfirst($row["payment_method"])); ?></td>
                <td><?php echo $row["transaction_date"]; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
