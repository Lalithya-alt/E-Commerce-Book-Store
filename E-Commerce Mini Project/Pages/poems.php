<?php
// Include the database connection
include '../backend/connection.php';

// Category 1 corresponds to Poems
$category_name = 'poems'; // Change this if you use different category IDs

// Fetch books in the "Poems" category
$query = "SELECT * FROM books WHERE category_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $category_name);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Poems Books</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    .wrapper{
      padding-top: 200px;
    }
  </style>
</head>
<body>
  <header class="header">
    <nav class="nav_bar">
      <img class="logo-header" src="../Assets/images/logo-header.jpg" alt="Logo" width="100%">
      <ul class="nav_list">
        <li class="nav_element"><a href="Index.html" class="nav_link">Home</a></li>
        <li class="nav_element"><a href="Contact_us.html" class="nav_link">Contact Us</a></li>
        <li class="nav_element"><a href="Cart.php" class="nav_link">Cart</a></li>
        <li class="nav_element"><a href="Checkout.html" class="nav_link">Check Out</a></li>
      </ul>
      <a class="btn btn-small" href="../dashboard.html">Log Out</a>
    </nav>
  </header>

  <!-- Website body -->
  <div class="wrapper grey--color">
    <div class="container">
      <div class="section__2">
        <h2 class="section__2__heading">Poems Book Collection</h2>
        <div class="component_container">
          <?php while ($book = $result->fetch_assoc()): ?>
            <div class="component">
              <h3 class="component__heading"><?php echo htmlspecialchars($book['title'] ?? ''); ?></h3>
              <img class="room-image" src="../Assets/images/poems/<?php echo htmlspecialchars($book['image_url'] ?? 'default.jpg'); ?>" alt="Book Image">
              <div class="component__description">
                <p><?php echo nl2br(htmlspecialchars($book['Description'] ?? '')); ?></p>
                <div style="width:1px; height:15px;"></div>
                <ul>
                    <li><strong>AUTHOR:</strong> <?php echo htmlspecialchars($book['author'] ?? ''); ?></li>
                    <li><strong>PRICE:</strong> LKR.<?php echo number_format($book['price'] ?? 0, 2); ?></li>
                </ul>
                <div class="btn-container" style="text-align:center;">
                    <input class="btn btn-small" type="button"
                    onclick="window.location.href='../backend/cart_backend.php?title=<?php echo urlencode($book['title'] ?? ''); ?>'"
                    value="Add To Cart" />
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>


  <div style="width:1px; height:30px;"></div>

  <!-- Footer -->
  <div class="wrapper brown--color">
    <div class="container">
      <footer class="row">
        <div class="footer-col footer-col-logo">
          <img class="logo-footer" src="../assets/images/logo-header.jpg" alt="Logo" />
        </div>
        <div class="footer-col">
          <h4 class="footer-col-hading">Company</h4>
          <ul>
            <li><a class="page-link" href="index.html">Home</a></li>
            <li><a class="page-link" href="Contact_us.html">Contact Us</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4 class="footer-col-hading">Follow us</h4>
          <div class="social-links">
            <a class="page-link" href="#"><i class="fab fa-facebook-f"></i></a>
            <a class="page-link" href="#"><i class="fab fa-twitter"></i></a>
            <a class="page-link" href="#"><i class="fab fa-instagram"></i></a>
            <a class="page-link" href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </footer>
      <footer class="white--color">
        Copyright &copy; 2025 by E-Commerce mini project Group (FOT UOR). All rights reserved.
      </footer>
    </div>
  </div>

  <script src="../script.js"></script>
</body>
</html>
