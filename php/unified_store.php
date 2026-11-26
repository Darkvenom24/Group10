<?php
/**
 * unified_store.php - Unified Relational Store for MySQL, Upstash/Vercel KV, and SQLite
 * Group 10 — Government MCA College, Maninagar (GMCA)
 *
 * Ensures one shared, deduplicated, role-authenticated database across:
 * - Practical 3 Railway Portal & Railway Records
 * - User Authentication (Login, PIN, Registration, Activity Audit)
 * - phpMyAdmin 5.2.1 Cloud Database Explorer
 * - Laravel Hub (laravel-hub.html)
 */

class UnifiedDbStore {
    /** @var string 'mysql'|'kv_rest'|'sqlite'|'ephemeral_sqlite' */
    public $driver = 'sqlite';
    /** @var bool */
    public $isCloudPersistent = false;
    /** @var string */
    public $persistenceLabel = '';
    /** @var PDO|null */
    public $pdo = null;
    /** @var string */
    public $lastError = '';

    private static $ALLOWED_TABLES = [
        'users',
        'railway_systems',
        'railway_bookings',
        'login_activity',
        'practical7_students',
        'user_profiles',
        'sql_query_logs',
    ];

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        // 1. Try MySQL if explicitly configured OR if running locally (non-Vercel)
        $tryMysql = has_explicit_mysql_env() || !is_vercel_env();
        if ($tryMysql && class_exists('PDO')) {
            try {
                $this->pdo = get_pdo_connection(false);
                $this->driver = 'mysql';
                $this->isCloudPersistent = true;
                $this->persistenceLabel = has_explicit_mysql_env()
                    ? 'Cloud MySQL Connected (' . DB_HOST . ')'
                    : 'Local MySQL Connected (' . DB_HOST . ')';
                $this->ensureSqlSchemaAndSeed();
                return;
            } catch (Exception $e) {
                $this->lastError = $e->getMessage();
                $this->pdo = null;
            }
        }

        // 2. Try Upstash Redis / Vercel KV REST API (Shared Cloud Persistence for Vercel)
        if (!empty(KV_REST_URL) && !empty(KV_REST_TOKEN) && function_exists('curl_init')) {
            $this->driver = 'kv_rest';
            $this->isCloudPersistent = true;
            $this->persistenceLabel = 'Cloud KV Database Connected (Real-Time Sync)';
            $this->ensureJsonStoreSeed();
            return;
        }

        // 2b. Try Firebase Realtime Database REST API (Shared Cloud Persistence for Vercel)
        if (!empty(FIREBASE_DB_URL) && function_exists('curl_init')) {
            $this->driver = 'firebase_rest';
            $this->isCloudPersistent = true;
            $this->persistenceLabel = 'Cloud Firebase RTDB Connected (Real-Time Sync)';
            $this->ensureJsonStoreSeed();
            return;
        }

        // 3. Fallback to SQLite (Persistent on local/VPS PHP server; ephemeral /tmp on Vercel without env vars)
        $isVercel = is_vercel_env();
        $sqlitePath = $isVercel
            ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'gmca_group10_unified.sqlite'
            : __DIR__ . DIRECTORY_SEPARATOR . 'gmca_unified.sqlite';

