<?php
session_start();
?>

<!DOCTYPE html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Cart</title>
        <link rel="stylesheet" href="../style.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

        <style>
          .img-section{
            position: relative;
          }
          
          .img-container{
            position: relative;
          }

          .image__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
          }

          #img-section-heading {
			      position: absolute;
            color: #fff;
            /* font-size: 52px; */
            font-weight: 700;
            letter-spacing: 1px;
            top:50%;
            left: 50%;
            transform: translate(-50%, -50%);
			      border: 5px solid #fff;
            padding: 24px 36px;
            z-index: 1;
		      }
          .box-container {
            position: absolute;
            top: 90%;
            left: 10%;
            display: flex;
            justify-content: space-between;
            gap: 52px;
            padding: 24px;
            margin-bottom: 20px;
          }
        
          .box-item {
            flex: 1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.07);
            border-radius: 5px;
            margin: 0 5px;
          }
          .text{
            padding: 30px 20px;
            /*color:#fff;*/
          }

          .text a{
            text-decoration: none;
          }

          .text h3{
            font-size: 20px;
            margin-bottom: 16px;
          }

          .text p{
            font-size: 16px;
            margin-bottom: 8px;
          }
        
          .image-column {
            overflow: hidden;
          }
        
          .image-column img {
            width: 100%;
            height: 200px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            margin-bottom: 10px;
			      display: block;
          }

        
          form {
            background-color: #4f7c82;
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

          #error{
            color: red;
          }

          .btn-proceed {
            background-color: #0B2E33;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            }

            .book-buttons {
  display: flex;
  gap: 10px; /* Adjust the space between buttons */
  
}
.main-book-buttons{
  display: flex;
  gap:100px;

}

.proceed{
  flex: 3;
}

        </style>
    </head>

    <body>
        <header class="header">
            <nav class="nav_bar">
              <img class="logo-header" src="../Assets/images/logo-header.jpg" alt="" srcset="" width="100%">
              <ul class="nav_list">
                <li class="nav_element"><a href="Index.html" class="nav_link">Home</a></li>
                
                <li class="nav_element">
                  <a href="Contact_us.html" class="nav_link">Contact Us</a>
                </li>
                <li class="nav_element">
                  <a href="cart.php" class="nav_link">Cart</a>
                </li>
                <li class="nav_element">
                    <a href="Checkout.php" class="nav_link">Check Out</a>
                  </li>
              </ul>
              <a class="btn btn-small" href="../dashboard.html">Log Out</a>
            </nav>
          </header>

          <div class="img-section">
            <div class="img-container">
                <h1 id="img-section-heading">Cart</h1>
                <img src="../Assets/images/Cart.jpg" alt="" srcset="">
                <div class="image__overlay"></div>
                </div>
          </div> 

          <!--cart table display-->

       

          <h2 id="table-heading" style="text-align: center;">Your Cart</h2>
          <div class="table-container" style="align-items: center;">
              <table id="table-cart" border ="1" style="margin: auto;">
                  <tr>
                      <th>BOOK NAME</th>
                      <th>BOOK AUTHOR</th>
                      <th>PRICE</th>
                      <th>CREATED_AT</th>
                      <th>ACTION</th> 
                  </tr>

          <?php
                  include '../backend/connection.php'; // Ensure this correctly initializes $conn
                  $user_id = $_SESSION['user_id'];
                  $sql = "SELECT id, book_name, book_author, price, created_at FROM cart WHERE user_id = ?";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("i", $user_id);
                  $stmt->execute();
                  // $result = mysqli_query($conn, $sql);
                  $result = $stmt->get_result();

                  if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                  ?>
                          <tr>
                              <td><?= htmlspecialchars($row['book_name']) ?></td>
                              <td><?= htmlspecialchars($row['book_author']) ?></td>
                              <td><?= htmlspecialchars($row['price']) ?></td>
                              <td><?= htmlspecialchars($row['created_at']) ?></td>
                              <td><a href="../backend/remove_from_cart.php?id=<?= $row['id'] ?>">Remove</a></td>
                          </tr>
            <?php
                      endwhile;
                  else:
                      echo "<tr><td colspan='4'>No items in cart.</td></tr>";
                  endif;
                  ?>
              </table>
          </div>

          <?php
  // Reset result to calculate total
                $stmt->execute();
                $result = $stmt->get_result();
                $total = 0;
                while ($row = $result->fetch_assoc()) {
                $total += $row['price'];
                }
            ?>
            <div style="width: 80%; margin: 20px auto; text-align: right;">
            <p>Total: Rs. <span id="totalAmount"><?= $total ?></span></p>
            <!-- <button onclick="proceedToCheckout()">Proceed</button> -->
             <div class = "main-book-buttons">
              <div class="book-buttons">
                <input class="btn-proceed" type="button"  onclick="window.location.href='../Pages/sinhala.php'" value="Sinhala books"/>
                <input class="btn-proceed" type="button"  onclick="window.location.href='../Pages/english.php'" value="English books"/>
                <input class="btn-proceed" type="button"  onclick="window.location.href='../Pages/poems.php'" value="Poems books"/>
              </div>
              <div class = "proceed">
                <button class="btn-proceed" onclick="proceedToCheckout()" >Proceed</button>
              </div>
             </div>
            </div>



          <script>
             function proceedToCheckout() {
             const total = document.getElementById("totalAmount").innerText;
             localStorage.setItem("cartTotal", total);

  // Collect book names and prices from the table (assuming a table with ID 'table-cart' exists)
             let cartItems = [];
             const rows = document.querySelectorAll("#table-cart tr:not(:first-child)");

             rows.forEach(row => {
             const cells = row.querySelectorAll("td");
             if (cells.length >= 3) {
             cartItems.push({
             bookName: cells[0].innerText.trim(),
             price: cells[2].innerText.trim()
             });
             }
             });

             localStorage.setItem("cartItems", JSON.stringify(cartItems));
             window.location.href = "Checkout.php";
            }
            </script>


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