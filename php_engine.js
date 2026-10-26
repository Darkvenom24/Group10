/**
 * php_engine.js - Shared Client-Side PHP Simulation & Relational Storage Engine
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Powers all PHP Practicals (9.1 to 12) & authentic phpMyAdmin 5.x Clone
 * Works seamlessly offline, locally, and on Vercel deployment with zero lag
 */

(function (window) {
    'use strict';

    const DB_USERS_KEY = 'gmca_php_users';
    const DB_AUTH_KEY = 'gmca_php_current_user';
    const DB_PRACTICAL7_KEY = 'gmca_php_practical7_students';
    const DB_SQL_LOG_KEY = 'gmca_php_sql_logs';
    const DB_LOGIN_ACTIVITY_KEY = 'gmca_php_login_activity';
    const DB_RAILWAY_BOOKINGS_KEY = 'gmca_railway_bookings_v3';
    const DB_USER_PROFILES_KEY = 'gmca_php_user_profiles';

    // 6 Realistic Indian Users
    const SEED_USERS = [
        {
            id: 1,
            full_name: "Ridham Bambhaniya",
            enrollment_no: "26GMCA61",
            email: "ridham.26gmca61@gmca.ac.in",
            username: "ridham61",
            password: "password123",
            role: "Team Lead",
            pin: "0101",
            avatar: "ridham.jpeg",
            last_login: "2026-10-26 12:15:00",
            created_at: "2026-08-15 10:30:00"
        },
        {
            id: 2,
            full_name: "Riya Thakkar",
            enrollment_no: "26GMCA33",
            email: "riya.26gmca33@gmca.ac.in",
            username: "riya33",
            password: "password123",
            role: "Student Member",
            pin: "9898",
            avatar: "riya.jpeg",
            last_login: "2026-10-26 11:45:00",
            created_at: "2026-08-15 10:30:00"
        },
        {
            id: 3,
            full_name: "Vaibhav Senjaliya",
            enrollment_no: "26GMCA52",
            email: "vaibhav.26gmca52@gmca.ac.in",
            username: "vaibhav52",
            password: "password123",
            role: "Student Member",
            pin: "9543",
            avatar: "vaibhav.jpeg",
            last_login: "2026-10-26 12:30:00",
            created_at: "2026-08-15 10:30:00"
        },
        {
            id: 4,
            full_name: "Aarav Patel",
            enrollment_no: "26GMCA05",
            email: "aarav.patel@gmca.ac.in",
            username: "aarav05",
            password: "password123",
            role: "Student Member",
            pin: "1234",
            avatar: "temp.jpeg",
            last_login: "2026-10-25 16:20:00",
            created_at: "2026-08-20 09:15:00"
        },
        {
            id: 5,
            full_name: "Sneha Joshi",
            enrollment_no: "26GMCA18",
            email: "sneha.joshi@gmca.ac.in",
            username: "sneha18",
            password: "password123",
            role: "Student Member",
            pin: "4321",
            avatar: "temp.jpeg",
            last_login: "2026-10-25 14:10:00",
            created_at: "2026-08-22 11:00:00"
        },
        {
            id: 6,
            full_name: "Devansh Shah",
            enrollment_no: "26GMCA44",
            email: "devansh.shah@gmca.ac.in",
            username: "devansh44",
            password: "password123",
            role: "Student Member",
            pin: "5678",
            avatar: "temp.jpeg",
            last_login: "2026-10-24 18:30:00",
            created_at: "2026-08-25 14:45:00"
        }
    ];

    // 6 Realistic Login Activity Logs
    const SEED_LOGIN_ACTIVITY = [
        { id: 1, user_email: "ridham.26gmca61@gmca.ac.in", ip_address: "103.21.124.5", user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0", login_time: "2026-10-26 12:15:00", status: "SUCCESS" },
        { id: 2, user_email: "riya.26gmca33@gmca.ac.in", ip_address: "103.21.124.8", user_agent: "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1", login_time: "2026-10-26 11:45:00", status: "SUCCESS" },
        { id: 3, user_email: "vaibhav.26gmca52@gmca.ac.in", ip_address: "49.36.18.22", user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/128.0", login_time: "2026-10-26 12:30:00", status: "SUCCESS" },
        { id: 4, user_email: "aarav.patel@gmca.ac.in", ip_address: "49.36.19.112", user_agent: "Mozilla/5.0 (Linux; Android 14) Chrome/128.0", login_time: "2026-10-25 16:20:00", status: "SUCCESS" },
        { id: 5, user_email: "sneha.joshi@gmca.ac.in", ip_address: "14.139.122.3", user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/130.0", login_time: "2026-10-25 14:10:00", status: "SUCCESS" },
        { id: 6, user_email: "devansh.shah@gmca.ac.in", ip_address: "14.139.122.9", user_agent: "Mozilla/5.0 (iPhone; CPU iPhone OS 17_6) Mobile/15E148", login_time: "2026-10-24 18:30:00", status: "SUCCESS" }
    ];

    // 6 Realistic Railway Bookings
    const SEED_RAILWAY_BOOKINGS = [
        { id: 101, pnr: "PNR748291", passenger_name: "Ridham Bambhaniya", train_name: "12957 - Ahmedabad Swarna Jayanti Rajdhani", travel_class: "3A", quota: "GN", passengers: 2, journey_date: "2026-11-15", total_fare: 2400.00, status: "Confirmed", user_enrollment: "26GMCA61", booking_date: "2026-10-01 10:15:00" },
        { id: 102, pnr: "PNR831902", passenger_name: "Ridham Bambhaniya", train_name: "22953 - Gujarat SF Express", travel_class: "SL", quota: "GN", passengers: 1, journey_date: "2026-11-22", total_fare: 400.00, status: "Confirmed", user_enrollment: "26GMCA61", booking_date: "2026-10-05 14:20:00" },
        { id: 201, pnr: "PNR612944", passenger_name: "Riya Thakkar", train_name: "12009 - Mumbai Central Vande Bharat Express", travel_class: "2A", quota: "GN", passengers: 1, journey_date: "2026-11-18", total_fare: 1800.00, status: "Confirmed", user_enrollment: "26GMCA33", booking_date: "2026-10-04 11:30:00" },
        { id: 301, pnr: "PNR930182", passenger_name: "Vaibhav Senjaliya", train_name: "12957 - Ahmedabad Swarna Jayanti Rajdhani", travel_class: "2A", quota: "GN", passengers: 1, journey_date: "2026-11-25", total_fare: 1800.00, status: "Confirmed", user_enrollment: "26GMCA52", booking_date: "2026-10-06 09:40:00" },
        { id: 302, pnr: "PNR441920", passenger_name: "Vaibhav Senjaliya", train_name: "19038 - Avadh Express", travel_class: "SL", quota: "GN", passengers: 2, journey_date: "2026-11-29", total_fare: 700.00, status: "Confirmed", user_enrollment: "26GMCA52", booking_date: "2026-10-08 16:50:00" },
        { id: 401, pnr: "PNR552199", passenger_name: "Aarav Patel", train_name: "12901 - Gujarat Mail", travel_class: "3A", quota: "GN", passengers: 1, journey_date: "2026-12-05", total_fare: 950.00, status: "Confirmed", user_enrollment: "26GMCA05", booking_date: "2026-10-12 13:00:00" }
    ];

    // 6 Realistic Practical 7 Student Records
    const SEED_STUDENTS = [
        {
            id: 101,
            enrollment: "26GMCA52",
            full_name: "Vaibhav Senjaliya",
            email: "vaibhav.senjaliya@gmca.ac.in",
            phone: "9876543210",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Male",
            dob: "2003-04-12",
            skills: "PHP, MySQL, JavaScript, HTML5, CSS3",
            project_title: "Automated Railway Reservation & Dynamic Pricing Engine",
            project_desc: "Built an interactive train ticket booking and fare calculation web application with persistent local dataset and fare rules.",
            address: "Ahmedabad, Gujarat",
            status: "Approved",
            created_at: "2026-09-01 11:20:00"
        },
        {
            id: 102,
            enrollment: "26GMCA61",
            full_name: "Ridham Bambhaniya",
            email: "ridham.bambhaniya@gmca.ac.in",
            phone: "9825012345",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Male",
            dob: "2002-11-20",
            skills: "PHP, MySQL, HTML5, CSS3, Python",
            project_title: "Institutional Web Portal & Practical Catalog System",
            project_desc: "Developed multi-tier responsive institutional portal architecture demonstrating academic practical catalogs and member profiles.",
            address: "Maninagar, Ahmedabad, Gujarat",
            status: "Approved",
            created_at: "2026-09-02 14:15:00"
        },
        {
            id: 103,
            enrollment: "26GMCA33",
            full_name: "Riya Thakkar",
            email: "riya.thakkar@gmca.ac.in",
            phone: "9898711223",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Female",
            dob: "2003-08-19",
            skills: "PHP, JavaScript, HTML5, CSS3",
            project_title: "Real-time Expression Evaluator & Keyboard Interface",
            project_desc: "Engineered arithmetic parser with event-driven keyboard shortcuts, error handling and dynamic layout stacking.",
            address: "Satellite, Ahmedabad, Gujarat",
            status: "Approved",
            created_at: "2026-09-03 16:45:00"
        },
        {
            id: 104,
            enrollment: "26GMCA05",
            full_name: "Aarav Patel",
            email: "aarav.patel@gmca.ac.in",
            phone: "9712345678",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Male",
            dob: "2003-01-15",
            skills: "PHP, MySQL, C++, Java",
            project_title: "Campus Library RFID & Circulation Tracker",
            project_desc: "Automated circulation ledger for university library.",
            address: "Vastrapur, Ahmedabad",
            status: "Approved",
            created_at: "2026-09-05 10:00:00"
        },
        {
            id: 105,
            enrollment: "26GMCA18",
            full_name: "Sneha Joshi",
            email: "sneha.joshi@gmca.ac.in",
            phone: "9823456789",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Female",
            dob: "2003-05-24",
            skills: "AngularJS, PHP, SQL, Figma",
            project_title: "Student Placement & Internship Matchmaker",
            project_desc: "Web app bridging hiring companies with eligible MCA graduates.",
            address: "Navrangpura, Ahmedabad",
            status: "Approved",
            created_at: "2026-09-06 12:30:00"
        },
        {
            id: 106,
            enrollment: "26GMCA44",
            full_name: "Devansh Shah",
            email: "devansh.shah@gmca.ac.in",
            phone: "9901234567",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Male",
            dob: "2002-09-30",
            skills: "PHP, MySQL, Cloud Computing, Linux",
            project_title: "Automated MySQL Backup & Health Monitor",
            project_desc: "Cron-based replication and heartbeat health checking utility.",
            address: "Paldi, Ahmedabad",
            status: "Approved",
            created_at: "2026-09-07 15:45:00"
        }
    ];

    // 5 Realistic User Profiles (Practical 11.3)
    const SEED_USER_PROFILES = [
        {
            id: 1,
            full_name: "Vaibhav Senjaliya",
            enrollment_no: "26GMCA52",
            email: "vaibhav@gmca.ac.in",
            course: "Master of Computer Applications (MCA)",
            bio: "Passionate about web architectures, full stack software development, and relational database systems.",
            skills: "PHP, MySQL, JavaScript, AngularJS, CSS3",
            city: "Ahmedabad",
            submitted_at: "2026-10-20 14:00:00"
        },
        {
            id: 2,
            full_name: "Ridham Bambhaniya",
            enrollment_no: "26GMCA61",
            email: "ridham@gmca.ac.in",
            course: "Master of Computer Applications (MCA)",
            bio: "Team lead focusing on institutional portal development, UI/UX consistency, and responsive CSS grids.",
            skills: "HTML5, CSS3, PHP, MySQL, System Design",
            city: "Ahmedabad",
            submitted_at: "2026-10-21 11:30:00"
        },
        {
            id: 3,
            full_name: "Riya Thakkar",
            enrollment_no: "26GMCA33",
            email: "riya@gmca.ac.in",
            course: "Master of Computer Applications (MCA)",
            bio: "Frontend engineer dedicated to accessible web applications, dynamic arithmetic parsers, and client storage.",
            skills: "JavaScript, AngularJS, UI Engineering, PHP",
            city: "Ahmedabad",
            submitted_at: "2026-10-22 15:20:00"
        },
        {
            id: 4,
            full_name: "Aarav Patel",
            enrollment_no: "26GMCA05",
            email: "aarav@gmca.ac.in",
            course: "Master of Computer Applications (MCA)",
            bio: "Exploring backend database tuning, stored procedures, and cloud database migrations.",
            skills: "MySQL, Relational Design, Python, C++",
            city: "Ahmedabad",
            submitted_at: "2026-10-23 09:40:00"
        },
        {
            id: 5,
            full_name: "Sneha Joshi",
            enrollment_no: "26GMCA18",
            email: "sneha@gmca.ac.in",
            course: "Master of Computer Applications (MCA)",
            bio: "Interested in data visualization, web technology pedagogy, and enterprise information systems.",
            skills: "PHP, Database Administration, HTML5",
            city: "Ahmedabad",
            submitted_at: "2026-10-24 16:10:00"
        }
    ];

    // 5 Initial SQL Query Audit Logs
    const SEED_SQL_LOGS = [
        { id: 1, action: "INIT", query: "CREATE TABLE IF NOT EXISTS `users` (...);", timestamp: "2026-10-26 10:00:00" },
        { id: 2, action: "INSERT", query: "INSERT INTO `users` VALUES (1, 'Ridham Bambhaniya', '26GMCA61', ...);", timestamp: "2026-10-26 10:00:01" },
        { id: 3, action: "SELECT", query: "SELECT * FROM `railway_bookings` WHERE `status` = 'Confirmed';", timestamp: "2026-10-26 10:05:12" },
        { id: 4, action: "SELECT", query: "SELECT * FROM `practical7_students` ORDER BY `id` ASC;", timestamp: "2026-10-26 10:08:45" },
        { id: 5, action: "STATUS", query: "SHOW TABLE STATUS FROM `gmca_group10_db`;", timestamp: "2026-10-26 10:10:00" }
    ];

    // Table Schema Metadata for authentic Table Structure view
    const TABLE_SCHEMAS = {
        users: {
            columns: [
                { Field: 'id', Type: 'int(11)', Collation: null, Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment', Privileges: 'select,insert,update,references', Comment: 'Primary key user identifier' },
                { Field: 'full_name', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Student or member full name' },
                { Field: 'enrollment_no', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'UNI', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'College enrollment number' },
                { Field: 'email', Type: 'varchar(120)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'UNI', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Institutional email address' },
                { Field: 'username', Type: 'varchar(50)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'UNI', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Unique login handle' },
                { Field: 'password', Type: 'varchar(255)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Masked password hash' },
                { Field: 'role', Type: 'varchar(30)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'Student Member', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Academic or admin role' },
                { Field: 'pin', Type: 'varchar(10)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: '1234', Extra: '', Privileges: 'select,insert,update,references', Comment: '4-digit quick login security PIN' },
                { Field: 'avatar', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'temp.jpeg', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Profile picture filename' },
                { Field: 'last_login', Type: 'datetime', Collation: null, Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Timestamp of last authentication' },
                { Field: 'created_at', Type: 'timestamp', Collation: null, Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Registration date & time' }
            ],
            indexes: [
                { Key_name: 'PRIMARY', Type: 'BTREE', Unique: 'Yes', Column_name: 'id', Cardinality: 6 },
                { Key_name: 'idx_enrollment', Type: 'BTREE', Unique: 'Yes', Column_name: 'enrollment_no', Cardinality: 6 },
                { Key_name: 'idx_email', Type: 'BTREE', Unique: 'Yes', Column_name: 'email', Cardinality: 6 },
                { Key_name: 'idx_username', Type: 'BTREE', Unique: 'Yes', Column_name: 'username', Cardinality: 6 }
            ],
            comment: 'System user accounts, credentials, and authentication state'
        },
        login_activity: {
            columns: [
                { Field: 'id', Type: 'int(11)', Collation: null, Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment', Privileges: 'select,insert,update,references', Comment: 'Log identifier' },
                { Field: 'user_email', Type: 'varchar(120)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'MUL', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Email of user who attempted login' },
                { Field: 'ip_address', Type: 'varchar(45)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Client IP address' },
                { Field: 'user_agent', Type: 'varchar(255)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Browser user-agent string' },
                { Field: 'login_time', Type: 'datetime', Collation: null, Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Timestamp of login event' },
                { Field: 'status', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'SUCCESS', Extra: '', Privileges: 'select,insert,update,references', Comment: 'SUCCESS or FAILED' }
            ],
            indexes: [
                { Key_name: 'PRIMARY', Type: 'BTREE', Unique: 'Yes', Column_name: 'id', Cardinality: 6 },
                { Key_name: 'idx_user_email', Type: 'BTREE', Unique: 'No', Column_name: 'user_email', Cardinality: 3 }
            ],
            comment: 'Audit log of all login events from website authentication forms'
        },
        railway_bookings: {
            columns: [
                { Field: 'id', Type: 'int(11)', Collation: null, Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment', Privileges: 'select,insert,update,references', Comment: 'Reservation record ID' },
                { Field: 'pnr', Type: 'varchar(15)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'UNI', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Unique 10-digit PNR number' },
                { Field: 'passenger_name', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Primary passenger name' },
                { Field: 'train_name', Type: 'varchar(120)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Train number and route name' },
                { Field: 'travel_class', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: 'SL', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Class (SL, 3A, 2A, 1A, CC)' },
                { Field: 'quota', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'GN', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Quota code (General, Tatkal, Ladies)' },
                { Field: 'passengers', Type: 'int(11)', Collation: null, Null: 'NO', Key: '', Default: '1', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Number of booked berths' },
                { Field: 'journey_date', Type: 'date', Collation: null, Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Date of travel' },
                { Field: 'total_fare', Type: 'decimal(10,2)', Collation: null, Null: 'NO', Key: '', Default: '0.00', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Calculated fare amount in INR' },
                { Field: 'status', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'Confirmed', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Booking status' },
                { Field: 'user_enrollment', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'MUL', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Student account that booked the ticket' },
                { Field: 'booking_date', Type: 'datetime', Collation: null, Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Creation timestamp' }
            ],
            indexes: [
                { Key_name: 'PRIMARY', Type: 'BTREE', Unique: 'Yes', Column_name: 'id', Cardinality: 6 },
                { Key_name: 'idx_pnr', Type: 'BTREE', Unique: 'Yes', Column_name: 'pnr', Cardinality: 6 },
                { Key_name: 'idx_user_enroll', Type: 'BTREE', Unique: 'No', Column_name: 'user_enrollment', Cardinality: 3 }
            ],
            comment: 'Practical 3 Indian Railways booking transactions and ticket ledgers'
        },
        practical7_students: {
            columns: [
                { Field: 'id', Type: 'int(11)', Collation: null, Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment', Privileges: 'select,insert,update,references', Comment: 'Student entry ID' },
                { Field: 'enrollment', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'MUL', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'GTU enrollment' },
                { Field: 'full_name', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Full candidate name' },
                { Field: 'email', Type: 'varchar(120)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Candidate contact email' },
                { Field: 'phone', Type: 'varchar(15)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Mobile contact' },
                { Field: 'department', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: 'MCA', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Department' },
                { Field: 'semester', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: 'Semester 2', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Current academic semester' },
                { Field: 'gender', Type: 'varchar(15)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'Not Specified', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Gender' },
                { Field: 'dob', Type: 'date', Collation: null, Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Date of birth' },
                { Field: 'skills', Type: 'text', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Technical competencies' },
                { Field: 'project_title', Type: 'varchar(255)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Capstone project title' },
                { Field: 'project_desc', Type: 'text', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Detailed abstract' },
                { Field: 'address', Type: 'text', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Residential location' },
                { Field: 'status', Type: 'varchar(30)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'Approved', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Faculty approval status' },
                { Field: 'created_at', Type: 'timestamp', Collation: null, Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Submission timestamp' }
            ],
            indexes: [
                { Key_name: 'PRIMARY', Type: 'BTREE', Unique: 'Yes', Column_name: 'id', Cardinality: 6 },
                { Key_name: 'idx_enrollment', Type: 'BTREE', Unique: 'No', Column_name: 'enrollment', Cardinality: 6 }
            ],
            comment: 'Practical 7 academic candidate project submissions and skill matrix'
        },
        user_profiles: {
            columns: [
                { Field: 'id', Type: 'int(11)', Collation: null, Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment', Privileges: 'select,insert,update,references', Comment: 'Profile ID' },
                { Field: 'full_name', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Student name' },
                { Field: 'enrollment_no', Type: 'varchar(20)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: 'MUL', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'College enrollment' },
                { Field: 'email', Type: 'varchar(120)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Contact email' },
                { Field: 'course', Type: 'varchar(100)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: 'Master of Computer Applications (MCA)', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Program name' },
                { Field: 'bio', Type: 'text', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Academic statement' },
                { Field: 'skills', Type: 'varchar(255)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Comma-separated skills' },
                { Field: 'city', Type: 'varchar(60)', Collation: 'utf8mb4_unicode_ci', Null: 'YES', Key: '', Default: 'Ahmedabad', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Home city' },
                { Field: 'submitted_at', Type: 'datetime', Collation: null, Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Form submission timestamp' }
            ],
            indexes: [
                { Key_name: 'PRIMARY', Type: 'BTREE', Unique: 'Yes', Column_name: 'id', Cardinality: 5 },
                { Key_name: 'idx_enroll', Type: 'BTREE', Unique: 'No', Column_name: 'enrollment_no', Cardinality: 5 }
            ],
            comment: 'Practical 11.3 dynamic user profile submissions and form handling records'
        },
        sql_query_logs: {
            columns: [
                { Field: 'id', Type: 'int(11)', Collation: null, Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment', Privileges: 'select,insert,update,references', Comment: 'Audit log identifier' },
                { Field: 'action', Type: 'varchar(30)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: 'QUERY', Extra: '', Privileges: 'select,insert,update,references', Comment: 'Action category' },
                { Field: 'query', Type: 'text', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Raw SQL query string' },
                { Field: 'timestamp', Type: 'varchar(50)', Collation: 'utf8mb4_unicode_ci', Null: 'NO', Key: '', Default: null, Extra: '', Privileges: 'select,insert,update,references', Comment: 'Execution timestamp' }
            ],
            indexes: [
                { Key_name: 'PRIMARY', Type: 'BTREE', Unique: 'Yes', Column_name: 'id', Cardinality: 5 }
            ],
            comment: 'Real-time query execution audit ledger and query monitoring logs'
        }
    };

    // Helper: Initialize LocalStorage DB with rich Indian datasets
    function initDatabase(force) {
        if (force || !localStorage.getItem(DB_USERS_KEY)) {
            localStorage.setItem(DB_USERS_KEY, JSON.stringify(SEED_USERS));
        }
        if (force || !localStorage.getItem(DB_LOGIN_ACTIVITY_KEY)) {
            localStorage.setItem(DB_LOGIN_ACTIVITY_KEY, JSON.stringify(SEED_LOGIN_ACTIVITY));
        }
        if (force || !localStorage.getItem(DB_RAILWAY_BOOKINGS_KEY)) {
            localStorage.setItem(DB_RAILWAY_BOOKINGS_KEY, JSON.stringify(SEED_RAILWAY_BOOKINGS));
        }
        if (force || !localStorage.getItem(DB_PRACTICAL7_KEY)) {
            localStorage.setItem(DB_PRACTICAL7_KEY, JSON.stringify(SEED_STUDENTS));
        }
        if (force || !localStorage.getItem(DB_USER_PROFILES_KEY)) {
            localStorage.setItem(DB_USER_PROFILES_KEY, JSON.stringify(SEED_USER_PROFILES));
        }
        if (force || !localStorage.getItem(DB_SQL_LOG_KEY)) {
            localStorage.setItem(DB_SQL_LOG_KEY, JSON.stringify(SEED_SQL_LOGS));
        }
    }

    // Helper: Append SQL query to log
    function logSQL(query, action) {
        try {
            const logs = JSON.parse(localStorage.getItem(DB_SQL_LOG_KEY) || '[]');
            logs.unshift({
                id: logs.length + 1,
                timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
                query: query,
                action: action || 'QUERY'
            });
            if (logs.length > 100) logs.pop();
            localStorage.setItem(DB_SQL_LOG_KEY, JSON.stringify(logs));
        } catch (e) {
            console.error("SQL Log error:", e);
        }
    }

    // Helper: get table key
    function getStorageKey(tableName) {
        const t = (tableName || '').toLowerCase().trim();
        switch (t) {
            case 'users': return DB_USERS_KEY;
            case 'login_activity': return DB_LOGIN_ACTIVITY_KEY;
            case 'railway_bookings': return DB_RAILWAY_BOOKINGS_KEY;
            case 'practical7_students': return DB_PRACTICAL7_KEY;
            case 'user_profiles': return DB_USER_PROFILES_KEY;
            case 'sql_query_logs':
            case 'gmca_sql_audit_logs': return DB_SQL_LOG_KEY;
            default: return null;
        }
    }

    function getTableRows(tableName) {
        initDatabase();
        const key = getStorageKey(tableName);
        if (!key) return [];
        try {
            return JSON.parse(localStorage.getItem(key) || '[]');
        } catch (e) {
            return [];
        }
    }

    function setTableRows(tableName, rows) {
        const key = getStorageKey(tableName);
        if (key) {
            localStorage.setItem(key, JSON.stringify(rows));
        }
    }

    // Core phpMyAdmin 5.x Backend Simulation Engine
    const PhpMyAdmin = {
        getServerOverview: function () {
            initDatabase();
            const tables = this.getDbStructure().tables;
            let totalRows = 0;
            let totalBytes = 0;
            tables.forEach(t => {
                totalRows += t.Rows;
                totalBytes += t.Data_length + t.Index_length;
            });

            return {
                server: "Cloud MySQL 8.0.35-GMCA-Cluster",
                version: "8.0.35-InnoDB",
                user: "group10_admin@cloud-sql.internal",
                database: "gmca_group10_db",
                charset: "utf8mb4",
                collation: "utf8mb4_unicode_ci",
                port: 3306,
                socket: "TCP/IP",
                uptime: "14 days, 6 hours, 28 minutes",
                active_db: "gmca_group10_db",
                databases: [
                    "information_schema",
                    "gmca_group10_db",
                    "mysql",
                    "performance_schema",
                    "sys"
                ],
                total_tables: tables.length,
                total_rows: totalRows,
                total_size_kib: (totalBytes / 1024).toFixed(1)
            };
        },

        getDbStructure: function () {
            initDatabase();
            const tableNames = ['users', 'login_activity', 'railway_bookings', 'practical7_students', 'user_profiles', 'sql_query_logs'];
            let totalRows = 0;
            let totalData = 0;
            let totalIndex = 0;

            const tables = tableNames.map(name => {
                const rows = getTableRows(name);
                const rowCount = rows.length;
                const dataLength = 16384 + (rowCount * 256);
                const indexLength = 16384;
                const totalLength = dataLength + indexLength;

                totalRows += rowCount;
                totalData += dataLength;
                totalIndex += indexLength;

                const schema = TABLE_SCHEMAS[name] || {};

                return {
                    Name: name,
                    Engine: "InnoDB",
                    Rows: rowCount,
                    Avg_row_length: rowCount > 0 ? Math.round(dataLength / rowCount) : 0,
                    Data_length: dataLength,
                    Index_length: indexLength,
                    Total_length: totalLength,
                    Size_formatted: (totalLength / 1024).toFixed(1) + " KiB",
                    Collation: "utf8mb4_unicode_ci",
                    Comment: schema.comment || "",
                    Create_time: "2026-08-15 10:00:00"
                };
            });

            const totalBytes = totalData + totalIndex;

            return {
                database: "gmca_group10_db",
                tables: tables,
                summary: {
                    table_count: tables.length,
                    total_rows: totalRows,
                    total_data_kib: (totalData / 1024).toFixed(1) + " KiB",
                    total_index_kib: (totalIndex / 1024).toFixed(1) + " KiB",
                    total_size_kib: (totalBytes / 1024).toFixed(1) + " KiB",
                    default_engine: "InnoDB",
                    default_collation: "utf8mb4_unicode_ci"
                }
            };
        },

        getTableBrowse: function (tableName, limit, offset, sortCol, sortDir, search) {
            initDatabase();
            const start = performance.now();
            const t = (tableName || 'users').toLowerCase().trim();
            const schema = TABLE_SCHEMAS[t];

            if (!schema) {
                return {
                    success: false,
                    error: `#1146 - Table 'gmca_group10_db.${t}' doesn't exist`
                };
            }

            let rows = getTableRows(t);
            const columns = schema.columns.map(c => c.Field);

            // Filter search if provided
            if (search && search.trim() !== '') {
                const term = search.trim().toLowerCase();
                rows = rows.filter(r => {
                    return columns.some(c => {
                        const val = r[c];
                        return val !== null && val !== undefined && String(val).toLowerCase().indexOf(term) !== -1;
                    });
                });
            }

            // Sort
            if (sortCol && columns.indexOf(sortCol) !== -1) {
                const dir = (sortDir || 'ASC').toUpperCase() === 'DESC' ? -1 : 1;
                rows.sort((a, b) => {
                    const va = a[sortCol];
                    const vb = b[sortCol];
                    if (va < vb) return -1 * dir;
                    if (va > vb) return 1 * dir;
                    return 0;
                });
            }

            const totalRows = rows.length;
            const lim = parseInt(limit, 10) || 25;
            const off = parseInt(offset, 10) || 0;
            const pagedRows = rows.slice(off, off + lim);

            const durationSec = ((performance.now() - start) / 1000 + 0.0008).toFixed(4);
            const sqlQuery = `SELECT * FROM \`${t}\`${search ? " WHERE ... " : ""}${sortCol ? " ORDER BY `" + sortCol + "` " + (sortDir || 'ASC') : ""} LIMIT ${off}, ${lim};`;

            return {
                success: true,
                table: t,
                query: sqlQuery,
                execution_time_seconds: durationSec,
                total_rows: totalRows,
                offset: off,
                limit: lim,
                columns: columns,
                rows: pagedRows
            };
        },

        getTableStructure: function (tableName) {
            initDatabase();
            const t = (tableName || 'users').toLowerCase().trim();
            const schema = TABLE_SCHEMAS[t];

            if (!schema) {
                return {
                    success: false,
                    error: `#1146 - Table 'gmca_group10_db.${t}' doesn't exist`
                };
            }

            const rows = getTableRows(t);
            const dataBytes = 16384 + (rows.length * 256);
            const indexBytes = 16384;

            return {
                success: true,
                table: t,
                columns: schema.columns,
                indexes: schema.indexes,
                comment: schema.comment,
                space_usage: {
                    data_size: (dataBytes / 1024).toFixed(1) + " KiB",
                    index_size: (indexBytes / 1024).toFixed(1) + " KiB",
                    total_size: ((dataBytes + indexBytes) / 1024).toFixed(1) + " KiB",
                    row_count: rows.length
                }
            };
        },

        insertRow: function (tableName, rowData) {
            initDatabase();
            const t = (tableName || '').toLowerCase().trim();
            const schema = TABLE_SCHEMAS[t];
            if (!schema) return { success: false, error: "Invalid table name." };

            const rows = getTableRows(t);
            let nextId = 1;
            rows.forEach(r => {
                const idVal = parseInt(r.id, 10);
                if (!isNaN(idVal) && idVal >= nextId) nextId = idVal + 1;
            });

            const newRow = Object.assign({}, rowData);
            if (!newRow.id) newRow.id = nextId;

            // Timestamp defaults
            if (schema.columns.some(c => c.Field === 'created_at') && !newRow.created_at) {
                newRow.created_at = new Date().toISOString().replace('T', ' ').substring(0, 19);
            }
            if (schema.columns.some(c => c.Field === 'booking_date') && !newRow.booking_date) {
                newRow.booking_date = new Date().toISOString().replace('T', ' ').substring(0, 19);
            }
            if (schema.columns.some(c => c.Field === 'submitted_at') && !newRow.submitted_at) {
                newRow.submitted_at = new Date().toISOString().replace('T', ' ').substring(0, 19);
            }

            rows.unshift(newRow);
            setTableRows(t, rows);

            const sql = `INSERT INTO \`${t}\` (${Object.keys(newRow).map(k => '`' + k + '`').join(', ')}) VALUES (${Object.values(newRow).map(v => typeof v === 'string' ? "'" + v.replace(/'/g, "''") + "'" : v).join(', ')});`;
            logSQL(sql, 'INSERT');

            return {
                success: true,
                message: "1 row inserted.",
                inserted_id: newRow.id,
                affected_rows: 1,
                query: sql
            };
        },

        updateRow: function (tableName, id, rowData) {
            initDatabase();
            const t = (tableName || '').toLowerCase().trim();
            const rows = getTableRows(t);
            const idx = rows.findIndex(r => String(r.id) === String(id));

            if (idx === -1) {
                return { success: false, error: `Row with id ${id} not found.` };
            }

            rows[idx] = Object.assign({}, rows[idx], rowData);
            setTableRows(t, rows);

            const sql = `UPDATE \`${t}\` SET ${Object.keys(rowData).map(k => '`' + k + "` = '" + String(rowData[k]).replace(/'/g, "''") + "'").join(', ')} WHERE \`id\` = ${id};`;
            logSQL(sql, 'UPDATE');

            return {
                success: true,
                message: "1 row updated.",
                affected_rows: 1,
                query: sql
            };
        },

        deleteRow: function (tableName, id) {
            initDatabase();
            const t = (tableName || '').toLowerCase().trim();
            let rows = getTableRows(t);
            const initialCount = rows.length;
            rows = rows.filter(r => String(r.id) !== String(id));

            if (rows.length === initialCount) {
                return { success: false, error: `Record with id ${id} not found.` };
            }

            setTableRows(t, rows);
            const sql = `DELETE FROM \`${t}\` WHERE \`id\` = ${id};`;
            logSQL(sql, 'DELETE');

            return {
                success: true,
                message: "1 row deleted.",
                affected_rows: 1,
                query: sql
            };
        },

        emptyTable: function (tableName) {
            initDatabase();
            const t = (tableName || '').toLowerCase().trim();
            setTableRows(t, []);
            const sql = `TRUNCATE TABLE \`${t}\`;`;
            logSQL(sql, 'TRUNCATE');

            return {
                success: true,
                message: `MySQL returned an empty result set (Table \`${t}\` truncated).`,
                query: sql
            };
        },

        resetSeed: function () {
            initDatabase(true);
            logSQL("CALL ResetAllDatabaseSeedTables();", "RESET");
            return {
                success: true,
                message: "All 6 database tables have been reset to default realistic Indian seed records."
            };
        },

        // Live App Form Integration Hooks
        recordLogin: function (userEmail, status, ip) {
            initDatabase();
            const email = (userEmail || '').trim().toLowerCase();
            const now = new Date().toISOString().replace('T', ' ').substring(0, 19);

            // Update last_login in users table
            const users = getTableRows('users');
            const user = users.find(u => u.email.toLowerCase() === email || u.username.toLowerCase() === email || u.enrollment_no.toLowerCase() === email);
            if (user) {
                user.last_login = now;
                setTableRows('users', users);
            }

            // Insert into login_activity
            this.insertRow('login_activity', {
                user_email: user ? user.email : userEmail,
                ip_address: ip || "49.36.18.22",
                user_agent: navigator.userAgent.substring(0, 120),
                login_time: now,
                status: status || "SUCCESS"
            });
        },

        recordSignup: function (userObj) {
            initDatabase();
            const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
            const newRecord = {
                full_name: userObj.full_name || userObj.name || "New Student",
                enrollment_no: userObj.enrollment_no || userObj.enrollment || "26GMCA99",
                email: userObj.email || (userObj.username + "@gmca.ac.in"),
                username: userObj.username || ("user" + Math.floor(Math.random() * 1000)),
                password: userObj.password || "password123",
                role: userObj.role || "Student Member",
                pin: userObj.pin || "1234",
                avatar: userObj.avatar || "temp.jpeg",
                last_login: now,
                created_at: now
            };

            const res = this.insertRow('users', newRecord);

            // Also record login activity
            this.recordLogin(newRecord.email, 'SUCCESS', '49.36.18.22');

            return res;
        },

        recordBooking: function (b) {
            initDatabase();
            const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
            return this.insertRow('railway_bookings', {
                pnr: b.pnr,
                passenger_name: b.passenger_name || b.passengerName,
                train_name: b.train_name || b.train,
                travel_class: b.travel_class || b.travelClass || "SL",
                quota: b.quota || "GN",
                passengers: b.passengers || 1,
                journey_date: b.journey_date || b.date,
                total_fare: b.total_fare || b.totalFare || 0.00,
                status: b.status || "Confirmed",
                user_enrollment: b.user_enrollment || b.userEnrollment || "26GMCA61",
                booking_date: now
            });
        },

        recordProfile: function (p) {
            initDatabase();
            const now = new Date().toISOString().replace('T', ' ').substring(0, 19);
            return this.insertRow('user_profiles', {
                full_name: p.fullName || p.full_name,
                enrollment_no: p.enrollment || p.enrollment_no || "26GMCA52",
                email: p.email || "vaibhav@gmca.ac.in",
                course: p.course || "Master of Computer Applications (MCA)",
                bio: p.bio || "",
                skills: p.skills || "PHP, MySQL",
                city: p.city || "Ahmedabad",
                submitted_at: now
            });
        },

        runSql: function (sql) {
            initDatabase();
            const start = performance.now();
            const q = (sql || '').trim();

            if (!q) {
                return { success: false, error: "#1065 - Query was empty" };
            }

            logSQL(q, 'CONSOLE');

            // 1. SHOW TABLES
            if (/^SHOW\s+TABLES/i.test(q)) {
                const tables = this.getDbStructure().tables.map(t => ({ "Tables_in_gmca_group10_db": t.Name }));
                const dur = ((performance.now() - start) / 1000 + 0.0006).toFixed(4);
                return {
                    success: true,
                    query: q,
                    execution_time_seconds: dur,
                    row_count: tables.length,
                    columns: ["Tables_in_gmca_group10_db"],
                    rows: tables
                };
            }

            // 2. SHOW TABLE STATUS
            if (/^SHOW\s+TABLE\s+STATUS/i.test(q)) {
                const tables = this.getDbStructure().tables;
                const dur = ((performance.now() - start) / 1000 + 0.0009).toFixed(4);
                return {
                    success: true,
                    query: q,
                    execution_time_seconds: dur,
                    row_count: tables.length,
                    columns: Object.keys(tables[0] || {}),
                    rows: tables
                };
            }

            // 3. Server info
            if (/^SELECT\s+(DATABASE|VERSION|USER)/i.test(q)) {
                const row = {
                    "DATABASE()": "gmca_group10_db",
                    "VERSION()": "8.0.35-GMCA-Cluster",
                    "USER()": "group10_admin@cloud-sql.internal"
                };
                const dur = ((performance.now() - start) / 1000 + 0.0005).toFixed(4);
                return {
                    success: true,
                    query: q,
                    execution_time_seconds: dur,
                    row_count: 1,
                    columns: Object.keys(row),
                    rows: [row]
                };
            }

            // 4. DESCRIBE or SHOW COLUMNS
            const descMatch = q.match(/^(?:DESCRIBE|DESC|SHOW\s+FULL\s+COLUMNS\s+FROM|SHOW\s+COLUMNS\s+FROM)\s+[`']?([a-zA-Z0-9_]+)[`']?/i);
            if (descMatch) {
                const target = descMatch[1].toLowerCase();
                const struct = this.getTableStructure(target);
                if (struct.success) {
                    const dur = ((performance.now() - start) / 1000 + 0.0007).toFixed(4);
                    return {
                        success: true,
                        query: q,
                        execution_time_seconds: dur,
                        row_count: struct.columns.length,
                        columns: Object.keys(struct.columns[0] || {}),
                        rows: struct.columns
                    };
                }
                return struct;
            }

            // 5. SELECT query
            const selMatch = q.match(/^SELECT\s+(.+?)\s+FROM\s+[`']?([a-zA-Z0-9_]+)[`']?(.*)$/i);
            if (selMatch) {
                const target = selMatch[2].toLowerCase();
                const schema = TABLE_SCHEMAS[target];
                if (!schema) {
                    return {
                        success: false,
                        error: `#1146 - Table 'gmca_group10_db.${target}' doesn't exist`,
                        query: q
                    };
                }

                const rows = getTableRows(target);
                const columns = schema.columns.map(c => c.Field);
                const dur = ((performance.now() - start) / 1000 + 0.0011).toFixed(4);

                return {
                    success: true,
                    query: q,
                    execution_time_seconds: dur,
                    row_count: rows.length,
                    columns: columns,
                    rows: rows
                };
            }

            // Default DML success response
            const dur = ((performance.now() - start) / 1000 + 0.0014).toFixed(4);
            return {
                success: true,
                query: q,
                execution_time_seconds: dur,
                message: "Query executed successfully. 1 row affected.",
                affected_rows: 1
            };
        }
    };

    // Backward compatibility for existing practicals
    const Auth = {
        getUsers: function () {
            initDatabase();
            return JSON.parse(localStorage.getItem(DB_USERS_KEY) || '[]');
        },
        getCurrentUser: function () {
            try {
                const raw = localStorage.getItem(DB_AUTH_KEY);
                return raw ? JSON.parse(raw) : null;
            } catch (e) {
                return null;
            }
        },
        login: function (usernameOrEmail, password) {
            initDatabase();
            const users = this.getUsers();
            const identifier = (usernameOrEmail || '').trim().toLowerCase();
            const user = users.find(u =>
                (u.username.toLowerCase() === identifier || u.email.toLowerCase() === identifier || (u.enrollment_no && u.enrollment_no.toLowerCase() === identifier)) &&
                u.password === password
            );

            logSQL(`SELECT * FROM users WHERE (username = '${identifier}' OR email = '${identifier}') AND password = '${password}' LIMIT 1;`, 'SELECT');

            if (user) {
                const sessionUser = {
                    id: user.id,
                    name: user.full_name || user.name,
                    enrollment: user.enrollment_no || user.enrollment,
                    email: user.email,
                    username: user.username,
                    role: user.role,
                    avatar: user.avatar,
                    loginTime: new Date().toLocaleString()
                };
                localStorage.setItem(DB_AUTH_KEY, JSON.stringify(sessionUser));
                PhpMyAdmin.recordLogin(user.email, 'SUCCESS');
                return { success: true, user: sessionUser };
            }

            return { success: false, message: "Invalid username/email or password." };
        },
        logout: function () {
            localStorage.removeItem(DB_AUTH_KEY);
            return { success: true };
        }
    };

    const Practical7 = {
        getAll: function () {
            initDatabase();
            return JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');
        },
        create: function (student) {
            initDatabase();
            return PhpMyAdmin.insertRow('practical7_students', student);
        },
        delete: function (id) {
            initDatabase();
            return PhpMyAdmin.deleteRow('practical7_students', id);
        }
    };

    // Code copying & download utilities
    function copyCode(elementId, btnElem) {
        const target = document.getElementById(elementId);
        if (!target) return;
        const text = target.innerText || target.textContent;
        navigator.clipboard.writeText(text).then(() => {
            if (btnElem) {
                const originalText = btnElem.innerHTML;
                btnElem.innerHTML = "✓ Copied!";
                btnElem.style.background = "#10B981";
                btnElem.style.color = "#FFFFFF";
                setTimeout(() => {
                    btnElem.innerHTML = originalText;
                    btnElem.style.background = "";
                    btnElem.style.color = "";
                }, 2000);
            }
        }).catch(err => {
            console.error("Failed to copy code: ", err);
        });
    }

    function downloadPhpFile(filename, codeContent) {
        const blob = new Blob([codeContent], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Expose to window
    window.PhpEngine = {
        Auth: Auth,
        Practical7: Practical7,
        PhpMyAdmin: PhpMyAdmin,
        DbExplorer: {
            getTables: function () {
                return PhpMyAdmin.getDbStructure().tables.map(t => ({
                    name: t.Name,
                    engine: t.Engine,
                    rows: t.Rows,
                    size_kb: (t.Total_length / 1024),
                    collation: t.Collation,
                    comment: t.Comment
                }));
            },
            getTableDetails: function (tbl) {
                const browse = PhpMyAdmin.getTableBrowse(tbl, 200, 0);
                const struct = PhpMyAdmin.getTableStructure(tbl);
                return {
                    table: tbl,
                    structure: struct.columns,
                    indexes: struct.indexes,
                    columns: browse.columns,
                    rows: browse.rows,
                    total_rows: browse.total_rows
                };
            },
            runQuery: function (sql) {
                return PhpMyAdmin.runSql(sql);
            }
        },
        copyCode: copyCode,
        downloadPhpFile: downloadPhpFile,
        logSQL: logSQL,
        initDatabase: initDatabase
    };

    // Initialize database immediately on script load
    initDatabase();

})(window);
