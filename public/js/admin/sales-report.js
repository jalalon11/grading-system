/**
 * Sales Report JavaScript
 * 
 * This file contains the JavaScript code for the sales report functionality.
 * It handles chart creation, data visualization, and export functionality.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Chart.js Global Configuration
    Chart.defaults.font.family = "'Nunito', 'Segoe UI', 'Arial'";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6c757d';
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 4;
    Chart.defaults.plugins.tooltip.titleFont = { weight: 'bold' };
    
    // Initialize charts if the elements exist
    if (document.getElementById('monthlySalesChart')) {
        initMonthlySalesChart();
    }
    
    if (document.getElementById('paymentMethodsChart')) {
        initPaymentMethodsChart();
    }
    
    if (document.getElementById('yearlySalesChart')) {
        initYearlySalesChart();
    }
    
    if (document.getElementById('billingCycleChart')) {
        initBillingCycleChart();
    }
});

/**
 * Initialize Monthly Sales Chart
 */
function initMonthlySalesChart() {
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
}

/**
 * Initialize Payment Methods Chart
 */
function initPaymentMethodsChart() {
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    const paymentMethodsData = JSON.parse(document.getElementById('payment-methods-data').dataset.methods);
    
    const methodLabels = paymentMethodsData.map(item => {
        const methods = {
            'bank_transfer': 'Bank Transfer',
            'gcash': 'GCash',
            'paymaya': 'PayMaya',
            'other': 'Other'
        };
        return methods[item.payment_method] || item.payment_method;
    });
    
    const methodCounts = paymentMethodsData.map(item => item.count);
    const methodColors = [
        'rgba(78, 115, 223, 0.8)',
        'rgba(28, 200, 138, 0.8)',
        'rgba(246, 194, 62, 0.8)',
        'rgba(231, 74, 59, 0.8)'
    ];
    
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
}

/**
 * Initialize Yearly Sales Chart
 */
function initYearlySalesChart() {
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
}

/**
 * Initialize Billing Cycle Chart
 */
function initBillingCycleChart() {
    const billingCycleCtx = document.getElementById('billingCycleChart').getContext('2d');
    const billingCycleData = JSON.parse(document.getElementById('billing-cycle-data').dataset.cycles);
    
    const cycleLabels = billingCycleData.map(item => {
        return item.billing_cycle === 'monthly' ? 'Monthly' : 'Yearly';
    });
    
    const cycleCounts = billingCycleData.map(item => item.count);
    const cycleAmounts = billingCycleData.map(item => item.total);
    
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

/**
 * Download chart as image
 * 
 * @param {string} chartId - The ID of the chart canvas element
 * @param {string} filename - The filename for the downloaded image
 */
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
