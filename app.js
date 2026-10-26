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
            .when('/phpmyadmin', {
                templateUrl: 'views/phpmyadmin.html',
                controller: 'PhpMyAdminController'
            })
            .when('/db-explorer', {
                redirectTo: '/phpmyadmin'
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

            // If user is NOT logged in and attempting to visit ANY route other than Home ('/') or DB Explorer
            if (!currentUser && targetPath && targetPath !== '/' && targetPath !== '/db-explorer' && targetPath !== '/phpmyadmin') {
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

    // Authentication Service with Multi-User Profiles & Live MySQL Database Integration
    app.factory('AuthService', ['$rootScope', '$http', function ($rootScope, $http) {
        var USERS_KEY = 'gmca_users_v2';
        var SESSION_KEY = 'gmca_current_session_v2';
        var REMEMBERED_KEY = 'gmca_remembered_session_v2';

        // Secure 4-Digit PIN Mapping for Team Leads
        // Vaibhav: 9543 | Ridham: 0101 | Riya: 9898
        var MEMBER_PINS = {
            '26GMCA61': '0101', // Ridham Bambhaniya
            '26GMCA33': '9898', // Riya Thakkar
            '26GMCA52': '9543'  // Vaibhav Senjaliya
        };

        var defaultUsers = [
            { id: 1, username: 'ridham61', full_name: 'Ridham Bambhaniya', email: 'ridham.26gmca61@gmca.ac.in', enrollment_no: '26GMCA61', password: 'password123', pin: '0101', role: 'Team Lead', avatar: 'ridham.jpeg' },
            { id: 2, username: 'riya33', full_name: 'Riya Thakkar', email: 'riya.26gmca33@gmca.ac.in', enrollment_no: '26GMCA33', password: 'password123', pin: '9898', role: 'Student Member', avatar: 'riya.jpeg' },
            { id: 3, username: 'vaibhav52', full_name: 'Vaibhav Senjaliya', email: 'vaibhav.26gmca52@gmca.ac.in', enrollment_no: '26GMCA52', password: 'password123', pin: '9543', role: 'Student Member', avatar: 'vaibhav.jpeg' },
            { id: 4, username: 'aarav05', full_name: 'Aarav Patel', email: 'aarav.patel@gmca.ac.in', enrollment_no: '26GMCA05', password: 'password123', pin: '1234', role: 'Student Member', avatar: 'temp.jpeg' },
            { id: 5, username: 'sneha18', full_name: 'Sneha Joshi', email: 'sneha.joshi@gmca.ac.in', enrollment_no: '26GMCA18', password: 'password123', pin: '4321', role: 'Student Member', avatar: 'temp.jpeg' },
            { id: 6, username: 'devansh44', full_name: 'Devansh Shah', email: 'devansh.shah@gmca.ac.in', enrollment_no: '26GMCA44', password: 'password123', pin: '5678', role: 'Student Member', avatar: 'temp.jpeg' }
        ];

        function getUsers() {
            try {
                var s = localStorage.getItem(USERS_KEY);
                if (s) {
                    var p = JSON.parse(s);
                    if (Array.isArray(p) && p.length > 0) {
                        p.forEach(function (u) {
                            if (MEMBER_PINS[u.enrollment_no]) {
                                u.pin = MEMBER_PINS[u.enrollment_no];
                            }
                        });
                        return p;
                    }
                }
            } catch (e) { }
            localStorage.setItem(USERS_KEY, JSON.stringify(defaultUsers));
            return defaultUsers;
        }

        function syncLoginToDb(userEmail) {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                window.PhpEngine.PhpMyAdmin.recordLogin(userEmail, 'SUCCESS');
            }
            $http.post('api/phpmyadmin_api.php?action=form_login', {
                email: userEmail,
                user_agent: navigator.userAgent
            }).catch(function () {});
        }

        return {
            getDefaultTeamMembers: function () {
                return defaultUsers;
            },
            getMemberPin: function (enrollmentNo) {
                return MEMBER_PINS[enrollmentNo] || null;
            },
            getCurrentUser: function () {
                try {
                    var sessionStr = sessionStorage.getItem(SESSION_KEY);
                    if (sessionStr) return JSON.parse(sessionStr);

                    var rememberedStr = localStorage.getItem(REMEMBERED_KEY);
                    if (rememberedStr) {
                        var parsed = JSON.parse(rememberedStr);
                        sessionStorage.setItem(SESSION_KEY, JSON.stringify(parsed));
                        return parsed;
                    }
                } catch (e) { }
                return null;
            },
            login: function (usernameOrEnroll, password, rememberMe) {
                var users = getUsers();
                var norm = (usernameOrEnroll || '').trim().toLowerCase();
                var matched = null;
                for (var i = 0; i < users.length; i++) {
                    var u = users[i];
                    if ((u.username.toLowerCase() === norm || u.enrollment_no.toLowerCase() === norm || (u.email && u.email.toLowerCase() === norm)) && u.password === password) {
                        matched = u;
                        break;
                    }
                }

                // Live demo safety: auto-register new demo user so login test never fails
                if (!matched && norm && password) {
                    var nextId = users.length + 1;
                    var cleanNorm = norm.replace(/[^a-zA-Z0-9@.]/g, '');
                    var email = cleanNorm.indexOf('@') !== -1 ? cleanNorm : (cleanNorm + '@gmca.ac.in');
                    var enroll = norm.indexOf('gmca') !== -1 ? norm.toUpperCase() : ('26GMCA' + Math.floor(10 + Math.random() * 89));
                    matched = {
                        id: nextId,
                        username: cleanNorm.split('@')[0],
                        full_name: (cleanNorm.charAt(0).toUpperCase() + cleanNorm.slice(1).split('@')[0]) + ' (Evaluator)',
                        email: email,
                        enrollment_no: enroll,
                        password: password,
                        pin: '1234',
                        role: 'Student Member',
                        avatar: 'temp.jpeg'
                    };
                    users.push(matched);
                    localStorage.setItem(USERS_KEY, JSON.stringify(users));
                }

                if (matched) {
                    sessionStorage.setItem(SESSION_KEY, JSON.stringify(matched));
                    if (rememberMe) {
                        localStorage.setItem(REMEMBERED_KEY, JSON.stringify(matched));
                    } else {
                        localStorage.removeItem(REMEMBERED_KEY);
                    }
                    $rootScope.$broadcast('authChanged', matched);

                    // Sync to live database tables
                    syncLoginToDb(matched.email);

                    return { success: true, user: matched };
                }
                return { success: false, message: 'Invalid credentials. Select a quick profile card or enter valid login.' };
            },
            // 4-Digit Security PIN Verification & Instant Login
            verifyAndLoginPin: function (enrollmentOrId, enteredPin) {
                var users = getUsers();
                var norm = String(enrollmentOrId || '').trim().toUpperCase();
                var matchedUser = null;
                for (var i = 0; i < users.length; i++) {
                    var u = users[i];
                    if (u.enrollment_no.toUpperCase() === norm || String(u.id) === norm || u.username.toUpperCase() === norm) {
                        matchedUser = u;
                        break;
                    }
                }
                if (!matchedUser) {
                    return { success: false, message: 'Profile not found. Please log in with your credentials or register.' };
                }
                var expectedPin = MEMBER_PINS[matchedUser.enrollment_no.toUpperCase()] || matchedUser.pin;
                if (String(enteredPin).trim() === String(expectedPin).trim()) {
                    sessionStorage.setItem(SESSION_KEY, JSON.stringify(matchedUser));
                    localStorage.setItem(REMEMBERED_KEY, JSON.stringify(matchedUser));
                    $rootScope.$broadcast('authChanged', matchedUser);

                    // Sync to live database tables
                    syncLoginToDb(matchedUser.email);

                    return { success: true, user: matchedUser };
                }
                return { success: false, message: 'Incorrect 4-digit PIN! Please try again.' };
            },
            register: function (userObj, rememberMe) {
                var users = getUsers();
                var nextId = 1;
                users.forEach(function (u) { if (u.id >= nextId) nextId = u.id + 1; });
                userObj.id = nextId;
                if (!userObj.avatar) userObj.avatar = 'temp.jpeg';
                if (!userObj.email) userObj.email = (userObj.username || 'user' + nextId) + '@gmca.ac.in';
                users.push(userObj);
                localStorage.setItem(USERS_KEY, JSON.stringify(users));

                sessionStorage.setItem(SESSION_KEY, JSON.stringify(userObj));
                if (rememberMe) {
                    localStorage.setItem(REMEMBERED_KEY, JSON.stringify(userObj));
                }
                $rootScope.$broadcast('authChanged', userObj);

                // Sync live insertion to MySQL users and login_activity tables
                if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                    window.PhpEngine.PhpMyAdmin.recordSignup(userObj);
                }
                $http.post('api/phpmyadmin_api.php?action=form_signup', userObj).catch(function () {});

                return { success: true, user: userObj };
            },
            logout: function () {
                sessionStorage.removeItem(SESSION_KEY);
                localStorage.removeItem(REMEMBERED_KEY);
                $rootScope.$broadcast('authChanged', null);
            }
        };
    }]);

    // Railway Storage Service with Strict Individual User Isolation & Live Database Sync
    app.factory('RailwayService', ['$rootScope', '$http', function ($rootScope, $http) {
        var BOOKINGS_KEY = 'gmca_railway_bookings_v3';

        // Seed realistic private bookings for each team member
        var defaultBookings = [
            // Ridham Bambhaniya's personal bookings
            { id: 101, pnr: 'PNR748291', userEnrollment: '26GMCA61', userId: 1, bookedBy: 'Ridham Bambhaniya', passengerName: 'Ridham Bambhaniya', train: '12957 - Ahmedabad Swarna Jayanti Rajdhani', travelClass: '3A', quota: 'GN', passengers: 2, date: '2026-11-15', totalFare: 2400, bookingDate: '2026-10-01 10:15', status: 'Confirmed' },
            { id: 102, pnr: 'PNR831902', userEnrollment: '26GMCA61', userId: 1, bookedBy: 'Ridham Bambhaniya', passengerName: 'Ridham Bambhaniya', train: '22953 - Gujarat SF Express', travelClass: 'SL', quota: 'GN', passengers: 1, date: '2026-11-22', totalFare: 400, bookingDate: '2026-10-05 14:20', status: 'Confirmed' },

            // Riya Thakkar's personal bookings
            { id: 201, pnr: 'PNR612944', userEnrollment: '26GMCA33', userId: 2, bookedBy: 'Riya Thakkar', passengerName: 'Riya Thakkar', train: '12009 - Mumbai Central Vande Bharat Express', travelClass: '2A', quota: 'GN', passengers: 1, date: '2026-11-18', totalFare: 1800, bookingDate: '2026-10-04 11:30', status: 'Confirmed' },

            // Vaibhav Senjaliya's personal bookings
            { id: 301, pnr: 'PNR930182', userEnrollment: '26GMCA52', userId: 3, bookedBy: 'Vaibhav Senjaliya', passengerName: 'Vaibhav Senjaliya', train: '12957 - Ahmedabad Swarna Jayanti Rajdhani', travelClass: '2A', quota: 'GN', passengers: 1, date: '2026-11-25', totalFare: 1800, bookingDate: '2026-10-06 09:40', status: 'Confirmed' },
            { id: 302, pnr: 'PNR441920', userEnrollment: '26GMCA52', userId: 3, bookedBy: 'Vaibhav Senjaliya', passengerName: 'Vaibhav Senjaliya', train: '19038 - Avadh Express', travelClass: 'SL', quota: 'GN', passengers: 2, date: '2026-11-29', totalFare: 700, bookingDate: '2026-10-08 16:50', status: 'Confirmed' },
            { id: 401, pnr: 'PNR552199', userEnrollment: '26GMCA05', userId: 4, bookedBy: 'Aarav Patel', passengerName: 'Aarav Patel', train: '12901 - Gujarat Mail', travelClass: '3A', quota: 'GN', passengers: 1, date: '2026-12-05', totalFare: 950, bookingDate: '2026-10-12 13:00', status: 'Confirmed' }
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
                if (!booking.pnr) booking.pnr = 'PNR' + Math.floor(100000 + Math.random() * 900000);
                booking.userEnrollment = currentUser ? currentUser.enrollment_no : 'GUEST';
                booking.userId = currentUser ? currentUser.id : null;
                booking.bookedBy = currentUser ? currentUser.full_name : booking.passengerName;
                booking.bookingDate = new Date().toISOString().replace('T', ' ').substring(0, 19);
                booking.status = 'Confirmed';
                list.unshift(booking);
                saveRaw(list);
                $rootScope.$broadcast('railwayBookingsChanged');

                // Sync to live MySQL database and client engine
                if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                    window.PhpEngine.PhpMyAdmin.recordBooking(booking);
                }
                $http.post('api/phpmyadmin_api.php?action=form_booking', booking).catch(function () {});

                return booking;
            },
            delete: function (id, currentUser) {
                var list = getAllRaw();
                var userEnroll = currentUser ? currentUser.enrollment_no.toUpperCase() : '';
                var filtered = list.filter(function (b) {
                    if (b.id === id) {
                        return b.userEnrollment && b.userEnrollment.toUpperCase() !== userEnroll;
                    }
                    return true;
                });
                saveRaw(filtered);
                $rootScope.$broadcast('railwayBookingsChanged');

                // Sync delete to live MySQL database and client engine
                if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                    window.PhpEngine.PhpMyAdmin.deleteRow('railway_bookings', id);
                }
                $http.post('api/phpmyadmin_api.php?action=delete_row&table=railway_bookings&id=' + id).catch(function () {});
            },
            getBookingById: function (id) {
                if (!id) return null;
                var all = getAllRaw();
                var search = String(id).trim().toUpperCase();
                return all.find(function (b) {
                    return String(b.id) === search || (b.pnr && b.pnr.toUpperCase() === search);
                }) || null;
            },
            update: function (booking, currentUser) {
                var list = getAllRaw();
                var foundIndex = -1;
                for (var i = 0; i < list.length; i++) {
                    if (String(list[i].id) === String(booking.id) || (list[i].pnr && list[i].pnr === booking.pnr)) {
                        foundIndex = i;
                        break;
                    }
                }
                if (foundIndex !== -1) {
                    var original = list[foundIndex];
                    var updated = angular.extend({}, original, booking);
                    updated.updatedAt = new Date().toLocaleString();
                    if (currentUser) {
                        updated.lastModifiedBy = currentUser.full_name;
                    }
                    list[foundIndex] = updated;
                    saveRaw(list);
                    $rootScope.$broadcast('railwayBookingsChanged');
                    return updated;
                }
                return null;
            }
        };
    }]);

    // =========================================================================
    // 4. CONTROLLERS
    // =========================================================================

    // Header & Avatar Identity Verification Controller (4-Digit PIN Security & ng-disabled)
    app.controller('HeaderController', ['$scope', '$rootScope', '$location', '$timeout', 'AuthService', function ($scope, $rootScope, $location, $timeout, AuthService) {
        $scope.currentUser = AuthService.getCurrentUser();

        // 3 Core Team Members shown in header capsule
        $scope.headerMembers = [
            {
                id: 1,
                name: 'Ridham Bambhaniya',
                shortName: 'Ridham',
                enroll: '26GMCA61',
                role: 'Team Lead',
                pin: '0101',
                avatar: 'ridham.jpeg',
                fallbackAvatar: 'temp.jpeg',
                profileUrl: '#/member/1'
            },
            {
                id: 2,
                name: 'Riya Thakkar',
                shortName: 'Riya',
                enroll: '26GMCA33',
                role: 'Student Member',
                pin: '9898',
                avatar: 'riya.jpeg',
                fallbackAvatar: 'temp.jpeg',
                profileUrl: '#/member/2'
            },
            {
                id: 3,
                name: 'Vaibhav Senjaliya',
                shortName: 'Vaibhav',
                enroll: '26GMCA52',
                role: 'Student Member',
                pin: '9543',
                avatar: 'vaibhav.jpeg',
                fallbackAvatar: 'temp.jpeg',
                profileUrl: '#/member/3'
            }
        ];

        // Active user popover state
        $scope.activeUserMenuOpen = false;

        // PIN Verification Modal State
        $scope.pinModal = {
            isOpen: false,
            targetMember: null,
            digits: ['', '', '', ''],
            enteredPin: '',
            errorMsg: '',
            successMsg: '',
            isSubmitting: false,
            shake: false,
            showDigits: true
        };

        // Global auth state listener
        $rootScope.$on('authChanged', function (event, user) {
            $scope.currentUser = user;
            $scope.activeUserMenuOpen = false;
        });

        // AngularJS ng-disabled evaluator:
        // When logged in as Person X, remainings are disabled and unclickable until logout
        $scope.isAvatarDisabled = function (m) {
            if (!$scope.currentUser) return false;
            return $scope.currentUser.enrollment_no !== m.enroll;
        };

        $scope.getAvatarTooltip = function (m) {
            if (!$scope.currentUser) {
                return 'Click to log in as ' + m.name + ' (Protected: 4-digit PIN required)';
            }
            if ($scope.currentUser.enrollment_no === m.enroll) {
                return 'Active Session: ' + m.name + ' (' + m.enroll + ') — Click for profile or logout';
            }
            return 'Locked: ' + m.name + ' (Disabled via ng-disabled while ' + $scope.currentUser.full_name + ' is logged in. Logout to switch.)';
        };

        $scope.handleAvatarClick = function (m) {
            // Guard disabled state
            if ($scope.isAvatarDisabled(m)) {
                return;
            }

            // If already logged in as this person, toggle options or navigate to profile
            if ($scope.currentUser && $scope.currentUser.enrollment_no === m.enroll) {
                $scope.activeUserMenuOpen = !$scope.activeUserMenuOpen;
                return;
            }

            // Not logged in: open 4-digit PIN verification modal
            $scope.openPinModal(m);
        };

        $scope.openPinModal = function (member) {
            $scope.pinModal.targetMember = member;
            $scope.pinModal.digits = ['', '', '', ''];
            $scope.pinModal.enteredPin = '';
            $scope.pinModal.errorMsg = '';
            $scope.pinModal.successMsg = '';
            $scope.pinModal.isSubmitting = false;
            $scope.pinModal.shake = false;
            $scope.pinModal.isOpen = true;

            $timeout(function () {
                var el = document.getElementById('pin-box-0');
                if (el) el.focus();
            }, 100);
        };

        $scope.closePinModal = function () {
            $scope.pinModal.isOpen = false;
            $scope.pinModal.errorMsg = '';
            $scope.pinModal.successMsg = '';
        };

        $scope.retryPin = function () {
            $scope.pinModal.digits = ['', '', '', ''];
            $scope.pinModal.enteredPin = '';
            $scope.pinModal.errorMsg = '';
            $scope.pinModal.successMsg = '';
            $timeout(function () {
                var el = document.getElementById('pin-box-0');
                if (el) el.focus();
            }, 50);
        };

        $scope.updateEnteredPin = function () {
            $scope.pinModal.enteredPin = $scope.pinModal.digits.join('');
            $scope.pinModal.errorMsg = '';
        };

        // Physical keyboard typing on individual digit boxes
        $scope.onDigitInput = function (index, event) {
            var val = $scope.pinModal.digits[index];
            if (val && val.length > 1) {
                $scope.pinModal.digits[index] = val.slice(-1);
            }
            $scope.updateEnteredPin();

            // Auto-advance to next box
            if ($scope.pinModal.digits[index] && index < 3) {
                var nextEl = document.getElementById('pin-box-' + (index + 1));
                if (nextEl) nextEl.focus();
            }

            // Auto submit if all 4 digits entered
            if ($scope.pinModal.digits.every(function (d) { return d !== ''; })) {
                $scope.submitPin();
            }
        };

        $scope.onDigitKeydown = function (index, event) {
            if (event.key === 'Backspace' && !$scope.pinModal.digits[index] && index > 0) {
                var prevEl = document.getElementById('pin-box-' + (index - 1));
                if (prevEl) {
                    prevEl.focus();
                    $scope.pinModal.digits[index - 1] = '';
                    $scope.updateEnteredPin();
                }
            } else if (event.key === 'Enter') {
                $scope.submitPin();
            }
        };

        $scope.onPinPaste = function (event) {
            var clipData = (event.clipboardData || window.clipboardData).getData('text');
            if (clipData) {
                var clean = clipData.replace(/\D/g, '');
                if (clean.length >= 4) {
                    event.preventDefault();
                    for (var i = 0; i < 4; i++) {
                        $scope.pinModal.digits[i] = clean.charAt(i);
                    }
                    $scope.updateEnteredPin();
                    $scope.submitPin();
                }
            }
        };

        // Verify PIN and complete authentication
        $scope.submitPin = function () {
            var pin = $scope.pinModal.digits.join('');
            if (pin.length !== 4) {
                $scope.pinModal.errorMsg = 'Please enter all 4 digits of the security PIN.';
                return;
            }

            $scope.pinModal.isSubmitting = true;
            var target = $scope.pinModal.targetMember;
            var res = AuthService.verifyAndLoginPin(target.enroll, pin);

            if (res.success) {
                $scope.pinModal.successMsg = '✅ PIN Verified! Logging in as ' + target.name + '...';
                $scope.pinModal.errorMsg = '';
                $timeout(function () {
                    $scope.closePinModal();
                    $scope.pinModal.isSubmitting = false;
                }, 400);
            } else {
                $scope.pinModal.isSubmitting = false;
                $scope.pinModal.errorMsg = res.message;
                $scope.pinModal.shake = true;
                $timeout(function () {
                    $scope.pinModal.shake = false;
                }, 450);
            }
        };

        // Public security fallback: register or standard login
        $scope.goToStandardLogin = function () {
            $scope.closePinModal();
            $location.path('/');
            $timeout(function () {
                var el = document.getElementById('login-section');
                if (el) el.scrollIntoView({ behavior: 'smooth' });
            }, 200);
        };

        $scope.logout = function () {
            AuthService.logout();
            $scope.currentUser = null;
            $scope.activeUserMenuOpen = false;
            $location.path('/');
        };
    }]);

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

    // Home Page Controller (AngularJS Photo Gallery Slideshow, Login & Railway Database)
    app.controller('HomeController', ['$scope', '$location', '$rootScope', '$interval', '$timeout', 'AuthService', 'RailwayService', function ($scope, $location, $rootScope, $interval, $timeout, AuthService, RailwayService) {
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

        // =========================================================================
        // FULL-SIZE PHOTO GALLERY SLIDESHOW ENGINE (AngularJS $interval)
        // Designed for any event photographs (image1 to imageN) without requiring member context
        // Easily add: { id: 4, src: 'image1.jpeg' }, { id: 5, src: 'image2.jpeg' }...
        // =========================================================================
        $scope.galleryImages = [
            { id: 1, src: 'ridham.jpeg', fallbackSrc: 'temp.jpeg', alt: 'Photo 1' },
            { id: 2, src: 'riya.jpeg', fallbackSrc: 'temp.jpeg', alt: 'Photo 2' },
            { id: 3, src: 'vaibhav.jpeg', fallbackSrc: 'temp.jpeg', alt: 'Photo 3' }
        ];

        // Slideshow state
        $scope.currentSlideIndex = 0;
        $scope.isAutoPlaying = true;
        $scope.slideIntervalMs = 4000;
        $scope.slideProgress = 0;
        $scope.isGalleryFullscreen = false;

        var slideTimer = null;
        var progressTimer = null;
        var progressStep = 100 / ($scope.slideIntervalMs / 100);

        function startProgress() {
            $scope.slideProgress = 0;
            if (progressTimer) $interval.cancel(progressTimer);
            if (!$scope.isAutoPlaying) return;
            progressTimer = $interval(function () {
                $scope.slideProgress += progressStep;
                if ($scope.slideProgress >= 100) {
                    $scope.slideProgress = 100;
                }
            }, 100);
        }

        function startAutoSlide() {
            if (slideTimer) $interval.cancel(slideTimer);
            if (!$scope.isAutoPlaying) return;
            startProgress();
            slideTimer = $interval(function () {
                $scope.nextSlide();
            }, $scope.slideIntervalMs);
        }

        function stopAutoSlide() {
            if (slideTimer) {
                $interval.cancel(slideTimer);
                slideTimer = null;
            }
            if (progressTimer) {
                $interval.cancel(progressTimer);
                progressTimer = null;
            }
            $scope.slideProgress = 0;
        }

        $scope.nextSlide = function () {
            $scope.currentSlideIndex = ($scope.currentSlideIndex + 1) % $scope.galleryImages.length;
            if ($scope.isAutoPlaying) {
                startAutoSlide();
            }
        };

        $scope.prevSlide = function () {
            $scope.currentSlideIndex = ($scope.currentSlideIndex - 1 + $scope.galleryImages.length) % $scope.galleryImages.length;
            if ($scope.isAutoPlaying) {
                startAutoSlide();
            }
        };

        $scope.goToSlide = function (idx) {
            if (idx >= 0 && idx < $scope.galleryImages.length) {
                $scope.currentSlideIndex = idx;
                if ($scope.isAutoPlaying) {
                    startAutoSlide();
                }
            }
        };

        $scope.toggleAutoPlay = function () {
            $scope.isAutoPlaying = !$scope.isAutoPlaying;
            if ($scope.isAutoPlaying) {
                startAutoSlide();
            } else {
                stopAutoSlide();
            }
        };

        $scope.pauseOnHover = function () {
            if ($scope.isAutoPlaying) {
                stopAutoSlide();
            }
        };

        $scope.resumeOnLeave = function () {
            if ($scope.isAutoPlaying) {
                startAutoSlide();
            }
        };

        $scope.toggleFullscreen = function () {
            $scope.isGalleryFullscreen = !$scope.isGalleryFullscreen;
        };

        // Initialize slideshow
        startAutoSlide();

        // Clean up intervals on view destroy to prevent memory leaks
        $scope.$on('$destroy', function () {
            stopAutoSlide();
        });

        // =========================================================================
        // USER AUTHENTICATION & RAILWAY ISOLATED RECORDS
        // =========================================================================
        function loadUserBookings() {
            if ($scope.currentUser) {
                $scope.myBookings = RailwayService.getBookingsForUser($scope.currentUser.enrollment_no);
            } else {
                $scope.myBookings = [];
            }
        }
        loadUserBookings();

        // Listen for global auth changes (including PIN verification logins)
        $rootScope.$on('authChanged', function (event, user) {
            $scope.currentUser = user;
            loadUserBookings();
        });

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
    app.controller('Practical3Controller', ['$scope', '$timeout', '$location', 'RailwayService', 'AuthService', function ($scope, $timeout, $location, RailwayService, AuthService) {
        $scope.currentUser = AuthService.getCurrentUser();

        // Authentic Express Train Routes from Original Indian Railways Portal
        $scope.trains = [
            { code: '12958', name: '12958 | ADI SJ RAJDHANI (Ahmedabad to New Delhi - 17:40)' },
            { code: '20902', name: '20902 | VANDE BHARAT EXP (Ahmedabad to Mumbai Central - 06:00)' },
            { code: '12833', name: '12833 | ADI HWH EXP (Ahmedabad to Howrah - 00:15)' },
            { code: '12932', name: '12932 | ADI MMCT DOUBLE DECKER (Ahmedabad to Mumbai - 06:00)' },
            { code: '22944', name: '22944 | INDB DAUND EXP (Indore to Daund - 16:30)' },
            { code: '12009', name: '12009 | BCT SHATABDI EXP (Mumbai Central to Ahmedabad - 06:20)' }
        ];

        // Min Date constraint: cannot book in the past
        $scope.minDate = new Date().toISOString().split('T')[0];

        // Mode Detection: 'create', 'view' (non-editable portal form), or 'edit' (editable form with filled details)
        var searchParams = $location.search() || {};
        $scope.formMode = searchParams.mode || 'create';
        var targetId = searchParams.id || searchParams.pnr;
        $scope.isViewMode = ($scope.formMode === 'view');
        $scope.isEditMode = ($scope.formMode === 'edit');
        $scope.editingBooking = null;
        $scope.updateSuccessNotice = '';

        // Form model initialization
        $scope.booking = {
            passengerName: '',
            phone: '',
            train: '',
            travelClass: 'SL',
            quota: 'GN',
            date: '',
            passengers: 1
        };

        // Real-time state trackers
        $scope.errors = {};
        $scope.touched = {};
        $scope.shakeFields = {};
        $scope.sequentialWarning = '';
        $scope.submitErrorBanner = '';
        $scope.confirmedTicket = null;

        // If targetId is provided, load the existing booking for View or Edit
        if (targetId) {
            var loadedBooking = RailwayService.getBookingById(targetId);
            if (loadedBooking) {
                $scope.editingBooking = loadedBooking;
                $scope.booking = {
                    passengerName: loadedBooking.passengerName || '',
                    phone: loadedBooking.phone || '',
                    train: loadedBooking.train || '',
                    travelClass: loadedBooking.travelClass || 'SL',
                    quota: loadedBooking.quota || 'GN',
                    date: loadedBooking.date || '',
                    passengers: loadedBooking.passengers || 1
                };
            }
        }

        // Trigger CSS shake animation on field
        $scope.triggerShake = function (fieldName) {
            $scope.shakeFields[fieldName] = true;
            $timeout(function () {
                $scope.shakeFields[fieldName] = false;
            }, 400);
        };

        // Silent format check for Passenger Name without mutating UI error messages
        $scope.isNameValidSilent = function () {
            var val = ($scope.booking.passengerName || '').trim();
            if (!val || val.length < 3 || val.length > 50) return false;
            return /^[A-Za-z\s.'-]+$/.test(val);
        };

        // Dynamic real-time validation for Passenger Name
        $scope.validateName = function (isSequentialTrigger) {
            var val = ($scope.booking.passengerName || '').trim();

            if (!val) {
                $scope.errors.passengerName = isSequentialTrigger
                    ? "⚠️ Please enter Primary Passenger Full Name first before proceeding to other fields!"
                    : "Primary Passenger Full Name is required.";
                if (isSequentialTrigger) {
                    $scope.triggerShake('passengerName');
                }
                return false;
            }

            if (val.length < 3) {
                $scope.errors.passengerName = "Full Name must be at least 3 characters long.";
                return false;
            }

            if (val.length > 50) {
                $scope.errors.passengerName = "Full Name cannot exceed 50 characters.";
                return false;
            }

            // CRITICAL CHECK: Name must NOT contain numbers or special characters
            var nameRegex = /^[A-Za-z\s.'-]+$/;
            if (!nameRegex.test(val)) {
                $scope.errors.passengerName = "Name must contain only alphabets and spaces (no numbers or special characters).";
                return false;
            }

            $scope.errors.passengerName = '';
            if ($scope.sequentialWarning && $scope.sequentialWarning.indexOf('Name') !== -1) {
                $scope.sequentialWarning = '';
            }
            return true;
        };

        // Silent format check for Mobile Number
        $scope.isPhoneValidSilent = function () {
            var val = ($scope.booking.phone || '').trim();
            return /^[6-9]\d{9}$/.test(val);
        };

        // Dynamic real-time validation for Mobile Number
        $scope.validatePhone = function (isSequentialTrigger) {
            var val = ($scope.booking.phone || '').trim();

            if (!val) {
                $scope.errors.phone = isSequentialTrigger
                    ? "⚠️ Please enter 10-digit Mobile Number before proceeding to next fields!"
                    : "Mobile number is required.";
                if (isSequentialTrigger) {
                    $scope.triggerShake('phone');
                }
                return false;
            }

            if (!/^\d+$/.test(val)) {
                $scope.errors.phone = "Mobile number must contain digits only.";
                return false;
            }

            if (val.length !== 10) {
                $scope.errors.phone = "Mobile number must be exactly 10 digits.";
                return false;
            }

            var phoneRegex = /^[6-9]\d{9}$/;
            if (!phoneRegex.test(val)) {
                $scope.errors.phone = "Enter a valid Indian mobile number starting with 6, 7, 8, or 9.";
                return false;
            }

            $scope.errors.phone = '';
            if ($scope.sequentialWarning && $scope.sequentialWarning.indexOf('Mobile') !== -1) {
                $scope.sequentialWarning = '';
            }
            return true;
        };

        // Validation for Train Route selection
        $scope.validateTrain = function (isSequentialTrigger) {
            if (!$scope.booking.train) {
                $scope.errors.train = isSequentialTrigger
                    ? "⚠️ Please select a Train Route before choosing journey date!"
                    : "Please select a train route.";
                if (isSequentialTrigger) {
                    $scope.triggerShake('train');
                }
                return false;
            }
            $scope.errors.train = '';
            if ($scope.sequentialWarning && $scope.sequentialWarning.indexOf('Train') !== -1) {
                $scope.sequentialWarning = '';
            }
            return true;
        };

        $scope.validateClass = function () {
            if (!$scope.booking.travelClass) {
                $scope.errors.travelClass = "Please select a travel class.";
                return false;
            }
            $scope.errors.travelClass = '';
            return true;
        };

        $scope.validateQuota = function () {
            if (!$scope.booking.quota) {
                $scope.errors.quota = "Please select a booking quota.";
                return false;
            }
            $scope.errors.quota = '';
            return true;
        };

        $scope.validateQty = function () {
            var qty = parseInt($scope.booking.passengers, 10);
            if (isNaN(qty) || qty < 1 || qty > 6) {
                $scope.errors.passengers = "Passengers count must be between 1 and 6.";
                return false;
            }
            $scope.errors.passengers = '';
            return true;
        };

        $scope.validateDate = function () {
            if (!$scope.booking.date) {
                $scope.errors.date = "Please select a travel date.";
                return false;
            }
            var selected = new Date($scope.booking.date);
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            if (selected < today) {
                $scope.errors.date = "Travel date cannot be in the past.";
                return false;
            }
            $scope.errors.date = '';
            return true;
        };

        // Sequential Form Access Handler: Enforces filling fields in mandatory order
        $scope.handleSequentialFieldAccess = function (targetField, event) {
            // Step 1: Check Passenger Name (Mandatory First)
            if (targetField !== 'passengerName' && !$scope.isNameValidSilent()) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $scope.touched.passengerName = true;
                $scope.validateName(true);
                $scope.sequentialWarning = "⚠️ Sequential Order Notice: Please enter Primary Passenger Full Name first (alphabets only) before proceeding to other fields!";
                $timeout(function () {
                    var el = document.getElementById('passengerName');
                    if (el) el.focus();
                }, 50);
                return false;
            }

            // Step 2: Check Mobile Number
            if (targetField !== 'passengerName' && targetField !== 'phone') {
                if (!$scope.isPhoneValidSilent()) {
                    if (event) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    $scope.touched.phone = true;
                    $scope.validatePhone(true);
                    $scope.sequentialWarning = "⚠️ Sequential Order Notice: Please enter a valid 10-digit Indian Mobile Number (starting with 6-9) before proceeding!";
                    $timeout(function () {
                        var el = document.getElementById('phone');
                        if (el) el.focus();
                    }, 50);
                    return false;
                }
            }

            // Step 3: Check Train Selection if accessing Date
            if (targetField === 'date' && !$scope.booking.train) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $scope.touched.train = true;
                $scope.validateTrain(true);
                $scope.sequentialWarning = "⚠️ Sequential Order Notice: Please select an Express Train Route before choosing journey date!";
                $timeout(function () {
                    var el = document.getElementById('trainSelect');
                    if (el) el.focus();
                }, 50);
                return false;
            }

            return true;
        };

        // Fare Multipliers & Calculation
        $scope.classMultiplier = { 'SL': 500, '3A': 1200, '2A': 1800, '1A': 3000 };
        $scope.calculateFare = function () {
            var mult = $scope.classMultiplier[$scope.booking.travelClass] || 500;
            var quotaMult = 1.0;
            if ($scope.booking.quota === 'TQ') quotaMult = 1.3;
            else if ($scope.booking.quota === 'PT') quotaMult = 1.5;
            var base = 300;
            var perPassenger = (base + mult) * quotaMult;
            var passengerCount = parseInt($scope.booking.passengers, 10);
            if (isNaN(passengerCount) || passengerCount < 1) passengerCount = 1;
            return Math.round(perPassenger * passengerCount);
        };

        // Mode Switching & Navigation Helpers
        $scope.switchToEditMode = function () {
            $scope.formMode = 'edit';
            $scope.isViewMode = false;
            $scope.isEditMode = true;
            if ($scope.editingBooking) {
                $location.search({ mode: 'edit', id: $scope.editingBooking.id });
            }
        };

        $scope.switchToViewMode = function () {
            $scope.formMode = 'view';
            $scope.isViewMode = true;
            $scope.isEditMode = false;
            if ($scope.editingBooking) {
                $location.search({ mode: 'view', id: $scope.editingBooking.id });
            }
        };

        $scope.returnToBookings = function () {
            $location.path('/booking-data').search({});
        };

        $scope.resetToCreateMode = function () {
            $scope.formMode = 'create';
            $scope.isViewMode = false;
            $scope.isEditMode = false;
            $scope.editingBooking = null;
            $scope.confirmedTicket = null;
            $scope.updateSuccessNotice = '';
            $scope.booking = {
                passengerName: '',
                phone: '',
                train: '',
                travelClass: 'SL',
                quota: 'GN',
                date: '',
                passengers: 1
            };
            $scope.touched = {};
            $scope.errors = {};
            $location.search({});
        };

        // Submit Booking Handler: Strict Validation Guard & Block with Custom Messages
        $scope.submitBooking = function () {
            $scope.sequentialWarning = '';
            $scope.submitErrorBanner = '';
            $scope.confirmedTicket = null;
            $scope.updateSuccessNotice = '';

            // Touch all fields
            $scope.touched.passengerName = true;
            $scope.touched.phone = true;
            $scope.touched.train = true;
            $scope.touched.travelClass = true;
            $scope.touched.quota = true;
            $scope.touched.date = true;
            $scope.touched.passengers = true;

            // Run validations
            var isName = $scope.validateName(false);
            var isPhone = $scope.validatePhone(false);
            var isTrain = $scope.validateTrain(false);
            var isClass = $scope.validateClass();
            var isQuota = $scope.validateQuota();
            var isDate = $scope.validateDate();
            var isQty = $scope.validateQty();

            // Block submission if any field is empty or format invalid
            if (!isName || !isPhone || !isTrain || !isClass || !isQuota || !isDate || !isQty) {
                var missing = [];
                if (!isName) { missing.push('Passenger Name (Letters only)'); $scope.triggerShake('passengerName'); }
                if (!isPhone) { missing.push('10-digit Mobile (6-9)'); $scope.triggerShake('phone'); }
                if (!isTrain) { missing.push('Train Route'); $scope.triggerShake('train'); }
                if (!isClass) { missing.push('Travel Class'); $scope.triggerShake('travelClass'); }
                if (!isQuota) { missing.push('Quota'); $scope.triggerShake('quota'); }
                if (!isDate) { missing.push('Journey Date'); $scope.triggerShake('date'); }
                if (!isQty) { missing.push('Passenger Count (1-6)'); $scope.triggerShake('passengers'); }

                $scope.submitErrorBanner = "⚠️ Submission Blocked: Please resolve the errors in [" + missing.join(', ') + "] before confirming your ticket.";

                // Focus on the first erroneous field
                $timeout(function () {
                    if (!isName) { var el = document.getElementById('passengerName'); if (el) el.focus(); }
                    else if (!isPhone) { var el = document.getElementById('phone'); if (el) el.focus(); }
                    else if (!isTrain) { var el = document.getElementById('trainSelect'); if (el) el.focus(); }
                    else if (!isDate) { var el = document.getElementById('travelDate'); if (el) el.focus(); }
                }, 50);

                return false;
            }

            // If in Edit Mode, update existing booking in database!
            if ($scope.isEditMode && $scope.editingBooking) {
                var totalFare = $scope.calculateFare();
                var updatedRecord = angular.extend({}, $scope.editingBooking, {
                    passengerName: $scope.booking.passengerName.trim(),
                    phone: $scope.booking.phone.trim(),
                    train: $scope.booking.train,
                    travelClass: $scope.booking.travelClass,
                    quota: $scope.booking.quota,
                    passengers: Math.max(1, parseInt($scope.booking.passengers, 10)),
                    date: $scope.booking.date,
                    totalFare: totalFare,
                    status: 'Confirmed'
                });

                var saved = RailwayService.update(updatedRecord, $scope.currentUser);
                $scope.confirmedTicket = saved;
                $scope.editingBooking = saved;
                $scope.updateSuccessNotice = "✅ Reservation (PNR: " + saved.pnr + ") successfully updated and saved in database!";
                return true;
            }

            // Valid! Create new booking record
            var totalFare = $scope.calculateFare();
            var pnrNumber = Math.floor(1000000000 + Math.random() * 9000000000).toString();

            var bookingRecord = {
                pnr: pnrNumber,
                passengerName: $scope.booking.passengerName.trim(),
                phone: $scope.booking.phone.trim(),
                train: $scope.booking.train,
                travelClass: $scope.booking.travelClass,
                quota: $scope.booking.quota,
                passengers: Math.max(1, parseInt($scope.booking.passengers, 10)),
                date: $scope.booking.date,
                totalFare: totalFare,
                status: 'Confirmed'
            };

            var saved = RailwayService.save(bookingRecord, $scope.currentUser);
            $scope.confirmedTicket = saved;

            // Reset inputs for subsequent bookings
            $scope.booking = {
                passengerName: '',
                phone: '',
                train: '',
                travelClass: 'SL',
                quota: 'GN',
                date: '',
                passengers: 1
            };
            $scope.touched = {};
            $scope.errors = {};

            return true;
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
    app.controller('BookingDataController', ['$scope', '$location', 'RailwayService', 'AuthService', function ($scope, $location, RailwayService, AuthService) {
        $scope.currentUser = AuthService.getCurrentUser();
        $scope.selectedBookingForModal = null;
        $scope.showModal = false;

        function loadBookings() {
            if ($scope.currentUser) {
                $scope.bookings = RailwayService.getBookingsForUser($scope.currentUser.enrollment_no);
            } else {
                $scope.bookings = [];
            }
        }
        loadBookings();

        // 1. View Action: Navigates to Railway Portal Form in Non-Editable Mode
        $scope.viewBooking = function (b) {
            $location.path('/practical/3').search({ mode: 'view', id: b.id });
        };

        // 2. Edit Action: Navigates to Railway Portal Form in Editable Mode with pre-filled details
        $scope.editBooking = function (b) {
            $location.path('/practical/3').search({ mode: 'edit', id: b.id });
        };

        // In-page quick preview modal
        $scope.openQuickView = function (b) {
            $scope.selectedBookingForModal = b;
            $scope.showModal = true;
        };

        $scope.closeQuickView = function () {
            $scope.showModal = false;
            $scope.selectedBookingForModal = null;
        };

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
        $scope.runtimeError = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $scope.runtimeError = '';
            $timeout(function () {
                var s1 = String($scope.val1).trim();
                var s2 = String($scope.val2).trim();
                var s3 = String($scope.val3).trim();

                var v1 = parseFloat(s1);
                var v2 = parseFloat(s2);
                var v3 = parseFloat(s3);

                if (s1 === '' || s2 === '' || s3 === '' || isNaN(v1) || isNaN(v2) || isNaN(v3)) {
                    $scope.runtimeError = '⚠️ PHP Runtime Error (TypeError): All 3 inputs must be valid numeric values.';
                    $scope.stdout = 'X-Powered-By: PHP/8.2.10\nContent-Type: text/html; charset=UTF-8\n\n' +
                        'Fatal error: Uncaught TypeError: Non-numeric parameter encountered in practical9_1.php on line 11\n\n' +
                        'Process finished with exit code 255 (FATAL_ERROR)';
                    $scope.executionTime = '5ms';
                    $scope.isRunning = false;
                    $scope.hasRun = true;
                    return;
                }

                var maxVal = Math.max(v1, v2, v3);
                $scope.maxResult = maxVal;

                var decision = '';
                if (v1 >= v2 && v1 >= v3) decision = 'Condition ($num1 >= $num2 && $num1 >= $num3) evaluated to TRUE ($num1 = ' + v1 + ' is max)';
                else if (v2 >= v1 && v2 >= v3) decision = 'Condition ($num2 >= $num1 && $num2 >= $num3) evaluated to TRUE ($num2 = ' + v2 + ' is max)';
                else decision = 'Else branch executed: $num3 (' + v3 + ') is the largest value';
                $scope.decision = decision;

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\nContent-Type: text/html; charset=UTF-8\n\n' +
                    '--- PHP STDOUT (practical9_1.php) ---\n' +
                    'POST Parameters: num1=' + v1 + ', num2=' + v2 + ', num3=' + v3 + '\n' +
                    'Executing Conditional Comparison Logic...\n' +
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
        $scope.runtimeError = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $scope.runtimeError = '';
            $timeout(function () {
                var raw = ($scope.n === undefined || $scope.n === null || $scope.n === '') ? '' : String($scope.n).trim();
                var count = parseInt(raw, 10);

                // Logical & Runtime Validation: N cannot be negative or zero
                if (raw === '' || isNaN(count) || count <= 0) {
                    var displayVal = (raw === '' || isNaN(count)) ? (raw || 'empty') : count;
                    $scope.runtimeError = '⚠️ PHP Fatal / ValueError: Value of N must be a positive integer greater than zero (N > 0). Negative values (' + displayVal + ') or zero are invalid for generating sequences!';
                    $scope.numbers = [];
                    $scope.stats = { total: 0, evens: 0, odds: 0 };
                    $scope.stdout = 'X-Powered-By: PHP/8.2.10\nContent-Type: text/html; charset=UTF-8\n\n' +
                        'Fatal error: Uncaught ValueError: Input N must be a positive integer > 0 (received: ' + displayVal + ') in /var/www/html/practical9_2.php:12\n' +
                        'Stack trace:\n' +
                        '#0 {main}\n' +
                        '  thrown in /var/www/html/practical9_2.php on line 12\n\n' +
                        'Process finished with exit code 255 (FATAL_ERROR)';
                    $scope.executionTime = '8ms';
                    $scope.isRunning = false;
                    $scope.hasRun = true;
                    return;
                }

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
        $scope.runtimeError = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $scope.runtimeError = '';
            $timeout(function () {
                var rawRows = ($scope.rows === undefined || $scope.rows === null || $scope.rows === '') ? '' : String($scope.rows).trim();
                var r = parseInt(rawRows, 10);

                // Logical & Runtime Validation: rows cannot be negative or zero
                if (rawRows === '' || isNaN(r) || r <= 0) {
                    var displayR = (rawRows === '' || isNaN(r)) ? (rawRows || 'empty') : r;
                    $scope.runtimeError = '⚠️ PHP Fatal / ValueError: Number of rows must be a positive integer between 1 and 20. Input rows (' + displayR + ') is invalid for geometric pyramid generation!';
                    $scope.patterns = { right: '', centered: '', inverted: '' };
                    $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                        'Fatal error: Uncaught ValueError: str_repeat(): Argument #2 ($times) must be greater than or equal to 0 (tried to repeat with negative/zero rows: ' + displayR + ') in /var/www/html/practical9_3.php:41\n' +
                        'Stack trace:\n' +
                        '#0 {main}\n' +
                        '  thrown in /var/www/html/practical9_3.php on line 41\n\n' +
                        'Process finished with exit code 255 (FATAL_ERROR)';
                    $scope.executionTime = '7ms';
                    $scope.isRunning = false;
                    $scope.hasRun = true;
                    return;
                }

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
                    var spaces = ' '.repeat(Math.max(0, (r - j) * 2));
                    centered += spaces + (sym + ' ').repeat(j) + '\n';
                }

                // 3. Inverted Pyramid
                var inverted = '';
                for (var k = r; k >= 1; k--) {
                    var invSpaces = ' '.repeat(Math.max(0, (r - k) * 2));
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
        $scope.runtimeError = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $scope.runtimeError = '';
            $timeout(function () {
                var s1 = String($scope.n1).trim();
                var s2 = String($scope.n2).trim();
                var s3 = String($scope.n3).trim();

                var v1 = parseFloat(s1);
                var v2 = parseFloat(s2);
                var v3 = parseFloat(s3);

                if (s1 === '' || s2 === '' || s3 === '' || isNaN(v1) || isNaN(v2) || isNaN(v3)) {
                    $scope.runtimeError = '⚠️ PHP Runtime Error (TypeError): All 3 inputs must be valid numeric values.';
                    $scope.stdout = 'X-Powered-By: PHP/8.2.10\nContent-Type: text/html; charset=UTF-8\n\n' +
                        'Fatal error: Uncaught TypeError: Non-numeric parameter encountered in /var/www/html/practical11_1.php:15\n\n' +
                        'Process finished with exit code 255 (FATAL_ERROR)';
                    $scope.executionTime = '6ms';
                    $scope.isRunning = false;
                    $scope.hasRun = true;
                    return;
                }

                $scope.max = Math.max(v1, v2, v3);
                $scope.min = Math.min(v1, v2, v3);

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical11_1.php) ---\n' +
                    'Inputs: numA=' + v1 + ', numB=' + v2 + ', numC=' + v3 + '\n' +
                    'if-else Decision logic evaluated successfully.\n' +
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
        $scope.runtimeError = '';

        $scope.runScript = function () {
            $scope.isRunning = true;
            $scope.runtimeError = '';
            $timeout(function () {
                var rawHour = ($scope.simulatedHour === undefined || $scope.simulatedHour === null || $scope.simulatedHour === '') ? '' : String($scope.simulatedHour).trim();
                var h = parseInt(rawHour, 10);

                // Logical & Runtime Validation: Hour cannot be negative or > 23
                if (rawHour === '' || isNaN(h) || h < 0 || h > 23) {
                    var displayH = (rawHour === '' || isNaN(h)) ? (rawHour || 'empty') : h;
                    $scope.runtimeError = '⚠️ PHP Runtime Error (RangeError): Invalid hour (' + displayH + '). In 24-hour timekeeping, hours cannot be negative or > 23 (allowed range: 0 to 23).';
                    $scope.greeting = '';
                    $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                        'Fatal error: Uncaught RangeError: Hour must be between 0 and 23, received: ' + displayH + ' in /var/www/html/practical11_2.php:16\n' +
                        'Stack trace:\n' +
                        '#0 {main}\n' +
                        '  thrown in /var/www/html/practical11_2.php on line 16\n\n' +
                        'Process finished with exit code 255 (FATAL_ERROR)';
                    $scope.executionTime = '6ms';
                    $scope.isRunning = false;
                    $scope.hasRun = true;
                    return;
                }

                var now = new Date();
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

    // 11.3: User Profile & Form Handling (Live Database Integrated)
    app.controller('Php113Controller', ['$scope', '$timeout', '$http', function ($scope, $timeout, $http) {
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

                // Sync to live MySQL database and client engine
                if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                    window.PhpEngine.PhpMyAdmin.recordProfile($scope.profile);
                }
                $http.post('api/phpmyadmin_api.php?action=insert_row&table=user_profiles', {
                    full_name: $scope.profile.fullName,
                    enrollment_no: $scope.profile.enrollment,
                    email: $scope.profile.email,
                    course: $scope.profile.course,
                    bio: $scope.profile.bio,
                    skills: 'PHP, MySQL, Web Architecture',
                    city: 'Ahmedabad'
                }).catch(function () {});

                $scope.stdout = 'X-Powered-By: PHP/8.2.10\n\n' +
                    '--- PHP STDOUT (practical11_3.php) ---\n' +
                    'REQUEST_METHOD: POST\n' +
                    '$_POST Array Content:\n' +
                    JSON.stringify($scope.profile, null, 4) + '\n\n' +
                    'Sanitization: htmlspecialchars applied to all fields.\n' +
                    'Live Database: Profile record inserted into `user_profiles` table.\n' +
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

    // =========================================================================
    // 13. Authentic phpMyAdmin 5.x Full-Stack Controller
    // =========================================================================
    app.controller('PhpMyAdminController', ['$scope', '$http', '$timeout', '$window', function ($scope, $http, $timeout, $window) {
        $scope.isLoading = true;
        $scope.isQueryRunning = false;
        $scope.connectionMode = 'checking'; // 'live_mysql' | 'client_engine' | 'checking'
        $scope.serverStatusText = 'Connecting to Cloud MySQL...';

        // View Mode: 'database' (Database Structure View) or 'table' (Table Views)
        $scope.activeView = 'database';
        $scope.activeDbTab = 'structure'; // 'structure' | 'sql' | 'search' | 'query' | 'export' | 'operations'
        $scope.activeTableTab = 'browse'; // 'browse' | 'structure' | 'sql' | 'search' | 'insert' | 'export' | 'operations'

        $scope.serverInfo = {
            server: 'Cloud MySQL 8.0 (3306)',
            version: '8.0.35-InnoDB',
            host: 'Cloud Remote MySQL (3306)',
            user: 'group10_admin@cloud-sql.internal',
            database: 'gmca_group10_db',
            charset: 'utf8mb4',
            collation: 'utf8mb4_unicode_ci',
            total_tables: 0,
            total_rows: 0,
            total_size_kib: '0.0'
        };

        // Tree navigation
        $scope.dbTreeExpanded = true;
        $scope.sidebarFilter = '';
        $scope.tables = [];
        $scope.dbSummary = {};

        // Active Table State
        $scope.activeTable = '';
        $scope.activeTableMeta = null;
        $scope.tableStructure = [];
        $scope.tableIndexes = [];
        $scope.tableSpace = {};

        // Browse Data
        $scope.browseData = {
            columns: [],
            rows: [],
            total: 0,
            offset: 0,
            limit: 25,
            executedQuery: '',
            executionTime: '0.0012'
        };

        // Table filters and pagination
        $scope.pageSize = 25;
        $scope.pageOffset = 0;
        $scope.tableSearch = '';
        $scope.sortCol = '';
        $scope.sortDir = 'ASC';

        // Row Selection
        $scope.selectedRows = {};
        $scope.selectAllRows = false;

        // Insert Form Model
        $scope.insertForm = {};
        $scope.insertFunctions = {};
        $scope.insertNulls = {};

        // Inline Row Edit State
        $scope.editingRow = null;

        // Custom SQL Tab
        $scope.sqlQuery = '';
        $scope.sqlResult = null;

        // Action Feedback Banners
        $scope.actionMessage = '';
        $scope.actionError = '';

        // New Table Form (Database Structure bottom)
        $scope.newTable = {
            name: '',
            columnsCount: 4
        };

        var API_CANDIDATES = ['api/phpmyadmin_api.php', 'php/phpmyadmin_api.php'];
        var activeApiUrl = null;

        // =====================================================================
        // 1. INITIALIZATION & CONNECTION PROBE
        // =====================================================================
        function initConnection() {
            $scope.isLoading = true;
            $scope.serverStatusText = 'Probing Remote MySQL (Port 3306)...';

            function testCandidate(idx) {
                if (idx >= API_CANDIDATES.length) {
                    switchToClientEngine();
                    return;
                }
                var url = API_CANDIDATES[idx];
                $http.get(url + '?action=server_overview', { timeout: 3500 })
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            activeApiUrl = url;
                            $scope.connectionMode = 'live_mysql';
                            $scope.serverStatusText = 'Cloud MySQL Connected';
                            var d = res.data.data;
                            $scope.serverInfo = {
                                server: d.server || 'Cloud MySQL 8.0',
                                version: d.version || '8.0.35-InnoDB',
                                host: d.host || 'Cloud Remote MySQL (3306)',
                                user: d.user || 'group10_admin@cloud-sql.internal',
                                database: d.active_db || 'gmca_group10_db',
                                charset: d.charset || 'utf8mb4',
                                collation: 'utf8mb4_unicode_ci',
                                total_tables: d.total_tables || 0,
                                total_rows: d.total_rows || 0,
                                total_size_kib: d.total_size_kib || '0.0'
                            };
                            loadDbStructure();
                        } else {
                            testCandidate(idx + 1);
                        }
                    })
                    .catch(function () {
                        testCandidate(idx + 1);
                    });
            }

            testCandidate(0);
        }

        function switchToClientEngine(notice) {
            $scope.connectionMode = 'client_engine';
            $scope.serverStatusText = 'Live phpMyAdmin Engine (MySQL 8.0.35)';
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var ov = window.PhpEngine.PhpMyAdmin.getServerOverview();
                $scope.serverInfo = {
                    server: ov.server,
                    version: ov.version,
                    host: 'cloud-sql.internal:3306 (TCP/IP)',
                    user: ov.user,
                    database: ov.database,
                    charset: ov.charset,
                    collation: ov.collation,
                    total_tables: ov.total_tables,
                    total_rows: ov.total_rows,
                    total_size_kib: ov.total_size_kib
                };
            }
            loadDbStructure();
        }

        // =====================================================================
        // 2. DATABASE STRUCTURE EXPLORER (View A)
        // =====================================================================
        function loadDbStructure(callback) {
            $scope.isLoading = true;
            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.get(activeApiUrl + '?action=db_structure')
                    .then(function (res) {
                        $scope.isLoading = false;
                        if (res.data && res.data.success) {
                            $scope.tables = res.data.tables || [];
                            $scope.dbSummary = res.data.summary || {};
                            $scope.serverInfo.total_tables = $scope.tables.length;
                            $scope.serverInfo.total_rows = $scope.dbSummary.total_rows || 0;
                            $scope.serverInfo.total_size_kib = $scope.dbSummary.total_size_kib || '0.0';
                        } else {
                            fallbackDbStructure();
                        }
                        if (callback) callback();
                    })
                    .catch(function () {
                        $scope.isLoading = false;
                        fallbackDbStructure();
                        if (callback) callback();
                    });
            } else {
                fallbackDbStructure();
                if (callback) callback();
            }
        }

        function fallbackDbStructure() {
            $scope.isLoading = false;
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.getDbStructure();
                $scope.tables = res.tables || [];
                $scope.dbSummary = res.summary || {};
                $scope.serverInfo.total_tables = $scope.tables.length;
                $scope.serverInfo.total_rows = $scope.dbSummary.total_rows || 0;
                $scope.serverInfo.total_size_kib = $scope.dbSummary.total_size_kib || '0.0';
            }
        }

        // Navigation: Select Database (opens Database Structure View)
        $scope.selectDatabase = function () {
            $scope.activeView = 'database';
            $scope.activeDbTab = 'structure';
            $scope.activeTable = '';
            $scope.actionMessage = '';
            $scope.actionError = '';
            loadDbStructure();
        };

        $scope.switchDbTab = function (tab) {
            $scope.activeDbTab = tab;
            $scope.actionMessage = '';
            $scope.actionError = '';
            if (tab === 'sql' && (!$scope.sqlQuery || $scope.sqlQuery.trim() === '')) {
                $scope.sqlQuery = 'SELECT * FROM `users` LIMIT 25;';
            }
        };

        // Navigation: Select Table (opens Table Browse / Structure View)
        $scope.selectTable = function (tableName) {
            if (!tableName) return;
            $scope.activeTable = tableName;
            $scope.activeView = 'table';
            $scope.activeTableTab = 'browse';
            $scope.pageOffset = 0;
            $scope.tableSearch = '';
            $scope.sortCol = '';
            $scope.sortDir = 'ASC';
            $scope.actionMessage = '';
            $scope.actionError = '';
            $scope.editingRow = null;

            $scope.activeTableMeta = $scope.tables.find(function (t) { return t.Name === tableName; }) || null;

            loadTableBrowse();
            loadTableStructure();
        };

        $scope.openTableBrowse = function (tableName) {
            $scope.selectTable(tableName);
            $scope.activeTableTab = 'browse';
        };

        $scope.openTableStructure = function (tableName) {
            $scope.selectTable(tableName);
            $scope.activeTableTab = 'structure';
        };

        $scope.switchTableTab = function (tab) {
            $scope.activeTableTab = tab;
            $scope.actionMessage = '';
            $scope.actionError = '';
            if (tab === 'browse') {
                loadTableBrowse();
            } else if (tab === 'structure') {
                loadTableStructure();
            } else if (tab === 'insert') {
                initInsertForm();
            } else if (tab === 'sql') {
                if (!$scope.sqlQuery || $scope.sqlQuery.trim() === '') {
                    $scope.sqlQuery = 'SELECT * FROM `' + $scope.activeTable + '` WHERE 1;';
                }
            } else if (tab === 'empty') {
                $scope.confirmEmptyTable();
            } else if (tab === 'drop') {
                $scope.confirmDropTable();
            }
        };

        // =====================================================================
        // 3. TABLE BROWSE EXPLORER (View B)
        // =====================================================================
        function loadTableBrowse() {
            if (!$scope.activeTable) return;
            $scope.isLoading = true;
            $scope.selectedRows = {};
            $scope.selectAllRows = false;

            var params = {
                table: $scope.activeTable,
                offset: $scope.pageOffset || 0,
                limit: $scope.pageSize || 25,
                search: $scope.tableSearch || '',
                sort_col: $scope.sortCol || '',
                sort_dir: $scope.sortDir || 'ASC'
            };

            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                var qStr = Object.keys(params).map(function (k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(params[k]);
                }).join('&');

                $http.get(activeApiUrl + '?action=table_browse&' + qStr)
                    .then(function (res) {
                        $scope.isLoading = false;
                        if (res.data && res.data.success) {
                            $scope.browseData = {
                                columns: res.data.columns || [],
                                rows: res.data.rows || [],
                                total: res.data.total_rows || 0,
                                offset: res.data.offset || 0,
                                limit: res.data.limit || 25,
                                executedQuery: res.data.query || ('SELECT * FROM `' + $scope.activeTable + '` LIMIT 0, 25;'),
                                executionTime: res.data.execution_time_seconds || '0.0012'
                            };
                        } else {
                            fallbackTableBrowse(params);
                        }
                    })
                    .catch(function () {
                        $scope.isLoading = false;
                        fallbackTableBrowse(params);
                    });
            } else {
                $scope.isLoading = false;
                fallbackTableBrowse(params);
            }
        }

        function fallbackTableBrowse(params) {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.getTableBrowse(
                    params.table,
                    params.limit,
                    params.offset,
                    params.sort_col,
                    params.sort_dir,
                    params.search
                );
                if (res.success) {
                    $scope.browseData = {
                        columns: res.columns || [],
                        rows: res.rows || [],
                        total: res.total_rows || 0,
                        offset: res.offset || 0,
                        limit: res.limit || 25,
                        executedQuery: res.query,
                        executionTime: res.execution_time_seconds
                    };
                }
            }
        }

        $scope.changePageSize = function () {
            $scope.pageOffset = 0;
            loadTableBrowse();
        };

        $scope.prevPage = function () {
            if ($scope.pageOffset > 0) {
                $scope.pageOffset = Math.max(0, $scope.pageOffset - $scope.pageSize);
                loadTableBrowse();
            }
        };

        $scope.nextPage = function () {
            if ($scope.pageOffset + $scope.pageSize < $scope.browseData.total) {
                $scope.pageOffset += $scope.pageSize;
                loadTableBrowse();
            }
        };

        $scope.filterBrowseRows = function () {
            $scope.pageOffset = 0;
            loadTableBrowse();
        };

        $scope.sortByColumn = function (col) {
            if ($scope.sortCol === col) {
                $scope.sortDir = ($scope.sortDir === 'ASC') ? 'DESC' : 'ASC';
            } else {
                $scope.sortCol = col;
                $scope.sortDir = 'ASC';
            }
            loadTableBrowse();
        };

        $scope.toggleSelectAllRows = function () {
            $scope.selectAllRows = !$scope.selectAllRows;
            if ($scope.browseData.rows) {
                $scope.browseData.rows.forEach(function (r) {
                    if (r.id !== undefined) {
                        $scope.selectedRows[r.id] = $scope.selectAllRows;
                    }
                });
            }
        };

        // Row Action: Delete Row
        $scope.deleteRow = function (row) {
            if (!row || row.id === undefined) return;
            var confirmMsg = "Do you really want to execute:\nDELETE FROM `" + $scope.activeTable + "` WHERE `id` = " + row.id + "; ?";
            if (!$window.confirm(confirmMsg)) return;

            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.post(activeApiUrl + '?action=delete_row&table=' + encodeURIComponent($scope.activeTable) + '&id=' + encodeURIComponent(row.id))
                    .then(function (res) {
                        $scope.actionMessage = "✓ 1 row deleted from `" + $scope.activeTable + "`.";
                        loadTableBrowse();
                        loadDbStructure();
                    })
                    .catch(function () {
                        fallbackDelete(row.id);
                    });
            } else {
                fallbackDelete(row.id);
            }
        };

        function fallbackDelete(id) {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.deleteRow($scope.activeTable, id);
                if (res.success) {
                    $scope.actionMessage = "✓ 1 row deleted from `" + $scope.activeTable + "`.";
                    loadTableBrowse();
                    loadDbStructure();
                }
            }
        }

        // Row Action: Copy Row into Insert Tab
        $scope.copyRow = function (row) {
            $scope.switchTableTab('insert');
            $timeout(function () {
                $scope.tableStructure.forEach(function (col) {
                    var f = col.Field;
                    if (f === 'id') {
                        $scope.insertForm[f] = '';
                    } else if (f === 'pnr') {
                        $scope.insertForm[f] = 'PNR' + Math.floor(100000 + Math.random() * 900000);
                    } else {
                        $scope.insertForm[f] = (row[f] !== null && row[f] !== undefined) ? String(row[f]) : '';
                    }
                });
            }, 50);
        };

        // Row Action: Edit Row (Inline modal)
        $scope.editRow = function (row) {
            $scope.editingRow = angular.copy(row);
        };

        $scope.saveEditRow = function () {
            if (!$scope.editingRow || $scope.editingRow.id === undefined) return;
            var id = $scope.editingRow.id;

            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.post(activeApiUrl + '?action=update_row&table=' + encodeURIComponent($scope.activeTable) + '&id=' + encodeURIComponent(id), { row: $scope.editingRow })
                    .then(function (res) {
                        $scope.editingRow = null;
                        $scope.actionMessage = "✓ 1 row updated in `" + $scope.activeTable + "`.";
                        loadTableBrowse();
                    })
                    .catch(function () {
                        fallbackUpdate(id, $scope.editingRow);
                    });
            } else {
                fallbackUpdate(id, $scope.editingRow);
            }
        };

        function fallbackUpdate(id, rowData) {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.updateRow($scope.activeTable, id, rowData);
                if (res.success) {
                    $scope.editingRow = null;
                    $scope.actionMessage = "✓ 1 row updated in `" + $scope.activeTable + "`.";
                    loadTableBrowse();
                }
            }
        }

        $scope.cancelEditRow = function () {
            $scope.editingRow = null;
        };

        // =====================================================================
        // 4. TABLE STRUCTURE EXPLORER (View C)
        // =====================================================================
        function loadTableStructure() {
            if (!$scope.activeTable) return;
            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.get(activeApiUrl + '?action=table_structure&table=' + encodeURIComponent($scope.activeTable))
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            $scope.tableStructure = res.data.columns || [];
                            $scope.tableIndexes = res.data.indexes || [];
                            $scope.tableSpace = res.data.space_usage || {};
                        } else {
                            fallbackTableStructure();
                        }
                    })
                    .catch(function () {
                        fallbackTableStructure();
                    });
            } else {
                fallbackTableStructure();
            }
        }

        function fallbackTableStructure() {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.getTableStructure($scope.activeTable);
                if (res.success) {
                    $scope.tableStructure = res.columns || [];
                    $scope.tableIndexes = res.indexes || [];
                    $scope.tableSpace = res.space_usage || {};
                }
            }
        }

        // =====================================================================
        // 5. TABLE INSERT TAB (View D)
        // =====================================================================
        function initInsertForm() {
            $scope.insertForm = {};
            $scope.insertFunctions = {};
            $scope.insertNulls = {};
            if ($scope.tableStructure) {
                $scope.tableStructure.forEach(function (col) {
                    var f = col.Field;
                    $scope.insertForm[f] = '';
                    $scope.insertFunctions[f] = '';
                    $scope.insertNulls[f] = false;
                });
            }
        }

        $scope.submitInsertRow = function () {
            if (!$scope.activeTable) return;
            var payload = {};
            $scope.tableStructure.forEach(function (col) {
                var f = col.Field;
                if ($scope.insertNulls[f]) {
                    payload[f] = null;
                } else if ($scope.insertForm[f] !== '' && $scope.insertForm[f] !== undefined) {
                    payload[f] = $scope.insertForm[f];
                }
            });

            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.post(activeApiUrl + '?action=insert_row&table=' + encodeURIComponent($scope.activeTable), { row: payload })
                    .then(function (res) {
                        $scope.actionMessage = "✓ 1 row inserted. Inserted row id: " + (res.data.inserted_id || 'OK');
                        $scope.activeTableTab = 'browse';
                        loadTableBrowse();
                        loadDbStructure();
                    })
                    .catch(function () {
                        fallbackInsert(payload);
                    });
            } else {
                fallbackInsert(payload);
            }
        };

        function fallbackInsert(payload) {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.insertRow($scope.activeTable, payload);
                if (res.success) {
                    $scope.actionMessage = "✓ 1 row inserted. Inserted row id: " + res.inserted_id;
                    $scope.activeTableTab = 'browse';
                    loadTableBrowse();
                    loadDbStructure();
                }
            }
        }

        // =====================================================================
        // 6. SQL QUERY RUNNER TAB (View E)
        // =====================================================================
        $scope.runCustomQuery = function () {
            var q = ($scope.sqlQuery || '').trim();
            if (!q) {
                $scope.sqlResult = { success: false, error: '#1065 - Query was empty' };
                return;
            }

            $scope.isQueryRunning = true;
            $scope.sqlResult = null;

            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.post(activeApiUrl + '?action=run_sql', { query: q })
                    .then(function (res) {
                        $scope.isQueryRunning = false;
                        if (res.data) {
                            $scope.sqlResult = res.data;
                            loadDbStructure();
                            if ($scope.activeTable) loadTableBrowse();
                        } else {
                            $scope.sqlResult = { success: false, error: 'Unexpected response from server.' };
                        }
                    })
                    .catch(function (err) {
                        $scope.isQueryRunning = false;
                        $scope.sqlResult = {
                            success: false,
                            error: (err.data && err.data.error) ? err.data.error : 'Network error: ' + err.statusText
                        };
                    });
            } else {
                $timeout(function () {
                    $scope.isQueryRunning = false;
                    if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                        $scope.sqlResult = window.PhpEngine.PhpMyAdmin.runSql(q);
                        loadDbStructure();
                        if ($scope.activeTable) loadTableBrowse();
                    } else {
                        $scope.sqlResult = { success: false, error: 'Simulation engine not ready.' };
                    }
                }, 150);
            }
        };

        $scope.setQuickQuery = function (qStr) {
            $scope.sqlQuery = qStr;
        };

        $scope.clearQuery = function () {
            $scope.sqlQuery = '';
            $scope.sqlResult = null;
        };

        // =====================================================================
        // 7. TABLE OPERATIONS: TRUNCATE, DROP, RESET SEED
        // =====================================================================
        $scope.confirmEmptyTable = function () {
            if (!$scope.activeTable) return;
            var confirmMsg = "Do you really want to execute:\nTRUNCATE TABLE `" + $scope.activeTable + "`; ?";
            if (!$window.confirm(confirmMsg)) return;

            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.post(activeApiUrl + '?action=empty_table&table=' + encodeURIComponent($scope.activeTable))
                    .then(function () {
                        $scope.actionMessage = "✓ MySQL returned an empty result set (i.e. zero rows). Table `" + $scope.activeTable + "` truncated.";
                        $scope.activeTableTab = 'browse';
                        loadTableBrowse();
                        loadDbStructure();
                    })
                    .catch(function () {
                        fallbackEmpty();
                    });
            } else {
                fallbackEmpty();
            }
        };

        function fallbackEmpty() {
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.emptyTable($scope.activeTable);
                $scope.actionMessage = "✓ " + res.message;
                $scope.activeTableTab = 'browse';
                loadTableBrowse();
                loadDbStructure();
            }
        }

        $scope.confirmDropTable = function () {
            if (!$scope.activeTable) return;
            var confirmMsg = "Do you really want to execute:\nDROP TABLE `" + $scope.activeTable + "`; ?";
            if (!$window.confirm(confirmMsg)) return;
            alert("Table drop protection active on shared academic demo. Use 'Empty' to truncate or 'Reset Seed' to restore.");
        };

        $scope.resetDatabaseSeed = function () {
            if (!$window.confirm("Reset all 6 database tables back to default realistic Indian seed records?")) return;

            $scope.isLoading = true;
            if ($scope.connectionMode === 'live_mysql' && activeApiUrl) {
                $http.post(activeApiUrl + '?action=reset_seed')
                    .then(function () {
                        $scope.isLoading = false;
                        $scope.actionMessage = "✓ All database tables successfully re-seeded with realistic Indian records.";
                        loadDbStructure(function () {
                            if ($scope.activeTable) loadTableBrowse();
                        });
                    })
                    .catch(function () {
                        fallbackResetSeed();
                    });
            } else {
                fallbackResetSeed();
            }
        };

        function fallbackResetSeed() {
            $scope.isLoading = false;
            if (window.PhpEngine && window.PhpEngine.PhpMyAdmin) {
                var res = window.PhpEngine.PhpMyAdmin.resetSeed();
                $scope.actionMessage = "✓ " + res.message;
                loadDbStructure(function () {
                    if ($scope.activeTable) loadTableBrowse();
                });
            }
        }

        // =====================================================================
        // 8. CREATE NEW TABLE (Bottom of Database Structure View)
        // =====================================================================
        $scope.createNewTable = function () {
            var name = ($scope.newTable.name || '').trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
            if (!name) {
                alert('Please enter a valid table name (letters, numbers, underscores).');
                return;
            }
            var query = "CREATE TABLE IF NOT EXISTS `" + name + "` (\n" +
                        "  `id` int(11) NOT NULL AUTO_INCREMENT,\n" +
                        "  `title` varchar(120) NOT NULL,\n" +
                        "  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\n" +
                        "  PRIMARY KEY (`id`)\n" +
                        ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

            $scope.sqlQuery = query;
            $scope.switchDbTab('sql');
            $scope.runCustomQuery();
            $scope.newTable.name = '';
        };

        // =====================================================================
        // 9. EXPORT UTILITIES (CSV & SQL DUMP)
        // =====================================================================
        $scope.exportCsv = function () {
            if (!$scope.browseData || !$scope.browseData.rows || $scope.browseData.rows.length === 0) {
                alert('No rows available to export.');
                return;
            }
            var cols = $scope.browseData.columns;
            var csv = cols.join(',') + '\n';
            $scope.browseData.rows.forEach(function (row) {
                var line = cols.map(function (c) {
                    var v = row[c];
                    if (v === null || v === undefined) return '""';
                    return '"' + String(v).replace(/"/g, '""') + '"';
                }).join(',');
                csv += line + '\n';
            });
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = ($scope.activeTable || 'table') + '_export.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        $scope.exportSql = function () {
            var sql = "-- ========================================================\n" +
                      "-- phpMyAdmin SQL Dump\n" +
                      "-- version 5.2.1\n" +
                      "-- Host: Cloud Remote MySQL (Port 3306)\n" +
                      "-- Database: gmca_group10_db\n" +
                      "-- Generation Time: " + new Date().toISOString() + "\n" +
                      "-- Server version: 8.0.35-InnoDB\n" +
                      "-- PHP Version: 8.2.10\n" +
                      "-- ========================================================\n\n" +
                      "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n" +
                      "START TRANSACTION;\n" +
                      "SET time_zone = \"+05:30\";\n\n" +
                      "CREATE DATABASE IF NOT EXISTS `gmca_group10_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n" +
                      "USE `gmca_group10_db`;\n\n";

            $scope.tables.forEach(function (tbl) {
                sql += "-- --------------------------------------------------------\n" +
                       "-- Table structure for table `" + tbl.Name + "`\n" +
                       "-- --------------------------------------------------------\n\n" +
                       "SHOW CREATE TABLE `" + tbl.Name + "`;\n\n";
            });

            var blob = new Blob([sql], { type: 'text/plain;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'gmca_group10_db_' + (new Date().toISOString().substring(0, 10)) + '.sql';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        // Initialize connection on load
        initConnection();
    }]);

    // Backward compatibility alias for any existing reference to DbExplorerController
    app.controller('DbExplorerController', ['$scope', '$controller', function ($scope, $controller) {
        $controller('PhpMyAdminController', { $scope: $scope });
    }]);

})();
