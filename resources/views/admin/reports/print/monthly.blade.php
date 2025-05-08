<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Sales Report - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            color: #333;
            line-height: 1.3;
            font-size: 12px;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            border: 1px solid #ddd;
        }
        .report-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .report-date {
            font-size: 12px;
            margin: 3px 0 0;
        }
        .report-view-options {
            margin-top: 5px;
            border-bottom: 1px solid #ddd;
            padding: 5px 10px;
        }
        .report-view-options a {
            margin-right: 15px;
            text-decoration: none;
            color: #0066cc;
            font-size: 12px;
        }
        .report-view-options a.active {
            font-weight: bold;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 5px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 4px 5px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .summary-section {
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .summary-table {
            width: auto;
            margin-left: auto;
        }
        .summary-table td {
            padding: 3px 10px;
            border: none;
        }
        .summary-table tr:last-child {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }
        .print-controls {
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 8px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .print-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            color: #666;
            padding-bottom: 10px;
        }
        .compact-table th, .compact-table td {
            padding: 3px 5px;
            font-size: 11px;
        }
        .compact-summary {
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 3px;
        }
        .compact-summary h3 {
            margin-top: 0;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }
        .compact-summary h4 {
            margin-top: 10px;
            font-size: 12px;
            color: #333;
            margin-bottom: 5px;
        }
        .compact-summary table {
            margin-bottom: 10px;
        }
        .compact-summary td {
            padding: 3px 0;
            font-size: 11px;
        }
        @media print {
            .print-controls {
                display: none;
            }
            body {
                padding: 0;
            }
            .report-container {
                border: none;
            }
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <a href="{{ route('admin.reports.sales', ['year' => $year, 'month' => $month, 'school_id' => $school ? $school->id : null]) }}" class="btn btn-secondary">Back to Reports</a>
        <button onclick="window.print()" class="btn">Print Report</button>
    </div>

    <div class="report-container">
        <div class="report-header">
            <h1 class="report-title">Monthly Sales Report</h1>
            <p class="report-date">{{ $monthName }} 1, {{ $year }} - {{ $monthName }} {{ Carbon\Carbon::createFromDate($year, $month)->endOfMonth()->format('d') }}, {{ $year }} {{ $school ? '- ' . $school->name : '' }}</p>
        </div>

        <div class="report-view-options">
            <a href="#" class="view-option active" data-view="detailed">Detailed View</a>
            <a href="#" class="view-option" data-view="summary">Summary View</a>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get all view option links
                const viewOptions = document.querySelectorAll('.view-option');
                const detailedView = document.getElementById('detailed-view');
                const summaryView = document.getElementById('summary-view');

                // Add click event to each option
                viewOptions.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Remove active class from all options
                        viewOptions.forEach(opt => opt.classList.remove('active'));

                        // Add active class to clicked option
                        this.classList.add('active');

                        // Show/hide appropriate view
                        const viewType = this.getAttribute('data-view');
                        if (viewType === 'detailed') {
                            detailedView.style.display = 'block';
                            summaryView.style.display = 'none';
                        } else {
                            detailedView.style.display = 'none';
                            summaryView.style.display = 'block';
                        }
                    });
                });
            });
        </script>

        <!-- Detailed View -->
        <div id="detailed-view" style="display: block;">
            <!-- Payment Methods Summary -->
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th class="text-center">Count</th>
                        <th class="text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentMethods as $method)
                        @if($method['count'] > 0)
                        <tr>
                            <td>
                                @if($method['payment_method'] == 'bank_transfer')
                                    Bank Transfer
                                @elseif($method['payment_method'] == 'gcash')
                                    GCash
                                @elseif($method['payment_method'] == 'paymaya')
                                    PayMaya
                                @else
                                    {{ ucfirst($method['payment_method']) }}
                                @endif
                            </td>
                            <td class="text-center">{{ $method['count'] }}</td>
                            <td class="text-right">₱{{ number_format($method['total'], 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    <tr class="summary-row">
                        <td>Total</td>
                        <td class="text-center">{{ $payments->count() }}</td>
                        <td class="text-right">₱{{ number_format($totalSales, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Billing Cycles Summary -->
            <table class="compact-table" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th>Billing Cycle</th>
                        <th class="text-center">Count</th>
                        <th class="text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billingCycles as $cycle)
                        @if($cycle['count'] > 0)
                        <tr>
                            <td>
                                @if($cycle['billing_cycle'] == 'monthly')
                                    Monthly
                                @elseif($cycle['billing_cycle'] == 'yearly')
                                    Yearly
                                @else
                                    {{ ucfirst($cycle['billing_cycle']) }}
                                @endif
                            </td>
                            <td class="text-center">{{ $cycle['count'] }}</td>
                            <td class="text-right">₱{{ number_format($cycle['total'], 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    <tr class="summary-row">
                        <td>Total</td>
                        <td class="text-center">{{ $payments->count() }}</td>
                        <td class="text-right">₱{{ number_format($totalSales, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Detailed Payments -->
            <table class="compact-table" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference #</th>
                        <th>School</th>
                        <th>Payment Method</th>
                        <th>Billing Cycle</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->setTimezone('Asia/Manila')->format('m/d/Y') }}</td>
                            <td>{{ $payment->reference_number }}</td>
                            <td>{{ $payment->school->name }}</td>
                            <td>
                                @if($payment->payment_method == 'bank_transfer')
                                    Bank Transfer
                                @elseif($payment->payment_method == 'gcash')
                                    GCash
                                @elseif($payment->payment_method == 'paymaya')
                                    PayMaya
                                @else
                                    {{ ucfirst($payment->payment_method) }}
                                @endif
                            </td>
                            <td>{{ ucfirst($payment->billing_cycle) }}</td>
                            <td class="text-right">₱{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No payments found for this period</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary View -->
        <div id="summary-view" style="display: none;">
            <div class="compact-summary">
                <h3>Monthly Sales Summary - {{ $monthName }} {{ $year }}</h3>
                <table class="compact-table">
                    <tr>
                        <td style="width: 50%; font-weight: bold;">Total Revenue:</td>
                        <td style="text-align: right; font-weight: bold;">₱{{ number_format($totalSales, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Transactions:</td>
                        <td style="text-align: right;">{{ $payments->count() }}</td>
                    </tr>
                    <tr>
                        <td>Average Transaction:</td>
                        <td style="text-align: right;">₱{{ $payments->count() > 0 ? number_format($totalSales / $payments->count(), 2) : '0.00' }}</td>
                    </tr>
                </table>

                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; margin-top: 10px;">
                    <div style="width: 48%;">
                        <h4>Payment Methods</h4>
                        <table class="compact-table">
                            @foreach($paymentMethods as $method)
                                @if($method['count'] > 0)
                                <tr>
                                    <td>
                                        @if($method['payment_method'] == 'bank_transfer')
                                            Bank Transfer
                                        @elseif($method['payment_method'] == 'gcash')
                                            GCash
                                        @elseif($method['payment_method'] == 'paymaya')
                                            PayMaya
                                        @else
                                            {{ ucfirst($method['payment_method']) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;">₱{{ number_format($method['total'], 2) }}</td>
                                </tr>
                                @endif
                            @endforeach
                            <tr style="border-top: 1px solid #ddd; font-weight: bold;">
                                <td>Total</td>
                                <td style="text-align: right;">₱{{ number_format($totalSales, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <div style="width: 48%;">
                        <h4>Billing Cycles</h4>
                        <table class="compact-table">
                            @foreach($billingCycles as $cycle)
                                @if($cycle['count'] > 0)
                                <tr>
                                    <td>
                                        @if($cycle['billing_cycle'] == 'monthly')
                                            Monthly
                                        @elseif($cycle['billing_cycle'] == 'yearly')
                                            Yearly
                                        @else
                                            {{ ucfirst($cycle['billing_cycle']) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right;">₱{{ number_format($cycle['total'], 2) }}</td>
                                </tr>
                                @endif
                            @endforeach
                            <tr style="border-top: 1px solid #ddd; font-weight: bold;">
                                <td>Total</td>
                                <td style="text-align: right;">₱{{ number_format($totalSales, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td>Total Payments:</td>
                    <td class="text-right">{{ $payments->count() }}</td>
                </tr>
                <tr>
                    <td>Total Sales:</td>
                    <td class="text-right">₱{{ number_format($totalSales, 2) }}</td>
                </tr>
                <tr>
                    <td>Average Payment:</td>
                    <td class="text-right">₱{{ $payments->count() > 0 ? number_format($totalSales / $payments->count(), 2) : '0.00' }}</td>
                </tr>
            </table>
        </div>

        <div class="print-footer">
            <p>Generated from Grading System | {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
