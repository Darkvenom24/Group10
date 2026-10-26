/**
 * php_engine.js - Shared Client-Side PHP Simulation & Relational Storage Engine
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Powers all PHP Practicals (9.1 to 12) seamlessly on Vercel without local XAMPP/MySQL
 */

(function (window) {
    'use strict';

    const DB_USERS_KEY = 'gmca_php_users';
    const DB_AUTH_KEY = 'gmca_php_current_user';
    const DB_PRACTICAL7_KEY = 'gmca_php_practical7_students';
    const DB_SQL_LOG_KEY = 'gmca_php_sql_logs';

    // Initial default seed users for GMCA Group 10
    const DEFAULT_USERS = [
        {
            id: 1,
            name: "Vaibhav Senjaliya",
            enrollment: "26GMCA52",
            email: "vaibhav.senjaliya@gmca.ac.in",
            username: "vaibhav52",
            password: "password123",
            role: "Developer",
            avatar: "vaibhav.jpeg",
            createdAt: "2026-08-15 10:30:00"
        },
        {
            id: 2,
            name: "Ridham Bambhaniya",
            enrollment: "26GMCA61",
            email: "ridham.bambhaniya@gmca.ac.in",
            username: "ridham61",
            password: "password123",
            role: "Team Lead",
            avatar: "ridham.jpeg",
            createdAt: "2026-08-15 10:30:00"
        },
        {
            id: 3,
            name: "Riya Thakkar",
            enrollment: "26GMCA33",
            email: "riya.thakkar@gmca.ac.in",
            username: "riya33",
            password: "password123",
            role: "Core Member",
            avatar: "riya.jpeg",
            createdAt: "2026-08-15 10:30:00"
        }
    ];

    // Initial default student records for Practical 7
    const DEFAULT_STUDENTS = [
        {
            id: 101,
            enrollment: "26GMCA52",
            fullName: "Vaibhav Senjaliya",
            email: "vaibhav.senjaliya@gmca.ac.in",
            phone: "9876543210",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Male",
            dob: "2003-04-12",
            skills: ["PHP", "MySQL", "JavaScript", "HTML5", "CSS3"],
            projectTitle: "Automated Railway Reservation & Dynamic Pricing Engine",
            projectDesc: "Built an interactive train ticket booking and fare calculation web application with persistent local dataset and fare rules.",
            address: "Ahmedabad, Gujarat",
            createdAt: "2026-09-01 11:20:00",
            status: "Approved"
        },
        {
            id: 102,
            enrollment: "26GMCA61",
            fullName: "Ridham Bambhaniya",
            email: "ridham.bambhaniya@gmca.ac.in",
            phone: "9825012345",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Male",
            dob: "2002-11-20",
            skills: ["PHP", "MySQL", "HTML5", "CSS3", "Python"],
            projectTitle: "Institutional Web Portal & Practical Catalog System",
            projectDesc: "Developed multi-tier responsive institutional portal architecture demonstrating academic practical catalogs and member profiles.",
            address: "Maninagar, Ahmedabad, Gujarat",
            createdAt: "2026-09-02 14:15:00",
            status: "Approved"
        },
        {
            id: 103,
            enrollment: "26GMCA33",
            fullName: "Riya Thakkar",
            email: "riya.thakkar@gmca.ac.in",
            phone: "9898711223",
            department: "Master of Computer Applications (MCA)",
            semester: "Semester 2",
            gender: "Female",
            dob: "2003-08-19",
            skills: ["PHP", "JavaScript", "HTML5", "CSS3"],
            projectTitle: "Real-time Expression Evaluator & Keyboard Interface",
            projectDesc: "Engineered arithmetic parser with event-driven keyboard shortcuts, error handling and dynamic layout stacking.",
            address: "Satellite, Ahmedabad, Gujarat",
            createdAt: "2026-09-03 16:45:00",
            status: "Approved"
        }
    ];

    // Helper: Initialize LocalStorage DB
    function initDatabase() {
        if (!localStorage.getItem(DB_USERS_KEY)) {
            localStorage.setItem(DB_USERS_KEY, JSON.stringify(DEFAULT_USERS));
        }
        if (!localStorage.getItem(DB_PRACTICAL7_KEY)) {
            localStorage.setItem(DB_PRACTICAL7_KEY, JSON.stringify(DEFAULT_STUDENTS));
        }
        if (!localStorage.getItem(DB_SQL_LOG_KEY)) {
            localStorage.setItem(DB_SQL_LOG_KEY, JSON.stringify([
                {
                    timestamp: new Date().toLocaleTimeString(),
                    query: "SELECT * FROM `gmca_practical7_students` WHERE 1 ORDER BY `id` DESC LIMIT 10;",
                    action: "INIT"
                }
            ]));
        }
    }

    // Helper: Append SQL query to log
    function logSQL(query, action) {
        try {
            const logs = JSON.parse(localStorage.getItem(DB_SQL_LOG_KEY) || '[]');
            logs.unshift({
                timestamp: new Date().toLocaleTimeString(),
                query: query,
                action: action || 'QUERY'
            });
            if (logs.length > 50) logs.pop();
            localStorage.setItem(DB_SQL_LOG_KEY, JSON.stringify(logs));
        } catch (e) {
            console.error("SQL Log error:", e);
        }
    }

    // Auth methods
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
                (u.username.toLowerCase() === identifier || u.email.toLowerCase() === identifier || u.enrollment.toLowerCase() === identifier) &&
                u.password === password
            );

            logSQL(`SELECT * FROM users WHERE (username = '${identifier}' OR email = '${identifier}') AND password = '${password}' LIMIT 1;`, 'SELECT');

            if (user) {
                const sessionUser = {
                    id: user.id,
                    name: user.name,
                    enrollment: user.enrollment,
                    email: user.email,
                    username: user.username,
                    role: user.role,
                    avatar: user.avatar,
                    loginTime: new Date().toLocaleString()
                };
                localStorage.setItem(DB_AUTH_KEY, JSON.stringify(sessionUser));
                return { success: true, user: sessionUser };
            }
            return { success: false, message: "Invalid credentials! Please check your username/email and password." };
        },
        register: function (userData) {
            initDatabase();
            const users = this.getUsers();

            // Validate uniqueness
            const exists = users.find(u =>
                u.username.toLowerCase() === userData.username.toLowerCase() ||
                u.email.toLowerCase() === userData.email.toLowerCase() ||
                u.enrollment.toUpperCase() === userData.enrollment.toUpperCase()
            );

            if (exists) {
                return { success: false, message: "User with this username, email or enrollment already exists!" };
            }

            const newUser = {
                id: Date.now(),
                name: userData.name.trim(),
                enrollment: userData.enrollment.trim().toUpperCase(),
                email: userData.email.trim().toLowerCase(),
                username: userData.username.trim(),
                password: userData.password,
                role: userData.role || 'Student',
                avatar: userData.avatar || 'vaibhav.jpeg',
                createdAt: new Date().toLocaleString()
            };

            users.push(newUser);
            localStorage.setItem(DB_USERS_KEY, JSON.stringify(users));

            logSQL(`INSERT INTO users (name, enrollment, email, username, password, role) VALUES ('${newUser.name}', '${newUser.enrollment}', '${newUser.email}', '${newUser.username}', '***', '${newUser.role}');`, 'INSERT');

            // Auto-login the new user
            const sessionUser = {
                id: newUser.id,
                name: newUser.name,
                enrollment: newUser.enrollment,
                email: newUser.email,
                username: newUser.username,
                role: newUser.role,
                avatar: newUser.avatar,
                loginTime: new Date().toLocaleString()
            };
            localStorage.setItem(DB_AUTH_KEY, JSON.stringify(sessionUser));

            return { success: true, user: sessionUser };
        },
        logout: function () {
            localStorage.removeItem(DB_AUTH_KEY);
            return true;
        }
    };

    // Practical 7 Student Records CRUD
    const Practical7 = {
        getAll: function () {
            initDatabase();
            logSQL("SELECT * FROM practical7_students ORDER BY id DESC;", "SELECT");
            return JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');
        },
        getById: function (id) {
            const list = this.getAll();
            return list.find(s => String(s.id) === String(id)) || null;
        },
        getByEnrollment: function (enrollment) {
            const list = this.getAll();
            return list.find(s => s.enrollment.toUpperCase() === (enrollment || '').toUpperCase()) || null;
        },
        add: function (data) {
            initDatabase();
            const list = JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');

            const newRecord = {
                id: Date.now(),
                enrollment: (data.enrollment || '').trim().toUpperCase(),
                fullName: (data.fullName || '').trim(),
                email: (data.email || '').trim(),
                phone: (data.phone || '').trim(),
                department: data.department || 'Master of Computer Applications (MCA)',
                semester: data.semester || 'Semester 2',
                gender: data.gender || 'Not Specified',
                dob: data.dob || '',
                skills: Array.isArray(data.skills) ? data.skills : (data.skills ? data.skills.split(',').map(s => s.trim()) : []),
                projectTitle: (data.projectTitle || '').trim(),
                projectDesc: (data.projectDesc || '').trim(),
                address: (data.address || '').trim(),
                createdAt: new Date().toLocaleString(),
                status: "Submitted"
            };

            list.unshift(newRecord);
            localStorage.setItem(DB_PRACTICAL7_KEY, JSON.stringify(list));

            logSQL(`INSERT INTO practical7_students (enrollment, full_name, email, phone, department, semester, skills, project_title) VALUES ('${newRecord.enrollment}', '${newRecord.fullName}', '${newRecord.email}', '${newRecord.phone}', '${newRecord.department}', '${newRecord.semester}', '${JSON.stringify(newRecord.skills)}', '${newRecord.projectTitle.replace(/'/g, "\\'")}');`, 'INSERT');

            return { success: true, record: newRecord };
        },
        update: function (id, data) {
            initDatabase();
            const list = JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');
            const index = list.findIndex(s => String(s.id) === String(id));

            if (index === -1) {
                return { success: false, message: "Record not found." };
            }

            list[index] = {
                ...list[index],
                fullName: data.fullName !== undefined ? data.fullName.trim() : list[index].fullName,
                email: data.email !== undefined ? data.email.trim() : list[index].email,
                phone: data.phone !== undefined ? data.phone.trim() : list[index].phone,
                department: data.department !== undefined ? data.department : list[index].department,
                semester: data.semester !== undefined ? data.semester : list[index].semester,
                gender: data.gender !== undefined ? data.gender : list[index].gender,
                skills: Array.isArray(data.skills) ? data.skills : (data.skills ? data.skills.split(',').map(s => s.trim()) : list[index].skills),
                projectTitle: data.projectTitle !== undefined ? data.projectTitle.trim() : list[index].projectTitle,
                projectDesc: data.projectDesc !== undefined ? data.projectDesc.trim() : list[index].projectDesc,
                address: data.address !== undefined ? data.address.trim() : list[index].address,
                updatedAt: new Date().toLocaleString()
            };

            localStorage.setItem(DB_PRACTICAL7_KEY, JSON.stringify(list));

            logSQL(`UPDATE practical7_students SET full_name = '${list[index].fullName}', email = '${list[index].email}', department = '${list[index].department}', semester = '${list[index].semester}' WHERE id = ${id};`, 'UPDATE');

            return { success: true, record: list[index] };
        },
        delete: function (id) {
            initDatabase();
            let list = JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');
            const beforeLen = list.length;
            list = list.filter(s => String(s.id) !== String(id));

            if (list.length === beforeLen) {
                return { success: false, message: "Record not found." };
            }

            localStorage.setItem(DB_PRACTICAL7_KEY, JSON.stringify(list));
            logSQL(`DELETE FROM practical7_students WHERE id = ${id};`, 'DELETE');

            return { success: true };
        },
        getLogs: function () {
            initDatabase();
            return JSON.parse(localStorage.getItem(DB_SQL_LOG_KEY) || '[]');
        }
    };

    // Database Explorer Simulation Engine (Foolproof Fallback for Vercel evaluation)
    const DbExplorer = {
        getTables: function () {
            initDatabase();
            const users = JSON.parse(localStorage.getItem(DB_USERS_KEY) || '[]');
            const students = JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');
            const bookings = JSON.parse(localStorage.getItem('gmca_railway_bookings_v3') || '[]');
            const logs = JSON.parse(localStorage.getItem(DB_SQL_LOG_KEY) || '[]');

            return [
                {
                    name: 'users',
                    engine: 'InnoDB',
                    rows: users.length,
                    size_kb: 16.0,
                    collation: 'utf8mb4_unicode_ci',
                    comment: 'System user credentials and academic roles'
                },
                {
                    name: 'practical7_students',
                    engine: 'InnoDB',
                    rows: students.length,
                    size_kb: 32.0,
                    collation: 'utf8mb4_unicode_ci',
                    comment: 'Practical 7 academic and project registration records'
                },
                {
                    name: 'railway_bookings',
                    engine: 'InnoDB',
                    rows: bookings.length,
                    size_kb: 48.0,
                    collation: 'utf8mb4_unicode_ci',
                    comment: 'Practical 3 Indian Railways booking transactions'
                },
                {
                    name: 'gmca_sql_audit_logs',
                    engine: 'InnoDB',
                    rows: logs.length,
                    size_kb: 16.0,
                    collation: 'utf8mb4_unicode_ci',
                    comment: 'Session SQL query audit trail'
                }
            ];
        },

        getTableDetails: function (tableName) {
            initDatabase();
            const t = (tableName || '').toLowerCase().trim();

            if (t === 'users') {
                const users = JSON.parse(localStorage.getItem(DB_USERS_KEY) || '[]');
                return {
                    table: 'users',
                    structure: [
                        { Field: 'id', Type: 'int(11)', Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment' },
                        { Field: 'enrollment', Type: 'varchar(20)', Null: 'NO', Key: 'UNI', Default: null, Extra: '' },
                        { Field: 'name', Type: 'varchar(100)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'email', Type: 'varchar(120)', Null: 'NO', Key: 'UNI', Default: null, Extra: '' },
                        { Field: 'username', Type: 'varchar(50)', Null: 'NO', Key: 'UNI', Default: null, Extra: '' },
                        { Field: 'password', Type: 'varchar(255)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'role', Type: 'varchar(30)', Null: 'YES', Key: '', Default: 'Student', Extra: '' },
                        { Field: 'created_at', Type: 'timestamp', Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '' }
                    ],
                    indexes: [
                        { key_name: 'PRIMARY', column_name: 'id', non_unique: 0, index_type: 'BTREE' },
                        { key_name: 'enrollment', column_name: 'enrollment', non_unique: 0, index_type: 'BTREE' },
                        { key_name: 'email', column_name: 'email', non_unique: 0, index_type: 'BTREE' },
                        { key_name: 'username', column_name: 'username', non_unique: 0, index_type: 'BTREE' }
                    ],
                    columns: ['id', 'enrollment', 'name', 'email', 'username', 'password', 'role', 'created_at'],
                    rows: users.map(u => ({
                        id: u.id,
                        enrollment: u.enrollment,
                        name: u.name,
                        email: u.email,
                        username: u.username,
                        password: '••••••••',
                        role: u.role,
                        created_at: u.createdAt || '2026-08-15 10:30:00'
                    })),
                    total_rows: users.length
                };
            }

            if (t === 'practical7_students') {
                const students = JSON.parse(localStorage.getItem(DB_PRACTICAL7_KEY) || '[]');
                return {
                    table: 'practical7_students',
                    structure: [
                        { Field: 'id', Type: 'int(11)', Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment' },
                        { Field: 'enrollment', Type: 'varchar(20)', Null: 'NO', Key: 'MUL', Default: null, Extra: '' },
                        { Field: 'full_name', Type: 'varchar(100)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'email', Type: 'varchar(120)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'phone', Type: 'varchar(15)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'department', Type: 'varchar(100)', Null: 'NO', Key: '', Default: 'MCA', Extra: '' },
                        { Field: 'semester', Type: 'varchar(20)', Null: 'NO', Key: '', Default: 'Semester 1', Extra: '' },
                        { Field: 'gender', Type: 'varchar(15)', Null: 'YES', Key: '', Default: 'Not Specified', Extra: '' },
                        { Field: 'dob', Type: 'date', Null: 'YES', Key: '', Default: null, Extra: '' },
                        { Field: 'skills', Type: 'text', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'project_title', Type: 'varchar(255)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'status', Type: 'varchar(30)', Null: 'YES', Key: '', Default: 'Submitted', Extra: '' },
                        { Field: 'created_at', Type: 'timestamp', Null: 'NO', Key: '', Default: 'CURRENT_TIMESTAMP', Extra: '' }
                    ],
                    indexes: [
                        { key_name: 'PRIMARY', column_name: 'id', non_unique: 0, index_type: 'BTREE' },
                        { key_name: 'idx_enrollment', column_name: 'enrollment', non_unique: 1, index_type: 'BTREE' }
                    ],
                    columns: ['id', 'enrollment', 'full_name', 'email', 'phone', 'department', 'semester', 'gender', 'skills', 'project_title', 'status', 'created_at'],
                    rows: students.map(s => ({
                        id: s.id,
                        enrollment: s.enrollment,
                        full_name: s.fullName,
                        email: s.email,
                        phone: s.phone,
                        department: s.department,
                        semester: s.semester,
                        gender: s.gender,
                        skills: Array.isArray(s.skills) ? s.skills.join(', ') : s.skills,
                        project_title: s.projectTitle,
                        status: s.status,
                        created_at: s.createdAt
                    })),
                    total_rows: students.length
                };
            }

            if (t === 'railway_bookings') {
                const bookings = JSON.parse(localStorage.getItem('gmca_railway_bookings_v3') || '[]');
                return {
                    table: 'railway_bookings',
                    structure: [
                        { Field: 'id', Type: 'int(11)', Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment' },
                        { Field: 'pnr', Type: 'varchar(15)', Null: 'NO', Key: 'UNI', Default: null, Extra: '' },
                        { Field: 'passenger_name', Type: 'varchar(100)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'train_name', Type: 'varchar(120)', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'travel_class', Type: 'varchar(20)', Null: 'NO', Key: '', Default: 'SL', Extra: '' },
                        { Field: 'passengers', Type: 'int(11)', Null: 'NO', Key: '', Default: '1', Extra: '' },
                        { Field: 'journey_date', Type: 'date', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'total_fare', Type: 'decimal(10,2)', Null: 'NO', Key: '', Default: '0.00', Extra: '' },
                        { Field: 'status', Type: 'varchar(20)', Null: 'YES', Key: '', Default: 'Confirmed', Extra: '' },
                        { Field: 'user_enrollment', Type: 'varchar(20)', Null: 'NO', Key: 'MUL', Default: null, Extra: '' }
                    ],
                    indexes: [
                        { key_name: 'PRIMARY', column_name: 'id', non_unique: 0, index_type: 'BTREE' },
                        { key_name: 'idx_pnr', column_name: 'pnr', non_unique: 0, index_type: 'BTREE' },
                        { key_name: 'idx_user', column_name: 'user_enrollment', non_unique: 1, index_type: 'BTREE' }
                    ],
                    columns: ['id', 'pnr', 'passenger_name', 'train_name', 'travel_class', 'passengers', 'journey_date', 'total_fare', 'status', 'user_enrollment'],
                    rows: bookings.map((b, idx) => ({
                        id: b.id || (idx + 1),
                        pnr: b.pnr,
                        passenger_name: b.passengerName,
                        train_name: b.train,
                        travel_class: b.travelClass,
                        passengers: b.passengers || 1,
                        journey_date: b.date,
                        total_fare: b.totalFare,
                        status: b.status || 'Confirmed',
                        user_enrollment: b.userEnrollment || '26GMCA61'
                    })),
                    total_rows: bookings.length
                };
            }

            if (t === 'gmca_sql_audit_logs') {
                const logs = JSON.parse(localStorage.getItem(DB_SQL_LOG_KEY) || '[]');
                return {
                    table: 'gmca_sql_audit_logs',
                    structure: [
                        { Field: 'id', Type: 'int(11)', Null: 'NO', Key: 'PRI', Default: null, Extra: 'auto_increment' },
                        { Field: 'action', Type: 'varchar(30)', Null: 'NO', Key: '', Default: 'QUERY', Extra: '' },
                        { Field: 'query', Type: 'text', Null: 'NO', Key: '', Default: null, Extra: '' },
                        { Field: 'timestamp', Type: 'varchar(50)', Null: 'NO', Key: '', Default: null, Extra: '' }
                    ],
                    indexes: [
                        { key_name: 'PRIMARY', column_name: 'id', non_unique: 0, index_type: 'BTREE' }
                    ],
                    columns: ['id', 'action', 'query', 'timestamp'],
                    rows: logs.map((l, idx) => ({
                        id: idx + 1,
                        action: l.action || 'QUERY',
                        query: l.query,
                        timestamp: l.timestamp
                    })),
                    total_rows: logs.length
                };
            }

            return null;
        },

        runQuery: function (sql) {
            initDatabase();
            const start = performance.now();
            const q = (sql || '').trim();

            if (!q) {
                return { success: false, error: "Empty query string provided." };
            }

            logSQL(q, 'CONSOLE');

            // Handle SHOW TABLES
            if (/^SHOW\s+TABLES/i.test(q)) {
                const tables = this.getTables().map(t => ({ "Tables_in_gmca_group10_db": t.name }));
                const duration = Math.round((performance.now() - start) * 100) / 100;
                return {
                    success: true,
                    query: q,
                    execution_time_ms: duration,
                    row_count: tables.length,
                    columns: ["Tables_in_gmca_group10_db"],
                    rows: tables
                };
            }

            // Handle SELECT DATABASE(), VERSION(), USER()
            if (/^SELECT\s+(DATABASE|VERSION|USER)/i.test(q)) {
                const row = {
                    "DATABASE()": "gmca_group10_db",
                    "VERSION()": "8.0.35-GMCA-Relational-Engine",
                    "USER()": "group10_admin@cloud-sql.internal"
                };
                const duration = Math.round((performance.now() - start) * 100) / 100;
                return {
                    success: true,
                    query: q,
                    execution_time_ms: duration,
                    row_count: 1,
                    columns: Object.keys(row),
                    rows: [row]
                };
            }

            // Handle DESCRIBE table / SHOW COLUMNS FROM table
            const descMatch = q.match(/^(?:DESCRIBE|DESC|SHOW\s+COLUMNS\s+FROM)\s+[`]?([a-zA-Z0-9_]+)[`]?/i);
            if (descMatch) {
                const details = this.getTableDetails(descMatch[1]);
                if (details) {
                    const duration = Math.round((performance.now() - start) * 100) / 100;
                    return {
                        success: true,
                        query: q,
                        execution_time_ms: duration,
                        row_count: details.structure.length,
                        columns: ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'],
                        rows: details.structure
                    };
                }
                return {
                    success: false,
                    query: q,
                    error: `#1146 - Table 'gmca_group10_db.${descMatch[1]}' doesn't exist`,
                    execution_time_ms: Math.round((performance.now() - start) * 100) / 100
                };
            }

            // Handle SELECT * FROM table
            const selectMatch = q.match(/^SELECT\s+(.*?)\s+FROM\s+[`]?([a-zA-Z0-9_]+)[`]?/i);
            if (selectMatch) {
                const tableName = selectMatch[2];
                const details = this.getTableDetails(tableName);
                if (details) {
                    let rows = details.rows;

                    // Handle simple WHERE filters
                    if (q.toLowerCase().includes('where')) {
                        const whereClause = q.split(/where/i)[1].split(/order|limit|;/i)[0].trim();
                        if (whereClause.includes('status =') || whereClause.includes('status=')) {
                            const val = whereClause.split('=')[1].replace(/['";\s]/g, '');
                            rows = rows.filter(r => String(r.status || '').toLowerCase() === val.toLowerCase());
                        } else if (whereClause.includes('role =') || whereClause.includes('role=')) {
                            const val = whereClause.split('=')[1].replace(/['";\s]/g, '');
                            rows = rows.filter(r => String(r.role || '').toLowerCase() === val.toLowerCase());
                        }
                    }

                    // Handle LIMIT
                    const limitMatch = q.match(/LIMIT\s+(\d+)/i);
                    if (limitMatch) {
                        rows = rows.slice(0, parseInt(limitMatch[1], 10));
                    }

                    const duration = Math.round((performance.now() - start) * 100) / 100;
                    return {
                        success: true,
                        query: q,
                        execution_time_ms: duration,
                        row_count: rows.length,
                        columns: details.columns,
                        rows: rows
                    };
                }

                return {
                    success: false,
                    query: q,
                    error: `#1146 - Table 'gmca_group10_db.${tableName}' doesn't exist`,
                    execution_time_ms: Math.round((performance.now() - start) * 100) / 100
                };
            }

            // Check for syntax error simulation
            return {
                success: false,
                query: q,
                error: `#1064 - You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '${q.slice(0, 30)}...'`,
                execution_time_ms: Math.round((performance.now() - start) * 100) / 100
            };
        }
    };

    // Code copying & file download utilities
    function copyCode(elementId, btnElem) {
        const target = document.getElementById(elementId);
        if (!target) return;
        const text = target.innerText || target.textContent;
        navigator.clipboard.writeText(text).then(() => {
            if (btnElem) {
                const originalText = btnElem.innerHTML;
                btnElem.innerHTML = "✓ Copied!";
                btnElem.style.background = "var(--green)";
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
        DbExplorer: DbExplorer,
        copyCode: copyCode,
        downloadPhpFile: downloadPhpFile,
        logSQL: logSQL
    };

    // Auto-init on load
    initDatabase();

})(window);
