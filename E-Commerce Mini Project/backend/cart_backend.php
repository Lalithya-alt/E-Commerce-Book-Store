<?php
session_start();
include 'connection.php';


if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book info from books table
    $stmt = $conn->prepare("SELECT title, author, price FROM books WHERE title = ?");
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Book not found.";
        exit;
    }

    $book = $result->fetch_assoc();
    $title = $book['title'];
    $author = $book['author'];
    $price = $book['price'];

    // Insert into cart with user_id
    $insert_stmt = $conn->prepare("INSERT INTO cart ( book_name, book_author, price) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $title, $author, $price);

    if ($insert_stmt->execute()) {
        header("Location: ../pages/cart.php");
        exit;
    } else {
        echo "Error: " . $insert_stmt->error;
    }
}

?>

