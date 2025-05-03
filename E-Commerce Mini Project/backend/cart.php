<?php
session_start();
include 'connection.php';

// Load cart data from session
$cart_items = $_SESSION['cart'] ?? [];

$books_in_cart = [];

if (!empty($cart_items)) {
    $ids = implode(",", array_keys($cart_items));
    $query = "SELECT * FROM books WHERE id IN ($ids)";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart_items[$row['id']];
        $books_in_cart[] = $row;
    }
}
?>

