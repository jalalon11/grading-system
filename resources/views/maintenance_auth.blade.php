@extends('layouts.maintenance')

@section('title', 'System Under Maintenance')

@push('styles')
<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', 'Segoe UI', Roboto, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .maintenance-wrapper {
        padding: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .system-update-card {
        background-color: #fff;
        background-image: linear-gradient(to bottom right, #ffffff, #f8fafc);
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .content-section {
        padding: 3rem 2rem;
    }

    .system-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .system-logo {
        width: 50px;
        height: 50px;
        background-color: #3182ce;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 16px rgba(49, 130, 206, 0.2);
    }

    .system-title {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: #1a3c61;
        margin: 0;
        line-height: 1.2;
    }

    .system-message {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        color: #4a5568;
        line-height: 1.6;
        padding: 1rem;
        background-color: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #3182ce;
    }

    .maintenance-info {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background-color: #ebf5ff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #3182ce;
        flex-shrink: 0;
    }

    .info-content h5 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .info-content p {
        font-size: 0.875rem;
        color: #718096;
        margin-bottom: 0;
    }

    .illustration-section {
        background-color: #e6f7ff;
        background-image: radial-gradient(#bfdbfe 1px, transparent 1px);
        background-size: 20px 20px;
        padding: 2rem;
        position: relative;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-left: 1px solid rgba(226, 232, 240, 0.8);
    }

    .illustration-container {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 500px;
    }

    .update-window {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(226, 232, 240, 0.8);
        width: 90%;
        max-width: 350px;
        margin: 0 auto;
        overflow: hidden;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
    }

    .window-header {
        background-color: #f8fafc;
        padding: 8px 12px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
    }

    .window-controls {
        display: flex;
        gap: 6px;
    }

    .control {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: block;
    }

    .control.red {
        background-color: #ff5f57;
    }

    .control.yellow {
        background-color: #ffbd2e;
    }

    .control.green {
        background-color: #28c940;
    }

    .window-content {
        padding: 2.5rem 2rem;
        text-align: center;
        background-color: #fff;
    }

    .update-icons {
        margin-bottom: 1.5rem;
        position: relative;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .gear-icon {
        font-size: 2rem;
        color: #3182ce;
        animation: spin 4s linear infinite;
        position: absolute;
    }

    .gear-icon-small {
        font-size: 1.25rem;
        color: #3182ce;
        animation: spin-reverse 3s linear infinite;
        position: absolute;
        margin-left: 35px;
        margin-top: -10px;
    }

    .updating-text {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a3c61;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .progress-container {
        margin-bottom: 0.5rem;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        margin-bottom: 0.5rem;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .progress-bar {
        background-color: #3182ce;
        border-radius: 4px;
        position: relative;
        overflow: hidden;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: linear-gradient(
            -45deg,
            rgba(255, 255, 255, 0.2) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0.2) 75%,
            transparent 75%,
            transparent
        );
        background-size: 16px 16px;
        animation: progress-animation 1s linear infinite;
    }

    .progress-text {
        text-align: right;
        font-weight: 600;
        color: #3182ce;
        font-size: 0.875rem;
    }

    @keyframes progress-animation {
        0% {
            background-position: 0 0;
        }
        100% {
            background-position: 16px 0;
        }
    }

    @keyframes spin-reverse {
        from { transform: rotate(0deg); }
        to { transform: rotate(-360deg); }
    }

    .illustration-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .element {
        position: absolute;
        font-size: 2rem;
        color: #3182ce;
        opacity: 0.6;
    }

    .gear.top-left {
        top: 15%;
        left: 15%;
        font-size: 2.5rem;
        color: #3182ce;
        opacity: 0.2;
        animation: spin 15s linear infinite;
    }

    .gear.top-right {
        top: 20%;
        right: 15%;
        font-size: 2rem;
        color: #3182ce;
        opacity: 0.2;
        animation: spin-reverse 12s linear infinite;
    }

    .plant.left {
        bottom: 15%;
        left: 15%;
        color: #48bb78;
        animation: float 7s ease-in-out infinite;
    }

    .plant.right {
        bottom: 15%;
        right: 15%;
        color: #48bb78;
        animation: float 9s ease-in-out infinite;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .user-info-compact {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 2rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #3182ce;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .user-details h5 {
        margin-bottom: 0.25rem;
        font-weight: 600;
        color: #2d3748;
    }

    .user-details p {
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #718096;
    }

    .logout-button {
        background-color: transparent;
        color: #64748b;
        border: 1px solid #cbd5e1;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .logout-button:hover {
        background-color: #f1f5f9;
        color: #334155;
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    .announcements-section {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a3c61;
    }

    .announcement-card {
        background-color: #f8fafc;
        border-radius: 8px;
        overflow: hidden;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .announcement-header {
        background-color: #edf2f7;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .announcement-title {
        margin-bottom: 0.25rem;
        font-weight: 600;
        color: #2d3748;
    }

    .announcement-date {
        font-size: 0.875rem;
        color: #718096;
        display: block;
    }

    .announcement-content {
        padding: 1rem;
        color: #4a5568;
        white-space: pre-line;
    }

    @media (max-width: 767.98px) {
        .content-section {
            padding: 2rem 1.5rem;
        }

        .illustration-section {
            min-height: 400px;
        }

        .system-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="maintenance-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Main Maintenance Card -->
                <div class="system-update-card">
                    <div class="row">
                        <div class="col-md-5 content-section">
                            <div class="system-header">
                                <div class="system-logo">
                                    <i class="fas fa-server"></i>
                                </div>
                                <h1 class="system-title">SYSTEM <span class="text-primary">UPDATE</span></h1>
                            </div>

                            @if(session('error'))
                            <div class="alert alert-danger mb-4">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            </div>
                            @endif

                            <div class="system-message">
                                {{ $maintenanceMessage }}
                            </div>

                            <div class="maintenance-info">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Estimated Completion</h5>
                                        @php
                                            $remainingMinutes = \App\Models\SystemSetting::getMaintenanceRemainingMinutes();
                                            $isPastEndTime = \App\Models\SystemSetting::isMaintenancePastEndTime();
                                        @endphp

                                        @if($isPastEndTime)
                                            <p>System will be completed any moment</p>
                                        @elseif($remainingMinutes !== null)
                                            <p>
                                                @if($remainingMinutes > 60)
                                                    Approximately {{ floor($remainingMinutes / 60) }} hour(s) and {{ $remainingMinutes % 60 }} minute(s)
                                                @else
                                                    Approximately {{ $remainingMinutes }} minute(s)
                                                @endif
                                            </p>
                                        @else
                                            <p>Please check back in a few minutes</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="user-info-compact">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <h5>{{ Auth::user()->name }}</h5>
                                        <p>{{ ucfirst(Auth::user()->role) }} • {{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-2"></i> You will be automatically redirected to your dashboard once the update is complete.
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="logout-button">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </div>

                        <div class="col-md-7 illustration-section">
                            <div class="illustration-container">
                                <div class="update-window">
                                    <div class="window-header">
                                        <div class="window-controls">
                                            <span class="control red"></span>
                                            <span class="control yellow"></span>
                                            <span class="control green"></span>
                                        </div>
                                    </div>
                                    <div class="window-content">
                                        <div class="update-icons">
                                            <i class="fas fa-cog gear-icon"></i>
                                            <i class="fas fa-cog gear-icon-small"></i>
                                        </div>
                                        <h3 class="updating-text">UPDATING...</h3>
                                        <div class="progress-container">
                                            <div class="progress">
                                                @php
                                                    $endTime = \App\Models\SystemSetting::getMaintenanceEndTime();
                                                    $duration = \App\Models\SystemSetting::getMaintenanceDuration();
                                                    $progressPercent = 65; // Default value

                                                    if ($endTime && $duration) {
                                                        try {
                                                            $endDateTime = new \DateTime($endTime);
                                                            $startTime = clone $endDateTime;
                                                            $startTime->modify('-' . $duration . ' minutes');
                                                            $now = new \DateTime();

                                                            if ($now > $endDateTime) {
                                                                $progressPercent = 100;
                                                            } else {
                                                                $totalDuration = $endDateTime->getTimestamp() - $startTime->getTimestamp();
                                                                $elapsedDuration = $now->getTimestamp() - $startTime->getTimestamp();
                                                                $progressPercent = min(99, max(1, round(($elapsedDuration / $totalDuration) * 100)));
                                                            }
                                                        } catch (\Exception $e) {
                                                            // Keep default value
                                                        }
                                                    }
                                                @endphp
                                                <div class="progress-bar" role="progressbar" style="width: {{ $progressPercent }}%" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="progress-text">{{ $progressPercent }}%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="illustration-elements">
                                    <div class="element gear top-left">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="element gear top-right">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div class="element plant left">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <div class="element plant right">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function to check maintenance mode status and redirect if disabled
    function checkMaintenanceStatus() {
        fetch('/maintenance/check-status-ajax', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.maintenance_mode) {
                // If maintenance mode is disabled, redirect to appropriate dashboard
                const userRole = "{{ Auth::user()->role }}";
                let redirectUrl = '/home';

                if (userRole === 'teacher') {
                    redirectUrl = '/teacher/dashboard';
                } else if (userRole === 'teacher-admin') {
                    redirectUrl = '/teacher-admin/dashboard';
                }

                window.location.href = redirectUrl;
            }
        })
        .catch(error => {
            console.error('Error checking maintenance status:', error);
        });
    }

    // Function to update the maintenance progress
    function updateMaintenanceProgress() {
        fetch('{{ route('maintenance.progress') }}')
            .then(response => response.json())
            .then(data => {
                // Update progress bar
                const progressBar = document.querySelector('.progress-bar');
                const progressText = document.querySelector('.progress-text');
                const completionMessage = document.querySelector('.info-content p');

                if (progressBar && progressText) {
                    progressBar.style.width = data.progress_percent + '%';
                    progressBar.setAttribute('aria-valuenow', data.progress_percent);
                    progressText.textContent = data.progress_percent + '%';
                }

                if (completionMessage) {
                    completionMessage.textContent = data.completion_message;
                }

                // If maintenance is complete (100%), check status more frequently
                if (data.progress_percent >= 100) {
                    setTimeout(checkMaintenanceStatus, 2000);
                }
            })
            .catch(error => {
                console.error('Error fetching maintenance progress:', error);
            });
    }

    // Check maintenance status every 10 seconds
    setInterval(checkMaintenanceStatus, 10000);

    // Update progress every 5 seconds
    setInterval(updateMaintenanceProgress, 5000);

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(checkMaintenanceStatus, 1000);
        updateMaintenanceProgress();
    });
</script>
@endpush
