<?php
/**
 * db_explorer.php - phpMyAdmin-Inspired Live Database Explorer Backend API
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Web Technology Practicals (WTP) - Course MC01094051
 * 
 * Provides JSON endpoints for:
 * 1. list_tables   : SHOW TABLE STATUS / SHOW TABLES + SELECT DATABASE()
 * 2. table_details : DESCRIBE <table_name> + SELECT * FROM <table_name> LIMIT 200
 * 3. run_query     : Custom query runner (SELECT, SHOW, DESCRIBE, EXPLAIN) + execution time
 */

// Enable CORS and JSON output
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Ensure error notices don't corrupt JSON output
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Locate and require config.php
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    $configPath = dirname(__DIR__) . '/php/config.php';
}

if (!file_exists($configPath)) {
    echo json_encode([
        'success' => false,
        'error' => 'Database configuration file (config.php) not found.'
    ]);
    exit;
}

require_once $configPath;

// Establish database connection with exception handling
try {
    $pdo = get_pdo_connection(false);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'is_connection_error' => true,
        'error' => 'Database Connection Failed: ' . $e->getMessage(),
        'host' => defined('DB_HOST') ? DB_HOST : 'unknown',
        'database' => defined('DB_NAME') ? DB_NAME : 'unknown',
        'suggestion' => 'Check database host, user, password, and port in config.php or environment variables.'
    ]);
    exit;
}

// Determine action
$action = $_GET['action'] ?? $_POST['action'] ?? 'list_tables';

// =============================================================================
// ACTION 1: list_tables
// =============================================================================
if ($action === 'list_tables') {
    try {
        // Query active database, version, and user
        $dbName = $pdo->query("SELECT DATABASE()")->fetchColumn() ?: DB_NAME;
        $version = $pdo->query("SELECT VERSION()")->fetchColumn() ?: 'MySQL Unknown';
        $user = $pdo->query("SELECT USER()")->fetchColumn() ?: DB_USER;

        $tables = [];
        $totalRowsAll = 0;

        // Try SHOW TABLE STATUS first to get engine, rows, collation, size
        $statusStmt = $pdo->query("SHOW TABLE STATUS");
        $statusRows = $statusStmt->fetchAll();

        if (!empty($statusRows)) {
            foreach ($statusRows as $row) {
                // Approximate row count from status or fallback to accurate count
                $tblName = $row['Name'] ?? $row['name'] ?? '';
                if (empty($tblName)) continue;

                $engine = $row['Engine'] ?? 'InnoDB';
                $dataLength = (int)($row['Data_length'] ?? 0);
                $indexLength = (int)($row['Index_length'] ?? 0);
                $sizeKb = round(($dataLength + $indexLength) / 1024, 1);
                $collation = $row['Collation'] ?? 'utf8mb4_unicode_ci';

                // Get exact row count
                $rowCount = 0;
                try {
                    $cntStmt = $pdo->query("SELECT COUNT(*) FROM `" . str_replace("`", "``", $tblName) . "`");
                    $rowCount = (int)$cntStmt->fetchColumn();
                } catch (Exception $e) {
                    $rowCount = (int)($row['Rows'] ?? 0);
                }

                $totalRowsAll += $rowCount;

                $tables[] = [
                    'name' => $tblName,
                    'engine' => $engine,
                    'rows' => $rowCount,
                    'size_kb' => $sizeKb,
                    'collation' => $collation,
                    'create_time' => $row['Create_time'] ?? null
                ];
            }
        } else {
            // Fallback: SHOW TABLES
            $tblStmt = $pdo->query("SHOW TABLES");
            while ($tblRow = $tblStmt->fetch(PDO::FETCH_NUM)) {
                $tblName = $tblRow[0];
                $cntStmt = $pdo->query("SELECT COUNT(*) FROM `" . str_replace("`", "``", $tblName) . "`");
                $rowCount = (int)$cntStmt->fetchColumn();
                $totalRowsAll += $rowCount;

                $tables[] = [
                    'name' => $tblName,
                    'engine' => 'InnoDB',
                    'rows' => $rowCount,
                    'size_kb' => 16.0,
                    'collation' => 'utf8mb4_unicode_ci',
                    'create_time' => null
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'mode' => 'live_mysql',
            'server' => $version,
            'database' => $dbName,
            'host' => DB_HOST . ':' . DB_PORT,
            'user' => $user,
            'total_tables' => count($tables),
            'total_rows' => $totalRowsAll,
            'tables' => $tables,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error listing tables: ' . $e->getMessage()
        ]);
        exit;
    }
}

// =============================================================================
// ACTION 2: table_details
// =============================================================================
if ($action === 'table_details') {
    $table = trim($_GET['table'] ?? $_POST['table'] ?? '');

    // Strict validation: Table names must be alphanumeric and underscores only
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid table name provided.'
        ]);
        exit;
    }

    try {
        $quotedTable = "`" . str_replace("`", "``", $table) . "`";

        // 1. Structure Schema: DESCRIBE table
        $descStmt = $pdo->query("DESCRIBE " . $quotedTable);
        $structure = $descStmt->fetchAll();

        // 2. Indexes: SHOW INDEX FROM table
        $indexes = [];
        try {
            $idxStmt = $pdo->query("SHOW INDEX FROM " . $quotedTable);
            $idxRows = $idxStmt->fetchAll();
            foreach ($idxRows as $idx) {
                $indexes[] = [
                    'key_name' => $idx['Key_name'] ?? '',
                    'column_name' => $idx['Column_name'] ?? '',
                    'non_unique' => (int)($idx['Non_unique'] ?? 0),
                    'index_type' => $idx['Index_type'] ?? 'BTREE'
                ];
            }
        } catch (Exception $e) {
            $indexes = [];
        }

        // 3. Total Rows
        $cntStmt = $pdo->query("SELECT COUNT(*) FROM " . $quotedTable);
        $totalRows = (int)$cntStmt->fetchColumn();

        // 4. Browse Data: SELECT * FROM table LIMIT 200
        $limit = isset($_GET['limit']) ? min(500, max(1, (int)$_GET['limit'])) : 200;
        $offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

        $startTime = microtime(true);
        $dataStmt = $pdo->query("SELECT * FROM " . $quotedTable . " LIMIT " . $limit . " OFFSET " . $offset);
        $rows = $dataStmt->fetchAll();
        $queryTime = round((microtime(true) - $startTime) * 1000, 2);

        // Dynamically get column names from DESCRIBE or first row
        $columns = [];
        foreach ($structure as $field) {
            $columns[] = $field['Field'];
        }

        echo json_encode([
            'success' => true,
            'table' => $table,
            'columns' => $columns,
            'structure' => $structure,
            'indexes' => $indexes,
            'rows' => $rows,
            'total_rows' => $totalRows,
            'limit' => $limit,
            'offset' => $offset,
            'query_time_ms' => $queryTime,
            'executed_query' => "SELECT * FROM " . $quotedTable . " LIMIT " . $limit . ";"
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to load table details: ' . $e->getMessage()
        ]);
        exit;
    }
}

