function loginSuccess(data) {
    console.log('Login success.');
    console.log(data);
    location.reload();
}

function loginFailure(jqXHR, textStatus, errorThrown) {
    console.log('Login failed.');
    // console.log(jqXHR);
    console.log(textStatus);
    console.log(errorThrown);
}

function login() {
    var username = $('#username').val();
    var password = $('#password').val();

    if (username.length == 0) {
        alert('Enter a username.');
        return 0;
    }
    if (password.length == 0) {
        alert('Enter a password.');
        return 0;
    }

    console.log('Username: ' + username);
    console.log('Password: ' + password);

    var loginResult = $.ajax({
        url: 'http://ubuntu-vm/user/login.php',
        type: 'POST',
        dataType: 'json',
        data: { 'username' : username, 'password' : password },
        success: loginSuccess,
        error: loginFailure,
    });

    return true;
}

function logout() {
    var logoutResult = $.ajax({
        url: 'http://ubuntu-vm/user/logout.php',
        dataType: 'json',
        success: function(data) { console.log('Logout success.'); console.log(data); location.reload(); },
        error: function(jqXHR, textStatus, thrownError) { console.log('Logout failure.'); console.log(textStatus); console.log(thrownError); },
    });
}
