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
    <script src="javascript-functions/users.js"></script>
    <script src="javascript-functions/continues-update.js"></script>

    
</head>
<body class="">

  <!-- LOADER -->
  <div class="loading-overlay">
    <div class="loader"></div>
</div>

<style>
      .profile-form-container {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
            padding: 40px;
            margin: 20px 0;
        }

        .profile-header {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(66, 153, 225, 0.1);
        }

        .profile-title {
            font-size: 1.75rem;
            color: #2d3748;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-title i {
            color: #4299e1;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.875rem;
            margin-bottom: 10px;
            display: block;
            letter-spacing: 0.3px;
        }

        .input-group {
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #ffffff;
            border: 2px solid #e2e8f0;
        }

        .input-group:focus-within {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }

        .input-group-text {
            background-color: transparent;
            border: none;
            color: #4299e1;
            padding-left: 15px;
        }

        .form-control {
            border: none;
            padding: 12px 15px;
            font-size: 0.95rem;
            background: transparent;
            color: #2d3748;
        }

        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        .form-group {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger animation delay for form groups */
.form-group:nth-child(1) { animation-delay: 0.1s; }
.form-group:nth-child(2) { animation-delay: 0.2s; }
.form-group:nth-child(3) { animation-delay: 0.3s; }
.form-group:nth-child(4) { animation-delay: 0.4s; }
    .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
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
.title-header {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #4299e1;
        }
</style>
  <!-- LOADER -->

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
    
<div class="content container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="title-header">System Users</h4> 
                        <div class="d-flex justify-content-between">
                             <a href="roles.php" class="btn btn-sm btn-primary">User Roles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
        <div class="card profile-form-container">
        <div class="card-body">
            <form id="registeruser">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="firstName" class="form-label">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="middleName" class="form-label">Middle Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="middleName" name="middleName">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastName" class="form-label">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="userRole" class="form-label">User Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-users"></i></span>
                                <select class="form-select" id="userRole" name="userRole" required>
                                    <option value="">Select Role</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
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
    