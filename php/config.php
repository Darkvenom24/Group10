<?php
/**
 * config.php - Unified Database Configuration (Cloud MySQL, Upstash/Vercel KV, Local SQLite)
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Web Technology Practicals (WTP) - Course MC01094051
 */

// 1. Resolve Remote MySQL Credentials from Environment Variables (if available)
$dbUrl = getenv('DATABASE_URL') ?: (getenv('MYSQL_URL') ?: (getenv('CLEARDB_DATABASE_URL') ?: ''));
$envHost = '';
$envUser = '';
$envPass = '';
$envName = '';
$envPort = 3306;
$envSsl  = false;

if (!empty($dbUrl) && preg_match('/^mysql/i', $dbUrl)) {
    $parsed = parse_url($dbUrl);
    if ($parsed) {
        $envHost = $parsed['host'] ?? '';
        $envUser = isset($parsed['user']) ? urldecode($parsed['user']) : '';
        $envPass = isset($parsed['pass']) ? urldecode($parsed['pass']) : '';
        $envName = ltrim($parsed['path'] ?? '', '/');
        $envPort = (int)($parsed['port'] ?? 3306);
        if (!empty($parsed['query']) && stripos($parsed['query'], 'ssl') !== false) {
            $envSsl = true;
        }
    }
}

$finalHost = $envHost ?: (getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: (getenv('MYSQL_HOST') ?: 'localhost')));
$finalUser = $envUser ?: (getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: (getenv('MYSQL_USER') ?: 'root')));
$finalPass = $envPass ?: (getenv('DB_PASS') ?: (getenv('MYSQLPASSWORD') ?: (getenv('MYSQL_PASSWORD') ?: '')));
$finalName = $envName ?: (getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: (getenv('MYSQL_DATABASE') ?: 'gmca_group10_db')));
$finalPort = $envPort ?: (int)(getenv('DB_PORT') ?: (getenv('MYSQLPORT') ?: (getenv('MYSQL_PORT') ?: 3306)));
$finalSsl  = $envSsl || filter_var(getenv('DB_SSL') ?: 'false', FILTER_VALIDATE_BOOLEAN);

if (!defined('DB_HOST')) define('DB_HOST', $finalHost);
if (!defined('DB_USER')) define('DB_USER', $finalUser);
if (!defined('DB_PASS')) define('DB_PASS', $finalPass);
if (!defined('DB_NAME')) define('DB_NAME', $finalName);
if (!defined('DB_PORT')) define('DB_PORT', $finalPort);
if (!defined('DB_SSL'))  define('DB_SSL',  $finalSsl);

// 2. Resolve Upstash Redis / Vercel KV REST Credentials (optional zero-config cloud persistence)
$kvUrl   = getenv('KV_REST_API_URL') ?: (getenv('UPSTASH_REDIS_REST_URL') ?: '');
$kvToken = getenv('KV_REST_API_TOKEN') ?: (getenv('UPSTASH_REDIS_REST_TOKEN') ?: '');
if (!defined('KV_REST_URL'))   define('KV_REST_URL', rtrim($kvUrl, '/'));
if (!defined('KV_REST_TOKEN')) define('KV_REST_TOKEN', $kvToken);

// 2b. Resolve Firebase Realtime Database REST Credentials (optional 1-variable Vercel cloud persistence)
$fbUrl  = getenv('FIREBASE_DB_URL') ?: (getenv('FIREBASE_DATABASE_URL') ?: '');
$fbAuth = getenv('FIREBASE_AUTH_SECRET') ?: (getenv('FIREBASE_DB_SECRET') ?: '');
if (!defined('FIREBASE_DB_URL'))     define('FIREBASE_DB_URL', rtrim($fbUrl, '/'));
if (!defined('FIREBASE_AUTH_SECRET')) define('FIREBASE_AUTH_SECRET', $fbAuth);

function is_vercel_env() {
    return !empty(getenv('VERCEL')) || !empty(getenv('VERCEL_ENV')) || !empty(getenv('NOW_REGION'));
}

function has_explicit_mysql_env() {
    $url = getenv('DATABASE_URL') ?: (getenv('MYSQL_URL') ?: (getenv('CLEARDB_DATABASE_URL') ?: ''));
    if (!empty($url) && preg_match('/^mysql/i', $url)) return true;
    return !empty(getenv('DB_HOST')) || !empty(getenv('MYSQLHOST')) || !empty(getenv('MYSQL_HOST'));
}

// 3. Procedural MySQLi Connection
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

// 4. Object-Oriented PDO Connection
function get_pdo_connection($dieOnError = true) {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 4,
    ];
    if (DB_SSL && defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Attempt database auto-creation if host is reachable and database doesn't exist yet
        if (strpos($e->getMessage(), '1049') !== false || stripos($e->getMessage(), 'Unknown database') !== false) {
            try {
                $baseDsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4;port=" . DB_PORT;
                $tmpPdo = new PDO($baseDsn, DB_USER, DB_PASS, $options);
                $safeDb = str_replace('`', '``', DB_NAME);
                $tmpPdo->exec("CREATE DATABASE IF NOT EXISTS `{$safeDb}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                return new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $inner) {
                if ($dieOnError) die("Database Connection Error (PDO): " . $inner->getMessage());
                throw $inner;
            }
        }
        if ($dieOnError) {
            die("Database Connection Error (PDO): " . $e->getMessage());
        }
        throw $e;
    }
}
?>
