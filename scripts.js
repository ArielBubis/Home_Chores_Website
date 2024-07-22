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
                // alert(res.message);
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

    $('#newChoreForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
    
        $.ajax({
            url: 'API/add_chore.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success == 1) {
                    console.log(response);
                    // Add the new chore to the table dynamically
                    var newRow = `
                        <tr>
                        <td>
                            <div class="ms-6 text-center">
                                <p class="fw-bold mb-1">${response.choreTitle}</p>
                            </div>
                        </td>
                        <td class="text-center">
                            <p class="text-break">${response.dateAdded}</p>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                                <div>
                                    <p class="mb-1">${response.choreUserName}</p>
                                </div>
                            </div>
                        </td>
                        <td><input type="checkbox" class="form-check-input status" id="chore-${response.chore_num}"></td>
                    </tr>
                `;
                    $('table tbody').append(newRow);
                    $('#newChoreModal').modal('hide'); // Hide the modal
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert('Error adding chore.');
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
