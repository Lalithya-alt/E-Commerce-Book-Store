<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Log In</title>
        <link rel="stylesheet" href="../style.css" />
        <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        />
    </head>

    <body>
        <!--header-->
    <header class="header">
        <nav class="nav_bar">
        <img class="logo-header" src="..\Assets\images\logo-header.jpg" alt="" srcset="" width="100%">
        <ul class="nav_list">
            <li class="nav_element">
            <a href="Index.html" class="nav_link">Home</a></li>
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
        <a class="btn btn-small" href="../dashboard.html">Log Out</a>
        </nav>
    </header>

    <div style="width:1px; height:250px;"></div>   

    <!--user Details-->
    <h3 id="table-heading" style="text-align: center;">USER DETAILS</h3>
<div class="table-container" style="align-items: center;">
    <table id="table-users" border="1" style="margin: auto;">
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>ROLE</th>
            <th>CREATED_AT</th>
        </tr>

        <?php
        include '../backend/connection.php';

        $sql = "SELECT id, name, email, role, created_at, updated_at FROM users";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
        
                </tr>
        <?php
            endwhile;
        else:
            echo "<tr><td colspan='6'>No users found.</td></tr>";
        endif;
        ?>
    </table>
</div>

  
    <!-- Cart-->

    <h3 id="table-heading" style="text-align: center;">CART DETAILS</h3>
          <div class="table-container" style="align-items: center;">
              <table id="table-cart" border ="1" style="margin: auto;">
                  <tr>
                      <th>BOOK NAME</th>
                      <th>BOOK AUTHOR</th>
                      <th>PRICE</th>
                      <th>CREATED_AT</th>
                
                  </tr>

                  <?php
                  include '../backend/connection.php'; // Ensure this correctly initializes $conn

                  $sql = "SELECT book_name, book_author, price, created_at FROM cart";
                  $result = mysqli_query($conn, $sql);

                  if (mysqli_num_rows($result) > 0):
                      while ($row = mysqli_fetch_assoc($result)):
                  ?>
                          <tr>
                              <td><?= htmlspecialchars($row['book_name']) ?></td>
                              <td><?= htmlspecialchars($row['book_author']) ?></td>
                              <td><?= htmlspecialchars($row['price']) ?></td>
                              <td><?= htmlspecialchars($row['created_at']) ?></td>
                          </tr>
                  <?php
                      endwhile;
                  else:
                      echo "<tr><td colspan='4'>No items in cart.</td></tr>";
                  endif;
                  ?>
              </table>
          </div>

      <div style="width:1px; height:40px;"></div>     

                <!--Book details-->

                
 <h3 id="table-heading" style="text-align: center;">BOOK DETAILS</h3>
<div class="table-container" style="align-items: center;">
    <table id="table-books" border="1" style="margin: auto;">
        <tr>
            <th>TITLE</th>
            <th>AUTHOR</th>
            <th>PRICE</th>
            <th>STOCK</th>
            <th>CATEGORY</th>
            <th>CREATED_AT</th>
        </tr>

        <?php
        include '../backend/connection.php';

        $sql = "SELECT title, author, price, stock, category, created_at FROM books";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                    <td><?= htmlspecialchars($row['stock']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
        <?php
            endwhile;
        else:
            echo "<tr><td colspan='6'>No books found.</td></tr>";
        endif;
        ?>
    </table>
</div>

<!--<div style="width:1px; height:40px;"></div> 
<div class="btn-container" >
                <input class="btn btn-small" type="submit" value="ADD BOOK" />
              </div>-->
<div style="width:1px; height:40px;"></div> 

     <!--footer-->
     <div class="wrapper brown--color">
        <div class="container">
          <footer class="row">
            <div class="footer-col footer-col-logo">
              <img class="logo-footer" src="../Assets/images/logo-header.jpg" alt="" srcset="" />
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
                <a class="page-link" href=""
                  ><i class="fab fa-linkedin-in"></i
                ></a>
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