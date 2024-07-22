$(document).ready(function () {

    $('#signUpForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        $('#logInLink').attr('hidden', 'hidden').hide();
        $('#signUpError').attr('hidden', 'hidden').hide();

        $.ajax({
            url: 'API/add_user.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success == '0') {
                    console.log(response.message);
                    $('#signUpError').text(response.message).removeAttr('hidden').show();
                    if (response.message == 'Email already exists.') {
                        $('#logInLink').removeAttr('hidden').show();
                    }
                } else {
                    // Redirect to login screen with sign_up parameter
                    showModal('Sign Up Successful', 'You successfully signed up to the website, please log in to continue.');
                    document.getElementById('closeButton').onclick = function () {
                        // Redirect after the button is clicked
                        window.location.href = 'log_in.php';
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log(xhr.responseText, error);
            }
        });
    });

    $('#signInForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        var loginFormData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: 'API/login_db.php',
            type: 'POST',
            data: loginFormData,
            dataType: 'json',
            success: function (response) {
                if (response.success == '0') {
                    // Login failed, display error message
                    console.log(response.message);
                    alert('Login failed. Please check your credentials and try again.');
                    $('#loginError').text(response.message).removeAttr('hidden').show();
                } else {
                    // Login successful, redirect to index page
                    showModal('Sign in Successful', 'You successfully logged in to the website');
                    document.getElementById('closeButton').onclick = function () {
                        // Redirect after the button is clicked
                        window.location.href = 'index.php';
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log(xhr.responseText, error);
            }
        });
    });

    $('.status').change(function () {
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
            success: function (response) {
                console.log('AJAX success response:', response);
                var res = JSON.parse(response);
                alert(res.message);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('Error updating chore status.');
            }
        });
    });
    
    // Using jQuery to handle logout
    $('#logoutNav, #logoutPage').on('click', function (e) {
        e.preventDefault(); // Prevent default link behavior
        $.ajax({
            url: 'API/logout.php', // Your server's logout endpoint
            type: 'POST',
            dataType: 'json', // Ensure the response is treated as JSON
            success: function (response) {
                // Now `response` is a JSON object
                if (response.logged_out) {
                    // Show the logout modal
                    showModal('Logged Out', 'You successfully logged out from the website.');

                    // Optionally, clear the session storage item
                    sessionStorage.removeItem('logged_out');

                    document.getElementById('closeButton').onclick = function () {
                        // Redirect after the button is clicked
                        window.location.href = 'log_in.php';
                    };
                }
            },
            error: function (xhr, status, error) {
                // Handle any errors
                console.error("Logout failed:", status, error);
            }
        });
    });


    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('message') === 'not_signed_in') {
        showModal('Not Signed In', 'You must be signed in to view that page. Please sign in.');
        document.getElementById('closeButton').onclick = function () {
            // Redirect after the button is clicked
            window.location.href = 'log_in.php';
        }

    }


    function showModal(title, body, footerButtonsHtml = '<button type="button" class="btn btn-success" data-bs-dismiss="modal" id = "closeButton">Close</button>') {
        // Update the title
        document.getElementById('genericModalLabel').innerText = title;

        // Update the body
        document.querySelector('#genericModal .modal-body').innerHTML = body;

        // Update the footer, if necessary
        document.querySelector('#genericModal .modal-footer').innerHTML = footerButtonsHtml;

        // Show the modal
        var modal = new bootstrap.Modal(document.getElementById('genericModal'), {});
        modal.show();
    }

});
