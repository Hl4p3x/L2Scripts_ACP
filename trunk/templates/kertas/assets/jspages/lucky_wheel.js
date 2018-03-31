
var charName = '';

$('#prize-block').jScrollPane({
    verticalDragMinHeight: 50,
    verticalDragMaxHeight: 200,
    horizontalDragMinWidth: 50,
    horizontalDragMaxWidth: 200
});
$("html, body").animate({ scrollTop: 120 }, 200);
function ShowWheelBtn(){
    if($('#char_name').val()=='Choose character' || $('#char_name').val()=='Выбрать персонажа' || $('#char_name').val().length == 0) {
        $('.wheel-btn').hide();
    } else {
        $('.wheel-btn').show();
    }
}
function WheelStart(){
    if($('#char_name').val()=='Choose character' || $('#char_name').val()=='Выбрать персонажа' || $('#char_name').val().length == 0){
        notify('danger','','Select character');
    } else {
        $('.start-popup').hide();
        $('.wheel-popup').fadeOut(800);
        charName = $('#char_name').val();
        $('.wheel-char').text(charName);
        $('.wheel-text').fadeIn(200);
    }
}
function ContinueGame(){
    $('.start-popup').hide();
    $('.wheel-popup').fadeOut(500);
}
function spin() {
	console.log("spinning");
	console.log(charName);
	if(charName=='Choose character' || charName=='Выбрать персонажа' || charName.length == 0){
        	notify('danger','','Select character');
        	$('.start-popup').show();
        	$('.wheel-popup').fadeIn(800);
    	}
	var nowBalance = $('.wheel-balance').find('span').text();
	if(nowBalance >= SPIN_PRICE) {
		$('.wheel-gear').removeClass('gospin3');
		setTimeout(function() {
			$('.wheel-gear').addClass('gospin');
			$('.wheel-buttonoff').show();
			$.ajax('/luckywheel/spin',{
				type: 'POST',
				data: {'char_name':charName},
				success: function(datas){
					var resultDelivery = $.parseJSON(datas);
					if(resultDelivery.result == false){
						notify('danger','',resultDelivery.message);
                        $('.wheel-popup').show();
                        $('.error-popup').fadeIn(500);
					}else {
						setTimeout(function () {
							var Prize;
							Prize = JSON.parse(datas);
							$('.wheel-gear').removeClass('gospin');
							setTimeout(function () {
								$('.wheel-gear').addClass('gospin2');
							}, 50);
							setTimeout(function () {
								$('.gear-item1').find('.wheel-prize').html('<img src="' + Prize.message.item_img + '" alt="">');
								$('.gear-item1').find('.wheel-prize-name').text(Prize.message.item_name.name + '' + Prize.message.item_name.sa + ' x' + Prize.message.count_item);
								if ($('.gear-item1').find('.wheel-prize-name').text() == $('.gear-item2').find('.wheel-prize-name').text()) {
									var tempName = $('.gear-item2').find('.wheel-prize-name').text();
									var tempImg = $('.gear-item2').find('.wheel-prize').html();
									$('.gear-item2').find('.wheel-prize-name').text($('.gear-item7').find('.wheel-prize-name').text());
									$('.gear-item2').find('.wheel-prize').html($('.gear-item7').find('.wheel-prize').html());
									$('.gear-item7').find('.wheel-prize-name').text(tempName);
									$('.gear-item7').find('.wheel-prize').html(tempImg);
								}
							}, 1000);
							setTimeout(function () {
								$('.wheel-gear').removeClass('gospin2');
								$('.wheel-glow-effect').fadeIn(100).delay(50).fadeOut(100).delay(50).fadeIn(100).delay(50).fadeOut(100).delay(50).fadeIn(100).delay(50).fadeOut(100);
								setTimeout(function () {
									$('.wheel-buttonoff').hide();
									$('.win-item-name').text(Prize.message.item_name.name + '' + Prize.message.item_name.sa + ' x' + Prize.message.count_item);
									$('.wheel-popup').show();
									$('.win-popup').fadeIn(500);
									$('.win-item-img').html('<img src="' + Prize.message.item_img + '" alt="">');
									ReverseItems();
								}, 1000);
							}, 4000);
						}, 5000);
					}
					WheelReduction(SPIN_PRICE);
					$('#cur_balance').text(resultDelivery.balance);
				}
			});
		}, 100);
	}else{
		$('.win-popup').fadeOut();
		$('.wheel-popup').show();
		$('.no-money-popup').fadeIn(500);
		console.log('Balance - '+nowBalance+' Spin Price - '+SPIN_PRICE);
	}
}
$(function(){
    readyLoadImg = [ false, false, false, false ];
    initProcess();
    function initProcess() {
        $( "<img />" ).hide()
            .load( function() {
                readyLoadImg[ 0 ] = true;
                $( this ).remove();
            } )
            .attr( "src", function() {
                var imgUrl = $( ".wheel-gear" ).css( "background-image" );
                return imgUrl.substring( 4, imgUrl.length - 1 ).replace( /\"/g, "" ).replace( /\'/g, "" );
            } );
        $( "<img />" ).hide()
            .load( function() {
                readyLoadImg[ 1 ] = true;
                $( this ).remove();
            } )
            .attr( "src", function() {
                var imgUrl = $( ".wheel-main" ).css( "background-image" );
                return imgUrl.substring( 4, imgUrl.length - 1 ).replace( /\"/g, "" ).replace( /\'/g, "" );
            } );
        $( "<img />" ).hide()
            .load( function() {
                readyLoadImg[ 2 ] = true;
                $( this ).remove();
            } )
            .attr( "src", function() {
                var imgUrl = $( ".wheel-bg" ).css( "background-image" );
                return imgUrl.substring( 4, imgUrl.length - 1 ).replace( /\"/g, "" ).replace( /\'/g, "" );
            } );
        $( "<img />" ).hide()
            .load( function() {
                readyLoadImg[ 3 ] = true;
                $( this ).remove();
            } )
            .attr( "src", function() {
                var imgUrl = $( ".wheel-button" ).css( "background-image" );
                return imgUrl.substring( 4, imgUrl.length - 1 ).replace( /\"/g, "" ).replace( /\'/g, "" );
            } );
        triggerIntro();
    }
    function triggerIntro() {
        setTimeout( function() {
            var cs = "";
            for( var i = 0, l = readyLoadImg.length; i < l; i++ ) {
                cs += readyLoadImg[ i ] + ",";
            }
            if( cs == "true,true,true,true," ) {
                aniPlayIntro();
            } else {
                triggerIntro();
            }
        }, 500 );
    }
    function aniPlayIntro() {
        $('.wheel-load').fadeOut(500);
    }
});

function ReverseItems(){
    var i = 2;
    var tempName = '';
    var tempImg = '';
    var num=1;
    while(i<=12){
        num = Math.floor(Math.random()*10)+2;
        tempName = $('.gear-item'+i).find('.wheel-prize-name').text();
        tempImg = $('.gear-item'+i).find('.wheel-prize').html();
        $('.gear-item'+i).find('.wheel-prize-name').text( $('.gear-item'+num).find('.wheel-prize-name').text() );
        $('.gear-item'+i).find('.wheel-prize').html( $('.gear-item'+num).find('.wheel-prize').html() );
        $('.gear-item'+num).find('.wheel-prize-name').text( tempName );
        $('.gear-item'+num).find('.wheel-prize').html( tempImg );
        i++;
    }
}
function WheelReduction(summ){
    var i = 0;
    (function() {
        if (i < summ) {
            var balance=$('.wheel-balance').find('span').text();
            $('.wheel-balance').find('span').text(parseInt(balance)-1);
            i++;
            setTimeout(arguments.callee, 30);
        }
    })();
}