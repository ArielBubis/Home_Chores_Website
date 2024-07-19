$(document).ready(function() {
    
    $('#signupPage').click(function(e) {
        $('#emailError').css('visibility', 'hidden');
    });

    $('#signUpForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var email = $('#email').val();

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if(jsonData.success == '0') {
                    $('#emailError').show();
                } else {
                    $('#emailError').hide();
                    alert('Signup successful!');
                }
            }, 
            error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
            }
        });
    });

    // $('#signUpForm').on('submit', function(e) {
    //     if ($('#emailError').is(':visible')) {
    //         e.preventDefault();
    //     }
    // });


});