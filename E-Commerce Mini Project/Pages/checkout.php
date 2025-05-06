<?php
session_start();
include '../backend/connection.php'; // Make sure this connects $conn

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $street = trim($_POST['Streetname'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $zipcode = trim($_POST['zipcode'] ?? '');
    $name_on_card = trim($_POST['name_on_card'] ?? '');
    $credit_card_no = trim($_POST['creadit_card_no'] ?? '');
    $exp_month_year = trim($_POST['exp_month&year'] ?? '');

    $errors = [];

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    if (!empty($zipcode) && !is_numeric($zipcode)) $errors[] = "Zip code must be numeric.";

    if (!empty($credit_card_no) && !preg_match('/^[0-9]{13,19}$/', $credit_card_no)) {
        $errors[] = "Credit card number must be between 13-19 digits.";
    }

    if (!empty($exp_month_year) && !preg_match('/^\d{4}-\d{2}$/', $exp_month_year)) {
        $errors[] = "Expiry must be in YYYY-MM format.";
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

    $stmt = $conn->prepare("INSERT INTO checkout_info 
    (name, email, street_name, city, country, zip_code, name_on_card, credit_card_no, exp_month_year) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssss", $name, $email, $street, $city, $country, $zipcode, $name_on_card, $credit_card_no, $exp_month_year);

    if ($stmt->execute()) {
        // ✅ Success popup and redirect
        echo "<script>
        alert('✅ Checkout successful! Thank you for your order.');
        window.location.href = '../pages/Checkout.html'; // or confirmation page
    </script>";
        exit;
    } else {
        echo "<script>
        alert('❌ Something went wrong. Please try again later.');
        window.history.back();
    </script>";
    }
}