        try {
            $this->pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $this->pdo->exec('PRAGMA journal_mode = WAL;');
            $this->pdo->exec('PRAGMA busy_timeout = 5000;');
            if ($isVercel) {
                $this->driver = 'ephemeral_sqlite';
                $this->isCloudPersistent = false;
                $this->persistenceLabel = 'Ephemeral Server Sync (Add KV_REST_API_URL, FIREBASE_DB_URL, or DATABASE_URL on Vercel for permanent survival)';
            } else {
                $this->driver = 'sqlite';
                $this->isCloudPersistent = true;
                $this->persistenceLabel = 'Unified Server SQLite Database (LAN/Local Shared)';
            }
            $this->ensureSqlSchemaAndSeed();
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
        }
    }

    public function getAllowedTables() {
        return self::$ALLOWED_TABLES;
    }

    public function isValidTable($table) {
        return in_array($table, self::$ALLOWED_TABLES, true);
    }

    // =========================================================================
    // DETERMINISTIC SEED DATASETS (5 Users + 4 Division Admins, 5 Systems, 525 Bookings)
    // =========================================================================
    public static function getSeedUsers() {
        return [
            ['id' => 1, 'full_name' => 'Ridham Bambhaniya', 'enrollment_no' => '26GMCA61', 'email' => 'ridham.26gmca61@gmca.ac.in', 'username' => 'ridham61', 'password' => 'password123', 'role' => 'Team Lead', 'pin' => '0101', 'system_code' => 'WR-ADI', 'is_admin' => 0, 'avatar' => 'ridham.jpeg', 'last_login' => '2026-11-23 18:30:00', 'created_at' => '2026-08-15 10:30:00'],
            ['id' => 2, 'full_name' => 'Riya Thakkar', 'enrollment_no' => '26GMCA33', 'email' => 'riya.26gmca33@gmca.ac.in', 'username' => 'riya33', 'password' => 'password123', 'role' => 'Student Member', 'pin' => '9898', 'system_code' => 'CR-CSMT', 'is_admin' => 0, 'avatar' => 'riya.jpeg', 'last_login' => '2026-11-23 17:45:00', 'created_at' => '2026-08-15 10:30:00'],
            ['id' => 3, 'full_name' => 'Vaibhav Senjaliya', 'enrollment_no' => '26GMCA52', 'email' => 'vaibhav.26gmca52@gmca.ac.in', 'username' => 'vaibhav52', 'password' => 'password123', 'role' => 'Developer', 'pin' => '9543', 'system_code' => 'WR-ADI', 'is_admin' => 0, 'avatar' => 'vaibhav.jpeg', 'last_login' => '2026-11-23 19:15:00', 'created_at' => '2026-08-15 10:30:00'],
            ['id' => 4, 'full_name' => 'K. P. Rathod (Station Master)', 'enrollment_no' => 'SM-ADI-01', 'email' => 'station_master@railways.gov.in', 'username' => 'station_master', 'password' => 'password123', 'role' => 'Station Master (Admin)', 'pin' => '0000', 'system_code' => 'WR-ADI', 'is_admin' => 1, 'avatar' => 'temp.jpeg', 'last_login' => '2026-11-23 20:00:00', 'created_at' => '2026-08-01 08:00:00'],
            ['id' => 5, 'full_name' => 'Aarav Patel', 'enrollment_no' => '26GMCA05', 'email' => 'aarav.patel@gmca.ac.in', 'username' => 'aarav05', 'password' => 'password123', 'role' => 'Passenger Member', 'pin' => '1234', 'system_code' => 'NR-NDLS', 'is_admin' => 0, 'avatar' => 'temp.jpeg', 'last_login' => '2026-11-23 16:20:00', 'created_at' => '2026-08-20 14:15:00'],
            ['id' => 6, 'full_name' => 'Rajesh Sharma (Station Master NDLS)', 'enrollment_no' => 'SM-NDLS-02', 'email' => 'sm.ndls@railways.gov.in', 'username' => 'station_master_ndls', 'password' => 'password123', 'role' => 'Station Master (Admin)', 'pin' => '0000', 'system_code' => 'NR-NDLS', 'is_admin' => 1, 'avatar' => 'temp.jpeg', 'last_login' => '2026-11-23 12:00:00', 'created_at' => '2026-08-01 08:00:00'],
            ['id' => 7, 'full_name' => 'Sanjay Deshmukh (Station Master CSMT)', 'enrollment_no' => 'SM-CSMT-03', 'email' => 'sm.csmt@railways.gov.in', 'username' => 'station_master_csmt', 'password' => 'password123', 'role' => 'Station Master (Admin)', 'pin' => '0000', 'system_code' => 'CR-CSMT', 'is_admin' => 1, 'avatar' => 'temp.jpeg', 'last_login' => '2026-11-23 12:00:00', 'created_at' => '2026-08-01 08:00:00'],
            ['id' => 8, 'full_name' => 'M. S. Ramanathan (Station Master MAS)', 'enrollment_no' => 'SM-MAS-04', 'email' => 'sm.mas@railways.gov.in', 'username' => 'station_master_mas', 'password' => 'password123', 'role' => 'Station Master (Admin)', 'pin' => '0000', 'system_code' => 'SR-MAS', 'is_admin' => 1, 'avatar' => 'temp.jpeg', 'last_login' => '2026-11-23 12:00:00', 'created_at' => '2026-08-01 08:00:00'],
            ['id' => 9, 'full_name' => 'Subhashish Roy (Station Master HWH)', 'enrollment_no' => 'SM-HWH-05', 'email' => 'sm.hwh@railways.gov.in', 'username' => 'station_master_hwh', 'password' => 'password123', 'role' => 'Station Master (Admin)', 'pin' => '0000', 'system_code' => 'ER-HWH', 'is_admin' => 1, 'avatar' => 'temp.jpeg', 'last_login' => '2026-11-23 12:00:00', 'created_at' => '2026-08-01 08:00:00'],
        ];
    }

    public static function getSeedSystems() {
        return [
            ['id' => 1, 'code' => 'WR-ADI', 'name' => 'Western Railway - Ahmedabad Division', 'zone' => 'Western Railway', 'division' => 'Ahmedabad (ADI)', 'headquarters' => 'Ahmedabad, Gujarat', 'station_master_name' => 'K. P. Rathod', 'station_master_username' => 'station_master', 'badge' => '#1E3A8A'],
            ['id' => 2, 'code' => 'NR-NDLS', 'name' => 'Northern Railway - New Delhi Division', 'zone' => 'Northern Railway', 'division' => 'New Delhi (NDLS)', 'headquarters' => 'New Delhi', 'station_master_name' => 'Rajesh Sharma', 'station_master_username' => 'station_master_ndls', 'badge' => '#0F766E'],
            ['id' => 3, 'code' => 'CR-CSMT', 'name' => 'Central Railway - Mumbai CSMT Division', 'zone' => 'Central Railway', 'division' => 'Mumbai CSMT', 'headquarters' => 'Mumbai, Maharashtra', 'station_master_name' => 'Sanjay Deshmukh', 'station_master_username' => 'station_master_csmt', 'badge' => '#B45309'],
            ['id' => 4, 'code' => 'SR-MAS', 'name' => 'Southern Railway - Chennai Central Division', 'zone' => 'Southern Railway', 'division' => 'Chennai Central (MAS)', 'headquarters' => 'Chennai, Tamil Nadu', 'station_master_name' => 'M. S. Ramanathan', 'station_master_username' => 'station_master_mas', 'badge' => '#15803D'],
            ['id' => 5, 'code' => 'ER-HWH', 'name' => 'Eastern Railway - Howrah Division', 'zone' => 'Eastern Railway', 'division' => 'Howrah (HWH)', 'headquarters' => 'Kolkata, West Bengal', 'station_master_name' => 'Subhashish Roy', 'station_master_username' => 'station_master_hwh', 'badge' => '#C2410C'],
        ];
    }

    public static function getSeedBookings() {
        $users = [
            ['id' => 1, 'name' => 'Ridham Bambhaniya', 'enroll' => '26GMCA61', 'sys' => 'WR-ADI'],
            ['id' => 2, 'name' => 'Riya Thakkar', 'enroll' => '26GMCA33', 'sys' => 'CR-CSMT'],
            ['id' => 3, 'name' => 'Vaibhav Senjaliya', 'enroll' => '26GMCA52', 'sys' => 'WR-ADI'],
            ['id' => 4, 'name' => 'K. P. Rathod (Station Master)', 'enroll' => 'SM-ADI-01', 'sys' => 'WR-ADI'],
            ['id' => 5, 'name' => 'Aarav Patel', 'enroll' => '26GMCA05', 'sys' => 'NR-NDLS'],
        ];
        $systems = ['WR-ADI', 'NR-NDLS', 'CR-CSMT', 'SR-MAS', 'ER-HWH'];
        $trains = [
            'WR-ADI' => [
                ['name' => '12957 - Swarna Jayanti Rajdhani', 'base' => 2400],
                ['name' => '22953 - Gujarat SF Express', 'base' => 450],
                ['name' => '12009 - Vande Bharat Express', 'base' => 1400],
                ['name' => '19038 - Avadh Express', 'base' => 750],
            ],
            'NR-NDLS' => [
                ['name' => '12424 - Dibrugarh Rajdhani', 'base' => 3100],
                ['name' => '22436 - Vande Bharat Varanasi', 'base' => 1750],
                ['name' => '12004 - Lucknow Shatabdi', 'base' => 1150],
            ],
            'CR-CSMT' => [
                ['name' => '22221 - CSMT Rajdhani Express', 'base' => 2850],
                ['name' => '12123 - Deccan Queen Superfast', 'base' => 390],
                ['name' => '12137 - Punjab Mail', 'base' => 890],
            ],
            'SR-MAS' => [
                ['name' => '12621 - Tamil Nadu Express', 'base' => 2700],
                ['name' => '20643 - Vande Bharat Coimbatore', 'base' => 1550],
                ['name' => '12675 - Kovai Superfast Express', 'base' => 480],
            ],
            'ER-HWH' => [
                ['name' => '12301 - Howrah Rajdhani Express', 'base' => 2950],
                ['name' => '22301 - Vande Bharat Jalpaiguri', 'base' => 1600],
                ['name' => '12321 - Mumbai Mail via Chheoki', 'base' => 920],
            ],
        ];
        $classes = ['1A', '2A', '3A', 'SL', 'CC', 'EC'];
        $statuses = ['Confirmed', 'Confirmed', 'Confirmed', 'RAC', 'Waiting List'];
        $quotas = ['GN', 'GN', 'GN', 'TQ', 'LD'];
        $coaches = ['1A' => 'H1', '2A' => 'A1', '3A' => 'B2', 'SL' => 'S4', 'CC' => 'C1', 'EC' => 'E1'];
        $mults = ['1A' => 3.5, '2A' => 2.5, '3A' => 1.8, 'SL' => 1.0, 'CC' => 1.2, 'EC' => 3.0];

        $all = [];
        $idCounter = 1;
        foreach ($users as $u) {
            for ($i = 1; $i <= 105; $i++) {
                $sysCode = ($i <= 65) ? $u['sys'] : $systems[($i + $u['id']) % count($systems)];
                $trainList = $trains[$sysCode] ?? $trains['WR-ADI'];
                $tr = $trainList[$i % count($trainList)];
                $cls = $classes[($i + $u['id']) % count($classes)];
                $st = $statuses[($i * 3 + $u['id']) % count($statuses)];
                $q = $quotas[($i + 2) % count($quotas)];

                $day = str_pad((string)(($i % 28) + 1), 2, '0', STR_PAD_LEFT);
                $month = str_pad((string)(($i % 12) + 1), 2, '0', STR_PAD_LEFT);
                $jDate = '2026-' . $month . '-' . $day;
                $bDate = '2026-' . str_pad((string)(($i % 11) + 1), 2, '0', STR_PAD_LEFT) . '-' . str_pad((string)(($i % 27) + 1), 2, '0', STR_PAD_LEFT) . ' 10:30:00';

                $pnr = 'PNR' . (100000 + $idCounter);
                $seat = ($coaches[$cls] ?? 'S1') . '-' . (($i % 72) + 1);
                $fare = round($tr['base'] * ($mults[$cls] ?? 1.0));
                $paxName = $u['name'];
                if ($i % 5 === 0) $paxName = $u['name'] . ' & Family';
                elseif ($i % 7 === 0) $paxName = 'Colleague of ' . explode(' ', $u['name'])[0];

                $all[] = [
                    'id' => $idCounter,
                    'pnr' => $pnr,
                    'user_id' => $u['id'],
                    'booked_by' => $u['name'] . ' (' . $u['enroll'] . ')',
                    'user_enrollment' => $u['enroll'],
                    'passenger_name' => $paxName,
                    'phone' => '98' . str_pad((string)(($idCounter * 7391 + 1029384) % 100000000), 8, '0', STR_PAD_LEFT),
                    'train_name' => $tr['name'],
                    'travel_class' => $cls,
                    'quota' => $q,
                    'passengers' => ($i % 3) + 1,
                    'journey_date' => $jDate,
                    'system_code' => $sysCode,
                    'total_fare' => $fare,
                    'status' => $st,
                    'coach_seat' => $seat,
                    'booking_date' => $bDate,
                    'last_modified_by' => null,
                    'updated_at' => null,
                ];
                $idCounter++;
            }
        }
        return $all;
    }

    public static function getSeedLoginActivity() {
        return [
            ['id' => 1, 'user_email' => 'ridham.26gmca61@gmca.ac.in', 'enrollment' => '26GMCA61', 'ip_address' => '103.21.124.5', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', 'login_time' => '2026-10-26 12:15:00', 'status' => 'SUCCESS'],
            ['id' => 2, 'user_email' => 'riya.26gmca33@gmca.ac.in', 'enrollment' => '26GMCA33', 'ip_address' => '103.21.124.8', 'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1', 'login_time' => '2026-10-26 11:45:00', 'status' => 'SUCCESS'],
            ['id' => 3, 'user_email' => 'vaibhav.26gmca52@gmca.ac.in', 'enrollment' => '26GMCA52', 'ip_address' => '49.36.18.22', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/128.0', 'login_time' => '2026-10-26 12:30:00', 'status' => 'SUCCESS'],
            ['id' => 4, 'user_email' => 'station_master@railways.gov.in', 'enrollment' => 'SM-ADI-01', 'ip_address' => '49.36.19.112', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/129.0', 'login_time' => '2026-11-23 20:00:00', 'status' => 'SUCCESS'],
            ['id' => 5, 'user_email' => 'aarav.patel@gmca.ac.in', 'enrollment' => '26GMCA05', 'ip_address' => '14.139.122.3', 'user_agent' => 'Mozilla/5.0 (Linux; Android 14) Chrome/128.0', 'login_time' => '2026-10-25 14:10:00', 'status' => 'SUCCESS'],
        ];
    }

    public static function getSeedStudents() {
        return [
            ['id' => 101, 'enrollment' => '26GMCA52', 'full_name' => 'Vaibhav Senjaliya', 'email' => 'vaibhav.senjaliya@gmca.ac.in', 'phone' => '9876543210', 'department' => 'Master of Computer Applications (MCA)', 'semester' => 'Semester 2', 'gender' => 'Male', 'dob' => '2003-04-12', 'skills' => 'PHP, MySQL, JavaScript, HTML5, CSS3', 'project_title' => 'Automated Railway Reservation & Dynamic Pricing Engine', 'project_desc' => 'Built an interactive train ticket booking and fare calculation web application with unified database sync.', 'address' => 'Ahmedabad, Gujarat', 'status' => 'Approved', 'created_at' => '2026-09-01 11:20:00'],
            ['id' => 102, 'enrollment' => '26GMCA61', 'full_name' => 'Ridham Bambhaniya', 'email' => 'ridham.bambhaniya@gmca.ac.in', 'phone' => '9825012345', 'department' => 'Master of Computer Applications (MCA)', 'semester' => 'Semester 2', 'gender' => 'Male', 'dob' => '2002-11-20', 'skills' => 'PHP, MySQL, HTML5, CSS3, Python', 'project_title' => 'Institutional Web Portal & Practical Catalog System', 'project_desc' => 'Developed multi-tier responsive institutional portal architecture.', 'address' => 'Maninagar, Ahmedabad, Gujarat', 'status' => 'Approved', 'created_at' => '2026-09-02 14:15:00'],
            ['id' => 103, 'enrollment' => '26GMCA33', 'full_name' => 'Riya Thakkar', 'email' => 'riya.thakkar@gmca.ac.in', 'phone' => '9898711223', 'department' => 'Master of Computer Applications (MCA)', 'semester' => 'Semester 2', 'gender' => 'Female', 'dob' => '2003-08-19', 'skills' => 'PHP, JavaScript, HTML5, CSS3', 'project_title' => 'Real-time Expression Evaluator & Keyboard Interface', 'project_desc' => 'Engineered arithmetic parser with event-driven keyboard shortcuts.', 'address' => 'Satellite, Ahmedabad, Gujarat', 'status' => 'Approved', 'created_at' => '2026-09-03 16:45:00'],
            ['id' => 104, 'enrollment' => '26GMCA05', 'full_name' => 'Aarav Patel', 'email' => 'aarav.patel@gmca.ac.in', 'phone' => '9712345678', 'department' => 'Master of Computer Applications (MCA)', 'semester' => 'Semester 2', 'gender' => 'Male', 'dob' => '2003-01-15', 'skills' => 'PHP, MySQL, C++, Java', 'project_title' => 'Campus Library RFID & Circulation Tracker', 'project_desc' => 'Automated circulation ledger for university library.', 'address' => 'Vastrapur, Ahmedabad', 'status' => 'Approved', 'created_at' => '2026-09-05 10:00:00'],
        ];
    }

    public static function getSeedProfiles() {
        return [
            ['id' => 1, 'full_name' => 'Vaibhav Senjaliya', 'enrollment_no' => '26GMCA52', 'email' => 'vaibhav@gmca.ac.in', 'course' => 'Master of Computer Applications (MCA)', 'bio' => 'Passionate about web architectures and relational database systems.', 'skills' => 'PHP, MySQL, JavaScript, AngularJS, CSS3', 'city' => 'Ahmedabad', 'submitted_at' => '2026-10-20 14:00:00'],
            ['id' => 2, 'full_name' => 'Ridham Bambhaniya', 'enrollment_no' => '26GMCA61', 'email' => 'ridham@gmca.ac.in', 'course' => 'Master of Computer Applications (MCA)', 'bio' => 'Team lead focusing on institutional portal development and responsive CSS grids.', 'skills' => 'HTML5, CSS3, PHP, MySQL, System Design', 'city' => 'Ahmedabad', 'submitted_at' => '2026-10-21 11:30:00'],
            ['id' => 3, 'full_name' => 'Riya Thakkar', 'enrollment_no' => '26GMCA33', 'email' => 'riya@gmca.ac.in', 'course' => 'Master of Computer Applications (MCA)', 'bio' => 'Frontend engineer dedicated to accessible web applications.', 'skills' => 'JavaScript, AngularJS, UI Engineering, PHP', 'city' => 'Ahmedabad', 'submitted_at' => '2026-10-22 15:20:00'],
        ];
    }

    public static function getSeedSqlLogs() {
        return [
            ['id' => 1, 'action' => 'INIT', 'query' => 'CREATE TABLE IF NOT EXISTS `users` (...);', 'execution_time_ms' => 1.2, 'status' => 'Success', 'timestamp' => '2026-10-26 10:00:00'],
            ['id' => 2, 'action' => 'SELECT', 'query' => 'SELECT * FROM `railway_bookings` WHERE `status` = \'Confirmed\';', 'execution_time_ms' => 1.1, 'status' => 'Success', 'timestamp' => '2026-10-26 10:05:12'],
            ['id' => 3, 'action' => 'STATUS', 'query' => 'SHOW TABLE STATUS FROM `gmca_group10_db`;', 'execution_time_ms' => 2.1, 'status' => 'Success', 'timestamp' => '2026-10-26 10:10:00'],
        ];
    }

    // =========================================================================
    // SCHEMA INITIALIZATION (MySQL & SQLite)
    // =========================================================================
    private function ensureSqlSchemaAndSeed() {
        if (!$this->pdo) return;
        $isMysql = ($this->driver === 'mysql');
        $pk = $isMysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
        $ts = $isMysql ? 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP' : 'TEXT DEFAULT CURRENT_TIMESTAMP';
        $suffix = $isMysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `_sync_meta` (
            `meta_key` VARCHAR(64) PRIMARY KEY,
            `meta_value` TEXT NOT NULL
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` {$pk},
            `full_name` VARCHAR(120) NOT NULL,
            `enrollment_no` VARCHAR(30) NOT NULL UNIQUE,
            `email` VARCHAR(120) NOT NULL UNIQUE,
            `username` VARCHAR(60) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` VARCHAR(60) DEFAULT 'Student Member',
            `pin` VARCHAR(10) DEFAULT '1234',
            `system_code` VARCHAR(20) DEFAULT 'WR-ADI',
            `is_admin` INT DEFAULT 0,
            `avatar` VARCHAR(255) DEFAULT 'temp.jpeg',
            `last_login` VARCHAR(40) NULL,
            `created_at` {$ts}
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `railway_systems` (
            `id` {$pk},
            `code` VARCHAR(20) NOT NULL UNIQUE,
            `name` VARCHAR(150) NOT NULL,
            `zone` VARCHAR(80) NOT NULL,
            `division` VARCHAR(80) NOT NULL,
            `headquarters` VARCHAR(120) NOT NULL,
            `station_master_name` VARCHAR(120) NOT NULL,
            `station_master_username` VARCHAR(60) NOT NULL,
            `badge` VARCHAR(30) DEFAULT '#1E3A8A'
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `railway_bookings` (
            `id` {$pk},
            `pnr` VARCHAR(25) NOT NULL UNIQUE,
            `user_id` INT NULL,
            `booked_by` VARCHAR(140) NOT NULL,
            `user_enrollment` VARCHAR(30) NOT NULL,
            `passenger_name` VARCHAR(120) NOT NULL,
            `phone` VARCHAR(25) DEFAULT '9876543210',
            `train_name` VARCHAR(180) NOT NULL,
            `travel_class` VARCHAR(20) NOT NULL DEFAULT 'SL',
            `quota` VARCHAR(20) DEFAULT 'GN',
            `passengers` INT DEFAULT 1,
            `journey_date` VARCHAR(20) NOT NULL,
            `system_code` VARCHAR(20) DEFAULT 'WR-ADI',
            `total_fare` DECIMAL(10,2) NOT NULL DEFAULT 0,
            `status` VARCHAR(30) DEFAULT 'Confirmed',
            `coach_seat` VARCHAR(30) DEFAULT 'S3-12',
            `booking_date` VARCHAR(40) NULL,
            `last_modified_by` VARCHAR(120) NULL,
            `updated_at` VARCHAR(40) NULL
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `login_activity` (
            `id` {$pk},
            `user_email` VARCHAR(120) NOT NULL,
            `enrollment` VARCHAR(30) DEFAULT '',
            `ip_address` VARCHAR(60) DEFAULT '127.0.0.1',
            `user_agent` VARCHAR(255) DEFAULT 'Web Client',
            `login_time` VARCHAR(40) NULL,
            `status` VARCHAR(30) DEFAULT 'SUCCESS'
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `practical7_students` (
            `id` {$pk},
            `enrollment` VARCHAR(30) NOT NULL,
            `full_name` VARCHAR(120) NOT NULL,
            `email` VARCHAR(120) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `department` VARCHAR(120) NOT NULL,
            `semester` VARCHAR(30) NOT NULL,
            `gender` VARCHAR(20) DEFAULT 'Not Specified',
            `dob` VARCHAR(20) NULL,
            `skills` TEXT NOT NULL,
            `project_title` VARCHAR(255) NOT NULL,
            `project_desc` TEXT NULL,
            `address` TEXT NULL,
            `status` VARCHAR(30) DEFAULT 'Approved',
            `created_at` {$ts}
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `user_profiles` (
            `id` {$pk},
            `full_name` VARCHAR(120) NOT NULL,
            `enrollment_no` VARCHAR(30) NOT NULL,
            `email` VARCHAR(120) NOT NULL,
            `course` VARCHAR(120) NOT NULL,
            `bio` TEXT NULL,
            `skills` VARCHAR(255) NULL,
            `city` VARCHAR(60) DEFAULT 'Ahmedabad',
            `submitted_at` VARCHAR(40) NULL
        ){$suffix}");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `sql_query_logs` (
            `id` {$pk},
            `action` VARCHAR(30) DEFAULT 'QUERY',
            `query` TEXT NOT NULL,
            `execution_time_ms` DECIMAL(10,2) DEFAULT 0,
            `status` VARCHAR(30) DEFAULT 'Success',
            `timestamp` VARCHAR(40) NULL
        ){$suffix}");

        // Ensure legacy MySQL tables (if created by older schema.sql) get any missing columns
        if ($isMysql) {
            $this->ensureMysqlColumns();
        }

        $seeded = $this->getMeta('seeded_v2');
        if ($seeded !== '1') {
            $this->seedSqlTablesIfEmpty();
            $this->setMeta('seeded_v2', '1');
            if (!$this->getMeta('data_version')) {
                $this->setMeta('data_version', '1');
            }
        }
    }

    private function ensureMysqlColumns() {
        $addCol = function($table, $col, $def) {
            try {
                $stmt = $this->pdo->query("SHOW COLUMNS FROM `{$table}` LIKE " . $this->pdo->quote($col));
                if (!$stmt->fetch()) {
                    $this->pdo->exec("ALTER TABLE `{$table}` ADD COLUMN `{$col}` {$def}");
                }
            } catch (Exception $e) {}
        };
        $addCol('users', 'full_name', "VARCHAR(120) NOT NULL DEFAULT ''");
        $addCol('users', 'enrollment_no', "VARCHAR(30) NOT NULL DEFAULT ''");
        $addCol('users', 'pin', "VARCHAR(10) DEFAULT '1234'");
        $addCol('users', 'system_code', "VARCHAR(20) DEFAULT 'WR-ADI'");
        $addCol('users', 'is_admin', "INT DEFAULT 0");

        $addCol('railway_bookings', 'user_id', "INT NULL");
        $addCol('railway_bookings', 'booked_by', "VARCHAR(140) NOT NULL DEFAULT ''");
        $addCol('railway_bookings', 'phone', "VARCHAR(25) DEFAULT '9876543210'");
        $addCol('railway_bookings', 'quota', "VARCHAR(20) DEFAULT 'GN'");
        $addCol('railway_bookings', 'system_code', "VARCHAR(20) DEFAULT 'WR-ADI'");
        $addCol('railway_bookings', 'coach_seat', "VARCHAR(30) DEFAULT 'S3-12'");
        $addCol('railway_bookings', 'booking_date', "VARCHAR(40) NULL");
        $addCol('railway_bookings', 'last_modified_by', "VARCHAR(120) NULL");
        $addCol('railway_bookings', 'updated_at', "VARCHAR(40) NULL");

        $addCol('sql_query_logs', 'timestamp', "VARCHAR(40) NULL");
        $addCol('user_profiles', 'enrollment_no', "VARCHAR(30) NOT NULL DEFAULT ''");
        $addCol('user_profiles', 'skills', "VARCHAR(255) NULL");
        $addCol('user_profiles', 'city', "VARCHAR(60) DEFAULT 'Ahmedabad'");
    }

    private function seedSqlTablesIfEmpty() {
        if ((int)$this->pdo->query("SELECT COUNT(*) FROM `railway_systems`")->fetchColumn() === 0) {
            foreach (self::getSeedSystems() as $s) {
                $this->insertRowInternal('railway_systems', $s, true);
            }
        }
        // Ensure all 9 core users exist (check by username so legacy 6-user seed gets upgraded)
        $existingUsers = (int)$this->pdo->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
        if ($existingUsers < 9) {
            $checkStmt = $this->pdo->prepare("SELECT id FROM `users` WHERE `username` = :u");
            foreach (self::getSeedUsers() as $u) {
                $checkStmt->execute([':u' => $u['username']]);
                if (!$checkStmt->fetch()) {
                    $row = $u;
                    if ($existingUsers > 0) unset($row['id']);
                    $this->insertRowInternal('users', $row, true);
                }
            }
        }
        if ((int)$this->pdo->query("SELECT COUNT(*) FROM `railway_bookings`")->fetchColumn() < 50) {
            $this->pdo->beginTransaction();
            try {
                $checkPnr = $this->pdo->prepare("SELECT id FROM `railway_bookings` WHERE `pnr` = :p");
                foreach (self::getSeedBookings() as $b) {
                    $checkPnr->execute([':p' => $b['pnr']]);
                    if (!$checkPnr->fetch()) {
                        $row = $b;
                        unset($row['id']);
                        $this->insertRowInternal('railway_bookings', $row, true);
                    }
                }
                $this->pdo->commit();
            } catch (Exception $e) {
                if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            }
        }
        if ((int)$this->pdo->query("SELECT COUNT(*) FROM `login_activity`")->fetchColumn() === 0) {
            foreach (self::getSeedLoginActivity() as $la) {
                $this->insertRowInternal('login_activity', $la, true);
            }
        }
        if ((int)$this->pdo->query("SELECT COUNT(*) FROM `practical7_students`")->fetchColumn() === 0) {
            foreach (self::getSeedStudents() as $st) {
                $this->insertRowInternal('practical7_students', $st, true);
            }
        }
        if ((int)$this->pdo->query("SELECT COUNT(*) FROM `user_profiles`")->fetchColumn() === 0) {
            foreach (self::getSeedProfiles() as $pr) {
                $this->insertRowInternal('user_profiles', $pr, true);
            }
        }
        if ((int)$this->pdo->query("SELECT COUNT(*) FROM `sql_query_logs`")->fetchColumn() === 0) {
            foreach (self::getSeedSqlLogs() as $lg) {
                $this->insertRowInternal('sql_query_logs', $lg, true);
            }
        }
    }

    // =========================================================================
    // JSON REST ADAPTER (UPSTASH / VERCEL KV & FIREBASE RTDB)
    // =========================================================================
    public function isJsonRestDriver() {
        return $this->driver === 'kv_rest' || $this->driver === 'firebase_rest';
    }

    private function kvRequest($commandArgs) {
        $ch = curl_init(KV_REST_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . KV_REST_TOKEN,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($commandArgs),
            CURLOPT_TIMEOUT => 5,
        ]);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($resp === false) {
            throw new Exception('KV REST error: ' . $err);
        }
        $decoded = json_decode($resp, true);
        return $decoded['result'] ?? null;
    }

    private function firebaseRequest($path, $method = 'GET', $body = null) {
        $url = FIREBASE_DB_URL . '/' . ltrim($path, '/') . '.json';
        if (!empty(FIREBASE_AUTH_SECRET)) {
            $url .= '?auth=' . urlencode(FIREBASE_AUTH_SECRET);
        }
        $ch = curl_init($url);
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 6,
        ];
        if ($body !== null) {
            $opts[CURLOPT_POSTFIELDS] = json_encode($body);
        }
        curl_setopt_array($ch, $opts);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($resp === false || $code >= 400) {
            throw new Exception('Firebase REST error (' . $code . '): ' . $err);
        }
        return json_decode($resp, true);
    }

    private function kvGetTable($table) {
        if ($this->driver === 'firebase_rest') {
            $data = $this->firebaseRequest('gmca_db/tbl/' . $table, 'GET');
            return is_array($data) ? array_values($data) : [];
        }
        $raw = $this->kvRequest(['GET', 'gmca_db:tbl:' . $table]);
        if (!$raw) return [];
        $arr = json_decode($raw, true);
        return is_array($arr) ? $arr : [];
    }

    private function kvSetTable($table, $rows) {
        if ($this->driver === 'firebase_rest') {
            $this->firebaseRequest('gmca_db/tbl/' . $table, 'PUT', array_values($rows));
            return;
        }
        $this->kvRequest(['SET', 'gmca_db:tbl:' . $table, json_encode(array_values($rows))]);
    }

    private function ensureJsonStoreSeed() {
        $seeded = ($this->driver === 'firebase_rest')
            ? $this->firebaseRequest('gmca_db/seeded_v2', 'GET')
            : $this->kvRequest(['GET', 'gmca_db:seeded_v2']);
        if ((string)$seeded === '1') return;

        $this->kvSetTable('users', self::getSeedUsers());
        $this->kvSetTable('railway_systems', self::getSeedSystems());
        $this->kvSetTable('railway_bookings', self::getSeedBookings());
        $this->kvSetTable('login_activity', self::getSeedLoginActivity());
        $this->kvSetTable('practical7_students', self::getSeedStudents());
        $this->kvSetTable('user_profiles', self::getSeedProfiles());
        $this->kvSetTable('sql_query_logs', self::getSeedSqlLogs());

        if ($this->driver === 'firebase_rest') {
            $this->firebaseRequest('gmca_db/version', 'PUT', 1);
            $this->firebaseRequest('gmca_db/seeded_v2', 'PUT', '1');
        } else {
            $this->kvRequest(['SET', 'gmca_db:version', '1']);
            $this->kvRequest(['SET', 'gmca_db:seeded_v2', '1']);
        }
    }

    // =========================================================================
    // META & VERSIONING
    // =========================================================================
    private function getMeta($key) {
        if (!$this->pdo) return null;
        $stmt = $this->pdo->prepare("SELECT `meta_value` FROM `_sync_meta` WHERE `meta_key` = :k");
        $stmt->execute([':k' => $key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? (string)$val : null;
    }

    private function setMeta($key, $value) {
        if (!$this->pdo) return;
        if ($this->driver === 'mysql') {
            $stmt = $this->pdo->prepare("INSERT INTO `_sync_meta` (`meta_key`, `meta_value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `meta_value` = :v2");
            $stmt->execute([':k' => $key, ':v' => (string)$value, ':v2' => (string)$value]);
        } else {
            $stmt = $this->pdo->prepare("INSERT OR REPLACE INTO `_sync_meta` (`meta_key`, `meta_value`) VALUES (:k, :v)");
            $stmt->execute([':k' => $key, ':v' => (string)$value]);
        }
    }

    public function getDataVersion() {
        if ($this->driver === 'firebase_rest') {
            $v = $this->firebaseRequest('gmca_db/version', 'GET');
            return (int)($v ?: 1);
        }
        if ($this->driver === 'kv_rest') {
            $v = $this->kvRequest(['GET', 'gmca_db:version']);
            return (int)($v ?: 1);
        }
        return (int)($this->getMeta('data_version') ?: 1);
    }

    public function bumpDataVersion() {
        if ($this->driver === 'firebase_rest') {
            $next = $this->getDataVersion() + 1;
            $this->firebaseRequest('gmca_db/version', 'PUT', $next);
            return $next;
        }
        if ($this->driver === 'kv_rest') {
            return (int)$this->kvRequest(['INCR', 'gmca_db:version']);
        }
        $next = $this->getDataVersion() + 1;
        $this->setMeta('data_version', (string)$next);
        return $next;
    }

    // =========================================================================
    // CRUD OPERATIONS (UNIFIED ACROSS DRIVERS)
    // =========================================================================
    public function getAllRows($table) {
        if (!$this->isValidTable($table)) return [];
        if ($this->isJsonRestDriver()) {
            return $this->kvGetTable($table);
        }
        $order = ($table === 'railway_bookings') ? 'ORDER BY `id` DESC' : 'ORDER BY `id` ASC';
        $stmt = $this->pdo->query("SELECT * FROM `{$table}` {$order}");
        return $stmt->fetchAll() ?: [];
    }

    public function getTableColumns($table) {
        if (!$this->isValidTable($table)) return [];
        if ($this->driver === 'mysql') {
            $rows = $this->pdo->query("SHOW FULL COLUMNS FROM `{$table}`")->fetchAll();
            return array_map(function($c) {
                return [
                    'Field' => $c['Field'] ?? '',
                    'Type' => $c['Type'] ?? '',
                    'Collation' => $c['Collation'] ?? 'utf8mb4_unicode_ci',
                    'Null' => $c['Null'] ?? 'NO',
                    'Key' => $c['Key'] ?? '',
                    'Default' => $c['Default'] ?? null,
                    'Extra' => $c['Extra'] ?? '',
                    'Privileges' => $c['Privileges'] ?? 'select,insert,update,references',
                    'Comment' => $c['Comment'] ?? ''
                ];
            }, $rows);
        }
        if ($this->isJsonRestDriver()) {
            $rows = $this->kvGetTable($table);
            $sample = $rows[0] ?? [];
            $cols = [];
            foreach (array_keys($sample) as $k) {
                $cols[] = [
                    'Field' => $k,
                    'Type' => ($k === 'id' || $k === 'passengers' || $k === 'is_admin') ? 'int(11)' : 'varchar(120)',
                    'Collation' => 'utf8mb4_unicode_ci',
                    'Null' => 'YES',
                    'Key' => ($k === 'id' ? 'PRI' : ''),
                    'Default' => null,
                    'Extra' => ($k === 'id' ? 'auto_increment' : ''),
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => ''
                ];
            }
            return $cols;
        }
        $rows = $this->pdo->query("PRAGMA table_info(`{$table}`)")->fetchAll();
        return array_map(function($c) {
            return [
                'Field' => $c['name'],
                'Type' => strtolower($c['type'] ?: 'text'),
                'Collation' => 'utf8mb4_unicode_ci',
                'Null' => $c['notnull'] ? 'NO' : 'YES',
                'Key' => $c['pk'] ? 'PRI' : '',
                'Default' => $c['dflt_value'],
                'Extra' => $c['pk'] ? 'auto_increment' : '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ];
        }, $rows);
    }

    public function insertRowInternal($table, $data, $skipVersionBump = false) {
        if (!$this->isValidTable($table)) return ['success' => false, 'error' => 'Invalid table.'];
        if ($this->isJsonRestDriver()) {
            $rows = $this->kvGetTable($table);
            $maxId = 0;
            foreach ($rows as $r) {
                if ((int)($r['id'] ?? 0) > $maxId) $maxId = (int)$r['id'];
            }
            if (empty($data['id'])) $data['id'] = $maxId + 1;
            if ($table === 'railway_bookings') {
                array_unshift($rows, $data);
            } else {
                $rows[] = $data;
            }
            $this->kvSetTable($table, $rows);
            if (!$skipVersionBump) $this->bumpDataVersion();
            return ['success' => true, 'inserted_id' => $data['id'], 'row' => $data];
        }

        // Filter keys to valid table columns
        $validCols = array_map(function($c) { return $c['Field']; }, $this->getTableColumns($table));
        $fields = [];
        $placeholders = [];
        $values = [];
        foreach ($data as $k => $v) {
            if (!in_array($k, $validCols, true)) continue;
            if ($k === 'id' && ($v === '' || $v === null)) continue;
            $fields[] = "`{$k}`";
            $placeholders[] = ":{$k}";
            $values[":{$k}"] = ($v === '' ? null : $v);
        }
        if (empty($fields)) {
            return ['success' => false, 'error' => 'No valid columns provided.'];
        }
        $sql = "INSERT INTO `{$table}` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        $insertedId = $this->pdo->lastInsertId() ?: ($data['id'] ?? null);
        if (!$skipVersionBump) $this->bumpDataVersion();
        return ['success' => true, 'inserted_id' => $insertedId, 'sql' => $sql];
    }

    public function updateRowInternal($table, $id, $data, $pk = 'id') {
        if (!$this->isValidTable($table)) return ['success' => false, 'error' => 'Invalid table.'];
        if ($this->isJsonRestDriver()) {
            $rows = $this->kvGetTable($table);
            $updated = null;
            foreach ($rows as &$r) {
                if ((string)($r[$pk] ?? '') === (string)$id) {
                    foreach ($data as $k => $v) {
                        if ($k !== 'id') $r[$k] = $v;
                    }
                    $updated = $r;
                    break;
                }
            }
            unset($r);
            if (!$updated) return ['success' => false, 'error' => 'Row not found.'];
            $this->kvSetTable($table, $rows);
            $this->bumpDataVersion();
            return ['success' => true, 'row' => $updated];
        }

        $validCols = array_map(function($c) { return $c['Field']; }, $this->getTableColumns($table));
        $sets = [];
        $values = [':_pk_val' => $id];
        foreach ($data as $k => $v) {
            if ($k === $pk || $k === 'id' || !in_array($k, $validCols, true)) continue;
            $sets[] = "`{$k}` = :u_{$k}";
            $values[":u_{$k}"] = ($v === '' ? null : $v);
        }
        if (empty($sets)) return ['success' => false, 'error' => 'No updatable columns provided.'];
        $sql = "UPDATE `{$table}` SET " . implode(', ', $sets) . " WHERE `{$pk}` = :_pk_val";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        $this->bumpDataVersion();
        return ['success' => true, 'affected_rows' => $stmt->rowCount(), 'sql' => $sql];
    }

    public function deleteRowInternal($table, $id, $pk = 'id') {
        if (!$this->isValidTable($table)) return ['success' => false, 'error' => 'Invalid table.'];
        if ($this->isJsonRestDriver()) {
            $rows = $this->kvGetTable($table);
            $before = count($rows);
            $rows = array_values(array_filter($rows, function($r) use ($pk, $id) {
                return (string)($r[$pk] ?? '') !== (string)$id;
            }));
            if (count($rows) === $before) return ['success' => false, 'error' => 'Row not found.'];
            $this->kvSetTable($table, $rows);
            $this->bumpDataVersion();
            return ['success' => true, 'affected_rows' => 1];
        }
        $sql = "DELETE FROM `{$table}` WHERE `{$pk}` = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $this->bumpDataVersion();
        return ['success' => true, 'affected_rows' => $stmt->rowCount(), 'sql' => $sql];
    }

    public function emptyTableInternal($table) {
        if (!$this->isValidTable($table)) return ['success' => false, 'error' => 'Invalid table.'];
        if ($this->isJsonRestDriver()) {
            $this->kvSetTable($table, []);
            $this->bumpDataVersion();
            return ['success' => true, 'message' => "Table `{$table}` has been emptied."];
        }
        $this->pdo->exec("DELETE FROM `{$table}`");
        $this->bumpDataVersion();
        return ['success' => true, 'message' => "Table `{$table}` has been emptied."];
    }

    public function resetSeedAll() {
        if ($this->isJsonRestDriver()) {
            $this->kvSetTable('users', self::getSeedUsers());
            $this->kvSetTable('railway_systems', self::getSeedSystems());
            $this->kvSetTable('railway_bookings', self::getSeedBookings());
            $this->kvSetTable('login_activity', self::getSeedLoginActivity());
            $this->kvSetTable('practical7_students', self::getSeedStudents());
            $this->kvSetTable('user_profiles', self::getSeedProfiles());
            $this->kvSetTable('sql_query_logs', self::getSeedSqlLogs());
            $this->bumpDataVersion();
            return ['success' => true, 'message' => 'All database tables reset and seeded with 525 realistic records.'];
        }
        foreach (self::$ALLOWED_TABLES as $t) {
            $this->pdo->exec("DROP TABLE IF EXISTS `{$t}`");
        }
        $this->setMeta('seeded_v2', '0');
        $this->ensureSqlSchemaAndSeed();
        $this->bumpDataVersion();
        return ['success' => true, 'message' => 'All database tables reset and seeded with 525 realistic records.'];
    }

    public function logSql($sql, $action = 'QUERY', $timeMs = 0.5, $status = 'Success') {
        try {
            $this->insertRowInternal('sql_query_logs', [
                'action' => $action,
                'query' => $sql,
                'execution_time_ms' => $timeMs,
                'status' => $status,
                'timestamp' => date('Y-m-d H:i:s'),
            ], true);
        } catch (Exception $e) {}
    }

    public function formatBookingForClient($b) {
        $jDate = (string)($b['journey_date'] ?? $b['date'] ?? '');
        if (strpos($jDate, 'T') !== false) {
            $jDate = substr($jDate, 0, 10);
        }
        return [
            'id' => (int)$b['id'],
            'pnr' => $b['pnr'],
            'userId' => isset($b['user_id']) ? (int)$b['user_id'] : null,
            'bookedBy' => $b['booked_by'] ?? $b['bookedBy'] ?? 'Member',
            'userEnrollment' => strtoupper((string)($b['user_enrollment'] ?? $b['userEnrollment'] ?? '')),
            'passengerName' => $b['passenger_name'] ?? $b['passengerName'] ?? '',
            'phone' => $b['phone'] ?? '9876543210',
            'train' => $b['train_name'] ?? $b['train'] ?? '',
            'travelClass' => $b['travel_class'] ?? $b['travelClass'] ?? 'SL',
            'quota' => $b['quota'] ?? 'GN',
            'passengers' => (int)($b['passengers'] ?? 1),
            'date' => $jDate,
            'systemCode' => $b['system_code'] ?? $b['systemCode'] ?? 'WR-ADI',
            'totalFare' => (float)($b['total_fare'] ?? $b['totalFare'] ?? 0),
            'status' => $b['status'] ?? 'Confirmed',
            'coachSeat' => $b['coach_seat'] ?? $b['coachSeat'] ?? 'S3-12',
            'bookingDate' => $b['booking_date'] ?? $b['bookingDate'] ?? '',
            'lastModifiedBy' => $b['last_modified_by'] ?? null,
            'updatedAt' => $b['updated_at'] ?? null,
        ];
    }

    public function formatUserForClient($u) {
        return [
            'id' => (int)$u['id'],
            'username' => $u['username'],
            'full_name' => $u['full_name'] ?? $u['name'] ?? '',
            'enrollment_no' => strtoupper((string)($u['enrollment_no'] ?? $u['enrollment'] ?? '')),
            'email' => $u['email'] ?? '',
            'role' => $u['role'] ?? 'Student Member',
            'systemCode' => $u['system_code'] ?? 'WR-ADI',
            'isAdmin' => !empty($u['is_admin']) && (int)$u['is_admin'] === 1,
            'avatar' => $u['avatar'] ?? 'temp.jpeg',
            'last_login' => $u['last_login'] ?? null,
        ];
    }
}
?>
