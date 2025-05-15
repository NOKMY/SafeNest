// CURRENT DATE //
$(document).ready(function() {
    function updateTime() {
        var now = new Date();
        var formattedDate = now.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        });

        formattedDate = formattedDate
            .replace(/(\w+)\s(\d+),/, '$1. $2,') 
            .replace(/PM/, 'p.m.')
            .replace(/AM/, 'a.m.');
        
        $('#current-time').text(formattedDate);
    }
    
    updateTime();
    setInterval(updateTime, 1000);
});
// CURRENT DATE //


// TOOL TIP //
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
// TOOL TIP //


// WATER LEVEL CHART //
$(document).ready(function () {
    let lastTimestamp = null; // To track the last processed timestamp
    let isUpdating = false;   // Prevent overlapping AJAX requests

    // Fetch initial data from API and initialize the chart
    $.ajax({
        url: 'functions/firebase-api.php',
        method: 'GET',
        success: function (response) {
            let dailyRecords = response.DailyRecords || {};
            let latestEntries = Object.values(dailyRecords).slice(-20); // Get the latest 20 entries

            // Prepare labels and data for the chart
            let initialLabels = latestEntries.map(entry => `${entry.Date} ${entry.Time}`);
            let initialData = latestEntries.map(entry => parseFloat(entry.Distance.replace(' cm', '')));

            initializeChart(initialLabels, initialData); // Initialize the chart with fetched data

            // Update last timestamp to avoid duplicates in real-time updates
            if (latestEntries.length > 0) {
                lastTimestamp = `${latestEntries[latestEntries.length - 1].Date} ${latestEntries[latestEntries.length - 1].Time}`;
            }
        }
    });

    function initializeChart(labels, data) {
        var ctx = document.getElementById('waterLevelChart').getContext('2d');
        waterLevelChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Water Level (cm)',
                    data: data,
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: { display: true, text: 'Time' },
                        ticks: { maxRotation: 90, minRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Water Level (cm)' },
                        ticks: {
                            precision: 2,
                            autoSkip: false,
                            callback: function (value) { return value.toFixed(2); }
                        },
                        min: 0
                    }
                },
                animation: {
                    onComplete: function () {
                        window.waterLevelChartData = waterLevelChart.toBase64Image();
                        window.waterLevelChartRendered = true;
                    }
                }
            }
        });

        // Start updating data at regular intervals after initialization
        setInterval(updateChartData, 5000);
    }

    function updateChartData() {
        if (isUpdating) return; // Prevent overlapping AJAX calls
        isUpdating = true;

        $.ajax({
            url: 'functions/firebase-api.php',
            method: 'GET',
            success: function (response) {
                isUpdating = false; // Reset flag when the request is complete

                // Handle sensor status
                let sensorStatus = response.SensorStatus || {};
let latestEntry = getLatestSensorEntry(sensorStatus);
let isActive = checkSensorActive(latestEntry);


                if (!isActive) {
                    $('#chartContainer').hide(); // Hide chart if sensor is inactive
                    $('#sensorStatus').text('Off').removeClass('status-active').addClass('status-inactive');
                    $('#sensorHeight').text('Off');
                    $('#waterStatus').text('Sensor is off');
                    $('#water-Status').text('Sensor is off');
                    return;
                } else {
                    $('#chartContainer').show(); // Show chart if sensor is active
                    $('#sensorStatus').text('Active').removeClass('status-inactive').addClass('status-active');
                }

                // Update chart data with waterLevel
                let waterLevel = response.waterLevel;
                let timestamp = `${waterLevel.Date} ${waterLevel.Time}`;
                let distance = parseFloat(waterLevel.Distance.replace(' cm', ''));

                // Ensure we only add new data if the timestamp is unique
                if (timestamp !== lastTimestamp) {
                    lastTimestamp = timestamp; // Update the last processed timestamp
                    waterLevelChart.data.labels.push(timestamp);
                    waterLevelChart.data.datasets[0].data.push(distance);

                    // Apply FIFO logic to keep only the last 20 entries
                    waterLevelChart.data.labels = waterLevelChart.data.labels.slice(-20);
                    waterLevelChart.data.datasets[0].data = waterLevelChart.data.datasets[0].data.slice(-20);

                    // Step 3: Update chart appearance dynamically
                    let sensorHeight = response.SensorConfig.SensorHeight;
                    $('#sensorHeight').text(sensorHeight + ' cm');

                    let status = determineWaterStatus(response.waterIndicator);
                    $('#waterStatus').text(status);
                    $('#water-Status').text(status);
                    updateStatusColor(status);
                    $('#WaterLevel').text(`${distance} cm`);

                    updateChartColor(status, waterLevelChart);

                    // Dynamically adjust Y-axis max value
                    if (waterLevelChart.options.scales.y.max !== sensorHeight) {
                        waterLevelChart.options.scales.y.max = sensorHeight;
                        let desiredSteps = 8;
                        let stepSize = sensorHeight / desiredSteps;
                        waterLevelChart.options.scales.y.ticks.stepSize = stepSize;
                    }

                    // Update the chart
                    waterLevelChart.update();
                    $('#currentWaterLevel').text(`Current Water Level: ${waterLevel.Distance} (${timestamp})`);
                    window.waterLevelChartData = waterLevelChart.toBase64Image();
                }
            },
            error: function () {
                isUpdating = false; // Reset flag on error
            }
        });
    }
    
    function getLatestSensorEntry(sensorStatus) {
        // Check if sensorStatus exists
        if (!sensorStatus || Object.keys(sensorStatus).length === 0) {
            console.log('SensorStatus is empty or undefined');
            return null;
        }
        
        try {
            // Get the latest date
            let latestDate = Object.keys(sensorStatus).sort().pop();
            
            // Check if the date object has entries
            if (!sensorStatus[latestDate] || typeof sensorStatus[latestDate] !== 'object') {
                console.log(`No valid entries found for date: ${latestDate}`);
                return null;
            }
            
            // Get entries for the latest date
            let entries = Object.values(sensorStatus[latestDate]);
            
            // Filter for only string entries (which should be timestamps)
            let timeEntries = entries.filter(entry => typeof entry === 'string' && entry.includes(' : '));
            
            if (timeEntries.length === 0) {
                console.log('No valid time entries found');
                return null;
            }
            
            // Get the latest entry
            let latestEntry = timeEntries.sort().pop();
            return latestEntry;
        } catch (error) {
            console.error('Error in getLatestSensorEntry:', error);
            return null;
        }
    }
    
    function checkSensorActive(latestEntry) {
        // If there's no latest entry, the sensor is inactive
        if (!latestEntry) {
            console.log('No latest entry found, sensor is inactive');
            return false;
        }
        
        try {
            // Parse the time from the entry
            let parts = latestEntry.split(' : ');
            if (parts.length < 2) {
                console.log('Invalid entry format:', latestEntry);
                return false;
            }
            
            let datePart = parts[0]; // e.g., "2025-03-18"
            let timePart = parts[1]; // e.g., "22:32:10"
            
            // Create a date object from the entry
            let [year, month, day] = datePart.split('-').map(Number);
            let [hours, minutes, seconds] = timePart.split(':').map(Number);
            
            let entryTime = new Date(year, month - 1, day, hours, minutes, seconds);
            let currentTime = new Date();
            
            // Calculate time difference in seconds
            let diffSeconds = (currentTime - entryTime) / 1000;
            
            // For debugging
            console.log(`Last sensor reading: ${entryTime.toLocaleString()}`);
            console.log(`Current time: ${currentTime.toLocaleString()}`);
            console.log(`Time difference: ${diffSeconds} seconds`);
            
            // Adjust the threshold if needed - currently checking if reading is within 15 seconds
            // Since your readings are from yesterday, this will be false
            const ACTIVE_THRESHOLD_SECONDS = 15;
            return diffSeconds <= ACTIVE_THRESHOLD_SECONDS;
        } catch (error) {
            console.error('Error in checkSensorActive:', error);
            return false;
        }
    }

    // const DEBUG_OVERRIDE = true; // Set to true for testing, false for production

    // function checkSensorActive(latestEntry) {
    //     if (DEBUG_OVERRIDE) {
    //         // Override logic: Simulate the sensor being active
    //         return true;
    //     }
    
    //     if (!latestEntry) return false;
    //     let latestTimeStr = latestEntry.split(' : ')[1];
    //     let latestTime = new Date();
    //     let [hours, minutes, seconds] = latestTimeStr.split(':').map(Number);
    //     latestTime.setHours(hours, minutes, seconds, 0);
    //     let currentTime = new Date();
    //     return (currentTime - latestTime) / 1000 <= 15; // Active if within 15 seconds
    // }
    
    setInterval(updateChartData, 5000);
    updateChartData();
    

