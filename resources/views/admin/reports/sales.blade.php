@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header with Gradient Background -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary text-white overflow-hidden position-relative animate__animated animate__fadeIn shadow" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; background-color: #4e73df !important;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 position-relative" style="z-index: 1;">
                            <h2 class="fw-bold display-6 mb-1 header-title"><i class="fas fa-chart-line me-2"></i>Sales Reports</h2>
                            <p class="mb-0 header-subtitle">Analyze payment data and track revenue</p>
                            <div class="mt-2">
                                <a href="{{ route('admin.reports.sales.print.monthly', ['year' => $year, 'month' => $month]) }}" target="_blank" class="btn btn-sm btn-outline-light">Test Monthly Print</a>
                                <a href="{{ route('admin.reports.sales.print.yearly', ['year' => $year]) }}" target="_blank" class="btn btn-sm btn-outline-light ms-2">Test Yearly Print</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <form action="{{ route('admin.reports.sales') }}" method="GET" class="d-flex flex-wrap justify-content-md-end gap-2">
                                <select name="year" class="form-select form-select-sm shadow-sm" style="width: auto;">
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                                <select name="month" class="form-select form-select-sm shadow-sm" style="width: auto;">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                <select name="school_id" class="form-select form-select-sm shadow-sm" style="width: auto;">
                                    <option value="">All Schools</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-light shadow-sm">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.reports.sales.print.monthly', ['year' => $year, 'month' => $month, 'school_id' => $schoolId]) }}" target="_blank" class="btn btn-sm btn-primary shadow-sm ms-2">
                                    <i class="fas fa-print me-1"></i> Monthly Report
                                </a>
                                <a href="{{ route('admin.reports.sales.print.yearly', ['year' => $year, 'school_id' => $schoolId]) }}" target="_blank" class="btn btn-sm btn-success shadow-sm ms-2">
                                    <i class="fas fa-print me-1"></i> Yearly Report
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Decorative shapes -->
                <div class="position-absolute" style="bottom: -20px; right: -10px; opacity: 0.1; transform: rotate(-10deg);">
                    <i class="fas fa-chart-pie fa-6x"></i>
                </div>
                <div class="position-absolute" style="top: -20px; right: 40%; opacity: 0.1;">
                    <i class="fas fa-coins fa-4x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @include('admin.reports.components.sales_dashboard')

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Monthly Sales Chart -->
        <div class="col-lg-8">
            @include('admin.reports.components.monthly_sales')
        </div>

        <!-- Payment Methods Chart -->
        <div class="col-lg-4">
            @include('admin.reports.components.payment_methods')
        </div>
    </div>

    <!-- Second Row of Charts -->
    <div class="row g-4 mb-4">
        <!-- Yearly Sales Trend -->
        <div class="col-lg-6">
            @include('admin.reports.components.yearly_sales')
        </div>

        <!-- Billing Cycle Distribution -->
        <div class="col-lg-6">
            @include('admin.reports.components.billing_cycles')
        </div>
    </div>

    <!-- Top Schools and Recent Payments -->
    <div class="row g-4">
        <!-- Top Schools -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn" style="animation-delay: 0.9s;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-trophy text-warning me-2"></i> Top Schools by Revenue ({{ $year }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="ps-4 py-3">School</th>
                                    <th class="py-3">Revenue</th>
                                    <th class="text-end pe-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSchools as $school)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($school->school->logo_path)
                                                    <div class="me-2" style="width: 36px; height: 36px; position: relative; border-radius: 50%; overflow: hidden; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                        <img src="{{ $school->school->logo_url }}" alt="{{ $school->school->name }}" style="position: absolute; width: 100%; height: 100%; object-fit: contain; top: 0; left: 0;">
                                                    </div>
                                                @else
                                                    <div class="me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 50%; background-color: #e9ecef; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                        <i class="fas fa-school text-secondary"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-medium">{{ $school->school->name }}</div>
                                                    <div class="small text-muted">{{ $school->school->school_division->name ?? 'No Division' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold">₱{{ number_format($school->total, 2) }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.reports.sales', ['school_id' => $school->school_id, 'year' => $year, 'month' => $month]) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="fas fa-search me-1"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn" style="animation-delay: 1s;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-receipt text-primary me-2"></i> Recent Payments</h5>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
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
                                    <th class="text-end pe-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $payment->reference_number }}</td>
                                        <td>{{ $payment->school->name }}</td>
                                        <td class="fw-bold">₱{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->payment_date->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No recent payments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/sales-report.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart.js Global Configuration
        Chart.defaults.font.family = "'Nunito', 'Segoe UI', 'Arial'";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#6c757d';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        Chart.defaults.plugins.tooltip.padding = 10;
        Chart.defaults.plugins.tooltip.cornerRadius = 4;
        Chart.defaults.plugins.tooltip.titleFont = { weight: 'bold' };

        // Initialize Bootstrap dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Debug data
        try {
            const paymentMethodsData = JSON.parse(document.getElementById('payment-methods-data').dataset.methods);
            const billingCycleData = JSON.parse(document.getElementById('billing-cycle-data').dataset.cycles);
            console.log('Payment Methods Data:', paymentMethodsData);
            console.log('Billing Cycle Data:', billingCycleData);
        } catch (e) {
            console.error('Error parsing chart data:', e);
        }

        // Initialize charts
        initializeCharts();
    });

    function initializeCharts() {
        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesData = JSON.parse(document.getElementById('monthly-sales-data').dataset.sales);
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        const monthlySalesChart = new Chart(monthlySalesCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Sales (₱)',
                    data: Object.values(monthlySalesData),
                    backgroundColor: 'rgba(78, 115, 223, 0.7)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(78, 115, 223, 0.9)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + new Intl.NumberFormat().format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + new Intl.NumberFormat().format(value);
                            }
                        }
                    }
                }
            }
        });

        // Payment Methods Chart
        const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const paymentMethodsData = JSON.parse(document.getElementById('payment-methods-data').dataset.methods);

        // Make sure we have data
        console.log('Payment Methods Data:', paymentMethodsData);

        const methodLabels = paymentMethodsData.map(item => {
            const methods = {
                'bank_transfer': 'Bank Transfer',
                'gcash': 'GCash',
                'paymaya': 'PayMaya',
                'other': 'Other'
            };
            return methods[item.payment_method] || item.payment_method;
        });

        const methodCounts = paymentMethodsData.map(item => parseInt(item.count) || 0);

        // Assign specific colors to each payment method for consistency
        const methodColors = [];
        paymentMethodsData.forEach(item => {
            switch(item.payment_method) {
                case 'bank_transfer':
                    methodColors.push('rgba(78, 115, 223, 0.8)'); // Blue for Bank Transfer
                    break;
                case 'gcash':
                    methodColors.push('rgba(28, 200, 138, 0.8)'); // Green for GCash
                    break;
                case 'paymaya':
                    methodColors.push('rgba(246, 194, 62, 0.8)'); // Yellow for PayMaya
                    break;
                default:
                    methodColors.push('rgba(231, 74, 59, 0.8)'); // Red for Other
            }
        });

        const paymentMethodsChart = new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: methodLabels,
                datasets: [{
                    data: methodCounts,
                    backgroundColor: methodColors,
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // Yearly Sales Chart
        const yearlySalesCtx = document.getElementById('yearlySalesChart').getContext('2d');
        const yearlySalesData = JSON.parse(document.getElementById('yearly-sales-data').dataset.sales);

        const yearLabels = Object.keys(yearlySalesData);
        const yearValues = Object.values(yearlySalesData);

        const yearlySalesChart = new Chart(yearlySalesCtx, {
            type: 'line',
            data: {
                labels: yearLabels,
                datasets: [{
                    label: 'Yearly Sales (₱)',
                    data: yearValues,
                    backgroundColor: 'rgba(54, 185, 204, 0.1)',
                    borderColor: 'rgba(54, 185, 204, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 185, 204, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + new Intl.NumberFormat().format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + new Intl.NumberFormat().format(value);
                            }
                        }
                    }
                }
            }
        });

        // Billing Cycle Chart
        const billingCycleCtx = document.getElementById('billingCycleChart').getContext('2d');
        const billingCycleData = JSON.parse(document.getElementById('billing-cycle-data').dataset.cycles);

        // Make sure we have data
        console.log('Billing Cycle Data:', billingCycleData);

        const cycleLabels = billingCycleData.map(item => {
            // Properly format the billing cycle labels
            const cycles = {
                'monthly': 'Monthly',
                'yearly': 'Yearly'
            };
            return cycles[item.billing_cycle] || item.billing_cycle;
        });

        const cycleCounts = billingCycleData.map(item => parseInt(item.count) || 0);
        const cycleAmounts = billingCycleData.map(item => parseFloat(item.total) || 0);

        const billingCycleChart = new Chart(billingCycleCtx, {
            type: 'bar',
            data: {
                labels: cycleLabels,
                datasets: [{
                    label: 'Number of Payments',
                    data: cycleCounts,
                    backgroundColor: 'rgba(246, 194, 62, 0.7)',
                    borderColor: 'rgba(246, 194, 62, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    order: 2
                }, {
                    label: 'Total Amount (₱)',
                    data: cycleAmounts,
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    type: 'line',
                    order: 1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Total Amount (₱)') {
                                    return '₱' + new Intl.NumberFormat().format(context.raw);
                                }
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Payments'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Total Amount (₱)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + new Intl.NumberFormat().format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Function to download chart as image
    function downloadChart(chartId, filename) {
        const canvas = document.getElementById(chartId);
        const image = canvas.toDataURL('image/png', 1.0);

        // Create download link
        const downloadLink = document.createElement('a');
        downloadLink.href = image;
        downloadLink.download = filename + '.png';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
@endpush