// =============================================================================
// ACTION 3: run_query
// =============================================================================
if ($action === 'run_query') {
    // Read input from POST JSON or form data
    $rawInput = file_get_contents('php://input');
    $postJson = json_decode($rawInput, true);

    $sql = trim($_POST['query'] ?? ($postJson['query'] ?? ($_GET['query'] ?? '')));

    if (empty($sql)) {
        echo json_encode([
            'success' => false,
            'error' => 'No SQL query was specified for execution.'
        ]);
        exit;
    }

    // Security Safety Check: Block catastrophic server-level commands
    $dangerousKeywords = ['DROP DATABASE', 'SHUTDOWN', 'GRANT ALL', 'REVOKE ALL', 'RESET MASTER', 'FLUSH PRIVILEGES'];
    foreach ($dangerousKeywords as $bad) {
        if (stripos($sql, $bad) !== false) {
            echo json_encode([
                'success' => false,
                'error' => 'Security Error: Operation "' . $bad . '" is blocked for safety in this educational evaluation demo.',
                'error_code' => 403
            ]);
            exit;
        }
    }

    $startTime = microtime(true);

    try {
        // Detect if query is a result-returning query (SELECT, SHOW, DESCRIBE, EXPLAIN)
        $cleanSql = ltrim($sql);
        $firstWord = strtoupper(strtok($cleanSql, " \t\n\r;"));

        $isSelectType = in_array($firstWord, ['SELECT', 'SHOW', 'DESCRIBE', 'DESC', 'EXPLAIN', 'CHECK']);

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        if ($isSelectType) {
            $rows = $stmt->fetchAll();
            $rowCount = count($rows);

            // Extract column names dynamically
            $columns = [];
            if ($rowCount > 0) {
                $columns = array_keys($rows[0]);
            } else {
                // Fetch column meta if empty result set
                $colCount = $stmt->columnCount();
                for ($i = 0; $i < $colCount; $i++) {
                    $meta = $stmt->getColumnMeta($i);
                    if ($meta && isset($meta['name'])) {
                        $columns[] = $meta['name'];
                    }
                }
            }

            echo json_encode([
                'success' => true,
                'query_type' => 'SELECT',
                'query' => $sql,
                'execution_time_ms' => $executionTime,
                'row_count' => $rowCount,
                'columns' => $columns,
                'rows' => $rows
            ]);
            exit;
        } else {
            // Action query (INSERT, UPDATE, DELETE, CREATE TABLE, ALTER TABLE)
            $affected = $stmt->rowCount();
            echo json_encode([
                'success' => true,
                'query_type' => 'DML',
                'query' => $sql,
                'execution_time_ms' => $executionTime,
                'affected_rows' => $affected,
                'message' => 'Query executed successfully. ' . $affected . ' row(s) affected.'
            ]);
            exit;
        }

    } catch (PDOException $e) {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        echo json_encode([
            'success' => false,
            'query' => $sql,
            'error' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'execution_time_ms' => $executionTime
        ]);
        exit;
    }
}

// Invalid action
echo json_encode([
    'success' => false,
    'error' => 'Unknown action requested: ' . htmlspecialchars($action)
]);
exit;
