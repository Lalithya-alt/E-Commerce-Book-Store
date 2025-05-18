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
        }


        echo "<script>
            alert('✅ Checkout successful! Thank you for your order.');
            window.location.href = '../pages/Checkout.php';
        </script>";
        exit;
    } else {
        echo "<script>
        alert('❌ Something went wrong. Please try again later.');
        window.history.back();
    </script>";
    }
}
