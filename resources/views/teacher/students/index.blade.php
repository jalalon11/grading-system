@extends('layouts.app')

@push('styles')
<style>
    /* Fix modal backdrop and interaction issues */
    .modal {
        z-index: 1055 !important;
    }

    .modal-backdrop {
        z-index: 1054 !important;
    }

    .modal-content {
        position: relative;
        z-index: 1056 !important;
    }

    .modal-dialog {
        position: relative;
        z-index: 1056 !important;
    }

    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --info-color: #4895ef;
        --warning-color: #f72585;
        --danger-color: #e63946;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --font-family: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        --border-radius: 0.5rem;
        --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --transition: all 0.2s ease-in-out;
    }

    /* Enhanced Dropdown Styling */
    .dropdown-menu {
        display: none;
        border-radius: var(--border-radius);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: 1px solid var(--gray-200);
        animation: fadeInDown 0.2s ease-in-out;
        z-index: 1000;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        padding: 0.6rem 1.2rem;
        transition: background-color 0.15s ease-in-out;
    }

    .dropdown-item:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card Styles */
    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        margin-right: 15px;
        border: 2px solid rgba(255,255,255,0.9);
    }

    .student-card {
        transition: var(--transition);
        border-radius: var(--border-radius) !important;
        overflow: hidden;
        border: 1px solid var(--gray-200) !important;
        background-color: white;
    }

    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.15) !important;
        border-color: var(--primary-color) !important;
    }

    .student-card .card-body {
        padding: 1.5rem;
    }

    .student-card .student-footer {
        padding: 12px 18px;
        background: rgba(67, 97, 238, 0.03);
        border-top: 1px solid var(--gray-200);
    }

    /* Statistics Cards */
    .counter-card {
        border-radius: var(--border-radius);
        overflow: hidden;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        height: 100%;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .counter-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.18);
    }

    .counter-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.12);
        clip-path: circle(80px at 85% 25%);
        z-index: -1;
    }

    .counter-card::after {
        content: "";
        position: absolute;
        top: -25px;
        right: -25px;
        width: 140px;
        height: 140px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: -1;
    }

    .counter-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        opacity: 0.95;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .counter-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.75rem;
    }

    .counter-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .counter-subtitle {
        font-size: 0.85rem;
        opacity: 0.85;
    }

    /* Headers and Organization */
    .section-header {
        background: linear-gradient(to right, rgba(67, 97, 238, 0.08), rgba(67, 97, 238, 0.15));
        padding: 1.25rem 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 25px;
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: var(--transition);
    }

    .section-header:hover {
        transform: translateX(5px);
    }

    .grade-level-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        margin-bottom: 25px;
        border-radius: var(--border-radius);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.15);
    }

    .grade-level-header h4 {
        margin-bottom: 0;
        color: white;
    }

    .grade-level-header .badge-count {
        background-color: rgba(255, 255, 255, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .grade-level-header .toggle-grade-btn {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .grade-level-header .toggle-grade-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* Improved Assigned Sections Header */
    .assigned-section-header {
        background: linear-gradient(135deg, #4895ef 0%, #4361ee 100%);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        box-shadow: 0 6px 15px rgba(72, 149, 239, 0.15);
    }

    .assigned-section-header h4 {
        margin-bottom: 0.5rem;
        color: white;
    }

    .assigned-section-header p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
    }

    /* Search and Filters */
    .search-wrapper {
        position: relative;
    }

    .search-input {
        padding-left: 48px;
        border-radius: var(--border-radius);
        height: 52px;
        border: 1px solid var(--gray-300);
        box-shadow: var(--box-shadow);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        font-size: 16px;
    }

    .filter-dropdown {
        height: 52px;
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
        background-color: white;
        box-shadow: var(--box-shadow);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .filter-dropdown:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    /* Badges and Buttons */
    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: var(--transition);
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .badge-count {
        padding: 0.5rem 0.85rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .student-info-badge {
        border-radius: 30px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        margin-right: 5px;
        margin-bottom: 5px;
        background-color: var(--gray-200);
        color: var(--gray-700);
        transition: var(--transition);
    }

    .student-info-badge:hover {
        background-color: var(--gray-300);
    }

    .student-info-badge i {
        margin-right: 0.35rem;
    }

    /* Analytics */
    .analytics-container {
        border-radius: var(--border-radius);
        padding: 20px;
        background: white;
        box-shadow: var(--box-shadow);
        height: 100%;
        border: 1px solid var(--gray-200);
    }

    .analytics-title {
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark-color);
        font-size: 1rem;
    }

    .gender-stat {
        border-radius: 8px;
        padding: 14px 16px;
        background-color: rgba(67, 97, 238, 0.05);
        margin-bottom: 10px;
        transition: var(--transition);
    }

    .gender-stat:hover {
        background-color: rgba(67, 97, 238, 0.08);
    }

    /* Custom progress bar */
    .progress {
        height: 10px;
        border-radius: 10px;
        background-color: var(--gray-200);
        overflow: hidden;
    }

    /* Table Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        font-weight: 600;
        color: var(--gray-700);
        background-color: var(--gray-100);
        border-bottom: 2px solid var(--gray-300);
    }

    .table td {
        vertical-align: middle;
        border-color: var(--gray-200);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.03);
    }

    /* Cards and containers */
    .card {
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-200);
        box-shadow: var(--box-shadow);
    }

    /* Custom Alerts */
    .alert {
        border-radius: var(--border-radius);
    }

    /* Modal customization */
    .modal-content {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        position: relative;
        z-index: 1056;
    }

    .modal-header {
        border-bottom: 1px solid var(--gray-200);
    }

    .modal-footer {
        border-top: 1px solid var(--gray-200);
    }

    /* Fix modal backdrop and interaction issues */
    .modal {
        z-index: 1055 !important;
    }

    .modal-backdrop {
        z-index: 1054 !important;
    }

    /* Ensure modal buttons are clickable */
    .modal-dialog {
        position: relative;
        z-index: 1056;
    }

    /* Empty state enhancements */
    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--gray-400);
        margin-bottom: 1.5rem;
    }

    /* Dark mode styles */
    .dark .student-card {
        background-color: var(--bg-card);
        border-color: var(--border-color) !important;
    }

    .dark .student-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25) !important;
        border-color: #4361ee !important;
    }

    .dark .student-card .student-footer {
        background: rgba(255, 255, 255, 0.03);
        border-top-color: var(--border-color);
    }

    .dark .section-header {
        background: linear-gradient(to right, rgba(67, 97, 238, 0.15), rgba(67, 97, 238, 0.25));
    }

    .dark .grade-level-header {
        background: linear-gradient(135deg, #3a0ca3 0%, #480ca8 100%);
    }

    .dark .assigned-section-header {
        background: linear-gradient(135deg, #3f37c9 0%, #3a0ca3 100%);
    }

    .dark .search-input {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .search-input:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .search-icon {
        color: var(--text-muted);
    }

    .dark .filter-dropdown {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .filter-dropdown:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .dropdown-menu {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .dropdown-item {
        color: var(--text-color);
    }

    .dark .dropdown-item:hover {
        background-color: rgba(67, 97, 238, 0.2);
        color: var(--text-color);
    }

    .dark .dropdown-divider {
        border-color: var(--border-color);
    }

    /* Dark mode counter cards */
    .dark .counter-card {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .dark .counter-card::before {
        background: rgba(255, 255, 255, 0.05);
    }

    .dark .counter-card::after {
        background: rgba(255, 255, 255, 0.05);
    }

    /* Dark mode text colors */
    .dark .text-muted {
        color: var(--text-muted) !important;
    }

    .dark .student-name {
        color: var(--text-color);
    }

    .dark .student-info {
        color: var(--text-muted);
    }

    /* Dark mode table styles */
    .dark .table {
        --bs-table-bg: var(--bg-card);
        --bs-table-color: var(--text-color);
        --bs-table-border-color: var(--border-color);
        background-color: var(--bg-card);
    }

    .dark .table > :not(caption) > * > * {
        background-color: var(--bg-card);
        color: var(--text-color);
        border-bottom-color: var(--border-color);
    }

    .dark .table-light {
        --bs-table-bg: var(--bg-card-header);
        --bs-table-color: var(--text-color);
        --bs-table-border-color: var(--border-color);
        border-color: var(--border-color);
    }

    .dark .table-light th {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.1) !important;
        color: var(--text-color);
    }

    .dark .table-hover > tbody > tr:hover > * {
        background-color: rgba(67, 97, 238, 0.1) !important;
        color: var(--text-color);
    }

    .dark .table > :not(:first-child) {
        border-top: 2px solid var(--border-color);
    }

    .dark .table-responsive {
        background-color: var(--bg-card);
        border-radius: var(--border-radius);
    }

    .dark .card .table {
        border-color: var(--border-color);
    }

    /* Dark mode student info in table */
    .dark .table .student-avatar {
        border-color: var(--border-color);
    }

    .dark .table .fw-bold {
        color: var(--text-color);
    }

    .dark .table td h6 {
        color: var(--text-color);
    }

    /* Dark mode dropdown in table */
    .dark .table .dropdown-toggle {
        background-color: rgba(67, 97, 238, 0.2);
        border-color: transparent;
        color: var(--text-color);
    }

    .dark .table .dropdown-toggle:hover,
    .dark .table .dropdown-toggle:focus {
        background-color: rgba(67, 97, 238, 0.3);
        color: var(--text-color);
    }

    .dark .table .dropdown-menu {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3);
    }

    .dark .table .dropdown-item {
        color: var(--text-color);
    }

    .dark .table .dropdown-item:hover {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--text-color);
    }

    /* Dark mode pagination */
    .dark .page-link {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .page-link:hover {
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
        color: #ffffff;
    }

    .dark .page-item.disabled .page-link {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-muted);
    }

    /* Dark mode analytics container */
    .dark .analytics-container {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .analytics-title {
        color: var(--text-color);
    }

    .dark .gender-stat {
        background-color: rgba(67, 97, 238, 0.1);
    }

    .dark .gender-stat:hover {
        background-color: rgba(67, 97, 238, 0.15);
    }

    .dark .progress {
        background-color: var(--border-color);
    }

    /* Dark mode subject badges */
    .dark .badge.bg-primary.bg-opacity-10 {
        background-color: rgba(67, 97, 238, 0.2) !important;
        color: #4361ee !important;
    }

    .dark .student-info-badge {
        background-color: var(--bg-card-header);
        color: var(--text-color);
    }

    .dark .student-info-badge:hover {
        background-color: var(--border-color);
    }

    /* Dark mode empty state */
    .dark .empty-state-icon {
        color: var(--text-muted);
    }

    .dark .bg-gradient-light {
        background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-header) 100%);
    }

    /* Dark mode alerts */
    .dark .alert-warning {
        background-color: rgba(255, 193, 7, 0.1);
        border-color: rgba(255, 193, 7, 0.2);
        color: var(--text-color);
    }

    .dark .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        border-color: rgba(40, 167, 69, 0.2);
        color: var(--text-color);
    }

    .dark .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.2);
        color: var(--text-color);
    }

    /* Dark mode modals */
    .dark .modal-content {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        position: relative;
        z-index: 1056;
    }

    .dark .modal-header {
        border-bottom-color: var(--border-color);
    }

    .dark .modal-footer {
        border-top-color: var(--border-color);
    }

    .dark .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Dark mode card */
    .dark .card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .card-body {
        color: var(--text-color);
    }

    /* Dark mode form elements */
    .dark .form-control {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .form-control:focus {
        background-color: var(--bg-card);
        border-color: #4361ee;
        color: var(--text-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .form-control::placeholder {
        color: var(--text-muted);
    }

    .dark .form-control:disabled,
    .dark .form-control[readonly] {
        background-color: var(--bg-card-header);
        color: var(--text-muted);
    }

    .dark .form-label {
        color: var(--text-color);
    }

    .dark .form-text {
        color: var(--text-muted);
    }

    .dark .input-group-text {
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    /* Dark mode required asterisk */
    .dark .required:after {
        color: #dc3545;
    }

    /* Dark mode card header */
    .dark .card-header {
        background-color: var(--bg-card-header);
        border-bottom-color: var(--border-color);
        color: var(--text-color);
    }

    /* Dark mode profile sections */
    .dark .profile-section {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .profile-header {
        background-color: var(--bg-card-header);
        border-bottom-color: var(--border-color);
    }

    .dark .profile-info {
        color: var(--text-color);
    }

    .dark .profile-label {
        color: var(--text-muted);
    }

    /* Dark mode tabs */
    .dark .nav-tabs {
        border-bottom-color: var(--border-color);
    }

    .dark .nav-tabs .nav-link {
        color: var(--text-muted);
        background-color: transparent;
        border-color: transparent;
    }

    .dark .nav-tabs .nav-link:hover {
        color: var(--text-color);
        border-color: var(--border-color);
        isolation: isolate;
    }

    .dark .nav-tabs .nav-link.active {
        color: #4361ee;
        background-color: var(--bg-card);
        border-color: var(--border-color);
        border-bottom-color: var(--bg-card);
    }

    /* Dark mode breadcrumb */
    .dark .breadcrumb {
        background-color: var(--bg-card-header);
    }

    .dark .breadcrumb-item {
        color: var(--text-muted);
    }

    .dark .breadcrumb-item.active {
        color: var(--text-color);
    }

    .dark .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
    }

    /* Dark mode back button */
    .dark .btn-back {
        color: var(--text-color);
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
    }

    .dark .btn-back:hover {
        background-color: var(--border-color);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    /* Dark mode section titles */
    .dark .section-title {
        color: var(--text-color);
        border-bottom-color: var(--border-color);
    }

    .dark .section-subtitle {
        color: var(--text-muted);
    }

    /* Dark mode info cards */
    .dark .info-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .info-card-header {
        background-color: var(--bg-card-header);
        border-bottom-color: var(--border-color);
    }

    .dark .info-label {
        color: var(--text-muted);
    }

    .dark .info-value {
        color: var(--text-color);
    }

    /* Dark mode personal info */
    .dark .personal-info-item {
        border-bottom-color: var(--border-color);
    }

    .dark .personal-info-label {
        color: var(--text-muted);
    }

    .dark .personal-info-value {
        color: var(--text-color);
    }

    /* Dark mode helper text */
    .dark .text-helper {
        color: var(--text-muted);
    }

    .dark .text-helper i {
        color: var(--text-muted);
    }

    /* Dark mode validation states */
    .dark .was-validated .form-control:valid,
    .dark .form-control.is-valid {
        border-color: #198754;
        background-color: var(--bg-card);
    }

    .dark .was-validated .form-control:invalid,
    .dark .form-control.is-invalid {
        border-color: #dc3545;
        background-color: var(--bg-card);
    }

    .dark .valid-feedback {
        color: #198754;
    }

    .dark .invalid-feedback {
        color: #dc3545;
    }

    /* Dark mode page header */
    .dark .page-header {
        border-bottom-color: var(--border-color);
    }

    .dark .page-title {
        color: var(--text-color);
    }

    .dark .page-subtitle {
        color: var(--text-muted);
    }

    /* Dark mode add student icon */
    .dark .add-student-icon {
        background-color: var(--bg-card-header);
        color: var(--text-color);
    }

    /* Dark mode required field text */
    .dark .required-field-text {
        color: var(--text-muted);
    }

    .dark .required-field-text i {
        color: #dc3545;
    }

    /* Gender Distribution Card */
    .gender-distribution-card {
        border-radius: var(--border-radius);
        overflow: hidden;
        background: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
        border: 1px solid var(--gray-200);
    }

    .gender-distribution-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .gender-distribution-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }

    .gender-distribution-body {
        padding: 1.25rem;
    }

    .gender-stat {
        border-radius: 8px;
        padding: 1rem;
        background-color: rgba(67, 97, 238, 0.05);
        margin-bottom: 1rem;
        border: 1px solid rgba(67, 97, 238, 0.1);
    }

    .gender-stat:last-child {
        margin-bottom: 0;
    }

    .gender-stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .gender-stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    /* Search and Filter Enhancements */
    .search-filter-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--gray-200);
    }

    .search-wrapper {
        position: relative;
    }

    .search-input {
        padding-left: 3rem;
        height: 3rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
        font-size: 1rem;
        transition: var(--transition);
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        font-size: 1.25rem;
    }

    .filter-dropdown {
        height: 3rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
        font-size: 1rem;
        transition: var(--transition);
    }

    .filter-dropdown:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    /* Section Toggle Styles */
    .section-header {
        padding: 15px;
        margin-bottom: 0;
        cursor: pointer;
        transition: var(--transition);
    }

    .section-header:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }

    .toggle-section-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border: none;
        transition: var(--transition);
    }

    .toggle-section-btn:hover {
        background-color: rgba(67, 97, 238, 0.2);
    }

    .toggle-section-btn i {
        transition: transform 0.3s ease;
    }

    .toggle-section-btn.collapsed i {
        transform: rotate(-180deg);
    }

    .section-content {
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out, margin 0.3s ease-out;
        overflow: hidden;
    }

    .section-content.collapsed {
        max-height: 0;
        opacity: 0;
        margin-top: 0;
        margin-bottom: 0;
        padding-top: 0;
        padding-bottom: 0;
    }

    /* Dark mode improvements */
    .dark .gender-distribution-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .gender-distribution-header {
        border-bottom-color: var(--border-color);
    }

    .dark .gender-stat {
        background-color: rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.2);
    }

    .dark .gender-stat:hover {
        background-color: rgba(67, 97, 238, 0.15);
    }

    .dark .gender-stat-value {
        color: var(--text-color);
    }

    .dark .grade-level-header {
        background: linear-gradient(135deg, #3a0ca3 0%, #480ca8 100%);
    }

    .dark .assigned-section-header {
        background: linear-gradient(135deg, #3f37c9 0%, #3a0ca3 100%);
    }

    .dark .section-header {
        background: linear-gradient(to right, rgba(67, 97, 238, 0.15), rgba(67, 97, 238, 0.25));
    }

    .dark .search-filter-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header - Professional Mobile Centered -->
    <div class="d-flex flex-column d-sm-flex flex-sm-row align-items-center align-items-sm-center justify-content-sm-between mb-4">
        <div class="text-center text-sm-start mb-4 mb-sm-0 w-100 w-sm-auto">
            <div class="d-inline-block bg-primary bg-opacity-10 rounded-3 p-2 mb-2 d-sm-none">
                <i class="fas fa-user-graduate text-primary fa-lg"></i>
            </div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">
                <span class=" me-3 text-primary shadow-sm d-none d-sm-inline-flex align-items-center justify-content-center">
                    <i class="fas fa-user-graduate"></i>
                </span>
                Student Management
            </h1>
            <p class="text-muted mb-0">Manage, monitor, and organize your student records effectively</p>
        </div>
        <div class="d-flex justify-content-center justify-content-sm-end gap-2 w-100 w-sm-auto">
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home me-1"></i> Dashboard
            </a>
            <a href="{{ route('teacher.students.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Add New Student
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <div class="p-2 me-3 bg-success bg-opacity-25 rounded-circle">
                    <i class="fas fa-check text-success"></i>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <div class="p-2 me-3 bg-danger bg-opacity-25 rounded-circle">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                </div>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($students->count() > 0)
        <!-- Improved Statistics Cards with Gender Distribution -->
        <div class="row mb-4">
            <div class="col-lg-9">
                <div class="row">
                    <!-- Total Students -->
                    <div class="col-md-4 mb-4">
                        <div class="counter-card" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                            <div class="counter-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="counter-title">Total Students</div>
                            <div class="counter-value">{{ $students->count() }}</div>
                            <div class="counter-subtitle">Across all sections</div>
                        </div>
                    </div>

                    <!-- Sections Count -->
                    <div class="col-md-4 mb-4">
                        <div class="counter-card" style="background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);">
                            <div class="counter-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="counter-title">Active Sections</div>
                            <div class="counter-value">{{ $students->pluck('section.name')->unique()->count() }}</div>
                            <div class="counter-subtitle">Under your supervision</div>
                        </div>
                    </div>

                    <!-- Grade Levels -->
                    <div class="col-md-4 mb-4">
                        <div class="counter-card" style="background: linear-gradient(135deg, #4895ef 0%, #3f37c9 100%);">
                            <div class="counter-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="counter-title">Grade Levels</div>
                            <div class="counter-value">{{ $students->pluck('section.grade_level')->unique()->count() }}</div>
                            <div class="counter-subtitle">Active curriculum levels</div>
                        </div>
                    </div>

                    <!-- Upcoming Birthday -->
                    <div class="col-md-4 mb-4">
                        <div class="counter-card" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
                            <div class="counter-icon">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div class="counter-title">Upcoming Birthday</div>
                            <div class="counter-value">
                                @php
                                    // Use Philippine time (Asia/Manila timezone)
                                    $today = \Carbon\Carbon::now('Asia/Manila')->startOfDay();
                                    $upcomingBirthday = null;
                                    $daysUntilBirthday = null;
                                    $birthdayStudent = null;
                                    $minDays = 366; // More than a year

                                    // Only consider students from sections where the teacher is the adviser
                                    // These are already filtered in the $students collection from the controller
                                    foreach($students as $student) {
                                        if ($student->birth_date) {
                                            // Get the birth date and set it to Manila timezone
                                            $birthdate = \Carbon\Carbon::parse($student->birth_date)->setTimezone('Asia/Manila');

                                            // Create next birthday date (this year)
                                            $nextBirthday = \Carbon\Carbon::create(
                                                $today->year,
                                                $birthdate->month,
                                                $birthdate->day,
                                                0, 0, 0,
                                                'Asia/Manila'
                                            );

                                            // If the birthday has already occurred this year, look at next year's birthday
                                            if ($today->gt($nextBirthday)) {
                                                $nextBirthday->addYear();
                                            }

                                            // Calculate days until birthday (as whole number)
                                            $days = (int)$today->diffInDays($nextBirthday, false);

                                            if ($days >= 0 && $days < $minDays) {
                                                $minDays = $days;
                                                $upcomingBirthday = $nextBirthday;
                                                $daysUntilBirthday = $days;
                                                $birthdayStudent = $student;
                                            }
                                        }
                                    }
                                @endphp
                                @if($birthdayStudent)
                                    @if($daysUntilBirthday === 0)
                                        Today!
                                    @else
                                        {{ $daysUntilBirthday }} {{ Str::plural('day', $daysUntilBirthday) }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </div>
                            <div class="counter-subtitle">
                                @if($birthdayStudent)
                                    {{ $birthdayStudent->first_name }} {{ $birthdayStudent->last_name }}
                                @else
                                    No upcoming birthdays
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Improved Gender Distribution -->
            <div class="col-lg-3 mb-4">
                <div class="gender-distribution-card h-100">
                    <div class="gender-distribution-header">
                        <h6 class="gender-distribution-title">
                            <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 text-primary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="fas fa-venus-mars"></i>
                            </span>
                            Gender Distribution
                        </h6>
                    </div>

                    <div class="gender-distribution-body">
                        <!-- Section selector for gender distribution -->
                        <div class="mb-3">
                            <select id="genderSectionFilter" class="form-select">
                                <option value="all">All Sections</option>
                                @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @php
                            // Convert to lowercase for case-insensitive comparison
                            $maleCount = $students->filter(function($student) {
                                return strtolower($student->gender) === 'male';
                            })->count();

                            $femaleCount = $students->filter(function($student) {
                                return strtolower($student->gender) === 'female';
                            })->count();

                            $totalStudents = $students->count();
                            $malePercentage = $totalStudents > 0 ? round(($maleCount / $totalStudents) * 100) : 0;
                            $femalePercentage = $totalStudents > 0 ? round(($femaleCount / $totalStudents) * 100) : 0;
                        @endphp

                        <div id="gender-stats-container">
                            <div class="gender-stat">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="gender-stat-label">
                                        <i class="fas fa-male text-primary me-2"></i> Male Students
                                    </div>
                                    <div class="gender-stat-value">{{ $maleCount }}</div>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $malePercentage }}%"></div>
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">{{ $malePercentage }}%</small>
                                </div>
                            </div>

                            <div class="gender-stat">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="gender-stat-label">
                                        <i class="fas fa-female text-danger me-2"></i> Female Students
                                    </div>
                                    <div class="gender-stat-value">{{ $femaleCount }}</div>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $femalePercentage }}%"></div>
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">{{ $femalePercentage }}%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Improved Search and Filter Section -->
        <div class="search-filter-card mb-4">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-md-6">
                    <label for="studentSearch" class="form-label fw-medium mb-2">Search Students</label>
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="studentSearch" class="form-control search-input" placeholder="Search by name, ID, or section...">
                    </div>
                </div>

                <!-- Filter Dropdowns -->
                <div class="col-md-3">
                    <label for="gradeFilter" class="form-label fw-medium mb-2">Grade Level</label>
                    <select id="gradeFilter" class="form-select filter-dropdown">
                        <option value="">All Grade Levels</option>
                        @foreach($students->pluck('section.grade_level')->unique()->sort() as $gradeLevel)
                            <option value="{{ $gradeLevel }}">Grade {{ $gradeLevel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="sectionFilter" class="form-label fw-medium mb-2">Section</label>
                    <select id="sectionFilter" class="form-select filter-dropdown">
                        <option value="">All Sections</option>
                        @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Organize students by grade level and section -->
        @php
            $studentsByGradeLevel = $students->groupBy(function ($student) {
                return $student->section->grade_level ?? 'Unassigned';
            })->sortKeys();
        @endphp

        @foreach($studentsByGradeLevel as $gradeLevel => $gradeStudents)
            <div class="grade-level-header shadow-sm border-start border-primary border-4 rounded mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 d-flex align-items-center">
                        <span class="bg-white bg-opacity-25 p-2 rounded-circle me-3 text-white">
                            <i class="fas fa-graduation-cap"></i>
                        </span>
                         {{ $gradeLevel }}
                        <span class="badge ms-2 badge-count">{{ $gradeStudents->count() }} students</span>
                    </h4>
                    <div>
                        <!-- <button class="btn btn-sm toggle-section-btn rounded-pill" data-grade="{{ $gradeLevel }}">
                            <i class="fas fa-chevron-down"></i>
                        </button> -->
                    </div>
                </div>
            </div>

            <!-- Group students by section within this grade level -->
            @php
                $studentsBySection = $gradeStudents->groupBy(function ($student) {
                    return $student->section->name ?? 'Unassigned';
                })->sortKeys();
            @endphp

            <div class="grade-level-container section-content" id="grade-content-{{ $gradeLevel }}" data-grade="{{ $gradeLevel }}">
                @foreach($studentsBySection as $sectionName => $sectionStudents)
                    @php
                        $sectionId = $sectionStudents->first()->section->id ?? 'section-' . Str::slug($sectionName);
                    @endphp
                    <div class="section-header shadow-sm border-start border-info border-4 rounded" data-section-id="{{ $sectionId }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 d-flex align-items-center">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 text-primary">
                                    <i class="fas fa-users"></i>
                                </span>
                                {{ $sectionName }}
                                <span class="badge bg-primary ms-2 badge-count">{{ $sectionStudents->count() }} students</span>
                            </h5>
                            <button class="btn btn-sm toggle-section-btn rounded-pill" data-section="{{ $sectionId }}">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>

                    <div class="section-content" id="section-content-{{ $sectionId }}">
                        <div class="row g-3 mb-4 student-section" data-grade="{{ $gradeLevel }}" data-section-id="{{ $sectionId }}">
                        @foreach($sectionStudents as $student)
                            <div class="col-xl-3 col-md-6 mb-3 student-item"
                                 data-name="{{ strtolower($student->last_name . ' ' . $student->first_name) }}"
                                 data-student-id="{{ strtolower($student->student_id) }}">
                                <div class="card student-card shadow-sm h-100 {{ !$student->is_active ? 'opacity-50' : '' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @php
                                                $avatarColors = [
                                                    'bg-primary' => '#4361ee',
                                                    'bg-success' => '#4cc9f0',
                                                    'bg-info' => '#4895ef',
                                                    'bg-warning' => '#f72585',
                                                    'bg-danger' => '#e63946'
                                                ];
                                                $hash = crc32($student->id . $student->first_name);
                                                $colorIndex = abs($hash) % count($avatarColors);
                                                $colorKey = array_keys($avatarColors)[$colorIndex];
                                                $bgColor = $avatarColors[$colorKey];
                                            @endphp
                                            <div class="student-avatar" style="background-color: <?php echo $bgColor; ?>">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">
                                                    {{ $student->last_name }}, {{ $student->first_name }}
                                                    @if(!$student->is_active)
                                                        <span class="badge bg-secondary ms-1">Disabled</span>
                                                    @endif
                                                </h6>
                                                <div class="mt-1">
                                                    <div class="student-info-badge">
                                                        <i class="fas fa-id-card text-primary"></i> {{ $student->student_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2 d-flex flex-wrap">
                                            <div class="student-info-badge me-1 mb-1">
                                                <i class="fas fa-venus-mars text-primary"></i> {{ $student->gender }}
                                            </div>
                                            @if($student->birth_date)
                                            <div class="student-info-badge me-1 mb-1">
                                                <i class="fas fa-birthday-cake text-info"></i> {{ \Carbon\Carbon::parse($student->birth_date)->age }} yrs
                                            </div>
                                            @endif
                                            <div class="student-info-badge mb-1">
                                                <i class="fas fa-id-badge text-info"></i> LRN: {{ $student->lrn ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-shield text-success me-2 small"></i>
                                                <span class="small text-truncate">{{ $student->guardian_name ?: 'Guardian not specified' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="student-footer">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary me-1 btn-action" title="View Student">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning me-1 btn-action" title="Edit Student">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($student->is_active)
                                            <button type="button" class="btn btn-sm btn-outline-warning btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}" title="Disable Student">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                            @else
                                            <form action="{{ route('teacher.students.reactivate', $student->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="Reactivate Student">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Divider -->
        <div class="my-5">
            <hr>
        </div>

        <!-- Assigned Subjects Sections -->
        @if($assignedStudents->count() > 0)
            <div class="assigned-section-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <span class="bg-white bg-opacity-25 p-2 rounded-circle me-3 text-white">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </span>
                        Assigned Subject Sections
                    </h4>
                </div>
                <p class="mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Sections where you teach subjects but are not the adviser. You can only view grades for subjects you teach.
                </p>
            </div>

            <!-- Group assigned students by section -->
            @php
                $assignedStudentsBySection = $assignedStudents->groupBy(function ($student) {
                    return $student->section_id;
                });
            @endphp

            @foreach($assignedSections as $section)
                @if(isset($assignedStudentsBySection[$section->id]))
                    <div class="section-header shadow-sm border-start border-primary border-4 rounded" data-section-id="{{ $section->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 d-flex align-items-center">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-2 text-primary">
                                    <i class="fas fa-users"></i>
                                </span>
                                Section: {{ $section->name }} {{ $section->grade_level }}
                                <span class="badge bg-primary ms-2 badge-count">{{ $assignedStudentsBySection[$section->id]->count() }} students</span>
                            </h5>
                            <button class="btn btn-sm toggle-section-btn rounded-pill" data-section="{{ $section->id }}">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4 section-content" id="section-content-{{ $section->id }}">
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <h6 class="mb-2 d-flex align-items-center">
                                    <span class="bg-primary bg-opacity-10 p-1 rounded-circle me-2 text-primary" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book-open"></i>
                                    </span>
                                    Subjects you teach in this section:
                                </h6>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($assignedSubjectsBySection[$section->id] ?? [] as $subject)
                                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">{{ $subject->name }} ({{ $subject->code }})</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%">Student</th>
                                            <th>ID</th>
                                            <th>Gender</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignedStudentsBySection[$section->id] as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            $avatarColors = [
                                                                'bg-primary' => '#4361ee',
                                                                'bg-success' => '#4cc9f0',
                                                                'bg-info' => '#4895ef',
                                                                'bg-warning' => '#f72585',
                                                                'bg-danger' => '#e63946'
                                                            ];
                                                            $hash = crc32($student->id . $student->first_name);
                                                            $colorIndex = abs($hash) % count($avatarColors);
                                                            $colorKey = array_keys($avatarColors)[$colorIndex];
                                                            $bgColor = $avatarColors[$colorKey];
                                                        @endphp
                                                        <div class="student-avatar" style="background-color: <?php echo $bgColor; ?>">
                                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $student->last_name }}, {{ $student->first_name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->student_id }}</td>
                                                <td>{{ $student->gender }}</td>
                                                <td class="text-end">
                                                    @if(count($assignedSubjectsBySection[$section->id] ?? []) > 1)
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenu-{{ $student->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                            View Subject Grades
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenu-{{ $student->id }}">
                                                            @foreach($assignedSubjectsBySection[$section->id] ?? [] as $subject)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('teacher.students.show', [
                                                                        'student' => $student->id,
                                                                        'from_assigned' => 1,
                                                                        'subject_id' => $subject->id
                                                                    ]) }}">
                                                                        {{ $subject->name }} ({{ $subject->code }})
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @elseif(count($assignedSubjectsBySection[$section->id] ?? []) == 1)
                                                    <!-- Single subject, just show a direct link -->
                                                    @php $subject = $assignedSubjectsBySection[$section->id][0]; @endphp
                                                    <a href="{{ route('teacher.students.show', [
                                                        'student' => $student->id,
                                                        'from_assigned' => 1,
                                                        'subject_id' => $subject->id
                                                    ]) }}" class="btn btn-sm btn-primary">
                                                        View {{ $subject->name }} Grades
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        <!-- No Results Message (hidden by default) -->
        <div id="noResults" class="text-center py-5 d-none">
            <div class="card shadow-sm border-0 p-4">
                <div class="card-body empty-state">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                        <i class="fas fa-search text-primary fa-3x"></i>
                    </div>
                    <h4 class="text-primary">No Students Found</h4>
                    <p class="text-muted mb-4">Try adjusting your search or filter criteria.</p>
                    <button id="resetFilters" class="btn btn-primary px-4">
                        <i class="fas fa-undo me-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="card shadow-sm border-0 p-5">
                <div class="card-body empty-state">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-flex mb-3">
                        <i class="fas fa-user-graduate text-primary fa-3x"></i>
                    </div>
                    <h3 class="text-primary mb-3">No Students Found</h3>
                    <p class="text-muted mb-4">You haven't added any students to your sections yet.</p>
                    <a href="{{ route('teacher.students.create') }}" class="btn btn-primary px-4">
                        <i class="fas fa-plus-circle me-1"></i> Add Your First Student
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Apply CSS fixes for any visual glitches
        document.querySelectorAll('.counter-card').forEach(card => {
            card.style.height = '100%';
        });

        const studentSearch = document.getElementById('studentSearch');
        const gradeFilter = document.getElementById('gradeFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const genderSectionFilter = document.getElementById('genderSectionFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const noResults = document.getElementById('noResults');
        const studentItems = document.querySelectorAll('.student-item');
        const studentSections = document.querySelectorAll('.student-section');
        const gradeLevelHeaders = document.querySelectorAll('.grade-level-header');
        const gradeLevelContainers = document.querySelectorAll('.grade-level-container');
        const sectionHeaders = document.querySelectorAll('.section-header');
        const toggleGradeBtns = document.querySelectorAll('.toggle-grade-btn');

        // Animation for cards
        studentItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100 + Math.random() * 300);
        });

        // Function to filter students
        function filterStudents() {
            const searchTerm = studentSearch.value.toLowerCase();
            const selectedGrade = gradeFilter.value;
            const selectedSection = sectionFilter.value;

            let visibleCount = 0;
            let visibleSections = new Set();
            let visibleGrades = new Set();

            // Loop through all student items
            studentItems.forEach(item => {
                const studentName = item.getAttribute('data-name');
                const studentId = item.getAttribute('data-student-id');
                const studentGrade = item.closest('.student-section').getAttribute('data-grade');
                const studentSectionId = item.closest('.student-section').getAttribute('data-section-id');

                // Check if student matches all filters
                const matchesSearch = searchTerm === '' ||
                    studentName.includes(searchTerm) ||
                    studentId.includes(searchTerm);

                const matchesGrade = selectedGrade === '' || studentGrade === selectedGrade;
                const matchesSection = selectedSection === '' || studentSectionId === selectedSection;

                // Show/hide based on filters
                if (matchesSearch && matchesGrade && matchesSection) {
                    item.classList.remove('d-none');
                    visibleCount++;
                    visibleSections.add(studentSectionId);
                    visibleGrades.add(studentGrade);
                } else {
                    item.classList.add('d-none');
                }
            });

            // Show/hide section containers based on visible students
            studentSections.forEach(section => {
                const sectionId = section.getAttribute('data-section-id');
                const hasVisibleStudents = visibleSections.has(sectionId);
                section.classList.toggle('d-none', !hasVisibleStudents);
            });

            // Show/hide section headers
            sectionHeaders.forEach(header => {
                const sectionId = header.getAttribute('data-section-id');
                const hasVisibleStudents = visibleSections.has(sectionId);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });

            // Show/hide grade level headers
            gradeLevelHeaders.forEach(header => {
                const grade = header.querySelector('.toggle-grade-btn').getAttribute('data-grade');
                const hasVisibleStudents = visibleGrades.has(grade);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });

            // Show/hide grade level containers
            gradeLevelContainers.forEach(container => {
                const grade = container.getAttribute('data-grade');
                const hasVisibleStudents = visibleGrades.has(grade);
                container.classList.toggle('d-none', !hasVisibleStudents);
            });

            // Show/hide no results message
            noResults.classList.toggle('d-none', visibleCount > 0);
        }

        // Function to update gender distribution based on selected section
        function updateGenderDistribution() {
            const selectedSectionId = genderSectionFilter.value;

            // Make an AJAX request to get gender data for the selected section
            fetch('/teacher/students/gender-distribution?section_id=' + selectedSectionId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Create the gender stats HTML
                    let html = `
                        <div class="gender-stat">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="gender-stat-label">
                                    <i class="fas fa-male text-primary me-2"></i> Male Students
                                </div>
                                <div class="gender-stat-value">${data.male_count}</div>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: ${data.male_percentage}%"></div>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">${data.male_percentage}%</small>
                            </div>
                        </div>

                        <div class="gender-stat">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="gender-stat-label">
                                    <i class="fas fa-female text-danger me-2"></i> Female Students
                                </div>
                                <div class="gender-stat-value">${data.female_count}</div>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: ${data.female_percentage}%"></div>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">${data.female_percentage}%</small>
                            </div>
                        </div>
                    `;

                    // Update the gender stats container
                    document.getElementById('gender-stats-container').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching gender distribution:', error);
                    // Display error message in the container
                    document.getElementById('gender-stats-container').innerHTML = `
                        <div class="alert alert-danger">
                            <p>Unable to load gender distribution data.</p>
                        </div>
                    `;
                });
        }

        // Call updateGenderDistribution when page loads
        if (genderSectionFilter) {
            updateGenderDistribution();
            // Add event listener
            genderSectionFilter.addEventListener('change', updateGenderDistribution);
        }

        // Toggle grade level sections
        toggleGradeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const grade = this.getAttribute('data-grade');
                const container = document.getElementById(`grade-content-${grade}`);
                const icon = this.querySelector('i');

                if (container) {
                    // Toggle collapsed state
                    container.classList.toggle('collapsed');
                    this.classList.toggle('collapsed');

                    // Store state in localStorage for persistence
                    const isCollapsed = container.classList.contains('collapsed');
                    localStorage.setItem(`grade-${grade}-collapsed`, isCollapsed);
                }
            });

            // Initialize state from localStorage on page load
            const grade = btn.getAttribute('data-grade');
            const container = document.getElementById(`grade-content-${grade}`);
            const savedState = localStorage.getItem(`grade-${grade}-collapsed`);

            if (savedState === 'true' && container) {
                container.classList.add('collapsed');
                btn.classList.add('collapsed');
            }
        });

        // Toggle assigned section content
        const toggleSectionBtns = document.querySelectorAll('.toggle-section-btn');
        toggleSectionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section');
                const contentEl = document.getElementById(`section-content-${sectionId}`);
                const icon = this.querySelector('i');

                if (contentEl) {
                    // Toggle collapsed state
                    contentEl.classList.toggle('collapsed');
                    this.classList.toggle('collapsed');

                    // Store state in localStorage for persistence
                    const isCollapsed = contentEl.classList.contains('collapsed');
                    localStorage.setItem(`section-${sectionId}-collapsed`, isCollapsed);
                }
            });

            // Initialize state from localStorage on page load
            const sectionId = btn.getAttribute('data-section');
            const contentEl = document.getElementById(`section-content-${sectionId}`);
            const savedState = localStorage.getItem(`section-${sectionId}-collapsed`);

            if (savedState === 'true' && contentEl) {
                contentEl.classList.add('collapsed');
                btn.classList.add('collapsed');
            }
        });

        // Make section headers clickable to toggle content
        document.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', function(e) {
                // Don't trigger if clicking on the button itself (it has its own handler)
                if (e.target.closest('.toggle-section-btn')) {
                    return;
                }

                const sectionId = this.getAttribute('data-section-id');
                const toggleBtn = this.querySelector(`.toggle-section-btn[data-section="${sectionId}"]`);

                if (toggleBtn) {
                    // Simulate click on the toggle button
                    toggleBtn.click();
                }
            });
        });

        // Make grade level headers clickable to toggle content
        document.querySelectorAll('.grade-level-header').forEach(header => {
            header.addEventListener('click', function(e) {
                // Don't trigger if clicking on the button itself (it has its own handler)
                if (e.target.closest('.toggle-section-btn')) {
                    return;
                }

                const gradeLevel = this.querySelector('.toggle-section-btn').getAttribute('data-grade');
                const toggleBtn = this.querySelector(`.toggle-section-btn[data-grade="${gradeLevel}"]`);

                if (toggleBtn) {
                    // Simulate click on the toggle button
                    toggleBtn.click();
                }
            });
        });

        // Add event listeners for search and filters
        if (studentSearch) {
            studentSearch.addEventListener('input', filterStudents);
        }

        if (gradeFilter) {
            gradeFilter.addEventListener('change', filterStudents);
        }

        if (sectionFilter) {
            sectionFilter.addEventListener('change', filterStudents);
        }

        // Reset filters
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function() {
                if (studentSearch) studentSearch.value = '';
                if (gradeFilter) gradeFilter.value = '';
                if (sectionFilter) sectionFilter.value = '';
                if (genderSectionFilter) genderSectionFilter.value = 'all';
                filterStudents();
                if (genderSectionFilter) updateGenderDistribution();
            });
        }

        // Fix dropdown functionality for "View Subject Grades"
        document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdownMenu = this.nextElementSibling;

                // Close all other open dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle current dropdown
                if (dropdownMenu) {
                    dropdownMenu.classList.toggle('show');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.dropdown-toggle, .dropdown-menu *')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Fix modal backdrop and interaction issues
        document.querySelectorAll('.modal').forEach(modalEl => {
            modalEl.addEventListener('shown.bs.modal', function() {
                // Ensure the modal is above the backdrop
                this.style.zIndex = '1055';

                // Find the backdrop and set its z-index
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.style.zIndex = '1054';
                }

                // Ensure the modal dialog and content are above both
                const dialog = this.querySelector('.modal-dialog');
                if (dialog) {
                    dialog.style.zIndex = '1056';
                }

                const content = this.querySelector('.modal-content');
                if (content) {
                    content.style.zIndex = '1056';
                }
            });
        });

        // Special handling for delete modals
        document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#deleteModal"]').forEach(button => {
            button.addEventListener('click', function(e) {
                // First, force close any existing modals to prevent stacking issues
                forceCloseAllModals();

                // Short delay to ensure cleanup is complete
                setTimeout(() => {
                    const targetId = this.getAttribute('data-bs-target');
                    const modalEl = document.querySelector(targetId);

                    if (modalEl) {
                        // Ensure the modal is properly initialized
                        if (typeof bootstrap !== 'undefined') {
                            // Store the modal instance in a variable for later use
                            const modalInstance = new bootstrap.Modal(modalEl);
                            modalEl._bsModal = modalInstance;
                            modalInstance.show();

                            // Handle cancel button click
                            const cancelBtn = modalEl.querySelector('.cancel-delete-btn');
                            if (cancelBtn) {
                                // Remove any existing event listeners
                                cancelBtn.removeEventListener('click', cancelDeleteHandler);
                                // Add new event listener
                                cancelBtn.addEventListener('click', cancelDeleteHandler);
                            }

                            // Handle close button click
                            const closeBtn = modalEl.querySelector('.close-delete-btn');
                            if (closeBtn) {
                                // Remove any existing event listeners
                                closeBtn.removeEventListener('click', cancelDeleteHandler);
                                // Add new event listener
                                closeBtn.addEventListener('click', cancelDeleteHandler);
                            }
                        }

                        // Apply styles to ensure modal is visible and interactive
                        modalEl.style.display = 'block';
                        modalEl.style.zIndex = '1055';

                        // Ensure buttons are clickable
                        const buttons = modalEl.querySelectorAll('button');
                        buttons.forEach(btn => {
                            btn.style.position = 'relative';
                            btn.style.zIndex = '1057';
                        });
                    }
                }, 50);
            });
        });

        // Function to handle cancel button click
        function cancelDeleteHandler() {
            const modalEl = this.closest('.modal');
            if (modalEl) {
                // Force hide the modal
                if (modalEl._bsModal) {
                    modalEl._bsModal.hide();
                }

                // Immediate cleanup
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                modalEl.removeAttribute('role');

                // Remove all backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => {
                    backdrop.parentNode.removeChild(backdrop);
                });

                // Clean up body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Additional cleanup after a short delay
                setTimeout(() => {
                    // Double-check for any remaining backdrops
                    const remainingBackdrops = document.querySelectorAll('.modal-backdrop');
                    remainingBackdrops.forEach(backdrop => {
                        backdrop.parentNode.removeChild(backdrop);
                    });
                }, 100);
            }
        }

        // Add event listeners to all cancel and close buttons
        document.querySelectorAll('.cancel-delete-btn, .close-delete-btn').forEach(btn => {
            btn.addEventListener('click', cancelDeleteHandler);
        });

        // Function to force close all modals
        function forceCloseAllModals() {
            const openModals = document.querySelectorAll('.modal');
            if (openModals.length > 0) {
                openModals.forEach(modal => {
                    // Force cleanup
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    modal.removeAttribute('aria-modal');
                    modal.removeAttribute('role');
                });

                // Remove all backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => {
                    backdrop.parentNode.removeChild(backdrop);
                });

                // Clean up body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        }

        // Global event listener for ESC key to force close any open modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                forceCloseAllModals();
            }
        });

        // Click handler for modal backdrop
        document.addEventListener('click', function(e) {
            // Check if click is on a backdrop or modal container (not on modal content)
            if (e.target.classList.contains('modal') || e.target.classList.contains('modal-backdrop')) {
                forceCloseAllModals();
            }
        });
    });
</script>
@endpush

<!-- Student Delete Modals -->
@foreach($students as $student)
<div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">Confirm Disable Student</h5>
                <button type="button" class="btn-close close-delete-btn" aria-label="Close" style="position: relative; z-index: 1057;"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                    </div>
                    <h5>Are you sure you want to disable this student?</h5>
                </div>
                <div class="alert alert-warning">
                    <p class="mb-0"><strong>{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</strong> will be disabled and will no longer appear in reports or grade entries.</p>
                </div>
                <p class="text-info small"><i class="fas fa-info-circle me-1"></i> The student's records will be preserved but hidden from reports.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-delete-btn" style="position: relative; z-index: 1057;">Cancel</button>
                <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST" style="position: relative; z-index: 1057;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning" style="position: relative; z-index: 1057;">Yes, Disable Student</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection