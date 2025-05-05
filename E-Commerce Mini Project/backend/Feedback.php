<?php
session_start();
include 'connection.php'; // Make sure this connects $conn

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if (empty($email)) {
        echo "Email is required.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (user_name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $comment);

    if ($stmt->execute()) {
        // ✅ Show popup
        echo "<script>alert('Thank you for your feedback!'); window.location.href = '../pages/Contact_us.html';</script>";
      /*  header("Location: ../Pages/Contact_us.html");*/
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
}

?>
