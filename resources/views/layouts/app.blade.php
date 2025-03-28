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

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    @stack('styles')
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Dropdown styling */
        .dropdown-menu.show {
            display: block;
            margin-top: 0.5rem;
            right: 0;
            left: auto;
            position: absolute;
            z-index: 1060;
        }
        
        .nav-item.dropdown {
            position: relative;
        }
        
        /* Modern Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            overflow-y: auto;
            will-change: transform;
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
            margin-left: -260px;
        }
        
        #sidebar .sidebar-header {
            padding: 20px 25px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
        }
        
        #sidebar .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }
        
        #sidebar ul.components {
            padding: 15px 0;
        }
        
        #sidebar ul li {
            position: relative;
            margin: 5px 10px;
            border-radius: 6px;
            overflow: hidden;
        }
        
        #sidebar ul li a {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        #sidebar ul li a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(3px);
        }
        
        #sidebar ul li.active > a {
            background: #3498db;
            color: #fff;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        #sidebar .dropdown-toggle::after {
            display: block;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }
        
        /* Main content positioning */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            margin-left: 0;
            position: relative;
            padding-top: 60px; /* Match navbar height */
        }
        
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            overflow-x: hidden;
        }
        
        body.sidebar-open #content {
            margin-left: 260px;
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
            transition: all 0.3s;
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
                margin-left: 260px;
            }
            
            body.sidebar-collapsed #content {
                margin-left: 0;
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
                background: linear-gradient(180deg, #1e3a5c 0%, #0f2033 100%);
            }
            
            #sidebar.active {
                margin-left: 0;
                box-shadow: 0 0 20px rgba(0,0,0,0.3);
            }
            
            #content {
                margin-left: 0 !important;
                width: 100%;
            }
            
            body.sidebar-open #content {
                margin-left: 0;
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
                background: rgba(0,0,0,0.3);
            }
            
            /* Enhance sidebar menu items on mobile */
            #sidebar ul li {
                margin: 4px 8px;
            }
            
            #sidebar ul li a {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            
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
        
        /* Enhanced Sidebar Toggle Button */
        #sidebarCollapse {
            position: relative;
            background: #3498db;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 3px 8px rgba(52, 152, 219, 0.3);
            transition: all 0.2s;
            z-index: 1050;
        }
        
        #sidebarCollapse:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        
        #sidebarCollapse:active {
            transform: scale(0.95);
        }
        
        #sidebarCollapse i {
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        body.sidebar-collapsed #sidebarCollapse i {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="{{ !Request::is('login') && !Request::is('register') && Auth::check() ? 'sidebar-open' : 'sidebar-collapsed' }}">
    <div class="wrapper">
        <!-- Sidebar  -->
        @auth
        @if(!Request::is('login'))
        <nav id="sidebar" class="{{ Request::is('login') || Request::is('register') ? 'd-none' : '' }}">
            <div class="sidebar-header">
                <h4><i class="fas fa-graduation-cap me-2"></i>Grading System</h4>
            </div>

            <ul class="list-unstyled components">
                @if(Auth::user()->role === 'admin')
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="{{ Request::is('admin/school-divisions*') ? 'active' : '' }}">
                    <a href="{{ route('admin.school-divisions.index') }}">
                        <i class="fas fa-building"></i> School Divisions
                    </a>
                </li>
                <li class="{{ Request::is('admin/schools*') ? 'active' : '' }}">
                    <a href="{{ route('admin.schools.index') }}">
                        <i class="fas fa-school"></i> Schools
                    </a>
                </li>
                <li class="{{ Request::is('admin/teachers*') ? 'active' : '' }}">
                    <a href="{{ route('admin.teachers.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li class="{{ Request::is('admin/teacher-admins*') ? 'active' : '' }}">
                    <a href="{{ route('admin.teacher-admins.index') }}">
                        <i class="fas fa-user-shield"></i> Teacher Admins
                    </a>
                </li>
                @elseif(Auth::user()->role === 'teacher')
                <li class="{{ Request::is('teacher/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('teacher.dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                
                <!-- Teacher functionality for ALL teachers (including teacher admins) -->
                <li class="{{ Request::is('teacher/students*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.students.index') }}">
                        <i class="fas fa-user-graduate"></i> Students
                    </a>
                </li>
                <li class="{{ Request::is('teacher/grades*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.grades.index') }}">
                        <i class="fas fa-star"></i> Grades
                    </a>
                </li>
                <li class="{{ Request::is('teacher/attendances*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.attendances.index') }}">
                        <i class="fas fa-clipboard-check"></i> Attendance
                    </a>
                </li>
                
                @if(Auth::user()->is_teacher_admin)
                <!-- Teacher Admin Section -->
                <div class="sidebar-divider"></div>
                <div class="sidebar-heading">
                    Teacher Admin Panel
                </div>
                <li class="{{ Request::is('teacher-admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('teacher-admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                    </a>
                </li>
                <li class="{{ Request::is('teacher-admin/sections*') ? 'active' : '' }}">
                    <a href="{{ route('teacher-admin.sections.index') }}">
                        <i class="fas fa-door-open"></i> Manage Sections
                    </a>
                </li>
                <li class="{{ Request::is('teacher-admin/subjects*') ? 'active' : '' }}">
                    <a href="{{ route('teacher-admin.subjects.index') }}">
                        <i class="fas fa-book"></i> Manage Subjects
                    </a>
                </li>
                @endif
                @endif
                
                <div class="sidebar-divider"></div>
                
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
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
            @if(!Request::is('login'))
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    @auth
                    <button type="button" id="sidebarCollapse" class="btn btn-primary d-flex justify-content-center align-items-center">
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

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link d-flex align-items-center" href="#" role="button">
                                        <span>{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down ms-2 small"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown" style="min-width: 200px; position: absolute; top: 100%;">
                                        <a class="dropdown-item d-flex align-items-center" href="{{ auth()->user()->role === 'admin' ? route('admin.profile') : (auth()->user()->is_teacher_admin ? route('teacher-admin.profile') : route('teacher.profile')) }}">
                                            <i class="fas fa-user-circle me-2"></i>
                                            <span>{{ __('My Profile') }}</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>
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

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Custom Scripts -->
    @stack('scripts')
    
    <script>
        $(document).ready(function () {
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
            
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('body').toggleClass('sidebar-collapsed sidebar-open');
                $('.sidebar-overlay').toggleClass('active');
                
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
