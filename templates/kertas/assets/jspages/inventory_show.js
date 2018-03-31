
var img_grade_src ='/media/grade/'; // /media/grade/a-grade.jpg
$(document).ready(function(){
    $('.item_hover').each(function(){
        if ($(this).attr('item-auctionable') == 'false') {
            $(this).css("opacity", 0.5);
            $(this).parent().css("background", "black");
        }
    });
    $('.item_hover').hover(function(){
        var gradeArray = ['a','b','c','d','s','s80','r','r95','r99'];
        var top = $(this).offset().top + 25;
        var left = $(this).offset().left + 25;
        var grade = $(this).attr('item-grade');
        $('.item_enchant').text($(this).attr('item-enchant'));
        $('.item_name').text($(this).attr('item-name'));
        $('.item_ls').text($(this).attr('item-sa'));
        $('#item_grade').appendTo(grade);
        if(Number($(this).attr('item-amount')) == 1){
            $('.item_amount').text('');
        }
        else{
            $('.item_amount').text($(this).attr('item-amount'));
        }
        if(gradeArray.indexOf(grade) != -1 &&  $('#grade_img').css('display') =='none'){
            $('#grade_img').attr('src', img_grade_src + grade + '-grade.jpg').show();
        }
        $('#item_title').css({'top':top,'left':left}).show();
    },function(){
        $('#grade_img').hide();
        $('#item_title').hide();
    });

    $(document).on('click',function(event){
        if( $(event.target).closest(".item_hover").length && $(event.target).attr('item-auctionable') == 'true'){
            $('#item_title').hide();
            var thisItemId = $(event.target).attr('item-id'),
                thisItemCountReal = $(event.target).attr('item-amount-real'),
                thisItemCount = $(event.target).attr('item-amount'),
                thisItemEnchant = $(event.target).attr('item-enchant'),
                thisItemType = $(event.target).attr('item-type'),
                thisItemSa = $(event.target).attr('item-sa'),
                thisItemAdmission = $(event.target).attr('item-is-auction'),
                thisWidth = $('#item-actions').width(),
                top = $(event.target).offset().top + 37,
                left = $(event.target).offset().left - (thisWidth / 2) + 16;
            $('#item-actions').css({'top':top,'left':left}).show();
            $('.send-auction').attr({'item-id': thisItemId, 'item-amount': thisItemCount, 'item-amount-real': thisItemCountReal, 'item-is-auction': thisItemAdmission, 'item-sa': thisItemSa, 'item-enchant': thisItemEnchant, 'item-type': thisItemType});
        }else{
            $('#item-actions').hide();
        }
    });

    $('.send-auction').on('click',function(){
        var thisItemId = $(this).attr('item-id'),
            thisItemCount = $(this).attr('item-amount');

            if (thisItemCount > 1){
                $('.modal-l2-count').show();
                $('.modal-l2-auctioned-items').css('height','230px');
            }
            $('.modal-l2-auctioned-items').show();
            $('.modal-l2-auctioned-items .modal-l2-confirm .l2-button').attr({'item-id': thisItemId, 'item-amount': thisItemCount});
    });

    $('.modal-l2-cancel a').on('click', function(e){
        e.preventDefault();
        $('.modal-l2-auctioned-items').hide().css('height','190px');
        $('.modal-l2-count').hide();
        $('.modal-l2-no-auctioned').hide();
        $('.modal-l2-auctioned-items input').val('');
    });

    $('.modal-l2-confirm a').on('click', function(e){
        var thisItemId = $(this).attr('item-id'),
            thisItemCount = $(this).attr('item-amount'),
            startPrice = $('.modal-l2-price input').val(),
            priceBuy = $('.modal-l2-priceBuyNow input').val(),
            priceStep = $('.modal-l2-priceStep input').val(),
            countAu = $('.modal-l2-count input').val(),
            renewal = $('.modal-l2-renewal input').is(':checked'),
            preiod = $('.modal-l2-period input').val();
        if(countAu == ''){
            countAu = 1;
        }
        if(countAu > thisItemCount){
            countAu = thisItemCount;
        }

        $.ajax('/auction/create',{
            type: 'POST',
                data: {'renewal': renewal, 'item_id': thisItemId, 'start_price': startPrice, 'price_buy': priceBuy, 'price_step': priceStep, 'count': countAu, 'period': preiod},
            beforeSend: function(){
                $('#ajaxLoader').arcticmodal({
                    closeOnEsc: false,
                    closeOnOverlayClick: false
                });
            },
            success: function(data){
                $('#ajaxLoader').arcticmodal('close');
                var response = $.parseJSON(data);
                if(response.result == true){
                    if(thisItemCount > 1){
                        $('img[item-id='+thisItemId+']').attr('item-amount',Number(thisItemCount)-Number(countAu));
                    }else{
                        $('img[item-id='+thisItemId+']').remove();
                    }
                    liliumNotify(response.message,'success');
                }else{
                    liliumNotify(response.message,'danger');
                }
            }
        });
        e.preventDefault();
        $('.modal-l2-auctioned-items').hide();
    });

    $('.modal-l2-period input').on('keyup', function(e){
        if($(this).val() > 7){
            $(this).val('7');
        }
    });
});