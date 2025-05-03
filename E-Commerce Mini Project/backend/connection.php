<?php
// setup_database.php - Combined DB connection + schema setup

// Configuration
$host = "localhost";
$user = "root";
$pass = "1234";
$dbname = "bookHeaven-eBook";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

try {
    // Step 1: Connect to MySQL server (no DB yet)
    $conn = new mysqli($host, $user, $pass);

    if ($conn->connect_error) {
        throw new Exception("❌ Connection failed: " . $conn->connect_error);
    }

    // Step 2: Create Database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname 
            CHARACTER SET utf8mb4 
            COLLATE utf8mb4_unicode_ci";

    if ($conn->query($sql)) {
        echo "✅ Database '$dbname' created or already exists.<br>";
    } else {
        throw new Exception("❌ Error creating database: " . $conn->error);
    }

    // Step 3: Select the database
    $conn->select_db($dbname);

    echo "✅ Connected to database '$dbname'.<br>";

    // Step 4: Table creation SQLs

    $createUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email)
    ) ENGINE=InnoDB";

    $createBooks = "CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        image_path VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_title (title)
    ) ENGINE=InnoDB";

    $createOrders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";

    $createOrderItems = "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";

    $createMessages = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_read BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB";

    // Create tables
    $tables = [
        'users' => $createUsers,
        'books' => $createBooks,
        'orders' => $createOrders,
        'order_items' => $createOrderItems,
        'messages' => $createMessages
    ];

    foreach ($tables as $name => $sql) {
        if ($conn->query($sql)) {
            echo "✅ Table '$name' created or already exists.<br>";
        } else {
            throw new Exception("❌ Error creating table '$name': " . $conn->error);
        }
    }

    // Insert sample books
    $checkBooks = "SELECT COUNT(*) AS count FROM books";
    $result = $conn->query($checkBooks);
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        $sampleBooks = [
            ["The Great Gatsby", "F. Scott Fitzgerald", 12.99, "A story of wealth and love in the Jazz Age.", "images/great-gatsby.jpg"],
            ["To Kill a Mockingbird", "Harper Lee", 10.99, "A powerful story of racial injustice.", "images/mockingbird.jpg"],
            ["1984", "George Orwell", 9.99, "A dystopian novel about totalitarianism.", "images/1984.jpg"]
        ];

        $stmt = $conn->prepare("INSERT INTO books (title, author, price, description, image_path) VALUES (?, ?, ?, ?, ?)");

        foreach ($sampleBooks as $book) {
            $stmt->bind_param("ssdss", $book[0], $book[1], $book[2], $book[3], $book[4]);
            $stmt->execute();
        }

        echo "✅ Sample books inserted into 'books' table.<br>";
    } else {
        echo "ℹ️ Books table already has data.<br>";
    }

    echo "🎉 All setup completed successfully!";
} catch (Exception $e) {
    die($e->getMessage());
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
