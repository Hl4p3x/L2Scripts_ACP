$(function(){
   var  filterTopRow = $('.auction-filter-top'),
        filterLeftRow = $('.type-filter'),
        navLeftRow = $('.nav-auction-page'),
        auctionFilterLeft = $('.auction-tool-left'),
        auctionTableHead = $('.auction-table-head'),
        thisAuctionHeight = $('.auction-tool-center').height(),
        thisAuctionOffset = $('.auction-tool-center').offset();
    changeSizeBlock();
    $(window).resize(function(){
        changeSizeBlock();
    });

    $('.auction-table-timer').each(function(){
        auction_timer($(this).data('auctionEnd'), $(this));
    });

    $('.enchant-filter input').on('keypress', function(e){
        e = e || event;
        if (e.ctrlKey || e.altKey || e.metaKey) return;

        var chr = getChar(e);
        if (chr == null) return;

        if (chr < '0' || chr > '9') {
            return false;
        }
    });

    $('input[name=enchant]').on('keyup', function(e){
        if($(this).val() > 25){
            $(this).val('25');
        }
    });

    function getChar(event) {
        if (event.which == null) {
            if (event.keyCode < 32) return null;
            return String.fromCharCode(event.keyCode);
        }

        if (event.which != 0 && event.charCode != 0) {
            if (event.which < 32) return null;
            return String.fromCharCode(event.which);
        }

        return null;
    }

    if(thisAuctionHeight > 200){
        $(window).scroll(function(){
            if($(this).scrollTop() > 94 && $(this).scrollTop() < thisAuctionOffset.top+thisAuctionHeight-128){
                filterTopRow.css({'position': 'fixed','top': '60px'});
                auctionTableHead.css({'position': 'fixed','top': '100px'});
            }else{
                filterTopRow.css({'position': 'relative','top': '0'});
                auctionTableHead.css({'position': 'relative','top': '0'});
            }
            if($(this).scrollTop() > thisAuctionOffset.top+thisAuctionHeight-128-86){
                filterTopRow.css({'position': 'relative','top': thisAuctionHeight-128-86+'px'});
                auctionTableHead.css({'position': 'relative','top': thisAuctionHeight-128-86+'px'});
            }

            if($(this).scrollTop() > 124 && auctionFilterLeft.height() > 500){
                filterLeftRow.css({'position': 'fixed','top': '30px'});
                navLeftRow.css({'position': 'fixed','top': '245px'})
            }else{
                filterLeftRow.css({'position': 'relative','top': '0'});
                navLeftRow.css({'position': 'relative','top': '0'})
            }
        });
    }

    function changeSizeBlock(){
        var thisAuctionWidth = $('.auction-tool-center').width(),
            thisAuctionHeight = $('.auction-tool-center').height();
        filterTopRow.css('width',thisAuctionWidth);
        auctionTableHead.css('width',thisAuctionWidth);
        auctionFilterLeft.css('height',thisAuctionHeight);
    }

    $('.grade-filter-el').on('click',function(e){
        e.preventDefault();
        var nowGrade = $(this),
            nowType = $('.type-filter-el.active');
        $('.grade-filter-el').removeClass('active');
        $(this).addClass('active');
        auction_filters(nowType, nowGrade);
    });

    $('.type-filter-el').on('click',function(e){
        e.preventDefault();
        var nowType = $(this),
            nowGrade = $('.grade-filter-el.active');
        $('.type-filter-el').removeClass('active');
        $(this).addClass('active');
        auction_filters(nowType, nowGrade);
    });

    $('.enchant-filter input').on('change',function(e){
        var nowType = $('.type-filter-el.active'),
            nowGrade = $('.grade-filter-el.active');
            auction_filters(nowType, nowGrade);
    });

    $('.sort-filter a').on('click',function(e){
        e.preventDefault();
        $('.sort-filter a').removeClass('active');
        $(this).addClass('active');
        if($(this).hasClass('aucstat-sort-filter')){
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {'request': 'pager', 'order': $(this).data('sortType'), 'order_direction': 1},
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
                        $('.auction-table-body').html(that.data);
                    }
                }
            });
        }else{
            var nowType = $('.type-filter-el.active'),
                nowGrade = $('.grade-filter-el.active');
            auction_filters(nowType, nowGrade);
        }
    });

    $('.aucstat-date-filter').on('change',function(){
        var startDate = $('.aucstat-date-filter[data-date-filter="start"]').val(),
            endDate = $('.aucstat-date-filter[data-date-filter="end"]').val();
        $.ajax({
            type: 'POST',
            data: {'request': 'pager', 'start_period': startDate, 'end_period': endDate, 'order': $('.sort-filter a.active').data('sortType'), 'order_direction': 1},
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
                    $('.auction-table-body').html(that.data);
                    $('.total-sum-price').html(that.iSummPrise);
                }
            }
        });
    });

    $('#nav-auction-status').on('change',function(){
        var nowType = $('.type-filter-el.active'),
            nowGrade = $('.grade-filter-el.active'),
            activeType = nowType.data('aucTypeEl'),
            enchantFilter = $('.enchant-filter input').val(),
            sortType = $('.sort-filter a.active').data('sortType'),
            status = $(this).val(),
            nameChar = $('#find-seller-char').val(),
            lotID = $('#find-lot-id').val(),
            itemName = $('#find-item-name').val(),
            activeGrade = nowGrade.data('aucGradeEl');
        $.ajax({
            type: 'POST',
            data: {'request': 'pager', 'id': lotID, 'item_name': itemName, 'enchant': enchantFilter, 'order': sortType, 'grade': activeGrade, 'item_type': activeType, 'page': currentAucPage, 'status': status,'seller': nameChar},
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
                    $('.auction-table-body').html(that.data);
                    $('.auction-table-timer').each(function(){
                        auction_timer($(this).data('auctionEnd'), $(this));
                    });
                }
            }
        });
    });

    $('.name-filter-auction').on('change',function(){
        var nowType = $('.type-filter-el.active'),
            nowGrade = $('.grade-filter-el.active'),
            activeType = nowType.data('aucTypeEl'),
            enchantFilter = $('.enchant-filter input').val(),
            sortType = $('.sort-filter a.active').data('sortType'),
            status = $('#nav-auction-status').val(),
            nameChar = $('#find-seller-char').val(),
            lotID = $('#find-lot-id').val(),
            itemName = $('#find-item-name').val(),
            activeGrade = nowGrade.data('aucGradeEl');
        $.ajax({
            type: 'POST',
            data: {'request': 'pager','id': lotID, 'item_name': itemName, 'enchant': enchantFilter, 'order': sortType, 'grade': activeGrade, 'item_type': activeType, 'page': currentAucPage, 'status': status, 'seller': nameChar},
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
                    $('.auction-table-body').html(that.data);
                    $('.auction-table-timer').each(function(){
                        auction_timer($(this).data('auctionEnd'), $(this));
                    });
                }
            }
        });
    });

    var nowWinLotChar = $('#win-char').val();
    $('#win-char').on('change',function(){
        nowWinLotChar = $('#win-char').val();
    });

    $('.win-lot-button').on('click',function(){
        var lotId = $(this).attr('data-auction-lot-ld');
        $.ajax('/auction/getlot',{
            type: 'POST',
            data: {'lot_id': lotId, 'char_name': nowWinLotChar},
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
                    $('.auction-table-row[data-auction-lot-id="'+lotId+'"]').remove();
                    liliumNotify(that.message,'success');
                    $('#win-item-auction').arcticmodal('close');
                }else{
                    liliumNotify(that.message,'danger');
                }
            }
        });
    });

    $('.set-lot-modal').on('click',function(e){
        e.preventDefault();
        $('#modal-set-lot .modal-l2-confirm a').attr('href','/character/inventory/'+$('#set-lot-char').val());
        $('#modal-set-lot').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false
        });
    });

    $('#set-lot-char').on('change',function(){
        $('#modal-set-lot .modal-l2-confirm a').attr('href','/character/inventory/'+$(this).val());
    });

    $('#modal-buy-now .modal-l2-confirm a').on('click',function(e){
        e.preventDefault();
        var lotId = $(this).attr('data-auction-lot-ld');
        $.ajax('/auction/buynow', {
            type: 'POST',
            data: {'lot_id': lotId},
            beforeSend: function(){
                $('#ajaxLoader').arcticmodal({
                    closeOnEsc: false,
                    closeOnOverlayClick: false
                });
            },
            success: function(data){
                $('#ajaxLoader').arcticmodal('close');
                var response = $.parseJSON(data);
                if (response.result == true){
                    thisItemRow.addClass('end-auction');
                    thisItemRow.find('.auction-table-timer').removeClass('auction-table-timer').addClass('auction-table-timer-end').html('0 day 00:00:00');
                }else{
                    liliumNotify(response.message,'danger');
                }
                $('#cur_balance').text(response.balance);
            }
        });
        $('#modal-buy-now').arcticmodal('close');
    });

    $('#modal-pace-ber .modal-l2-confirm a').on('click',function(e){
        e.preventDefault();
        var lotId = $(this).attr('data-auction-lot-ld');
        $.ajax('/auction/bid', {
            type: 'POST',
            data: {'lot_id': lotId},
            beforeSend: function(){
                $('#ajaxLoader').arcticmodal({
                    closeOnEsc: false,
                    closeOnOverlayClick: false
                });
            },
            success: function(data){
                $('#ajaxLoader').arcticmodal('close');
                var response = $.parseJSON(data);
                if (response.result == true){
                    thisItemRow.find('.auction-table-nowPrice span').text(Number(thisItemRow.find('.auction-table-nowPrice span').text()) + Number(thisItemRow.find('.auction-table-stepPrice span').text()));
                    thisItemRow.find('.auction-button[data-auction-btn-type=place_bet]').addClass('my-bet').removeAttr('onclick');
                    liliumNotify(response.message,'success');
                }else{
                    liliumNotify(response.message,'danger');
                }
                $('#cur_balance').text(response.balance);
            }
        });
        $('#modal-pace-ber').arcticmodal('close');
    });

    $('#modal-get-lot .modal-l2-confirm a').on('click',function(e){
        e.preventDefault();
        var lotId = $(this).attr('data-auction-lot-ld');
        $.ajax('/auction/getlot',{
            type: 'POST',
            data: {'lot_id': lotId},
            beforeSend: function(){
                $('#ajaxLoader').arcticmodal({
                    closeOnEsc: false,
                    closeOnOverlayClick: false
                });
            },
            success: function(data){
                $('#ajaxLoader').arcticmodal('close');
                var response = $.parseJSON(data);
                if (response.result == true){
                    liliumNotify(response.message,'success');
                    thisItemRow.remove();
                }else{
                    liliumNotify(response.message,'danger');
                }
            }
        });
        $('#modal-get-lot').arcticmodal('close');
    });

    $('.modal-l2-cancel a').on('click',function(e){
        e.preventDefault();
        $.arcticmodal('close');
    });


});

