<!-- Yearly Sales Trend -->
<div class="card border-0 shadow-sm h-100 animate__animated animate__fadeIn" style="animation-delay: 0.7s;">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-line text-info me-2"></i> Yearly Sales Trend</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadChart('yearlySalesChart', 'Yearly Sales Trend')">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="yearlySalesChart"></canvas>
        </div>
    </div>
</div>

<!-- Hidden data for chart -->
<div id="yearly-sales-data" data-sales="{{ json_encode($yearlySales) }}" style="display: none;"></div>
