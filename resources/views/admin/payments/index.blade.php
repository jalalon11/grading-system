@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
    /* Custom styles for admin payment management page */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
        color: white !important;
    }

    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
        min-width: 36px;
        min-height: 36px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-sm.text-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: white;
        background-color: #4e73df;
        text-transform: uppercase;
    }

    /* Card hover effects */
    .card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Button styles */
    .btn-primary {
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
    }

    /* Badge styles */
    .badge {
        transition: all 0.2s ease;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    /* Table styles */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Pagination styles */
    .pagination {
        margin-bottom: 0;
        border-radius: 0.5rem;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.25rem;
    }

    .pagination .page-item {
        margin: 0 0.125rem;
    }

    .pagination .page-item .page-link {
        border: none;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #4e73df;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
        min-width: 2.5rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 2.5rem;
        font-size: 0.95rem;
    }

    .pagination .page-item.active .page-link {
        background-color: #4e73df;
        color: white;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .pagination .page-item .page-link:hover {
        background-color: rgba(78, 115, 223, 0.1);
        transform: translateY(-2px);
    }

    .pagination .page-item.disabled .page-link {
        color: #b7b9cc;
        background-color: #f8f9fc;
        pointer-events: none;
    }

    .pagination-container {
        background-color: white;
        border-radius: 0.75rem;
        padding: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        display: inline-flex;
    }

    /* Failed payment styles */
    tr.text-muted {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    tr.text-muted:hover {
        opacity: 0.95;
    }

    /* Pending payment styles */
    tr.pending-payment-row {
        background-color: rgba(255, 193, 7, 0.05);
        transition: all 0.3s ease;
    }

    tr.pending-payment-row:hover {
        background-color: rgba(255, 193, 7, 0.1);
    }

    /* Empty state styles */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
    }

    /* Status indicator */
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    /* Image object fit */
    .object-fit-cover {
        object-fit: contain;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }

    /* Header title visibility fix */
    .header-title {
        color: white !important;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        font-weight: 700 !important;
    }

    .header-subtitle {
        color: rgba(255,255,255,0.9) !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header with Gradient Background -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary text-white overflow-hidden position-relative animate__animated animate__fadeIn shadow" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; background-color: #4e73df !important;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 position-relative" style="z-index: 1;">
                            <h2 class="fw-bold display-6 mb-1 header-title"><i class="fas fa-credit-card me-2"></i>Payment Management</h2>
                            <p class="mb-0 header-subtitle">Manage and process school subscription payments</p>
                        </div>
                        <div class="col-md-6 text-md-end position-relative" style="z-index: 1;">
                            <div class="d-inline-block rounded-pill px-4 py-2 shadow-sm" style="background-color: rgba(255,255,255,0.25) !important;">
                                <span class="header-subtitle"><i class="fas fa-chart-line me-2"></i>Total Payments: <strong>{{ $payments->total() }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative elements -->
                <div class="position-absolute" style="top: -50px; right: -10px; width: 200px; height: 200px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                <div class="position-absolute" style="bottom: -80px; left: -20px; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-filter"></i>
                </div>
                <h5 class="fw-bold mb-0">Filter Payments</h5>
            </div>

            <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status" class="form-label text-muted small mb-1">Payment Status</label>
                        <select name="status" id="status" class="form-select form-select-lg shadow-sm border-0" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="school_id" class="form-label text-muted small mb-1">School</label>
                        <select name="school_id" id="school_id" class="form-select form-select-lg shadow-sm border-0" onchange="this.form.submit()">
                            <option value="">All Schools</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-lg me-2 px-4">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fas fa-redo me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments List -->
    <div class="card border-0 shadow-sm animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-list"></i>
                </div>
                <h5 class="fw-bold mb-0">Payments List</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th class="ps-4 py-3">Reference #</th>
                            <th class="py-3">School</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Method</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Billing Cycle</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="{{ $payment->status === 'failed' ? 'text-muted bg-light bg-opacity-50' : '' }} {{ $payment->status === 'pending' ? 'pending-payment-row' : '' }}">
                                <td class="ps-4 fw-medium">{{ $payment->reference_number }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($payment->school->logo_path)
                                            <div class="me-2" style="width: 36px; height: 36px; position: relative; border-radius: 50%; overflow: hidden; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                <img src="{{ $payment->school->logo_url }}" alt="{{ $payment->school->name }}" style="position: absolute; width: 100%; height: 100%; object-fit: contain; top: 0; left: 0;">
                                            </div>
                                        @else
                                            <div class="me-2" style="width: 36px; height: 36px; border-radius: 50%; background-color: #4e73df; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; text-transform: uppercase; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                {{ substr($payment->school->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="fw-medium text-truncate" style="max-width: 180px;">{{ $payment->school->name }}</span>
                                    </div>
                                </td>
                                <td class="fw-bold">₱{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $methodColors = [
                                            'gcash' => 'bg-info',
                                            'bank_transfer' => 'bg-primary',
                                            'cash' => 'bg-success',
                                            'check' => 'bg-secondary',
                                            'paymaya' => 'bg-warning'
                                        ];
                                        $methodColor = $methodColors[$payment->payment_method] ?? 'bg-info';

                                        // If status is failed, make the badge appear greyed out
                                        if ($payment->status === 'failed') {
                                            $methodColor = 'bg-secondary bg-opacity-50';
                                        }
                                    @endphp
                                    <span class="badge {{ $methodColor }} rounded-pill">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status === 'completed')
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator bg-success me-2"></span>
                                            <span>Completed</span>
                                        </div>
                                    @elseif($payment->status === 'pending')
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator bg-warning me-2 animate__animated animate__pulse animate__infinite"></span>
                                            <span class="fw-bold text-warning">Pending</span>
                                            <span class="ms-2 badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill animate__animated animate__pulse animate__infinite" style="animation-delay: 0.5s;">Action Required</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator bg-secondary me-2"></span>
                                            <span>Failed</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $billingCycleText = match($payment->billing_cycle) {
                                            'monthly' => 'Monthly',
                                            'yearly' => 'Yearly',
                                            default => 'Unknown'
                                        };
                                    @endphp
                                    <span class="badge {{ $payment->status === 'failed' ? 'bg-secondary bg-opacity-50 text-muted' : 'bg-primary' }} rounded-pill">
                                        {{ $billingCycleText }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    @if($payment->status === 'pending')
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm animate__animated animate__pulse animate__infinite" style="animation-delay: 1s;">
                                            <i class="fas fa-exclamation-circle me-1"></i> Review Now
                                        </a>
                                    @else
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm {{ $payment->status === 'failed' ? 'btn-outline-secondary' : 'btn-primary' }} rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon bg-light text-secondary mb-3">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <h6 class="fw-bold">No Payment Records</h6>
                                        <p class="text-muted mb-3">No payment records found matching your criteria.</p>
                                        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-redo me-2"></i> Reset Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($payments->hasPages())
        <div class="d-flex justify-content-center mt-4 animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
            <div class="pagination-container shadow-sm">
                {{ $payments->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            // Different animation for different payment statuses
            if (row.classList.contains('text-muted')) {
                row.classList.add('animate__animated', 'animate__fadeIn');
            } else if (row.classList.contains('pending-payment-row')) {
                row.classList.add('animate__animated', 'animate__fadeInRight');
                // Add a subtle highlight animation for pending rows
                setTimeout(() => {
                    row.classList.add('animate__flash');
                }, 1000 + (index * 200));
            } else {
                row.classList.add('animate__animated', 'animate__fadeInRight');
            }
            row.style.animationDelay = (0.3 + (index * 0.05)) + 's';
        });

        // Add special highlight to pending payment rows
        const pendingRows = document.querySelectorAll('.pending-payment-row');
        if (pendingRows.length > 0) {
            // Add a notification sound or visual indicator that there are pending payments
            const paymentHeader = document.querySelector('.header-title');
            if (paymentHeader) {
                const pendingBadge = document.createElement('span');
                pendingBadge.className = 'ms-2 badge bg-warning text-dark rounded-pill animate__animated animate__pulse animate__infinite';
                pendingBadge.innerHTML = `<i class="fas fa-bell me-1"></i> ${pendingRows.length} Pending`;
                pendingBadge.style.fontSize = '0.5em';
                pendingBadge.style.verticalAlign = 'middle';
                paymentHeader.appendChild(pendingBadge);
            }
        }

        // Add animation to badges
        const badges = document.querySelectorAll('.badge');
        badges.forEach((badge, index) => {
            badge.classList.add('animate__animated', 'animate__fadeIn');
            badge.style.animationDelay = (0.5 + (index * 0.05)) + 's';
        });

        // Add hover effect to action buttons
        const actionButtons = document.querySelectorAll('.btn-outline-secondary');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.classList.add('animate__animated', 'animate__pulse');
            });

            button.addEventListener('mouseleave', function() {
                this.classList.remove('animate__animated', 'animate__pulse');
            });
        });
    });
</script>
@endsection
