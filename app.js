/**
 * Group 10 — Web Technology Practicals & Projects
 * Government MCA College, Maninagar (GMCA)
 * Single Page Application (SPA) Engine powered by AngularJS
 */

(function () {
    'use strict';

    var app = angular.module('group10App', ['ngRoute']);

    // =========================================================================
    // 1. ROUTING CONFIGURATION ($routeProvider)
    // =========================================================================
    app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
        $locationProvider.hashPrefix('');

        $routeProvider
            .when('/', {
                templateUrl: 'views/home.html',
                controller: 'HomeController'
            })
            .when('/about', {
                templateUrl: 'views/about.html',
                controller: 'AboutController'
            })
            .when('/member/:id', {
                templateUrl: 'views/member.html',
                controller: 'MemberController'
            })
            .when('/practical/1', {
                templateUrl: 'views/practical1.html',
                controller: 'Practical1Controller'
            })
            .when('/practical/2', {
                templateUrl: 'views/practical2.html',
                controller: 'Practical2Controller'
            })
            .when('/practical/3', {
                templateUrl: 'views/practical3.html',
                controller: 'Practical3Controller'
            })
            .when('/practical/4', {
                templateUrl: 'views/practical4.html',
                controller: 'Practical4Controller'
            })
            .when('/practical/7', {
                templateUrl: 'views/practical7.html',
                controller: 'Practical7Controller'
            })
            .when('/practical/7-data', {
                templateUrl: 'views/practical7-data.html',
                controller: 'Practical7DataController'
            })
            .when('/booking-data', {
                templateUrl: 'views/booking-data.html',
                controller: 'BookingDataController'
            })
            .when('/php/hub', {
                templateUrl: 'views/php-hub.html',
                controller: 'PhpHubController'
            })
            .when('/php/9-1', {
                templateUrl: 'views/php9-1.html',
                controller: 'Php91Controller'
            })
            .when('/php/9-2', {
                templateUrl: 'views/php9-2.html',
                controller: 'Php92Controller'
            })
            .when('/php/9-3', {
                templateUrl: 'views/php9-3.html',
                controller: 'Php93Controller'
            })
            .when('/php/10-1', {
                templateUrl: 'views/php10-1.html',
                controller: 'Php101Controller'
            })
            .when('/php/10-2', {
                templateUrl: 'views/php10-2.html',
                controller: 'Php102Controller'
            })
            .when('/php/11-1', {
                templateUrl: 'views/php11-1.html',
                controller: 'Php111Controller'
            })
            .when('/php/11-2', {
                templateUrl: 'views/php11-2.html',
                controller: 'Php112Controller'
            })
            .when('/php/11-3', {
                templateUrl: 'views/php11-3.html',
                controller: 'Php113Controller'
            })
            .when('/php/11-4', {
                templateUrl: 'views/php11-4.html',
                controller: 'Php114Controller'
            })
            .when('/php/12', {
                templateUrl: 'views/php12.html',
                controller: 'Php12Controller'
            })
            .otherwise({
                redirectTo: '/'
            });
    }]);

    // =========================================================================
    // 2. SERVICES & DATA STORES
    // =========================================================================

    // Student Database & CRUD Service
    app.factory('StudentDbService', ['$rootScope', function ($rootScope) {
        var DB_KEY = 'gmca_p7_students_v1';
        var QUERY_KEY = 'gmca_sql_query_log';

        var defaultStudents = [
            { id: 1, enrollment_no: '26GMCA61', student_name: 'Ridham Bambhaniya', email: 'ridham.26gmca61@gmca.ac.in', phone: '9876543210', division: 'A', roll_no: '61', project_title: 'WTP Portal & Institutional Layouts', technology_stack: 'HTML5, CSS3, JavaScript, PHP, MySQL', github_repo: 'https://github.com/Darkvenom24/Group10', semester: 'Semester 1', submission_date: '2026-10-01 10:15:00' },
            { id: 2, enrollment_no: '26GMCA33', student_name: 'Riya Thakkar', email: 'riya.26gmca33@gmca.ac.in', phone: '9876543211', division: 'A', roll_no: '33', project_title: 'Indian Railways Dynamic Booking Portal', technology_stack: 'HTML5, CSS3, JavaScript DOM, Client Storage', github_repo: 'https://github.com/Darkvenom24/Group10', semester: 'Semester 1', submission_date: '2026-10-02 11:30:00' },
            { id: 3, enrollment_no: '26GMCA52', student_name: 'Vaibhav Senjaliya', email: 'vaibhav.26gmca52@gmca.ac.in', phone: '9876543212', division: 'A', roll_no: '52', project_title: 'Interactive Calculator & PHP Hub Engine', technology_stack: 'AngularJS, PHP 8, MySQL PDO, Responsive CSS', github_repo: 'https://github.com/Darkvenom24/Group10', semester: 'Semester 1', submission_date: '2026-10-03 14:45:00' }
        ];

        function getRecords() {
            try {
                var stored = localStorage.getItem(DB_KEY);
                if (stored) {
                    var parsed = JSON.parse(stored);
                    if (Array.isArray(parsed) && parsed.length > 0) return parsed;
                }
            } catch (e) { }
            saveRecords(defaultStudents);
            return defaultStudents;
        }

        function saveRecords(records) {
            localStorage.setItem(DB_KEY, JSON.stringify(records));
        }

        function logQuery(sql) {
            var logs = [];
            try {
                var s = localStorage.getItem(QUERY_KEY);
                if (s) logs = JSON.parse(s);
            } catch (e) { }
            var entry = {
                timestamp: new Date().toLocaleTimeString(),
                query: sql
            };
            logs.unshift(entry);
            if (logs.length > 50) logs = logs.slice(0, 50);
            localStorage.setItem(QUERY_KEY, JSON.stringify(logs));
            $rootScope.$broadcast('sqlQueryLogged', entry);
        }

        return {
            getAll: function () {
                logQuery('SELECT * FROM practical7_students ORDER BY id DESC;');
                return getRecords();
            },
            getById: function (id) {
                var list = getRecords();
                id = parseInt(id, 10);
                for (var i = 0; i < list.length; i++) {
                    if (list[i].id === id) {
                        logQuery('SELECT * FROM practical7_students WHERE id = ' + id + ' LIMIT 1;');
                        return list[i];
                    }
                }
                return null;
            },
            getByEnrollment: function (enrollment) {
                if (!enrollment) return null;
                var list = getRecords();
                var norm = enrollment.trim().toUpperCase();
                for (var i = 0; i < list.length; i++) {
                    if (list[i].enrollment_no.toUpperCase() === norm) return list[i];
                }
                return null;
            },
            insert: function (record) {
                var list = getRecords();
                var nextId = 1;
                list.forEach(function (r) { if (r.id >= nextId) nextId = r.id + 1; });
                record.id = nextId;
                record.submission_date = new Date().toISOString().replace('T', ' ').substring(0, 19);
                list.unshift(record);
                saveRecords(list);
                logQuery("INSERT INTO practical7_students (enrollment_no, student_name, email, division, roll_no, project_title, technology_stack, github_repo, semester) VALUES ('" + record.enrollment_no + "', '" + record.student_name + "', '" + record.email + "', '" + record.division + "', '" + record.roll_no + "', '" + record.project_title + "', '" + record.technology_stack + "', '" + record.github_repo + "', '" + record.semester + "');");
                $rootScope.$broadcast('studentRecordsChanged');
                return record;
            },
            update: function (id, updated) {
                var list = getRecords();
                id = parseInt(id, 10);
                var found = false;
                for (var i = 0; i < list.length; i++) {
                    if (list[i].id === id) {
                        updated.id = id;
                        if (!updated.submission_date) updated.submission_date = list[i].submission_date;
                        list[i] = updated;
                        found = true;
                        break;
                    }
                }
                if (found) {
                    saveRecords(list);
                    logQuery("UPDATE practical7_students SET student_name='" + updated.student_name + "', project_title='" + updated.project_title + "', technology_stack='" + updated.technology_stack + "' WHERE id=" + id + ";");
                    $rootScope.$broadcast('studentRecordsChanged');
                }
                return found;
            },
            delete: function (id) {
                var list = getRecords();
                id = parseInt(id, 10);
                var filtered = list.filter(function (r) { return r.id !== id; });
                saveRecords(filtered);
                logQuery("DELETE FROM practical7_students WHERE id = " + id + ";");
                $rootScope.$broadcast('studentRecordsChanged');
                return true;
            },
            getQueryLogs: function () {
                try {
                    var s = localStorage.getItem(QUERY_KEY);
                    if (s) return JSON.parse(s);
                } catch (e) { }
                return [
                    { timestamp: new Date().toLocaleTimeString(), query: "SELECT * FROM practical7_students ORDER BY id DESC;" }
                ];
            }
        };
    }]);

    // Authentication Service
    app.factory('AuthService', ['$rootScope', function ($rootScope) {
        var USERS_KEY = 'gmca_users_v1';
        var SESSION_KEY = 'gmca_current_session';

        var defaultUsers = [
            { id: 1, username: 'ridham61', full_name: 'Ridham Bambhaniya', email: 'ridham@gmca.ac.in', enrollment_no: '26GMCA61', password: 'password123', role: 'Team Lead', avatar: 'ridham.jpeg' },
            { id: 2, username: 'riya33', full_name: 'Riya Thakkar', email: 'riya@gmca.ac.in', enrollment_no: '26GMCA33', password: 'password123', role: 'Student Member', avatar: 'riya.jpeg' },
            { id: 3, username: 'vaibhav52', full_name: 'Vaibhav Senjaliya', email: 'vaibhav@gmca.ac.in', enrollment_no: '26GMCA52', password: 'password123', role: 'Student Member', avatar: 'vaibhav.jpeg' }
        ];

        function getUsers() {
            try {
                var s = localStorage.getItem(USERS_KEY);
                if (s) {
                    var p = JSON.parse(s);
                    if (Array.isArray(p) && p.length > 0) return p;
                }
            } catch (e) { }
            localStorage.setItem(USERS_KEY, JSON.stringify(defaultUsers));
            return defaultUsers;
        }

        return {
            getCurrentUser: function () {
                try {
                    var s = sessionStorage.getItem(SESSION_KEY) || localStorage.getItem(SESSION_KEY);
                    if (s) return JSON.parse(s);
                } catch (e) { }
                // Default active session as Vaibhav (26GMCA52) for smooth immediate testing
                var defaultSession = defaultUsers[2];
                sessionStorage.setItem(SESSION_KEY, JSON.stringify(defaultSession));
                return defaultSession;
            },
            login: function (usernameOrEnroll, password) {
                var users = getUsers();
                var norm = usernameOrEnroll.trim().toLowerCase();
                for (var i = 0; i < users.length; i++) {
                    var u = users[i];
                    if ((u.username.toLowerCase() === norm || u.enrollment_no.toLowerCase() === norm || u.email.toLowerCase() === norm) && u.password === password) {
                        sessionStorage.setItem(SESSION_KEY, JSON.stringify(u));
                        localStorage.setItem(SESSION_KEY, JSON.stringify(u));
                        $rootScope.$broadcast('authChanged', u);
                        return { success: true, user: u };
                    }
                }
                return { success: false, message: 'Invalid credentials. Try vaibhav52 / password123' };
            },
            register: function (userObj) {
                var users = getUsers();
                var nextId = 1;
                users.forEach(function (u) { if (u.id >= nextId) nextId = u.id + 1; });
                userObj.id = nextId;
                if (!userObj.avatar) userObj.avatar = 'temp.jpeg';
                users.push(userObj);
                localStorage.setItem(USERS_KEY, JSON.stringify(users));
                sessionStorage.setItem(SESSION_KEY, JSON.stringify(userObj));
                localStorage.setItem(SESSION_KEY, JSON.stringify(userObj));
                $rootScope.$broadcast('authChanged', userObj);
                return { success: true, user: userObj };
            },
            logout: function () {
                sessionStorage.removeItem(SESSION_KEY);
                localStorage.removeItem(SESSION_KEY);
                $rootScope.$broadcast('authChanged', null);
            }
        };
    }]);

    // Railway Storage Service
    app.factory('RailwayService', function () {
        var BOOKINGS_KEY = 'railway_bookings_v1';
        return {
            getAll: function () {
                try {
                    var s = localStorage.getItem(BOOKINGS_KEY);
                    if (s) return JSON.parse(s);
                } catch (e) { }
                return [
                    { id: 101, pnr: 'PNR748291', name: 'Ridham Bambhaniya', train: '12957 - Ahmedabad Swarna Jayanti Rajdhani', travelClass: '3A', passengers: 2, quota: 'GN', date: '2026-10-15', totalFare: 2400 },
                    { id: 102, pnr: 'PNR930182', name: 'Vaibhav Senjaliya', train: '12009 - Mumbai Central Vande Bharat', travelClass: '2A', passengers: 1, quota: 'GN', date: '2026-10-18', totalFare: 1800 }
                ];
            },
            save: function (booking) {
                var list = this.getAll();
                booking.id = Date.now();
                booking.pnr = 'PNR' + Math.floor(100000 + Math.random() * 900000);
                list.unshift(booking);
                localStorage.setItem(BOOKINGS_KEY, JSON.stringify(list));
                return booking;
            },
            delete: function (id) {
                var list = this.getAll().filter(function (b) { return b.id !== id; });
                localStorage.setItem(BOOKINGS_KEY, JSON.stringify(list));
            }
        };
    });

    // =========================================================================
    // 3. CONTROLLERS
    // =========================================================================

    // Navigation & Global Shell Controller
    app.controller('NavController', ['$scope', '$location', '$rootScope', 'AuthService', function ($scope, $location, $rootScope, AuthService) {
        $scope.mobileMenuOpen = false;
        $scope.currentUser = AuthService.getCurrentUser();
        $scope.activeDropdown = null;

        $scope.toggleDropdown = function (name, $event) {
            if ($event) {
                $event.preventDefault();
                $event.stopPropagation();
            }
            $scope.activeDropdown = ($scope.activeDropdown === name) ? null : name;
        };

        $scope.toggleMobileMenu = function () {
            $scope.mobileMenuOpen = !$scope.mobileMenuOpen;
        };

        $scope.closeMenu = function () {
            $scope.mobileMenuOpen = false;
            $scope.activeDropdown = null;
        };

        // Close clicked dropdown when clicking anywhere outside
        angular.element(document).on('click', function () {
            if ($scope.activeDropdown) {
                $scope.$applyAsync(function () {
                    $scope.activeDropdown = null;
                });
            }
        });

        $scope.isActive = function (route) {
            var currentPath = $location.path();
            if (route === '/' && currentPath === '/') return true;
            if (route !== '/' && currentPath.indexOf(route) === 0) return true;
            return false;
        };

        $rootScope.$on('$routeChangeSuccess', function () {
            $scope.activeDropdown = null;
            $scope.mobileMenuOpen = false;
        });

        $rootScope.$on('authChanged', function (event, user) {
            $scope.currentUser = user;
        });
    }]);

    // Home Page Controller
    app.controller('HomeController', ['$scope', '$location', 'AuthService', 'StudentDbService', function ($scope, $location, AuthService, StudentDbService) {
        // Visit counter increment
        var visits = parseInt(localStorage.getItem('gmca_visits') || '0', 10) + 1;
        localStorage.setItem('gmca_visits', visits);
        $scope.visitCount = visits;

        $scope.currentUser = AuthService.getCurrentUser();
        $scope.recentRecords = StudentDbService.getAll().slice(0, 5);
        $scope.userRecord = $scope.currentUser ? StudentDbService.getByEnrollment($scope.currentUser.enrollment_no) : null;

        $scope.loginModel = { usernameOrEnroll: '26GMCA52', password: 'password123' };
        $scope.regModel = { role: 'Student' };
        $scope.activeTab = 'login';
        $scope.loginError = '';
        $scope.regSuccess = '';

        $scope.quickFill = function (enroll, pwd) {
            $scope.loginModel.usernameOrEnroll = enroll;
            $scope.loginModel.password = pwd;
        };

        $scope.submitLogin = function () {
            $scope.loginError = '';
            var res = AuthService.login($scope.loginModel.usernameOrEnroll, $scope.loginModel.password);
            if (res.success) {
                $scope.currentUser = res.user;
                $scope.userRecord = StudentDbService.getByEnrollment(res.user.enrollment_no);
            } else {
                $scope.loginError = res.message;
            }
        };

        $scope.submitRegister = function () {
            $scope.regModel.avatar = 'temp.jpeg';
            var res = AuthService.register($scope.regModel);
            if (res.success) {
                $scope.currentUser = res.user;
                $scope.userRecord = null;
                $scope.activeTab = 'login';
            }
        };

        $scope.logout = function () {
            AuthService.logout();
            $scope.currentUser = null;
            $scope.userRecord = null;
        };

        $scope.$on('studentRecordsChanged', function () {
            $scope.recentRecords = StudentDbService.getAll().slice(0, 5);
            if ($scope.currentUser) {
                $scope.userRecord = StudentDbService.getByEnrollment($scope.currentUser.enrollment_no);
            }
        });
    }]);

    // About Us Controller
    app.controller('AboutController', ['$scope', function ($scope) {
        $scope.members = [
            { id: 1, name: 'Ridham Bambhaniya', enroll: '26GMCA61', role: 'Team Lead / Layout Architect', avatar: 'ridham.jpeg', email: 'ridham.26gmca61@gmca.ac.in', tasks: 'Practical 1 (HTML table structure), Practical 2 (Tricolor styling), Core CSS layout design.' },
            { id: 2, name: 'Riya Thakkar', enroll: '26GMCA33', role: 'Frontend & Logic Developer', avatar: 'riya.jpeg', email: 'riya.26gmca33@gmca.ac.in', tasks: 'Practical 3 (Railway booking portal, fare calculator, validation), localStorage persistence.' },
            { id: 3, name: 'Vaibhav Senjaliya', enroll: '26GMCA52', role: 'Full Stack & System Integrator', avatar: 'vaibhav.jpeg', email: 'vaibhav.26gmca52@gmca.ac.in', tasks: 'Practical 4 (Calculator), Practical 7 (Student Form & CRUD), PHP Practicals 9-12 runner engine & AngularJS SPA.' }
        ];
    }]);

    // Member Profile Controller
    app.controller('MemberController', ['$scope', '$routeParams', function ($scope, $routeParams) {
        var id = parseInt($routeParams.id, 10);
        var memberData = {
            1: { name: 'Ridham Bambhaniya', enroll: '26GMCA61', avatar: 'ridham.jpeg', role: 'Team Lead', email: 'ridham.26gmca61@gmca.ac.in', phone: '+91 98765 43210', bio: 'MCA Semester 1 candidate at GMCA specializing in modern web design, responsive layouts, and Indian tricolor UI aesthetics.' },
            2: { name: 'Riya Thakkar', enroll: '26GMCA33', avatar: 'riya.jpeg', role: 'Developer', email: 'riya.26gmca33@gmca.ac.in', phone: '+91 98765 43211', bio: 'MCA Semester 1 candidate at GMCA passionate about frontend interaction, form validations, and user-centric web applications.' },
            3: { name: 'Vaibhav Senjaliya', enroll: '26GMCA52', avatar: 'vaibhav.jpeg', role: 'Developer & Integrator', email: 'vaibhav.26gmca52@gmca.ac.in', phone: '+91 98765 43212', bio: 'MCA Semester 1 candidate at GMCA focused on full stack development, relational databases, PHP, and AngularJS SPA engineering.' }
        };
        $scope.member = memberData[id] || memberData[1];
    }]);

    // Practical 1 Controller
    app.controller('Practical1Controller', ['$scope', function ($scope) {
        $scope.info = 'Demonstrates pure HTML structure, tables, formatting tags, hyperlinks, and image embedding.';
    }]);

    // Practical 2 Controller
    app.controller('Practical2Controller', ['$scope', function ($scope) {
        $scope.info = 'Demonstrates external CSS styling, Indian Flag tricolor palette, card layouts, and hover effects.';
    }]);

    // Practical 3 Controller (Indian Railways Booking Portal)
    app.controller('Practical3Controller', ['$scope', 'RailwayService', function ($scope, RailwayService) {
        $scope.trains = [
            { code: '12957', name: '12957 - Ahmedabad Swarna Jayanti Rajdhani', baseFare: 800 },
            { code: '12009', name: '12009 - Mumbai Central Vande Bharat Express', baseFare: 950 },
            { code: '22953', name: '22953 - Gujarat SF Express', baseFare: 400 },
            { code: '19038', name: '19038 - Avadh Express', baseFare: 350 }
        ];

        $scope.booking = {
            train: $scope.trains[0].name,
            travelClass: 'SL',
            quota: 'GN',
            passengers: 1,
            date: new Date().toISOString().substring(0, 10),
            name: '',
            phone: ''
        };

        $scope.classMultiplier = { 'SL': 500, '3A': 1200, '2A': 1800, '1A': 3000 };

        $scope.calculateFare = function () {
            var mult = $scope.classMultiplier[$scope.booking.travelClass] || 500;
            var quotaMult = ($scope.booking.quota === 'TQ') ? 1.3 : 1.0;
            var base = 300;
            var perPassenger = (base + mult) * quotaMult;
            return Math.round(perPassenger * ($scope.booking.passengers || 1));
        };

        $scope.submitBooking = function () {
            if (!$scope.booking.name || !$scope.booking.phone) {
                alert('Please enter passenger full name and contact number.');
                return;
            }
            $scope.booking.totalFare = $scope.calculateFare();
            var saved = RailwayService.save(angular.copy($scope.booking));
            $scope.successMessage = 'Booking confirmed! PNR: ' + saved.pnr + ' | Amount: ₹' + saved.totalFare;
            $scope.booking.name = '';
            $scope.booking.phone = '';
        };
    }]);

    // Practical 4 Controller (Calculator)
    app.controller('Practical4Controller', ['$scope', function ($scope) {
        $scope.display = '0';
        $scope.history = [];

        $scope.pressKey = function (val) {
            if ($scope.display === '0' || $scope.display === 'Error') {
                $scope.display = val;
            } else {
                $scope.display += val;
            }
        };

        $scope.clear = function () {
            $scope.display = '0';
        };

        $scope.backspace = function () {
            if ($scope.display.length > 1) {
                $scope.display = $scope.display.slice(0, -1);
            } else {
                $scope.display = '0';
            }
        };

        $scope.evaluate = function () {
            try {
                // Safe arithmetic evaluation
                var sanitized = $scope.display.replace(/[^0-9+\-*/().]/g, '');
                var res = Function('"use strict";return (' + sanitized + ')')();
                $scope.history.unshift($scope.display + ' = ' + res);
                if ($scope.history.length > 5) $scope.history.pop();
                $scope.display = String(res);
            } catch (e) {
                $scope.display = 'Error';
            }
        };
    }]);

    // Practical 7 Controller (Student Form)
    app.controller('Practical7Controller', ['$scope', '$location', 'AuthService', 'StudentDbService', function ($scope, $location, AuthService, StudentDbService) {
        var user = AuthService.getCurrentUser();
        $scope.student = {
            enrollment_no: user ? user.enrollment_no : '',
            student_name: user ? user.full_name : '',
            email: user ? user.email : '',
            phone: '9876543210',
            division: 'A',
            roll_no: user ? user.enrollment_no.slice(-2) : '52',
            project_title: 'WTP College Practicals & Interactive Web Portal',
            technology_stack: 'AngularJS, PHP, MySQL, HTML5, CSS3',
            github_repo: 'https://github.com/Darkvenom24/Group10',
            semester: 'Semester 1'
        };

        $scope.submitForm = function () {
            StudentDbService.insert(angular.copy($scope.student));
            alert('Practical 7 data recorded successfully! Redirecting to Student Records Dashboard...');
            $location.path('/practical/7-data');
        };
    }]);

    // Practical 7 Data Controller (CRUD Dashboard)
    app.controller('Practical7DataController', ['$scope', 'StudentDbService', function ($scope, StudentDbService) {
        $scope.records = StudentDbService.getAll();
        $scope.queryLogs = StudentDbService.getQueryLogs();
        $scope.searchKeyword = '';
        $scope.selectedRecord = null;
        $scope.editRecord = null;
        $scope.isEditModalOpen = false;
        $scope.isViewModalOpen = false;

        $scope.refreshData = function () {
            $scope.records = StudentDbService.getAll();
            $scope.queryLogs = StudentDbService.getQueryLogs();
        };

        $scope.viewDossier = function (r) {
            $scope.selectedRecord = r;
            $scope.isViewModalOpen = true;
        };

        $scope.closeViewModal = function () {
            $scope.isViewModalOpen = false;
            $scope.selectedRecord = null;
        };

        $scope.openEditModal = function (r) {
            $scope.editRecord = angular.copy(r);
            $scope.isEditModalOpen = true;
        };

        $scope.closeEditModal = function () {
            $scope.isEditModalOpen = false;
            $scope.editRecord = null;
        };

        $scope.saveEdit = function () {
            if ($scope.editRecord && $scope.editRecord.id) {
                StudentDbService.update($scope.editRecord.id, $scope.editRecord);
                $scope.closeEditModal();
                $scope.refreshData();
            }
        };

        $scope.deleteRecord = function (id) {
            if (confirm('Are you sure you want to delete student record #' + id + '? (Simulates DELETE FROM practical7_students)')) {
                StudentDbService.delete(id);
                $scope.refreshData();
            }
        };

        $scope.exportCsv = function () {
            var csv = 'ID,Enrollment,Student Name,Email,Division,Roll,Project Title,Tech Stack,Semester\n';
            $scope.records.forEach(function (r) {
                csv += [r.id, r.enrollment_no, '"' + r.student_name + '"', r.email, r.division, r.roll_no, '"' + r.project_title + '"', '"' + r.technology_stack + '"', r.semester].join(',') + '\n';
            });
            var blob = new Blob([csv], { type: 'text/csv' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'practical7_students_data.csv';
            a.click();
        };

        $scope.$on('sqlQueryLogged', function (e, entry) {
            $scope.queryLogs.unshift(entry);
        });
    }]);

    // Booking Data Controller
    app.controller('BookingDataController', ['$scope', 'RailwayService', function ($scope, RailwayService) {
        $scope.bookings = RailwayService.getAll();
        $scope.deleteBooking = function (id) {
            if (confirm('Cancel and delete booking record?')) {
                RailwayService.delete(id);
                $scope.bookings = RailwayService.getAll();
            }
        };
    }]);

    // -------------------------------------------------------------------------
    // PHP PRACTICAL CONTROLLERS (9, 10, 11, 12)
    // -------------------------------------------------------------------------

    // 9.1: Max of 3 Numbers
    app.controller('Php91Controller', ['$scope', function ($scope) {
        $scope.val1 = 45;
        $scope.val2 = 82;
        $scope.val3 = 63;
        $scope.maxResult = 82;
        $scope.algorithm = '';

        $scope.computeMax = function () {
            var v1 = parseFloat($scope.val1) || 0;
            var v2 = parseFloat($scope.val2) || 0;
            var v3 = parseFloat($scope.val3) || 0;
            var maxVal = Math.max(v1, v2, v3);
            $scope.maxResult = maxVal;
            if (v1 >= v2 && v1 >= v3) {
                $scope.algorithm = 'Value 1 (' + v1 + ') is greater than or equal to Value 2 (' + v2 + ') and Value 3 (' + v3 + ').';
            } else if (v2 >= v1 && v2 >= v3) {
                $scope.algorithm = 'Value 2 (' + v2 + ') is greater than or equal to Value 1 (' + v1 + ') and Value 3 (' + v3 + ').';
            } else {
                $scope.algorithm = 'Value 3 (' + v3 + ') is greater than or equal to Value 1 (' + v1 + ') and Value 2 (' + v2 + ').';
            }
        };
        $scope.computeMax();
    }]);

    // 9.2: Print 1 to N Numbers
    app.controller('Php92Controller', ['$scope', function ($scope) {
        $scope.n = 15;
        $scope.numbers = [];
        $scope.stats = { total: 0, evens: 0, odds: 0 };

        $scope.generate = function () {
            var count = parseInt($scope.n, 10) || 1;
            if (count > 200) count = 200;
            var list = [];
            var sum = 0, evens = 0, odds = 0;
            for (var i = 1; i <= count; i++) {
                list.push(i);
                sum += i;
                if (i % 2 === 0) evens++; else odds++;
            }
            $scope.numbers = list;
            $scope.stats = { total: sum, evens: evens, odds: odds };
        };
        $scope.generate();
    }]);

    // 9.3: 3 Pyramid Patterns
    app.controller('Php93Controller', ['$scope', function ($scope) {
        $scope.rows = 5;
        $scope.symbol = '*';
        $scope.patterns = { right: '', centered: '', inverted: '' };

        $scope.buildPatterns = function () {
            var r = parseInt($scope.rows, 10) || 5;
            if (r > 20) r = 20;
            var sym = $scope.symbol || '*';

            // 1. Right Angled
            var right = '';
            for (var i = 1; i <= r; i++) {
                right += (sym + ' ').repeat(i) + '\n';
            }

            // 2. Centered Pyramid
            var centered = '';
            for (var j = 1; j <= r; j++) {
                var spaces = ' '.repeat((r - j) * 2);
                centered += spaces + (sym + ' ').repeat(j) + '\n';
            }

            // 3. Inverted Pyramid
            var inverted = '';
            for (var k = r; k >= 1; k--) {
                var invSpaces = ' '.repeat((r - k) * 2);
                inverted += invSpaces + (sym + ' ').repeat(k) + '\n';
            }

            $scope.patterns = { right: right, centered: centered, inverted: inverted };
        };
        $scope.buildPatterns();
    }]);

    // 10.1: Array Operations
    app.controller('Php101Controller', ['$scope', function ($scope) {
        $scope.input1 = '12, 45, 7, 89, 23';
        $scope.input2 = '34, 11, 56, 90';
        $scope.result = {};

        $scope.processArray = function () {
            var a1 = $scope.input1.split(',').map(function (x) { return parseFloat(x.trim()); }).filter(function (x) { return !isNaN(x); });
            var a2 = $scope.input2.split(',').map(function (x) { return parseFloat(x.trim()); }).filter(function (x) { return !isNaN(x); });

            var reversed = angular.copy(a1).reverse();
            var merged = a1.concat(a2).sort(function (a, b) { return a - b; });
            var sum = a1.reduce(function (acc, val) { return acc + val; }, 0);

            $scope.result = {
                original: a1,
                reversed: reversed,
                mergedSorted: merged,
                sum: sum
            };
        };
        $scope.processArray();
    }]);

    // 10.2: String Name & Size Passed as Argument
    app.controller('Php102Controller', ['$scope', function ($scope) {
        $scope.studentName = 'Vaibhav Senjaliya';
        $scope.sampleString = 'Government MCA College, Maninagar';
        $scope.strLength = 0;
        $scope.wordCount = 0;

        $scope.calculateString = function () {
            $scope.strLength = ($scope.sampleString || '').length;
            $scope.wordCount = ($scope.sampleString || '').trim().split(/\s+/).filter(Boolean).length;
        };
        $scope.calculateString();
    }]);

    // 11.1: Max & Min Numbers
    app.controller('Php111Controller', ['$scope', function ($scope) {
        $scope.n1 = 34;
        $scope.n2 = 89;
        $scope.n3 = 12;
        $scope.max = 89;
        $scope.min = 12;

        $scope.evaluate = function () {
            var v1 = parseFloat($scope.n1) || 0;
            var v2 = parseFloat($scope.n2) || 0;
            var v3 = parseFloat($scope.n3) || 0;
            $scope.max = Math.max(v1, v2, v3);
            $scope.min = Math.min(v1, v2, v3);
        };
        $scope.evaluate();
    }]);

    // 11.2: Date/Time & Greeting Message
    app.controller('Php112Controller', ['$scope', '$interval', function ($scope, $interval) {
        $scope.simulatedHour = new Date().getHours();
        $scope.isLive = true;

        function updateClock() {
            var now = new Date();
            $scope.currentTimeStr = now.toLocaleTimeString();
            $scope.currentDateStr = now.toLocaleDateString('en-IN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            if ($scope.isLive) {
                $scope.simulatedHour = now.getHours();
            }
            $scope.updateGreeting();
        }

        $scope.updateGreeting = function () {
            var h = parseInt($scope.simulatedHour, 10);
            if (h >= 5 && h < 12) {
                $scope.greeting = 'Good Morning! 🌅';
                $scope.greetingColor = '#D97706';
            } else if (h >= 12 && h < 17) {
                $scope.greeting = 'Good Afternoon! ☀️';
                $scope.greetingColor = '#2563EB';
            } else if (h >= 17 && h < 22) {
                $scope.greeting = 'Good Evening! 🌇';
                $scope.greetingColor = '#7C3AED';
            } else {
                $scope.greeting = 'Good Night! 🌙';
                $scope.greetingColor = '#475569';
            }
        };

        updateClock();
        var timer = $interval(function () {
            if ($scope.isLive) updateClock();
        }, 1000);

        $scope.$on('$destroy', function () {
            $interval.cancel(timer);
        });
    }]);

    // 11.3: User Profile & Form
    app.controller('Php113Controller', ['$scope', function ($scope) {
        $scope.profile = {
            fullName: 'Vaibhav Senjaliya',
            email: 'vaibhav@gmca.ac.in',
            course: 'Master of Computer Applications (MCA)',
            bio: 'Passionate about web architectures, full stack software development, and database engineering.'
        };
        $scope.submittedProfile = angular.copy($scope.profile);

        $scope.submitProfile = function () {
            $scope.submittedProfile = angular.copy($scope.profile);
            $scope.showSuccess = true;
        };
    }]);

    // 11.4: String Functions (5 Tasks)
    app.controller('Php114Controller', ['$scope', function ($scope) {
        $scope.nameInput = 'Vaibhav Senjaliya';
        $scope.strArg = 'Web Technology Practicals 2026';
        $scope.concatA = 'Government MCA ';
        $scope.concatB = 'College, Maninagar';
        $scope.caseInput = 'welcome to gmca web portal';
        $scope.haystack = 'The quick brown fox jumps over the lazy dog';
        $scope.needle = 'fox';

        $scope.getName = function () { return $scope.nameInput; };
        $scope.getSize = function () { return ($scope.strArg || '').length; };
        $scope.getConcat = function () { return ($scope.concatA || '') + ($scope.concatB || ''); };
        $scope.getUpper = function () { return ($scope.caseInput || '').toUpperCase(); };
        $scope.getLower = function () { return ($scope.caseInput || '').toLowerCase(); };
        $scope.getWords = function () {
            return ($scope.caseInput || '').replace(/\b\w/g, function (l) { return l.toUpperCase(); });
        };
        $scope.getFind = function () {
            var pos = ($scope.haystack || '').indexOf($scope.needle || '');
            return pos !== -1 ? 'Found at index ' + pos : 'Substring not found';
        };
    }]);

    // 12: Database Connection & CRUD Guide
    app.controller('Php12Controller', ['$scope', function ($scope) {
        $scope.activeCodeTab = 'pdo';
    }]);

    // PHP Interactive Hub
    app.controller('PhpHubController', ['$scope', function ($scope) {
        $scope.activeTab = '9-1';
    }]);

})();
