<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $this->title; ?></title>

    <link rel="icon" href="/templates/kertas//assets/img/favicon.ico">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- BEGIN CSS FRAMEWORK -->
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/font-awesome/css/font-awesome.min.css">
    <!-- END CSS FRAMEWORK -->

    <!-- BEGIN CSS PLUGIN -->
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/switchery/switchery.min.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/pace/pace-theme-minimal.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/jquery-gritter/css/jquery.gritter.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/bootstrap-summernote/summernote.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/bootstrap-summernote/summernote-bs3.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/jquery-magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/jquery-niftymodal/css/component.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/icheck/skins/square/blue.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/select2/select2.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/select2/select2-bootstrap.css">
    <link rel="stylesheet" href="/templates/kertas/assets/plugins/liCover/css/liCover.css">
    <!-- END CSS PLUGIN -->

    <!-- BEGIN CSS TEMPLATE -->
    <link rel="stylesheet" href="/templates/kertas/assets/material-design-iconic-font/css/material-design-iconic-font.css">
    <link rel="stylesheet" href="/templates/kertas/assets/css/main.css" media="all">
    <link rel="stylesheet" href="/templates/kertas/assets/css/skins.css">
    <!-- END CSS TEMPLATE -->

    <!-- inventory -->
    <link rel="stylesheet" href="/templates/kertas/assets/css/inventory.css">
    <!-- end-->
    <link rel="stylesheet" href="/templates/kertas/assets/js/modal/jquery.arcticmodal-0.3.css">

    <!-- BEGIN JS FRAMEWORK -->
    <script src="/templates/kertas/assets/plugins/jquery-2.1.0.min.js"></script>
    <script src="/templates/kertas/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/templates/kertas/assets/js/bootstrap-growl.js"></script>
    <script src="/templates/kertas/assets/js/jquery.cookie.js"></script>
    <!-- END JS FRAMEWORK -->

    <!-- external lib -->
    <script type="text/javascript">
        function notify(type,title,msg){
            $.growl({
                title: title,
                message: msg,
                url: ''
            },{
                element: 'body',
                type: type,
                allow_dismiss: true,
                offset: {
                    x: 20,
                    y: 85
                },
                spacing: 10,
                z_index: 1031,
                delay: 5000,
                timer: 1000,
                url_target: '_blank',
                mouse_over: false,
                icon_type: 'class',
                template: '<div data-growl="container" class="alert" role="alert">' +
                '<button type="button" class="close" data-growl="dismiss">' +
                '<span aria-hidden="true">&times;</span>' +
                '<span class="sr-only">Close</span>' +
                '</button>' +
                '<span data-growl="icon"></span>' +
                '<span data-growl="title"></span>' +
                '<span data-growl="message"></span>' +
                '<a href="#" data-growl="url"></a>' +
                '</div>'
            });
        };
    </script>

    <script>
        var coinsFromEuro = <?php echo isset($this->coin_ratio) ? $this->coin_ratio : COINS_PER_DOLLAR; ?>;
    </script>
</head>

<body class="<?php echo $this->body_class; ?>">

<div id="loader"></div>

<?php if ($this->show_nav): ?>
<!-- BEGIN HEADER -->
<header class="header">
    <!-- BEGIN LOGO -->
    <a href="/account/index/" class="logo" >
    <img class="profile-img" src="/templates/kertas/assets/img/photo2.png" alt="" style="margin-top: 5px;"> </a>
    <!-- END LOGO -->
    <!-- BEGIN NAVBAR -->
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-right">
            <ul class="nav navbar-nav">
            	<li class="navbar-menu">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default addCash-btn" data-toggle="modal" data-target="#modalDonate">  <i class="fa fa-bank fa-lg"></i> <?php echo _s("ADD_CURRENCY_EURO"); ?></button>
                    </div>
                </li>
				<li class="navbar-menu">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default addCash-btn" data-toggle="modal" data-target="#modalCoinTransfer">  <i class="fa fa-mail-forward"></i> <?php echo _s("DONATE_COIN"); ?></button>
                    </div>
                </li>
                <li class="navbar-menu">
                    <a href="/account/logout">
                        <i class="fa fa-power-off fa-lg"></i> <?php echo _s("EXIT"); ?>                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END NAVBAR -->
</header>
<!-- END HEADER -->
<?php endif; ?>
