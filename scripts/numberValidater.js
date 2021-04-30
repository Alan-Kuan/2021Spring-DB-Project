// Feedbacks
var is_empty_or_not_num = "欄位空白或填入非數字";
var invalid_num = "請輸入正整數或零";

$(document).ready(function() {

    $('#register-shop input[type="number"]').on('input', function() {
        validateNumber($(this));
    });

});

function numIsValid(phone_num) {

    const legal_chars = new RegExp('^[+-]?[0-9]+$'); 

    if(!legal_chars.test(phone_num))
        return false;

    return parseInt(phone_num, 10) >= 0;

}

function validateNumber(num_field) {

    let num = num_field.val();

    if(num === '') {
        num_field.addClass('is-invalid');
        num_field.removeClass('is-valid');
        num_field.siblings('.invalid-feedback').text(is_empty_or_not_num);
    } else if(numIsValid(num)) {
        num_field.removeClass('is-invalid');
        num_field.addClass('is-valid');
    } else {
        num_field.addClass('is-invalid');
        num_field.removeClass('is-valid');
        num_field.siblings('.invalid-feedback').text(invalid_num);
    }

}
