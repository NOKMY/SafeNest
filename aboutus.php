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

    <script src="javascript-functions/dashboard.js"></script>
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
    .card-body {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
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

<div class="content container-fluid">
    <div class="row">
        
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>About us</h4>
                </div>
                <div class="card-body">
                    At Barangay Tumaga SafeNest Group, we are a passionate team of students committed to ensuring public safety within Barangay Tumaga. With unwavering dedication, we collaborate to implement effective measures and promote well-being. Our mission extends beyond the classroom; it’s a calling to protect and serve. From emergency response planning to community outreach, we strive to create a secure environment where everyone feels protected. Join us in our collective effort to make Barangay Tumaga a safer place for all.sss
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
    