//     // Test function for SMS alerts
// TEST FUNCTION FOR SENDING SMS //
function testSMSFunction(status, waterLevel) {
    console.log(`Testing SMS Alert for status: ${status}, water level: ${waterLevel}cm`);
    
    // Clear previous status from localStorage to ensure the test SMS gets sent
    localStorage.removeItem('lastSMSStatus');
    localStorage.removeItem('lastSMSTime');
    // Call the sendSMSAlert function directly
    sendSMSAlert(status, waterLevel);
}

$('#testMediumAlert').click(function() {
    testSMSFunction('Medium', 150);
});

$('#testHighAlert').click(function() {
    testSMSFunction('High', 200);
});

$('#testOverflowAlert').click(function() {
    testSMSFunction('Overflow', 300);
});
// TEST FUNCTION FOR SENDING SMS //

// FUNCTION TO SEND SMS ALERT //
function sendSMSAlert(status, waterLevel) {
    $.ajax({
        url: 'functions/send-message.php',
        method: 'POST',
        data: { 
            status: status,
            waterLevel: waterLevel
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    console.log('SMS alerts sent successfully');
                } else {
                    console.error('Error sending SMS alerts:', result.error);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error sending SMS alerts:', error);
        }
    });
}
// FUNCTION TO SEND SMS ALERT //



