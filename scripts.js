// Ensure the DOM is fully loaded before executing any scripts
$(document).ready(function () {

    // Attach a submit event handler to the sign-up form
    $('#signUpForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data

        // Initially hide the login link and sign-up error message
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
                    // On successful sign-up, show a modal indicating success
                    showModal('Sign Up Successful', 'You successfully signed up to the website, please log in to continue.');
                    // Set up a click event handler for the modal's close button
                    document.getElementById('closeButton').onclick = function () {
                        // Redirect the user to the login page upon clicking the close button
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


    // Attach a submit event handler to the sign-in form
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
                    // alert('Login failed. Please check your credentials and try again.');
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

    // Attach a submit event handler to the new chore form to add a new chore to the table dynamically
    $('#newChoreForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: 'API/add_chore.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
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
                            <img src="https://api.dicebear.com/9.x/bottts/svg?baseColor=${response.avatar_color}" alt="" style="width: 45px; height: 45px; object-fit: cover;" class="img-fluid rounded-circle mb-2 d-none d-sm-block" />
                            <div>
                                <p class="mb-1">${response.choreUserName}</p>
                            </div>
                        </div>
                    </td>
                    <td><input type="checkbox" class="form-check-input status" id="${response.chore_num}"></td>
                </tr>
            `; $('table tbody').append(newRow);
                    $('#newChoreModal').modal('hide'); // Hide the modal
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert('Error adding chore.');
            }
        });
    });


    // Attach a submit event handler to the to the back button in the chores page to update the status of the list
    var choresBackButton = document.getElementById('choresBackButton');
    if (choresBackButton) {
        choresBackButton.addEventListener('click', function () {
            console.log(choresBackButton);
            var listId = choresBackButton.value;
            console.log('List ID: ' + listId);
            $.ajax({
                type: "POST",
                url: 'API/check_status.php', 
                dataType: 'json',
                data: {
                    action: 'updateChoreListStatus', 
                    listId: listId
                },
                success: function (response) {
                    if (!('error' in response)) {
                        console.log(response.message); // Handle success
                        window.location.href = 'index.php';
                    } else {
                        console.log(response.error); // Handle error
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error: ", status, error); // Handle AJAX error
                }
            });
        });
    }




    // Assuming 'choresContainer' is the ID of the parent container for all chores
    var choresContainer = document.getElementById('choresContainer');
    if (choresContainer) {
        // Use event delegation for dynamically added .status elements
        $(choresContainer).on('change', '.status', function () {
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
    }
    // Check if the URL contains a message parameter with the value 'not_signed_in' and redirect to the login page
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('message') === 'not_signed_in') {
        showModal('Not Signed In', 'You must be signed in to view that page. Please sign in.');
    }

    // Function to show a modal with the given title, body, and footer buttons
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


    // Get the avatar color picker element in the signupscreen
    var avatarColorPicker = document.getElementById('avatarColorPicker');
    if (avatarColorPicker) {
        avatarColorPicker.addEventListener('change', function () {
            var color = this.value.substring(1); // Remove the '#' from the color value
            var avatarImage = document.getElementById('avatarImage');
            if (avatarImage) {
                avatarImage.src = 'https://api.dicebear.com/9.x/bottts/svg?baseColor=' + color;
            }
        });
    }


});
