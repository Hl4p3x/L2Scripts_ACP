/**
 * Created by пользователь on 21.05.2015.
 */


/*
* pagination news
* */


function ajaxPagination(url)
{
    $.get(url,function(data){
        $('.container').html(data);
    });
    return false;
}


function ajaxCheckLogin(){
    var prefix = $('#prefix').val().split(' ')[1]; // $100
    $.get('/ajax/checklogin/' + prefix + $('#user_id').val(),function(data){
        var response = jQuery.parseJSON(data);
        if(!response.result){
            $('#login-error').children('p').html(response.message);
            $('#login-error').show();
            setTimeout("$('#login-error').hide(200)",2000);
        }
    });
}

function checkPassword(){
    var pass = $('#password').val();
    if ( pass.length < 8 ) {
        $('#pass-error').children('p').html('password min size 8');
        $('#pass-error').show();
        setTimeout("$('#pass-error').hide(200)",2000);
    }
}
function checkConfirmPassword(){
    var pass = $('#password').val();
    var confirm_pass = $('#password_confirm').val();
    if ( pass !=  confirm_pass ) {
        $('#cpass-error').children('p').html('Password confirm must be as same password');
        $('#cpass-error').show();
        setTimeout("$('#cpass-error').hide(200)",2000);
    }
}

function ajaxCheckEmail(){

    $.post('/ajax/checkemail/',{email: $('#email').val()},function(data){
        var response = jQuery.parseJSON(data);
        if(!response.result){
            $('#email-error').children('p').html(response.message);
            $('#email-error').show();
            setTimeout("$('#email-error').hide(200)",2000);
        }
    });
}


function ajaxRegistration(){
    $.post('/ajax/registration',{
        login: $('#user_id').val(),
        password: $('#password').val(),
        password_confirm: $('#password_confirm').val(),
        email: $('#email').val(),
        referral: $('#referral').val(),
        captcha: $('#captcha').val()
    },function(data){
        var response = $.parseJSON(data);
        //alert(response.message);
        $('#reg_mes span').html(response.message);
        $('#ModalReg').reveal();
        if(response.result === true){
            $('#reg_form').trigger( 'reset' );
            ajaxGeneratePrefix();
            $.get('/ajax/renewcaptcha',function(data){
                var id = Math.floor(Math.random()*10000);
                $("img.captcha").attr("src","/captcha/default?id="+id);
            })
            window.location ='/site/getfile/';
        }
    });
}

function ajaxRegistrationFromAccount(){
    $.post('/ajax/registration',{
        login: $('#user_id').val(),
        password: $('#password').val(),
        password_confirm: $('#password_confirm').val(),
        email: $('#email').val(),
        referral: $('#referral').val(),
        captcha: $('#captcha').val()
    },function(data){
        var response = $.parseJSON(data);
        //alert(response.message);
        $('#reg_mes').html(response.message);
        $('#modal_error').modal('show');
        if(response.result === true){
            $('#reg_form').trigger( 'reset' );
            ajaxGeneratePrefix();
            $.get('/ajax/renewcaptcha',function(data){
                var id = Math.floor(Math.random()*10000);
                $("img.captcha").attr("src","/captcha/default?id="+id);
            })
            window.location ='/site/getfile/';
        }
    });
}

function ajaxGeneratePrefix(){
    $.get('/ajax/generateprefix/',function(data){
        $('#prefix').val('Prefix: '+ data);
    });
}

function ajaxLogin(){
    $.ajax({
        url: '/ajax/login',
        type: 'POST',
        data: {
            email: $('#email').val(),
            password: $('#password').val(),
            captcha: $('#captcha').val(),
            server_id: $('#server_id').val(),
            remember: $('#remember').val()
        },
        success: function (data) {
            var response = $.parseJSON(data);
            if (response.result === true) {
                document.location.href = "/account/index";
            }
            else {
                $('#errorsMessage').html(response.message);
            }
        }
    });
}

function ajaxSendVerificationCode(){
    $.post('/ajax/sendverificationcode',{email:$('#email').val()},function(data){
        var response = $.parseJSON(data);
        if(response.result === true){
            $('#email').attr('disabled', 'disabled');
            $("#code").removeAttr("disabled");
            $("#block-captcha").show();
            $('#btn-SCode').hide();
            $('#btn-VCode').show();
            $('#errorsMessage').html('');
        }
        else{
            $('#errorsMessage').html(response.message);
        }
    });
}

function ajaxCheckCode(){
    $.post('/ajax/checkcode',{code: $('#code').val(),captcha:$('#captcha').val()},function(data){
        var response = $.parseJSON(data);
        if(response.result === true){
            $('#btn-VCode').hide();
            $('#btn-Pass').show();
            $('#password').show();
            $('#password_confirm').show();
            $('#errorsMessage').html('');
        }
        else{
            $('#errorsMessage').html(response.message);
        }
    });
}

function ajaxSaveNewPassword(){
    $.post('/ajax/changepassword',{password: $('#password').val(), password_confirm:$('#password_confirm').val()},function(data){
        var response = $.parseJSON(data);
        if(response.result === true){
            liliumNotify(response.message,'success');
            $('#errorsMessage').html('');
        }
        else{
            liliumNotify(response.message,'danger');
        }
    });
}

function ajaxServerReload(){
    $.post('/admin/servers',{request:'reload'},function(data){
        $('#global-content').html(data);
    });
}

function ajaxServerDelete(id){
    if (confirm("Delete?")) {
        $.post('/admin/servers', {request: 'delete', id: id});
        ajaxServerReload();
    }
}

var updServId; //глобальная переменная для передачи ид при апдейте записи

function showUpdServerForm(btn){
    updServId = $(btn.closest('tr')).children('td[serv-data = "id"]').html();
    $('#serv_id_input').val($(btn.closest('tr')).children('td[serv-data = "server_id"]').html());
    $('#serv_name_input').val($(btn.closest('tr')).children('td[serv-data = "server_name"]').html());
    $('#chronicle_input').val($(btn.closest('tr')).children('td[serv-data = "chronicle"]').html());
    $('#rate_input').val($(btn.closest('tr')).children('td[serv-data = "rate"]').html());
    $('#max_online_input').val($(btn.closest('tr')).children('td[serv-data = "max_online"]').html());
    $('#add_serv').hide(200);
    setTimeout("$('#upd_serv').show(200)",200);
}

function showAddServerForm(){
    $('#upd_serv').hide(200);
    setTimeout("$('#add_serv').show(200)",200);
}

function ajaxServerAdd(){
    $.post('/admin/servers',{
        request: 'add',
        server_id:$('#a_serv_id_input').val(),
        server_name: $('#a_serv_name_input').val(),
        chronicle: $('#a_chronicle_input').val(),
        rate: $('#a_rate_input').val(),
        max_online: $('#a_max_online_input').val()
    },function(data){
        $('#global-content').html(data);
    });
}

function ajaxServerUpdate(){
    $.post('/admin/servers',{
        request: 'update',
        id: updServId,
        server_id:$('#serv_id_input').val(),
        server_name: $('#serv_name_input').val(),
        chronicle: $('#chronicle_input').val(),
        rate: $('#rate_input').val(),
        max_online: $('#max_online_input').val()
    },function(data){
        $('#global-content').html(data);
    });
}

$(function(){
	/*
	обновление капчи по аяксу
	 */
	$('.captch-image').click(function(){
	    $.get('/ajax/renewcaptcha',function(data){
	        var id = Math.floor(Math.random()*1000000);
	        $("img.captcha").attr("src","/captcha/default?id="+id);
	    })
	});
});