// FUNCTION TO UPDATE CHART COLOR BASED ON STATUS //
function updateChartColor(status, chart) {
        // Get the chart context to create gradients
        const ctx = chart.ctx;
        const chartArea = chart.chartArea;
        
        // Create a gradient (will be defined based on status)
        let gradient;
        let borderColor;
        let pointBackgroundColor;
        
        // Only create gradient if chart is rendered
        if (!chartArea) return;
        
        // Create gradient from top to bottom of chart
        gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        
        // Set colors based on status
        switch(status) {
            case 'Overflow':
                borderColor = 'rgba(255, 0, 0, 1)';
                pointBackgroundColor = 'rgba(255, 0, 0, 1)';
                gradient.addColorStop(0, 'rgba(255, 0, 0, 0.6)');
                gradient.addColorStop(1, 'rgba(255, 0, 0, 0.0)');
                break;
            case 'High':
                borderColor = 'rgba(255, 153, 0, 1)';
                pointBackgroundColor = 'rgba(255, 153, 0, 1)';
                gradient.addColorStop(0, 'rgba(255, 153, 0, 0.6)');
                gradient.addColorStop(1, 'rgba(255, 153, 0, 0.0)');
                break;
            case 'Medium':
                borderColor = 'rgba(255, 255, 0, 1)';
                pointBackgroundColor = 'rgba(255, 255, 0, 1)';
                gradient.addColorStop(0, 'rgba(255, 255, 0, 0.6)');
                gradient.addColorStop(1, 'rgba(255, 255, 0, 0.0)');
                break;
            case 'Low':
                borderColor = 'rgba(0, 255, 0, 1)';
                pointBackgroundColor = 'rgba(0, 255, 0, 1)';
                gradient.addColorStop(0, 'rgba(0, 255, 0, 0.6)');
                gradient.addColorStop(1, 'rgba(0, 255, 0, 0.0)');
                break;
            case 'Normal':
                borderColor = 'rgba(0, 128, 255, 1)';
                pointBackgroundColor = 'rgba(0, 128, 255, 1)';
                gradient.addColorStop(0, 'rgba(0, 128, 255, 0.6)');
                gradient.addColorStop(1, 'rgba(0, 128, 255, 0.0)');
                break;
            default:
                borderColor = 'rgba(128, 128, 128, 1)';
                pointBackgroundColor = 'rgba(128, 128, 128, 1)';
                gradient.addColorStop(0, 'rgba(128, 128, 128, 0.6)');
                gradient.addColorStop(1, 'rgba(128, 128, 128, 0.0)');
        }
        
        // Update chart styling
        chart.data.datasets[0].borderColor = borderColor;
        chart.data.datasets[0].backgroundColor = gradient;
        chart.data.datasets[0].fill = true;
        chart.data.datasets[0].tension = 0.3;
        chart.data.datasets[0].pointRadius = 3;
        chart.data.datasets[0].pointBackgroundColor = pointBackgroundColor;
        chart.data.datasets[0].pointBorderColor = '#fff';
        chart.data.datasets[0].pointHoverRadius = 5;
        chart.data.datasets[0].pointHoverBackgroundColor = '#fff';
        chart.data.datasets[0].pointHoverBorderColor = borderColor;
    }
