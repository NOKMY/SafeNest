<?php
require_once __DIR__ . '/../functions/get-user-avatar.php';
$userAvatar = getUserAvatar($_SESSION['user_id']);
$defaultAvatar = '../static/assets/img/blank-profile-picture-973460_640.png';
$avatarSrc = !empty($userAvatar) ? htmlspecialchars($userAvatar) : $defaultAvatar;
?>
<div class="header">
        
<div class="header-left mt-3">
        <a href="" class="logo text-light"><h3 class="poppins-text"><i class="fi fi-rr-cloud-sun-rain"></i> SafeNest</h3></a>
        <a href="" class="logo logo-small text-light"><h3 class="poppins-text"><i class="fi fi-rr-cloud-sun-rain"></i> SafeNest</h3></a>
    </div>

    <a href="javascript:void(0);" id="toggle_btn"><i class="fe fe-text-align-left text-light d-none"></i></a>
    <a class="mobile_btn mt-3 " id="mobile_btn">
        <i class="fa fa-bars text-light  "></i>
    </a>
    

    <ul class="nav user-menu">
        
    <li class="nav-item dropdown has-arrow">
        <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
        <span class="user-img">
    <img class="rounded-circle mt-2" src="<?php echo $avatarSrc; ?>" height="31" width="31" alt="Profile">
</span>
        </a>
        <div class="dropdown-menu">
            <div class="user-header">
            <div class="avatar avatar-sm">
    <img src="<?php echo $avatarSrc; ?>" alt="User Image" class="avatar-img rounded-circle">
</div>
            <div class="user-text">
                <h6></h6>
                <p class="text-muted mb-0"></p>
            </div>
        </div>
        <a class="dropdown-item" href="profile.php">My Profile</a>
            <a class="dropdown-item" href="functions/logout.php">Logout</a>
            </div>
    </li>

    </ul>

    </div>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    .poppins-text {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #fff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, text-shadow 0.3s;
    }

    .poppins-text:hover {
        transform: scale(1.1);
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .header-left a.logo:hover {
        transform: scale(1.1);
    }
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>