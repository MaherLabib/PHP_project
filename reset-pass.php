<?php

$token =$_GET['token'];
$token_hash = hash('sha256', $token);

require_once 'dbConnect.php';
session_start();

$sql ="SELECT * FROM users where reset_token_hash=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if($user==null){
    die('invalid token');
}
if(strtotime($user['reset_expires_at']) < time()){
    die('Token expired. Please request a new password reset.');
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $conform_password = password_hash($_POST['confirm_password'], PASSWORD_DEFAULT);
    if($_POST['new_password'] !== $_POST['confirm_password']){
       die('Passwords do not match. Please try again.');
    }
    $sql = "UPDATE users SET password=?, reset_token_hash=NULL, reset_expires_at=NULL WHERE reset_token_hash=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $token_hash);
    if($stmt->execute()){
        echo "<script>alert('Password has been reset successfully. You can now log in with your new password.'); window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to reset password. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | ShopEasy</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #16a085 0%, #2980b9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .reset-container {
            background: var(--background-white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        .reset-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .reset-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .reset-container button {
            width: 100%;
            padding: 10px;
            background-color: #0077ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .reset-container button:hover {
            background-color: #0077ff;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <form method="POST" action="">
            <input type="password" name="new_password" placeholder="Enter New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>