// FUNCTION TO UPDATE CHART COLOR BASED ON STATUS //

// FUNCTION TO DETERMINE WATER STATUS AND TEXT COLOR // 
function determineWaterStatus(indicators) {
    if (!indicators.High && !indicators.Medium && !indicators.Low && !indicators.Overflow) {
        return 'Sensor is OFF';
    }
    if (indicators.Overflow) return 'Overflow';
    if (indicators.High) return 'High';
    if (indicators.Medium) return 'Medium';
    if (indicators.Low) return 'Low';
    return 'Normal';
}

function updateStatusColor(status) {
    // First, remove any existing animation classes
    $('#waterStatus, #water-Status').removeClass('pulse-animation normal-status medium-status low-status overflow-status high-status sensor-off');
    
    // Apply appropriate styling based on status
    switch(status) {
        case 'Overflow':
            $('#waterStatus, #water-Status').css('color', '#ff0000').addClass('pulse-animation overflow-status');
            break;
        case 'High':
            $('#waterStatus, #water-Status').css('color', '#ff9900').addClass('pulse-animation high-status');
            break;
        case 'Medium':
            $('#waterStatus, #water-Status').css('color', '#ffff00').addClass('medium-status');
            break;
        case 'Low':
            $('#waterStatus, #water-Status').css('color', '#00ff00').addClass('low-status');
            break;
        case 'Normal':
            $('#waterStatus, #water-Status').css('color', '#00ff00').addClass('normal-status');
            break;
        default:
            $('#waterStatus, #water-Status').css('color', '#gray').addClass('sensor-off');
    }
}
// FUNCTION TO DETERMINE WATER STATUS AND TEXT COLOR // 

});
// WATER LEVEL CHART //

// Toggle switch and automated message sending //
$(document).ready(function() {
    // Function to fetch the current state of the message switch
    function fetchMessageSwitchState() {
        $.ajax({
            url: 'functions/get-message-switch.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateToggleUI(response.value);
                } else {
                    console.error('Error fetching message switch state:', response.message);
                    $('#messageSwitchStatus').text('Error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#messageSwitchStatus').text('Error');
            }
        });
    }

    // Function to update the toggle UI based on the switch value
    function updateToggleUI(switchValue) {
        // Update the checkbox state
        $('#messageSwitchToggle').prop('checked', switchValue == 1);
        
        // Update the status text
        $('#messageSwitchStatus').text(switchValue == 1 ? 'Auto' : 'Manual');	
        $('#messageSwitchStatus').removeClass('text-success text-danger')
            .addClass(switchValue == 1 ? 'text-danger' : 'text-success');
        
        // Show/hide the send message button
        if (switchValue == 0) {
            $('#sendMessageButtonContainer').show();
        } else {
            $('#sendMessageButtonContainer').hide();
        }
    }

    // Handle toggle change
    $('#messageSwitchToggle').on('change', function() {
        const newValue = $(this).is(':checked') ? 1 : 0;
        
        // Show loading state
        $('#messageSwitchStatus').text('Updating...');
        
        // Send the new value to the server
        $.ajax({
            url: 'functions/toggle-message-switch.php',
            method: 'POST',
            data: { value: newValue },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateToggleUI(response.value);
                } else {
                    console.error('Error updating message switch:', response.message);
                    // Revert the UI if update failed
                    fetchMessageSwitchState();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                // Revert the UI if AJAX failed
                fetchMessageSwitchState();
            }
        });
    });

    // Then update the button click handler to use this function
