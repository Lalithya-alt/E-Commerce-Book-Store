<?php
include 'connection.php';

ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if database connection is successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the email and password from POST data
    $email = trim($_POST['lname'] ?? '');
    $password = trim($_POST['lpsw'] ?? '');

    // Check if the email and password are provided
    if (empty($email) || empty($password)) {
        echo "All fields are required!";
        exit;
    }

    // Admin login check (hardcoded admin credentials)
    $adminEmail = 'admin@gmail.com';
    $adminPassword = 'admin123'; // You should hash this password in production (optional)

    // Check if the login is for admin
    if ($email === $adminEmail && $password === $adminPassword) {
        $_SESSION['email'] = $adminEmail;
        $_SESSION['is_admin'] = true;  // You can use this flag to check for admin permissions
        header("Location: ../Pages/admin-dashboard.php");
        exit;
    }

    // Normal user login check (database check)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if password matches (use password_verify if passwords are hashed)
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Storing user role for access control
            header("Location: ../Pages/Index.html");
            exit;
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();

    ob_end_flush();

}
?>


