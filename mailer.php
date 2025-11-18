<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "vendor/autoload.php";

$mail = new PHPMailer(true);
$mail ->isSMTP();
$mail->SMTPAuth = true;

$mail ->Host = "smtp.gmail.com";
$mail ->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail ->Port = 587;
$mail ->Password = "aglp ckje mddi xiko"; 
$mail ->Username = "maherlabib04@gmail.com"; 

$mail->isHTML(true);

return $mail;

