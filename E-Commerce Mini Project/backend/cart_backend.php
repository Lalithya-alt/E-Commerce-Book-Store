<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book info from books table
    include 'connection.php';
    $stmt = $conn->prepare("SELECT title, author, price FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Book not found with ID: $book_id";
        exit;
    }

    $book = $result->fetch_assoc();
    $title = $book['title'];
    $author = $book['author'];
    $price = $book['price'];

    // Store book in session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add book to cart session array
    $_SESSION['cart'][] = array(
        'book_name' => $title,
        'book_author' => $author,
        'price' => $price,
    );

    header("Location: ../pages/cart.php");
    exit;
} else {
    echo "No book ID provided in the URL";
}
?>
