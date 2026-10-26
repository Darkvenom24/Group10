<?php
/**
 * config.php - MySQL Database Connection Configuration
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Web Technology Practicals (WTP) - Course MC01094051
 * Supports both local XAMPP and Cloud Remote SQL on Vercel / Railway / PlanetScale / Aiven
 */

// 1. Resolve Remote Database Credentials from Environment Variables (if available)
$dbUrl = getenv('DATABASE_URL') ?: (getenv('MYSQL_URL') ?: (getenv('CLEARDB_DATABASE_URL') ?: ''));
$envHost = '';
$envUser = '';
$envPass = '';
$envName = '';
$envPort = 3306;

if (!empty($dbUrl)) {
    $parsed = parse_url($dbUrl);
    if ($parsed) {
        $envHost = $parsed['host'] ?? '';
        $envUser = $parsed['user'] ?? '';
        $envPass = $parsed['pass'] ?? '';
        $envName = ltrim($parsed['path'] ?? '', '/');
        $envPort = (int)($parsed['port'] ?? 3306);
    }
}

$finalHost = $envHost ?: (getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: (getenv('MYSQL_HOST') ?: 'localhost')));
$finalUser = $envUser ?: (getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: (getenv('MYSQL_USER') ?: 'root')));
$finalPass = $envPass ?: (getenv('DB_PASS') ?: (getenv('MYSQLPASSWORD') ?: (getenv('MYSQL_PASSWORD') ?: '')));
$finalName = $envName ?: (getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: (getenv('MYSQL_DATABASE') ?: 'gmca_group10_db')));
$finalPort = $envPort ?: (int)(getenv('DB_PORT') ?: (getenv('MYSQLPORT') ?: (getenv('MYSQL_PORT') ?: 3306)));

if (!defined('DB_HOST')) define('DB_HOST', $finalHost);
if (!defined('DB_USER')) define('DB_USER', $finalUser);
if (!defined('DB_PASS')) define('DB_PASS', $finalPass);
if (!defined('DB_NAME')) define('DB_NAME', $finalName);
if (!defined('DB_PORT')) define('DB_PORT', $finalPort);

// 2. Procedural MySQLi Connection
function get_mysqli_connection($dieOnError = true) {
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (!$conn) {
        if ($dieOnError) {
            die("Connection Failed: " . mysqli_connect_error());
        }
        return false;
    }
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}

// 3. Object-Oriented PDO Connection (Recommended Best Practice)
function get_pdo_connection($dieOnError = true) {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5,
    ];
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if ($dieOnError) {
            die("Database Connection Error (PDO): " . $e->getMessage());
        }
        throw $e;
    }
}
?>
