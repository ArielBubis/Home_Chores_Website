$(document).ready(function() {
    $('#signUpForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        $('#logInLink').attr('hidden', 'hidden').hide();
        $('#signUpError').attr('hidden', 'hidden').hide();

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#logInLink').attr('hidden', 'hidden').hide();
                // console.log("scriptjs", response);
                if(response.success == '0') {
                    console.log(response.message);
                    $('#signUpError').text(response.message).removeAttr('hidden').show();
                    if(response.message == 'Email already exists.') {
                    $('#logInLink').removeAttr('hidden').show();
                    }

                } else {
                    $('#signUpError').attr('hidden', 'hidden').hide();
                    $('#logInLink').attr('hidden', 'hidden').hide();
                    alert('Signup successful!');
                    window.location.href = 'loginscreen.php';
                }
            }, 
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log(xhr.responseText, error);
            }
            
        });
    });

    $('#signInForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var loginFormData = $(this).serialize(); // Serialize form data
        
        $.ajax({
            url: 'login_db.php',
            type: 'POST',
            data: loginFormData,
            dataType: 'json',
            success: function(response) {
                if(response.success == '0') {
                    // Login failed, display error message
                    console.log(response.message);
                    alert('Login failed. Please check your credentials and try again.');
                    $('#loginError').text(response.message).removeAttr('hidden').show();
                } else {
                    alert('Login successful!');
                    // Login successful
                    window.location.href = 'index.php';

                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log(xhr.responseText, error);
            }
        });
    });
    // $('#loginForm').submit(function(e) {
    //     e.preventDefault(); // Prevent default form submission
    //     var loginFormData = $(this).serialize(); // Serialize form data

    //     $.ajax({
    //         url: 'login_db.php',
    //         type: 'POST',
    //         data: loginFormData,
    //         dataType: 'json',
    //         success: function(response) {
    //             if(response.success == 0) {
    //                 window.location.href = 'index.php';
    //             }
    //             else {
    //                 alert(response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("AJAX error:", status, error);
    //             console.log(xhr.responseText, error);
    //         }

    //     });
    // });

    // $('#homepage').on('click', function() {
    //     $.ajax({
    //         url: "index.php", 
    //         type: "POST",
    //         success: function (response) {
    //             if(response.success == true) {
    //                 window.location('index.php');
    //                 exit;
    //             }
    //             else {
    //                 alert('You must login first');
    //             }
    //         }
    //     })
    // });

});