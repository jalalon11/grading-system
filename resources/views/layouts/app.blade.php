<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Grading System') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery for sidebar toggle -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom Styles -->
    @stack('styles')

    <style>
        /* Prevent transitions during page load */
        .no-transition * {
            transition: none !important;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        /* Dropdown styling */
        .dropdown-menu.show {
            display: block;
            margin-top: 0.5rem;
            right: 0;
            left: auto;
            position: absolute;
            z-index: 1060;
            animation: fadeInDown 0.2s ease-out;
            transform-origin: top center;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.08);
            top: 100%;
            transform: none;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .nav-item.dropdown {
            position: relative;
        }

        .dropdown-menu.dropdown-menu-end {
            left: auto;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            position: absolute;
            transform: none !important;
        }

        /* Modern Sidebar Styling */
        #sidebar {
            min-width: 70px;
            max-width: 260px;
            min-height: 100vh;
            background: linear-gradient(to bottom, #2c3e50, #1a252f);
            color: #fff;
            transition: all 0.35s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            z-index: 1000;
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            overflow-y: auto;
            will-change: transform, min-width, max-width;
            transform: translateZ(0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            perspective: 1000px;
        }



        #sidebar::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
        }

        #sidebar.active {
            min-width: 70px;
            max-width: 70px;
        }

        #sidebar .sidebar-header {
            padding: 15px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        #sidebar.active .sidebar-header {
            padding: 10px 15px;
            justify-content: center;
        }

        #sidebar .logo-text {
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
            margin-left: 10px;
            margin-right: 15px;
        }

        #sidebar .sidebar-header h4 {
            display: flex;
            align-items: center;
            margin: 0;
        }

        #sidebar.active .sidebar-header h4,
        #sidebar.active .logo-text {
            display: none;
        }

        #sidebar.active .sidebar-header h4 i {
            font-size: 24px;
            margin: 0;
        }

        #sidebar.active #sidebarCollapse {
            display: none;
        }

        #sidebar ul.components {
            padding: 15px 0;
        }

        #sidebar ul li {
            position: relative;
            margin: 5px 0;
            border-radius: 0;
            overflow: hidden;
        }

        #sidebar ul li a {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        #sidebar.active ul li a {
            padding: 15px 0;
            text-align: center;
            justify-content: center;
        }

        #sidebar ul li a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left: 3px solid #3498db;
        }

        #sidebar ul li.active > a {
            background: rgba(52, 152, 219, 0.2);
            color: #fff;
            border-left: 3px solid #3498db;
        }

        #sidebar ul li a span {
            white-space: nowrap;
        }

        #sidebar.active ul li a span {
            display: none;
        }

        #sidebar ul li a i {
            margin-right: 12px;
            min-width: 25px;
            text-align: center;
            font-size: 18px;
            opacity: 0.9;
            transition: all 0.2s;
        }

        #sidebar.active ul li a i {
            margin-right: 0;
            min-width: unset;
            font-size: 20px;
            margin: 0 auto;
        }

        #sidebar ul li:hover a i {
            opacity: 1;
        }

        /* Main content positioning */
        #content {
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.35s cubic-bezier(0.25, 0.1, 0.25, 1);
            margin-left: 260px;
            position: relative;
            padding-top: 60px; /* Match navbar height */
            will-change: margin-left, width;
            flex: 1;
        }

        /* Remove padding on login page */
        body.login-page {
            padding: 0 !important;
            margin: 0 !important;
            overflow-x: hidden;
        }

        body.login-page #content {
            padding: 0 !important;
            margin: 0 !important;
        }

        body.login-page .wrapper {
            padding: 0 !important;
            margin: 0 !important;
            width: 100vw;
        }

        body.login-page main {
            padding: 0 !important;
            margin: 0 !important;
            width: 100%;
        }

        body.login-page .container,
        body.login-page .container-fluid,
        body.login-page .row,
        body.login-page .col,
        body.login-page .col-md-12,
        body.login-page .col-lg-12,
        body.login-page .login-container {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Override login card hover animation */
        body.login-page .login-card, 
        body.register-page .register-card {
            transform: none !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -5px rgba(0, 0, 0, 0.04) !important;
            transition: none !important;
        }

        body.login-page .login-card:hover,
        body.register-page .register-card:hover {
            transform: none !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        /* Keep hover effect for input fields */
        body.login-page .form-control,
        body.register-page .form-control {
            transition: all 0.2s ease !important;
        }

        /* Fix sign in button animation */
        body.login-page .btn-login::before,
        body.register-page .btn-register::before {
            animation: none !important;
            display: none !important;
        }

        body.login-page .btn-login:hover::before,
        body.register-page .btn-register:hover::before {
            animation: none !important;
            display: none !important;
        }

        /* Fix floating animation on login logo */
        body.login-page .school-logo,
        body.register-page .school-logo {
            animation: none !important;
        }

        body.login-page .school-logo::after,
        body.register-page .school-logo::after {
            animation: none !important;
            display: none !important;
        }

        /* Add specific styles for register page */
        body.register-page {
            padding: 0 !important;
            margin: 0 !important;
            overflow-x: hidden;
        }

        body.register-page #content,
        body.register-page main,
        body.register-page .container,
        body.register-page .row,
        body.register-page .col-12 {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
            max-width: 100vw;
        }

        body.sidebar-open #content {
            width: calc(100% - 260px);
            margin-left: 260px;
        }

        body.sidebar-collapsed #content {
            width: calc(100% - 70px);
            margin-left: 70px;
        }

        /* Fixed navbar */
        .navbar {
            padding: 12px 20px;
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            z-index: 990;
            height: 60px;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            width: 100%;
            transition: left 0.35s cubic-bezier(0.25, 0.1, 0.25, 1),
                        width 0.35s cubic-bezier(0.25, 0.1, 0.25, 1);
            will-change: left, width;
        }

        body.sidebar-open .navbar {
            left: 260px;
            width: calc(100% - 260px);
        }

        @media (min-width: 769px) {
            body {
                overflow-x: hidden;
            }

            #content {
                width: calc(100% - 260px);
                margin-left: 260px;
            }

            body.sidebar-collapsed #content {
                width: calc(100% - 70px);
                margin-left: 70px;
            }

            body.sidebar-collapsed .navbar {
                left: 0;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
                z-index: 1050;
                width: 260px;
                max-width: 80%;
                background: #2c3e50;
            }

            #sidebar.active {
                margin-left: 0;
                min-width: 260px;
                max-width: 80%;
                box-shadow: 0 0 20px rgba(0,0,0,0.3);
            }

            #content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            body.sidebar-open #content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            body.sidebar-collapsed #content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Show sidebar header contents on mobile */
            #sidebar.active .sidebar-header h4,
            #sidebar.active .logo-text {
                display: flex;
            }

            /* Show text on mobile */
            #sidebar.active ul li a span {
                display: inline;
            }

            /* Adjust mobile sidebar menu items */
            #sidebar.active ul li a {
                padding: 12px 15px !important;
                text-align: left !important;
                justify-content: flex-start !important;
            }

            #sidebar.active ul li a i {
                margin-right: 12px !important;
                min-width: 25px !important;
                font-size: 18px !important;
                margin: 0 !important;
            }

            /* Show admin panel headings on mobile */
            #sidebar.active .teacher-admin-panel .sidebar-heading {
                display: block;
            }

            /* Fix alignment of teacher admin panel items on mobile */
            #sidebar.active .teacher-admin-panel li a {
                padding: 10px 15px !important;
                text-align: left !important;
                justify-content: flex-start !important;
            }

            #sidebar.active .teacher-admin-panel li a i {
                margin-right: 12px !important;
                min-width: 25px !important;
            }

            /* Hide user dropdown when sidebar is active on mobile */
            body.sidebar-open .navbar .nav-item.dropdown {
                display: none;
                opacity: 0;
                transition: opacity 0.3s;
            }

            body.sidebar-collapsed .navbar .nav-item.dropdown {
                display: block;
                opacity: 1;
                transition: opacity 0.3s;
            }

            #sidebarCollapse span {
                display: none;
            }

            /* Improve sidebar header on mobile */
            #sidebar .sidebar-header {
                padding: 15px;
                background: rgba(0,0,0,0.2);
            }

            /* Enhance sidebar menu items on mobile */
            #sidebar ul li {
                margin: 2px 0;
            }

            #sidebar ul li a {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            /* Removed conflicting rules that were centering icons in mobile view */

            /* Overlay background when sidebar is active on mobile */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                transition: all 0.3s;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.active {
                display: block;
            }

            /* Fix alignment of teacher admin panel items on mobile */
            #sidebar.active .teacher-admin-panel li a {
                padding: 10px 15px !important;
                text-align: left;
                justify-content: flex-start;
            }

            #sidebar.active .teacher-admin-panel li a i {
                margin-right: 12px;
                min-width: 25px;
            }

            /* Fix navbar positioning when sidebar is collapsed */
            body.sidebar-collapsed .navbar {
                left: 0 !important;
                width: 100% !important;
                z-index: 1000;
            }

            /* Ensure navbar content doesn't overlay */
            .navbar .container-fluid {
                padding-left: 60px; /* Make room for the fixed sidebar toggle button */
            }

            /* Better fixed toggle button positioning on mobile */
            #sidebarCollapseFixed {
                top: 12px;
                left: 15px;
                z-index: 1001; /* Higher than navbar */
            }

            /* Improve user dropdown on mobile */
            .dropdown-menu.show {
                width: 200px;
                right: -10px;
                left: auto;
                top: 100%;
                transform: none;
            }

            .nav-item.dropdown .dropdown-menu {
                transition: all 0.3s ease;
            }

            .navbar .nav-item.dropdown {
                margin-right: 10px;
            }
        }

        .sidebar-submenu {
            padding-left: 15px;
            list-style: none;
            background: rgba(0, 0, 0, 0.1);
            margin: 0 5px;
            border-radius: 5px;
        }

        #sidebar ul li a i {
            margin-right: 12px;
            min-width: 20px;
            text-align: center;
            font-size: 18px;
            opacity: 0.85;
            transition: all 0.2s;
        }

        #sidebar ul li:hover a i {
            opacity: 1;
            transform: translateX(2px);
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 15px 15px;
        }

        /* Sidebar section headings */
        .sidebar-heading {
            padding: 10px 15px 5px;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* Teacher Admin Panel styling */
        .teacher-admin-panel {
            margin-top: 5px;
            background: rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            margin: 10px;
            padding-bottom: 10px;
        }

        .teacher-admin-panel .sidebar-heading {
            padding: 12px 15px 8px;
            color: rgba(255, 255, 255, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 8px;
        }

        .teacher-admin-panel li a {
            padding: 10px 15px !important;
        }

        #sidebar.active .teacher-admin-panel {
            background: transparent;
            margin: 5px 0;
            padding-bottom: 5px;
        }

        #sidebar.active .teacher-admin-panel .sidebar-heading {
            display: none;
        }

        /* Enhanced Sidebar Toggle Button */
        #sidebarCollapse {
            position: relative;
            background: rgba(52, 152, 219, 0.2);
            border: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        #sidebarCollapse:hover {
            background: rgba(52, 152, 219, 0.3);
            transform: scale(1.05);
        }

        #sidebarCollapse:active {
            transform: scale(0.95);
        }

        #sidebarCollapse i {
            color: white;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
        }

        body.sidebar-collapsed #sidebarCollapse i {
            transform: rotate(180deg);
        }

        /* Fixed sidebar toggle button that appears when sidebar is collapsed */
        #sidebarCollapseFixed {
            position: fixed;
            left: 20px;
            top: 15px;
            background: #3498db;
            border: none;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
            z-index: 1060;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            opacity: 0;
            visibility: hidden;
        }

        body.sidebar-collapsed #sidebarCollapseFixed {
            opacity: 1;
            visibility: visible;
        }

        #sidebarCollapseFixed:hover {
            background: #2980b9;
            transform: scale(1.05);
        }

        #sidebarCollapseFixed:active {
            transform: scale(0.95);
        }

        #sidebarCollapseFixed i {
            color: white;
            font-size: 1.1rem;
        }

        /* Remove icon rotation */
        body.sidebar-collapsed #sidebarCollapseFixed i {
            transform: none;
        }

        /* Mobile sidebar toggle button - removing this */
        #mobileSidebarToggle {
            display: none !important; /* Hide this button completely */
        }



        /* User avatar styling */
        .avatar-circle {
            width: 32px;
            height: 32px;
            background-color: #3498db;
            text-align: center;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .initials {
            position: relative;
            font-size: 14px;
            line-height: 1;
            color: white;
            font-weight: 600;
        }

        .user-dropdown-toggle {
            border-radius: 30px;
            padding: 5px 12px;
            transition: all 0.2s ease;
            position: relative;
        }

        .user-dropdown-toggle:hover {
            background-color: rgba(0,0,0,0.05);
        }

        .dropdown-header {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Enhanced mobile dropdown styling */
        @media (max-width: 768px) {
            .user-dropdown-toggle {
                border-radius: 50%;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            }

            .user-dropdown-toggle .fa-chevron-down {
                display: none;
            }

            .user-dropdown-toggle i.fa-user-circle {
                font-size: 1.4rem;
            }

            .dropdown-menu.show {
                animation: fadeInUp 0.25s ease-out;
                width: 200px;
                right: -5px;
            }
        }
    </style>
</head>
<body class="{{ !Request::is('login') && !Request::is('register') && Auth::check() ? 'sidebar-open' : 'sidebar-collapsed' }} {{ Request::is('login') ? 'login-page' : '' }} {{ Request::is('register') ? 'register-page' : '' }} no-transition">
    <div class="wrapper">
        <!-- Sidebar  -->
        @auth
        @if(!Request::is('login'))
        <nav id="sidebar" class="{{ Request::is('login') || Request::is('register') ? 'd-none' : '' }}">
            <div class="sidebar-header">
                <h4>
                    <i class="fas fa-graduation-cap"></i>
                    <span class="logo-text">Grading System</span>
                </h4>
                <button type="button" id="sidebarCollapse" class="btn d-flex justify-content-center align-items-center">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="list-unstyled components">
                @if(Auth::user()->role === 'admin')
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/school-divisions*') ? 'active' : '' }}">
                    <a href="{{ route('admin.school-divisions.index') }}">
                        <i class="fas fa-building"></i> <span>School Divisions</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/schools*') ? 'active' : '' }}">
                    <a href="{{ route('admin.schools.index') }}">
                        <i class="fas fa-school"></i> <span>Schools</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/teachers*') ? 'active' : '' }}">
                    <a href="{{ route('admin.teachers.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i> <span>Teachers</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/teacher-admins*') ? 'active' : '' }}">
                    <a href="{{ route('admin.teacher-admins.index') }}">
                        <i class="fas fa-user-shield"></i> <span>Teacher Admins</span>
                    </a>
                </li>
                @elseif(Auth::user()->role === 'teacher')
                <li class="{{ Request::is('teacher/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('teacher.dashboard') }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                <!-- Teacher functionality for ALL teachers (including teacher admins) -->
                <li class="{{ Request::is('teacher/students*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.students.index') }}">
                        <i class="fas fa-user-graduate"></i> <span>Students</span>
                    </a>
                </li>
                <li class="{{ Request::is('teacher/grades*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.grades.index') }}">
                        <i class="fas fa-star"></i> <span>Grades</span>
                    </a>
                </li>
                <li class="{{ Request::is('teacher/attendances*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.attendances.index') }}">
                        <i class="fas fa-clipboard-check"></i> <span>Attendance</span>
                    </a>
                </li>

                <li class="{{ Request::is('teacher/reports*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.reports.index') }}">
                        <i class="fas fa-file-alt"></i> <span>Reports</span>
                    </a>
                </li>

                @if(Auth::user()->is_teacher_admin)
                <!-- Teacher Admin Section -->
                <div class="sidebar-divider"></div>
                <div class="teacher-admin-panel">
                    <div class="sidebar-heading">
                        <i class="fas fa-user-shield me-2"></i>Teacher Admin Panel
                    </div>
                    <ul class="list-unstyled">
                        <li class="{{ Request::is('teacher-admin/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('teacher-admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> <span>Admin Dashboard</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('teacher-admin/sections*') ? 'active' : '' }}">
                            <a href="{{ route('teacher-admin.sections.index') }}">
                                <i class="fas fa-door-open"></i> <span>Manage Sections</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('teacher-admin/subjects*') ? 'active' : '' }}">
                            <a href="{{ route('teacher-admin.subjects.index') }}">
                                <i class="fas fa-book"></i> <span>Manage Subjects</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('teacher-admin/reports*') ? 'active' : '' }}">
                            <a href="{{ route('teacher-admin.reports.index') }}">
                                <i class="fas fa-chart-bar"></i> <span>Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif
                @endif

                <div class="sidebar-divider"></div>

                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        @endif
        @endauth

        <!-- Page Content  -->
        <div id="content">
            @if(!Request::is('login') && !Request::is('register'))
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    @auth
                    <!-- Fixed sidebar toggle button -->
                    <button type="button" id="sidebarCollapseFixed" class="btn d-flex justify-content-center align-items-center">
                        <i class="fas fa-bars"></i>
                    </button>
                    @endauth

                    <!-- <a class="navbar-brand ms-3" href="{{ url('/') }}">
                        <span class="fw-bold">Grading System</span>
                    </a> -->

                    <div class="d-flex justify-content-end flex-grow-1" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">

                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link d-flex align-items-center user-dropdown-toggle" href="#" role="button">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2 d-none d-md-flex">
                                                <div class="avatar-circle">
                                                    <span class="initials">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                            <span class="d-md-none">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            <i class="fas fa-chevron-down ms-2 small"></i>
                                        </div>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                                        <div class="dropdown-header py-2 px-3 bg-light">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    <span class="initials">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                                                    <div class="small text-muted">{{ ucfirst(Auth::user()->role) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ auth()->user()->role === 'admin' ? route('admin.profile') : (auth()->user()->is_teacher_admin ? route('teacher-admin.profile') : route('teacher.profile')) }}">
                                            <i class="fas fa-user-circle me-2 text-primary"></i>
                                            <span>{{ __('My Profile') }}</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                                            <span>{{ __('Logout') }}</span>
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            @endif

            <main class="py-4 {{ Request::is('login') || Request::is('register') ? 'p-0' : '' }}">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Custom Scripts -->
    @stack('scripts')

    <script>
        // Execute immediately to avoid flicker
        (function() {
            // Check saved sidebar state in localStorage
            const sidebarState = localStorage.getItem('sidebarState');

            // Apply saved state if it exists before DOM is fully loaded
            if (sidebarState === 'collapsed') {
                document.getElementById('sidebar').classList.add('active');
                document.body.classList.remove('sidebar-open');
                document.body.classList.add('sidebar-collapsed');
            } else if (sidebarState === 'open') {
                document.getElementById('sidebar').classList.remove('active');
                document.body.classList.add('sidebar-open');
                document.body.classList.remove('sidebar-collapsed');
            }


        })();

        $(document).ready(function () {
            // Enable transitions after page load with a slight delay
            setTimeout(function() {
                $('body').removeClass('no-transition');
            }, 200);

            // Fixed sidebar button icon should match state
            if ($('body').hasClass('sidebar-collapsed')) {
                $('#sidebarCollapse i').addClass('fa-bars');
                $('#sidebarCollapseFixed i').addClass('fa-bars');
            } else {
                $('#sidebarCollapse i').addClass('fa-bars');
                $('#sidebarCollapseFixed i').addClass('fa-bars');
            }

            // Direct click handler for the dropdown
            $('#navbarDropdown').on('click', function(e) {
                e.preventDefault();
                $(this).next('.dropdown-menu').toggleClass('show');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });

            // Add overlay div to the body
            $('body').append('<div class="sidebar-overlay"></div>');

            // Toggle handler for both sidebar toggle buttons
            $('#sidebarCollapse, #sidebarCollapseFixed').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('body').toggleClass('sidebar-collapsed sidebar-open');
                $('.sidebar-overlay').toggleClass('active');

                // Force browser reflow to ensure proper transition
                $('#content')[0].offsetHeight;

                // Always use the fa-bars icon (remove icon rotation)
                if ($('body').hasClass('sidebar-collapsed')) {
                    // Save state to localStorage
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    // Save state to localStorage
                    localStorage.setItem('sidebarState', 'open');
                }

                // On mobile, hide user dropdown when sidebar is opened
                if ($(window).width() <= 768) {
                    if ($('#sidebar').hasClass('active')) {
                        $('.navbar .nav-item.dropdown').fadeOut(300);
                    } else {
                        setTimeout(function() {
                            $('.navbar .nav-item.dropdown').fadeIn(300);
                        }, 100);
                    }
                }
            });

            // Close sidebar when clicking on overlay
            $('.sidebar-overlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('body').removeClass('sidebar-open').addClass('sidebar-collapsed');
                $(this).removeClass('active');

                // Save state to localStorage
                localStorage.setItem('sidebarState', 'collapsed');

                // On mobile, show user dropdown when sidebar is closed
                if ($(window).width() <= 768) {
                    setTimeout(function() {
                        $('.navbar .nav-item.dropdown').fadeIn(300);
                    }, 100);
                }
            });

            // Detect small screens and collapse sidebar by default
            function checkWidth() {
                if ($(window).width() <= 768) {
                    $('#sidebar').removeClass('active');
                    $('body').removeClass('sidebar-open').addClass('sidebar-collapsed');
                    $('.sidebar-overlay').removeClass('active');
                }
            }

            // Check on load
            checkWidth();

            // Check on resize
            $(window).resize(function() {
                checkWidth();
            });
        });
    </script>
</body>
</html>
