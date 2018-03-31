function coinsTarif(elem){
    $('#coins').val(Math.floor($(elem).val() * coinsFromEuro));
}

function sendCoins(elem){
    $(elem).buttonLoader('start');
    var data = $('#coinsForm').serialize();
    $.post('/ajax/globalcoins/', data, function(data){
        var response = $.parseJSON(data);
        if(response.result == true){
            notify('success','','ok');
            window.location.reload();
        }
        else{
            liliumNotify(response.message,'danger');
            $(elem).buttonLoader('stop');
        }
    });
}

$(document).ready(function(){
    $('#euro').keypress(function(e) {
        if (!(e.which==8 || e.which==44 ||e.which==45 ||e.which==46 ||(e.which>47 && e.which<58))) return false;
    });
    $('#euro').keyup(function(e) {
        coinsTarif(this);
    });
});