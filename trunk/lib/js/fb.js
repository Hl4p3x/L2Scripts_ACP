window.fbAsyncInit = function() {
    FB.init({
        appId      : '395873580607312',
        xfbml      : true,
        version    : 'v2.3'
    });
};

function shareFB(link,caption,picture,name,desc) {
    FB.ui({
            method: 'feed',
            link: link,
            name: name,
            description: desc,
            caption: caption,
            picture: picture
        },
        function (response) {
            if (response && response.post_id) {
                $.post('/ajax/repost',{hash: hashToRepost }, function(data){
                    console.log(data);
                });
            }
        }
    );
}

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
