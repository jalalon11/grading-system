<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            padding: 0;
            margin: 0;
        }

        .grade-slip-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .grade-slip {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            border: 1px solid #000;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .header {
            margin-bottom: 20px;
        }

        .student-info {
            margin-bottom: 20px;
        }

        .table th, .table td {
            padding: 0.5rem;
            vertical-align: middle;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 50%;
            margin: 10px auto;
        }

        @media print {
            body {
                background-color: #fff;
                margin: 0;
                padding: 0;
            }

            .container, .grade-slip-container {
                width: 100%;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }

            .d-print-none {
                display: none !important;
            }

            .grade-slip {
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
            }

            .table-bordered {
                border-collapse: collapse;
            }

            .table-bordered th, .table-bordered td {
                border: 1px solid #000 !important;
            }

            @page {
                size: A4;
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
