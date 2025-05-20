<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Feedbacks</title>
  <link rel="stylesheet" href="../../style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    .btn-small {
      margin-left: 30px;
    }
    table {
      width: 90%;
      border-collapse: collapse;
      margin: 30px auto;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #0B2E33;
    }
    h2 {
      text-align: center;
      margin-top: 40px;
    }
    .wrapper-feedback {
      padding-top: 200px;
    }
    
  </style>
</head>
<body>

<header class="header">
  <nav class="nav_bar">
    <img class="logo-header" src="../../Assets/images/logo-header.jpg" alt="" width="100%">
    <ul class="nav_list">
      <li class="nav_element"><a href="../Index.html" class="nav_link">Home</a></li>
      <li class="nav_element"><a href="../Contact_us.html" class="nav_link">Contact Us</a></li>
      <li class="nav_element"><a href="../cart.php" class="nav_link">Cart</a></li>
      <li class="nav_element"><a href="../Checkout.html" class="nav_link">Check Out</a></li>
    </ul>
    <a class="btn btn-small" href="../../dashboard.html">Log Out</a>
  </nav>
</header>
<div class="wrapper-feedback">
    <h2>User Feedbacks</h2>

<?php
include '../../backend/connection.php';
$sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="table-container">
  <table>
    <tr>
      <th>ID</th>
      <th>User Name</th>
      <th>Email</th>
      <th>Message</th>
      <th>Submitted At</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['user_name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
          <td><?php echo $row['created_at']; ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">No feedback found.</td></tr>
    <?php endif; ?>
  </table>
</div>

<?php $conn->close(); ?>
</div>

<div class="wrapper brown--color">
  <div class="container">
    <footer class="row">
      <div class="footer-col footer-col-logo">
        <img class="logo-footer" src="../../Assets/images/logo-header.jpg" alt="" />
      </div>
      <div class="footer-col">
        <h4 class="footer-col-hading">Company</h4>
        <ul>
          <li><a class="page-link" href="../Index.html">Home</a></li>
          <li><a class="page-link" href="../Contact_us.html">Contact Us</a></li>
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
      Copyright &copy; 2025 by E-Commerce mini project Group 6 by FOT UOR. All rights reserved.
    </footer>
  </div>
</div>

<script src="../script.js"></script>
</body>
</html>
