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

function loginSuccess(data) {
    console.log('Login success called.');
    console.log(data);
    if (data.success) {
        console.log('Attempting to refresh window...');
        location.reload();
    }
    else {
        displayLoginError('Error: '+data.error);
    }
};

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
};

function logoutError(jqXHR, textStatus, errorThrown) {
    console.log(jqXHR);
    console.log('Status: '+textStatus);
    console.log('Error: '+errorThrown);
    if (errorThrown === undefined || errorThrown.length == 0 || errorThrown == 'No Transport' || errorThrown == 'Error: Access is denied.') {
        displayLoginError('Error logging out (access is denied)');
    }
    else {
        displayLoginError('Error logging out: ['+textStatus+'] '+errorThrown);
    }
};

function login(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    var username = $('#username').val();
    var password = $('#password').val();

    var https_endpoint = $.appConfig.secureEndpoint || false;
    var endpoint = https_endpoint || 'http://'+$.appConfig.ajaxDomain;
    var current_origin = $.appConfig.currentOrigin;
    console.log('https_endpoint: '+https_endpoint);
    console.log('endpoint: '+endpoint);
    console.log('current_origin: '+current_origin);

    if (username.length == 0 || password.length == 0) {
        $('div#headererror').css('display', 'block');
        $('div#headererror').html('Username and password cannot be blank');
        return false;
    }

    displayLoginStatus('Logging in with username <em>'+username+'</em>...');
    if ($.support.cors || (endpoint == current_origin)) {
        $.ajax({
            url: endpoint+'user/login.php',
            type: 'POST',
            dataType: 'json',
            data: { 'username' : username, 'password' : password },
            success: loginSuccess,
            error: loginFailure,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: endpoint != current_origin
        });
    }
    else {
        // displayLoginError('Error: This browser does not support CORS requests.')
        var iframe = $('<iframe>');
        var postForm = $('#loginform').clone(true);
        // var frameName = ('resp'+Math.random()).replace('.', '');
        var frameName = 'testframe';
        var cookieName = 'watch_login';

        iframe
            .css('display', 'none')
            .attr('name', frameName)
            .appendTo('body');

        console.log('frameName: '+frameName);

        iframe[0].contentWindow.name = frameName;

        postForm
            .unbind()
            .css('display', 'none')
            .attr('action', endpoint+'user/login.php')
            .attr('target', frameName)
            .appendTo('body');

        $('<input>')
            .attr({
                type: 'hidden',
                name: 'cookie',
                value: cookieName
            })
            .appendTo(postForm);

        var interval = setInterval(function() {
            var data = $.cookie(cookieName);
            console.log('data: '+data);
            console.log(cookieName);
            console.log(document.cookie);
            // console.log(iframe[0].contentWindow.document.cookie);
            if (data) {
                try {
                    data = $.parseJSON(data);
                }
                catch(e) {
                    data = null;
                }
                $.cookie(cookieName, null, {domain:'', path:'/'});
                clearInterval(interval);
                $.each(data, function(k, v) { console.log(k+': '+v); });
                postForm.remove();
                iframe.remove();
                if (data.success) {
                    location.reload();
                }
                else {
                    displayLoginError('Error: '+data.error);
                }
            }
        }, 500);

        postForm.submit();
    }

    return false;
};

function logout(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    var https_endpoint = $.appConfig.secureEndpoint || false;
    var endpoint = https_endpoint || 'http://'+$.appConfig.ajaxDomain;
    var current_origin = $.appConfig.currentOrigin;
    console.log('https_endpoint: '+https_endpoint);
    console.log('endpoint: '+endpoint);
    console.log('current_origin: '+current_origin);

    displayLoginStatus('Logging out...');
    if ($.support.cors || (current_origin == endpoint)) {
        $.ajax({
            url: endpoint+'user/logout.php',
            type: 'POST',
            dataType: 'json',
            success: function(data) { 
                console.log('Logout success.'); 
                console.log(data); 
                location.reload();
            },
            error: logoutError,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: endpoint != current_origin
        });
    }
    else {
        console.log('Logging out alternate method...');
        var iframe = $('<iframe>');
        var postForm = $('#loginform').clone(true);
        // var frameName = ('resp'+Math.random()).replace('.', '');
        frameName = 'testframe';
        var cookieName = 'watch_logout';

        iframe
            .css('display', 'none')
            .attr('name', frameName)
            .appendTo('body');

        console.log('frameName: '+frameName);

        iframe[0].contentWindow.name = frameName;

        postForm
            .unbind()
            .css('display', 'none')
            .attr('action', endpoint+'user/logout.php')
            .attr('target', frameName)
            .appendTo('body');

        $('<input>')
            .attr({
                type: 'hidden',
                name: 'cookie',
                value: cookieName
            })
            .appendTo(postForm);

        var interval = setInterval(function() {
            data = $.cookie(cookieName);
            console.log('data: '+data);
            console.log(cookieName);
            console.log(document.cookie);
            // console.log(iframe[0].contentWindow.document.cookie);
            if (data) {
                try {
                    data = $.parseJSON(data);
                }
                catch(e) {
                    data = null;
                }
                $.cookie(cookieName, null, {domain:'', path:'/'});
                clearInterval(interval);
                $.each(data, function(k, v) { console.log(k+': '+v); });
                postForm.remove();
                iframe.remove();
                if (data.success) {
                    location.reload();
                }
                else {
                    displayLoginError('Error: '+data.error);
                }
            }
        }, 500);

        postForm.submit();
    }
};

function resetSuccess(data) {
	console.log('Reset success called.');
    if (data.success) {
        displayLoginError('A password reset email has been sent for user <em>'+$('#username').val()+'</em>.');
    }
    else {
        displayLoginError('Error: '+data.error);
    }
};

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
};

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
    var endpoint = 'http://'+$.appConfig.ajaxDomain;
    $.ajax({
        url: endpoint+'user/reset_password.php',
        type: 'POST',
        dataType: 'json',
        data: { 'username' : username },
        success: resetSuccess,
        error: resetFailure
    });
};
