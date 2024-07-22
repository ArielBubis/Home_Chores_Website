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
                if(response.success == '0') {
                    console.log(response.message);
                    $('#signUpError').text(response.message).removeAttr('hidden').show();
                    if(response.message == 'Email already exists.') {
                        $('#logInLink').removeAttr('hidden').show();
                    }
                } else {
                    // Redirect to login screen with sign_up parameter
                    window.location.href = 'log_in.php?sign_up=true';
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
                    // Login successful, redirect to index page
                    window.location.href = 'index.php';
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log(xhr.responseText, error);
            }
        });
    });

    $('.status').change(function() {
        var choreNum = $(this).attr('id');
        var finished = $(this).is(':checked') ? 1 : 0;
        console.log('Chore number: ' + choreNum + ', Finished: ' + finished);
  
        $.ajax({
          url: 'API/update_chore.php',
          type: 'POST',
          data: {
            chore_num: choreNum,
            finished: finished
          },
          success: function(response) {
            console.log('AJAX success response:', response);
            var res = JSON.parse(response);
            alert(res.message);
          },
          error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('Error updating chore status.');
          }
        });
      });



    // Check if the logged_out parameter is set in the URL and show the logout modal
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('logged_out') && urlParams.get('logged_out') == 'true') {
        var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'), {});
        logoutModal.show();
        
    }

    // Check if the sign_up parameter is set in the URL and show the sign up modal
    if (urlParams.has('sign_up') && urlParams.get('sign_up') == 'true') {
        var signUpModal = new bootstrap.Modal(document.getElementById('signUpModal'), {});
        signUpModal.show();
    }
});
