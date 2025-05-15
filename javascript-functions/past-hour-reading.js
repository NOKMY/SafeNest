$(document).ready(function () {
    const ctx = document.getElementById('pastreadingChart').getContext('2d');

    // Create gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(0, 123, 255, 0.5)');
    gradient.addColorStop(1, 'rgba(0, 123, 255, 0.05)');

    // Initialize empty chart
    const past24HoursChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Water Level (cm)',
                data: [],
                borderColor: 'rgba(0, 123, 255, 1)',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: { display: true, text: 'Time' },
                    ticks: { maxTicksLimit: 24 }
                },
                y: {
                    title: { display: true, text: 'Water Level (cm)' }
                }
            }
        }
    });

    function fetchHistoricalData() {
        $('.chart-loading-overlay').css('display', 'flex');
        $('#24houraverageReading').text('Loading...');

        $.ajax({
            url: 'functions/past-hour-reading.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success' && response.data.length > 0) {
                    const timeLabels = response.data.map(item => item.time);
                    const waterLevels = response.data.map(item => item.avgWaterLevel);

                    past24HoursChart.data.labels = timeLabels;
                    past24HoursChart.data.datasets[0].data = waterLevels;
                    past24HoursChart.update();

                    $('.chart-loading-overlay').css('display', 'none');
                    $('#24houraverageReading').text(
                        `${(waterLevels.reduce((a, b) => a + b, 0) / waterLevels.length).toFixed(2)} cm`
                    );
                } else {
                    $('#24houraverageReading').text('No data');
                    $('.chart-loading-overlay').css('display', 'none');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
                $('.chart-loading-overlay').css('display', 'none');
            }
        });
    }

    fetchHistoricalData();
    setInterval(fetchHistoricalData, 20000);
});
