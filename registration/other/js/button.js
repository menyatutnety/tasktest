$(document).ready(function () {

    $('#sign-in-button').click(function () {
        $.ajax({
            type: 'POST',
            url: '/site/signin',
            dataType: 'json',
            data: $('#sign_in_form').serialize(),
            success: function (data) {
                if (data.success) {

                    location.reload();
                } else {
                    $('#sign-in-errors').html(data.message).show();
                }
                $('#sign-up-errors').html('').hide();
                $('#sign-up-success').html('').hide();
                $('#sign_up_form')[0].reset();
            }
        });
    });

    $('#sign-up-button').click(function () {
        $.ajax({
            type: 'POST',
            url: '/site/signup',
            dataType: 'json',
            data: $('#sign_up_form').serialize(),
            success: function (data) {
                if (data.success) {
                    $('#sign-up-errors').html(data.message).hide();
                    $('#sign-up-success').html(data.message).show();
                    $('#sign_up_form')[0].reset();
                } else {
                    $('#sign-up-errors').html(data.message).show();
                    $('#sign-up-success').html(data.message).hide();
                }
                $('#sign-in-errors').html('').hide();
                $('#sign_in_form')[0].reset();
            }
        });
    });

});