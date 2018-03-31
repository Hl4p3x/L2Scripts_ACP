$(function(){
    $("head").append($("<link rel='stylesheet' href='/templates/kertas/assets/plugins/liliumEffect/liliumEffect.css' type='text/css' />"));
});
var keys = {37: 1, 38: 1, 39: 1, 40: 1};

function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
        e.preventDefault();
    e.returnValue = false;
}

function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
        preventDefault(e);
        return false;
    }
}

function disableScroll() {
    if (window.addEventListener) // older FF
        window.addEventListener('DOMMouseScroll', preventDefault, false);
    window.onwheel = preventDefault; // modern standard
    window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
    window.ontouchmove  = preventDefault; // mobile
    document.onkeydown  = preventDefaultForScrollKeys;
}

function enableScroll() {
    if (window.removeEventListener)
        window.removeEventListener('DOMMouseScroll', preventDefault, false);
    window.onmousewheel = document.onmousewheel = null;
    window.onwheel = null;
    window.ontouchmove = null;
    document.onkeydown = null;
}


function liliumNotify(text,type){
    var textArray = text.split('<br>'),
		liliumWpElHTML = '<div class="lilium-notify-wp"></div>',
		liliumContElHTML = '<div class="liliumEff-notify-cont"></div>';

	if(!$('*').is('.liliumEff-wp')){
		$('body').append(liliumWpElHTML);
        for(var i = 0; i<textArray.length; i++){
            $('body').append('<div class="liliumEff-notify-box liliumEff-top-'+i+'"><div class="lilium-notify-text">'+textArray[i]+'</div><div class="lilium-notify-border"></div></div>');
        }
    }
    if(!$('*').is('.liliumEff-notify-cont')){
        $('body').append(liliumContElHTML);
	}
	var	liliumBoxEl = $('.liliumEff-notify-box'),
		liliumTextEl = $('.lilium-notify-text'),
		liliumWpEl = $('.lilium-notify-wp'),
		liliumCont = $('.liliumEff-notify-cont'),
		liliumWidth = '', liliumHeight = '', liliumLastEl = '', liliumElPos = '', liliumBorder = '';
    disableScroll();
    liliumWpEl.show().animate({'opacity': '1'});
    liliumBoxEl.each(function(index, domEle){
        liliumBoxEl.show();
        liliumWidth = liliumTextEl.eq(index).width();
        liliumHeight = liliumTextEl.eq(index).height();
        $('.lilium-notify-border').eq(index).width(liliumWidth).height(liliumHeight);

        setTimeout(function(){
            liliumCont.append('<div class="lilium-notify-el lilium-'+type+'">'+textArray[index]+'</div>');
        },2000);

        setTimeout(function(){
            liliumElPos = $('.lilium-notify-el').eq(index).position();
            liliumWidth = $('.lilium-notify-text').eq(index).width();
            $('.liliumEff-notify-box').eq(index).css({'position': 'fixed', 'left': 'auto','margin': 'auto'}).animate({
                'top': liliumElPos.top+20,
                'right': '20px',
                'width': liliumWidth,
                'font-size': '20px',
                'padding': '10px 15px'
            }, 300);
            setTimeout(function(){
                $('.liliumEff-notify-box').eq(index).css({'text-align': 'right', 'width': '200px', 'font-weight': 'normal', 'box-sizing': 'border-box'});
                $('.liliumEff-notify-box').eq(index).remove();
                $('.lilium-notify-el').eq(index).css('color','white');
                liliumWpEl.animate({'opacity': '0'});
                enableScroll();
                setTimeout(function(){
                    liliumWpEl.remove();
                },800);
                liliumBoxEl.eq(index).remove();
            }, 280);

            setTimeout(function(){
                $('.lilium-notify-el').eq(index).addClass('liliumElRemoved');
                setTimeout(function(){
                    $('.lilium-notify-el').remove();
                },800);
            },3000);

        }, 3000);
    });

}
