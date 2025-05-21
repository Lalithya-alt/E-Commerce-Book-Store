<?php
session_start();
include '../backend/connection.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('You must be logged in to make a payment.'); window.location.href='../Pages/log-in.html';</script>";
    exit;
}

// Get data from POST
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$email = $_SESSION['email'];
$name_on_card = $_POST['name_on_card'];
$credit_card_no = $_POST['creadit_card_no'];
$exp_month_year = $_POST['exp_month&year'];

$errors=[];

if (empty($name)) $errors[] = "Name is required.";
if (empty($mobile)) {
    $errors[] = "Mobile number is required";
} elseif (!preg_match('/^[0-9]{1,10}$/', $mobile)) {
    $errors[] = "Mobile number must contain only digits and be no more than 10 digits long.";
}
if (empty($name_on_card)) $errors[] = "Name of the card is required";
 if (!empty($credit_card_no) && !preg_match('/^[0-9]{13,19}$/', $credit_card_no)) {
        $errors[] = "Credit card number must be between 13-19 digits.";
 }

 if (!empty($errors)) {
        echo "<ul style='color:red;'>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "<script>
        alert('❌ " . implode("\\n", $errors) . "');
        window.history.back();
    </script>";
        exit;
    }


// Insert into table
$sql = "INSERT INTO subscriberspayments (name, mobile, email, name_on_card, credit_card_no, exp_month_year)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $mobile, $email, $name_on_card, $credit_card_no, $exp_month_year);

if ($stmt->execute()) {
     $stmt2 = $conn->prepare("INSERT INTO premium_users (username) VALUES (?)");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $stmt2->close();
        echo "<script>
            alert('✅ Subscribe successful! Thank you.');
            window.location.href = '../pages/Subscribed_Homepage.html';
        </script>";
        exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
