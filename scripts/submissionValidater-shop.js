// Feedbacks
var no_selection = "請選擇一個縣市";

$(document).ready(function() {

    $('#register-shop input[type="submit"]').click(function(e) {

        validateCity();
        validateShopName($('#register-shop input#shop_name'));
        validateNumber($('#register-shop input#mask_price'));
        validateNumber($('#register-shop input#mask_amount'));

        let is_valid = true;

        $(this).parent().siblings().children('input, select').each(function() {

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

    function validateCity() {

        let city_select = $('#register-shop select[name="city"]');
        let selection = city_select.val();

        if(selection === 'no-selection') {
            city_select.addClass('is-invalid');
            city_select.removeClass('is-valid');
            city_select.siblings('.invalid-feedback').text(no_selection);
        } else {
            city_select.addClass('is-valid');
            city_select.removeClass('is-invalid');
        }

    }

});
