<?php

$_CONFIG['servers'] = array(
    array(
		//Server name
		//Название сервера
        'name' => 'Eternalwars',

        // login server mysql information
		//Конфигурация мускула для логин сервера
        'ls_mysql_host' => 'localhost',
        'ls_mysql_user' => 'root',
        'ls_mysql_pass' => 'potokerios1',
        'ls_mysql_db'   => 'eternalwars',

        // game server mysql information
		//Конфигурация мускула для гейм сервера
        'gs_mysql_host' => 'localhost',
        'gs_mysql_user' => 'root',
        'gs_mysql_pass' => 'potokerios1',
        'gs_mysql_db'   => 'eternalwars',
		
		//Maximum game accounts per Master accounts
		//Максимум игровых акаунтов для одного мастер акаунта
        'max_accounts'  => 4,
		//Donation coin ID (if transferred from ACP to char)
		//ИД донат монеы (игрок получит эту монету при запросе)
        'coin_id'       => 4037,
		//ticket prefix name
		//Префикс для тех. поддержки
        'ticket_prefix' => 'TKI1-',
    ),
);
