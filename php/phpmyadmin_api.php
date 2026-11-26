<?php
/**
 * phpmyadmin_api.php - Unified Database API for Railway Portal, Auth, Laravel Hub & phpMyAdmin 5.2.1
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

ini_set('display_errors', '0');
error_reporting(E_ALL);

$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    $configPath = dirname(__DIR__) . '/php/config.php';
}
require_once $configPath;
require_once __DIR__ . '/unified_store.php';

$store = new UnifiedDbStore();

$rawInput = file_get_contents('php://input');
$payload = json_decode($rawInput, true);
if (!is_array($payload)) $payload = [];

$action = $_GET['action'] ?? $_POST['action'] ?? ($payload['action'] ?? 'db_structure');

function param_get($key, $default = null) {
    global $payload;
    if (isset($_GET[$key])) return $_GET[$key];
    if (isset($_POST[$key])) return $_POST[$key];
    if (isset($payload[$key])) return $payload[$key];
    return $default;
}

function normalize_date_str($val) {
    $s = trim((string)$val);
    if ($s === '') return date('Y-m-d');
    if (preg_match('/^\d{4}-\d{2}-\d{2}/', $s, $m)) {
        // Handle UTC ISO string like 2026-11-29T18:30:00.000Z by converting to IST (+05:30)
        if (strpos($s, 'T') !== false && substr($s, -1) === 'Z') {
            $ts = strtotime($s);
            if ($ts !== false) {
                $dt = new DateTime('@' . $ts);
                $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
                return $dt->format('Y-m-d');
            }
        }
        return $m[0];
    }
    $ts = strtotime($s);
    return $ts !== false ? date('Y-m-d', $ts) : date('Y-m-d');
}

function find_user_by_identifier($store, $identifier) {
    $norm = strtolower(trim((string)$identifier));
    if ($norm === '') return null;
    foreach ($store->getAllRows('users') as $u) {
        $un = strtolower((string)($u['username'] ?? ''));
        $en = strtolower((string)($u['enrollment_no'] ?? $u['enrollment'] ?? ''));
        $em = strtolower((string)($u['email'] ?? ''));
        if ($un === $norm || $en === $norm || $em === $norm) {
            return $u;
        }
    }
    return null;
}

function record_login_event($store, $user, $status = 'SUCCESS') {
    $now = date('Y-m-d H:i:s');
    $store->updateRowInternal('users', $user['id'], ['last_login' => $now]);
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Web Client', 0, 200);
    $store->insertRowInternal('login_activity', [
        'user_email' => $user['email'] ?? '',
        'enrollment' => $user['enrollment_no'] ?? '',
        'ip_address' => $ip,
        'user_agent' => $ua,
        'login_time' => $now,
        'status' => $status,
    ]);
}

// =============================================================================
// 1. REAL-TIME SYNC & BOOTSTRAP ENDPOINTS
// =============================================================================
if ($action === 'sync_version') {
    $bookings = $store->getAllRows('railway_bookings');
    echo json_encode([
        'success' => true,
        'data_version' => $store->getDataVersion(),
        'driver' => $store->driver,
        'is_cloud_persistent' => $store->isCloudPersistent,
        'persistence_label' => $store->persistenceLabel,
        'total_bookings' => count($bookings),
        'server_time' => date('Y-m-d H:i:s'),
    ]);
    exit;
}

if ($action === 'bootstrap' || $action === 'sync_state') {
    $users = array_map([$store, 'formatUserForClient'], $store->getAllRows('users'));
    $systems = $store->getAllRows('railway_systems');
    $bookingsRaw = $store->getAllRows('railway_bookings');

    // Deduplicate defensively by PNR
    $seenPnr = [];
    $bookings = [];
    foreach ($bookingsRaw as $b) {
        $pnrKey = strtoupper((string)($b['pnr'] ?? $b['id']));
        if (isset($seenPnr[$pnrKey])) continue;
        $seenPnr[$pnrKey] = true;
        $bookings[] = $store->formatBookingForClient($b);
    }

    echo json_encode([
        'success' => true,
        'data_version' => $store->getDataVersion(),
        'driver' => $store->driver,
        'is_cloud_persistent' => $store->isCloudPersistent,
        'persistence_label' => $store->persistenceLabel,
        'users' => $users,
        'systems' => $systems,
        'bookings' => $bookings,
        'server_time' => date('Y-m-d H:i:s'),
    ]);
    exit;
}

if ($action === 'get_bookings') {
    $bookingsRaw = $store->getAllRows('railway_bookings');
    $seenPnr = [];
    $bookings = [];
    foreach ($bookingsRaw as $b) {
        $pnrKey = strtoupper((string)($b['pnr'] ?? $b['id']));
        if (isset($seenPnr[$pnrKey])) continue;
        $seenPnr[$pnrKey] = true;
        $bookings[] = $store->formatBookingForClient($b);
    }

    echo json_encode([
        'success' => true,
        'data_version' => $store->getDataVersion(),
        'driver' => $store->driver,
        'is_cloud_persistent' => $store->isCloudPersistent,
        'persistence_label' => $store->persistenceLabel,
        'bookings' => $bookings,
    ]);
    exit;
}

// =============================================================================
// 2. UNIFIED AUTHENTICATION ENDPOINTS
// =============================================================================
if ($action === 'auth_login') {
    $identifier = trim((string)param_get('usernameOrEnroll', ''));
    $password = (string)param_get('password', '');
    $user = find_user_by_identifier($store, $identifier);

    if (!$user || (string)($user['password'] ?? '') !== $password) {
        echo json_encode(['success' => false, 'message' => 'Invalid username/enrollment or password.']);
        exit;
    }

    record_login_event($store, $user, 'SUCCESS');
    $fresh = find_user_by_identifier($store, $user['username']);
    echo json_encode([
        'success' => true,
        'user' => $store->formatUserForClient($fresh ?: $user),
        'data_version' => $store->getDataVersion(),
    ]);
    exit;
}

if ($action === 'auth_pin') {
    $identifier = trim((string)param_get('enrollmentOrId', ''));
    $enteredPin = trim((string)param_get('pin', ''));
    $user = find_user_by_identifier($store, $identifier);
    if (!$user) {
        foreach ($store->getAllRows('users') as $u) {
            if ((string)$u['id'] === $identifier) {
                $user = $u;
                break;
            }
        }
    }
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Profile not found.']);
        exit;
    }
    $expectedPin = (string)($user['pin'] ?? '1234');
    if ($enteredPin !== $expectedPin) {
        echo json_encode(['success' => false, 'message' => 'Incorrect 4-digit PIN! Please try again.']);
        exit;
    }
    record_login_event($store, $user, 'PIN_SUCCESS');
    $fresh = find_user_by_identifier($store, $user['username']);
    echo json_encode([
        'success' => true,
        'user' => $store->formatUserForClient($fresh ?: $user),
        'data_version' => $store->getDataVersion(),
    ]);
    exit;
}

if ($action === 'auth_quick_switch') {
    $identifier = trim((string)param_get('identifier', ''));
    $user = find_user_by_identifier($store, $identifier);
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User account not found.']);
        exit;
    }
    record_login_event($store, $user, 'QUICK_SWITCH');
    $fresh = find_user_by_identifier($store, $user['username']);
    echo json_encode([
        'success' => true,
        'user' => $store->formatUserForClient($fresh ?: $user),
        'data_version' => $store->getDataVersion(),
    ]);
    exit;
}

if ($action === 'auth_register' || $action === 'form_signup') {
    $fullName = trim((string)param_get('full_name', ''));
    $enroll = strtoupper(trim((string)param_get('enrollment_no', '')));
    $email = trim((string)param_get('email', ''));
    $username = strtolower(trim((string)param_get('username', '')));
    $password = (string)param_get('password', '');
    $role = trim((string)param_get('role', 'Student Member'));

    if ($fullName === '' || $enroll === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Full name, enrollment number, and password are required.']);
        exit;
    }
    if ($email === '') $email = strtolower($enroll) . '@gmca.ac.in';
    if ($username === '') $username = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $enroll));

    if (find_user_by_identifier($store, $enroll) || find_user_by_identifier($store, $username) || find_user_by_identifier($store, $email)) {
        echo json_encode(['success' => false, 'message' => 'An account with this Enrollment, Username, or Email already exists. Please login instead.']);
        exit;
    }

    $now = date('Y-m-d H:i:s');
    $res = $store->insertRowInternal('users', [
        'full_name' => $fullName,
        'enrollment_no' => $enroll,
        'email' => $email,
        'username' => $username,
        'password' => $password,
        'role' => $role ?: 'Student Member',
        'pin' => '1234',
        'system_code' => 'WR-ADI',
        'is_admin' => 0,
        'avatar' => 'temp.jpeg',
        'last_login' => $now,
        'created_at' => $now,
    ]);

    if (empty($res['success'])) {
        echo json_encode(['success' => false, 'message' => $res['error'] ?? 'Registration failed.']);
        exit;
    }

    $newUser = find_user_by_identifier($store, $enroll);
    if ($newUser) {
        record_login_event($store, $newUser, 'REGISTERED');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Registered successfully in unified database.',
        'user' => $newUser ? $store->formatUserForClient($newUser) : null,
        'data_version' => $store->getDataVersion(),
    ]);
    exit;
}

if ($action === 'form_login') {
    $identifier = trim((string)(param_get('usernameOrEnroll') ?: param_get('enrollment') ?: param_get('email') ?: ''));
    $user = find_user_by_identifier($store, $identifier);
    if ($user) {
        record_login_event($store, $user, 'SUCCESS');
    }
    echo json_encode(['success' => true, 'data_version' => $store->getDataVersion()]);
    exit;
}

// =============================================================================
// 3. UNIFIED RAILWAY BOOKING CRUD (DEDUPLICATED + ROLE-AUTHORIZED)
// =============================================================================
if ($action === 'save_booking' || $action === 'form_booking') {
    $pnr = strtoupper(trim((string)param_get('pnr', '')));
    if ($pnr === '') $pnr = 'PNR' . rand(100000, 999999);

    $passenger = trim((string)(param_get('passengerName') ?: param_get('passenger_name') ?: ''));
    $phone = trim((string)(param_get('phone') ?: '9876543210'));
    $train = trim((string)(param_get('train') ?: param_get('train_name') ?: '12957 - Swarna Jayanti Rajdhani'));
    $travelClass = trim((string)(param_get('travelClass') ?: param_get('travel_class') ?: 'SL'));
    $quota = trim((string)(param_get('quota') ?: 'GN'));
    $passengers = max(1, (int)(param_get('passengers') ?: 1));
    $date = normalize_date_str(param_get('date') ?: param_get('journey_date') ?: '');
    $totalFare = (float)(param_get('totalFare') ?: param_get('total_fare') ?: 500);

    $actorEnroll = strtoupper(trim((string)(param_get('actorEnrollment') ?: param_get('userEnrollment') ?: param_get('user_enrollment') ?: '')));
    $actorUser = $actorEnroll ? find_user_by_identifier($store, $actorEnroll) : null;
    if (!$actorUser) {
        $actorUser = find_user_by_identifier($store, 'ridham61');
    }

    $userEnroll = $actorUser ? strtoupper($actorUser['enrollment_no']) : ($actorEnroll ?: 'GUEST');
    $userId = $actorUser ? (int)$actorUser['id'] : null;
    $bookedBy = $actorUser
        ? ($actorUser['full_name'] . ' (' . $userEnroll . ')')
        : (param_get('bookedBy') ?: $passenger);
    $systemCode = trim((string)(param_get('systemCode') ?: param_get('system_code') ?: ($actorUser['system_code'] ?? 'WR-ADI')));

    $coachPrefix = ($travelClass === '1A') ? 'H1' : (($travelClass === '2A') ? 'A1' : (($travelClass === '3A') ? 'B2' : 'S4'));
    $coachSeat = trim((string)(param_get('coachSeat') ?: param_get('coach_seat') ?: ($coachPrefix . '-' . rand(1, 64))));
    $now = date('Y-m-d H:i:s');

    // Prevent duplicate PNR insertion! If same PNR exists, return/update existing row cleanly.
    $existing = null;
    foreach ($store->getAllRows('railway_bookings') as $row) {
        if (strtoupper((string)$row['pnr']) === $pnr) {
            $existing = $row;
            break;
        }
    }

    if ($existing) {
        echo json_encode([
            'success' => true,
            'deduplicated' => true,
            'booking' => $store->formatBookingForClient($existing),
            'data_version' => $store->getDataVersion(),
        ]);
        exit;
    }

    $record = [
        'pnr' => $pnr,
        'user_id' => $userId,
        'booked_by' => $bookedBy,
        'user_enrollment' => $userEnroll,
        'passenger_name' => $passenger,
        'phone' => $phone,
        'train_name' => $train,
        'travel_class' => $travelClass,
        'quota' => $quota,
        'passengers' => $passengers,
        'journey_date' => $date,
        'system_code' => $systemCode,
        'total_fare' => $totalFare,
        'status' => 'Confirmed',
        'coach_seat' => $coachSeat,
        'booking_date' => $now,
        'last_modified_by' => null,
        'updated_at' => null,
    ];

    $res = $store->insertRowInternal('railway_bookings', $record);
    if (empty($res['success'])) {
        echo json_encode(['success' => false, 'error' => $res['error'] ?? 'Failed to insert booking.']);
        exit;
    }

    $record['id'] = (int)($res['inserted_id'] ?? 0);
    echo json_encode([
        'success' => true,
        'message' => 'Booking saved to unified database.',
        'booking' => $store->formatBookingForClient($record),
        'data_version' => $store->getDataVersion(),
        'is_cloud_persistent' => $store->isCloudPersistent,
    ]);
    exit;
}

if ($action === 'update_booking') {
    $id = param_get('id');
    $pnr = strtoupper(trim((string)param_get('pnr', '')));
    $actorEnroll = strtoupper(trim((string)param_get('actorEnrollment', '')));
    $actor = $actorEnroll ? find_user_by_identifier($store, $actorEnroll) : null;
    $isAdmin = $actor && !empty($actor['is_admin']) && (int)$actor['is_admin'] === 1;

    $target = null;
    foreach ($store->getAllRows('railway_bookings') as $row) {
        if (($id !== null && (string)$row['id'] === (string)$id) || ($pnr !== '' && strtoupper((string)$row['pnr']) === $pnr)) {
            $target = $row;
            break;
        }
    }
    if (!$target) {
        echo json_encode(['success' => false, 'error' => 'Booking record not found.']);
        exit;
    }

    if (!$isAdmin && $actorEnroll !== '' && strtoupper((string)$target['user_enrollment']) !== $actorEnroll) {
        echo json_encode(['success' => false, 'error' => 'Permission denied: you can only modify your own reservations.']);
        exit;
    }

    $updates = [
        'passenger_name' => trim((string)(param_get('passengerName') ?: param_get('passenger_name') ?: $target['passenger_name'])),
        'phone' => trim((string)(param_get('phone') ?: $target['phone'])),
        'train_name' => trim((string)(param_get('train') ?: param_get('train_name') ?: $target['train_name'])),
        'travel_class' => trim((string)(param_get('travelClass') ?: param_get('travel_class') ?: $target['travel_class'])),
        'quota' => trim((string)(param_get('quota') ?: $target['quota'])),
        'passengers' => max(1, (int)(param_get('passengers') ?: $target['passengers'])),
        'journey_date' => normalize_date_str(param_get('date') ?: param_get('journey_date') ?: $target['journey_date']),
        'total_fare' => (float)(param_get('totalFare') ?: param_get('total_fare') ?: $target['total_fare']),
        'status' => trim((string)(param_get('status') ?: $target['status'])),
        'last_modified_by' => $actor ? $actor['full_name'] : ($actorEnroll ?: 'System'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $res = $store->updateRowInternal('railway_bookings', $target['id'], $updates);
    if (empty($res['success'])) {
        echo json_encode(['success' => false, 'error' => $res['error'] ?? 'Failed to update booking.']);
        exit;
    }

    $merged = array_merge($target, $updates);
    echo json_encode([
        'success' => true,
        'booking' => $store->formatBookingForClient($merged),
        'data_version' => $store->getDataVersion(),
    ]);
    exit;
}

if ($action === 'delete_booking') {
    $id = param_get('id');
    $pnr = strtoupper(trim((string)param_get('pnr', '')));
    $actorEnroll = strtoupper(trim((string)param_get('actorEnrollment', '')));
    $actor = $actorEnroll ? find_user_by_identifier($store, $actorEnroll) : null;
    $isAdmin = $actor && !empty($actor['is_admin']) && (int)$actor['is_admin'] === 1;

    $target = null;
    foreach ($store->getAllRows('railway_bookings') as $row) {
        if (($id !== null && (string)$row['id'] === (string)$id) || ($pnr !== '' && strtoupper((string)$row['pnr']) === $pnr)) {
            $target = $row;
            break;
        }
    }
    if (!$target) {
        echo json_encode(['success' => false, 'error' => 'Booking not found.']);
        exit;
    }
    if (!$isAdmin && $actorEnroll !== '' && strtoupper((string)$target['user_enrollment']) !== $actorEnroll) {
        echo json_encode(['success' => false, 'error' => 'Permission denied: only Station Master or owner can cancel this booking.']);
        exit;
    }

    $store->deleteRowInternal('railway_bookings', $target['id']);
    echo json_encode([
        'success' => true,
        'deleted_id' => $target['id'],
        'data_version' => $store->getDataVersion(),
        'is_cloud_persistent' => $store->isCloudPersistent,
    ]);
    exit;
}

// =============================================================================
// 4. PHPMYADMIN 5.2.1 EXPLORER ACTIONS (NORMALIZED RESPONSE KEYS)
// =============================================================================
if ($action === 'server_overview') {
    $tables = $store->getAllowedTables();
    $totalRows = 0;
    $totalBytes = 0;
    foreach ($tables as $t) {
        $cnt = count($store->getAllRows($t));
        $totalRows += $cnt;
        $totalBytes += 16384 + ($cnt * 256) + 16384;
    }
    $sizeKib = number_format($totalBytes / 1024, 1, '.', '');
    $hostLabel = ($store->driver === 'mysql')
        ? DB_HOST . ':' . DB_PORT
        : ($store->isJsonRestDriver() ? 'Cloud REST Database Cluster' : 'Unified Server Storage');

    $data = [
        'server' => ($store->driver === 'mysql') ? 'Cloud MySQL 8.0' : 'Unified Cloud Relational Engine',
        'version' => ($store->driver === 'mysql') ? '8.0.35-InnoDB' : '8.0.35-Unified',
        'host' => $hostLabel,
        'user' => ($store->driver === 'mysql') ? DB_USER : 'group10_admin@cloud-sql.internal',
        'active_db' => DB_NAME,
        'charset' => 'utf8mb4',
        'total_tables' => count($tables),
        'total_rows' => $totalRows,
        'total_size_kib' => $sizeKib,
        'databases' => [DB_NAME],
    ];

    echo json_encode([
        'success' => true,
        'driver' => $store->driver,
        'is_cloud_persistent' => $store->isCloudPersistent,
        'persistence_label' => $store->persistenceLabel,
        'data_version' => $store->getDataVersion(),
        'data' => $data,
        'server_version' => $data['version'],
        'active_database' => $data['active_db'],
    ]);
    exit;
}

if ($action === 'db_structure') {
    $tables = [];
    $totalRows = 0;
    $totalSizeKib = 0.0;
    foreach ($store->getAllowedTables() as $name) {
        $cnt = count($store->getAllRows($name));
        $dataLen = 16384 + ($cnt * 256);
        $idxLen = 16384;
        $kib = round(($dataLen + $idxLen) / 1024, 1);
        $totalRows += $cnt;
        $totalSizeKib += $kib;
        $tables[] = [
            'Name' => $name,
            'Rows' => $cnt,
            'Engine' => 'InnoDB',
            'Collation' => 'utf8mb4_unicode_ci',
            'Data_length' => $dataLen,
            'Index_length' => $idxLen,
            'Size_kib' => $kib,
            'Size_formatted' => $kib . ' KiB',
            'Overhead' => '-',
            'Comment' => '',
        ];
    }

    $totalSizeFormatted = round($totalSizeKib, 1);
    echo json_encode([
        'success' => true,
        'database' => DB_NAME,
        'driver' => $store->driver,
        'is_cloud_persistent' => $store->isCloudPersistent,
        'data_version' => $store->getDataVersion(),
        'tables' => $tables,
        'summary' => [
            'table_count' => count($tables),
            'total_rows' => $totalRows,
            'total_size_kib' => $totalSizeFormatted,
            'total_size_formatted' => $totalSizeFormatted . ' KiB',
            'default_engine' => 'InnoDB',
            'default_collation' => 'utf8mb4_unicode_ci',
        ],
    ]);
    exit;
}

if ($action === 'table_browse') {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('table', 'users'));
    if (!$store->isValidTable($table)) {
        echo json_encode(['success' => false, 'error' => "Table '{$table}' does not exist."]);
        exit;
    }
    $limit = max(1, min(200, (int)param_get('limit', 25)));
    $offset = max(0, (int)param_get('offset', 0));
    $sortCol = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('sort_col', ''));
    $sortDir = strtoupper((string)param_get('sort_dir', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
    $search = trim((string)param_get('search', ''));

    $start = microtime(true);
    $colsMeta = $store->getTableColumns($table);
    $columns = array_map(function($c) { return $c['Field']; }, $colsMeta);
    $rows = $store->getAllRows($table);

    if ($search !== '') {
        $kw = strtolower($search);
        $rows = array_values(array_filter($rows, function($r) use ($kw) {
            foreach ($r as $v) {
                if ($v !== null && strpos(strtolower((string)$v), $kw) !== false) {
                    return true;
                }
            }
            return false;
        }));
    }

    if ($sortCol !== '' && in_array($sortCol, $columns, true)) {
        usort($rows, function($a, $b) use ($sortCol, $sortDir) {
            $va = $a[$sortCol] ?? '';
            $vb = $b[$sortCol] ?? '';
            if (is_numeric($va) && is_numeric($vb)) {
                $cmp = $va <=> $vb;
            } else {
                $cmp = strcasecmp((string)$va, (string)$vb);
            }
            return $sortDir === 'DESC' ? -$cmp : $cmp;
        });
    }

    $totalRows = count($rows);
    $paged = array_slice($rows, $offset, $limit);
    $durSec = number_format(max(0.0004, microtime(true) - $start), 4);
    $executedSql = "SELECT * FROM `{$table}`" . ($search !== '' ? " WHERE ..." : "") . ($sortCol ? " ORDER BY `{$sortCol}` {$sortDir}" : "") . " LIMIT {$offset}, {$limit};";

    echo json_encode([
        'success' => true,
        'table' => $table,
        'query' => $executedSql,
        'executed_query' => $executedSql,
        'execution_time_seconds' => $durSec,
        'total_rows' => $totalRows,
        'offset' => $offset,
        'limit' => $limit,
        'columns' => $columns,
        'rows' => array_values($paged),
    ]);
    exit;
}

if ($action === 'table_structure') {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('table', 'users'));
    if (!$store->isValidTable($table)) {
        echo json_encode(['success' => false, 'error' => "Table '{$table}' does not exist."]);
        exit;
    }
    $columns = $store->getTableColumns($table);
    $rows = $store->getAllRows($table);
    $dataKib = round((16384 + count($rows) * 256) / 1024, 1);
    $idxKib = 16.0;

    echo json_encode([
        'success' => true,
        'table' => $table,
        'columns' => $columns,
        'indexes' => [
            ['Key_name' => 'PRIMARY', 'Type' => 'BTREE', 'Unique' => 'Yes', 'Column_name' => 'id', 'Cardinality' => count($rows)],
        ],
        'space_usage' => [
            'data_size' => $dataKib . ' KiB',
            'index_size' => $idxKib . ' KiB',
            'total_size' => ($dataKib + $idxKib) . ' KiB',
            'row_count' => count($rows),
        ],
    ]);
    exit;
}

if ($action === 'insert_row') {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('table', ''));
    $rowData = param_get('row') ?: param_get('data');
    if (!is_array($rowData)) {
        // Fallback when direct JSON payload fields are sent (e.g. Practical 11.3)
        $rowData = $payload;
        unset($rowData['action'], $rowData['table']);
    }
    $res = $store->insertRowInternal($table, $rowData);
    echo json_encode($res + ['data_version' => $store->getDataVersion()]);
    exit;
}

if ($action === 'update_row') {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('table', ''));
    $id = param_get('id');
    $rowData = param_get('row') ?: param_get('data') ?: [];
    if ($id === null && isset($rowData['id'])) $id = $rowData['id'];
    $res = $store->updateRowInternal($table, $id, $rowData);
    echo json_encode($res + ['data_version' => $store->getDataVersion()]);
    exit;
}

if ($action === 'delete_row') {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('table', ''));
    $id = param_get('id');
    $pk = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('primary_key', 'id'));
    $res = $store->deleteRowInternal($table, $id, $pk ?: 'id');
    echo json_encode($res + ['data_version' => $store->getDataVersion()]);
    exit;
}

if ($action === 'empty_table') {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string)param_get('table', ''));
    $res = $store->emptyTableInternal($table);
    echo json_encode($res + ['data_version' => $store->getDataVersion()]);
    exit;
}

if ($action === 'reset_seed') {
    $res = $store->resetSeedAll();
    echo json_encode($res + ['data_version' => $store->getDataVersion()]);
    exit;
}

if ($action === 'run_sql') {
    $sql = trim((string)(param_get('query') ?: param_get('sql') ?: ''));
    if ($sql === '') {
        echo json_encode(['success' => false, 'error' => '#1065 - Query was empty']);
        exit;
    }
    $start = microtime(true);
    $store->logSql($sql, 'SQL_RUN');

    if (!$store->isJsonRestDriver() && $store->pdo) {
        try {
            $firstWord = strtoupper(strtok(ltrim($sql), " \t\n\r;"));
            // Normalize MySQL SHOW TABLES / DESCRIBE for SQLite
            if ($store->driver !== 'mysql') {
                if (preg_match('/^SHOW\s+TABLES/i', $sql)) {
                    $tables = array_map(function($t) { return ['Tables_in_' . DB_NAME => $t]; }, $store->getAllowedTables());
                    echo json_encode([
                        'success' => true,
                        'query' => $sql,
                        'execution_time_seconds' => number_format(max(0.0005, microtime(true) - $start), 4),
                        'row_count' => count($tables),
                        'columns' => ['Tables_in_' . DB_NAME],
                        'rows' => $tables,
                    ]);
                    exit;
                }
                if (preg_match('/^(?:DESCRIBE|DESC|SHOW\s+FULL\s+COLUMNS\s+FROM|SHOW\s+COLUMNS\s+FROM)\s+[`\']?([a-zA-Z0-9_]+)[`\']?/i', $sql, $m)) {
                    $cols = $store->getTableColumns($m[1]);
                    echo json_encode([
                        'success' => true,
                        'query' => $sql,
                        'execution_time_seconds' => number_format(max(0.0005, microtime(true) - $start), 4),
                        'row_count' => count($cols),
                        'columns' => !empty($cols) ? array_keys($cols[0]) : [],
                        'rows' => $cols,
                    ]);
                    exit;
                }
            }

            $isSelect = in_array($firstWord, ['SELECT', 'SHOW', 'DESCRIBE', 'DESC', 'EXPLAIN', 'PRAGMA'], true);
            $stmt = $store->pdo->prepare($sql);
            $stmt->execute();
            $dur = number_format(max(0.0005, microtime(true) - $start), 4);
            if ($isSelect) {
                $rows = $stmt->fetchAll() ?: [];
                $columns = !empty($rows) ? array_keys($rows[0]) : [];
                echo json_encode([
                    'success' => true,
                    'query' => $sql,
                    'execution_time_seconds' => $dur,
                    'row_count' => count($rows),
                    'total_rows' => count($rows),
                    'columns' => $columns,
                    'rows' => $rows,
                ]);
                exit;
            } else {
                $affected = $stmt->rowCount();
                $store->bumpDataVersion();
                echo json_encode([
                    'success' => true,
                    'query' => $sql,
                    'execution_time_seconds' => $dur,
                    'affected_rows' => $affected,
                    'message' => "Query executed successfully. ({$affected} row(s) affected.)",
                ]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'query' => $sql,
                'error' => '#' . $e->getCode() . ' - ' . $e->getMessage(),
            ]);
            exit;
        }
    }

    // KV REST basic SQL interpreter
    if (preg_match('/^SELECT\s+.+?\s+FROM\s+[`\']?([a-zA-Z0-9_]+)[`\']?/i', $sql, $m)) {
        $t = strtolower($m[1]);
        if (!$store->isValidTable($t)) {
            echo json_encode(['success' => false, 'error' => "#1146 - Table '{$t}' doesn't exist"]);
            exit;
        }
        $rows = $store->getAllRows($t);
        $cols = !empty($rows) ? array_keys($rows[0]) : [];
        echo json_encode([
            'success' => true,
            'query' => $sql,
            'execution_time_seconds' => '0.0012',
            'row_count' => count($rows),
            'columns' => $cols,
            'rows' => array_slice($rows, 0, 100),
        ]);
        exit;
    }
    echo json_encode([
        'success' => true,
        'query' => $sql,
        'execution_time_seconds' => '0.0010',
        'message' => 'Query logged.',
    ]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Unknown action: ' . htmlspecialchars((string)$action)]);
exit;
?>
