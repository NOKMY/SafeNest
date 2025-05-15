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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript-functions/sweetalert.min.js"></script>
    <script src="javascript-functions/profile.js"></script>
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

.btn-update-profile {
    background: linear-gradient(135deg, #4299e1, #667eea);
    padding: 12px 35px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(66, 153, 225, 0.2);
}

.btn-update-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(66, 153, 225, 0.3);
}

.btn-update-profile i {
    margin-right: 8px;
}

/* Animation for form elements */
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
</style>

                <!-- SIDE BAR -->
                <?php include('components/sidebar.php'); ?>
                <!-- SIDE BAR -->

<div class="page-wrapper mt-3">

<div class="content container-fluid">
    <div class="row">
    <div class="loader-overlay" style="display: none;">
                        <div class="loader"></div>
                    </div>
        <style>
           .profile-image-container {
    margin: 20px 0;
    border-radius: 50%;
}

.profile-image {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.profile-image-container:hover .profile-image {
    transform: scale(1.02);
}

.change-profile-text {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 10px;
    font-size: 14px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.3s;
    border-bottom-left-radius: 90px;
    border-bottom-right-radius: 90px;
}

.profile-image-container:hover .change-profile-text {
    opacity: 1;
}
        </style>
  <div class="col-sm-12 text-center mb-4">

            <!-- profile image -->
  <?php
require_once 'functions/get-user-avatar.php';
$userAvatar = getUserAvatar($_SESSION['user_id']);
?>
<div class="profile-image-container position-relative d-inline-block">
    <img id="profileImage" src="<?php echo htmlspecialchars($userAvatar); ?>" 
         class="rounded-circle profile-image" 
         alt="Profile Picture">
    <label for="profileImageInput" class="change-profile-text">
        Change Profile
    </label>
    <input type="file" class="d-none" id="profileImageInput" accept="image/*">
</div>
    <h1 class="mt-3">My Profile</h1>
</div>
            <!-- profile image -->

<script>
    $('#profileImageInput').change(function(e) {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#profileImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

        <div class="col-sm-12">
        
        <div class="profile-form-container">
    <div class="profile-header">
        <h4 class="profile-title">
            <i class="fe fe-user"></i>
            <span>Personal Information</span>
        </h4>
    </div>
    <form method="POST" enctype="multipart/form-data" id="profileForm">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
                    </div>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your first name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                        <input type="text" class="form-control" id="middle" name="middle" placeholder="Enter your middle name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your last name">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fe fe-mail"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="mobile_number">Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fe fe-phone"></i></span>
                        <input type="tel" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter your mobile number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact">Alternative Contact</label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                        <input type="tel" class="form-control" id="contact" name="contact" placeholder="Enter alternative contact">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Complete Address</label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your complete address"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fe fe-save"></i>
                Save Changes
            </button>
        </div>
    </form>
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

    