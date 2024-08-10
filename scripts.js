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
                    
                    confirmButton = document.getElementById('confirmButton');
                    confirmButton.setAttribute('hidden' , 'hidden');

                    okButton = document.getElementById('closeButton');
                    okButton.textContent = 'OK';
                    okButton.classList.add('btn-success');

                    okButton.onclick = function () {
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
    
    $('#signUpemail').on('blur', function () {
        var email = $(this).val();
        if (email) {
            $.ajax({
                url: 'API/check_email_exist.php',
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function (response) {
                    if (response.exists) {
                        $('#signUpError').text(response.message).removeAttr('hidden').show();
                        $('#logInLink').removeAttr('hidden').show();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error checking email:', error);
                }
            });
        }
    });


    // Attach a submit event handler to the sign-in form
    $('#signInForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        var loginFormData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: 'API/check_login.php',
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
                    confirmButton = document.getElementById('confirmButton');
                    confirmButton.setAttribute('hidden' , 'hidden');

                    okButton = document.getElementById('closeButton');
                    okButton.textContent = 'OK';
                    okButton.classList.add('btn-success');

                    okButton.onclick = function () {
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
        showModal('Log out', 'Are you sure you want to log out?');
        var closeButton = document.getElementById('closeButton');
        closeButton.removeAttribute('hidden');
        closeButton.textContent = 'Cancel';
        document.getElementById('confirmButton').textContent = 'Confirm';

        // Handle the confirmation button click
        document.getElementById('confirmButton').onclick = function () {
        $.ajax({
            url: 'API/logout.php', // Your server's logout endpoint
            type: 'POST',
            dataType: 'json', // Ensure the response is treated as JSON
            success: function (response) {
                // Now `response` is a JSON object
                if (response.logged_out) {
                    // Show the logout modal
                    showModal('Logged Out', 'You successfully logged out from the website.');
                    confirmButton = document.getElementById('confirmButton');
                    confirmButton.setAttribute('hidden' , 'hidden');

                    okButton = document.getElementById('closeButton');
                    okButton.textContent = 'OK';
                    okButton.classList.add('btn-success');
            
                    // Optionally, clear the session storage item
                    sessionStorage.removeItem('logged_out');

                    okButton.onclick = function () {
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
    }
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
                <tr class = "chore-item">
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
                                      <td>
                    <button class="btn btn-danger delete-chore" value="${response.chore_num}">Delete</button>
                  </td>
                </tr>
            `; $('table tbody').append(newRow);
                    $('#newChoreModal').modal('hide'); // Hide the modal
                    $('#newChoreForm').trigger("reset"); // Clear form afer closing
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

    
    if(document.getElementById('choresContainer')) {
        document.getElementById('choresContainer').addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('delete-chore')) {
              const choreId = event.target.value;
              const deleteButton = event.target;
          
              // Show the modal
              showModal('Confirm Deletion', 'Are you sure you want to delete this chore?');
              var closeButton = document.getElementById('closeButton');
              closeButton.removeAttribute('hidden');
              closeButton.textContent = 'Cancel';
              document.getElementById('confirmButton').textContent = 'Delete';

              // Handle the confirmation button click
              document.getElementById('confirmButton').onclick = function () {
                const formData = JSON.stringify({ choreId: choreId });
          
                $.ajax({
                  url: 'API/delete_chore.php',
                  type: 'POST',
                  contentType: 'application/json',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                    if (response.success) {
                    // Ensure the closest method finds the correct ancestor
                      const choreItem = deleteButton.closest('.chore-item');
                      if (choreItem) {
                        choreItem.remove();
                      } else {
                        console.error('Chore item not found');
                      }
                    } else {
                      alert('Error deleting chore: ' + response.error);
                    }
                  },
                  error: function(xhr, status, error) {
                    console.error('Error:', error);
                  }
                });
              };
            }
          });
        }
    
    // Attach a submit event handler to the index page to add a new user to the Household
    $('#newUserForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
            url: 'API/add_user_to_household.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success == 1) {
                    console.log(response);
                    alert('User added successfully!');
                    
                    // Optionally, close the modal
                    $('#newUserModal').modal('hide');
                    
                    // Clear the form fields
                    $('#newUserForm')[0].reset();
                } else {
                    alert(response.message);
                    console.log(response);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);

                alert('Error adding user to household.');
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
        document.getElementById('confirmButton').setAttribute('hidden', 'hidden');
        document.getElementById('closeButton').textContent = 'OK';
        document.getElementById('closeButton').classList.add('btn-success');

    }

    // Function to show a modal with the given title, body, and footer buttons
    function showModal(title, body, footerButtonsHtml = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id = "closeButton">Cancel</button> <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="confirmButton">Confirm</button>') {
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


    // Fetch emails from the server
    var emailInput = document.getElementById('emailInput');
    if (emailInput) {
        $.ajax({
            url: 'API/fetch_emails.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // Initialize autocomplete with fetched emails
                $('#emailInput').autocomplete({
                    appendTo: "#suggesstion-box",
                    source: data
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching emails:', error);
            }
        });
    }
    // Attach a submit event handler to the password reset form
    $('#newUserModal').on('hidden.bs.modal', function () {
        $('#emailInput').val('');
    });


});
