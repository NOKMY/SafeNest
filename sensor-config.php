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
    <link rel="stylesheet" href="../staticassets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../static/assets/css/feathericon.min.css">
    <link rel="stylesheet" href="../static/assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="../static/assets/css/style.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="javascript-functions/sensor-setting.js"></script>
    <script src="javascript-functions/continues-update.js"></script>


    
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
    .loader {
  width: 50px;
  aspect-ratio: 1;
  display: grid;
  border-radius: 50%;
  background:
    linear-gradient(0deg ,rgb(0 0 0/50%) 30%,#0000 0 70%,rgb(0 0 0/100%) 0) 50%/8% 100%,
    linear-gradient(90deg,rgb(0 0 0/25%) 30%,#0000 0 70%,rgb(0 0 0/75% ) 0) 50%/100% 8%;
  background-repeat: no-repeat;
  animation: l23 1s infinite steps(12);
}
.loader::before,
.loader::after {
   content: "";
   grid-area: 1/1;
   border-radius: 50%;
   background: inherit;
   opacity: 0.915;
   transform: rotate(30deg);
}
.loader::after {
   opacity: 0.83;
   transform: rotate(60deg);
}
@keyframes l23 {
  100% {transform: rotate(1turn)}
}
.loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        border-radius: 10px;
    }

    .login-card {
        position: relative;
    }
    .card-header h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #4299e1;
        }
</style>

                <!-- SIDE BAR -->
                <?php include('components/sidebar.php'); ?>
                <!-- SIDE BAR -->

<div class="page-wrapper mt-3">


<div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sensor Configuration</h4>
                    </div>
                    <div class="card-body">
                    <div class="loader-overlay" style="display: none;">
                        <div class="loader"></div>
                    </div>
                        <form id="sensorConfigForm">
                        <div class="mb-3">
                                <label for="sensorHeight" class="form-label">Sensor Height (cm)</label>
                                <input type="number" class="form-control" id="sensorHeight" name="sensorHeight">
                            </div>
                            <div class="mb-3">
                                <label for="highValue" class="form-label">High Threshold</label>
                                <input type="number" class="form-control" id="highValue" name="highValue">
                            </div>
                            <div class="mb-3">
                                <label for="mediumValue" class="form-label">Medium Threshold</label>
                                <input type="number" class="form-control" id="mediumValue" name="mediumValue">
                            </div>
                            <div class="mb-3">
                                <label for="lowValue" class="form-label">Low Threshold</label>
                                <input type="number" class="form-control" id="lowValue" name="lowValue">
                            </div>
                           
                            <button type="submit" class="btn btn-primary">Save Configuration</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</div>

<?php
include('footer.php');
?>
</body>
</html>