$('#sendMessageBtn').on('click', function() {
    sendAlertMessage();
});

    // Fetch initial state when page loads
    fetchMessageSwitchState();
    setInterval(fetchMessageSwitchState, 10000);
});
// Toggle switch and automated message sending //


 
// SEND SMS FUNCTION MANUAL //
function sendAlertMessage() {
     // Get water status and level from the page
 const waterStatus = $('#water-Status').text();
 const waterLevel = $('#WaterLevel').text().replace(' cm', '');

    return Swal.fire({
        title: 'Send Alert Message',
        html: `Are you sure you want to send alert messages about <b>${waterStatus}</b> water level (${waterLevel}cm) to all registered users?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, send it!'
    }).then((result) => {
        if (result.isConfirmed) {
            return $.ajax({
                url: 'functions/send-message.php',
                method: 'POST',
                data: {
                    status: waterStatus,
                    waterLevel: waterLevel
                },
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Sending alert messages to all users',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Sent!',
                            'Alert messages have been sent successfully.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            'Failed to send alert messages: ' + (response.message || 'Unknown error'),
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'Failed to send alert messages due to a server error.',
                        'error'
                    );
                }
            });
        }
        return false;
    });
}
// SEND SMS FUNCTION MANUAL //

// SEND SMS FUNCTION AUTO //
function autoSendAlertMessage() {
    $.ajax({
        url: 'functions/get-message-switch.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // Only proceed if messaging is in auto mode (value is 1)
            if (response.status === 'success' && response.value === 1) {

                 // Get CURRENT water status and level at the time this executes
                 const waterStatus = $('#water-Status').text();
                 const waterLevel = $('#WaterLevel').text().replace(' cm', '');
                
                // Only send alerts for Medium or High water status
                if (waterStatus === 'Medium' || waterStatus === 'High' || waterStatus === 'Overflow') {
                    // Send alert without confirmation
                    $.ajax({
                        url: 'functions/send-message.php',
                        method: 'POST',
                        data: {
                            status: waterStatus,
                            waterLevel: waterLevel
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                console.log(`Auto alert sent for ${waterStatus} water level (${waterLevel}cm)`);
                            } else {
                                console.error('Failed to send auto alert:', response.message || 'Unknown error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error sending auto alert:', error);
                        }
                    });
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error checking message switch state:', error);
        }
    });
}

$(document).ready(function() {
    setInterval(autoSendAlertMessage, 20000);
});
// SEND SMS FUNCTION AUTO //





// WEATHER CHART //
function updateWeather() {
    $.ajax({
        url: 'functions/weather.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#temperature').text(response.temperature + '°C');
            $('#description').html(`<i class="fas fa-cloud"></i> ${response.description}`);
            $('#humidity').text(response.humidity + '%');
            $('#windSpeed').text(response.windSpeed + ' m/s');
            
            if (response.error) {
                console.error('Weather API error:', response.error);
                return;
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            console.log('Response:', xhr.responseText);
        }
    });
}

$(document).ready(function() {
    updateWeather();
    setInterval(updateWeather,5000);
});
// WEATHER CHART //

// USERS COUNT //
function updateUsersCount() {
    $.ajax({
        url: 'functions/fetch-users-count.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('.users-count').text(response.count);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching users count:', error);
            $('.users-count').text('Error');
        }
    });
}

$(document).ready(function() {
    updateUsersCount();
});
// USERS COUNT //