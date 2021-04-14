// Feedbacks
var is_empty = "欄位空白";
var invalid_password = "密碼只能包含 ASCII-standard 的字元";
var is_different = "密碼驗證≠ 密碼";
var too_short = "密碼至少要 8 個字元";

$(document).ready(function() {

    $('input#password').on('input', function() {
        validatePassword($(this));
    });

    $('#register input#password-retype').focus(function() {
        if($(this).val() !== '') {
            validatePassword2($(this));
        }
    });
    $('#register input#password-retype').on('input', function() {
        validatePassword2($(this));
    });

});

function passwordIsValid(password) {

    const legal_chars = new RegExp('^[\ -~]+$'); 

    return legal_chars.test(password);

}

function validatePassword(password_field) {

    let password = password_field.val();

    if(password === '') {
        password_field.addClass('is-invalid');
        password_field.removeClass('is-valid');
        password_field.siblings('.invalid-feedback').text(is_empty);
    } else if(password.length < 8) {
        password_field.addClass('is-invalid');
        password_field.removeClass('is-valid');
        password_field.siblings('.invalid-feedback').text(too_short);
    } else if(passwordIsValid(password)) {
        password_field.removeClass('is-invalid');
        password_field.addClass('is-valid');
    } else {
        password_field.addClass('is-invalid');
        password_field.removeClass('is-valid');
        password_field.siblings('.invalid-feedback').text(invalid_password);
    }

}

function validatePassword2(password2_field) {

    let previous_password = $('#register input#password').val();
    let password_retype = password2_field.val();

    if(password_retype === '') {
        password2_field.addClass('is-invalid');
        password2_field.removeClass('is-valid');
        password2_field.siblings('.invalid-feedback').text(is_empty);
    } else if(previous_password === password_retype) {
        password2_field.removeClass('is-invalid');
        password2_field.addClass('is-valid');
    } else {
        password2_field.addClass('is-invalid');
        password2_field.removeClass('is-valid');
        password2_field.siblings('.invalid-feedback').text(is_different);
    }

}
