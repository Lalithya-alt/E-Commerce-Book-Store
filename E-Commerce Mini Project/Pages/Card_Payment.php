<?php
session_start();
$successMsg = $errorMsg = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $name = trim($_POST["name"]);
    $mobile = trim($_POST["mobile"]);
    $email = trim($_POST["email"]);
    $name_on_card = trim($_POST["name_on_card"]);
    $card_no_raw = trim($_POST["credit_card_no"]);
    $exp = trim($_POST["exp_month_year"]);

    // Remove non-digits from card number
    $card_no = preg_replace('/\D/', '', $card_no_raw);

    // Validation
    if ($name && $mobile && $email && $name_on_card && $card_no && $exp) {
        if (!preg_match("/^[0-9]{10}$/", $mobile)) {
            $errorMsg = "Invalid mobile number. Must be 10 digits.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg = "Invalid email format.";
        } elseif (strlen($card_no) != 16) {
            $errorMsg = "Card number must be 16 digits.";
        } else {
            // Database insert
            $conn = new mysqli("localhost", "root", "", "online_bookstore");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO cardpayment (cd_name, mobile_num, cd_mail, cd_name_of_the_card, cd_card_number, cd_expire_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $mobile, $email, $name_on_card, $card_no, $exp);

            if ($stmt->execute()) {
                $successMsg = "Your payment was successful!";
            } else {
                $errorMsg = "Database error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        $errorMsg = "Please fill all the details.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <meta charset="UTF-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            max-width: 500px;
            padding: 20px;
            border-radius: 8px;
            background: #f9f9f9;
            font-size: 16px;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="tel"],
        form input[type="month"] {
            width: 100%;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        form button {
            background-color: #0B2E33;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h2>Make Your Payment</h2>

    <?php if ($successMsg): ?>
        <p class="success"><?= htmlspecialchars($successMsg) ?></p>
    <?php elseif ($errorMsg): ?>
        <p class="error"><?= htmlspecialchars($errorMsg) ?></p>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateForm();">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" />

        <label for="mobile">Mobile Number:</label>
        <input type="tel" name="mobile" id="mobile" maxlength="10" />

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" />

        <label for="name_on_card">Name on Card:</label>
        <input type="text" name="name_on_card" id="name_on_card" />

        <label for="credit_card_no">Credit Card Number:</label>
        <input type="text" name="credit_card_no" id="credit_card_no" placeholder="1234-5678-9012-3456" maxlength="19" />

        <label for="exp_month_year">Expiry Month and Year:</label>
        <input type="month" name="exp_month_year" id="exp_month_year" />

        <button type="submit">Pay</button>
    </form>

    <script>
        function validateForm() {
            let name = document.getElementById('name').value.trim();
            let mobile = document.getElementById('mobile').value.trim();
            let email = document.getElementById('email').value.trim();
            let cardName = document.getElementById('name_on_card').value.trim();
            let cardNo = document.getElementById('credit_card_no').value.trim();
            let exp = document.getElementById('exp_month_year').value;

            if (!name || !mobile || !email || !cardName || !cardNo || !exp) {
                alert("Please fill all the details.");
                return false;
            }

            if (!/^\d{10}$/.test(mobile)) {
                alert("Mobile number must be 10 digits.");
                return false;
            }

            if (!/^\S+@\S+\.\S+$/.test(email)) {
                alert("Invalid email format.");
                return false;
            }

            if (!/^\d{4}-?\d{4}-?\d{4}-?\d{4}$/.test(cardNo)) {
                alert("Card number must be 16 digits, with or without dashes.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
