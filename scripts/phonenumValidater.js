// Feedbacks
var is_empty = "欄位空白";
var invalid_phonenum = "電話號碼只能包含 0-9 的數字";
var length_incorrect = "電話號碼的長度應介於 7 ~ 10 個數字";

$(document).ready(function() {

    $('#register input#phone_num').on('input', function() {
        validatePhonenum($(this));
    });

});

function phonenumIsValid(phone_num) {

    const legal_chars = new RegExp('^[0-9]+$'); 

    return legal_chars.test(phone_num);

}

function validatePhonenum(phonenum_field) {

    let phone_num = phonenum_field.val();

    if(phone_num === '') {
        phonenum_field.addClass('is-invalid');
        phonenum_field.removeClass('is-valid');
        phonenum_field.siblings('.invalid-feedback').text(is_empty);
    } else if(!phonenumIsValid(phone_num)) {
        phonenum_field.addClass('is-invalid');
        phonenum_field.removeClass('is-valid');
        phonenum_field.siblings('.invalid-feedback').text(invalid_phonenum);
    } else if(phone_num.length < 7 || phone_num.length > 10) {
        phonenum_field.addClass('is-invalid');
        phonenum_field.removeClass('is-valid');
        phonenum_field.siblings('.invalid-feedback').text(length_incorrect);
    } else {
        phonenum_field.removeClass('is-invalid');
        phonenum_field.addClass('is-valid');
    }

}
