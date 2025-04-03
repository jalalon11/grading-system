<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        // Set title to include MAPEH component if needed
        $pageTitle = is_object($subject) ? $subject->name : 'Subject';
        $displaySubjectName = $pageTitle;
        
        // Check if this is a MAPEH component
        if (isset($mapehInfo) && isset($mapehInfo['is_component']) && $mapehInfo['is_component']) {
            $pageTitle = $mapehInfo['component_name'] . ' (MAPEH Component)';
            $displaySubjectName = "MAPEH " . $mapehInfo['component_name'];
        }
    @endphp
    
    <title>Class Record - {{ $displaySubjectName }} ({{ is_string($quarter) ? $quarter : 'Q' . $quarter }})</title>
    
    @php
        // Transmutation Table 1 function
        function transmutationTable1($initialGrade) {
            if ($initialGrade >= 100) return 100;
            if ($initialGrade >= 98.40) return 99;
            if ($initialGrade >= 96.80) return 98;
            if ($initialGrade >= 95.20) return 97;
            if ($initialGrade >= 93.60) return 96;
            if ($initialGrade >= 92.00) return 95;
            if ($initialGrade >= 90.40) return 94;
            if ($initialGrade >= 88.80) return 93;
            if ($initialGrade >= 87.20) return 92;
            if ($initialGrade >= 85.60) return 91;
            if ($initialGrade >= 84.00) return 90;
            if ($initialGrade >= 82.40) return 89;
            if ($initialGrade >= 80.80) return 88;
            if ($initialGrade >= 79.20) return 87;
            if ($initialGrade >= 77.60) return 86;
            if ($initialGrade >= 76.00) return 85;
            if ($initialGrade >= 74.40) return 84;
            if ($initialGrade >= 72.80) return 83;
            if ($initialGrade >= 71.20) return 82;
            if ($initialGrade >= 69.60) return 81;
            if ($initialGrade >= 68.00) return 80;
            if ($initialGrade >= 66.40) return 79;
            if ($initialGrade >= 64.80) return 78;
            if ($initialGrade >= 63.20) return 77;
            if ($initialGrade >= 61.60) return 76;
            if ($initialGrade >= 60.00) return 75;
            if ($initialGrade >= 56.00) return 74;
            if ($initialGrade >= 52.00) return 73;
            if ($initialGrade >= 48.00) return 72;
            if ($initialGrade >= 44.00) return 71;
            if ($initialGrade >= 40.00) return 70;
            if ($initialGrade >= 36.00) return 69;
            if ($initialGrade >= 32.00) return 68;
            if ($initialGrade >= 28.00) return 67;
            if ($initialGrade >= 24.00) return 66;
            if ($initialGrade >= 20.00) return 65;
            if ($initialGrade >= 16.00) return 64;
            if ($initialGrade >= 12.00) return 63;
            if ($initialGrade >= 8.00) return 62;
            if ($initialGrade >= 4.00) return 61;
            if ($initialGrade >= 0.00) return 60;
            return 0;
        }
    @endphp
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                font-size: 11pt;
            }
            .container {
                width: 100%;
                padding: 0;
            }
            .page-header, .page-footer {
                display: none;
            }
            @page {
                size: landscape;
                margin: 0.5cm;
            }
            .no-print-during-actual-printing {
                display: none !important;
            }
            #helpButton, 
            button[onclick*="window.print"],
            .btn-print,
            .action-buttons,
            [id*="print"],
            [id*="help"] {
                display: none !important;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
            font-size: 10pt;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-row {
            display: table-row;
        }
        .logo-left, .logo-right {
            display: table-cell;
            width: 15%;
            vertical-align: middle;
            text-align: center;
        }
        .logo-left img, .logo-right img {
            max-width: 100px;
            height: auto;
        }
        .title-center {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
            text-align: center;
        }
        .title-center h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .title-center p {
            margin: 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        .info-table td {
            padding: 5px;
            vertical-align: middle;
            border: 1px solid black;
        }
        .info-label {
            width: 15%;
            text-align: right;
            font-weight: bold;
            background-color: #f2f2f2;
            padding-right: 10px;
        }
        .info-value {
            width: 35%;
            text-align: center;
        }
        .info-spacer {
            width: 0.5%;
            border-left: 1px solid black;
            border-right: 1px solid black;
            padding: 0;
        }
        .school-info {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .col {
            flex: 1;
            min-width: 300px;
        }
        .school-info p {
            margin: 5px 0;
            line-height: 1.5;
        }
        .school-info .label {
            font-weight: bold;
            min-width: 100px;
            display: inline-block;
        }
        .school-info .value {
            font-weight: normal;
        }
        .class-info {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .class-info p {
            margin: 5px 0;
            line-height: 1.5;
        }
        .class-info .label {
            font-weight: bold;
            min-width: 100px;
            display: inline-block;
        }
        .class-info .value {
            font-weight: normal;
        }
        .table-header {
            font-weight: bold;
            text-align: center;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature-item {
            text-align: center;
            margin: 0 20px;
        }
        .signature-line {
            border-top: 1px solid black;
            width: 200px;
            margin: 50px auto 0;
        }
        .male-section, .female-section {
            margin-top: 5px;
        }
        .gender-label {
            font-weight: bold;
            text-align: left;
            padding: 5px;
            background-color: #e6e6e6;
        }
        .centered {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .column-header {
            font-size: 9pt;
            vertical-align: middle;
        }
        .btn-print {
            background-color: #5f6163;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-print:hover {
            background-color: #5f6163;
        }
        .no-print {
            margin-bottom: 20px;
            text-align: left;
        }
        
        /* Add editable score styles */
        .editable-score {
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        
        .editable-score:hover {
            background-color: #f5f9ff;
            box-shadow: inset 0 0 0 1px #4dabf7;
        }
        
        .editable-score.editing {
            padding: 0;
            background-color: #e8f4ff;
            box-shadow: inset 0 0 0 2px #4dabf7;
        }
        
        .editable-score.editing input {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            text-align: center;
            border: none;
            outline: none;
            font-size: 10pt;
            background-color: transparent;
            padding: 5px;
        }
        
        .editable-score:before {
            content: "✏️";
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 12px;
            color: #2196F3;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .editable-score:hover:before {
            opacity: 1;
        }
        
        .edit-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            width: 220px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .edit-controls h6 {
            margin: 0 0 10px 0;
            padding-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #e9ecef;
            color: #343a40;
        }
        
        .edit-controls .status {
            font-size: 12px;
            margin-bottom: 15px;
            color: #6c757d;
        }
        
        .edit-controls .actions {
            display: flex;
            gap: 8px;
        }
        
        .edit-controls button {
            flex: 1;
            padding: 8px 5px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
        }
        
        .edit-controls button.save {
            background-color: #2b7ded;
            color: white;
        }
        
        .edit-controls button.cancel {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .edit-controls button.save:hover {
            background-color: #1a66ca;
        }
        
        .edit-controls button.cancel:hover {
            background-color: #dee2e6;
        }
        
        @media print {
            .editable-score:before,
            .edit-controls,
            .toast-container {
                display: none !important;
            }
            .editable-score:hover {
                background-color: transparent;
                box-shadow: none;
            }
        }
        
        /* Toast notification - modernized, professional design */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 6px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            min-width: 300px;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-left: 4px solid transparent;
            background-color: #fff;
        }
        
        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .toast.success {
            border-left-color: #4caf50;
        }
        
        .toast.error {
            border-left-color: #f44336;
        }
        
        .toast.info {
            border-left-color: #2196F3;
        }
        
        .toast-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            margin-right: 12px;
            border-radius: 50%;
            background-color: rgba(0,0,0,0.05);
            color: #666;
        }
        
        .toast.success .toast-icon {
            color: #4caf50;
        }
        
        .toast.error .toast-icon {
            color: #f44336;
        }
        
        .toast.info .toast-icon {
            color: #2196F3;
        }
        
        .toast-content {
            flex-grow: 1;
        }
        
        .toast-title {
            font-weight: 600;
            margin-bottom: 3px;
            font-size: 14px;
            color: #333;
        }
        
        .toast-message {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
        }
        
        .toast-close {
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
            opacity: 0.7;
            color: #999;
            transition: all 0.2s ease;
        }
        
        .toast-close:hover {
            opacity: 1;
            color: #666;
        }
        
        /* Cell types styling */
        .ww-score:before {
            content: "W";
            font-size: 7px;
            background-color: #e9ecef;
            color: #495057;
            padding: 1px 3px;
            border-radius: 3px;
            position: absolute;
            top: 2px;
            left: 2px;
        }
        
        .pt-score:before {
            content: "P";
            font-size: 7px;
            background-color: #e3fafc;
            color: #0b7285;
            padding: 1px 3px;
            border-radius: 3px;
            position: absolute;
            top: 2px;
            left: 2px;
        }
        
        .qa-score:before {
            content: "Q";
            font-size: 7px;
            background-color: #fff9db;
            color: #e67700;
            padding: 1px 3px;
            border-radius: 3px;
            position: absolute;
            top: 2px;
            left: 2px;
        }

        /* Help Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h4 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        
        .modal-close {
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }
        
        .modal-close:hover {
            color: #333;
        }
        
        .modal-body {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e9ecef;
            text-align: right;
        }
        
        .modal-btn {
            padding: 8px 16px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .modal-btn:hover {
            background-color: #0d8bf0;
        }
        
        .help-section {
            margin-bottom: 20px;
        }
        
        .help-section h5 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
        }
        
        .help-icon {
            margin-right: 10px;
            font-style: normal;
        }
        
        .help-section p {
            margin-top: 0;
            color: #555;
            line-height: 1.5;
        }
        
        .help-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .help-section li {
            margin-bottom: 8px;
            color: #555;
        }
        
        .help-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .help-badge.written {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .help-badge.performance {
            background-color: #e3fafc;
            color: #0b7285;
        }
        
        .help-badge.quarterly {
            background-color: #fff9db;
            color: #e67700;
        }

        @media print {
            .modal-overlay {
                display: none !important;
            }
        }
        
        /* Bulk editing styles */
        .bulk-selected {
            background-color: #e8f5e9 !important;
            box-shadow: inset 0 0 0 2px #4caf50 !important;
            position: relative;
        }
        
        .bulk-selected:after {
            content: "✓";
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 10px;
            color: #4caf50;
        }
        
        .bulk-edit-container {
            position: fixed;
            bottom: 60px;
            right: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 999;
        }
        
        #bulkEditInput {
            width: 220px;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Add CSS for error state */
        .editing input.error {
            background-color: #fff0f0;
            border: 1px solid #dc3545;
            color: #dc3545;
        }
        
        /* Row highlighting on hover */
        .highlight-row {
            background-color: rgba(246, 249, 252, 0.7) !important;
            transition: background-color 0.2s ease;
        }
        
        /* Increase visibility of the current cell being hovered */
        tr:hover td.editable-score {
            position: relative;
            z-index: 1;
        }
        
        /* Keep the row highlight when a cell is being edited */
        tr:has(td.editing) {
            background-color: rgba(246, 249, 252, 0.7) !important;
        }

        /* Replace with stronger highlighting */
        .highlight-row {
            background-color: rgba(235, 245, 255, 0.95) !important;
            box-shadow: 0 0 0 2px #4dabf7 inset;
            transition: all 0.15s ease;
        }

        tr:hover {
            background-color: rgba(235, 245, 255, 0.75) !important;
        }

        /* More visible cell highlighting */
        .editable-score:hover {
            background-color: rgba(210, 233, 255, 1) !important;
            box-shadow: inset 0 0 0 2px #4dabf7;
        }

        /* Keep the row highlight when a cell is being edited */
        tr:has(td.editing) {
            background-color: rgba(235, 245, 255, 0.95) !important;
            box-shadow: 0 0 0 2px #4dabf7 inset;
        }

        function setupRowHighlighting() {
            // Get all editable cells
            const editableCells = document.querySelectorAll('.editable-score');
            
            // Add mouse enter/leave events to each cell
            editableCells.forEach(cell => {
                cell.addEventListener('mouseenter', function() {
                    // Find the parent row
                    const row = this.closest('tr');
                    if (row) {
                        row.classList.add('highlight-row');
                        // Add a small delay to make the transition visible
                        setTimeout(() => {
                            row.style.transition = 'background-color 0.15s ease';
                        }, 10);
                    }
                });
                
                cell.addEventListener('mouseleave', function() {
                    // Find the parent row
                    const row = this.closest('tr');
                    if (row) {
                        // Only remove highlight if not editing a cell in this row
                        if (!row.querySelector('.editing')) {
                            row.classList.remove('highlight-row');
                        }
                    }
                });
            });
        }
    </style>
</head>
<body>

    <!-- Action buttons with refresh button added -->
    <div class="action-buttons" style="position: fixed; top: 10px; right: 10px; z-index: 1000; display: flex; gap: 10px;">
        <button onclick="printPage(); return false;" class="print-button" style="padding: 8px 15px; background: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <span style="margin-right: 5px;">🖨️</span> Print Now
        </button>
        <button id="helpButton" class="help-button" style="padding: 8px 15px; background: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <span style="margin-right: 5px;">ℹ️</span> Help
        </button>
    </div>


    @php
        // Define variables needed for the report
        $schoolName = 'St. Anthony Parish School';
        $region = 'Region X - Northern Mindanao';
        $division = 'District of Manticao';
        $schoolYear = '2025-2026';
        $spcName = 'SR. MARIA YOLANDA CARIDAD ORPILLA , SPC.';

        if (is_string($quarter)) {
            switch ($quarter) {
                case 'Q1':
                    $quarterText = 'First';
                    break;
                case 'Q2':
                    $quarterText = 'Second';
                    break;
                case 'Q3':
                    $quarterText = 'Third';
                    break;
                case 'Q4':
                    $quarterText = 'Fourth';
                    break;
                default:
                    $quarterText = 'First';
            }
        } else {
            switch ($quarter) {
                case 1:
                    $quarterText = 'First';
                    break;
                case 2:
                    $quarterText = 'Second';
                    break;
                case 3:
                    $quarterText = 'Third';
                    break;
                case 4:
                    $quarterText = 'Fourth';
                    break;
                default:
                    $quarterText = 'First';
            }
        }
    @endphp
    
    <!-- Just keep the Close button -->
    <div>
        <button class="btn-print" onclick="window.close();">Close</button>
    </div>
    
    <div class="container">
        @php
            $schoolName = isset($section) && $section->school ? $section->school->name : 'St. Anthony Parish School';
            $region = isset($section) && $section->school && $section->school->schoolDivision ? $section->school->schoolDivision->region : 'Region XI';
            $division = isset($section) && $section->school && $section->school->schoolDivision ? $section->school->schoolDivision->name : 'Division of Davao del Sur';
            $principal = isset($section) && $section->school && $section->school->principal ? $section->school->principal : 'Principal Name';
            
            $quarterNames = [
                1 => 'First',
                2 => 'Second',
                3 => 'Third',
                4 => 'Fourth',
                'Q1' => 'First',
                'Q2' => 'Second',
                'Q3' => 'Third',
                'Q4' => 'Fourth'
            ];
            
            $quarterText = isset($quarter) && isset($quarterNames[$quarter]) ? $quarterNames[$quarter] : 'First';
            $schoolYear = isset($section) && $section->school_year ? (is_object($section->school_year) ? $section->school_year->name : $section->school_year) : '2023-2024';
        @endphp
        
        <div class="header">
            <div class="header-row">
                <div class="logo-left">
                    @if(isset($section) && $section->school && $section->school->logo_path)
                        <img src="{{ asset($section->school->logo_path) }}" alt="School Logo" style="max-height: 80px;">
                    @else
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNDAiIHk9IjQwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNhYWFhYWEiPkxvZ288L3RleHQ+PC9zdmc+" alt="School Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNDAiIHk9IjQwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNhYWFhYWEiPkxvZ288L3RleHQ+PC9zdmc+'">
                    @endif
                </div>
                <div class="title-center">
                    <h1>Class Record</h1>
                    <p>(Pursuant to DepEd Order 8 series of 2015)</p>
                </div>
                <div class="logo-right">
                    <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo">
                </div>
            </div>
        </div>
        
        <table class="info-table">
            <tr>
                <td class="info-label">REGION</td>
                <td class="info-value">{{ $region }}</td>
                <td class="info-label">QUARTER</td>
                <td class="info-value">{{ $quarter }}</td>
            </tr>
            <tr>
                <td class="info-label">DIVISION</td>
                <td class="info-value">{{ $division }}</td>
                <td class="info-label">SCHOOL YEAR</td>
                <td class="info-value">{{ $schoolYear }}</td>
            </tr>
            <tr>
                <td class="info-label">SCHOOL NAME</td>
                <td class="info-value">{{ $schoolName }}</td>
                <td class="info-label">SUBJECT</td>
                <td class="info-value">
                    @if(isset($mapehInfo) && isset($mapehInfo['is_component']) && $mapehInfo['is_component'])
                        MAPEH - {{ $mapehInfo['component_name'] }}
                    @else
                        {{ $subject->name ?? 'Subject Name' }}
                    @endif
                </td>
            </tr>
        </table>
        
        
        <table style="table-layout: fixed;">
            <tr>
                <td style="width: 20%;" class="table-header">
                    {{ $quarterText ?? $quarter }} QUARTER
                </td>
                <td style="width: 30%;" class="table-header">
                    GRADE & SECTION: {{ isset($section->grade_level) ? $section->grade_level : 'Grade' }} {{ $section->name ?? 'Section' }}
                </td>
                <td style="width: 25%;" class="table-header">
                    TEACHER: {{ auth()->user()->name ?? 'Teacher Name' }}
                </td>
                <td style="width: 25%;" class="table-header">
                    SUBJECT: 
                    @if(isset($mapehInfo) && isset($mapehInfo['is_component']) && $mapehInfo['is_component'])
                        MAPEH - {{ $mapehInfo['component_name'] }}
                    @else
                        {{ $subject->name ?? 'Subject Name' }}
                    @endif
                </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <th rowspan="2" style="width: 20%;">LEARNERS' NAMES</th>
                <th colspan="10" style="width: 25%;">WRITTEN WORKS ({{ $gradeConfig->written_work_percentage }}%)</th>
                <th colspan="11" style="width: 35%;">PERFORMANCE TASKS ({{ $gradeConfig->performance_task_percentage }}%)</th>
                <th colspan="3" style="width: 10%;">QUARTERLY ASSESSMENT ({{ $gradeConfig->quarterly_assessment_percentage }}%)</th>
                <th rowspan="2" style="width: 5%;">Initial Grade</th>
                <th rowspan="2" style="width: 5%;">Quarterly Grade</th>
            </tr>
            <tr>
                <!-- Written Works Columns -->
                @for($i = 1; $i <= 7; $i++)
                <th class="column-header">{{ $i }}</th>
                @endfor
                
                <th class="column-header">Total</th>
                <th class="column-header">PS</th>
                <th class="column-header">WS</th>
                
                <!-- Performance Tasks Columns -->
                @for($i = 1; $i <= 8; $i++)
                <th class="column-header">{{ $i }}</th>
                @endfor
                
                <th class="column-header">Total</th>
                <th class="column-header">PS</th>
                <th class="column-header">WS</th>
                
                <!-- Quarterly Assessment Columns -->
                <th class="column-header">1</th>
                <th class="column-header">PS</th>
                <th class="column-header">WS</th>
            </tr>
            <tr>
                <td class="centered">HIGHEST POSSIBLE SCORE</td>
                
                <!-- Written Works Max Scores -->
                @php
                    $writtenWorksTotal = 0;
                    $performanceTasksTotal = 0;
                    $quarterlyAssessmentTotal = 0;
                @endphp
                
                <!-- Display Written Works -->
                @foreach($writtenWorks->take(7) as $work)
                    <td>{{ number_format($work->max_score, 0) }}</td>
                    @php $writtenWorksTotal += $work->max_score; @endphp
                @endforeach
                
                @for($i = $writtenWorks->count(); $i < 7; $i++)
                    <td></td>
                @endfor
                
                <td>{{ number_format($writtenWorksTotal, 0) }}</td>
                <td>100.00</td>
                <td>{{ number_format($gradeConfig->written_work_percentage, 1) }}%</td>
                
                <!-- Performance Tasks Max Scores -->
                @foreach($performanceTasks->take(8) as $task)
                    <td>{{ number_format($task->max_score, 0) }}</td>
                    @php $performanceTasksTotal += $task->max_score; @endphp
                @endforeach
                
                @for($i = $performanceTasks->count(); $i < 8; $i++)
                    <td></td>
                @endfor
                
                <td>{{ number_format($performanceTasksTotal, 0) }}</td>
                <td>100.00</td>
                <td>{{ number_format($gradeConfig->performance_task_percentage, 1) }}%</td>
                
                <!-- Quarterly Assessment -->
                @if($quarterlyAssessments->isNotEmpty())
                    <td>{{ number_format($quarterlyAssessments->first()->max_score, 0) }}</td>
                    @php $quarterlyAssessmentTotal = $quarterlyAssessments->first()->max_score; @endphp
                @else
                    <td></td>
                    @php $quarterlyAssessmentTotal = 0; @endphp
                @endif
                <td>100.00</td>
                <td>{{ number_format($gradeConfig->quarterly_assessment_percentage, 1) }}%</td>
                
                <td>100.0%</td>
                <td>{{ transmutationTable1(100) }}</td>
            </tr>
            
            <!-- Male Students -->
            <tr>
                <td class="gender-label" colspan="27">MALE</td>
            </tr>
            @php 
                $maleStudents = $students->where('gender', 'Male')->sortBy('last_name'); 
                error_log('Male students found: ' . $maleStudents->count());
                foreach ($maleStudents as $m) {
                    error_log('Male student: ' . $m->first_name . ' ' . $m->last_name . ' (ID: ' . $m->id . ')');
                }
            @endphp
            @forelse($maleStudents as $index => $student)
                <tr>
                    <td class="text-left">{{ $student->last_name }}, {{ $student->first_name }}</td>
                    
                    <!-- Written Works -->
                    @php
                        $studentGradeData = $studentGrades->get($student->id, collect([]));
                        $studentWrittenWorks = $studentGradeData->where('grade_type', 'written_work');
                        $studentPerfTasks = $studentGradeData->where('grade_type', 'performance_task');
                        $studentQuarterly = $studentGradeData->where('grade_type', 'quarterly')->first();
                    @endphp
                    
                    <!-- Display Written Works Scores -->
                    @php $studentWWTotal = 0; @endphp
                    @foreach($writtenWorks->take(7) as $index => $work)
                        @php
                            $grade = $studentWrittenWorks->first(function($item) use ($work) {
                                return $item->assessment_name == $work->assessment_name;
                            });
                            
                            if ($grade) {
                                $studentWWTotal += $grade->score;
                            }
                        @endphp
                        <td class="ww-score editable-cell" 
                            data-student-id="{{ $student->id }}" 
                            data-subject-id="{{ $subject->id }}" 
                            data-quarter="{{ $quarter }}" 
                            data-grade-type="written_work" 
                            data-assessment-name="{{ $work->assessment_name }}"
                            data-assessment-index="{{ $index + 1 }}"
                            data-max-score="{{ $work->max_score }}">
                            {{ $grade ? number_format($grade->score, 0) : '' }}
                        </td>
                    @endforeach
                    
                    @for($i = $writtenWorks->count(); $i < 7; $i++)
                        <td></td>
                    @endfor
                    
                    <!-- Written Works Total, PS, WS -->
                    @php
                        $wwPS = '';
                        $wwWS = '';
                        
                        // Debug calculations
                        $debugWW = [];
                        
                        // Calculate average percentage the same way as Grade Summary page
                        $assessmentCount = 0;
                        $totalPercentage = 0;
                        
                        foreach($writtenWorks->take(7) as $work) {
                            $grade = $studentWrittenWorks->first(function($item) use ($work) {
                                return $item->assessment_name == $work->assessment_name;
                            });
                            
                            if ($grade) {
                                $assessmentCount++;
                                $assessmentPercentage = ($grade->score / $work->max_score) * 100;
                                $totalPercentage += $assessmentPercentage;
                                
                                // Debug info
                                $debugWW[] = [
                                    'name' => $work->assessment_name,
                                    'score' => $grade->score,
                                    'max' => $work->max_score,
                                    'percentage' => $assessmentPercentage
                                ];
                            }
                        }
                        
                        if ($assessmentCount > 0) {
                            // Calculate PS (Percentage Score) - average of all percentages
                            $wwPS = $totalPercentage / $assessmentCount;
                            
                            // Calculate WS (Weighted Score) - apply the weight percentage
                            $wwWS = ($wwPS / 100) * $gradeConfig->written_work_percentage;
                        }
                    @endphp
                    <td>{{ $studentWWTotal > 0 ? number_format($studentWWTotal, 0) : '' }}</td>
                    <td>{{ $wwPS !== '' ? number_format($wwPS, 2) : '' }}</td>
                    <td>{{ $wwWS !== '' ? number_format($wwWS, 1) : '' }}%</td>
                    
                    <!-- Performance Tasks -->
                    @php $studentPTTotal = 0; @endphp
                    @foreach($performanceTasks->take(8) as $index => $task)
                        @php
                            $grade = $studentPerfTasks->first(function($item) use ($task) {
                                return $item->assessment_name == $task->assessment_name;
                            });
                            
                            if ($grade) {
                                $studentPTTotal += $grade->score;
                            }
                        @endphp
                        <td class="pt-score editable-cell" 
                            data-student-id="{{ $student->id }}" 
                            data-subject-id="{{ $subject->id }}" 
                            data-quarter="{{ $quarter }}" 
                            data-grade-type="performance_task" 
                            data-assessment-name="{{ $task->assessment_name }}"
                            data-assessment-index="{{ $index + 1 }}"
                            data-max-score="{{ $task->max_score }}">
                            {{ $grade ? number_format($grade->score, 0) : '' }}
                        </td>
                    @endforeach
                    
                    @for($i = $performanceTasks->count(); $i < 8; $i++)
                        <td></td>
                    @endfor
                    
                    <!-- Performance Tasks Total, PS, WS -->
                    @php
                        $ptPS = '';
                        $ptWS = '';
                        
                        // Calculate average percentage instead of total/max
                        $ptAssessmentCount = 0;
                        $ptTotalPercentage = 0;
                        
                        foreach($performanceTasks->take(8) as $task) {
                            $grade = $studentPerfTasks->first(function($item) use ($task) {
                                return $item->assessment_name == $task->assessment_name;
                            });
                            
                            if ($grade) {
                                $ptAssessmentCount++;
                                $ptTotalPercentage += ($grade->score / $task->max_score) * 100;
                            }
                        }
                        
                        if ($ptAssessmentCount > 0) {
                            // Average of percentages
                            $ptPS = $ptTotalPercentage / $ptAssessmentCount;
                            // Weighted Score
                            $ptWS = ($ptPS / 100) * $gradeConfig->performance_task_percentage;
                        }
                    @endphp
                    <td>{{ $studentPTTotal > 0 ? number_format($studentPTTotal, 0) : '' }}</td>
                    <td>{{ $ptPS !== '' ? number_format($ptPS, 2) : '' }}</td>
                    <td>{{ $ptWS !== '' ? number_format($ptWS, 1) : '' }}%</td>
                    
                    <!-- Quarterly Assessment for male students -->
                    @php
                        $qaScore = '';
                        $qaPS = '';
                        $qaWS = '';
                        if ($studentQuarterly) {
                            $qaScore = $studentQuarterly->score;
                            $qaMaxScore = $studentQuarterly->max_score;
                            // Calculate PS as a percentage
                            $qaPS = ($qaScore / $qaMaxScore) * 100;
                            // Calculate WS as actual contribution to final grade
                            $qaWS = ($qaPS / 100) * $gradeConfig->quarterly_assessment_percentage;
                        }
                    @endphp
                    
                    <td class="qa-score editable-cell" 
                        data-student-id="{{ $student->id }}" 
                        data-subject-id="{{ $subject->id }}" 
                        data-quarter="{{ $quarter }}" 
                        data-grade-type="quarterly" 
                        data-assessment-name="Quarterly Assessment"
                        data-assessment-index="1"
                        data-max-score="{{ $studentQuarterly ? $studentQuarterly->max_score : 100 }}">
                        {{ $qaScore !== '' ? number_format($qaScore, 0) : '' }}
                    </td>
                    <td>{{ $qaPS !== '' ? number_format($qaPS, 2) : '' }}</td>
                    <td>{{ $qaWS !== '' ? number_format($qaWS, 1) : '' }}%</td>
                    
                    <!-- Initial and Quarterly Grades for male students -->
                    @php
                        $initialGrade = '';
                        $quarterlyGrade = '';
                        
                        // Only calculate grades if the student has actual grades
                        $hasGrades = ($studentWrittenWorks->count() > 0 || $studentPerfTasks->count() > 0 || $studentQuarterly);
                        
                        if ($hasGrades && ($wwWS !== '' || $ptWS !== '' || $qaWS !== '')) {
                            // Initial grade is the sum of all weighted scores
                            $initialGrade = ($wwWS !== '' ? $wwWS : 0) + ($ptWS !== '' ? $ptWS : 0) + ($qaWS !== '' ? $qaWS : 0);
                            // Calculate quarterly grade using the transmutation table
                            $quarterlyGrade = $initialGrade !== '' ? transmutationTable1($initialGrade) : '';
                        }
                    @endphp
                    <td>{{ $initialGrade !== '' ? number_format($initialGrade, 1) : '' }}%</td>
                    <td>{{ $quarterlyGrade !== '' ? $quarterlyGrade : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="27" class="text-center">No male students in this section</td>
                </tr>
            @endforelse
            
            <!-- Female Students -->
            <tr>
                <td class="gender-label" colspan="27">FEMALE</td>
            </tr>
            @php $femaleStudents = $students->where('gender', 'Female')->sortBy('last_name'); @endphp
            @forelse($femaleStudents as $index => $student)
                <tr>
                    <td class="text-left">{{ $student->last_name }}, {{ $student->first_name }}</td>
                    
                    <!-- Written Works -->
                    @php
                        $studentGradeData = $studentGrades->get($student->id, collect([]));
                        $studentWrittenWorks = $studentGradeData->where('grade_type', 'written_work');
                        $studentPerfTasks = $studentGradeData->where('grade_type', 'performance_task');
                        $studentQuarterly = $studentGradeData->where('grade_type', 'quarterly')->first();
                    @endphp
                    
                    <!-- Display Written Works Scores -->
                    @php $studentWWTotal = 0; @endphp
                    @foreach($writtenWorks->take(7) as $index => $work)
                        @php
                            $grade = $studentWrittenWorks->first(function($item) use ($work) {
                                return $item->assessment_name == $work->assessment_name;
                            });
                            
                            if ($grade) {
                                $studentWWTotal += $grade->score;
                            }
                        @endphp
                        <td class="ww-score editable-cell" 
                            data-student-id="{{ $student->id }}" 
                            data-subject-id="{{ $subject->id }}" 
                            data-quarter="{{ $quarter }}" 
                            data-grade-type="written_work" 
                            data-assessment-name="{{ $work->assessment_name }}"
                            data-assessment-index="{{ $index + 1 }}"
                            data-max-score="{{ $work->max_score }}">
                            {{ $grade ? number_format($grade->score, 0) : '' }}
                        </td>
                    @endforeach
                    
                    @for($i = $writtenWorks->count(); $i < 7; $i++)
                        <td></td>
                    @endfor
                    
                    <!-- Written Works Total, PS, WS -->
                    @php
                        $wwPS = '';
                        $wwWS = '';
                        
                        // Debug calculations
                        $debugWW = [];
                        
                        // Calculate average percentage the same way as Grade Summary page
                        $assessmentCount = 0;
                        $totalPercentage = 0;
                        
                        foreach($writtenWorks->take(7) as $work) {
                            $grade = $studentWrittenWorks->first(function($item) use ($work) {
                                return $item->assessment_name == $work->assessment_name;
                            });
                            
                            if ($grade) {
                                $assessmentCount++;
                                $assessmentPercentage = ($grade->score / $work->max_score) * 100;
                                $totalPercentage += $assessmentPercentage;
                                
                                // Debug info
                                $debugWW[] = [
                                    'name' => $work->assessment_name,
                                    'score' => $grade->score,
                                    'max' => $work->max_score,
                                    'percentage' => $assessmentPercentage
                                ];
                            }
                        }
                        
                        if ($assessmentCount > 0) {
                            // Calculate PS (Percentage Score) - average of all percentages
                            $wwPS = $totalPercentage / $assessmentCount;
                            
                            // Calculate WS (Weighted Score) - apply the weight percentage
                            $wwWS = ($wwPS / 100) * $gradeConfig->written_work_percentage;
                        }
                    @endphp
                    <td>{{ $studentWWTotal > 0 ? number_format($studentWWTotal, 0) : '' }}</td>
                    <td>{{ $wwPS !== '' ? number_format($wwPS, 2) : '' }}</td>
                    <td>{{ $wwWS !== '' ? number_format($wwWS, 1) : '' }}%</td>
                    
                    <!-- Performance Tasks -->
                    @php $studentPTTotal = 0; @endphp
                    @foreach($performanceTasks->take(8) as $index => $task)
                        @php
                            $grade = $studentPerfTasks->first(function($item) use ($task) {
                                return $item->assessment_name == $task->assessment_name;
                            });
                            
                            if ($grade) {
                                $studentPTTotal += $grade->score;
                            }
                        @endphp
                        <td class="pt-score editable-cell" 
                            data-student-id="{{ $student->id }}" 
                            data-subject-id="{{ $subject->id }}" 
                            data-quarter="{{ $quarter }}" 
                            data-grade-type="performance_task" 
                            data-assessment-name="{{ $task->assessment_name }}"
                            data-assessment-index="{{ $index + 1 }}"
                            data-max-score="{{ $task->max_score }}">
                            {{ $grade ? number_format($grade->score, 0) : '' }}
                        </td>
                    @endforeach
                    
                    @for($i = $performanceTasks->count(); $i < 8; $i++)
                        <td></td>
                    @endfor
                    
                    <!-- Performance Tasks Total, PS, WS -->
                    @php
                        $ptPS = '';
                        $ptWS = '';
                        
                        // Calculate average percentage instead of total/max
                        $ptAssessmentCount = 0;
                        $ptTotalPercentage = 0;
                        
                        foreach($performanceTasks->take(8) as $task) {
                            $grade = $studentPerfTasks->first(function($item) use ($task) {
                                return $item->assessment_name == $task->assessment_name;
                            });
                            
                            if ($grade) {
                                $ptAssessmentCount++;
                                $ptTotalPercentage += ($grade->score / $task->max_score) * 100;
                            }
                        }
                        
                        if ($ptAssessmentCount > 0) {
                            // Average of percentages
                            $ptPS = $ptTotalPercentage / $ptAssessmentCount;
                            // Weighted Score
                            $ptWS = ($ptPS / 100) * $gradeConfig->performance_task_percentage;
                        }
                    @endphp
                    <td>{{ $studentPTTotal > 0 ? number_format($studentPTTotal, 0) : '' }}</td>
                    <td>{{ $ptPS !== '' ? number_format($ptPS, 2) : '' }}</td>
                    <td>{{ $ptWS !== '' ? number_format($ptWS, 1) : '' }}%</td>
                    
                    <!-- Quarterly Assessment for female students -->
                    @php
                        $qaScore = '';
                        $qaPS = '';
                        $qaWS = '';
                        if ($studentQuarterly) {
                            $qaScore = $studentQuarterly->score;
                            $qaMaxScore = $studentQuarterly->max_score;
                            // Calculate PS as a percentage
                            $qaPS = ($qaScore / $qaMaxScore) * 100;
                            // Calculate WS as actual contribution to final grade
                            $qaWS = ($qaPS / 100) * $gradeConfig->quarterly_assessment_percentage;
                        }
                    @endphp
                    
                    <td class="qa-score editable-cell" 
                        data-student-id="{{ $student->id }}" 
                        data-subject-id="{{ $subject->id }}" 
                        data-quarter="{{ $quarter }}" 
                        data-grade-type="quarterly" 
                        data-assessment-name="Quarterly Assessment"
                        data-assessment-index="1"
                        data-max-score="{{ $studentQuarterly ? $studentQuarterly->max_score : 100 }}">
                        {{ $qaScore !== '' ? number_format($qaScore, 0) : '' }}
                    </td>
                    <td>{{ $qaPS !== '' ? number_format($qaPS, 2) : '' }}</td>
                    <td>{{ $qaWS !== '' ? number_format($qaWS, 1) : '' }}%</td>
                    
                    <!-- Initial and Quarterly Grades for female students -->
                    @php
                        $initialGrade = '';
                        $quarterlyGrade = '';
                        
                        // Only calculate grades if the student has actual grades
                        $hasGrades = ($studentWrittenWorks->count() > 0 || $studentPerfTasks->count() > 0 || $studentQuarterly);
                        
                        if ($hasGrades && ($wwWS !== '' || $ptWS !== '' || $qaWS !== '')) {
                            // Initial grade is the sum of all weighted scores
                            $initialGrade = ($wwWS !== '' ? $wwWS : 0) + ($ptWS !== '' ? $ptWS : 0) + ($qaWS !== '' ? $qaWS : 0);
                            // Calculate quarterly grade using the transmutation table
                            $quarterlyGrade = $initialGrade !== '' ? transmutationTable1($initialGrade) : '';
                        }
                    @endphp
                    <td>{{ $initialGrade !== '' ? number_format($initialGrade, 1) : '' }}%</td>
                    <td>{{ $quarterlyGrade !== '' ? $quarterlyGrade : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="27" class="text-center">No female students in this section</td>
                </tr>
            @endforelse
        </table>
        
        <div class="signature-section">
            <div class="signature-item">
                <p style="margin-bottom: 50px;">Prepared by:</p>
                <div class="signature-line"></div>
                <p><strong>{{ auth()->user()->name ?? 'Teacher Name' }}</strong></p>
                <p>Section Adviser</p>
            </div>
            
            <div class="signature-item">
                <p style="margin-bottom: 50px;">Checked by:</p>
                <div class="signature-line"></div>
                <p><strong>{{ $section->coordinator_name ?? 'Coordinator Name' }}</strong></p>
                <p>Subject Coordinator</p>
            </div>
            
            <div class="signature-item">
                <p style="margin-bottom: 50px;">Approved by:</p>
                <div class="signature-line"></div>
                <p><strong>{{ $principal }}</strong></p>
                <p>School Principal</p>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Uncomment to automatically print when page loads
            // window.print();
        };
        
        // Print function that doesn't hide buttons afterward
        function printPage() {
            try {
                // Cancel any pending page reload timer
                if (window.pageReloadTimer) {
                    clearTimeout(window.pageReloadTimer);
                    window.pageReloadTimer = null;
                }
                
                // Hide any modals or controls before printing
                const helpModal = document.getElementById('helpModal');
                const editControls = document.querySelector('.edit-controls');
                const toastContainer = document.querySelector('.toast-container');
                const actionButtons = document.querySelector('.action-buttons');
                const closeButton = document.querySelector('.btn-print');
                const allNoprint = document.querySelectorAll('.no-print');
                
                // Force hide all no-print elements
                allNoprint.forEach(el => {
                    el.style.display = 'none';
                });
                
                // Hide specific buttons and controls
                if (helpModal) helpModal.style.display = 'none';
                if (editControls) editControls.style.display = 'none';
                if (toastContainer) toastContainer.innerHTML = '';
                if (actionButtons) actionButtons.style.display = 'none';
                if (closeButton) closeButton.style.display = 'none';
                
                // Add one-time print stylesheet
                document.body.insertAdjacentHTML('beforeend', 
                    '<style id="temp-print-styles">.no-print{display:none !important} .btn-print{display:none !important} #helpButton{display:none !important}</style>');
                
                // Directly trigger print
                setTimeout(() => {
                    window.print();
                    
                    // Remove the temporary print styles after printing
                    setTimeout(() => {
                        const tempStyles = document.getElementById('temp-print-styles');
                        if (tempStyles) tempStyles.remove();
                        
                        // Remove this toast notification
                        // showToast('Print', 'Print dialog opened', 'success');
                    }, 500);
                }, 100);
            } catch (e) {
                // If there's an error, show it
                showToast('Error', 'Could not open print dialog: ' + e.message, 'error');
            }
        }
    </script>

    <!-- Add edit controls -->
    <div class="edit-controls no-print" style="display: none;">
        <h6>Edit Score</h6>
        <div class="status">Currently editing: <span id="editingInfo">-</span></div>
        <div class="actions">
            <button type="button" class="save">Save</button>
            <button type="button" class="cancel">Cancel</button>
        </div>
    </div>

    <!-- Toast notifications container -->
    <div class="toast-container"></div>

    <!-- Help Modal -->
    <div id="helpModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Editing Assessments Help</h4>
                <span class="modal-close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="help-section">
                    <h5><i class="help-icon">📝</i> Editing Scores</h5>
                    <p>Click on any score cell to edit its value. Each cell type is marked with a letter:</p>
                    <ul>
                        <li><span class="help-badge written">W</span> <strong>Written Works</strong> - Quizzes, tests, and other written assessments</li>
                        <li><span class="help-badge performance">P</span> <strong>Performance Tasks</strong> - Projects and hands-on assessments</li>
                        <li><span class="help-badge quarterly">Q</span> <strong>Quarterly Assessment</strong> - Final exam for the quarter</li>
                    </ul>
                </div>
                <div class="help-section">
                    <h5><i class="help-icon">⌨️</i> Keyboard Shortcuts</h5>
                    <ul>
                        <li><strong>Enter</strong> - Save changes</li>
                        <li><strong>Escape</strong> - Cancel editing</li>
                        <li><strong>Tab</strong> - Move to next editable cell</li>
                    </ul>
                </div>
                <div class="help-section">
                    <h5><i class="help-icon">ℹ️</i> Important Notes</h5>
                    <p>After changing a score, the system will automatically recalculate all grades. The page will reload after 3 seconds to show updated values.</p>                </div>
            </div>
            <div class="modal-footer">
                <button id="closeHelpBtn" class="modal-btn">Got it</button>
            </div>
        </div>
    </div>

    <!-- Add JavaScript to handle editing -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enable edit mode for all editable cells
            setupAssessmentEditing();
            
            // Setup row highlighting
            setupRowHighlighting();
            
            // Hide buttons when page is being printed
            window.addEventListener('beforeprint', function() {
                // Force hide during ACTUAL printing only
                const actionButtons = document.querySelector('.action-buttons');
                const closeButton = document.querySelector('.btn-print');
                const helpButton = document.getElementById('helpButton');
                
                if (actionButtons) actionButtons.classList.add('no-print-during-actual-printing');
                if (closeButton) closeButton.classList.add('no-print-during-actual-printing');
                if (helpButton) helpButton.classList.add('no-print-during-actual-printing');
            });
            
            // Restore visibility after printing
            window.addEventListener('afterprint', function() {
                // Ensure buttons are visible
                const actionButtons = document.querySelector('.action-buttons');
                const closeButton = document.querySelector('.btn-print');
                
                if (actionButtons) {
                    actionButtons.classList.remove('no-print-during-actual-printing');
                    actionButtons.style.display = 'flex';
                }
                if (closeButton) {
                    closeButton.classList.remove('no-print-during-actual-printing');
                    closeButton.style.display = 'inline-block';
                }
            });
            
            // Print button functionality - make it reliable
            const printButton = document.getElementById('printButton');
            if (printButton) {
                printButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    printPage(); // Use the direct function
                });
            }
            
            // Help button functionality
            const helpButton = document.getElementById('helpButton');
            const helpModal = document.getElementById('helpModal');
            const closeHelpBtn = document.getElementById('closeHelpBtn');
            const modalClose = document.querySelector('.modal-close');
            
            if (helpButton) {
                helpButton.addEventListener('click', function() {
                    helpModal.style.display = 'flex';
                });
            }
            
            if (closeHelpBtn) {
                closeHelpBtn.addEventListener('click', function() {
                    helpModal.style.display = 'none';
                });
            }
            
            if (modalClose) {
                modalClose.addEventListener('click', function() {
                    helpModal.style.display = 'none';
                });
            }
            
            // Close modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target === helpModal) {
                    helpModal.style.display = 'none';
                }
            });
        });
        
        // Create and show toast notification
        function showToast(title, message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            // Icon based on type
            let icon = '✓';
            if (type === 'error') icon = '✕';
            if (type === 'info') icon = 'ℹ';
            
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <div class="toast-close">×</div>
            `;
            
            // Add to container
            toastContainer.appendChild(toast);
            
            // Show animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Close button functionality
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode) toast.remove();
                    }, 300);
                }
            }, 5000);
        }
        
        function setupRowHighlighting() {
            // Get all editable cells
            const editableCells = document.querySelectorAll('.editable-score');
            
            // Add mouse enter/leave events to each cell
            editableCells.forEach(cell => {
                cell.addEventListener('mouseenter', function() {
                    // Find the parent row
                    const row = this.closest('tr');
                    if (row) {
                        row.classList.add('highlight-row');
                        // Add a small delay to make the transition visible
                        setTimeout(() => {
                            row.style.transition = 'background-color 0.15s ease';
                        }, 10);
                    }
                });
                
                cell.addEventListener('mouseleave', function() {
                    // Find the parent row
                    const row = this.closest('tr');
                    if (row) {
                        // Only remove highlight if not editing a cell in this row
                        if (!row.querySelector('.editing')) {
                            row.classList.remove('highlight-row');
                        }
                    }
                });
            });
        }
        
        function setupAssessmentEditing() {
            // Make all assessment scores editable
            const editableCells = document.querySelectorAll('.editable-cell');
            const editControls = document.querySelector('.edit-controls');
            const editingInfo = document.getElementById('editingInfo');
            let activeCell = null;
            let originalValue = null;
            let bulkEditMode = false;
            let bulkEditCells = [];
            
            // Add click handler to each editable cell
            editableCells.forEach(cell => {
                cell.classList.add('editable-score');
                
                cell.addEventListener('click', function(e) {
                    // Check if ctrl/command key is pressed for bulk selection
                    if (e.ctrlKey || e.metaKey) {
                        handleBulkSelection(this);
                        return;
                    }
                    
                    // If not bulk mode, proceed with single cell edit
                    bulkEditMode = false;
                    bulkEditCells = [];
                    
                    // Reset visual selection for all cells
                    editableCells.forEach(c => c.classList.remove('bulk-selected'));
                    
                    // If another cell is already being edited, cancel it
                    if (activeCell && activeCell !== this) {
                        cancelEdit(activeCell);
                    }
                    
                    // Start editing this cell
                    startEditing(this);
                });
            });
            
            function handleBulkSelection(cell) {
                bulkEditMode = true;
                
                // Toggle selection for this cell
                if (cell.classList.contains('bulk-selected')) {
                    cell.classList.remove('bulk-selected');
                    bulkEditCells = bulkEditCells.filter(c => c !== cell);
                } else {
                    // Only allow selecting cells of the same type
                    const cellType = cell.dataset.gradeType;
                    
                    // If this is first selection or matches existing type
                    if (bulkEditCells.length === 0 || bulkEditCells[0].dataset.gradeType === cellType) {
                        cell.classList.add('bulk-selected');
                        bulkEditCells.push(cell);
                    } else {
                        // Show warning that different types can't be mixed
                        showToast('Warning', 'You can only bulk edit the same type of assessments', 'error');
                    }
                }
                
                // Update controls UI
                if (bulkEditCells.length > 0) {
                    // Convert activeCell to a bulk editing interface
                    const cellType = bulkEditCells[0].dataset.gradeType;
                    let typeLabel = 'Unknown';
                    
                    if (cellType === 'written_work') typeLabel = 'Written Work';
                    if (cellType === 'performance_task') typeLabel = 'Performance Task';
                    if (cellType === 'quarterly') typeLabel = 'Quarterly Assessment';
                    
                    editingInfo.textContent = `Bulk Edit: ${typeLabel} (${bulkEditCells.length} cells)`;
                    
                    // Add input field to one of the cells
                    if (!activeCell) {
                        activeCell = document.createElement('div');
                        activeCell.classList.add('bulk-edit-container');
                        activeCell.innerHTML = `
                            <input type="number" min="0" max="100" step="1" placeholder="Enter new score for all selected cells" id="bulkEditInput">
                        `;
                        
                        // Show edit controls
                        editControls.querySelector('h6').textContent = 'Bulk Edit Scores';
                        editControls.style.display = 'block';
                        
                        // Focus input
                        setTimeout(() => {
                            const input = document.getElementById('bulkEditInput');
                            if (input) {
                                input.focus();
                                
                                // Add event listeners for input
                                input.addEventListener('keydown', function(e) {
                                    if (e.key === 'Enter') {
                                        saveBulkEdit();
                                    } else if (e.key === 'Escape') {
                                        cancelBulkEdit();
                                    }
                                });
                            }
                        }, 50);
                    }
                } else {
                    // No cells selected, cancel bulk mode
                    bulkEditMode = false;
                    if (activeCell) {
                        editControls.style.display = 'none';
                        activeCell = null;
                    }
                }
            }
            
            function startEditing(cell) {
                if (!cell) return;
                
                // Set active cell
                activeCell = cell;
                
                // Highlight the row when editing
                const row = cell.closest('tr');
                if (row) {
                    row.classList.add('highlight-row');
                }
                
                const studentId = cell.dataset.studentId;
                const subjectId = cell.dataset.subjectId;
                const quarter = cell.dataset.quarter;
                const gradeType = cell.dataset.gradeType;
                const assessmentName = cell.dataset.assessmentName;
                const assessmentIndex = cell.dataset.assessmentIndex;
                // Get max score from the data attribute or default to 100
                const maxScore = cell.dataset.maxScore || 100;
                
                // Store current value
                originalValue = cell.textContent.trim();
                
                // Debug information
                console.log(`Editing: Type: ${gradeType}, Name: ${assessmentName}, Index: ${assessmentIndex}, Max: ${maxScore}`);
                
                // Display which type of score is being edited
                let typeLabel = 'Unknown';
                if (gradeType === 'written_work') typeLabel = 'Written Work';
                if (gradeType === 'performance_task') typeLabel = 'Performance Task';
                if (gradeType === 'quarterly') typeLabel = 'Quarterly Assessment';
                
                // Update editing info display with assessment index if available
                editControls.querySelector('h6').textContent = 'Edit Score';
                if (assessmentIndex) {
                    editingInfo.textContent = `${typeLabel} #${assessmentIndex} (Max: ${maxScore})`;
                } else {
                    editingInfo.textContent = `${typeLabel} (Max: ${maxScore})`;
                }
                
                // Clear previous content and add input
                cell.classList.add('editing');
                cell.innerHTML = `<input type="number" min="0" max="${maxScore}" step="1" value="${originalValue}" 
                                    data-student-id="${studentId}"
                                    data-subject-id="${subjectId}"
                                    data-quarter="${quarter}"
                                    data-grade-type="${gradeType}"
                                    data-assessment-name="${assessmentName}"
                                    data-assessment-index="${assessmentIndex}"
                                    data-max-score="${maxScore}">`;
                
                // Focus input
                const input = cell.querySelector('input');
                input.focus();
                input.select();
                
                // Assign a tabindex for keyboard navigation
                input.tabIndex = 1;
                
                // Show edit controls
                editControls.style.display = 'block';
                
                // Add event listeners for input
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        saveEdit();
                    } else if (e.key === 'Escape') {
                        cancelEdit(activeCell);
                    } else if (e.key === 'Tab') {
                        // Find the next editable cell
                        e.preventDefault();
                        saveEdit(true); // Save and move to next
                    }
                });
                
                // Add validation for input changes
                input.addEventListener('input', function(e) {
                    const maxScore = parseFloat(this.dataset.maxScore);
                    const enteredValue = parseFloat(this.value);
                    
                    if (enteredValue > maxScore) {
                        this.classList.add('error');
                        showToast('Warning', `Score cannot exceed maximum of ${maxScore}`, 'error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            }
            
            // Save button functionality
            const saveButton = editControls.querySelector('.save');
            saveButton.addEventListener('click', function() {
                if (bulkEditMode) {
                    saveBulkEdit();
                } else {
                    saveEdit();
                }
            });
            
            // Cancel button functionality
            const cancelButton = editControls.querySelector('.cancel');
            cancelButton.addEventListener('click', function() {
                if (bulkEditMode) {
                    cancelBulkEdit();
                } else if (activeCell) {
                    cancelEdit(activeCell);
                }
            });
            
            function saveEdit(moveToNext = false) {
                if (!activeCell) return;
                
                const input = activeCell.querySelector('input');
                if (!input) return;
                
                const newValue = input.value.trim();
                const studentId = input.dataset.studentId;
                const subjectId = input.dataset.subjectId;
                const quarter = input.dataset.quarter;
                const gradeType = input.dataset.gradeType;
                const assessmentName = input.dataset.assessmentName;
                const assessmentIndex = input.dataset.assessmentIndex;
                // Use data-max-score attribute if present, otherwise default to 100
                const maxScore = parseFloat(input.dataset.maxScore) || parseFloat(activeCell.dataset.maxScore) || 100;
                
                // Debug log
                console.log(`Editing: Type: ${gradeType}, Name: ${assessmentName}, Index: ${assessmentIndex}, Max: ${maxScore}`);
                
                // Validate the score doesn't exceed maximum
                if (parseFloat(newValue) > maxScore) {
                    showToast('Error', `Score cannot exceed maximum of ${maxScore}`, 'error');
                    return;
                }
                
                // Show loading state in the cell
                activeCell.innerHTML = '<span style="opacity: 0.5">Saving...</span>';
                
                // Make AJAX request to update the score
                console.log('Sending data:', {
                    student_id: studentId,
                    subject_id: subjectId,
                    quarter: quarter,
                    assessment_type: gradeType,
                    assessment_name: assessmentName,
                    assessment_index: assessmentIndex,
                    score: newValue,
                    max_score: maxScore // Send the max score for server-side validation
                });
                
                $.ajax({
                    url: '{{ route("teacher.reports.edit-assessment") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        student_id: studentId,
                        subject_id: subjectId,
                        quarter: quarter,
                        assessment_type: gradeType,
                        assessment_name: assessmentName,
                        score: newValue,
                        max_score: maxScore // Send the max score for server-side validation
                    },
                    success: function(response) {
                        console.log('Save successful:', response);
                        // Update cell with new value
                        activeCell.textContent = newValue;
                        activeCell.classList.remove('editing');
                        
                        if (moveToNext) {
                            // Find next cell and edit it
                            const allCells = Array.from(editableCells);
                            const currentIndex = allCells.indexOf(activeCell);
                            const nextCell = allCells[currentIndex + 1];
                            
                            // Reset active cell
                            const prevActiveCell = activeCell;
                            activeCell = null;
                            
                            // Hide edit controls only if we can't find a next cell
                            if (!nextCell) {
                                editControls.style.display = 'none';
                            } else {
                                startEditing(nextCell);
                            }
                            
                            // Remove row highlighting from previous row
                            const prevRow = prevActiveCell.closest('tr');
                            if (prevRow) {
                                prevRow.classList.remove('highlight-row');
                            }
                        } else {
                            // Reset active cell
                            activeCell = null;
                            
                            // Hide edit controls
                            editControls.style.display = 'none';
                            
                            // Remove row highlighting
                            const row = document.querySelector('.highlight-row');
                            if (row) {
                                row.classList.remove('highlight-row');
                            }
                            
                            // Show toast notification - removed printing tip
                            showToast('Success', 'Score updated successfully. Page will reload in 2 seconds.', 'success');
                            
                            // Restore the reload timer to show updated grades
                            let reloadTimer = setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                            
                            // Allow canceling reload with ESC key
                            const escHandler = function(e) {
                                if (e.key === 'Escape') {
                                    clearTimeout(reloadTimer);
                                    showToast('Reload Canceled', 'Page reload stopped.', 'info');
                                    document.removeEventListener('keydown', escHandler);
                                }
                            };
                            
                            document.addEventListener('keydown', escHandler);
                        }
                    },
                    error: function(xhr) {
                        console.error('Save error:', xhr);
                        let errorMessage = 'Error updating score';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        // Show error toast
                        showToast('Error', errorMessage, 'error');
                        
                        // Restore original value
                        cancelEdit(activeCell);
                    }
                });
            }
            
            function saveBulkEdit() {
                if (bulkEditCells.length === 0) return;
                
                const input = document.getElementById('bulkEditInput');
                if (!input) return;
                
                const newValue = input.value.trim();
                if (!newValue) {
                    showToast('Error', 'Please enter a value', 'error');
                    return;
                }
                
                // Get the type of assessment from the first cell
                const firstCell = bulkEditCells[0];
                const gradeType = firstCell.dataset.gradeType;
                const assessmentName = firstCell.dataset.assessmentName;
                
                // Find the max score for this assessment type
                let maxScoreValue = 100; // Default fallback
                
                // Get max score based on assessment type
                if (gradeType === 'written_work') {
                    const writtenWorks = @json($writtenWorks);
                    const assessment = writtenWorks.find(w => w.assessment_name === assessmentName);
                    if (assessment) maxScoreValue = assessment.max_score;
                } else if (gradeType === 'performance_task') {
                    const performanceTasks = @json($performanceTasks);
                    const assessment = performanceTasks.find(t => t.assessment_name === assessmentName);
                    if (assessment) maxScoreValue = assessment.max_score;
                } else if (gradeType === 'quarterly') {
                    const quarterlyAssessments = @json($quarterlyAssessments);
                    if (quarterlyAssessments.length > 0) {
                        maxScoreValue = quarterlyAssessments[0].max_score;
                    }
                }
                
                // Validate the score doesn't exceed maximum
                if (parseFloat(newValue) > maxScoreValue) {
                    showToast('Error', `Score cannot exceed maximum of ${maxScoreValue}`, 'error');
                    return;
                }
                
                // Show loading message
                showToast('Processing', `Updating ${bulkEditCells.length} scores...`, 'info');
                
                // Close edit controls
                editControls.style.display = 'none';
                
                // Create an array of promises for each update
                const updatePromises = bulkEditCells.map(cell => {
                    return new Promise((resolve, reject) => {
                        // Visual feedback
                        cell.innerHTML = '<span style="opacity: 0.5">Saving...</span>';
                        
                        // Get cell data
                        const studentId = cell.dataset.studentId;
                        const subjectId = cell.dataset.subjectId;
                        const quarter = cell.dataset.quarter;
                        const cellAssessmentName = cell.dataset.assessmentName;
                        
                        // Make AJAX request
                        $.ajax({
                            url: '{{ route("teacher.reports.edit-assessment") }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: {
                                student_id: studentId,
                                subject_id: subjectId,
                                quarter: quarter,
                                assessment_type: gradeType,
                                assessment_name: cellAssessmentName,
                                score: newValue,
                                max_score: maxScoreValue // Send max score for validation
                            },
                            success: function(response) {
                                // Update cell with new value
                                cell.textContent = newValue;
                                cell.classList.remove('bulk-selected');
                                resolve();
                            },
                            error: function(xhr) {
                                let errorMessage = 'Error updating score';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                cell.textContent = originalValue || '';
                                cell.classList.remove('bulk-selected');
                                reject(errorMessage);
                            }
                        });
                    });
                });
                
                // Process all updates
                Promise.allSettled(updatePromises).then(results => {
                    const successful = results.filter(r => r.status === 'fulfilled').length;
                    const failed = results.filter(r => r.status === 'rejected').length;
                    
                    if (failed > 0) {
                        showToast('Partial Success', `Updated ${successful} scores, ${failed} failed`, 'error');
                    } else {
                        showToast('Success', `All ${successful} scores updated successfully. Page will reload in 2 seconds.`, 'success');
                    }
                    
                    // Reset bulk mode
                    bulkEditMode = false;
                    bulkEditCells = [];
                    activeCell = null;
                    
                    // Restore reload timer to show recalculated grades
                    window.pageReloadTimer = setTimeout(() => {
                        location.reload();
                    }, 2000);
                    
                    // Allow cancelling the reload with ESC key
                    document.addEventListener('keydown', function escHandler(e) {
                        if (e.key === 'Escape' && window.pageReloadTimer) {
                            clearTimeout(window.pageReloadTimer);
                            window.pageReloadTimer = null;
                            showToast('Reload Cancelled', 'Page reload stopped.', 'info');
                            document.removeEventListener('keydown', escHandler);
                        }
                    });
                });
            }
            
            function cancelBulkEdit() {
                // Reset all the selected cells
                bulkEditCells.forEach(cell => {
                    cell.classList.remove('bulk-selected');
                });
                
                bulkEditMode = false;
                bulkEditCells = [];
                activeCell = null;
                
                // Hide edit controls
                editControls.style.display = 'none';
            }
            
            function cancelEdit(cell) {
                if (!cell) return;
                
                // Restore original text
                cell.textContent = originalValue;
                cell.classList.remove('editing');
                
                // Remove row highlight
                const row = cell.closest('tr');
                if (row) {
                    row.classList.remove('highlight-row');
                }
                
                // Hide edit controls
                editControls.style.display = 'none';
                
                // Reset active cell
                activeCell = null;
            }
        }
    </script>

    <!-- Add direct print function -->
    <script>
        function printPage() {
            try {
                // Cancel any pending page reload timer
                if (window.pageReloadTimer) {
                    clearTimeout(window.pageReloadTimer);
                    window.pageReloadTimer = null;
                }
                
                // Hide any modals or controls before printing
                const helpModal = document.getElementById('helpModal');
                const editControls = document.querySelector('.edit-controls');
                const toastContainer = document.querySelector('.toast-container');
                const actionButtons = document.querySelector('.action-buttons');
                const closeButton = document.querySelector('.btn-print');
                const allNoprint = document.querySelectorAll('.no-print');
                
                // Force hide all no-print elements
                allNoprint.forEach(el => {
                    el.style.display = 'none';
                });
                
                // Hide specific buttons and controls
                if (helpModal) helpModal.style.display = 'none';
                if (editControls) editControls.style.display = 'none';
                if (toastContainer) toastContainer.innerHTML = '';
                if (actionButtons) actionButtons.style.display = 'none';
                if (closeButton) closeButton.style.display = 'none';
                
                // Add one-time print stylesheet
                document.body.insertAdjacentHTML('beforeend', 
                    '<style id="temp-print-styles">.no-print{display:none !important} .btn-print{display:none !important} #helpButton{display:none !important}</style>');
                
                // Directly trigger print
                setTimeout(() => {
                    window.print();
                    
                    // Remove the temporary print styles after printing
                    setTimeout(() => {
                        const tempStyles = document.getElementById('temp-print-styles');
                        if (tempStyles) tempStyles.remove();
                        
                        // Remove this toast notification
                        // showToast('Print', 'Print dialog opened', 'success');
                    }, 500);
                }, 100);
            } catch (e) {
                // If there's an error, show it
                showToast('Error', 'Could not open print dialog: ' + e.message, 'error');
            }
        }
    </script>

    <!-- Add this script after the existing scripts -->
    <script>
        function simulateEscKey() {
            // Create and dispatch Escape key event
            const escEvent = new KeyboardEvent('keydown', {
                key: 'Escape',
                code: 'Escape',
                keyCode: 27,
                which: 27,
                bubbles: true
            });
            document.dispatchEvent(escEvent);
            
            // Show a message
            showToast('Reload Canceled', 'Page reload stopped. You can now print the report.', 'info');
        }
    </script>
</body>
</html> 