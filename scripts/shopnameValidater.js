// Feedbacks
var is_empty = "欄位空白";
var shop_name_exists = "該店家名稱已被註冊";
var leading_or_trailing_space = "開頭或結尾不能包含空白";

$(document).ready(function() {

    $('#register-shop input#shop_name').on('input', function() {
        validateShopName($(this));
    });

});

function shopNameIsValid(shop_name) {

    const leading_space = new RegExp('^\ +.*$'); 
    const trailing_space = new RegExp('^.*\ +$'); 

    return !leading_space.test(shop_name) && !trailing_space.test(shop_name);

}

function checkShopExists(shop_name, job) {

    $.ajax({
        method: 'POST',
        url: document.location.origin + '/project/ajax/checkShopExist.php',
        data: {
            'shop_name': shop_name
        },
        dataType: 'json',
        success: (res) => {
            job(res);
        },
        error: (err) => {
            console.log(err);
        }
    });

}

function validateShopName(shop_name_field) {

    let shop_name = shop_name_field.val();

    if(shop_name === '') {
        shop_name_field.addClass('is-invalid');
        shop_name_field.removeClass('is-valid');
        shop_name_field.siblings('.invalid-feedback').text(is_empty);
    } else if(shopNameIsValid(shop_name)) {

        checkShopExists(shop_name, (res) => {

            if(res.shopExists === 0) {
                shop_name_field.removeClass('is-invalid');
                shop_name_field.addClass('is-valid');
            } else if(res.shopExists === 1) {
                shop_name_field.addClass('is-invalid');
                shop_name_field.removeClass('is-valid');
                shop_name_field.siblings('.invalid-feedback').text(shop_name_exists);
            } else {
                console.log('An internal error occurred when checking validity of the shop_name!');
            }

        });

    } else {
        shop_name_field.addClass('is-invalid');
        shop_name_field.removeClass('is-valid');
        shop_name_field.siblings('.invalid-feedback').text(leading_or_trailing_space);
    }

}
