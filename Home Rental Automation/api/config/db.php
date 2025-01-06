<?php
// backend/config/db.php

$host = "localhost"; // Database host
$dbname = "RentalManagement"; // Database name
$username = "root"; // Database username
$password = ""; // Database password

try {
    // Create PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
