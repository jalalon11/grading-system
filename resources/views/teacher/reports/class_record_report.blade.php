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

            /* Optimize tables for print */
            table {
                font-size: 8pt !important;
            }
            th, td {
                padding: 2px !important;
                font-size: 8pt !important;
                white-space: nowrap !important;
            }
            .column-header {
                padding: 1px !important;
                font-size: 8pt !important;
            }

            /* Reduce column widths */
            th[rowspan="2"][style*="width: 20%"] {
                width: 15% !important;
            }
            th[colspan="10"][style*="width: 25%"] {
                width: 30% !important;
            }
            th[colspan="11"][style*="width: 35%"] {
                width: 35% !important;
            }
            th[colspan="3"][style*="width: 10%"] {
                width: 10% !important;
            }
            th[rowspan="2"][style*="width: 5%"] {
                width: 5% !important;
            }

            /* Optimize header, reduce its size */
            .header {
                margin-bottom: 5px !important;
            }
            .title-center h1 {
                font-size: 14pt !important;
                margin-bottom: 2px !important;
            }
            .title-center p {
                font-size: 9pt !important;
            }
            .logo-left img, .logo-right img {
                max-height: 60px !important;
            }

            /* Remove extra margins and padding */
            .info-table {
                margin-bottom: 5px !important;
            }
            .signature-section {
                margin-top: 10px !important;
            }
            .gender-label {
                padding: 3px 5px !important;
                font-size: 9pt !important;
            }
            .text-left {
                padding-left: 5px !important;
                font-size: 8pt !important;
            }

            /* Compress table-responsive margin */
            .table-responsive {
                margin-bottom: 5px !important;
            }
        }

        /* Mobile Responsive Styles */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
                font-size: 11px;
            }
            .container {
                width: 100%;
                max-width: 100%;
                overflow-x: auto;
            }
            .header {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-top: 60px !important; /* Give more space at the top */
            }
            /* Hide the container div for the close button in mobile view */
            .close-button-container {
                margin: 0;
                height: 0;
                overflow: visible;
            }
            .header-row {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            .logo-left, .logo-right {
                display: inline-block;
                width: 100%;
                margin-bottom: 10px;
            }
            .title-center {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
            .title-center h1 {
                font-size: 18px;
            }
            .title-center p {
                font-size: 12px;
            }
            /* Make the table header row larger on mobile */
            .table-header {
                padding: 8px 4px !important;
                font-size: 10px !important;
                white-space: normal !important;
                height: auto !important;
            }
            table[style*="table-layout: fixed"] td {
                white-space: normal !important;
                height: auto !important;
                padding: 8px 4px !important;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                max-width: 100%;
                display: block;
            }
            .info-table {
                table-layout: auto;
            }
            .info-label {
                width: 30%;
                font-size: 10px;
            }
            .info-value {
                width: 70%;
                font-size:.9em;
            }
            th, td {
                padding: 3px;
                font-size: 9pt;
                white-space: nowrap;
            }
            .signature-section {
                flex-direction: column;
            }
            .signature-item {
                margin: 10px 0;
            }
            .signature-line {
                width: 150px;
            }
            .scroll-indicator {
                display: block;
                text-align: center;
                color: #666;
                margin: 5px 0;
                font-style: italic;
            }
            /* Make sure buttons are big enough to tap on mobile */
            button, .btn {
                min-height: 44px;
                min-width: 44px;
                padding: 10px 15px;
            }
            /* Modal styling for mobile */
            .modal-content {
                width: 95% !important;
                max-width: 95% !important;
                margin: 10px auto !important;
                max-height: 90vh !important;
                overflow-y: auto !important;
            }
            /* Action buttons styling */
            .action-buttons {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 999;
                display: flex;
                flex-direction: column;
            }
            .action-buttons button {
                margin-bottom: 10px;
                border-radius: 50%;
                width: 56px;
                height: 56px;
                display: flex;
                justify-content: center;
                align-items: center;
                box-shadow: 0 3px 5px rgba(0,0,0,0.3);
            }
            /* Make the close button sticky in mobile view */
            .btn-print {
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 999;
                box-shadow: 0 3px 5px rgba(0,0,0,0.3);
            }
            /* Disable fixed positioning for print view */
            @media print {
                .action-buttons, .btn-print {
                    position: static;
                    display: none !important;
                }
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
            font-weight: bold;
            border: 1px solid #000;
        }

        /* Enhanced table header styling */
        .table-header {
            font-weight: bold;
            text-align: center;
            background-color: #e0e0e0;
            border: 1px solid #000;
        }

        /* Additional styling for column headers in the main grades table */
        .column-header {
            background-color: #f8f8f8;
            font-weight: bold;
            text-align: center;
            padding: 3px;
            border: 1px solid #000;
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
            background-color: #e0e0e0;
            padding-right: 10px;
            border: 1px solid #000;
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
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
        .signature-item {
            text-align: center;
            margin: 0 10px;
            flex: 1;
        }
        .signature-line {
            border-top: 1px solid black;
            width: 150px;
            margin: 30px auto 0;
        }
        /* Add table responsive wrapper class */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }
        /* Float action buttons for mobile - hidden by default */
        .float-action-button {
            display: none !important;
        }
        /* Helper classes for responsive design */
        .d-none {
            display: none;
        }
        .d-block {
            display: block;
        }

        /* Gender label styling for male and female rows */
        .gender-label {
            font-weight: bold;
            text-align: left;
            padding: 5px 8px;
            background-color: #d9d9d9;
            color: #000;
            font-size: 11pt;
            border: 1px solid #000;
        }

        /* Close button styling */
        .btn-print {
            padding: 12px 20px !important;
            font-size: 16px !important;
            min-height: 44px;
            min-width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px auto;
            box-shadow: 0 3px 6px rgba(0,0,0,0.16);
        }

        @media screen and (min-width: 769px) {
            .d-md-none {
                display: none;
            }
            .d-md-inline {
                display: inline;
            }
            .action-buttons {
                position: fixed;
                top: 10px;
                right: 10px;
                z-index: 1000;
                display: flex;
                gap: 10px;
            }
            /* Hide floating button on desktop */
            .float-action-button {
                display: none !important;
            }
        }

        @media screen and (max-width: 768px) {
            .d-md-none {
                display: block;
            }
            .d-md-inline {
                display: none;
            }
            .action-buttons {
                position: fixed;
                top: 10px;
                right: 10px;
                z-index: 1000;
                display: flex;
                gap: 5px;
            }
            .action-buttons button {
                padding: 8px !important;
                min-width: 44px;
                min-height: 44px;
            }
            /* Improve header spacing in mobile view */
            .header {
                margin-top: 40px;
                padding: 0 5px;
            }
            .header-row {
                gap: 15px;
            }
            .title-center h1 {
                font-size: 16px;
                margin-bottom: 5px;
            }
            .title-center p {
                font-size: 11px;
            }
            .logo-left img, .logo-right img {
                max-width: 60px;
            }
            /* Ensure float button is visible */
            .float-action-button {
                display: none !important;
            }
            body {
                padding: 10px;
                font-size: 11px;
            }
            .container {
                width: 100%;
                max-width: 100%;
                overflow-x: auto;
            }
            .header {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .header-row {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            .logo-left, .logo-right {
                display: inline-block;
                width: 100%;
                margin-bottom: 10px;
            }
            .title-center {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
            .title-center h1 {
                font-size: 18px;
            }
            .title-center p {
                font-size: 12px;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                max-width: 100%;
                display: block;
            }
            .info-table {
                table-layout: auto;
            }
            .info-label {
                width: 30%;
                font-size: 10px;
            }
            .info-value {
                width: 70%;
                font-size:.9em;
            }
            th, td {
                padding: 3px;
                font-size: 9pt;
                white-space: nowrap;
            }
            .signature-section {
                flex-direction: column;
            }
            .signature-item {
                margin: 10px 0;
            }
            .signature-line {
                width: 150px;
            }
            .scroll-indicator {
                display: block;
                text-align: center;
                color: #666;
                margin: 5px 0;
                font-style: italic;
            }
            /* Make sure buttons are big enough to tap on mobile */
            button, .btn {
                min-height: 44px;
                min-width: 44px;
                padding: 10px 15px;
            }
            /* Modal styling for mobile */
            .modal-content {
                width: 95% !important;
                max-width: 95% !important;
                margin: 10px auto !important;
                max-height: 90vh !important;
                overflow-y: auto !important;
            }
            /* Action buttons styling */
            .action-buttons {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 999;
                display: flex;
                flex-direction: column;
            }
            .action-buttons button {
                margin-bottom: 10px;
                border-radius: 50%;
                width: 56px;
                height: 56px;
                display: flex;
                justify-content: center;
                align-items: center;
                box-shadow: 0 3px 5px rgba(0,0,0,0.3);
            }
            /* Disable fixed positioning for print view */
            @media print {
                .action-buttons {
                    position: static;
                    display: none !important;
                }
            }
        }

        /* Add editable score styles */
        .editable-score {
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            min-width: 40px; /* Base minimum width to display 2 digits */
        }

        .editable-score:hover {
            background-color: #f5f9ff;
            box-shadow: inset 0 0 0 1px #4dabf7;
        }

        .editable-score.editing {
            padding: 0;
            background-color: #e8f4ff;
            box-shadow: inset 0 0 0 2px #4dabf7;
            min-width: 50px !important; /* Ensure room for 2 digits */
        }

        .editable-score.editing input {
            width: 100%;
            min-width: 45px; /* Minimum width for 2 digits */
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

        /* Mobile-friendly edit controls */
        @media screen and (max-width: 768px) {
            .edit-controls {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                border-radius: 8px 8px 0 0;
                padding: 10px;
                background-color: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(5px);
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                display: flex;
                flex-direction: column;
                align-items: center;
                z-index: 1010;
                /* Fix for Android sticky positioning */
                transform: translateZ(0);
                -webkit-transform: translateZ(0);
            }

            .edit-controls h6 {
                font-size: 12px;
                margin-bottom: 5px;
                padding-bottom: 5px;
            }

            .edit-controls .status {
                font-size: 11px;
                margin-bottom: 8px;
            }

            .edit-controls .actions {
                width: 100%;
                justify-content: space-between;
            }

            .edit-controls button {
                padding: 6px 5px;
                font-size: 12px;
            }

            /* Ensure table doesn't get hidden behind edit controls */
            .table-responsive {
                padding-bottom: 60px;
            }

            /* Make input larger for easier touch on mobile */
            .editable-score.editing input {
                font-size: 16px;
                min-height: 36px;
                min-width: 50px; /* Ensure at least 2 digits are visible */
                width: auto; /* Allow input to grow based on content */
            }

            /* Enhanced mobile cell editing */
            .editable-score.mobile-editing {
                position: relative;
                z-index: 1001;
                background-color: #E3F2FD !important;
                box-shadow: 0 0 0 3px #2196F3 !important;
                min-width: 50px; /* Ensure the cell is wide enough */
            }

            /* Make the cell being edited more visible */
            tr.highlight-row {
                background-color: rgba(200, 230, 255, 0.2) !important;
            }

            /* Focus visible indicator for accessibility */
            .editable-score.editing input:focus {
                box-shadow: inset 0 0 0 2px #2196F3;
                outline: none;
            }

            /* More space for input in edited cell */
            .editable-score.editing {
                padding: 0 !important;
                min-width: 50px !important; /* Ensure width for 2+ digits */
            }

            /* Better validation display for Android */
            .toast-container {
                bottom: 80px; /* Place above the edit controls */
                top: auto;
                z-index: 1020; /* Higher than edit controls */
            }

            .toast {
                opacity: 1 !important; /* Ensure visibility */
                min-width: 80%; /* Make toast wider */
                max-width: 90%;
                margin: 0 auto;
                transform: none !important; /* Prevent transform issues */
                right: 0;
                left: 0;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.2); /* Stronger shadow */
            }
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

        /* Student name styling */
        .text-left {
            text-align: left !important;
            padding-left: 10px !important;
        }

        @media print {
            /* Ensure table headers maintain background color when printing */
            th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table-header {
                background-color: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .info-label {
                background-color: #e0e0e0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-weight: bold !important;
            }

            .column-header {
                background-color: #f8f8f8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Gender label coloring */
            .gender-label {
                background-color: #d9d9d9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Make signature section more compact in print */
            .signature-section {
                max-width: 80%;
                margin-top: 15px;
            }
            .signature-item {
                margin: 0 5px;
            }
            .signature-line {
                width: 120px;
                margin: 15px auto 0;
            }
            .signature-item p {
                margin: 3px 0;
                font-size: 9pt !important;
            }
            .signature-item p[style*="margin-bottom"] {
                margin-bottom: 15px !important;
            }
        }

        /* Print layout exclusions - ensure mobile changes don't affect printing */
        @media print {
            .edit-controls {
                display: none !important;
            }

            .editable-score,
            .editable-score.mobile-editing,
            .editable-score.editing {
                min-width: initial !important;
                width: auto !important;
                padding: initial !important;
                background-color: transparent !important;
                box-shadow: none !important;
            }

            tr.highlight-row {
                background-color: transparent !important;
            }

            .table-responsive {
                padding-bottom: 0 !important;
            }

            .toast-container {
                display: none !important;
            }

            /* Ensure table cells have consistent sizing */
            td, th {
                padding: 2px !important;
                font-size: 9pt !important;
            }
        }

        .edit-controls h6 {
            margin: 0 0 10px 0;
            padding-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #e9ecef;
            color: #343a40;
        }
    </style>
</head>
<body>

    <!-- Action buttons with refresh button added -->
    <div class="action-buttons no-print">
        <button onclick="printPage(); return false;" class="print-button" style="padding: 8px 15px; background: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <span style="margin-right: 5px;">🖨️</span> <span class="d-none d-md-inline">Print Now</span>
        </button>
        <button id="helpButton" class="help-button" style="padding: 8px 15px; background: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <span style="margin-right: 5px;">ℹ️</span> <span class="d-none d-md-inline">Help</span>
        </button>
        <button id="gradeRangesButton" class="grade-ranges-button" style="padding: 8px 15px; background: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer;">
            <span style="margin-right: 5px;">📊</span> <span class="d-none d-md-inline">Students by Grade Range</span>
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

    <!-- Close button container -->
    <div style="margin: 10px 0; text-align: left;" class="close-button-container">
        <button class="btn-print" onclick="closeReport();" style="padding: 12px 20px; background-color: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: 500; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s ease; min-height: 44px; min-width: 120px; display: inline-block; text-align: center;">Close</button>
    </div>

    <script>
        function closeReport() {
            console.log('Closing report...');
            // Try multiple methods to close the window
            try {
                // Method 1: Try window.close() if opened by JavaScript
                if (window.opener) {
                    window.close();
                    return;
                }

                // Method 2: Check if we're in an iframe
                if (window.parent && window.parent !== window) {
                    window.parent.postMessage('close-iframe', '*');
                    return;
                }

                // Method 3: Go back in history if possible
                if (window.history.length > 1) {
                    window.history.back();
                    return;
                }

                // Method 4: Fallback to redirect
                window.location.href = '{{ route("teacher.reports.class-record") }}';
            } catch (e) {
                console.error('Error closing window:', e);
                // Final fallback
                window.location.href = '{{ route("teacher.reports.class-record") }}';
            }
        }
    </script>

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
                        <img src="{{ $section->school->logo_url }}" alt="School Logo" style="max-height: 80px;">
                    @else
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNDAiIHk9IjQwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNhYWFhYWEiPkxvZ288L3RleHQ+PC9zdmc+" alt="School Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1Ii8+PHRleHQgeD0iNDAiIHk9IjQwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNhYWFhYWEiPkxvZ288L3RleHQ+PC9zdmc+'">
                    @endif
                </div>
                <div class="title-center">
                    <h1>Class Record</h1>
                    <p>(Pursuant to DepED Order 8 series of 2015)</p>
                </div>
                <div class="logo-right">
                    <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            {{-- <p class="scroll-indicator d-md-none">Swipe horizontally to view all information</p> --}}
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
        </div>

        <div class="table-responsive">
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
        </div>

        <div class="table-responsive">
            {{-- <p class="scroll-indicator d-md-none">Swipe horizontally to view all grades</p> --}}
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
                    <td class="gender-label" colspan="27" style="text-align: left; background-color: #d9d9d9; color: #000; font-size: 11pt; padding: 5px 8px; font-weight: normal; border: 1px solid #000;">MALE</td>
                </tr>
                @php
                    $maleStudents = $students->where('gender', 'Male')->sortBy('last_name');
                    error_log('Male students found: ' . $maleStudents->count());
                    foreach ($maleStudents as $m) {
                        error_log('Male student: ' . $m->first_name . ' ' . $m->last_name . ' (ID: ' . $m->id . ')');
                    }
                @endphp
                @forelse($maleStudents as $maleIndex => $student)
                    <tr>
                        <td class="text-left" style="text-align: left; padding-left: 10px;">{{ $maleIndex + 1 }}. {{ $student->last_name }}, {{ $student->first_name }}</td>

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

                            // Calculate total max score for written works
                            $writtenWorksMaxTotal = 0;
                            foreach($writtenWorks->take(7) as $work) {
                                $writtenWorksMaxTotal += $work->max_score;
                            }

                            if ($writtenWorksMaxTotal > 0 && $studentWWTotal > 0) {
                                // Calculate PS (Percentage Score) - total score divided by total max score
                                $wwPS = ($studentWWTotal / $writtenWorksMaxTotal) * 100;

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

                            // Calculate total max score for performance tasks
                            $performanceTasksMaxTotal = 0;
                            foreach($performanceTasks->take(8) as $task) {
                                $performanceTasksMaxTotal += $task->max_score;
                            }

                            if ($performanceTasksMaxTotal > 0 && $studentPTTotal > 0) {
                                // Calculate PS (Percentage Score) - total score divided by total max score
                                $ptPS = ($studentPTTotal / $performanceTasksMaxTotal) * 100;

                                // Calculate WS (Weighted Score) - apply the weight percentage
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
                    <td class="gender-label" colspan="27" style="text-align: left; background-color: #d9d9d9; color: #000; font-size: 11pt; padding: 5px 8px; font-weight: normal; border: 1px solid #000;">FEMALE</td>
                </tr>
                @php $femaleStudents = $students->where('gender', 'Female')->sortBy('last_name'); @endphp
                @forelse($femaleStudents as $femaleIndex => $student)
                    <tr>
                        <td class="text-left" style="text-align: left; padding-left: 10px;">{{ $femaleIndex + 1 }}. {{ $student->last_name }}, {{ $student->first_name }}</td>

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

                            // Calculate total max score for written works
                            $writtenWorksMaxTotal = 0;
                            foreach($writtenWorks->take(7) as $work) {
                                $writtenWorksMaxTotal += $work->max_score;
                            }

                            if ($writtenWorksMaxTotal > 0 && $studentWWTotal > 0) {
                                // Calculate PS (Percentage Score) - total score divided by total max score
                                $wwPS = ($studentWWTotal / $writtenWorksMaxTotal) * 100;

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

                            // Calculate total max score for performance tasks
                            $performanceTasksMaxTotal = 0;
                            foreach($performanceTasks->take(8) as $task) {
                                $performanceTasksMaxTotal += $task->max_score;
                            }

                            if ($performanceTasksMaxTotal > 0 && $studentPTTotal > 0) {
                                // Calculate PS (Percentage Score) - total score divided by total max score
                                $ptPS = ($studentPTTotal / $performanceTasksMaxTotal) * 100;
                                // Calculate WS (Weighted Score) - apply the weight percentage
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
        </div>

        <div class="signature-section">
            <div class="signature-item">
                <p style="margin-bottom: 30px;">Prepared by:</p>
                <div class="signature-line"></div>
                <p><strong>{{ auth()->user()->name ?? 'Teacher Name' }}</strong></p>
                <p>Section Adviser</p>
            </div>

            <div class="signature-item">
                <p style="margin-bottom: 30px;">Checked by:</p>
                <div class="signature-line"></div>
                <p><strong>{{ $section->coordinator_name ?? 'Coordinator Name' }}</strong></p>
                <p>Subject Coordinator</p>
            </div>

            <div class="signature-item">
                <p style="margin-bottom: 30px;">Approved by:</p>
                <div class="signature-line"></div>
                <p><strong>{{ $principal }}</strong></p>
                <p>School Principal</p>
            </div>
        </div>
    </div>

    <!-- Mobile Floating Action Buttons -->
    <!-- Removed print button as requested -->

    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Add swipe indicators for mobile
            if (window.innerWidth <= 768) {
                const tables = document.querySelectorAll('.table-responsive');
                tables.forEach(table => {
                    if (table.scrollWidth > table.clientWidth) {
                        const indicator = table.querySelector('.scroll-indicator');
                        if (indicator) {
                            indicator.style.display = 'block';
                        }
                    }
                });

                // Print button code removed per request
            }
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
        // iOS detection function
        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        }

        // Determine request method and URL based on device
        const requestMethod = isIOS() ? 'GET' : 'POST';
        const requestUrl = isIOS() ?
            '{{ route("teacher.reports.edit-assessment-get") }}' :
            '{{ route("teacher.reports.edit-assessment") }}';

        // Log device information for debugging
        console.log('Device detection:', {
            isIOS: isIOS(),
            userAgent: navigator.userAgent,
            requestMethod: requestMethod,
            requestUrl: requestUrl
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enable edit mode for all editable cells
            setupAssessmentEditing();

            // Setup row highlighting
            setupRowHighlighting();

            // Check if mobile and adjust UI accordingly
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                // Add class to body for mobile-specific styling
                document.body.classList.add('mobile-view');
            }

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

            // Add grade ranges button event listener
            const gradeRangesButton = document.getElementById('gradeRangesButton');
            if (gradeRangesButton) {
                gradeRangesButton.addEventListener('click', function() {
                    fetchStudentsByGradeRanges();
                });
            }
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
                <div class="toast-close" onclick="this.parentNode.remove()">&times;</div>
            `;

            // Add to container
            toastContainer.appendChild(toast);

            // Fade in
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Remove after delay (longer for errors)
            const removeDelay = type === 'error' ? 8000 : 5000;
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, removeDelay);
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
                                    style="min-width: 50px; width: auto;"
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

                // Check if on mobile and scroll to cell if needed
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    // Ensure cell is visible by scrolling to it
                    cell.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Add mobile edit class to highlight
                    cell.classList.add('mobile-editing');

                    // Create a compact student + assessment identifier
                    const studentName = row.querySelector('td:first-child')?.textContent.trim() || 'Student';
                    editingInfo.textContent = `${studentName}: ${typeLabel} (Max: ${maxScore})`;
                }

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
                    const value = parseFloat(this.value) || 0;
                    if (value > maxScore) {
                        this.setCustomValidity(`Score cannot exceed maximum of ${maxScore}`);
                        this.style.borderColor = '#f44336';
                        this.style.backgroundColor = 'rgba(244, 67, 54, 0.05)';
                    } else {
                        this.setCustomValidity('');
                        this.style.borderColor = '';
                        this.style.backgroundColor = '';
                    }

                    // Ensure the input is sized appropriately to show at least 2 digits
                    if (this.value.length < 2) {
                        this.style.width = '50px';
                    } else {
                        this.style.width = (this.value.length * 25) + 'px';
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
                    url: requestUrl,
                    method: requestMethod,
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
                        activeCell.classList.remove('mobile-editing');

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
                            url: requestUrl,
                            method: requestMethod,
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

                // Restore original value
                cell.innerHTML = originalValue;
                cell.classList.remove('editing');
                cell.classList.remove('mobile-editing');

                // Remove row highlighting
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

        function fetchStudentsByGradeRanges() {
            // Show loading message
            showToast('Loading', 'Generating student lists by grade ranges...', 'info');

            // Get the section, subject, and quarter from the page
            const sectionId = '{{ $section->id }}';
            const subjectId = '{{ $subject->id }}';
            const quarter = '{{ $quarter }}';

            // Make AJAX request to get students by grade ranges
            $.ajax({
                url: '{{ route("teacher.reports.students-by-grade-ranges") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    section_id: sectionId,
                    subject_id: subjectId,
                    quarter: quarter
                },
                success: function(response) {
                    // Close any existing toast
                    if (typeof hideToasts === 'function') {
                        hideToasts();
                    }

                    // Create modal for displaying results
                    createGradeRangesModal(response);
                },
                error: function(xhr) {
                    console.error('Error fetching students by grade ranges:', xhr);
                    let errorMessage = 'Error generating student lists';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    // Show error toast
                    showToast('Error', errorMessage, 'error');
                }
            });
        }

        function createGradeRangesModal(data) {
            // Create modal container if it doesn't exist
            let modal = document.getElementById('gradeRangesModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'gradeRangesModal';
                modal.className = 'modal';
                document.body.appendChild(modal);
            }

            // Create modal content
            let modalContent = `
                <div class="modal-content" style="max-width: 90%; max-height: 90%; overflow-y: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 4px;">
                    <div class="modal-header" style="background-color: #f5f5f5; padding: 15px 20px; border-bottom: 1px solid #e0e0e0;">
                        <h2 style="margin: 0; color: #333; font-size: 1.5rem;">Students by Grade Range - ${data.subject.name} (${data.quarter})</h2>
                        <span class="modal-close" style="font-size: 24px; color: #666;">&times;</span>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; margin-bottom: 15px; background-color: #f9f9f9; padding: 15px; border-radius: 4px; border-left: 3px solid #5f6163;">
                            <p style="margin: 0 30px 0 0;"><strong>Section:</strong> ${data.section.name}</p>
                            <p style="margin: 0 30px 0 0;"><strong>Subject:</strong> ${data.subject.name}</p>
                            <p style="margin: 0;"><strong>Quarter:</strong> ${data.quarter}</p>
                        </div>
            `;

            // Add summary statistics at the top
            let totalStudents = 0;
            let countByRanges = {};

            for (const [range, students] of Object.entries(data.ranges)) {
                totalStudents += students.length;
                countByRanges[range] = students.length;
            }

            modalContent += `
                <div class="range-summary" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
            `;

            for (const [range, count] of Object.entries(countByRanges)) {
                const percentage = totalStudents > 0 ? (count / totalStudents * 100).toFixed(1) : 0;

                modalContent += `
                    <div style="flex: 1; min-width: 120px; background-color: #f5f5f5; padding: 10px; border-radius: 4px; text-align: center; border: 1px solid #e0e0e0;">
                        <div style="font-weight: bold; margin-bottom: 5px;">${range}</div>
                        <div style="font-size: 1.2rem;">${count} students</div>
                        <div style="font-size: 0.9rem; color: #555;">${percentage}%</div>
                    </div>
                `;
            }

            modalContent += `
                </div>
            `;

            // Add each range with improved but simplified styling
            for (const [range, students] of Object.entries(data.ranges)) {
                // Skip empty ranges in print mode by adding a print-hide class
                const printHideClass = students.length === 0 ? 'print-hide-empty' : '';

                modalContent += `
                    <div class="grade-range ${printHideClass}" style="margin-bottom: 25px;">
                        <h3 style="margin-top: 0; padding: 10px; background-color: #f5f5f5; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e0e0e0;">
                            <span>Grade Range: ${range}</span>
                            <span style="font-size: 0.9rem;">${students.length} students</span>
                        </h3>
                `;

                if (students.length === 0) {
                    modalContent += `<p style="padding: 10px; text-align: center; color: #666; font-style: italic;">No students in this range.</p>`;
                } else {
                    modalContent += `
                        <table class="range-table" style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border: 1px solid #e0e0e0;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 12px; border: 1px solid #e0e0e0; text-align: left; width: 50%;">Name</th>
                                    <th style="padding: 12px; border: 1px solid #e0e0e0; text-align: left; width: 20%;">Gender</th>
                                    <th style="padding: 12px; border: 1px solid #e0e0e0; text-align: center; width: 30%;">Quarterly Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    students.forEach((item, index) => {
                        const student = item.student;
                        const rowBg = index % 2 === 0 ? '#ffffff' : '#f9f9f9';

                        modalContent += `
                            <tr style="background-color: ${rowBg};">
                                <td style="padding: 10px; border: 1px solid #e0e0e0; font-weight: 500;">${student.last_name}, ${student.first_name} ${student.middle_name ? student.middle_name.charAt(0) + '.' : ''}</td>
                                <td style="padding: 10px; border: 1px solid #e0e0e0;">${student.gender}</td>
                                <td style="padding: 10px; border: 1px solid #e0e0e0; text-align: center; font-weight: bold;">${item.grade}</td>
                            </tr>
                        `;
                    });

                    modalContent += `
                            </tbody>
                        </table>
                    `;
                }

                modalContent += `</div>`;
            }

            modalContent += `
                    </div>
                    <div class="modal-footer" style="padding: 15px 20px; background-color: #f5f5f5; border-top: 1px solid #e0e0e0; text-align: right;">
                        <button id="printRangesBtn" class="modal-btn" style="background-color: #5f6163; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px; font-weight: 500;">Print Report</button>
                        <button id="closeRangesBtn" class="modal-btn" style="background-color: #f1f1f1; color: #333; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Close</button>
                    </div>
                </div>
            `;

            // Set modal content
            modal.innerHTML = modalContent;

            // Add modal styling if not already present
            if (!document.getElementById('modalStyles')) {
                const modalStyles = document.createElement('style');
                modalStyles.id = 'modalStyles';
                modalStyles.textContent = `
                    .modal {
                        display: block;
                        position: fixed;
                        z-index: 1000;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        overflow: auto;
                        background-color: rgba(0,0,0,0.4);
                    }
                    .modal-content {
                        background-color: #fff;
                        margin: 5% auto;
                        width: 90%;
                        max-width: 1200px;
                    }
                    .modal-close:hover {
                        color: #000;
                        cursor: pointer;
                    }
                    .modal-btn:hover {
                        opacity: 0.9;
                    }
                    @media print {
                        body * {
                            visibility: hidden;
                        }
                        .modal, .modal * {
                            visibility: visible;
                        }
                        .print-hide-empty {
                            display: none !important;
                        }
                        .modal {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 100%;
                            height: auto;
                            margin: 0;
                            padding: 0;
                            overflow: visible;
                            background-color: white;
                        }
                        .modal-content {
                            margin: 0;
                            padding: 0;
                            border: none;
                            width: 100%;
                            max-width: 100%;
                        }
                        .modal-header, .modal-footer, .modal-close {
                            display: none;
                        }
                        .range-table {
                            page-break-inside: avoid;
                            border-collapse: collapse;
                            width: 100%;
                            font-size: 11px;
                        }
                        .range-table th {
                            background-color: #f5f5f5 !important;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                            padding: 5px !important;
                        }
                        .range-table td {
                            padding: 4px !important;
                        }
                        .range-summary {
                            display: none !important;
                        }
                        .grade-range {
                            page-break-inside: avoid;
                            margin-bottom: 10px;
                        }
                        .grade-range h3 {
                            page-break-after: avoid;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                            font-size: 14px;
                            padding: 5px !important;
                            margin: 0 0 5px 0 !important;
                        }
                        .modal-body {
                            padding: 5px !important;
                        }
                        /* Removed fixed landscape orientation to allow user choice */
                        @page {
                           size: auto;
                        }
                        table tr {
                            page-break-inside: avoid;
                        }

                        /* For large data sets, optimize table layout */
                        .multi-column-layout .range-table {
                            column-count: 2;
                            column-gap: 20px;
                        }

                        /* Orientation-specific styles */
                        .landscape-optimized {
                            /* Additional styles for landscape mode */
                        }

                        .portrait-optimized {
                            /* Styles for portrait mode */
                            font-size: 10px !important;
                        }

                        /* Apply portrait optimizations when user selects portrait */
                        @media (orientation: portrait) {
                            .range-table {
                                font-size: 10px;
                            }
                            .range-table th,
                            .range-table td {
                                padding: 3px !important;
                            }
                        }
                    }
                `;
                document.head.appendChild(modalStyles);
            }

            // Show modal
            modal.style.display = 'block';

            // Add event listeners for close buttons
            const closeBtn = modal.querySelector('.modal-close');
            const closeRangesBtn = document.getElementById('closeRangesBtn');

            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            closeRangesBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Add event listener for print button
            const printRangesBtn = document.getElementById('printRangesBtn');
            printRangesBtn.addEventListener('click', function() {
                // Prepare special print styling
                const printStyle = document.createElement('style');
                printStyle.id = 'print-style-temp';
                printStyle.innerHTML = `
                    @media print {
                        @page {
                            /* Removed fixed orientation to allow user choice */
                            size: auto;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            font-size: 11px;
                        }
                        .grade-range h3 {
                            background-color: #f5f5f5 !important;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            font-size: 14px;
                            padding: 5px !important;
                        }
                        .range-table {
                            margin-bottom: 10px !important;
                        }
                        .range-table th {
                            background-color: #f5f5f5 !important;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                            padding: 5px !important;
                            font-size: 11px;
                        }
                        .range-table td {
                            padding: 3px 5px !important;
                            font-size: 10px;
                        }
                        .range-table tr:nth-child(even) {
                            background-color: #f9f9f9 !important;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                        }

                        /* Layout optimizations for many students */
                        .grade-range:has(table tr:nth-child(n+8)) table {
                            font-size: 9px !important;
                        }

                        /* Compact cells when we have many students */
                        .grade-range:has(table tr:nth-child(n+12)) table td,
                        .grade-range:has(table tr:nth-child(n+12)) table th {
                            padding: 2px 4px !important;
                        }

                        /* Two-column layout for ranges with many students - only in landscape */
                        @media (orientation: landscape) {
                            .grade-range:has(table tr:nth-child(n+20)) {
                                column-count: 2;
                                column-gap: 10px;
                            }

                            /* For very large data sets */
                            .grade-range:has(table tr:nth-child(n+30)) {
                                column-count: 3;
                                column-gap: 10px;
                            }
                        }

                        /* Portrait mode optimizations */
                        @media (orientation: portrait) {
                            .grade-range table {
                                font-size: 9px !important;
                            }
                            .grade-range h3 {
                                font-size: 12px !important;
                            }
                            /* Single column layout for portrait */
                            .grade-range {
                                column-count: 1 !important;
                            }
                        }
                    }
                `;
                document.head.appendChild(printStyle);

                // Add orientation choice UI
                const orientationUI = document.createElement('div');
                orientationUI.id = 'orientation-choice';
                orientationUI.innerHTML = `
                    <div style="position: fixed; top: 20px; right: 20px; z-index: 2000; background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <p style="margin: 0 0 10px 0; font-weight: bold;">Print Settings</p>
                        <p style="margin: 0 0 5px 0; font-size: 12px;">Choose page orientation in your browser's print dialog:</p>
                        <p style="margin: 0; font-size: 12px; color: #666;">• Landscape: Better for wide tables with many columns</p>
                        <p style="margin: 0; font-size: 12px; color: #666;">• Portrait: Better for long lists with few columns</p>
                        <button id="continue-print" style="margin-top: 10px; padding: 5px 10px; background: #5f6163; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%;">Continue to Print</button>
                    </div>
                `;
                document.body.appendChild(orientationUI);

                // Add event listener to continue to print
                document.getElementById('continue-print').addEventListener('click', function() {
                    // Remove the orientation UI
                    document.body.removeChild(orientationUI);

                    // Add a title for the print
                    const printTitle = document.createElement('div');
                    printTitle.id = 'print-title-temp';
                    printTitle.innerHTML = `
                        <div style="text-align: center; padding: 5px; display: none;">
                            <h1 style="margin: 0; font-size: 18px;">Student Grade Range Report</h1>
                            <p style="margin: 3px 0; font-size: 12px;">Section: ${data.section.name} | Subject: ${data.subject.name} | Quarter: ${data.quarter}</p>
                        </div>
                    `;
                    const modalBody = document.querySelector('.modal-body');
                    if (modalBody) {
                        modalBody.prepend(printTitle);
                    }

                    // Make print title visible only during printing
                    printTitle.style.display = 'block';

                    // Count total students with data to determine optimal layout
                    let totalStudentsWithData = 0;
                    for (const [range, students] of Object.entries(data.ranges)) {
                        totalStudentsWithData += students.length;
                    }

                    // Add multi-column layout class if we have many students
                    if (totalStudentsWithData > 20) {
                        modalBody.classList.add('multi-column-layout');
                    }

                    // Print the page
                    window.print();

                    // Remove the temporary print styles and title after printing
                    setTimeout(() => {
                        if (printStyle.parentNode) {
                            printStyle.parentNode.removeChild(printStyle);
                        }
                        if (printTitle.parentNode) {
                            printTitle.parentNode.removeChild(printTitle);
                        }
                        modalBody.classList.remove('multi-column-layout');
                    }, 1000);
                });
            });

            // Close modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Toast notification functions
        function showToast(title, message, type = 'info') {
            // Hide any existing toasts
            hideToasts();

            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.id = 'toast';

            let bgColor = '#4CAF50'; // Success green
            if (type === 'error') {
                bgColor = '#f44336'; // Error red
            } else if (type === 'info') {
                bgColor = '#2196F3'; // Info blue
            } else if (type === 'warning') {
                bgColor = '#ff9800'; // Warning orange
            }

            // Check if this is mobile
            const isMobile = window.innerWidth <= 768;
            const isAndroid = /Android/i.test(navigator.userAgent);

            // Base styling
            let toastStyle = `
                position: fixed;
                ${isMobile ? 'bottom: 80px;' : 'top: 20px;'}
                right: 20px;
                min-width: ${isMobile ? '80%' : '250px'};
                max-width: ${isMobile ? '90%' : '350px'};
                background-color: ${bgColor};
                color: white;
                padding: 15px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                z-index: ${isMobile ? '1020' : '1001'};
                opacity: 0;
                transition: opacity 0.5s;
            `;

            // Add specific Android fixes
            if (isAndroid) {
                toastStyle += `
                    left: 0;
                    right: 0;
                    margin: 0 auto;
                    max-width: 90%;
                    transform: none !important;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                    opacity: 1;
                `;
            }

            toast.style = toastStyle;

            toast.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong>${title}</strong>
                    <span onclick="hideToasts()" style="cursor: pointer; font-weight: bold;">&times;</span>
                </div>
                <div>${message}</div>
            `;

            document.body.appendChild(toast);

            // Fade in (except for Android)
            if (!isAndroid) {
                setTimeout(() => {
                    toast.style.opacity = '1';
                }, 10);
            }

            // Auto hide after 5 seconds for success/info, longer for errors
            if (type !== 'error') {
                setTimeout(hideToasts, 5000);
            } else {
                // Errors stay longer
                setTimeout(hideToasts, 8000);
            }
        }

        function hideToasts() {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 500);
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

    <!-- Grade Ranges Modal -->
    <div id="gradeRangesModal" class="modal" style="display: none;">
        <!-- Modal content -->
        <div class="modal-content" style="max-width: 80%; max-height: 80%; overflow-y: auto;">
            <div class="modal-header">
                <h2>Students by Grade Range - {{ $subject->name }} ({{ $quarter }})</h2>
                <span class="modal-close">&times;</span>
            </div>
            <div class="modal-body">
                <p><strong>Section:</strong> {{ $section->name }}</p>
        `;

        // Add each range
        for (const [range, students] of Object.entries(data.ranges)) {
            modalContent += `
                <div class="grade-range">
                    <h3>Grade Range: ${range}</h3>
            `;

            if (students.length === 0) {
                modalContent += `<p>No students in this range.</p>`;
            } else {
                modalContent += `
                    <table class="range-table" style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Name</th>
                                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Gender</th>
                                <th style="padding: 8px; border: 1px solid #ddd; text-align: center;">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                students.forEach(item => {
                    const student = item.student;
                    modalContent += `
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;">${student.last_name}, ${student.first_name} ${student.middle_name ? student.middle_name.charAt(0) + '.' : ''}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${student.gender}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${item.grade}</td>
                        </tr>
                    `;
                });

                modalContent += `
                        </tbody>
                    </table>
                `;
            }

            modalContent += `</div>`;
        }

        modalContent += `
                </div>
                <div class="modal-footer">
                    <button id="printRangesBtn" class="modal-btn" style="background-color: #4CAF50; color: white;">Print</button>
                    <button id="closeRangesBtn" class="modal-btn">Close</button>
                </div>
            </div>
        `;

        // Set modal content
        modal.innerHTML = modalContent;

        // Add modal styling if not already present
        if (!document.getElementById('modalStyles')) {
            const modalStyles = document.createElement('style');
            modalStyles.id = 'modalStyles';
            modalStyles.textContent = `
                .modal {
                    display: block;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0,0,0,0.4);
                }
                .modal-content {
                    background-color: #fefefe;
                    margin: 10% auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 80%;
                    max-width: 800px;
                    border-radius: 5px;
                }
                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }
                .modal-header h2 {
                    margin: 0;
                    font-size: 20px;
                }
                .modal-close {
                    color: #aaa;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }
                .modal-close:hover {
                    color: black;
                }
                .modal-footer {
                    margin-top: 20px;
                    text-align: right;
                    border-top: 1px solid #eee;
                    padding-top: 15px;
                }
                .modal-btn {
                    padding: 8px 16px;
                    background-color: #f1f1f1;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    margin-left: 10px;
                }
                .modal-btn:hover {
                    background-color: #ddd;
                }
                @media print {
                    .modal {
                        position: absolute;
                        background-color: white;
                    }
                    .modal-content {
                        margin: 0;
                        padding: 0;
                        border: none;
                        width: 100%;
                        max-width: 100%;
                    }
                    .modal-header, .modal-footer, .modal-close {
                        display: none;
                    }
                    body * {
                        visibility: hidden;
                    }
                    .modal, .modal * {
                        visibility: visible;
                    }
                    .modal {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }
                }
            `;
            document.head.appendChild(modalStyles);
        }

        // Show modal
        modal.style.display = 'block';

        // Add event listeners for close buttons
        const closeBtn = modal.querySelector('.modal-close');
        const closeRangesBtn = document.getElementById('closeRangesBtn');

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        closeRangesBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Add event listener for print button
        const printRangesBtn = document.getElementById('printRangesBtn');
        printRangesBtn.addEventListener('click', function() {
            window.print();
        });

        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Toast notification functions
    function showToast(title, message, type = 'info') {
        // Hide any existing toasts
        hideToasts();

        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.id = 'toast';

        let bgColor = '#4CAF50'; // Success green
        if (type === 'error') {
            bgColor = '#f44336'; // Error red
        } else if (type === 'info') {
            bgColor = '#2196F3'; // Info blue
        } else if (type === 'warning') {
            bgColor = '#ff9800'; // Warning orange
        }

        // Check if this is mobile
        const isMobile = window.innerWidth <= 768;
        const isAndroid = /Android/i.test(navigator.userAgent);

        // Base styling
        let toastStyle = `
            position: fixed;
            ${isMobile ? 'bottom: 80px;' : 'top: 20px;'}
            right: 20px;
            min-width: ${isMobile ? '80%' : '250px'};
            max-width: ${isMobile ? '90%' : '350px'};
            background-color: ${bgColor};
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: ${isMobile ? '1020' : '1001'};
            opacity: 0;
            transition: opacity 0.5s;
        `;

        // Add specific Android fixes
        if (isAndroid) {
            toastStyle += `
                left: 0;
                right: 0;
                margin: 0 auto;
                max-width: 90%;
                transform: none !important;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                opacity: 1;
            `;
        }

        toast.style = toastStyle;

        toast.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong>${title}</strong>
                <span onclick="hideToasts()" style="cursor: pointer; font-weight: bold;">&times;</span>
            </div>
            <div>${message}</div>
        `;

        document.body.appendChild(toast);

        // Fade in (except for Android)
        if (!isAndroid) {
            setTimeout(() => {
                toast.style.opacity = '1';
            }, 10);
        }

        // Auto hide after 5 seconds for success/info, longer for errors
        if (type !== 'error') {
            setTimeout(hideToasts, 5000);
        } else {
            // Errors stay longer
            setTimeout(hideToasts, 8000);
        }
    }

    function hideToasts() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.style.opacity = '0';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500);
        }
    }
</script>
</html>