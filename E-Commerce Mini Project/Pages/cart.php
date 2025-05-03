<?php
session_start();
include 'connection.php';


// Ensure session cart data exists
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

$cart_items = $_SESSION['cart'] ?? [];
$books_in_cart = [];

echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';

if (!empty($cart_items)) {
    $ids = implode(",", array_keys($cart_items));
    $query = "SELECT * FROM books WHERE id IN ($ids)";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart_items[$row['id']];
        $books_in_cart[] = $row;
    }
}
?>


<!DOCTYPE html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Cart</title>
        <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

        <style>
          .cart-section-main{
            padding-top: 300px;
          }
        </style>
    </head>

    <body>
        <header class="header">
            <nav class="nav_bar">
              <img class="logo-header" src="..\Assets\images\logo-header.jpg" alt="" srcset="" width="100%">
              <ul class="nav_list">
                <li class="nav_element"><a href="Index.html" class="nav_link">Home</a></li>
                
                <li class="nav_element">
                  <a href="Contact_us.html" class="nav_link">Contact Us</a>
                </li>
                <li class="nav_element">
                  <a href="cart.php" class="nav_link">Cart</a>
                </li>
                <li class="nav_element">
                    <a href="Checkout.html" class="nav_link">Check Out</a>
                  </li>
              </ul>
              <a class="btn btn-small" href="log-in.html">Log In</a>
            </nav>
          </header>
          <div class="cart-section-main">
            
          </div>

          
      <!--footer-->
      <div class="wrapper brown--color">
        <div class="container">
          <footer class="row">
            <div class="footer-col footer-col-logo">
              <img class="logo-footer" src="../assets/images/logo-header.jpg" alt="" srcset="" />
            </div>
            <div class="footer-col">
              <h4 class="footer-col-hading">Company</h4>
              <ul>
                <li><a class="page-link" href="index.html">Home</a></li>
                <li><a class="page-link" href="About_us.html">About Us</a></li>
                <li><a class="page-link" href="Contact_us.html">Contact Us</a></li>
              </ul>
            </div>
            
            <div class="footer-col">
              <h4 class="footer-col-hading">Follow us</h4>
              <div class="social-links">
                <a class="page-link" href=""><i class="fab fa-facebook-f"></i></a>
                <a class="page-link" href=""><i class="fab fa-twitter"></i></a>
                <a class="page-link" href=""><i class="fab fa-instagram"></i></a>
                <a class="page-link" href=""
                  ><i class="fab fa-linkedin-in"></i
                ></a>
              </div>
            </div>
            <div class="footer-col"></div>
          </footer>
          <footer class="white--color">
            Copyright &copy; 2025 by Lalithya Rasingolla. All rights reserved.
          </footer>
        </div>
      </div>
      <script src="../script.js"></script>
    </body>
  </html>