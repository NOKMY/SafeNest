$(document).ready(function() {
    const ctx = document.getElementById('historicalChart').getContext('2d');
    let historicalChart = null;
    // Add variables for sensor thresholds
    let sensorConfig = null;

    // Fetch sensor configuration from Firebase
    function fetchSensorConfig() {
        return $.ajax({
            url: 'functions/sensor-config.php',
            method: 'GET',
            dataType: 'json'
        });
    }

    // Function to toggle loading overlay
    function toggleLoading(show) {
        if (show) {
            $('.chart-loading-overlay1').css('display', 'flex');
        } else {
            $('.chart-loading-overlay1').css('display', 'none');
        }
    }

    // Function to calculate daily average
    function calculateDailyAverage(records) {
        if (!records || Object.keys(records).length === 0) return 0;
    
        // Extract water levels from each reading within the date
        const waterLevels = Object.values(records) // Get all readings (Reading_001, Reading_002, etc.)
            .map(reading => reading["Water Level"] ? parseFloat(reading["Water Level"].replace(" cm", "")) : null)
            .filter(level => level !== null && !isNaN(level)); // Remove invalid entries
    
        if (waterLevels.length === 0) return 0; // Prevent division by zero
    
        return Math.round(waterLevels.reduce((sum, level) => sum + level, 0) / waterLevels.length);
    }
    
    // Function to get bar color based on water level and thresholds
    function getBarColor(value) {
        if (!sensorConfig) return 'rgba(75, 192, 192, 0.8)'; // Default color
        
        if (value >= sensorConfig.High) {
            return 'rgb(238, 101, 74)'; 
        } else if (value >= sensorConfig.Medium) {
            return 'rgba(241, 181, 41, 0.8)';
        } else if (value >= sensorConfig.Low) {
            return 'rgba(54, 162, 235, 0.8)';
        } else {
            return 'rgba(75, 192, 192, 0.8)'; 
        }
    }

    // Initialize the chart with empty data
    function initChart(labels, averages) {
        if (historicalChart) {
            historicalChart.destroy();
        }

        // Create background colors array based on thresholds
        const backgroundColors = averages.map(value => getBarColor(value));

        // Create the chart
        historicalChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Average Water Level (cm)',
                    data: averages,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                    borderWidth: 1,
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Average Water Level Readings by Date'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y + ' cm';
                                
                                // Add threshold category
                                if (sensorConfig) {
                                    const value = context.parsed.y;
                                    if (value >= sensorConfig.High) {
                                        label += ' (High)';
                                    } else if (value >= sensorConfig.Medium) {
                                        label += ' (Medium)';
                                    } else if (value >= sensorConfig.Low) {
                                        label += ' (Low)';
                                    } else {
                                        label += ' (Normal)';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Water Level (cm)'
                        },
                        // Explicit settings for the y-axis range
                        min: 0,
                        max: sensorConfig && sensorConfig.SensorHeight ? sensorConfig.SensorHeight : 30,
                        ticks: {
                            // Force integer step size based on sensor height
                            stepSize: sensorConfig && sensorConfig.SensorHeight ? Math.max(4, Math.ceil(sensorConfig.SensorHeight / 8)) : 4,
                            // Force all ticks to be integers
                            precision: 0,
                            callback: function(value) {
                                // Add threshold indicators to y-axis labels
                                if (sensorConfig) {
                                    if (value === sensorConfig.High) return value + ' cm (High)';
                                    if (value === sensorConfig.Medium) return value + ' cm (Medium)';
                                    if (value === sensorConfig.Low) return value + ' cm (Low)';
                                }
                                return value + ' cm';
                            }
                        },
                        // Add grid line styling
                        grid: {
                            color: function(context) {
                                if (sensorConfig) {
                                    if (context.tick.value === sensorConfig.High) return 'rgba(243, 147, 118, 0.5)'; // Vibrant orange
                                    if (context.tick.value === sensorConfig.Medium) return 'rgba(248, 231, 177, 0.5)'; // Vibrant yellow
                                    if (context.tick.value === sensorConfig.Low) return 'rgba(33, 150, 243, 0.5)'; // Vibrant blue
                                }
                                return 'rgba(0, 0, 0, 0.1)';
                            },
                            lineWidth: function(context) {
                                if (sensorConfig) {
                                    if (context.tick.value === sensorConfig.High ||
                                        context.tick.value === sensorConfig.Medium ||
                                        context.tick.value === sensorConfig.Low) {
                                        return 2;
                                    }
                                }
                                return 1;
                            }
                        }
                    }
                }
            }
        });

        // Add horizontal threshold lines if we have sensor config
        if (sensorConfig) {
            const thresholds = [
                { value: sensorConfig.High, color: 'rgb(225, 149, 126)', label: 'High' }, 
                { value: sensorConfig.Medium, color: 'rgb(241, 218, 150)', label: 'Medium' }, 
                { value: sensorConfig.Low, color: 'rgb(33, 150, 243)', label: 'Low' } 
            ];
            
            // Add annotation plugin for horizontal lines
            historicalChart.options.plugins.annotation = {
                annotations: thresholds.map((threshold, index) => ({
                    type: 'line',
                    scaleID: 'y',
                    value: threshold.value,
                    borderColor: threshold.color,
                    borderWidth: 2,
                    borderDash: [5, 5],
                    label: {
                        content: threshold.label + ' (' + threshold.value + ' cm)',
                        enabled: true,
                        position: 'right'
                    }
                }))
            };
            
            historicalChart.update();
        }
    }

    // Date range filter handler
    $('#historical-data').on('submit', function(e) {
        e.preventDefault();
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        toggleLoading(true);

        // First fetch sensor config, then fetch data
        fetchSensorConfig()
            .then(function(configResponse) {
                if (configResponse && configResponse.SensorConfig) {
                    sensorConfig = configResponse.SensorConfig;
                    
                    return $.ajax({
                        url: 'functions/list-db.php',
                        method: 'POST',
                        data: {
                            startDate: startDate,
                            endDate: endDate
                        }
                    });
                } else {
                    throw new Error('Failed to load sensor configuration');
                }
            })
            .then(function(response) {
                try {
                    if (response.status === 'success' && response.data.DailyRecords) {
                        const records = response.data.DailyRecords;
                    
                        const dates = [];
                        const averages = [];
            
                        Object.keys(records).sort().forEach(date => {
                            if (date >= startDate && date <= endDate) {
                                dates.push(date);
                                averages.push(calculateDailyAverage(records[date]));
                            }
                        });
            
                        // Calculate and display overall average
                        const overallAverage = averages.length > 0 
                            ? (averages.reduce((sum, val) => sum + val, 0) / averages.length).toFixed(2)
                            : '0.00';
            
                        // Update the average reading display
                        $('#averageReading').text(`${overallAverage} cm`);
            
                        // Add alert level to average reading
                        if (sensorConfig) {
                            const avgValue = parseFloat(overallAverage);
                            let alertClass = '';
                            let alertText = '';
                            
                            if (avgValue >= sensorConfig.High) {
                                alertClass = 'text-danger';
                                alertText = 'High Alert';
                            } else if (avgValue >= sensorConfig.Medium) {
                                alertClass = 'text-warning';
                                alertText = 'Medium Alert';
                            } else if (avgValue >= sensorConfig.Low) {
                                alertClass = 'text-primary';
                                alertText = 'Low Alert';
                            } else {
                                alertClass = 'text-success';
                                alertText = 'Normal';
                            }
                            
                            $('#averageAlert').html(`<span class="${alertClass}">${alertText}</span>`);
                        }
                        
                        initChart(dates, averages);
                    } else {
                        throw new Error('Invalid data structure');
                    }
                } catch (err) {
                    console.error('Error processing data:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process data'
                    });
                } finally {
                    toggleLoading(false);
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch data'
                });
                toggleLoading(false);
            });
    });

    // Initialize chart with empty data
    initChart([], []);
    
    // Fetch sensor config on page load to have it ready
    fetchSensorConfig()
        .then(function(configResponse) {
            if (configResponse && configResponse.SensorConfig) {
                sensorConfig = configResponse.SensorConfig;
                console.log('Sensor config loaded:', sensorConfig);
            }
        })
        .catch(function(error) {
            console.error('Error loading sensor config:', error);
        });
});



// Static Past Hour Chart
document.addEventListener('DOMContentLoaded', function() {
    // Mock data for past hour (static)
    const hourLabels = [
        '09:00', '09:10', '09:20', '09:30', '09:40', '09:50', '10:00'
    ];
    const hourData = [
        24, 26, 25, 27, 30, 28, 25
    ];
    
    // Create chart with static data
    const hourCtx = document.getElementById('pastHourChart').getContext('2d');
    const pastHourChart = new Chart(hourCtx, {
        type: 'line',
        data: {
            labels: hourLabels,
            datasets: [{
                label: 'Water Level (cm)',
                data: hourData,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
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
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Water Level (cm)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Time'
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
                }
            }
        }
    });
});