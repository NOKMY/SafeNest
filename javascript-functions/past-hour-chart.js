var pastHourChart;
$(document).ready(function() {
    // Get the chart context
    const hourCtx = document.getElementById('pastHourChart').getContext('2d');
    
    // Create gradient fill
    const gradient = hourCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.5)');
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0.05)');
    
    // Custom plugin to display "No Data" message
    const noDataPlugin = {
        id: 'noDataPlugin',
        afterDraw: function(chart) {
            if (chart.data.datasets[0].data.length === 0) {
                const ctx = chart.ctx;
                const width = chart.width;
                const height = chart.height;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = '16px Arial';
                ctx.fillStyle = 'rgba(128, 128, 128, 1)';
                ctx.fillText('No Data from the Past Hour', width / 2, height / 2);
                ctx.restore();
            }
        }
    };
    
    // Initialize empty chart with a placeholder dataset
    pastHourChart = new Chart(hourCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Water Level (cm)',
                data: [],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                borderWidth: 2,
                pointRadius: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Water Level (cm)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Time'
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return 'Time: ' + tooltipItems[0].label;
                        },
                        label: function(context) {
                            return 'Water Level: ' + context.raw + ' cm';
                        }
                    }
                },
                noDataPlugin: noDataPlugin
            },
            animation: {
                onComplete: function() {
                    // Capture the past hour chart image
                    window.pastHourChartData = pastHourChart.toBase64Image();
                    window.pastHourChartRendered = true;
                }
            }
        },
        plugins: [noDataPlugin]
    });
    
    // Function to fetch historical data
    function fetchLatestReadings() {
        $.ajax({
            url: 'functions/past-1hr-reading.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("Received latest 20 readings (3-min interval):", response);
    
                let timeLabels = [];
                let waterLevels = [];
    
                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(item => {
                        // Format time label
                        const time = new Date(item.time);
                        const timeLabel = time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    
                        timeLabels.push(timeLabel);
                        waterLevels.push(item.avgWaterLevel);
                    });
    
                    pastHourChart.data.labels = timeLabels;
                    pastHourChart.data.datasets[0].data = waterLevels;
                    pastHourChart.update();
    
                    // Update the average reading display
                    if (response.averageReading !== null) {
                        $('#averageReading').text(`${response.averageReading} cm`);
                    } else {
                        $('#averageReading').text('-- cm');
                    }
                } else {
                    console.log("No data available.");
                    pastHourChart.data.labels = [];
                    pastHourChart.data.datasets[0].data = [];
                    pastHourChart.update();
    
                    // Reset average display if no data
                    $('#averageReading').text('-- cm');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching latest readings:', error);
            }
        });
    }
    
    // Fetch data initially and refresh every 5 seconds
    fetchLatestReadings();
    setInterval(fetchLatestReadings, 10000);
    
    
});