function auction_timer(date_left, that){
    date_left--;
    if (date_left > 0){
        var day = parseInt(date_left/(60*60*24)),
            hour = parseInt(date_left/(60*60))%24,
            minut = parseInt(date_left/(60))%60,
            sec = parseInt(date_left)%60;
        day = day.toString();
        hour = hour.toString();
        minut = minut.toString();
        sec = sec.toString();
        if(hour<10){
            hour = '0'+hour;
        }
        if(minut<10){
            minut = '0'+minut;
        }
        if(sec<10){
            sec = '0'+sec;
        }
        if(day == 0 && hour < 1){
            that.closest('.auction-table-row').addClass('low-time-auction');
        }
        $(that).html(day+' day '+hour+':'+minut+':'+sec);
        setTimeout(function(){
            auction_timer(date_left, that);
        },1000);
    }else{
        that.closest('.auction-table-row').addClass('end-auction');
        $(that).html('0 day 00:00:00');
    }

}

function navigationAuc(numberPage){
    var nowType = $('.type-filter-el.active'),
        nowGrade = $('.grade-filter-el.active');
    currentAucPage = numberPage;
    auction_filters(nowType, nowGrade);
}

function auction_filters(type_el, grade_el){
    var activeType = type_el.data('aucTypeEl'),
        enchantFilter = $('.enchant-filter input').val(),
        sortType = $('.sort-filter a.active').data('sortType'),
        activeGrade = grade_el.data('aucGradeEl');
    $.ajax({
        type: 'POST',
        data: {'request': 'pager','enchant': enchantFilter, 'order': sortType, 'grade': activeGrade, 'item_type': activeType, 'page': currentAucPage},
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
                $('.auction-table-body').html(that.data);
                $('.auction-table-timer').each(function(){
                    auction_timer($(this).data('auctionEnd'), $(this));
                });
            }
        }
    });
}
var thisItemRow,thisAuctionLogId,thisItemName,thisItemImg;

