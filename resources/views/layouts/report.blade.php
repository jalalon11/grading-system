@php
use Illuminate\Support\Facades\Route;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Grading System') }} - Consolidated Grades Report</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @yield('styles')

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-left, .logo-right {
            width: 15%;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-left img, .logo-right img {
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;
        }

        .title-center {
            width: 70%;
            text-align: center;
        }

        .title-center h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .title-center p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table-header {
            font-weight: bold;
            text-align: left;
            padding: 5px;
        }

        .info-table {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: bold;
            width: 15%;
            text-align: left;
        }

        .info-value {
            width: 35%;
            text-align: left;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-item {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 0 auto 5px;
            width: 80%;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                font-size: 11pt;
                background-color: white;
            }

            .container {
                width: 100%;
                padding: 0;
                margin: 0;
            }

            @page {
                size: landscape;
                margin: 0.5cm;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            table {
                font-size: 9pt !important;
                page-break-inside: avoid;
            }

            th, td {
                padding: 2px !important;
                font-size: 9pt !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Action buttons - only visible on screen -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if(!str_contains(Route::currentRouteName(), 'attendance'))
                        <form id="transmutationForm" class="d-flex align-items-center">
                            <label for="transmutation_table" class="form-label fw-semibold me-2 mb-0">Transmutation Table:</label>
                            <select class="form-select form-select-sm shadow-sm" id="transmutation_table" name="transmutation_table" onchange="updateTransmutedGrades()" style="width: 300px;">
                                <option value="1">Table 1: DepEd Transmutation Table</option>
                                <option value="2">Table 2: Grades 1-10 & Non-Core TVL</option>
                                <option value="3">Table 3: SHS Core & Work Immersion</option>
                                <option value="4">Table 4: All other SHS Subjects</option>
                            </select>
                        </form>
                        @endif
                    </div>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary shadow-sm">
                            <i class="fas fa-print me-1"></i> Print Report
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2 shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Selection
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Additional Scripts -->
    @yield('scripts')
</body>
</html>
