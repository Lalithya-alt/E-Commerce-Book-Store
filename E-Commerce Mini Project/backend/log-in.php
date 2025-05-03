<?php
include 'connection.php';

ob_start(); // start output buffering
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['lname'] ?? '');
    $password = trim($_POST['lpsw'] ?? '');

    // Validate fields
    if (empty($email) || empty($password)) {
        echo "All fields are required!";
        exit;
    }

    // Search for the user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ If passwords are hashed:
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['email'] = $user['email'];
            header("Location: ../Pages/Index.html");
            exit;
        } else {
            echo "Incorrect password!";
            exit;
        }

        // ❌ If password is in plain text (not recommended):
        // if ($password === $user['password']) { ... }
    } else {
        echo "User not found with that email!";
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
