
function regAccount(btn){
    $(btn).buttonLoader('start');
    $.post('/account/register',$('#regForm').serialize(),function(data){
        var response = $.parseJSON(data);
        if(response.result === true){
            liliumNotify(response.message,'success');
            $('#regForm').trigger( 'reset' );
            grecaptcha.reset();
            $(btn).buttonLoader('stop');

            window.location.href = '/account/regsuccess/' + response.successId;
        }
        else{
            if(response.result == false){
                liliumNotify(response.message,'danger');
                grecaptcha.reset();
                $(btn).buttonLoader('stop');
            }
        }
    });
}

function alertMessage(id, msgClass, msgTitle, msg) {
    $(id).attr("class", "alert").addClass('alert-' + msgClass);
    $(id).html('<strong>' + msgTitle + '</strong> ' + msg);
    $(id).show();
}

function createGameAcc(e){
    $(e).buttonLoader('start');
    $.post('/ajax/CreateGameAccount',$('#create_game_account_form').serialize(),function(data){
        $(e).buttonLoader('stop');
        var response = $.parseJSON(data);
        if (response.result == false) {
            alertMessage('#errorCreateGameAccount', 'danger', 'Error!', response.message);
        } else {
            alertMessage('#errorCreateGameAccount', 'success', 'Success!', 'You have successfully created a game account!');
            setTimeout('window.location.reload()',2000);
        }
    });
}

function changePasswordMA(e){
    $(e).buttonLoader('start');
    $.post('/ajax/ChangePasswordMA',$('#change_password_MA_form').serialize(),function(data){
        $(e).buttonLoader('stop');
        var response = $.parseJSON(data);
        if (response.result == false) {
            alertMessage('#errorChangePasswordMA', 'danger', 'Error!', response.message);
        }
        else {
            alertMessage('#errorChangePasswordMA', 'success', 'Success!', 'Password for MA successfully changed!');
            setTimeout('window.location.reload()',2000);
        }
    });
}

var login;

function setLogin(btn){
    login = $(btn).closest('.grid-header').children('.login_data').children('b').html();
    $('.login_head').html(login);
}

function changePasswordGA(e){
    var data = $('#change_pass').serialize()+'&login='+login;
    $(e).buttonLoader('start');
    $.post('/ajax/ChangePasswordGA',data,function(data){
        $(e).buttonLoader('stop');
        var response = $.parseJSON(data);
        if (response.result == false) {
            alertMessage('#errorChangePasswordGA', 'danger', 'Error!', response.message);
        }
        else {
            alertMessage('#errorChangePasswordGA', 'success', 'Success!', 'The password for your game account has been successfully changed.!');
            setTimeout('window.location.reload()',2000);
        }
    });
}

function recoverPasswordGA(e){
    var data = $('#recover_pass').serialize()+'&login='+login;
    $(e).buttonLoader('start');
    $.post('/ajax/RecoverPasswordGA',data,function(data){
        $(e).buttonLoader('stop');
        var response = $.parseJSON(data);
        if (response.result == false) {
            alertMessage('#errorRecoverPasswordGA', 'danger', 'Error!', response.message);
        }
        else {
            alertMessage('#errorRecoverPasswordGA', 'success', 'Success!', 'New password has been sent to your email!');
            setTimeout('window.location.reload()',2000);
        }
    });
}

var thisModalName = '', thisCharName = '';
editChar = {
    openMainModal : function(charName){
        $('#modal-edit-char').arcticmodal();
        thisCharName = charName;
    },

    openNextModal : function(modalName){
        thisModalName = modalName;
        $('#modal-edit-'+modalName+'').arcticmodal();
    },

    closeNextModal : function(){
        $('#modal-edit-'+thisModalName+'').arcticmodal('close');
    },

    validInput : function(id, regex) {
        var element = document.getElementById(id);
        if (element && element.value.length < 16) {
            var lastValue = element.value;
            if (!regex.test(lastValue))
                lastValue = '';
            setInterval(function() {
                var value = element.value;
                if (value != lastValue) {
                    if (regex.test(value))
                        lastValue = value;
                    else
                        element.value = lastValue;
                }
            }, 10);
        }
    },

    editName : function(){
        $.ajax({
            url: "/ajax/changeCharName",
            type: 'POST',
            data: {'char_name': thisCharName, 'new_char_name': $('#edit-char-name').val()},
            beforeSend: function(){
                $('#ajaxLoader').arcticmodal({
                    closeOnEsc: false,
                    closeOnOverlayClick: false
                });
            },
            success: function(data){
                $('#ajaxLoader').arcticmodal('close');
                var that = $.parseJSON(data);
                if(that.result == true){
                    $.arcticmodal('close');
                    liliumNotify(that.message,'success');
                }else{
                    liliumNotify(that.message,'danger');
                }
            }
        });
    },

    editGender : function(){
        $.ajax({
            url: "/ajax/changeCharGender",
            type: 'POST',
            data: {'request': 'changeCharGender', 'char_name': thisCharName},
            beforeSend: function(){
                $('#ajaxLoader').arcticmodal({
                    closeOnEsc: false,
                    closeOnOverlayClick: false
                });
            },
            success: function(data){
                $('#ajaxLoader').arcticmodal('close');
                var that = $.parseJSON(data);
                if(that.result == true){
                    $.arcticmodal('close');
                    liliumNotify(that.message,'success');
                }else{
                    liliumNotify(that.message,'danger');
                }
            }
        });
    }
};

$(function(){
    $('.gender-button').on('click',function(){
        $('.gender-button').removeClass('active');
        $(this).addClass('active');

        editChar.validInput('edit-char-name', /^[a-zA-Z0-9]*$/);
        editChar.validInput('edit-char-transfer', /^[a-zA-Z0-9]*$/);
        editChar.validInput('edit-clan-name', /^[a-zA-Z0-9]*$/);
    });
});