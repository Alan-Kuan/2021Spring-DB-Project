$(document).ready(function() {

    $('#register-shop input[type="submit"]').click(function(e) {

        validateShopName($('#register-shop input#shop_name'));
        validateNumber($('#register-shop input#mask_price'));
        validateNumber($('#register-shop input#mask_amount'));

        let is_valid = true;

        $(this).parent().siblings().children('input').each(function() {

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
