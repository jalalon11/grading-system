<!-- Monthly Sales Chart -->
<div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.5s;">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i> Monthly Sales ({{ $year }})</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadChart('monthlySalesChart', 'Monthly Sales {{ $year }}')">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="monthlySalesChart"></canvas>
        </div>
    </div>
</div>

<!-- Hidden data for chart -->
<div id="monthly-sales-data" data-sales="{{ json_encode($monthlySales) }}" style="display: none;"></div>
