<?php
include 'connection.php';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


ob_start(); // start output buffering
ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['sname'];
    $email = $_POST['email'];
    $password = $_POST['spsw'];
    $passwordConfirm = $_POST['srpsw'];

    // Validation (Check if fields are empty)
    if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
        echo "All fields are required!";
        exit;
    }

    // Password match check
    if ($password !== $passwordConfirm) {
        echo "Passwords do not match!";
        exit;
    }

    // Sanitize inputs to avoid SQL injection
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    // Check if the username or email already exists using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or Email already exists!";
        header("Location: ../Pages/log-in.html");
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if($stmt){
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if($stmt->execute()){
            echo "Registration Successfull";
            // Redirect to login page
            header("Location: ../Pages/log-in.html");
            exit();
        }else{
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }else{
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>
