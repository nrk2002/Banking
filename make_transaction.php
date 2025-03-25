<?php
session_start();
$conn = new mysqli("localhost", "root", "", "banking_system");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $beneficiary_account = $_POST['beneficiary_account'];
    $beneficiary_name = $_POST['beneficiary_name'];
    $ifsc_code = $_POST['ifsc_code'];
    $bank_name_branch = $_POST['bank_name_branch'];
    $amount = $_POST['amount'];
    $transaction_type = $_POST['transaction_type'];
    $payment_method = $_POST['payment_method'];
    
    $netbanking_user = isset($_POST['netbanking_user']) ? $_POST['netbanking_user'] : NULL;
    $netbanking_pass = isset($_POST['netbanking_pass']) ? password_hash($_POST['netbanking_pass'], PASSWORD_DEFAULT) : NULL;

    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : NULL;
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : NULL;

    $stmt = $conn->prepare("INSERT INTO transactions 
        (user_id, beneficiary_account, beneficiary_name, ifsc_code, bank_name_branch, amount, transaction_type, payment_method, 
        netbanking_user, netbanking_pass, card_number, expiry_date, cvv) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("issssdsisssss", $user_id, $beneficiary_account, $beneficiary_name, $ifsc_code, $bank_name_branch, 
        $amount, $transaction_type, $payment_method, $netbanking_user, $netbanking_pass, $card_number, $expiry_date, $cvv);

    if ($stmt->execute()) {
        echo "<script>alert('Transaction Successful!');</script>";
    } else {
        echo "<script>alert('Transaction Failed!');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Transfer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #28a745;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Money Transfer</h2>
    <form method="POST">
        <label>Beneficiary Account Number:</label>
        <input type="text" name="beneficiary_account" required>

        <label>Beneficiary Name:</label>
        <input type="text" name="beneficiary_name" required>

        <label>IFSC Code:</label>
        <input type="text" name="ifsc_code" required>

        <label>Bank Name & Branch:</label>
        <input type="text" name="bank_name_branch" required>

        <label>Transfer Amount:</label>
        <input type="number" name="amount" min="1" required>

        <label>Transaction Type:</label>
        <select name="transaction_type" required>
            <option value="credit">Credit</option>
            <option value="debit">Debit</option>
        </select>

        <label>Payment Method:</label>
        <select name="payment_method" id="payment_method" required onchange="togglePaymentFields()">
            <option value="select">Select</option>
            <option value="net_banking">Net Banking</option>
            <option value="debit_card">Debit Card</option>
            <option value="credit_card">Credit Card</option>
        </select>

        <!-- Net Banking Fields -->
        <div id="net_banking_fields" class="hidden">
            <label>Net Banking Username:</label>
            <input type="text" name="netbanking_user">

            <label>Net Banking Password:</label>
            <input type="password" name="netbanking_pass">

            <label>Captcha:</label>
            <input type="text" name="captcha" placeholder="Enter CAPTCHA">
        </div>

        <!-- Card Payment Fields -->
        <div id="card_fields" class="hidden">
            <label>Card Number:</label>
            <input type="text" name="card_number" pattern="\d{16}" placeholder="Enter 16-digit card number">

            <label>Expiry Date (MM/YY):</label>
            <input type="text" name="expiry_date" placeholder="MM/YY">

            <label>CVV:</label>
            <input type="password" name="cvv" pattern="\d{3}" placeholder="Enter CVV">

            <label>Captcha:</label>
            <input type="text" name="captcha" placeholder="Enter CAPTCHA">
        </div>

        <button type="submit">Submit Transaction</button>
    </form>
</div>

<script>
    function togglePaymentFields() {
        let paymentMethod = document.getElementById('payment_method').value;

        document.getElementById('net_banking_fields').classList.add('hidden');
        document.getElementById('card_fields').classList.add('hidden');

        if (paymentMethod === "net_banking") {
            document.getElementById('net_banking_fields').classList.remove('hidden');
        } else if (paymentMethod === "debit_card" || paymentMethod === "credit_card") {
            document.getElementById('card_fields').classList.remove('hidden');
        }
    }
</script>

</body>
</html>
