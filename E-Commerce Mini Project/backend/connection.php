<?php
    // setup_database.php - Combined DB connection + schema setup

    // Configuration
    $host = "localhost";
    $user = "root";
    $pass = "1234";
    $dbname = "online_bookstore";

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);

    // Step 1: Connect to MySQL server (no DB yet)
    $conn = new mysqli($host, $user, $pass);

    if ($conn->connect_error) {
        die ("Connection failed: " .$conn->connect_error);
    }

    // Step 2: Create Database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) !== TRUE) {
        die("Error creating database: " . $conn->error);
    }

    // Step 3: Select the database
    $conn->select_db($dbname);

    // Step 4: Table creation SQLs
    $createUsers = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email)
    ) ENGINE=InnoDB";

    $createBooks = "CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        stock INT DEFAULT 0,
        image_url VARCHAR(255),
        category_name VARCHAR(255),  
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_title (title)
    ) ENGINE=InnoDB";

    $createcart = "CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_name VARCHAR(255) NOT NULL,
        book_author VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_id INT,  -- Foreign Key should be here
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE  -- Added foreign key to users table
    ) ENGINE=InnoDB";

    $createOrderItems = "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES cart(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";

    $createMessages = "CREATE TABLE IF NOT EXISTS feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    $createPayments = "CREATE TABLE IF NOT EXISTS payments (
        payment_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        payment_method VARCHAR(50),
        payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES cart(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";

    // Create tables
    $tables = [
        'users' => $createUsers,
        'books' => $createBooks,
        'cart' => $createcart,
        'order_items' => $createOrderItems,
        'feedback' => $createMessages,
        'payments' => $createPayments
    ];

    foreach ($tables as $name => $sql) {
        if ($conn->query($sql)) {
            /* echo "✅ Table '$name' created or already exists.<br>"; */
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
            ['Mandodari', 'Mohan Raj Madawala', 1650.00, 10, 'Mandodari.jpeg', 'sinhala'],
            ['Apoiyawa', 'Mahinda Prasad Masimbula', 630.00, 12, 'Apoiyawa.jpeg', 'sinhala'],
            ['Madol Doowa', 'Martin Wickramasinghe', 425.00, 20, 'madol_doowa.jpg', 'sinhala'],
            ['Nil Katrol', 'Mohan Raj Madawala', 1485.00, 8, 'Nil Katrol.jpeg', 'sinhala'],
            ['Loveena', 'Mohan Raj Madawala', 1485.00, 15, 'Loveena.jpg', 'sinhala'],
            ['Amba Yaluwo', 'T.B.Ilangarathna', 650.00, 12, 'Amba Yaluwo.jpg','sinhala'],
            ['A Story of Struggle', 'Ashok Kumawat', 850.00, 15, 'A_story_of_struggle.png', 'English'],
            ['The DaVinci Code', 'Dan Brown',700.00, 20, 'Davinci_code.jpg', 'English'],
            ['Peter Pan', 'J.M.Barrie', 750.00, 5, 'Peter_pan.png','English'],
            ['The push', ' Ashley Audrain', 1200.00, 10, 'The_push.png', 'English'],
            ['The seventh Moons of Maali Almeida', 'Shehan Karunatilka', 1800.00, 5, 'The_seventh_Moons.jpg','English'],
            ['Tom Swayer', 'Mark Twain', 950.00, 7,'TomSwayer.png','Engilsh'],
            ['Ammapai Man Kaduwa Dunna', 'Shashika Sadeep', 700.00, 10, 'Ammapai_man_kaduwa_dunna.jpg','poems'],
            ['At Least we Met', 'Ranganee Fernando',  550.00, 15, 'At_least_we_met.jpg', 'poems'],
            ['Bodima', 'Mahagama Sekara', 800.00, 8, 'bodima.jpg', 'poems'],
            ['Doomalawanyagaraya', 'Ruwan bandujeewa', 900.0, 32,'Doomalawanyagaraya.jpg', 'poems'],
            ['Kada Watena Tharuwak', 'Ilaksha Jayawardhana', 800.00, 12,'Kadawatena_Tharuwak.jpg', 'poems'],
            ['Prabudhdha', 'Mahagama Sekara', 750.00, 25,'Prabudda.jpg', 'poems']
        ];

        $stmt = $conn->prepare("INSERT INTO books (title, author, price, stock, image_url, category_name) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($sampleBooks as $book) {
            $stmt->bind_param("ssdiss", $book[0], $book[1], $book[2], $book[3], $book[4], $book[5]);
            $stmt->execute();
        }

        // echo "✅ Sample books inserted into 'books' table.<br>";
    } else {
        /* echo "ℹ️ Books table already has data.<br>"; */
    }

    /* echo "🎉 All setup completed successfully!"; */
?>
