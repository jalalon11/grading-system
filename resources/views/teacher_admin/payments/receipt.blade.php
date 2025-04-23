<!DOCTYPE html>
<html lang="en" class="receipt-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt #{{ $payment->reference_number }}</title>
    <!-- HTML2Canvas library for image capture -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        @page {
            size: 3.5in 8in;
            margin: 0.2cm;
        }

        @media print {
            body {
                font-family: Arial, sans-serif;
                line-height: 1.1;
                color: #333;
                margin: 0;
                padding: 0;
                font-size: 8pt;
            }

            .receipt-container {
                max-width: 100%;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .receipt-header {
                text-align: center;
                margin-bottom: 5px;
                border-bottom: 1px solid #eee;
                padding-bottom: 4px;
            }

            .receipt-header h1 {
                font-size: 12pt;
                margin-bottom: 1px;
                color: #2e59d9;
                margin-top: 0;
            }

            .receipt-header p {
                font-size: 7pt;
                color: #666;
                margin: 0;
                line-height: 1.1;
            }

            .receipt-status {
                display: inline-block;
                padding: 1px 5px;
                border-radius: 8px;
                font-weight: bold;
                font-size: 7pt;
                margin-top: 1px;
            }

            .receipt-status.completed {
                background-color: #e8f5e9;
                color: #1cc88a;
            }

            .receipt-status.failed {
                background-color: #ffebee;
                color: #e74a3b;
            }

            .receipt-status.pending {
                background-color: #fff8e1;
                color: #f6c23e;
            }

            .receipt-body {
                margin-bottom: 5px;
                padding: 0 5px;
            }

            .receipt-section {
                margin-bottom: 5px;
            }

            .receipt-section h2 {
                font-size: 9pt;
                margin: 0 0 2px 0;
                padding-bottom: 1px;
                border-bottom: 1px solid #eee;
                color: #2e59d9;
            }

            .receipt-row {
                display: flex;
                margin-bottom: 0;
                line-height: 1.1;
            }

            .receipt-label {
                flex: 0 0 90px;
                font-weight: bold;
                color: #555;
                font-size: 7pt;
            }

            .receipt-value {
                flex: 1;
                font-size: 7pt;
            }

            .receipt-notes {
                background-color: #f9f9f9;
                padding: 3px;
                border-radius: 2px;
                margin-top: 3px;
                font-size: 6pt;
            }

            .receipt-notes-title {
                font-weight: bold;
                margin-bottom: 1px;
                color: #555;
                font-size: 6pt;
            }

            .receipt-footer {
                margin-top: 5px;
                text-align: center;
                font-size: 6pt;
                color: #777;
                border-top: 1px solid #eee;
                padding-top: 3px;
            }

            .no-print {
                display: none;
            }

            .school-logo {
                max-height: 30px;
                max-width: 80px;
                margin: 0 auto 2px auto;
                object-fit: contain;
                display: block;
            }

            .school-logo-placeholder {
                width: 30px;
                height: 30px;
                background-color: #4e73df;
                color: white;
                font-size: 14px;
                font-weight: bold;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                margin: 0 auto 2px auto;
            }

            .amount {
                font-size: 8pt;
                font-weight: bold;
                color: #2e59d9;
            }
        }

        /* Non-print styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #f8f9fc;
            margin: 0;
            padding: 20px;
        }

        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 10px;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .receipt-header h1 {
            font-size: 20px;
            margin-bottom: 3px;
            color: #2e59d9;
        }

        .receipt-header p {
            font-size: 12px;
            color: #666;
            margin: 3px 0;
        }

        .receipt-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }

        .receipt-status.completed {
            background-color: #e8f5e9;
            color: #1cc88a;
        }

        .receipt-status.failed {
            background-color: #ffebee;
            color: #e74a3b;
        }

        .receipt-status.pending {
            background-color: #fff8e1;
            color: #f6c23e;
        }

        .receipt-body {
            margin-bottom: 15px;
        }

        .receipt-section {
            margin-bottom: 15px;
        }

        .receipt-section h2 {
            font-size: 16px;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1px solid #eee;
            color: #2e59d9;
        }

        .receipt-row {
            display: flex;
            margin-bottom: 6px;
        }

        .receipt-label {
            flex: 0 0 120px;
            font-weight: bold;
            color: #555;
        }

        .receipt-value {
            flex: 1;
        }

        .receipt-notes {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-top: 8px;
        }

        .receipt-notes-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .receipt-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .no-print {
            margin-top: 30px;
            text-align: center;
        }

        .no-print button {
            background-color: #2e59d9;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .no-print button:hover {
            background-color: #2e4bae;
        }

        .school-logo {
            max-height: 60px;
            max-width: 150px;
            margin: 0 auto 5px auto;
            object-fit: contain;
            display: block;
        }

        .school-logo-placeholder {
            width: 60px;
            height: 60px;
            background-color: #4e73df;
            color: white;
            font-size: 28px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 5px auto;
        }

        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #2e59d9;
        }

        @media (max-width: 768px) {
            .receipt-row {
                flex-direction: column;
            }

            .receipt-label {
                flex: 0 0 100%;
                margin-bottom: 5px;
            }

            .receipt-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="text-center" style="margin-bottom: 1px;">
                @if($school->logo_path)
                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="school-logo" onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
                    <div id="logo-fallback" class="school-logo-placeholder" style="display: none;">{{ substr($school->name, 0, 1) }}</div>
                @else
                    <div class="school-logo-placeholder">{{ substr($school->name, 0, 1) }}</div>
                @endif
                <h1>Payment Receipt</h1>
                <p>{{ $school->name }}</p>
                <div class="receipt-status {{ $payment->status }}" style="margin-top: 0;">
                    {{ ucfirst($payment->status) }}
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="receipt-section">
                <h2>Payment Information</h2>
                <div class="receipt-row">
                    <div class="receipt-label">Reference #</div>
                    <div class="receipt-value">{{ $payment->reference_number }}</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Amount</div>
                    <div class="receipt-value amount">₱{{ number_format($payment->amount, 2) }}</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Payment Date</div>
                    <div class="receipt-value">{{ $payment->payment_date->setTimezone('Asia/Manila')->format('M d, Y') }}</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Payment Method</div>
                    <div class="receipt-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Billing Cycle</div>
                    <div class="receipt-value">{{ ucfirst($payment->billing_cycle ?? $school->billing_cycle) }}</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">Processed On</div>
                    <div class="receipt-value">{{ $payment->updated_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</div>
                </div>
            </div>

            <div class="receipt-section">
                <h2>Subscription Period</h2>
                <div class="receipt-row">
                    <div class="receipt-label">Start Date</div>
                    <div class="receipt-value">{{ $payment->subscription_start_date->setTimezone('Asia/Manila')->format('M d, Y') }}</div>
                </div>
                <div class="receipt-row">
                    <div class="receipt-label">End Date</div>
                    <div class="receipt-value">{{ $payment->subscription_end_date->setTimezone('Asia/Manila')->format('M d, Y') }}</div>
                </div>
            </div>

            @if($payment->notes || $payment->admin_notes)
                <div class="receipt-section">
                    <h2>Additional Information</h2>

                    @if($payment->notes)
                        <div class="receipt-notes">
                            <div class="receipt-notes-title">Payment Notes</div>
                            <div>{{ $payment->notes }}</div>
                        </div>
                    @endif

                    @if($payment->admin_notes)
                        <div class="receipt-notes" style="margin-top: 3px;">
                            <div class="receipt-notes-title">
                                {{ $payment->status === 'completed' ? 'Approval Note' : ($payment->status === 'failed' ? 'Rejection Reason' : 'Admin Notes') }}
                            </div>
                            <div>{{ $payment->admin_notes }}</div>
                        </div>
                    @endif
                </div>
            @endif


        </div>

        <div class="receipt-footer">
            <p style="margin: 0;">Official receipt - Keep for your records</p>
            <p style="margin: 0;">{{ now()->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>

            @if(config('app.debug'))
            <!-- Debug information - only visible in debug mode -->
            <div class="debug-info small text-muted mt-4 p-2 border" style="display: none;">
                <p class="mb-1"><strong>Logo Path:</strong> {{ $school->logo_path ?: 'Not set' }}</p>
                <p class="mb-1"><strong>Logo URL:</strong> {{ $school->logo_url ?: 'Not available' }}</p>
                <p class="mb-0"><strong>School ID:</strong> {{ $school->id }}</p>
            </div>
            <script>
                // Add a way to show debug info with double-click
                document.querySelector('.receipt-footer').addEventListener('dblclick', function() {
                    document.querySelector('.debug-info').style.display = 'block';
                });
            </script>
            @endif
        </div>

        <div class="no-print" style="margin-top: 15px; text-align: center;">
            <button onclick="downloadAsImage()" id="downloadButton" style="background-color: #4e73df; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-right: 10px;">Download as Image</button>
            <button onclick="window.close()" style="background-color: #858796; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Close</button>
            <div id="downloadStatus" style="margin-top: 10px; font-size: 12px; color: #555; display: none;">Generating image...</div>
        </div>

        <script>
            // Auto-focus the download button when page loads
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('downloadButton').focus();
            });

            // Function to download the receipt as an image
            function downloadAsImage() {
                const statusElement = document.getElementById('downloadStatus');
                statusElement.style.display = 'block';

                // Get the receipt container element
                const receiptElement = document.querySelector('.receipt-container');

                // Hide the buttons during capture
                const buttonsElement = document.querySelector('.no-print');
                buttonsElement.style.display = 'none';

                // Use html2canvas to capture the receipt as an image
                html2canvas(receiptElement, {
                    scale: 2, // Higher scale for better quality
                    backgroundColor: '#ffffff',
                    logging: false,
                    useCORS: true
                }).then(function(canvas) {
                    // Show the buttons again
                    buttonsElement.style.display = 'block';

                    // Convert the canvas to a data URL
                    const imageData = canvas.toDataURL('image/png');

                    // Create a download link
                    const downloadLink = document.createElement('a');
                    downloadLink.href = imageData;
                    downloadLink.download = 'Receipt-{{ $payment->reference_number }}.png';

                    // Trigger the download
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    // Update status
                    statusElement.textContent = 'Download complete!';
                    setTimeout(() => {
                        statusElement.style.display = 'none';
                    }, 3000);
                }).catch(function(error) {
                    console.error('Error generating image:', error);
                    statusElement.textContent = 'Error generating image. Please try again.';
                    buttonsElement.style.display = 'block';
                });
            }
        </script>
    </div>
</body>
</html>
