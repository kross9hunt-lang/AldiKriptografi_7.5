<?php
// config/database.php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'yudisium_db';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Database Connection Failed: ' . $conn->connect_error);
}

// Set charset biar gak jadi tulisan hieroglif
$conn->set_charset('utf8mb4');
