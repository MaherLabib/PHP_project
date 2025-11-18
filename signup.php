<?php
require_once 'dbConnect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   $name =    $_POST['name'];
    $email =    $_POST['email'];
    $number = $_POST['number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_password = password_hash($_POST['confirm_password'], PASSWORD_DEFAULT);
    if($_POST['password'] !== $_POST['confirm_password']){
      echo "<script alert('Passwords do not match. Please try again.');</script>";
    }


    $checkEmail = "SELECT * From users where email = ?";
    $check = $conn->prepare($checkEmail);
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if($result->num_rows > 0){
        echo "<script>alert('Email already exists. Please use a different email.'); window.location.href='signup.php';</script>";
        exit();
    }

        $stmt = $conn->prepare("INSERT INTO users( full_name, email, number, password) VALUES ( ?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $number, $password,);
        $stmt->execute();
        header("Location: login.php");
        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - ShopEasy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <h2>Create Account</h2>
    <form method="POST" action="signup.php">
      <label>Full Name</label>
      <input type="text"  name="name" placeholder="Enter your name" required>
      <label>Email</label>
      <input type="email" name="email" placeholder="Enter your email" required>
      <label>Number</label>
      <input type="number" name="number" placeholder="Enter your mobile number" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="Create password" required>
      <label>Conform Password</label>
      <input type="password" name="confirm_password" placeholder="Enter password " required>
      <button type="submit">Sign Up</button>
      <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>
</body>
</html>
