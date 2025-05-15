var waterLevelChart;
$(document).ready(function() {

    // Initialize cached data arrays for local storage
    let cachedLabels = JSON.parse(localStorage.getItem('waterLevelLabels')) || [];
    let cachedData = JSON.parse(localStorage.getItem('waterLevelData')) || [];

    var ctx = document.getElementById('waterLevelChart').getContext('2d');
    waterLevelChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: cachedLabels,
            datasets: [{
                label: 'Water Level (cm)',
                data: cachedData,
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time'
                    },
                    ticks: {
                        maxRotation: 90,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Water Level (cm)'
                    },
                    ticks: {
                        precision: 2,
                        autoSkip: false,
                        callback: function(value) {
                            return value.toFixed(2);
                        }
                    },
                    min: 0
                }
            },
            animation: {
                onComplete: function() {
                    window.waterLevelChartData = waterLevelChart.toBase64Image();
                    window.waterLevelChartRendered = true;
                }
            }
        }
    });

    function updateLocalStorage(labels, data) {
        localStorage.setItem('waterLevelLabels', JSON.stringify(labels));
        localStorage.setItem('waterLevelData', JSON.stringify(data));
    }

    function updateChartData() {
        $.ajax({
            url: 'functions/firebase-api.php',
            method: 'GET',
            success: function(response) {
                let waterLevel = response.waterLevel;
                let timestamp = `${waterLevel.Date} ${waterLevel.Time}`;
                let distance = parseFloat(waterLevel.Distance.replace(' cm', ''));
    
                let sensorHeight = response.SensorConfig.SensorHeight;
    
                // Update sensor height display
                $('#sensorHeight').text(sensorHeight + ' cm');
    
                // Update water status
                let status = determineWaterStatus(response.waterIndicator);
                $('#waterStatus').text(status);
                $('#water-Status').text(status);
    
                // Set status color
                updateStatusColor(status);
    
                // SEND SMS ALERT BASED ON STATUS //
                if (status === 'Medium' || status === 'High' || status === 'Overflow') {
                    // Check local storage to prevent repeated SMS for the same status
                    const lastSMSStatus = localStorage.getItem('lastSMSStatus');
                    const lastSMSTime = localStorage.getItem('lastSMSTime');
                    const currentTime = new Date().getTime();
    
                    // Only send SMS if:
                    // 1. Status is different from last time, OR
                    // 2. No SMS sent in the last 30 minutes (1800000 ms) for the same status
                    if (lastSMSStatus !== status || 
                        !lastSMSTime || 
                        (currentTime - parseInt(lastSMSTime)) > 1800000) {
                        
                        sendSMSAlert(status, distance);
                        
                        // Update localStorage with current status and time
                        localStorage.setItem('lastSMSStatus', status);
                        localStorage.setItem('lastSMSTime', currentTime.toString());
                    }
                }
                // SEND SMS ALERT BASED ON STATUS //
    
                // Update chart color based on status
                updateChartColor(status, waterLevelChart);
    
                // Dynamic sensor height scale adjustment
                if (waterLevelChart.options.scales.y.max !== sensorHeight) {
                    waterLevelChart.options.scales.y.max = sensorHeight;
    
                    // Set step size to ensure the exact sensor height is included
                    let desiredSteps = 8;
                    let stepSize = sensorHeight / desiredSteps;
    
                    waterLevelChart.options.scales.y.ticks.stepSize = stepSize;
                    waterLevelChart.update();
                }
    
                // Update chart data
                waterLevelChart.data.labels.push(timestamp);
                waterLevelChart.data.datasets[0].data.push(distance);
    
                // Keep only 20 points of plot data
                if (waterLevelChart.data.labels.length > 20) {
                    waterLevelChart.data.labels.shift();
                    waterLevelChart.data.datasets[0].data.shift();
                }
    
                // Update localStorage with current chart data
                updateLocalStorage(
                    waterLevelChart.data.labels,
                    waterLevelChart.data.datasets[0].data
                );
    
                waterLevelChart.update();
                $('#currentWaterLevel').text(`Current Water Level: ${waterLevel.Distance} (${timestamp})`);
    
                // Capture the real-time water level chart image
                window.waterLevelChartData = waterLevelChart.toBase64Image();
            }
        });
    }

    setInterval(updateChartData, 5000);
    updateChartData
