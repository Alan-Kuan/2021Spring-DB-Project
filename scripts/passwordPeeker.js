$(document).ready(function() {

    $('.password-peeker').click(function() {

        let password_field = $(this).siblings('input');

        if(password_field.attr('type') === 'password')
            password_field.attr('type', 'text');
        else
            password_field.attr('type', 'password');

        $(this).children('i').toggleClass('bi-eye-slash');
        $(this).children('i').toggleClass('bi-eye');

    });

});
