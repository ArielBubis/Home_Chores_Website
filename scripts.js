$(document).ready(function() {

    $('#signUpForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // console.log("scriptjs", response);
                if(response.success == '0') {
                    console.log(response.message);
                    $('#signUpError').text(response.message).removeAttr('hidden').show();
                    if(response.message == 'Email already exists.') {
                    $('#logInLink').removeAttr('hidden').show();
                    }

                } else {
                    $('#signUpError').attr('hidden', 'hidden').hide();
                    $('#logInLink').removeAttr('hidden').show();

                    alert('Signup successful!');
                }
            }, 
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log(xhr.responseText, error);
            }
            
        });
    });



});