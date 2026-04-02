<?php
// ============================================
// File: config/database.php
// Database configuration with PDO
// ============================================
$host = 'sql100.infinityfree.com';
$dbname = 'if0_41561598_jamlags_shop';
$username = 'if0_41561598';
$password = 'Staylegit2026';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>