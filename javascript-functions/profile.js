$(document).ready(function() {
    $('.loader-overlay').show();

    // Fetch user data when page loads
    $.ajax({
        url: 'functions/get-user-profile.php',
        type: 'GET',
        success: function(response) {
            const userData = JSON.parse(response);
            $('#username').val(userData.username);
            $('#fname').val(userData.fname);
            $('#middle').val(userData.middle);
            $('#lname').val(userData.lname);
            $('#email').val(userData.email);
            $('#mobile_number').val(userData.mobile_number);
            $('#contact').val(userData.contact);
            $('#address').val(userData.address);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching user data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load profile data'
            });
        },
        complete: function() {
            $('.loader-overlay').hide();
        }
    });

    // Handle form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.loader-overlay').show();
        
        $.ajax({
            url: 'functions/update-profile.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const result = JSON.parse(response);
                if(result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully!'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update profile'
                    });
                }
                $('.loader-overlay').hide(); // Ensure loader is hidden
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating profile'
                });
                $('.loader-overlay').hide(); // Ensure loader is hidden
            },
            complete: function() {
                $('.loader-overlay').hide();
            }
        });
    });


    $('#profileImageInput').change(function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const formData = new FormData();
            formData.append('profile_photo', file);
    
            // Show loader
            $('.loader-overlay').show();
    
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#profileImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
    
            // Upload image
            $.ajax({
                url: 'functions/update-profile-photo.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Profile photo updated successfully!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to update profile photo'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating profile photo'
                    });
                },
                complete: function() {
                    $('.loader-overlay').hide();
                }
            });
        }
    });
});