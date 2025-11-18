<?php
require 'dbConnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); 

   

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('No account found with this email.'); window.location.href='forget-pass.php';</script>";
        exit();
    }

    $stmt->close();

    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expire = date("Y-m-d H:i:s", time() + 60 * 30);

    $sql = "UPDATE users SET reset_token_hash = ?, reset_expires_at = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token_hash, $expire, $email);
    $stmt->execute();

    if ($stmt->affected_rows ) {
        require 'mailer.php';
        $mail->setFrom('maherlabib04@gmail.com', 'ShopEasy');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "
            <p>We received a request to reset your password. Click the link below to reset it:</p>
            <a href='http://localhost/php_project/reset-pass.php?token=$token&email=" . urlencode($email) . "'>Reset Password</a>
            <p>This link will expire in 30 minutes.</p>";
            try{
                $mail->send();
                echo "<script>alert('An email has been sent to your email address with password reset instructions.')</script>";
            }
            catch (Exception $e){
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - ShopEasy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <h2>Forgot Password</h2>
    <p class="subtext">Enter your registered email address and we’ll send you instructions to reset your password.</p>
    
    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" placeholder="Enter your email" required>

      <button type="submit">Send Reset Link</button>

      <p><a href="login.php" class="back-link">← Back to Login</a></p>
    </form>
  </div>
</body>
</html>
