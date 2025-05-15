<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Barangay</title>
    <link rel="icon" type="image/png" href="../static/assets/img/weather.png">	
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="../static/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../static/assets/css/feathericon.min.css">
    <link rel="stylesheet" href="../static/assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="../static/assets/css/style.css">
    <link rel="stylesheet" href="../static/assets/css/dashboard.css">
    <link rel="stylesheet" href="../static/assets/css/status-animation.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="javascript-functions/dashboard.js"></script>


    
</head>
<body class="">
<div class="main-wrapper">

                <!-- HEADER -->
                <?php include('components/header.php'); ?>
                <!-- HEADER -->

  <style>
    @media print {
        @page {
            size: landscape;
        }
        body * {
            visibility: hidden;
        }
        canvas {
            visibility: visible;
            position: absolute;
            top: 0;http://localhost/barangay/index.php
            left: 0;
            width: 100vw;
            height: 100vh;
        }
    }
    
    
</style>

                <!-- SIDE BAR -->
<?php include('components/sidebar.php'); ?>
                <!-- SIDE BAR -->

<div class="page-wrapper mt-3">

<script src="https://www.gstatic.com/firebasejs/9.9.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.9.3/firebase-database.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="content container-fluid">
    <div class="row">
    <div class="col-xl-4 col-sm-4 col-12">
            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                <div class="card-body dash-card">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fi fi-rr-cloud-sun-rain"></i>
                        </span>
                        <div class="dash-count">
                            <a href="" class="count-title">Water Status</a>
                            <a href="" class="count "> <span id="waterStatus">Detecting...</span></a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4 col-12">
            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                <div class="card-body dash-card">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fe fe-users"></i>
                        </span>
                        <div class="dash-count">
                            <a href="#" class="count-title">Users</a>
                            <a href="#" class="count users-count">Counting...</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-4 col-12">
            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                <div class="card-body dash-card">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon bg-primary">
                            <i class="fe fe-clock"></i>
                        </span>
                        <div class="dash-count p-1">
    <a href="#" class="count-title">Time</a>
    <span id="current-time" class="count users-count" style="font-size: 18px;">Detecting..</span>
</div>
                        
                    </div>
                </div>
            </div>

    </div>
    <div class="row justify-content-center">
    <!-- Chart Card -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between p-1 align-items-center">
                    <h4></h4>
                    <div class="">
                        <button onclick="printChart()" class="btn btn-success">Print Chart</button>
                    </div>  
                    <!-- TEST SMS MESSAGE -->
                    <!-- <div class="mt-3">
    <button id="testMediumAlert" class="btn btn-warning btn-sm">Test Medium Alert</button>
    <button id="testHighAlert" class="btn btn-danger btn-sm">Test High Alert</button>
    <button id="testOverflowAlert" class="btn btn-dark btn-sm">Test Overflow Alert</button>
</div>          -->
<!-- TEST SMS MESSAGE -->             
                </div>
            </div>
            <div class="card-body">
                <style>
                    #waterLevelChart {
    width: 100% !important;
    height: 300px !important; /* Adjust as needed */
}
                </style>
                <canvas id="waterLevelChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Water Info Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="water-info-container">
                    <div class="water-title">
                        <div class="widget">Water Level</div>
                        <div class="icon-value-container">
                            <div class="water-icon">
                                <img src="../static/assets/img/.gif" alt="Water Image">
                            </div>
                            <div class="water-value">
                                <span id="WaterLevel">0 cm</span>
                            </div>
                        </div>
                    </div>
                    <div class="water-stats">
    <div class="stat-item stat-item-tooltip" data-tooltip="Shows the current sensor connection status">
        <span class="stat-label"><i class="fas fa-microchip"></i></span>
        <span class="stat-value status-active" id="sensorStatus">Detecting..</span>
    </div>
    <div class="stat-item stat-item-tooltip" data-tooltip="Shows the height of the mounted sensor">
        <span class="stat-label"><i class="fas fa-arrows-alt-v"></i></span>
        <span class="stat-value" id="sensorHeight">Detecting..</span>
    </div>
    <div class="stat-item stat-item-tooltip" data-tooltip="Shows the current water level status">
        <span class="stat-label"><i class="fas fa-water"></i></span>
        <span class="stat-value" id="water-Status">Detecting..</span>
    </div>
</div>
                </div>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === '1'): ?>
                <div class="messaging-control mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="stat-label"><i class="fas fa-bell"></i> SMS Alerts:</span>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="messageSwitchToggle">
            <span id="messageSwitchStatus" class="ms-2 fw-bold"></span>
        </div>
    </div>
    <div id="sendMessageButtonContainer" class="mt-2" style="display: none;">
        <button id="sendMessageBtn" class="btn btn-primary btn-sm w-100">
            <i class="fas fa-paper-plane"></i> Send Alert Message
        </button>
    </div>
</div>
<?php endif; ?>
            </div>
          
        </div>


        <div class="card">
            <div class="card-body">
                <div class="water-info-container">
                    <div class="water-title">
                        <div class="weather-widget">Zamboanga City</div>
                        <div class="icon-value-container">
                            <div class="weather-icon">
                                <img src="../static/assets/img/weather.gif" alt="Water Image">
                            </div>
                            <div class="water-value">
                                <span id="temperature" class="water-value">Detecting..</span>
                            </div>
                        </div>
                    </div>
                    <div class="weather-info">
                    <p class="weather-data">
                    <span id="description" class="description"><i class="fas fa-cloud"></i>  Detecting..</span>
            </p>
            <p class="weather-data">
                <span class="stat-label">Humidity:</span>
                <span id="humidity" class="stat-value">Detecting..</span>
            </p>
            <p class="weather-data">
                <span class="stat-label">Wind Speed:</span>
                <span id="windSpeed" class="stat-value">Detecting..</span>
            </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <!-- Water Info Card -->
      </div>

      <!-- Past 1 Hour Reading -->
      <div class="row mt-4 justify-content-center"></div>
      <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between p-1 align-items-center">
                <h4 style="font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #4299e1;">Real-Time Water Level Data (Past 1 Hour)</h4>
                </div>
            </div>
            <div class="card-body">
                <canvas id="pastHourChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
    <div class="card">
    <div class="card-header">
        <div style="  font-size: clamp(14px, 2vw, 16px);
  font-weight: 700;
  color: #333;">Average Reading</div>
    </div>
    <div class="card-body">
        <div class="text-center">
            <h3 id="averageReading">-- cm</h3>
            <small class="text-muted">Average Reading</small>
        </div>
    </div>
</div>
</div>
    <!-- Past 1 Hour Reading -->

        </div>
</div>

    </div>
</div>
    
</div>
<script src="javascript-functions/past-hour-chart.js"></script>
<script src="../static/assets/js/script.js"></script>
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
</body>
</html>