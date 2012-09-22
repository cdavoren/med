function loginSuccess(data) {
    console.log('Login success called.');
    console.log(data);
    if (data.success) {
        window.location.href = window.location.href;
    }
    else {
        $('div#headerloading').css('display', 'none');
        $('div#headererror').css('display', 'block');
        $('div#headererror').html('Error: '+data.error);
    }
}

function loginFailure(jqXHR, textStatus, errorThrown) {
    console.log('Login failure called.');
    // console.log(jqXHR);
    console.log(textStatus);
    console.log(errorThrown);

    $('div#headerloading').css('display', 'none');
    $('div#headererror').css('display', 'block');
    if (errorThrown !== undefined || errorThrown.length > 0) {
        $('div#headererror').html('Error logging in: ['+textStatus+'] ' + errorThrown);
    }
    else {
        $('div#headererror').html('Error logging in (probable cross-site scripting error)');
    }
}

function login(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    var username = $('#username').val();
    var password = $('#password').val();

    if (username.length == 0 || password.length == 0) {
        $('div#headererror').css('display', 'block');
        $('div#headererror').html('Username and password cannot be blank');
        return false;
    }

    $('#headerstatus').html('Logging in with username <em>'+username+'</em>...');
    $('div#headererror').css('display', 'none');
    $('div#headerloading').css('display', 'block');

    var url = ($.appConfig.ssl_enabled ? 'https' : 'http') +
        '://' + 
        $.appConfig.app_server +
        $.appConfig.app_root +
        'user/login.php';

    var loginResult = $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: { 'username' : username, 'password' : password },
        success: loginSuccess,
        error: loginFailure,
        xhrFields: {
            withCredentials: true,
        },
    });

    return false;
}

function logout(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    $('#headerstatus').html('Logging out...');
    $('div#headererror').css('display', 'none');
    $('div#headerloading').css('display', 'block');

    var url = ($.appConfig.ssl_enabled ? 'https' : 'http') +
        '://' + 
        $.appConfig.app_server +
        $.appConfig.app_root +
        'user/logout.php';

    var logoutResult = $.ajax({
        url: url,
        dataType: 'json',
        success: function(data) { console.log('Logout success.'); console.log(data); window.location.href = window.location.href; },
        error: function(jqXHR, textStatus, thrownError) { console.log('Logout failure.'); console.log(textStatus); console.log(thrownError); },
        xhrFields: {
            withCredentials: true,
        },
        crossDomain: true,
    });
}
