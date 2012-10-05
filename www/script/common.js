"use strict";

if (console === undefined) {
    var console = {};
    console.log = function(msg) {
    };
};

var displayLoginStatus = function (html) {
    $('div#headererror').css('display', 'none');
    $('#headerstatus').html(html);
    $('div#headerloading').css('display', 'block');
};

var displayLoginError = function(html) {
    $('div#headerloading').css('display', 'none');
    $('div#headererror').html(html);
    $('div#headererror').css('display', 'block');
};

var loginSuccess = function(data) {
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

var loginFailure = function(jqXHR, textStatus, errorThrown) {
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

var logoutError = function(jqXHR, textStatus, errorThrown) {
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

    var https_endpoint = $.appConfig.https_endpoint || false;
    var endpoint = https_endpoint || 'http://'+$.appConfig.ajax_domain;
    var current_origin = $.appConfig.current_origin;
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

var logout = function(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }
    var https_endpoint = $.appConfig.https_endpoint || false;
    var endpoint = https_endpoint || 'http://'+$.appConfig.ajax_domain;
    var current_origin = $.appConfig.current_origin;
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

var resetFailure = function(jqXHR, textStatus, errorThrown) {
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

var resetPassword = function(evt) {
    if (evt !== undefined) {
        evt.preventDefault();
    }

    var username = $('#username').val();

    if (username.length == 0) {
        displayLoginError('Enter a username to reset a forgotten password.');
        return;
    }
    displayLoginStatus('Sending password reset email...');
    var endpoint = 'http://'+$.appConfig.ajax_domain;
    $.ajax({
        url: endpoint+'user/reset_password.php',
        type: 'POST',
        dataType: 'json',
        data: { 'username' : username },
        success: resetSuccess,
        error: resetFailure
    });
};
