<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Group 10 — Indian Railways Portal & Practicals Hub | GMCA Laravel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #000080;
            --navy-dark: #00004d;
            --navy-light: #1E3A8A;
            --saffron: #FF9933;
            --saffron-dark: #E65100;
            --green: #138808;
            --green-dark: #15803D;
            --ashoka-blue: #000080;
            --bg-light: #F8FAFC;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --card-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Government / Tricolor Bar */
        .flag-strip {
            height: 4px;
            background: linear-gradient(90deg, #FF9933 33.3%, #FFFFFF 33.3%, #FFFFFF 66.6%, #138808 66.6%);
        }

        /* Header Bar */
        .top-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-dark) 100%);
            color: #FFFFFF;
            padding: 12px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 128, 0.2);
        }

        .top-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .branding-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .rail-emblem {
            width: 44px;
            height: 44px;
            background: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--saffron);
        }

        .brand-title {
            font-size: 19px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.3px;
        }

        .brand-subtitle {
            font-size: 12px;
            color: #CBD5E1;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-academic {
            background: rgba(255, 153, 51, 0.2);
            color: #FED7AA;
            border: 1px solid rgba(255, 153, 51, 0.4);
            padding: 1px 7px;
            border-radius: 4px;
            font-size: 10.5px;
            font-weight: 600;
        }

        /* System & User Switchers in Header */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .system-pill-dropdown, .user-pill-dropdown {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 6px 12px;
            color: #FFFFFF;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .system-pill-dropdown:hover, .user-pill-dropdown:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: var(--saffron);
        }

        /* Dropdown Menus */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 6px;
            background: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--border-color);
            min-width: 270px;
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-header {
            background: #F1F5F9;
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-item {
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-main);
            text-decoration: none;
            font-size: 13px;
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.15s ease;
        }

        .dropdown-item:hover {
            background: #F8FAFC;
            color: var(--navy);
        }

        .dropdown-item.active {
            background: #EFF6FF;
            font-weight: 600;
            color: var(--navy);
        }

        /* Navigation Bar */
        .main-navbar {
            background: #FFFFFF;
            border-bottom: 2px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 52px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            height: 100%;
        }

        .nav-item {
            height: 100%;
            display: flex;
            align-items: center;
            position: relative;
        }

        .nav-link {
            padding: 0 14px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #334155;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-item:hover > .nav-link {
            color: var(--navy);
            background: #F8FAFC;
            border-bottom-color: var(--saffron);
        }

        .nav-link.active {
            color: var(--navy);
            border-bottom-color: var(--navy);
            font-weight: 700;
        }

        .nav-subnav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 0 0 8px 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            min-width: 240px;
            z-index: 1000;
            list-style: none;
            padding: 6px 0;
        }

        .nav-item:hover .nav-subnav {
            display: block;
        }

        .nav-subnav a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 16px;
            color: #334155;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-subnav a:hover {
            background: #EFF6FF;
            color: var(--navy);
            padding-left: 20px;
        }

        .subnav-badge {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            background: #E2E8F0;
            color: #475569;
        }

        /* Active Division Notice Banner */
        .system-banner {
            background: #F0FDF4;
            border-bottom: 1px solid #BBF7D0;
            padding: 8px 24px;
            font-size: 12.5px;
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .system-banner-inner {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* Main Container */
        .page-content {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 24px auto;
            padding: 0 24px;
        }

        /* Flash Notifications */
        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .alert-success {
            background: #DCFCE7;
            border: 1px solid #86EFAC;
            color: #15803D;
        }

        .alert-error {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #B91C1C;
        }

        .alert-info {
            background: #E0F2FE;
            border: 1px solid #BAE6FD;
            color: #0369A1;
        }

        /* Card Component */
        .card {
            background: #FFFFFF;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .badge-navy { background: #E0E7FF; color: var(--navy); }
        .badge-saffron { background: #FFEDD5; color: #9A3412; }
        .badge-green { background: #DCFCE7; color: #166534; }
        .badge-admin { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
        .badge-blue { background: #EFF6FF; color: #1D4ED8; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: var(--navy);
            color: #FFFFFF;
        }
        .btn-primary:hover {
            background: var(--navy-light);
        }

        .btn-saffron {
            background: var(--saffron);
            color: #FFFFFF;
        }
        .btn-saffron:hover {
            background: var(--saffron-dark);
        }

        .btn-outline {
            background: transparent;
            border-color: var(--border-color);
            color: #334155;
        }
        .btn-outline:hover {
            background: #F1F5F9;
            color: var(--navy);
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
        }

        /* Table Styling */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .data-table th {
            background: var(--navy);
            color: #FFFFFF;
            padding: 12px 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .data-table td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--border-color);
            color: #334155;
        }

        .data-table tr:hover td {
            background-color: #F8FAFC;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 9px 12px;
            border-radius: 6px;
            border: 1px solid #CBD5E1;
            font-size: 13px;
            color: #1E293B;
            background: #FFFFFF;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(0, 0, 128, 0.1);
        }

        /* Pagination custom wrapper */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
            font-size: 13px;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 4px;
        }

        .page-item .page-link {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            background: #FFFFFF;
            color: #334155;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12.5px;
            font-weight: 500;
        }

        .page-item.active .page-link {
            background: var(--navy);
            border-color: var(--navy);
            color: #FFFFFF;
            font-weight: 700;
        }

        .page-item.disabled .page-link {
            color: #94A3B8;
            pointer-events: none;
            background: #F8FAFC;
        }

        /* Footer */
        .footer {
            background: #0F172A;
            color: #94A3B8;
            padding: 30px 24px 20px;
            margin-top: auto;
            border-top: 4px solid var(--navy);
            font-size: 13px;
        }

        .footer-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .footer-inner {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Indian Tricolor Ribbon -->
    <div class="flag-strip"></div>

    <!-- Header Bar with Government Branding, Division Selector & User Switcher -->
    <header class="top-header">
        <div class="top-header-content">
            <div class="branding-wrap">
                <div class="rail-emblem">🚆</div>
                <div>
                    <div class="brand-title">INDIAN RAILWAYS RESERVATION PORTAL</div>
                    <div class="brand-subtitle">
                        <span>Government MCA College, Maninagar</span>
                        <span class="badge-academic">Group 10 — Laravel Framework MVC</span>
                    </div>
                </div>
            </div>

            <!-- System Selector & User Switcher Controls -->
            <div class="header-actions">
                @php
                    $activeSysCode = session('active_system_code', Auth::check() && Auth::user()->assigned_system_code ? Auth::user()->assigned_system_code : 'WR-ADI');
                    $activeSys = \App\Models\RailwaySystem::where('code', $activeSysCode)->first() ?: \App\Models\RailwaySystem::first();
                    $allSys = \App\Models\RailwaySystem::all();
                    $currentUser = Auth::user();
                    $allUsersList = \App\Models\User::all();
                @endphp

                <!-- 1. System Selector (Requirement 6: Multiple Systems with Dedicated Admins) -->
                <div class="system-pill-dropdown" onclick="toggleDropdown('systemMenu')">
                    <span style="font-size: 14px;">🏢</span>
                    <div>
                        <div style="font-size: 10px; color: #FED7AA; text-transform: uppercase; font-weight: 700;">Division System</div>
                        <div style="font-weight: 700;">{{ $activeSys->division }} ({{ $activeSys->code }}) ▾</div>
                    </div>

                    <div id="systemMenu" class="dropdown-menu">
                        <div class="dropdown-header">Select Railway Division / System (Req 6)</div>
                        @foreach($allSys as $s)
                            <a href="{{ route('switch.system', $s->code) }}" class="dropdown-item {{ $s->code === $activeSys->code ? 'active' : '' }}">
                                <span>🚆</span>
                                <div>
                                    <div><strong>{{ $s->division }}</strong> ({{ $s->code }})</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">Admin: {{ $s->station_master_name }}</div>
                                </div>
                            </a>
                        @endforeach
                        <a href="{{ route('railway.systems') }}" class="dropdown-item" style="border-top: 1px solid #E2E8F0; font-weight: 700; color: var(--navy);">
                            <span>🌐</span> View All 5 Railway Systems & Details →
                        </a>
                    </div>
                </div>

                <!-- 2. User Switcher (Requirement 2 & 5: Total 5 Users, Station Master Admin) -->
                <div class="user-pill-dropdown" onclick="toggleDropdown('userMenu')">
                    @if($currentUser)
                        <img src="{{ asset($currentUser->avatar) }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="User" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--saffron);">
                        <div>
                            <div style="font-size: 10px; color: #CBD5E1;">
                                {{ $currentUser->isStationMaster() ? 'ADMIN (STATION MASTER)' : 'ACTIVE USER' }}
                            </div>
                            <div style="font-weight: 700;">{{ $currentUser->name }} ▾</div>
                        </div>
                    @else
                        <span>👤 Sign In</span>
                    @endif

                    <div id="userMenu" class="dropdown-menu" style="right: 0;">
                        <div class="dropdown-header">Total 5 Users (Req 2 & 5) — 1-Click Switch</div>
                        @foreach($allUsersList as $u)
                            <a href="{{ route('quick.login', $u->username) }}" class="dropdown-item {{ $currentUser && $currentUser->id === $u->id ? 'active' : '' }}">
                                <img src="{{ asset($u->avatar) }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                <div style="flex: 1;">
                                    <div style="font-size: 12.5px;"><strong>{{ $u->name }}</strong> ({{ $u->username }})</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        {{ $u->role }} 
                                        @if($u->isStationMaster())
                                            <span class="badge badge-admin" style="font-size: 9px; padding: 1px 4px;">Station Master</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge badge-navy" style="font-size: 10px;">{{ $u->railwayBookings()->count() }} tickets</span>
                            </a>
                        @endforeach
                        <div style="padding: 8px 12px; background: #F8FAFC; border-top: 1px solid #E2E8F0; display: flex; justify-content: space-between;">
                            <a href="{{ route('login') }}" style="font-size: 12px; color: var(--navy); text-decoration: none; font-weight: 600;">Sign In Page</a>
                            <a href="{{ route('logout') }}" style="font-size: 12px; color: #DC2626; text-decoration: none; font-weight: 600;">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="main-navbar">
        <div class="nav-container">
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <span>🏠</span> Home Hub
                    </a>
                </li>

                <!-- Indian Railways Dropdown -->
                <li class="nav-item">
                    <a href="{{ route('railway.index') }}" class="nav-link {{ request()->is('railway*') ? 'active' : '' }}">
                        <span>🎫</span> Railway Portal ▾
                    </a>
                    <ul class="nav-subnav">
                        <li>
                            <a href="{{ route('railway.index') }}">
                                <span>Book New Ticket</span>
                                <span class="subnav-badge">Form</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('railway.bookings') }}">
                                <span>Master Bookings List</span>
                                <span class="subnav-badge">100+ Pagination</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('railway.systems') }}">
                                <span>5 Railway Divisions</span>
                                <span class="subnav-badge">Req 6</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Practicals Dropdown -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('practicals*') ? 'active' : '' }}">
                        <span>📚</span> Practicals ▾
                    </a>
                    <ul class="nav-subnav">
                        <li><a href="{{ route('practicals.p1') }}">Practical 1 (HTML Webpage) <span class="subnav-badge">HTML</span></a></li>
                        <li><a href="{{ route('practicals.p2') }}">Practical 2 (HTML & CSS) <span class="subnav-badge">CSS</span></a></li>
                        <li><a href="{{ route('practicals.p4') }}">Practical 4 (Calculator) <span class="subnav-badge">JS Calc</span></a></li>
                        <li><a href="{{ route('practicals.p7') }}">Practical 7 (Academic Form) <span class="subnav-badge">Form</span></a></li>
                        <li><a href="{{ route('practicals.p7.data') }}">Practical 7 (Student Data) <span class="subnav-badge">CRUD Data</span></a></li>
                    </ul>
                </li>

                <!-- PHP Practicals Dropdown -->
                <li class="nav-item">
                    <a href="{{ route('php.index') }}" class="nav-link {{ request()->is('php*') ? 'active' : '' }}">
                        <span>🐘</span> PHP Practicals ▾
                    </a>
                    <ul class="nav-subnav">
                        <li><a href="{{ route('php.index') }}">PHP Practicals Hub <span class="subnav-badge">Overview</span></a></li>
                        <li><a href="{{ route('php.9_1') }}">9.1 Max of 3 Values</a></li>
                        <li><a href="{{ route('php.9_2') }}">9.2 Print 1 to N</a></li>
                        <li><a href="{{ route('php.9_3') }}">9.3 3 Types of Pyramids</a></li>
                        <li><a href="{{ route('php.10_1') }}">10.1 Array Operations</a></li>
                        <li><a href="{{ route('php.10_2') }}">10.2 String Name & Length</a></li>
                        <li><a href="{{ route('php.11_1') }}">11.1 Max & Min Numbers</a></li>
                        <li><a href="{{ route('php.11_2') }}">11.2 Date/Time & Greeting</a></li>
                        <li><a href="{{ route('php.11_3') }}">11.3 User Profile ID Card</a></li>
                        <li><a href="{{ route('php.11_4') }}">11.4 5 PHP String Functions</a></li>
                        <li><a href="{{ route('php.12') }}">12 Database CRUD Operations</a></li>
                        <li><a href="{{ route('php.db_explorer') }}">phpMyAdmin Database Explorer</a></li>
                    </ul>
                </li>

                <!-- Team Members Dropdown -->
                <li class="nav-item">
                    <a href="{{ route('members.index') }}" class="nav-link {{ request()->is('members*') ? 'active' : '' }}">
                        <span>👥</span> Team Group 10 ▾
                    </a>
                    <ul class="nav-subnav">
                        <li><a href="{{ route('members.show', 1) }}">Ridham Bambhaniya <span class="subnav-badge">26GMCA61</span></a></li>
                        <li><a href="{{ route('members.show', 2) }}">Riya Thakkar <span class="subnav-badge">26GMCA33</span></a></li>
                        <li><a href="{{ route('members.show', 3) }}">Vaibhav Senjaliya <span class="subnav-badge">26GMCA52</span></a></li>
                        <li><a href="{{ route('members.index') }}">All Team Overview</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Quick Book Button -->
            <div>
                <a href="{{ route('railway.index') }}" class="btn btn-saffron btn-sm">
                    <span>+</span> Book Train Ticket
                </a>
            </div>
        </div>
    </nav>

    <!-- Context Strip: Active Division & Station Master In-Charge -->
    <div class="system-banner">
        <div class="system-banner-inner">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span>🟢</span>
                <span>Active Railway System: <strong>{{ $activeSys->name }}</strong></span>
                <span class="badge badge-navy" style="font-size: 11px;">Zone: {{ $activeSys->zone }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span>Designated Admin (Station Master): <strong>{{ $activeSys->station_master_name }}</strong></span>
                <a href="{{ route('railway.systems') }}" style="color: var(--green-dark); font-weight: 700; text-decoration: underline;">Change System (Req 6)</a>
            </div>
        </div>
    </div>

    <!-- Main Body Container -->
    <main class="page-content">
        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <div><span>✅</span> {{ session('success') }}</div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:16px;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <div><span>❌</span> {{ session('error') }}</div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:16px;">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <div><span>ℹ️</span> {{ session('info') }}</div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:16px;">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div>
                <div style="font-size: 16px; font-weight: 800; color: #FFFFFF; margin-bottom: 8px;">
                    Government MCA College, Maninagar (GMCA)
                </div>
                <p style="margin-bottom: 12px; font-size: 13px;">
                    K.K. Shastri Educational Campus, Khokhra Road, Maninagar (East), Ahmedabad - 380008, Gujarat.<br>
                    Department of Computer Applications | Course: Web Technology Practicals (WTP - MC01094051)
                </p>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <span class="badge badge-navy">Laravel 11/12</span>
                    <span class="badge badge-saffron">PHP 8.3</span>
                    <span class="badge badge-green">SQLite / MySQL</span>
                    <span class="badge badge-blue">Eloquent Pagination</span>
                </div>
            </div>

            <div>
                <div style="font-size: 14px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px;">Group 10 Team</div>
                <ul style="list-style: none; font-size: 13px; line-height: 1.8;">
                    <li><strong>Ridham Bambhaniya</strong> (`26GMCA61`) - Team Lead</li>
                    <li><strong>Riya Thakkar</strong> (`26GMCA33`) - Core Member</li>
                    <li><strong>Vaibhav Senjaliya</strong> (`26GMCA52`) - Developer</li>
                </ul>
            </div>

            <div>
                <div style="font-size: 14px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px;">Evaluation Specs</div>
                <ul style="list-style: none; font-size: 12.5px; line-height: 1.8;">
                    <li>✔ Full Project Converted to Laravel</li>
                    <li>✔ Total 5 Users (3 Members + 1 Admin)</li>
                    <li>✔ 105+ Entries Per User Account (525+ Total)</li>
                    <li>✔ Laravel Eloquent Pagination Component</li>
                    <li>✔ Admin role is Station Master</li>
                    <li>✔ 5 Distinct Railway Division Systems</li>
                </ul>
            </div>
        </div>
        <div style="max-width: 1400px; margin: 20px auto 0; padding-top: 15px; border-top: 1px solid #334155; text-align: center; font-size: 12px; color: #64748B;">
            © 2025–2026 Government MCA College, Maninagar — Group 10 WTP Practical Laravel Submission.
        </div>
    </footer>

    <script>
        function toggleDropdown(id) {
            const menu = document.getElementById(id);
            const isShown = menu.classList.contains('show');
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
            if (!isShown) {
                menu.classList.add('show');
            }
        }

        window.onclick = function(event) {
            if (!event.target.closest('.system-pill-dropdown') && !event.target.closest('.user-pill-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
