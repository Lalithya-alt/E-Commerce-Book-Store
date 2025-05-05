<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cart_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../Pages/cart.php");
        exit;
    } else {
        echo "Failed to remove item: " . $stmt->error;
    }
} else {
    echo "Invalid ID provided.";
}
?>
