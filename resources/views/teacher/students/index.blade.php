@extends('layouts.app')

@push('styles')
<style>
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
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        height: 100%;
        position: relative;
        z-index: 1;
    }
    
    .counter-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        clip-path: circle(65px at 85% 25%);
        z-index: -1;
    }
    
    .counter-card::after {
        content: "";
        position: absolute;
        top: -15px;
        right: -15px;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        z-index: -1;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #4895ef 0%, #3f37c9 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
    }
    
    .counter-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .counter-value {
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    /* Headers and Organization */
    .section-header {
        padding: 18px 20px;
        border-radius: var(--border-radius);
        margin-bottom: 25px;
        background: var(--gray-100);
        border-left: 4px solid var(--primary-color);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
    }
    
    .section-header:hover {
        transform: translateX(5px);
        background: linear-gradient(to right, rgba(67, 97, 238, 0.05), rgba(255, 255, 255, 1));
    }
    
    .grade-level-header {
        padding: 18px 20px;
        margin-bottom: 25px;
        background: linear-gradient(to right, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.1));
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
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
    }
    
    .modal-header {
        border-bottom: 1px solid var(--gray-200);
    }
    
    .modal-footer {
        border-top: 1px solid var(--gray-200);
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
        background: var(--bg-card-header);
        border-left-color: #4361ee;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    
    .dark .section-header:hover {
        background: linear-gradient(to right, rgba(67, 97, 238, 0.15), var(--bg-card-header));
    }
    
    .dark .grade-level-header {
        background: linear-gradient(to right, rgba(67, 97, 238, 0.15), rgba(67, 97, 238, 0.2));
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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
    }
    
    .dark .modal-header {
        border-bottom-color: var(--border-color);
    }
    
    .dark .modal-footer {
        border-top-color: var(--border-color);
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
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-flex align-items-center">
                <span class="bg-primary rounded-circle p-2 me-3 text-white shadow-sm">
                    <i class="fas fa-user-graduate"></i>
                </span>
                Student Management
            </h1>
            <p class="text-muted mb-0 mt-1">Manage, monitor, and organize your student records effectively</p>
        </div>
        <div class="d-flex gap-2">
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
        <!-- Quick Analytics -->
        <div class="row mb-4">
            <!-- Key Stats -->
            <div class="col-lg-9">
                <div class="row">
                    <!-- Total Students -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-primary shadow">
                            <div class="counter-title">Total Students</div>
                            <div class="counter-value">{{ $students->count() }}</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.2);">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="small">Across all sections</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sections Count -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-success shadow">
                            <div class="counter-title">Active Sections</div>
                            <div class="counter-value">{{ $students->pluck('section.name')->unique()->count() }}</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.2);">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="small">Under your supervision</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grade Levels -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-info shadow">
                            <div class="counter-title">Grade Levels</div>
                            <div class="counter-value">{{ $students->pluck('section.grade_level')->unique()->count() }}</div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.2);">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="small">Active curriculum levels</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Average Age -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-warning shadow">
                            <div class="counter-title">Average Age</div>
                            <div class="counter-value">
                                @php
                                    $totalAge = 0;
                                    $studentCount = 0;
                                    foreach($students as $student) {
                                        if ($student->birth_date) {
                                            $age = \Carbon\Carbon::parse($student->birth_date)->age;
                                            $totalAge += $age;
                                            $studentCount++;
                                        }
                                    }
                                    $averageAge = $studentCount > 0 ? round($totalAge / $studentCount, 1) : 0;
                                @endphp
                                {{ $averageAge }}
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.2);">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="small">Years old</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gender Distribution -->
            <div class="col-lg-3 mb-4">
                <div class="analytics-container h-100 shadow-sm">
                    <h6 class="analytics-title d-flex align-items-center">
                        <span class="bg-info bg-opacity-10 p-2 rounded-circle me-2 text-info">
                            <i class="fas fa-venus-mars"></i>
                        </span>
                        Gender Distribution
                    </h6>
                    
                    <!-- Section selector for gender distribution -->
                    <div class="mb-3">
                        <select id="genderSectionFilter" class="form-select form-select-sm">
                            <option value="all">All Sections</option>
                            @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @php
                        // Debug: collect all unique gender values from the database
                        $uniqueGenders = $students->pluck('gender')->unique()->toArray();
                        
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
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-male text-primary me-2"></i> Male Students
                            </div>
                            <div class="fw-bold">{{ $maleCount }} ({{ $malePercentage }}%)</div>
                        </div>
                        
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-female text-danger me-2"></i> Female Students
                            </div>
                            <div class="fw-bold">{{ $femaleCount }} ({{ $femalePercentage }}%)</div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $malePercentage; ?>%" aria-valuenow="<?php echo $malePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $femalePercentage; ?>%" aria-valuenow="<?php echo $femalePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Row -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-6">
                        <label for="studentSearch" class="form-label small text-muted mb-1">Search Students</label>
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="studentSearch" class="form-control search-input" placeholder="Search by name, ID, or section...">
                        </div>
                    </div>
                    
                    <!-- Filter Dropdowns -->
                    <div class="col-md-3">
                        <label for="gradeFilter" class="form-label small text-muted mb-1">Grade Level</label>
                        <select id="gradeFilter" class="form-select filter-dropdown">
                            <option value="">All Grade Levels</option>
                            @foreach($students->pluck('section.grade_level')->unique()->sort() as $gradeLevel)
                                <option value="{{ $gradeLevel }}">Grade {{ $gradeLevel }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="sectionFilter" class="form-label small text-muted mb-1">Section</label>
                        <select id="sectionFilter" class="form-select filter-dropdown">
                            <option value="">All Sections</option>
                            @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
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
            <div class="grade-level-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 d-flex align-items-center">
                        <span class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 text-primary">
                            <i class="fas fa-graduation-cap"></i>
                        </span>
                        Grade {{ $gradeLevel }}
                        <span class="badge bg-primary ms-2 badge-count">{{ $gradeStudents->count() }} students</span>
                    </h4>
                    <div>
                        <button class="btn btn-sm btn-outline-primary toggle-grade-btn rounded-pill" data-grade="{{ $gradeLevel }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Group students by section within this grade level -->
            @php
                $studentsBySection = $gradeStudents->groupBy(function ($student) {
                    return $student->section->name ?? 'Unassigned';
                })->sortKeys();
            @endphp

            <div class="grade-level-container" data-grade="{{ $gradeLevel }}">
                @foreach($studentsBySection as $sectionName => $sectionStudents)
                    <div class="section-header" data-section-id="{{ $sectionStudents->first()->section->id ?? '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 d-flex align-items-center">
                                <span class="bg-info bg-opacity-10 p-2 rounded-circle me-2 text-info">
                                    <i class="fas fa-users"></i>
                                </span>
                                Section: {{ $sectionName }}
                                <span class="badge bg-info ms-2 badge-count">{{ $sectionStudents->count() }} students</span>
                            </h5>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 student-section" data-grade="{{ $gradeLevel }}" data-section-id="{{ $sectionStudents->first()->section->id ?? '' }}">
                        @foreach($sectionStudents as $student)
                            <div class="col-xl-3 col-md-6 mb-3 student-item" 
                                 data-name="{{ strtolower($student->last_name . ' ' . $student->first_name) }}"
                                 data-student-id="{{ strtolower($student->student_id) }}">
                                <div class="card student-card shadow-sm h-100">
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
                                                <h6 class="mb-0 fw-bold">{{ $student->last_name }}, {{ $student->first_name }}</h6>
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
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}" title="Delete Student">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center mb-3">
                                                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                                                        <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                                                    </div>
                                                    <h5>Are you sure you want to delete this student?</h5>
                                                </div>
                                                <div class="alert alert-warning">
                                                    <p class="mb-0"><strong>{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</strong> will be permanently removed from your records.</p>
                                                </div>
                                                <p class="text-danger small"><i class="fas fa-info-circle me-1"></i> This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yes, Delete Student</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 bg-gradient-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 d-flex align-items-center">
                            <span class="bg-primary p-2 rounded-circle me-3 text-white">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            Assigned Subject Sections
                            <span class="badge bg-primary ms-2 badge-count">{{ $assignedSections->count() }} sections</span>
                        </h4>
                    </div>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Sections where you teach subjects but are not the adviser. You can only view grades for subjects you teach.
                    </p>
                </div>
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
                                Section: {{ $section->name }} (Grade {{ $section->grade_level }})
                                <span class="badge bg-primary ms-2 badge-count">{{ $assignedStudentsBySection[$section->id]->count() }} students</span>
                            </h5>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm border-0 mb-4">
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
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-male text-primary me-2"></i> Male Students
                            </div>
                            <div class="fw-bold">${data.male_count} (${data.male_percentage}%)</div>
                        </div>
                        
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-female text-danger me-2"></i> Female Students
                            </div>
                            <div class="fw-bold">${data.female_count} (${data.female_percentage}%)</div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${data.male_percentage}%" aria-valuenow="${data.male_percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: ${data.female_percentage}%" aria-valuenow="${data.female_percentage}" aria-valuemin="0" aria-valuemax="100"></div>
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
        updateGenderDistribution();

        // Toggle grade level sections
        toggleGradeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const grade = this.getAttribute('data-grade');
                const container = document.querySelector(`.grade-level-container[data-grade="${grade}"]`);
                const icon = this.querySelector('i');
                
                container.classList.toggle('d-none');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            });
        });

        // Add event listeners
        studentSearch.addEventListener('input', filterStudents);
        gradeFilter.addEventListener('change', filterStudents);
        sectionFilter.addEventListener('change', filterStudents);
        genderSectionFilter.addEventListener('change', updateGenderDistribution);
        
        // Reset filters
        resetFiltersBtn.addEventListener('click', function() {
            studentSearch.value = '';
            gradeFilter.value = '';
            sectionFilter.value = '';
            genderSectionFilter.value = 'all';
            filterStudents();
            updateGenderDistribution();
        });
        
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
                dropdownMenu.classList.toggle('show');
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
    });
</script>
@endpush
@endsection 