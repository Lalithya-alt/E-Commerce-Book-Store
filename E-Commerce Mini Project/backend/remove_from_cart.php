<?php
session_start();

if (isset($_GET['index']) && is_numeric($_GET['index'])) {
    $index = $_GET['index'];

    // Remove item from cart
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
    }

    header("Location: cart.php");
    exit;
} else {
    echo "Invalid cart item index";
    exit;
}
?>
