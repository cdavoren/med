<?php header('Content-Type: application/json; charset=utf-8') ?>
<?php 
require __DIR__.'/../../lib/init.php';
$appConfig = App::getConfiguration(); 
?>
$.support.cors = true;
function loginSuccess(data) {
    console.log('Login success called.');
    console.log(data);
    if (data.success) {
        window.location.href = window.location.href;
    }
    else {
        displayLoginError('Error: '+data.error);
    }
}

function loginFailure(jqXHR, textStatus, errorThrown) {
    console.log('Login failure called.');
    // console.log(jqXHR);
    console.log(textStatus);
    console.log(errorThrown);

    if (errorThrown === undefined || errorThrown.length == 0 || errorThrown == 'No Transport') {
        displayLoginError('Error logging in (probable cross-site scripting error)');
    }
    else {
        displayLoginError('Error logging in: ['+textStatus+'] ' + errorThrown);
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
    displayLoginStatus('Logging in with username <em>'+username+'</em>...');

    $.support.cors = true;
    var loginResult = $.ajax({
        url: '<?php echo ($appConfig['ssl_enabled'] ? 'https' : 'http').'://'.$appConfig['app_server'].$appConfig['app_root'].'user/login.php' ?>',
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
    displayLoginStatus('Logging out...');

    $.support.cors = true;
    var logoutResult = $.ajax({
        url: '<?php echo ($appConfig['ssl_enabled'] ? 'https' : 'http').'://'.$appConfig['app_server'].$appConfig['app_root'].'user/logout.php' ?>',
        dataType: 'json',
        success: function(data) { console.log('Logout success.'); console.log(data); window.location.href = window.location.href; },
        error: function(jqXHR, textStatus, thrownError) { console.log('Logout failure.'); console.log(textStatus); console.log(thrownError); },
        xhrFields: {
            withCredentials: true,
        },
        crossDomain: true,
    });
}

function displayLoginStatus(html) {
    $('div#headererror').css('display', 'none');
    $('#headerstatus').html(html);
    $('div#headerloading').css('display', 'block');
}

function displayLoginError(html) {
    $('div#headerloading').css('display', 'none');
    $('div#headererror').html(html);
    $('div#headererror').css('display', 'block');
}

function resetSuccess(data) {
    if (data.success) {
        displayLoginError('A password reset email has been sent for user <em>'+$('#username').val()+'</em>.');
    }
    else {
        displayLoginError('Error: '+data.error);
    }
}

function resetFailure(jqXHR, textStatus, errorThrown) {
    console.log(jqXHR);
    console.log('Status: '+textStatus);
    console.log('Thrown error: '+errorThrown);
    if (errorThrown === undefined || errorThrown.length == 0 || errorThrown == 'No Transport') {
        displayLoginError('Error logging in (probable cross-site scripting error)');
    }
    else {
        displayLoginError('Error logging in: ['+textStatus+'] ' + errorThrown);
    }
}

function resetPassword(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }

    var username = $('#username').val();

    if (username.length == 0) {
        displayLoginError('Enter a username to reset a forgotten password.');
        return;
    }
    displayLoginStatus('Sending password reset email...');
    $.ajax({
        url: '<?php echo App::getRelativeRootForPath() ?>user/reset_password.php',
        type: 'POST',
        dataType: 'json',
        data: { 'username' : username },
        success: resetSuccess,
        failure: resetFailure
    });
}
