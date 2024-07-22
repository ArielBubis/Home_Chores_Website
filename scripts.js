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
                if(response.success == '0') {
                    console.log(response.message);
                    $('#signUpError').text(response.message).removeAttr('hidden').show();
                    if(response.message == 'Email already exists.') {
                    $('#logInLink').removeAttr('hidden').show();
                    }

                } else {
                    $('#signUpError').attr('hidden', 'hidden').hide();
                    $('#logInLink').attr('hidden', 'hidden').hide();
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

// Check if used logged out and show the logout modal
$(document).ready(function() {
    // Check if the logged_out parameter is set in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('logged_out') && urlParams.get('logged_out') == 'true') {
      // Show the logout modal
      var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'), {});
      logoutModal.show();
    }
  });

// Check if the sign_up parameter is set in the URL and show the sign up modal
$(document).ready(function() {
    var urlParams1 = new URLSearchParams(window.location.search);
    if (urlParams1.has('sign_up') && urlParams1.get('sign_up') == 'true') {
        var siginModal = new bootstrap.Modal(document.getElementById('signUpModal'), {});
        siginModal.show();
    }
});



  document.addEventListener('DOMContentLoaded', function() {
    // Attach change event listener to checkboxes
    document.querySelectorAll('.status').forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        const choreId = this.getAttribute('data-id');
        const finished = this.checked ? 1 : 0; // 1 if checked, 0 if not
  
        // Prepare data to send
        const formData = new FormData();
        formData.append('chore_num', choreId);
        formData.append('finished', finished);
  
        // Send AJAX request to update.php
        fetch('update.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log(data.message); // Log success message
        })
        .catch(error => console.error('Error:', error));
      });
    });
  });