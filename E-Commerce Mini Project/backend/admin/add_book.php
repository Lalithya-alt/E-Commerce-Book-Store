<?php
// Include database connection
include '../connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form inputs
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_name = isset($_POST['category']) ? trim($_POST['category']) : 'poems';

    // Define allowed categories and folders
    $allowed_categories = [
        'poems' => 'poems',
        'sinhala' => 'sinhala',
        'english' => 'english'
    ];

    if (!array_key_exists($category_name, $allowed_categories)) {
        echo "❌ Invalid category.";
        exit;
    }

    $image_folder = $allowed_categories[$category_name];
    $image_url = "";

    // STEP 1: Check if the same book exists
    $check_stmt = $conn->prepare("SELECT id, stock FROM books WHERE title = ? AND author = ? AND category_name = ?");
    $check_stmt->bind_param("sss", $title, $author, $category_name);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Book exists – update stock
        $check_stmt->bind_result($book_id, $existing_stock);
        $check_stmt->fetch();
        $new_stock = $existing_stock + $stock;

        $update_stmt = $conn->prepare("UPDATE books SET stock = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_stock, $book_id);

        if ($update_stmt->execute()) {
            // Redirect to correct page
            header("Location: ../../Pages/{$image_folder}.php");
            exit;
        } else {
            echo "❌ Error updating stock: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        // New book – handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $base_dir = "../../Assets/images/";
            $target_dir = $base_dir . $image_folder . "/";

            if (!file_exists($target_dir)) {
                echo "❌ Folder does not exist: $target_dir";
                exit;
            }

            // Create folder if not exists
            // if (!file_exists($target_dir)) {
            //     if (!mkdir($target_dir, 0777, true)) {
            //         echo "❌ Failed to create directory: $target_dir. Error: " . error_get_last()['message'];
            //         exit;
            //     }
            // }
            // $target_dir = "../Assets/images/poems/";

            $image_name = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            $max_file_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($imageFileType, $allowed_types)) {
                echo "❌ Only JPG, JPEG, PNG & GIF files are allowed.";
                exit;
            }

            if ($_FILES["image"]["size"] > $max_file_size) {
                echo "❌ Image is too large. Max size: 5MB.";
                exit;
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $image_name;
            } else {
                echo "❌ Image upload failed.";
                exit;
            }
        }

        // Insert new book
        $stmt = $conn->prepare("INSERT INTO books (title, author, description, price, stock, image_url, category_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdiss", $title, $author, $description, $price, $stock, $image_url, $category_name);

        if ($stmt->execute()) {
            header("Location: ../../Pages/{$image_folder}.php");
            exit;
        } else {
            echo "❌ Error inserting book: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
} else {
    echo "No form data submitted.";
}
?>
