function updateLocalStorage(labels, data) {
    console.log('Updating local storage with labels and data:', labels, data);
    localStorage.setItem('waterLevelLabels', JSON.stringify(labels));
    localStorage.setItem('waterLevelData', JSON.stringify(data));
}

function updateChartData() {
    $.ajax({
        url: 'functions/firebase-api.php',
        method: 'GET',
        success: function(response) {
            console.log('Received response from server:', response);
            let waterLevel = parseFloat(response.waterLevel.replace('"', '').replace('cm', '').trim());
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
                    
                    sendSMSAlert(status, waterLevel);
                    
                    // Update localStorage with current status and time
                    localStorage.setItem('lastSMSStatus', status);
                    localStorage.setItem('lastSMSTime', currentTime.toString());
                }
            }
            // SEND SMS ALERT BASED ON STATUS //

            // Update localStorage with current chart data
            let currentDateTime = new Date().toLocaleString('en-US', {
                month: 'numeric',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            let labels = JSON.parse(localStorage.getItem('waterLevelLabels')) || [];
            let data = JSON.parse(localStorage.getItem('waterLevelData')) || [];

            labels.push(currentDateTime);
            data.push(waterLevel);

            // keep only 20 points of plot data
            if (labels.length > 20) {
                labels.shift();
                data.shift();
            }

            updateLocalStorage(labels, data);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data from server:', error);
        }
    });
}

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


// Start the data update interval
setInterval(updateChartData, 5000);
updateChartData();