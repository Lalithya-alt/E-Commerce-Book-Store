<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


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

    $book_names = $_POST['book_name'] ?? [];
    $book_authors = $_POST['book_author'] ?? [];

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

    if (empty($book_authors)) $errors[] = "Not book Selected";

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
        $checkout_id = $conn->insert_id; // Get the ID for linking books

        // Insert each book
        // Prepare all statements

        $book_stmt = $conn->prepare("INSERT INTO checkout_books (checkout_id, book_name, book_author) VALUES (?, ?, ?)");
        $stock_check_stmt = $conn->prepare("SELECT stock FROM books WHERE title = ? AND author = ?");
        $stock_update_stmt = $conn->prepare("UPDATE books SET stock = stock - 1 WHERE title = ? AND author = ?");

        $price_stmt = $conn->prepare("SELECT price FROM books WHERE title = ? AND author = ?");

        $book_details = "";
        $total_price = 0;



        for ($i = 0; $i < count($book_names); $i++) {
            $book_name = trim($book_names[$i]);
            $book_author = trim($book_authors[$i]);

            // 1. Insert into checkout_books
            $book_stmt->bind_param("iss", $checkout_id, $book_name, $book_author);
            $book_stmt->execute();

            // 2. Check stock from books table
            $stock_check_stmt->bind_param("ss", $book_name, $book_author);
            $stock_check_stmt->execute();
            $result = $stock_check_stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if ($row['stock'] >= 1) {
                    // 3. Update stock - reduce by 1
                    $stock_update_stmt->bind_param("ss", $book_name, $book_author);
                    $stock_update_stmt->execute();
                } else {
                    echo "<script>
                alert('❌ \"$book_name\" by $book_author is out of stock.');
                window.history.back();
            </script>";
                    exit;
                }
            } else {
                echo "<script>
            alert('❌ \"$book_name\" by $book_author not found in book database.');
            window.history.back();
        </script>";
                exit;
            }


            // 4. Fetch price
            $price_stmt->bind_param("ss", $book_name, $book_author);
            $price_stmt->execute();
            $price_result = $price_stmt->get_result();

            if ($price_row = $price_result->fetch_assoc()) {
                $price = $price_row['price'];
                $total_price += $price;
                $book_details .= "- \"$book_name\" by $book_author — Rs. $price\n";
            }
        }

        // Send confirmation email
        $subject = " Your Book Order Confirmation - Book Heaven";
        $headers = "From: Book Heaven <no-reply@leesagallery.com>\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $message = "Dear $name,\n\n";
        $message .= "Thank you for your purchase from Book Heaven!\n\n";
        $message .= " Shipping City: $city\n";
        $message .= " Book(s) Ordered:\n$book_details\n";
        $message .= " Total Price: Rs. $total_price\n\n";
        $message .= "Your books will be dispatched shortly.\n\n";
        $message .= "Best Regards,\nBook Heaven Team";

        // mail($email, $subject, $message, $headers);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';             //  Change this
            $mail->SMTPAuth = true;
            $mail->Username = 'sadeedina2002@gmail.com'; //  Your SMTP email
            $mail->Password = 'gxjw nxbm lfiy dthz';             //  Your SMTP password
            $mail->SMTPSecure = 'tls';                     // 'ssl' if using port 465
            $mail->Port = 587;                             // 465 if using 'ssl'

            $mail->setFrom('sadeedina2002@gmail.com', 'Book Heaven');
            $mail->addAddress($email, $name);

            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
        } catch (Exception $e) {
            error_log("❌ Email Error: {$mail->ErrorInfo}");
        }


        echo "<script>
            alert('✅ Checkout successful! Thank you for your order.');
            window.location.href = '../pages/index.html';
        </script>";
        exit;
    } else {
        echo "<script>
        alert('❌ Something went wrong. Please try again later.');
        window.history.back();
    </script>";
    }
}
