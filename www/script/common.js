(function(){
"use strict";
})();

if (console === undefined) {
    var console = {};
    console.log = function(msg) {
    };
};

function ajaxSubmitWithWatch(form, secure, successFunc, errorFunc) {
    if (secure === false || $.appConfig.secureEndpoint == $.appConfig.currentOrigin) {
        $(form).ajaxSubmit({success: successFunc, error: errorFunc});
    }
    else if (secure === true) {
        if ($.support.cors) {
            // We can use a standard cross-domain request
            console.log('ajaxSubmitWithWatch: Using standard CORS support...');
            $(form).ajaxSubmit({
                success: successFunc,
                error: errorFunc,
                crossDomain: true,
                xhrFields: {
                    withCredentials: true
                }
            });
        }
        else {
            console.log('ajaxSubmitWithWatch: Using non-standard iframe/cookie submission method...')
            cookieName = ('watch_'+Math.random()).replace('.','');

            var interval = setInterval(
                function() {
                    var data = $.cookie(cookieName);
                    if (data !== null) {
                        console.log('Data returned: ');
                        console.log(data);
                        clearInterval(interval);
                        successFunc(data);
                    }
                }, 500);

            $(form).ajaxSubmit({
                iframe: true,
                data: { 'cookie': cookieName },
                error: errorFunc
            });
        }
    }
};

function displayLoginStatus(html) {
    $('div#headererror').css('display', 'none');
    $('#headerstatus').html(html);
    $('div#headerloading').css('display', 'block');
};

function displayLoginError(html) {
    $('div#headerloading').css('display', 'none');
    $('div#headererror').html(html);
    $('div#headererror').css('display', 'block');
};

function loginSubmitSuccess(data) {
    console.log(data);
    data = $.parseJSON(data);
    if (data.success) {
        location.reload();
    }
    else {
        displayLoginError('Error: '+data.error);
    }
}

function loginSubmit(loginData, loginForm, options) {
    var username = $.trim(loginData[0].value),
        password = $.trim(loginData[1].value);

    if (username.length == 0 || password.length == 0) {
        displayLoginError('Username and password cannot be blank.');
        return false;
    }

    displayLoginStatus('Logging in with username <em>'+username+'</em>...');
    ajaxSubmitWithWatch($('#loginform'), true, loginSubmitSuccess);
    return false;
}

function logoutSubmitSuccess(data) {
    if (data.success) {
        location.reload();
    }
    else {
        displayLoginError('Error logging out: '+data.error);
    }
}

function logoutSubmit(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    var username = $.appConfig.user;

    displayLoginStatus('Logging out user <em>'+username+'</em>...');
    $.ajax({
        url: $.appConfig.currentOrigin+'user/logout.php',
        type: 'post',
        dataType: 'json',
        data: { username: $.appConfig.user },
        success: logoutSubmitSuccess,
        error: function(jqXHR, errorThrown, message) { console.log(errorThrown); console.log(message); }
    });
}

function resetPasswordSubmitSuccess(data) {
    if (data.success) {
        displayLoginError('Password reset email for user <em>'+data.username+'</em> has been sent.');
    }
    else {
        displayLoginError('Unable to reset password: '+data.error);
    }
}

function resetPasswordSubmit(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    var username = $.trim($('#username').val());

    if (username.length == 0) {
        displayLoginError('Enter your username in the first login field.');
        return;
    }

    displayLoginStatus('Sending password reset email for user <em>'+username+'</em>...');
    $.ajax({
        url: $.appConfig.currentOrigin+'user/reset_password.php',
        type: 'post',
        dataType: 'json',
        data: { username: username },
        success: resetPasswordSubmitSuccess,
        error: function(jqXHR, errorThrown, message) { console.log(errorThrown), console.log(message); }
    });
}
