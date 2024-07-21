$(document).ready(function() {
    $('#signUpForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        $('#logInLink').attr('hidden', 'hidden').hide();
        $('#signUpError').attr('hidden', 'hidden').hide();

        $.ajax({
            url: 'API/add_user.php',
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
                    // alert('Signup successful!');
                    window.location.href = 'loginscreen.php?sign_up=true';
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
            url: 'API/login_db.php',
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

});


$(document).ready(function() {
    // Check if the logged_out parameter is set in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('logged_out')) {
      // Show the logout modal
      var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'), {});
      logoutModal.show();
    }
  });