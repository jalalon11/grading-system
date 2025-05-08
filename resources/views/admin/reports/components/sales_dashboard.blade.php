<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold">Current Month Sales</h6>
                        <h3 class="fw-bold mb-0">₱{{ number_format($currentMonthSales, 2) }}</h3>
                        <p class="small text-success mb-0">
                            <i class="fas fa-calendar-alt"></i> {{ date('F Y') }}
                        </p>
                    </div>
                    <div class="dashboard-icon bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-money-bill-wave text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold">Current Year Sales</h6>
                        <h3 class="fw-bold mb-0">₱{{ number_format($currentYearSales, 2) }}</h3>
                        <p class="small text-success mb-0">
                            <i class="fas fa-calendar-check"></i> {{ date('Y') }}
                        </p>
                    </div>
                    <div class="dashboard-icon bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-chart-line text-success fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold">Completed Payments</h6>
                        <h3 class="fw-bold mb-0">{{ $completedPaymentsCount }}</h3>
                        <p class="small text-success mb-0">
                            <i class="fas fa-check-circle"></i> Successfully Processed
                        </p>
                    </div>
                    <div class="dashboard-icon bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-receipt text-info fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.4s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold">Pending Payments</h6>
                        <h3 class="fw-bold mb-0">{{ $pendingPaymentsCount }}</h3>
                        <p class="small text-warning mb-0">
                            <i class="fas fa-clock"></i> Awaiting Approval
                        </p>
                    </div>
                    <div class="dashboard-icon bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-hourglass-half text-warning fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
