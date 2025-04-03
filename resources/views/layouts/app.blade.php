<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" x-data="{ 
    darkMode: {{ Auth::check() && Auth::user()->dark_mode_preference !== null ? (Auth::user()->dark_mode_preference ? 'true' : 'false') : "localStorage.getItem('darkMode') === 'true'" }} 
}" x-init="$watch('darkMode', val => { 
    localStorage.setItem('darkMode', val); 
    // Only apply dark mode if not on login page
    if (window.location.pathname !== '/login') {
        document.documentElement.classList.toggle('dark', val);
    }
    @if(Auth::check())
    // Send AJAX request to update user preference
    fetch('{{ route('user.update-dark-mode') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ dark_mode: val })
    });
    @endif
})" x-bind:class="{ 'dark': darkMode && window.location.pathname !== '/login' }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Dark mode flash prevention -->
    <script>
        // Immediate execution before any content renders
        (function() {
            // Check if we're on the login page
            const isLoginPage = window.location.pathname === '/login';
            
            @if(Auth::check() && Auth::user()->dark_mode_preference !== null)
            // Use the user's preference from the database if logged in
            const userPrefersDark = {{ Auth::user()->dark_mode_preference ? 'true' : 'false' }};
            // Never apply dark mode on login page
            if (!isLoginPage && userPrefersDark) {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#121212';
                document.documentElement.style.color = '#e4e6eb';
            }
            // Also update localStorage to match
            localStorage.setItem('darkMode', userPrefersDark);
            @else
            // Fall back to localStorage for guests or users without a preference
            if (!isLoginPage && localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#121212';
                document.documentElement.style.color = '#e4e6eb';
            }
            @endif
        })();
    </script>

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
        /* Apply initial dark mode colors immediately */
        .dark {
            background-color: #121212;
            color: #e4e6eb;
        }
        
        /* Prevent transitions during page load */
        .no-transition * {
            transition: none !important;
        }
        
        /* Dark mode color variables */
        :root.dark {
            --bg-main: #121212;
            --bg-card: #242526;
            --bg-card-header: #1e1e1e;
            --border-color: #3a3b3c;
            --text-color: #e4e6eb;
            --text-muted: #9fa6b2;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Dark mode toggle styling */
        .dark-mode-toggle {
            position: relative;
            transition: transform 0.2s ease;
            height: 36px;
            margin: 0;
        }

        .dark-mode-toggle:hover {
            transform: scale(1.05);
        }

        .dark-mode-icon-container {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .dark-mode-icon-container i {
            position: absolute;
            transition: opacity 0.2s ease, transform 0.2s ease;
            font-size: 1.1rem;
        }

        [x-cloak] {
            display: none !important;
        }

        .dark .dark-mode-toggle {
            color: var(--text-color);
        }

        .dark .btn-link.nav-link:hover {
            color: var(--text-color);
            opacity: 0.9;
        }
        
        .dark .text-muted {
            color: #a0a0a0 !important;
        }
        
        /* Dark mode styles */
        .dark body {
            background-color: var(--bg-main);
            color: var(--text-color);
        }
        
        /* Body class when dark mode is active */
        .dark-mode-body {
            background-color: var(--bg-main) !important;
            color: var(--text-color) !important;
        }
        
        .dark .navbar {
            background-color: #1e1e1e !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
        }
        
        .dark .navbar .nav-link,
        .dark .navbar .navbar-brand {
            color: #e4e6eb !important;
        }
        
        .dark .card {
            background-color: #242526;
            border-color: #2d2d2d;
            color: #e4e6eb;
        }
        
        .dark .card-header {
            background-color: #18191a;
            border-color: #2d2d2d;
        }
        
        .dark .table {
            color: #e4e6eb;
        }
        
        .dark .table thead th {
            border-color: #2d2d2d;
            background-color: #18191a;
        }
        
        .dark .table td,
        .dark .table th {
            border-color: #2d2d2d;
        }
        
        .dark .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .dark .dropdown-menu {
            background-color: #242526;
            border-color: #2d2d2d;
        }
        
        .dark .dropdown-item {
            color: #e4e6eb;
        }
        
        .dark .dropdown-item:hover {
            background-color: #3a3b3c;
            color: #e4e6eb;
        }
        
        .dark .dropdown-divider {
            border-color: #3a3b3c;
        }
        
        .dark input, 
        .dark select, 
        .dark textarea {
            background-color: #3a3b3c !important;
            border-color: #2d2d2d !important;
            color: #e4e6eb !important;
        }
        
        .dark .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .dark .modal-content {
            background-color: #242526;
            border-color: #2d2d2d;
            color: #e4e6eb;
        }
        
        .dark .modal-header,
        .dark .modal-footer {
            border-color: #3a3b3c;
        }
        
        .dark .list-group-item {
            background-color: #242526;
            border-color: #3a3b3c;
            color: #e4e6eb;
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
            min-width: 70px;
            max-width: 260px;
            min-height: 100vh;
            background: #2c3e50;
            color: #fff;
            transition: min-width 0.35s cubic-bezier(0.25, 0.1, 0.25, 1), 
                        max-width 0.35s cubic-bezier(0.25, 0.1, 0.25, 1),
                        background 0.3s ease;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
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
        
        /* Dark mode sidebar */
        .dark #sidebar {
            background: #1a1a1a;
            box-shadow: 3px 0 15px rgba(0,0,0,0.3);
        }
        
        .dark #sidebar .sidebar-header {
            background: rgba(0,0,0,0.3);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .dark #sidebar ul li a:hover {
            background: rgba(255,255,255,0.07);
            border-left: 3px solid #4b9fd6;
        }
        
        .dark #sidebar ul li.active > a {
            background: rgba(75, 159, 214, 0.15);
            border-left: 3px solid #4b9fd6;
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
            width: 100%;
            min-height: 100vh;
            transition: margin-left 0.35s cubic-bezier(0.25, 0.1, 0.25, 1);
            margin-left: 0;
            position: relative;
            padding-top: 60px; /* Match navbar height */
            will-change: margin-left;
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
        body.login-page .login-card {
            transform: none !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -5px rgba(0, 0, 0, 0.04) !important;
            transition: none !important;
        }
        
        body.login-page .login-card:hover {
            transform: none !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }
        
        /* Keep hover effect for input fields */
        body.login-page .form-control {
            transition: all 0.2s ease !important;
        }
        
        /* Fix sign in button animation */
        body.login-page .btn-login::before {
            animation: none !important;
            display: none !important;
        }
        
        body.login-page .btn-login:hover::before {
            animation: none !important;
            display: none !important;
        }
        
        /* Fix floating animation on login logo */
        body.login-page .school-logo {
            animation: none !important;
        }
        
        body.login-page .school-logo::after {
            animation: none !important;
            display: none !important;
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
        
        body.sidebar-collapsed #content {
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
                margin-left: 260px;
            }
            
            body.sidebar-collapsed #content {
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
                width: 100%;
            }
            
            body.sidebar-open #content {
                margin-left: 0;
            }
            
            body.sidebar-collapsed #content {
                margin-left: 0;
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
            transition: all 0.2s;
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
            transition: all 0.3s;
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
            transition: all 0.3s;
            z-index: 1060;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
        
        body.sidebar-collapsed #sidebarCollapseFixed i {
            transform: rotate(180deg);
        }
        
        /* Mobile sidebar toggle button - removing this */
        #mobileSidebarToggle {
            display: none !important; /* Hide this button completely */
        }

        /* Override dark mode styles for login page */
        body.login-page {
            background-color: #f8f9fa !important;
            color: #212529 !important;
        }

        body.login-page .card {
            background-color: #ffffff !important;
            border-color: #e9ecef !important;
        }

        body.login-page .form-control {
            background-color: #ffffff !important;
            border-color: #ced4da !important;
            color: #212529 !important;
        }

        body.login-page .form-control:focus {
            background-color: #ffffff !important;
            border-color: #86b7fe !important;
            color: #212529 !important;
        }

        body.login-page .form-label {
            color: #212529 !important;
        }

        body.login-page .text-muted {
            color: #6c757d !important;
        }

        body.login-page .btn-primary {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        body.login-page .btn-primary:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }

        body.login-page .btn-outline-secondary {
            color: #6c757d !important;
            border-color: #6c757d !important;
        }

        body.login-page .btn-outline-secondary:hover {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #ffffff !important;
        }

        body.login-page .alert {
            background-color: #f8f9fa !important;
            border-color: #e9ecef !important;
            color: #212529 !important;
        }

        body.login-page .alert-danger {
            background-color: #f8d7da !important;
            border-color: #f5c2c7 !important;
            color: #842029 !important;
        }

        body.login-page .alert-success {
            background-color: #d1e7dd !important;
            border-color: #badbcc !important;
            color: #0f5132 !important;
        }

        body.login-page .alert-info {
            background-color: #cff4fc !important;
            border-color: #b6effb !important;
            color: #055160 !important;
        }

        body.login-page .alert-warning {
            background-color: #fff3cd !important;
            border-color: #ffecb5 !important;
            color: #664d03 !important;
        }
    </style>
