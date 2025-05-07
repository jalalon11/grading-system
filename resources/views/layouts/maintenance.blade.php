<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Grading System') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1C2833;
            --primary-hover: #2E4053;
            --secondary-color: #f59e0b;
            --secondary-hover: #d97706;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-color: #f3f4f6;
            --dark-color: #1C2833;
            --body-bg: #F4F6F6;
            --card-bg: rgba(244, 246, 246, 0.95);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .maintenance-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
        }

        .maintenance-card {
            background-color: var(--card-bg);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-bottom: 2rem;
            border: none;
        }

        .maintenance-icon {
            font-size: 4rem;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
        }

        .maintenance-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .maintenance-message {
            font-size: 1.125rem;
            color: #4b5563;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-hover);
            border-color: var(--secondary-hover);
        }

        .btn-outline-light {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: #fff;
        }

        .user-info {
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .user-info h4 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .user-info p {
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        .announcement-card {
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .announcement-header {
            background-color: #f3f4f6;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .announcement-title {
            margin-bottom: 0;
            font-weight: 600;
            color: var(--dark-color);
        }

        .announcement-date {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .announcement-content {
            padding: 1.5rem;
            white-space: pre-line;
        }

        .changelog-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--dark-color);
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Main Content -->
    <main class="maintenance-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
