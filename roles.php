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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="../staticassets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../static/assets/css/feathericon.min.css">
    <link rel="stylesheet" href="../static/assets/plugins/morris/morris.css">
    <link rel="stylesheet" href="../static/assets/css/style.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
 

    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <script src="https://cdn.datatables.net/2.0.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
  
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="javascript-functions/users.js"></script>
    <script src="javascript-functions/continues-update.js"></script>

    
</head>
<body class="">
    
  <!-- LOADER -->
<div class="loading-overlay">
    <div class="loader"></div>
</div>

<style>
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
                    <h4 class="title-header">System User Roles</h4> 
                        <div class="d-flex justify-content-between">
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#userRoleModal">
        Add User Role
    </button>
</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data" class="table table-striped table-hover" style="width:100%">
                                    <thead>
                                    <tr>
               
                <th><i class="fa fa-user" aria-hidden="true" style="color: #4299e1;"></i> Roles</th>
                <th><i class="fa fa-cogs" aria-hidden="true" style="color: #4299e1;"></i> Action</th>
            </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <a href="" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>               
                                    </tbody>
                                                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- ADD ROLES Modal -->
<div class="modal fade" id="userRoleModal" tabindex="-1" aria-labelledby="userRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userRoleModalLabel">Add User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userRoleForm">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="roleName" name="roleName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="roleType" id="adminRole" value="admin">
                            <label class="form-check-label" for="adminRole">
                                Admin
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="roleType" id="nonAdminRole" value="non-admin" checked>
                            <label class="form-check-label" for="nonAdminRole">
                                Non-Admin
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="userRoleForm" class="btn btn-primary">Save Role</button>
            </div>
        </div>
    </div>
</div>
<!-- ADD ROLES Modal -->

<!-- EDIT ROLE -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_role_id" name="role_id">
                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="edit_role_name" name="role_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- EDIT ROLE -->

    </div>
    
</div>


</div>
<script src="../static/assets/js/script.js"></script>

</body>
</html>
    