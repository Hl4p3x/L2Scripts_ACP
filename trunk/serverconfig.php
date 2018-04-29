<?php

$_CONFIG['servers'] = array(
    array(
		//Server name
		//Название сервера
        'name' => 'Nation Warriors Classic',

        // login server mysql information
		//Конфигурация мускула для логин сервера
        'ls_mysql_host' => '46.105.30.162',
        'ls_mysql_user' => 'infoalqowirjfhvmxh6384%#*03716',
        'ls_mysql_pass' => '?5k}NE56wSUB?!m',
        'ls_mysql_db'   => 'kdjhhyrudjkJJjdhyes',

        // game server mysql information
		//Конфигурация мускула для гейм сервера
        'gs_mysql_host' => '46.105.30.162',
        'gs_mysql_user' => 'infoalqowirjfhvmxh6384%#*03716',
        'gs_mysql_pass' => '?5k}NE56wSUB?!m',
        'gs_mysql_db'   => 'kdjhhyrudjkJJjdhyes',
		
		//Maximum game accounts per Master accounts
		//Максимум игровых акаунтов для одного мастер акаунта
        'max_accounts'  => 4,
		//Donation coin ID (if transferred from ACP to char)
		//ИД донат монеы (игрок получит эту монету при запросе)
        'coin_id'       => 4037,
		//ticket prefix name
		//Префикс для тех. поддержки
        'ticket_prefix' => 'TKT-NW-',
    ),
);