</head>
<body class="{{ !Request::is('login') && !Request::is('register') && Auth::check() ? 'sidebar-open' : 'sidebar-collapsed' }} {{ Request::is('login') ? 'login-page' : '' }} no-transition" x-bind:class="{ 'dark-mode-body': darkMode }">
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
            @if(!Request::is('login'))
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
                            <!-- Dark Mode Toggle -->
                            <li class="nav-item mx-2">
                                <button type="button" class="btn btn-link nav-link px-2 py-1 d-flex align-items-center dark-mode-toggle" x-on:click="darkMode = !darkMode" title="Toggle dark mode">
                                    <div class="dark-mode-icon-container">
                                        <i class="fas fa-moon" x-show="!darkMode" x-cloak></i>
                                        <i class="fas fa-sun text-warning" x-show="darkMode" x-cloak></i>
                                    </div>
                                    <span class="ms-2 small text-muted d-none d-md-inline" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                                </button>
                            </li>
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

            <main class="py-4 {{ Request::is('login') ? 'p-0' : '' }}">
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
            
            // Initialize dark mode based on saved preference or system preference
            const savedDarkMode = localStorage.getItem('darkMode');
            if (savedDarkMode === 'true') {
                document.documentElement.classList.add('dark');
            } else if (savedDarkMode === 'false') {
                document.documentElement.classList.remove('dark');
            } else {
                // Check system preference if no saved preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                } else {
                    localStorage.setItem('darkMode', 'false');
                }
            }
        })();
        
        $(document).ready(function () {
            // Enable transitions after page load with a slight delay
            setTimeout(function() {
                $('body').removeClass('no-transition');
            }, 200);
            
            // Fixed sidebar button icon should match state
            if ($('body').hasClass('sidebar-collapsed')) {
                $('#sidebarCollapse i').removeClass('fa-times').addClass('fa-bars');
                $('#sidebarCollapseFixed i').removeClass('fa-times').addClass('fa-bars');
            } else {
                $('#sidebarCollapse i').removeClass('fa-bars').addClass('fa-times');
                $('#sidebarCollapseFixed i').removeClass('fa-bars').addClass('fa-times'); 
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
                
                // Toggle icon rotation for the inner sidebar button
                if ($('body').hasClass('sidebar-collapsed')) {
                    $('#sidebarCollapse i').removeClass('fa-times').addClass('fa-bars');
                    $('#sidebarCollapseFixed i').removeClass('fa-times').addClass('fa-bars');
                    // Save state to localStorage
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    $('#sidebarCollapse i').removeClass('fa-bars').addClass('fa-times');
                    $('#sidebarCollapseFixed i').removeClass('fa-bars').addClass('fa-times');
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
