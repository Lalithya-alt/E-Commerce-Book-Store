<?php
    // setup_database.php - Combined DB connection + schema setup

    // Configuration
    $host = "localhost";
    $user = "root";
    $pass = "";
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
        Description TEXT,
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

$createcheckout = "
CREATE TABLE IF NOT EXISTS checkout_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    street_name VARCHAR(100),
    city VARCHAR(50),
    country VARCHAR(50),
    zip_code VARCHAR(20),
    name_on_card VARCHAR(100),
    credit_card_no VARCHAR(30),
    exp_month_year VARCHAR(7), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$checkotedbooks="CREATE TABLE IF NOT EXISTS checkout_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    checkout_id INT,
    book_name VARCHAR(255),
    book_author VARCHAR(255),
    FOREIGN KEY (checkout_id) REFERENCES checkout_info(id) ON DELETE CASCADE
)";

    // Create tables
    $tables = [
        'users' => $createUsers,
        'books' => $createBooks,
        'cart' => $createcart,
        'order_items' => $createOrderItems,
        'feedback' => $createMessages,
        'payments' => $createPayments,
        'checkout'=> $createcheckout,
        'checkoutedboks'=>$checkotedbooks
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
            ['Mandodari', 'Mohan Raj Madawala', 'Mandodari is a Sinhala novel by Mohan Raj Madawala that tells the story of a strong woman facing love, betrayal, and revenge. It explores deep emotions and personal strength through her journey.', 1650.00, 10, 'Mandodari.jpeg', 'sinhala'],
            ['Apoiyawa', 'Mahinda Prasad Masimbula', 'Apoiyawa is a Sinhala novel by Mahinda Prasad Masimbula that beautifully portrays rural Sri Lankan life through the eyes of a young boy.',630.00, 12, 'Apoiyawa.jpeg', 'sinhala'],
            ['Madol Doowa', 'Martin Wickramasinghe', 'Madol Doova is a famous Sri Lankan children’s novel by Martin Wickramasinghe. It tells the story of a mischievous boy named Upali and his friend Jinna, who run away to live on an island. There, they work hard and learn to be responsible.',425.00, 20, 'madol_doowa.jpg', 'sinhala'],
            ['Nil Katrol', 'Mohan Raj Madawala','Nil Katrol by Mohan Raj Madawala is a Sinhala novel that blends historical fiction with romance. Set over 1,500 years ago, it narrates a hidden love story of a mysterious artist, weaving intrigue and emotion into a tale that reimagines ancient Sri Lankan history', 1485.00, 8, 'Nil Katrol.jpeg', 'sinhala'],
            ['Loveena', 'Mohan Raj Madawala','Loveena is a Sinhala novel by Mohan Raj Madawala about a forbidden love between a Sri Lankan dancer and a British governor during colonial times. It blends romance, history, and cultural conflict in a powerful story.', 1485.00, 15, 'Loveena.jpg', 'sinhala'],
            ['Amba Yaluwo', 'T.B.Ilangarathna', 'Amba Yahaluwo is a Sinhala novel by T.B. Ilangaratne about a strong friendship between two boys from different social classes, showing how their bond overcomes caste barriers. It highlights themes of equality, compassion, and the innocence of childhood.',650.00, 12, 'Amba Yaluwo.jpg','sinhala'],

            ['A Story of Struggle', 'Ashok Kumawat', '"A Story of Struggle" follows a person journey through hardship and adversity. With resilience and determination, they rise above their challenges to find hope and strength.',850.00, 15, 'A_story_of_struggle.png', 'English'],
            ['The DaVinci Code', 'Dan Brown', 'The Da Vinci Code is a mystery thriller about a symbologist who uncovers a secret hidden in famous artworks and religious history. As he races to solve clues, he becomes entangled in a conspiracy that could shake the foundations of Christianity.',700.00, 20, 'Davinci_code.jpg', 'English'],
            ['Peter Pan', 'J.M.Barrie', 'Peter Pan is a fantasy story about a boy who never grows up and lives in the magical world of Neverland. He embarks on adventures with fairies, pirates, and lost boys, celebrating childhood and imagination.',750.00, 5, 'Peter_pan.png','English'],
            ['The push', ' Ashley Audrain', 'The Push is a psychological story about a mother who questions her bond with her daughter. As dark truths unfold, she struggles with guilt, fear, and the legacy of motherhood.',1200.00, 10, 'The_push.png', 'English'],
            ['The seventh Moons of Maali Almeida', 'Shehan Karunatilka', 'The Seven Moons of Maali Almeida follows a war photographer in the afterlife who has seven days to solve his own murder. Set in 1980s Sri Lanka, it blends mystery, satire, and the supernatural.', 1800.00, 5, 'The_seventh_Moons.jpg','English'],
            ['Tom Swayer', 'Mark Twain', 'The Adventures of Tom Sawyer is about a clever and mischievous boy who has exciting adventures with his friends. He explores caves, hunts for treasure, and learns important life lessons along the way.',950.00, 7,'TomSwayer.png','Engilsh'],

            ['Ammapai Man Kaduwa Dunna', 'Shashika Sadeep', 'Ammapai Man Kaduwa Dunna is a Sinhala poetry collection by Shashika Sadeep, published by Santhawa Prakashana. The book features contemporary poems that explore themes of emotion, identity, and inner conflict, offering readers a reflective and heartfelt literary experience.',700.00, 10, 'Ammapai_man_kaduwa_dunna.jpg','poems'],
            ['At Least we Met', 'Ranganee Fernando', 'At Least We Met by Rangani Fernando is a Sinhala poetry collection exploring love, loss, and human connection. The poems capture the emotions and fleeting moments of relationships.',550.00, 15, 'At_least_we_met.jpg', 'poems'],
            ['Bodima', 'Mahagama Sekara', 'Bodima by Mahagama Sekara is a Sinhala poetry collection that explores human struggles, dreams, and relationships. The poems reflect on personal experiences and the complexities of life.',800.00, 8, 'bodima.jpg', 'poems'],
            ['Doomalawanyagaraya', 'Ruwan bandujeewa', 'Doomalawanyagaraya by Ruwan Bandujeewa is a Sinhala poetry collection published in 2023. The book has received positive reviews, with an average rating of 3.88 out of 5 on Goodreads, based on 8 ratings.',900.0, 32,'Doomalawanyagaraya.jpg', 'poems'],
            ['Kada Watena Tharuwak', 'Ilaksha Jayawardhana', '"Kada Watena Tharuwak" by Ilaksha Jayawardhana is a Sinhala poetry collection that explores themes of love, longing, and the fleeting nature of life.', 800.00, 12,'Kadawatena_Tharuwak.jpg', 'poems'],
            ['Prabudhdha', 'Mahagama Sekara', '"Prabuddha" by Mahagama Sekara is a Sinhala poetry collection that explores themes of love, suffering, and spiritual awakening. It reflects his deep engagement with Buddhist philosophy and human emotions.',750.00, 25,'Prabudda.jpg', 'poems']
        ];

        $stmt = $conn->prepare("INSERT INTO books (title, author, Description, price, stock, image_url, category_name) VALUES (?, ?, ?, ?, ?, ?,?)");

        foreach ($sampleBooks as $book) {
            $stmt->bind_param("sssdiss", $book[0], $book[1], $book[2], $book[3], $book[4], $book[5],$book[6]);
            $stmt->execute();
        }

        // echo "✅ Sample books inserted into 'books' table.<br>";
    } else {
        /* echo "ℹ️ Books table already has data.<br>"; */
    }

    /* echo "🎉 All setup completed successfully!"; */
?>
