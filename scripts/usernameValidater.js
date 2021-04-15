// Feedbacks
var is_empty = "欄位空白";
var invalid_username = "帳號名稱只接受 0-9, '.', a-z, A-Z";
var username_exists = "帳號已被註冊";
var not_register = "不存在的帳號";

$(document).ready(function() {

    $('#register input#username').on('input', function() {
        validateRegisterUsername($(this));
    });

    $('#login input#username').on('input', function() {
        validateLoginUsername($(this));
    });

});

function usernameIsValid(username) {

    const legal_chars = new RegExp('^[0-9\.a-zA-Z]+$'); 

    return legal_chars.test(username);

}

function checkUserExists(username, job) {

    $.ajax({
        method: 'POST',
        url: document.location.origin + '/project/ajax/checkUserExist.php',
        data: {
            'username': username
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

function validateRegisterUsername(username_field) {

    let username = username_field.val();

    if(username === '') {
        username_field.addClass('is-invalid');
        username_field.removeClass('is-valid');
        username_field.siblings('.invalid-feedback').text(is_empty);
    } else if(usernameIsValid(username)) {

        checkUserExists(username, (res) => {

            if(res.userExists === 0) {
                username_field.removeClass('is-invalid');
                username_field.addClass('is-valid');
            } else if(res.userExists === 1) {
                username_field.addClass('is-invalid');
                username_field.removeClass('is-valid');
                username_field.siblings('.invalid-feedback').text(username_exists);
            } else {
                console.log('An internal error occurred when checking validity of the username!');
            }

        });

    } else {
        username_field.addClass('is-invalid');
        username_field.removeClass('is-valid');
        username_field.siblings('.invalid-feedback').text(invalid_username);
    }

}

function validateLoginUsername(username_field) {

    let username = username_field.val();

    if(username === '') {
        username_field.addClass('is-invalid');
        username_field.removeClass('is-valid');
        username_field.siblings('.invalid-feedback').text(is_empty);
    } else if(usernameIsValid(username)) {

        checkUserExists(username, (res) => {

            if(res.userExists === 0) {
                username_field.addClass('is-invalid');
                username_field.removeClass('is-valid');
                username_field.siblings('.invalid-feedback').text(not_register);
            } else if(res.userExists === 1) {
                username_field.removeClass('is-invalid');
                username_field.addClass('is-valid');
            } else {
                console.log('An internal error occurred when checking validity of the username!');
            }

        });

    } else {
        username_field.addClass('is-invalid');
        username_field.removeClass('is-valid');
        username_field.siblings('.invalid-feedback').text(invalid_username);
    }

}
