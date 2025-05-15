<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"></li>
                <li class="">
                    <a href="index.php"><i class="fe fe-home" style="color: #4299e1;"></i> <span>Dashboard</span></a>
                </li>
                <li class="">
                    <a href="history.php"><i class="fi fi-rr-calendar-clock" style="color: #4299e1;"></i> <span>Historical Records</span></a>
                </li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === '1'): ?>
                <li class="">
                    <a href="users.php"><i class="fi fi-rr-member-list" style="color: #4299e1;"></i> <span>Users</span></a>
                </li>
                <li class="">
                    <a href="sensor-config.php"><i class="fi fi-rr-settings" style="color: #4299e1;"></i> <span>Sensor Setting</span></a>
                </li>
                <?php endif; ?>    
                <li class="">
                    <a href="aboutus.php"><i class="fi fi-rr-info" style="color: #4299e1;"></i> <span>About us</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .sidebar-menu i {
        color: #4299e1;
    }
</style>