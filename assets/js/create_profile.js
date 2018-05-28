function validateMatchPwd(value, element) {
    matchValue = $("#" + $(element).attr('match-pwd-with')).val();
    value = value.toLowerCase();
    matchValue = matchValue.toLowerCase();
    return value.indexOf(matchValue) === 0;
}

$(document).ready(function () {

    $.get("rest/gradovi", function (data) {
        $.each(data, function (index, item) {
            $('#citiesList').append(
                $('<option></option>').val(item.id + " - " + item.city).html(item.id + " - " + item.city)
            );
        });
    });

    $(['osnovne', 'srednje', 'fakulteti']).each(function (index, type) {
        $.get("rest/"+type, function (data) {
            $.each(data, function (index, item) {
                $('#'+type+'-skole').append(
                    $('<option></option>').val(item.id + " - " + item.name).html(item.id + " - " + item.name)
                );
            });
        });
    });

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
        submitHandler: function(form) {
            if (confirm('Da li ste sigurni da želite nastaviti?')){
                $.post('rest/kreiraj_korisnika', $('#profile-form').serialize(), function(){
                    $('#profile-form').trigger('reset');
                    $(['osnovci', 'srednjoskolci', 'studenti']).each(function (index, name) {
                        $('#'+name+'-table').DataTable().ajax.reload();
                    });
                    alert("Uspješno pregledan izvještaj!");
                });
                return false;
            }
        }
    });

    $.validator.addMethod("match_password", validateMatchPwd, "Passwords do not match!");
});