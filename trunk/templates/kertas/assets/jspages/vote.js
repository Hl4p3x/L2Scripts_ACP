$(function(){
    $('#btn_select_char').on("click", function(){
        window.location.href = "/vote/index/" + $('#vote_character').val();
    });

    $('[data-last-vote]').each(function(){
        if ($(this).data('last-vote') == 0) {
            $(this).html("Never");
        } else {
            var last_vote = $(this).data('last-vote');
            var now = Math.round(new Date().getTime()/1000);

            var diff = now - last_vote;
            var delay = $(this).data('vote-delay') * 3600;

            if (diff < 60) {
                $(this).html(diff + " Seconds ago");
            } else if (diff < 3600) {
                $(this).html(Math.floor(diff/60) + " Minutes ago");
            } else {
                $(this).html(Math.floor(diff/3600) + " Hours ago");
            }

            if (diff < delay) {
                $(this).parent().find('a').hide();
                if ($(this).data('vote-rewarded') == 1) {
                    $(this).parent().find('button').removeClass('btn-primary').addClass('btn-success').html("Voted!");
                } else {
                    $(this).parent().find('button').html("Get Reward");
                }
            }
        }
    });

    $('.btn-checkvote').on("click", function(event){
        event.preventDefault();

        if ($(this).hasClass("btn-success"))
            return false;

        var th_elem = $(this).parent().parent();
        var site_id = th_elem.data('site-id');
        var character = th_elem.data('character');

        $.ajax('/vote/checkVote/' + site_id + '/' + character).done(function(data){
            var response = $.parseJSON(data);
            if (response.result == true) {
                th_elem.find('[data-last-vote]').html("Just Now");
                th_elem.find('a').hide();
                if (response.rewarded == true) {
                   th_elem.find('button').removeClass('btn-primary').addClass('btn-success').html("Voted!");
                   liliumNotify("Vote successful and reward has been delivered.",'success');
                } else {
                    th_elem.find('button').html("Get Reward");
                    liliumNotify("Vote successful, click Get Reward!",'success');
                }
            } else {
                liliumNotify(response.message,'danger');
            }
        });
    });
});