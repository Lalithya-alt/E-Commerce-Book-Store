<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['user_id'])){
    echo "User not logged in";
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['title'])) {
    $book_name = $_GET['title'];

    // Fetch book info from books table
    $stmt = $conn->prepare("SELECT title, author, price FROM books WHERE title = ?");
    $stmt->bind_param("s", $book_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Book not found with name: $book_name";
        exit;
    }

    $book = $result->fetch_assoc();
    
    $book_name = $book['title'];
    $author = $book['author'];
    $price = $book['price'];

    // Insert into cart with user_id
    $insert_stmt = $conn->prepare("INSERT INTO cart ( user_id, book_name, book_author, price) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("issd", $user_id, $book_name, $author, $price);

    if ($insert_stmt->execute()) {
        header("Location: ../pages/cart.php");
        exit;
    } else {
        echo "Error: " . $insert_stmt->error;
    }
}else{
    echo "No book name provided in the URL";
}

?>