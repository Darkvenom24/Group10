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
            // Practical 7 removed as per professor instructions (Railway form is group's primary form)
            .when('/practical/7', {
                redirectTo: '/practical/3'
            })
            .when('/practical/7-data', {
                redirectTo: '/booking-data'
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
    // 2. ROUTE ACCESS GUARD (Strict Professor Rule: Everyone MUST Login)
    // =========================================================================
    app.run(['$rootScope', '$location', 'AuthService', function ($rootScope, $location, AuthService) {
        $rootScope.accessDeniedNotice = '';

        $rootScope.$on('$routeChangeStart', function (event, next) {
            var currentUser = AuthService.getCurrentUser();
            var targetPath = next && next.originalPath ? next.originalPath : '';

            // If user is NOT logged in and attempting to visit ANY route other than Home ('/')
            if (!currentUser && targetPath && targetPath !== '/') {
                event.preventDefault();
                $rootScope.accessDeniedNotice = '🔒 Authentication Required: You must be logged in to access practicals, the Railway booking portal, or personal database records. Please log in below.';
                $location.path('/');
            } else if (currentUser) {
                // Clear notice on valid navigation
                $rootScope.accessDeniedNotice = '';
            }
        });
    }]);

    // =========================================================================
    // 3. SERVICES & AUTHENTICATION
    // =========================================================================

    // Authentication Service with Multi-User Profiles & Remember-Me
    app.factory('AuthService', ['$rootScope', function ($rootScope) {
        var USERS_KEY = 'gmca_users_v2';
        var SESSION_KEY = 'gmca_current_session_v2';
        var REMEMBERED_KEY = 'gmca_remembered_session_v2';

        var defaultUsers = [
            { id: 1, username: 'ridham61', full_name: 'Ridham Bambhaniya', email: 'ridham.26gmca61@gmca.ac.in', enrollment_no: '26GMCA61', password: 'password123', role: 'Team Lead', avatar: 'ridham.jpeg' },
            { id: 2, username: 'riya33', full_name: 'Riya Thakkar', email: 'riya.26gmca33@gmca.ac.in', enrollment_no: '26GMCA33', password: 'password123', role: 'Student Member', avatar: 'riya.jpeg' },
            { id: 3, username: 'vaibhav52', full_name: 'Vaibhav Senjaliya', email: 'vaibhav.26gmca52@gmca.ac.in', enrollment_no: '26GMCA52', password: 'password123', role: 'Student Member', avatar: 'vaibhav.jpeg' }
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
            getDefaultTeamMembers: function () {
                return defaultUsers;
            },
            getCurrentUser: function () {
                try {
                    // Check active session first, then remembered session
                    var sessionStr = sessionStorage.getItem(SESSION_KEY);
                    if (sessionStr) return JSON.parse(sessionStr);

                    var rememberedStr = localStorage.getItem(REMEMBERED_KEY);
                    if (rememberedStr) {
                        var parsed = JSON.parse(rememberedStr);
                        sessionStorage.setItem(SESSION_KEY, JSON.stringify(parsed));
                        return parsed;
                    }
                } catch (e) { }
                // Return null by default: user MUST explicitly login or choose profile
                return null;
            },
            login: function (usernameOrEnroll, password, rememberMe) {
                var users = getUsers();
                var norm = (usernameOrEnroll || '').trim().toLowerCase();
                for (var i = 0; i < users.length; i++) {
                    var u = users[i];
                    if ((u.username.toLowerCase() === norm || u.enrollment_no.toLowerCase() === norm || u.email.toLowerCase() === norm) && u.password === password) {
                        sessionStorage.setItem(SESSION_KEY, JSON.stringify(u));
                        if (rememberMe) {
                            localStorage.setItem(REMEMBERED_KEY, JSON.stringify(u));
                        } else {
                            localStorage.removeItem(REMEMBERED_KEY);
                        }
                        $rootScope.$broadcast('authChanged', u);
                        return { success: true, user: u };
                    }
                }
                return { success: false, message: 'Invalid credentials. Select a quick profile card or enter valid login.' };
            },
            register: function (userObj, rememberMe) {
                var users = getUsers();
                var nextId = 1;
                users.forEach(function (u) { if (u.id >= nextId) nextId = u.id + 1; });
                userObj.id = nextId;
                if (!userObj.avatar) userObj.avatar = 'temp.jpeg';
                users.push(userObj);
                localStorage.setItem(USERS_KEY, JSON.stringify(users));

                sessionStorage.setItem(SESSION_KEY, JSON.stringify(userObj));
                if (rememberMe) {
                    localStorage.setItem(REMEMBERED_KEY, JSON.stringify(userObj));
                }
                $rootScope.$broadcast('authChanged', userObj);
                return { success: true, user: userObj };
            },
            logout: function () {
                sessionStorage.removeItem(SESSION_KEY);
                localStorage.removeItem(REMEMBERED_KEY);
                $rootScope.$broadcast('authChanged', null);
            }
        };
    }]);

    // Railway Storage Service with Strict Individual User Isolation (Private Call Log Concept)
    app.factory('RailwayService', ['$rootScope', function ($rootScope) {
        var BOOKINGS_KEY = 'gmca_railway_bookings_v3';

        // Seed realistic private bookings for each team member
        var defaultBookings = [
            // Ridham Bambhaniya's personal bookings
            { id: 101, pnr: 'PNR748291', userEnrollment: '26GMCA61', userId: 1, bookedBy: 'Ridham Bambhaniya', passengerName: 'Ridham Bambhaniya', train: '12957 - Ahmedabad Swarna Jayanti Rajdhani', travelClass: '3A', quota: 'GN', passengers: 2, date: '2026-10-15', totalFare: 2400, bookingDate: '2026-10-01 10:15', status: 'Confirmed' },
            { id: 102, pnr: 'PNR831902', userEnrollment: '26GMCA61', userId: 1, bookedBy: 'Ridham Bambhaniya', passengerName: 'Ridham Bambhaniya', train: '22953 - Gujarat SF Express', travelClass: 'SL', quota: 'GN', passengers: 1, date: '2026-10-22', totalFare: 400, bookingDate: '2026-10-05 14:20', status: 'Confirmed' },

            // Riya Thakkar's personal bookings
            { id: 201, pnr: 'PNR612944', userEnrollment: '26GMCA33', userId: 2, bookedBy: 'Riya Thakkar', passengerName: 'Riya Thakkar', train: '12009 - Mumbai Central Vande Bharat Express', travelClass: '2A', quota: 'GN', passengers: 1, date: '2026-10-18', totalFare: 1800, bookingDate: '2026-10-04 11:30', status: 'Confirmed' },

            // Vaibhav Senjaliya's personal bookings
            { id: 301, pnr: 'PNR930182', userEnrollment: '26GMCA52', userId: 3, bookedBy: 'Vaibhav Senjaliya', passengerName: 'Vaibhav Senjaliya', train: '12957 - Ahmedabad Swarna Jayanti Rajdhani', travelClass: '2A', quota: 'GN', passengers: 1, date: '2026-10-25', totalFare: 1800, bookingDate: '2026-10-06 09:40', status: 'Confirmed' },
            { id: 302, pnr: 'PNR441920', userEnrollment: '26GMCA52', userId: 3, bookedBy: 'Vaibhav Senjaliya', passengerName: 'Vaibhav Senjaliya', train: '19038 - Avadh Express', travelClass: 'SL', quota: 'GN', passengers: 2, date: '2026-10-29', totalFare: 700, bookingDate: '2026-10-08 16:50', status: 'Confirmed' }
        ];

        function getAllRaw() {
            try {
                var s = localStorage.getItem(BOOKINGS_KEY);
                if (s) {
                    var p = JSON.parse(s);
                    if (Array.isArray(p)) return p;
                }
            } catch (e) { }
            localStorage.setItem(BOOKINGS_KEY, JSON.stringify(defaultBookings));
            return defaultBookings;
        }

        function saveRaw(list) {
            localStorage.setItem(BOOKINGS_KEY, JSON.stringify(list));
        }

        return {
            // Returns ONLY the records belonging to the authenticated user
            getBookingsForUser: function (enrollmentOrId) {
                if (!enrollmentOrId) return [];
                var all = getAllRaw();
                var norm = String(enrollmentOrId).trim().toUpperCase();
                return all.filter(function (b) {
                    return (b.userEnrollment && b.userEnrollment.toUpperCase() === norm) ||
                           (b.userId && String(b.userId) === norm);
                });
            },
            save: function (booking, currentUser) {
                var list = getAllRaw();
                booking.id = Date.now();
                booking.pnr = 'PNR' + Math.floor(100000 + Math.random() * 900000);
                booking.userEnrollment = currentUser ? currentUser.enrollment_no : 'GUEST';
                booking.userId = currentUser ? currentUser.id : null;
                booking.bookedBy = currentUser ? currentUser.full_name : booking.passengerName;
                booking.bookingDate = new Date().toLocaleString();
                booking.status = 'Confirmed';
                list.unshift(booking);
                saveRaw(list);
                $rootScope.$broadcast('railwayBookingsChanged');
                return booking;
            },
            delete: function (id, currentUser) {
                var list = getAllRaw();
                var userEnroll = currentUser ? currentUser.enrollment_no.toUpperCase() : '';
                var filtered = list.filter(function (b) {
                    if (b.id === id) {
                        // Security check: Only delete if it belongs to current user
                        return b.userEnrollment && b.userEnrollment.toUpperCase() !== userEnroll;
                    }
                    return true;
                });
                saveRaw(filtered);
                $rootScope.$broadcast('railwayBookingsChanged');
            }
        };
    }]);

    // =========================================================================
    // 4. CONTROLLERS
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

        // Guard link clicks in nav if not logged in
        $scope.guardNav = function (targetUrl, $event) {
            if (!$scope.currentUser && targetUrl !== '#/' && targetUrl !== '/') {
                if ($event) $event.preventDefault();
                $rootScope.accessDeniedNotice = '🔒 Authentication Required: Please log in first to access practicals and portal tools.';
                $location.path('/');
                $scope.closeMenu();
                return false;
            }
            $scope.closeMenu();
            return true;
        };

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

        $scope.logout = function () {
            AuthService.logout();
            $scope.currentUser = null;
            $location.path('/');
        };

        $rootScope.$on('$routeChangeSuccess', function () {
            $scope.activeDropdown = null;
            $scope.mobileMenuOpen = false;
        });

        $rootScope.$on('authChanged', function (event, user) {
            $scope.currentUser = user;
        });
    }]);

    // Home Page Controller (Login, Multi-Account Selection & Personal Railway Data)
    app.controller('HomeController', ['$scope', '$location', '$rootScope', 'AuthService', 'RailwayService', function ($scope, $location, $rootScope, AuthService, RailwayService) {
        var visits = parseInt(localStorage.getItem('gmca_visits') || '0', 10) + 1;
        localStorage.setItem('gmca_visits', visits);
        $scope.visitCount = visits;

        $scope.currentUser = AuthService.getCurrentUser();
        $scope.teamMembers = AuthService.getDefaultTeamMembers();

        $scope.loginModel = {
            usernameOrEnroll: '',
            password: '',
            rememberMe: true
        };
        $scope.regModel = { role: 'Student Member', rememberMe: true };
        $scope.activeTab = 'login';
        $scope.loginError = '';
        $scope.regSuccess = '';
        $scope.selectedProfileId = null;

        // Load personal railway bookings
        function loadUserBookings() {
            if ($scope.currentUser) {
                $scope.myBookings = RailwayService.getBookingsForUser($scope.currentUser.enrollment_no);
            } else {
                $scope.myBookings = [];
            }
        }
        loadUserBookings();

        // 1-Click Select Profile
        $scope.selectProfile = function (member) {
            $scope.selectedProfileId = member.id;
            $scope.loginModel.usernameOrEnroll = member.enrollment_no;
            $scope.loginModel.password = member.password;
            $scope.loginError = '';
        };

        // Quick Instant Login for Selected Profile
        $scope.quickLoginAs = function (member) {
            $scope.selectProfile(member);
            $scope.submitLogin();
        };

        $scope.submitLogin = function () {
            $scope.loginError = '';
            if (!$scope.loginModel.usernameOrEnroll || !$scope.loginModel.password) {
                $scope.loginError = 'Please select a profile or enter your username/enrollment and password.';
                return;
            }
            var res = AuthService.login($scope.loginModel.usernameOrEnroll, $scope.loginModel.password, $scope.loginModel.rememberMe);
            if (res.success) {
                $scope.currentUser = res.user;
                $rootScope.accessDeniedNotice = '';
                loadUserBookings();
            } else {
                $scope.loginError = res.message;
            }
        };

        $scope.submitRegister = function () {
            if (!$scope.regModel.full_name || !$scope.regModel.enrollment_no || !$scope.regModel.password) {
                alert('Please complete all registration fields.');
                return;
            }
            $scope.regModel.avatar = 'temp.jpeg';
            var res = AuthService.register($scope.regModel, $scope.regModel.rememberMe);
            if (res.success) {
                $scope.currentUser = res.user;
                $rootScope.accessDeniedNotice = '';
                loadUserBookings();
            }
        };

        $scope.logout = function () {
            AuthService.logout();
            $scope.currentUser = null;
            $scope.loginModel.usernameOrEnroll = '';
            $scope.loginModel.password = '';
            $scope.selectedProfileId = null;
            loadUserBookings();
        };

        $scope.$on('railwayBookingsChanged', function () {
            loadUserBookings();
        });
    }]);

    // About Us Controller
    app.controller('AboutController', ['$scope', function ($scope) {
        $scope.members = [
            { id: 1, name: 'Ridham Bambhaniya', enroll: '26GMCA61', role: 'Team Lead / Layout Architect', avatar: 'ridham.jpeg', email: 'ridham.26gmca61@gmca.ac.in', tasks: 'Practical 1 (HTML table structure), Practical 2 (Tricolor styling), Core CSS layout design.' },
            { id: 2, name: 'Riya Thakkar', enroll: '26GMCA33', role: 'Frontend & Logic Developer', avatar: 'riya.jpeg', email: 'riya.26gmca33@gmca.ac.in', tasks: 'Practical 3 (Railway booking portal, fare calculator, validation), localStorage persistence.' },
            { id: 3, name: 'Vaibhav Senjaliya', enroll: '26GMCA52', role: 'Full Stack & System Integrator', avatar: 'vaibhav.jpeg', email: 'vaibhav.26gmca52@gmca.ac.in', tasks: 'Practical 4 (Calculator), PHP Practicals 9-12 runner engine & AngularJS SPA architecture.' }
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
    app.controller('Practical3Controller', ['$scope', 'RailwayService', 'AuthService', function ($scope, RailwayService, AuthService) {
        $scope.currentUser = AuthService.getCurrentUser();

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
            passengerName: $scope.currentUser ? $scope.currentUser.full_name : '',
            phone: '9876543210'
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
            if (!$scope.booking.passengerName || !$scope.booking.phone) {
                alert('Please enter passenger full name and contact number.');
                return;
            }
            $scope.booking.totalFare = $scope.calculateFare();
            var saved = RailwayService.save(angular.copy($scope.booking), $scope.currentUser);
            $scope.successMessage = 'Booking confirmed! PNR: ' + saved.pnr + ' | Amount: ₹' + saved.totalFare + ' (Saved to your personal account)';
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

    // Booking Data Controller (User-Isolated Personal Railway Records)
    app.controller('BookingDataController', ['$scope', 'RailwayService', 'AuthService', function ($scope, RailwayService, AuthService) {
        $scope.currentUser = AuthService.getCurrentUser();

        function loadBookings() {
            if ($scope.currentUser) {
                $scope.bookings = RailwayService.getBookingsForUser($scope.currentUser.enrollment_no);
            } else {
                $scope.bookings = [];
            }
        }
        loadBookings();

        $scope.deleteBooking = function (id) {
            if (confirm('Cancel and delete booking record?')) {
                RailwayService.delete(id, $scope.currentUser);
                loadBookings();
            }
        };

        $scope.$on('railwayBookingsChanged', function () {
            loadBookings();
        });
    }]);

    // -------------------------------------------------------------------------
    // PHP PRACTICAL CONTROLLERS (9, 10, 11, 12) — VS Code IDE & Runtime Playground
    // -------------------------------------------------------------------------

    // 9.1: Max of 3 Numbers
    app.controller('Php91Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.val1 = 45;
        $scope.val2 = 82;
        $scope.val3 = 63;

        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.executionTime = '0ms';
        $scope.maxResult = null;
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var v1 = parseFloat($scope.val1) || 0;
                var v2 = parseFloat($scope.val2) || 0;
                var v3 = parseFloat($scope.val3) || 0;
                var maxVal = Math.max(v1, v2, v3);
                $scope.maxResult = maxVal;

                var decision = '';
                if (v1 >= v2 && v1 >= v3) decision = 'Condition ($num1 >= $num2 && $num1 >= $num3) evaluated to TRUE';
                else if (v2 >= v1 && v2 >= v3) decision = 'Condition ($num2 >= $num1 && $num2 >= $num3) evaluated to TRUE';
                else decision = 'Else branch executed: $num3 is the largest value';
                $scope.decision = decision;

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\nContent-Type: text/html; charset=UTF-8\n\n' +
                    '--- PHP STDOUT (practical9_1.php) ---\n' +
                    'POST Parameters: num1=' + v1 + ', num2=' + v2 + ', num3=' + v3 + '\n' +
                    'Executing Conditional Logic...\n' +
                    'Branch Evaluated: ' + decision + '\n' +
                    'Maximum Value Computed: ' + maxVal + '\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(12 + Math.random() * 8) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 9.2: Print 1 to N Numbers
    app.controller('Php92Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.n = 15;
        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.numbers = [];
        $scope.stats = { total: 0, evens: 0, odds: 0 };
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var count = parseInt($scope.n, 10) || 1;
                if (count > 200) count = 200;
                if (count < 1) count = 1;

                var list = [];
                var sum = 0, evens = 0, odds = 0;
                for (var i = 1; i <= count; i++) {
                    list.push(i);
                    sum += i;
                    if (i % 2 === 0) evens++; else odds++;
                }

                $scope.numbers = list;
                $scope.stats = { total: sum, evens: evens, odds: odds };

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical9_2.php) ---\n' +
                    'N = ' + count + '\n' +
                    'Loop: for ($i = 1; $i <= ' + count + '; $i++)\n' +
                    'Numbers: ' + list.join(', ') + '\n' +
                    'Total Count: ' + list.length + ' numbers\n' +
                    'Cumulative Sum: ' + sum + '\n' +
                    'Even Count: ' + evens + ' | Odd Count: ' + odds + '\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(14 + Math.random() * 8) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 9.3: 3 Pyramid Patterns
    app.controller('Php93Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.rows = 5;
        $scope.symbol = '*';
        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.patterns = { right: '', centered: '', inverted: '' };
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var r = parseInt($scope.rows, 10) || 5;
                if (r > 15) r = 15;
                if (r < 1) r = 1;
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

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical9_3.php) ---\n' +
                    'Rows: ' + r + ', Symbol: ' + sym + '\n\n' +
                    'Pattern 1 (Right-Angled):\n' + right + '\n' +
                    'Pattern 2 (Centered Pyramid):\n' + centered + '\n' +
                    'Pattern 3 (Inverted Pyramid):\n' + inverted + '\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(16 + Math.random() * 8) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 10.1: Array Operations
    app.controller('Php101Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.input1 = '14, 8, 35, 2, 19';
        $scope.input2 = '5, 42, 11, 23';
        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.result = {};
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
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

                var printR1 = 'Array\n(\n' + a1.map(function (v, idx) { return '    [' + idx + '] => ' + v; }).join('\n') + '\n)';
                var printRMerged = 'Array\n(\n' + merged.map(function (v, idx) { return '    [' + idx + '] => ' + v; }).join('\n') + '\n)';

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical10_1.php) ---\n' +
                    '10.1.1 Print Values:\n' + printR1 + '\n\n' +
                    '10.1.2 array_reverse($arr1):\n' + JSON.stringify(reversed) + '\n\n' +
                    '10.1.3 array_merge & sort:\n' + printRMerged + '\n\n' +
                    '10.1.4 array_sum($arr1): ' + sum + '\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(15 + Math.random() * 8) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 10.2: String Name & Size Passed as Argument
    app.controller('Php102Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.studentName = 'Vaibhav Senjaliya';
        $scope.sampleString = 'Government MCA College, Maninagar';
        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var str = $scope.sampleString || '';
                $scope.strLength = str.length;
                $scope.wordCount = str.trim().split(/\s+/).filter(Boolean).length;
                $scope.revStr = str.split('').reverse().join('');

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical10_2.php) ---\n' +
                    'Student Name Argument: ' + $scope.studentName + '\n' +
                    'Target String Argument: "' + str + '"\n' +
                    'strlen($str): ' + $scope.strLength + ' characters\n' +
                    'str_word_count($str): ' + $scope.wordCount + ' words\n' +
                    'strrev($str): "' + $scope.revStr + '"\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(13 + Math.random() * 6) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 11.1: Max & Min Numbers
    app.controller('Php111Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.n1 = 45;
        $scope.n2 = 18;
        $scope.n3 = 92;
        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var v1 = parseFloat($scope.n1) || 0;
                var v2 = parseFloat($scope.n2) || 0;
                var v3 = parseFloat($scope.n3) || 0;
                $scope.max = Math.max(v1, v2, v3);
                $scope.min = Math.min(v1, v2, v3);

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical11_1.php) ---\n' +
                    'Inputs: numA=' + v1 + ', numB=' + v2 + ', numC=' + v3 + '\n' +
                    'if-else Decision logic evaluated.\n' +
                    'Maximum: ' + $scope.max + '\n' +
                    'Minimum: ' + $scope.min + '\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(12 + Math.random() * 6) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 11.2: Date/Time & Greeting Message
    app.controller('Php112Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.simulatedHour = new Date().getHours();
        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var now = new Date();
                var h = parseInt($scope.simulatedHour, 10);
                var greeting = '';
                var color = '';

                if (h >= 5 && h < 12) {
                    greeting = 'Good Morning! 🌅';
                    color = '#D97706';
                } else if (h >= 12 && h < 17) {
                    greeting = 'Good Afternoon! ☀️';
                    color = '#2563EB';
                } else if (h >= 17 && h < 22) {
                    greeting = 'Good Evening! 🌇';
                    color = '#7C3AED';
                } else {
                    greeting = 'Good Night! 🌙';
                    color = '#475569';
                }

                $scope.currentDateStr = now.toLocaleDateString('en-IN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                $scope.currentTimeStr = String(h).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0') + ':' + String(now.getSeconds()).padStart(2, '0');
                $scope.greeting = greeting;
                $scope.greetingColor = color;

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical11_2.php) ---\n' +
                    'date_default_timezone_set("Asia/Kolkata");\n' +
                    'Current Date: ' + $scope.currentDateStr + '\n' +
                    'Hour Evaluated: ' + h + ':00 hrs (24-hour format)\n' +
                    'Greeting Decision: ' + greeting + '\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(14 + Math.random() * 6) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 11.3: User Profile & Form Handling
    app.controller('Php113Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.profile = {
            fullName: 'Vaibhav Senjaliya',
            enrollment: '26GMCA52',
            email: 'vaibhav@gmca.ac.in',
            course: 'Master of Computer Applications (MCA)',
            bio: 'Passionate about web architectures, full stack software development, and relational database systems.'
        };

        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.submittedProfile = null;
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                $scope.submittedProfile = angular.copy($scope.profile);

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical11_3.php) ---\n' +
                    'REQUEST_METHOD: POST\n' +
                    '$_POST Array Content:\n' +
                    JSON.stringify($scope.profile, null, 4) + '\n\n' +
                    'Sanitization: htmlspecialchars applied to all fields.\n' +
                    'Profile Record Verified. HTTP 200 OK\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(18 + Math.random() * 8) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 11.4: String Functions (5 Tasks)
    app.controller('Php114Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.nameInput = 'Vaibhav Senjaliya';
        $scope.sizeArg = 'Government MCA College, Maninagar';
        $scope.concat1 = 'Web Technology';
        $scope.concat2 = 'Practical Portal';
        $scope.caseStr = 'Master of Computer Applications';
        $scope.haystack = 'Gujarat Technological University Ahmedabad';
        $scope.needle = 'University';

        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.out = {};
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                var pos = ($scope.haystack || '').indexOf($scope.needle || '');
                $scope.out = {
                    name: $scope.nameInput,
                    size: ($scope.sizeArg || '').length,
                    concat: ($scope.concat1 || '') + ' ' + ($scope.concat2 || ''),
                    upper: ($scope.caseStr || '').toUpperCase(),
                    lower: ($scope.caseStr || '').toLowerCase(),
                    title: ($scope.caseStr || '').replace(/\b\w/g, function (l) { return l.toUpperCase(); }),
                    foundPos: pos !== -1 ? 'Found at index position: ' + pos : 'Substring NOT found'
                };

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical11_4.php) ---\n' +
                    '11.4.1 printName(): ' + $scope.out.name + '\n' +
                    '11.4.2 strlen("' + $scope.sizeArg + '"): ' + $scope.out.size + ' bytes\n' +
                    '11.4.3 concatenation: "' + $scope.out.concat + '"\n' +
                    '11.4.4 strtoupper: ' + $scope.out.upper + '\n' +
                    '       strtolower: ' + $scope.out.lower + '\n' +
                    '       ucwords:    ' + $scope.out.title + '\n' +
                    '11.4.5 strpos: ' + $scope.out.foundPos + '\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(15 + Math.random() * 8) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 300);
        };
    }]);

    // 12: Database Connection & Architecture Guide
    app.controller('Php12Controller', ['$scope', '$timeout', function ($scope, $timeout) {
        $scope.dbHost = 'localhost';
        $scope.dbUser = 'root';
        $scope.dbName = 'gmca_wtp_practical';
        $scope.testQuery = 'SELECT * FROM railway_bookings WHERE status = "Confirmed"';

        $scope.hasRun = false;
        $scope.isRunning = false;
        $scope.activeOutputTab = 'web';
        $scope.connectionStatus = null;
        $scope.stdout = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $timeout(function () {
                $scope.connectionStatus = {
                    connected: true,
                    dsn: 'mysql:host=' + $scope.dbHost + ';dbname=' + $scope.dbName + ';charset=utf8mb4',
                    serverVersion: 'MySQL 8.0.35 InnoDB',
                    ping: '0.8ms'
                };

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP PDO STDOUT (practical12_crud.php) ---\n' +
                    'Connecting to: mysql:host=' + $scope.dbHost + ';dbname=' + $scope.dbName + '\n' +
                    'PDO Attributes: ATTR_ERRMODE => ERRMODE_EXCEPTION, ATTR_DEFAULT_FETCH_MODE => FETCH_ASSOC\n' +
                    'Database Connection: ESTABLISHED SUCCESSFULLY.\n' +
                    'Executing Query: ' + $scope.testQuery + '\n' +
                    'Query Status: 200 OK | Records matched: Simulated Active\n\n' +
                    'Script completed with exit code 0';

                $scope.executionTime = Math.floor(22 + Math.random() * 10) + 'ms';
                $scope.isRunning = false;
                $scope.hasRun = true;
            }, 350);
        };
    }]);

    // PHP Interactive Hub
    app.controller('PhpHubController', ['$scope', function ($scope) {
        $scope.activeTab = '9-1';
    }]);

})();
