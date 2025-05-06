<?php
session_start();
include '../backend/connection.php'; // Ensure this file connects $conn

$cart_items = [];

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("SELECT book_name, book_author, price FROM cart WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
  }
}
?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Check Out</title>
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    form {
      padding: 20px;
      border-radius: 5px;
      margin: 300px 0 96px 0;
      font-size: 20px;
      /* margin-top: 300px; */
    }

    form label {
      display: block;
      margin-bottom: 10px;
    }

    form input[type="text"],
    form input[type="email"],
    form textarea {
      width: 95%;
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }

    form button {
      background-color: #0B2E33;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    #error {
      color: red;
    }
  </style>

</head>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const updateTotal = () => {
      let total = 0;
      document.querySelectorAll('.cart-row').forEach(row => {
        const priceCell = row.querySelector('td:nth-child(3)');
        const price = parseFloat(priceCell.textContent);
        if (!isNaN(price)) {
          total += price;
        }
      });

      const totalElement = document.getElementById('total-amount');
      if (totalElement) {
        totalElement.textContent = total.toFixed(2);
      }
    };

    document.querySelectorAll('.remove-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('.cart-row');
        if (row) {
          row.remove();
          updateTotal();
        }
      });
    });
  });
</script>



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
          <a href="Cart.php" class="nav_link">Cart</a>
        </li>
        <li class="nav_element">
          <a href="Checkout.html" class="nav_link">Check Out</a>
        </li>
      </ul>
      <a class="btn btn-small" href="../dashboard.html">Log Out</a>
    </nav>
  </header>

  <div class="section__6">
    <div class="_wrapper">
      <div class="section__6__image">
        <img id="__image" src="../assets/images/Check out.jpg" alt="Check Out" srcset="" />
        <div class="section__6__image__overlay"></div>
      </div>
    </div>
    <div class="section__6__text__box">
      <h2 id="text__heading_6">Just a few clicks to go!</h2>
    </div>
  </div>



  <div class="wrapper">
    <div class="container">
      <form id="purchase" method="POST" action="checkoutbackend.php">
        <h2 class="text__section__heading">Your Basic details</h2>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <h2 class="text__section__heading">Billing Address</h2>

        <label for="street">Street Name:</label>
        <input type="text" id="Streetname" name="Streetname">

        <label for="city">City:</label>
        <input type="text" id="city" name="city">

        <label for="country">Country:</label>
        <input type="text" id="country" name="country">

        <label for="zipcode">zip Code:</label>
        <input type="text" id="zipcode" name="zipcode">

        <?php if (!empty($cart_items)): ?>
          <table>
            <tr>
              <th>Book Name</th>
              <th>Author</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
            <?php
            $total = 0;
            foreach ($cart_items as $index => $item):
              $total += $item['price'];
            ?>
              <tr class="cart-row">
                <td><input type="hidden" name="book_name[]" value="<?= htmlspecialchars($item['book_name']) ?>"><?= htmlspecialchars($item['book_name']) ?></td>
                <td><input type="hidden" name="book_author[]" value="<?= htmlspecialchars($item['book_author']) ?>"><?= htmlspecialchars($item['book_author']) ?></td>
                <td><?= htmlspecialchars($item['price']) ?></td>
                <td><button type="button" class="remove-btn">Remove</button></td>
              </tr>
            <?php endforeach; ?>

            <tr>
              <td colspan="2"><strong>Total</strong></td>
              <td colspan="2"><strong id="total-amount"><?= number_format($total, 2) ?></strong></td>
            </tr>
          </table>
        <?php else: ?>
          <p>No items in cart. <?php echo isset($_SESSION['user_id']) ? '(User is logged in)' : '(Please log in)'; ?></p>
        <?php endif; ?>

        <h2 class="text__section__heading">Payment Method</h2>

        <span>Cards Accepted :</span>

        <div style="width:1px; height:10px;"></div>

        <img src="../Assets/images/card_img.png" alt="Accepted Cards">

        <div style="width:1px; height:20px;"></div>

        <label for="name_on_card">Name On Card:</label>
        <input type="text" id="name_on_card" name="name_on_card">

        <label for="creadit_card_no">Creadit Card Number:</label>
        <input type="text" id="creadit_card_no" name="creadit_card_no">

        <label for="exp_month&yaer">Expiry Month and Year:</label>
        <input type="month" id="exp_month&year" name="exp_month&year">

        <div style="width:1px; height:30px;"></div>

        <input class="btn btn-small" type="submit" value="Check Out">
        <p id="error" style="display: inline-block; margin-left: 50px;"></p>

        <input class="btn btn-small" type="reset" value="Cancel">


      </form>
    </div>
  </div>

  <!--footer-->>
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
            <li><a class="page-link" href="Contact_us.html">Contact Us</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4 class="footer-col-hading">Follow us</h4>
          <div class="social-links">
            <a class="page-link" href=""><i class="fab fa-facebook-f"></i></a>
            <a class="page-link" href=""><i class="fab fa-twitter"></i></a>
            <a class="page-link" href=""><i class="fab fa-instagram"></i></a>
            <a class="page-link" href=""><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="footer-col"></div>
      </footer>
      <footer class="white--color">
        Copyright &copy; 2025 by E-Commerce mini project Group () by FOT UOR. All rights reserved.
      </footer>
    </div>
  </div>
  <script src="../script.js"></script>

</body>

</html>