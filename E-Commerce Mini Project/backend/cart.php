<?php
session_start();
include '../db_connect.php';

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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart</title>
    <link rel="stylesheet" href="../style.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <style>
        .cart-section-main{
            padding-top: 120px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav_bar">
            <img class="logo-header" src="../Assets/images/logo-header.jpg" alt="" width="100%">
            <ul class="nav_list">
                <li class="nav_element"><a href="Index.html" class="nav_link">Home</a></li>
                <li class="nav_element"><a href="Contact_us.html" class="nav_link">Contact Us</a></li>
                <li class="nav_element"><a href="Cart.php" class="nav_link">Cart</a></li>
                <li class="nav_element"><a href="Checkout.php" class="nav_link">Check Out</a></li>
            </ul>
            <a class="btn btn-small" href="log-in.html">Log In</a>
        </nav>
    </header>

    <!-- Cart Section -->
    <div class="cart-section-main">
        <h2>Your Cart</h2>
        <?php if (empty($books_in_cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Book</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
                <?php 
                    $total = 0;
                    foreach ($books_in_cart as $book): 
                        $subtotal = $book['price'] * $book['quantity'];
                        $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td>$<?= number_format($book['price'], 2) ?></td>
                    <td><?= $book['quantity'] ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <h3>Total: $<?= number_format($total, 2) ?></h3>
            <a href="Checkout.php" class="btn">Proceed to Checkout</a>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="wrapper brown--color">
        <div class="container">
            <footer class="row">
                <div class="footer-col footer-col-logo">
                    <img class="logo-footer" src="../assets/images/logo-header.jpg" alt="" />
                </div>
                <div class="footer-col">
                    <h4 class="footer-col-hading">Company</h4>
                    <ul>
                        <li><a class="page-link" href="index.html">Home</a></li>
                        <li><a class="page-link" href="About_us.html">About Us</a></li>
                        <li><a class="page-link" href="Contact_us.html">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-col-hading">Follow us</h4>
                    <div class="social-links">
                        <a class="page-link" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="page-link" href=""><i class="fab fa-twitter"></i></a>
                        <a class="page-link" href=""><i class="fab fa-instagram"></i></a>
                        <a class="page-link" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="footer-col"></div>
            </footer>
            <footer class="white--color">
                Copyright &copy; 2025 by Lalithya Rasingolla. All rights reserved.
            </footer>
        </div>
    </div>

    <script src="../script.js"></script>
</body>
</html>
