<?php
/**
 * config.php - MySQL Database Connection Configuration
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Web Technology Practicals (WTP) - Course MC01094051
 */

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gmca_group10_db');
define('DB_PORT', 3306);

// 1. Procedural MySQLi Connection
function get_mysqli_connection() {
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}

// 2. Object-Oriented PDO Connection (Recommended Best Practice)
function get_pdo_connection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die("Database Connection Error (PDO): " . $e->getMessage());
    }
}
?>
