$(document).ready(function() {
    // Show and Hide loader function
    function showLoader() {
        $('.loader-overlay').show();
    }
    function hideLoader() {
        $('.loader-overlay').hide();
    }


    // Fetch initial sensor configuration
    function fetchSensorConfig() {
        showLoader(); // Show loader before fetch
        
        $.ajax({
            url: 'functions/sensor-config.php',
            method: 'GET',
            success: function(response) {
                if (response.SensorConfig) {
                    const config = response.SensorConfig;
                    // Populate form fields
                    $('#highValue').val(config.High);
                    $('#mediumValue').val(config.Medium);
                    $('#lowValue').val(config.Low);
                    $('#sensorHeight').val(config.SensorHeight);
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load sensor configuration'
                });
                console.error('Error fetching sensor config:', error);
            },
            complete: function() {
                hideLoader();
            }
        });
    }

    // Load configuration when page loads
    fetchSensorConfig();

    // Handle form submission
    $('#sensorConfigForm').on('submit', function(e) {
        e.preventDefault();
        showLoader();
        
        const sensorConfig = {
            High: parseFloat($('#highValue').val()),
            Medium: parseFloat($('#mediumValue').val()),
            Low: parseFloat($('#lowValue').val()),
            SensorHeight: parseFloat($('#sensorHeight').val())
        };
    
        // Send update request to server
        $.ajax({
            url: 'functions/calibrate-sensor.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(sensorConfig),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Settings Saved',
                    text: 'Sensor configuration has been updated successfully!'
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update sensor configuration'
                });
                console.error('Error:', error);
            },
            complete: function() {
                hideLoader();
            }
        });
    });
});