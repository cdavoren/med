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

function getAnswerChar(question, answerIndex) {
    if (typeof answerIndex == 'string') {
        answerIndex = parseInt(answerIndex);
    }
    if (question.answer_type == '1') {
        return String.fromCharCode(answerIndex+64);
    }
    else {
        return String.fromCharCode(answerIndex+48);
    }
}

function testSubmitSuccess(testData, correctData) {
    // console.log(testData);
    // console.log(correctData);

    var html = '',
        questionsShown = $('input[name^="question_shown_"]'),
        questionAnswers = $('input[name^="question_answer_"]');

    var answers = {};
    $.each(testData, function(index, value) {
        // console.log(value.name);
        if (value.name.match(/^question_answer_/)) {
            // console.log(value);
            answers[value.name.substr('question_answer_'.length)] = value.value;
        }
    });

    // console.log(answers);

    var numCorrect = 0;

    questionsShown.each(function(index, value) {
        var qid = $(value).attr('name').substring('question_shown_'.length);
        var selectorPrefix = 'div#question_'+correctData[qid].number+' ';
        // alert(selectorPrefix);
        // console.log(qid+': '+answers[qid]+'/'+correctData[qid].correct_answer);
        $($(selectorPrefix+' label')[parseInt(correctData[qid].correct_answer)-1]).addClass('correct');
        $($(selectorPrefix+' div.explanation')).css('display', 'block');
        $(selectorPrefix+'input').each(function() {
            $(this).attr('disabled', 'disabled');
        });

        if (answers[qid] !== undefined && correctData[qid].correct_answer == answers[qid]) {
            // alert(selector);
            $(selectorPrefix+'div.correct').css('display', 'block');
            $(selectorPrefix+'div.incorrect').css('display', 'none');
            html += 'Q'+correctData[qid].number+' correct: '+getAnswerChar(correctData[qid], answers[qid])+'<br />';
            numCorrect++;
            $('#question_'+qid+'_feedback').html(getAnswerChar(correctData[qid], answers[qid])+' was correct.')
        }
        else {
            // alert(selector)
            $(selectorPrefix+'div.incorrect').css('display', 'block');
            $(selectorPrefix+'div.correct').css('display', 'none');
            html += 'Q'+correctData[qid].number+' is incorrect: ('+
                (answers[qid] === undefined ? 'no answer' : getAnswerChar(correctData[qid], answers[qid])) +
                ' / '+getAnswerChar(correctData[qid], correctData[qid].correct_answer)+')<br />';
            $('#question_'+qid+'_feedback').html(''+
                (answers[qid] === undefined ? 'Correct answer was '+getAnswerChar(correctData[qid], correctData[qid].correct_answer)+' (you did not respond)' :
                                              getAnswerChar(correctData[qid], answers[qid])+' incorrect (correct answer was '+getAnswerChar(correctData[qid], correctData[qid].correct_answer)+')'));
        }
    });

    html = 'Number correct: '+numCorrect+'/'+questionsShown.length+'<br />'+html;

    $('span#result_correct_total').html(''+numCorrect+'/'+questionsShown.length);
    // $('div#results').html(html);
    $('div#results').css('display', 'block');
    $('input#test_submit').css('display', 'none');
    $(window).scrollTop($('div#results').position().top);
}

function testSubmitError(jqXHR, errorThrown, message) {
	/*
    console.log(errorThrown);
    console.log(message);
    */
    alert(errorThrown);
    alert(message);
}

function testSubmit(testData, testForm, options) {
    // console.log('Mark test.');
    // alert($.appConfig.currentOrigin+'take_test.php');

    $.ajax({
        url: $.appConfig.currentOrigin+'take_test.php',
        type: 'post',
        dataType: 'json',
        data: {
            id: $('#test_id').val(),
            correct_only: 'true'
        },
        success: function(data) { testSubmitSuccess(testData, data); },
        error: testSubmitError
    });

    return false;
}

