// ADD ROLES //
$(document).ready(function() {
    $('#userRoleForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.loading-overlay').css('display', 'flex');
        
        $.ajax({
            type: 'POST',
            url: 'functions/add-roles.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('.loading-overlay').hide();
                
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#userRoleModal').modal('hide');
                        $('#userRoleForm')[0].reset();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function() {
                $('.loading-overlay').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error occurred while saving'
                });
            }
        });
    });
});
// ADD ROLES //

// FETCH ROLES DISPLAY TO THE TABLE //
$(document).ready(function() {
    loadRoles();
});
function loadRoles() {
    $.ajax({
        url: 'functions/fetch-roles.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var tbody = $('#data tbody');
            tbody.empty();
            
            $.each(data, function(index, role) {
                var row = `
                    <tr>
                        <td>${role.role_name}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editRole(${role.role_id})" 
                                    data-bs-toggle="modal" data-bs-target="#editRoleModal">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteRole(${role.role_id})">Delete</button>
                        </td>
                    </tr>`;
                tbody.append(row);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching roles:', error);
        }
    });
}

// Edit Role Function
function editRole(roleId) {
    $.ajax({
        url: 'functions/get-role.php',
        type: 'POST',
        data: {role_id: roleId},
        dataType: 'json',
        success: function(response) {
            $('#edit_role_id').val(response.role_id);
            $('#edit_role_name').val(response.role_name);
        }
    });
}



// Delete Role Function
function deleteRole(roleId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('.loading-overlay').css('display', 'flex');
            
            $.ajax({
                type: 'POST',
                url: 'functions/delete-role.php',
                data: {role_id: roleId},
                dataType: 'json',
                success: function(response) {
                    $('.loading-overlay').hide();
                    if(response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
}

$(document).ready(function() {
    $(document).on('submit', '#editRoleForm', function(e) {
        e.preventDefault();
        $('.loading-overlay').css('display', 'flex');
        $.ajax({
            type: 'POST',
            url: 'functions/update-role.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('.loading-overlay').hide();
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#editRoleModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Update failed'
                    });
                }
            },
            error: function(xhr, status, error) {
                $('.loading-overlay').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            }
        });
    });
});
// FETCH ROLES DISPLAY TO THE TABLE //


// FETCH ROLE FOR ADD USER ROLE OPTIONS //
$(document).ready(function() {
    loadRoleOptions();
});
function loadRoleOptions() {
    $.ajax({
        url: 'functions/role-options.php',
        type: 'GET',
        dataType: 'json',
        success: function(roles) {
            var select = $('#userRole');
            select.empty();
            select.append('<option value="">Select Role</option>');
            
            $.each(roles, function(i, role) {
                select.append($('<option>', {
                    value: role.role_id,
                    text: role.role_name
                }));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error loading roles:', error);
        }
    });
}
// FETCH ROLE FOR ADD USER ROLE OPTIONS //


// ADD USERS //
$(document).ready(function() {
    $('#registeruser').on('submit', function(e) {
        e.preventDefault();
        
        // Password validation
        if($('#password').val() !== $('#confirmPassword').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Passwords do not match!'
            });
            return false;
        }
        
        $('.loading-overlay').css('display', 'flex');
        
        $.ajax({
            type: 'POST',
            url: 'functions/add-users.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('.loading-overlay').hide();
                
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#registeruser')[0].reset();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function() {
                $('.loading-overlay').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            }
        });
    });
});
// ADD USERS //