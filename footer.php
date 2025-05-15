<script>
    new DataTable('#data');
    new DataTable('#printables', {
        layout: {
            topStart: {
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        }
    });

    function printChart() {
        // Ensure charts are rendered before printing
        const chartsRendered = new Promise((resolve) => {
            const checkChartsRendered = setInterval(() => {
                if (window.pastHourChartRendered && window.waterLevelChartRendered) {
                    clearInterval(checkChartsRendered);
                    resolve();
                }
            }, 100);
        });

        chartsRendered.then(() => {
            window.print();
        }).catch((error) => {
            console.error('Error rendering charts:', error);
        });
    }
</script>




<script>
    // Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyBLEljKRdr3TBOz4WKtoD-wr6U3SNQq9jI",
        authDomain: "water-level-cf938.firebaseapp.com",
        databaseURL: "https://water-level-cf938-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "water-level-cf938",
        storageBucket: "water-level-cf938.appspot.com",
        messagingSenderId: "99109659503",
        appId: "1:99109659503:web:74c87bbb7382f483163e9a"
    };


   

    $(document).ready(function() {
    // Trigger on form submit
    $("#updateButton").click(function(event) {
        event.preventDefault();

        // Get values from form fields and convert to integers
        const sensorHeight = parseInt($("input[name='sensor_height']").val(), 10);
        const lowThreshold = parseInt($("input[name='low_threshold']").val(), 10);
        const mediumThreshold = parseInt($("input[name='medium_threshold']").val(), 10);
        const highThreshold = parseInt($("input[name='high_threshold']").val(), 10);

        // Check if any value is NaN
        if (isNaN(sensorHeight) || isNaN(lowThreshold) || isNaN(mediumThreshold) || isNaN(highThreshold)) {
            alert("Please enter valid numeric values.");
            return;
        }

        // Data to update on Firebase
        const data = {
            SensorHeight: sensorHeight,
            Low: lowThreshold,
            Medium: mediumThreshold,
            High: highThreshold,
        };

        // Update Firebase with the form data
        database.ref('/SensorConfig').update(data)
            .then(function() {
                alert("Data updated successfully in Firebase!");
                // Optionally, update UI or reset form
            })
            .catch(function(error) {
                alert("Error updating Firebase: " + error.message);
            });

        // Allow form submission to Django
        $("form").submit();
    });
});

$(document).ready(function() {
        // Fetch data from HistoricalRecords table on page load
        firebase.database().ref('/HistoricalRecords').once('value')
            .then(function(snapshot) {
                var data = snapshot.val();
                // Log the raw data
                console.log("Raw data from Firebase:", data);
                // Clean data structure
                var cleanedData = cleanDataStructure(data);
                // Display cleaned data as JSON
                console.log("Cleaned data:", JSON.stringify(cleanedData, null, 2));
            })
            .catch(function(error) {
                console.error("Error fetching data: ", error);
            });
    });
    // Function to update the graph
    function updateChart(waterLevel) {
        const currentTime = new Date().toLocaleTimeString();

        waterLevelChart.data.labels.push(currentTime);
        waterLevelChart.data.datasets[0].data.push(waterLevel);

        // Limit chart to last 100 data points
        if (waterLevelChart.data.labels.length > 100) {
            waterLevelChart.data.labels.shift();
            waterLevelChart.data.datasets[0].data.shift();
        }

        waterLevelChart.update();
    }

    // Function to update water level status
function updateWaterLevelStatus(status) {
    const statusElement = document.getElementById("waterLevelStatus");

    if (status === "high") {
        statusElement.textContent = "HIGH";
        statusElement.style.color = "white";
        statusElement.className = 'badge bg-danger';
        console.log("Water level is high");
    } else if (status === "medium") {
        statusElement.textContent = "MEDIUM";
        statusElement.style.color = "white";
        statusElement.className = 'badge bg-warning';
        console.log("Water level is medium");
    } else if (status === "low") {
        statusElement.textContent = "LOW";
        statusElement.style.color = "white";
        statusElement.className = 'badge bg-success';
        console.log("Water level is low");
    } else {
        statusElement.textContent = "NORMAL";
        statusElement.style.color = "white";
        statusElement.className = 'badge bg-primary';
    }
}
    // Fetch data from Firebase to update the graph and status
    waterLevelRef.on('value', (snapshot) => {
        const distance = parseFloat(snapshot.val());
        console.log("Fetched water level distance:", distance);

        if (!isNaN(distance)) {
            updateChart(distance);  // Update the graph
        }
    });

    waterIndicatorRef.on('value', (snapshot) => {
        const indicator = snapshot.val();

        if (indicator.High) {
            updateWaterLevelStatus("high");
            console.log("Water level is high");
        } else if (indicator.Medium) {
            updateWaterLevelStatus("medium");
        } else if (indicator.Low) {
            updateWaterLevelStatus("low");
        } else {
            updateWaterLevelStatus("normal");
        }
    });
</script>


<script>
    // Firebase reference for historical readings
    var readingsRef = database.ref('/HistoricalReadings');
    var query = readingsRef.orderByChild('Timestamp').limitToLast(20);

    // Create chart for historical readings
    var ctxHistorical = document.getElementById('historicalChart').getContext('2d');
    var historicalChart = new Chart(ctxHistorical, {
        type: 'line',
        data: {
            labels: [], // Placeholder for time labels of historical data
            datasets: [{
                label: 'Historical Water Levels (cm)',
                data: [], // Placeholder for historical water level values
                borderColor: 'rgba(0, 0, 255, 1)', // Blue color for historical data
                borderWidth: 2,
                fill: true
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
                    }
                }
            }
        }
    });

    // Function to update the historical chart with new data
    function updateHistoricalChart(waterLevel, timestamp) {
        historicalChart.data.labels.push(timestamp);
        historicalChart.data.datasets[0].data.push(waterLevel);

        // Limit chart to the last 100 historical readings
        if (historicalChart.data.labels.length > 100) {
            historicalChart.data.labels.shift();
            historicalChart.data.datasets[0].data.shift();
        }

        historicalChart.update();
    }

    // Fetch the last 20 historical readings from Firebase and update the chart
    query.on('value', (snapshot) => {
        if (snapshot.exists()) {
            console.log("Historical readings snapshot:", snapshot.val());

            // Clear existing data before adding the latest 20 readings
            historicalChart.data.labels.length = 0;
            historicalChart.data.datasets[0].data.length = 0;

            snapshot.forEach((childSnapshot) => {
                const data = childSnapshot.val();
                const waterLevel = parseFloat(data.Distance); // Convert distance to float
                const timestamp = data.Timestamp;

                if (!isNaN(waterLevel)) {
                    historicalChart.data.labels.push(timestamp);
                    historicalChart.data.datasets[0].data.push(waterLevel);
                }
            });

            historicalChart.update(); // Update the chart with the latest data
        } else {
            console.log("No historical readings found.");
        }
    });
</script>

<script src="../static/assets/js/jquery-3.6.0.min.js"></script>
<script src="../static/assets/js/bootstrap.bundle.min.js"></script>
<script src="../static/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="../static/assets/js/script.js"></script>