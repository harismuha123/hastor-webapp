function validateMatchPwd(value, element) {
    matchValue = $("#" + $(element).attr('match-pwd-with')).val();
    value = value.toLowerCase();
    matchValue = matchValue.toLowerCase();
    return value.indexOf(matchValue) === 0;
}

$(document).ready(function () {

    $("#profile-form").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 40
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 40
            },
            email: {
                required: true
            },
            address: {
                required: true,
                minlength: 5
            }
        },
        submitHandler(form) {

        }
    });

    $.validator.addMethod("match_password", validateMatchPwd, "Passwords do not match!");
});