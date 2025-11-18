<?php 
require 'dbConnect.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ShopEasy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <h2>Login</h2>
    <form method="POST" action="login.php">
      <label>Email</label>
      <input type="email" name="email" placeholder="Enter your email" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="Enter your password" required>
      <div class="forgot-section">
        <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
      </div>
      <button type="submit">Login</button>
      <p>Don’t have an account? <a href="signup.php">Sign Up</a></p>
    </form>
  </div>
</body>
</html>
