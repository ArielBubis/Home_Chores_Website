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



});