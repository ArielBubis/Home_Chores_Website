$(document).ready(function() {
    
    $('#email').submit(function(e) {
        e.preventDefault(); 
        var email = $(this).val();

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: email,
            success: function(response) {
                if (response == 'Email already exists. Please use a different email.') {
                    $('#emailError').show();
                } else {
                    $('#emailError').hide();
                }
            }
        });
    });

    $('#signUpForm').on('submit', function(e) {
        if ($('#emailError').is(':visible')) {
            e.preventDefault();
        }
    });


});