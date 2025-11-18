<?php
require 'dbConnect.php';
session_start();

if (isset($_POST['buy_now'])) {
    
    echo "<script>alert('Purchase Successful! ');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Shopping - Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1 class="logo">ShopEasy</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="login.php">Login</a>
      <a href="signup.php" class="signup-btn">Sign Up</a>
    </nav>
  </header>

  <section class="hero">
    <h2>Welcome to ShopEasy</h2>
    <p>Your one-stop online shop for everything!</p>
    <a href="signup.php" class="cta-btn">Start Shopping</a>
  </section>

  <section class="products">
    <h2>Featured Products</h2>
    <div class="product-grid">
      <?php 
        $query = "SELECT * FROM products";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $image = $row['image_url'];
            $name = $row['product_name'];
            $description = $row['description'];
            $price = $row['price'];
      ?>
      <div class="product-card">
        <img src="<?php echo $image; ?>" alt="Product">
        <h3><?php echo $name; ?></h3>
        <p><?php echo $description; ?></p>
        <p><strong><?php echo $price; ?> BDT</strong></p>

        <form method="post">
          
            <button type="submit" name="buy_now" class="btn-buy">Buy Now</button>
        </form>
      </div>
      <?php } ?>
    </div>
  </section>

  <footer>
    <p>© 2025 ShopEasy. All Rights Reserved.</p>
  </footer>
</body>
</html>
