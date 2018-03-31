<?php
define('ctx', 1);

// config
include 'config.php';
include 'serverconfig.php';

// utils
include 'libs/utils.php';

// autoloader
include 'autoloader.php';

// init session
Session::init();

// strings
$lang = LANGUAGE;
if (Session::get("lang") != "" && (Session::get("lang") == "en" || Session::get("lang") == "ru"))
    $lang = Session::get("lang");

include 'strings_'.$lang.'.php';

// correct cloudflare forwarding if needed
if (array_key_exists('HTTP_CF_CONNECTING_IP', $_SERVER))
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

// run application
$app = new Application();
