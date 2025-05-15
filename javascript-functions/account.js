// CREATE ACCOUNT //
$(document).ready(function() {
    $('#signup').on('submit', function(e) {
        e.preventDefault();

        if($('#password').val() !== $('#confirm_password').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!'
            });
            return;
        }

        // Show loader
        $('.loader-overlay').fadeIn(200);

        $.ajax({
            type: 'POST',
            url: 'barangay/functions/create-account.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Hide loader
                $('.loader-overlay').fadeOut(200);
                
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'login';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                // Hide loader
                $('.loader-overlay').fadeOut(200);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred: ' + error
                });
            }
        });
    });
});
// CREATE ACCOUNT //

// TYPING EFFECT //
$(document).ready(function() {
    var typed = new Typed('.typed-title', {
        strings: [
            'Create your account',
            'Join SafeNest today',
            'Sign up now'
        ],
        typeSpeed: 50,
        backSpeed: 30,
        backDelay: 2000,
        loop: true,
        showCursor: true,
        cursorChar: '|',
        fadeOut: true,
        fadeOutClass: 'typed-fade-out',
        fadeOutDelay: 500
    });
});
// TYPING EFFECT //

// TOGGLE PASSWORD //
$(document).ready(function() {
    $('#showPassword').click(function() {
        if($(this).is(':checked')) {
            $('#password, #confirm_password').attr('type', 'text');
        } else {
            $('#password, #confirm_password').attr('type', 'password');
        }
    });
});
// TOGGLE PASSWORD //

// LOGIN //
$(document).ready(function() {
    // Show/Hide Password
    $('#showPassword').on('change', function() {
        var passwordField = $('#password');
        passwordField.attr('type', this.checked ? 'text' : 'password');
    });

    // Login Form Submit
    $('#login').on('submit', function(e) {
        e.preventDefault();
        $('.loading-overlay').css('display', 'flex');
        
        $.ajax({
            type: 'POST',
            url: 'barangay/functions/login.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('.loading-overlay').hide();
                
                if(response.status === 'success') {
                    window.location.href = 'barangay/index.php';
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
// LOGIN //