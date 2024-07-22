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

document.addEventListener('DOMContentLoaded', (event) => {
    // Add event listener to all checkboxes with class 'status'
    document.querySelectorAll('input.status').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            window.location.href = 'log_in.php?sign_up=true';

            // console.log('Checkbox changed');
            // // Get the ID of the checkbox
            // const choreId = this.id;
            // // Determine if the checkbox is checked
            // const isChecked = this.checked;

            // // Send AJAX request to update the database
            // const xhr = new XMLHttpRequest();
            // xhr.open('POST', 'update_chore.php', true);
            // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // xhr.onload = function() {
            //     if (xhr.status >= 200 && xhr.status < 300) {
            //         console.log('Chore updated successfully');
            //     } else {
            //         console.error('Error updating chore');
            //     }
            // };

            // xhr.send(`chore_id=${encodeURIComponent(choreId)}&finished=${encodeURIComponent(isChecked ? 1 : 0)}`);
        });
    });
});