function buyAuction(that){
        thisItemRow = $(that).closest('.auction-table-row');
        thisAuctionLogId = thisItemRow.data('auctionLotId');
        thisItemName = thisItemRow.find('.auction-table-name').html();
        thisItemImg = thisItemRow.find('.auction-table-img').html();
    var thisAction = $(that).data('auctionBtnType'),
        thisItemBuy = thisItemRow.find('.auction-table-buyPrice').text(),
        thisItemCost = Number(thisItemRow.find('.auction-table-nowPrice span').text()) + Number(thisItemRow.find('.auction-table-stepPrice span').text());
    $('.modal-l2-confirm a').attr('data-auction-lot-ld',thisAuctionLogId);
    $('.win-lot-button').attr('data-auction-lot-ld',thisAuctionLogId);

    if(thisAction == 'buy_now'){
        $('.buy-cost').text(thisItemBuy);
        $('.buy-item').html(thisItemName);
        $('#modal-buy-now').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false,
            overlay: {
                css: {opacity: .0}
            }
        });
    }else if(thisAction == 'place_bet'){
        $('.put-cost').text(thisItemCost+' EUROS');
        $('.put-item').html(thisItemName);
        $('#modal-pace-ber').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false,
            overlay: {
                css: {opacity: .0}
            }
        });
    }else if(thisAction == 'get_lot'){
        $('.get-item').html(thisItemName);
        $('#modal-get-lot').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false,
            overlay: {
                css: {opacity: .0}
            }
        });
    }else if(thisAction == 'get_bought'){
        $('.get-item').html(thisItemName);
        nowWinLotId = thisAuctionLogId;
        $('#win-item-auction').arcticmodal({
            closeOnEsc: false,
            closeOnOverlayClick: false,
            overlay: {
                css: {opacity: .0}
            }
        });
    }
}

function generateHtmlForBet(betNumber,betMA,betDate,betPrice,betStatus){
    return '<div class="modal-info-bet-box">' +
               '<div class="modal-info-bet-box-title">Bet â„–<span>'+betNumber+'</span></div>' +
               '<div class="modal-info-row" id="betinfoma-'+betNumber+'">' +
                   '<span>Bet MA</span>' +
                   '<span>'+betMA+'</span>' +
               '</div>' +
               '<div class="modal-info-row" id="lastbetdata-'+betNumber+'">' +
                   '<span>Bet date</span>' +
                   '<span>'+betDate+'</span>' +
               '</div>' +
               '<div class="modal-info-row" id="betprice-'+betNumber+'">' +
                   '<span>Bet price</span>' +
                   '<span>'+betPrice+'</span>' +
               '</div>' +
               '<div class="modal-info-row" id="betinfo-'+betNumber+'">' +
                   '<span>Bet status</span>' +
                   '<span>'+betStatus+'</span>' +
               '</div>' +
           '</div>';
}