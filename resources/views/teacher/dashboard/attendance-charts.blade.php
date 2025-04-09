<!-- Attendance Charts -->
<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-line text-primary me-2"></i> Attendance Trends</h5>
        <div class="btn-group" role="group" aria-label="Attendance period toggle">
            <button type="button" class="btn btn-sm btn-outline-primary active" id="weeklyViewBtn">Weekly</button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="monthlyViewBtn">Monthly</button>
        </div>
    </div>
    <div class="card-body">
        <div id="weeklyAttendanceView">
            <div class="attendance-chart-container chart-container" style="height: 300px;">
                <canvas id="weeklyAttendanceChart"></canvas>
            </div>
        </div>
        <div id="monthlyAttendanceView" style="display: none;">
            <div class="attendance-chart-container chart-container" style="height: 300px;">
                <canvas id="monthlyAttendanceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart colors
    const fontColor = '#666';
    const gridColor = 'rgba(0, 0, 0, 0.1)';

    // Chart instances
    let weeklyChart, monthlyChart;

    // Fetch attendance data from the server
    fetch('{{ route("teacher.dashboard.attendance-data") }}')
        .then(response => response.json())
        .then(response => {
            if (response.error) {
                console.error('Error fetching attendance data:', response.error);
                return;
            }

            // Process weekly data
            const weeklyData = response.data?.weekly || {};
            const weeklyLabels = [];
            const weeklyPresent = [];
            const weeklyLate = [];
            const weeklyAbsent = [];
            const weeklyExcused = [];
            const weeklyHalfDay = [];

            // Process weekly data
            if (weeklyData.dates && weeklyData.daily_stats) {
                Object.keys(weeklyData.dates).forEach(date => {
                    // Format date for display
                    const formattedDate = weeklyData.dates[date] || date;
                    weeklyLabels.push(formattedDate);

                    // Get stats for this date
                    const stats = weeklyData.daily_stats[date] || {};
                    weeklyPresent.push(stats.present || 0);
                    weeklyLate.push(stats.late || 0);
                    weeklyHalfDay.push(stats.half_day || 0);
                    weeklyAbsent.push(stats.absent || 0);
                    weeklyExcused.push(stats.excused || 0);
                });
            }

            // Process monthly data
            const monthlyData = response.data?.monthly || {};
            const monthlyLabels = [];
            const monthlyPresent = [];
            const monthlyLate = [];
            const monthlyAbsent = [];
            const monthlyExcused = [];
            const monthlyHalfDay = [];

            // Process monthly data
            if (monthlyData.weekly_stats) {
                Object.entries(monthlyData.weekly_stats).forEach(([weekNumber, stats]) => {
                    // Format week label
                    const weekLabel = `Week ${weekNumber}: ${stats.start_date || ''} - ${stats.end_date || ''}`;
                    monthlyLabels.push(weekLabel);

                    // Get stats for this week
                    monthlyPresent.push(stats.present || 0);
                    monthlyLate.push(stats.late || 0);
                    monthlyHalfDay.push(stats.half_day || 0);
                    monthlyAbsent.push(stats.absent || 0);
                    monthlyExcused.push(stats.excused || 0);
                });
            }

            // Initialize weekly chart
            const weeklyCtx = document.getElementById('weeklyAttendanceChart');
            if (weeklyCtx) {
                weeklyChart = new Chart(weeklyCtx, {
                    type: 'bar',
                    data: {
                        labels: weeklyLabels,
                        datasets: [
                            {
                                label: 'Present',
                                data: weeklyPresent,
                                backgroundColor: '#28a745',
                                borderColor: '#28a745',
                                borderWidth: 1
                            },
                            {
                                label: 'Late',
                                data: weeklyLate,
                                backgroundColor: '#ffc107',
                                borderColor: '#ffc107',
                                borderWidth: 1
                            },
                            {
                                label: 'Half Day',
                                data: weeklyHalfDay,
                                backgroundColor: '#17a2b8',
                                borderColor: '#17a2b8',
                                borderWidth: 1
                            },
                            {
                                label: 'Absent',
                                data: weeklyAbsent,
                                backgroundColor: '#dc3545',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            },
                            {
                                label: 'Excused',
                                data: weeklyExcused,
                                backgroundColor: '#6c757d',
                                borderColor: '#6c757d',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: fontColor
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: fontColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            y: {
                                ticks: {
                                    color: fontColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            }
                        }
                    }
                });
            }

            // Initialize monthly chart
            const monthlyCtx = document.getElementById('monthlyAttendanceChart');
            if (monthlyCtx) {
                monthlyChart = new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: monthlyLabels,
                        datasets: [
                            {
                                label: 'Present',
                                data: monthlyPresent,
                                backgroundColor: '#28a745',
                                borderColor: '#28a745',
                                borderWidth: 1
                            },
                            {
                                label: 'Late',
                                data: monthlyLate,
                                backgroundColor: '#ffc107',
                                borderColor: '#ffc107',
                                borderWidth: 1
                            },
                            {
                                label: 'Half Day',
                                data: monthlyHalfDay,
                                backgroundColor: '#17a2b8',
                                borderColor: '#17a2b8',
                                borderWidth: 1
                            },
                            {
                                label: 'Absent',
                                data: monthlyAbsent,
                                backgroundColor: '#dc3545',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            },
                            {
                                label: 'Excused',
                                data: monthlyExcused,
                                backgroundColor: '#6c757d',
                                borderColor: '#6c757d',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: fontColor
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: fontColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            y: {
                                ticks: {
                                    color: fontColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
        });

    // Toggle between weekly and monthly views
    const weeklyViewBtn = document.getElementById('weeklyViewBtn');
    const monthlyViewBtn = document.getElementById('monthlyViewBtn');
    const weeklyAttendanceView = document.getElementById('weeklyAttendanceView');
    const monthlyAttendanceView = document.getElementById('monthlyAttendanceView');

    if (weeklyViewBtn && monthlyViewBtn) {
        weeklyViewBtn.addEventListener('click', function() {
            weeklyViewBtn.classList.add('active');
            monthlyViewBtn.classList.remove('active');
            weeklyAttendanceView.style.display = 'block';
            monthlyAttendanceView.style.display = 'none';
        });

        monthlyViewBtn.addEventListener('click', function() {
            monthlyViewBtn.classList.add('active');
            weeklyViewBtn.classList.remove('active');
            monthlyAttendanceView.style.display = 'block';
            weeklyAttendanceView.style.display = 'none';
        });
    }
});
</script>
