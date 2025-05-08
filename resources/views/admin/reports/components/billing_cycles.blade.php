<!-- Billing Cycle Distribution -->
<div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.8s;">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-calendar-alt text-warning me-2"></i> Billing Cycle Distribution</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadChart('billingCycleChart', 'Billing Cycle Distribution')">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="billingCycleChart"></canvas>
        </div>
    </div>
</div>

<!-- Hidden data for chart -->
<div id="billing-cycle-data" data-cycles="{{ json_encode($billingCycles) }}" style="display: none;"></div>
