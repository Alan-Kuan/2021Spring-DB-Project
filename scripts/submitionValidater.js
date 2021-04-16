$(document).ready(function() {

    $('input[type="submit"]').click(function(e) {

        if($(this).hasClass('register')) {
            validateRegisterUsername($('#register input#username'));
            validateRegisterPassword($('#register input#password'));
            validatePassword2($('#register input#password-retype'));
            validatePhonenum($('#register input#phone_num'));
        } else if($(this).hasClass('login')) {
            validateLoginUsername($('#login input#username'));
            validateLoginPassword($('#login input#password'));
        }

        let is_valid = true;

        $(this).siblings().children('input').each(function() {

            if($(this).hasClass('is-invalid')) {

                let input_container = $(this).parent();

                input_container.addClass('animate__animated animate__shakeX');

                setTimeout(function() {
                    input_container.removeClass('animate__animated animate__shakeX');
                }, 1000);

                is_valid = false;
            }

        });

        if(!is_valid) {
            e.preventDefault();
        }

    });

});
