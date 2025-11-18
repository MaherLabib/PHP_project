<?php


 $conn = new mysqli('localhost', 'root', '', 'shopeasy');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } 